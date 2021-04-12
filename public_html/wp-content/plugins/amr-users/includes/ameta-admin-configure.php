<?php
include ('ameta-admin-overview.php');

function amr_manage_headings_submit () {
	if (!empty($_REQUEST['headings']) and amr_users_can_edit('headings'))
			$headings_submit = 
			PHP_EOL.'<div style="float:left;"> <!-- headings -->'
			.'<h2>'.__('Custom Column Headings - mainly for multi field columns','amr-users').'</h2>'
			.'<p><b>'.__('Advice:  Rather use nice names to avoid confusing yourself if you configure new colmns but forget to change headings.','amr-users').'</b></p>'
			.'<input type="submit" name="update_headings" id="update_headings" class="button-primary" value="'
			.__('Update Column Headings','amr-users').'"/>&nbsp;'
			.'<input type="submit" name="reset_headings" id="reset_headings" class="button" value="'
			.__('Reset Column Headings','amr-users').'"/>'
			.'</div> <!-- end headings -->'.PHP_EOL;
		else 
			$headings_submit = '';	
		return 
			$headings_submit;
}

function amr_allow_update_headings ($cols,$icols,$ulist, $sortable) {
global $aopt;

	if (!empty($_POST['reset_headings'])) {// check for updates to headings
		amr_users_reset_column_headings ($ulist);
		amr_users_message(__('To see reset headings, rebuild the cache.', 'amr-users'));
	}
	
	$cols = amr_users_get_column_headings  ($ulist, $cols, $icols);	
	
	if (!empty($_POST['update_headings'])) {// check for updates to headings
		//echo '<br/>Ulist='.$ulist;
	
		foreach ($icols as $ic => $cv) {
			if (isset($_POST['headings'][$ic])) {				
				$customcols[$cv] = esc_html($_POST['headings'][$ic]);				
				if ($customcols[$cv] === $icols[$ic]) {// if same as default, do not save  !! NOT COLS
					unset($customcols[$cv]);
				}
			}
		}

		if (!empty($customcols)) amr_users_store_column_headings  ($ulist, $customcols);
	}
	
	$cols = amr_users_get_column_headings  ($ulist, $cols, $icols);
	
	$html = '';		
	foreach ($icols as $ic => $cv) { /* use the icols as our controlling array, so that we have the internal field names */
		if (!($ic == 'checkbox')) {   			
			$v 		= '<input type="text" size="'.
			min(strlen($cols[$ic]), 80)
			.'" name="headings['.$ic.']" value="'.$cols[$ic].'" />';
		}
		else $v = 	$cols[$ic];	
		
		$html 	.= '<td>'.$v.'</td>';
		
	}	
	$hhtml = '<tr>'.$html.'</tr>'; /* setup the html for the table headings */		
	return ($hhtml);		
}

