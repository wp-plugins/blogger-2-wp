<?php
/**
 * Plugin Name: Blogger 2 WordPress
 * Plugin URI: http://subinsb.com/move-blog-from-blogger-to-wordpress
 * Description: Move your Blogger Blog to Wordpress with Posts Redirection. Example : myblog.blogspot.com/2014/02/post-1 to wordpressblog.com/post-1. You can also redirect Blogger Pages to your New Wordpress Pages. Easily move from old abc.blogspot.com to new domain abc.com
 * Version: 0.4.5
 * Author: Subin Siby
 * Author URI: http://subinsb.com
 * License: GPLv3
*/
function b2w_admin_menu() {
 add_submenu_page('plugins.php', __('Blogger 2 WordPress'), __('Blogger 2 WordPress'), 'manage_options', 'b2w_admin', 'bToW_options_page');
 /*if (isset($_GET['page']) && $_GET['page'] == 'blogger-2-wordpress-admin') {
  wp_enqueue_script('dashboard');
  wp_enqueue_style('dashboard');
 }*/
}
add_action('admin_menu', 'b2w_admin_menu');
function bloggerTowordpressAdminPage(){
 if(isset($_POST['pfrom']) && isset($_POST['pto'])){
  $pgsj=array();
  foreach($_POST['pfrom'] as $k=>$fromVal){
   $toVal=$_POST['pto'][$k];
   if($toVal!="" && $fromVal!=""){
    $pgsj[$toVal]=$fromVal;
   }
  }
  $pages=json_encode($pgsj);
  update_option("b2wps048", $pages);
  echo'<div class="wrap"><div id="message" style="height: 50px;" class="updated"><p style="font-size: 19px;">Added Pages For Redirection</p></div></div>';
 }
 $wpBlogURL=get_site_url();
?>
 <div id="poststuff">
  <div id="post-body" class="metabox-holder columns-2">
   <div id="postbox-container-1" class="postbox-container">
    <div class="postbox">
     <div class="inside">
      <h2>Donate</h2>
      <p>I'm 14 and I need donations to create more plugins.</p>
      <p>Please consider a donation for the improvement of this plugin and for future awesome plugins.</p>
       <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="ZYQWUZ2B8ZXXA">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
       </form>
      </div>
     </div>
    </div>
    <div id="postbox-container-2" class="postbox-container">
     <div style="font-size:16px;line-height:2em;" class="postbox">
      <div class="inside">
       <h3 class="hndle"><span>Keep In Mind</span></h3>
       <div id="message" class="update-nag">
        Please keep this plugin <strong>activated</strong> for redirection to work.
       </div>
       <div id="message" class="update-nag">
        <a target="_blank" href="http://sag-3.blogspot.com/2013/04/spam-urls-in-blogger-traffic-source.html">See A Demo</a>
       </div>
       <div id="message" class="update-nag">You should read <a target="_blank" href="http://subinsb.com/move-blog-from-blogger-to-wordpress">this Blog post</a> to see how to migrate your blogger blog to wordpress using this plugin.</div>
      </div>
     </div>
     <div style="font-size:16px;line-height:2em;" class="postbox">
      <div class="inside">
       <h3 class="hndle"><span>Get Template Code</span></h3>
       <p>If you have already done this, you don't have to do it again <b>unless you upgraded this plugin</b>.</p>
       <p>You need to replace your Blogger Template to redirect your visitors.</p>
       <form method="post" action="plugins.php?page=b2w_admin">
        <blockquote>
         <input name="blogger" type="submit" size="45" value="Generate Template Code" />
        </blockquote>
       </form>
      </div>
     </div>
     <div style="font-size:16px;margin-top:15px;line-height:2em;" class="postbox">
      <div class="inside b2wOpt">
       <h3 class="hndle"><span>Pages Redirection</span></h3><br/>
       <form method="post" action="plugins.php?page=b2w_admin">
        <p>If your Blogger blog have pages that you want to redirect, add them (Example below this) :</p>
        <div style="margin:10px;">
         <?
         $pages=get_option("b2wps048");
         $pages=json_decode($pages, true);
         if(is_array($pages) && count($pages)!=0){
          foreach($pages as $k=>$v){
           echo "<div class='b2wOptPR'>";
            echo "<input type='text' name='pfrom[]' placeholder='From' value='$v'/>";
            echo "<input type='text' name='pto[]' placeholder='To' value='$k'/>";
            echo "<a href='javascript:void(0);' onclick='jQuery(this).parent().remove();'>Remove</a>";
           echo "</div>";
          }
         }else{
          echo "<div class='b2wOptPR'>";
           echo "<input type='text' name='pfrom[]' placeholder='From' value=''/>";
           echo "<input type='text' name='pto[]' placeholder='To' value=''/>";
          echo "</div>";
         }
         ?>
         <a href="javascript:void(0);" style="margin:5px;display:block;" onclick="jQuery('.b2wOptPR:last').after('<div id=\'b2wOptPR\'><input type=\'text\' name=\'pfrom[]\' placeholder=\'From\' value=\'\'/><input type=\'text\' name=\'pto[]\' placeholder=\'To\' value=\'\'/><a href=\'javascript:void(0);\' onclick=\'jQuery(this).parent().remove();\'>Remove</a></div>');">Add New Field</a>
        </div>
        <p>DO NOT USE FULL URL. Since Blogger Blogs have multiple domains (com, in, au), URL of Pages will be different. So only add the URL Path.</p>
        <input type="submit" value="Update Pages Redirection"/>
       </form>
       <h4>Example</h4>
       <?
       $pages=array(
        "page" => "/p/page.html",
        "page2" => "/p/page2.html",
        "contact" => "/p/contact.html"
       );
       foreach($pages as $k=>$v){
        echo "<div>";
         echo "<input type='text' disabled='disabled' placeholder='From' value='$v'/>";
         $toURL=substr($wpBlogURL, 0, -1)=="/" ? $k:"/$k";
         echo "<input type='text' disabled='disabled' placeholder='To' value='$wpBlogURL$toURL'/>";
        echo "</div>";
       }
       ?>
       <style>
       .b2wOpt input[type=text]{min-width:300px;}
       </style>
      </div>
     </div>
    </div>
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
 <div id="poststuff">
  <div id="post-body" class="metabox-holder columns-2">
   <div id="postbox-container-1" class="postbox-container">
    <div class="postbox">
     <div class="inside">
      <h2>Donate</h2>
      <p>I'm 14 and I need donations to create more plugins.</p>
      <p>Please consider a donation for the improvement of this plugin and for future awesome plugins. (Default <strong>5$</strong>)</p>
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
       <input type="hidden" name="cmd" value="_s-xclick">
       <input type="hidden" name="hosted_button_id" value="ZYQWUZ2B8ZXXA">
       <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
       <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
      </form>
     </div>
    </div>
   </div>
   <div id="postbox-container-2" class="postbox-container">
    <div style="font-size:16px;line-height:2em;" class="postbox">
     <div class="inside">
      <h3 class="hndle"><span>Configure Blogger Blog</span></h3>
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
     </div>
    </div>
   </div>
  </div>
 </div>
<?
 }
}
function StartB2WRedirection() {
 global $_SERVER;
 global $wp_query;
 global $wp;
 global $wpdb;
 $b2w = (isset($_GET['b2wURL'])) ? $_GET['b2wURL']:false;
 $current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
 $current_url = parse_url($current_url);
 $current_url = $current_url['host'].$current_url['path'];
 $siteURL=get_home_url();
 $siteURLParts=parse_url($siteURL);
 $siteURL=$siteURLParts['host'].$siteURLParts['path'];
 if(!$b2w && $wp_query->is_404===true){
  $b2w=str_replace($siteURL, "", $current_url);
 }
 if($b2w){
  $pgs=get_option("b2wps048");
  $pgs=json_decode($pgs, true);
  if(is_array($pgs)){
   $gtun=array_search($b2w, $pgs);
  }
  $b2w=str_replace(".html", "", $b2w);
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
   $sqlstr = $wpdb->prepare("SELECT wposts.ID, wposts.guid
    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
    WHERE wposts.ID = wpostmeta.post_id
    AND wpostmeta.meta_key = 'blogger_permalink'
    AND wpostmeta.meta_value LIKE %s", "%$b2w%"
   );
   $wpurl = $wpdb->get_results($sqlstr, ARRAY_N);
  }
  if(isset($wpurl) && $wpurl){
   $prvt=get_permalink($wpurl[0][0]);
   header( 'Location: '.$prvt.' ',true,301) ;
   exit;
  }elseif($gtun!=false){
   header('Location: '.$gtun.' ',true,301);
   exit;
  }
 }
}
add_action('template_redirect','StartB2WRedirection');
?>
