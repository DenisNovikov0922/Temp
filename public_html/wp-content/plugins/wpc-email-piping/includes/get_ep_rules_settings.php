
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$email_piping_rules = get_terms([
	'taxonomy'   => 'wpsc_ep_rules',
	'hide_empty' => false,
	'orderby'    => 'meta_value_num',
	'order'    	 => 'ASC',
	'meta_query' => array('order_clause' => array('key' => 'wpsc_en_rule_load_order')),
]);

?>
<h4>
	<?php _e('Email Piping Rules','wpsc-ep');?>
	<button style="margin-left:10px;" class="btn btn-success btn-sm" id="wpsc_ep_piping_rules_form_btn"onclick="wpsc_get_ep_rules_form_field();"><?php _e('+Add New','wpsc-ep');?></button>
</h4>
<ul class="wpsc-sortable">
	<?php foreach ( $email_piping_rules as $emails ) :  
    ?>
		<li class="ui-state-default" data-id="<?php echo $emails->term_id?>">
			<div class="wpsc-flex-container" style="background-color:#1E90FF;color:#fff;">
				<div class="wpsc-sortable-handle"><i class="fa fa-bars"></i></div>
				<div class="wpsc-sortable-label"><?php echo $emails->name?></div>
				<div class="wpsc-sortable-edit" onclick="wpsc_get_edit_ep_rules(<?php echo $emails->term_id?>);"><i class="fa fa-edit"></i></div>
				<div class="wpsc-sortable-delete" onclick="delete_ep_rules_form_field(<?php echo $emails->term_id?>);"><i class="fa fa-trash"></i></div>
			</div>
		</li>
	<?php endforeach;?>
</ul>
<script>
jQuery(function(){
	jQuery( ".wpsc-sortable" ).sortable({ handle: '.wpsc-sortable-handle' });
	jQuery( ".wpsc-sortable" ).on("sortupdate",function(event,ui){
		var ids = jQuery(this).sortable( "toArray", {attribute: 'data-id'} );
		var data = {
			action: 'wpsc_set_ep_rule_list_order',
		  field_ids : ids
		};
		jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
			var response = JSON.parse(response_str);
			if (response.sucess_status=='1') {
				jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
			}
			jQuery('#wpsc_alert_success').slideDown('fast',function(){});
			setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
		});
	});
});

function wpsc_get_ep_rules_form_field(){
  
  jQuery('.wpsc_setting_col2').html(wpsc_admin.loading_html);
  var data = {
    action: 'wpsc_get_ep_rules_form_field',
  };
  jQuery.post(wpsc_admin.ajax_url, data, function(response) {
    jQuery('.wpsc_setting_col2').html(response);
  });
}

function wpsc_get_edit_ep_rules(term_id){	
	jQuery('.wpsc_setting_col2').html(wpsc_admin.loading_html);	
	var data = {
		action: 'wpsc_get_edit_ep_rules_form_field',
		term_id: term_id				
	};
	jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
		  jQuery('.wpsc_setting_col2').html(response_str);		
	});
}

function delete_ep_rules_form_field(term_id){	
	var flag = confirm(wpsc_admin.are_you_sure);
	if (flag) {
		var data = {
			action: 'wpsc_delete_ep_rules_form_field',		
			term_id : term_id
		};
		jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
			var response = JSON.parse(response_str);
			if (response.sucess_status=='1') {
				jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
				jQuery('#wpsc_alert_success').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
				wpsc_get_ep_rules_settings();
			} else {
				jQuery('#wpsc_alert_error .wpsc_alert_text').text(response.messege);
				jQuery('#wpsc_alert_error').slideDown('fast',function(){});
				setTimeout(function(){ jQuery('#wpsc_alert_error').slideUp('fast',function(){}); }, 3000);
			}
		});
	}
}
</script>