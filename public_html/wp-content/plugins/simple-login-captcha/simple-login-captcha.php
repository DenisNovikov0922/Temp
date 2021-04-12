<?php

/*
Plugin Name: Simple Login Captcha
Description: Adds a simple 3-digit number captcha on the login form.
Author: Nikolay Nikolov
Author URI: https://nikolaydev.com/
Text Domain: simple-login-captcha
Domain Path: /languages
Version: 1.3.2
*/

define( "SIMPLE_LOGIN_CAPTCHA_VERSION", "1.3.2" );

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Creates the database table on plugin activation
register_activation_hook( plugin_dir_path( __FILE__ ) . 'simple-login-captcha.php', 'slc_create_captcha_db_table' );

// Adds the captcha to the login form and deletes old captchas from the database
add_action( 'login_form', 'slc_login_form_captcha', 9999999999 );

// Adds the captcha to the WooCommerce login form and deletes old captchas from the database
add_action( 'woocommerce_login_form', 'slc_woo_login_form_captcha', 9999999999 );

// Validates the login captcha before wordpress checks the user and pass
add_filter( 'authenticate', 'slc_validate_login_form', 10, 3 );

// Loads the style for the captcha on the login page
add_action( 'login_enqueue_scripts', 'slc_register_captcha_style' );

// Loads the style on the site if WooCommerce is used, so it works on the WooCommerce login page
add_action( 'wp_enqueue_scripts', 'slc_register_woo_captcha_style' );

// Adds the statistics action link on the plugins page
add_filter( 'plugin_action_links', 'slc_plugin_action_link_statistics', 10, 2 );

// Loads the plugin's translated strings from the languages folder inside the plugin folder
add_action( 'init', 'slc_load_the_languages' );

// Loads the plugin's translated strings from the languages folder inside the plugin folder
function slc_load_the_languages() {
    load_plugin_textdomain( 'simple-login-captcha', FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
}

/**
 * Adds the statistics action link on the plugins page
 * @param array $actions
 * @param string $plugin_file
 * @return array
 */
function slc_plugin_action_link_statistics( $actions, $plugin_file ) {
    if ( strpos( $plugin_file, 'simple-login-captcha.php' ) !== false ) {
        $href_value = 'javascript:alert(\''
            . esc_js( esc_attr__( 'Blocked this month:', 'simple-login-captcha' ) ) . ' '
            . intval( get_option( 'slc-blocked-' . date( 'm' ) . '-' . date( 'Y' ) ) ) . '\n'
            . esc_js( esc_attr__( 'Passed this month:', 'simple-login-captcha' ) ) . ' '
            . intval( get_option( 'slc-passed-' . date( 'm' ) . '-' . date( 'Y' ) ) ) . '\')';
        $actions['slc-captcha-statistics'] = '<a href="' . $href_value . '">' . esc_html__( 'Statistics', 'simple-login-captcha' ) . '</a>';
    }
    return $actions;
}

// Registers the captcha style file and enqueues it
function slc_register_captcha_style() {
    wp_register_style( 'slc-login-captcha-style', plugin_dir_url( __FILE__ ) . 'styles/login.css', false, SIMPLE_LOGIN_CAPTCHA_VERSION );
    wp_enqueue_style( 'slc-login-captcha-style' );
}

// Loads the style on the site if WooCommerce is used, so it works on the WooCommerce login page
function slc_register_woo_captcha_style() {
    if ( class_exists( 'woocommerce' ) ) {
        wp_register_style( 'slc-login-captcha-style', plugin_dir_url( __FILE__ ) . 'styles/login.css', false, SIMPLE_LOGIN_CAPTCHA_VERSION );
        wp_enqueue_style( 'slc-login-captcha-style' );
    }
}

// Outputs the HTML code needed for displaying the correct security code and the request ID.
function slc_output_captcha_code() {
    $request_id = md5( mt_rand( 1, 999999999 ) . 'mki3uflxnq29r4dzplf9u6671qmn43cvjg85jdxbv130oppr4zxs3edjfv85dnswk5tkg96' );
    $answer_pool = mt_rand( 100000000, 999999999 );
    $start = mt_rand( 0, 6 );
    $answer = substr( (string) $answer_pool, $start, 3 );

    // Make sure that on a multisite the new global database table from version 1.2.0 is created
    if ( is_multisite() && get_site_option( 'slc-new-database-table-created' ) !== 'yes' ) {
        slc_create_captcha_db_table();
    }

    global $wpdb;
    $table_name = slc_get_table_name();
    $wpdb->insert( $table_name,	array( 'request_id' => $request_id, 'answer' => $answer, 'unix_time_added' => time() ), array( '%s', '%s', '%d' ) );
    $wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $table_name . ' WHERE unix_time_added < %d', ( time() - 600 ) ) );

    ?>
    <span class="slc-code-span">
        <script type="text/javascript">
            var answerPool = "<?php echo intval( $answer_pool ); ?>";
            for ( var i = 0; i < answerPool.length; i++ ) {
                if ( i >= <?php echo intval( $start ); ?> && i < <?php echo intval( $start + 3 ); ?> ) {
                    document.write( answerPool.charAt( i ) );
                }
            }
        </script>
        <noscript><?php esc_html_e( 'Your browser does not support JavaScript!', 'simple-login-captcha' ); ?></noscript>
    </span>
    <input name="slc-captcha-request" type="hidden" value="<?php echo esc_attr( $request_id ); ?>" />
    <?php
}

