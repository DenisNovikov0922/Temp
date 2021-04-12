<?php 
// is_plugin_active only gets declared by wp in admin and late it seems, lots of trouble tryingto use it apparently clashing
// doesnt work for network active plugins
function amr_is_plugin_active( $plugin ) {
		if (function_exists(('is_plugin_active'))) { 
			return (is_plugin_active($plugin));
			}
		else {
		
			$active = (array) get_option( 'active_plugins', array() );

			if (in_array( $plugin, $active )) return true;
			
			if ( !is_multisite() )            return false;
			$plugins = get_site_option( 'active_sitewide_plugins');		
			if (isset($plugins[$plugin])) return true;
			return false;
		}	
}

function amr_swap(&$x,&$y) {  // to assist with a descending sort
    $tmp=$x;
    $x=$y;
    $y=$tmp;
}

if (!function_exists('auser_multisort_collate')) { //20171027
function auser_multisort_collate(&$arr, $cols, $fields_types) {
	global $collator, $sortfields, $amr_fields_types, $numeric_sorts ;
	
	$numeric_sorts = array('integer','id','floating','currency_amount');
	$amr_fields_types = $fields_types;	
	$sortfields = $cols;   
	$l = get_locale();
	$collator = new collator($l);
	
	if (empty($sortfields) or !(is_array($sortfields))) return $arr; // 20171028
	
	//make sure numerics are numeric
	foreach ($sortfields as $fld => $sort) { // really only until return!
		if ((isset($amr_fields_types[$fld])) and (in_array($amr_fields_types[$fld],$numeric_sorts))) {
				foreach ($arr as $i => $v) { 
					if (!empty($arr[$i][$fld])) 
						$arr[$i][$fld] = (float) $arr[$i][$fld]; 
					
				}
		};
	}

	uasort ($arr, 
		function ( $a, $b) {   // multidimensional sort with custom function
			global $collator, $sortfields,$amr_fields_types, $numeric_sorts ;  
	
			foreach ($sortfields as $fld1 => $sort1) { // really only until return!
				
				if ((!empty($amr_fields_types[$fld1])) and (in_array($amr_fields_types[$fld1],$numeric_sorts))) { 
				// its numeric, don't need collate compare
					//echo '<br />'.$amr_fields_types[$fld1].' '.$fld1;
					if (empty ($a[$fld1])) 	$aa = 0;
					else 					$aa = $a[$fld1];
					if (empty ($b[$fld1])) 	$bb = 0;
					else 					$bb = $b[$fld1];						
					if     ($aa < $bb) 	$cmp = -1;
					elseif ($aa > $bb) 	$cmp = +1;
					else $cmp = 0; 		// check next sortfield



				//echo '<br />----------------'.$fld1.' '.$aa.' '.$bb. ' '.$cmp;					

					}
				else {		// non numeric			
					if (empty ($a[$fld1])) 	$aa = '';
					else 					$aa = $a[$fld1];
					if (empty ($b[$fld1])) 	$bb = '';
					else 					$bb = $b[$fld1];
					$cmp = $collator->compare($aa, $bb);	//+1 if $aa greater than $bb
					//echo '<br />             '.$fld1.' '.$aa.' '.$bb. ' '.$cmp;
					
				}	
				// if field values are equal, go to next level sort, else we got a difference, so return
				if ($sort1 == SORT_DESC) { $cmp = -$cmp;}
				if (!($cmp == 0)) break;
				
			}
			
			return $cmp;
		}
	); // end uasort
	return $arr;		
	}
}
 
if (!function_exists('auser_multisort')) { 
function auser_multisort(&$arraytosort, $cols) { // $ cols has $col (eg: first name) the $order eg: ASC or DESC

	if (empty($arraytosort)) 
		return (false);
	if (empty($cols)) 
		return $arraytosort;
	
	$numeric_sorts = array('integer','id','floating','currency_amount');	
	$fields_types = ausers_get_option('amr-users-field-types');
	$fields_types_fixed = amr_fixed_fieldstypes();
	$amr_fields_types = array_merge($fields_types_fixed, $fields_types );
	
	if (class_exists('Collator')) {
		auser_multisort_collate($arraytosort, $cols, $amr_fields_types);
		return $arraytosort;
	}
		
	/* Example: $arr2 = array_msort($arr1, array('name'=>array(SORT_DESC,SORT_REGULAR), 'cat'=>SORT_ASC));*/
	    $colarr = array();
	    foreach ($cols as $col => $order) {
	        $colarr[$col] = array(); // eg $colarr[firstname]  
	        foreach ($arraytosort as $k => $row) { 
				if (!isset($row[$col])) 
					$colarr[$col]['_'.$k] = '';
				else 
					$colarr[$col]['_'.$k] = strtolower($row[$col]); // to make case insensitive ?
			}			
	    }
		
	    foreach ($cols as $col => $order) {  
	        $dimensionarr[] = $colarr[$col];
			$orderarr[] = $order;;		
			// test if numeric and set sortflags
			if ((!empty($amr_fields_types[$col])) and (in_array($amr_fields_types[$col],$numeric_sorts))) {
				$typeflags[] = SORT_NUMERIC; 
			}
			else $typeflags[] = SORT_LOCALE_STRING;  // not working for locale
	    }
		
		if (count($dimensionarr) < 2) { 
			array_multisort($dimensionarr[0], $orderarr[0], $typeflags[0],
							$arraytosort);
		}					
		elseif (count($dimensionarr) == 2)
			array_multisort($dimensionarr[0], $orderarr[0],$typeflags[0],
							$dimensionarr[1], $orderarr[1],$typeflags[1],
							$arraytosort
							);
		elseif (count($dimensionarr) == 3)
			array_multisort($dimensionarr[0], $orderarr[0],$typeflags[0],
							$dimensionarr[1], $orderarr[1],$typeflags[1],
							$dimensionarr[2], $orderarr[2],$typeflags[2],
							$arraytosort);
		elseif (count($dimensionarr) == 4)
			array_multisort($dimensionarr[0], $orderarr[0],$typeflags[0],
							$dimensionarr[1], $orderarr[1],$typeflags[1],
							$dimensionarr[2], $orderarr[2],$typeflags[2],
							$dimensionarr[3], $orderarr[3],$typeflags[3],
							$arraytosort
							);
		else
			array_multisort($dimensionarr[0], $orderarr[0],$typeflags[0],
							$dimensionarr[1], $orderarr[1],$typeflags[1],
							$dimensionarr[2], $orderarr[2],$typeflags[2],
							$dimensionarr[3], $orderarr[3],$typeflags[3],
							$dimensionarr[4], $orderarr[4],$typeflags[4],
							$arraytosort);
			
		return($arraytosort);

	}
}