function amrmeta_validate_listfields()	{
	global $aopt;
/* We are only coming here if there is a SAVE, now there may be blanked out fields in all areas - except must have something selected*/
/*	if ( get_magic_quotes_gpc() ) { // deprecated with no replacement 2020
		$_POST      = array_map( 'stripslashes_deep', $_POST );
	}
*/	
//	echo '<pre>'.print_r($_REQUEST, true).'</pre>';//
	
	if (isset($_POST['list'])) {
		if (is_array($_POST['list'])) {/*  do we have selected, etc*/
			foreach ($_POST['list'] as $i => $arr) {		/* for each list */				
				if (is_array($arr))  { 
				
					if (empty ($_REQUEST['config'])  OR ( isset($_REQUEST['config']) and ($_REQUEST['config'] =='select' ))) { //20170125
				
						if (!empty ($arr['selected']) and is_array($arr['selected']))  {/*  do we have  name, selected, etc*/							
							unset($aopt['list'][$i]['selected']);	
							foreach ($arr['selected'] as $j => $v) {
								$v = trim($v);
								if ((empty($v)) or ($v == '0')  ) unset ($aopt['list'][$i]['selected'][$j] );
								else {
									if ($s = filter_var($v, FILTER_VALIDATE_FLOAT,
										array("options" => array("min_range"=>1, "max_range"=>999)))) {
										$aopt['list'][$i]['selected'][$j] = $s;
										
										}
									else {
										echo '<h2>Error in display order for '.$j.$s.'</h2>';
										return(false);
									}
								}						
							}
	//						asort ($aopt['list'][$i]['selected']); /* sort at update time so we don't have to sosrt every display time */
						
						}
					/*	else {						
							echo '<h2>'.__('No fields selected for display','amr-users').'</h2>'; 
							return (false);
						}
					*/ 	
						
						unset ($aopt['list'][$i]['sortby']	);		// unset all sort by's 									
						if (isset($arr['sortby']) and is_array($arr['sortby']))  {

							foreach ($arr['sortby'] as $j => $v) {						
								if (a_novalue($v)) unset ($aopt['list'][$i]['sortby'][$j]);
								else $aopt['list'][$i]['sortby'][$j]  = $v;	
							}	
						}
						/* Now check sortdir */
						unset ($aopt['list'][$i]['sortdir']	);		/* unset all sort directions */		
						if (isset($arr['sortdir']) and is_array($arr['sortdir']))  {				
							foreach ($arr['sortdir'] as $j => $v) {									
								if (!(a_novalue($v))) $aopt['list'][$i]['sortdir'][$j] = $v;
								else $aopt['list'][$i]['sortdir'][$j] = 'SORT_ASC';
							}	
						}
					
					}
					/* Now check included */
					if (isset($_REQUEST['config']) and ($_REQUEST['config'] ==='include_exclude' )) {					
					
						unset($aopt['list'][$i]['included']);
						if (!empty($arr['included']) and is_array($arr['included']))  {		
							
							foreach ($arr['included'] as $j => $v) {
								if (a_novalue($v)) 
									unset($aopt['list'][$i]['included'][$j]);
								else {
									$aopt['list'][$i]['included'][$j] 
										= explode (',', filter_var($v, FILTER_SANITIZE_STRING));
									$aopt['list'][$i]['included'][$j] = 
										array_map('trim', $aopt['list'][$i]['included'][$j] );
									}
							}	
						}
						
						unset($aopt['list'][$i]['includeonlyifblank']);									
						if (isset($arr['includeonlyifblank']) and is_array($arr['includeonlyifblank']))  {	
										
							foreach ($arr['includeonlyifblank'] as $j => $v) {
								$aopt['list'][$i]['includeonlyifblank'][$j] = true; 
								}	
							}	
						/* Now check excluded */
						unset($aopt['list'][$i]['excluded']);	
						if (isset($arr['excluded']) and is_array($arr['excluded']))  {
											
							foreach ($arr['excluded'] as $j => $v) {
								if (a_novalue($v)) 
									unset($aopt['list'][$i]['excluded'][$j]);
								else {
									$aopt['list'][$i]['excluded'][$j] 
										= explode(',', filter_var($v, FILTER_SANITIZE_STRING));
									}	
								}	
							}	
						/* Now check what to do with blanks */
						unset($aopt['list'][$i]['excludeifblank']);	
						if (isset($arr['excludeifblank']) and is_array($arr['excludeifblank']))  {	
											
							foreach ($arr['excludeifblank'] as $j => $v) {
								$aopt['list'][$i]['excludeifblank'][$j] = true;
								}	
							}	
					}	
				
					if (isset($_REQUEST['config']) and ($_REQUEST['config'] ==='before_after' )) {
										/* Now check before*/
						unset ($aopt['list'][$i]['before']	);		/* unset all  */		
						if (isset($arr['before']) and is_array($arr['before']))  {				
							foreach ($arr['before'] as $j => $v) {									
								if (!(a_novalue($v))) 
									$aopt['list'][$i]['before'][$j] = (esc_html($v));
								else $aopt['list'][$i]['before'][$j] = '';
							}	
						}
																/* Now check after*/
						unset ($aopt['list'][$i]['after']	);		/* unset all  */		
						if (isset($arr['after']) and is_array($arr['after']))  {				
							foreach ($arr['after'] as $j => $v) {									
								if (!(a_novalue($v))) 
								$aopt['list'][$i]['after'][$j] = esc_html($v);
								else $aopt['list'][$i]['after'][$j] = '';
							}	
						}
					}

					if (isset($_REQUEST['config']) and ($_REQUEST['config'] ==='format' )) {		
						unset ($aopt['list'][$i]['format']	);		/* unset all  */		
						if (isset($arr['format']) and is_array($arr['format']))  {				
							foreach ($arr['format'] as $j => $v) {									
								if (!empty($v)) 
									$aopt['list'][$i]['format'][$j] = ($v);
								//else $aopt['list'][$i]['format'][$j] = 'none';
							}	
						}
											
						if (isset($arr['formatdate']))  {				
							$aopt['list'][$i]['formatdate'] = sanitize_text_field($arr['formatdate']);	
						}
						if (isset($arr['formattime']))  {				
							$aopt['list'][$i]['formattime'] = sanitize_text_field($arr['formattime']);	
						}	
						if (isset($arr['formatdatetime']))  {				
							$aopt['list'][$i]['formatdatetime'] = sanitize_text_field($arr['formatdatetime']);	
						}						
					
																				/* Now check links*/
						unset ($aopt['list'][$i]['links']	);		/* unset all  */		
						if (isset($arr['links']) and is_array($arr['links']))  {				
							foreach ($arr['links'] as $j => $v) {									
								if (!empty($v)) 
									$aopt['list'][$i]['links'][$j] = ($v);
								//else $aopt['list'][$i]['links'][$j] = 'none';
							}	
						}
					}
				}

			}
			ausers_update_option ('amr-users', $aopt);
			amr_users_message(__('Options Updated', 'amr-users'));
		}
	}
	return (true);	
}

