<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( isset($_REQUEST['state']) && $_REQUEST['state'] == 'wpsc_ep' && isset($_REQUEST['code']) ) {

  $code = sanitize_text_field( $_REQUEST['code'] );
  $url  = 'https://www.googleapis.com/oauth2/v4/token';

  $response = wp_remote_post( $url, array(
    'method'      => 'POST',
    'timeout'     => 45,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking'    => true,
    'headers'     => array(),
    'body'        => array(
        'client_id'     => get_option('wpsc_ep_client_id',''),
        'client_secret' => get_option('wpsc_ep_client_secret',''),
        'redirect_uri'  => admin_url('admin.php'),
        'code'          => $code,
        'grant_type'    => 'authorization_code',
    ),
    'cookies'     => array()
    )
  );

  if ( is_wp_error( $response ) ) {
      $error_message = $response->get_error_message();
      echo "Something went wrong: $error_message";
  } else {
      
      $access = json_decode( $response['body'], true );
      
      if (isset($access['refresh_token'])) {
        $refresh_token = sanitize_text_field( $access['refresh_token'] );
        update_option('wpsc_ep_refresh_token',$refresh_token);
      }
      
      // Get history_id
      $response = wp_remote_post( 'https://www.googleapis.com/gmail/v1/users/'.get_option('wpsc_ep_email_address','').'/profile', array(
        'method'      => 'GET',
        'timeout'     => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking'    => true,
        'headers'     => array(),
        'body'        => array(
            'access_token'     => $access['access_token'],
        ),
        'cookies'     => array()
      ));
      
      if (!is_wp_error( $response )) {
        
        $profile = json_decode( $response['body'], true );
        if (isset($profile['historyId'])) {
          
          $historyId = $profile['historyId'];
          update_option('wpsc_ep_historyId',$historyId);
          
        } else {
          
          echo '<pre>';
          print_r($profile);
          echo '</pre>';
          
        }
        
      } else {
        echo '<pre>';
        print_r($response);
        echo '</pre>';
      }
      
      ?>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <link rel="stylesheet" href="<?php echo WPSC_PLUGIN_URL.'asset/css/bootstrap-iso.css'?>">
      <link rel="stylesheet" href="<?php echo WPSC_PLUGIN_URL.'asset/lib/font-awesome/css/all.css'?>">
      <div class="bootstrap-iso">
        <div class="row" style="margin-top:20px;">
          <div class="col-md-6 col-md-offset-3" style="background-color:#1E90FF;color:#fff;border-radius:4px;text-align:center;">
            <h2 style="margin-top:20px;margin-bottom:20px;"><i class="fa fa-thumbs-up"> Connection Successful!</i></h2>
            <p><?php echo 'New emails will start importing to tickets. Try sending an email to <strong>'.get_option('wpsc_ep_email_address','').'</strong>'?></p>
            <a class="btn btn-sm btn-success" style="margin-bottom:20px;" href="<?php echo admin_url('admin.php').'?page=wpsc-settings'?>">Back to Settings</a>
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

}