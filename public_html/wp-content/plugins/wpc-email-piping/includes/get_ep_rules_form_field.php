<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpdb,$post;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

?>
<h4 style="margin-bottom:20px;"><?php _e('Add New Rule','wpsc-ep');?></h4>
<form id="wpsc_frm_general_settings" method="post" action="javascript:set_ep_rules_form_field();">
  
  <div class="form-group">
    <label for="wpsc_ep_rule_title"><?php _e('Title','wpsc-ep');?></label>
    <p class="help-block"><?php _e('Title to show in rule list. It will be easier to know what this rule for.','wpsc-ep');?></p>
    <input type="text" class="form-control" name="wpsc_ep_rule_title" id="wpsc_ep_rule_title" value="" />
  </div>
  
  <div class="form-group">
    <label for="wpsc_ep_to_address"><?php _e('To Address','wpsc-ep');?></label>
    <p class="help-block"><?php _e('Please enter the email addresses to match from which email was forworded for piping. One email per line. You can insert email pattern like *@paypal.com, admin@*, etc. If this condition matched, no further conditions checked and fields will get applied to ticket.','wpsc-ep');?></p>
    <textarea class="form-control" style="height:80px !important;" name="wpsc_ep_to_address" id="wpsc_ep_to_address"></textarea>
  </div>
  
  <div class="form-group">
    <label for="wpsc_ep_has_words"><?php _e('Has Words','wpsc-ep');?></label>
    <p class="help-block"><?php _e('Please enter words/strings to match in email subject or body text. One string per line. You can use patterns like abc,xyz,*abc,xyz* etc. If this condition matched, fields will get applied to ticket regardless of other conditions.','wpsc-ep');?></p>
    <textarea class="form-control" style="height:80px !important;" name="wpsc_ep_has_words" id="wpsc_ep_has_words"></textarea>
  </div>
  
  <h4 style="margin-bottom:20px;margin-top:40px;"><?php _e('Select Fields','wpsc-ep');?></h4>
	
	<?php
	$status          = get_term_by('slug','ticket_status','wpsc_ticket_custom_fields');
	$label           = get_term_meta($status->term_id,'wpsc_tf_label',true);
	$extra_info      = get_term_meta($status->term_id,'wpsc_tf_extra_info',true);		
	?>
	<div class="form-group">
		<label for="ticket_status"><?php echo $label?></label>
		<p class="help-block"><?php echo $extra_info?></p>
		<select id="ticket_status" class="form-control" name="ticket_status" >
			<?php
			$statuses = get_terms([
				'taxonomy'   => 'wpsc_statuses',
				'hide_empty' => false,
				'orderby'    => 'meta_value_num',
				'order'    	 => 'ASC',
				'meta_query' => array('order_clause' => array('key' => 'wpsc_status_load_order')),
			]);
			foreach ( $statuses as $status ) :
				echo '<option value="'.$status->term_id.'">'.$status->name.'</option>';
			endforeach;
			?>
		</select>
	</div>
	
	<div class="form-group">
			
		<?php
		$category   = get_term_by('slug','ticket_category','wpsc_ticket_custom_fields');
		$label      = get_term_meta($category->term_id,'wpsc_tf_label',true);
		$extra_info = get_term_meta($category->term_id,'wpsc_tf_extra_info',true);
		?>
		
		<label for="ticket_category"><?php echo $label?></label>
		<p class="help-block"><?php echo $extra_info?></p>
		<select id="ticket_category" class="form-control" name="ticket_category" >
			<?php
			$categories = get_terms([
				'taxonomy'   => 'wpsc_categories',
				'hide_empty' => false,
				'orderby'    => 'meta_value_num',
				'order'    	 => 'ASC',
				'meta_query' => array('order_clause' => array('key' => 'wpsc_category_load_order')),
			]);
			foreach ( $categories as $category ) :
				echo '<option value="'.$category->term_id.'">'.$category->name.'</option>';
			endforeach;
			?>
		</select>
			
	</div>
	<div class="form-group">
			
		<?php
		$priority   = get_term_by('slug','ticket_priority','wpsc_ticket_custom_fields');
		$label      = get_term_meta($priority->term_id,'wpsc_tf_label',true);
		$extra_info = get_term_meta($priority->term_id,'wpsc_tf_extra_info',true);
		?>
				
		<label for="ticket_priority"><?php echo $label?></label>
		<p class="help-block"><?php echo $extra_info?></p>
		<select id="ticket_priority" class="form-control" name="ticket_priority">
			<?php
			$priorities = get_terms([
				'taxonomy'   => 'wpsc_priorities',
				'hide_empty' => false,
				'orderby'    => 'meta_value_num',
				'order'    	 => 'ASC',
				'meta_query' => array('order_clause' => array('key' => 'wpsc_priority_load_order')),
			]);
			foreach ( $priorities as $priority ) :
				echo '<option value="'.$priority->term_id.'">'.$priority->name.'</option>';
			endforeach;
			?>
		</select>
		
	</div>		
	<?php
	$fields = get_terms([
				'taxonomy'   => 'wpsc_ticket_custom_fields',
				'hide_empty' => false,
				'orderby'    => 'meta_value_num',
				'order'    	 => 'ASC',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'       => 'agentonly',
						'value'     => array(0,1),
						'compare'   => 'IN'
					),
					array(
						'key'       => 'wpsc_tf_type',
						'value'     => '0',
						'compare'   => '>'
					),
				),
				]);
		
		include WPSC_EP_ABSPATH . 'includes/class-email-rule-format.php';

		$ticket_fields = new WPSC_Email_rule_set();
		if($fields){
			foreach ($fields as $field) {				
					$ticket_fields->print_field_format($field,$field->term_id);
			}
		}						
	 ?>
	
	<div class = "form-group">
		<label for="wpsc_additional_receipients"><?php _e('Additional Recepients','wpsc-ep'); ?></label>
		<p class="help-block"></p>
		<textarea class="wpsc_textarea" name="wpsc_ticket_et_user" id="wpsc_ticket_et_user" spellcheck="false"></textarea>
	</div>

	<div>
		<button type="submit" class="btn btn-success" id="wpsc_save_changes_ep_rules_btn"><?php _e('Save Changes','wpsc-ep');?></button>
	  <img class="wpsc_submit_wait" style="display:none;" src="<?php echo WPSC_PLUGIN_URL.'asset/images/ajax-loader@2x.gif';?>">
	  <input type="hidden" name="action" value="set_ep_rules_form_field" />			
	</div>
  
