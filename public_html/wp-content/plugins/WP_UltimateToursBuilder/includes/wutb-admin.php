<?php

if (!defined('ABSPATH'))
    exit;

class wutb_admin {

    /**
     * The single instance
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $_instance = null;

    /**
     * The main plugin object.
     * @var    object
     * @access  public
     * @since    1.0.0
     */
    public $parent = null;

    /**
     * Prefix for plugin settings.
     * @var     string
     * @access  publicexport
     * Delete
     * @since   1.0.0
     */
    public $base = '';

    /**
     * Available settings for plugin.
     * @var     array
     * @access  public
     * @since   1.0.0
     */
    public $settings = array();

    public function __construct($parent) {
        $this->parent = $parent;
        $this->base = 'wutb_';
        $this->dir = dirname($this->parent->file);
        add_action('admin_menu', array($this, 'add_menu_item'));
        add_action('admin_print_scripts', array($this, 'admin_scripts'));
        add_action('admin_print_styles', array($this, 'admin_styles'));

        add_action('admin_print_scripts', array($this->parent, 'frontend_enqueue_scripts'), 10, 1);
        add_action('admin_print_styles', array($this->parent, 'frontend_enqueue_styles'), 10, 1);

        add_action('admin_init', array($this, 'checkActions'));
        add_action('admin_head', array($this->parent, 'custom_styles'));
        add_action('admin_init', array($this, 'checkAutomaticUpdates'));
        add_action('wp_ajax_nopriv_wutb_createTour', array($this, 'createTour'));
        add_action('wp_ajax_wutb_createTour', array($this, 'createTour'));
        add_action('wp_ajax_nopriv_wutb_editTour', array($this, 'editTour'));
        add_action('wp_ajax_wutb_editTour', array($this, 'editTour'));
        add_action('wp_ajax_nopriv_wutb_deleteTour', array($this, 'deleteTour'));
        add_action('wp_ajax_wutb_deleteTour', array($this, 'deleteTour'));
        add_action('wp_ajax_nopriv_wutb_saveTour', array($this, 'saveTour'));
        add_action('wp_ajax_wutb_saveTour', array($this, 'saveTour'));
        add_action('wp_ajax_nopriv_wutb_previewTour', array($this, 'previewTour'));
        add_action('wp_ajax_wutb_previewTour', array($this, 'previewTour'));
        add_action('wp_ajax_nopriv_wutb_previewTourByID', array($this, 'previewTourByID'));
        add_action('wp_ajax_wutb_previewTourByID', array($this, 'previewTourByID'));
        add_action('wp_ajax_nopriv_wutb_duplicateTour', array($this, 'duplicateTour'));
        add_action('wp_ajax_wutb_duplicateTour', array($this, 'duplicateTour'));
        add_action('wp_ajax_nopriv_wutb_exportTours', array($this, 'exportTours'));
        add_action('wp_ajax_wutb_exportTours', array($this, 'exportTours'));
        add_action('wp_ajax_nopriv_wutb_importTours', array($this, 'importTours'));
        add_action('wp_ajax_wutb_importTours', array($this, 'importTours'));
        add_action('wp_ajax_nopriv_wutb_verifyPurchaseCode', array($this, 'verifyPurchaseCode'));
        add_action('wp_ajax_wutb_verifyPurchaseCode', array($this, 'verifyPurchaseCode'));
    }

    /**
     * Add menu to admin
     * @return void
     */
    public function add_menu_item() {
        add_menu_page('Tours Builder', esc_html__("Tours Builder", 'wutb'), 'manage_options', 'wutb_menu', array($this, 'view_backend'), 'dashicons-media-document');
        $menuSlag = 'wutb_menu';
    }

