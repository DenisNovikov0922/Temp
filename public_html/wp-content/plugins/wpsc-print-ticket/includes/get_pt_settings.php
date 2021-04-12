<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $wpscfunction,$current_user,$wpdb;
$directionality = $wpscfunction->check_rtl();
?>

<h4 style="margin-bottom: 10px;">
	<?php _e('Print Setting','wpsc-pt');?>
</h4>
<div class="wpsc_padding_space"></div>

<form id="wpsc_frm_pt_settings" method="post" action="javascript:wpsc_set_pt_settings();">
  <div class="form-group">
    <label for="wpsc_print_th_btn"><?php _e('Thank you page print button','wpsc-pt');?></label>
    <p class="help-block"><?php _e('Enable or Disable Print Buttton in thank you page.','wpsc-pt');?></p>
    <select class="form-control" name="wpsc_print_th_btn_setting" id="wpsc_print_th_btn_setting">
      <?php
      $wpsc_print_th_btn_setting = get_option('wpsc_print_th_btn_setting');
      $selected = $wpsc_print_th_btn_setting == '1' ? 'selected="selected"' : '';
      echo '<option '.$selected.' value="1">'.__('Enable','supportcandy').'</option>';
      $selected = $wpsc_print_th_btn_setting == '0' ? 'selected="selected"' : '';
      echo '<option '.$selected.' value="0">'.__('Disable','supportcandy').'</option>';
      ?>
    </select>
  </div>
  
  <div class="form-group">
    <label for="wpsc_print_th_btn"><?php _e('Print Button Label','wpsc-pt');?></label>
    <p class="help-block"><?php _e('Label to represent print ticket button.','wpsc-pt');?></p>
    <input type="text" class="form-control" name="wpsc_print_btn_lbl" id="wpsc_print_btn_lbl" value="<?php echo get_option('wpsc_print_btn_lbl');?>" />
  </div>
  
  <div class="form-group">
    <label for="wpsc_print_th_btn"><?php _e('Allow Print Button for customer','wpsc-pt');?></label>
    <p class="help-block"><?php _e('Enable or Disable print buttton for customer.','wpsc-pt');?></p>
    <select class="form-control" name="wpsc_print_cust_btn_setting" id="wpsc_print_cust_btn_setting">
      <?php
      $wpsc_print_cust_btn_setting = get_option('wpsc_print_cust_btn_setting');
      $selected = $wpsc_print_cust_btn_setting == '1' ? 'selected="selected"' : '';
      echo '<option '.$selected.' value="1">'.__('Enable','wpsc-pt').'</option>';
      $selected = $wpsc_print_cust_btn_setting == '0' ? 'selected="selected"' : '';
      echo '<option '.$selected.' value="0">'.__('Disable','wpsc-pt').'</option>';
      ?>
    </select>
  </div>
  
  <div class="wpsc_padding_space"></div>
  <h4 style="margin-bottom: 10px;">
  	<?php _e('Print Template Settings','wpsc-pt');?>
  </h4>
  <div class="wpsc_padding_space"></div>
  
  <div class="form-group">
    <label for="wpsc_print_page_header_height"><?php _e('Header Height','wpsc-pt');?></label>
    <p class="help-block"><?php _e('Print page header height.','wpsc-pt');?></p>
    <input type="text" class="form-control" name="wpsc_print_page_header_height" id="wpsc_print_page_header_height" value="<?php echo get_option('wpsc_print_page_header_height');?>" />
  </div>
  
  <div class="form-group">
    <label for="wpsc_print_page_footer_height"><?php _e('Footer Height','wpsc-pt');?></label>
    <p class="help-block"><?php _e('Print page footer height.','wpsc-pt');?></p>
    <input type="text" class="form-control" name="wpsc_print_page_footer_height" id="wpsc_print_page_footer_height" value="<?php echo get_option('wpsc_print_page_footer_height');?>" />
  </div>
  
  <div class="form-group">
    <label for="wpsc_print_ticket_header"><?php _e('Header','wpsc-pt');?></label>
    <div class="text-right">
      <button id="visual" class="wpsc-switch-editor wpsc-switch-editor-active visual_header" type="button" onclick="wpsc_get_tinymce_header('wpsc_print_ticket_header','wpsc_print_ticket_header_body');"><?php _e('Visual','wpsc-pt');?></button>
      <button id="text" class="wpsc-switch-editor text_header" type="button" onclick="wpsc_get_textarea_header('wpsc_print_ticket_header')"><?php _e('Text','wpsc-pt');?></button>
   </div>
    <textarea class="form-control" style="height:200px !important;" name="wpsc_print_ticket_header" id="wpsc_print_ticket_header"><?php echo html_entity_decode(stripcslashes(get_option('wpsc_print_ticket_header'))); ?></textarea>
    <div class="row attachment_link">
      <span onclick="wpsc_get_templates()" ><?php _e('Insert Macros','wpsc-pt')?></span>
    </div>
  </div>
  
  <div class="form-group">
    <label for="wpsc_print_ticket_body"><?php _e('Body','wpsc-pt');?></label>
    <div class="text-right">
      <button id="visual1" class="wpsc-switch-editor wpsc-switch-editor-active visual_body" type="button" onclick="wpsc_get_tinymce_body('wpsc_print_ticket_body','wpsc_print_ticket_body');"><?php _e('Visual','wpsc-pt');?></button>
      <button id="text1"  class="wpsc-switch-editor text_body " type="button" onclick="wpsc_get_textarea_body('wpsc_print_ticket_body')"><?php _e('Text','wpsc-pt');?></button>
    </div>
    <textarea class="form-control" style="height:200px !important;" id="wpsc_print_ticket_body" name="wpsc_print_ticket_body"><?php echo html_entity_decode(stripcslashes(get_option('wpsc_print_ticket_body')));?></textarea>
    <div class="row attachment_link">
      <span onclick="wpsc_get_templates()" ><?php _e('Insert Macros','supportcandy')?></span>
    </div>
  </div>
  
  <div class="form-group">
    <label for="wpsc_print_ticket_footer"><?php _e('Footer','wpsc-pt');?></label>
    <div class="text-right">
      <button id="visual2" class="wpsc-switch-editor wpsc-switch-editor-active visual_footer" type="button" onclick="wpsc_get_tinymce_footer('wpsc_print_ticket_footer','print_ticket_footer');"><?php _e('Visual','wpsc-pt');?></button>
      <button id="text2"  class="wpsc-switch-editor text_footer " type="button" onclick="wpsc_get_textarea_footer('wpsc_print_ticket_footer')"><?php _e('Text','wpsc-pt');?></button>
    </div>
    <textarea class="form-control" style="height:200px !important;" id="wpsc_print_ticket_footer" name="wpsc_print_ticket_footer"><?php echo html_entity_decode(stripcslashes(get_option('wpsc_print_ticket_footer')));?></textarea>
    <div class="row attachment_link">
      <span onclick="wpsc_get_templates()" ><?php _e('Insert Macros','supportcandy')?></span>
    </div>
  </div>
  
  <?php do_action('wpsc_get_print_ticket_settings');?>
 
  <button type="submit" id="wpsc_save_ptint_ticket_setting" class="btn btn-success"><?php _e('Save Changes','supportcandy');?></button>
  <button type="button" id="wpsc_reset_pt_default" onclick="wpsc_reset_default()" class="btn btn-sm btn-default" style="font-size:15px;"><?php _e('Reset Default','supportcandy');?></button>
  <img class="wpsc_submit_wait" style="display:none;" src="<?php echo WPSC_PLUGIN_URL.'asset/images/ajax-loader@2x.gif';?>">
  <input type="hidden" name="action" value="wpsc_set_pt_settings" />