if (!function_exists('amr_get_href_link')) {
	function amr_get_href_link ($field, $v, $u, $linktype) {  
		
	switch ($linktype) { 
			case 'none': return '';
			case 'mailto': {
				if (!empty($u->user_email)) return ('mailto:'.$u->user_email);
				else return '';
				}
			case 'postsbyauthor': { // figure out which post type ?
			
				if (empty($v) or !current_user_can('edit_others_posts')) return( '');
				else {
					$href = network_admin_url('edit.php?author='.$u->ID);		

					if (stristr($field, '_count')) { // it is a item count thing, but not a post count
						if (is_object($u) and isset ($u->ID) ) {
							$ctype = str_replace('_count', '', $field);
							$href=add_query_arg(array(   // safe - its a network admin url
								'post_type'=>$ctype
								),
								$href    
								);
							
						} // end if
					} // end if stristr
					return ($href);	
				}
				return '';
			}
			case 'edituser': {
				if (current_user_can('edit_users') and is_object($u) and isset ($u->ID) ) {
					if (is_network_admin())
						return ( network_admin_url('user-edit.php?user_id='.$u->ID));
					else	
						return ( admin_url('user-edit.php?user_id='.$u->ID));
					}
					//return ( network_admin_url('user-edit.php?user_id='.$u->ID));
				else return '';
				}
			case 'authorarchive': {  // should do on a post count only else may not be an author template ?
				if (is_object($u) and isset ($u->ID) ) { 
					//return(add_query_arg('author', $u->ID, home_url())); // 201401
					return(get_author_posts_url( $u->ID ));
					}
				else return '';
				}	
			case 'commentsbyauthor': {  //20140722			
				if (empty($v)) 
					return('');
				else 
					return (add_query_arg('s',$u->user_email, admin_url('edit-comments.php'))); //safe its admin url
			}
			case 'url': {
				if (!empty($u->user_url)) return($u->user_url);
			}	
			case 'wplist': { // for multisite
				if (current_user_can('edit_users') and is_object($u) and isset ($u->user_login) )
					return(network_admin_url('users.php?s='.$u->user_login));
			}
			case 'bbpressprofile' : {
				if (function_exists ('bbp_get_user_profile_url'))
				return (bbp_get_user_profile_url($u->ID));
/*	20130702 - use bbpress function instead, it allows filters etc, so who knows what might be there
				$slug 	= get_option('_bbp_user_slug');
				$forums = get_option('_bbp_root_slug');
				return (home_url('/'
				.__( $forums ,'bbpress')
				.'/'
				.__( $slug, 'bbpress')
				.'/'.$u->user_login 
				));*/
				else return '';
			}	
			default: 
			

			$url = apply_filters('amr-users-linktype-function',
				$linktype, // the current value
				$u,
				$field);

			return($url); // all the user values
	}
}
}

if (!function_exists('amr_wp_list_format_cell')) {
	function amr_wp_list_format_cell($column_name, $text, $user_info) {
		if ($column_name == 'user_url') {
			$text = '<a href="'.$text.'">'.$text.'</a>';
		}
		elseif (is_array($text)) { //20171010  cope with arrays
			//201710 note will not sort nicely with arrays - need a meta key sort
			$text = implode($text,', ');
			}
		return $text;
	}
}	

if (!function_exists('get_commentnumbers_by_author')) {
	function get_commentnumbers_by_author(  ) {
		 global $wpdb;

		$approved = "comment_approved = '1'";  // only approved comments
		$c = array();
		$comments = $wpdb->get_results(
		"SELECT user_id, count(1) as \"comment_count\" FROM $wpdb->comments WHERE $approved GROUP BY user_id;" 
		// 20140722 this will only get emails of folks who registered users and ignores whether they changed email address. and only gets logged in comments
		// if want general activity by all emails, not just unregistered, consider an add-on
		//	"SELECT user_id, comment_author_email, count(1) as \"comment_count\" FROM $wpdb->comments WHERE $approved AND user_id > 0 GROUP BY user_id, comment_author_email;" 
		);
		foreach ($comments as $i => $v) {
			$c[$v->user_id] = $v->comment_count;
		}
		unset ($comments);
		return $c;
	}
}

