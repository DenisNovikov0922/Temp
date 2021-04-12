<?php
/**
 * Merlin WP configuration file.
 *
 * @package   Merlin WP
 * @version   @@pkg.version
 * @link      https://merlinwp.com/
 * @author    Rich Tabor, from ThemeBeans.com & the team at ProteusThemes.com
 * @copyright Copyright (c) 2018, Merlin WP of Inventionn LLC
 * @license   Licensed GPLv3 for Open Source Use
 */
if ( ! class_exists( 'Merlin' ) ) {
	return;
}
/**
 * Set directory locations, text strings, and settings.
 */
$wizard = new Merlin(
	$config = array(
		'directory'            => 'inc/merlin', // Location / directory where Merlin WP is placed in your theme.
		'merlin_url'           => 'woofood-wizard', // The wp-admin page slug where Merlin WP loads.
		'parent_slug'          => 'themes.php', // The wp-admin parent page slug for the admin menu item.
		'capability'           => 'manage_options', // The capability required for this menu to be displayed to the user.
		'child_action_btn_url' => 'https://codex.wordpress.org/child_themes', // URL for the 'child-action-link'.
		'dev_mode'             => true, // Enable development mode for testing.
		'license_step'         => true, //  license activation step.
		'license_required'     => true, // Require the license activation step.
		'license_help_url'     => 'https://www.wpslash.com/submit-ticket/', // URL for the 'license-tooltip'.
		'edd_remote_api_url'   => '', // EDD_Theme_Updater_Admin remote_api_url.
		'edd_item_name'        => '', // EDD_Theme_Updater_Admin item_name.
		'edd_theme_slug'       => '', // EDD_Theme_Updater_Admin item_slug.
		'logo_url' => get_template_directory_uri().'/inc/imgs/woofood-logo.png', // Link for the big button on the ready step.

		'ready_big_button_url' => home_url( '/' ), // Link for the big button on the ready step.
	
	),
	$strings = array(
		'admin-menu'               => esc_html__( 'WooFood Wizard', 'woofood' ),
		/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
		'title%s%s%s%s'            => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'woofood' ),
		'return-to-dashboard'      => esc_html__( 'Return to the dashboard', 'woofood' ),
		'ignore'                   => esc_html__( 'Disable this wizard', 'woofood' ),
		'btn-skip'                 => esc_html__( 'Skip', 'woofood' ),
		'btn-next'                 => esc_html__( 'Next', 'woofood' ),
		'btn-start'                => esc_html__( 'Start', 'woofood' ),
		'btn-no'                   => esc_html__( 'Cancel', 'woofood' ),
		'btn-plugins-install'      => esc_html__( 'Install', 'woofood' ),
		'btn-child-install'        => esc_html__( 'Install', 'woofood' ),
		'btn-content-install'      => esc_html__( 'Install', 'woofood' ),
		'btn-import'               => esc_html__( 'Import', 'woofood' ),
		'btn-license-activate'     => esc_html__( 'Activate', 'woofood' ),
		'btn-license-skip'         => esc_html__( 'Later', 'woofood' ),
		/* translators: Theme Name */
		'license-header%s'         => esc_html__( 'Activate %s', 'woofood' ),
		/* translators: Theme Name */
		'license-header-success%s' => esc_html__( '%s is Activated', 'woofood' ),
		/* translators: Theme Name */
		'license%s'                => esc_html__( 'Enter your license key to enable remote updates and theme support.', 'woofood' ),
		'no-license-link'          => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://www.wpslash.com/plugin/woofood/', esc_html__( 'Don\'t have a license yet? You can obtain one by clicking', 'woofood' ) ),

		'license-label'            => esc_html__( 'License key', 'woofood' ),
		'license-success%s'        => esc_html__( 'The theme is already registered, so you can go to the next step!', 'woofood' ),
		'license-json-success%s'   => esc_html__( 'Your theme is activated! Remote updates and theme support are enabled.', 'woofood' ),
		'license-tooltip'          => esc_html__( 'Need help?', 'woofood' ),
		/* translators: Theme Name */
		'welcome-header%s'         => esc_html__( 'Welcome to %s', 'woofood' ),
		'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'woofood' ),
		'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'woofood' ),
		'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'woofood' ),
		'child-header'             => esc_html__( 'Install Child Theme', 'woofood' ),
		'child-header-success'     => esc_html__( 'You\'re good to go!', 'woofood' ),
		'child'                    => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'woofood' ),
		'child-success%s'          => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'woofood' ),
		'child-action-link'        => esc_html__( 'Learn about child themes', 'woofood' ),
		'child-json-success%s'     => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'woofood' ),
		'child-json-already%s'     => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'woofood' ),
		'plugins-header'           => esc_html__( 'Install Plugins', 'woofood' ),
		'plugins-header-success'   => esc_html__( 'You\'re up to speed!', 'woofood' ),
		'plugins'                  => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'woofood' ),
		'plugins-success%s'        => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'woofood' ),
		'plugins-action-link'      => esc_html__( 'Advanced', 'woofood' ),
		'import-header'            => esc_html__( 'Import Content', 'woofood' ),
		'import'                   => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.', 'woofood' ),
		'import-action-link'       => esc_html__( 'Advanced', 'woofood' ),
		'ready-header'             => esc_html__( 'All done. Have fun!', 'woofood' ),
		/* translators: Theme Author */
		'ready%s'                  => esc_html__( 'Your theme has been all set up. Enjoy your new theme by %s.', 'woofood' ),
		'ready-action-link'        => esc_html__( 'Extras', 'woofood' ),
		'ready-big-button'         => esc_html__( 'View your website', 'woofood' ),
		'ready-link-1'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__( 'Explore WordPress', 'woofood' ) ),
		'ready-link-2'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://www.wpslash.com/submit-ticket/', esc_html__( 'Get WooFood Support', 'woofood' ) ),
		'ready-link-3'             => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'customize.php' ), esc_html__( 'Start Customizing', 'woofood' ) ),
	)
);
