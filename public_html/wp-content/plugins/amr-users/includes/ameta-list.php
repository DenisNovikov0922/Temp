<?php
include ('amr-users-csv.php');

function amr_rows_per_page($rpp){  //check if rows_per_page were requested or changed, set default if nothing passed
	if (!empty($_REQUEST['rows_per_page'])) {

		return ((int) ($_REQUEST['rows_per_page']));
	}
	else {
		if (!empty($rpp)) 
			return($rpp);
		else return(50);
	}
}
 
function amr_count_user_posts($userid, $post_type) {  // wordpress function does not allow for custom post types
    global $wpdb;
	if (!post_type_exists( $post_type )) 
		return (false);
    $where = get_posts_by_author_sql($post_type, true, $userid);

    $count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );

    return apply_filters('get_usernumposts', $count, $userid);
	}
 
function amr_allow_count () { //used to allow the counting function to cost posts
	return ('read_private_posts'); //will allows us to count the taxonmies then
}
 
function amr_need_the_field($ulist,$field) {
global $aopt;
	$l = $aopt['list'][$ulist]; /* *get the config */

	if ((isset ($l['selected'][$field])) or
	   (isset ($l['included'][$field])) or
	   (isset ($l['excluded'][$field])) or
	   (isset ($l['includeonlyifblank'][$field])) or
	   (isset ($l['excludeifblank'][$field])) or
	   (isset ($l['sortby'][$field])) or 
	   ($field == 'ID') or // always need the id
	   ($field == 'index') or
	   (isset ($l['grouping'][1]) and ($l['grouping'][1] == $field)) 
	)
	{		
		return  true;
	}
	else
	return false;
}
 
function amr_rptid ($ulist) {
	if ($ulist < 10) $rptid = 'user-0'.$ulist;
	else $rptid = 'user-'.$ulist;
	return $rptid;
}

function amr_check_use_transient ($ulist, $options) {  // may no longer need if removing trasient html 20170906
global $post;

	if (is_admin() ) return false;
	if (!amr_first_showing ())  // can only  use transient if no dynamic filters, no search, no sort, nothing special happening
		return false;

	if (!empty($post) ) {//we are in a page, BUT might be widget
		if (!empty($options['widget'])) // not in our widget anyway
			return ($ulist); // just use the ulist for the transient
		else 
			return ($ulist.'-'.$post->ID);  // since we can now pass shortcode parameters, the html can differ per page for same list
	}		
	return ($ulist);
}

function amr_first_showing() { // use GET not REQUEST to be sure it is dynamic input ? 
	if ((!isset($_REQUEST['filter'])) and
		(!isset($_REQUEST['su'])) and
		(!isset($_REQUEST['clear_filtering'])) and
		(!isset($_REQUEST['listpage'])) and
		(!isset($_REQUEST['rows_per_page'])) and
		(!isset($_REQUEST['refresh'] ))and
		(!isset($_REQUEST['dir'])) and
		(!isset($_REQUEST['sort'])) 
		and
		(!isset($_GET['filter'])) and
		(!isset($_GET['su'])) and
		(!isset($_GET['clear_filtering'])) and
		(!isset($_GET['listpage'])) and
		(!isset($_GET['rows_per_page'])) and
		(!isset($_GET['refresh'] ))and
		(!isset($_GET['dir'])) and
		(!isset($_GET['sort'])) 
	)
	return true;  // ie then we can try using the transient
	else {
		return false;
	}
}

function amr_undo_csv (&$value) {  // for front end display
// for historical reasons, cached data stored in csv format
// until this is revised, we need to uncsv some values
// eg those with double double quotes
	$value = str_replace('""', '"', $value);
}
 
