<?php
/**
 * Plugin Name: Blogger 2 WordPress
 * Text Domain: blogger-2-wp
 * Plugin URI: http://subinsb.com/move-blog-from-blogger-to-wordpress
 * Description: Move your Blogger Blog to Wordpress with Posts Redirection. Example : myblog.blogspot.com/2014/02/post-1 to wordpressblog.com/post-1. You can also redirect Blogger Pages to your New Wordpress Pages. Easily move from old abc.blogspot.com to new domain abc.com
 * Version: 0.4.5
 * Author: Subin Siby
 * Author URI: http://subinsb.com
 * License: GPLv3
*/

/* Translations */
function blogger_to_wp_init() {
	load_plugin_textdomain( 'blogger-2-wp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'blogger_to_wp_init' );

function b2w_admin_menu() {
 add_submenu_page('plugins.php', __('Blogger 2 WordPress'), __('Blogger 2 WordPress'), 'manage_options', 'b2w_admin', 'bToW_options_page');
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
  $text = __("Added Pages For Redirection", "blogger-2-wp");
  echo '<div class="wrap"><div id="message" style="height: 50px;" class="updated"><p style="font-size: 19px;">'.$text.'</p></div></div>';
 }
 $wpBlogURL=get_site_url();
?>
 <div id="poststuff">
  <div id="post-body" class="metabox-holder columns-2">
   <div id="postbox-container-1" class="postbox-container">
    <div class="postbox">
     <div class="inside">
      <h2><?php _e("Donate", "blogger-2-wp");?></h2>
      <p><?php _e("I'm 14 and I need donations to create more plugins.", "blogger-2-wp");?></p>
      <p><?php _e("Please consider a donation for the improvement of this plugin and for future awesome plugins.", "blogger-2-wp");?></p>
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
       <h3 class="hndle"><span><?_e("Keep In Mind", "blogger-2-wp");?></span></h3>
       <div id="message" class="update-nag">
        <?_e("Please keep this plugin", "blogger-2-wp");?> <strong><?_e("activated", "blogger-2-wp");?></strong> <?php _e("for redirection to work.", "blogger-2-wp");?>
       </div>
       <div id="message" class="update-nag">
        <a target="_blank" href="http://sag-3.blogspot.com/2013/04/spam-urls-in-blogger-traffic-source.html"><?_e("See A Demo", "blogger-2-wp");?></a>
       </div>
       <div id="message" class="update-nag"><?php _e("You should read", "blogger-2-wp");?> <a target="_blank" href="http://subinsb.com/move-blog-from-blogger-to-wordpress"><?php _e("this Blog post", "blogger-2-wp");?></a> <?php _e("to see how to migrate your blogger blog to wordpress using this plugin.", "blogger-2-wp");?></div>
      </div>
     </div>
     <div style="font-size:16px;line-height:2em;" class="postbox">
      <div class="inside">
       <h3 class="hndle"><span><? _e("Get Template Code", "blogger-2-wp");?></span></h3>
       <p><?_e("If you have already done this, you don't have to do it again", "blogger-2-wp");?>&nbsp;<b><?_e("unless you upgraded this plugin", "blogger-2-wp");?></b>.</p>
       <p><?_e("You need to replace your Blogger Template to redirect your visitors.", "blogger-2-wp");?></p>
       <form method="post" action="plugins.php?page=b2w_admin">
        <blockquote>
         <input name="blogger" type="submit" size="45" value="<?_e("Generate Template Code", "blogger-2-wp");?>" />
        </blockquote>
       </form>
      </div>
     </div>
     <div style="font-size:16px;margin-top:15px;line-height:2em;" class="postbox">
      <div class="inside b2wOpt">
       <h3 class="hndle"><span><?_e("Pages Redirection", "blogger-2-wp");?></span></h3><br/>
       <form method="post" action="plugins.php?page=b2w_admin">
        <p><?_e("If your Blogger blog have pages that you want to redirect, add them (Example below this) :", "blogger-2-wp");?></p>
        <div style="margin:10px;">
         <?
         $pages=get_option("b2wps048");
         $pages=json_decode($pages, true);
         $fromText = __("From", "blogger-2-wp");
         $toText   = __("To", "blogger-2-wp");
         if(is_array($pages) && count($pages)!=0){
          foreach($pages as $k=>$v){
           echo "<div class='b2wOptPR'>";
            echo "<input type='text' name='pfrom[]' placeholder='".$fromText."' value='$v'/>";
            echo "<input type='text' name='pto[]' placeholder='".$toText."' value='$k'/>";
            echo "<a href='javascript:void(0);' onclick='jQuery(this).parent().remove();'>Remove</a>";
           echo "</div>";
          }
         }else{
          echo "<div class='b2wOptPR'>";
           echo "<input type='text' name='pfrom[]' placeholder='".$fromText."' value=''/>";
           echo "<input type='text' name='pto[]' placeholder='".$toText."' value=''/>";
          echo "</div>";
         }
         ?>
         <a href="javascript:void(0);" style="margin:5px;display:block;" onclick="jQuery('.b2wOptPR:last').after('<div id=\'b2wOptPR\'><input type=\'text\' name=\'pfrom[]\' placeholder=\'From\' value=\'\'/><input type=\'text\' name=\'pto[]\' placeholder=\'To\' value=\'\'/><a href=\'javascript:void(0);\' onclick=\'jQuery(this).parent().remove();\'>Remove</a></div>');"><?_e("Add New Field", "blogger-2-wp");?></a>
        </div>
        <p><?_e("DO NOT USE FULL URL. Since Blogger Blogs have multiple domains (com, in, au), URL of Pages will be different. So only add the URL Path.", "blogger-2-wp");?></p>
        <input type="submit" value="<?_e("Update Pages Redirection", "blogger-2-wp");?>"/>
       </form>
       <h4><?_e("Example", "blogger-2-wp");?></h4>
       <?
       $pages=array(
        "page" => "/p/page.html",
        "page2" => "/p/page2.html",
        "contact" => "/p/contact.html"
       );
       foreach($pages as $k=>$v){
        echo "<div>";
         echo "<input type='text' disabled='disabled' placeholder='".$fromText."' value='$v'/>";
         $toURL=substr($wpBlogURL, 0, -1)=="/" ? $k:"/$k";
         echo "<input type='text' disabled='disabled' placeholder='".$toText."' value='$wpBlogURL$toURL'/>";
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
      <h2><?php _e("Donate", "blogger-2-wp");?></h2>
      <p><?php _e("I'm 14 and I need donations to create more plugins.", "blogger-2-wp");?></p>
      <p><?php _e("Please consider a donation for the improvement of this plugin and for future awesome plugins.", "blogger-2-wp");?></p>
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
      <h3 class="hndle"><span><?_e("Configure Blogger Blog", "blogger-2-wp");?></span></h3>
      <?_e("The Posts of", "blogger-2-wp");?> <b>your blogger blog</b> <?_e("Will Be Moved to the Posts @ ", "blogger-2-wp");?><b><?echo$wpBlogURL;?></b>
      <p>
       <ol>
        <li><?_e("Go to you Blogger Blog", "blogger-2-wp");?> <b>Template</b> <?_e("page", "blogger-2-wp");?>.</li>
        <li><?_e("Apply the", "blogger-2-wp");?> <b>Simple Template</b> <?_e("To Your Blog", "blogger-2-wp");?></li>
        <li><?_e("Click On", "blogger-2-wp");?> <b>Edit HTML</b> <?_e("button", "blogger-2-wp");?>.</li>
        <li><?_e("Paste the following code in the textarea", "blogger-2-wp");?></li>
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