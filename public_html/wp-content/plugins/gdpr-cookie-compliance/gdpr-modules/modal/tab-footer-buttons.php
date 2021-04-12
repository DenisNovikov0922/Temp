<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>
<div class="moove-gdpr-button-holder">
  <button class="mgbutton moove-gdpr-modal-allow-all button-visible"  aria-label="<?php echo esc_attr( $content->allow_label ); ?>"><?php echo esc_attr( $content->allow_label ); ?></button>
  <button class="mgbutton moove-gdpr-modal-save-settings button-visible" aria-label="<?php echo esc_attr( $content->settings_label ); ?>"><?php echo esc_attr( $content->settings_label ); ?></button>
</div>
<!--  .moove-gdpr-button-holder -->