function amr_get_lines_to_array (
	$c, 
	$rptid, 
	$start, 
	$rows, 
	$icols, /* the controlling array */
	$shuffle=false
	) {
	
	if (!empty($_REQUEST['su'])) {		// check for search request
		$s = filter_var ($_REQUEST['su'], FILTER_SANITIZE_STRING );
		$lines = $c->search_cache_report_lines ($rptid, $start, $rows, $s, $shuffle);
		//$amr_search_result_count = count($lines);
	}
	else { 
		$lines = $c->get_cache_report_lines ($rptid, $start, $rows, $shuffle );
	}

	if (!($lines>0)) {// amr_flag_error($c->get_error('norecords'));	//20170531 do better message later as line in html
	return (false);	}  
	
	foreach ($lines as $il =>$l) {
		if (!defined('str_getcsv')) {
	
			$lineitems = amr_str_getcsv( ($l['csvcontent']), '","','"','\\'); /* break the line into the cells */
			
		}	
		else {
			$lineitems = str_getcsv( $l['csvcontent'], ',','"','\\'); /* break the line into the cells */
		}
		array_walk ($lineitems,'amr_undo_csv');
		
		$linehtml = '';
		
		$linessaved[$il] = amr_convert_indices ($lineitems, $icols);
		
	}
	unset($lines);	
	
	return ($linessaved);
}
 
function amr_convert_indices ($lineitems, $icols) {

		//if (WP_DEBUG) {echo '<br/>before convert csv:<br />'; var_dump($icols);}

		foreach ($icols as $ic => $c) { 
			/* use the icols as our controlling array, so that we have the internal field names */
		
			if (isset($lineitems[$ic])) {
				$w = $lineitems[$ic];
			}
			else $w = '';
			$line[$c] = stripslashes($w);
		}
		return ($line);

}
 
function amr_check_for_sort_request ($list, $cols=null) {
/* check for any sort request and then sort our cache by those requests */
	$dir=SORT_ASC;
	if ((!empty($_REQUEST['dir'])) and ($_REQUEST['dir'] === 'SORT_DESC' ))  
		$dir=SORT_DESC;
	//20111214
/*	if (!empty($_REQUEST['lastsort'])) { 
		$lastsort = esc_attr($_REQUEST['lastsort']); 
	}
	else 
		$lastsort = 'ID';
	
	if (!empty($_REQUEST['lastdir'])) { 
		$lastdir = esc_attr($_REQUEST['lastdir']); 
	}
	else 
		$lastdir = $dir; //SORT_ASC; // 20171104
	*/
	//..20111214
	if (!empty($_REQUEST['sort'])) {
		//$cols = array($_REQUEST['sort'] => array($dir), 'ID' => array($dir) );   20111214
		$cols = array($_REQUEST['sort'] => $dir /*, $lastsort => $lastdir */);
		$list = auser_multisort($list, $cols );
		return($list);
	}
	else return($list);
}
 
function alist_one_widget ($type='user', $i=1, $do_headings=false, $do_csv=false, $max=10){
/* a widget version of alist one*/
	/* Get the fields to use for the chosen list type */
global $aopt;
global $amain;

	$c = new adb_cache();
	$rptid = $c->reportid($i, $type);

		$lines = $c->get_column_headings ($rptid); /* get the internal heading names  for internal plugin use only */  /* get the user defined heading names */
		$line = $lines['0'];
		
		if (!defined('str_getcsv')) 
			$icols = amr_str_getcsv( $line[0]['csvcontent'], ',','"','\\');
		else 
			$icols = str_getcsv( $line[0]['csvcontent'], ',','"','\\');
				
//		if (!defined('str_getcsv')) $cols = amr_str_getcsv( $line[1]['csvcontent'], '","','"','\\');
//		else $cols = str_getcsv( $line[1]['csvcontent'], ',','"','\\');

		foreach ($icols as $ic => $cv) { /* use the icols as our controlling array, so that we have the internal field names */
				$v = $cols[$ic];

				$html .= '<th>'.$v.'</th>';
			}
		$hhtml = '<thead><tr>'.$html.'</tr></thead>'; /* setup the html for the table headings */
		$fhtml = '<tfoot><tr>'.$html.'</tr>'

		.'</tfoot>'; /* setup the html for the table headings */

		$html='';
		$totalitems = $c->get_cache_totallines($rptid);
		$lines = $c->get_cache_report_lines ($rptid, $start+1, $max );


		if (!($lines>0)) {
			amr_flag_error($c->get_error('numoflists'));
			return (false);
		}
		foreach ($lines as $il => $lineitems) {
			//if (WP_DEBUG) var_dump($lineitems);
			$id = $lineitems[0]; /*  *** pop the first one - this should always be the id */

			$user = amr_get_userdata($id);
			unset($linehtml);
			foreach ($icols as $ic => $c) { /* use the icols as our controlling array, so that we have the internal field names */
			
				$v = $lineitems[$ic];
				$linehtml .= '<td>'.amr_format_user_cell($c, $v, $user, $l). '</td>';
			}
			$html .=  PHP_EOL.'<tr>'.$linehtml.'</tr>';
		}

		$html = '<table>'.$hhtml.$fhtml.'<tbody>'.$html.'</tbody></table>';

	return ($html);
}
 
