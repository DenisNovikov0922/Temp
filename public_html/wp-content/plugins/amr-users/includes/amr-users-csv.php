<?php
/*
The csv file functions for the plugin
*/
  
function amr_csvlines_to_csvbatch ($lines, $ulist=1) { // convert array to csv separated stuff for subset
global $aopt;
global $amr_nicenames;

	$selected = $aopt['list'][$ulist]['selected'];
	asort($selected);
	
	$headings = amr_build_col_headings ($selected);
	unset ($headings[0]);
/*	foreach ($selected as $field => $colno) { 
		$headings[$field] = $amr_nicenames[$field];
	}
	*/
	array_unshift( $lines, $headings);

	foreach ($lines as $k => $line) {
		if (!empty($line['index'] )) unset ($line['index']);
		if (!isset($headings['ID'])) unset ($line['ID']);
		$csvlines[]['csvcontent'] = amr_cells_to_csv ($line);
	}

	return $csvlines;
}
 
function amr_cells_to_csv ($line) { // convert a line of cells to csv

	foreach ($line as $jj => $kk) {
		if (empty($kk)) 
			$line[$jj] = '""'; /* ***there is no value */
			//$line[$jj] = ''; /* there is no value */
		else {
			$line[$jj] = '"'.str_replace('"','""',$kk).'"';   
			// for any csv a doublequote must be represented by two double quotes, or backslashed - BUT only want for csv, and some systems can backslash?
			// when cacheing rewritten not to be so csv oriented, move this to the csv generation
			//$line[$jj] = '"'.$kk.'"';   //- gets addslashed later, BUT not adding slashes to ' doc "dutch" tor ' - why not ?
			}
	}
	$csv = implode (',', $line); 
	return $csv;		
}
 
function amr_meta_handle_csv () {
// check if there is a csv request on this page BEFORE we do anything else ?
		/* since data passed by the form, a security check here is unnecessary, since it will just create headers for whatever is passed .*/
	if (isset($_REQUEST['ulist']) ) 
		$ulist = intval ($_REQUEST['ulist']);	 // or might be csvsubset

	if (!empty($_POST['csv'])) {// we have a filtered subset form submission
		$csv = stripslashes (htmlspecialchars_decode( $_POST['csv']));  // wtf slashes started appearing in %_POST??
		if (isset($_REQUEST['reqxls']) ) {			
			$csvlines = explode(chr(13).chr(10),$csv); // was comma separated csv
			amr_to_xls($ulist, $csvlines);
		}
		else 
			amr_to_csv ($csv,'csv');
		return;	
	}
	else {
		if (isset($_REQUEST['reqxls']) )  
			$ulist = intval ($_REQUEST['reqxls']);
		elseif (isset($_REQUEST['reqcsv']) )  
			$ulist = intval ($_REQUEST['reqcsv']);
		else return;	
	}
	
	if (!ausers_ok_to_show_list($ulist))return;

	$strip_endings =  true;
	$strip_html = false;
	$wrapper = '"';
	$delimiter = ',';
	$nextrow = chr(13).chr(10);
	$tofile = false;


	if (isset($_REQUEST['suffix'])) {
		if ($_REQUEST['suffix']=='xls')  {
			$suffix = 'xls';
			$wrapper = '';
			$delimiter = '';
			$nextrow = '';
		}
		elseif ($_REQUEST['suffix']=='csv') {
			$suffix = 'csv';
			$delimiter = ',';
		}
		else {
			$suffix = 'txt';
			$delimiter = chr(9);
		}
	}

	echo amr_generate_csv($ulist, $strip_endings, $strip_html, $suffix,$wrapper,$delimiter,$nextrow);
/*
		if ($suffix == 'xls') {
			amr_to_xls($ulist, htmlspecialchars_decode( $_POST['csv']), $suffix);		
			}
		else {
			amr_to_csv (htmlspecialchars_decode($_POST['csv']),$suffix);
/*		amr_to_csv (html_entity_decode($_POST['csv'])); */
		//}
	exit();
}	
 
