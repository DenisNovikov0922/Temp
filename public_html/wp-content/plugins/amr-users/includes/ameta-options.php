<?php // the default and option related stuff

function amr_excluded_userkey ($i) {  // dont really need this anoymore if have excluded keys
global $excluded_nicenames;
/* exclude some less than useful keys to reduce the list a bit */
		if (!empty($excluded_nicenames[$i])) { 
			return (true);
		}  
		return (false);		
	}

function amr_default_fieldstypes() {
	$fields_types = array(
				'ID' 				=> 'id',
				'user_login' 		=> 'handle',  // could be email
				'user_nicename' 	=> 'free_text',
				'user_email'		=> 'email' ,
				'user_url'			=> 'url',
				'user_registered' 	=> 'datetime',  //UTC
				'display_name' 		=> 'free_text',
				'first_name' 		=> 'free_text',
				'last_name' 		=> 'free_text',
				'nick_name' 		=> 'free_text',
				'description' 		=> 'free_text',
				//'first_role' 		=> 'validated_value' // deprecated 2020
			
			);
	if (!is_network_admin()) {
		$fields_types['post_count']		= 'integer';
		$fields_types['comment_count'] 	= 'integer';
	}
	
	$fields_types = apply_filters('amr-users-default-fieldtypes',$fields_types); 
	$fields_types = apply_filters('amr-users-fixed-fieldtypes',$fields_types); 
	return ($fields_types);		
}

function amr_fixed_fieldstypes() {  //20170912 make sure stay fixed.  Only really serioius ones, others may use fields in strange ways
global $wp_roles, $wpdb;
	$fields_types ['ID'] 				= 'id';
	$fields_types [	'user_email']		= 'email' ;
	$fields_types [	'user_url'	]		= 'url';
	$fields_types [	'user_registered' ]	= 'datetime';  //UTC			
	//$fields_types [	'first_role' ]		= 'validated_value';
	$fields_types [	'avatar' ]			= 'avatar';
	$fields_types [	'admin_color' ]		= 'validated_value';

	$fields_types [$wpdb->prefix.'capabilities' ]	= 'array_values';		
	$post_types = get_post_types();	
	foreach ($post_types as $posttype) {
		$fields_types[$posttype.'_count'] = 'integer';	
	}

	foreach ($wp_roles->role_names as $role => $rolename) { 
		$fields_types[$wpdb->prefix.'capabilities-'.$role] = 'boolean';
	}
	
	$fields_types = apply_filters('amr-users-fixed-fieldtypes',$fields_types); // allow addons to fix more

	return ($fields_types);		
}
	
function amr_fieldtypes() { // generic fieldtypes
	$types = array (
	
		'avatar'			=> __('Avatar - uses email address', 'amr-users'),
		'boolean' 			=> __('Boolean', 'amr-users'), // True, False, 1, 0, Y,N, T,F
		'date'				=> __('Date yyyy-mm-dd (ISO)', 'amr-users'),
		'date_ambiguous'	=> __('Date ambiguous - as accepted by PHP', 'amr-users'),
		'datetime' 			=> __('Datetime yyyy-mm-dd hh:mm:ss (ISO)', 'amr-users'),
		'timestamp'			=> __('Datetime Unix timestamp', 'amr-users'),
		'hhmm'				=> __('Time hh:mm', 'amr-users'),
		'email' 			=> __('Email address', 'amr-users'),		
		'free_text' 		=> __('Text free', 'amr-users'),
		'html_colour'		=> __('Html Colour',  'amr-users'), //202008		
		'ip_address'		=> __('Ip address',  'amr-users'), //202008		
		'phone' 			=> __('Number Telephone', 'amr-users'), //home, fax or mobile
		'integer' 			=> __('Number Integer', 'amr-users'),
		'currency_amount' 	=> __('Number Currency', 'amr-users'),
		'floating' 			=> __('Number Floating point', 'amr-users'),
		'post_id'			=> __('Number Post id', 'amr-users'),	
		'rating'			=> __('Number Rating',  'amr-users'),
		'percent'			=> __('Number Percent',  'amr-users'),
		'url' 				=> __('Url', 'amr-users'), //website or social profile
		'uploads_url'		=> __('Url Relative Uploads', 'amr-users'), //partial url in uploads folder (eg buddypress)		
		'id' 				=> __('User id', 'amr-users'), //own or related
		'handle' 			=> __('Username or social media handle', 'amr-users'), 
		'validated_value'	=> __('Validated value', 'amr-users'),
		'array_values'		=> __('Values, array or comma separated', 'amr-users'),		//202008	
		'array_serialised'	=> __('Serialised array of values', 'amr-users'),		//202009 wtf profile grid	
		//'lookup_value' 		=> __('Code requires lookup', 'amr-users') //addon?
		);

	$types = apply_filters('amr-users-fieldtypes',$types); //generic fieldtypes
	return ($types);
}

