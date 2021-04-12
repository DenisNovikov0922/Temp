<?php
function amr_copy_list ($old, $copy) { //20201015
//copy an existing list to a new list IF new list number does not exist already
	global $amain, $aopt;
	
	if (!empty($amain['names'][$copy])) {
		echo 'List '.$copy.' already exists as'. $amain['names'][$copy]; 
		return;
	}
	
	$amain['no-lists'] = count($amain['names']);
	ausers_update_option ('amr-users-main', $amain);	

	$optname = 'amr-users-main';
	$option = ausers_get_option($optname);
	foreach ($option as $setting => $lists) {
		if (is_array($lists)) { 	
			if (isset($option[$setting][$old])) {
				$option[$setting][$copy] = $option[$setting][$old];
			}
			ksort($option[$setting]); 
		}
	}
	$option['names'][$copy] .=  __(' - copy','amr-users');
	
	$result = ausers_update_option($optname, $option);
	
	$optname = 'amr-users';
	$option = ausers_get_option($optname); 
	$option['list'][$copy] = $option['list'][$old];
	ausers_update_option($optname, $option);

	$optname = 'amr-users-filtering';
	$option = ausers_get_option($optname);
	if (isset($option[$old])) {
		$option[$copy] = $option[$old];
		ausers_update_option($optname, $option);
	}	
	
	$optname = 'amr-users-custom-headings';
	$option = ausers_get_option($optname);
	if (isset($option[$old])) {
		$option[$copy] = $option[$old];
		ausers_update_option($optname, $option);
	}
}
	
function amr_handle_copy_delete () {	
	global $amain, $aopt;
	if (!current_user_can('administrator')) {
		_e('Inadequate access','amr-users');
		return;
	}
	if (isset($_GET['copylist'])) {  	
		$source = (int) $_REQUEST['copylist'];
		if (!isset($amain['names'][$source])) echo 'Error copying list '.$source; 
		$next = 1;  // get the current max index
		foreach($amain['names'] as $j=>$name) { 
			$next = max($next,$j);
		}
		$next = $next +1;
		//
		amr_copy_list ($source, $next);
		$amain = ausers_get_option ('amr-users-main');		
	}
	elseif (isset($_GET['deletelist'])) { // we not deleteing all but so long as main is gone, when they click update on other settings,the other bits will disappear
		$source = (int) $_REQUEST['deletelist'];
		
		if (!isset($amain['names'][$source])) {
			amr_users_message ( sprintf(__('Error deleting list %S','amr-users'),$source)); 
		}
		else {
			foreach($amain as $j=>$setting) {
				if (is_array($setting)) { 
					//if (WP_DEBUG) echo '<br />deleting '.$j.' from list '.$source;
					if (isset($amain[$j][$source]) ) 
						unset ($amain[$j][$source]);
				}
			}
		}

		$amain['no-lists'] = count($amain['names']);
		if (!empty($aopt['list'][$source]) ) { 		
			unset($aopt['list'][$source]);
		}
		$acache = new adb_cache();
		$acache->clear_cache ($acache->reportid($source) );
		ausers_update_option ('amr-users-main', $amain);
		ausers_update_option ('amr-users', $aopt); 
		amr_users_message(__('List and the cache deleted.','amr-users'));
	}
}	