    public function getSettings() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $table_name = $wpdb->prefix . "wutb_settings";
            $settings = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wutb_settings WHERE id=1");            
            $settings = $settings[0];
            return $settings;
        }
    }

    public function checkActions() {
        global $wpdb;
        if (isset($_GET['wutb_action']) && $_GET['wutb_action'] == 'exportTours') {
            $target_path = plugin_dir_path(__FILE__) . '../tmp/export_tours.json';
            header('Content-type: application/json');
            header('Content-Disposition: attachment; filename="' . basename($target_path) . '"');
            header("Content-Transfer-Encoding: Binary");
            header("Content-length: " . filesize($target_path));
            header("Pragma: no-cache");
            header("Expires: 0");
            ob_clean();
            flush();
            readfile($target_path);
            unlink($target_path);
            exit;
        }
    }

    function admin_styles() {
        $url = '';
        if (isset($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
        }
        $settings = $this->getSettings();
        if (isset($_GET['page']) && $_GET['page'] == 'wutb_menu') {
            wp_register_style($this->parent->_token . '-reset', esc_url($this->parent->assets_url) . 'css/reset.min.css', array(), $this->parent->_version);
            wp_enqueue_style($this->parent->_token . '-reset');
            wp_register_style($this->parent->_token . '-jqueryui', esc_url($this->parent->assets_url) . 'css/jquery-ui-theme/jquery-ui.min.css', array(), $this->parent->_version);
            wp_enqueue_style($this->parent->_token . '-jqueryui');
            wp_register_style($this->parent->_token . '-bootstrap', esc_url($this->parent->assets_url) . 'css/bootstrap.min.css', array(), $this->parent->_version);
            wp_enqueue_style($this->parent->_token . '-bootstrap');
            wp_register_style($this->parent->_token . '-fontawesome', esc_url($this->parent->assets_url) . 'css/font-awesome.min.css', array(), $this->parent->_version);
            wp_enqueue_style($this->parent->_token . '-fontawesome');
            wp_register_style($this->parent->_token . '-wutbTheme', esc_url($this->parent->assets_url) . 'css/wutb-theme.min.css', array(), $this->parent->_version);
            wp_enqueue_style($this->parent->_token . '-wutbTheme');
            wp_register_style($this->parent->_token . '-colpick', esc_url($this->parent->assets_url) . 'css/colpick.min.css', array(), $this->parent->_version);
            wp_enqueue_style($this->parent->_token . '-colpick');
            wp_register_style($this->parent->_token . '-summernote', esc_url($this->parent->assets_url) . 'css/summernote-bs4.min.css', array(), $this->parent->_version);
            wp_enqueue_style($this->parent->_token . '-summernote');
            wp_register_style($this->parent->_token . '-mcustomscrollbar', esc_url($this->parent->assets_url) . 'css/jquery.mCustomScrollbar.min.css', array(), $this->parent->_version);
            wp_enqueue_style($this->parent->_token . '-mcustomscrollbar');

            wp_register_style($this->parent->_token . '-codemirror', esc_url($this->parent->assets_url) . 'css/codemirror.min.css', array(), $this->parent->_version);
            wp_enqueue_style($this->parent->_token . '-codemirror');
            wp_register_style($this->parent->_token . '-codemirrorTheme', esc_url($this->parent->assets_url) . 'css/codemirror-xq-light.min.css', array(), $this->parent->_version);
            wp_enqueue_style($this->parent->_token . '-codemirrorTheme');
            wp_register_style($this->parent->_token . '-bootstrapSelect', esc_url($this->parent->assets_url) . 'css/bootstrap-select.min.css', array(), $this->parent->_version);
            wp_enqueue_style($this->parent->_token . '-bootstrapSelect');
            wp_register_style($this->parent->_token . '-admin', esc_url($this->parent->assets_url) . 'css/admin.min.css', array(), $this->parent->_version);
            wp_enqueue_style($this->parent->_token . '-admin');
        }
    }

    /*
     * Load admin scripts
     */

    function admin_scripts() {
        global $wpdb;
        $url = '';
        if (isset($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
        }
        $settings = $this->getSettings();
        if (isset($_GET['page']) && $_GET['page'] == 'wutb_menu') {

            wp_register_script('popper', esc_url($this->parent->assets_url) . 'js/popper.min.js', array(), $this->parent->_version);
            wp_enqueue_script('popper');
            wp_register_script('bootstrap', esc_url($this->parent->assets_url) . 'js/bootstrap.min.js', array('popper'), $this->parent->_version);
            wp_enqueue_script('bootstrap');
            wp_register_script('bootstrap-select', esc_url($this->parent->assets_url) . 'js/bootstrap-select.min.js', array(), $this->parent->_version);
            wp_enqueue_script('bootstrap-select');
            wp_register_script($this->parent->_token . '-wutbTheme', esc_url($this->parent->assets_url) . 'js/wutb-theme.min.js', array('jquery',
                'jquery-ui-core',
                'jquery-ui-mouse',
                'jquery-ui-slider',
                'jquery-ui-datepicker',
                'jquery-ui-accordion',
                'jquery-ui-position',
                'jquery-ui-droppable',
                'jquery-ui-draggable',
                'jquery-ui-resizable',
                'jquery-ui-sortable',
                'jquery-effects-core',
                'jquery-effects-drop',
                'jquery-effects-fade',
                'jquery-effects-bounce'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-wutbTheme');
            wp_register_script('colpick', esc_url($this->parent->assets_url) . 'js/colpick.min.js', array(), $this->parent->_version);
            wp_enqueue_script('colpick');
            wp_register_script('summernote', esc_url($this->parent->assets_url) . 'js/summernote-bs4.min.js', array(), $this->parent->_version);
            wp_enqueue_script('summernote');
            wp_register_script('mcustomscrollbar', esc_url($this->parent->assets_url) . 'js/jquery.mCustomScrollbar.min.js', array(), $this->parent->_version);
            wp_enqueue_script('mcustomscrollbar');

            wp_register_script( 'codemirror', esc_url($this->parent->assets_url) . 'js/codemirror.min.js', array(), $this->parent->_version);
            wp_enqueue_script( 'codemirror');
            wp_register_script('codemirror-javascript', esc_url($this->parent->assets_url) . 'js/codemirror-javascript.min.js', array(), $this->parent->_version);
            wp_enqueue_script('codemirror-javascript');
            wp_register_script('codemirror-css', esc_url($this->parent->assets_url) . 'js/codemirror-css.min.js', array(), $this->parent->_version);
            wp_enqueue_script('codemirror-css');
            wp_register_script($this->parent->_token . '-admin', esc_url($this->parent->assets_url) . 'js/admin.min.js', array($this->parent->_token . '-wutbTheme'), $this->parent->_version);
            wp_enqueue_script($this->parent->_token . '-admin');

            $js_data[] = array(
                'assetsUrl' => esc_url($this->parent->assets_url),
                'adminUrl' => esc_url(get_admin_url()),
                'websiteUrl' => esc_url(get_home_url()),
                'websiteTitle' => get_bloginfo('name'),
                'siteUrl' => home_url(),
                'texts' => array(
                    'My button' => esc_html__('My button', 'wutb'),
                    'New step' => esc_html__('New step', 'wutb'),
                    'tip_flagStep' => esc_html__('Click the flag icon to set this step at first step', 'wutb'),
                    'tip_linkStep' => esc_html__('Start a link to another step', 'wutb'),
                    'tip_delStep' => esc_html__('Remove this step', 'wutb'),
                    'tip_duplicateStep' => esc_html__('Duplicate this step', 'wutb'),
                    'tip_editStep' => esc_html__('Edit this step', 'wutb'),
                    'tip_editLink' => esc_html__('Edit a link', 'wutb'),
                    'Yes' => esc_html__('Yes', 'wutb'),
                    'No' => esc_html__('No', 'wutb'),
                    'Backend' => esc_html__('Backend', 'wutb'),
                    'Frontend' => esc_html__('Frontend', 'wutb'),
                    'errorExport' => esc_html__('An error occurred during the exportation.', 'wutb'),
                    'errorImport' => esc_html__('An error occurred during the importation.', 'wutb'),
                    'My title' => esc_html__('My title', 'wutb'),
                    'Button' => esc_html__('Button', 'wutb'),
                    'Is superior to' => esc_html__('Is superior to', 'wutb'),
                    'Is inferior to' => esc_html__('Is inferior to', 'wutb'),
                    'Is month equals to' => esc_html__('Is month equals to', 'wutb'),
                    'Is month different than' => esc_html__('Is month different than', 'wutb'),
                    'Is month inferior to' => esc_html__('Is month inferior to', 'wutb'),
                    'Is month superior to' => esc_html__('Is month superior to', 'wutb'),
                    'Is equals to' => esc_html__('Is equals to', 'wutb'),
                    'Is different than' => esc_html__('Is different than', 'wutb'),
                    'Contains' => esc_html__('Contains', 'wutb'),
                    'Does not Contain' => esc_html__('Does not Contain', 'wutb'),
                    'Username is' => esc_html__('Username is', 'wutb'),
                    'Last name is' => esc_html__('Last name is', 'wutb'),
                    'Email is' => esc_html__('Email is', 'wutb'),
                    'Role is' => esc_html__('Role is', 'wutb'),                    
                    'Is selected' => esc_html__('Is selected', 'wutb'),
                    'Is not selected' => esc_html__('Is not selected', 'wutb'),
                    'My text here' => esc_html__('My text here', 'wutb'),
                    'Cancel' => esc_html__('Cancel', 'wutb'),
                    'Do you want to save the tour before leaving ?' => esc_html__('Do you want to save the tour before leaving ?', 'wutb'),
                    'Is month equals to' => esc_html__('Is month equals to', 'wutb'),
                    'Is month different than' => esc_html__('Is month different than', 'wutb'),
                    'Is month inferior to' => esc_html__('Is month inferior to', 'wutb'),
                    'Is month superior to' => esc_html__('Is month superior to', 'wutb'),
                    'Current URL' => esc_html__('Current URL', 'wutb'),
                    'Current Date' => esc_html__('Current Date', 'wutb'),
                    'Current WP user' => esc_html__('Current WP user', 'wutb'),
                    'The tour was correctly saved' => esc_html__('The tour was correctly saved', 'wutb'),
                    'Select an element' => esc_html__('Select an element', 'wutb'),
                    'Do you want to select this element ?' => esc_html__(' Do you want to select this element ?', 'wutb'),
                    'Click the target element in the page' => esc_html__('Click the target element in the page', 'wutb'),
                    'Select this page' => esc_html__('Select this page', 'wutb'),
                    'Do you want to delete this tour ?' => esc_html__('Do you want to delete this tour ?', 'wutb'),
                    'Do you want to delete this step ?' => esc_html__('Do you want to delete this step ?', 'wutb'),
                    'Navigate to the desired page then click the button below to select it.' => esc_html__('Navigate to the desired page then click the button below to select it.', 'wutb'),
                    'Navigate to the desired page and click the button below to select the desired item.' => esc_html__('Navigate to the desired page and click the button below to select the desired item.', 'wutb')
                )
            );
            wp_localize_script($this->parent->_token . '-admin', 'wutb_data', $js_data);
        }
    }

    private function jsonRemoveUnicodeSequences($struct) {
        return json_encode($struct);
    }

    public function view_backend() {
        global $wpdb;
        $settings = $this->getSettings();
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');
        $tourContent = '';
        $tourContent .= '<div id="wutb_mainPanel" class="wutb wutb_bootstraped">';
        $tourContent .= '<div id="wutb_loader"><div class="wutb_spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>';
        $tourContent .= '<div class="wutb_pageBody">';

        $tourContent .= '<div id="wutb_panelTourEdit" class="layout-panel">';
        $tourContent .= '<div class="panel-top hidden">';
        $tourContent .= '<div class="panel-header text-center p-0">';

        $tourContent .= '<div data-tab="tour">';
        $tourContent .= '<a href="javascript:" data-action="addStep" ><span class="fas fa-plus"></span>' . esc_html__('Add a step', 'wutb') . '</a>';
        $tourContent .= '<a href="javascript:"   data-action="preview"><span class="fas fa-eye"></span>' . esc_html__('Preview', 'wutb') . '</a>';
        $tourContent .= '<a href="javascript:"   data-action="saveTour"><span class="fas fa-save"></span>' . esc_html__('Save', 'wutb') . '</a>';
        $tourContent .= '</div>'; // eof [data-tab="tour"]

        $tourContent .= '<div data-tab="step">';
        $tourContent .= '<a href="javascript:"   data-action="closeStep"><span class="fas fa-times"></span>' . esc_html__('Close the step', 'wutb') . '</a>';
        $tourContent .= '</div>'; // eof [data-tab="step"]

        $tourContent .= '</div>'; // eof .panel-header       
        $tourContent .= '</div>'; // eof .panel-top


        $tourContent .= '<div class="panel-center">';
        $tourContent .= '<div class="panel-body p-0">';

        $tourContent .= '<div id="wutb_panelFirstTour">';
        $tourContent .= '<a href="javascript:" data-action="createTour"><span class="fas fa-plus-circle"></span><br/>' . esc_html__('Create a new tour', 'wutb') . '</a>';
        $tourContent .= '</div>'; // #wutb_panelFirstTour

        $tourContent .= '<div id="wutb_panelToursList">';
        $tourContent .= '<table id="wutb_tourListTable" class="table table-striped">';
        $tourContent .= '<thead class="bg-primary">';
        $tourContent .= '<th>' . esc_html__('Tour title', 'wutb') . '</th>';
        $tourContent .= '<th></th>';
        $tourContent .= '<th class="wutb_btnsContainer text-right">'
                . '<a href="javascript:" data-action="openWinLicense" class="btn btn-secondary btn-circle"  data-tooltip="true" data-placement="bottom"  data-tooltipcolor="grey" title="' . esc_html__('Purchase code', 'wutb') . '"><span class="fas fa-key"></span></a>'
                . '<a href="javascript:" data-action="importTours" class="btn btn-secondary btn-circle"  data-tooltip="true" data-placement="bottom"  data-tooltipcolor="grey" title="' . esc_html__('Import tours', 'wutb') . '"><span class="fas fa-cloud-upload-alt"></span></a>'
                . '<a href="javascript:" data-action="exportTours" class="btn btn-secondary btn-circle"  data-tooltip="true" data-placement="bottom"  data-tooltipcolor="grey" title="' . esc_html__('Export the tours', 'wutb') . '"><span class="fas fa-cloud-download-alt"></span></a>'
                . '<a href="javascript:" data-action="createTour" class="btn btn-warning btn-circle "  data-tooltip="true" data-placement="bottom"  data-tooltipcolor="grey" title="' . esc_html__('Create a new tour', 'wutb') . '"><span class="fas fa-plus"></span></a>'
                . '</th>';
        $tourContent .= '</thead>';
        $tourContent .= '<tbody>';

        $table_name = $wpdb->prefix . "wutb_tours";
        
        $tours = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wutb_tours ORDER BY id ASC");
        foreach ($tours as $tour) {
            $tourContent .= '<tr data-tour="' . $tour->id . '">';
            $tourContent .= '<td><a href="javascript:">' . $tour->title . '</a></td>';
            $tourContent .= '<td colspan="2" class="text-right">';
            $tourContent .= '<a href="javascript:" data-action="editTour"  class="btn btn-circle btn-primary" data-tooltip="true"  data-placement="bottom" title="' . esc_html__('Edit this tour', 'wutb') . '"><span class="fas fa-pencil-alt"></span></a>';
            $tourContent .= '<a href="javascript:" data-action="previewTourByID" data-tourid="' . $tour->id . '" class="btn btn-secondary btn-circle " data-toggle="tooltip" title="' . esc_html__('Preview this tour', 'wutb') . '" data-placement="bottom"><span class="fas fa-eye"></span></a>';

            $tourContent .= '<a href="javascript:" data-action="duplicateTour" data-tourid="' . $tour->id . '"  class="btn btn-circle btn-secondary" data-tooltip="true"  data-placement="bottom" title="' . esc_html__('Duplicate this tour', 'wutb') . '"><span class="fa fa-copy"></span></a>';
            $tourContent .= '<a href="javascript:" data-action="deleteTour"  class="btn btn-circle btn-danger" data-tooltip="true" data-placement="bottom" title="' . esc_html__('Delete this tour', 'wutb') . '"><span class="fas fa-trash-alt"></span></a>';
            $tourContent .= '</td>';
            $tourContent .= '</tr>';
        }
        $tourContent .= '</tbody>';
        $tourContent .= '</table>'; // eof #wutb_tourListTable
        $tourContent .= '</div>'; // eof #wutb_panelToursList


        $tourContent .= '<div id="wutb_panelTourSettings">';

        $tourContent .= '</div>'; // eof #wutb_panelTourSettings

        $tourContent .= '<div id="wutb_stepManagerPanel" class="p-4">';

        $tourContent .= '<div id="wutb_stepsOverflow">';
        $tourContent .= '<div id="wutb_stepsContainer">';
        $tourContent .= '<canvas id="wutb_stepsCanvas"></canvas>';
        $tourContent .= '</div>';
        $tourContent .= '</div>';
        $tourContent .= '</div>'; // eof #wutb_stepManagerPanel    

        $tourContent .= '<div id="wutb_elementSelectionPanel">';
        $tourContent .= '<iframe id="wutb_elementSelectionFrame"></iframe>';
        $tourContent .= '<div id="wutb_frameLoader"><div class="wutb_spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>';
        // $tourContent .= '<div id="wutb_stepPreview"></div>';        
        $tourContent .= '</div>'; // eof #wutb_elementSelectionPanel   

        $tourContent .= '<div id="wutb_stepContentPanel">';
        $tourContent .= '<div id="wutb_stepContentWrapper">';
        $tourContent .= '<div id="wutb_stepContent">';
        $tourContent .= '<div id="wutb_stepContentInside" class=""></div>';
        $tourContent .= '</div>'; // eof #wutb_stepContent
        $tourContent .= '</div>'; // eof #wutb_stepContentWrapper    
        $tourContent .= '</div>'; // eof #wutb_tourContentPanel

        $tourContent .= '</div>'; // eof .panel-body
        $tourContent .= '</div>'; // eof .panel-center

        $tourContent .= '<div class="panel-right hidden minified " data-buttons="minify">';
        $tourContent .= '<div class="panel-header">';
        $tourContent .= ' <i class="fas fa-pencil-alt"></i> ' . esc_html__('Step settings', 'wutb');
        $tourContent .= '</div>'; // eof .panel-header
        $tourContent .= '<div class="panel-body ">';

        $tourContent .= '<div class="panel-tabs">';
        $tourContent .= '<a href="javascript:" data-tab="settings" class="btn btn-secondary" title="' . esc_html__('Settings', 'wutb') . '" data-toggle="tooltip"><span class="fas fa-cogs"></span></a>';
        $tourContent .= '<a href="javascript:" data-tab="style" class="btn btn-secondary" title="' . esc_html__('Style', 'wutb') . '" data-toggle="tooltip"><span class="fas fa-palette"></span></a>';
        $tourContent .= '<a href="javascript:"  data-tab="buttons" class="btn btn-secondary" title="' . esc_html__('Buttons', 'wutb') . '" data-toggle="tooltip"><span class="fas fa-hand-point-up"></span></a>';

        $tourContent .= '</div>'; // eof .panel-tabs

        $tourContent .= '<div class="m_scrollbar pt-2">';
        $tourContent .= '<div data-tab="settings" class="p-2">';

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Title', 'wutb') . '</label>';
        $tourContent .= '<input type="text" class="form-control" name="title" />';
        $tourContent .= '</div>'; // eof .form-group      

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Action', 'wutb') . '</label>';
        $tourContent .= '<select name="type" class="form-control">';
        $tourContent .= '<option value="dialog">' . esc_html__('Dialog', 'wutb') . '</option>';
        $tourContent .= '<option value="executeJS">' . esc_html__('Execute JS code', 'wutb') . '</option>';
        $tourContent .= '<option value="text">' . esc_html__('Fullscreen text', 'wutb') . '</option>';
        $tourContent .= '<option value="redirection">' . esc_html__('Redirection', 'wutb') . '</option>';
        $tourContent .= '<option value="showElement">' . esc_html__('Show an element', 'wutb') . '</option>';
        $tourContent .= '</select>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">
            <label>' . esc_html__('Page URL', 'wutb') . '</label>
            <div class="input-group">
            <input type="url" name="url" data-dontrefresh class="form-control" placeholder="' . esc_html__('Page URL', 'wutb') . '" >
            <div class="input-group-append">
              <a href="javascript:" data-action="startSelectUrl" class="btn btn-primary btn-circle"><span class="fas fa-hand-point-up"></span></a>
            </div>
            </div>
          </div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">
            <label>' . esc_html__('Target Element', 'wutb') . '</label>
            <div class="input-group">
            <input type="text" name="domElement" class="form-control" placeholder="' . esc_html__('Target Element', 'wutb') . '" >
            <div class="input-group-append">
              <a href="javascript:" data-action="startSelectElement" class="btn btn-primary btn-circle"><span class="fas fa-hand-point-up"></span></a>
            </div>
             </div>
          </div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Header text', 'wutb') . '</label>';
        $tourContent .= '<input type="text" class="form-control" name="headerText" />';
        $tourContent .= '</div>'; // eof .form-group      

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Text', 'wutb') . '</label>';
        $tourContent .= '<textarea type="text" class="form-control" name="text"></textarea>';
        $tourContent .= '</div>'; // eof .form-group      

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('JS Code', 'wutb') . '</label>';
        $tourContent .= '<textarea id="wutb_codeJSEditor" class="form-control" name="codeJS"></textarea>';
        $tourContent .= '</div>'; // eof .form-group      

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Action to continue', 'wutb') . '</label>';
        $tourContent .= '<select name="continueAction" class="form-control">';
        $tourContent .= '<option value="click">' . esc_html__('Click on the element', 'wutb') . '</option>';
        $tourContent .= '<option value="delay">' . esc_html__('Fixed delay', 'wutb') . '</option>';
        $tourContent .= '</select>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Delay before showing (in seconds)', 'wutb') . '</label>';
        $tourContent .= '<input type="number" min="0" max="30" step="1" name="startDelay" data-slider>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Delay before continue (in seconds)', 'wutb') . '</label>';
        $tourContent .= '<input type="number" min="0" max="360" step="1" name="continueDelay" data-slider>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '</div>'; // eof [data-tab="settings"]
        $tourContent .= '<div data-tab="style" class="p-2">';

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Text style', 'wutb') . '</label>';
        $tourContent .= '<select name="textStyle" class="form-control">';
        $tourContent .= '<option value="arrow">' . esc_html__('Arrow', 'wutb') . '</option>';
        $tourContent .= '<option value="tooltip">' . esc_html__('Tooltip', 'wutb') . '</option>';
        $tourContent .= '</select>';
        $tourContent .= '</div>'; // eof .form-group  
        
        
        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Position X', 'wutb') . '</label>';
        $tourContent .= '<input type="number" min="-200" max="200" step="1" name="offsetX" data-slider>';
        $tourContent .= '</div>'; // eof .form-group  
        
        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Position Y', 'wutb') . '</label>';
        $tourContent .= '<input type="number" min="-200" max="200" step="1" name="offsetY" data-slider>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Background color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="backgroundColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Text color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="textColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Header color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="headerColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Header text color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="headerTextColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Footer color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="footerColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Text size', 'wutb') . '</label>';
        $tourContent .= '<input type="number" min="8" max="99" step="1" name="textSize" data-slider>';
        $tourContent .= '</div>'; // eof .form-group 

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Header text size', 'wutb') . '</label>';
        $tourContent .= '<input type="number" min="8" max="99" step="1" name="headerTextSize" data-slider>';
        $tourContent .= '</div>'; // eof .form-group 

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Entry animation', 'wutb') . '</label>';
        $tourContent .= '<select name="entryAnimation" class="form-control">';
        $tourContent .= '<option value="">' . esc_html__('None', 'wutb') . '</option>';
        $tourContent .= '<option value="bounceIn">' . esc_html__('Bounce', 'wutb') . '</option>';
        $tourContent .= '<option value="fadeIn">' . esc_html__('Fade', 'wutb') . '</option>';
        $tourContent .= '<option value="slideInUp">' . esc_html__('Slide Up', 'wutb') . '</option>';
        $tourContent .= '<option value="slideInDown">' . esc_html__('Slide Down', 'wutb') . '</option>';
        $tourContent .= '<option value="zoomIn">' . esc_html__('Zoom', 'wutb') . '</option>';
        $tourContent .= '</select>';
        $tourContent .= '</div>'; // eof .form-group  


        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Animation', 'wutb') . '</label>';
        $tourContent .= '<select name="animation" class="form-control">';
        $tourContent .= '<option value="">' . esc_html__('None', 'wutb') . '</option>';
        $tourContent .= '<option value="bounce">' . esc_html__('Bounce', 'wutb') . '</option>';
        $tourContent .= '<option value="flash">' . esc_html__('Flash', 'wutb') . '</option>';
        $tourContent .= '<option value="pulse">' . esc_html__('Pulse', 'wutb') . '</option>';
        $tourContent .= '<option value="rubberBand">' . esc_html__('Rubber Band', 'wutb') . '</option>';
        $tourContent .= '<option value="shake">' . esc_html__('Shake', 'wutb') . '</option>';
        $tourContent .= '<option value="tada">' . esc_html__('Tada', 'wutb') . '</option>';
        $tourContent .= '<option value="wobble">' . esc_html__('Wobble', 'wutb') . '</option>';
        $tourContent .= '<option value="jello">' . esc_html__('Jello', 'wutb') . '</option>';
        $tourContent .= '<option value="heartBeat">' . esc_html__('Heart beat', 'wutb') . '</option>';
        $tourContent .= '</select>';
        $tourContent .= '</div>'; // eof .form-group  


        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Position', 'wutb') . '</label>';
        $tourContent .= '<select name="position" class="form-control">';
        $tourContent .= '<option value="left">' . esc_html__('Left', 'wutb') . '</option>';
        $tourContent .= '<option value="right">' . esc_html__('Right', 'wutb') . '</option>';
        $tourContent .= '<option value="top">' . esc_html__('Top', 'wutb') . '</option>';
        $tourContent .= '<option value="down">' . esc_html__('Down', 'wutb') . '</option>';
        $tourContent .= '</select>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<div class="form-group switch-group"><input type="checkbox" name="useOverlay" class="form-check-input switch" /><label class="form-check-label switch-label">' . esc_html__('Add overlay', 'wutb') . '</label></div>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Overlay opacity', 'wutb') . '</label>';
        $tourContent .= '<input type="number" min="0" max="1" data-step="0.1" name="overlayOpacity" data-slider>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Overlay color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="overlayColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  



        $tourContent .= '</div>'; // eof [data-tab="style"]
        $tourContent .= '<div data-tab="buttons" class="p-2">';

        $tourContent .= '<table id="stepButtonsTable" class="table table-striped">';
        $tourContent .= '<thead>';
        $tourContent .= '<tr>';
        $tourContent .= '<th>' . esc_html__('Label', 'wutb') . '</th>';
        $tourContent .= '<th class="text-right"><a href="javascript:" data-action="createButton" class="btn btn-circle btn-primary" title="' . esc_html__('Create a new button', 'wutb') . '" data-toggle="tooltip"><span class="fas fa-plus"></span></a></th>';
        $tourContent .= '</tr>';
        $tourContent .= '</thead>'; // eof thead
        $tourContent .= '<tbody>';
        $tourContent .= '</tbody>'; // eof tbody
        $tourContent .= '</table>'; // eof #stepButtonsTable

        $tourContent .= '</div>'; // eof [data-tab="buttons"]
        $tourContent .= '</div>'; // eof .m_scrollbar
        
        $tourContent .= '</div>'; // eof .panel-body
        $tourContent .= '</div>'; // eof .panel-bottom

        $tourContent .= '<div class="panel-bottom hidden minified " data-buttons="minify">';
        $tourContent .= '<div class="panel-header">';
        $tourContent .= ' <i class="fas fa-cog"></i> ' . esc_html__('Settings', 'wutb');
        $tourContent .= '</div>'; // eof .panel-header
        $tourContent .= '<div class="panel-body   p-0">';
        $tourContent .= '<div id="wutb_bottomSettings">';

        $tourContent .= '<ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="javascript:" data-tab="settings"><span class="fas fa-info"></span>' . esc_html__('Settings', 'wutb') . '</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:"  data-tab="design"><span class="fas fa-palette"></span>' . esc_html__('Design', 'wutb') . '</a>
                            </li>
                        </ul>';

        $tourContent .= '<div class="p-3 m_scrollbar">';
        $tourContent .= '<div data-tab="settings">';

        $tourContent .= '<div class="col-md-4">';
        
        
        $tourContent .= '<div class="form-group">';
        $tourContent .= '<div class="form-group switch-group"><input type="checkbox" name="activated" class="form-check-input switch" /><label class="form-check-label switch-label">' . esc_html__('Activated', 'wutb') . '</label></div>';
        $tourContent .= '</div>'; // eof .form-group 

        
        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Title', 'wutb') . '</label>';
        $tourContent .= '<input type="text" class="form-control" name="title" />';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Targeted devices', 'wutb') . '</label>';
        $tourContent .= '<select class="form-control" name="devices">';
        $tourContent .= '<option value="">' . esc_html__('All', 'wutb') . '</option>';
        $tourContent .= '<option value="computers">' . esc_html__('Computers & tablets', 'wutb') . '</option>';
        $tourContent .= '<option value="mobiles">' . esc_html__('Mobiles only', 'wutb') . '</option>';
        $tourContent .= '</select>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<div class="form-group switch-group"><input type="checkbox" name="runOnce" class="form-check-input switch" /><label class="form-check-label switch-label">' . esc_html__('Run only once', 'wutb') . '</label></div>';
        $tourContent .= '</div>'; // eof .form-group 

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<div class="form-group switch-group"><input type="checkbox" name="showNavbar" class="form-check-input switch" /><label class="form-check-label switch-label">' . esc_html__('Show navigation bar', 'wutb') . '</label></div>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Label "Stop the tour"', 'wutb') . '</label>';
        $tourContent .= '<input type="text" class="form-control" name="navbar_txtStopTour" />';
        $tourContent .= '</div>'; // eof .form-group  
        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Label "Next step"', 'wutb') . '</label>';
        $tourContent .= '<input type="text" class="form-control" name="navbar_txtNextStep" />';
        $tourContent .= '</div>'; // eof .form-group  
        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Label "Previous step"', 'wutb') . '</label>';
        $tourContent .= '<input type="text" class="form-control" name="navbar_txtPreviousStep" />';
        $tourContent .= '</div>'; // eof .form-group  
        
        $tourContent .= '</div>'; // eof .col-md-4 

        $tourContent .= '<div class="col-md-4">';

        $tourContent .= '
        <div class="form-group">
            <label>' . esc_html__('Start URL', 'wutb') . '</label>
            <div class="input-group">
            <input type="url" name="startURL" class="form-control" placeholder="https://..." >
            <div class="input-group-append">
              <a href="javascript:" data-action="startSelectStartURL" class="btn btn-primary btn-circle"><span class="fas fa-hand-point-up"></span></a>
            </div>
            </div>
          </div>';

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Start method', 'wutb') . '</label>';
        $tourContent .= '<select class="form-control" name="startMethod">';
        $tourContent .= '<option value="">' . esc_html__('Start automatically', 'wutb') . '</option>';
        $tourContent .= '<option value="elementClick">' . esc_html__('When an element is clicked', 'wutb') . '</option>';
        $tourContent .= '</select>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group  mb-3">
            <label>' . esc_html__('Target element CSS selector', 'wutb') . '</label>
            <div class="input-group">
            <input type="text" name="tourDomElement" class="form-control" placeholder="' . esc_html__('Fill the target CSS selector here', 'wutb') . '" >
            <div class="input-group-append">
              <a href="javascript:" data-action="startSelectElement" class="btn btn-primary btn-circle"><span class="fas fa-hand-point-up"></span></a>
            </div>
            </div>
          </div>'; // eof .form-group  

        $tourContent .= '<div class="alert alert-info">' . esc_html__('You can also add this CSS class to any element of the website to start the tour when the user clicks it', 'wutb') . ': &nbsp; <strong>open-tour-<span data-info="tourID"></span></strong></div>';

        $tourContent .= '</div>'; // eof .col-md-2

        $tourContent .= '<div class="col-md-4">';
        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Target users roles', 'wutb') . '</label>';
        $tourContent .= '<select class="form-control" name="allowedRoles" multiple="multiple">';
        $tourContent .= '<option value="">' . esc_html__('Everybody', 'wutb') . '</option>';
        global $wp_roles;
        foreach ($wp_roles->roles as $key => $role) {
            $tourContent .= '<option value="' . $key . '">' . $role['name'] . '</option>';
        }
        $tourContent .= '</select>';

        $tourContent .= ' </div>'; // eof .form-group  
        $tourContent .= '</div>'; // eof .col-md-4 
        $tourContent .= '</div>'; // eof [data-tab="settings"]

        $tourContent .= '<div data-tab="design">';

        $tourContent .= '<div class="col-md-2">';
        $tourContent .= '<h5>' . esc_html__('Navigation bar', 'wutb') . '</h5>';

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Navigation bar position', 'wutb') . '</label>';
        $tourContent .= '<select class="form-control" name="navbarPosition">';
        $tourContent .= '<option value="bottomLeft">' . esc_html__('Bottom left', 'wutb') . '</option>';
        $tourContent .= '<option value="bottomRight">' . esc_html__('Bottom right', 'wutb') . '</option>';
        $tourContent .= '<option value="topLeft">' . esc_html__('Top left', 'wutb') . '</option>';
        $tourContent .= '<option value="topRight">' . esc_html__('Top right', 'wutb') . '</option>';
        $tourContent .= '</select>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Background color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="navbarColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Bar opacity', 'wutb') . '</label>';
        $tourContent .= '<input type="number" min="0" max="1" data-step="0.1" name="navbarOpacity" data-slider>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Buttons color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="navbarBtnsColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Tooltip color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="navbar_tooltipColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Tooltip text color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="navbar_tooltipTextColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div style="height: 148px;">&nbsp;</div>';

        $tourContent .= '</div>'; // eof .col-md-2

        $tourContent .= '<div class="col-md-2">';
        $tourContent .= '<h5>' . esc_html__('Fullscreen texts', 'wutb') . '</h5>';

        $tourContent .= '<div class="form-group  mb-3">
            <label>' . esc_html__('Google Font', 'wutb') . '</label>
            <div class="input-group">
            <input type="text" name="texts_font" class="form-control" placeholder="' . esc_html__('Paste the google font name here', 'wutb') . '" >
            <div class="input-group-append">
              <a href="https://www.google.com/fonts" target="_blank" class="btn btn-primary btn-circle"><span class="fas fa-font"></span></a>
            </div>
            </div>
          </div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Text color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="texts_textColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  
        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Header text color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="texts_headerTextColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Text size', 'wutb') . '</label>';
        $tourContent .= '<input type="number" min="8" max="68" step="1" name="texts_textSize" data-slider>';
        $tourContent .= '</div>'; // eof .form-group 

        $tourContent .= '</div>'; // eof .col-md-2
        $tourContent .= '<div class="col-md-2">';
        $tourContent .= '<h5>' . esc_html__('Tooltips', 'wutb') . '</h5>';

        $tourContent .= '<div class="form-group  mb-3">
            <label>' . esc_html__('Google Font', 'wutb') . '</label>
            <div class="input-group">
            <input type="text" name="tooltip_font" class="form-control" placeholder="' . esc_html__('Paste the google font name here', 'wutb') . '" >
            <div class="input-group-append">
              <a href="https://www.google.com/fonts" target="_blank" class="btn btn-primary btn-circle"><span class="fas fa-font"></span></a>
            </div>
            </div>
          </div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Text color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="tooltip_textColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Text size', 'wutb') . '</label>';
        $tourContent .= '<input type="number" min="8" max="68" step="1" name="tooltip_textSize" data-slider>';
        $tourContent .= '</div>'; // eof .form-group 

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Background color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="tooltip_backgroundColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '</div>'; // eof .col-md-2

        $tourContent .= '<div class="col-md-2">';

        $tourContent .= '<h5>' . esc_html__('Arrows', 'wutb') . '</h5>';

        $tourContent .= '<div class="form-group  mb-3">
            <label>' . esc_html__('Google Font', 'wutb') . '</label>
            <div class="input-group">
            <input type="text" name="arrow_font" class="form-control" placeholder="' . esc_html__('Paste the google font name here', 'wutb') . '" >
            <div class="input-group-append">
              <a href="https://www.google.com/fonts" target="_blank" class="btn btn-primary btn-circle"><span class="fas fa-font"></span></a>
            </div>
            </div>
          </div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Text color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="arrow_textColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Text size', 'wutb') . '</label>';
        $tourContent .= '<input type="number" min="8" max="68" step="1" name="arrow_textSize" data-slider>';
        $tourContent .= '</div>'; // eof .form-group 

        $tourContent .= '</div>'; // eof .col-md-2

        $tourContent .= '<div class="col-md-2">';
        $tourContent .= '<h5>' . esc_html__('Dialog', 'wutb') . '</h5>';

        $tourContent .= '<div class="form-group  mb-3">
            <label>' . esc_html__('Google Font', 'wutb') . '</label>
            <div class="input-group">
            <input type="text" name="dialog_font" class="form-control" placeholder="' . esc_html__('Paste the google font name here', 'wutb') . '" >
            <div class="input-group-append">
              <a href="https://www.google.com/fonts" target="_blank" class="btn btn-primary btn-circle"><span class="fas fa-font"></span></a>
            </div>
            </div>
          </div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Text color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="dialog_textColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Background color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="dialog_backgroundColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group 

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Header color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="dialog_headerColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Header text color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="dialog_headerTextColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Footer color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="dialog_footerColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '</div>'; // eof .col-md-2

        $tourContent .= '<div class="col-md-2">';
        $tourContent .= '<h5>' . esc_html__('Overlay', 'wutb') . '</h5>';

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Overlay opacity', 'wutb') . '</label>';
        $tourContent .= '<input type="number" min="0" max="1" data-step="0.1" name="overlayOpacity" data-slider>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '<div class="form-group">';
        $tourContent .= '<label>' . esc_html__('Overlay color', 'wutb') . '</label>';
        $tourContent .= '<input class="form-control" type="text" name="overlayColor" data-colorpicker>';
        $tourContent .= '</div>'; // eof .form-group  

        $tourContent .= '</div>'; // eof .col-md-2

        $tourContent .= '</div>'; // eof [data-tab="design"]

        $tourContent .= '</div>'; // eof .p-3 
        //  $tourContent .= '</div>'; // eof [data-tab="tour"]  
        $tourContent .= '</div>'; // eof .#wutb_bottomSettings   

        $tourContent .= '</div>'; // eof .panel-body
        $tourContent .= '</div>'; // eof .panel-bottom

        $tourContent .= '</div>'; // eof #wutb_panelTourEdit

        $tourContent .= '<div id="wutb_winConditions" class="wutb_window container-fluid"> ';
        $tourContent .= '<div class="wutb_panelHeader col-md-12" ><span class="fas fa-pencil-alt" ></span > <span>' . esc_html__('Edit conditions', 'wutb') . '</span>';

        $tourContent .= ' <div class="btn-toolbar"> ';
        $tourContent .= '<a class="btn btn-primary btn-circle" href="javascript:" ><span class="fas fa-times wutb_btnWinClose" ></span ></a > ';
        $tourContent .= '</div> '; // eof toolbar
        $tourContent .= '</div> '; // eof header

        $tourContent .= '<div class="clearfix"></div><div class="container-fluid wutb_container" style="max-width: 90%;margin: 0 auto;margin-top: 18px;"> ';

        $tourContent .= '<div id="wutb_linkInteractions" > ';
        $tourContent .= '<div id="wutb_linkMainImg" class="wutb_stepsPreview">
            <div data-type="steps">
                <div id="wutb_linkOriginStep" class="wutb_stepBloc "><div class="wutb_stepBlocWrapper"><h4 id="wutb_linkOriginTitle"></h4></div> </div>
                <div id="wutb_linkStepArrow"></div>
                <div id="wutb_linkDestinationStep" class="wutb_stepBloc  "><div class="wutb_stepBlocWrapper"><h4 id="wutb_linkDestinationTitle"></h4></div></div>
              </div>
              <div  data-type="visibility">
               <span class="fas fa-eye"></span>
              </div>
         </div>';
        $tourContent .= '<p>'
                . '<select id="wutb_conditionsOperator" class="form-control wutb_conditionOperator">'
                . '<option value="">' . esc_html__('All conditions must be filled', 'wutb') . '</option>'
                . '<option value="OR">' . esc_html__('One of the conditions must be filled', 'wutb') . '</option>'
                . '</select>'
                . '<a href="javascript:" data-action="addConditionInteraction" class="btn btn-primary" ><span class="fas fa-plus" ></span > ' . esc_html__('Add a condition', 'wutb') . ' </a></p> ';
        $tourContent .= '<table id="wutb_conditionsTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>' . esc_html__('Element', 'wutb') . '</th>
                        <th>' . esc_html__('Condition', 'wutb') . '</th>
                        <th>' . esc_html__('Value', 'wutb') . '</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
              </table>';

        $tourContent .= '<div class="row" ><div class="col-md-12" ><p style="padding-left: 16px;padding-right: 16px; text-align: center;" id="wutb_conditionsBtnsContainer">'
                . '   <a href="javascript:" id="wutb_conditionsSaveBtn" class="btn btn-primary" style="margin-top: 24px; margin-right: 8px;" ><span class="fas fa-check" ></span > ' . esc_html__('Apply', 'wutb') . ' </a>
              <a href="javascript:" id="wutb_conditionsDelBtn" class="btn btn-danger" style="margin-top: 24px;" ><span class="fas fa-trash-alt" ></span > ' . esc_html__('Delete', 'wutb') . ' </a ></p ></div></div> ';

        $tourContent .= '<div class="clearfix"></div>';
        $tourContent .= '</div> '; // eof row
        $tourContent .= '</div> '; // eof wutb_linkInteractions
        $tourContent .= '</div> '; // eof wutb_container

        $tourContent .= '</div> '; //eof wutb_winConditions

        $tourContent .= '<div id="wutb_winLicense" class="modal fade">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                      <h5 class="modal-title"><span class="fas fa-key"></span>' . esc_html__('Purchase code', 'wutb') . '</h5>                        
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                       <p class="text-center">
                        '.esc_html__('Fill your purchase code to activate the automatic updates','wutb').'
                        </p>
                        <div class="form-group">
                            <label>' . esc_html__('Purchase code', 'wutb') . '</label>
                            <input type="text" class="form-control" name="purchaseCode" value="'.$settings->purchaseCode.'"/>
                        </div>
                        <div class="alert alert-info text-center">
                            <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">' . esc_html__('Need help to find your purchase code ?', 'wutb') . '</a>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <a href="javascript:" class="btn btn-primary" data-action="verifyLicense"><span class="fas fa-check"></span>' . esc_html__('Validate', 'wutb') . '</a>
                        <a href="javascript:" class="btn btn-secondary" data-dismiss="modal"><span class="fas fa-times"></span>' . esc_html__('Cancel', 'wutb') . '</a>
                      </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->';

        $tourContent .= '<div id="wutb_winExport" class="modal fade">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                      <h5 class="modal-title"><span class="fas fa-download"></span>' . esc_html__('Export the tours', 'wutb') . '</h5>                        
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="text-center"><a href="admin.php?page=wutb_menu&wutb_action=exportTours" target="_blank" class="btn btn-primary btn-lg" id="wutb_exportLink"><span class="fas fa-save"></span>' . esc_html__('Download the exported tours', 'wutb') . '</a></div>
                      </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->';

        $tourContent .= '<div id="wutb_winImport" class="modal fade">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title"><span class="fas fa-upload"></span>' . esc_html__('Import tours', 'wutb') . '</h5>                        
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                               <div class="alert alert-danger text-center"><p>' . esc_html__('Be carreful : all existing tours and steps will be erased and replaced by the imported data', 'wutb') . '</p></div>
                                   <form id="wutb_winImportForm" method="post" enctype="multipart/form-data">
                                       <div class="form-group">
                                        <input type="hidden" name="action" value="wutb_importTours"/>
                                        <label>' . esc_html__('Select the .json data file', 'wutb') . '</label><input name="importFile" type="file" class="" />
                                       </div>
                                  </form>
                              </div>
                              <div class="modal-footer">
                                <a href="javascript:" class="btn btn-secondary" data-dismiss="modal"><span class="fas fa-times"></span>' . esc_html__('Cancel', 'wutb') . '</a>
                                <a href="javascript:" class="btn btn-primary" data-action="importToursJson"><span class="fas fa-save"></span>' . esc_html__('Import', 'lfb') . '</a>
                            </div>
                            </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->';

        $tourContent .= '<div id="wutb_winEditButton" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">' . esc_html__('Edit a button', 'wutb') . '</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label>' . esc_html__('Title', 'wutb') . '</label>
            <input type="text" name="title" class="form-control" />
        </div>
       <div class="form-group">
       <label>' . esc_html__('Background color', 'wutb') . '</label>
       <input class="form-control" type="text" name="backgroundColor" data-colorpicker>
       </div>
       <div class="form-group">
       <label>' . esc_html__('Text color', 'wutb') . '</label>
       <input class="form-control" type="text" name="textColor" data-colorpicker>
       </div>
        
        <div class="form-group">
            <label>' . esc_html__('Icon', 'wutb') . '</label>
            <div class="input-group">
                <input name="icon" type="text" class="form-control" />
                <div class="input-group-append"><a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank" class="btn btn-circle btn-secondary"><span class="fas fa-eye-dropper"></span></a></div>
            </div>
        </div>
        <div class="form-group">
            <label>' . esc_html__('Action', 'wutb') . '</label>
            <select name="action" class="form-control">
                <option value="nextStep">' . esc_html__('Go to next step', 'wutb') . '</option>
                <option value="stopTour">' . esc_html__('Stop the tour', 'wutb') . '</option>
            </select>
        </div>
        <div class="form-group">
            <label>' . esc_html__('Final page URL', 'wutb') . '</label>
            <input type="text" name="finalPage" class="form-control" />
        </div>
      </div>
      <div class="modal-footer">
        <a href="javascript:" data-action="saveButton" class="btn btn-primary"><span class="fas fa-check"></span>' . esc_html__('Apply', 'wutb') . '</a>
        <a href="javascript:" data-action="cancelButton" class="btn btn-secondary" data-dismiss="modal"><span class="fas fa-times"></span>' . esc_html__('Cancel', 'wutb') . '</a>
      </div>
    </div>
  </div>