function amr_handle_the_doubleescaped_ulist () {
	// can't do add_query_arg for submenus when adding lists to user menu - sigh
	// and somehow it gets double escaped and so we have to check for ulist manually

	if (isset($_REQUEST['page']))  { /*  somehow needs to be ? instead of & in wordpress admin, so we don't get as separate  */
		$param = 'ulist%3D';
		if (stristr( $_REQUEST['page'], $param))	{			
			$l = substr (stristr( $_REQUEST['page'], $param), strlen($param));
			//echo '<br />we gotescaped =?'.$param.' l='.$l;
			}
		else {
			$param = 'ulist=';
			if (stristr( $_REQUEST['page'], $param)) {
				$l = substr (stristr( $_REQUEST['page'], $param), strlen($param));
				//echo '<br />we got =?'.$l;			
			}
			else {
				//var_dump($_REQUEST);
			}
		}
	}
	return $l;
}
	
function amr_list_user_meta(){   /* Echos out the paginated version of the requested list */
global $aopt;
global $amain;
global $amr_nicenames;
global $thiscache;

	ameta_options();
	if (!isset ($aopt['list'])) {
		_e ("No lists Defined", 'amr-users');
		return false;
		}

	$l = amr_handle_the_doubleescaped_ulist ();
	
	$thiscache = new adb_cache();  // nlr?

	amr_list_user_admin_headings($l);	// will only do if in_admin

	echo ausers_form_start();
	
	if (empty($_REQUEST['filtering']) and (empty($_REQUEST['headings'])) and empty ($_REQUEST['csvsubset'])) 
		ausers_bulk_actions();	// will check capabilities

	echo alist_one('user',$l, array());  /* list the user list with the explanatory headings */

	if (empty($_REQUEST['filtering']) and (empty($_REQUEST['headings'])) and empty ($_REQUEST['csvsubset']))  
		ausers_bulk_actions(); // will check capabilities
		
	echo ausers_form_end();

	return;
}
 
function amr_try_build_cache_now ($c, $i, $rptid) { // the cache object, the report id, the list number
global $amain;
		if ($c->cache_in_progress($rptid)) {
			echo ( '<div style="clear:both;"><strong>'.$amain['names'][$i].' ('.$rptid.') '.$c->get_error('inprogress').'</strong></div>');
			return (false);
		}
		else {
			return amr_build_user_data_maybe_cache($i);
			return true;
		}
}
 
function amr_does_filter_match_data_array ($filtervalue, $data_arr) { 
		if (is_array($filtervalue)) {	
			foreach ($data_arr as $data) { // only need 1 match for truth
				if (in_array($data, $filtervalue)) 
					return true;
			}
			return false;
		}
		else { // filtervalue not an array
			if (!in_array($filtervalue, $data_arr)) 
				return false;
			else return true;	
		}
}
 