function amrmeta_validate_overview()	{ 
	global $amain;
	global $aopt;

	if (isset($_REQUEST['addnew'])) {  		
		if ((count ($amain['names'])) < 1)
			$amain['names'][1] = __('New list','amr-users');
		else 
			$amain['names'][] = __('New list','amr-users');
		$amain['no-lists'] = count ($amain['names']);
	}		

	if (isset($_POST['name'])) {
		$return = amrmeta_validate_names();
		if ( is_wp_error($return) )	echo $return->get_error_message();
	}

	if (isset($_POST['checkedpublic'])) { /* admin has seen the message and navigated to the settings screen and saved */
		$amain['checkedpublic'] = true;
	}
//	unset($amain['public']);	
//	unset($amain['sortable']);
//	unset($amain['customnav']);

	//
	if (isset($_POST['list_avatar_size'])) {	
		if (is_array($_POST['list_avatar_size']))  {
			foreach ($_POST['list_avatar_size'] as $i=>$value) 
				$amain['list_avatar_size'][$i] = ( int) $value;
		}
	}
	if (isset($_POST['list_rows_per_page'])) {	
		if (is_array($_POST['list_rows_per_page']))  {
			foreach ($_POST['list_rows_per_page'] as $i=>$value) 
				$amain['list_rows_per_page'][$i] = ( int) $value;
		}
	}
	
	if (isset($_POST['html_type'])) {	
		if (is_array($_POST['html_type']))  {
			foreach ($_POST['html_type'] as $i=>$value) {
				if (in_array( $value, array('table','simple'))) {
					$amain['html_type'][$i] =  $value;					
				}	
			}
		}
	}

	if (isset($_POST['filter_html_type'])) {	
		if (is_array($_POST['filter_html_type']))  {
			foreach ($_POST['filter_html_type'] as $i=>$value) {
				if (in_array( $value, array('intableheader','above','none'))) {
					$amain['filter_html_type'][$i] =  $value;					
				}	
			}
		}
	}
//	
		$addon_settings = apply_filters('amr-users-addon-settings', array()); //20150820
		if (!empty($addon_settings)) { //20150820
			foreach ($addon_settings as $setting) {	
				foreach ($amain['names'] as $i=>$n) {
					$amain[$setting['name']][$i] = false;
				}	
				//if ((!isset($_REQUEST['ulist'])) or ($_REQUEST['ulist'] == $i)) { // in case we are only doing 1 list - insingle view  // do we need this ??
				//	$amain[$setting['name']][$i] = $setting['default'];
				//}
			}
		}
	
	foreach ($amain['names'] as $i=>$n) { // NB clear booleans in case not set
		if ((!isset($_GET['ulist'])) or ($_GET['ulist'] == $i)) { // in case we are only doing 1 list - insingle view // 20180710 should this still be here, don;t think we need it.  We have ulist in the POST, so do not use $_REQUEST
			$amain['show_search'][$i] = false;
			$amain['show_perpage'][$i] = false;
			$amain['show_pagination'][$i] = false;
			$amain['show_headings'][$i] = false;
			$amain['show_csv'][$i] = false;
			$amain['show_xls'][$i] = false;
			$amain['show_refresh'][$i] = false;
			$amain['public'][$i] = false;
			$amain['customnav'][$i] = false;
			$amain['sortable'][$i] = false;
		}
	}
	
	if (isset($_POST['sortable'])) {	
		if (is_array($_POST['sortable']))  {
			foreach ($_POST['sortable'] as $i=>$y) 
				$amain['sortable'][$i] = true;
		}
	}
	if (isset($_POST['public'])) {	
		if (is_array($_POST['public']))  {
			foreach ($_POST['public'] as $i=>$y) 
				$amain['public'][$i] = true;
		}

	}
//nlr	amr_users_clear_all_public_csv ($amain['public']);
//nlr	amr_users_message(__('Csv lists privacy check done.  Any no longer public lists deleted. ','amr-users'));

	if (!empty($addon_settings)) { //20150820
		foreach ($addon_settings as $setting) {
			
			if (isset($_POST[$setting['name']])) { // else all unticked
				
				foreach ($_POST[$setting['name']] as $i=>$y)
					$amain[$setting['name']][$i] =true;
			}
			else $amain[$setting['name']] = array();
		}
	}	
	
	
	if (isset($_POST['show_search'])) {	
		if (is_array($_POST['show_search']))  {
			foreach ($_POST['show_search'] as $i=>$y) 
				$amain['show_search'][$i] = true;
		}
	}
	if (isset($_POST['customnav'])) {	
		if (is_array($_POST['customnav']))  {
			foreach ($_POST['customnav'] as $i=>$y) 
				$amain['customnav'][$i] = true;
		}
	}
	if (isset($_POST['show_perpage'])) {	
		if (is_array($_REQUEST['show_perpage']))  {
			foreach ($_REQUEST['show_perpage'] as $i=>$y) 
				$amain['show_perpage'][$i] = true;
		}
	}
	if (isset($_POST['show_pagination'])) {	
		if (is_array($_REQUEST['show_pagination']))  {
			foreach ($_REQUEST['show_pagination'] as $i=>$y) 
				$amain['show_pagination'][$i] = true;
		}
	}
	if (isset($_POST['show_headings'])) {	
		if (is_array($_REQUEST['show_headings']))  {
			foreach ($_REQUEST['show_headings'] as $i=>$y) 
				$amain['show_headings'][$i] = true;
		}
	}
	if (isset($_POST['show_csv'])) {	
		if (is_array($_REQUEST['show_csv']))  {
			foreach ($_REQUEST['show_csv'] as $i=>$y) 
				$amain['show_csv'][$i] = true;
		}
	}
	
	if (isset($_POST['show_xls'])) {	
		if (is_array($_REQUEST['show_xls']))  { 
			foreach ($_REQUEST['show_xls'] as $i=>$y) 
				$amain['show_xls'][$i] = true;
		}
	}
	
	if (isset($_POST['show_refresh'])) {	
		if (is_array($_REQUEST['show_refresh']))  {
			foreach ($_REQUEST['show_refresh'] as $i=>$y) 
				$amain['show_refresh'][$i] = true;
		}
	}
	
	$amain['version'] = AUSERS_VERSION;
	
	if (isset($_POST)) {	
		ausers_update_option ('amr-users-main', $amain);
		//ausers_update_option ('amr-users', $aopt);		
	}
	
	amr_users_message(__('Options Updated', 'amr-users'));	
		
	return;
}

