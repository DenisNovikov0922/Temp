<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_editor();
wp_enqueue_script('wpsc-public');

global $wpscfunction;

$ticket_id = isset($_REQUEST['ticket_id']) ? intval($_REQUEST['ticket_id']) : 0;
if(!$ticket_id) return;

$rating_id = isset($_REQUEST['rating_id']) ? intval($_REQUEST['rating_id']) : 0;
if(!$rating_id) return;

$auth_code = isset($_REQUEST['auth_code']) ? sanitize_text_field($_REQUEST['auth_code']) : '';
if(!$auth_code) return;

$ticket_auth_code = $wpscfunction->get_ticket_fields($ticket_id,'ticket_auth_code');
if( $ticket_auth_code != $auth_code ) return;

$sf_rating = $wpscfunction->get_ticket_meta($ticket_id,'sf_rating',true);
if(!$sf_rating){
	$wpscfunction->add_ticket_meta($ticket_id,'sf_rating',$rating_id);
}
else {
	$wpscfunction->update_ticket_meta($ticket_id,'sf_rating',array('meta_value' => $rating_id));
}
do_action('wpsc_submit_ticket_rating',$ticket_id);

$wpsc_sf_thankyou_text = stripslashes(get_option('wpsc_sf_thankyou_text'));
if($wpsc_sf_thankyou_text) echo $wpsc_sf_thankyou_text;
?>

<div class="bootstrap-iso">
  
	<div id="wpsc_sf_more_feedback_container" class="row">
		<form id="frm_more_feedback" action="javascript:wpsc_set_more_feedback();" method="post">
			<div class="form-group">
				<h4><?php _e('More Feedback (Optional):','wpsc-sf');?></h4>
				<textarea class="form-control" name="wpsc_more_feedback" id="wpsc_more_feedback"></textarea>
			</div>
			<button id="wpsc_sf_btn_submit" type="submit" class="btn btn-lg btn-success"><?php _e('Submit','wpsc-sf')?></button>
			<input type="hidden" name="action" value="wpsc_sf_set_more_feedback" />
			<input type="hidden" name="ticket_id" value="<?php echo $ticket_id?>" />
			<input type="hidden" name="auth_code" value="<?php echo $auth_code?>" />
		</form>
	</div>
	
</div>
<?php add_action('wp_footer', 'wpsc_footer_script',99999999999); ?>

<?php
if(!function_exists('wpsc_footer_script')) {
function wpsc_footer_script(){
	?>
	<script>
	jQuery(document).ready(function(){
		tinymce.remove();
		tinymce.init({ 
			selector:'#wpsc_more_feedback',
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
	});
	function wpsc_set_more_feedback(){
		jQuery('#wpsc_sf_btn_submit').text('<?php _e('Please wait..','wpsc-sf')?>');
		var dataform = new FormData(jQuery('#frm_more_feedback')[0]);
		jQuery.ajax({
			url: wpsc_admin.ajax_url,
			type: 'POST',
			data: dataform,
			processData: false,
			contentType: false
		})
		.done(function (response_str) {
			jQuery('#wpsc_sf_more_feedback_container').html(response_str);
		});
	}
	</script>
	<?php
}
}
?>