function amrmeta_datetime_formats($listindex, $config) {
	
	$df = get_option( 'date_format' ); //might be custom
	$tf = get_option( 'time_format' ); //might be custom
	$tzobj = amr_getset_timezone ();
	$date = new DateTime();
	date_timezone_set( $date, $tzobj );
	$timestamp = $date->format( 'U' );
	
	$date_formats = array_unique( apply_filters( 'date_formats', array( __( 'F j, Y' ),
		'Y-m-d',
		'm/d/Y',
		'd/m/Y' ) ) );
	if (!in_array($df,$date_formats )) //might be custom
		$date_formats[] = $df;
	
	$time_formats = array_unique( apply_filters( 'time_formats', array( 
		__( 'g:i a' ),
		'g:i A',
		'H:i'	) ) );
	if (!in_array($tf,$time_formats )) //might be custom
		$time_formats[] = $tf;
		
	foreach ($date_formats as $df) {
		foreach ($time_formats as $tf) {
			$dt_formats[] = $df.' '.$tf;
		}
	}	
		
	?><h4><?php
	_e('Define date and time formats for this list:', 'amr-users'); 
	?></h4>
<p>
	<a href="<?php echo admin_url('options-general.php'); ?>">
	<?php _e('(see also the wordpress general settings custom options)', 'amr-users'); ?>
	</a>
</p>
<table>
<tr>
	<td><label><?php _e('Date format','amr-users'); ?></label></td>
	<td><select id="formatdate<?php echo $listindex; ?>" name="list[<?php echo $listindex; ?>][formatdate]" >
	<?php
	foreach ($date_formats as $lti => $format ) {
		 echo ' <option value="'.$format .'" ';
		 if (!empty ($config['formatdate']) and ($config['formatdate'] === $format))  
			echo ' selected = "selected" ';
		 echo ' >'.date_i18n($format,$timestamp).'</option>';	//show sample 		
	}	?>
	</select></td>
</tr>
<tr>
	<td><label><?php
	_e('Time Format','amr-users');
	echo '</label></td><td>';
	echo '<select id="formattime'.$listindex.'" '
		.' name="list['.$listindex.'][formattime]" >';
	foreach ($time_formats as $lti => $format ) {
		 echo PHP_EOL.' <option value="'.$format .'" ';
		 if (!empty ($config['formattime']) and ($config['formattime'] === $format))  
			echo ' selected = "selected" ';
		 echo ' >'.date_i18n($format,$timestamp).'</option>';	//show sample 20171112?				
	}	?>
</select></td>
</tr>
<tr>
	<td><label><?php
	_e('Date and Time Format','amr-users');?>
</label></td>
<td><select id="formatdatetime<?php echo $listindex; ?>" name="list[<?php echo $listindex; ?>][formatdatetime]" ><?php
	foreach ($dt_formats as $lti => $format ) {
		 echo PHP_EOL.' <option value="'.$format .'" ';
		 if (!empty ($config['formatdatetime']) and ($config['formatdatetime'] === $format))  
			echo ' selected = "selected" ';
		 echo ' >'.date_i18n($format,$timestamp).'</option>';	//show sample 20171112?				
	}	?>
</select></td>
</tr>
</table>
<?php

	return ;
}

function amrmeta_get_fieldtypes() { // 20200119
	if (!($ftypes = ausers_get_option('amr-users-field-types')))
		$ftypes = amr_default_fieldstypes(); // nlr? get what type each field is

	// for options that were saved before we prevented change of some field types	
	$fields_types_fixed = amr_fixed_fieldstypes();//2017
	$fields_types = $fields_types_fixed + $ftypes;
	return $fields_types;
}