function amr_to_csv ($csv, $suffix) {
/* create a csv file for download */
	if (!isset($suffix)) $suffix = 'csv';
	$file = 'userlist-'.date('Ymd_Hi').'.'.$suffix;
	if (amr_is_network_admin()) $file = 'network_'.$file;
	header("Content-Description: File Transfer");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$file");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $csv;
	exit(0);   /* Terminate the current script sucessfully */
}		
 
function amr_undo_db_slashes (&$line) {  
//wp adds to content when inserting into db
//somehow when extracting for csv the backslashes are not being dealt with
// is okay when listing on front end
	$line['csvcontent'] = str_replace('\"','"',$line['csvcontent']);
}
 
function amr_remove_ID_from_front (&$line) { //its a csv line.  Find first comma and strip till after comma
	$line['csvcontent'] = substr($line['csvcontent'], strpos($line['csvcontent'],',')+1);  
}

function amr_remove_index_from_back (&$line) { //its a csv line.  strip from last comma to end
	$line['csvcontent'] = substr($line['csvcontent'], 0, strrpos($line['csvcontent'],','));  
}
 
 
function amr_get_csv_lines($ulist) { // from cache
/* get the whole cached file - write to file? but security / privacy ? */
/* how big */
	global $aopt;
	
	$c = new adb_cache();
	$rptid = $c->reportid($ulist);
	//$total = $c->get_cache_totallines ($rptid );  // nlr should rather pass 0 to get all 

	$lines = $c->get_cache_report_lines($rptid,0,0); 
	if (empty($lines)) return false; // 20140722 no headings lines at moment either
	
	$headinglines = $c->get_column_headings($rptid);
	
	$lines = apply_filters ('amr_users_csv_before_export', $lines, $headinglines);
	
	// if we have navigation, remove the index from the saved cache
	// if heading 0 has ;index at back
	if (substr($headinglines[0]['csvcontent'],-5) == 'index') { 
		array_walk($lines,'amr_remove_index_from_back');
	}

	array_unshift( $lines, $headinglines[1]);
	
	array_walk($lines,'amr_remove_ID_from_front'); // REMOVE technical ID
	//$lines = $c->get_cache_report_lines($rptid,0,$total); 
	/* we want the heading line (line1), but not the internal nameslines (line 0) , 
	plus all the data lines, so need total + 1 */
	return ($lines);
	}

	
function amr_lines_to_csv($lines,  // these lines have 'csvcontent'
	$ulist,  // receive lines and output to csv
	$strip_endings, // allows filter?
	$strip_html = false, 
	$suffix, 
	$wrapper, 
	$delimiter, 
	$nextrow, 
	$tofile=false) {
	
	global $aopt;
	
	if (isset($lines) and is_array($lines)) 
		$t = count($lines);
	else 
		$t = 0;

	$csv = '';	
	if ($t > 0) {
	
		array_walk($lines,'amr_undo_db_slashes');
		//for subset csv filter, we won'thave extra ID, but for full file we do

		if ($strip_endings) {
			foreach ($lines as $k => $line) {
				$csv .= apply_filters( 'amr_users_csv_line', $line['csvcontent'] ).$nextrow;
			}
		}
		else {
			foreach ($lines as $k => $line)
			$csv .= $line['csvcontent'].$nextrow;
			}
			
		$csv = str_replace ('","', $wrapper.$delimiter.$wrapper, $csv);	
		/* we already have in std csv - allow for other formats */
		$csv = str_replace ($nextrow.'"', $nextrow.$wrapper, $csv);
		$csv = str_replace ('"'.$nextrow, $wrapper.$nextrow, $csv);
		if ($csv[0] == '"') $csv[0] = $wrapper;

		if (!isset($_REQUEST['csvsubset'])) 

			amr_to_csv($csv, $suffix);

// if csv subset do form? or can we still pass all the filtering?
		else 
			$html = amr_csv_form($csv);
		
	}
	else { // 20140722 added empty file check
		$csv = ''; //20140722 empty so previous file will be deleted
		//$csvurl = amr_users_get_csv_link($ulist,$suffix);
		$html = '<br />'.__('No lines found.','amr-users');
	}	
		
	return $html;
	}
	
