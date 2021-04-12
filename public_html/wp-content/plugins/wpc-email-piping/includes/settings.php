<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction;

$siteurl = explode('/',site_url());;
$javascript_origin = $siteurl[0].'//'.$siteurl[2];
$block_emails = get_option('wpsc_ep_block_emails');
if($block_emails){
	$block_emails = implode("\n",$block_emails);
}
$directionality = $wpscfunction->check_rtl();
$exe_time = get_option('wpsc_ep_cron_execution_time');
if(!$exe_time) $exe_time = 1;


?>
<ul class="nav nav-tabs">
  <li role="presentation" class="tab active" onclick="wpsc_change_tab(this,'google_setup');"><a href="#"><?php _e('Connection Settings','wpsc-ep');?></a></li>
  <li role="presentation" class="tab" onclick="wpsc_change_tab(this,'other_settings');"><a href="#"><?php _e('Other Settings','wpsc-ep');?></a></li>
</ul>

<div id="google_setup" class="tab_content visible" style="margin-top:20px;">
	
	<form id="wpsc_frm_ep_settings" method="post" action="javascript:set_email_piping_settings();">
	
		<?php
		$piping_type = get_option('wpsc_ep_piping_type');
		?>
		<div class="form-group" style="margin-bottom:30px;">
	    <label for="wpsc_ep_piping_type"><?php _e('Piping Type','wpsc-ep');?></label>
	    <p class="help-block"><?php _e("Email piping type.","wpsc-ep");?></p>
			<select id="wpsc_ep_piping_type" class="form-control" name="wpsc_ep_piping_type" onchange="wpsc_toggle_imap_type(this);">
				<option <?php echo $piping_type=="imap"?'selected="selected"':''; ?> value="imap"><?php _e('IMAP','wpsc-ep')?></option>
				<option <?php echo $piping_type=="gmail"?'selected="selected"':''; ?> value="gmail"><?php _e('Gmail','wpsc-ep')?></option>
			</select>
	  </div>
		
		<div class="wpsc_ep_setting_gmail">
			<h4>Steps To Setup Google</h4>
			<p><a href="https://console.developers.google.com/" target="_blank">Click Here</a> to go to Google Developer Console.</p>
			<p>Click <strong>My Project</strong> on top left corner. It will open pop-up screen. Click <strong>New Project</strong> on top right corner.</p>
			<p>Insert Project Name as <strong><?php echo get_bloginfo('name')?></strong> and click <strong>Create</strong> button.</p>
			<p>Select <strong><?php echo get_bloginfo('name')?></strong> project if not selected already from top left corner. Click on <strong>Credentials</strong> from <strong>APIs & Services</strong> menu.</p>
			<p>Click <strong>OAuth consent screen</strong> tab. Insert <strong><?php echo get_bloginfo('name')?></strong> in <strong>Product name shown to users</strong> and click <strong>Save</strong> button at bottom.</p>
			<p>Click on <strong>Credentials</strong> tab. Click <strong>Create Credentials</strong> button. Click <strong>OAuth client ID</strong> option.</p>
			<p>Select <strong>Web application</strong>. Insert <strong><?php echo get_bloginfo('name')?></strong> as Name. Insert <strong><?php echo $javascript_origin?></strong> as Authorised JavaScript origins. Insert <strong><?php echo admin_url('admin.php')?></strong> as Authorised redirect URIs. Click <strong>Create</strong> button.</p>
			<p>Copy <strong>Client ID</strong> and <strong>Client Secret</strong> in this page below and insert google email address you wish to pipe.</p>
			<p>Finally click <strong>Dashboard</strong> and enable <strong>Gmail API</strong>.</p>
			<hr>
		</div>
	
		<div class="form-group wpsc_ep_setting_gmail">
	    <label for="wpsc_ep_client_id"><?php _e('Client ID','wpsc-ep');?></label>
	    <p class="help-block"><?php _e("Google app Client ID.","wpsc-ep");?></p>
	    <input type="text" id="wpsc_ep_client_id" class="form-control" name="wpsc_ep_client_id" value="<?php echo get_option('wpsc_ep_client_id','')?>" />
	  </div>
	  
	  <div class="form-group wpsc_ep_setting_gmail">
	    <label for="wpsc_ep_client_secret"><?php _e('Client Secret','wpsc-ep');?></label>
	    <p class="help-block"><?php _e("Google app Client Secret.","wpsc-ep");?></p>
	    <input type="text" id="wpsc_ep_client_secret" class="form-control" name="wpsc_ep_client_secret" value="<?php echo get_option('wpsc_ep_client_secret','')?>" />
	  </div>
	  
	  <div class="form-group wpsc_ep_setting_gmail">
	    <label for="wpsc_ep_email_address"><?php _e('Email Address','wpsc-ep');?></label>
	    <p class="help-block"><?php _e("Google email address to pipe.","wpsc-ep");?></p>
	    <input type="text" id="wpsc_ep_email_address" class="form-control" name="wpsc_ep_email_address" value="<?php echo get_option('wpsc_ep_email_address','')?>" />
	  </div>
		
		<div class="form-group wpsc_ep_setting_imap">
	    <label for="wpsc_ep_imap_email_address"><?php _e('Email Address / Email Username','wpsc-ep');?></label>
	    <input type="text" id="wpsc_ep_imap_email_address" class="form-control" name="wpsc_ep_imap_email_address" value="<?php echo get_option('wpsc_ep_imap_email_address','')?>" />
	  </div>
		
		<div class="form-group wpsc_ep_setting_imap">
	    <label for="wpsc_ep_imap_email_password"><?php _e('Email Password','wpsc-ep');?></label>
	    <input type="password" id="wpsc_ep_imap_email_password" class="form-control" name="wpsc_ep_imap_email_password" value="<?php echo get_option('wpsc_ep_imap_email_password','')?>" />
	  </div>
		
		<div class="form-group wpsc_ep_setting_imap">
	    <label for="wpsc_ep_imap_encryption"><?php _e('Encryption','wpsc-ep');?></label>
	    <select id="wpsc_ep_imap_encryption" class="form-control" name="wpsc_ep_imap_encryption">
				<?php
				$imap_encryption = get_option('wpsc_ep_imap_encryption','');
				?>
				<option <?php echo $imap_encryption=='ssl'?'selected="selected"':''?> value="ssl">SSL</option>
				<option <?php echo $imap_encryption=='none'?'selected="selected"':''?> value="none">None</option>
			</select>
	  </div>
		
		<div class="form-group wpsc_ep_setting_imap">
	    <label for="wpsc_ep_imap_incoming_mail_server"><?php _e('Incoming Mail Server','wpsc-ep');?></label>
	    <input type="text" id="wpsc_ep_imap_incoming_mail_server" class="form-control" name="wpsc_ep_imap_incoming_mail_server" value="<?php echo get_option('wpsc_ep_imap_incoming_mail_server','')?>" />
	  </div>
		
		<div class="form-group wpsc_ep_setting_imap">
	    <label for="wpsc_ep_imap_port"><?php _e('Port','wpsc-ep');?></label>
	    <input type="number" id="wpsc_ep_imap_port" class="form-control" name="wpsc_ep_imap_port" value="<?php echo get_option('wpsc_ep_imap_port','')?>" />
	  </div>
	  
	  <input type="hidden" name="action" value="wpsc_set_ep_settings" />
	  
	  <button type="submit" class="btn btn-success" id="wpsc_email_piping_save_conn_btn"><?php _e('Save & Connect','wpsc-ep');?></button>
	  <img class="wpsc_submit_wait" style="display:none;" src="<?php echo WPSC_PLUGIN_URL.'asset/images/ajax-loader@2x.gif';?>">
		
		<?php
		
		$refresh_token = get_option('wpsc_ep_refresh_token');
		$imap_uid      = get_option('wpsc_ep_imap_uid');
		
		if ( $refresh_token ) {?>
			<span class="wpsc_ep_setting_gmail" style="color:green;"><?php _e('Connected!','wpsc-ep')?></span>
		  <?php
	  } else {?>
			<span class="wpsc_ep_setting_gmail" style="color:red;"><?php _e('Not Connected!','wpsc-ep')?></span>
			<?php
		}
		
		if(is_numeric($imap_uid)) {?>
			<span class="wpsc_ep_setting_imap" style="color:green;"><?php _e('Connected!','wpsc-ep')?></span>
			<?php
		} else {?>
			<span class="wpsc_ep_setting_imap" style="color:red;"><?php _e('Not Connected!','wpsc-ep')?></span>
			<?php
		}
		?>
	</form>