function amrmeta_format_fields( $listindex , $sel, $config) { // 20170112
global $amain,
	$amr_nicenames;
	
// date format, time format, date and time format
	amrmeta_datetime_formats($listindex, $config);	

	$linktypes = amr_linktypes();
	$fields_types = amrmeta_get_fieldtypes();  
	$fieldtype_formats = amr_formats_for_fieldtypes(); //get the possible formats for each field type
		
?>
<div class="userlistfields">
	<table class="widefat" style="padding-right: 2px; width:auto;">
	<thead  style="text-align:center;">
	<tr>
	<th><?php _e('Field name','amr-users');?></th>
	<th><a href="#" title="'<?php echo __('Field format based on field type.','amr-users')
		.' '.__('(overrides default formats','amr-users').'"> '.__('Format:','amr-users'); ?>
		</a>
		<?php _e(' (some formats require add-ons or custom function)'); ?>
	</th>
	<th style="width:2em;">
		<a href="#" title="<?php _e('Type of link to be generated on the field value', 'amr-users'); ?>"><?php
		_e('Link Type:','amr-users'); ?></a>
	</th>
	</tr>
	</thead>
	<tbody><?php

	foreach ( $sel as $i => $f )		{		/* list through all the selected fields*/			
		?>

<tr>
		<td><?php
		$l = 'l'.$listindex.'-'.$i;			
		echo $amr_nicenames[$i];?>
		</td><?php
			
		$ftype = '';
		$formats = array(); 
		if ((!empty($fields_types)) and (!empty($fields_types[$i]) )) {
			$ftype = $fields_types[$i]; 
			if (!empty($ftype)) {
				if (!empty( $fieldtype_formats[$ftype])) {
					$formats =	$fieldtype_formats[$ftype];		
				}
			}							
		}
		// if our field has a defined type, then we can offer format options
		?>

	<td><?php
		if (!empty($formats)) {	
			echo PHP_EOL.'<select id="format'.$l.'" name="list['.$listindex.'][format]['.$i.']" >';				
			echo PHP_EOL.'<option value="" >'.__('None: ie linktype & css, or custom function','amr-users').' </option>';	
	
			foreach ($formats as $format => $formatname ) {
				$avail = ' disabled="disabled" ';
				$funcname = amru_get_func_name ($i, $format);   
				echo PHP_EOL.'<!--- '.$funcname.'  -->';
				
				if (function_exists($funcname)) { 
					$avail = ' ';	
				}					
				 echo PHP_EOL.' <option '.$avail.' value="'.$format.'" ';
				 if (!empty ($config['format'][$i]) and ($config['format'][$i] === $format ))  
					echo ' selected = "selected" ';
				 echo ' >'.$formatname.'</option>';					
			}	
			echo PHP_EOL.'</select>';
			//show sample 20171112?		
		}
		else  {
			if (function_exists('ausers_format_'.$i)) {
				echo PHP_EOL.'<a href="" title="'.__('Default formatting function exists','amr-users').'"> ! </a>';
			}
			/*if ($ftype=='validated_value') // maybe have option to display text labelor code
				echo 'fixed';	
			else*/
			if (empty($ftype)) { // 202009
				echo PHP_EOL.'<a href="'
				.admin_url('admin.php?page=ameta-admin-nice-names.php').'#nn'.$f
				.'">'
				.__('To see formats for this field, specify the fieldtype once for all lists','amr-users')
				.'</a>';
				}
			else {
				echo PHP_EOL.__('Fixed format, or no formats available','amr-users');
			}
		}
		if (!empty($ftype)) echo ' '.'('.$ftype.')';
		echo '</td>';
		
		if (isset($sel[$i]) and (!strpos($sel[$i],'.'))) {
			
			// if not a partial cell, then can have link type
			//if (isset($sel[$i]) and !strpos($sel[$i],'.')) {			
				echo PHP_EOL.'<td><select id="links'.$l.'" '
				.' name="list['.$listindex.'][links]['.$i.']" >';
				foreach ($linktypes as $lti => $linktype ) {
					 echo PHP_EOL.' <option value="'.$lti.'" ';
					 if (!empty ($config['links'][$i]) and ($config['links'][$i] === $lti ))  
						echo ' selected = "selected" ';
					 echo ' >'.$linktype.'</option>';
					
				}	
				echo PHP_EOL.'</select>'.PHP_EOL.'</td>';
			}
			else echo PHP_EOL.'<td>-</td>';
			
		echo PHP_EOL.'</tr>';
		}
?>
	</tbody>
	</table>
	</div><!-- end userlistfield -->
	</div><!-- end wrap -->
<?php
	return;	
}

