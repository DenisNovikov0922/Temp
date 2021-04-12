<?php

// Require Merlin autoloader
require_once get_parent_theme_file_path( '/admin/merlin/vendor/autoload.php' );

// Require Merlin main class
require_once get_parent_theme_file_path( '/admin/merlin/class-merlin.php' );

if ( ! class_exists( 'Merlin' ) ) {
	return;
}

/**
 * Set directory locations, text strings, and settings.
 */
$wizard = new Merlin( 
	$config = array(
		'directory'            => 'admin/merlin', // Location / directory where Merlin WP is placed in your theme.
		'merlin_url'           => 'merlin', // The wp-admin page slug where Merlin WP loads.
		'parent_slug'          => 'themes.php', // The wp-admin parent page slug for the admin menu item.
		'capability'           => 'manage_options', // The capability required for this menu to be displayed to the user.
		'child_action_btn_url' => 'https://codex.wordpress.org/child_themes', // URL for the 'child-action-link'.
		'dev_mode'             => true, // Enable development mode for testing.
		'license_step'         => false, // EDD license activation step.
		'license_required'     => false, // Require the license activation step.
		'license_help_url'     => '', // URL for the 'license-tooltip'.
		'edd_remote_api_url'   => '', // EDD_Theme_Updater_Admin remote_api_url.
		'edd_item_name'        => '', // EDD_Theme_Updater_Admin item_name.
		'edd_theme_slug'       => '', // EDD_Theme_Updater_Admin item_slug.
		'ready_big_button_url' => '', // Link for the big button on the ready step.
	),
	$strings = array(
		'admin-menu'               => esc_html__( 'Theme Setup', 'jumpstart' ),
		/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
		'title%s%s%s%s'            => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'jumpstart' ),
		'return-to-dashboard'      => esc_html__( 'Return to the dashboard', 'jumpstart' ),
		'ignore'                   => esc_html__( 'Disable this wizard', 'jumpstart' ),
		'btn-skip'                 => esc_html__( 'Skip', 'jumpstart' ),
		'btn-next'                 => esc_html__( 'Next', 'jumpstart' ),
		'btn-start'                => esc_html__( 'Start', 'jumpstart' ),
		'btn-no'                   => esc_html__( 'Cancel', 'jumpstart' ),
		'btn-plugins-install'      => esc_html__( 'Install', 'jumpstart' ),
		'btn-child-install'        => esc_html__( 'Install', 'jumpstart' ),
		'btn-content-install'      => esc_html__( 'Install', 'jumpstart' ),
		'btn-import'               => esc_html__( 'Import', 'jumpstart' ),
		'btn-license-activate'     => esc_html__( 'Activate', 'jumpstart' ),
		'btn-license-skip'         => esc_html__( 'Later', 'jumpstart' ),
		/* translators: Theme Name */
		'license-header%s'         => esc_html__( 'Activate %s', 'jumpstart' ),
		/* translators: Theme Name */
		'license-header-success%s' => esc_html__( '%s is Activated', 'jumpstart' ),
		/* translators: Theme Name */
		'license%s'                => esc_html__( 'Enter your license key to enable remote updates and theme support.', 'jumpstart' ),
		'license-label'            => esc_html__( 'License key', 'jumpstart' ),
		'license-success%s'        => esc_html__( 'The theme is already registered, so you can go to the next step!', 'jumpstart' ),
		'license-json-success%s'   => esc_html__( 'Your theme is activated! Remote updates and theme support are enabled.', 'jumpstart' ),
		'license-tooltip'          => esc_html__( 'Need help?', 'jumpstart' ),
		/* translators: Theme Name */
		'welcome-header%s'         => esc_html__( 'Welcome to %s', 'jumpstart' ),
		'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'jumpstart' ),
		'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'jumpstart' ),
		'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'jumpstart' ),
		'child-header'             => esc_html__( 'Install Child Theme', 'jumpstart' ),
		'child-header-success'     => esc_html__( 'You\'re good to go!', 'jumpstart' ),
		'child'                    => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'jumpstart' ),
		'child-success%s'          => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'jumpstart' ),
		'child-action-link'        => esc_html__( 'Learn about child themes', 'jumpstart' ),
		'child-json-success%s'     => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'jumpstart' ),
		'child-json-already%s'     => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'jumpstart' ),
		'plugins-header'           => esc_html__( 'Install Plugins', 'jumpstart' ),
		'plugins-header-success'   => esc_html__( 'You\'re up to speed!', 'jumpstart' ),
		'plugins'                  => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'jumpstart' ),
		'plugins-success%s'        => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'jumpstart' ),
		'plugins-action-link'      => esc_html__( 'Advanced', 'jumpstart' ),
		'import-header'            => esc_html__( 'Import Content', 'jumpstart' ),
		'import'                   => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.', 'jumpstart' ),
		'import-action-link'       => esc_html__( 'Advanced', 'jumpstart' ),
		'ready-header'             => esc_html__( 'All done. Have fun!', 'jumpstart' ),
		/* translators: Theme Author */
		'ready%s'                  => esc_html__( 'Your theme has been all set up. Enjoy your new theme by %s.', 'jumpstart' ),
		'ready-action-link'        => esc_html__( 'Extras', 'jumpstart' ),
		'ready-big-button'         => esc_html__( 'View your website', 'jumpstart' ),
		'ready-link-1'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__( 'Explore WordPress', 'jumpstart' ) ),
		'ready-link-2'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://www.tommusrhodus.com/contact/', esc_html__( 'Get Theme Support', 'jumpstart' ) ),
		'ready-link-3'             => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'customize.php' ), esc_html__( 'Start Customizing', 'jumpstart' ) ),
	)
);

/**
* Setup Demo Data Files
*/
function tommusrhodus_setup_merlin_local_import_files() {

	$import_notice_vc = '
		<h3>Ready to Import Jumpstart Demo Data</h3>
		<p>Please ensure all required plugins in "appearance => install plugins" are installed before running this demo importer.</p>
	';

	return array(
		array(
            'import_file_name'             		=> 'Jumpstart Demo Data',
            'local_import_file'              	=> get_parent_theme_file_path( '/admin/demo-data/demo-data.xml' ),
            'local_import_widget_file'       	=> get_parent_theme_file_path( '/admin/demo-data/demo-widgets.wie' ),
            'local_import_customizer_file'   	=> get_parent_theme_file_path( '/admin/demo-data/demo-options.dat' ),
            'import_preview_image_url'     		=> get_parent_theme_file_path( '/screenshot.png' ),
            'import_notice'                		=> $import_notice_vc,
		),
	);
}
add_filter( 'merlin_import_files', 'tommusrhodus_setup_merlin_local_import_files' );

function tommusrhodus_merlin_after_import_setup() {
	
    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Standard Navigation', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary'  	=> $main_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Overview' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}

add_action( 'merlin_after_all_import', 'tommusrhodus_merlin_after_import_setup' );