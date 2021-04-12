<?php
/**
* Plugin Name: Nexi XPay
 * Plugin URI:
 * Description: New Nexi Payments gateway. Official Nexi XPay plugin.
 * Version: 5.1.0
 * Author: Nexi SpA
 * Author URI: https://www.nexi.it
 * Text Domain: woocommerce-gateway-nexi-xpay
 * Domain Path: /lang
 * Copyright: © 2017-2018, Nexi SpA
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

define('WC_GATEWAY_XPAY_VERSION', '5.1.0');

/**
 * Required functions
 */
if (!defined('ABSPATH')) {
    exit;
}
if (!function_exists('woothemes_queue_update')) {
    require_once('lib/woo-includes/woo-functions.php');
}

register_activation_hook(__FILE__, array( 'WC_Nexi_XPay', 'install' ));

class WC_Nexi_XPay
{

    /**
     * Plugin's version.
     *
     * @since 1.6.0
     *
     * @var string
     */
    public $version;

    /**
     * Plugin's absolute path.
     *
     * @var string
     */
    public $path;

    /**
     * Plugin's URL.
     *
     * @since 1.6.0
     *
     * @var string
     */
    public $plugin_url;

    public static function install()
    {
        update_option('nexi_unique', uniqid());
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->version = WC_GATEWAY_XPAY_VERSION;
        $this->path = untrailingslashit(plugin_dir_path(__FILE__));
        $this->plugin_url = untrailingslashit(plugins_url('/', __FILE__));
        add_action('init', array($this, 'init'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'my_plugin_action_links'));
    }

    /**
     * Init
     */
    public function init()
    {
        if (!$this->wc_nexi_is_plugin_woocommerce_active()) {
            add_action('admin_notices', function () {
                $msg = __('Nexi XPay is inactive because WooCommerce is not installed.', 'woocommerce-gateway-nexi-xpay');
                echo '<div class="error"><p>' . $msg . '</p></div>';
            });
            return;
        }



        $this->load_plugin_textdomain();
        $this->init_gateway();
        $this->init_admin_order_details();
    }

    public function my_plugin_action_links($links)
    {
        //return $links;
        $plugin_links = array('<a href="' . esc_url(admin_url('admin.php?page=wc-settings&tab=checkout&section=xpay')) . '">' . __('Settings') . '</a>',
      );
        return array_merge($plugin_links, $links);
    }

    /**
     * Init gateway
     */
    public function init_gateway()
    {
        if (!class_exists('WC_Payment_Gateway')) {
            return;
        }

        //include_once($this->path . '/includes/constant-wc-gateway-nexi-xpay.php');
        include_once($this->path . '/includes/class-wc-gateway-nexi-xpay.php');
        include_once($this->path . '/includes/class-wc-gateway-nexi-xpay-easy.php');
        include_once($this->path . '/includes/class-wc-gateway-nexi-xpay-pro.php');
        include_once($this->path . '/includes/class-wc-gateway-nexi-xpay-api.php');
        include_once($this->path . '/includes/class-wc-gateway-nexi-xpay-order-payment-info.php');
        include_once($this->path . '/includes/class-wc-gateway-nexi-xpay-token.php');
        include_once($this->path . '/includes/class-wc-gateway-nexi-xpay-apm.php');
        $this->xpay_style_scripts();
        // SE esiste Subscriptions 2.0, carico il modulo XPay con le ricorrenze, altrimenti modulo normale
        if (class_exists('WC_Subscriptions_Order') && function_exists('wcs_create_renewal_order')) {
            include_once($this->path . '/includes/class-wc-gateway-nexi-xpay-subscription.php');
            $this->gateway = new WC_Gateway_XPay_Subscription();
        } else {
            $this->gateway = new WC_Gateway_XPay_Pro();
        }

        add_filter('woocommerce_payment_gateways', array($this, 'add_gateway'));
    }

    /**
     * Load translations.
     *
     * @since 1.6.0
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain('woocommerce-gateway-nexi-xpay', false, dirname(plugin_basename(__FILE__)) . '/lang');
    }

    /**
     * Add XPay to WC.
     *
     * @param array $methods List of payment methods.
     *
     * @return array List of payment methods.
     */
    public function add_gateway($methods)
    {
        //edit this array for allowed APM
        $allowedMethods = array('PAYPAL','SOFORT', 'AMAZONPAY', 'GOOGLEPAY', 'APPLEPAY', 'ALIPAY', 'WECHATPAY',
            'GIROPAY', 'IDEAL', 'BCMC', 'EPS', 'P24', 'BANCOMATPAY', 'SCT', 'MASTERPASS');

        $methods[] = $this->gateway;
        $avaiable_methods = json_decode(WC_Admin_Settings::get_option('xpay_available_methods'), true);
        if (is_array($avaiable_methods)) {
            foreach ($avaiable_methods as $am) {
                if (in_array($am['selectedcard'], $allowedMethods)) {
                if ($am['type'] == 'APM' && $am['selectedcard'] != '') {
                    if (class_exists("WC_Subscriptions_Cart") && WC_Subscriptions_Cart::cart_contains_subscription()) {
                        if ($am['selectedcard'] == 'PAYPAL') {
                            $methods[] = new WC_Gateway_XPay_APM($this->version, $am['code'], $am['description'], $am['selectedcard'], $am['image'], $am['recurring']);
                        }
                    } else {
                        $methods[] = new WC_Gateway_XPay_APM($this->version, $am['code'], $am['description'], $am['selectedcard'], $am['image'], $am['recurring']);
                    }
                }
            }
            }
        }
        return $methods;
    }

    /**
     *
     */
    public function init_admin_order_details()
    {
        include_once($this->path . '/includes/class-wc-gateway-nexi-xpay-admin-order-details.php');

        $this->admin_order_details = new WC_Gateway_XPay_Admin_Order_Details();
        $this->admin_order_details->set_meta_box_xpay();
    }

    /**
     *
     */
    public function xpay_style_scripts()
    {
        wp_enqueue_style('xpay-style', plugins_url('assets/css/xpay.css', __FILE__));
        wp_enqueue_script('xpay-script', plugins_url('assets/js/xpay.js', __FILE__));
    }

    public static function wc_nexi_is_plugin_woocommerce_active()
    {
        $active_plugins = (array) get_option('active_plugins', array());
        $active_plugins = array_merge($active_plugins, (array) get_site_option('active_sitewide_plugins', array()));
		return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins) || preg_grep("/woocommerce.php/", $active_plugins) || is_plugin_active_for_network('woocommerce/woocommerce.php');
    }
}
 

/**
 * Return instance of WC_Gateway_XPay.
 *
 * @since 1.6.0
 *
 * @return WC_Gateway_XPay
 */
function wc_nxp()
{
    static $plugin;

    if (!isset($plugin)) {
        $plugin = new WC_Nexi_XPay();
    }

    return $plugin;
}

/**
 * Get order property with compatibility for WC lt 3.0.
 *
 * @since 1.7.0
 *
 * @param WC_Order $order Order object.
 * @param string   $key   Order property.
 *
 * @return mixed Value of order property.
 */
function wc_nxp_get_order_prop($order, $key)
{
    switch ($key) {
        case 'order_currency':
            return is_callable(array($order, 'get_currency')) ? $order->get_currency() : $order->get_order_currency();
            break;
        default:
            $getter = array($order, 'get_' . $key);
            return is_callable($getter) ? call_user_func($getter) : $order->{ $key };
    }
}

// Provides backward compatibility.
$GLOBALS['wc_gateway_xpay'] = wc_nxp();
