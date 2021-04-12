<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}
?>
<form id="wpsc_frm_ep_en_settings" method="post" action="javascript:wpsc_set_ep_en_setting();">
    <h4><?php _e('Close ticket warning email','wpsc-ep');?></h4>
    <p class="help-block"><?php _e('If replies for closed ticket is disabled, this email will get sent back to customer.','wpsc-ep');?></p>
    <div class="form-group">
		<label for="wpsc_ct_warn_email_subject"><?php _e('Email Subject','wpsc-ep');?></label>
	    <p class="help-block"><?php _e('Subject for email to send.','wpsc-ep');?></p>
	    <input type="text" class="form-control" name="wpsc_ct_warn_email_subject" id="wpsc_ct_warn_email_subject" value="<?php echo get_option('wpsc_ct_warn_email_subject','')?>" />
   </div>

   <div class="form-group">
        <label for="wpsc_ct_warn_email_body"><?php _e('Email Body','wpsc-ep');?></label>
        <p class="help-block"><?php _e('Body for email to send. Use macros for ticket specific details. Macros will get replaced by its value while sending an email.','wpsc-ep');?></p>
        <div class="text-right">
			    <button id="visual" class="wpsc-switch-editor wpsc-switch-editor-active visual_header" type="button" onclick="wpsc_get_ep_tinymce_email_header('wpsc_ct_warn_email_body','html_body');"><?php _e('Visual','wpsc-ep');?></button>
			    <button id="text" class="wpsc-switch-editor" type="button" onclick="wpsc_get_ep_textarea_email_header('wpsc_ct_warn_email_body')"><?php _e('Text','wpsc-ep');?></button>
		    </div>
        <textarea class="form-control" name="wpsc_ct_warn_email_body" id="wpsc_ct_warn_email_body"><?php echo htmlentities(get_option('wpsc_ct_warn_email_body',''))?></textarea>
        <div class="row attachment_link">
            <span onclick="wpsc_get_templates(); "><?php _e('Insert Macros','wpsc-ep') ?></span>
        </div>
    </div>
    <hr>
    <h4><?php _e('Allowed User type warning email','wpsc-ep');?></h4>
    <p class="help-block"><?php _e('If non register user trying to create ticket, this email will get sent back to customer.','wpsc-ep');?></p>
    <div class="form-group">
		<label for="wpsc_close_user_warn_email_subject"><?php _e('Email Subject','wpsc-ep');?></label>
	    <p class="help-block"><?php _e('Subject for email to send.','wpsc-ep');?></p>
	    <input type="text" class="form-control" name="wpsc_close_user_warn_email_subject" id="wpsc_close_user_warn_email_subject" value="<?php echo get_option('wpsc_close_user_warn_email_subject','')?>" />
    </div>

    <div class="form-group">
        <label for="wpsc_close_user_warn_email_body"><?php _e('Email Body','wpsc-ep');?></label>
        <p class="help-block"><?php _e('Body for email to send. Use macros for ticket specific details. Macros will get replaced by its value while sending an email.','wpsc-ep');?></p>
        <div class="text-right">
			    <button id="visual1" class="wpsc-switch-editor wpsc-switch-editor-active" type="button" onclick="wpsc_get_ep_tinymce_email_body('wpsc_close_user_warn_email_body','html_body');"><?php _e('Visual','wpsc-ep');?></button>
			    <button id="text1" class="wpsc-switch-editor" type="button" onclick="wpsc_get_ep_textarea_email_body('wpsc_close_user_warn_email_body')"><?php _e('Text','wpsc-ep');?></button>
		    </div>
        <textarea  class="form-control" name="wpsc_close_user_warn_email_body" id="wpsc_close_user_warn_email_body"><?php echo htmlentities(get_option('wpsc_close_user_warn_email_body',''))?></textarea>
    </div>

    <button type="submit" class="btn btn-success"><?php _e('Save Changes','supportcandy');?></button>
    <img class="wpsc_submit_wait" style="display:none;" src="<?php echo WPSC_PLUGIN_URL.'asset/images/ajax-loader@2x.gif';?>">
    <input type="hidden" name="action" value="wpsc_set_ep_en_setting" />
</form>

<script>

function wpsc_set_ep_en_setting(){
  jQuery('.wpsc_submit_wait').show();
  var dataform = new FormData(jQuery('#wpsc_frm_ep_en_settings')[0]);
  
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

function wpsc_get_ep_tinymce_email_header(selector,body_id){
  
  jQuery('#visual_header').removeClass('btn btn-primary visual_header');
  jQuery('#text_header').addClass('btn btn-primary text_header');
  jQuery('#text_header').removeClass('btn btn-default text_header');
  jQuery('#text').removeClass('wpsc-switch-editor-active');
  jQuery('#visual').addClass('wpsc-switch-editor-active');
  tinymce.init({ 
    selector:'#'+selector,
    body_id: body_id,
    //directionality : '<?php //echo $directionality; ?>',
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

function wpsc_get_ep_textarea_email_header(selector){
  jQuery('#visual_body').addClass('btn btn-primary visual_body');
  jQuery('#visual_body').removeClass('btn btn-default visual_body');
  jQuery('#text_body').removeClass('btn btn-primary text_body');
  tinymce.remove('#'+selector);
  jQuery('#text').addClass('wpsc-switch-editor-active');
  jQuery('#visual').removeClass('wpsc-switch-editor-active');
}
tinymce.remove();
tinymce.init({ 
  selector:'#wpsc_ct_warn_email_body',
  body_id: 'wpsc_ct_warn_email_body',
  menubar: false,
  //directionality : '<?php //echo $directionality; ?>',
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
function wpsc_get_ep_tinymce_email_body(selector,body_id){
  jQuery('#visual_header').removeClass('btn btn-primary visual_header');
  jQuery('#text_header').addClass('btn btn-primary text_header');
  jQuery('#text_header').removeClass('btn btn-default text_header');
  jQuery('#text1').removeClass('wpsc-switch-editor-active');
  jQuery('#visual1').addClass('wpsc-switch-editor-active');
  tinymce.init({ 
    selector:'#'+selector,
    body_id: body_id,
  //directionality : '<?php //echo $directionality; ?>',
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
function wpsc_get_ep_textarea_email_body(selector){

  jQuery('#visual_body').addClass('btn btn-primary visual_body');
  jQuery('#visual_body').removeClass('btn btn-default visual_body');
  jQuery('#text_body').removeClass('btn btn-primary text_body');
  tinymce.remove('#'+selector);
  jQuery('#text1').addClass('wpsc-switch-editor-active');
  jQuery('#visual1').removeClass('wpsc-switch-editor-active');
}
tinymce.init({ 
  selector:'#wpsc_close_user_warn_email_body',
  body_id: 'wpsc_close_user_warn_email_body',
  menubar: false,
  //directionality : '<?php //echo $directionality; ?>',
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
</script>