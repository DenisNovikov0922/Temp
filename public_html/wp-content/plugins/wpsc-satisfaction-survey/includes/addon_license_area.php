<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$license_key    = get_option('wpsc_sf_license_key','');
$license_expiry = get_option('wpsc_sf_license_expiry','');

if($license_expiry && $license_expiry!="lifetime"){
  $expiry = new DateTime($license_expiry);
  $expiry = date_format($expiry, 'M d, Y');
}else{
	$expiry = "Lifetime";
}

?>
<div class="col-md-4 wpsc_addon_license_container">
  <div class="row wpsc_lic_title"><h4><?php _e('Satisfaction Survey','wpsc-sf')?></h4></div>
  <div class="row wpsc_lic_body">
    <input type="text" id="wpsc_sf_license_key" <?php echo $license_expiry ? 'disabled' : ''?> value="<?php echo $license_key?>">
    <?php
    if ($license_expiry) {
      ?>
      <button onclick="wpsc_sf_lic_deactivate();" type="button" name="button">Deactivate</button>
      <?php
    } else {
      ?>
      <button onclick="wpsc_sf_lic_activate();" type="button" name="button">Activate</button>
      <?php
    }
    ?>
  </div>
  <div class="row wpsc_lic_footer">
    <?php
    if ($license_expiry) {
      echo 'License expires on '.$expiry;
    } else {
      echo 'Activate license to get automatic updates.';
    }
    ?>
    </div>
</div>

<script>
  function wpsc_sf_lic_activate(){
    var license_key = jQuery('#wpsc_sf_license_key').val().trim();
    if (!license_key) {
      alert('Please enter license key!');
      return;
    }
    jQuery('#wpsc_sf_license_key, .wpsc_lic_body button').attr('disabled',true);
    var data = {
      action: 'wpsc_sf_activate_license',
      license_key : license_key
    };
    jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
      var response = JSON.parse(response_str);
      if(response.success == 1){
        jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
        jQuery('#wpsc_alert_success').slideDown('fast',function(){});
      } else {
        jQuery('#wpsc_alert_error .wpsc_alert_text').text(response.messege);
        jQuery('#wpsc_alert_error').slideDown('fast',function(){});
      }
      setTimeout(function(){ location.reload(true); }, 1000);
    });
  }
  function wpsc_sf_lic_deactivate(){
    var license_key = jQuery('#wpsc_sf_license_key').val().trim();
    if (!license_key) {
      alert('Please enter license key!');
      return;
    }
    jQuery('#wpsc_sf_license_key, .wpsc_lic_body button').attr('disabled',true);
    var data = {
      action: 'wpsc_sf_deactivate_license',
      license_key : license_key
    };
    jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
      var response = JSON.parse(response_str);
      if(response.success == 1){
        jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
        jQuery('#wpsc_alert_success').slideDown('fast',function(){});
      } else {
        jQuery('#wpsc_alert_error .wpsc_alert_text').text(response.messege);
        jQuery('#wpsc_alert_error').slideDown('fast',function(){});
      }
      setTimeout(function(){ location.reload(true); }, 1000);
    });
  }
</script>
