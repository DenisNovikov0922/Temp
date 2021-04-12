<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmProHooksController {

	/**
	 * @since 3.0
	 */
	public static function load_pro() {
		$frmedd_update = FrmProAppHelper::get_updater();

		// load the license form
		add_action( 'frm_upgrade_page', 'FrmProSettingsController::standalone_license_box' );
		if ( FrmAppHelper::is_admin_page('formidable-settings') ) {
			add_action('frm_before_settings', 'FrmProSettingsController::license_box', 1);
		}

		add_action( 'admin_init', 'FrmProAppController::admin_init' );

		global $frm_vars;
		if ( ! $frm_vars['pro_is_authorized'] ) {
			add_action( 'admin_notices', 'FrmProAppController::admin_notices' );
			return;
		}

		$frm_vars['next_page'] = array();
		$frm_vars['prev_page'] = array();
		$frm_vars['pro_is_installed'] = 'deprecated';
		add_filter('frm_pro_installed', '__return_true');

		add_filter( 'frm_load_controllers', 'FrmProHooksController::load_controllers' );
		FrmHooksController::trigger_load_hook();
		remove_filter( 'frm_load_controllers', 'FrmProHooksController::load_controllers' );
		add_filter( 'frm_load_controllers', 'FrmProHooksController::add_hook_controller' );
	}

	/**
	 * @since 3.0
	 */
	public static function load_controllers( $controllers ) {
		unset( $controllers[0] ); // don't load hooks in free again
		return self::add_hook_controller( $controllers );
	}

	/**
	 * @since 3.0
	 */
	public static function add_hook_controller( $controllers ) {
		$controllers[] = 'FrmProHooksController';
		return $controllers;
	}

	public static function load_hooks() {
		global $wp_version;

		add_action( 'plugins_loaded', 'FrmProAppController::load_lang' );
		add_action( 'init', 'FrmProAppController::create_taxonomies', 0 );
		add_filter( 'frm_combined_js_files', 'FrmProAppController::combine_js_files' );
		add_filter( 'frm_js_location', 'FrmProAppController::pro_js_location' );
		add_action( 'wp_before_admin_bar_render', 'FrmProAppController::admin_bar_configure', 25 );
		add_action( 'frm_before_get_form', 'FrmProAppController::register_scripts' );

		add_filter( 'frm_db_needs_upgrade', 'FrmProDb::needs_upgrade' );
		add_action( 'frm_before_install', 'FrmProDb::before_free_version_db_upgrade' );
		add_action( 'frm_after_install', 'FrmProDb::upgrade' );
		add_action( 'delete_nested_views', 'FrmProDb::delete_nested_views_folder' );

		add_filter('wpmu_drop_tables', 'FrmProAppController::drop_tables');

		add_shortcode('frm_set_get', 'FrmProAppController::set_get');
		add_shortcode('frm-set-get', 'FrmProAppController::set_get');
		add_shortcode( 'frm-condition', 'FrmProAppController::frm_condition_shortcode' );

		// Views
		add_action( 'init', 'FrmProContent::add_rewrite_endpoint' );

		// Entries Controller
		add_filter('frm_data_sort', 'FrmProEntriesController::data_sort', 20);

		add_filter('frm_update_entry', 'FrmProEntriesController::check_draft_status', 10, 2);
		add_action('frm_after_create_entry', 'FrmProEntriesController::remove_draft_hooks', 1);
		add_action('frm_process_entry', 'FrmProEntriesController::process_update_entry', 10, 4);
		add_action('frm_display_form_action', 'FrmProEntriesController::edit_update_form', 10, 5);
		add_action('frm_submit_button_action', 'FrmProEntriesController::ajax_submit_button');
		add_filter('frm_success_filter', 'FrmProEntriesController::get_confirmation_method', 10, 3);
		add_action('deleted_post', 'FrmProEntriesController::delete_entry');
		add_action('trashed_post', 'FrmProEntriesController::trashed_post');
		add_action('untrashed_post', 'FrmProEntriesController::trashed_post');
		add_filter( 'frm_show_entry_defaults', 'FrmProEntriesController::show_entry_defaults' );

		add_filter( 'frmpro_fields_replace_shortcodes', 'FrmProEntriesController::filter_shortcode_value', 10, 4 );
		add_filter( 'frm_display_value_custom', 'FrmProEntriesController::filter_display_value', 1, 3 );
		add_filter( 'frm_display_value_atts', 'FrmProEntriesController::display_value_atts', 10, 2 );
		add_filter( 'frm_display_value', 'FrmProEntriesController::display_value', 10, 3 );

		add_action( 'frm_after_create_entry', 'FrmProEntriesController::maybe_set_cookie', 20, 2 );
		add_filter( 'frm_setup_edit_entry_vars', 'FrmProEntriesController::setup_edit_vars' );

		// File field
		add_filter( 'frm_validate_entry', 'FrmProFileField::upload_files_no_js', 10, 1 );
		add_action( 'frm_before_destroy_entry', 'FrmProFileField::delete_files_with_entry', 10, 2 );
		add_action( 'frm_after_duplicate_entry', 'FrmProFileField::duplicate_files_with_entry', 10, 3 );
		add_filter( 'rest_attachment_query', 'FrmProFileField::filter_api_attachments' );

		// Entry and Meta Helpers
		add_filter('frm_show_new_entry_page', 'FrmProEntriesHelper::allow_form_edit', 10, 2);

		// Entry Shortcodes
		add_shortcode('formresults', 'FrmProEntriesController::get_form_results');
		add_shortcode('frm-search', 'FrmProEntriesController::get_search');
		add_shortcode('frm-entry-links', 'FrmProEntriesController::entry_link_shortcode');
		add_shortcode('frm-entry-edit-link', 'FrmProEntriesController::entry_edit_link');
		add_shortcode('frm-entry-update-field', 'FrmProEntriesController::entry_update_field');
		add_shortcode('frm-entry-delete-link', 'FrmProEntriesController::entry_delete_link');
		add_shortcode('frm-field-value', 'FrmProEntriesController::get_field_value_shortcode');
		add_shortcode('frm-show-entry', 'FrmProEntriesController::show_entry_shortcode');
		add_shortcode('frm-alt-color', 'FrmProEntriesController::change_row_color');

		// Trigger entry model
		add_action('frm_validate_form_creation', 'FrmProEntry::validate', 10, 5);
		add_filter('frm_pre_create_entry', 'FrmProEntry::mod_other_vals', 10, 1);
		add_filter('frm_pre_update_entry', 'FrmProEntry::mod_other_vals', 10, 1);
		add_filter('frm_pre_create_entry', 'FrmProEntry::save_sub_entries', 20, 2);
		add_filter('frm_pre_update_entry', 'FrmProEntry::save_sub_entries', 20, 2);
		add_action('frm_after_duplicate_entry', 'FrmProEntry::duplicate_sub_entries', 10, 3);
		add_action('frm_after_create_entry', 'FrmProEntry::update_parent_id', 10, 2);

		// Trigger entry meta model
		add_filter('frm_validate_field_entry', 'FrmProEntryMeta::validate', 10, 4);

		// Field Factory
		add_filter( 'frm_create_field_value_selector', 'FrmProFieldFactory::create_field_value_selector', 10, 3 );
		add_filter( 'frm_get_field_type_class', 'FrmProFieldFactory::get_field_type_class', 10, 2 );

		// Fields Controller
		add_filter('frm_field_type', 'FrmProFieldsController::change_type', 9, 2);
		add_filter('frm_field_value_saved', 'FrmProFieldsController::use_field_key_value', 10, 3);
		add_action('frm_field_input_html', 'FrmProFieldsController::input_html', 10, 2);
		add_filter('frm_field_classes', 'FrmProFieldsController::add_field_class', 20, 2);
		add_action( 'template_redirect', 'FrmProFieldsController::redirect_attachment', 1 );

		// Fields Helper
		add_filter('frm_posted_field_ids', 'FrmProFieldsHelper::posted_field_ids');
		add_filter('frm_pro_available_fields', 'FrmProFieldsHelper::modify_available_fields', 10);
		add_filter('frm_is_field_hidden', 'FrmProFieldsHelper::route_to_is_field_hidden', 10, 3);
		add_filter( 'frm_get_current_page', 'FrmProFieldsHelper::get_current_page', 10, 3 );

		// Form Actions Controller
		add_action('frm_registered_form_actions', 'FrmProFormActionsController::register_actions');
		add_filter('frm_email_control_settings', 'FrmProFormActionsController::email_action_control');
		add_filter( 'frm_trigger_create_action', 'FrmProFormActionsController::maybe_trigger_draft_actions', 10, 2 );
		add_action('frm_after_update_entry', 'FrmProFormActionsController::trigger_update_actions', 10, 2);
		add_action('frm_before_destroy_entry', 'FrmProFormActionsController::trigger_delete_actions', 20, 2);

		// Forms Controller
		if ( ! FrmAppHelper::is_admin() ) {
			add_action( 'wp_footer', 'FrmProFormsController::enqueue_footer_js', 19 );
			add_action( 'wp_footer', 'FrmProFormsController::footer_js', 20 );
		}
		add_action( 'wp_head', 'FrmProFormsController::head' );
		add_action('formidable_shortcode_atts', 'FrmProFormsController::formidable_shortcode_atts', 10, 2);
		add_action( 'frm_pre_get_form', 'FrmProFormsController::add_submit_conditions_to_frm_vars', 10 );
		add_filter('frm_replace_content_shortcodes', 'FrmProFormsController::replace_content_shortcodes', 10, 3);
		add_filter('frm_conditional_shortcodes', 'FrmProFormsController::conditional_options');
		add_filter( 'frm_helper_shortcodes', 'FrmProFormsController::add_pro_field_helpers', 10, 2 );
		add_filter( 'frm_form_strings', 'FrmProFormsController::add_form_strings', 10, 2 );

		add_filter( 'frm_validate_entry', 'FrmProFormsHelper::can_submit_form_now', 15, 2 );
		add_filter( 'frm_pre_display_form', 'FrmProFormsHelper::prepare_inline_edit_form', 10, 1 );
		add_filter( 'frm_submit_button_class', 'FrmProFormsHelper::add_submit_button_class', 10, 2 );

		// trigger form model
		add_filter('frm_validate_form', 'FrmProFormsController::validate', 10, 2);

		add_action( 'frm_after_title', 'FrmProPageField::page_navigation' );

		// Images model
		add_filter( 'frm_field_div_classes', 'FrmProImages::get_image_option_classes', 10, 2 );
		add_action( 'frm_admin_single_opt', 'FrmProImages::admin_options' );

		// Posts model
		add_action( 'frm_trigger_wppost_action', 'FrmProPost::save_post', 10, 3 );
		add_action( 'frm_before_destroy_entry', 'FrmProPost::destroy_post', 10, 2 );

		// Stats Controller
		add_shortcode('frm-stats', 'FrmProStatisticsController::stats_shortcode');

		// Math Controller
		add_shortcode( 'frm-math', 'FrmProMathController::math_shortcode' );

		// Styles Controller
		add_action( 'frm_include_front_css', 'FrmProStylesController::include_front_css' );
		add_filter( 'frm_default_style_settings', 'FrmProStylesController::add_defaults' );
		add_filter( 'frm_override_default_styles', 'FrmProStylesController::override_defaults' );

		// Graphs Controller
		add_shortcode('frm-graph', 'FrmProGraphsController::graph_shortcode');
		add_action('frm_form_action_reports', 'FrmProGraphsController::show_reports', 9);

		// notification model
		add_filter('frm_notification_attachment', 'FrmProNotification::add_attachments', 1, 3 );

		// XML Controller
		add_filter( 'frm_importing_xml', 'FrmProXMLController::importing_xml', 10, 2 );
		add_action( 'frm_update_child_form_parent_id', 'FrmProXMLHelper::maybe_update_in_section_variables_for_repeater_children', 10, 2 );

		// Simple Blocks Controller
		add_action( 'init', 'FrmProSimpleBlocksController::register_simple_view_block', 20 );

		// Protect files
		add_action( 'init', 'FrmProFileField::check_for_download', 10, 0 );
		add_filter( 'wp_get_attachment_url', 'FrmProFileField::filter_attachment_url', 10, 2 );
		add_filter( 'wp_get_attachment_image_src', 'FrmProFileField::filter_attachment_image_src', 10, 4 );
		add_filter( 'wp_get_attachment_image_attributes', 'FrmProFileField::filter_attachment_image_attributes', 10, 3 );

		$number_of_parameters_supported = version_compare( $wp_version, '5.3' ) === -1 ? 2 : 3;
		add_filter( 'wp_generate_attachment_metadata', 'FrmProFileField::protect_metadata_attachments', 10, $number_of_parameters_supported );
	}

	public static function load_admin_hooks() {
		add_action('frm_after_uninstall', 'FrmProDb::uninstall');
		add_filter( 'frm_form_nav_list', 'FrmProAppController::form_nav', 10, 2 );
		add_filter( 'frm_icon', 'FrmProAppController::whitelabel_icon', 10, 2 );
		add_filter( 'frm_media_icon', 'FrmProAppController::whitelabel_media_icon' );
		add_filter( 'frm_admin_script_strings', 'FrmProAppController::admin_js_strings' );

		// Addons Controller
		add_action( 'frm_overlay_footer', 'FrmProAddonsController::show_expired_message' );
		add_action( 'frm_page_footer', 'FrmProAddonsController::renewal_message' );

		// Entries Controller
		add_action('frm_after_show_entry', 'FrmProEntriesController::show_comments');

		add_action('add_meta_boxes', 'FrmProEntriesController::create_entry_from_post_box', 10, 2);

		// admin listing page
		add_action('frm_entry_action_route', 'FrmProEntriesController::route');
		add_filter('frm_entries_list_class', 'FrmProEntriesController::list_class');
		add_filter('frm_row_actions', 'FrmProEntriesController::row_actions', 10, 2 );

		// entries helper
		add_filter( 'frm_entry_actions_dropdown', 'FrmProEntriesHelper::add_actions_dropdown', 10, 2 );

		// Address Fields
		add_filter( 'frm_csv_field_columns', 'FrmProAddressesController::add_csv_columns', 10, 2 );

		// Credit Card Fields
		add_filter( 'frm_csv_field_columns', 'FrmProCreditCardsController::add_csv_columns', 10, 2 );

		// Upload Fields
		add_filter( 'frm_import_val', 'FrmProFileImport::import_attachment', 10, 2 );
		add_action( 'pre_get_posts', 'FrmProFileField::filter_media_library', 99 );

		// Fields Controller
		add_action('frm_after_field_created', 'FrmProFieldsController::create_multiple_fields', 10, 2);
		add_action('frm_duplicate_field_divider', 'FrmProFieldsController::duplicate_section', 10, 2);
		add_action('frm_field_options_form_top', 'FrmProFieldsController::options_form_top', 10, 3);
		add_action('frm_before_field_options', 'FrmProFieldsController::options_form_before');
		add_action( 'frm_field_options', 'FrmProFieldsController::advanced_field_options' );
		add_action( 'frm_after_field_options', 'FrmProFieldsController::options_form_after', 10, 3 );
		add_action( 'frm_default_value_setting', 'FrmProFieldsController::more_default_values' );
		add_filter( 'frm_default_value_types', 'FrmProFieldsController::default_value_types', 10, 2 );
		add_filter('frm_build_field_class', 'FrmProFieldsController::build_field_class', 10, 2);
		add_filter( 'frm_clean_divider_field_options_before_update', 'FrmProFieldsController::update_repeater_form_name' );
		add_action( 'restrict_manage_posts', 'FrmProFieldsController::filter_media_library_link' );
		add_action( 'admin_footer', 'FrmProFieldsController::delete_temp_files' );
		add_filter( 'frm_single_input_fields', 'FrmProFieldsController::single_input_fields' );

		// Fields Helper
		add_filter('frm_show_custom_html', 'FrmProFieldsHelper::show_custom_html', 10, 2);

		// Trigger field model
		add_filter( 'frm_before_field_created', 'FrmProField::create' );
		add_filter( 'frm_field_options_to_update', 'FrmProField::skip_update_field_setting' );
		add_filter( 'frm_update_field_options', 'FrmProField::update', 10, 3 );
		add_filter( 'frm_duplicated_field', 'FrmProField::duplicate', 10, 2 );
		add_action( 'frm_before_destroy_field', 'FrmProField::delete' );
		add_filter( 'frm_create_repeat_form', 'FrmProField::create_repeat_form', 10, 2 );

		// Form Actions Controller
		add_action('frm_additional_action_settings', 'FrmProFormActionsController::form_action_settings', 10, 2);
		add_action('frm_form_action_settings', 'FrmProFormActionsController::fill_action_options', 10, 2);
		add_filter( 'frm_action_update_callback', 'FrmProFormActionsController::remove_incomplete_logic' );

		// Forms Controller
		add_filter( 'frm_builder_field_label', 'FrmProFormsController::builder_field_label', 10, 2 );
		if ( FrmAppHelper::is_admin_page( 'formidable' ) ) {
			// form builder page hooks
			add_action('frm_noallow_class', 'FrmProFormsController::noallow_class');
			add_filter( 'frm_smart_values_box', 'FrmProFormsController::smart_values_box' );
			add_filter('frmpro_field_links', 'FrmProFormsController::add_field_link');

			// form settings page
			add_filter('frm_before_save_wppost_action', 'FrmProFormsController::save_wppost_actions', 10, 2 );
			add_filter('frm_update_form_field_options', 'FrmProFormsController::update_form_field_options', 10, 2);
			add_action( 'frm_add_form_settings_section', 'FrmProFormsController::form_settings_sections' );
			add_action( 'frm_add_form_perm_options', 'FrmProFormsController::add_form_page_options', 100 );
			add_action('frm_add_form_ajax_options', 'FrmProFormsController::add_form_ajax_options');
			add_action('frm_add_form_button_options', 'FrmProFormsController::add_form_button_options');
			add_action( 'frm_add_form_style_tab_options', 'FrmProFormsController::add_form_style_tab_options' );
			add_action('frm_add_form_msg_options', 'FrmProFormsController::add_form_msg_options');
			add_action( 'admin_enqueue_scripts', 'FrmProAppController::load_admin_js_assets' );
		}

		add_action( 'admin_init', 'FrmProFormsController::admin_js', 1 );
		// enqueue right before scripts are printed
		add_action( 'admin_footer', 'FrmProFormsController::enqueue_footer_js', 19 );
		// print our scripts after js files have been loaded
		add_action( 'admin_print_footer_scripts', 'FrmProFormsController::footer_js', 40 );

		add_filter('frm_setup_new_form_vars', 'FrmProFormsController::setup_new_vars');
		add_filter('frm_setup_edit_form_vars', 'FrmProFormsController::setup_edit_vars');
		add_filter('frm_advanced_shortcodes', 'FrmProFormsController::advanced_options');

		// form settings and import
		add_filter( 'frm_form_options_before_update', 'FrmProFormsController::update_options', 10, 2 );

		// form builder and form settings pages
		add_action('frm_update_form', 'FrmProFormsController::update', 10, 2);

		// form builder and import page
		add_filter('frm_after_duplicate_form_values', 'FrmProFormsController::after_duplicate');

		// edit post page with shortcode popup
		add_filter('frm_popup_shortcodes', 'FrmProFormsController::popup_shortcodes');
		add_filter('frm_sc_popup_opts', 'FrmProFormsController::sc_popup_opts', 10, 2);

		// Images
		add_filter( 'frm_build_field_class', 'FrmProImages::get_image_option_classes', 20, 2 );

		// Settings Controller
		add_filter( 'frm_add_settings_section', 'FrmProSettingsController::add_settings_section', 1 );
		add_action( 'frm_update_settings', 'FrmProSettingsController::update' );
		add_action('frm_store_settings', 'FrmProSettingsController::store');
		add_filter( 'frm_advanced_helpers', 'FrmProSettingsController::advanced_helpers', 10, 2 );
		add_filter( 'frm_filter_inbox', 'FrmProSettingsController::filter_inbox' );
		add_filter( 'frm_inbox_badge', 'FrmProSettingsController::inbox_badge' );

		// Styles Controller
		add_action( 'wp_ajax_pro_fields_css', 'FrmProStylesController::include_pro_fields_ajax_css' );
		add_action( 'frm_output_single_style', 'FrmProStylesController::output_single_style' );
		add_filter( 'frm_style_boxes', 'FrmProStylesController::add_style_boxes' );

		// XML Controller
		add_filter('frm_xml_route', 'FrmProXMLController::route', 10, 2 );
		add_filter('frm_upload_instructions1', 'FrmProXMLController::csv_instructions_1');
		add_filter('frm_upload_instructions2', 'FrmProXMLController::csv_instructions_2');
		add_action('frm_csv_opts', 'FrmProXMLController::csv_opts');
		add_filter( 'frm_csv_where', 'FrmProXMLController::csv_filter', 1, 2 );
		add_filter( 'frm_csv_row', 'FrmProXMLController::csv_row', 10, 2 );
		add_filter( 'frm_csv_value', 'FrmProXMLController::csv_field_value', 10, 2 );
		add_filter('frm_xml_export_types', 'FrmProXMLController::xml_export_types');
		add_filter('frm_export_formats', 'FrmProXMLController::export_formats');
		add_action('frm_before_import_csv', 'FrmProXMLController::map_csv_fields');

		// XML Helper
		add_action( 'frm_after_field_is_imported', 'FrmProXMLHelper::after_field_is_imported', 10, 2 );
		add_filter( 'frm_import_xml_field', 'FrmProXMLHelper::run_field_migrations' );

		// Lookup Controller
		add_filter( 'frm_clean_lookup_field_options_before_update', 'FrmProLookupFieldsController::clean_field_options_before_update' );

		// Time Controller
		add_action('wp_ajax_frm_fields_ajax_time_options', 'FrmProTimeFieldsController::ajax_time_options');
		add_action('wp_ajax_nopriv_frm_fields_ajax_time_options', 'FrmProTimeFieldsController::ajax_time_options');

		// Usage
		add_filter( 'frm_usage_settings', 'FrmProUsageController::settings' );
		add_filter( 'frm_usage_form', 'FrmProUsageController::form', 10, 2 );

		// Summary field
		add_action( 'frm_before_create_field', 'FrmProFieldSummary::maybeAddBreakFieldBeforeSummary', 10, 2 );
	}

	public static function load_ajax_hooks() {
		// Addons Controller
		add_action( 'wp_ajax_frm_multiple_addons', 'FrmProAddonsController::ajax_multiple_addons' );

		// Entries Controller
		add_action('wp_ajax_frm_create_post_entry', 'FrmProEntriesController::create_post_entry');

		add_action('wp_ajax_nopriv_frm_entries_ajax_set_cookie', 'FrmProEntriesController::ajax_set_cookie');
		add_action('wp_ajax_frm_entries_ajax_set_cookie', 'FrmProEntriesController::ajax_set_cookie');

		add_action( 'wp_loaded', 'FrmProEntriesController::ajax_create', 5 ); //trigger before process_entry
		add_action('wp_ajax_frm_entries_destroy', 'FrmProEntriesController::wp_ajax_destroy');
		add_action('wp_ajax_nopriv_frm_entries_destroy', 'FrmProEntriesController::wp_ajax_destroy');
		add_action('wp_ajax_frm_entries_edit_entry_ajax', 'FrmProEntriesController::edit_entry_ajax');
		add_action('wp_ajax_nopriv_frm_entries_edit_entry_ajax', 'FrmProEntriesController::edit_entry_ajax');
		add_action('wp_ajax_frm_entries_update_field_ajax', 'FrmProEntriesController::update_field_ajax');
		add_action('wp_ajax_nopriv_frm_entries_update_field_ajax', 'FrmProEntriesController::update_field_ajax');
		add_action('wp_ajax_frm_entries_send_email', 'FrmProEntriesController::send_email');
		add_action('wp_ajax_nopriv_frm_entries_send_email', 'FrmProEntriesController::send_email');

		// Fields Controller
		add_action('wp_ajax_frm_get_field_selection', 'FrmProFieldsController::get_field_selection');
		add_action('wp_ajax_frm_get_field_values', 'FrmProFieldsController::get_field_values');
		add_action('wp_ajax_frm_get_dynamic_widget_opts', 'FrmProFieldsController::get_dynamic_widget_opts');
		add_action('wp_ajax_frm_fields_ajax_get_data', 'FrmProFieldsController::ajax_get_data');
		add_action('wp_ajax_nopriv_frm_fields_ajax_get_data', 'FrmProFieldsController::ajax_get_data');
		add_action('wp_ajax_frm_fields_ajax_data_options', 'FrmProFieldsController::ajax_data_options');
		add_action('wp_ajax_nopriv_frm_fields_ajax_data_options', 'FrmProFieldsController::ajax_data_options');
		add_action('wp_ajax_frm_add_logic_row', 'FrmProFieldsController::_logic_row');
		add_action( 'wp_ajax_frm_update_field_after_move', 'FrmProFieldsController::update_field_after_move' );
		add_action( 'wp_ajax_nopriv_frm_submit_dropzone', 'FrmProFieldsController::ajax_upload' );
		add_action( 'wp_ajax_frm_submit_dropzone', 'FrmProFieldsController::ajax_upload' );
		add_action( 'wp_ajax_frm_add_field_option', 'FrmProFieldsController::add_other_option' );
		add_action( 'wp_ajax_frm_bulk_products', 'FrmProFieldsController::bulk_edit_products' );
		add_action( 'wp_ajax_frm_user_search', 'FrmProFieldsController::user_search' );

		// Lookup Fields
		add_action( 'wp_ajax_frm_add_watch_lookup_row', 'FrmProLookupFieldsController::add_watch_lookup_row' );
		add_action( 'wp_ajax_frm_get_options_for_get_values_field', 'FrmProLookupFieldsController::ajax_get_options_for_get_values_field' );
		add_action('wp_ajax_frm_replace_lookup_field_options', 'FrmProLookupFieldsController::ajax_get_dependent_lookup_field_options');
		add_action('wp_ajax_nopriv_frm_replace_lookup_field_options', 'FrmProLookupFieldsController::ajax_get_dependent_lookup_field_options');
		add_action('wp_ajax_frm_replace_cb_radio_lookup_options', 'FrmProLookupFieldsController::ajax_get_dependent_cb_radio_lookup_options');
		add_action('wp_ajax_nopriv_frm_replace_cb_radio_lookup_options', 'FrmProLookupFieldsController::ajax_get_dependent_cb_radio_lookup_options');
		add_action('wp_ajax_nopriv_frm_get_lookup_text_value', 'FrmProLookupFieldsController::ajax_get_text_field_lookup_value');
		add_action('wp_ajax_frm_get_lookup_text_value', 'FrmProLookupFieldsController::ajax_get_text_field_lookup_value');

		// Form Actions Controller
		add_action('wp_ajax_frm_add_form_logic_row', 'FrmProFormActionsController::_logic_row');
		add_action('wp_ajax_frm_add_postmeta_row', 'FrmProFormActionsController::_postmeta_row');
		add_action('wp_ajax_frm_add_posttax_row', 'FrmProFormActionsController::_posttax_row');
		add_action('wp_ajax_frm_replace_posttax_options', 'FrmProFormActionsController::_replace_posttax_options');

		//Form general settings controller
		add_action('wp_ajax_frm_add_submit_logic_row', 'FrmProFormsController::_submit_logic_row');

		// Nested forms controller
		add_action('wp_ajax_frm_add_form_row', 'FrmProNestedFormsController::ajax_add_repeat_row');
		add_action('wp_ajax_nopriv_frm_add_form_row', 'FrmProNestedFormsController::ajax_add_repeat_row');

		// XML Controller
		add_action('wp_ajax_frm_import_csv', 'FrmProXMLController::import_csv_entries');

		// Updates
		add_action( 'wp_ajax_frm_deauthorize', 'FrmProEddController::deactivate', 9 );
		add_action( 'wp_ajax_frm_reset_cache', 'FrmProEddController::reset_cache' );
	}

	public static function load_form_hooks() {
		global $frm_input_masks;
		$frm_input_masks = array();

		// Entries Controller
		add_filter('frm_continue_to_new', 'FrmProEntriesController::maybe_editing', 10, 3);

		// Fields Controller
		add_action('frm_get_field_scripts', 'FrmProFieldsController::show_field', 10, 3);
		add_action('frm_date_field_js', 'FrmProFieldsController::date_field_js', 10, 2);
		add_filter( 'frm_is_field_required', 'FrmProFieldsController::maybe_make_field_optional', 10, 2 );

		// Fields Helper
		add_filter('frm_get_default_value', 'FrmProFieldsHelper::get_default_value', 10, 4);
		add_filter('frm_get_default_value', 'FrmProFieldsHelper::get_dynamic_field_default_value', 11, 4);
		add_filter('frm_filter_default_value', 'FrmProFieldsHelper::get_default_value', 10, 3);
		add_filter('frm_setup_new_fields_vars', 'FrmProFieldsHelper::setup_new_vars', 10, 2);
		add_filter('frm_setup_edit_fields_vars', 'FrmProFieldsHelper::setup_edit_vars', 10, 3);
		add_filter( 'frm_default_field_options', 'FrmProFieldsHelper::add_default_field_settings', 10, 2 );
		add_action('frm_after_checkbox', 'FrmProFieldsHelper::get_child_checkboxes');
		add_filter('frm_get_paged_fields', 'FrmProFieldsHelper::get_form_fields', 10, 3);
		add_filter('frm_before_replace_shortcodes', 'FrmProFieldsHelper::before_replace_shortcodes', 10, 2);
		add_filter('frm_replace_shortcodes', 'FrmProFieldsHelper::replace_html_shortcodes', 10, 3);
		add_filter( 'frm_field_div_classes', 'FrmProFieldsHelper::get_field_div_classes', 10, 3 );

		// Forms Controller
		add_action( 'frm_enqueue_form_scripts', 'FrmProFormsController::after_footer_loaded' );
		add_filter( 'frm_form_classes', 'FrmProFormsController::add_form_classes' );
		add_filter('frm_form_fields_class', 'FrmProFormsController::form_fields_class');
		add_action('frm_entry_form', 'FrmProFormsController::form_hidden_fields', 10, 2);
		add_filter('frm_submit_button', 'FrmProFormsController::submit_button_label', 5, 2);
		add_filter('frm_form_replace_shortcodes', 'FrmProFormsController::replace_shortcodes', 10, 3);
	}

	public static function load_view_hooks() {
		add_filter( 'frm_display_entry_content', 'FrmProContent::replace_shortcodes', 10, 7 );
	}

	public static function load_multisite_hooks() {
		// Copies Controller
		add_action( 'init', 'FrmProCopiesController::copy_forms' );
		add_action('frm_after_install', 'FrmProCopiesController::activation_install', 20);
		add_action('frm_update_form', 'FrmProCopiesController::save_copied_form', 20, 2);
		add_action('frm_destroy_form', 'FrmProCopiesController::destroy_copied_form');
		add_action( 'wp_uninitialize_site', 'FrmProCopiesController::delete_copy_rows', 20, 2 );
	}
}
