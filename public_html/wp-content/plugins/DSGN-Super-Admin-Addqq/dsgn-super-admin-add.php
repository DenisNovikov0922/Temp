<?php
/**
 * Plugin Name: DSGN - Super Admin ADD Network (SINGLE SITE)
 * Plugin URI:  https://www.dsgn.cc
 * Description: Aggiungi super-admin a tutti i siti (per report)
 * Version:     1.0.0
 * Author:      DSGN
 * Author URI:  https://www.dsgn.cc
 * Copyright:   2021 DSGN
 *
 * Text Domain: super-admin-add-network
 * Domain Path: /languages/
 */

add_action('init', 'update_super_admins_access');

function update_super_admins_access() {

	

	// since this is a big thing to do, we can run a test and see whats

	// going to happen before it does

	$test = false;

	

	// this is the scheduling feature, run only once a month.

	// I used transient because the API is eaiser and the load of the function isn't demanding. 

	// I know transient is for cacheing but as shown it can be used to schedule aswell. 

	// wp_schedule_event() cron events would be the best and cleanest solution. I reccomend doing it that way.

	// if you're ontop of your code, an if statment like if (isset($_GET['update_super_admins'])) 

	// would be good enough to run and fire manually

	$cache = 60*60*24*30; 

	if ( false === ( $last = get_transient( 'update_super_admins_access' ) ) ) {

		

		// lets get all the super admins, and all the sites

		$superAdmins = get_super_admins();

		$allSites = wp_get_sites(

			array('deleted' => '0')

		);

		

		// and we'll store the test run in a simple array

		$dryrun = array();

		

		// cycle through each super admin 

		foreach ($superAdmins as $sAdmin) {

			

			// get the super admin details since the above was just slug

			$user = get_user_by('login',$sAdmin);

			$user_id = $user->ID;

			

			// k lets define the user, and prepare the list of sites

			$dryrun[$user->data->user_nicename] = array();

			

			// cycle through each site

			foreach ($allSites as $site) {

				

				// check if is already added or not

				if (!is_user_member_of_blog( $user_id, $site['blog_id'] )) {

					

					// record the site for the user to be added to

					$dryrun[$user->data->user_nicename][] = $site['path'];

					

					// and if we're not testing: go go go

					if (!$test) 

						add_user_to_blog( $site['blog_id'], $user_id, 'administrator' );

				}

			}

		}

		

		// if testing, just spit the array and some instructions

		if ($test) {

			echo "<pre>".print_r($dryrun, true)."</pre>";

			die('The above shows which users will be added to which sites. to actually run this, set `$test = false;`');

		} 

		// if we're actually running this, don't do it again until cache exp

		else {

			set_transient( 'update_super_admins_access', time(), $cache );

		}

	}

}
