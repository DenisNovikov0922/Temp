<?php
/**
 * Branding output.
 *
 * @package Ultimate_Dashboard_Pro
 */

namespace UdbPro\Widget;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;
use WP_Query;
use UdbPro\Helpers\Content_Helper;
use UdbPro\Helpers\Widget_Helper;
use UdbPro\Helpers\Video_Helper;
use UdbPro\Helpers\Multisite_Helper;

/**
 * Class to setup branding output.
 */
class Widget_Output extends Base_Output {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance = null;

	/**
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Get instance of the class.
	 *
	 * @return object
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/widget';

	}

	/**
	 * Init the class setup.
	 */
	public static function init() {

		$class = new self();
		$class->setup();

	}

	/**
	 * Setup widgets output.
	 */
	public function setup() {

		add_action( 'wp_dashboard_setup', array( self::get_instance(), 'remove_3rd_party_widgets' ), 100 );
		add_filter( 'udb_widget_user_roles', array( self::get_instance(), 'dashboard_user_roles' ) );
		add_filter( 'udb_allow_widget_access', array( self::get_instance(), 'check_widget_access' ), 10, 3 );
		add_filter( 'udb_widget_output', array( self::get_instance(), 'widget_output' ), 10, 2 );

		/**
		 * ! https://developer.wordpress.org/reference/hooks/screen_layout_columns/
		 * ! Why not just using add_screen_option(); ?
		 */
		add_action( 'screen_layout_columns', array( self::get_instance(), 'dashboard_columns' ) );
		add_filter( 'get_user_option_screen_layout_dashboard', array( self::get_instance(), 'dashboard_columns_layout' ) );

		add_action( 'admin_init', array( self::get_instance(), 'update_widget_order' ), 15 );
		add_action( 'user_register', array( self::get_instance(), 'update_widget_order' ) );

		add_action( 'admin_init', array( self::get_instance(), 'check_dashboard_page' ) );

	}

	/**
	 * Remove 3rd party widgets.
	 */
	public function remove_3rd_party_widgets() {

		$widget_helper = new Widget_Helper();

		$saved_widgets = $widget_helper->get_saved_3rd_party_widgets();

		foreach ( $saved_widgets as $id => $widget ) {

			if ( false !== $widget ) {
				remove_meta_box( $id, 'dashboard', $widget['context'] );
			}
		}

	}

	/**
	 * Add "super_admin" to the current user roles on multisite.
	 * This filter will be called in the free version's "class-widget-output.php" inside "add_dashboard_widgets" method.
	 *
	 * @param array $roles Array of user roles.
	 * @return array
	 */
	public function dashboard_user_roles( $roles ) {

		$ms_helper = new Multisite_Helper();

		if ( $ms_helper->multisite_supported() ) {
			if ( is_super_admin() ) {
				array_push( $roles, 'super_admin' );
			}
		}

		return $roles;

	}

	/**
	 * Add extra checking whether or not to allow widget access.
	 * This filter will be called in "class-widget-output.php" in the free version.
	 *
	 * @param bool  $allow_access The existing condition.
	 * @param int   $post_id The widget's post id.
	 * @param array $user_roles Current user roles.
	 *
	 * @return bool Whether access is allowed or not.
	 */
	public function check_widget_access( $allow_access, $post_id, $user_roles ) {

		$current_user = wp_get_current_user();
		$widget_roles = get_post_meta( $post_id, 'udb_widget_roles', true );
		$widget_roles = empty( $widget_roles ) ? array( 'all' ) : $widget_roles;
		$widget_roles = is_serialized( $widget_roles ) ? unserialize( $widget_roles ) : $widget_roles;
		$role_allowed = false;

		// Check widget roles.
		foreach ( $widget_roles as $widget_role ) {
			if ( 'all' === $widget_role ) {
				$role_allowed = true;
				break;
			} else {
				if ( in_array( $widget_role, $user_roles, true ) ) {
					$role_allowed = true;
					break;
				}
			}
		}

		if ( ! $role_allowed ) {
			return false;
		}

		$allowed_user_ids = get_post_meta( $post_id, 'udb_restrict_users', true );
		$allowed_user_ids = empty( $allowed_user_ids ) ? array( 'all' ) : $allowed_user_ids;
		$allowed_user_ids = is_serialized( $allowed_user_ids ) ? unserialize( $allowed_user_ids ) : $allowed_user_ids;
		$user_allowed     = false;

		// Check user restriction.
		if ( in_array( 'all', $allowed_user_ids, true ) ) {
			$user_allowed = true;
		} else {
			if ( in_array( $current_user->ID, $allowed_user_ids ) ) {
				$user_allowed = true;
			}
		}

		if ( ! $user_allowed ) {
			return false;
		}

		return true;

	}

