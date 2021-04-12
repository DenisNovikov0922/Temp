<?php
/**
 * Content shown before job listings in `[jobs]` shortcode.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-listings-start.php.
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

<div class="row justify-content-center">
<div class="col-12">

<div class="px-3 d-none d-md-block">
	<div class="row mb-4">
		<div class="col">
			<h5 class="mb-0"><?php esc_html_e( 'Position', 'jumpstart' ); ?></h5>
		</div>
		<div class="col-3">
			<h5 class="mb-0"><?php esc_html_e( 'Department', 'jumpstart' ); ?></h5>
		</div>
		<div class="col-3">
			<h5 class="mb-0"><?php esc_html_e( 'Location', 'jumpstart' ); ?></h5>
		</div>
	</div>
</div>

<div class="job_listings">