function amrmeta_before_after_fields( $listindex = 1, $sel, $config) { // 20170112
global $amain,
	$amr_nicenames;
	
		echo PHP_EOL.'<div class="clear"></div>';
		echo PHP_EOL.'<div class="userlistfields">';
		echo '<table class="widefat" style="width: auto;"><thead  style="text-align:center;"><tr>'
			.PHP_EOL.'<th>'.__('Field name','amr-users').'</th>'				
			.PHP_EOL.'<th><a href="#" title="'.__('Html to appear before if there is a value', 'amr-users').'"> '.__('Before:','amr-users').'</a></th>'
			.PHP_EOL.'<th><a href="#" title="'.__('Html to appear after if there is a value', 'amr-users').'"> '.__('After:','amr-users').'</a></th>'
			
			.PHP_EOL.'</tr></thead><tbody>';
	
			foreach ( $sel as $i => $f )		{		/* list through all the selected fields*/			
				echo PHP_EOL.'<tr>';
				$l = 'l'.$listindex.'-'.$i;
				echo '<td>'.$amr_nicenames[$i].'</td>';

						echo '<td><input type="text" size="10"  name="list['.$listindex.'][before]['.$i.']"';
						if (isset ($config['before'][$i])) echo ' value="'
						.stripslashes($config['before'][$i]).'"';  //handle slashes returned by quotes
						echo ' /></td>';  // do not use htmlentities2 here - break foreign chars

						echo '<td><input type="text" size="10"  name="list['.$listindex.'][after]['.$i.']"';
						if (isset ($config['after'][$i])) echo ' value="'
						.stripslashes($config['after'][$i]).'"';
						echo ' /></td>';
					
				echo '</tr>';
			}
		echo PHP_EOL.'</tbody></table>';
		echo PHP_EOL.'</div><!-- end userlistfield -->';
		echo PHP_EOL.'</div><!-- end wrap -->';
	return;	
}

function amrmeta_include_exclude_fields( $listindex = 1, $sel, $config) { // 20170112
global $amain,
	$amr_nicenames;
	
		echo PHP_EOL.'<div class="clear"></div>';
		echo PHP_EOL.'<div class="userlistfields">';
		echo '<table class="widefat" style="width: auto;" ><thead  style="text-align:center;"><tr>'
			.PHP_EOL.'<th>'.__('Field name','amr-users').'</th>'				
			.PHP_EOL.'<th><a href="#" title="'.__('Eg: value1,value2', 'amr-users'). ' '
			.__('Do not use spaces unless your field values have spaces.', 'amr-users')
			.'"> '
			.__('Include:','amr-users').'</a></th>'
			.PHP_EOL.'<th><a href="#" title="'.__('Tick to include a user ONLY if there is no value', 'amr-users').'"> '.__('Include ONLY if Blank:','amr-users').'</a></th>'
			.PHP_EOL.'<th><a href="#" title="'.__('Eg: value1,value2.', 'amr-users')
			.' '.__('Display the field to set up the exclusion, then you can undisplay it afterwards.', 'amr-users'). ' '
			.__('Do not use spaces unless your field values have spaces.', 'amr-users')
			.'"> '.__('But Exclude:','amr-users').'</a></th>'
			.PHP_EOL.'<th><a href="#" title="'.__('Tick to exclude a user if there is no value', 'amr-users').'"> '.__('Exclude if Blank:','amr-users').'</a></th>'

			.PHP_EOL.'</tr></thead><tbody>';
	
			foreach ( $sel as $i => $f )		{		/* list through all the selected fields*/			
				echo PHP_EOL.'<tr>';
				$l = 'l'.$listindex.'-'.$i;
				echo '<td>'.$amr_nicenames[$i].'</td>';


//	echo '<td><select name="list['.$listindex.'][included]['.$i.']"';
//	echo amr_users_dropdown ($choices, $config['included'][$i]);
//	echo '</select>';
					
					echo '<td><input type="text" size="20"  name="list['.$listindex.'][included]['.$i.']"';
					if (isset ($config['included'][$i])) echo ' value="'.implode(',',$config['included'][$i]) .'"';
					
					echo ' /></td>';
					
					$l = 'c'.$listindex.'-'.$i;
					echo '<td><input type="checkbox"  name="list['.$listindex.'][includeonlyifblank]['.$i.']"';
					if (isset ($config['includeonlyifblank'][$i]))	{
						echo ' checked="checked" />';
						if (isset ($config['excludeifblank'][$i])) /* check for inconsistency and flag */
							echo '<span style="color:#D54E21; font-size:larger;">*</span>';
					}
					else echo '/>';
					echo '</td>';
					
					$l = 'x'.$listindex.'-'.$i;
					echo '<td><input type="text" size="20" id="'.$l.'" name="list['.$listindex.'][excluded]['.$i.']"';
					if (isset ($config['excluded'][$i])) {
						if (is_array($config['excluded'][$i])) 
							$val = implode(',',$config['excluded'][$i]);
						else $val = $config['excluded'][$i];	
						echo ' value="'.$val .'"';
					}
					echo ' /></td>';

					$l = 'b'.$listindex.'-'.$i;
					echo '<td><input type="checkbox" id="'.$l.'" name="list['.$listindex.'][excludeifblank]['.$i.']"';
					if (isset ($config['excludeifblank'][$i]))	{
						echo ' checked="checked" />';
						if (isset ($config['includeonlyifblank'][$i])) /* check for inconsistency and flag */
							echo '<span style="color:#D54E21; font-size:larger;">*</span>';
					}
					else echo '/>';
					echo '</td>';
					
				echo '</tr>';
			}
		echo PHP_EOL.'</tbody></table>';
		echo PHP_EOL.'</div><!-- end userlistfield -->';
		echo PHP_EOL.'</div><!-- end wrap -->';
	return;	
}