function amr_generate_csv($ulist,
	$strip_endings, // allows filter?
	$strip_html = false, 
	$suffix, 
	$wrapper, 
	$delimiter, 
	$nextrow, 
	$tofile=false) {

	$lines = amr_get_csv_lines($ulist);		
	// could break it here into a get line part and a generate csv part so could call for filtered csv

	$html ='';
	if (($suffix == 'xls')) {
		if (function_exists('amr_lines_to_xlsx') ) {	
			$html = amr_lines_to_xlsx(
				$lines, 
				$ulist,  			
				'xls');	
		}			
		else echo 'Function not available';
	}
	else

		$html = amr_lines_to_csv($lines, $ulist,  
		$strip_endings, // allows filter?
		$strip_html, 
		$suffix, 
		$wrapper, 
		$delimiter, 
		$nextrow, 
		$tofile);
	
	return($html);
}
	
 
function amr_users_setup_filename($ulist, $suffix) {	//  * Return the full path to the  file 
	$type = 'user_list_'.$ulist;	
	$now = date('Ymd_Hi');
	if (is_user_logged_in()) {
		if (!empty($_REQUEST['csvsubset'])) {  // check fo
			$current_user = wp_get_current_user();
			$name = $current_user->user_login;
			$type='user_list_'.$name.'_filter_';
		}
	}
	return $type.'_'.$now.'.'.$suffix; ;
}

function amr_users_get_csv_link($ulist,$suffix) {	//  * Return the full path to the  file 
global $amain;

	$text = (empty ($amain['csv_text'] ) ? '' : $amain['csv_text']);
	$query_arg = 'req'.$suffix;
//	$csvfile = amr_users_setup_export_filepath($ulist, $suffix);
//	$url = amr_users_get_csv_url($csvfile);

	$url = add_query_arg(array('reqcsv'=>$ulist,
							'suffix'=> $suffix));
	
	if ($suffix == 'txt') {
		$title = __('Txt Export','amr-users');
	}
	elseif (($suffix == 'xls') and function_exists('amr_to_xls')) { 
		$text = (empty ($amain['xls_text'] ) ? ' xls ' : $amain['xls_text']);
		$title = __('Xls Export','amr-users');
	}
	elseif ($suffix == 'csv') {
		$text = (empty ($amain['csv_text'] ) ? ' csv ' : $amain['csv_text']);
		$title = __('Csv Export','amr-users');	
	}
	else return '';
	
	if (ausers_ok_to_show_list($ulist))   { //ie if is public.  No need to check here ? should have been done before?
			$html = PHP_EOL.'<div class="csvlink">
			<p><a class="csvlink" title="'.$title.'" href="'.$url.'">'  
			.$text
			.'</a></p>'.PHP_EOL.
			'</div><!-- end csv link -->'.PHP_EOL ;
		}
	else $html = '<!-- user does not have capability to list users for csv -->';
	return $html;

}	

function amr_users_get_refresh_link($ulist) {	//  * Return the full path to the  file 
global $amain;

	$text = (empty ($amain['refresh_text'] ) ? '' : $amain['refresh_text']);
	
	$url = remove_query_arg(array('sort','dir','listpage'));
	
	$url = esc_url(add_query_arg(array('refresh'=>'1'),$url));
	return (
	PHP_EOL.'<div class="refreshlink">
	<p><a class="refreshlink" title="'.__('Refresh Cache','amr-users').'" href="'.$url.'">'
	.$text
	.'</a></p>
	</div>'.PHP_EOL
	) ;

}

function amr_csv_form($csv) {
	/* accept a long csv string and output a form with it in the data - this is to keep private - avoid the file privacy issue */

	$html = PHP_EOL.'<input type="hidden" name="csv" value="'.htmlspecialchars($csv) . '" />';
	$text = __('Export to CSV','amr-users');	
	$html .= PHP_EOL.  '<input type="submit" name="reqcsv" value="'
		.$text.'" class="button button-primary user-export" />'; 
		
	if (function_exists('amr_to_xls') )	{	
		$text = __('Export to .xls','amr-users'); // for excel users				
		$html .= PHP_EOL.  '<input type="submit" name="reqxls" value="'
			.$text.'" class="button button-primary user-export" />'; 
	}		

	return ( $html );
}

