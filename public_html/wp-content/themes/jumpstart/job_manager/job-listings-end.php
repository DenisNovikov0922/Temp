<?php
/**
 * Content shown after job listings in `[jobs]` shortcode.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-listings-end.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.15.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

</div>

</div>
</div>

<div class="row justify-content-center mt-3 mt-md-4">
	<div class="col-auto">
		<div class="alert bg-secondary">
			<?php esc_html_e( "Didn't see your dream job?", 'jumpstart' ); ?> <a href="#"><?php esc_html_e( 'Get in touch', 'jumpstart' ); ?></a>
		</div>
	</div>
</div>