// Adds the captcha to the login form and deletes old captchas from the database
function slc_login_form_captcha() {
    ?>
    <p class="slc-code-paragraph">
        <span class="slc-label-span"><?php esc_html_e( 'Security Code:', 'simple-login-captcha' ) ?></span>
        <?php slc_output_captcha_code(); ?>
    </p>
    <p>
        <label for="slc-captcha-answer"><?php esc_html_e( 'Enter the security code:', 'simple-login-captcha' ) ?></label>
        <input id="slc-captcha-answer" autocomplete="off" name="slc-captcha-answer" size="20" type="text" />
    </p>
    <?php
}

// Adds the captcha to the WooCommerce login form and deletes old captchas from the database
function slc_woo_login_form_captcha() {
    ?>
    <p class="slc-code-paragraph woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label><?php esc_html_e( 'Security Code:', 'simple-login-captcha' ); ?></label>
        <?php slc_output_captcha_code(); ?>
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="slc-captcha-answer">
            <?php esc_html_e( 'Enter the security code:', 'simple-login-captcha' ); ?>
            <span class="required">*</span>
        </label>
        <input id="slc-captcha-answer" class="woocommerce-Input woocommerce-Input--text input-text" autocomplete="off"
            name="slc-captcha-answer" size="20" type="text" />
    </p>
<?php
}

/**
 * Validates the login captcha before wordpress checks the user and pass
 * @param object $user
 * @param string $username
 * @param string $password
 * @return object
 */
