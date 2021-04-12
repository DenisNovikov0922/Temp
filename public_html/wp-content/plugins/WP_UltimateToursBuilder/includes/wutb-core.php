<?php

if (!defined('ABSPATH'))
    exit;

class wutb_Core {

    /**
     * The single instance
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $_instance = null;

    /**
     * Settings class object
     * @var     object
     * @access  public
     * @since   1.0.0
     */
    public $settings = null;

    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_version;

    /**
     * The token.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_token;

    /**
     * The main plugin file.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $file;

    /**
     * The main plugin directory.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $dir;

    /**
     * The plugin assets directory.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_dir;

    /**
     * The plugin assets URL.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_url;

    /**
     * Suffix for Javascripts.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $templates_url;

    /**
     * Suffix for Javascripts.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $script_suffix;

    /**
     * For menu instance
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $menu;

    /**
     * For template
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $plugin_slug;

    /**
     * Constructor function.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function __construct($file = '', $version = '1.0.0') {
        $this->_version = $version;
        $this->_token = 'wutb';
        $this->plugin_slug = 'wutb';
        $this->currentForms = array();

        $this->file = $file;
        $this->dir = dirname($this->file);
        $this->assets_dir = trailingslashit($this->dir) . 'assets';
        $this->assets_url = esc_url(trailingslashit(plugins_url('/assets/', $this->file)));
        add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue_scripts'), 10, 1);
        add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue_styles'), 10, 1);
        add_action('plugins_loaded', array($this, 'init_localization'));
    }

    /*
     * Plugin init localization
     */

    public function init_localization() {
        $moFiles = scandir(trailingslashit($this->dir) . 'languages/');
        foreach ($moFiles as $moFile) {
            if (strlen($moFile) > 3 && substr($moFile, -3) == '.mo' && strpos($moFile, get_locale()) > -1) {
                load_textdomain('wutb', trailingslashit($this->dir) . 'languages/' . $moFile);
            }
        }
    }

    public function frontend_enqueue_scripts($hook = '') {
        global $post;
        global $wpdb;

        wp_register_script('webfontloader', esc_url($this->assets_url) . 'js/webfontloader.js', array(), $this->_version);
        wp_enqueue_script('webfontloader');
        wp_register_script('moment', esc_url($this->assets_url) . 'js/moment.min.js', array(), $this->_version);
        wp_enqueue_script('moment');
        wp_register_script($this->_token . '-frontend', esc_url($this->assets_url) . 'js/frontend.min.js', array('jquery', 'jquery-ui-core', 'jquery-ui-mouse', 'jquery-ui-position', 'jquery-ui-tooltip'), $this->_version);
        wp_enqueue_script($this->_token . '-frontend');

        $previewTour = 0;
        $previewStep = 0;

        $tours = array();

        $toursReq = $wpdb->get_results("SELECT * FROM  {$wpdb->prefix}wutb_tours ORDER BY id ASC");
        foreach ($toursReq as $tourReq) {
            $tourObj = json_decode($tourReq->tourData);
            
            if ($tourObj->settings->allowedRoles == '') {
                $tours[] = $tourReq;
            } else {
                $allowedRoles = explode(',', $tourObj->settings->allowedRoles);
                if (is_user_logged_in()) {
                    $user = new WP_User(get_current_user_id());
                    $chkOK = false;
                    if (!empty($user->roles) && is_array($user->roles)) {
                        foreach ($user->roles as $key => $role) {
                            if ($role == "shop_manager") {
                                $role = "shop-manager";
                            }
                            if (in_array(strtolower($role), $allowedRoles)) {
                                $chkOK = true;
                            }
                        }
                    }
                    if ($chkOK) {
                        $tours[] = $tourReq;
                    }
                }
            }
        }

        if (isset($_GET['tourPreview'])) {
            $previewTour = sanitize_text_field($_GET['tourPreview']);
            session_start();
            if (isset($_SESSION['wutb_previewData'])) {
                $tours = array(($_SESSION['wutb_previewData']));
            }
        }
        if (isset($_GET['stepPreview'])) {
            $previewStep = sanitize_text_field($_GET['stepPreview']);
        }

        $username = '';
        $firstName = '';
        $lastName = '';
        $email = '';
        $group = '';
        $roles = '';
        $profileUrl = '';

       
        if ( is_user_logged_in()) {
            $userWP = wp_get_current_user();
            $username = $userWP->display_name;
            $firstName = $userWP->user_firstname;
            $lastName = $userWP->user_lastname;
            $email = $userWP->user_email;
        }
        
         if ((is_plugin_active('buddypress/bp-loader.php')) && is_user_logged_in()) {
           $username = bp_core_get_username(bp_loggedin_user_id());
            $profileUrl = urlencode(bp_core_get_user_domain(bp_loggedin_user_id()));
            if (bp_is_active('groups') && bp_is_group()) {
                $group = bp_get_current_group_name();
            }
        } 

        if(is_user_logged_in()){
            $roles = $userWP->roles;            
        }


        global $post;
        $post_id = '';
        if ($post) {
            $post_id = $post->ID;
        }
        $isAdmin = 0;
        if (is_admin()) {
            $isAdmin = 1;
        }
        $js_data[] = array(
            'assetsUrl' => esc_url($this->assets_url),
            'websiteUrl' => esc_url(get_home_url()),
            'adminUrl' => esc_url(get_admin_url()),
            'websiteTitle' => get_bloginfo('name'),
            'siteUrl' => home_url(),
            'previewTour' => $previewTour,
            'previewStep' => $previewStep,
            'tours' => ($tours),
            'group' => $group,
            'username' => $username,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'profileUrl'=>$profileUrl,
            'email' => $email,
            'roles'=>$roles,
            'post_id' => $post_id,
            'isAdmin' => $isAdmin
        );
        wp_localize_script($this->_token . '-frontend', 'wutb_toursData', $js_data);
    }