function amr_echo_setting_html($setting_name, $i, $status, $disabled='') { // 20150820
	//$i=list number, status is input field status
// $available bhas disabled
		if ($disabled)  $disabledtext = ' disabled';
		else $disabledtext = ''; //2020 09
		echo '<td><input type="checkbox" id="'.$setting_name
			.$i.'" name="'.$setting_name.'['. $i .']" value="1" '.$disabledtext;
		if (!empty($status)) echo ' checked="Checked" '; 
		echo '/></td>';
}

function amr_echo_setting_heading_html($title, $text, $class='') { // 201608
		echo '<th '.$class.'>'.$title;
		echo ' '.$text.'</th>';
}

function amr_meta_overview_page() { /* the main setting spage  - num of lists and names of lists */
	global $amain;
	global $aopt;
	
	$tabs['overview']	= __('Overview', 'amr-users');
	$tabs['tools'] 	= __('Tools', 'amr-users');
	
	if (isset($_GET['tab'])) {

		if ($_GET['tab'] == 'tools'){  //nlr
			amr_users_do_tabs ($tabs,'tools');
			amr_user_list_tools();
			return;
		}
	}
	
	amr_users_do_tabs ($tabs,'overview');

	if (empty($amain)) $amain = ausers_get_option('amr-users-main');
	
	amr_meta_main_admin_header('Overview of configured user lists'.' '.AUSERS_VERSION);
	amr_meta_admin_headings ($plugin_page=''); // does the nonce check etc
	
	if ( isset( $_POST['import-list'] )) {
			amr_meta_handle_import();		
	}
	elseif ( isset( $_POST['export-list'] )  ) {
			amr_meta_handle_export();
		}	
	elseif (isset ($_POST['action']) and  ($_POST['action'] == "save")) { 
		if (!empty($_POST['reset'])) {
			amr_meta_reset();
			return;
		}


		else
			amrmeta_validate_overview();
	}

	else amr_handle_copy_delete();

	if ((!ameta_cache_enable()) or  (!ameta_cachelogging_enable())) 
			echo '<h2>'.__('Problem creating DB tables','amr-users').'</h2>';

	if (!(isset ($amain['checkedpublic']))) {
		echo '<input type="hidden" name="checkedpublic" value="true" />';
	}

	//echo '<h2>'. __('Overview &amp; tools', 'amr-users') .'</h2>';
	
	echo PHP_EOL.'<div class="wrap"><!-- one wrap -->'.PHP_EOL;

	
	if (!isset ($amain['names'])) { 
		echo '<h2>'
		.__('There is a problem - Some overview list settings got lost somehow.  Try reset options.','amr-users')
		. '</h2>';
	}
	else { 
	
		amr_meta_overview_form ();
/*			amr_meta_overview_onelist_headings();
			amr_meta_overview_onelist_headings_middle();
			
			foreach ($amain['names'] as $i => $name) {
			//for ($i = 1; $i <= $amain['no-lists']; $i++)	{
				amr_meta_overview_onelist_settings($i);
				echo '</tr>';
			}
			amr_meta_overview_onelist_headings_end();	
*/
	};
		
	echo '</div><!-- end of one wrap --> <br />'.PHP_EOL;
			
	//echo '<div style="clear: both; float:right; padding-right:100px;" class="submit">';
	echo ausers_submit();
	echo '<input class="button-primary" type="submit" name="addnew" value="'. __('Add new', 'amr-users') .'" />';

	echo ausers_form_end();
}						

