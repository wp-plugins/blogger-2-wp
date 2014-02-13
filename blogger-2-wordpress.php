<?php
/**
 * Plugin Name: Blogger 2 Wordpress
 * Plugin URI: http://subinsb.com/move-blog-from-blogger-to-wordpress
 * Description: Move your Blogger Blog to Wordpress with Posts Redirection. Example : http://myblog.blogspot.com/2014/02/post-1 to http://wordpressblog.com/post-1 or http://wordpressblog.com/2014/02/post-1. Very easy to configure.
 * Version: 0.1
 * Author: Subin Siby
 * Author URI: http://subinsb.com
 * License: GPL2
*/
function b2w_admin_menu() {
 add_submenu_page('plugins.php', __('Blogger 2 Wordpress'), __('Blogger 2 Wordpress'), 'manage_options', 'b2w_admin', 'bToW_options_page');
 
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
  <div id="message" class="error"><p>Please keep this plugin <strong>activated</strong> for redirection to work.</p></div>
  <div id="content_block" class="align_left">
   <h2>Instructions</h2>
   <div id="message" class="error"><p>You should read <a href="http://subinsb.com/move-blog-from-blogger-to-wordpress">this Blog post</a> to see how to migrate your blogger blog to wordpress using this plugin.</p></div>
   <h2>Setup</h2>
   <h3>Get Template Code</h3>
   If you have already done this, you don't have to do it again.
   Type in your Blogger Blog's address :
   <form method="post" action="plugins.php?page=b2w_admin">
    <blockquote>
     <input name="blogger" type="text" size="35" placeholder="http://yourblog.blogspot.com"/>
    </blockquote>
    <input type="submit" value="Start Configuration"/>
   </form>
   <form method="post" action="plugins.php?page=b2w_admin">
    <h3>Pages Redirection</h3>
    If your Blogger blog have pages that you want to redirect, add them (Example Below) :<br/>
    <textarea cols="60" rows="10" name="pages"><?echo get_option("b2wps048");?></textarea><br/>
    <input type="submit" value="Add pages redirection"/>
   </form>
   <h4>Example</h4>
   <textarea cols="60" rows="10" disabled="disabled">{
"<?echo$wpBlogURL;?>/page" : "blog.blogspot.com/p/page.html", 
"<?echo$wpBlogURL;?>/page2" : "blog.blogspot.com/p/page2.html"
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
    <li>Find the <b>Revert to classic template</b> link and click it.</li>
    <li>Refresh Page</li>
    <li>Paste the following code in the <b>Edit Template HTML</b> textarea</li>
   </ol>
   <textarea cols="90" rows="15"><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="<$BlogLanguageDirection$>"><head><meta name="google-site-verification" content="W6NTV9M49kD1C3pqZoRLShXFLZqhHEp9iuViXnb81zo" /><title><$BlogPageTitle$> </title> <script type="text/javascript"> <MainorArchivePage>window.location.href="<?echo$wpBlogURL;?>/"</MainOrArchivePage> <Blogger><ItemPage>window.location.href="<?echo$wpBlogURL;?>/?b2w=<$BlogItemPermalinkURL$>"</ItemPage></Blogger></script><MainPage><link rel="canonical" href="<?echo$wpBlogURL;?>/" /></MainPage>  <Blogger><ItemPage><link rel="canonical" href="<?echo$wpBlogURL;?>/?b2w=<$BlogItemPermalinkURL$>" /></ItemPage></Blogger></head><body> <div style="border: #ccc 1px solid; background: #eee; padding: 20px; margin: 80px;"><p>This page has moved to a new address.</p><h1><MainOrArchivePage><a href="<?echo$wpBlogURL;?>/"><$BlogTitle$></a></MainOrArchivePage><Blogger><ItemPage><a href="<?echo$wpBlogURL;?>/?b2w=<$BlogItemPermalinkURL$>"><$BlogItemTitle$></a></ItemPage></Blogger></h1></div></body></html></textarea>
  </p>
<?
 }
}
function StartB2WRedirection() {
 global $_SERVER;
 $b2w = (isset($_GET['b2w'])) ? $_GET['b2w']:false;
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