if (!function_exists('amr_format_field')) {
	function amr_format_field($fld, $val, $u) { // get the fieldtype and the requested format, return false if none
		global 	$aopt,
				$amr_current_list;
		
		//if (empty($val)) return false; //*** 20200927 - will this break other things? We might have formats for empty fields?
		
		if (isset ($aopt['list'][$amr_current_list]) ) {
			$l = $aopt['list'][$amr_current_list];
		}
		else return false;	
				
		if (!empty($l['format'])) {
			if (!empty($l['format'][$fld])) {
				//if (WP_DEBUG) echo '<br />Using format '.$l['format'][$fld].' for '.$fld ;
				$format = $l['format'][$fld];					
				$func = amru_get_func_name ($fld, $format);
				//debug_this($func, ' the func' );
				if (function_exists($func)) {
					$text = call_user_func($func, $val, $fld, $u);
					return $text;
				}
			}
		}
// else format is empty
		$func = amru_get_func_name ($fld, '');
		if (function_exists($func)) {
			$text = call_user_func($func, $val, $fld, $u);
			return $text;
		}
		else {
			return false;
		}		
	}
}
	
if (!function_exists('ausers_format_url_url_link')) { //20170309 
	function ausers_format_url_url_link ($url, $fld, $u) { //expects url
		return (make_clickable($url));
	}
}

if (!function_exists('amr_format_interval')) {
	function amr_format_interval($interval, $lowest_time='s') {  // rounds up
		// interval tests not working unless i var_dump the interval - wtf?
		// setting a variable rather than checking directly seems to work
		//y for years, m months, d days, h hours, i minutes, s seconds.
		$text = array();
		if (empty($interval)) {
			return false;
		}
		$y = $interval->y;
		if (!empty($y)) {			
			$text[] = $interval->format('%y '._n('year','years',$y,'amr-users'));
		}
		$m = $interval->m;
		if (!empty($m)) {
			$text[] = $interval->format('%m '._n('month','months',$m,'amr-users'));
		}
		if ((empty($y)) and (empty($m))) { //need more detail
			$d = $interval->d; 
			if (!empty($d)) { 	
				$text[] = $interval->format('%d '._n('day','days',$d,'amr-users'));
			}
			if (empty($d)) {
				if (!($lowest_time === 'd')) { // must at least have d
					//var_dump($interval);  if I dump the interval I get the detail - why?
					$x = $interval->h;
					if (!empty($x)) {
						$text[] = $interval->format('%h '._n('hour','hours',$x,'amr-users'));
					}
					if (!($lowest_time == 'h')) { // must be minutes, add them on?
						$x = $interval->i;
						if (!empty($x)) {  //must set variable, not check the object somehow is better
								//20170111
							$text[] = $interval->format('%i '._n('minute','minutes',$x,'amr-users'));
						}
						// don't do seconds for now... really
					}
				}
			}	
		}
		if (empty($text)) { // then it practically just happened - now
			$text[] = $interval->format('%i '._n('minute','minutes',$interval->i,'amr-users'));
			//$text[] = $interval->format('%s '._n('second','seconds',$interval->i,'amr-users'));
		}

		$textstring = implode(_x(',','Separator in for example years, months, days','amr-users'),$text);
		$textstring .= ' '._x('ago','how many days, minutes etc ago and event occured', 'amr-users');
		return ($textstring);
	}
}

if (!function_exists('amr_human_time_diff')) { // gives a rough feeling of how long with details in the tooltip
	function amr_human_time_diff($earlier, $later='', $lowest_time='s') { // uses date time objects
		//lowest time options are: 
		//y for years, m months, d days, h hours, i minutes, s seconds.

		if (empty($earlier)) {
			return false;
		}	
		if (!is_object($earlier)) {
			if (is_numeric($earlier)) {
				$earlier = amru_newDateTime('@'.$earlier);
				}
			else {
				$earlier = amru_newDateTime($earlier);
				}
		}

		if (empty($later)) {
			$later = amru_newDateTime();   // what is default timezone? 
		}	
		if ( method_exists($earlier, 'diff') ) {  // if not php 5.3 or above
				$interval = $earlier->diff($later);
				//var_dump($interval); echo '<br />';
				$text = amr_format_interval($interval, $lowest_time);			
			}
			else {
				$text = human_time_diff($earlier->format('U'));
		}

		$tip = $earlier->format('l, j M Y h:i a e' );		
		return (ausers_tooltip($text,$tip ));
	}
}

if (!function_exists('ausers_format_datetime_ago_wp')) { //202009
	function ausers_format_datetime_ago_wp($datetime) { //expects YYYY-MM-DD hh:mm:ss and returns age in years and months
		$dt = amru_newDateTime($datetime);
		$now = amru_newDateTime();
		$text = human_time_diff($dt->format('U'), $now->format('U'));
		return $text;
	}
}

if (!function_exists('ausers_format_ausers_last_login')) {
	function ausers_format_ausers_last_login($v, $u) {
		if (!empty($v))
			return (substr($v, 0, 16)); //2011-05-30-11:03:02 EST Australia/Sydney
		else return ('');	
	}
}

if (!function_exists('ausers_tooltip')) {  // create a tooltip span using browser default tooltip unless styled otherwise
// do not use class="tooltip" because bootystrap messes with it 20170903
	function ausers_tooltip( $text, $tip) {
		return('<span class="amr-tooltip" title="'.$tip.'">'.$text.'</span>');
	}
}	

if (!function_exists('ausers_format_user_registered')) {  //because I want to see days ago
	function ausers_format_user_registered($v, $u) { 
		if (empty($v)) return ('');	
		$dt = amru_newDateTime($v);
		$text = amr_human_time_diff($dt);  
		return($text);
	}
}

