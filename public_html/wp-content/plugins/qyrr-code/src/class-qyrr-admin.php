<?php

namespace qyrr;

/**
 * Admin Options Class
 */
class QYRR_Admin {

	/**
	 * Retun new instance of QR_Admin.
	 *
	 * @return object
	 */
	public static function get_instance() {
		return new QYRR_Admin();
	}

	/**
	 * Setting up admin fields
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_scripts' ) );
		add_action( 'init', array( $this, 'register_qr' ) );
		add_action( 'init', array( $this, 'register_qr_campain' ) );
		add_filter( 'manage_qr_posts_columns', array( $this, 'set_columns' ) );
		add_action( 'manage_qr_posts_custom_column', array( $this, 'set_columns_content' ), 10, 2 );
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @return void
	 */
	public function add_admin_scripts() {

		$post_type = get_post_type();

		if ( 'qr' !== $post_type ) {
			return;
		}

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// color picker with alpha.
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_script( 'wp-color-picker-alpha', QYRR_URL . '/assets/wp-color-picker-alpha.min.js', array(), '1.0', true );

		// Media Uploader.
		wp_enqueue_media();

		// admin styles and scripts.
		wp_enqueue_style( 'qyrr-admin', QYRR_URL . '/assets/qyrr-admin' . $suffix . '.css', array(), '1.0', 'all' );
		wp_enqueue_script( 'jquery-qrcode', QYRR_URL . '/assets/jquery-qrcode.min.js', array( 'jquery' ), '0.17.0', true );
		wp_enqueue_script( 'jquery-range', QYRR_URL . '/assets/jquery-range.min.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'jquery-fontselect', QYRR_URL . '/assets/jquery-fontselect.min.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'qyrr-admin', QYRR_URL . '/assets/qyrr-admin' . $suffix . '.js', array( 'jquery', 'jquery-qrcode', 'jquery-range' ), '1.0', true );

		wp_localize_script( 'qyrr-admin', 'ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'post_id' => get_the_id(), 'nonce' => wp_create_nonce( 'qyrr' ), 'data_uri' => get_post_meta( get_the_id(), 'data-uri', true ) ) );
	}

	/**
	 * Register a custom post type called "qr".
	 *
	 * @see get_post_type_labels() for label keys.
	 */
	public function register_qr() {
		$labels = array(
			'name'                  => _x( 'QR Code', 'Post type general name', 'qyrr' ),
			'singular_name'         => _x( 'QR Code', 'Post type singular name', 'qyrr' ),
			'menu_name'             => _x( 'QR Codes', 'Admin Menu text', 'qyrr' ),
			'name_admin_bar'        => _x( 'QR Code', 'Add New on Toolbar', 'qyrr' ),
			'add_new'               => __( 'Add New', 'qyrr' ),
			'add_new_item'          => __( 'Add New QR Code', 'qyrr' ),
			'new_item'              => __( 'New QR Code', 'qyrr' ),
			'edit_item'             => __( 'Edit QR Code', 'qyrr' ),
			'view_item'             => __( 'View QR Code', 'qyrr' ),
			'all_items'             => __( 'All QR Codes', 'qyrr' ),
			'search_items'          => __( 'Search QR Codes', 'qyrr' ),
			'parent_item_colon'     => __( 'Parent QR Codes:', 'qyrr' ),
			'not_found'             => __( 'No QR codes found.', 'qyrr' ),
			'not_found_in_trash'    => __( 'No QR codes found in Trash.', 'qyrr' ),
			'archives'              => _x( 'QR codes archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'qyrr' ),
			'insert_into_item'      => _x( 'Insert into QR Code', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'qyrr' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this QR Code', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'qyrr' ),
			'filter_items_list'     => _x( 'Filter QR codes list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'qyrr' ),
			'items_list_navigation' => _x( 'QR list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'qyrr' ),
			'items_list'            => _x( 'QR codes list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'qyrr' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'supports'           => array( 'title' ),
			'menu_icon'          => 'dashicons-smartphone',
		);

		register_post_type( 'qr', $args );
	}

	/**
	 * Register a 'Campain' taxonomy for post type 'qyrr'.
	 *
	 * @see register_post_type() for registering post types.
	 */
	public function register_qr_campain() {
		$labels = array(
			'name'                       => _x( 'Campains', 'taxonomy general name', 'qyrr' ),
			'singular_name'              => _x( 'Campain', 'taxonomy singular name', 'qyrr' ),
			'search_items'               => __( 'Search Campains', 'qyrr' ),
			'popular_items'              => __( 'Popular Campains', 'qyrr' ),
			'all_items'                  => __( 'All Campains', 'qyrr' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Campain', 'qyrr' ),
			'update_item'                => __( 'Update Campain', 'qyrr' ),
			'add_new_item'               => __( 'Add New Campain', 'qyrr' ),
			'new_item_name'              => __( 'New Campain Name', 'qyrr' ),
			'separate_items_with_commas' => __( 'Separate Campains with commas', 'qyrr' ),
			'add_or_remove_items'        => __( 'Add or remove Campains', 'qyrr' ),
			'choose_from_most_used'      => __( 'Choose from the most used Campains', 'qyrr' ),
			'not_found'                  => __( 'No Campains found.', 'qyrr' ),
			'menu_name'                  => __( 'Campains', 'qyrr' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => false,
			'public'            => false,
		);

		register_taxonomy( 'qr-campain', 'qr', $args );
	}

	/**
	 * Set column headers-
	 *
	 * @param  array $columns array of columns.
	 * @return array
	 */
	public function set_columns( $columns ) {
		unset( $columns['date'] );
		$columns['qr_code']  = __( 'QR-Code', 'qyrr' );
		$columns['download'] = __( 'Download PNG', 'qyrr' );

		return $columns;
	}

	/**
	 * Add content to registered columns.
	 *
	 * @param  string $column name of the column.
	 * @param  int    $post_id current id.
	 * @return void
	 */
	public function set_columns_content( $column, $post_id ) {
		switch ( $column ) {
			case 'qr_code':
				$qr_code = get_post_meta( $post_id, 'data-uri', true );
				echo '<img width="50px" src="' . esc_html( $qr_code ) . '"</div>';
				break;
			case 'download':
				$post = get_post( $post_id );
				echo '<a href="#" id="column-download" download="' . esc_html( $post->post_name ) . '.png" class="button-primary">' . esc_html__( 'Download', 'qyrr' ) . '</a>';
				break;
		}
	}
}
