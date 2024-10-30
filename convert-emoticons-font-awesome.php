<?php
/*
 * @wordpress-plugin
 * Plugin Name:       Convert Emoticons Font Awesome
 * Plugin URI:        https://www.gallagherwebsitedesign.com/plugin/convert-emoticons-font-awesome/
 * Description:       Converts emoticons to Font Awesome icons.
 * Version:           1.0
 * Author:            Gallagher Website Design
 * Author URI:        https://www.gallagherwebsitedesign.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

########################
# Register Form Fields #
########################
function register_font_awesome_emoticons()
{
 register_setting('font-awesome-emoticons-settings','fa_emoticons_enable');
 register_setting('font-awesome-emoticons-settings','fa_emoticons_style');
 register_setting('font-awesome-emoticons-settings','fa_emoticons_kit_url');
}

###################
# Admin Menu Page #
###################
function font_awesome_emoticons_menu_page()
{
 add_submenu_page('options-general.php','Convert Emoticons Font Awesome','Convert Emoticons Font Awesome','manage_options','convert-emoticons-font-awesome.php','add_admin_font_awesome_emoticons');
 add_action('admin_init','register_font_awesome_emoticons');
}
add_action('admin_menu','font_awesome_emoticons_menu_page');

##################
# Add Admin Page #
##################
function add_admin_font_awesome_emoticons()
{
 $fa_styles = array('fas'=>'Solid','far'=>'Regular','fal'=>'Light (Pro only)','fad'=>'Duotone (Pro only)');
 // Process Form
 if(@$_POST['action']=='save_fa_emoticons')
 {
  // Check Nonce
  if(!isset($_POST['fa_emoticons_convert_field']) || !wp_verify_nonce($_POST['fa_emoticons_convert_field'],'fa_emoticons_convert_action')) { echo '<div class="notice notice-error is-dismissible"><p>Sorry, your nonce did not verify. Please try again.</p></div>'; }
  else
  {
   // Sanitize Input
   $fa_emoticons_enable = (@$_POST['fa_emoticons_enable']=='Y') ? 'Y' : '';
   $fa_emoticons_style = (isset($fa_styles[($_POST['fa_emoticons_style'])])) ? $_POST['fa_emoticons_style'] : '';
   preg_match("/https(.*)\.js/",$_POST['fa_emoticons_kit_url'],$kit_url);
   $kit_url = esc_url_raw($kit_url[0]);
   // Check Blanks
   if($fa_emoticons_enable=='Y' && (!$kit_url || !$fa_emoticons_style))
   { echo '<div class="notice notice-error is-dismissible"><p>Error, one or more fields was left blank. Please fill in all fields and try again.</p></div>'; }
   // Save Options
   else
   {
    update_option('fa_emoticons_enable',$fa_emoticons_enable);
    update_option('fa_emoticons_style',$fa_emoticons_style);
    update_option('fa_emoticons_kit_url',$kit_url);
    echo '<div class="notice notice-success is-dismissible"><p>Settings have been saved!</p></div>';
   }
  }
 }
 // Display Page
 $fa_emoticons_enable = (@$_POST['action']=='save_fa_emoticons') ? $fa_emoticons_enable : ((get_option('fa_emoticons_enable')=='Y') ? 'Y' : '');
 $fa_emoticons_style = (@$_POST['action']=='save_fa_emoticons') ? $fa_emoticons_style : get_option('fa_emoticons_style');
 $fa_emoticons_style = (isset($fa_styles[$fa_emoticons_style])) ? $fa_emoticons_style : '';
 $kit_url = (@$_POST['action']=='save_fa_emoticons') ? $kit_url : esc_url_raw(get_option('fa_emoticons_kit_url'));
 echo '
 <div class="wrap">
 <h1>Convert Emoticons Font Awesome Settings</h1>
 <form method="post">
 <input type="hidden" name="action" value="save_fa_emoticons">
 <table class="form-table" role="presentation">
 <tbody>
 <tr>
  <td><div class="gwd_shadow_box"><fieldset><label for="fa_emoticons_enable"><input name="fa_emoticons_enable" type="checkbox" id="fa_emoticons_enable" value="Y"'.(($fa_emoticons_enable=='Y') ? ' CHECKED' : '').'> Convert emoticons like <code>:-)</code> and <code>:-P</code> to <strong>Font Awesome</strong> icons on display</label><div style="margin-left:25px;font-style:italic;">Note: Make sure to turn off "Convert emoticons..." in <a href="options-writing.php" target="_blank">Settings -&gt; Writing</a> (otherwise it will conflict with this plugin)</div></fieldset></div></td>
  </tr>
  </tr>
  <td><div class="gwd_shadow_box"><fieldset>Select icon style <select name="fa_emoticons_style" id="fa_emoticons_style">';
  foreach($fa_styles as $fa_style => $fa_style_txt)
  {
   echo "\n".'   <option value="'.$fa_style.'"'.(($fa_emoticons_style==$fa_style) ? ' SELECTED' : '').'>'.$fa_style_txt.'</option>';
  }
  echo '
  </select></fieldset></div></td>
 </tr>
 <tr>
  <td><div class="gwd_shadow_box"><fieldset>Enter your kit script URL from Font Awesome<div><input name="fa_emoticons_kit_url" type="input" id="fa_emoticons_kit_url" value="'.$kit_url.'" style="width:400px;"></div><div style="font-style:italic;"><span style="padding-right:5px;">Example:</span> https://kit.fontawesome.com/YOUR_ID_HERE.js</div><div style="margin-top:10px;color:#888888;">Not sure where to find your kit script?.. <a href="#" onclick="jQuery(\'#how_to_kit\').slideToggle();return false;">Click here</a>.</div></div><div id="how_to_kit" class="gwd_shadow_box"><h2>How To Set Up A Kit</h2>
  <ol>
   <li>Go to Font Awesome\'s start page, <a href="https://fontawesome.com/start" target="_blannk">click here</a>.</li>
   <li>Type in your email address into the form</li>
   <li>Click the "Send Kit Code" button</li>
   <li>Go to your email and open up the email from Font Awesome</li>
   <li>Follow the instructions in the email to finish creating your kit</li>
   <li>Once you have your kit script code, copy and paste ONLY the URL from the script into the form field above.<div class="kit_code_example">For example, if this is your kit script code:<br><code>&lt;script src="https://kit.fontawesome.com/YOUR_ID_HERE.js" crossorigin="anonymous"&gt;&lt;/script&gt;</code><br>Then only copy & paste the URL <code>https://kit.fontawesome.com/YOUR_ID_HERE.js</code></div></li>
  </ol></div></fieldset></td>
  </tr>
 </tbody>
 </table>
 '.wp_nonce_field('fa_emoticons_convert_action','fa_emoticons_convert_field').'
 '.get_submit_button().'
 </form>
 <style>
 .gwd_shadow_box{padding:10px 20px;background:#ffffff;-webkit-border-radius: 5px;border-radius: 5px;-webkit-box-shadow: 0 0 5px 1px #cccccc;box-shadow: 0 0 5px 1px #cccccc;}
 #how_to_kit{margin-top:10px;display:none;}
 #how_to_kit h2{margin-top:0px;padding-top:0px;}
 .kit_code_example{margin-top:10px;line-height:26px;}
 p.submit{padding-left:10px;position:relative;}
 </style>
 </div>';
}

############################
# Enqueue Font Awesome Kit #
############################
function enqueue_font_awesome_emoticons()
{
 $enable = ((get_option('fa_emoticons_enable')=='Y') ? 'Y' : '');
 $kit_url = esc_url_raw(get_option('fa_emoticons_kit_url'));
 if($enable=='Y' && $kit_url)
 {
  wp_enqueue_script( 'font_awesome_kit_emoticons',$kit_url,[],null );
 }
}
add_action('wp_enqueue_scripts','enqueue_font_awesome_emoticons');
  
################################
# Apply Font Awesome Emoticons #
################################
function apply_font_awesome_emoticons($content)
{
 $fa_styles = array('fas'=>'Solid','far'=>'Regular','fal'=>'Light (Pro only)','fad'=>'Duotone (Pro only)');
 $enable = ((get_option('fa_emoticons_enable')=='Y') ? 'Y' : '');
 $type = get_option('fa_emoticons_style');
 $type = (isset($fa_styles[$type])) ? $type : '';
 $kit_url = esc_url_raw(get_option('fa_emoticons_kit_url'));
 if($enable=='Y' && $kit_url)
 {
  $type = ($type) ? $type : 'far';
  $icons = array(
   ':)' => '<i class="'.$type.' fa-smile"></i>',
   ':-)' => '<i class="'.$type.' fa-smile"></i>',
   ':smile:' => '<i class="'.$type.' fa-smile"></i>',
   ';)' => '<i class="'.$type.' fa-smile-wink"></i>',
   ';-)' => '<i class="'.$type.' fa-smile-wink"></i>',
   ':wink:' => '<i class="'.$type.' fa-smile-wink"></i>',
   ':(' => '<i class="'.$type.' fa-frown"></i>',
   ':-(' => '<i class="'.$type.' fa-frown"></i>',
   ':sad:' => '<i class="'.$type.' fa-frown"></i>',
   ':O' => '<i class="'.$type.' fa-surprise"></i>',
   ':o' => '<i class="'.$type.' fa-surprise"></i>',
   ':0' => '<i class="'.$type.' fa-surprise"></i>',
   ':-O' => '<i class="'.$type.' fa-surprise"></i>',
   ':-o' => '<i class="'.$type.' fa-surprise"></i>',
   ':-0' => '<i class="'.$type.' fa-surprise"></i>',
   ':eek:' => '<i class="'.$type.' fa-surprise"></i>',
   '8O' => '<i class="'.$type.' fa-surprise"></i>',
   '8-O' => '<i class="'.$type.' fa-flushed"></i>',
   ':shock:' => '<i class="'.$type.' fa-flushed"></i>',
   ':?' => '<i class="'.$type.' fa-frown-open"></i>',
   ':-?' => '<i class="'.$type.' fa-frown-open"></i>',
   ':???:' => '<i class="'.$type.' fa-frown-open"></i>',
   ':D' => '<i class="'.$type.' fa-grin"></i>',
   ':-D' => '<i class="'.$type.' fa-grin"></i>',
   ':grin:' => '<i class="'.$type.' fa-grin"></i>',
   ':P' => '<i class="'.$type.' fa-grin-tongue"></i>',
   ':-P' => '<i class="'.$type.' fa-grin-tongue"></i>',
   ':razz:' => '<i class="'.$type.' fa-grin-tongue"></i>',
   '8)' => '<i class="'.$type.' fa-sunglasses"></i>',
   '8-)' => '<i class="'.$type.' fa-sunglasses"></i>',
   ':cool:' => '<i class="'.$type.' fa-sunglasses"></i>',
   ':x' => '<i class="'.$type.' fa-angry"></i>',
   ':-x' => '<i class="'.$type.' fa-angry"></i>',
   ':mad:' => '<i class="'.$type.' fa-angry"></i>',
   ':|' => '<i class="'.$type.' fa-meh"></i>',
   ':-|' => '<i class="'.$type.' fa-meh"></i>',
   ':neutral:' => '<i class="'.$type.' fa-meh"></i>',
   ':lol:' => '<i class="'.$type.' fa-laugh-squint"></i>',
   ':oops:' => '<i class="'.$type.' fa-flushed"></i>',
   ':cry:' => '<i class="'.$type.' fa-sad-tear"></i>',
   ':evil:' => '<i class="'.$type.' fa-grin-tongue-squint"></i>',
   ':twisted:' => '<i class="'.$type.' fa-grin-tongue-squint"></i>',
   ':roll:' => '<i class="'.$type.' fa-meh-rolling-eyes"></i>',
   ':!:' => '<i class="'.$type.' fa-exclamation"></i>',
   ':?:' => '<i class="'.$type.' fa-question"></i>',
   ':idea:' => '<i class="'.$type.' fa-lightbulb"></i>',
   ':arrow:' => '<i class="'.$type.' fa-arrow-square-right"></i>',
   ':mrgreen:' => '<i class="'.$type.' fa-laugh"></i>',
   ':thumbs:' => '<i class="'.$type.' fa-thumbs-up"></i>',
   ':thumbsup:' => '<i class="'.$type.' fa-thumbs-up"></i>',
   ':thumbsdown:' => '<i class="'.$type.' fa-thumbs-down"></i>',
   '<3' => '<i class="'.$type.' fa-heart"></i>',
   ':heart:' => '<i class="'.$type.' fa-heart"></i>',
   ':star:' => '<i class="'.$type.' fa-star"></i>',
  );
  $content = str_replace(array_keys($icons),$icons,$content);
 }
 return $content;
}
add_filter('the_content','apply_font_awesome_emoticons');
add_filter('comment_text','apply_font_awesome_emoticons');

?>