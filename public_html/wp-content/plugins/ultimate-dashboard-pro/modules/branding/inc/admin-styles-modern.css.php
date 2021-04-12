<?php
/**
 * Admin styles.
 *
 * @package Ultimate_Dashboard_PRO
 *
 * @subpackage Branding
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$branding     = get_option( 'udb_branding' );
$accent_color = isset( $branding['accent_color'] ) ? $branding['accent_color'] : '';


if ( $accent_color ) {

	$hex = $accent_color;
	$hex = str_replace( '#', '', $hex );

	if ( 3 === strlen( $hex ) ) {
		$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
		$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
		$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
	} else {
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
	}

	$rgb = array( $r, $g, $b );

	?>

	/* This part is based on a WordPress color scheme */

	/* Links */
	a {
	  color: <?php echo esc_attr( $accent_color ); ?>;
	}

	a:hover, a:active, a:focus {
	  color: rgba(<?php echo esc_attr( $rgb[0] ); ?>,<?php echo esc_attr( $rgb[1] ); ?>, <?php echo esc_attr( $rgb[2] ); ?>, .7);
	}

	#post-body .misc-pub-post-status:before,
	#post-body #visibility:before,
	.curtime #timestamp:before,
	#post-body .misc-pub-revisions:before,
	span.wp-media-buttons-icon:before {
	  color: currentColor;
	}

	/* Forms */
	input[type=checkbox]:checked::before {
	  content: url("data:image/svg+xml;utf8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%2020%2020%27%3E%3Cpath%20d%3D%27M14.83%204.89l1.34.94-5.81%208.38H9.02L5.78%209.67l1.34-1.25%202.57%202.4z%27%20fill%3D%27%237e8993%27%2F%3E%3C%2Fsvg%3E");
	}

	input[type=radio]:checked::before {
	  background: <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-core-ui input[type="reset"]:hover,
	.wp-core-ui input[type="reset"]:active {
	  color: rgba(<?php echo esc_attr( $rgb[0] ); ?>,<?php echo esc_attr( $rgb[1] ); ?>, <?php echo esc_attr( $rgb[2] ); ?>, .7);
	}

	input[type="text"]:focus,
	input[type="password"]:focus,
	input[type="color"]:focus,
	input[type="date"]:focus,
	input[type="datetime"]:focus,
	input[type="datetime-local"]:focus,
	input[type="email"]:focus,
	input[type="month"]:focus,
	input[type="number"]:focus,
	input[type="search"]:focus,
	input[type="tel"]:focus,
	input[type="text"]:focus,
	input[type="time"]:focus,
	input[type="url"]:focus,
	input[type="week"]:focus,
	input[type="checkbox"]:focus,
	input[type="radio"]:focus,
	select:focus,
	textarea:focus {
	  border-color: <?php echo esc_attr( $accent_color ); ?>;
	  box-shadow: 0 0 0 1px <?php echo esc_attr( $accent_color ); ?>;
	}

	/* Core UI */
	.wp-core-ui .button {
	  border-color: #7e8993;
	  color: #32373c;
	}

	.wp-core-ui .button.hover,
	.wp-core-ui .button:hover,
	.wp-core-ui .button.focus,
	.wp-core-ui .button:focus {
	  border-color: #717c87;
	  color: #262a2e;
	}

	.wp-core-ui .button.focus,
	.wp-core-ui .button:focus {
	  border-color: #7e8993;
	  color: #262a2e;
	  box-shadow: 0 0 0 1px #32373c;
	}

	.wp-core-ui .button:active {
	  border-color: #7e8993;
	  color: #262a2e;
	  box-shadow: none;
	}

	.wp-core-ui .button.active,
	.wp-core-ui .button.active:focus,
	.wp-core-ui .button.active:hover {
	  border-color: <?php echo esc_attr( $accent_color ); ?>;
	  color: #262a2e;
	  box-shadow: inset 0 2px 5px -3px <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-core-ui .button.active:focus {
	  box-shadow: 0 0 0 1px #32373c;
	}

	.wp-core-ui .button-primary {
	  background: <?php echo esc_attr( $accent_color ); ?>;
	  border-color: <?php echo esc_attr( $accent_color ); ?>;
	  color: #fff;
	}

	.wp-core-ui .button-primary:hover, .wp-core-ui .button-primary:focus {
	  background: rgba(<?php echo esc_attr( $rgb[0] ); ?>,<?php echo esc_attr( $rgb[1] ); ?>, <?php echo esc_attr( $rgb[2] ); ?>, .85);
	  border-color: rgba(<?php echo esc_attr( $rgb[0] ); ?>,<?php echo esc_attr( $rgb[1] ); ?>, <?php echo esc_attr( $rgb[2] ); ?>, .85);
	  color: #fff;
	}

	.wp-core-ui .button-primary:focus {
	  box-shadow: 0 0 0 1px #fff, 0 0 0 3px <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-core-ui .button-primary:active {
	  background: <?php echo esc_attr( $accent_color ); ?>;
	  border-color: <?php echo esc_attr( $accent_color ); ?>;
	  color: #fff;
	}

	.wp-core-ui .button-primary.active, .wp-core-ui .button-primary.active:focus, .wp-core-ui .button-primary.active:hover {
	  background: rgba(<?php echo esc_attr( $rgb[0] ); ?>,<?php echo esc_attr( $rgb[1] ); ?>, <?php echo esc_attr( $rgb[2] ); ?>, .7);
	  color: #fff;
	  border-color: rgba(<?php echo esc_attr( $rgb[0] ); ?>,<?php echo esc_attr( $rgb[1] ); ?>, <?php echo esc_attr( $rgb[2] ); ?>, .7);
	  box-shadow: inset 0 2px 5px -3px #241906;
	}

	.wp-core-ui .button-primary[disabled], .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary.button-primary-disabled, .wp-core-ui .button-primary.disabled {
	  color: #d1cdc7 !important;
	  background: <?php echo esc_attr( $accent_color ); ?> !important;
	  border-color: <?php echo esc_attr( $accent_color ); ?> !important;
	  text-shadow: none !important;
	}

	.wp-core-ui .button-group > .button.active {
	  border-color: <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-core-ui .wp-ui-primary {
	  color: #fff;
	  background-color: <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-core-ui .wp-ui-text-primary {
	  color: <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-core-ui .wp-ui-highlight {
	  color: #fff;
	  background-color: <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-core-ui .wp-ui-text-highlight {
	  color: <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-core-ui .wp-ui-notification {
	  color: #fff;
	  background-color: <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-core-ui .wp-ui-text-notification {
	  color: <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-core-ui .wp-ui-text-icon {
	  color: <?php echo esc_attr( $accent_color ); ?>;
	}

	/* List tables */
	.wrap .add-new-h2:hover,
	.wrap .page-title-action:hover {
	  color: #fff;
	  background-color: <?php echo esc_attr( $accent_color ); ?>;
	}

	.view-switch a.current:before {
	  color: <?php echo esc_attr( $accent_color ); ?>;
	}

	.view-switch a:hover:before {
	  color: <?php echo esc_attr( $accent_color ); ?>;
	}

	/* Admin Menu */
	#adminmenuback,
	#adminmenuwrap,
	#adminmenu {
	  background: #2E3640;
	}

	#adminmenu a {
	  color: #fff;
	}

	#adminmenu div.wp-menu-image:before {
	  color: rgba(255,255,255,.7);
	}

	#adminmenu a:hover,
	#adminmenu li.menu-top:hover,
	#adminmenu li.opensub > a.menu-top,
	#adminmenu li > a.menu-top:focus {
	  color: #fff;
	  background-color: <?php echo esc_attr( $accent_color ); ?>;
	}

	#adminmenu li.menu-top:hover div.wp-menu-image:before,
	#adminmenu li.opensub > a.menu-top div.wp-menu-image:before {
	  color: #fff;
	}

	/* Active tabs use a bottom border color that matches the page background color. */
	.about-wrap .nav-tab-active,
	.nav-tab-active,
	.nav-tab-active:hover {
	  background-color: #f1f1f1;
	  border-bottom-color: #f1f1f1;
	}

	/* Admin Menu: submenu */
	#adminmenu .wp-submenu,
	#adminmenu .wp-has-current-submenu .wp-submenu,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu,
	.folded #adminmenu .wp-has-current-submenu .wp-submenu,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu {
	  background: #38404B;
	}

	<?php if ( is_rtl() ) { ?>

	#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
	  border-left-color: #38404B;
	}

	<?php } else { ?>

	#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
	  border-right-color: #38404B;
	}

	<?php } ?>

	#adminmenu .wp-submenu .wp-submenu-head {
	  color: rgba(255,255,255,.7);
	}

	#adminmenu .wp-submenu a,
	#adminmenu .wp-has-current-submenu .wp-submenu a,
	.folded #adminmenu .wp-has-current-submenu .wp-submenu a,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu a {
	  color: rgba(255,255,255,.7);
	}

	#adminmenu .wp-submenu a:focus, #adminmenu .wp-submenu a:hover,
	#adminmenu .wp-has-current-submenu .wp-submenu a:focus,
	#adminmenu .wp-has-current-submenu .wp-submenu a:hover,
	.folded #adminmenu .wp-has-current-submenu .wp-submenu a:focus,
	.folded #adminmenu .wp-has-current-submenu .wp-submenu a:hover,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:focus,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:hover,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu a:focus,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu a:hover {
	  color: #fff;
	}

	/* Admin Menu: current */
	#adminmenu .wp-submenu li.current a,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a {
	  color: #fff;
	}

	#adminmenu .wp-submenu li.current a:hover, #adminmenu .wp-submenu li.current a:focus,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:hover,
	#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:focus,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:hover,
	#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:focus {
	  color: #fff;
	}

	<?php if ( is_rtl() ) { ?>

	ul#adminmenu a.wp-has-current-submenu:after,
	ul#adminmenu > li.current > a.current:after {
	  border-left-color: #f1f1f1;
	}

	<?php } else { ?>

	ul#adminmenu a.wp-has-current-submenu:after,
	ul#adminmenu > li.current > a.current:after {
	  border-right-color: #f1f1f1;
	}

	<?php } ?>

	#adminmenu li.current a.menu-top,
	#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
	#adminmenu li.wp-has-current-submenu .wp-submenu .wp-submenu-head,
	.folded #adminmenu li.current.menu-top {
	  color: #fff;
	  background: <?php echo esc_attr( $accent_color ); ?>;
	}

	#adminmenu li.wp-has-current-submenu div.wp-menu-image:before,
	#adminmenu a.current:hover div.wp-menu-image:before,
	#adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before,
	#adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before,
	#adminmenu li:hover div.wp-menu-image:before,
	#adminmenu li a:focus div.wp-menu-image:before,
	#adminmenu li.opensub div.wp-menu-image:before,
	.ie8 #adminmenu li.opensub div.wp-menu-image:before {
	  color: #fff;
	}

	/* Admin Menu: bubble */
	#adminmenu .awaiting-mod,
	#adminmenu .update-plugins {
	  color: #fff;
	  background: <?php echo esc_attr( $accent_color ); ?>;
	}

	#adminmenu li.current a .awaiting-mod,
	#adminmenu li a.wp-has-current-submenu .update-plugins,
	#adminmenu li:hover a .awaiting-mod,
	#adminmenu li.menu-top:hover > a .update-plugins {
	  color: #fff;
	  background: #38404B;
	}

	/* Admin Menu: collapse button */
	#collapse-button:hover,
	#collapse-button:focus {
	  color: #fff;
	}

	/* Admin Bar */
	#wpadminbar {
	  color: #fff;
	  background: #232931;
	}

	#wpadminbar .ab-item,
	#wpadminbar a.ab-item,
	#wpadminbar > #wp-toolbar span.ab-label,
	#wpadminbar > #wp-toolbar span.noticon {
	  color: #fff;
	}

	#wpadminbar .ab-icon,
	#wpadminbar .ab-icon:before,
	#wpadminbar .ab-item:before,
	#wpadminbar .ab-item:after {
	  color: rgba(255,255,255,.7);
	}

	#wpadminbar:not(.mobile) .ab-top-menu > li:hover > .ab-item,
	#wpadminbar:not(.mobile) .ab-top-menu > li > .ab-item:focus,
	#wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus,
	#wpadminbar.nojs .ab-top-menu > li.menupop:hover > .ab-item,
	#wpadminbar .ab-top-menu > li.menupop.hover > .ab-item {
	  color: #fff;
	  background: #38404B;
	}

	#wpadminbar:not(.mobile) > #wp-toolbar li:hover span.ab-label,
	#wpadminbar:not(.mobile) > #wp-toolbar li.hover span.ab-label,
	#wpadminbar:not(.mobile) > #wp-toolbar a:focus span.ab-label {
	  color: #fff;
	}

	#wpadminbar:not(.mobile) li:hover .ab-icon:before,
	#wpadminbar:not(.mobile) li:hover .ab-item:before,
	#wpadminbar:not(.mobile) li:hover .ab-item:after,
	#wpadminbar:not(.mobile) li:hover #adminbarsearch:before {
	  color: #fff;
	}

	/* Admin Bar: submenu */
	#wpadminbar .menupop .ab-sub-wrapper {
	  background: #38404B;
	}

	#wpadminbar .quicklinks .menupop ul.ab-sub-secondary,
	#wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu {
	  background: #38404B;
	}

	#wpadminbar .ab-submenu .ab-item,
	#wpadminbar .quicklinks .menupop ul li a,
	#wpadminbar .quicklinks .menupop.hover ul li a,
	#wpadminbar.nojs .quicklinks .menupop:hover ul li a {
	  color: rgba(255,255,255,.7);
	}

	#wpadminbar .quicklinks li .blavatar,
	#wpadminbar .menupop .menupop > .ab-item:before {
	  color: rgba(255,255,255,.7);
	}

	#wpadminbar .quicklinks .menupop ul li a:hover,
	#wpadminbar .quicklinks .menupop ul li a:focus,
	#wpadminbar .quicklinks .menupop ul li a:hover strong,
	#wpadminbar .quicklinks .menupop ul li a:focus strong,
	#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover > a,
	#wpadminbar .quicklinks .menupop.hover ul li a:hover,
	#wpadminbar .quicklinks .menupop.hover ul li a:focus,
	#wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover,
	#wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus,
	#wpadminbar li:hover .ab-icon:before,
	#wpadminbar li:hover .ab-item:before,
	#wpadminbar li a:focus .ab-icon:before,
	#wpadminbar li .ab-item:focus:before,
	#wpadminbar li .ab-item:focus .ab-icon:before,
	#wpadminbar li.hover .ab-icon:before,
	#wpadminbar li.hover .ab-item:before,
	#wpadminbar li:hover #adminbarsearch:before,
	#wpadminbar li #adminbarsearch.adminbar-focused:before {
	  color: #fff;
	}

	#wpadminbar .quicklinks li a:hover .blavatar,
	#wpadminbar .quicklinks li a:focus .blavatar,
	#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover > a .blavatar,
	#wpadminbar .menupop .menupop > .ab-item:hover:before,
	#wpadminbar.mobile .quicklinks .ab-icon:before,
	#wpadminbar.mobile .quicklinks .ab-item:before {
	  color: #fff;
	}

	#wpadminbar.mobile .quicklinks .hover .ab-icon:before,
	#wpadminbar.mobile .quicklinks .hover .ab-item:before {
	  color: <?php echo esc_attr( $accent_color ); ?>;
	}

	/* Admin Bar: search */
	#wpadminbar #adminbarsearch:before {
	  color: <?php echo esc_attr( $accent_color ); ?>;
	}

	#wpadminbar > #wp-toolbar > #wp-admin-bar-top-secondary > #wp-admin-bar-search #adminbarsearch input.adminbar-input:focus {
	  color: #fff;
	  background: <?php echo esc_attr( $accent_color ); ?>;
	}

	/* Admin Bar: recovery mode */
	#wpadminbar #wp-admin-bar-recovery-mode {
	  color: #fff;
	  background-color: <?php echo esc_attr( $accent_color ); ?>;
	}

	#wpadminbar #wp-admin-bar-recovery-mode .ab-item,
	#wpadminbar #wp-admin-bar-recovery-mode a.ab-item {
	  color: #fff;
	}

	#wpadminbar .ab-top-menu > #wp-admin-bar-recovery-mode.hover > .ab-item,
	#wpadminbar.nojq .quicklinks .ab-top-menu > #wp-admin-bar-recovery-mode > .ab-item:focus,
	#wpadminbar:not(.mobile) .ab-top-menu > #wp-admin-bar-recovery-mode:hover > .ab-item,
	#wpadminbar:not(.mobile) .ab-top-menu > #wp-admin-bar-recovery-mode > .ab-item:focus {
	  color: #fff;
	  background-color: #cb9841;
	}

	/* Admin Bar: my account */
	#wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar > a img {
	  border-radius: 100%;
	  border: none;
	  height: 26px;
	}

	#wpadminbar #wp-admin-bar-user-info .display-name {
	  color: #fff;
	}

	#wpadminbar #wp-admin-bar-user-info a:hover .display-name {
	  color: #fff;
	}

	#wpadminbar #wp-admin-bar-user-info .username {
	  color: rgba(255,255,255,.7);
	}

	/* Pointers */
	.wp-pointer .wp-pointer-content h3 {
	  background-color: <?php echo esc_attr( $accent_color ); ?>;
	  border-color: <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-pointer .wp-pointer-content h3:before {
	  color: <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-pointer.wp-pointer-top .wp-pointer-arrow,
	.wp-pointer.wp-pointer-top .wp-pointer-arrow-inner,
	.wp-pointer.wp-pointer-undefined .wp-pointer-arrow,
	.wp-pointer.wp-pointer-undefined .wp-pointer-arrow-inner {
	  border-bottom-color: <?php echo esc_attr( $accent_color ); ?>;
	}

	/* Media */
	.media-item .bar,
	.media-progress-bar div {
	  background-color: <?php echo esc_attr( $accent_color ); ?>;
	}

	.details.attachment {
	  box-shadow: inset 0 0 0 3px #fff, inset 0 0 0 7px <?php echo esc_attr( $accent_color ); ?>;
	}

	.attachment.details .check {
	  background-color: <?php echo esc_attr( $accent_color ); ?>;
	  box-shadow: 0 0 0 1px #fff, 0 0 0 2px <?php echo esc_attr( $accent_color ); ?>;
	}

	.media-selection .attachment.selection.details .thumbnail {
	  box-shadow: 0 0 0 1px #fff, 0 0 0 3px <?php echo esc_attr( $accent_color ); ?>;
	}

	/* Themes */
	.theme-browser .theme.active .theme-name,
	.theme-browser .theme.add-new-theme a:hover:after,
	.theme-browser .theme.add-new-theme a:focus:after {
	  background: <?php echo esc_attr( $accent_color ); ?>;
	}

	.theme-browser .theme.add-new-theme a:hover span:after,
	.theme-browser .theme.add-new-theme a:focus span:after {
	  color: <?php echo esc_attr( $accent_color ); ?>;
	}

	.theme-section.current,
	.theme-filter.current {
	  border-bottom-color: <?php echo esc_attr( $accent_color ); ?>;
	}

	body.more-filters-opened .more-filters {
	  color: #fff;
	  background-color: <?php echo esc_attr( $accent_color ); ?>;
	}

	body.more-filters-opened .more-filters:before {
	  color: #fff;
	}

	body.more-filters-opened .more-filters:hover,
	body.more-filters-opened .more-filters:focus {
	  background-color: <?php echo esc_attr( $accent_color ); ?>;
	  color: #fff;
	}

	body.more-filters-opened .more-filters:hover:before,
	body.more-filters-opened .more-filters:focus:before {
	  color: #fff;
	}

	/* Widgets */
	.widgets-chooser li.widgets-chooser-selected {
	  background-color: <?php echo esc_attr( $accent_color ); ?>;
	  color: #fff;
	}

	.widgets-chooser li.widgets-chooser-selected:before,
	.widgets-chooser li.widgets-chooser-selected:focus:before {
	  color: #fff;
	}

	/* Responsive Component */
	div#wp-responsive-toggle a:before {
	  color: #fff;
	}

	.wp-responsive-open div#wp-responsive-toggle a {
	  border-color: transparent;
	  background: <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle a {
	  background: <?php echo esc_attr( $accent_color ); ?>;
	}

	.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before {
	  color: <?php echo esc_attr( $accent_color ); ?>;
	}

	/* TinyMCE */
	.mce-container.mce-menu .mce-menu-item:hover,
	.mce-container.mce-menu .mce-menu-item.mce-selected,
	.mce-container.mce-menu .mce-menu-item:focus,
	.mce-container.mce-menu .mce-menu-item-normal.mce-active,
	.mce-container.mce-menu .mce-menu-item-preview.mce-active {
	  background: <?php echo esc_attr( $accent_color ); ?>;
	}

	<?php if ( is_rtl() ) { ?>

	/* Custom Admin Styles */

	@media screen and (min-width:961px) {

		/* Block Editor */

		body:not(.folded) .interface-interface-skeleton {
			right: 220px;
		}

		.edit-post-sidebar {
			top: 106px;
		}

		body:not(.folded) .edit-post-header {
			right: 220px;
		}

		body:not(.folded) .edit-post-layout .components-editor-notices__snackbar {
			right: 220px;
		}

		/* Layout */

		#adminmenu #collapse-menu {
			border-top: 2px solid #232931;
			margin-top: 10px;
			padding-top: 10px;
		}
		.wp-admin:not(.folded) #wpcontent, #wpfooter {
			margin-right: 220px;
		}

		.wp-admin:not(.folded) #adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap {
			width: 220px;
			margin-top: 0;
		}

		.wp-admin:not(.folded) #adminmenuwrap {
			padding-top: 50px;
		}

		.wp-admin:not(.folded) #wpadminbar {
			margin-right: 220px;
			width: calc(100% - 220px);
		}

		#adminmenu .wp-submenu {
			right: 220px
		}

		/* Menu items */
		.wp-admin:not(.folded) #adminmenu div.wp-menu-image {
			position: absolute;
			left: 20px;
			opacity: .7;
		}

		.wp-admin:not(.folded) #adminmenu li.menu-top:hover div.wp-menu-image,
		.wp-admin:not(.folded) #adminmenu li.opensub > a.menu-top div.wp-menu-image {
			opacity: 1;
		}

		.wp-admin:not(.folded) #adminmenu li.wp-has-current-submenu div.wp-menu-image {
			opacity: 1;
		}

		.wp-admin:not(.folded) #adminmenu div.wp-menu-name,
		.wp-admin:not(.folded) div.wp-menu-image:before {
			padding: 10px 10px 10px 50px;
		}

		#adminmenu .wp-has-current-submenu ul > li > a, .folded #adminmenu li.menu-top .wp-submenu > li > a {
			padding: 7px 15px;
		}

		#adminmenu .wp-submenu a {
			padding: 7px 0;
		}

		/* Custom Logo */
		#adminmenu #udb-admin-logo-wrapper a {
			position: absolute;
			height: 60px;
			top: 0;
			left: 0;
			width: 160px;
			margin-top: -32px;
			background: #38404B;
			padding: 10px 30px;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		#adminmenu #udb-admin-logo-wrapper .udb-admin-logo {
			max-width: 100%;
			max-height: 100%;
		}

		#wp-admin-bar-wp-logo {
			display: none;
		}

		.wp-admin.folded #adminmenu #udb-admin-logo-wrapper {
			display: none;
		}

	}

	<?php } else { ?>

	/* Custom Admin Styles */

	@media screen and (min-width:961px) {

		/* Block Editor */

		body:not(.folded) .interface-interface-skeleton {
			left: 220px;
		}

		.edit-post-sidebar {
			top: 106px;
		}

		body:not(.folded) .edit-post-header {
			left: 220px;
		}

		body:not(.folded) .edit-post-layout .components-editor-notices__snackbar {
			left: 220px;
		}

		/* Layout */

		#adminmenu #collapse-menu {
			border-top: 2px solid #232931;
			margin-top: 10px;
			padding-top: 10px;
		}
		.wp-admin:not(.folded) #wpcontent, #wpfooter {
			margin-left: 220px;
		}

		.wp-admin:not(.folded) #adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap {
			width: 220px;
			margin-top: 0;
		}

		.wp-admin:not(.folded) #adminmenuwrap {
			padding-top: 50px;
		}

		.wp-admin:not(.folded) #wpadminbar {
			margin-left: 220px;
			width: calc(100% - 220px);
		}

		#adminmenu .wp-submenu {
			left: 220px
		}

		/* Menu items */
		.wp-admin:not(.folded) #adminmenu div.wp-menu-image {
			position: absolute;
			right: 20px;
			opacity: .7;
		}

		.wp-admin:not(.folded) #adminmenu li.menu-top:hover div.wp-menu-image,
		.wp-admin:not(.folded) #adminmenu li.opensub > a.menu-top div.wp-menu-image {
			opacity: 1;
		}

		.wp-admin:not(.folded) #adminmenu li.wp-has-current-submenu div.wp-menu-image {
			opacity: 1;
		}

		.wp-admin:not(.folded) #adminmenu div.wp-menu-name,
		.wp-admin:not(.folded) div.wp-menu-image:before {
			padding: 10px 50px 10px 10px;
		}

		#adminmenu .wp-has-current-submenu ul > li > a, .folded #adminmenu li.menu-top .wp-submenu > li > a {
			padding: 7px 15px;
		}

		#adminmenu .wp-submenu a {
			padding: 7px 0;
		}

		/* Custom Logo */
		#adminmenu #udb-admin-logo-wrapper a {
			position: absolute;
			height: 60px;
			top: 0;
			left: 0;
			width: 160px;
			margin-top: -32px;
			background: #38404B;
			padding: 10px 30px;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		#adminmenu #udb-admin-logo-wrapper .udb-admin-logo {
			max-width: 100%;
			max-height: 100%;
		}

		#wp-admin-bar-wp-logo {
			display: none;
		}

		.wp-admin.folded #adminmenu #udb-admin-logo-wrapper {
			display: none;
		}

	}

	<?php } ?>

	@media screen and (max-width:960px) {

		#adminmenu #udb-admin-logo-wrapper {
			display: none;
		}

	}

	<?php

}