if (!function_exists('ausers_format_user_registration_date')) {  // this one mimics the user_registered for folks who want to see the actual date, not the days ago
	function ausers_format_user_registration_date($v, $u) {  // but not localised
	global $tzobj;
		if (empty($v)) return ('');	
		$dt = new datetime($u->user_registered);
		$dt->setTimeZone($tzobj);
		$text = $dt->format('Y-m-d h:i:s');
		return($text);
	}
}

if (!function_exists('ausers_format_datestring')) { //hmmm should this be a special format option? and standardised then
	function ausers_format_datestring($v) {  // Y-m-d H:i:s  .. old may change
		if (empty($v)) return ('');	
		$ts = strtotime($v);  
		if ($ts < 0) return $v;
		$now = strtotime(current_time('mysql'));
		if ($ts < $now)
			$htd = sprintf( _x('%s ago', 'indicate how long ago something happened','amr-users'), human_time_diff($ts, $now));
		else {
			$htd = sprintf( _x('in %s time', 'indicate how long ago something happened','amr-users'), human_time_diff($ts, $now));
		}
		return ( 
			'<a href="#" title="'.$v.'">'
			.$htd
			.'</a>');
	}
}

if (!function_exists('ausers_format_datetime_ago')) {
	function ausers_format_datetime_ago($datetime) { //expects YYYY-MM-DD hh:mm:ss and returns age in years and months
		if (empty($datetime)) return ('');	
		$dt = amru_newDateTime($datetime);
		$now = amru_newDateTime();
		$text = amr_human_time_diff($dt, $now);
		return $text;
	}
}

if (!function_exists('ausers_format_datetime_datetime')) {  // convert to website timezone
	function ausers_format_datetime_datetime($dt) { // from a string
		global $aopt, $amr_current_list;
		global $tzobj;
		if (empty($dt)) return ('');	
		//expects YYYY-MM-DD hh:mm:ss and fetch the datetime format option	
		if (empty($aopt['list'][$amr_current_list]['formatdatetime'])) {
			return $dt;
		}
		else {
			$format = $aopt['list'][$amr_current_list]['formatdatetime'];
			$text = amru_localised_formatted_datetime ($dt, $format);		
			return $text;
		}
	}
}

if (!function_exists('ausers_format_timestamp')) {
	function ausers_format_timestamp($v) {  
		if (empty($v)) return ('');	
		$d = date('Y-m-d H:i:s', (int) $v) ;
		if (!$d) $d = $v;
		return (	
			'<a href="#" title="'.$d.'">'
			.sprintf( _x('%s ago', 'indicate how long ago something happened','amr-users'),
			human_time_diff($v, current_time('timestamp', true))) // 20191226 because timestanps are always in UTC
			.'</a>');
	}
}

if (!function_exists('ausers_format_timestamp_date')) { //20201027  May also be in amr-users-plus for now
	function ausers_format_timestamp_date($ts) { 
	global $aopt, $amr_current_list; 
		if (empty($aopt['list'][$amr_current_list]['formatdate'])) {
			$text = ausers_format_timestamp_as_date($ts); //if no format use old function
		}
		else {
			if (!is_numeric($ts)) return ($ts);	
			$text = amru_localised_formatted_datetime ("@$ts", 
				$aopt['list'][$amr_current_list]['formatdate']);
		}		
		return $text;
	}
}

if (!function_exists('ausers_format_timestamp_as_date')) {  //NB function used if no format  also default if no format set
	function ausers_format_timestamp_as_date($v) {  
	// in the right timezone
	global $tzobj ;
		if (empty($v)) return ('');	
		if (empty($tzobj)) 
			$tzobj = amr_getset_timezone ();
		// $d = date('Y-m-d H:i:s e', (int) $v) ;
		if (is_numeric($v)) {
			$dt = amru_new_DateTime_nonfatal('@'.$v,$tzobj); //20170124
			//the @ makes it interpret as a Unix timestamp
			//$dt = new datetime('@'.$v);
			//$dt->setTimeZone($tzobj);
			/* optional if you want to use your sites formats	
			$date_format = get_option('date_format'); //wp preloads these
			$time_format = get_option('time_format');	
			*/	
		}
		else 
			return($v);  // we got something that is definitely not a timestamp
		if (!is_object($dt)) 
			$d = $v ;  //if we got bad data - show it - show something anyway
		else {
			$date_format = 	'Y-m-d';
			$time_format = 'H:i'; //'H:i:s'
			$tz = 'P';  //e or T or P
			$d = $dt->format($date_format.' '.$time_format.' '.$tz);
		}
		return ($d);
	}
}

if (!function_exists('ausers_format_phone')) {
	function ausers_format_phone($v) {
		$v2 = str_replace('-','',$v);
		$v2 = str_replace(' ','',$v2);
		return ('<a class="phone"  href="tel:+'.$v2.'" target="_blank" title="'.
		__('Click to call this number now','amr-users').'">'.$v.'</a>');
	}
}

function amr_get_avatar_size_for_list() { //202008
	global $amain,$amr_current_list;
	if (!isset($amain['list_avatar_size'][$amr_current_list])) {
		if (!isset($amain['avatar_size'])) 
			$avatar_size = 16;
		else	
			$avatar_size = $amain['avatar_size'];
	}
	else $avatar_size = $amain['list_avatar_size'][$amr_current_list];
	return($avatar_size);
}

if (!function_exists('ausers_format_email_avatar')) { // shoe email address 202008
	function ausers_format_email_avatar($email, $fld, $user) {
		$avatar_size = amr_get_avatar_size_for_list();
		if (!empty($email))
			return (get_avatar( $email, $avatar_size )); 
		else return ('');	
	}
}