</div>';
        $tourContent .= '</div>'; // eof .wutb_pageBody
        $tourContent .= '</div>';
        echo $tourContent;
    }

    public function createTour() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $preset = sanitize_text_field($_POST['preset']);
            $tourData = new stdClass();
            $tourData->steps = array();
            $tourData->links = array();
            $tourData->settings = new stdClass();
            $tourData->settings->activated = false;
            $tourData->settings->title = esc_html__('My tour', 'wutb');
            $tourData->settings->runOnce = false;
            $tourData->settings->startURL = '';
            $tourData->settings->devices = '';
            $tourData->settings->startMethod = '';
            $tourData->settings->allowedRoles = '';
            $tourData->settings->tourDomElement = '';
            $tourData->settings->showNavbar = false;
            $tourData->settings->navbarPosition = 'bottomLeft';
            $tourData->settings->navbarColor = '#000000';
            $tourData->settings->navbarOpacity = 0.8;
            $tourData->settings->navbarBtnsColor = '#1abc9c';

            $tourData->settings->texts_font = 'Lato';
            $tourData->settings->texts_textColor = '#fcfcfc';
            $tourData->settings->texts_textSize = 34;
            $tourData->settings->texts_headerTextSize = 56;
            $tourData->settings->texts_headerTextColor = '#1abc9c';

            $tourData->settings->overlayOpacity = 0.7;
            $tourData->settings->overlayColor = '#000000';

            $tourData->settings->tooltip_font = 'Lato';
            $tourData->settings->tooltip_textColor = '#ffffff';
            $tourData->settings->tooltip_backgroundColor = '#1abc9c';
            $tourData->settings->tooltip_textSize = 18;

            $tourData->settings->arrow_font = 'Lato';
            $tourData->settings->arrow_textColor = '#ffffff';
            $tourData->settings->arrow_textSize = 28;

            $tourData->settings->dialog_font = 'Lato';
            $tourData->settings->dialog_textColor = '#7f8c8d';
            $tourData->settings->dialog_backgroundColor = '#dfdfdf';
            $tourData->settings->dialog_headerColor = '#1abc9c';
            $tourData->settings->dialog_headerTextColor = '#ffffff';
            $tourData->settings->dialog_footerColor = '#ccc';
            $tourData->settings->dialog_textSize = 16;

            $tourData->settings->navbar_txtStopTour = esc_html__('Stop the tour', 'wutb');
            $tourData->settings->navbar_txtNextStep = esc_html__('Go to the next step', 'wutb');
            $tourData->settings->navbar_txtPreviousStep = esc_html__('Return to the previous step', 'wutb');
            $tourData->settings->navbar_tooltipColor = '#1abc9c';
            $tourData->settings->navbar_tooltipTextColor = '#ffffff';

            $tourData->indexID = 0;

            $table_name = $wpdb->prefix . "wutb_tours";
            $wpdb->insert($table_name, array('title' => esc_html__('My new tour', 'wutb'), 'tourData' => json_encode($tourData)));
            $tourID = $wpdb->insert_id;

            $tourData->id = $tourID;
            $wpdb->update($table_name, array('tourData' => json_encode($tourData)), array('id' => $tourID));
            echo intval($tourID);
            die();
        }
    }

    public function editTour() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $tourID = sanitize_text_field($_POST['tourID']);                                
            $tour = $wpdb->get_results($wpdb->prepare("SELECT tourData,id FROM {$wpdb->prefix}wutb_tours WHERE id=%s LIMIT 1", $tourID));

            if (count($tour) > 0) {
                $tour = $tour[0];
                echo ($tour->tourData);
            }
            die();
        }
    }

    public function deleteTour() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $tourID = sanitize_text_field($_POST['tourID']);
            $table_name = $wpdb->prefix . "wutb_tours";
            $wpdb->delete($table_name, array('id' => $tourID));
        }
    }

    public function saveTour() {
        global $wpdb;
        if (current_user_can('manage_options')) {
            $tourID = sanitize_text_field($_POST['tourID']);
            $tourData = stripslashes($_POST['tourData']);
            $tourDataObj = json_decode($tourData);
            $table_name = $wpdb->prefix . "wutb_tours";
            $wpdb->update($table_name, array('tourData' => ($tourData), 'title' => $tourDataObj->settings->title), array('id' => $tourID));

            $tourObj = new stdClass();
            $tourDataObj = json_decode($tourData);
            $tourObj->id = $tourID;
            $tourObj->title = $tourData->title;
            $tourObj->tourData = $tourData;
            session_start();
            $_SESSION['wutb_previewData'] = ($tourObj);

            die();
        }
    }

    function previewTourByID() {
        global $wpdb;
        if (current_user_can('manage_options')) {
            $tourID = sanitize_text_field($_POST['tourID']);

            $tour = $wpdb->get_results($wpdb->prepare("SELECT tourData,id FROM {$wpdb->prefix}wutb_tours WHERE id=%s LIMIT 1", $tourID));
            if (count($tour) > 0) {
                $tour = $tour[0];
                session_start();
                $_SESSION['wutb_previewData'] = ($tour);
                $tourDataObj = json_decode($tour->tourData);
                
                echo esc_url($tourDataObj->startURL);
            }

            die();
        }
    }

    function checkAutomaticUpdates() {
        $settings = $this->getSettings();
        if ($settings && $settings->purchaseCode != "") {
            require_once('wutb-plugin_update_check.php');
            $updateCheckerWutb = new PluginUpdateChecker_2_0(
                    'https://kernl.us/api/v1/updates/5d1a099ccc015004c8ab0c95/', $this->parent->file, 'wutb', 1
            );
            $updateCheckerWutb->purchaseCode = $settings->purchaseCode;
        }
    }

    function previewTour() {
        global $wpdb;
        if (current_user_can('manage_options')) {
            $tourID = sanitize_text_field($_POST['tourID']);
            $tourData = stripslashes($_POST['tourData']);

            $tourObj = new stdClass();
            $tourObj->id = $tourID;
            $tourObj->title = $tourData->title;
            $tourObj->tourData = $tourData;

            session_start();
            $_SESSION['wutb_previewData'] = ($tourObj);

            die();
        }
    }

    public function duplicateTour() {
        global $wpdb;
        if (current_user_can('manage_options')) {
            $tourID = sanitize_text_field($_POST['tourID']);
            $tour = $wpdb->get_results($wpdb->prepare("SELECT tourData,id FROM {$wpdb->prefix}wutb_tours WHERE id=%s LIMIT 1",$tourID));
            if (count($tour) > 0) {
                $tour = $tour[0];
                unset($tour->id);
                $tourData = json_decode($tour->tourData);
                $tourData->settings->title .= ' (1)';
                $wpdb->insert($wpdb->prefix . "wutb_tours", array('title' => $tourData->settings->title, 'tourData' => json_encode($tourData)));
                $newTourID = $wpdb->insert_id;
                
                $tourData->id = $newTourID;
                foreach ($tourData->steps as $step) {
                    $step->tourID = $newTourID;
                    $step->settings->tourID = $newTourID;
                }
                $wpdb->update($wpdb->prefix.'wutb_tours', array('tourData' => json_encode($tourData)), array('id' => $newTourID));
                echo intval($newTourID);
            }
        }
        die();
    }


    public function importTours() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $settings = $this->getSettings();
            if (isset($_FILES['importFile'])) {
                $error = false;
                if (!is_dir(plugin_dir_path(__FILE__) . '../tmp')) {
                    mkdir(plugin_dir_path(__FILE__) . '../tmp');
                    chmod(plugin_dir_path(__FILE__) . '../tmp', $this->parent->chmodWrite);
                }
                $jsonfilename = 'export_tours.json';
                $target_path = plugin_dir_path(__FILE__) . '../tmp/' . $jsonfilename;

                if (@move_uploaded_file($_FILES['importFile']['tmp_name'], $target_path)) {
                    $toursData = array();

                    $file = file_get_contents(plugin_dir_path(__FILE__) . '../tmp/' . $jsonfilename);
                    $dataJson = json_decode($file, true);

                    $table_name = $wpdb->prefix . "wutb_tours";
                    $wpdb->query("TRUNCATE TABLE $table_name");

                    if (is_array($dataJson['settings']) && array_key_exists('colorA', $dataJson['settings'][0])) {

                        foreach ($dataJson['steps'] as $tour) {

                            $tourData = new stdClass();
                            $tourData->steps = array();
                            $tourData->links = array();
                            $tourData->settings = new stdClass();
                            $tourData->settings->title = $tour['title'];
                            $tourData->settings->runOnce = $tour['onceTime'];
                            $tourData->settings->startURL = str_replace(esc_url(get_home_url()), '', $tour['page']);
                            $tourData->settings->devices = '';
                            if ($tour['onlyMobile']) {
                                $tourData->settings->devices = 'mobiles';
                            } else if ($tour['mobileEnabled'] == 0) {
                                $tourData->settings->devices = 'computers';
                            }
                            $tourData->settings->startMethod = '';
                            if ($tour['start'] == 'click') {
                                $tourData->settings->startMethod = 'click';
                            }
                            $tourData->settings->allowedRoles = $tour->rolesAllowed;
                            $tourData->settings->tourDomElement = $tour->domElement;
                            $tourData->settings->showNavbar = $tour->closeHelperBtn;
                            $tourData->settings->navbarPosition = 'bottomLeft';

                            $tourData->settings->texts_font = 'Lato';
                            $tourData->settings->texts_textColor = $dataJson['settings'][0]->colorC;
                            $tourData->settings->texts_textSize = 34;
                            $tourData->settings->texts_headerTextSize = 56;

                            $tourData->settings->overlayOpacity = 0.7;
                            $tourData->settings->overlayColor = '#000000';

                            $tourData->settings->tooltip_font = 'Lato';
                            $tourData->settings->tooltip_textColor = '#ffffff';
                            $tourData->settings->tooltip_backgroundColor = $dataJson['settings'][0]->colorA;
                            $tourData->settings->tooltip_textSize = 18;

                            $tourData->settings->arrow_font = 'Lato';
                            $tourData->settings->arrow_textColor = '#ffffff';
                            $tourData->settings->arrow_textSize = 28;

                            $tourData->settings->dialog_font = 'Lato';
                            $tourData->settings->dialog_textColor = '#ffffff';
                            $tourData->settings->dialog_backgroundColor = '#dfdfdf';
                            $tourData->settings->dialog_headerColor = $dataJson['settings'][0]->colorA;
                            $tourData->settings->dialog_headerTextColor = '#ffffff';
                            $tourData->settings->dialog_footerColor = '#ccc';
                            $tourData->settings->dialog_textSize = 16;

                            $tourData->settings->navbar_txtStopTour = esc_html__('Stop the tour', 'wutb');
                            $tourData->settings->navbar_txtNextStep = esc_html__('Go to the next step', 'wutb');
                            $tourData->settings->navbar_txtPreviousStep = esc_html__('Return to the previous step', 'wutb');

                            $tourData->indexID = 0;

                            foreach ($dataJson['items'] as $step) {
                                if ($step['stepID'] == $tour['id']) {
                                    if ($step['id'] > $toursData->indexID) {
                                        $toursData->indexID = $step['id'];
                                    }
                                }
                            }
                            $toursData->indexID += 1;

                            $table_name = $wpdb->prefix . "wutb_tours";
                            $wpdb->insert($table_name, array('title' => $tour['title'], 'tourData' => json_encode($tourData)));
                            $tourID = $wpdb->insert_id;

                            $tourData->id = $tourID;
                            $wpdb->update($table_name, array('tourData' => json_encode($tourData)), array('id' => $tourID));

                            foreach ($dataJson['items'] as $step) {
                                if ($step['stepID'] == $tour['id']) {
                                    if ($step['ordersort'] == '0') {
                                        $step['ordersort'] = $step['id'];
                                    }
                                    $stepData = new stdClass();
                                    $stepData->id = $step['id'];
                                    $stepData->tourID = $step['stepID'];
                                    $stepData->buttons = array();
                                    $stepData->start = 0;

                                    $stepData->position = [140 + intval($step['ordersort']) * 180, 80];
                                    $stepData->settings = new stdClass();
                                    $stepData->settings->title = $step['title'];
                                    $type = 'text';
                                    $textStyle = 'arrow';
                                    $textSize = 34;
                                    $textColor = $tourData->settings->texts_textColor;
                                    $backgroundColor = $tourData->settings->tooltip_backgroundColor;
                                    if ($step['type'] == 'tooltip') {
                                        $type = 'showElement';
                                        $textStyle = 'tooltip';
                                        $textColor = $tourData->settings->tooltip_textColor;
                                        $textSize = $tourData->settings->tooltip_textSize;
                                    } else if ($step['type'] == 'dialog') {
                                        $type = 'dialog';
                                        $textColor = $tourData->settings->dialog_textColor;
                                        $textSize = $tourData->settings->dialog_textSize;
                                        $backgroundColor = $tourData->settings->dialog_backgroundColor;
                                    }
                                    $stepData->settings->type = $type;
                                    $stepData->settings->textStyle = $textStyle;
                                    $stepData->settings->title = $step['title'];
                                    $stepData->settings->headerText = $step['title'];
                                    $stepData->settings->textColor = $textColor;
                                    $stepData->settings->textSize = $textSize;
                                    $stepData->settings->position = 'down';
                                    $continueAction = '';
                                    if ($step->actionNeeded == 'click') {
                                        $stepData->settings->continueAction = 'click';
                                    }
                                    $stepData->settings->continueDelay = $step['delay'];
                                    $stepData->settings->useOverlay = $step['overlay'];
                                    $stepData->settings->overlayOpacity = $tourData->settings->overlayOpacity;
                                    $stepData->settings->overlayColor = $tourData->settings->overlayColor;
                                    $stepData->settings->startDelay = $step['delayStart'];
                                    $stepData->settings->backgroundColor = $backgroundColor;
                                    $stepData->settings->headerColor = $tourData->settings->dialog_headerColor;
                                    $stepData->settings->headerTextColor = $tourData->settings->dialog_headerTextColor;
                                    $stepData->settings->footerColor = $tourData->settings->dialog_footerColor;
                                    $stepData->settings->animation = 'bounce';
                                    $stepData->settings->entryAnimation = 'fadeIn';
                                    $stepData->settings->url = $step['page'];
                                    $stepData->settings->codeJS = '';
                                    $stepData->settings->text = $step['content'];

                                    if ($stepData->settings->type == 'dialog') {
                                        $btnData = new stdClass();
                                        $btnData->id = 100 + $stepData->id;
                                        $btnData->title = $step['btnContinue'];
                                        $btnData->icon = '';
                                        $btnData->finalPage = '';
                                        $btnData->action = 'nextStep';
                                        $btnData->backgroundColor = $dataJson['settings'][0]->colorA;
                                        $btnData->textColor = '#ffffff';
                                        $stepData->buttons[] = $btnData;
                                        if ($tour['btnStop'] != '') {
                                            $btnData = new stdClass();
                                            $btnData->id = 100 + $stepData->id;
                                            $btnData->title = $step['btnStop'];
                                            $btnData->icon = '';
                                            $btnData->finalPage = '';
                                            $btnData->action = 'stopTour';
                                            $btnData->backgroundColor = $dataJson['settings'][0]->colorB;
                                            $btnData->textColor = '#ffffff';
                                            $stepData->buttons[] = $btnData;
                                        }
                                    }
                                    $tourData->steps[] = $stepData;
                                }
                            }
                            usort($tourData->steps, array($this, 'sortStepsArray'));
                            $i = 0;
                            foreach ($tourData->steps as $step) {
                                if ($i == 0) {
                                    $step->start = 1;
                                }
                                if ($i < count($tourData->steps) - 1) {
                                    $linkData = new stdClass();
                                    $linkData->originID = $step->id;
                                    $linkData->destinationID = $tourData->steps[i + 1]->id;
                                    $linkData->conditions = array();
                                    $tourData->links[] = $linkData;
                                }
                                $i++;
                            }
                            $wpdb->update($table_name, array('tourData' => json_encode($tourData)), array('id' => $tourID));
                        }
                    } else {
                        $table_name = $wpdb->prefix . "wutb_settings";
                        foreach ($dataJson['settings'] as $key => $value) {
                            if ($value['id'] == 1) {
                                foreach ($value as $keyV => $valueV) {
                                    if ($keyV != 'id' && $keyV != 'purchaseCode' && $keyV != 'version') {
                                        $wpdb->update($table_name, array($keyV => $valueV), array('id' => 1));
                                    }
                                }
                            }
                        }
                        $table_name = $wpdb->prefix . "wutb_tours";
                        $wpdb->query("TRUNCATE TABLE $table_name");
                        if (array_key_exists('tours', $dataJson)) {
                            foreach ($dataJson['tours'] as $key => $value) {
                                $wpdb->insert($table_name, $value);
                            }
                        }
                    }
                }
            }
            if ($error) {
                echo esc_html__('An error occurred during the transfer', 'wutb');
                die();
            } else {
                echo 1;
                die();
            }
        }
    }

    private function sortStepsArray($a, $b) {
        $diff = $a->ordersort - $b->ordersort;
        return ($diff !== 0) ? $diff : $a->id - $b->id;
    }

    public function exportTours() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            if (!is_dir(plugin_dir_path(__FILE__) . '../tmp')) {
                mkdir(plugin_dir_path(__FILE__) . '../tmp');
                chmod(plugin_dir_path(__FILE__) . '../tmp', $this->parent->chmodWrite);
            }

            $jsonExport = array();
            $table_name = $wpdb->prefix . "wutb_settings";
            $settings = $this->getSettings();
            $settings->purchaseCode = "";

            $jsonExport['settings'] = array();
            $jsonExport['settings'][] = $settings;

            $tours = array();            
           
            foreach ($wpdb->get_results("SELECT * FROM {$wpdb->prefix}wutb_tours") as $key => $row) {
                $tours[] = $row;
            }
            $jsonExport['tours'] = $tours;

            $fp = fopen(plugin_dir_path(__FILE__) . '../tmp/export_tours.json', 'w');
            fwrite($fp, json_encode($jsonExport));
            fclose($fp);

            echo '1';
            die();
        }
    }

    public function verifyPurchaseCode() {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $purchaseCode = sanitize_text_field($_POST['purchaseCode']);        
            if(strlen($purchaseCode)<20 || strlen($purchaseCode) >40){                
                $table_name = $wpdb->prefix . "wutb_settings";
                $wpdb->update($table_name, array('purchaseCode' => ''), array('id' => 1));
                echo '1';
            }else{
            try {

                $url = 'https://www.loopus-plugins.com/updates/update.php?checkCode=24096901&code=' . $purchaseCode;
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $rep = curl_exec($ch);
                if ($rep != '0410') {
                    $table_name = $wpdb->prefix . "wutb_settings";
                    $wpdb->update($table_name, array('purchaseCode' => $purchaseCode), array('id' => 1));
                } else {
                    $table_name = $wpdb->prefix . "wutb_settings";
                    $wpdb->update($table_name, array('purchaseCode' => ''), array('id' => 1));
                    echo '1';
                }
            } catch (Throwable $t) {
                $table_name = $wpdb->prefix . "wutb_settings";
                $wpdb->update($table_name, array('purchaseCode' => $purchaseCode), array('id' => 1));
            } catch (Exception $e) {
                $table_name = $wpdb->prefix . "wutb_settings";
                $wpdb->update($table_name, array('purchaseCode' =>$purchaseCode), array('id' => 1));
            }
            }
            die();
        }
    }

    /**
     * Main Instance
     *
     *
     * @since 1.0.0
     * @static
     * @return Main instance
     */
    public static function instance($parent) {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($parent);
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
        _doing_it_wrong(__FUNCTION__, '', $this->parent->_version);
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
}