</form>

<script>
jQuery(document).ready(function(){
	jQuery('.wpsc_drop_down,.wpsc_checkbox,.wpsc_radio_btn,.wpsc_category,.wpsc_priority').change(function(){
		wpsc_reset_visibility();
	});
	jQuery( ".wpsc_date" ).datepicker({
			dateFormat : '<?php echo get_option('wpsc_calender_date_format')?>',
			showAnim : 'slideDown',
			changeMonth: true,
			changeYear: true,
			yearRange: "-50:+50",
	});
	
	jQuery('.wpsc_datetime').datetimepicker({
			 dateFormat : '<?php echo get_option('wpsc_calender_date_format')?>',
				showAnim : 'slideDown',
				changeMonth: true,
				changeYear: true,
			 timeFormat: 'HH:mm:ss'
		 });

	jQuery( "#customer_name" ).autocomplete({
		minLength: 1,
		appendTo: jQuery("#wpsc_agent_name").parent(),
		source: function( request, response ) {
			var term = request.term;
			request = {
				action: 'wpsc_tickets',
				setting_action : 'get_users',
				term : term
			}
			jQuery.getJSON( wpsc_admin.ajax_url, request, function( data, status, xhr ) {
				response(data);
			});	
		},
		select: function (event, ui) {
			jQuery('#customer_name').val(ui.item.value);
			jQuery('#customer_email').val(ui.item.email);
		}
	});
});

 function set_ep_rules_form_field($field_id){   
  // jQuery('.wpsc_submit_wait').show();
  var dataform = new FormData(jQuery('#wpsc_frm_general_settings')[0]);
  dataform.append('action', 'wpsc_set_ep_rules_form_field');
   
  jQuery.ajax({
    url: wpsc_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    var response = JSON.parse(response_str);    
    if (response.sucess_status=='1') {
      jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
			wpsc_get_ep_rules_settings();
    }
    jQuery('#wpsc_alert_success').slideDown('fast',function(){});
    setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
  });
}

</script>