function amrmeta_listfields( $listindex = 1) {
	global $aopt;
	global $amain;
	global $amr_nicenames, 
	$excluded_nicenames,
	$ausersadminurl;

	/* check if we have some options already in Database. - use their names, if not, use default, else overwrite .*/
	if (!($checkifusingdefault = ausers_get_option ('amr-users-nicenames')) 
		or (empty($amr_nicenames))) {
		//$text = __('Possible fields not configured! default list being used. Please build complete nicenames list.','amr-users');
		amrmeta_check_find_fields();		
		exit;
	}

		echo PHP_EOL.'<div class="wrap">'.PHP_EOL
		.'<input id="submit" class="button-primary" type="submit" name="update" value="';
		_e('Update', 'amr-users'); 
		echo '" />&nbsp; &nbsp;';

	
	$config = &$aopt['list'][$listindex];
	$sel = &$config['selected'];
	if (!empty($sel)) {
		// any to exclude?
		foreach ($sel as $i=>$s) {
			if (!empty($excluded_nicenames[$i])) unset ($sel[$i]);
		}
	}
	else $sel = array();
	/* sort our controlling index by the selected display order for ease of viewing */
	
	$keyfields = array();
	foreach ($amr_nicenames as $i => $n) {  
		if ((isset ($config['selected'][$i])) or
			(isset ($config['sortby'][$i])) or
			(isset ($config['included'][$i])) or
			(isset ($config['includeonlyifblank'][$i])) or
			(isset ($config['excluded'][$i])) or
			(isset ($config['excludeifblank'][$i])) or
			(isset ($config['sortdir'][$i])) 
			)
			$keyfields[$i] = $i;
		
	}
	
	asort($amr_nicenames);
	
	if (isset ($sel))	
		$nicenames = auser_sortbyother($amr_nicenames, $sel); /* sort for display with the selected fields first */
	else 
		$nicenames = $amr_nicenames;

	if (count ($sel) > 0) {	

		uasort ($sel,'amr_usort');
		$nicenames = auser_sortbyother($nicenames, $sel); /* sort for display with the selected fields first */
		$keyfields = auser_sortbyother($keyfields, $sel); /* sort for display with the selected fields first */																																								   
	}
	$amr_nicenames = $nicenames; //clean up variables later please
//

	
//	20170112	201703 use keyfields, not sel

	if (isset($_GET['config'])) {
		if ($_GET['config'] == 'format'){  //
			amrmeta_format_fields ( $listindex, $keyfields, $config);
			return;
		}
		if ($_GET['config'] == 'include_exclude'){  //
			amrmeta_include_exclude_fields ( $listindex, $keyfields, $config);
			return;
		}
		if ($_GET['config'] == 'before_after'){  //
			amrmeta_before_after_fields ( $listindex, $keyfields, $config);
			return;
		}
	}
//

		echo PHP_EOL.'<div class="clear"></div>';
		echo PHP_EOL.'<div class="userlistfields">';
		echo '<table class="widefat" style="width: auto;"><thead  style="text-align:center;"><tr>'
			.PHP_EOL.'<th>'.__('Field name','amr-users').'</th>';
		
// display order
		echo PHP_EOL.'<th><a href="#" title="'.__('Blank to hide, Enter a number to select and specify column order.  Eg: 1 2 6 8', 'amr-users').'"> '.__('Display order','amr-users').'</a></th>'

			.PHP_EOL.'<th><a href="#" title="'
				.__('Enter integers, need not be contiguous', 'amr-users')
				.' '
				.__('Maximum 2 sort level. Can switch off display.', 'amr-users')
				.'"> '.__('Sort Order:','amr-users').'</a></th>'
			.PHP_EOL.'<th><a href="#" title="'.__('For sort order.  Default is ascending', 'amr-users').'"> '.__('Sort Descending:','amr-users').'</a></th>'
						
			.PHP_EOL.'</tr></thead><tbody>';
	
			foreach ( $nicenames as $i => $f )		{		/* list through all the possible fields*/			
				echo PHP_EOL.'<tr>';
				$l = 'l'.$listindex.'-'.$i;
				if ($i === 'comment_count') $f .= '<a title="'.__('Explanation of comment total functionality','amr-users')
				.'" href="https://wpusersplugin.com/comment-totals-by-authors/">**</a>';
				echo '<td>'.$f .'</td>';
					echo '<td><input type="text" size="2" id="'.$l.'" name="list['.$listindex.'][selected]['.$i.']"'. 
				' value="';
				if (isset($sel[$i]) or 
					(!empty($config['included'][$i])) or 
					(!empty($config['excluded'][$i])) or 
					(!empty($config['excludeifblank'][$i])) or 
					(!empty($config['includeonlyifblank'][$i])) or 
					(!empty($config['sortby'][$i])) or
					(!empty($config['sortdir'][$i])) 
					)  {
									
					if (isset($sel[$i]))	echo $sel[$i];			
					echo '" /></td>';

					$l = 's'.$listindex.'-'.$i;
					echo '<td>'
					.'<input type="text" size="2" id="'.$l.'" name="list['.$listindex.'][sortby]['.$i.']"';
					if (isset ($config['sortby'][$i]))  echo ' value="'.$config['sortby'][$i] .'"';
					echo ' /></td>'
					.'<td><input type="checkbox" id="sd'.$l.'" name="list['.$listindex.'][sortdir]['.$i.']"';
					 echo ' value="SORT_DESC"';
					if (isset ($config['sortdir'][$i]))  echo ' checked="checked"';
					echo ' />'
					.'</td>';
				
					}
				else {
					echo '" /></td>';
					echo '<td>&nbsp;-&nbsp;</td>'
					.'<td>&nbsp;-&nbsp;</td>';
				}
				
				echo '</tr>';
			}
		echo PHP_EOL.'</tbody></table>';
		echo PHP_EOL.'</div><!-- end userlistfield -->';
		echo PHP_EOL.'</div><!-- end wrap -->';
	return;	
	}
	