if (!function_exists('ausers_format_avatar')) {  // see also new formats thumbnail, medium for avatar
	function ausers_format_avatar($v, $u) {
		$avatar_size = amr_get_avatar_size_for_list();
		if (!empty($u->user_email))
			return (get_avatar( $u->user_email, $avatar_size )); 
		else return ('');	
	}
}

if (!function_exists('ausers_format_usersettingstime')) {  // why 2 similar - is one old or bbpress ?
	function ausers_format_usersettingstime($v, $u) {  
		if (empty($v)) return ('');
		return(ausers_format_timestamp($v));
	}
}

if (!function_exists('ausers_format_user_settings_time')) {  // why 2 similar
	function ausers_format_user_settings_time($v, $u) {  
		if (empty($v)) return ('');
		return(ausers_format_timestamp($v));
	}
}

if (!function_exists('amr_format_user_cell')) {
function amr_format_user_cell($i, $v, $u, $line='') {  // thefield, the value, the user object
global $aopt, $amr_current_list, $amr_your_prefixes;

	//if (empty($v)) return '';  //20201023  Avatars are empty! do not exclude. new format routines shouldn't all do individual empty check, do once here.
	/* receive the key and the value and format accordingly - wordpress has a similar user function function - should we use that? */
	$title = '';
	$href = '';
	$text = '';  

// have they asked for a link?	
	if (isset ($aopt['list'][$amr_current_list]['links'][$i]) ) {
		$lt = $aopt['list'][$amr_current_list]['links'][$i];
		$href= amr_get_href_link($i, $v, $u, $lt );
		if (!empty($href)) {
		switch ($lt) {  // depending on link type
			case 'mailto': 			$title = __('Email the user','amr-users');
				break;
			case 'edituser': 		$title = __('Edit the user','amr-users');
				break;				
			case 'authorarchive':	$title = __('Go to author archive','amr-users');
				break;
			case 'url': 			$title = __('Go to users website','amr-users');
				break;
			case 'postsbyauthor': 	$title = __('View posts in admin','amr-users');
				break;
			case 'commentsbyauthor': $title = __('See comments by user','amr-users');
				break;
			case 'wplist': 			$title = __('Go to wp userlist filtered by user ','amr-users');
				break;	
			default: 				$title = '';
			}//end switch
		}
	}
	
// do we have a configured format ? use that before trying other options	20170113
	$text = amr_format_field($i, $v, $u);  // returns false if could not do

	if (empty($text)) {  // then we do not have a configured format, try for function or other logic
		//if (WP_DEBUG) echo '<br />No custom format for '.$i.' ';
		// now get the value if special formatting required
		$generic_i = str_replace('-','_',$i); // cannot have function with dashes, so any special function must use underscores

		// strip all prefixes out, will obviously only be one actaully there, but we may have a shared user db, so may have > 1
		foreach ($amr_your_prefixes as $ip=> $tp) {  
			$generic_i = str_replace($tp, '',$generic_i  );
		}
		
		if (function_exists('ausers_format_'.$generic_i) ) { 		
			$text =  (call_user_func('ausers_format_'.$generic_i, $v, $u, $line));
		}
		else { 
		//if (WP_DEBUG) echo '<br />didnt find custom function: ausers_format_'.$generic_i;
			switch ($i) {
				case 'description': {  
					$text = (nl2br($v)); break;
				}
				default: { 
					if (is_array($v)) { 
						$text = implode(', ',$v);
					}
					else $text = $v;
				}
			} // end switch
		}	
	}
	//else if (WP_DEBUG) echo '<br />Found custom format for '.$i.' ';
	
	if (!empty($text)) {  //echo '<br />'.$text;
		if (!empty($href)) {
			if (!empty ($title)) {
				$title = ' title="'.$title.'"';
			}	
			$text = '<a '.$title.' href="'.$href.'" >'.$text.'</a>';
			}
	}
	else {
		
		$text = $v;  //20170131 - return the value if no formatting
	}	
/*	unfortunately - due to fields being in columns and columns being what is cached, 
the before/after formatting is done before cacheing - not ideal, should rather be in final format  
	if (!empty($text)) {
		if (!empty($l['before'][$i]))
			$text = html_entity_decode($l['before'][$i]).$text;
		if (!empty($l['after'][$i]))
			$text = $text.html_entity_decode($l['after'][$i]);
	}
*/	

	//if (WP_DEBUG) echo '<br /> before '.$text;
	if ($text == $v) // if there is no change, ie no formating has been applied, then do the old filters  20201109
		$text = apply_filters('amr_users_format_value', $text, $i, $v, $u, $line); // to allow for other unusual circumstances eg
	//if (WP_DEBUG) echo ' after filters '.$text;
	return(($text));
}
}

if (!function_exists('amr_do_cell')) {
	function amr_do_cell($i, $k, $openbracket,$closebracket) {
		
		return ($openbracket.$i.$closebracket);
	}
}

