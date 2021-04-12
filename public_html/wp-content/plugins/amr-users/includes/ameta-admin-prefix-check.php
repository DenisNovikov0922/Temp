<?php // only in admin

if ( function_exists( 'amr_prefix_check_change' ) ) {
	$ok = amr_prefix_check_change();
	if (!$ok) 
		add_action( 'admin_notices', 'amr_prefix_change_notice' );
}

function amr_prefix_check_change() {  // check if we still using the same prefix as stored in the settings or was the prefix changed either manually or by a staging system.
	global $wpdb;
	
	$amr_wp_prefix 	= get_option('amr-users-save-prefix'); // get the saved prefix if there is one
	if (empty($amr_wp_prefix)) { // we havent saved one yet
		add_option('amr-users-save-prefix',$wpdb->prefix );
		return true;
	}
	elseif (!($wpdb->prefix == $amr_wp_prefix)) {   // we already using that, probably still same
		// prefix has changed - sigh
		
		$settings_to_check = array(
		'amr-users-excluded-meta-key');
		update_option('amr-users-save-prefix',$wpdb->prefix );
		return true;
	}
	else return true;
}

function amr_prefix_change_notice() {
  ?>
  <div class="updated notice">
      <p><?php _e( 'The wp prefix has changed. Nice Name and List field settings updated.', 'amr-users' ); ?></p>
  </div>
  <?php
}