    public function frontend_enqueue_styles($hook = '') {
        global $wp_styles;

        wp_register_style($this->_token . '-jqueryui', esc_url($this->assets_url) . 'css/jquery-ui-theme/jquery-ui.min.css', array(), $this->_version);
        wp_enqueue_style($this->_token . '-jqueryui');
        wp_register_style($this->_token . '-fontawesome', esc_url($this->assets_url) . 'css/font-awesome.min.css', array(), $this->_version);
        wp_enqueue_style($this->_token . '-fontawesome');
        wp_register_style($this->_token . '-animate', esc_url($this->assets_url) . 'css/animate.min.css', array(), $this->_version);
        wp_enqueue_style($this->_token . '-animate');
        wp_register_style($this->_token . '-frontend', esc_url($this->assets_url) . 'css/frontend.min.css', array(), $this->_version);
        wp_enqueue_style($this->_token . '-frontend');
        wp_register_style('wutb-styles', false);
        wp_enqueue_style('wutb-styles');
        wp_add_inline_style('wutb-styles', $this->custom_styles());
    }

    private function is_enqueued_script($script) {
        return isset($GLOBALS['wp_scripts']->registered[$script]);
    }

    /**
     * Main wutb_Core Instance
     *
     *
     * @since 1.0.0
     * @static
     * @see wutb_Core()
     * @return Main wutb_Core instance
     */
    public static function instance($file = '', $version = '1.0.0') {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($file, $version);
        }
        return self::$_instance;
    }

// End instance()

    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone() {
        
    }

// End __clone()

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup() {
        
    }

// End __wakeup()

    /**
     * Return settings.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function getSettings() {
        global $wpdb;
        $table_name = $wpdb->prefix . "wutb_settings";
        $settings = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wutb_settings WHERE id=1 LIMIT 1");
        return $settings[0];
    }

    public function custom_styles() {
        global $wpdb;
        $output = '';
        $allFonts = array();

        $tours = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wutb_tours ORDER BY id ASC");
        foreach ($tours as $tour) {
            $tourData = json_decode($tour->tourData);
            if (!in_array($tourData->settings->texts_font, $allFonts)) {
                $allFonts[] = $tourData->settings->texts_font;
            }
            if (!in_array($tourData->settings->tooltip_font, $allFonts)) {
                $allFonts[] = $tourData->settings->tooltip_font;
            }
            if (!in_array($tourData->settings->arrow_font, $allFonts)) {
                $allFonts[] = $tourData->settings->arrow_font;
            }
            if (!in_array($tourData->settings->dialog_font, $allFonts)) {
                $allFonts[] = $tourData->settings->dialog_font;
            }
        }
        foreach ($allFonts as $font) {
            $fontName = str_replace(' ', '+', $font);
            $output .= "@import url('https://fonts.googleapis.com/css?family=" . $fontName . "');";
        }
        foreach ($tours as $tour) {
            $tourData = json_decode($tour->tourData);
            $output .= '#wutb_stepContainer[data-tour="' . $tour->id . '"] .wutb_fullscreenText{'
                    . 'font-family: "' . $tourData->settings->texts_font . '"!important;'
                    . '}';
            $output .= '#wutb_stepContainer[data-tour="' . $tour->id . '"] .wutb_dialogContainer{'
                    . 'font-family: "' . $tourData->settings->dialog_font . '"!important;'
                    . '}';
            $output .= '#wutb_stepContainer[data-tour="' . $tour->id . '"] #wutb_elementText{'
                    . 'font-family: "' . $tourData->settings->arrow_font . '"!important;'
                    . '}';
            $output .= '#wutb_stepContainer[data-tour="' . $tour->id . '"] #wutb_tooltip{'
                    . 'font-family: "' . $tourData->settings->tooltip_font . '"!important;'
                    . '}';

            $output .= 'body .ui-tooltip.wutb-tour-' . $tour->id . ' , body .ui-tooltip.wutb-tour-' . $tour->id . ' .ui-tooltip-content{'
                    . 'background-color:' . $tourData->settings->navbar_tooltipColor . ';'
                    . 'color:' . $tourData->settings->navbar_tooltipTextColor . ';'
                    . '}';
        }
        return $output;
    }

    /**
     * Log the plugin version number.
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    private function _log_version_number() {
        update_option($this->_token . '_version', $this->_version);
    }

}