</div>

<div id="other_settings" class="tab_content hidden" style="margin-top:20px;">
	<form id="wpsc_ep_other_settings" method="post" action="javascript:wpsc_set_em_other_settings();">
		<div class="form-group">
	      <label for="wpsc_block_email"><?php _e('Block Emails','wpsc-ep')?></label>
	      <p class="help-block"><?php _e('Add one email per line. You can insert email pattern like *@paypal.com, admin@*, etc.','wpsc-ep')?></p>
	      <textarea name="wpsc_block_email" style="width: 350px; height: 100px;" value=""><?php echo $block_emails?></textarea>
	  </div>
		<div class="form-group">
	      <label for="wpsc_block_subject"><?php _e('Ignore emails having these words in subject','wpsc-ep')?></label>
	      <p class="help-block"><?php _e('Add one pattern per line. You can use patterns like abc,xyz,*abc,xyz* etc.','wpsc-ep')?></p>
	      <textarea name="wpsc_block_subject" style="width: 350px; height: 100px;" value=""><?php echo get_option('wpsc_ep_block_subject')?></textarea>
	  </div>
		<div class="form-group">
	      <label for="wpsc_allow_user"><?php _e('Allowed User Emails','wpsc-ep')?></label>
				<select class="form-control" name="wpsc_allow_user">
	        <option <?php echo get_option('wpsc_ep_allowed_user')=="1"?'selected="selected"':''; ?> value="1"><?php _e('Anyone (including guest users)','wpsc-ep')?></option>
	        <option <?php echo get_option('wpsc_ep_allowed_user')=="0"?'selected="selected"':''; ?> value="0"><?php _e('Registered Users Only','wpsc-ep')?></option>      
				</select>
	  </div>
		<div class="form-group">
			<label><?php _e('Cron Execution Time','wpsc-ep');?></label><br>
			<input type="number" id="wpsc_ep_cron_execution_time" name="wpsc_ep_cron_execution_time" value="<?php echo $exe_time;?>" />
		  <label>  <?php _e('Minute(s)','wpsc-ep');?></label>&nbsp;<span id="errmsg" style ="color: #f70f0f;">
		</div>
		<div class="form-group">
	      <label for="wpsc_email_type"><?php _e('Email Body','wpsc-ep')?></label>
				<p class="help-block"><?php _e('Set whether you want to accept email as text or html format. We recommend using text format to parse exact reply from sender. If you wish to use html format, it will load full html content along with email history (we stripe css and javascript in html for safety).','wpsc-ep')?></p>
				<select class="form-control" name="wpsc_ep_email_type">
	        <option <?php echo get_option('wpsc_ep_email_type')=="html"?'selected="selected"':''; ?> value="html"><?php _e('Html','wpsc-ep')?></option>
					<option <?php echo get_option('wpsc_ep_email_type')=="text"?'selected="selected"':''; ?> value="text"><?php _e('Text','wpsc-ep')?></option>
				</select>
	  </div>
		<div class="form-group">
	      <label for="wpsc_allow_user"><?php _e('Debug Mode','wpsc-ep')?></label>
				<p class="help-block"><?php _e('It is not safe to keep debug mode enabled all the time, your gmail messeges can be exposed to hackers. Enable it only when required.','wpsc-ep')?></p>
				<select class="form-control" name="wpsc_ep_debug_mode">
	        <option <?php echo get_option('wpsc_ep_debug_mode')=="0"?'selected="selected"':''; ?> value="0"><?php _e('Disable','wpsc-ep')?></option>
					<option <?php echo get_option('wpsc_ep_debug_mode')=="1"?'selected="selected"':''; ?> value="1"><?php _e('Enable','wpsc-ep')?></option>
				</select>
	  </div>
		
		<div class="form-group">
	      <label for="wpsc_allow_user"><?php _e(' From email in email notification to customer','wpsc-ep')?></label>
				<p class="help-block"><?php _e('If set Original, email address to which customer sent an email originally will be used as From email in an email notification to customer for the ticket created. e.g. You have piping set for support@yourdomain.com and forwarded all incoming emails from sales@yourdomain, account@yourdomain, etc. to support@yourdomain to create ticket. In case, user sent an email to sales@yourdomain.com he will receive an email notificatification from sales@yourdomain.com instead of default set in email notification setting.','wpsc-ep')?></p>
				<select class="form-control" name="wpsc_ep_from_email">
	        <option <?php echo get_option('wpsc_ep_from_email')=="0"?'selected="selected"':''; ?> value="0"><?php _e('Default','wpsc-ep')?></option>
					<option <?php echo get_option('wpsc_ep_from_email')=="1"?'selected="selected"':''; ?> value="1"><?php _e('Original','wpsc-ep')?></option>
				</select>
	  </div>
		
		<div class="form-group">
				<label for="wpsc_allow_user"><?php _e('Accept Emails','wpsc-ep')?></label>
				<p class="help-block"><?php _e('In this settings you have to define which type of emails you will reccive .','wpsc-ep')?></p>
				<select class="form-control" name="wpsc_ep_accept_emails" id="wpsc_ep_accept_emails">
					<?php $wpsc_ep_accept_emails = get_option('wpsc_ep_accept_emails');		?>
					<option <?php echo $wpsc_ep_accept_emails=="new"?'selected="selected"':''; ?> value="new"><?php _e('New Emails','wpsc-ep')?></option>
					<option <?php echo $wpsc_ep_accept_emails=="reply"?'selected="selected"':''; ?> value="reply"><?php _e('Reply Emails','wpsc-ep')?></option>
					<option <?php echo $wpsc_ep_accept_emails=="all"?'selected="selected"':''; ?> value="all"><?php _e('All Emails','wpsc-ep')?></option>
			  </select>	
		</div>
		
		<div class="form-group">
				<label for="wpsc_allow_user"><?php _e('Import CC as additional recipients','wpsc-ep')?></label>
				<p class="help-block"><?php _e('If enabled, CC emails found in an incoming email will be added as addtional recipients to a ticket.','wpsc-ep')?></p>
				<select class="form-control" name="wpsc_add_additional_recepients" id="wpsc_add_additional_recepients">
					<option <?php echo get_option('wpsc_add_additional_recepients')=="0"?'selected="selected"':''; ?> value="0"><?php _e('Disable','wpsc-ep')?></option>
					<option <?php echo get_option('wpsc_add_additional_recepients')=="1"?'selected="selected"':''; ?> value="1"><?php _e('Enable','wpsc-ep')?></option>
				</select>	
		</div>
		
		<button type="submit" class="btn btn-success" id="wpsc_other_setting_email_btn"><?php _e('Save Changes','wpsc-ep');?></button>
		<img class="wpsc_submit_wait" style="display:none;" src="<?php echo WPSC_PLUGIN_URL.'asset/images/ajax-loader@2x.gif';?>">
		<input type="hidden" name="action" value="wpsc_set_ep_other_settings" />
	</form>