</form>

<script>
function wpsc_get_tinymce_header(selector,body_id){
  
  jQuery('#visual_header').removeClass('btn btn-primary visual_header');
  jQuery('#text_header').addClass('btn btn-primary text_header');
  jQuery('#text_header').removeClass('btn btn-default text_header');
  jQuery('#text').removeClass('wpsc-switch-editor-active');
  jQuery('#visual').addClass('wpsc-switch-editor-active');
  tinymce.init({ 
    selector:'#'+selector,
    body_id: body_id,
    directionality : '<?php echo $directionality; ?>',
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

function wpsc_get_textarea_header(selector){
  jQuery('#visual_body').addClass('btn btn-primary visual_body');
  jQuery('#visual_body').removeClass('btn btn-default visual_body');
  jQuery('#text_body').removeClass('btn btn-primary text_body');
  tinymce.remove('#'+selector);
  jQuery('#text').addClass('wpsc-switch-editor-active');
  jQuery('#visual').removeClass('wpsc-switch-editor-active');
}

function wpsc_get_tinymce_body(selector,body_id){
  jQuery('#visual_header').removeClass('btn btn-primary visual_header');
  jQuery('#text_header').addClass('btn btn-primary text_header');
  jQuery('#text_header').removeClass('btn btn-default text_header');
  jQuery('#text1').removeClass('wpsc-switch-editor-active');
  jQuery('#visual1').addClass('wpsc-switch-editor-active');
  tinymce.init({ 
    selector:'#'+selector,
    body_id: body_id,
    directionality : '<?php echo $directionality; ?>',
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

function wpsc_get_textarea_body(selector){

  jQuery('#visual_body').addClass('btn btn-primary visual_body');
  jQuery('#visual_body').removeClass('btn btn-default visual_body');
  jQuery('#text_body').removeClass('btn btn-primary text_body');
  tinymce.remove('#'+selector);
  jQuery('#text1').addClass('wpsc-switch-editor-active');
  jQuery('#visual1').removeClass('wpsc-switch-editor-active');
}

function wpsc_get_tinymce_footer(selector,body_id){
  jQuery('#visual_header').removeClass('btn btn-primary visual_header');
  jQuery('#text_header').addClass('btn btn-primary text_header');
  jQuery('#text_header').removeClass('btn btn-default text_header');
  jQuery('#text2').removeClass('wpsc-switch-editor-active');
  jQuery('#visual2').addClass('wpsc-switch-editor-active');
  tinymce.init({ 
    selector:'#'+selector,
    body_id: body_id,
    directionality : '<?php echo $directionality; ?>',
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

function wpsc_get_textarea_footer(selector){
    
  jQuery('#visual_body').addClass('btn btn-primary visual_body');
  jQuery('#visual_body').removeClass('btn btn-default visual_body');
  jQuery('#text_body').removeClass('btn btn-primary text_body');
  tinymce.remove('#'+selector);
  jQuery('#text2').addClass('wpsc-switch-editor-active');
  jQuery('#visual2').removeClass('wpsc-switch-editor-active');
}

function wpsc_set_pt_settings(){
  
  jQuery('.wpsc_submit_wait').show();
  var dataform = new FormData(jQuery('#wpsc_frm_pt_settings')[0]);
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
 
function wpsc_reset_default() {
  
  var data = {
    action: 'wpsc_reset_default_pt_settings'
  };
  jQuery.post(wpsc_admin.ajax_url, data, function(response_str) {
    var response = JSON.parse(response_str);
    if (response.sucess_status=='1') {
      jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
    }
    jQuery('#wpsc_alert_success').slideDown('fast',function(){});
    setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
  });
}

tinymce.remove();
tinymce.init({ 
  selector:'#wpsc_print_ticket_header',
  body_id: 'print_ticket_header',
  menubar: false,
  directionality : '<?php echo $directionality; ?>',
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


tinymce.init({ 
  selector:'#wpsc_print_ticket_body',
  body_id: 'print_ticket_body',
  menubar: false,
  directionality : '<?php echo $directionality; ?>',
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


tinymce.init({ 
  selector:'#wpsc_print_ticket_footer',
  body_id: 'print_ticket_footer',
  menubar: false,
  directionality : '<?php echo $directionality; ?>',
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