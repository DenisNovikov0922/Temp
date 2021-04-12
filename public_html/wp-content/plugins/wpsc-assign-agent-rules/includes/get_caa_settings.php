<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$conditional_agents_field    = get_term_by('slug','conditional_agent_assign','wpsc_ticket_custom_fields');
$conditional_agents_label    = get_term_meta($conditional_agents_field->term_id,'wpsc_tf_label',true);
?>
<div>
	<ul class="nav nav-tabs">
		<li role="presentation" class="tab active" onclick="wpsc_change_tab(this,'add_caa');"><a href="#"><?php _e('Assign Rules','wpsc-caa');?></a></li>
		<li role="presentation" class="tab" onclick="wpsc_change_tab(this,'auto_assign_first_responder');"><a href="#"><?php _e('Other Settings','wpsc-caa');?></a></li>
	</ul>
</div>

<div id="add_caa" class="tab_content visible" style="margin-top:20px;">
	<h4 style="margin-bottom:20px;margin-top:20px;">
		<?php _e('Assign Agent Rules','wpsc-caa')?>
		<button type="button" style="margin-left: 6px;" onclick="wpsc_get_add_new_agent_rule();" class="btn btn-sm btn-success"><?php _e('+ Add New','wpsc-caa');?></button>
	</h4>
	<?php do_action('wpsc_get_caa_settings');?>
	<?php
	
	$agent_role = get_terms([
		'taxonomy'   => 'wpsc_caa',
		'hide_empty' => false,
		'orderby'    => 'meta_value_num',
		'order'    	 => 'ASC',
		'meta_query' => array('order_clause' => array('key' => 'load_order')),
	]);
	?>
	<div class="wpsc_padding_space"></div>
	<table class="table table-striped table-hover">
		<tr>
			<th><?php _e('Title','wpsc-caa')?></th>
			<th><?php _e('Assigned Agent Name','wpsc-caa')?></th>
			<th><?php _e('Actions','wpsc-caa')?></th>
		</tr>
		
		<?php foreach ( $agent_role as $agent ) :
			$agent_term_ids = get_term_meta( $agent->term_id, 'agent_ids',true);
			$agents = array();
				
			foreach ($agent_term_ids as $key => $agent_id) {
				if($agent_id){
					$agent_name = get_term_meta( $agent_id, 'label',true);
					if($agent_name){
						$agents[]= $agent_name;
				  }
				}
			}
			$agents_str = implode(", ",$agents);
			?>
			<tr>
				<td><?php echo $agent->name?></td>
				<td><?php echo $agents_str?></td>
				<td>
					<div class="wpsc_flex">
						<div onclick="wpsp_get_edit_condition(<?php echo $agent->term_id;?>);" style="cursor:pointer;"><i class="fa fa-edit"></i></div>
						<div onclick="wpsc_delete_agent_condition(<?php echo $agent->term_id;?>);" style="cursor:pointer; padding-left: 10px;"><i class="fa fa-trash"></i></div>
					</div>
				</td>
			</tr>
		<?php endforeach;?>
	</table>
	</div>

	<div id="auto_assign_first_responder" class="tab_content hidden" style="margin-top:20px;">
		<form id="wpsc_caa_other_settings" method="post" action="javascript:wpsc_set_other_settings()">
			<h4 style="margin-bottom:20px;margin-top:20px;">
				<?php _e('Other Settings','wpsc-caa')?></h4>
				
				<div class="form-group">
					<label for="auto_assign_first_responder"><?php _e('Auto assign first responder','wpsc-caa')?></label>
					<p class="help-block"><?php _e('First responder agent will be automatically assigned to unassigned ticket.','wpsc-caa') ?></p>
					<select class="form-control" name="wpsc_set_assign_auto_responder" id="wpsc_set_assign_auto_responder">
						
						<option <?php echo get_option('wpsc_assign_auto_responder')=="0"?'selected="selected"':''; ?> value="0"><?php _e('Disable','wpsc-caa')?></option>
						<option <?php echo get_option('wpsc_assign_auto_responder')=="1"?'selected="selected"':''; ?> value="1"><?php _e('Enable','wpsc-caa')?></option>
					</select>
				</div>
				
				<button type="submit" class="btn btn-success" id="wpsc_other_settings_btn"><?php _e('Save Changes','wpsc-caa');?></button>
				<img class="wpsc_submit_wait" style="display:none;" src="<?php echo WPSC_PLUGIN_URL.'asset/images/ajax-loader@2x.gif';?>">
				<input type="hidden" name="action" value="wpsc_set_other_settings" />
			</form>
		</div>
		