	/**
	 * Display pro widgets.
	 * This action will be called in "class-widget-output.php" in the free version.
	 *
	 * @param string $output The existing output.
	 * @param array  $args The widget arguments (id, title, position, priority, widget_type).
	 *
	 * @return string $output The widget output.
	 */
	public function widget_output( $output, $args ) {

		$post_id     = $args['id'];
		$widget_type = $args['widget_type'];

		if ( 'form' === $widget_type ) {

			$form_note           = get_post_meta( $post_id, 'udb_form_notes', true );
			$form_name           = get_post_meta( $post_id, 'udb_form_name', true ) ? get_post_meta( $post_id, 'udb_form_name', true ) : __( 'Your Name' );
			$form_email          = get_post_meta( $post_id, 'udb_form_email', true ) ? get_post_meta( $post_id, 'udb_form_email', true ) : __( 'Your Email' );
			$form_enable_subject = get_post_meta( $post_id, 'udb_form_subject_enable', true );
			$form_subject        = get_post_meta( $post_id, 'udb_form_subject', true ) ? get_post_meta( $post_id, 'udb_form_subject', true ) : __( 'Subject' );
			$form_message        = get_post_meta( $post_id, 'udb_form_message', true ) ? get_post_meta( $post_id, 'udb_form_message', true ) : __( 'Message' );
			$admin_url           = "'" . admin_url( 'admin-ajax.php' ) . "'";

			$subject_field = '<div class="udb-form-widget-group"><label for="subject">' . $form_subject . '</label><input type="text" name="subject" class="udb-form-widget-input" required></div>';
			$subject_field = $form_enable_subject ? $subject_field : false;

			$output = do_shortcode(
				'<form class="udb-form-widget" method="post">'
				. '<input type="hidden" name="post_id" value="' . $post_id . '">'
				. '<input type="hidden" name="nonce" value="' . wp_create_nonce( 'udb_submit_contact_form_' . $post_id ) . '">'
				. '<p class="udb-form-widget-text">' . $form_note . '</p>'
				. '<div class="udb-form-widget-group">'
				. '<label for="name">' . $form_name . '</label>'
				. '<input type="text" name="name" class="udb-form-widget-input" required>'
				. '</div>'
				. '<div class="udb-form-widget-group">'
				. '<label for="email">' . $form_email . '</label>'
				. '<input type="email" name="email" class="udb-form-widget-input" required>'
				. '</div>'
				. $subject_field
				. '<div class="udb-form-widget-group">'
				. '<label for="message">' . $form_message . '</label>'
				. '<textarea type="text" name="message" class="udb-form-widget-input" required></textarea>'
				. '</div>'
				. '<div class="udb-form-widget-group">'
				. '<button type="submit" class="button button-primary button-large submit-button">' . __( 'Submit', 'ultimatedashboard' ) . '</button>'
				. '<div class="udb-form-notice"></div>'
				. '</form>'
				. '</div>'
			);

		} elseif ( 'video' === $widget_type ) {

			$video_id       = get_post_meta( $post_id, 'udb_video_id', true );
			$video_id       = Video_Helper::get_url_id( $video_id );
			$video_platform = get_post_meta( $post_id, 'udb_video_platform', true );
			$video_src      = 'vimeo' === $video_platform ? 'https://player.vimeo.com/video/' . $video_id . '?autoplay=1' : 'https://www.youtube.com/embed/' . $video_id . '?autoplay=1';

			$video_thumbnail = get_post_meta( $post_id, 'udb_video_thumbnail', true );

			if ( ! $video_thumbnail && 'youtube' === $video_platform ) {
				$video_thumbnail = 'https://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg';
			}

			$output  = '<div class="udb-video-preview-image-wrapper">';
			$output .= '<img class="udb-video-preview-image" src="' . $video_thumbnail . '">';
			$output .= '<div class="udb-video-preview-image-overlay"><div class="udb-video-preview-play-button-outer"><div class="udb-video-preview-play-button"></div></div></div>';
			$output .= '</div>';

			$output .= '<div class="udb-video-overlay">';
			$output .= '<iframe class="udb-video" data-udb-video-src="' . $video_src . '" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
			$output .= '<div class="udb-video-close"></div>';
			$output .= '</div>';

		}

		return $output;

	}

