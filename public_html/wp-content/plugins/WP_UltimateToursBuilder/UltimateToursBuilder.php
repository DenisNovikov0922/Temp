<?php

/*
 * Plugin Name: WP Ultimate Tours Builder
 * Version: 1.049
 * Plugin URI: https://codecanyon.net/user/loopus/portfolio
 * Description: This plugin allows you to easily create beautiful & powerful tours on your website
 * Author: Biscay Charly (loopus)
 * Author URI: https://www.loopus-plugins.com/
 * Requires at least: 3.8
 * Tested up to: 5.3.2
 *
 * @package WordPress
 * @author Biscay Charly (loopus)
 * @since 1.0.0
 */

if (!defined('ABSPATH'))
    exit;

register_activation_hook(__FILE__, 'wutb_install');
//register_deactivation_hook(__FILE__, 'wutb_uninstall');
register_uninstall_hook(__FILE__, 'wutb_uninstall');

global $jal_db_version;
$jal_db_version = "1.1";

require_once('includes/wutb-core.php');
require_once('includes/wutb-admin.php');

function UltimateToursBuilder() {
    $version = 1.049;
    wutb_checkDBUpdates($version);
    $instance = wutb_Core::instance(__FILE__, $version);
    if (is_null($instance->menu)) {
        $instance->menu = wutb_admin::instance($instance);
    }
    return $instance;
}

/**
 * Installation. Runs on activation.
 * @access  public
 * @since   1.0.0
 * @return  void
 */
function wutb_install() {
    global $wpdb;
    global $jal_db_version;
    require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
    add_option("jal_db_version", $jal_db_version);

    $db_table_name = $wpdb->prefix . "wutb_settings";
    if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        $sql = "CREATE TABLE $db_table_name (
    		id mediumint(9) NOT NULL AUTO_INCREMENT,
    		purchaseCode VARCHAR(250) NOT NULL,   
		UNIQUE KEY id (id)
		) $charset_collate;";

        dbDelta($sql);
        $rows_affected = $wpdb->insert($db_table_name, array('purchaseCode' => ''));
    }
    $db_table_name = $wpdb->prefix . "wutb_tours";
    if ($wpdb->get_var("SHOW TABLES LIKE '$db_table_name'") != $db_table_name) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        $sql = "CREATE TABLE $db_table_name (
    		id mediumint(9) NOT NULL AUTO_INCREMENT,
    		title VARCHAR(250) NOT NULL DEFAULT 'My tour',   
                tourData LONGTEXT NOT NULL,
                UNIQUE KEY id (id)
		) $charset_collate;";

        dbDelta($sql);
    }



    global $isInstalled;
    $isInstalled = true;
}

// End install()

/**
 * Update database
 * @access  public
 * @since   1.0
 * @return  void
 */
function wutb_checkDBUpdates($version) {
    global $wpdb;
    $installed_ver = get_option("wutb_version");
    require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
    
    if (!$installed_ver || $installed_ver < 1.032) {
        $table_forms = $wpdb->prefix . "wutb_tours";
        $tours = $wpdb->get_results("SELECT tourData,id FROM {$wpdb->prefix}wutb_tours ORDER BY id DESC");

        foreach ($tours as $tour) {    
                $tourDataObj = json_decode($tour->tourData);
                $tourDataObj->settings->activated = true;
                
            $wpdb->update($table_forms, array('tourData' => json_encode($tourDataObj)), array('id' => $tour->id));
        }
    }

    update_option("wutb_version", $version);
}

/**
 * Uninstallation.
 * @access  public
 * @since   1.0.0
 * @return  void
 */
function wutb_uninstall() {
    global $wpdb;
    global $jal_db_version;
    $table_name = $wpdb->prefix . "wutb_settings";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $table_name = $wpdb->prefix . "wutb_tours";
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}
// End uninstall()

UltimateToursBuilder();
