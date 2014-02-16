<?php
/**
 * Plugin Name: Blogger 2 WordPress
 * Plugin URI: http://subinsb.com/move-blog-from-blogger-to-wordpress
 * Description: Move your Blogger Blog to Wordpress with Posts Redirection. Example : myblog.blogspot.com/2014/02/post-1 to wordpressblog.com/post-1. You can also redirect Blogger Pages to your New Wordpress Pages. Easily move from old abc.blogspot.com to new domain abc.com
 * Version: 0.2
 * Author: Subin Siby
 * Author URI: http://subinsb.com
 * License: GPLv3
*/
function b2w_admin_menu() {
 add_submenu_page('plugins.php', __('Blogger 2 WordPress'), __('Blogger 2 WordPress'), 'manage_options', 'b2w_admin', 'bToW_options_page');
 
 if (isset($_GET['page']) && $_GET['page'] == 'blogger-2-wordpress-admin') {
  /*wp_enqueue_script('dashboard');
  wp_enqueue_style('dashboard');*/
 }
}
add_action('admin_menu', 'b2w_admin_menu');
function bloggerTowordpressAdminPage(){
 echo'<div class="wrap">';
 if(isset($_POST['pages'])){
  $pages=str_replace('\"', '"', $_POST['pages']);
  if($pages!=""){
   if(is_object(json_decode($pages))){
    update_option("b2wps048", $pages);
    echo '<div id="message" class="updated"><p>Added Pages For Redirection</p></div>';
   }else{
    echo '<div id="message" class="error"><p>Not A Valid Data</p></div>';
   }
  }
 }
  $wpBlogURL=get_site_url();
?>
  <div id="content_block" class="align_left">
   <h2>Instructions</h2>
   <div id="message" class="error"><p>Please keep this plugin <strong>activated</strong> for redirection to work.</p></div>
   <div id="message" class="updated"><p><a target="_blank" href="http://sag-3.blogspot.com/2013/04/spam-urls-in-blogger-traffic-source.html">See A Demo</a></p></div>
   <div id="message" class="update-nag">You should read <a target="_blank" href="http://subinsb.com/move-blog-from-blogger-to-wordpress">this Blog post</a> to see how to migrate your blogger blog to wordpress using this plugin.</div>
   <h2>Setup</h2>
   <h3>Get Template Code</h3>
   <p>If you have already done this, you don't have to do it again <b>unless you upgraded this plugin</b>.</p>
   <p>You need to replace your Blogger Template to redirect your visitors.</p>
   <form method="post" action="plugins.php?page=b2w_admin">
    <blockquote>
     <input name="blogger" type="submit" size="45" value="Generate Template Code" />
    </blockquote>
   </form>
   <form method="post" action="plugins.php?page=b2w_admin">
    <h3>Pages Redirection</h3>
    If your Blogger blog have pages that you want to redirect, add them as JSON array (Example Below) :<br/>
    <textarea cols="60" rows="10" name="pages"><?echo get_option("b2wps048");?></textarea><br/>
    <p>DO NOT USE FULL URL. Since Blogger Blogs have multiple domains (com, in, au), <br/>URL of Pages will be different. So only add the URL Path Name.</p>
    <input type="submit" value="Add pages redirection"/>
   </form>
   <h4>Example</h4>
   <textarea cols="60" rows="10" disabled="disabled">{
"<?echo$wpBlogURL;?>/page" : "/p/page.html", 
"<?echo$wpBlogURL;?>/page2" : "/p/page2.html",
"<?echo$wpBlogURL;?>/contact" : "/p/contact.html"
}</textarea>
  </div>
 </div>
<?
}
function bToW_options_page(){
 if(!isset($_POST['blogger'])){
  bloggerTowordpressAdminPage();
 }else{
  $wpBlogURL=get_site_url();
?>
  <h2>Configure Blogger Blog</h2>
  The Posts of <b><?echo$_POST['blogger'];?></b> Will Be Moved to the Posts @ <b><?echo$wpBlogURL;?></b>
  <p>
   <ol>
    <li>Go to you Blogger Blog <b>Template</b> page.</li>
    <li>Apply the <b>Simple Template</b> To Your Blog</li>
    <li>Click On <b>Edit HTML</b> button.</li>
    <li>Paste the following code in the textarea</li>
   </ol>
   <textarea cols="90" rows="15"><?echo'<?xml version="1.0" encoding="UTF-8" ?>';?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html>&amp;lt;!--<head>&amp;lt;style &amp;gt;&amp;lt;!--/*<b:skin><![CDATA[*/]]></b:skin>&amp;lt;!--</head>--&amp;gt;<body><script>var GtParam=window.location.pathname=="/" ? "":"/?b2wURL="+window.location.pathname;window.location="<?echo$wpBlogURL;?>/"+GtParam;</script><p>This page has been moved to a new address. Redirecting....</p>&amp;lt;!-- /*<b:section id='main'></b:section>--&amp;gt;&amp;lt;!--/*</body>--&amp;gt;</html></textarea>
  </p>
<?
 }
}
function StartB2WRedirection() {
 global $_SERVER;
 $b2w = (isset($_GET['b2wURL'])) ? $_GET['b2wURL']:false;
 if(!$b2w && is_404()){
  $b2w=$_SERVER['REQUEST_URI'];
 }
 if($b2w){
  global $wpdb;
  $sql = "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} where meta_key = 'blogger_blog'";
  $results = $wpdb->get_results($sql);
  foreach ($results as $result){
   $result->meta_value = substr($result->meta_value, 0, strrpos($result->meta_value,'.'));
   if (strstr($b2w, $result->meta_value) !== false) {
    $b2w_temp = explode($result->meta_value, $b2w);
    $b2w = substr($b2w_temp[1], strpos($b2w_temp[1], '/'));
    if(strpos($b2w,'?') > 0){
     $b2w = strstr($b2w,'?',true);
    }
   }
   $pgs=json_decode(get_option("b2wps048"), true);
   if(is_array($pgs)){
    $gtun=array_search($b2w,$pgs);
   }
   $sqlstr = $wpdb->prepare("SELECT wposts.ID, wposts.guid
    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
    WHERE wposts.ID = wpostmeta.post_id
    AND wpostmeta.meta_key = 'blogger_permalink'
    AND wpostmeta.meta_value LIKE %s", "%$b2w%"
   );
   $wpurl = $wpdb->get_results($sqlstr, ARRAY_N);
   if($wpurl){
    $prvt=str_replace("www.","",get_permalink($wpurl[0][0]));
    header( 'Location: '.$prvt.' ',true,301) ;
    exit;
   }elseif($gtun!=false){
    header('Location: '.$gtun.' ',true,301);
    exit;
   }
  }
 }
}
add_action('init','StartB2WRedirection');
?>