</div>
	


<script>

jQuery(document).ready(function(){
	var piping_type = '<?php echo $piping_type?>';
	if(piping_type == 'imap'){
		jQuery('.wpsc_ep_setting_gmail').hide();
	} else if(piping_type == 'gmail') {
		jQuery('.wpsc_ep_setting_imap').hide();
	}
});

function wpsc_toggle_imap_type(e){
	var piping_type = jQuery(e).val();
	if(piping_type == 'imap'){
		jQuery('.wpsc_ep_setting_gmail').hide();
		jQuery('.wpsc_ep_setting_imap').show();
	} else if(piping_type == 'gmail') {
		jQuery('.wpsc_ep_setting_imap').hide();
		jQuery('.wpsc_ep_setting_gmail').show();
	}
}

function wpsc_change_tab(e,content_id){
	jQuery('.tab').removeClass('active');
	jQuery(e).addClass('active');
	jQuery('.tab_content').removeClass('visible').addClass('hidden');
	jQuery('#'+content_id).removeClass('hidden').addClass('visible');
}

function set_email_piping_settings(){
  jQuery('.wpsc_submit_wait').show();
  var dataform = new FormData(jQuery('#wpsc_frm_ep_settings')[0]);
  
  jQuery.ajax({
    url: wpsc_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    var response = JSON.parse(response_str);
    if (response.url!='') {
      window.location.href = response.url;
    } else {
			jQuery('.wpsc_submit_wait').hide();
			jQuery('#wpsc_alert_success .wpsc_alert_text').text(response.messege);
			jQuery('#wpsc_alert_success').slideDown('fast',function(){});
	    setTimeout(function(){ jQuery('#wpsc_alert_success').slideUp('fast',function(){}); }, 3000);
		}
  });
}

function wpsc_set_em_other_settings(){
  jQuery('.wpsc_submit_wait').show();
  var dataform = new FormData(jQuery('#wpsc_ep_other_settings')[0]);
  
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

tinymce.remove();
tinymce.init({ 
  selector:'#wpsc_ct_warn_email_body',
  body_id: 'wpsc_ct_warn_email_body',
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
</script>