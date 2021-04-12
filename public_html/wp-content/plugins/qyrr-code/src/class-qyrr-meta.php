<?php

namespace qyrr;

/**
 * Admin Meta Class
 */
class QYRR_Meta {

	/**
	 * Return instance of QYRR_Meta
	 *
	 * @return void
	 */
	public static function get_instance() {
		new QYRR_Meta();
	}

	/**
	 * Constructor for QYRR_Meta.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
		add_action( 'save_post', array( $this, 'save_metaboxes' ) );
		add_action( 'post_edit_form_tag', array( $this, 'update_edit_form' ) );
		add_action( 'wp_ajax_data_uri_to_meta', array( $this, 'data_uri_to_meta' ) );
	}

	/**
	 * Adds the meta box container.
	 *
	 * @param array $post_type array of post types.
	 * @return void
	 */
	public function add_metaboxes( $post_type ) {
		add_meta_box( 'qr-general', __( 'General', 'qyrr' ), array( $this, 'render_qr_general' ), 'qr', 'normal', 'high' );
		add_meta_box( 'qr-logo', __( 'Logo or Label', 'qyrr' ), array( $this, 'render_qr_logo' ), 'qr', 'normal', 'high' );
		add_meta_box( 'qr-preview', __( 'QR-Code', 'qyrr' ), array( $this, 'render_qr_code' ), 'qr', 'side', 'high' );
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_qr_general( $post ) {
		wp_nonce_field( 'qr_nonce_check', 'qr_nonce_check_value' );

		/* default should be canvas - image/div only for older browsers */
		$render_mode_active = apply_filters( 'render_mode_active', false );

		$meta_fields = array(
			'qr-content'           => get_post_meta( $post->ID, 'qr-content', true ),
			'render-mode'          => get_post_meta( $post->ID, 'render-mode', true ),
			'size'                 => get_post_meta( $post->ID, 'size', true ),
			'fill-color'           => get_post_meta( $post->ID, 'fill-color', true ),
			'background-color'     => get_post_meta( $post->ID, 'background-color', true ),
			'min-version'          => get_post_meta( $post->ID, 'min-version', true ),
			'error-handling-level' => get_post_meta( $post->ID, 'error-handling-level', true ),
			'quiet-zone'           => get_post_meta( $post->ID, 'quiet-zone', true ),
			'corner-radius'        => get_post_meta( $post->ID, 'corner-radius', true ),
		);
		?>
		<div class="qr-meta">
			<label for="qr-content"><?php esc_html_e( 'URL', 'qyrr' ); ?></label>
			<input type="url" placeholder="<?php echo esc_html( get_bloginfo( 'url' ) ); ?>/my-target-page" name="qr-content" id="qr-content" value="<?php echo esc_html( $meta_fields['qr-content'] ); ?>"/>
		</div>
		<?php if ( true === $render_mode_active ) : ?>
			<div class="qr-meta">
				<label for="render-mode"><?php esc_html_e( 'Render mode', 'qyrr' ); ?></label>
				<select name="render-mode" id="render-mode">
					<?php if ( 'canvas' === $meta_fields['render-mode'] ) : ?>
					<option value="canvas" selected><?php esc_html_e( 'Canvas', 'qyrr' ); ?></option>
					<?php else : ?>
					<option value="canvas"><?php esc_html_e( 'Canvas', 'qyrr' ); ?></option>
					<?php endif; ?>
					<?php if ( 'image' === $meta_fields['render-mode'] ) : ?>
					<option value="image" selected><?php esc_html_e( 'Image', 'qyrr' ); ?></option>
					<?php else : ?>
					<option value="image"><?php esc_html_e( 'Image', 'qyrr' ); ?></option>
					<?php endif; ?>
					<?php if ( 'div' === $meta_fields['render-mode'] ) : ?>
					<option value="div" selected><?php esc_html_e( 'Div', 'qyrr' ); ?></option>
					<?php else : ?>
					<option value="div"><?php esc_html_e( 'Div', 'qyrr' ); ?></option>
					<?php endif; ?>
				</select>
			</div>
		<?php else : ?>
			<div class="qr-meta" style="display:none;">
				<label for="render-mode"><?php esc_html_e( 'Render mode', 'qyrr' ); ?></label>
				<select name="render-mode" id="render-mode">
					<option value="canvas" selected><?php esc_html_e( 'Canvas', 'qyrr' ); ?></option>
				</select>
			</div>
		<?php endif; ?>
		<div class="qr-meta">
			<label for="size"><?php esc_html_e( 'Size (Px)', 'qyrr' ); ?></label>
			<input name="size" id="size" placeholder="400" type="number" max="10000" value="<?php echo esc_html( $meta_fields['size'] ); ?>"/>
		</div>
		<div class="qr-meta">
			<label for="fill-color"><?php esc_html_e( 'Fill color', 'qyrr' ); ?></label>
			<input name="fill-color" id="fill-color" class="color-picker" placeholder="#000000" type="text" value="<?php echo esc_html( $meta_fields['fill-color'] ); ?>"/>
		</div>
		<div class="qr-meta">
			<label for="background-color"><?php esc_html_e( 'Background color', 'qyrr' ); ?></label>
			<input name="background-color" id="background-color" class="color-picker" placeholder="#FFFFFF" type="text" value="<?php echo esc_html( $meta_fields['background-color'] ); ?>"/>
		</div>
		<div class="qr-meta">
			<label for="min-version"><?php esc_html_e( 'Min version', 'qyrr' ); ?></label>
			<input name="min-version" placeholder="5" type="number" value="<?php echo esc_html( $meta_fields['min-version'] ); ?>"/>
		</div>
		<div class="qr-meta">
			<label for="error-handling-level"><?php esc_html_e( 'Error handling level', 'qyrr' ); ?></label>
			<select name="error-handling-level" id="error-handling-level">
				<?php if ( 'L' === $meta_fields['error-handling-level'] ) : ?>
					<option value="L" selected><?php esc_html_e( 'L - Low (7%)', 'qyrr' ); ?></option>
				<?php else : ?>
					<option value="L"><?php esc_html_e( 'L - Low (7%)', 'qyrr' ); ?></option>
				<?php endif; ?>
				<?php if ( 'M' === $meta_fields['error-handling-level'] ) : ?>
					<option value="M" selected><?php esc_html_e( 'M - Medium (15%)', 'qyrr' ); ?></option>
				<?php else : ?>
					<option value="M"><?php esc_html_e( 'M - Medium (15%)', 'qyrr' ); ?></option>
				<?php endif; ?>
				<?php if ( 'Q' === $meta_fields['error-handling-level'] ) : ?>
					<option value="Q" selected><?php esc_html_e( 'Q - Quartile (25%)', 'qyrr' ); ?></option>
				<?php else : ?>
					<option value="Q"><?php esc_html_e( 'Q - Quartile (25%)', 'qyrr' ); ?></option>
				<?php endif; ?>
				<?php if ( 'H' === $meta_fields['error-handling-level'] ) : ?>
					<option value="H" selected><?php esc_html_e( 'H - High (30%)', 'qyrr' ); ?></option>
				<?php else : ?>
					<option value="H"><?php esc_html_e( 'H - High (30%)', 'qyrr' ); ?></option>
				<?php endif; ?>
			</select>
		</div>
		<div class="qr-meta">
			<label for="quiet-zone"><?php esc_html_e( 'Quiet zone', 'qyrr' ); ?></label>
			<input name="quiet-zone" id="quiet-zone" placeholder="3" type="number" value="<?php echo esc_html( $meta_fields['quiet-zone'] ); ?>"/>
		</div>
		<div class="qr-meta">
			<label for="corner-radius"><?php esc_html_e( 'Corner radius (%)', 'qyrr' ); ?></label>
			<input name="corner-radius" id="corner-radius" placeholder="0" type="number" value="<?php echo esc_html( $meta_fields['corner-radius'] ); ?>"/>
		</div>
		<?php
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_qr_logo( $post ) {
		$meta_fields = array(
			'label-mode'  => get_post_meta( $post->ID, 'label-mode', true ),
			'logo-size'   => get_post_meta( $post->ID, 'logo-size', true ),
			'position-x'  => get_post_meta( $post->ID, 'position-x', true ),
			'position-y'  => get_post_meta( $post->ID, 'position-y', true ),
			'label-text'  => get_post_meta( $post->ID, 'label-text', true ),
			'font'        => get_post_meta( $post->ID, 'font', true ),
			'font-color'  => get_post_meta( $post->ID, 'font-color', true ),
			'logo-upload' => get_post_meta( $post->ID, 'logo-upload', true ),
		);

		/* check for logo file */
		$file = $meta_fields['logo-upload'];

		if ( isset( $file ) && ! empty( $file ) ) {
			$logo = $file['url'];
		} else {
			$logo = '';
		}

		$logo_url = $meta_fields['logo-upload'];
		?>
		<div class="qr-meta">
			<label for="label-mode"><?php esc_html_e( 'Label Mode', 'qyrr' ); ?></label>
			<select id="label-mode" name="label-mode">
				<?php if ( '0' === $meta_fields['label-mode'] ) : ?>
				<option value="0" selected><?php esc_html_e( 'Normal', 'qyrr' ); ?></option>
				<?php else : ?>
				<option value="0"><?php esc_html_e( 'Normal', 'qyrr' ); ?></option>
				<?php endif; ?>
				<?php if ( '1' === $meta_fields['label-mode'] ) : ?>
				<option value="1" selected><?php esc_html_e( 'Label-Strip', 'qyrr' ); ?></option>
				<?php else : ?>
				<option value="1"><?php esc_html_e( 'Label-Strip', 'qyrr' ); ?></option>
				<?php endif; ?>
				<?php if ( '2' === $meta_fields['label-mode'] ) : ?>
				<option value="2" selected><?php esc_html_e( 'Label-Box', 'qyrr' ); ?></option>
				<?php else : ?>
				<option value="2"><?php esc_html_e( 'Label-Box', 'qyrr' ); ?></option>
				<?php endif; ?>
				<?php if ( '3' === $meta_fields['label-mode'] ) : ?>
				<option value="3" selected><?php esc_html_e( 'Logo-Strip', 'qyrr' ); ?></option>
				<?php else : ?>
				<option value="3"><?php esc_html_e( 'Logo-Strip', 'qyrr' ); ?></option>
				<?php endif; ?>
				<?php if ( '4' === $meta_fields['label-mode'] ) : ?>
				<option value="4" selected><?php esc_html_e( 'Logo-Box', 'qyrr' ); ?></option>
				<?php else : ?>
				<option value="4"><?php esc_html_e( 'Logo-Box', 'qyrr' ); ?></option>
				<?php endif; ?>
			</select>
		</div>
		<div class="qr-meta">
			<label for="logo-size"><?php esc_html_e( 'Logo size (%)', 'qyrr' ); ?></label>
			<input id="logo-size" name="logo-size" placeholder="30" type="number" value="<?php echo esc_html( $meta_fields['logo-size'] ); ?>"/>
		</div>
		<div class="qr-meta">
			<label for="position-x"><?php esc_html_e( 'Position X (%)', 'qyrr' ); ?></label>
			<input id="position-x" name="position-x" placeholder="50" type="number" value="<?php echo esc_html( $meta_fields['position-x'] ); ?>"/>
		</div>
		<div class="qr-meta">
			<label for="position-y"><?php esc_html_e( 'Position Y (%)', 'qyrr' ); ?></label>
			<input id="position-y" name="position-y" placeholder="50" type="number" value="<?php echo esc_html( $meta_fields['position-y'] ); ?>"/>
		</div>
		<div class="qr-meta">
			<label for="label-text"><?php esc_html_e( 'Label Text', 'qyrr' ); ?></label>
			<input id="label-text" name="label-text" placeholder="Your Label" type="text" value="<?php echo esc_html( $meta_fields['label-text'] ); ?>"/>
		</div>
		<div class="qr-meta">
			<label for="font" style="top: -10px;position: relative;"><?php esc_html_e( 'Font', 'qyrr' ); ?></label>
			<input id="font" name="font" placeholder="Verdana" type="text" value="<?php echo esc_html( $meta_fields['font'] ); ?>"/>
		</div>
		<div class="qr-meta">
			<label for="font-color"><?php esc_html_e( 'Font-Color', 'qyrr' ); ?></label>
			<input id="font-color" name="font-color" class="color-picker" placeholder="#000000" type="text" value="<?php echo esc_html( $meta_fields['font-color'] ); ?>"/>
		</div>
		<div class="qr-meta">
			<label for="logo-upload"><?php esc_html_e( 'Logo-Upload', 'qyrr' ); ?></label>
			<input id="logo-upload" name="logo-upload" type="file">
			<img id="img-buffer" style="display:none;" src="<?php echo esc_html( $logo ); ?>">
		</div>
		<?php
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_qr_code( $post ) {
		?>
		<div id="qr-code"></div>
		<div id="qr-actions">
			<input type="hidden" class="shortcode-copy" value='[qyrr code="<?php echo esc_html( $post->post_name ); ?>"]'/>
			<a href="#" id="download" download="<?php echo esc_html( $post->post_name ); ?>.png" class="button-primary">Download</a>
			<a href="#" id="shortcode" data-clipboard-target=".shortcode-copy" class="button-primary copy">Shortcode</a>
		</div>
		<?php
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_metaboxes( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['qr_nonce_check_value'] ) ) {
			return $post_id;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['qr_nonce_check_value'], 'qr_nonce_check' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// Update the meta fields.
		$meta_fields = array(
			'qr-content'           => esc_url_raw( $_POST['qr-content'] ),
			'render-mode'          => sanitize_text_field( $_POST['render-mode'] ),
			'size'                 => sanitize_text_field( $_POST['size'] ),
			'fill-color'           => sanitize_text_field( $_POST['fill-color'] ),
			'background-color'     => sanitize_text_field( $_POST['background-color'] ),
			'min-version'          => sanitize_text_field( $_POST['min-version'] ),
			'error-handling-level' => sanitize_text_field( $_POST['error-handling-level'] ),
			'quiet-zone'           => sanitize_text_field( $_POST['quiet-zone'] ),
			'corner-radius'        => sanitize_text_field( $_POST['corner-radius'] ),
			'label-mode'           => sanitize_text_field( $_POST['label-mode'] ),
			'logo-size'            => sanitize_text_field( $_POST['logo-size'] ),
			'position-x'           => sanitize_text_field( $_POST['position-x'] ),
			'position-y'           => sanitize_text_field( $_POST['position-y'] ),
			'label-text'           => sanitize_text_field( $_POST['label-text'] ),
			'font'                 => sanitize_text_field( $_POST['font'] ),
			'font-color'           => sanitize_text_field( $_POST['font-color'] ),
		);

		foreach ( $meta_fields as $meta_key => $meta_value ) {
			if ( isset( $meta_value ) ) {
				update_post_meta( $post_id, $meta_key, $meta_value );
			} else {
				delete_post_meta( $post_id, $meta_key );
			}
		}

		/* upload attachement and save file meta */
		if ( ! empty( $_FILES['logo-upload']['name'] ) ) {
			$supported_types = apply_filters( 'qr_supported_file_types', array( 'application/svg', 'image/jpeg', 'image/png', 'image/gif' ) );
			$arr_file_type   = wp_check_filetype( basename( $_FILES['logo-upload']['name'] ) );
			$uploaded_type   = $arr_file_type['type'];

			if ( in_array( $uploaded_type, $supported_types ) ) {
				$upload = wp_upload_bits( sanitize_file_name( $_FILES['logo-upload']['name'] ), null, file_get_contents( $_FILES['logo-upload']['tmp_name'] ) );

				if ( isset( $upload['error'] ) && 0 != $upload['error'] ) {
					wp_die( esc_html__( 'There was an error uploading your file. The error is:', 'qyrr' ) . ' ' . esc_html( $upload['error'] ) );
				} else {
					add_post_meta( $post_id, 'logo-upload', $upload );
					update_post_meta( $post_id, 'logo-upload', $upload );
				}
			} else {
				wp_die( esc_html__( "The file type that you've uploaded is not a allowed.", 'qyrr' ) );
			}
		}
	}
	/**
	 * Add functionality for file upload.
	 */
	public function update_edit_form() {
		echo ' enctype="multipart/form-data"';
	}

	/**
	 * Save data uri to meta field.
	 *
	 * @return void
	 */
	public function data_uri_to_meta() {

		/* verify nonce */
		check_ajax_referer( 'qyrr', 'nonce' );

		/* check $_POST and update meta */
		if ( isset( $_POST['post_id'] ) && ! empty( $_POST['post_id'] ) && isset( $_POST['data-uri'] ) && ! empty( $_POST['data-uri'] ) ) {
			$post_id  = sanitize_text_field( $_POST['post_id'] );
			$data_uri = sanitize_text_field( $_POST['data-uri'] );

			update_post_meta( $post_id, 'data-uri', $data_uri );
		}
	}
}