function au_buildcache_link($text, $i,$name) { // to refresh now!
global $ausersadminurl;
	$t = PHP_EOL.'<a style="color: green;" href="'.
		wp_nonce_url(
		esc_url(
		add_query_arg(array(
		'page'=>'ameta-admin-configure.php',
		'rebuildwarning'=>'1',
		'ulist'=>$i),$ausersadminurl),
		'amr-meta'))
		.'" title="'.__('Rebuild list', 'amr-users').'" >'
		.$text
		.'</a>';
	return ($t);
}
 	
function au_buildcache_view_link($text, $i,$name) { // to refresh now!
global $ausersadminusersurl;
	$t = PHP_EOL.'<a style="color: green;" href="'.
		esc_url(add_query_arg(array(
		'page'=>'ameta-list.php?ulist='.$i,
		'refresh'=>'1')
		,$ausersadminusersurl.''))
		.'" title="'.__('Rebuild list in realtime - could be slow!', 'amr-users').'" >'
		.$text
		.'</a>';
	return ($t);
}
 	
function au_buildcachebackground_link() {//*** fix
	global $ausersadminusersurl;
	$t = '<a href="'.wp_nonce_url(esc_url($ausersadminusersurl.'&amp;am_page=rebuildcache','amr-meta'))
		.'" title="'.__('Build Cache in Background', 'amr-users').'" >'
		.__('Build Cache for all', 'amr-users')
		.'</a>';
	return ($t);
}
 	
function amrmeta_confighelp() {
// style="background-image: url(images/screen-options-right-up.gif);"


	$html = '<p>'.__('Almost all possible user fields that have data are listed in field list (nice names).  If you have not yet created data for another plugin used in your main site, then there may be no related data here.  Yes this is a looooong list, and if you have a sophisticated membership system, it may be even longer than others.  The fields that you are working with will be sorted to the top, once you have defined their display order.', 'amr-users')
	.'</p><p>'
	.__('After a configuration change, the cached listing must be rebuilt for the view to reflect the change.', 'amr-users')
	.'</p><ol><li>'
	.__('Enter a number in the display order column to select a field for display and to define the display order.', 'amr-users')
	.'</li><li>'
	.__('Enter a number (1-2) to define the sort order for your list', 'amr-users')
	.'</li><li>'
	.__('Use decimals to define ordered fields in same column (eg: first name, last name)', 'amr-users')
	.'</li><li>'
	.__('If a sort order should be descending, such as counts or dates, click "sort descending" for that field.', 'amr-users')
	.'</li><li>'
	.__('From the view list, you will see the data values.  If you wish to include or exclude a record by a value, note the value, then enter that value in the Include or Exclude Column.  Separate the values with a comma, but NO spaces.', 'amr-users')
	.__('Note: Exclude and Include blank override any other value selection.', 'amr-users')
	.'</li></ol>';
	
	return($html);
}