if (!function_exists('amr_display_message_line')) {
	function amr_display_message_line ($msg, $colspan, $ahtm) {
		$linehtml = $ahtm['td'].' colspan="'.$colspan.'" class="td">'.$msg.$ahtm['tdc']; 
		$html =  $ahtm['tr'].'>'.$linehtml.$ahtm['trc'];
		return ($html);
		
	}
}
//------------------------------------------------------ just one user 
if (!function_exists('amr_display_a_line')) {
	function amr_display_a_line ($line, $icols, $cols, $user, $ahtm) {
	global $amr_odd_even;
	
		if (empty($amr_odd_even)) { 
			$amr_odd_even = 'odd';
		}	
		elseif ($amr_odd_even == 'even') {
			$amr_odd_even = 'odd';
			}
		else {
			$amr_odd_even = 'even';		
		}	
		
		$linehtml = '';
		
		//var_dump($icols);
		foreach ($icols as $ic => $c) { 			
			$w = amr_format_user_cell($c, $line[$c], $user, $line);// add line in case need those values
			if (($c == 'checkbox') )
				$linehtml .= $ahtm['th'].' class="manage-column column-cb check-column th">'.$w. $ahtm['thc'];
			else {
				$linehtml .= $ahtm['td'].
			' data-label="'.$cols[$ic].'" '.
			' class="'.$c.' td td'.$ic.' ">'.$w. $ahtm['tdc']; 
				// no esc_attr here - messes up hyperlinks
			}
		}
		$cssclass = apply_filters('amr-users-css-class-row','vcard '.$amr_odd_even, $line, $user); // allow styling by the values
		$html =  $ahtm['tr'].' class="'.$cssclass.'">'.$linehtml.$ahtm['trc'];
		return ($html);
		
	}
}
//------------------------------------------------------
if (!function_exists('amr_add_data_in_line_to_user_object')) {
	function amr_add_data_in_line_to_user_object($line, $user) {
		if (!is_object($user)) {
			if (current_user_can('Administrator') and WP_DEBUG) {  // 2014 10 - is this always a data bug?
				echo '<br />Message to admin only Error in data or possible bug - user is not a user object: see user dump and line dump'; 
				var_dump($user);	
				var_dump($line);
			}			
		}
		else {
		foreach ($line as $field=> $d) {   // if we have data, add it to the user object in case we need it elsewhere on the line.
			if ( empty($user->$field) and (!empty($d))) 
				$user->$field = $d;
		}
		}
		return $user;
	}
}	
//------------------------------------------------------ just the lines on this page
if (!function_exists('amr_display_a_page')) {
	function amr_display_a_page ($linessaved, $icols, $cols, $ahtm ) {
		
		
		$html = '';
		
		foreach ($linessaved as $il =>$line) { /// have index at this point		
			$id = $line['ID']; /*   always have the id - may not always print it  */
			
			$user = amr_get_userdata($id); 
			
			$user = amr_add_data_in_line_to_user_object ($line,$user); // in case we wnt to use it
			// need this data for links, etc
			// hmmm not sure about this, what if want other values in line?
			// how to accomodate both line data and maybe other user data ?
			
			$html.= amr_display_a_line ($line, $icols, $cols, $user, $ahtm);
		}

		return ($html);
	}
}
//  now prepare for listing