function amr_does_filter_match ($filtervalue, $datavalue) {
// datavalue might be a comma separated set of multiple values
// data value may or may not have &amps; ???
// filtervalue might be an array, and might be '-' for blanks, or '*' for all - these should be weeded out earlier

	// if asking for blanks and a value, then if blank it's a yes
	if (empty($datavalue) and (is_array($filtervalue) and in_array('-',$filtervalue)))
		return true;
		
	if (stristr($datavalue, ', ')) {  //might be a csv string
		$data_arr = explode (', ',$datavalue);   
		return (amr_does_filter_match_data_array ($filtervalue, $data_arr));	
	}
	elseif (stristr($datavalue, ',')) {  //might be a csv string
		$data_arr = explode (',',$datavalue);
		return (amr_does_filter_match_data_array ($filtervalue, $data_arr));	
	}
	else { // datavalue is not array
		if (is_array($filtervalue)) {
			if (!in_array($datavalue,$filtervalue)) return false;  // array of filter values 20141121
		}
		else {  // 2014 12 07 - handle ampersdands that may or may not be in data
			if ($filtervalue == ($datavalue))  return true;
			if ($filtervalue == html_entity_decode($datavalue)) return true;
			return false;
			}
	}
	
	return true;

}
 
/**
 * Remove slashes from strings, arrays and objects
 * 
 * @param    mixed   input data
 * @return   mixed   cleaned input data
 */
function amr_stripslashesFull($input) {
    if (is_array($input)) {
        $input = array_map('amr_stripslashesFull', $input);
    } elseif (is_object($input)) {
        $vars = get_object_vars($input);
        foreach ($vars as $k=>$v) {
            $input->{$k} = amr_stripslashesFull($v);
        }
    } else {
        $input = stripslashes($input);
    }
    return $input;
}