function amr_formats_for_fieldtypes() { //functions need to exist for format to work
	//$types = amr_fieldtypes();
	
	$date_formats =	array(
		'date'				=> __('Date','amr-users').' '.__('(translateable)','amr-users'), //20201004 cleanup {
		'age' 				=> __('Age','amr-users'), 
		'ago' 				=> __('Ago (Human time difference)','amr-users'), 
		'month' 			=> __('Month','amr-users').' '.__('(translateable)','amr-users'),  //eg for birthmonth
		'abbr_month' 		=> __('Month abbreviated','amr-users').' '.__('(translateable)','amr-users'),  //eg for birthmonth		
		'year' 				=> __('Year','amr-users','amr-users'));  // 20210227	
		
	$dt_formats	=	array (	'datetime'=> __('Date and time','amr-users'),
				'time'	=> __('Time','amr-users'))
				+ $date_formats;

	$fieldtype_formats = array (  
		'freetext' 			=> array('icon_hover' => __('Icon hover','amr-users')),
		'boolean' 			=> array('tick_cross' => __('Tick or cross','amr-users'), // True, False, 1, 0, Y,N, T,F
								'true_false' => __('True or False','amr-users').' '.__('(translateable)','amr-users'),
								't_f'	=>  __('T or F','amr-users').' '.__('(translateable)','amr-users'),
								'yes_no' 	=>  __('Yes or No','amr-users').' '.__('(translateable)','amr-users'),
								'Y_N' 		=>  __('Y or N','amr-users').' '.__('(translateable)','amr-users')
								),
		'email' 			=> array('avatar'	=> __('Avatar','amr-users'),
									'mailto' 	=> __('"Email" with mailto','amr-users'),
									'emailto' 	=> __('Email address with mailto','amr-users'),
									'icon_mailto'=> __('Icon with mailto','amr-users'), 
									),//css to change
		'url' 				=> array('url_link'=> __('Url text with link','amr-users'), 
									'fieldname_link' => __('Field name with link','amr-users'), //202008
									'icon_link' => __('Icon with link','amr-users'), //202008
									'image' => 	__('As image','amr-users'),
									'image_icon_link' => __('Image icon with link','amr-users')),
		'uploads_url'		=> array('fieldname_link' => __('Field name with link','amr-users'), //202008
									'icon_link' => __('Icon with link','amr-users'),
									'filename_link' => __('Filename with link','amr-users'), 
									'image'		=> __('Image','amr-users-plus'),
									'image_icon_link' => __('Image icon with link','amr-users'),
									'image_thumbnail' 	=> __('Thumbnail'), //use wp translations hopefully
									'image_medium'		=> __('Medium'),
									'image_medium_large'=> __('Medium Large'),
									'image_large'		=> __('Large'),
									'image_full'		=> __('Full')),
		'avatar'			=> array ( //20200928
				'thumbnail' 	=> __('Thumbnail'),
				'medium'		=> __('Medium'),				//use wp translations hopefully
											),		
//
		'phone' 			=> array(
								'number_tel' 	=> __('Phone number with tel','amr-users'), //20201004
								'fieldname_tel' => __('Fieldname with tel','amr-users'), 
								'call_link'		=> __('call icon with tel','amr-users')), //202008
		//home, fax or mobile
		'integer' 			=> array('age_from_year' => __('Age from year','amr-users')), //calc asif integer was year
//
		'date'				=> $date_formats,
		'timestamp'			=> $dt_formats,
		'datetime' 			=> $dt_formats,
		'date_ambiguous'	=> $dt_formats,
		'id' 				=> array(), //with linktype
		'handle' 			=> //('Username or social media handle', 'amr-users'), 
								array(), //see social addon							
		'currency_amount' 	=> array(),
		'floating' 			=> array(),
		'html_colour'		=> array('block' => __('Colour Block','amr-users'),
								'colour_text'=> __('Colour text','amr-users')),
		'array_values'		=> array('comma_separated' => __('Comma separated values','amr-users'), //202008
									'comma_space_separated' => __('Comma space separated values','amr-users'),
									'html_list' => __('html list','amr-users')),
		'post_id'			=> array('post_title'	=>  __('Post title','amr-users'),
								'post_link'		=>  __('Post link with title','amr-users'),
								'image_thumbnail' => __('Thumbnail'), //use wp translations hopefully
								'image_medium'		=> __('Medium'),
								'image_medium_large'=> __('Medium Large'),
								'image_large'		=> __('Large'),
								'image_full'		=> __('Full')
								),	
		'rating'			=> array('number'	=> __('Number','amr-users-plus'),
									'stars5'	=> __('Stars (5)','amr-users-plus')),
		'percent'			=> array('percent'	=> __('Percent','amr-users-plus'),
									'stars5'	=> __('Stars (5)','amr-users-plus')),
									
		//'validated_value'	=> array('as_is' => __('as_is', 'amr-users')),	//maybe allow addons to add a 'translated' option	but for now, no formats					
		);
		$fieldtype_formats['array_serialised'] = $fieldtype_formats['array_values'] ;

	
	$fieldtype_formats = apply_filters('amr-users-fieldtype-formats',$fieldtype_formats); 
	//add possible formats to the fieldtypes - functions must exist
	return ($fieldtype_formats);
	
	}	
	
