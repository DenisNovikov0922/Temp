<?php
/**
 * Widget module.
 *
 * @package Ultimate_Dashboard
 */

namespace UdbPro\Widget;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup widgets module.
 */
class Widget_Module extends Base_Module {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/widget';

	}

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Setup widgets module.
	 */
	public function setup() {

		add_filter( 'udb_widget_types', array( self::get_instance(), 'register_widget_types' ) );
		add_filter( 'udb_widget_list_type_column_content', array( self::get_instance(), 'type_column_content' ), 10, 3 );
		add_filter( 'udb_widget_list_roles_column_content', array( self::get_instance(), 'roles_column_content' ), 10, 2 );

		add_action( 'udb_widget_metabox', array( self::get_instance(), 'video_widget' ) );
		add_action( 'udb_widget_metabox', array( self::get_instance(), 'form_widget' ) );

		add_action( 'add_meta_boxes', array( self::get_instance(), 'register_meta_boxes' ) );
		add_action( 'save_post', array( self::get_instance(), 'save_post' ) );

		add_action( 'udb_dashboard_styles', array( self::get_instance(), 'dashboard_styles' ) );
		add_action( 'udb_edit_widget_scripts', array( self::get_instance(), 'edit_widget_scripts' ) );
		add_action( 'udb_dashboard_scripts', array( self::get_instance(), 'dashboard_scripts' ) );

		add_action( 'wp_ajax_udb_contact_form_clear_logs', array( self::get_instance(), 'clear_contact_form_logs' ) );
		add_action( 'wp_ajax_udb_submit_contact_form', array( self::get_instance(), 'submit_contact_form' ) );

		// The module output.
		require_once __DIR__ . '/class-widget-output.php';
		Widget_Output::init();

		add_action( 'udb_before_wp_dashboard_setup', array( self::get_instance(), 'original_widgets_before_dashboard_setup' ) );
		add_action( 'udb_after_wp_dashboard_setup', array( self::get_instance(), 'original_widgets_after_dashboard_setup' ) );

	}

	/**
	 * Register widget types.
	 *
	 * @param array $widget_types The existing widget types.
	 * @return array The modified widget types.
	 */
	public function register_widget_types( $widget_types ) {

		$widget_types['video'] = __( 'Video Widget', 'ultimatedashboard' );
		$widget_types['form']  = __( 'Contact Form Widget', 'ultimatedashboard' );

		return $widget_types;

	}

	/**
	 * Set "type" column's content on widget list screen.
	 *
	 * @param string $content The existing type column's content.
	 * @param int    $post_id The current post id.
	 * @param string $widget_type The current widget type.
	 *
	 * @return string The type column's content.
	 */
	public function type_column_content( $content, $post_id, $widget_type ) {

		if ( 'form' === $widget_type ) {
			$content = __( 'Contact Form', 'ultimatedashboard' );
		} elseif ( 'video' === $widget_type ) {
			$content = __( 'Video', 'ultimatedashboard' );
		}

		return $content;

	}

	/**
	 * Modify the roles column content in admin page's post list screen.
	 *
	 * @param string $column_content The existing column content.
	 * @param int    $post_id The current admin page's post id.
	 *
	 * @return string The column content.
	 */
	public function roles_column_content( $column_content, $post_id ) {

		$roles = get_post_meta( $post_id, 'udb_widget_roles', true );
		$roles = is_serialized( $roles ) ? unserialize( $roles ) : $roles;
		$roles = empty( $roles ) ? array( 'all' ) : $roles;
		$roles = implode( ', ', $roles );

		return $roles;

	}

	/**
	 * Enqueue styles on dashboard page.
	 */
	public function dashboard_styles() {

		// Dashboard.
		wp_enqueue_style( 'udb-pro-dashboard', $this->url . '/assets/css/dashboard.css', array( 'udb-dashboard' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	}

	/**
	 * Scripts to enqueue on new widget & edit widget screen.
	 */
	public function edit_widget_scripts() {

		// Edit widget.
		wp_enqueue_script( 'udb-pro-edit-widget', $this->url . '/assets/js/edit-widget.js', array( 'udb-edit-widget' ), ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION, true );

		// Edit widget object.
		wp_localize_script(
			'udb-pro-edit-widget',
			'udbProEditWidget',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'labels'  => array(
					'clearLog'    => __( 'Clear Log', 'ultimatedashboard' ),
					'clearingLog' => __( 'Clearing Log Data...', 'ultimatedashboard' ),
				),
			)
		);

	}

	/**
	 * Scripts to enqueue on dashboard screen.
	 */
	public function dashboard_scripts() {

		// Dashboard.
		wp_enqueue_script( 'udb-pro-dashboard', $this->url . '/assets/js/dashboard.js', array( 'udb-dashboard' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// General dashboard object.
		wp_localize_script(
			'udb-pro-dashboard',
			'udbProDashboard',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			)
		);

		// Contact form object.
		wp_localize_script(
			'udb-pro-dashboard',
			'udbProContactForm',
			array(
				'labels' => array(
					'submit'     => __( 'Submit', 'ultimatedashboard' ),
					'submitting' => __( 'Submitting...', 'ultimatedashboard' ),
				),
			)
		);

	}

	/**
	 * Action to run on "get_original" method on "class-widget-helper.php" in the free version.
	 */
	public function original_widgets_before_dashboard_setup() {

		remove_action( 'wp_dashboard_setup', array( Widget_Output::get_instance(), 'remove_3rd_party_widgets' ), 100 );

	}

	/**
	 * Action to run on "get_original" method on "class-widget-helper.php" in the free version.
	 */
	public function original_widgets_after_dashboard_setup() {

		add_action( 'wp_dashboard_setup', array( Widget_Output::get_instance(), 'remove_3rd_party_widgets' ), 100 );

	}

	/**
	 * Define the video widget.
	 */
	public function video_widget() {

		$widget = require __DIR__ . '/templates/widget-types/video-widget.php';
		$widget();

	}

	/**
	 * Define the form widget.
	 */
	public function form_widget() {

		$widget = require __DIR__ . '/templates/widget-types/form-widget.php';
		$widget();

	}

	/**
	 * Register metaboxes.
	 */
	public function register_meta_boxes() {

		add_meta_box( 'udb-widget-roles-metabox', __( 'User Role Access', 'ultimatedashboard' ), array( $this, 'widget_roles_metabox' ), 'udb_widgets', 'side' );
		add_meta_box( 'udb-restrict-users-metabox', __( 'User Access', 'ultimatedashboard' ), array( $this, 'restrict_users_metabox' ), 'udb_widgets', 'side' );

	}

	/**
	 * Widget roles metabox.
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function widget_roles_metabox( $post ) {

		$metabox = require __DIR__ . '/templates/metaboxes/widget-roles.php';
		$metabox( $post );

	}

	/**
	 * Restrict user metabox.
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function restrict_users_metabox( $post ) {

		$metabox = require __DIR__ . '/templates/metaboxes/restrict-users.php';
		$metabox( $post );

	}

	/**
	 * Save widget's postmeta data.
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_post( $post_id ) {

		$save_widget = require __DIR__ . '/inc/save-post.php';
		$save_widget( $post_id );

	}

	/**
	 * Ajax handler of clear contact form logs inside edit widget screen.
	 */
	public function clear_contact_form_logs() {

		$ajax = require __DIR__ . '/ajax/clear-contact-form-logs.php';
		$ajax();

	}

	/**
	 * Ajax handler of contact form submission.
	 */
	public function submit_contact_form() {

		$ajax = require __DIR__ . '/ajax/submit-contact-form.php';
		$ajax( $this );

	}

	/**
	 * Contact form logger.
	 *
	 * @param int    $post_id The form's post id.
	 * @param string $message The log message.
	 * @param string $local_timestamp The timestamp.
	 * @param string $email The sender email.
	 * @param string $subject The submission subject.
	 * @param string $name The sender name.
	 * @param string $status The email sending status.
	 */
	public function contact_form_logger( $post_id, $message, $local_timestamp, $email, $subject, $name, $status ) {

		$clean_message = sanitize_text_field( $message );
		$clean_email   = sanitize_email( $email );
		$clean_subject = sanitize_text_field( $subject );
		$clean_name    = sanitize_text_field( $name );

		$current_site_id   = 0;
		$blueprint_site_id = 0;

		if ( is_multisite() ) {

			$current_site_id   = get_current_blog_id();
			$blueprint_site_id = get_site_option( 'udb_multisite_blueprint' ) ? (int) get_site_option( 'udb_multisite_blueprint' ) : 0;

			if ( ! empty( $blueprint_site_id ) ) {
				switch_to_blog( $blueprint_site_id );
			}
		}

		$log_enabled = get_post_meta( $post_id, 'udb_form_enable_logs', true );
		$log         = get_post_meta( $post_id, 'udb_contact_form_logs', true );

		// Stop here if logs are disabled.
		if ( ! $log_enabled ) {
			return;
		}

		if ( $log ) {

			// Extend existing log if it exists.
			$log .= $this->create_contact_form_log_message( $local_timestamp, $clean_message, $clean_name, $clean_subject, $clean_email, $status );
			update_post_meta( $post_id, 'udb_contact_form_logs', $log );

		} else {

			// Create log entry if it doesn't exist.
			$log = $this->create_contact_form_log_message( $local_timestamp, $clean_message, $clean_name, $clean_subject, $clean_email, $status );
			update_post_meta( $post_id, 'udb_contact_form_logs', $log );

		}

		if ( is_multisite() && ! empty( $blueprint_site_id ) ) {
			switch_to_blog( $current_site_id );
		}

	}

	/**
	 * Construct contact form log entry.
	 *
	 * @param string $local_timestamp The timestamp.
	 * @param string $clean_message The sanitized submission message.
	 * @param string $clean_name The sanitized sender name.
	 * @param string $clean_subject The sanitized submission subject.
	 * @param string $clean_email The sanitized sender email.
	 * @param string $status The email sending status.
	 *
	 * @return string The log message.
	 */
	public function create_contact_form_log_message( $local_timestamp, $clean_message, $clean_name, $clean_subject, $clean_email, $status ) {

		$log_message  = '<div class="udb-form-widget-log-entry">';
		$log_message .= '<strong>' . __( 'Message:', 'ultimatedashboard' ) . '</strong>';
		$log_message .= '<br/>';
		$log_message .= $clean_message;
		$log_message .= '<br/>';
		$log_message .= '<hr>';
		$log_message .= '<strong>' . __( 'From:', 'ultimatedashboard' ) . ' </strong>';
		$log_message .= $clean_name;
		$log_message .= '<br/>';
		$log_message .= '<strong>' . __( 'Subject:', 'ultimatedashboard' ) . ' </strong>';
		$log_message .= $clean_subject;
		$log_message .= '<br/>';
		$log_message .= '<strong>' . __( 'Email:', 'ultimatedashboard' ) . ' </strong>';
		$log_message .= $clean_email;
		$log_message .= '<br/>';
		$log_message .= '<hr>';
		$log_message .= $status ? '<span class="udb-form-widget-log-entry-indicator success"></span>' . __( 'Sent', 'ultimatedashboard' ) : '<span class="udb-form-widget-log-entry-indicator error"></span>' . __( 'Error', 'ultimatedashboard' );
		$log_message .= ' - ';
		$log_message .= $local_timestamp;
		$log_message .= '<br/>';

		$log_message .= '</div>';

		return $log_message;

	}

}