if (!function_exists('amr_display_final_list')) {
	function amr_display_final_list (
		$linessaved, $icols, $cols,
		$page, $rowsperpage, $totalitems,
		$caption,
		$search, $ulist, $c, 
		$filtercol,
		$sortedbynow,
		$options ) { //20191215 remove set to blank array
	global $aopt,
		$amain,
		$amrusers_fieldfiltering,
		$amr_current_list,
		$amr_search_result_count,
		$ahtm;  // the display html structure to use
	global $amr_refreshed_heading;
	
		$amr_current_list = $ulist;	
		
		$html = $hhtml = $fhtml = '';
		$filterhtml 			= '';
		$filterhtml_separate 	= '';	
		$apply_filter_html 		= '';
		$filter_submit_html 	= '';
		$summary 				= '';
		$explain_filter 		= '';
		
		$adminoptions = $amain;
	
		$adminoptions = array (  // forced defaults for admin
		//20191215 used to show anyway in admin, but that is confusing 
				'show_search' 		=> false,
				'show_perpage'		=> false,
				'show_pagination' 	=> false,
				'show_headings'		=> false,
				'show_csv'			=> false,
				'show_xls'			=> false,
				'show_refresh'		=> false,
				'show_totals'		=> false,  // have other total line
				);
				
		//20191215  be consistent between admin & front end		
		foreach ($adminoptions as $opt => $bool) {
				if (!empty ( $amain[$opt][$ulist]))  
					$adminoptions[$opt] = $amain[$opt][$ulist];
				else 
					$adminoptions[$opt] = false;
			}
			
		if (!empty($_REQUEST['headings'])) //or // ie in admin doing headings settings***
			//(!empty($_REQUEST['filtering']))) 
			{
				$adminoptions['show_search'] = false;
				$adminoptions['show_csv'] = false;
				$adminoptions['show_xls'] = false;
				$adminoptions['show_refresh'] = false;
				$adminoptions['show_perpage'] = false;
				$adminoptions['show_headings'] = false;
				$adminoptions['show_totals'] = false;
				$adminoptions['show_pagination'] = true;
				$amain['filter_html_type'][$amr_current_list] = 'none';// if editingheadings, then no showingfilter
			}
			
		if ( ( is_admin() OR (!isset($amain['html_type'][$amr_current_list])) ))  {  
		// must be after the check above, so will force table in admin
			$ahtm = amr_get_html_to_use ('table');
		}
		else {
			$ahtm = amr_get_html_to_use ($amain['html_type'][$amr_current_list]);				
		}	
		
		
		if (empty($linessaved)) { 
			$saveditems = 0;	
			}
		else
			$saveditems = count($linessaved);
		
		if (is_array($caption))
			$caption =  '<h3 class="caption">'.implode(', ',esc_attr($caption)).'</h3>';

// now fix the icols and cols for any special functioning--------------------------
	//	var_dump($icols);	
	//	echo '</br>';
	//	var_dump($cols);	
		if ((isset($icols[0])) and ($icols[0] === 'ID')) {  /* we only saved the ID so that we can access extra info on display - we don't want to always display it */
		// unless we specifically requested?
			unset ($icols[0]);
			unset ($cols[0]);
		}
		
		//20170217 we still need these for situation when someone has chosen to display index and we have alpha navigation
		$icols = array_unique($icols);	// since may end up with two indices, eg if filtering and grouping by same value	
				
		foreach ($icols as $i=> $col) {   
			if (($col == 'index')) {  // we only saved the index so that we can access extra info on display - we don't want to display it , unless we do ;) 
				if (!isset($aopt['list'][$amr_current_list]['selected']['index'])) {
					unset ($icols[$i]);
					unset ($cols[$i]);
				}
			}
			else {
				if (!isset($cols[$i])) unset ($icols[$i]);
			}	
		}
// end fix icols and cols

		// 20150106 grouping fieldlost here				var_dump($icols);

		if (!empty($search)) {
			$searchselectnow = sprintf(
						__('%s Users found.','amr-users')
						,$amr_search_result_count);
			$searchselectnow .=	sprintf(
						__('Searching for "%s" in list','amr-users'),
						$search);
		}  // reset count if searching

		if (isset($amain['sortable'][$amr_current_list]))   
				$sortable = $amain['sortable'][$amr_current_list];
			else
				$sortable = false;
		
		if (!empty($adminoptions['show_headings'])) { //admin always has
				if (is_admin()) {
					if (!empty ($amr_refreshed_heading))
						$summary = $amr_refreshed_heading;
					else
						$summary = $c->get_cache_summary (amr_rptid($ulist)) ;
				}	
				if (!empty($sortedbynow))
					$summary = str_replace ('<li class="sort">',$sortedbynow, $summary  ) ;
				if (!empty($searchselectnow)) {
					$summary = str_replace ('<li class="selected">',
					'<li class="searched">'.$searchselectnow.'</li><li class="selected">',$summary);
				}
				if (!empty($filtercol)) { 
					foreach ($filtercol as $fld => $value) {
						if (is_array($value)) {
							$t[$fld] = implode(', ',$value);
						}
						else $t[$fld] = $value;
					}
					$text = implode(', ',$t);
					$summary =	str_replace (
					'<li class="selected">',
					'<li class="selected">'.__('Selected users with: ','amr-users').esc_attr($text)
					//		'<li class="selected">'.__('Selected users from main list of ',count($linessaved),'amr-users')
					.'</li><li class="selected">',
					$summary);
				}
				
		}		
		if ((!empty($linessaved)) and is_admin() and current_user_can('remove_users')
		and (empty($_REQUEST['filtering']) and (empty($_REQUEST['headings']))) ) {
			$name = 'users';	
						
			array_unshift($icols, 'checkbox');
			array_unshift($cols, '<input type="checkbox">');
			foreach ($linessaved as $il =>$line) {
				if (!empty($line['ID']))
					$linessaved[$il]['checkbox'] =
					'<input '
					//.'class="check-column" '
					.'type="checkbox" value="'.$line['ID'].'" name="'.$name.'[]" />';
				else
					$linessaved[$il]['checkbox'] = '&nbsp;';
			}					
		}
	//
		//$sortedbynow is set if maually resorted
					
		if 	((!isset($amain['html_type'][$amr_current_list]))  or //maybe old ?
			(!isset($amain['filter_html_type'])) or
			((isset($amain['filter_html_type'][$amr_current_list]) and 
			($amain['filter_html_type'][$amr_current_list] == "intableheader")))
			) { 
				
			if (function_exists('amr_show_filters')) {  // for pseudo compatability if unmatched versions
				$filterhtml 			= amr_show_filters ($cols,$icols,$ulist,$filtercol); // will have tr and th		
			}
		}	
		elseif (!empty($amain['filter_html_type'][$amr_current_list]) and $amain['filter_html_type'][$amr_current_list] == "above") { 
			if (function_exists('amr_show_filters_alt')) {			
				$filterhtml_separate 	= amr_show_filters_alt($cols,$icols,$ulist,$filtercol); 						
			}
		}

		//2016 12 07 - add filters for additional filtering
		$filterhtml = apply_filters('amr_users_filter_html',$filterhtml);
		$filterhtml_separate 
			= apply_filters('amr_users_filter_html_separate',$filterhtml_separate);
		
		if (!empty($filterhtml) or (!empty($filterhtml_separate))) { 
				$apply_filter_html = amr_show_apply_filter_button ($ulist);
			}			
		
		$apply_filter_html = apply_filters('amr_users_apply_filter_html',$apply_filter_html);

		if ( !empty($_REQUEST['headings']) and amr_users_can_edit('headings')) {
					$hhtml = amr_allow_update_headings ($cols,$icols, $ulist, $sortable);
		}
		elseif (is_admin() and amr_users_can_edit('filtering')) {	// in admin  and plus function available etc					
					$explain_filter 	= amr_explain_filtering ();
					$hhtml 				= amr_offer_filtering ($cols,$icols,$ulist);
					$filter_submit_html	= amr_manage_filtering_submit(); //will only show if relevant
					}
		else { 
			if (!empty($adminoptions['show_headings'])) 	
				$hhtml = amr_list_headings ($cols,$icols,$ulist,$sortable,$ahtm);	
		}
		
		$fhtml = '';	
	
		if (!empty($linessaved)) {		
			$html .= amr_display_a_page ($linessaved, $icols, $cols, $ahtm );
		}
		else {
			$msgdetail = '';
			if (amr_debug() ) { // only do if debug mode (admin and in debug)
				$msgdetail .= '<br />For admin in debug mode: Search criteria was: '. print_r($search,true);
				$msgdetail .= '<br />For admin in debug mode: Filter criteria were: ';
				foreach ($filtercol as $i=> $f) {
					$msgdetail .= '<br />&nbsp;'.$i.' = '.print_r($f, true);
				}
			}

			if (empty($colspan)) $colspan = 1;
			
			if (!(empty ($search)) or !(empty($filtercol))) // ie we searched for something
			$html .= amr_display_message_line(__('No records found for criteria','amr-users').$msgdetail, $colspan, $ahtm);
	
		}
	//
		if (!empty($adminoptions['show_search']) )
				$sformtext = alist_searchform($ulist);
			else
				$sformtext = '';
	//		
		if (!empty($adminoptions['show_csv']) ) {	
				$csvtext = amr_users_get_csv_link($ulist,'csv');
			}
		else {
				$csvtext = '';				
		}
	//
		if (!empty($adminoptions['show_xls']) ) {	
				$xlstext = amr_users_get_csv_link($ulist,'xls');			
				}
			else
				$xlstext = '';
	//
		if (!empty($adminoptions['show_refresh']) ) {
				$refreshtext = amr_users_get_refresh_link($ulist);
				}
			else
				$refreshtext = '';
	//
			if (!empty($adminoptions['show_perpage']))
				$pformtext = alist_per_pageform($ulist);
			else
				$pformtext = '';
				
			if (!empty($amr_search_result_count)) {
				if ($rowsperpage > $amr_search_result_count)
					$rowsperpage  = $amr_search_result_count;	
				$totalitems = 	$amr_search_result_count;	
			}
			
			if (function_exists ('amr_custom_navigation')) {
				$custom_nav = amr_custom_navigation($ulist);
			}
			else $custom_nav = '';
			
			$moretext = '';
			
			if (!empty($linessaved) and !empty($adminoptions['show_pagination']))  // allows on to just show latest x
				$pagetext = amr_pagetext($page, $totalitems, $rowsperpage);
			else {	
				$pagetext = '';				
			}
			if (!empty($filterhtml) or !empty($hhtml)) 	{
				$hhtml =
					$ahtm['thead'].$filterhtml.$hhtml.$ahtm['theadc'];
			}		
			if (!empty($adminoptions['show_totals'])) { 
				$total_text	= PHP_EOL.'<div id="user_totals">'
				.sprintf(_n('%1s record', '%1s records',$totalitems, 'amr-users'),$totalitems)
				.'</div>';	
			}
			else	$total_text	= '';		
				
							
			$html = amr_manage_headings_submit() //will only show if relevant
				.$filter_submit_html //will only show if relevant
				.$sformtext
				.$explain_filter
				.'<div class="filter-box">' //20191227
				.$filterhtml_separate
				.$apply_filter_html
				.'</div>'
				.$custom_nav
				.$total_text
				.$pagetext
				.PHP_EOL.'<div id="userslist'.$ulist.'" class="userslist"><!-- amr users list-->'.PHP_EOL
				.$ahtm['table']		
				.$caption
				.$hhtml
				.PHP_EOL
				.$ahtm['tbody'].$html.$ahtm['tbodyc']
				.PHP_EOL
				.$fhtml // since wp themes seem to use html5 doctype
				.'<!-- end user list body-->'.PHP_EOL
				.$ahtm['tablec'].'<!-- end user list table-->'.PHP_EOL
				.PHP_EOL.'</div><!-- end user list-->'.PHP_EOL
				.'<div class="userlistfooter">'
				.$pagetext	
				.$csvtext
				.$xlstext
				.$refreshtext
				.$pformtext
				.'</div>';
			if (is_admin() ) 
				$html = PHP_EOL.'<div class="wrap" >'.$html.'</div>'.PHP_EOL;
			$html = $summary.$html;

		return ($html);
	}
}