function amr_linktypes () {
	$linktypes = array (
		'none' 				=> __('none', 'amr-users'),
		'edituser'			=> __('edit user', 'amr-users'),
		'mailto'			=> __('mail to', 'amr-users'),
		'postsbyauthor' 	=> __('posts by author in admin', 'amr-users'),
		'authorarchive' 	=> __('author archive', 'amr-users'),
		'commentsbyauthor' 	=> __('comments by author (*)', 'amr-users'), // requires extra functionality
		'url' 				=> __('users url', 'amr-users'),
		'wplist' 			=> __('wp user list filtered by user', 'amr-users'),//eg for other user details that may be in list, but not in  ?
		'bbpressprofile' 	=> __('bbpress user profile page', 'amr-users')
		);

	$linktypes = apply_filters('amr-users-linktypes',$linktypes); 
	return ($linktypes);
	
	}
	
function ameta_defaultnicenames () {
global $orig_mk, $wpdb;

unset($nicenames);
$nicenames = array (
	'ID' 					=> __('Id', 'amr-users'),
	'avatar' 				=> __('Avatar','amr-users'),
	'user_login' 			=> __('User name','amr-users'),
	'user_nicename'			=> __('Nice name','amr-users'),
	'user_email' 			=> __('Email','amr-users'),
	'user_url' 				=> __('Url','amr-users'),
	'user_registered' 		=> __('User Registered','amr-users'),
	//'user_registration_date' => __('Registration date','amr-users'),
	'user_status' 			=> __('User status','amr-users'),
	'display_name' 			=> __('Display Name','amr-users'),
	'first_name' 			=> __('First name','amr-users'),
	'last_name' 			=> __('Last name','amr-users'),
	'nick_name' 			=> __('Nick Name','amr-users'),
	'post_count' 			=> __('Post Count','amr-users'),
	'comment_count' 		=> __('Comment Count','amr-users'),
	'first_role' 			=> __('First Role', 'amr-users'),
	'admin_color' 			=> __('Admin Color Scheme', 'amr-users'),
	$wpdb->prefix.'capabilities' 			=> __('Roles', 'amr-users'),
	//'ausers_last_login' => __('Last Login', 'amr-users')
);


return ($nicenames);
}

