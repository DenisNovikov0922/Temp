<?php
/**
 * Video widget.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	global $post;

	if ( function_exists( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	} else {
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
	}

	?>

	<div data-type="video">

		<div class="postbox">
			<div class="postbox-header">
				<h2><?php _e( 'Video URL', 'ultimatedashboard' ); ?></h2>
			</div>
			<div class="inside">
				<?php $stored_meta = get_post_meta( $post->ID, 'udb_video_id', true ); ?>
				<input id="udb-video-id" type="url" placeholder="https://www.youtube.com/watch?v=YlUKcNNmywk" name="udb_video_id" value="<?php echo esc_attr( $stored_meta ? $stored_meta : '' ); ?>" />
			</div>
		</div>

		<div class="postbox">
			<div class="postbox-header">
				<h2><?php _e( 'Platform', 'ultimatedashboard' ); ?></h2>
			</div>
			<div class="inside">
				<?php
				$stored_meta = get_post_meta( $post->ID, 'udb_video_platform', true );
				$stored_meta = $stored_meta ? $stored_meta : 'youtube';
				?>
				<select id="udb-video-platform" name="udb_video_platform">
					<option value="youtube" <?php selected( $stored_meta, 'youtube' ); ?>><?php _e( 'YouTube', 'ultimatedashboard' ); ?></option>
					<option value="vimeo" <?php selected( $stored_meta, 'vimeo' ); ?>><?php _e( 'Vimeo', 'ultimatedashboard' ); ?></option>
				</select>
			</div>
		</div>

		<div class="postbox">
			<div class="postbox-header">
				<h2><?php _e( 'Thumbnail', 'ultimatedashboard' ); ?></h2>
			</div>
			<div class="inside udb-metabox-field">
				<label for="udb-video-thumbnail">
					<?php _e( 'Required for Vimeo videos, optional for YouTube videos.', 'ultimatedashboard' ); ?>
				</label>
				<br>
				<?php $stored_meta = get_post_meta( $post->ID, 'udb_video_thumbnail', true ); ?>
				<input style="max-width: 400px" id="udb-video-thumbnail" class="udb-video-thumbnail-url" type="text" name="udb_video_thumbnail" value="<?php echo esc_attr( $stored_meta ? $stored_meta : '' ); ?>">
				<a href="#" class="udb-video-thumbnail-upload button-secondary"><?php _e( 'Add or Upload File', 'ultimatedashboard' ); ?></a>
				<a href="#" class="udb-video-thumbnail-remove button-secondary">x</a>
			</div>
		</div>

	</div>

	<?php

};