<script>
	function wpsc_get_add_new_agent_rule() {
		wpsc_modal_open('<?php _e('Add New Agent Rule','wpsc-caa')?>');
		var data = {
			action : 'wpsc_get_add_new_agent_rule'
		};
		jQuery.post(wpsc_admin.ajax_url, data, function(response_str){
			var response = JSON.parse(response_str);
			jQuery('#wpsc_popup_body').html(response.body);
			jQuery('#wpsc_popup_footer').html(response.footer);
			jQuery('#wpsc_caa_title').focus();
		});
	}

	function wpsc_set_add_new_agent_rule() {
		var title = jQuery('#wpsc_caa_title').val().trim();
		if(title.length==0){
			alert('<?php _e('Title should not be empty.','wpsc-caa')?>');
			return;
		}
		var conditions = wpsc_condition_parse('wpsc_add_agent_rule_conditions');
	  if(!wpsc_condition_validate(conditions)) {
	    	alert('<?php _e('At least one condition required.','wpsc-caa')?>');
	    return;
	  }
		
		var dataform = new FormData(jQuery('#wpsc_frm_add_agent')[0]);
		dataform.append('conditions',JSON.stringify(conditions));
		jQuery('.wpsc_popup_action').text('<?php _e('Please wait...', 'wpsc')?>');
		jQuery('.wpsc_popup_action, #wpsc_popup_body input').attr("disabled", "disabled");
		jQuery.ajax({
			url: wpsc_admin.ajax_url,
			type : 'POST',
			data : dataform,
			processData: false,
			contentType: false
		})
		.done(function (response_str) {
			wpsc_modal_close();
			var response = JSON.parse(response_str);
			if (response.sucess_status=='1') {
				jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
				jQuery('#wpsc_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
				wpsc_get_caa_settings();
			} 
			else {
				jQuery('#wpsc_alert_error .wpsc_alert_text').text(response.messege);
				jQuery('#wpsc_alert_error').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wpsc_alert_error').slideUp('fast',function(){}); }, 3000);
			}
		});
	}

	function wpsp_get_edit_condition(term_id) {
		wpsc_modal_open('<?php _e('Edit Condition','wpsc-caa')?>');
		var data = {
			action: 'wpsp_get_edit_condition',
			term_id : term_id
		};
		jQuery.post(wpsc_admin.ajax_url, data, function(response_str){
			var response = JSON.parse(response_str);
			jQuery('#wpsc_popup_body').html(response.body);
			jQuery('#wpsc_popup_footer').html(response.footer);
			jQuery('#wpsc_caa_title').focus();
		});
	}
	function wpsc_set_edit_condition() {
		var title = jQuery('#wpsc_caa_title').val().trim();
		
		if(title.length==0){
			alert('<?php _e('Title should not be empty.','wpsc-caa')?>');
			return;
		}
		var conditions = wpsc_condition_parse('wpsc_edit_agent_rule_conditions');
	  if(!wpsc_condition_validate(conditions)) {
	    	alert('<?php _e('At least one condition required.','wpsc-caa')?>');
	    return;
	  }
		var dataform = new FormData(jQuery('#wpsc_frm_add_agent')[0]);
		dataform.append('conditions',JSON.stringify(conditions));
		jQuery('.wpsc_popup_action').text('<?php _e('Please wait ...','wpsc')?>');
		jQuery('.wpsc_popup_action, #wpsc_popup_body input').attr("disabled", "disabled");
		jQuery.ajax({
			url: wpsc_admin.ajax_url,
			type: 'POST',
			data: dataform,
			processData: false,
			contentType: false
		})
		.done(function (response_str) {
			wpsc_modal_close();
			var response = JSON.parse(response_str);
			if (response.sucess_status=='1') {
				jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
				jQuery('#wpsc_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
				wpsc_get_caa_settings();
			} 
			else {
				jQuery('#wpsc_alert_error .wpsc_alert_text').text(response.messege);
				jQuery('#wpsc_alert_error').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wpsc_alert_error').slideUp('fast',function(){}); }, 3000);
			}
		});
	}

	function wpsc_delete_agent_condition(term_id) {
		if(!confirm('<?php _e('Are you sure to delete this condition?','wpsc-caa')?>')) return;
		var data = {
			action : 'wpsc_delete_agent_condition',
			term_id : term_id
		};
		jQuery.post(wpsc_admin.ajax_url, data, function(response_str){
			var response = JSON.parse(response_str);
			if (response.sucess_status=='1') {
				jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
				jQuery('#wpsc_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
				wpsc_get_caa_settings();
			}
		});
	}
		
	function wpsc_change_tab(e,content_id){
		jQuery('.tab').removeClass('active');
		jQuery(e).addClass('active');
		jQuery('.tab_content').removeClass('visible').addClass('hidden');
		jQuery('#'+content_id).removeClass('hidden').addClass('visible');
	}
		
	function wpsc_set_other_settings(){
		jQuery('.wpsc_submit_wait').show();
		var dataform = new FormData(jQuery('#wpsc_caa_other_settings')[0]);

		jQuery.ajax({
			url: wpsc_admin.ajax_url,
			type: 'POST',
			data: dataform,
			processData: false,
			contentType: false
		})
		.done(function (response_str) {
			var response = JSON.parse(response_str);
			jQuery('.wpsc_submit_wait').hide();
			jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
			jQuery('#wpsc_alert_success').slideDown('fast',function(){});
			setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
		});
	}
	
	</script>