function amr_meta_overview_form () { 
	global $amain, $aopt;

	if (is_plugin_active('amr-users-plus/amr-users-plus.php')) {
		$greyedout = '';
		$plusstatus = '';			
	}	
	else {
		$greyedout = ' style="color: #AAAAAA;" ';
		$plusstatus = ' disabled="disabled" ';
	}
	
	
	echo '<table class="form-table" style="width: auto;"><tr>';
	
	amr_echo_setting_heading_html(	__('Shortcode', 'amr-users'), '');
	foreach ($amain['names'] as $i => $name) {
		echo PHP_EOL.'<td>[userlist list='.$i.']</td>';
	}
	echo PHP_EOL.'</tr><tr>';
	
	amr_echo_setting_heading_html(	__('Name of List', 'amr-users'), '');
	
	foreach ($amain['names'] as $i => $name) {
		echo PHP_EOL.'<td><input type="text" size="20" id="name'
		.$i.'" name="name['. $i.']"  value="'.$amain['names'][$i].'" /><br /> ';
	
		echo PHP_EOL.au_copy_link(__('Copy','amr-users'),$i,$amain['names'][$i]);
		
		echo PHP_EOL.' | '.au_delete_link(__('Delete','amr-users'),$i,$amain['names'][$i])
			.PHP_EOL.' |'.au_view_link('&nbsp;'.__('View','amr-users'),$i,$amain['names'][$i]);
			
		if (!is_network_admin()) {
			echo PHP_EOL.' | '.au_add_userlist_page(__('Add page','amr-users'), $i,$amain['names'][$i]);	
			echo '</td>';	
		}	
	}

	
	echo '</tr><tr>';

	amr_echo_setting_heading_html(	__('Avatar size', 'amr-users'), 
	'<a class="tooltip" title="gravatar size info" href="http://en.gravatar.com/site/implement/images/">?</a>');	
	
	foreach ($amain['names'] as $i => $name) {	
	
		if (empty($amain['avatar_size'])) $amain['avatar_size'] = 10;
		if (empty($amain['list_avatar_size'][$i])) 
				$amain['list_avatar_size'][$i] = $amain['avatar_size'];	
		echo '<td><input type="text" size="3" id="avatar_size'
		.$i.'" name="list_avatar_size['. $i.']"  value="'.$amain['list_avatar_size'][$i].'" /></td>';
	}
			
	//------- public
		echo '</tr><tr>';
		
		amr_echo_setting_heading_html(	__('Public', 'amr-users'), 
		'<a class="tooltip" href="#" title="'.__('List may be viewed in public pages', 'amr-users').'">?</a>');	
		foreach ($amain['names'] as $i => $name) {		
			echo '<td>';
			echo '<input type="checkbox" id="public' 
				.$i.'" name="public['. $i .']" value="1" ';
			if (!empty($amain['public'][$i])) {
					echo ' checked="checked" ';
					}		
			echo '/></td>';
		}

//------- html type
		echo '</tr><tr>';
			
		amr_echo_setting_heading_html(	__('Public Html Type', 'amr-users'), ''); 
		foreach ($amain['names'] as $i => $name) {	
		
			echo '<td>';
			if (empty($amain['html_type'][$i])) 
				$amain['html_type'][$i] = 'table';
			foreach (array('table','simple') as $type) {
				echo '<input type="radio" id="html_type_'.$type.$i.'" name="html_type['. $i .']" value="'.$type.'" ';
				if (($amain['html_type'][$i]) == $type) echo ' checked="Checked" '; 
				echo '/>';
				_e($type);
				echo '&nbsp;&nbsp;';
			}
			echo '</td>';
	
		}
		
//------- rows pp 	
		echo '</tr><tr>';
		
		amr_echo_setting_heading_html(	__('Rows per page', 'amr-users'), ''); 

		foreach ($amain['names'] as $i => $name) {	 
			
			if (empty($amain['list_rows_per_page'][$i])) 
					$amain['list_rows_per_page'][$i] = (empty($amain['rows_per_page'][$i])? 10 :$amain['rows_per_page'][$i]) ; //202009 in case add new was done
				
			echo '<td><input type="text" size="3" id="rows_per_page'
			.$i.'" name="list_rows_per_page['. $i.']"  value="'
			.$amain['list_rows_per_page'][$i].'" /></td>';	

		}

	echo '</tr><tr>';
	amr_echo_setting_heading_html(	__('Per page', 'amr-users'), '<a class="tooltip" href="#" title="'.
	__('If list is public, show per page option.', 'amr-users').'">?</a>');			
	
	foreach ($amain['names'] as $i => $name) {
		if (empty($amain['show_perpage'][$i])) $sp = false;
		else $sp = $amain['show_perpage'][$i];
		amr_echo_setting_html('show_perpage',$i,$sp);
	}	
		
	echo '</tr><tr>';			
	
	amr_echo_setting_heading_html(	__('Search', 'amr-users'), ' <a class="tooltip" href="#" title="'.
	__('If list is public, show user search form.', 'amr-users').'">?</a>');		
	
	foreach ($amain['names'] as $i => $name) {
		if (empty($amain['show_search'][$i])) $sp = false;
		else $sp = $amain['show_search'][$i];
		amr_echo_setting_html('show_search',$i,$sp);
	}

	echo '</tr><tr>';	
		
	amr_echo_setting_heading_html(	__('Pagination', 'amr-users'), '<a class="tooltip" href="#" title="'.
		__('If list is public, show pagination, else just show top n results.', 'amr-users').'">?</a>');					
	foreach ($amain['names'] as $i => $name) {
		if (empty($amain['show_pagination'][$i])) $sp = false;
		else $sp = $amain['show_pagination'][$i];
		amr_echo_setting_html('show_pagination',$i,$sp);
	}
	
	echo '</tr><tr>';	
	
	amr_echo_setting_heading_html(	__('Headings', 'amr-users'), '<a class="tooltip" href="#" title="'.
			__('If list is public, show column headings.', 'amr-users').'">?</a>');						
	foreach ($amain['names'] as $i => $name) {
		if (empty($amain['show_headings'][$i])) $sp = false;
		else $sp = $amain['show_headings'][$i];
		amr_echo_setting_html('show_headings',$i,$sp);
	}
			
	echo '</tr><tr>';
	
	amr_echo_setting_heading_html(	__('Xls link', 'amr-users'), '<a class="tooltip" href="#" title="'.
	__('If list is public, show a link to xls export file', 'amr-users').' '.__('Requires the amr-users-plus addon.', 'amr-users').'">?</a>', $greyedout);			
	foreach ($amain['names'] as $i => $name) {
		if (empty($amain['show_xls'][$i])) 
			$sp = false;
		else 
			$sp = $amain['show_xls'][$i];	
		
		amr_echo_setting_html('show_xls',$i,$sp, $plusstatus);
	}	
	
	echo '</tr><tr>';	
	amr_echo_setting_heading_html(	__('Csv link', 'amr-users'), '<a class="tooltip" href="#" title="'.
	__('If list is public, show a link to csv export file', 'amr-users').'">?</a>');			
	foreach ($amain['names'] as $i => $name) {
		if (empty($amain['show_csv'][$i])) $sp = false;
		else $sp = $amain['show_csv'][$i];		
		amr_echo_setting_html('show_csv',$i,$sp); //20201125
	}	
	echo '</tr><tr>';
	
	amr_echo_setting_heading_html(	__('Refresh', 'amr-users'), '<a class="tooltip" href="#" title="'.
	__('If list is public, show a link to refresh the cache', 'amr-users').'">?</a>');			
	foreach ($amain['names'] as $i => $name) {
		if (empty($amain['show_refresh'][$i])) $sp = false;
		else $sp = $amain['show_refresh'][$i];	
		amr_echo_setting_html('show_refresh',$i,$sp);
	}	
			
	echo '</tr><tr>';
	
	amr_echo_setting_heading_html(	__('Sortable', 'amr-users'), '<a class="tooltip" href="#" title="'.
	__('Offer sorting of the cached list by clicking on the columns.', 'amr-users').'">?</a>');				
	foreach ($amain['names'] as $i => $name) {
		if (empty($amain['sortable'][$i])) $sp = false;
		else $sp = $amain['sortable'][$i];	
		amr_echo_setting_html('sortable',$i,$sp);
	}	
	
	echo '</tr><tr>';

	amr_echo_setting_heading_html(	__('Custom navigation', 'amr-users'), '<a class="tooltip"  href="#" title="'.
	__('Show custom navigation to find users. ', 'amr-users').__('Requires the amr-users-plus addon.', 'amr-users') .'">?</a>', $greyedout);
		
	foreach ($amain['names'] as $i => $name) {
		if (empty($amain['customnav'][$i])) $sp = false;
		else $sp = $amain['customnav'][$i];
		amr_echo_setting_html('customnav',$i,$sp, $plusstatus);
	}	
			
	echo '</tr><tr>';			

	amr_echo_setting_heading_html(	__('Filtering Location', 'amr-users'), '<a class="tooltip"  href="#" title="'.
	__('Show filtering. ', 'amr-users').__('Requires the amr-users-plus addon.', 'amr-users') .'">?</a>', $greyedout
		);
	foreach ($amain['names'] as $i => $name) {	
		echo '<td>';
		if (empty($amain['filter_html_type'][$i])) 
			$amain['filter_html_type'][$i] = 'none';	
		foreach (array(
				'intableheader' => __('in table','amr-users'),
				'above' 		=> __('above','amr-users'), 
				'none' 			=> __('none','amr-users')) as $val => $type) {
				echo '<input type="radio" id="filter_html_type_'.$val.$i.'" name="filter_html_type['. $i .']" value="'.$val.'" '
				.$plusstatus ;
				if (($amain['filter_html_type'][$i]) == $val) echo ' checked="Checked" '; 
				echo '/>';
				echo $type;
				echo '<br />';
			}
		echo '</td>';	
	}	
	//
	echo '</tr>';
	
	$addon_settings = apply_filters('amr-users-addon-settings', array()); //20150820
	if (!empty($addon_settings)) { //20150820
		echo '<tr>';
		foreach ($addon_settings as $setting) {
			
			amr_echo_setting_heading_html($setting['text'], ' <a class="tooltip" href="#" title="'
			.$setting['helptext']. '">?</a>');
			foreach ($amain['names'] as $i => $name) {
				if (!empty($amain[$setting['name']])  and 
					(!empty($amain[$setting['name']][$i]))) {
					$value = $amain[$setting['name']][$i];
					//echo '<br />'.$setting['name'].' '.$i.' '.$value;
					}
				else $value = false;	
				
				amr_echo_setting_html($setting['name'], $i, $value);
			}
		}
		echo '</tr>';
	}


	echo PHP_EOL.'</table>';
}