function slc_validate_login_form( $user, $username, $password ) {

    // If the username or password are not sent we do nothing (and return $user), this way we avoid errors to be shown before the user clicks the button to log in
    if ( ! isset( $username ) || '' == $username || ! isset( $password ) || '' == $password ) {
        return $user;
    }

    // The request data is empty or missing
    if ( ! isset( $_POST['slc-captcha-request'] ) || '' == $_POST['slc-captcha-request'] ) {
        slc_remove_authenticate_and_increase_blocked();
        $user = new WP_Error( 'denied', '<strong>' . esc_html__( 'ERROR', 'simple-login-captcha' ) . '</strong>: '
            . esc_html__( 'Missing request ID.', 'simple-login-captcha' ) );
        return $user;
    }

    // The answer data is empty or missing
    if ( ! isset( $_POST['slc-captcha-answer'] ) || '' == $_POST['slc-captcha-answer'] ) {
        slc_remove_authenticate_and_increase_blocked();
        $user = new WP_Error( 'denied', '<strong>' . esc_html__( 'ERROR', 'simple-login-captcha' ) . '</strong>: '
            . esc_html__( 'Please enter the security code.', 'simple-login-captcha' ) );
        return $user;
    }

    // Make sure that on a multisite the new global database table from version 1.2.0 is created
    if ( is_multisite() && get_site_option( 'slc-new-database-table-created' ) !== 'yes' ) {
        slc_create_captcha_db_table();
    }

    $request_id = sanitize_html_class( $_POST['slc-captcha-request'] );
    $answer = intval( $_POST['slc-captcha-answer'] );
    global $wpdb;
    $table_name = slc_get_table_name();
    $results = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $table_name . ' WHERE request_id = %s', $request_id ), ARRAY_A );

    // There is no such request ID. It is invalid or has expired.
    if ( null === $results ) {
        slc_remove_authenticate_and_increase_blocked();
        $user = new WP_Error( 'denied', '<strong>' . esc_html__( 'ERROR', 'simple-login-captcha' ) . '</strong>: '
            . esc_html__( 'Invalid or expired request.', 'simple-login-captcha' ) );
        return $user;
    }

    // The answer is incorrect or does not match the request ID
    if ( intval( $results['answer'] ) !== intval( $answer ) ) {
        slc_remove_authenticate_and_increase_blocked();
        $user = new WP_Error( 'denied', '<strong>' . esc_html__( 'ERROR', 'simple-login-captcha' ) . '</strong>: '
            . esc_html__( 'This is not the correct security code.', 'simple-login-captcha' ) );
        $wpdb->delete( $table_name, array( 'request_id' => $request_id ), array( '%s' ) );
        return $user;
    }

    // At this point the answer is correct. We delete the database data for this request and answer so it cannot be used again.
    $wpdb->delete( $table_name, array( 'request_id' => $request_id, 'answer' => $answer ), array( '%s', '%s' ) );

    slc_increase_option_number( 'passed' );

    // Return $user to allow wordpress to check password and username
    return $user;
}

// Creates a database table to store the captcha information
function slc_create_captcha_db_table() {
    global $wpdb;
    $table_name = slc_get_table_name();
    $collate = '';
    if ( $wpdb->has_cap( 'collation' ) ) {
        $collate = $wpdb->get_charset_collate();
    }
    $sql = 'CREATE TABLE IF NOT EXISTS ' . $table_name . ' ( '
        . 'row_id bigint(20) NOT NULL AUTO_INCREMENT, '
        . 'request_id varchar(32) DEFAULT NULL, '
        . 'answer varchar(5) DEFAULT NULL, '
        . 'unix_time_added int(11) NOT NULL, '
        . 'PRIMARY KEY  (row_id) '
        . ') ' . $collate . ';';
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    if ( is_multisite() ) {
        update_site_option( 'slc-new-database-table-created', 'yes' );
    }
}

// We remove further authentication and increase the counter for blocked requests
function slc_remove_authenticate_and_increase_blocked() {
    remove_action( 'authenticate', 'wp_authenticate_username_password', 20 );
    remove_action( 'authenticate', 'wp_authenticate_email_password', 20 );
    slc_increase_option_number( 'blocked' );
}

// Increases the number in an option value (used to count passed and blocked requests for the current month)
function slc_increase_option_number( $name ) {
    $value = intval( get_option( 'slc-' . $name . '-' . date( 'm' ) . '-' . date( 'Y' ) ) ) + 1;
    update_option( 'slc-' . $name . '-' . date( 'm' ) . '-' . date( 'Y' ), $value );
}

/**
 * Returns the id of the main site of the multisite network (which is usually 1)
 * @return int
 */
function slc_get_main_site_id() {
    global $wp_version;
    if ( version_compare( $wp_version, '4.9', '>=' ) ) {
        return get_main_site_id();
    } else {
        global $current_site;
        return intval( $current_site->blog_id );
    }
}

/**
 * Returns the name of the database table we are using. It is different if it is a multisite.
 * @return string
 */
function slc_get_table_name() {
    global $wpdb;
    if ( is_multisite() ) {
        $main_blog_prefix = $wpdb->get_blog_prefix( slc_get_main_site_id() );
        return $main_blog_prefix . 'slc_simple_login_captcha';
    } else {
        return $wpdb->prefix . 'slc_simple_login_captcha';
    }
}
