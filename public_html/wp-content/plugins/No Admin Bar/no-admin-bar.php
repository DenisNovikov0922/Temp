<?php
/**
 * Plugin Name: DSGN - NO ADMIN BAR
 * Plugin URI:  https://www.dsgn.cc
 * Description: Togli Admin Bar da Frontend
 * Version:     1.0.0
 * Author:      DSGN
 * Author URI:  https://www.dsgn.cc
 * Copyright:   2021 DSGN
 *
 * Text Domain: no-admin-bar
 * Domain Path: /languages/
 */

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}