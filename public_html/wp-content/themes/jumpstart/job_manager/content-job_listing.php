<?php
/**
 * Job listing in the loop.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-job_listing.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @since       1.0.0
 * @version     1.27.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;
?>

<a href="<?php the_job_permalink(); ?>" class="card card-body px-3 py-4 flex-sm-row align-items-center justify-content-between hover-box-shadow mb-2 mb-sm-3" data-longitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_long ); ?>">
	<div class="col-auto col-md">
		<h6 class="mb-0"><?php wpjm_the_job_title(); ?></h6>
	</div>
	<div class="col-auto col-md-3"><?php if ( get_option( 'job_manager_enable_types' ) ) { ?>
		<?php $types = wpjm_get_the_job_types(); ?>
		<?php if ( ! empty( $types ) ) : foreach ( $types as $type ) : ?>
			<?php echo esc_html( $type->name ); ?> 
		<?php endforeach; endif; ?>
	<?php } ?></div>
	<div class="col-auto col-md-3"><?php the_job_location( false ); ?></div>
</a>