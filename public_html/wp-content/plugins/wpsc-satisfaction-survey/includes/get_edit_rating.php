<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$rating_id = isset($_POST['rating_id']) ? intval($_POST['rating_id']) : 0;
if(!$rating_id) exit;

$rating = get_term_by('id', $rating_id, 'wpsc_sf_rating');
$color  = get_term_meta( $rating_id, 'color', true);

ob_start();
?>
<div class="form-group">
  <label for="wpsc_rating_name"><?php _e('Rating Label','wpsc-sf');?></label>
  <p class="help-block"><?php _e('Insert rating label. Please make sure label you are entering should not already exist.','wpsc-sf');?></p>
  <input id="wpsc_rating_name" class="form-control" name="wpsc_rating_name" value="<?php echo $rating->name?>" />
</div>
<div class="form-group">
  <label for="wpsc_rating_color"><?php _e('Color','wpsc-sf');?></label>
  <input id="wpsc_rating_color" class="wpsc_color_picker" name="wpsc_rating_color" value="<?php echo $color?>" />
</div>
<script>
  jQuery(document).ready(function(){
      jQuery('.wpsc_color_picker').wpColorPicker();
  });
</script>
<?php 
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wpsc_popup_close" onclick="wpsc_modal_close();"><?php _e('Close','wpsc-sf');?></button>
<button type="button" class="btn wpsc_popup_action" onclick="wpsc_set_edit_rating(<?php echo $rating_id?>);"><?php _e('Submit','wpsc-sf');?></button>
<?php 
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);