function ameta_default_list_options () { // default lists  $aopt
/* setup some list defaults */

ameta_cache_enable(); //in case cache tables got deleted
ameta_cachelogging_enable();

if (amr_is_network_admin()) {
	$default = array (
	'list' => 
		array ( '1' => 
				array(
				'selected' => array ( 
					'user_login' => 2, 
					'user_email' => 3,
					'user_registered' => 5,
					'blogcount_as_subscriber' => 10,
					'blogcount_as_administrator' => 15,
					'bloglist_as_subscriber' => 20,
					'bloglist_as_administrator' => 25,		
					'bloglist' => 100
					),
				'sortdir' => array ( /* some fields should always be sorted in a certain order, so keep that fact, even if not sorting by it*/
					'user_registered' => 'SORT_DESC'),
				'sortby' => array ( 
					'user_registered' => '1'
					),
				'before' => array (    
					'last_name' => '<br />'
					),			
				'links' => array (    
					'user_email' => 'mailto',
					'user_login' => 'edituser',
					'bloglist' => 'wplist'
					),
				)
		)
		);


}
else {
		$sortdir = array ( /* some fields should always be sorted in a certain order, so keep that fact, even if not sorting by it*/
							'user_registered' => 'SORT_DESC'
							);

		$default = array (
			'list' => 
				array ( '1' => 
						array(
						'selected' => array ( 
 
							'user_login' => 20, 
							'user_email' => 30,
							'display_name' => 40,
							'user_registered' => 50,
							//'first_role' => 60
							),
						'sortdir' => array ( /* some fields should always be sorted in a certain order, so keep that fact, even if not sorting by it*/
							'user_registered' => 'SORT_DESC'),
						'sortby' => array ( 
							'user_registered' => '1'
							),				
						'links' => array (    
							'user_email' => 'mailto',
							'user_login' => 'edituser', 	
							'user_url' => 'url', 	
							'avatar' => 'authorarchive',
							
							),
						'excluded' => array ( 
							//'ID' => '1', // not great to exclude by id, rather use role
							//'first_role' => 'Administrator'
							),	
						),
						'2' => 
						array(
						'selected' => array ( 
							'avatar' => 10, 
							'display_name' => 20,
							//'description' => 30,  if theu don't run find fields it won't show up and be confusing
							//'user_url' => 30, no - because default format is no link - looks bad
							'user_registered' => 40
							),
						'excluded' => array ( 
							'ID' => '1', 
							),
						'sortby' => array ( 
							'user_registered' => '2'
							),
						'links' => array (    
							'avatar' =>  'edituser',
							'display_name' => 'authorarchive'

							)					
						)
					)
		//			,
		//	'stats' => array ( '1' => 
		//				array(
		//					'selected' => $selected,
		//					'totals' => array ( /* within the selected */
		//						'ym_status' ,
		//						'account_type'
		//						)
		//				),
		//			)
				);
	}
	ausers_update_option('amr-users',$default);
	
	return ($default);

}	

function ameta_default_main () {
/* setup some defaults */

$default = array (
	'notonuserupdate' => true,
	'checkedpublic' => true, /* so message should only show up if we have retrieved options from DB and did not have this field - must have been an upgrade, not a reset, and not a new activation. */
    'rows_per_page' => 20,
	'avatar_size' => 16,
	'csv_text' =>  ('<img src="'.plugins_url('amr-users/images/file_export.png').'" alt="'.__('Csv', 'amr-users') .'"/>'),
	'xls_text' =>  ('<img src="'.plugins_url('amr-users/images/xls.png').'" alt="'.__('Xls', 'amr-users') .'"/>'),
	'refresh_text' =>  ('<img src="'.plugins_url('amr-users/images/rebuild.png').'" alt="'.__('Refresh user list cache', 'amr-users') .'"/>'),
	'noaccess_text' => __('You do not have access to this list, or are not logged in.', 'amr-users'),
	'sortable' =>	array ( '1' => true,
				'2' => true,
				),		
	'names' => 
		array ( '1' => __("Users: Details", 'amr-users'),
				'2' => __("Users: Directory", 'amr-users'),
				),
	'html_type' =>
		array ( '1' => 'table',
				'2' => 'simple',
				),	
	'filter_html_type' =>
		array ( '1' => 'intableheader',
				'2' => 'above',
				),				
	'public' => 	
		array ( '1' => true,
				'2' => true,
				),
	'show_refresh' => 	
		array ( '1' => false,
				'2' => false,
				),
	'show_perpage'	=>
		array ( '1' => true,
				'2' => false,
				),		
	'show_search'	=>
		array ( '1' => true,
				'2' => true,
				),	
	'show_csv'	=>
		array ( '1' => false,
				'2' => false,
				),		
	'show_xsl'	=>
		array ( '1' => false,
				'2' => false,
				),					
	'customnav'	=>
		array ( '1' => true,
				'2' => true,
				),					
	'show_headings'	=>
		array ( '1' => true,
				'2' => false,
				),		
	'list_avatar_size' => 	
		array ( '1' => 16,
				'2' => 100,
				),
	'show_pagination'	=>
		array ( '1' => true,
				'2' => true,
				),				
	);
	
	if (amr_is_network_admin()) {
		unset($default['names']['2']);
		unset($default['names']['3']);
	}
	ausers_update_option('amr-users-main', $default);			
	return ($default);

}	
	