function alist_one($type='user', $ulist ,$options) {

//options  can be headings, csv, show_search, show_perpage
	/* Get the fields to use for the chosen list type */
global $aopt,
	$amain,
	$amrusers_fieldfiltering,
	$amr_current_list,
	$amr_search_result_count;
global $amr_refreshed_heading, $totalitems; 	

	if (empty ($ulist) or empty($aopt)) {  // use first list?
		$ulist = key($aopt['list']);
	}
/*	else {
		printf(__('No such list: %s','amr-users'),$ulist); 
		$ulist = amr_debug_no_such_list ();   // issue debug messages and use first list found 
		
	}
*/	
	$l = $aopt['list'][$ulist]; /* *get the config */
	
	do_action('amr-add-criteria-to-list', $ulist);   
	// allows one to force criteria into the request field for example (eg: show only logged in user)
	
/*	20170906 - remove transient html
	$transient_suffix = amr_check_use_transient ($ulist, $options) ;
	if ($transient_suffix) { // no filters, no search, no sort, nothing special happening
		//if (WP_DEBUG) echo '<br />using transient: '.$transient_suffix.'<br />';
		$html = get_transient('amr-users-html-for-list-'.$transient_suffix);
		if (!empty($html)) {
			if (current_user_can('administrator')) {
				echo '<br /><a href="'.esc_url(add_query_arg('refresh','1')).'" title="'
				.__('Note to logged in admin only: Now using temporary saved html (transient) for frontend.  Click to refresh.','amr-users').'">!</a>';
			}
			return( $html);
		}	
	}
	*/
	
	$caption 	= '';
	$sortedbynow = '';
	
	if (empty($amain['list_rows_per_page'][$ulist]))  
		$amain['list_rows_per_page'][$ulist] = $amain['rows_per_page'];
		
	$rowsperpage = amr_rows_per_page($amain['list_rows_per_page'][$ulist]); // will check request

//  use $options as our 'request' input so shortcode parameters will work.
// allow _REQUEST to override $options

	/*$request_override_allowed = array(
		'filter',
		'fieldvaluefilter',
		'fieldnamefilter',
		'sort'); */

	
// figure out what we are doing - searching, filtering -------------------------------------------------------

	$search = '';	
	if (isset($_REQUEST['clear_filtering'])) { 	// we do not need these then
		unset($_REQUEST['fieldnamefilter']);
		unset($_REQUEST['fieldvaluefilter']);
		unset($_REQUEST['filter']);
		unset($_REQUEST['su']);
		//do we need to unset the individual cols? maybe not

		foreach ($_REQUEST as $i => $r) {  
			if (strpos($i,'_from')  or (strpos($i,'_to'))) {
				unset ($_REQUEST[$i]);
			}
		}
	}
	else {

		if (!empty($_REQUEST['su'])) { 
			//if (WP_DEBUG) echo '<br />We got search too:'.$_REQUEST['su'];
			$search = strip_tags ($_REQUEST['su']);
		}	
		
		foreach ($_REQUEST as $param => $value) { // we do not know the column names, so just transfer all?
		// some might be an array
			//skip some obvious ones
			if (empty($value)) continue;
			if (in_array( $param,
				array('page','action','action2','amr-meta','_wp_http_referer', 'dobulk'))) 
				continue;
				
			if (is_array($value)) {
				foreach ($value as $i => $val) {
				
					if (!(is_array($val))) 
						$options[$param][$i] = htmlspecialchars_decode(sanitize_text_field($val));
					//else just ignore nested array in $_REQUEST experienced by someone using WPSEO 20180614

				}
			}	
			else {
				$options[$param] = htmlspecialchars_decode(sanitize_text_field($value));

				}

		}	
	}	
	
	$amrusers_fieldfiltering = false;
	if (!empty($options['filter'])) { 
		//if (amr_debug()) {echo '<h1>Filtering</h1>';}
		foreach (array('fieldnamefilter', 'fieldvaluefilter') as $i=> $filtertype) {
			
			if (isset($options[$filtertype])) { 
			 //if (WP_DEBUG) {echo '<br />doing: '.$filtertype; var_dump($options);}
				foreach ($options[$filtertype] as $i => $col) {
					if (empty($options[$col])) {//ie showing all
						unset($options[$filtertype][$i]);
						unset($options[$col]);
					}
					else $amrusers_fieldfiltering = true;  // set as we are maybe doing realtime filtering flag
				};
			}
		}	
	}
	
	$c = new adb_cache();
	$rptid = $c->reportid($ulist, $type);

	if ($amrusers_fieldfiltering) {
		$lines = amr_build_user_data_maybe_cache($ulist); // since we are filtering, we will run realtime, but not save, else we would lose the normal report
	
		if (empty($lines)) return;
		$totalitems = count($lines);
		//if (WP_DEBUG) echo '<br /> field filtering & $totalitems='.$totalitems;
	}
	else { 
		if ((!($c->cache_exists($rptid))) or (isset($options['refresh']))) {
			//if (amr_debug()) _e('If debug only: Either refresh requested OR no cache exists.  A rebuild will be initiated .... ','amr-users');
			$success = amr_try_build_cache_now ($c, $ulist, $rptid) ;
			//$lines = amr_build_user_data_maybe_cache($ulist);  
			$totalitems = $c->get_cache_totallines($rptid);
			//now need the lines, but first, paging check will tell us how many
			$amrusers_fieldfiltering = false; // already done if it must be
		}
		else {
			$totalitems = $c->get_cache_totallines($rptid);
			
		}
	}
	
	//---------- setup paging variables
	if ($totalitems < 1) {
			$html = __('No lines found.','amr-users').amr_users_get_refresh_link($ulist);  // return don't echo 20191225
			return $html;
	}
	if ($rowsperpage > $totalitems)
		$rowsperpage  = $totalitems;

	$lastpage = ceil($totalitems / $rowsperpage);
	
	if (!empty ($_REQUEST['listpage'])) // if we requested a page MUST use that
		$page = (int) $_REQUEST['listpage'];	
	else { // is a random page stipulated ?
		if (isset($options['show_randompage'])) { // allows a random page
			$page = rand (1, $lastpage);
		}
		else {// else.....start at the very beginning, a very good place to start...
			$page=1;
		}
	}		
	if ($page > $lastpage) 
		$page = $lastpage;
	if ($page == 1)
		$start = 0;
	else
		$start = (($page - 1) * $rowsperpage);
	
	$shuffle = false;
	if (!empty($options['shuffle'])) {
		$shuffle = true;
	}
	$filtercol = array();
	
// 		get the data
		if (!$amrusers_fieldfiltering) { // because already have lines if were doing field level filtering	
			$headinglines = $c->get_column_headings ($rptid); /* get the internal heading names  for internal plugin use only */  /* get the user defined heading names */

			if (!defined('str_getcsv'))
				$icols = amr_str_getcsv( ($headinglines[0]['csvcontent']), ',','"','\\');
			else
				$icols = str_getcsv( $headinglines[0]['csvcontent'], ',','"','\\');
			
			if (!defined('str_getcsv'))
				$cols = amr_str_getcsv( $headinglines[1]['csvcontent'], '","','"','\\');
			else
				$cols = str_getcsv( $headinglines[1]['csvcontent'], ',','"','\\');

			//var_dump($icols);
		// 20170201	
			//$icols = array_unique($icols);	//since may end up with two indices, eg if filtering and grouping by same value	
			// but loses the id if is being shown, so do the count comparision below, if cols has more, then they are the extra indices on the end.
			//20170217 BUT WE still need them ? so why did we do this?
			$ic = count($icols);
			$cc = count($cols);
			if ($cc < $ic) {  // we have indices in icols
				//$icols = array_slice ($icols, 0, $cc);
			}
			
			$fetch_amount = $rowsperpage;  // default
			if (isset($options['filter']) 
			or (!empty($options['sort'])) 
			or !empty($_REQUEST['su']) 
			or isset($options['csvsubset']))  //20140718 - add csvsubset
				$fetch_amount = 0; //fetch all
			
// if  at initial display not searching or filtering
			if (isset($options['start_empty']) and empty($options['su']) and empty($options['filter']) and empty($options['sort'])) {
					$lines = array();
					$totalitems = 0;
				}
			else	{
			
					$lines = amr_get_lines_to_array(   // also does search 
						$c, 
						$rptid, 
						$start, 
						$fetch_amount, 
						$icols,
						$shuffle );
					
			}			

		}
		else {  // we are field filtering
			unset ($lines[0]); // the tech lines and the headings line
			unset ($lines[1]);
			
			$totalitems = count($lines); // must be here, only reset for field filtering
			$s = $l['selected'];
			asort ($s); /* get the selected fields in the display  order requested */
			$cols 	= amr_build_col_headings($s);
			$icols 	= amr_build_cols ($s);
			
			foreach ($lines as $i => $j) {
				$lines[$i] = amr_convert_indices ($j, $icols);
			}
		}
		

		// 	display time filter check

		if ((!empty($lines)) and (isset($options['filter']) or isset ($options['csvsubset']))) {
		// then we are filtering
			// check for date range too - where
			foreach ($icols as $cindex => $col) {
				if (!empty ($options[$col]) ) { 
					if ((!(isset ($options['fieldnamefilter']) and in_array($col, $options['fieldnamefilter']))) and
					   (!(isset ($options['fieldvaluefilter']) and in_array($col, $options['fieldvaluefilter'])))) {
						$filtercol[$col] = amr_stripslashesFull($options[$col]);  
						// 20140419 take out esc_attr
						// 2014 12 08 add stripslahes to force apostrophe s to match data 					
					}
				}
				else {
					if ( !empty($options[$col.'_fromdate']) ) {// has date range
						$from[$col] = $options[$col.'_fromdate'];
					}
					elseif ( !empty($options[$col.'_fromunix']) ) {// has date range
						$from[$col] = strtotime($options[$col.'_fromunix']);
					}
					elseif ( !empty($options[$col.'_from']) ) {// has date range
						$from[$col] = $options[$col.'_from'];
					}	
					
					if ( !empty($options[$col.'_todate']) ) {
						$to[$col] = $options[$col.'_todate'];
					}
					elseif ( !empty($options[$col.'_tounix']) ) {
						$to[$col] = strtotime($options[$col.'_tounix']);
					}	
					elseif ( !empty($options[$col.'_to']) ) {
						$to[$col] = $options[$col.'_to'];
					}						
				}			
			}
			
			if (!empty($options['index'])) {
				$filtercol['index'] = strip_tags($options['index']);
			}
			
			if (!empty($filtercol)) { // for each of the filter columns that are not field filters
				foreach ($filtercol as $fcol => $value) {
					if ((is_array($value) 
					and in_array('*',$value) 
					and in_array('-',$value) )) { //stupid, but show all, so don't use this filter
						// 201710 do nothing? 
						continue; //we want all
					}
					else {

					foreach ($lines as $i=> $line) {
						
						if ((is_array($value) and in_array('*',$value))
							or ($value == '*')) { 	// 201710 looking for all with values							
								if (empty($line[$fcol]) ) {// don't want if empty
									unset ($lines[$i]);
									continue;
									// move on 
								}	
								else { // we want it
									continue;
								}
							}
						elseif (!is_array($value))
							if ($value == '-') { // 201710 we only looking for all blanks										
								if (!empty($line[$fcol]) ) {
									unset ($lines[$i]); 
									continue;
								}	
								else { // leave it for the next layer check 
									continue;
								}
							}	
							else {// leave it for the next layer check 	
							}
						else { // it is array 
							
								if (!in_array('-', $value)) { // if dont want blanks
									if (empty($line[$fcol]) ) 	{ //we want something
										unset ($lines[$i]);
										continue;
									}
									// else drop down to next check
								}
								//else next layer // we do want blanks too
							}
						//continue checking if haven't 'coninued' out!
						if ($fcol == 'ID') { // id can have  filtering  - allows link to profile page 
								if (!($line[$fcol] == $value) ) {/// amr ??
									unset ($lines[$i]); 
								}
						}
						else { 
								if (!amr_does_filter_match ($value, $line[$fcol]) ) {
								// 20140305 - GET RID OF Fuzzy matching, testted both comma separated and comma space separated - working!!
								//$instring = strpos($line[$fcol],$value ); 
								// fuzzy filtering - hmm why - maybe not???
								// *** definitely NOT - number values overmatch then 
								// eg: filtering 4, then 41, 24 matches
								// BUT can we just explode from commas? 
								// how do we know if we should explode or not?
								// will be better when we are using query mode down the track
								// is fuzzy filter to avoid situation where value may have spaces before/after ???
								// used strstr before, but strpos faster
								//if ($instring === false) { // note strpos may return 0 if in front of string
									unset ($lines[$i]);
								}					
						}
						//else if (!($line[$fcol] == $value)) {  strisstr will catch these ?
						//}
						if ((!empty ($options['filter']) and $options['filter'] == 'hide') ) {  
							unset($lines[$i][$fcol]);
						}
					} // if hiding, delete that column
					if (!empty ($options['filter']) and ($options['filter'] == 'hide') ) {
						foreach ($icols as $cindex=> $col) {
							
							if ($fcol == $col) {
								unset ($icols[$cindex]);
								unset ($cols[$cindex]);
							}
						}
					} // end delete col
					//if (amr_debug()) echo '<br />* Records found that meet criteria: '.count($lines);
					}
				}
				// since we filtered, if also trying to search then do search here.
				// cannot do at initial query like plain search because pagination gets messed
			} //20161207
//-----------------------------------------------------------------------------
			if (!empty($from)) { // for each of the filter columns that are not field filters
				foreach ($from as $col => $value) {
					foreach ($lines as $i=> $line) {					
						if (empty($line[$col])) unset ($lines[$i]); 
						elseif ($line[$col] < $value) {
							unset ($lines[$i]); //echo ' Less than from so unset';
						}
						//else echo ' Not Less than from so keep';
					}
				}	
			}
			
			if (!empty($to)) { // for each of the filter columns that are not field filters
				foreach ($to as $col => $value) {
					foreach ($lines as $i=> $line) {			
						if (empty($line[$col])) unset ($lines[$i]); 
						elseif ($line[$col] > $value) {
							unset ($lines[$i]); //echo ' Greater than to, so unset';
						}
						//else echo ' Not greater than to so keep';
					}
				}	
			}
		
			$lines = apply_filters('amr_add_custom_filter',$lines);  //2016 12 06

			$amr_search_result_count = count($lines);  // of filtering		
			$totalitems = $amr_search_result_count;
			
			// slice the right section of the returned values based on rowsperpage and currentpage
			// update the paging variables
			
			if (($amr_search_result_count > 0) and ($rowsperpage > $amr_search_result_count))
				$rowsperpage  = $amr_search_result_count;

			$lastpage = ceil($amr_search_result_count / $rowsperpage);
			if ($page > $lastpage)
				$page = $lastpage;
			if ($page == 1) {
				$start = 0;
			}	
			else {
				$start = (($page - 1) * $rowsperpage);					
				}
			//if (WP_DEBUG) echo '<br />count lines = '.$amr_search_result_count. ' start='.$start. ' rowspp='. $rowsperpage;	
		// 20161207}


		}  //end if

//------------------------------------------------------------------------------	 check for sort 
		if (!empty($options['sort'])) {	
			//if (WP_DEBUG) {echo '<br/> before sort start:'.$start.' rows pp:'.$rowsperpage.' '.count($lines);}
			if ($lines) { 
				$linesunsorted = amr_check_for_sort_request ($lines);
				$linesunsorted = array_values($linesunsorted); /* reindex as our indexing is stuffed and splice will not work properly */
				//if (!empty($search)) 
					$totalitems = count($linesunsorted);	//save total here before splice
				$lines = $linesunsorted;
				//$lines = array_splice($linesunsorted, $start, $rowsperpage, true );
				unset($linesunsorted); // free up memory?
				//if (WP_DEBUG) {echo '<br/> after sort :'.$rowsperpage.' '.count($lines);}
				/* now fix the cache headings*/
				$sortedbynow = '';
				if (!empty($options['sort'])) {
					foreach ($icols as $i=>$t) {
						if ($t == $options['sort'])
							$sortedbynow = strip_tags($cols[$i]) ;
					}
					$sortedbynow = '<li><em>'
						.__('Sorted by:','amr-users').'</em>'.$sortedbynow.'</li><li class="sort">';
				}
				
			}
		}

	// do csv filter here ?

		if (!empty($_REQUEST['csvsubset']) and (!empty($amain['show_csv'][$ulist]) or !empty($amain['show_xls'][$ulist] ))) {
			// to avoid redoing if widget on same page
			$csvlines = amr_csvlines_to_csvbatch($lines, $ulist);
			$html = amr_lines_to_csv($csvlines, $ulist, true, false,'csv','"',',',chr(13).chr(10) ); //*** need form?
			return $html;
		}
		else {	
				// if clean request, we may have the right number lines already - do not reslice
			if ((!empty($lines)) and ($rowsperpage < count($lines)))  //** 2018 (change OR to AND) do we need this? should it not be right before 
				$lines = array_slice($lines, $start, $rowsperpage, true);	
		}
			
	// finished filtering and sorting

		//var_dump($ulist);
		$html = amr_display_final_list (
			$lines, $icols, $cols,
			$page, $rowsperpage, $totalitems,
			$caption,
			$search, $ulist, $c, $filtercol,
			$sortedbynow, 
			$options);
	
/*	20170906 remove transient html
		if ($transient_suffix) { // ie no filters, no search, no sort, nothing special happening
			$expiration = (empty($amain['transient_expiration']) ? 60 : $amain['transient_expiration']);	//allow setting later
			set_transient('amr-users-html-for-list-'.$transient_suffix, $html ,$expiration );
			//track_progress('Transient set for html for list '.$transient_suffix);
		}
*/
				
		return $html;
}
