<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://registration_magic.com
 * @since      1.0.0
 *
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Registraion_Magic
 * @subpackage Registraion_Magic/admin
 * @author     CMSHelplive
 */
class RM_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $registraion_magic    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The controller of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $controller    The main controller of this plugin.
     */
    private $controller;
    
    /**
     * The icon of plugin dashboard menu.
     *
     * @since    4.6.0.6
     * @access   private
     * @var      string    $icon    The icon of plugin dashboard menu.
     */
    private $icon;
    private static $editor_counter = 1;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name   The name of this plugin.
     * @param      string    $version       The version of this plugin.
     */
    public function __construct($plugin_name, $version, $controller) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->controller = $controller;
        $this->icon = base64_encode('<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   viewBox="0 0 8.0933332 8.2133331"
   height="8.2133331"
   width="8.0933332"
   xml:space="preserve"
   id="svg2"
   version="1.1"><metadata
     id="metadata8"><rdf:RDF><cc:Work
         rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" /></cc:Work></rdf:RDF></metadata><defs
     id="defs6"><clipPath
       id="clipPath20"
       clipPathUnits="userSpaceOnUse"><path
         style="clip-rule:evenodd"
         id="path18"
         d="M 54.0703,57 H 10.9102 V 3.87891 h 28.6211 l 14.539,13.90239 z" /></clipPath></defs><g
     transform="matrix(1.3333333,0,0,-1.3333333,0,8.2133333)"
     id="g10"><g
       transform="scale(0.1)"
       id="g12"><g
         id="g14"><g
           clip-path="url(#clipPath20)"
           id="g16"><path
             id="path22"
             style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:none"
             d="m 37.4688,3.87891 h 16.6016 v 16.6016 H 37.4688 Z" /><path
             id="path24"
             style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:none"
             d="M 60.7188,47.0391 H -5.69141 V 63.6406 H 60.7188 Z M -2.37109,60.3203 V 50.3594 H 57.3984 v 9.9609 H -2.37109" /></g></g><path
         id="path26"
         style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:none"
         d="M 54.0703,17.7813 39.5313,3.87891 H 10.9102 V 57 H 54.0703 Z M 14.2305,53.6797 V 7.19922 H 38.1992 L 50.7617,19.1992 V 53.6797 H 14.2305" /><path
         id="path28"
         style="fill:#000000;fill-opacity:1;fill-rule:nonzero;stroke:none"
         d="M 39.7617,34.1602 16.5508,15.7109 10.3984,23.4492 33.6094,41.8906 Z M 8.80859,9.55078 C 7.75,8.71094 6.19141,8.89063 5.33984,9.96094 L 2.26953,13.8203 c -0.84765,1.0703 -0.66797,2.6289 0.40235,3.4688 l 7.72652,6.1601 6.1524,-7.7383 z m 36.26951,35.14062 -3.3672,-9 -6.1523,7.7383 9.5195,1.2617" /><path
         id="path30"
         style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:none"
         d="M 35.9297,13.7617 H 16.4102 V 8.76953 h 19.5195 v 4.99217" /></g></g></svg>');
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_version() {
        return $this->version;
    }

    public function get_controller() {
        return $this->controller;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/style_rm_admin.css', array(), $this->version, 'all');
        wp_enqueue_style('style_rm_rating', RM_BASE_URL . 'public/js/rating3/rateit.css', array(), $this->version, 'all');
        wp_register_style('style_rm_formcard_menu', RM_BASE_URL . 'admin/css/style_rm_formcard_menu.css', array($this->plugin_name), $this->version, 'all');
        wp_enqueue_style('rm_google_font', 'https://fonts.googleapis.com/css?family=Titillium+Web:400,600', array(), $this->version, 'all');
        wp_enqueue_style('rm_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), $this->version, 'all');
        //wp_enqueue_style('rm-jquery-ui','http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css',false,$this->version,'all');        
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script('rm-color', plugin_dir_url(__FILE__) . 'js/jscolor.min.js', array(), $this->version, false);
       
        wp_register_script('rm-utilities', plugin_dir_url(__FILE__) . 'js/script_rm_utilities.js', array(), $this->version, false);
         $utilities_vars= array(
                        'price_fixed'=>sprintf(__("For creating fixed price single product. <a target='_blank' class='rm-more' href='%s'>More</a>",'registrationmagic-gold'),'https://registrationmagic.com/knowledgebase/add-product/#htprodpricetype'),
                        'price_multisel'=>sprintf(__("Allow user to pick multiple products with individual prices. Price will calculated as cumulative for the selection for products. <a target='_blank' class='rm-more' href='%s'>More</a>",'registrationmagic-gold'),'https://registrationmagic.com/knowledgebase/add-product/#htprodpricetype'),
                        'dropdown'=>sprintf(__("Allows user to pick a single product from multiple products with individual prices. <a target='_blank' class='rm-more' href='%s'>More</a>",'registrationmagic-gold'),'https://registrationmagic.com/knowledgebase/add-product/#htprodpricetype'),
                        'userdef'=>sprintf(__("Allows user to enter his/ her own price for product with the form. Useful for accepting donations etc. <a target='_blank' class='rm-more' href='%s'>More</a>",'registrationmagic-gold'),'https://registrationmagic.com/knowledgebase/add-product/#htprodpricetype'),
                        'price_default'=>__("Define how the product will be priced.",'registrationmagic-gold'),
                        'admin_url'=>admin_url()
        );
        wp_localize_script('rm-utilities','utilities_vars',$utilities_vars);
        wp_enqueue_script('rm-utilities');
        
        wp_register_script('rm-formflow', plugin_dir_url(__FILE__) . 'js/script_rm_formflow.js', array(), $this->version, false);
        $formflow_vars= array(
                         'copied'=>__("Copied",'registrationmagic-gold'),
                         'copy'=>__("Copy",'registrationmagic-gold'),
                        'ajaxnonce' => wp_create_nonce('rm_formflow')
        );
        wp_localize_script('rm-formflow','formflow_vars',$formflow_vars);
        
        wp_enqueue_script('script_rm_rating', RM_BASE_URL . 'public/js/rating3/jquery.rateit.js', array(), $this->version, false);
        
        wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/script_rm_admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-tabs', 'jquery-ui-datepicker'), $this->version, false);
        $rm_admin_vars= array(
                        'user_deletion_warning'=>__("Are you sure, you want to delete the selected Users?",'registrationmagic-gold'),
        );
        wp_localize_script($this->plugin_name,'rm_admin_vars',$rm_admin_vars);
        wp_enqueue_script($this->plugin_name);
        
        wp_enqueue_script('google_charts', 'https://www.gstatic.com/charts/loader.js');
        wp_register_script('script_rm_formcard_menu', RM_BASE_URL . 'admin/js/script_rm_formcard_menu.js', array($this->plugin_name), $this->version, false);
        wp_register_script('script_rm_angular', RM_BASE_URL . 'admin/js/angular.min.js', array($this->plugin_name), $this->version, false);
        wp_register_script('chart_js',RM_BASE_URL . 'admin/js/chartjs.js',array('jquery'));
        wp_register_script('select2',RM_BASE_URL.'public/js/script_rm_select2.js', array('jquery'));
        wp_register_style('select2',RM_BASE_URL.'public/css/style_rm_select2.css');
    }

    /**
     * Registers menu pages and submenu pages at the admin area.
     *
     * @since    1.0.0
     */
    public function add_menu() {
        if (current_user_can('manage_options'))
        {
            global  $rm_env_requirements;
            
            if(!RM_Utilities::fatal_errors())
            {
                global $submenu;

                add_menu_page(RM_UI_Strings::get('ADMIN_MENU_REG'), RM_UI_Strings::get('ADMIN_MENU_REG'), "manage_options", "rm_form_manage", array($this->get_controller(), 'run'),  'data:image/svg+xml;base64,' . $this->icon, 26); 
                //add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), RM_UI_Strings::get('ADMIN_MENU_NEWFORM'), "manage_options", "rm_form_add", array($this->get_controller(), 'run'));
                //add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), "manage_options", "rm_form_manage&create_new_form", "__return_null");
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), "manage_options", "rm_form_sett_general", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_SUBS'), RM_UI_Strings::get('ADMIN_MENU_SUBS'), "manage_options", "rm_submission_manage", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_CSTATUS'), RM_UI_Strings::get('ADMIN_MENU_CSTATUS'), "manage_options", "rm_form_manage_cstatus", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_ATTS'), RM_UI_Strings::get('ADMIN_MENU_ATTS'), "manage_options", "rm_attachment_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_MNG_FIELDS_PT'), RM_UI_Strings::get('ADMIN_MENU_MNG_FIELDS_PT'), "manage_options", "rm_field_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), "manage_options", "rm_field_add", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_FORM_STATS'), RM_UI_Strings::get('ADMIN_MENU_FORM_STATS'), "manage_options", "rm_analytics_show_form", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_FIELD_STATS'), RM_UI_Strings::get('ADMIN_MENU_FIELD_STATS'), "manage_options", "rm_analytics_show_field", array($this->get_controller(), 'run'));
                do_action("rm_admin_menu_after_field_stats");
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_INV'), RM_UI_Strings::get('ADMIN_MENU_INV'), "manage_options", "rm_invitations_manage", array($this->get_controller(), 'run'));                
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_ADD_PP_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_PP_FIELD_PT'), "manage_options", "rm_paypal_field_add", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_PP_PROC_PT'), "", "manage_options", "rm_paypal_proc", array($this->get_controller(), 'run'));                
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_ATT_DL_PT'), RM_UI_Strings::get('ADMIN_MENU_ATT_DL_PT'), "manage_options", "rm_attachment_download", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_PT'), RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_PT'), "manage_options", "rm_submission_view", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_RELATED'), RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_RELATED'), "manage_options", "rm_submission_related", array($this->get_controller(), 'run'));
                
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), "manage_options", "rm_sent_emails_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), "manage_options", "rm_sent_emails_view", array($this->get_controller(), 'run'));
                
                //Sub menu for User role section 8th March 2016
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_USERS'), RM_UI_Strings::get('ADMIN_MENU_USERS'), "manage_options", "rm_user_manage", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_ROLES'), RM_UI_Strings::get('ADMIN_MENU_ROLES'), "manage_options", "rm_user_role_manage", array($this->get_controller(), 'run'));
                
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_PRICE'), RM_UI_Strings::get('ADMIN_MENU_PRICE'), "manage_options", "rm_paypal_field_manage", array($this->get_controller(), 'run'));
                
                /* Option menues */
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_SETTINGS'), RM_UI_Strings::get('ADMIN_MENU_SETTINGS'), "manage_options", "rm_options_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_general", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_FAB_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_FAB_PT'), "manage_options", "rm_options_fab", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_AS_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_security", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_UA_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_UA_PT'), "manage_options", "rm_options_user", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_AR_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), "manage_options", "rm_options_autoresponder", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), "manage_options", "rm_options_thirdparty", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), "manage_options", "rm_options_payment", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), "manage_options", "rm_options_default_pages", array($this->get_controller(), 'run'));
                add_submenu_page("", __('User Privacy', 'registrationmagic-gold'), __('User Privacy', 'registrationmagic-gold'), "manage_options", "rm_options_user_privacy", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_SETTING_SAVE_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_SAVE_PT'), "manage_options", "rm_options_save", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_ADD_NOTE_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_NOTE_PT'), "manage_options", "rm_note_add", array($this->get_controller(), 'run'));

                /* End of settings */
                
                //add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_FRONTEND'), RM_UI_Strings::get('ADMIN_MENU_FRONTEND'), "manage_options", "rm_support_frontend", array($this->get_controller(), 'run'));
                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_SUPPORT'), RM_UI_Strings::get('ADMIN_MENU_SUPPORT'), "manage_options", "rm_support_forum", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_USER_ROLE_DEL_PT'), RM_UI_Strings::get('ADMIN_MENU_USER_ROLE_DEL_PT'), "manage_options", "rm_user_role_delete", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_REG_PT'), RM_UI_Strings::get('ADMIN_MENU_REG_PT'), "manage_options", "rm_user_view", array($this->get_controller(), 'run'));
                
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_AR_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_AR_PT'), "manage_options", "rm_form_sett_autoresponder", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_ET_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ET_PT'), "manage_options", "rm_form_sett_email_templates", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_LIM_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_LIM_PT'), "manage_options", "rm_form_sett_limits", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_PST_SUB_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_PST_SUB_PT'), "manage_options", "rm_form_sett_post_sub", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_ACC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ACC_PT'), "manage_options", "rm_form_sett_accounts", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_VIEW_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_VIEW_PT'), "manage_options", "rm_form_sett_view", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_MC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_MC_PT'), "manage_options", "rm_form_sett_mailchimp", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_CC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_CC_PT'), "manage_options", "rm_form_sett_ccontact", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_OV_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_OV_PT'), "manage_options", "rm_form_sett_override", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_AW_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_AW_PT'), "manage_options", "rm_form_sett_aweber", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_IMPORT'), RM_UI_Strings::get('ADMIN_MENU_FS_IMPORT'), "manage_options", "rm_form_import", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_TWITTER'), RM_UI_Strings::get('ADMIN_MENU_FS_TWITTER'), "manage_options", "rm_login_twitter", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_PT'), "manage_options", "rm_form_sett_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_FS_ACTRL_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ACTRL_PT'), "manage_options", "rm_form_sett_access_control", array($this->get_controller(), 'run'));
                $submenu['rm_form_manage'][0][0] = RM_UI_Strings::get('ADMIN_SUBMENU_REG');
                add_submenu_page("", RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), "manage_options", "rm_field_add_widget", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Add Custom Status', 'Add Custom Status', "manage_options", "rm_form_add_cstatus", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Advance Options', 'Advance Options', "manage_options", "rm_options_advance", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Login Fields', 'Login Fields', "manage_options", "rm_login_field_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Login Fields', 'Login Fields', "manage_options", "rm_login_field_add", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Login Fields', 'Login Fields', "manage_options", "rm_login_field_view_sett", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Logged in View', 'Logged in View', "manage_options", "rm_login_view", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Login Dashboard', 'Login Dashboard', "manage_options", "rm_login_sett_manage", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Login Redirections', 'Login Redirections', "manage_options", "rm_login_sett_redirections", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Login Validation & Security', 'Login Validation & Security', "manage_options", "rm_login_val_sec", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Password Recovery', 'Password Recovery', "manage_options", "rm_login_recovery", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Email Templates', 'Email Templates', "manage_options", "rm_login_email_temp", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Two Factor Authentication', 'Two Factor Authentication', "manage_options", "rm_login_two_factor_auth", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Third Part Integrations', 'Third Part Integrations', "manage_options", "rm_login_integrations", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Login Analytics', 'Login Analytics', "manage_options", "rm_login_analytics", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Log Retention', 'Log Retention', "manage_options", "rm_login_retention", array($this->get_controller(), 'run'));
                add_submenu_page("", 'Advanced Log', 'Advanced Log', "manage_options", "rm_login_advanced", array($this->get_controller(), 'run'));
                add_submenu_page("", 'ProfileGrid', 'ProfileGrid', "manage_options", "rm_form_sett_profilegrid", array($this->get_controller(), 'run'));
                add_submenu_page("", 'EventPrime', 'EventPrime', "manage_options", "rm_options_eventprime", array($this->get_controller(), 'run'));
            }
            else
            {
                add_menu_page(RM_UI_Strings::get('ADMIN_MENU_REG'), RM_UI_Strings::get('ADMIN_MENU_REG'), "manage_options", "rm_form_manage", array($this, 'fatal_error_message_display'), plugins_url('../images/profile-icon2.png', __FILE__), 26);
            }
        }
    }
    
    //To disaply errors on menu page. Such as SimplXML extension not available or PHP version.
    public function fatal_error_message_display()
    {        
        include_once RM_ADMIN_DIR.'views/template_rm_cant_continue.php';
    }

    public function add_dashboard_widget()
    {
        //Dashboard widget is for admin users only.
        if (current_user_can('manage_options'))
        {
            wp_add_dashboard_widget(
                'rm_dashboard_widget_display', // Widget slug.
                RM_UI_Strings::get('TITLE_DASHBOARD_WIDGET'), // Title.
                array($this, 'dashboard_widget_display_function')
            );
        }
    }

    public function dashboard_widget_display_function() {
        $xml_loader = RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');

        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_dashboard_widget_display', true);

        $params = array('request' => $request, 'xml_loader' => $xml_loader);
        $this->controller = new RM_Main_Controller($params);
        $this->controller->run();
    }

    public function user_edit_page_widget($user) {
        $xml_loader = RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');

        $request = new RM_Request($xml_loader);
        $request->setReqSlug('rm_user_widget', true);

        $params = array('request' => $request, 'xml_loader' => $xml_loader, 'user' => $user);
        $this->controller = new RM_Main_Controller($params);
        $this->controller->run();
    }

    function add_new_form_editor_button() {
        if (is_admin()) {
            $screen = get_current_screen();
            if ($screen->base == 'post') {
                $xml_loader = RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');

                $request = new RM_Request($xml_loader);
                $request->setReqSlug('rm_editor_actions_add_form', true);

                $params = array('request' => $request, 'xml_loader' => $xml_loader);
                $this->controller = new RM_Main_Controller($params);
                $this->controller->run();
            }
        }
    }

    function add_field_autoresponder() {
        if (is_admin()) {
            $screen = get_current_screen();
            if ($screen->base == 'admin_page_rm_form_sett_autoresponder') {
                if (self::$editor_counter == 1) {
                    $xml_loader = RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');

                    $request = new RM_Request($xml_loader);
                    $request->setReqSlug('rm_editor_actions_add_email', true);

                    $params = array('request' => $request, 'xml_loader' => $xml_loader);
                    $this->controller = new RM_Main_Controller($params);
                    $this->controller->run();
                }

                self::$editor_counter = self::$editor_counter + 1;
            } elseif ($screen->base == 'registrationmagic_page_rm_invitations_manage') {
                $xml_loader = RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');

                $request = new RM_Request($xml_loader);
                $request->setReqSlug('rm_editor_actions_add_fields_dropdown_invites', true);

                $params = array('request' => $request, 'xml_loader' => $xml_loader);
                $this->controller = new RM_Main_Controller($params);
                $this->controller->run();
            }
        }
    }

    public function remove_queue()
    {
        $inv_service = new RM_Invitations_Service;
        $form_id= $_POST['form_id'];
        
        $inv_service->remove_queue($form_id);
        
        wp_die();
    }
    
    public function update_submit_field_config()
    {
        if(!current_user_can('manage_options'))
            wp_die();
        
        $service = new RM_Services;
        $form_id= $_POST['form_id'];
        $config = $_POST['data'];
        $service->update_submit_field_config($form_id, $config);
        
        wp_die();
    }
    
    public function update_login_button_config()
    {  
        if(!current_user_can('manage_options'))
            wp_die();
        
        $service = new RM_Login_Service();
        $config = $_POST['data'];
        $data= array();
        $data['register_btn']= sanitize_text_field($config['register_btn_label']);
        $data['login_btn']= sanitize_text_field($config['login_btn_label']);
        $data['align']= sanitize_text_field($config['btn_align']);
        $data['display_register']= absint($config['display_register']);
        $service->update_button_config($data);
        wp_die();
    }
    
    public function fcm_update_form()
    {
        if(!current_user_can('manage_options'))
            wp_die();
        
        $service = new RM_Services;
        $form_id= $_POST['form_id'];
        $data = $_POST['data'];
        $service->fcm_update_form($form_id, $data);
        
        wp_die();
    }
    
    public function add_version_header() {      
        ?>
        <style>
          .rmagic::before {content:"v<?php echo RM_PLUGIN_VERSION; ?> Premium";}
            .rmagic.rm-hide-version-number::before { display:none}
        </style>
        <?php
    }
    
    public function feedback_dialog()
    {   
        if(!is_admin())
            return;
        
        $screen = get_current_screen();
        
        if(!isset($screen->id))
            return;
        
        if (!in_array($screen->id, array('plugins', 'plugins-network' )))
            return;
        
       include_once RM_ADMIN_DIR.'views/template_rm_plugin_feedback_dialog.php';     
    }
    
    
   
    
    public function post_feedback(){
        $msg= isset($_POST['msg']) ? $_POST['msg'] : '';
        $feedback= $_POST['feedback'];
        $body= '';
        switch($feedback)
        {
            case 'feature_not_available': $body='Feature not available: '; break;
            case 'feature_not_working': $body='Feature not working: '; break;
            case 'found_a_better_plugin': $body='Found a better plugin: '; break;
            case 'plugin_broke_site': $body='Plugin broke my site.'; break;
            case 'plugin_stopped_working': $body='Plugin stopped working'; break;
            case 'temporary_deactivation': return;
            case 'upgrade':  $body='Upgrading to premium '; break;   
            case 'other': $body='Other: '; break;
            default: return;
        }
        $body .= '<p>'.$msg.'</p>';
        $body .= '<p>RegistrationMagic Premium - version '.RM_PLUGIN_VERSION.'</p>';
        RM_Utilities::quick_email('feedback@registrationmagic.com', 'Uninstallation Feedback', $body,RM_EMAIL_GENERIC,array('do_not_save'=>true));
        wp_die();
    }
    
    public function disable_notice(){
        
        if(isset($_REQUEST['disable_dpx'])){
            $dpx_options= new RM_Dpx_Options();
            $dpx_options->set_value_of('dpx_notice_shown',1);
        }
       
        wp_die();
    }
    
    public function upload_template(){
       check_ajax_referer( 'rm_admin_upload_template', 'rm_ajaxnonce' );
       if($_FILES && current_user_can('manage_options')){
               $name=get_temp_dir().'RMagic.xml';
               if(is_array($_FILES['file']['tmp_name']))
               $status= move_uploaded_file ( $_FILES['file']['tmp_name']['0'] , $name );
               else
               $status= move_uploaded_file ( $_FILES['file']['tmp_name'], $name );    
               echo json_encode(array('success'=>$status));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
        wp_die();
    }
    
    public function custom_status_update(){
        $sub_id= absint($_REQUEST['submission_id']);
        $submission= new RM_Submissions();
        $submission->load_from_db($sub_id);
        
        if(isset($_REQUEST['action_type'])){
            $user_model= new RM_User;
            $service = new RM_Services();
            if($_REQUEST['action_type']=='append'){
                $form= new RM_Forms();
                $form->load_from_db($_REQUEST['form_id']);
                $form_options= $form->get_form_options();
                $status_data = $form_options->custom_status[$_REQUEST['status_index']];
                if(isset($status_data['cs_action_status_en']) && $status_data['cs_action_status_en']==1){
                    if($status_data['cs_action_status']=='clear_all'){
                        $service->update_custom_statuses($_REQUEST['status_index'],$_REQUEST['submission_id'],$_REQUEST['form_id'],'clear_all');
                    }else if($status_data['cs_action_status']=='clear_specific'){
                        $service->update_custom_statuses($_REQUEST['status_index'],$_REQUEST['submission_id'],$_REQUEST['form_id'],'clear_specific',$status_data['cs_act_status_specific']);
                    }
                }
                if(isset($status_data['cs_email_user_en']) && $status_data['cs_email_user_en']==1){
                    if($status_data['cs_email_user_body']!=''){
                        $admin_email = get_option('admin_email');
                        $rm_email= new RM_Email();
                        $body= str_replace(array('{{SUB_ID}}','{{UNIQUE_TOKEN}}'), array($sub_id,$submission->get_unique_token()), $status_data['cs_email_user_body']);
                        $rm_email->message($body);
                        $rm_email->subject(trim($status_data['cs_email_user_subject'])!=''?$status_data['cs_email_user_subject']:RM_UI_Strings::get('LABEL_USER_SUBJECT'));
                        $rm_email->from($admin_email);
                        $rm_email->to($_REQUEST['user_email']);
                        $rm_email->send();
                    }
                }
                if(isset($status_data['cs_email_admin_en']) && $status_data['cs_email_admin_en']==1){
                    if($status_data['cs_email_admin_body']!=''){
                        $admin_email = get_option('admin_email');
                        $rm_email= new RM_Email();
                        $body= str_replace(array('{{SUB_ID}}','{{UNIQUE_TOKEN}}'), array($sub_id,$submission->get_unique_token()), $status_data['cs_email_admin_body']);
                        $rm_email->message($body);
                        $rm_email->subject(trim($status_data['cs_email_admin_subject'])!=''?$status_data['cs_email_admin_subject']:RM_UI_Strings::get('LABEL_ADMIN_SUBJECT'));
                        $rm_email->from($admin_email);
                        $rm_email->to($admin_email);
                        $rm_email->send();
                    }
                }
                if(isset($status_data['cs_action_user_act_en']) && $status_data['cs_action_user_act_en']==1){
                    if($status_data['cs_action_user_act']=='create_account'){
                        $user = get_user_by( 'email', $_REQUEST['user_email'] );
                        if(empty($user)){
                            $admin_email = get_option('admin_email');
                            if($user->data->user_email!=$admin_email){
                                $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                                //wp_create_user($_REQUEST['user_email'],$random_password,$_REQUEST['user_email']);
                                $front_service = new RM_Front_Form_Service(); 
                                $registered_user_id= $front_service->register_user_on_custom_status($_REQUEST['user_email'], $_REQUEST['user_email'], $random_password, false,$form_options->user_auto_approval,$_REQUEST['form_id']);
                                if(!empty($registered_user_id)){
                                    update_user_meta($registered_user_id, 'RM_UMETA_FORM_ID', $_REQUEST['form_id']);
                                    update_user_meta($registered_user_id, 'RM_UMETA_SUB_ID', $_REQUEST['submission_id']);
                                    update_user_meta($registered_user_id, 'rm_activation_time', date('Y-m-d H:i:s'));
                                    $opt=new RM_Options;
                                    
                                    if($form_options->user_auto_approval=='default')
                                    {  
                                       do_action('rm_user_registered',$registered_user_id);
                                       $check_setting=$opt->get_value_of('user_auto_approval');
                                       if($check_setting=="verify"){
                                            $front_service->get_user_service()->deactivate_user_by_id($registered_user_id);
                                            wp_die();
                                       }
                                    }
                                    else
                                    {
                                         $check_setting=$form_options->user_auto_approval;
                                    }

                                    if ($check_setting == 'yes' || $check_setting=='verify')
                                    {   
                                        $registered_user_id = $front_service->get_user_service()->activate_user_by_id($registered_user_id);
                                        
                                    }

                                }
                                 
                            }
                        }
                    }else if($status_data['cs_action_user_act']=='deactivate_user'){
                        $user = get_user_by( 'email', $_REQUEST['user_email'] );
                        if(!empty($user)){
                            $admin_email = get_option('admin_email');
                            if($user->data->user_email!=$admin_email){
                                $user_model->deactivate_user($user->ID);
                            }
                        }
                    }else if($status_data['cs_action_user_act']=='activate_user'){
                        $user = get_user_by( 'email', $_REQUEST['user_email'] );
                        if(!empty($user)){
                            $admin_email = get_option('admin_email');
                            if($user->data->user_email!=$admin_email){
                                $user_model->activate_user($user->ID);
                            }
                        }
                    }else if($status_data['cs_action_user_act']=='delete_user'){
                        $user = get_user_by( 'email', $_REQUEST['user_email'] );
                        if(!empty($user)){
                            $admin_email = get_option('admin_email');
                            if($user->data->user_email!=$admin_email){
                                if ( is_multisite() ) 
                                { wpmu_delete_user($user->ID); }
                                else{
                                wp_delete_user( $user->ID);}
                                
                            }
                        }
                    }
                }
                if(isset($status_data['cs_note_en']) && $status_data['cs_note_en']==1){
                    if($status_data['cs_note_text']!=''){
                        $rm_notes= new RM_Notes();
                        $rm_notes->set_initialized(true);
                        $rm_notes->set_submission_id($_REQUEST['submission_id']);
                        $rm_notes->set_notes($status_data['cs_note_text']);
                        $status = $status_data['cs_note_public']==1?'publish':'dtaft';
                        $rm_notes->set_status($status);
                        $rm_notes->set_publication_date(RM_Utilities::get_current_time());
                        $rm_notes->set_published_by(get_current_user_id());
                        
                        $note_options = new stdClass;
                        $note_options->bg_color = 'FFFFFF';
                        $note_options->type = 'note';
                        $rm_notes->set_note_options($note_options);
                        $rm_notes->insert_into_db();
                        
                        if($status_data['cs_note_public']==1){
                            $rm_note_service= new RM_Note_Service();
                            $rm_note_service->notify_users($rm_notes);
                        }
                    }
                }
                if(isset($status_data['cs_blacklist_en'])){
                    $submission_model= new RM_Submissions();
                    $submission_model->load_from_db(absint($_REQUEST['submission_id']));
                    if($status_data['cs_block_email']==1){
                        $submission_model->block_email($_REQUEST['user_email']);
                    }
                    if($status_data['cs_unblock_email']==1){
                        $submission_model->unblock_email($_REQUEST['user_email']);
                    }
                    if($status_data['cs_block_ip']==1){ 
                        $sub_ip = $submission_model->get_submission_ip();
                        if($sub_ip!=''){
                            $submission_model->block_ip($sub_ip);
                        }
                    }
                    if($status_data['cs_unblock_ip']==1){
                        $sub_ip = $submission_model->get_submission_ip();
                        if($sub_ip!=''){
                            $submission_model->unblock_ip($sub_ip);
                        }
                    }
                }
                //echo '<pre>';print_r($status_data);echo '</pre>';die;
            }
            //echo '<pre>';print_r($_REQUEST);echo '</pre>';die;
            echo $service->update_custom_statuses($_REQUEST['status_index'],$_REQUEST['submission_id'],$_REQUEST['form_id'],$_REQUEST['action_type']);
        }
        wp_die();
    }
    
    
    public function admin_notices(){ 
        /* Showing noticed for WooCommerce and EDD integration */
        $g_opts= new RM_Options();
        if(!empty($_GET['rm_disable_edd_notice'])){
            $g_opts->set_value_of('edd_notice', 0);
        }
        if(!empty($_GET['rm_disable_wc_notice'])){
            $g_opts->set_value_of('wc_notice', 0);
        }
        if(!empty($_GET['rm_disable_php_notice'])){
            $g_opts->set_value_of('php_notice', 0);
        }
        if(!empty($_GET['rm_disable_dropbox_notice'])){
            $g_opts->set_value_of('dropbox_notice', 0);
        }
        if(!empty($_GET['rm_disable_wepay_notice'])){
            $g_opts->set_value_of('wepay_notice', 0);
        }
        if(!empty($_GET['rm_disable_stripe_notice'])){
            $g_opts->set_value_of('stripe_notice', 0);
        }
        if(!empty($_GET['rm_disable_mailpoet_notice'])){
            $g_opts->set_value_of('mailpoet_notice', 0);
        }

        $edd_notice= $g_opts->get_value_of('edd_notice');
        $wc_notice= $g_opts->get_value_of('wc_notice');
        $php_notice= $g_opts->get_value_of('php_notice');
        $dropbox_notice= $g_opts->get_value_of('dropbox_notice');
        $wepay_notice= $g_opts->get_value_of('wepay_notice');
        $stripe_notice= $g_opts->get_value_of('stripe_notice');
        $mailpoet_notice= $g_opts->get_value_of('mailpoet_notice');
        $query_string= $_SERVER['QUERY_STRING'];
        if(empty($query_string)){
            $query_string= '?';
        }
        else
        {
            $query_string= '?'.$query_string.'&';
        }

        ?>
        <?php if($php_notice!=0): ?>
            <?php if(version_compare(PHP_VERSION, '5.6.0', '<')): ?>
            <div class="rm_admin_notice rm-notice-banner notice notice-success is-dismissible">
                <p><?php printf(__( 'It seems you are using now obsolete version of PHP. Please note that RegistrationMagic works best with PHP 5.6 or later versions. You may want to upgrade to avoid any potential issues. This is one time warning check and message may not display again once dismissed.','registrationmagic-gold')); ?><a class="rm_dismiss" href="<?php echo $query_string.'rm_disable_php_notice=1' ?>"><img src="<?php echo RM_IMG_URL. '/close-rm.png'; ?>"></a></p>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($edd_notice!=0 &&  class_exists( 'Easy_Digital_Downloads')): ?>
            <div class="rm_admin_notice rm-notice-banner notice notice-success is-dismissible">
                <p><?php printf(__( 'Using RegistrationMagic with Easy Digital Downloads? <a target="__blank" href="%s">Learn how to</a> build intelligent contact forms using RegistrationMagic which display user EDD Order History and Customer details with the form submission, helping you answer support requests faster and better.','registrationmagic-gold'),'https://registrationmagic.com/create-super-intelligent-forms-wordpress/'); ?><a class="rm_dismiss" href="<?php echo $query_string.'rm_disable_edd_notice=1' ?>"><img src="<?php echo RM_IMG_URL. '/close-rm.png'; ?>"></a></p>
            </div>
        <?php endif; ?>

        <?php if($wc_notice!=0 && class_exists( 'WooCommerce' )): ?>
            <div class="rm_admin_notice rm-notice-banner notice notice-success is-dismissible">
                <p><?php printf(__( 'Using RegistrationMagic with WooCommerce? <a target="__blank" href="%s">Learn how to</a> build intelligent contact forms using RegistrationMagic which display user WooCommerce Order history with the form submission, helping you answer support requests faster and better.','registrationmagic-gold'),'https://registrationmagic.com/create-super-intelligent-forms-wordpress/'); ?><a class="rm_dismiss" href="<?php echo $query_string.'rm_disable_wc_notice=1' ?>"><img src="<?php echo RM_IMG_URL. '/close-rm.png'; ?>"></a></p>
            </div>
        <?php endif; ?>
        <?php if($dropbox_notice!=0): ?>
            <?php if(version_compare(PHP_VERSION, '5.6.4', '<')): ?>
            <div class="rm_admin_notice rm-notice-banner notice notice-success is-dismissible">
                <p><?php printf(__( 'Dropbox API is available on PHP 5.6.4 or later versions. It looks like your site is using older PHP version and will need to be upgraded for Dropbox integration access.','registrationmagic-gold')); ?><a class="rm_dismiss" href="<?php echo $query_string.'rm_disable_dropbox_notice=1' ?>"><img src="<?php echo RM_IMG_URL. '/close-rm.png'; ?>"></a></p>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($wepay_notice!=0): ?>
            <?php if(version_compare(PHP_VERSION, '5.6.0', '<')): ?>
            <div class="rm_admin_notice rm-notice-banner notice notice-success is-dismissible">
                <p><?php printf(__( 'WePay API is available on PHP 5.6 or later versions. It looks like your site is using older PHP version and will need to be upgraded for WePay integration access.','registrationmagic-gold')); ?><a class="rm_dismiss" href="<?php echo $query_string.'rm_disable_wepay_notice=1' ?>"><img src="<?php echo RM_IMG_URL. '/close-rm.png'; ?>"></a></p>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($stripe_notice!=0): ?>
            <?php if(version_compare(PHP_VERSION, '5.6.0', '<')): ?>
            <div class="rm_admin_notice rm-notice-banner notice notice-success is-dismissible">
                <p><?php printf(__( 'Stripe API is available on PHP 5.6 or later versions. It looks like your site is using older PHP version and will need to be upgraded for Stripe integration access.','registrationmagic-gold')); ?><a class="rm_dismiss" href="<?php echo $query_string.'rm_disable_stripe_notice=1' ?>"><img src="<?php echo RM_IMG_URL. '/close-rm.png'; ?>"></a></p>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($mailpoet_notice!=0): ?>
            <?php if(version_compare(PHP_VERSION, '5.6.0', '<')): ?>
            <div class="rm_admin_notice rm-notice-banner notice notice-success is-dismissible">
                <p><?php printf(__( 'Mailpoet API is available on PHP 5.6 or later versions. It looks like your site is using older PHP version and will need to be upgraded for Mailpoet integration access.','registrationmagic-gold')); ?><a class="rm_dismiss" href="<?php echo $query_string.'rm_disable_mailpoet_notice=1' ?>"><img src="<?php echo RM_IMG_URL. '/close-rm.png'; ?>"></a></p>
            </div>
            <?php endif; ?>
        <?php endif; ?>
       <?php   
            
    }
}

  
