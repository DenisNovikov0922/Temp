<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction,$current_user;
$wpsc_sf_age = get_option('wpsc_sf_age');
?>

<form id="frm_sf_email_notification_settings" action="javascript:wpsc_set_sf_email_notification_settings();" method="post" >

	<h4><?php _e('Feedback Email','wpsc-sf');?></h4>
	
	<div class="form-group">
		<label><?php _e('Age','wpsc-sf');?></label>
		<p class="help-block"><?php _e('Select time after which feedback email should be sent to customer. Set \'0\' to disable email.','wpsc-sf');?></p>
		<div class="row">
			<div class="col-md-2" style="padding-left:0;">
				<input type="number" class="form-control" name="wpsc_sf_age" id="wpsc_sf_age" value="<?php echo $wpsc_sf_age?>" />
			</div>
			<div class="col-md-2" style="padding-left:0;">
				<select class="form-control" name="wpsc_sf_age_unit">
					<?php
					$wpsc_sf_age_unit = get_option('wpsc_sf_age_unit');
					?>
					<option <?php echo $wpsc_sf_age_unit == 'h' ? 'selected="selected"' : ''?> value="h"><?php _e('Hours','wpsc-sf')?></option>
					<option <?php echo $wpsc_sf_age_unit == 'd' ? 'selected="selected"' : ''?> value="d"><?php _e('Days','wpsc-sf')?></option>
				</select>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label for="wpsc_sf_subject"><?php _e('Subject','wpsc-sf');?></label>
		<p class="help-block"><?php _e('Subject for email to send.','wpsc-sf');?></p>
		<input type="text" class="form-control" name="wpsc_sf_subject" id="wpsc_sf_subject" value="<?php echo get_option('wpsc_sf_subject')?>" />
	</div>
	
	<div class="form-group">
		<label for="wpsc_sf_email_body"><?php _e('Body','wpsc-sf');?></label>	
		<p class="help-block"><?php _e('Body for email to send. Use macros for ticket specific details. Macros will get replaced by its value while sending an email.','wpsc-sf');?></p>
		<div class="text-right">
			<button id="visual" class="wpsc-switch-editor wpsc-switch-editor-active " type="button" onclick="wpsc_get_tinymce_email('wpsc_sf_email_body','html_body');"><?php _e('Visual','wpsc-sf');?></button>
			<button id="text" class="wpsc-switch-editor" type="button" onclick="wpsc_get_textarea_email('wpsc_sf_email_body')"><?php _e('Text','wpsc-sf');?></button>
		</div>
		<textarea class="form-control" name="wpsc_sf_email_body" id="wpsc_sf_email_body"><?php echo stripslashes(get_option('wpsc_sf_email_body'))?></textarea>
		<div class="row attachment_link">
			<span onclick="wpsc_get_templates(); "><?php _e('Insert Macros','wpsc-sf') ?></span>
		</div>
	</div>

    <button type="submit" class="btn btn-success" id="wpsc_rating_save_changes_btn"><?php _e('Save Changes','wpsc-sf');?></button>
	<img class="wpsc_submit_wait" style="display:none;" src="<?php echo WPSC_PLUGIN_URL.'asset/images/ajax-loader@2x.gif';?>">
	<input type="hidden" name="action" value="wpsc_set_sf_email_notification_settings" />

</form>

<script>

	function wpsc_set_sf_email_notification_settings(){
	  
	  jQuery('.wpsc_submit_wait').show();
	  var dataform = new FormData(jQuery('#frm_sf_email_notification_settings')[0]);
	  
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
	    if (response.sucess_status=='1') {
	      jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
	    }
	    jQuery('#wpsc_alert_success').slideDown('fast',function(){});
	    setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
	  });
	  
	}

	tinymce.remove();
	tinymce.init({ 
	  selector:'#wpsc_sf_email_body',
	  body_id: 'thankyou_body',
	  menubar: false,
		statusbar: false,
	  height : '200',
	  plugins: [
	      'lists link image directionality'
	  ],
	  image_advtab: true,
	  toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
	  branding: false,
	  autoresize_bottom_margin: 20,
	  browser_spellcheck : true,
	  relative_urls : false,
	  remove_script_host : false,
	  convert_urls : true,
		setup: function (editor) {
	  }
	});
	

	function wpsc_get_tinymce_email(selector,body_id){
	
		jQuery('#visual_header').addClass('btn btn-primary visual_header');
		jQuery('#text_header').removeClass('btn btn-primary text_header');
		jQuery('#text_header').addClass('btn btn-default text_header');
		jQuery('#text').removeClass('wpsc-switch-editor-active');
        jQuery('#visual').addClass('wpsc-switch-editor-active');
		tinymce.init({ 
			selector:'#'+selector,
			body_id: body_id,
			menubar: false,
			statusbar: false,
			height : '200',
			plugins: [
			'lists link image directionality'
			],
			image_advtab: true,
			toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
			branding: false,
			autoresize_bottom_margin: 20,
			browser_spellcheck : true,
			relative_urls : false,
			remove_script_host : false,
			convert_urls : true,
			setup: function (editor) {
			}
		});
	}

	function wpsc_get_textarea_email(selector){

		jQuery('#visual_body').removeClass('btn btn-primary visual_body');
		jQuery('#visual_body').addClass('btn btn-default visual_body');
		jQuery('#text_body').addClass('btn btn-primary text_body');
		tinymce.remove('#'+selector);
        jQuery('#text').addClass('wpsc-switch-editor-active');
        jQuery('#visual').removeClass('wpsc-switch-editor-active');
	}
</script>