	/**
	 * Dashboard columns.
	 *
	 * @param array $columns The dashboard columns.
	 * @return array The updated dashboard columns.
	 */
	public function dashboard_columns( $columns ) {

		$settings = get_option( 'udb_settings' );

		if ( ! isset( $settings['dashboard_columns'] ) ) {
			$columns['dashboard'] = 4;
		} else {
			$columns['dashboard'] = $settings['dashboard_columns'];
		}

		return apply_filters( 'udb_pro_dashboard_columns', $columns );

	}

	/**
	 * Dashboard columns layout.
	 *
	 * @param int $columns The dashboard columns.
	 * @return integer The dashboard columns.
	 */
	public function dashboard_columns_layout( $columns ) {

		$settings = get_option( 'udb_settings' );

		if ( ! isset( $settings['dashboard_columns'] ) ) {
			$columns = 4;
		} else {
			$columns = $settings['dashboard_columns'];
		}

		return apply_filters( 'udb_pro_dashboard_columns_layout', $columns );

	}

	/**
	 * Update widget order.
	 *
	 * @param int $user_id The user ID (when new user registered).
	 */
	public function update_widget_order( $user_id = 0 ) {

		$multisite_helper = new \UdbPro\Helpers\Multisite_Helper();
		$widget_helper    = new \UdbPro\Helpers\Widget_Helper();

		// Stop here if plugin is activated network wide.
		if ( $multisite_helper->is_network_active() ) {
			return;
		}

		$widget_order_user = $widget_helper->get_widget_order_user();
		$order_option_meta = get_option( 'udb_pro_widget_order' );

		// Stop here if no user is selected.
		if ( ! $widget_order_user ) {
			return;
		}

		$order_key       = 'meta-box-order_dashboard';
		$order_user_meta = get_user_meta( $widget_order_user, $order_key, true );

		if ( ! empty( $user_id ) ) {
			// If executed in "user_register" hook, update the new user's meta.
			update_user_meta( $user_id, $order_key, $order_user_meta );
		}

		// If no change, return.
		if ( $order_user_meta === $order_option_meta ) {
			return;
		}

		$blogusers = get_users();

		foreach ( $blogusers as $user ) {

			// Check if they're not the selected user.
			if ( $user->ID !== $widget_order_user ) {
				update_user_meta( $user->ID, $order_key, $order_user_meta );
			}
		}

		update_option( 'udb_pro_widget_order', $order_user_meta );

	}

	/**
	 * Check if we're in Dashboard.
	 */
	public function check_dashboard_page() {

		global $pagenow;

		if ( 'index.php' !== $pagenow ) {
			return;
		}

		add_action( 'admin_notices', array( self::get_instance(), 'choose_dashboard_page' ) );

	}

	/**
	 * Choose templates to be displayed.
	 */
	public function choose_dashboard_page() {

		$ms_helper      = new Multisite_Helper();
		$switch_blog    = $ms_helper->needs_to_switch_blog();
		$settings       = get_option( 'udb_settings' );
		$user_roles     = wp_get_current_user()->roles;
		$template_roles = isset( $settings['page_builder_template'] ) ? $settings['page_builder_template'] : array();

		array_push( $user_roles, 'all' );

		if ( $ms_helper->multisite_supported() ) {
			if ( is_super_admin() ) {
				array_push( $user_roles, 'super_admin' );
			}
		}

		$multisite_template_roles = array();

		if ( $switch_blog ) {
			global $blueprint;
			switch_to_blog( $blueprint );

			$multisite_settings       = get_option( 'udb_settings' );
			$multisite_template_roles = isset( $multisite_settings['page_builder_template'] ) ? $multisite_settings['page_builder_template'] : array();

			restore_current_blog();
		}

		foreach ( $user_roles as $user_role ) {
			if ( ! empty( $template_roles ) && isset( $template_roles[ $user_role ] ) && ! empty( $template_roles[ $user_role ] ) ) {
				$this->render_dashboard_page( $template_roles[ $user_role ] );
			} else {
				if ( ! empty( $multisite_template_roles ) && isset( $multisite_template_roles[ $user_role ] ) && ! empty( $multisite_template_roles[ $user_role ] ) ) {
					switch_to_blog( $blueprint );
					$this->render_dashboard_page( $multisite_template_roles[ $user_role ] );
					restore_current_blog();
				}
			}
		}

	}

	/**
	 * Render page builder for dashboard page.
	 *
	 * @param array $template The template value.
	 */
	public function render_dashboard_page( $template ) {
		$content_helper  = new Content_Helper();
		$active_builders = $content_helper->get_active_page_builders();
		$explode         = explode( '_', $template );
		$builder         = $explode[0];
		$template_id     = absint( $explode[1] );

		if ( in_array( $builder, $active_builders, true ) ) {
			require __DIR__ . '/templates/page-builder-template.php';
		}
	}

}