function list_configurable_lists() {
global $amr_current_list, $amain,$ausersadminurl;

	echo PHP_EOL.'<div class="clear"> </div>'.PHP_EOL;	
	
	amr_users_dropdown_form ($amain['names'], $amr_current_list);

	echo PHP_EOL;
	return;
}	

function amrmeta_configure_page() {
	global $aopt;
	global $amr_nicenames;
	global $pluginpage;
	global $amain, $amr_current_list;	

	$amr_current_list = key($amain['names']); //202001 in case we deleted first list
	// check if we are changing list 
	if (!empty($_REQUEST['select'])) {
		if (!empty($_REQUEST['ulist']) ) 
			$amr_current_list = (int) ($_REQUEST['ulist']);
	}
	else {
		if (!empty($_REQUEST['ulist']) ) 
			$amr_current_list = (int) ($_REQUEST['ulist']);
	}
	ameta_options();  // should handle emptiness etc

	amr_meta_main_admin_header(__('Configure a user list','amr-users')
	.' : '.$amain['names'][$amr_current_list]);
	amr_meta_admin_headings ($plugin_page=''); // does the nonce check  and formstartetc
	$ulist = $amr_current_list;	
	list_configurable_lists();  // to allow selection of which to configure
	
	amr_userlist_submenu ( $amr_current_list );


	$tabs['select']	= __('Fields & Sort', 'amr-users');
	$tabs['format']	= __('Format', 'amr-users');
	$tabs['include_exclude'] 	= __('Include Exclude', 'amr-users');
	$tabs['before_after'] 	= __('Before &amp; After', 'amr-users');
	$tabs['filtering'] 	= __('Filtering', 'amr-users');
	$tabs['navigation'] 	= __('Navigation', 'amr-users');
	$tabs['grouping'] 	= __('Grouping', 'amr-users');
	
	$configtab = 'select';
	
	if (!empty($_GET['config']))   
		$configtab = sanitize_text_field($_GET['config']);
		
	amr_users_do_tabs_config ($tabs, $configtab);
	
//	else
	if (isset ($_REQUEST['rebuild'])) { /* can only do one list at a time in realtime */
		amr_rebuild_in_realtime_with_info ($ulist);
		echo ausers_form_end();
		return;
	}/* then we have a request to kick off cron */

	elseif (!empty($_REQUEST['rebuildback']))  { /*  */	
		amr_request_cache_with_feedback(); 	
		echo ausers_form_end();		
		return;			
	}
	
	elseif (!empty($_REQUEST['rebuildwarning']))  { /*  */	
		amr_rebuildwarning($ulist); 
		echo ausers_form_end();
		return;			
	}	
	
//	elseif (isset ($_REQUEST['custom_navigation'])) {
	elseif (isset($_GET['config']) and ($_GET['config'] == 'navigation')) {
		if (function_exists('amrmeta_custom_navigation_page')) {
			amrmeta_custom_navigation_page($ulist);
			echo ausers_form_end();
			return;
		}
		else {
			echo '<p>'.__('Requires amr-users-plus functionality','amr-users').'</p>'; //20170201
			return;
			}
	}
	
	//elseif (isset ($_REQUEST['grouping'])) {
	elseif (isset($_GET['config']) and ($_GET['config'] == 'grouping')) {
		if (function_exists('amr_grouping_admin_form')) {
			amr_grouping_admin_form($ulist);
			echo ausers_form_end();
			return;
		}
		else {
			echo '<p>'.__('Grouping Function not active', 'amr-users').'</p>';
			return;
			}
	}

	elseif (isset($_GET['config']) and ($_GET['config'] == 'filtering'))	{
		
		if (!function_exists('amrmeta_filtering_page')) {
			echo '<p>'.__('Requires amr-users-plus functionality','amr-users').'</p>'; //20170201
			return;
		}	
		amrmeta_filtering_page($ulist); // doesn't exist?
		echo ausers_form_end();
		return;	
	}
	
	elseif (isset ($_POST['action']) and  ($_POST['action'] == "save")) { 
//	echo '<pre>'.print_r($_POST, true).'</pre>';
		if (isset ($_POST['updateoverview']) ) {			
			amrmeta_validate_overview();	
		}
//		elseif (isset ($_POST['update']) ) {
			
			if (!amrmeta_validate_listfields($ulist)) {
				amr_users_message(__('List Fields Validation failed', 'amr-users')); 
			}	
//		}		

//		elseif (isset ($_POST['configure']) ) {
			// ulist already set above, so will just configure			
//		}
	}

	amrmeta_listfields($ulist);
	echo ausers_form_end();
}

