<?php 

/*
 *******************
 * RENAME OLD PLUGIN LICENSE TABLE
 *******************
 *
 *	Detect if the old license table "wp_admin_theme_cd_license_data" exist, then rename to new license table name.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

function wphave_admin_rename_old_plugin_license_db_table() {
	
	global $wpdb;
	
	// List old license table names
	$old_license_tables = array(
		'wp_admin_theme_cd_license_data',
		$wpdb->prefix . 'wp_admin_theme_cd_license_data'
	);
	
	// Define new license table name
	$new_license_table = 'wphave_admin_license_data';
	
	// Check for the old license table
	foreach( $old_license_tables as $old_license_table ) {
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $old_license_table ) );
		
		if( $wpdb->get_var( $query ) == $old_license_table ) {
			$old_license_table = $old_license_table;	
		}
	}
	
	// Check if the old license table exist
	if( in_array( $wpdb->get_var( $query ), $old_license_tables ) ) {
		
		// MySQL connect
		$mysqli = wphave_admin_mysqli_connect();
	
		// Define new license table name
		$new_license_table = $wpdb->prefix . $new_license_table;
		
		// Search for old table name and rename to the new table name
		mysqli_query( $mysqli, "ALTER TABLE " . $old_license_table . " RENAME TO " . $new_license_table );
		
	}
	
	// Old license table not found
	return false;
	
}

add_action('admin_init', 'wphave_admin_rename_old_plugin_license_db_table');