function ausers_get_option($option) { // allows user reports to be run either at site level and/or at blog level
global $ausersadminurl, $amr_nicenames;
	
	if (amr_is_network_admin() )
		$result = get_site_option('network_'.$option);
	else 
		$result = get_option($option);	
	
	if (empty($result)) { // it's new, get defaults
		//if ($option == 'amr-users-no-lists' ) 	return ameta_default_main(); // old - leave for upgrade check 
		if ($option == 'amr-users-main' ) 		{ // and it's empty
			//-------------------------
			//if (WP_DEBUG) echo '<br />Renaming stored option "amr-users-no-lists" to "amr-users-main" ';
			$amain = get_site_option('amr-users-no-lists');   // might return default ok, if not will have done upgrade check 
			if (empty($amain)) {
				$amain = ausers_get_option('amr-users-no-lists');
				if (empty($amain)) {
					$amain = ameta_default_main();
				}
			}

			$amain['version'] = AUSERS_VERSION;
			ausers_update_option('amr-users-main',$amain);
			ausers_delete_option('amr-users-no-lists');
			return $amain;
			//-------------------------
		}
		if ($option == 'amr-users' ) 					
			return (ameta_default_list_options());
		if ($option == 'amr-users-nicenames-excluded') 	
			return array(
				'attachment_count' 		=> true,
				'activation_key' 		=> true,
				'dismissed_wp_pointers'	=> true,
				'default_password_nag'	=> true,
				'nav_menu_item_count'	=> true,
				'revision_count'		=> true,
				'comment_count'			=> true,
				'show_admin_bar_front'	=> true,
				'show_welcome_panel'	=> true,
				'user_activation_key'	=> true,
				'user_status'			=> true,
				'yim'					=> true,
				'aim'					=> true,
				'jabber'				=> true,
				'reply_count'			=> true,
				'topic_count'			=> true,
				'forum_count'			=> true,
				'use_ssl'				=> true
				);
		if ($option == 'amr-users-original-keys') 		return array();
		if ($option == 'amr-users-custom-headings') 	return array();
		if ($option == 'amr-users-prefixes-in-use') 	return array();
		if ($option == 'amr-users-nicenames' ) 	{		
			$amr_nicenames = ameta_defaultnicenames();  
			$result = $amr_nicenames;			
			}  
		if ($option == 'amr-users-field-types' ) 	{		
			$result = amr_default_fieldstypes();  			
			}			
	}		
	return($result);
}

function ausers_update_option($option, $value) { // allows user reports to be run either at site level and/or at blog level
global $ausersadminurl;

//dont really need 'network' prefix but keep for compatibility else we have to convert.  Site options stored in sitemeta.
// Inconsistent terminology - in DB, site is the whole network.
// The admin page calls the blogs 'sites' - sooo confusing.

	if (amr_is_network_admin()) { 
		if (empty($value)) 
			$result = delete_site_option('network_'.$option);
		else		
			$result = update_site_option('network_'.$option, $value);

		if (!$result) { // try add 20170615
			$result = add_site_option('network_'.$option, $value);
			if (!$result) { //either no update, no change or error
			  echo '<br/> Unexpected error adding option: '.$option;
			}
		}
	}

//	if (stristr($ausersadminurl,'network') == FALSE) {	
	//	$result = update_option($option, $value);
//	}
	else {
		if (empty($value)) 
			$result = delete_option($option);
		else {
			$result = update_option($option, $value);	
			if (!$result) {
				$result = add_option($option, $value); //***
			}
		}	
	}
	//if (WP_DEBUG) {	echo 'Option update '.$option;}
	if (!($option== 'amr-users-cache-status')) {
		ausers_delete_htmltransients() ;
		}
	return($result);
}

function ausers_delete_option($option) { 
global $ausersadminurl;
	
	if (amr_is_network_admin()) 	{
		$option = 'network_'.$option;
		$result = delete_site_option($option);
		}
	else 
		$result = delete_option($option);	
	return($result);
}
	
function ameta_options (){ // set up all  the options

global 
	$amr_current_list ,
	$aopt,
	$amain,
	$amr_nicenames, 
	$amr_your_prefixes,
	$excluded_nicenames,
	$ausersadminurl,
	$wpdb;

	if (empty($amain)) 
		$amain 				= ausers_get_option('amr-users-main');
		$amr_your_prefixes 	= ausers_get_option('amr-users-prefixes-in-use');
		$amr_nicenames 		= ausers_get_option('amr-users-nicenames');
		$excluded_nicenames = ausers_get_option('amr-users-nicenames-excluded');

	foreach ($excluded_nicenames as $i=>$v)	{
		if ($v) unset ($amr_nicenames[$i]);
	}

	$aopt = ausers_get_option ('amr-users');
	
	return;
}
	