if (!function_exists('amr_pagetext')) {
function amr_pagetext($thispage=1, $totalitems, $rowsperpage=30){ 
/* echo's paging text based on parameters - */

	$lastpage = ceil($totalitems / $rowsperpage);
	if ($thispage > $lastpage) 
		$thispage = $lastpage;
	$from = (($thispage-1) * $rowsperpage) + 1;
	$to = $from + $rowsperpage-1;
	if ($to > $totalitems) 
		$to = $totalitems;
	$totalpages = ceil($totalitems / $rowsperpage);
	$base = amr_adjust_query_args (); 
	$base = (add_query_arg('listpage','%#%', $base));
	
	$paging_text = paginate_links( array(  /* uses wordpress function */
		'total' 	=> $totalpages,
		'current' 	=> max(1,$thispage),
//				'base' => $base.'%_%', // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
		'base' 		=> @$base,  
		'format' 	=> '',
		'end_size' 	=> 2,
		'mid_size' 	=> 2,
		'add_args' 	=> false
	) );
	if ( $paging_text ) {
		$paging_text = PHP_EOL.
			'<div class="tablenav">'.PHP_EOL
			.'<div class="tablenav-pages">'
			.sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s','amr-users' ) . '</span>&nbsp;%s',
			number_format_i18n( $from ),
			number_format_i18n( $to ),
			number_format_i18n( $totalitems ),
			$paging_text
			.'</div>'.PHP_EOL
			.'</div>'
		);
	}
	return($paging_text);		
}
}

?>