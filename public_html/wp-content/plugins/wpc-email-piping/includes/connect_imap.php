<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( isset($_REQUEST['state']) && $_REQUEST['state'] == 'wpsc_ep_imap_connect') {

  $wpsc_ep_imap_email_address        = get_option('wpsc_ep_imap_email_address');
  $wpsc_ep_imap_email_password       = get_option('wpsc_ep_imap_email_password');
  $wpsc_ep_imap_encryption           = get_option('wpsc_ep_imap_encryption');
  $wpsc_ep_imap_incoming_mail_server = get_option('wpsc_ep_imap_incoming_mail_server');
  $wpsc_ep_imap_port                 = get_option('wpsc_ep_imap_port');
  
  $wpsc_ep_imap_encryption = $wpsc_ep_imap_encryption=='none' ? 'novalidate-cert':'imap/ssl/novalidate-cert';
  
  $flag = true;

  $response = 'New emails will start importing to tickets. Try sending an email to <strong>'.$wpsc_ep_imap_email_address.'</strong>';

  if( $flag && ( !extension_loaded('imap') ) ){
    $response = '<strong>php-imap</strong> module not enabled on your server. Enable it from your cPanel or contact to your host provider.';
    $flag = false;
    } 

  $user = get_user_by( 'email', $wpsc_ep_imap_email_address );
  if( $flag && $user ){
    $response = 'Email account belong to registered user is not allowed to be piped. Do not use the same email already in use by a WordPress user. Create a unique email for piping, not to be used by any WordPress user.';
    $flag = false;
  }

  if ($flag){
    if(!empty($wpsc_ep_imap_incoming_mail_server) && !empty($wpsc_ep_imap_port) && !empty($wpsc_ep_imap_encryption) && !empty($wpsc_ep_imap_email_address) && !empty($wpsc_ep_imap_email_password)){
      $conn = @imap_open('{'.$wpsc_ep_imap_incoming_mail_server.':'.$wpsc_ep_imap_port.'/'.$wpsc_ep_imap_encryption.'}INBOX', $wpsc_ep_imap_email_address, $wpsc_ep_imap_email_password);
    }
  

  if (!$conn){
      $response = imap_last_error();
      $flag = false;
    }
  }

  if( $flag && $conn ){
    $uids     = imap_search($conn, 'ALL', SE_UID);
    $last_uid = $uids ? $uids[count($uids)-1] : 0;
    update_option( 'wpsc_ep_imap_uid', $last_uid );
  }
  if( $flag ){
    $icon_class = 'fa fa-thumbs-up';
    $title = 'Connection Successful!';
    $background_color = '#1E90FF';
  } else {
    $icon_class = 'fa fa-exclamation-triangle';
    $title = 'Connection Failed!';
    $background_color = '#FF0000';
  }
  
  ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="<?php echo WPSC_PLUGIN_URL.'asset/css/bootstrap-iso.css'?>">
  <link rel="stylesheet" href="<?php echo WPSC_PLUGIN_URL.'asset/lib/font-awesome/css/all.css'?>">
  <div class="bootstrap-iso">
    <div class="row" style="margin-top:20px;">
      <div class="col-md-6 col-md-offset-3" style="background-color:<?php echo $background_color?>;color:#fff;border-radius:4px;text-align:center;">
        <h2 style="margin-top:20px;margin-bottom:20px;"><i class="<?php echo $icon_class?>"> <?php echo $title?></i></h2>
        <p><?php echo $response?></p>
        <a class="btn btn-sm btn-default" style="margin-bottom:20px;" href="<?php echo admin_url('admin.php').'?page=wpsc-settings'?>">Back to Settings</a>
      </div>
    </div>
  </div>
  <script>
  var bootstrap_between_768_992  = '<?php echo '<link href="'.WPSC_PLUGIN_URL.'asset/css/responsive/bootstrap-between-768-992.css?version='.WPSC_VERSION.'" rel="stylesheet">'?>';
  var bootstrap_between_992_1200 = '<?php echo '<link href="'.WPSC_PLUGIN_URL.'asset/css/responsive/bootstrap-between-992-1200.css?version='.WPSC_VERSION.'" rel="stylesheet">'?>';
  var bootstrap_max_width_767    = '<?php echo '<link href="'.WPSC_PLUGIN_URL.'asset/css/responsive/bootstrap-max-width-767.css?version='.WPSC_VERSION.'" rel="stylesheet">'?>';
  var bootstrap_min_width_768    = '<?php echo '<link href="'.WPSC_PLUGIN_URL.'asset/css/responsive/bootstrap-min-width-768.css?version='.WPSC_VERSION.'" rel="stylesheet">'?>';
  var bootstrap_min_width_992    = '<?php echo '<link href="'.WPSC_PLUGIN_URL.'asset/css/responsive/bootstrap-min-width-992.css?version='.WPSC_VERSION.'" rel="stylesheet">'?>';
  var bootstrap_min_width_1200   = '<?php echo '<link href="'.WPSC_PLUGIN_URL.'asset/css/responsive/bootstrap-min-width-1200.css?version='.WPSC_VERSION.'" rel="stylesheet">'?>';

  jQuery(document).ready(function(){
    wpsc_apply_responsive_bootstrap();
  });

  function wpsc_apply_responsive_bootstrap(){
    
    if (jQuery('.bootstrap-iso').length > 0) {
      
      var wpsc_width = jQuery('.bootstrap-iso').width();
      
      /* @media screen and (max-width: 767px) */
      if( wpsc_width < 768 ){
        jQuery('html').append(bootstrap_max_width_767);
      }
      
      /* @media (min-width: 768px) */
      if( wpsc_width >= 768 ){
        jQuery('html').append(bootstrap_min_width_768);
      }
      
      /* @media (min-width: 768px) and (max-width: 991px) */
      if( wpsc_width >= 768 && wpsc_width < 992 ){
        jQuery('html').append(bootstrap_between_768_992);
      }
      
      /* @media (min-width: 992px) */
      if( wpsc_width >= 992 ){
        jQuery('html').append(bootstrap_min_width_992);
      }
      
      /* @media (min-width: 992px) and (max-width: 1199px) */
      if( wpsc_width >= 992 && wpsc_width < 1200 ){
        jQuery('html').append(bootstrap_between_992_1200);
      }
      
      /* @media (min-width: 1200px) */
      if( wpsc_width >= 1200 ){
        jQuery('html').append(bootstrap_min_width_1200);
      }
      
    }
  }
  </script>
  <?php

}

?>