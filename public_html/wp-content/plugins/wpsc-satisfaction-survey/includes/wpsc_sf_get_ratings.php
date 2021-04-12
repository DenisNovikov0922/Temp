<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wpscfunction;

$sf_page_id       = get_option('wpsc_sf_page','0');
$sf_page_url      = get_permalink( $sf_page_id );
$ticket_id        = isset($_POST['ticket_id']) ? sanitize_text_field($_POST['ticket_id']) : '';
$ticket_auth_code = $wpscfunction->get_ticket_fields($ticket_id,'ticket_auth_code');

$ratings = get_terms([
	'taxonomy'   => 'wpsc_sf_rating',
	'hide_empty' => false,
	'orderby'    => 'meta_value_num',
	'order'    	 => 'ASC',
	'meta_query' => array('order_clause' => array('key' => 'load_order')),
	]);

ob_start();

?>
<div class="bootstrap-iso">
	<div class="row" id ="satsifaction_survey">
		<?php foreach ($ratings as $rating_term) {
			$color  = get_term_meta( $rating_term->term_id, 'color', true);
		?>
		<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 wpsc_rating_btns">
			<button class="rating_element_sf" style="background-color:<?php echo $color ?>" onclick="wpsc_add_sf_rating(<?php echo $ticket_id?>,<?php echo $rating_term->term_id;?>)">
				<?php echo $rating_term->name;?></button>
		</div>
		<?php
		}	?>	
	</div>
	<div id="wpsc_sf_more_feedback_container" class="row" style="display:none;">
		
		<form id="frm_more_feedback" method="post">
			<div class="form-group">
				<h4><?php _e('More Feedback (Optional):','wpsc-sf');?></h4>
				<textarea class="wpsc_textarea" name="wpsc_more_feedback" id="wpsc_more_feedback"></textarea>
			</div>
			<input type="hidden" name="action" value="wpsc_sf_set_feedback" />
			<input type="hidden" name="ticket_id" value="<?php echo $ticket_id?>" />
			<input type="hidden" name="auth_code" value="<?php echo $ticket_auth_code?>" />
		</form>
	</div>
	
</div>

<style>
@media (min-width:1025px){
	#wpsc_popup_container{
		left: 25% !important;
		width: 50% !important;
	}
}
.rating_element_sf{
	color:#ffffff !important;
	padding:5px 5px;
	border: 0px;
	border-radius:6px;
	margin:0 3px;
}
.wpsc_rating_btns{
	padding: 10px;
	width : fit-content !important; 
}
</style>	

<script>
	function wpsc_add_sf_rating(ticket_id,rating_id){
		var data = {
			action: 'wpsc_add_sf_rating',
			ticket_id : ticket_id,
			rating_id : rating_id
		}
		jQuery.post(wpsc_admin.ajax_url, data, function(response) {
			jQuery('#satsifaction_survey').hide();
			jQuery('#wpsc_sf_more_feedback_container').show();
			jQuery('#wpsc_sf_btn_submit').show();
		});
	}

	function wpsc_sf_set_feedback(ticket_id){
		jQuery('#wpsc_sf_btn_submit').text('<?php _e('Please wait..','wpsc-sf')?>');
		var wpsc_more_feedback = jQuery('#wpsc_more_feedback').val();
		var dataform = new FormData(jQuery('#frm_more_feedback')[0]);
		dataform.append('wpsc_more_feedback',wpsc_more_feedback);

		jQuery.ajax({
			url: wpsc_admin.ajax_url,
			type: 'POST',
			data: dataform,
			processData: false,
			contentType: false
		})
		.done(function (response_str) {
			if (response_str == '') {
				wpsc_modal_close();
			}
			jQuery('#wpsc_sf_more_feedback_container').html(response_str);
            jQuery('#wpsc_sf_btn_submit').hide();
            jQuery('#wpsc_sf_btn_close').html('<?php _e('Ok','wpsc-sf');?>');
			wpsc_open_ticket(ticket_id);
		});
	}
</script>
<?php
$body = ob_get_clean();
ob_start();
?>
<button id = "wpsc_sf_btn_close" type="button" class="btn wpsc_popup_close" onclick="return wpsc_modal_close();" style="width:100px !important"> <?php _e('Cancel','wpsc-sf');?></button>
<button id="wpsc_sf_btn_submit" type="submit" class="btn wpsc_popup_action" style="display:none" onclick="wpsc_sf_set_feedback(<?php echo $ticket_id; ?>)"><?php _e('Submit','wpsc-sf')?></button>

<?php
$footer = ob_get_clean();
$response = array(
    'body'      => $body,
    'footer'    => $footer
);
echo json_encode($response);