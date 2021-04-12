<?php


class WooFood_Settings
{
/**
* Holds the values to be used in the fields callbacks
*/
private $options_woofood;
private $options_woofood_delivery_hours;
private $options_woofood_push_notifications;
private $options_woofood_pickup_hours;


/**
* Start up
*/

public function __construct()
{
    add_action( 'admin_menu', array( $this, 'add_plugin_page' ), 1 );
    add_action( 'admin_init', array( $this, 'page_init' ) );

}

/**
* Add options page
*/
public function add_plugin_page()
{




      add_menu_page('Easy Delivery', 'Easy Delivery', 'manage_woocommerce', 'woofood-options', array( $this, 'create_admin_page' ), plugin_dir_url( __FILE__ ) .'/icons/foodmaster-logo.png' );
      add_submenu_page( 'woofood-options', esc_html__('Settings', 'woofood-plugin'), esc_html__('Settings', 'woofood-plugin'), 'manage_woocommerce','woofood-options');

      add_submenu_page( 'woofood-options', esc_html__('Orders', 'woofood-plugin'), esc_html__('Orders', 'woofood-plugin'), 'manage_woocommerce', 'woofood-orders', array( $this, 'woofood_orders' ));

      //add_submenu_page( 'woofood-options', esc_html__('Extra Options', 'woofood-plugin'), esc_html__('Extra Options', 'woofood-plugin'), 'manage_woocommerce', 'edit.php?post_type=extra_option');
     // add_submenu_page( 'woofood-options', esc_html__('Extra Option Categories', 'woofood-plugin'), esc_html__('Extra Option Categories', 'woofood-plugin'), 'manage_woocommerce', 'edit-tags.php?taxonomy=extra_option_categories');

     add_submenu_page( 'woofood-options', esc_html__('Extra Options Management', 'woofood-plugin'), esc_html__('Extra Options Management', 'woofood-plugin'), 'manage_woocommerce','extra-management', array( $this, 'woofood_extra_options_page' ));

      add_submenu_page( 'woofood-options', esc_html__('Delivery Hours', 'woofood-plugin'), esc_html__('Delivery Hours', 'woofood-plugin'), 'manage_woocommerce', 'delivery-hours', array( $this, 'woofood_open_hours' ));

      add_submenu_page( 'woofood-options', esc_html__('Pickup Hours', 'woofood-plugin'), esc_html__('Pickup Hours', 'woofood-plugin'), 'manage_woocommerce', 'pickup-hours', array( $this, 'woofood_open_pickup_hours' ));

      //add_submenu_page( 'woofood-options', esc_html__('Push Notifications(Mobile)', 'woofood-plugin'), esc_html__('Push Notifications', 'woofood-plugin'), 'manage_woocommerce', 'push-notifications', array( $this, 'woofood_push_notifications' ));



}


function wf_load_admin_css() {
          $woofood_plugin_rtl = woofood_plugin_is_rtl();

  wp_enqueue_style( 'woofood_css_admin', plugin_dir_url( __FILE__ ) . 'css/admin'.$woofood_plugin_rtl.'.css', array(), '1.0.0', 'all' );

}

/**
* Options page callback
*/
public function create_admin_page()
{
// Set class property
    $this->options_woofood = get_option( 'woofood_options' );

    $google_api_key = isset($this->options_woofood['woofood_google_api_key']) ? $this->options_woofood['woofood_google_api_key'] : null;
    if( $google_api_key)
    {
        wp_enqueue_script('google-js-api', 'https://maps.googleapis.com/maps/api/js?libraries=places,drawing,geometry&key='.$google_api_key.'&language='.substr(get_bloginfo ( 'language' ), 0, 2).'');
    wp_enqueue_script(  'woofood_js_google', plugin_dir_url( __FILE__ ) . 'js/autocomplete_address.js' , array(), '1.0.0', 'all' );

    }

      wp_enqueue_style( 'woofood_settings_css_admin', plugin_dir_url( __FILE__ ) . 'css/adminsettings.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );
  wp_enqueue_script('woofood_settings_js_admin', plugin_dir_url(__FILE__).'js/adminsettings.js', array(), WOOFOOD_PLUGIN_VERSION, 'all');






    ?>
            <?php settings_errors($this); ?>

            

    <div class="wrap">
     <h2><?php esc_html_e('WooFood Settings', 'woofood-plugin'); ?></h2>  


       
        <?php if( isset($_GET['settings-updated']) ) { ?>
        <div id="message" class="updated">
            <p><strong><?php _e('Settings Updated.', 'woofood-plugin') ?></strong></p>
        </div>
        <?php } ?>


    
<?php
  $tabs = array( 'delivery' => __('Delivery', 'woofood-plugin'), 'pickup' => __('Pickup', 'woofood-plugin'),'distance' => __('Distance Restrictions', 'woofood-plugin'), 'accept-decline' => __('Accept/Decline Orders', 'woofood-plugin'),  'force_disable' => __('Force Disable', 'woofood-plugin'),    );
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<nav class="nav-tab-wrapper woo-nav-tab-wrapper">';

    if(isset($_GET['tab']))
          {
            $current = $_GET['tab'];

          }
          else
          {
            $current = "license";
          }
    foreach( $tabs as $tab => $name ){

        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab $class' href='?page=woofood-options&tab=$tab'>$name</a>";

    }
    echo '</nav>';
?>

        <form method="post" action="options.php">
            <?php
              
              if(isset($_GET['tab']) && $_GET['tab'] =="delivery" || !isset($_GET['tab'])) :

            
                settings_fields( 'woofood_settings_group_options' );
                do_settings_sections( 'woofood_settings_delivery_page' );


                 elseif($_GET['tab'] =="ajax") :

            
                settings_fields( 'woofood_settings_group_options' );
                do_settings_sections( 'woofood_settings_ajax_page' );


                elseif($_GET['tab'] =="delivery") :

            
                settings_fields( 'woofood_settings_group_options' );
                do_settings_sections( 'woofood_settings_delivery_page' );


                 elseif($_GET['tab'] =="pickup") :

            
                settings_fields( 'woofood_settings_group_options' );
                do_settings_sections( 'woofood_settings_pickup_page' );


                 elseif($_GET['tab'] =="checkout") :

            
                settings_fields( 'woofood_settings_group_options' );
                do_settings_sections( 'woofood_settings_checkout_page' );

                elseif($_GET['tab'] =="distance") :

            
                settings_fields( 'woofood_settings_group_options' );
                do_settings_sections( 'woofood_settings_distance_page' );

                elseif($_GET['tab'] =="accept-decline") :

            
                settings_fields( 'woofood_settings_group_options' );
                do_settings_sections( 'woofood_settings_accept_decline_page' );


                  elseif($_GET['tab'] =="force_disable") :

            
                settings_fields( 'woofood_settings_group_options' );
                do_settings_sections( 'woofood_settings_force_disable_page' );


                 elseif($_GET['tab'] =="delivery-boys") :

            
                settings_fields( 'woofood_settings_group_options' );
                do_settings_sections( 'woofood_settings_delivery_boys_page' );
                
                  elseif($_GET['tab'] =="faq") :

            
                settings_fields( 'woofood_settings_group_options' );
                do_settings_sections( 'woofood_settings_faq_page' );


                 elseif($_GET['tab'] =="shortcodes") :

            
                settings_fields( 'woofood_settings_group_options' );
                do_settings_sections( 'woofood_settings_shortcodes_page' );


                     elseif($_GET['tab'] =="tweaks") :

            
                settings_fields( 'woofood_settings_group_options' );
                do_settings_sections( 'woofood_settings_tweaks_page' );


                 elseif($_GET['tab'] =="availability-checker") :

            
                settings_fields( 'woofood_settings_group_options' );
                do_settings_sections( 'woofood_settings_availability_checker_page' );


                  endif;
           if(!isset($_GET['tab']) || $_GET['tab'] !="shortcodes" && $_GET['tab'] !="faq" ) :
           
            submit_button(); 
            endif;
            ?>
        </form>






    </div><!--#wrap -->
    <?php


}




public function woofood_open_hours()
{
            $woofood_plugin_rtl = woofood_plugin_is_rtl();

// Set class property
    $this->options_woofood_delivery_hours = get_option( 'woofood_options_delivery_hours' );
  wp_enqueue_style( 'woofood_css_admin', plugin_dir_url( __FILE__ ) . 'css/admin'.$woofood_plugin_rtl.'.css', array(), '1.0.0', 'all' );
    wp_enqueue_style( 'woofood_css_admin_time_picker', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker'.$woofood_plugin_rtl.'.css', array(), '1.0.0', 'all' );
wp_enqueue_script('woofood_js_admin_time_picker', plugin_dir_url(__FILE__).'js/jquery.timepicker.min.js', array());
wp_enqueue_script('woofood_js_admin_delivery_hours', plugin_dir_url(__FILE__).'js/delivery_hours.js', array());



    ?>
    <div class="wrap">
      <h2><?php _e('Delivery  Hours', 'woofood-plugin'); ?></h2>  


       
        <?php if( isset($_GET['settings-updated']) ) { ?>
        <div id="message" class="updated">
            <p><strong><?php _e('Settings Updated.', 'woofood-plugin') ?></strong></p>
        </div>
        <?php } ?>

        <?php settings_errors($this); ?>
    


        <form method="post" action="options.php">
            <?php
            
                settings_fields( 'woofood_settings_delivery_hours' );
                do_settings_sections( 'woofood_settings_delivery_hours_page' );
           
            submit_button(); 
            ?>
        </form>






    </div><!--#wrap -->
    <?php

}

public function woofood_open_pickup_hours()
{
    $woofood_plugin_rtl = woofood_plugin_is_rtl();

// Set class property
    $this->options_woofood_pickup_hours = get_option( 'woofood_options_pickup_hours' );
  wp_enqueue_style( 'woofood_css_admin', plugin_dir_url( __FILE__ ) . 'css/admin'.$woofood_plugin_rtl.'.css', array(), '1.0.0', 'all' );
    wp_enqueue_style( 'woofood_css_admin_time_picker', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker'.$woofood_plugin_rtl.'.css', array(), '1.0.0', 'all' );
wp_enqueue_script('woofood_js_admin_time_picker', plugin_dir_url(__FILE__).'js/jquery.timepicker.min.js', array());
wp_enqueue_script('woofood_js_admin_delivery_hours', plugin_dir_url(__FILE__).'js/delivery_hours.js', array());



    ?>
    <div class="wrap">
      <h2><?php _e('Pickup  Hours', 'woofood-plugin'); ?></h2>  


       
        <?php if( isset($_GET['settings-updated']) ) { ?>
        <div id="message" class="updated">
            <p><strong><?php _e('Settings Updated.', 'woofood-plugin') ?></strong></p>
        </div>
        <?php } ?>

        <?php settings_errors($this); ?>
    


        <form method="post" action="options.php">
            <?php
            
                settings_fields( 'woofood_settings_pickup_hours' );
                do_settings_sections( 'woofood_settings_pickup_hours_page' );
           
            submit_button(); 
            ?>
        </form>






    </div><!--#wrap -->
    <?php

}



public function woofood_extra_options_page()
{
      $woofood_plugin_rtl = woofood_plugin_is_rtl();

// Set class property
  wp_enqueue_style('select2', plugin_dir_url( __FILE__ ) . 'css/select2.min'.$woofood_plugin_rtl.'.css' );
  wp_enqueue_script('select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array('jquery') );

  wp_enqueue_style( 'woofood_css_admin', plugin_dir_url( __FILE__ ) . 'css/admin'.$woofood_plugin_rtl.'.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );
  wp_enqueue_script('woofood_js_admin_extra_management', plugin_dir_url(__FILE__).'js/admin_extra_management.js', array(), WOOFOOD_PLUGIN_VERSION, 'all');

    wp_localize_script('woofood_js_admin_extra_management', 'woofoodextramng', array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
          ));

?>
    <div class="wrap">
      <div class="woofood-overlay">
        <div class="woofood-loading-content">
        <?php esc_html_e('Loading...', 'woofood-plugin'); ?>
          </div>
        </div>
      <h2><?php _e('Exra Options Management', 'woofood-plugin'); ?></h2>  
      <div class="wf_extra_options_management">
      <div class="wf_extra_options_management_header">
        <a class="wf_add_extra_option_category_btn button"><?php esc_html_e('Add New Extra Option Category', 'woofood-plugin') ?></a>
</div>
       <div class="wf_extra_options_sidebar">
       </div> 

        <div class="wf_extra_options_content">
      
          <?php woofood_extra_option_categories_list(); ?>
     

 </div> 


</div>
<div class="wf_extra_option_edit_popup">

  

</div>



    </div><!--#wrap -->
    <?php
    wp_enqueue_script( 'jquery-ui-sortable' );

}






public function woofood_push_notifications()
{
        $woofood_plugin_rtl = woofood_plugin_is_rtl();


// Set class property
    $this->options_woofood_push_notifications = get_option( 'woofood_options_push_notifications' );
  wp_enqueue_style( 'woofood_css_admin', plugin_dir_url( __FILE__ ) . 'css/admin'.$woofood_plugin_rtl.'.css', array(), '1.0.0', 'all' );
wp_enqueue_script('woofood_js_admin_push_notification', plugin_dir_url(__FILE__).'js/pushnotification.js', array());
  wp_localize_script('woofood_js_admin_push_notification', 'wfpush', array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
          ));



    ?>
    <div class="wrap">
      <h2><?php _e('Push Notifications', 'woofood-plugin'); ?></h2>  


       
        <?php if( isset($_GET['settings-updated']) ) { ?>
        <div id="message" class="updated">
            <p><strong><?php _e('Settings Updated.', 'woofood-plugin') ?></strong></p>
        </div>
        <?php } ?>

        <?php settings_errors($this); ?>
    


        <form method="post" action="options.php">
            <?php


            
                settings_fields( 'woofood_settings_push_notifications' );
               do_settings_sections( 'woofood_settings_push_notifications_page' );
           
           submit_button(); 
            ?>
            <hr/>


        </form>


<form method="post" action="" id="woofood_push_notifications_form" >
<input type="hidden" name="action" value="woofood_push_notification_send"/>
<input type="text" name="woofood_push_title" placeholder="<?php _e('Push Title','woofood-plugin');?>"/>
<input type="text" name="woofood_push_message" placeholder="<?php _e('Push Message','woofood-plugin');?>"/>
<input type="submit" class="button primary" value="<?php _e('Send Push Notifications','woofood-plugin');?>"/>
<div id="woofood_push_output"></div>

</form>



    </div><!--#wrap -->
    <?php
}


public function woofood_orders()
{
        $woofood_plugin_rtl = woofood_plugin_is_rtl();

// Set class property
    $this->options_woofood_delivery_hours = get_option( 'woofood_options_delivery_hours' );
  wp_enqueue_style( 'woofood_css_admin', plugin_dir_url( __FILE__ ) . 'css/admin'.$woofood_plugin_rtl.'.css', array(), '1.0.0', 'all' );
wp_enqueue_script('woofood_js_admin_js', plugin_dir_url(__FILE__).'js/admin.js', array(), WOOFOOD_PLUGIN_VERSION, 'all');
  wp_localize_script( 'woofood_js_admin_js', 'wfajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );


    ?>
    <div class="wrap">
      <h2><?php _e('Orders', 'woofood-plugin'); ?></h2>  



       
        <?php if( isset($_GET['settings-updated']) ) { ?>
        <div id="message" class="updated">
            <p><strong><?php _e('Settings Updated.', 'woofood-plugin') ?></strong></p>
        </div>
        <?php } ?>
        <div class="woofood_orders_list">
                  <form id="wf_order_list" action="" method="POST">
                  <div class="wf_order_field_top">
                  <?php
                  woocommerce_form_field( 'order_status_select', array(
        'type'          => 'multiselect',
        'label'         => esc_html__('Select Order Status', 'woofood-plugin'),
        'desc_tip'    => true,
        'class'  =>array(''),
        'label_class' =>array(),
        // 'wrapper_class' => 'form-row',
        'description' => esc_html__( 'Select Order Status.', 'woofood-plugin' ),
        'placeholder'   => esc_html__('Select Order Status', 'woofood-plugin'),
        'options'       => wc_get_order_statuses(),
        ), array('wc-accepting', 'wc-processing'));
                  ?>
                  </div>
                  <div class="wf_order_field_top">

                  <?php
                  woocommerce_form_field( 'order_refreshing', array(
        'type'          => 'select',
        'label'         => __('Update Every', 'woofood-plugin'),
        'desc_tip'    => true,
        'class'  =>array(),

        // 'wrapper_class' => 'form-row',
        'description' => esc_html__( 'Seconds ', 'woofood-plugin' ),
        'placeholder'   => esc_html__('Update Every', 'woofood-plugin'),
        'options'       => array(
        esc_html__('10','woofood-plugin')=> 10,
        esc_html__('20','woofood-plugin')=>20,
        esc_html__('30','woofood-plugin')=>30,
        esc_html__('60','woofood-plugin')=>60  )
        ), '');
                  ?>
                  </div>                  <div class="wf_order_field_top">

                                        <input type="hidden" name="action" value="woofood_order_list_refresh"/>
                                        <p class="form-row">
                                        <button class="button" data-loading-text="<?php _e('Loading...', 'woofood') ?>" type="submit"><?php _e('Refresh Orders', 'woofood-plugin'); ?></button>
                                        </p>
                                        </div>

</form>
</div>
<script>
  jQuery(document).ready(function($){
  var wf_loop= jQuery('#order_refreshing').val();

 window.setInterval(function(){
   jQuery('#wf_order_list').submit();
}, wf_loop*1000);


 jQuery('#order_refreshing').change(function() 
  {
     wf_loop = jQuery('#order_refreshing').val();


});


});

</script>
<div class="ajax-order-list" id="ajax-order-list">


</div>
        <?php settings_errors($this); ?>
    







    </div><!--#wrap -->
    <?php
}

/**
* Register and add settings
*/
public function page_init()
{        
     register_setting(
'woofood_settings_group_options', // Option group
'woofood_options', // Option name
array( $this, 'sanitize' ) // Sanitize
);







       register_setting(
'woofood_settings_delivery_hours', // Option group
'woofood_options_delivery_hours', // Option name
array( $this, 'sanitize_delivery_hours' ) // Sanitize
);


       register_setting(
'woofood_settings_pickup_hours', // Option group
'woofood_options_pickup_hours', // Option name
array( $this, 'sanitize_pickup_hours' ) // Sanitize
);

              register_setting(
'woofood_settings_push_notifications', // Option group
'woofood_options_push_notifications', // Option name
array( $this, 'sanitize_push_notifications' ) // Sanitize
);


 
    
  add_settings_section(
'setting_section_license_number', //WooFood License Number
esc_html__("License", "woofood-plugin"), // Title
array( $this, 'print_wf_license_number_info' ), // Callback
'woofood_settings_license_page' // Page
); 
    add_settings_field(
'woofood_license_number', // WooFood License Number
esc_html__("WooFood License number", "woofood-plugin"), // Title 
array( $this, 'wf_license_number_callback' ), // Callback
'woofood_settings_license_page', // Page
'setting_section_license_number' // Section           
); 



      add_settings_section(
'setting_section_delivery_time', //WooFood Delivery Time 
esc_html__("Delivery Time", "woofood-plugin"), // Title
array( $this, 'print_wf_delivery_time_info' ), // Callback
'woofood_settings_delivery_page' // Page
); 
    add_settings_field(
'woofood_delivery_time', // WooFood Delivery Time
esc_html__("Average Delivery Time", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_time_callback' ), // Callback
'woofood_settings_delivery_page', // Page
'setting_section_delivery_time' // Section           
); 





          add_settings_section(
'setting_section_force_disable_delivery', //WooFood Delivery Time 
esc_html__("Force Disable Delivery", "woofood-plugin"), // Title
array( $this, 'print_wf_force_disable_delivery' ), // Callback
'woofood_settings_force_disable_page' // Page
); 
    add_settings_field(
'woofood_force_disable_delivery', // WooFood Delivery Time
esc_html__("Disable", "woofood-plugin"), // Title 
array( $this, 'wf_force_disable_delivery_callback' ), // Callback
'woofood_settings_force_disable_page', // Page
'setting_section_force_disable_delivery' // Section           
); 


          add_settings_section(
'setting_section_force_disable_pickup', //WooFood Delivery Time 
esc_html__("Force Disable Pickup", "woofood-plugin"), // Title
array( $this, 'print_wf_force_disable_pickup' ), // Callback
'woofood_settings_force_disable_page' // Page
); 
    add_settings_field(
'woofood_force_disable_pickup', // WooFood Delivery Time
esc_html__("Disable", "woofood-plugin"), // Title 
array( $this, 'wf_force_disable_pickup_callback' ), // Callback
'woofood_settings_force_disable_page', // Page
'setting_section_force_disable_pickup' // Section           
); 







     add_settings_section(
'setting_section_delivery_fee', //WooFood Delivery Time 
esc_html__("Delivery Cost(Fee)", "woofood-plugin"), // Title
array( $this, 'print_wf_delivery_fee' ), // Callback
'woofood_settings_delivery_page' // Page
); 


      add_settings_field(
'woofood_delivery_fee', // WooFood Delivery Time
esc_html__("Delivery Cost", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_fee_callback' ), // Callback
'woofood_settings_delivery_page', // Page
'setting_section_delivery_fee' // Section           
); 




     add_settings_section(
'setting_section_delivery_off_out_of_hours', //WooFood Delivery Time 
esc_html__("Force Disable Delivery Orders out of Delivery Hours", "woofood-plugin"), // Title
array( $this, 'print_wf_delivery_off_out_of_hours' ), // Callback
'woofood_settings_delivery_page' // Page
); 


      add_settings_field(
'woofood_delivery_off_out_of_hours', // WooFood Delivery Time
esc_html__("Disable", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_off_out_of_hours_callback' ), // Callback
'woofood_settings_delivery_page', // Page
'setting_section_delivery_off_out_of_hours' // Section           
); 




  add_settings_section(
'setting_section_availability_checker_keep_opened', //WooFood Delivery Time 
esc_html__("Keep Availability Checker Opened until valid address input", "woofood-plugin"), // Title
array( $this, 'print_wf_availability_checker_keep_opened' ), // Callback
'woofood_settings_availability_checker_page' // Page
); 


      add_settings_field(
'woofood_availability_checker_keep_opened', // WooFood Delivery Time
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_availability_checker_keep_opened_callback' ), // Callback
'woofood_settings_availability_checker_page', // Page
'setting_section_availability_checker_keep_opened' // Section           
); 




 add_settings_section(
'setting_section_availability_checker_hide_address_pickup', //WooFood Delivery Time 
esc_html__("Hide Address When Pickup is Selected", "woofood-plugin"), // Title
array( $this, 'print_wf_availability_checker_hide_address_pickup' ), // Callback
'woofood_settings_availability_checker_page' // Page
); 


      add_settings_field(
'woofood_availability_checker_hide_address_pickup', // WooFood Delivery Time
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_availability_checker_hide_address_pickup_callback' ), // Callback
'woofood_settings_availability_checker_page', // Page
'setting_section_availability_checker_hide_address_pickup' // Section           
); 







/*         add_settings_section(
'setting_section_auto_delivery_time', //WooFood Delivery Time 
esc_html__("Automatic Delivery Time", "woofood-plugin"), // Title
array( $this, 'print_wf_auto_delivery_time_info' ), // Callback
'woofood_settings_delivery_page' // Page
); 
    add_settings_field(
'woofood_auto_delivery_time', // WooFood Delivery Time
esc_html__("Enable Automatic Delivery Time", "woofood-plugin"), // Title 
array( $this, 'wf_auto_delivery_time_callback' ), // Callback
'woofood_settings_delivery_page', // Page
'setting_section_auto_delivery_time' // Section           
); */



add_settings_section(
'setting_section_enable_pickup_option', 
esc_html__("Enable Pickup Option", "woofood-plugin"), // Title
array( $this, 'print_wf_pickup_option' ), // Callback
'woofood_settings_pickup_page' // Page
); 

  
    add_settings_field(
'woofood_enable_pickup_option', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_option' ), // Callback
'woofood_settings_pickup_page', // Page
'setting_section_enable_pickup_option' // Section           
); 

       add_settings_field(
'woofood_hide_address_on_pickup_option', 
esc_html__("Hide Address on Pickup", "woofood-plugin"), // Title 
array( $this, 'wf_hide_address_on_pickup_option' ), // Callback
'woofood_settings_pickup_page', // Page
'setting_section_enable_pickup_option' // Section           
); 


  add_settings_section(
'setting_section_pickup_time', //WooFood Delivery Time 
esc_html__("Pickup Time", "woofood-plugin"), // Title
array( $this, 'print_wf_pickup_time_info' ), // Callback
'woofood_settings_pickup_page' // Page
); 
    add_settings_field(
'woofood_pickup_time', // WooFood Delivery Time
esc_html__("Average Pickup Time", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_time_callback' ), // Callback
'woofood_settings_pickup_page', // Page
'setting_section_pickup_time' // Section           
); 



         add_settings_section(
'setting_section_pickup_off_out_of_hours', //WooFood Delivery Time 
esc_html__("Force Disable Pickup Orders out of Pickup Hours", "woofood-plugin"), // Title
array( $this, 'print_wf_pickup_off_out_of_hours' ), // Callback
'woofood_settings_pickup_page' // Page
); 


      add_settings_field(
'woofood_pickup_off_out_of_hours', // WooFood Delivery Time
esc_html__("Disable", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_off_out_of_hours_callback' ), // Callback
'woofood_settings_pickup_page', // Page
'setting_section_pickup_off_out_of_hours' // Section           
); 



    add_settings_section(
'setting_section_enable_time_to_deliver_option', 
esc_html__("Enable Time To Deliver Option", "woofood-plugin"), // Title
array( $this, 'print_wf_time_to_deliver_option' ), // Callback
'woofood_settings_delivery_page' // Page
); 





  add_settings_section(
'setting_section_enable_date_to_deliver_option', 
esc_html__("Enable Date To Deliver Option", "woofood-plugin"), // Title
array( $this, 'print_wf_date_to_deliver_option' ), // Callback
'woofood_settings_delivery_page' // Page
); 
add_settings_field(
'woofood_enable_date_to_deliver_option', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_date_to_deliver_option' ), // Callback
'woofood_settings_delivery_page', // Page
'setting_section_enable_date_to_deliver_option' // Section           
); 

  add_settings_field(
'woofood_delivery_date_up_to_days_option', 
esc_html__("Up to (Days)", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_date_up_to_days_option' ), // Callback
'woofood_settings_delivery_page', // Page
'setting_section_enable_date_to_deliver_option' // Section           
); 




    add_settings_section(
'setting_section_enable_date_to_pickup_option', 
esc_html__("Enable Date To Pickup Option", "woofood-plugin"), // Title
array( $this, 'print_wf_date_to_pickup_option' ), // Callback
'woofood_settings_pickup_page' // Page
); 
add_settings_field(
'woofood_enable_date_to_pickup_option', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_date_to_pickup_option' ), // Callback
'woofood_settings_pickup_page', // Page
'setting_section_enable_date_to_pickup_option' // Section           
); 

  add_settings_field(
'woofood_pickup_date_up_to_days_option', 
esc_html__("Up to (Days)", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_date_up_to_days_option' ), // Callback
'woofood_settings_pickup_page', // Page
'setting_section_enable_date_to_pickup_option' // Section           
); 






        add_settings_section(
'setting_section_enable_time_to_pickup_option', 
esc_html__("Enable Time To Pickup Option", "woofood-plugin"), // Title
array( $this, 'print_wf_time_to_pickup_option' ), // Callback
'woofood_settings_pickup_page' // Page
); 

  
    add_settings_field(
'woofood_enable_time_to_deliver_option', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_time_to_deliver_option' ), // Callback
'woofood_settings_delivery_page', // Page
'setting_section_enable_time_to_deliver_option' // Section           
); 


 

       add_settings_field(
'woofood_enable_time_to_pickup_option', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_time_to_pickup_option' ), // Callback
'woofood_settings_pickup_page', // Page
'setting_section_enable_time_to_pickup_option' // Section           
); 


 add_settings_field(
'woofood_disable_now_from_pickup_time', 
esc_html__("Disable 'Now' Time", "woofood-plugin"), // Title 
array( $this, 'wf_disable_now_from_pickup_time' ), // Callback
'woofood_settings_pickup_page', // Page
'setting_section_enable_time_to_pickup_option' // Section           
); 

        add_settings_field(
'woofood_enable_asap_on_pickup_time', 
esc_html__("Enable 'ASAP' on  Time", "woofood-plugin"), // Title 
array( $this, 'wf_enable_asap_on_pickup_time' ), // Callback
'woofood_settings_pickup_page', // Page
'setting_section_enable_time_to_pickup_option' // Section           
); 




     add_settings_field(
'woofood_disable_now_from_time', 
esc_html__("Disable 'Now' Time", "woofood-plugin"), // Title 
array( $this, 'wf_disable_now_from_time' ), // Callback
'woofood_settings_delivery_page', // Page
'setting_section_enable_time_to_deliver_option' // Section           
); 

        add_settings_field(
'woofood_enable_asap_on_time', 
esc_html__("Enable 'ASAP' on  Time", "woofood-plugin"), // Title 
array( $this, 'wf_enable_asap_on_time' ), // Callback
'woofood_settings_delivery_page', // Page
'setting_section_enable_time_to_deliver_option' // Section           
); 

         add_settings_field(
'woofood_break_down_times_every', 
esc_html__("Break Down Delivery Times every", "woofood-plugin"), // Title 
array( $this, 'wf_break_down_times_every' ), // Callback
'woofood_settings_delivery_page', // Page
'setting_section_enable_time_to_deliver_option' // Section           
); 


         add_settings_field(
'woofood_break_down_pickup_times_every', 
esc_html__("Break Down Pickup Times every", "woofood-plugin"), // Title 
array( $this, 'wf_break_down_pickup_times_every' ), // Callback
'woofood_settings_pickup_page', // Page
'setting_section_enable_time_to_pickup_option' // Section           
); 

  $theme = wp_get_theme(); // gets the current theme

 if ( 'Avada' === $theme->name || 'Avada' === $theme->parent_theme ) {
    add_settings_section(
'setting_section_enable_avada_compatibilities', 
esc_html__("Enable Avada Compatibility", "woofood-plugin"), // Title
array( $this, 'print_wf_avada_compatibility_option' ), // Callback
'woofood_settings_tweaks_page' // Page
); 


       add_settings_field(
'woofood_enable_avada_compatibility_option', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_enable_avada_compatiblity_option' ), // Callback
'woofood_settings_tweaks_page', // Page
'setting_section_enable_avada_compatibilities' // Section           
); 


}
add_settings_section(
'setting_section_enable_product_short_description', 
esc_html__("Show Product Short Description", "woofood-plugin"), // Title
array( $this, 'print_wf_product_short_description_option' ), // Callback
'woofood_settings_tweaks_page' // Page
); 

add_settings_section(
'setting_section_enable_hide_extra_cat_title_option', 
esc_html__("Hide Extra Category Name on Cart", "woofood-plugin"), // Title
array( $this, 'print_wf_hide_extra_cat_title_option' ), // Callback
'woofood_settings_tweaks_page' // Page
); 



add_settings_section(
'setting_section_enable_rtl_support', 
esc_html__("RTL Support", "woofood-plugin"), // Title
array( $this, 'print_wf_rtl_option' ), // Callback
'woofood_settings_tweaks_page' // Page
); 
   add_settings_field(
'woofood_enable_rtl', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_rtl_option' ), // Callback
'woofood_settings_tweaks_page', // Page
'setting_section_enable_rtl_support' // Section           
); 





add_settings_section(
'setting_section_enable_minutes_display_format_option', 
esc_html__("Minutes Display Format", "woofood-plugin"), // Title
array( $this, 'print_wf_setting_section_enable_minutes_display_format_option' ), // Callback
'woofood_settings_tweaks_page' // Page
); 


add_settings_section(
'setting_section_disable_address_changer', 
esc_html__("Disable Address Changer", "woofood-plugin"), // Title
array( $this, 'print_wf_setting_section_disable_address_changer' ), // Callback
'woofood_settings_tweaks_page' // Page
); 


    add_settings_field(
'woofood_disable_address_changer_option', 
esc_html__("Disable", "woofood-plugin"), // Title 
array( $this, 'wf_disable_address_changer_option' ), // Callback
'woofood_settings_tweaks_page', // Page
'setting_section_disable_address_changer' // Section           
); 

add_settings_section(
'setting_section_enable_woocommerce_product_addons_option', 
esc_html__("WooCommerce Product Add-ons Compatibility", "woofood-plugin"), // Title
array( $this, 'print_wf_setting_section_enable_woocommerce_product_addons_option' ), // Callback
'woofood_settings_tweaks_page' // Page
); 


add_settings_section(
'setting_section_shortcode_usage', 
esc_html__("How To Use WooFood Shortcodes", "woofood-plugin"), // Title
array( $this, 'print_wf_shortcodes_usage' ), // Callback
'woofood_settings_shortcodes_page' // Page
); 


 
    add_settings_field(
'woofood_enable_product_short_description_option', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_enable_product_short_description_option' ), // Callback
'woofood_settings_tweaks_page', // Page
'setting_section_enable_product_short_description' // Section           
); 

  
    add_settings_field(
'woofood_enable_hide_extra_cat_title_option', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_hide_extra_cat_title_option' ), // Callback
'woofood_settings_tweaks_page', // Page
'setting_section_enable_hide_extra_cat_title_option' // Section           
); 


    add_settings_field(
'woofood_minutes_display_format', 
esc_html__("Format", "woofood-plugin"), // Title 
array( $this, 'wf_minutes_display_format' ), // Callback
'woofood_settings_tweaks_page', // Page
'setting_section_enable_minutes_display_format_option' // Section           
); 

       add_settings_field(
'woofood_woocommerce_product_addons_compatibility_enabled', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_woocommerce_product_addons_compatibility_enabled' ), // Callback
'woofood_settings_tweaks_page', // Page
'setting_section_enable_woocommerce_product_addons_option' // Section           
); 




    add_settings_section(
'setting_section_enable_ajax_option', 
esc_html__("Enable Ajax", "woofood-plugin"), // Title
array( $this, 'print_wf_ajax_option' ), // Callback
'woofood_settings_ajax_page' // Page
); 


      

  
    add_settings_field(
'woofood_enable_ajax_option', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_ajax_option' ), // Callback
'woofood_settings_ajax_page', // Page
'setting_section_enable_ajax_option' // Section           
); 


    add_settings_section(
'setting_section_enable_ajax_upsell_option', 
esc_html__("Enable Upsell Products", "woofood-plugin"), // Title
array( $this, 'print_wf_ajax_upsell_option' ), // Callback
'woofood_settings_ajax_page' // Page
); 


      

  
    add_settings_field(
'woofood_enable_ajax_upsell_option', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_ajax_upsell_option' ), // Callback
'woofood_settings_ajax_page', // Page
'setting_section_enable_ajax_upsell_option' // Section           
);     


        add_settings_section(
'setting_section_enable_ajax_related_option', 
esc_html__("Enable Related Products", "woofood-plugin"), // Title
array( $this, 'print_wf_ajax_related_option' ), // Callback
'woofood_settings_ajax_page' // Page
); 


      

  
    add_settings_field(
'woofood_enable_ajax_related_option', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_ajax_related_option' ), // Callback
'woofood_settings_ajax_page', // Page
'setting_section_enable_ajax_related_option' // Section           
);   


add_settings_section(
'setting_section_enable_doorbell_option', 
esc_html__("Enable Doorbell", "woofood-plugin"), // Title
array( $this, 'print_wf_doorbell_option' ), // Callback
'woofood_settings_checkout_page' // Page
); 

 add_settings_field(
'woofood_enable_doorbell_option', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_doorbell_option' ), // Callback
'woofood_settings_checkout_page', // Page
'setting_section_enable_doorbell_option' // Section           
); 


 add_settings_section(
'setting_section_hide_country_option', 
esc_html__("Hide Country Field", "woofood-plugin"), // Title
array( $this, 'print_wf_hide_country_option' ), // Callback
'woofood_settings_checkout_page' // Page
); 

 add_settings_field(
'woofood_hide_country_option', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_hide_country_option' ), // Callback
'woofood_settings_checkout_page', // Page
'setting_section_hide_country_option' // Section           
); 




    add_settings_section(
'setting_section_hide_images_option', 
esc_html__("Hide Images", "woofood-plugin"), // Title
array( $this, 'print_wf_hide_images' ), // Callback
'woofood_settings_tweaks_page' // Page
); 

  
    add_settings_field(
'woofood_enable_hide_images', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_hide_images' ), // Callback
'woofood_settings_tweaks_page', // Page
'setting_section_hide_images_option' // Section           
); 





         add_settings_section(
'setting_section_minimum_delivery_amount', //WooFood Delivery Time 
esc_html__("Minimum amount to delivery", "woofood-plugin"), // Title
array( $this, 'print_wf_minimum_delivery_amount_info' ), // Callback
'woofood_settings_delivery_page' // Page
); 
    add_settings_field(
'woofood_minimum_delivery_amount', // WooFood Delivery Time
esc_html__("Minimum Order amount", "woofood-plugin"), // Title 
array( $this, 'wf_minimum_delivery_amount_callback' ), // Callback
'woofood_settings_delivery_page', // Page
'setting_section_minimum_delivery_amount' // Section           
); 








         add_settings_section(
'setting_section_maximum_orders_delivery_timeslot', //WooFood Delivery Time 
esc_html__("Maximum Number of Orders per Timeslot", "woofood-plugin"), // Title
array( $this, 'print_wf_enable_maximum_orders_delivery_timeslot' ), // Callback
'woofood_settings_delivery_page' // Page
); 


    add_settings_field(
'woofood_enable_maximum_orders_delivery_timeslot', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_enable_maximum_orders_delivery_timeslot' ), // Callback
'woofood_settings_delivery_page', // Page
'setting_section_maximum_orders_delivery_timeslot' // Section           
); 


       add_settings_field(
'woofood_maximum_orders_delivery_timeslot', 
esc_html__("Number of Orders", "woofood-plugin"), // Title 
array( $this, 'wf_maximum_orders_delivery_timeslot' ), // Callback
'woofood_settings_delivery_page', // Page
'setting_section_maximum_orders_delivery_timeslot' // Section           
); 







         add_settings_section(
'setting_section_maximum_orders_pickup_timeslot', //WooFood Delivery Time 
esc_html__("Maximum Number of Orders per Timeslot", "woofood-plugin"), // Title
array( $this, 'print_wf_enable_maximum_orders_pickup_timeslot' ), // Callback
'woofood_settings_pickup_page' // Page
); 


    add_settings_field(
'woofood_enable_maximum_orders_pickup_timeslot', 
esc_html__("Enable", "woofood-plugin"), // Title 
array( $this, 'wf_enable_maximum_orders_pickup_timeslot' ), // Callback
'woofood_settings_pickup_page', // Page
'setting_section_maximum_orders_pickup_timeslot' // Section           
); 


       add_settings_field(
'woofood_maximum_orders_pickup_timeslot', 
esc_html__("Number of Orders", "woofood-plugin"), // Title 
array( $this, 'wf_maximum_orders_pickup_timeslot' ), // Callback
'woofood_settings_pickup_page', // Page
'setting_section_maximum_orders_pickup_timeslot' // Section           
); 














 add_settings_section(
'setting_section_google_api_key', //WooFood Google API Key
esc_html__("Chiavi API di Google (NON MODIFICARE)", "woofood-plugin"), // Title
array( $this, 'print_wf_google_api_key_info' ), // Callback
'woofood_settings_distance_page' // Page
); 
    add_settings_field(
'woofood_google_api_key', // WooFood Google API Key
esc_html__("Google API Key(Maps JavaScript API)", "woofood-plugin"), // Title 
array( $this, 'wf_google_api_key_callback' ), // Callback
'woofood_settings_distance_page', // Page
'setting_section_google_api_key' // Section           
); 


     add_settings_field(
'woofood_google_distance_matrix_api_key', // WooFood Google Distance Matrix API Key
esc_html__("Google API Key(Distance Matrix API)", "woofood-plugin"), // Title 
array( $this, 'wf_google_distance_matrix_api_key_callback' ), // Callback
'woofood_settings_distance_page', // Page
'setting_section_google_api_key' // Section           
); 



 add_settings_section(
'setting_section_woofood_max_delivery_distance', //WooFood maximum distance
esc_html__("Distance Restriction Mode", "woofood-plugin"), // Title
array( $this, 'print_wf_max_delivery_distance_info' ), // Callback
'woofood_settings_distance_page' // Page
); 
    add_settings_field(
'woofood_max_delivery_distance', // WooFood maximum distance
esc_html__("Restrict by ", "woofood-plugin"), // Title 
array( $this, 'wf_max_delivery_distance_callback' ), // Callback
'woofood_settings_distance_page', // Page
'setting_section_woofood_max_delivery_distance' // Section           
); 




     add_settings_section(
'setting_section_woofood_store_address', //WooFood maximum distance
esc_html__("Store Address", "woofood-plugin"), // Title
array( $this, 'print_wf_store_address_info' ), // Callback
'woofood_settings_distance_page' // Page
); 
    add_settings_field(
'woofood_store_address', // WooFood maximum distance
esc_html__("Store Address", "woofood-plugin"), // Title 
array( $this, 'wf_store_address_callback' ), // Callback
'woofood_settings_distance_page', // Page
'setting_section_woofood_store_address' // Section           

); 




             add_settings_section(
'setting_section_enable_order_accepting', //WooFood Enable Accept/Decline
esc_html__("Enable Accept/Decline Orders", "woofood-plugin"), // Title
array( $this, 'print_wf_enable_order_accepting' ), // Callback
'woofood_settings_accept_decline_page' // Page
); 
    add_settings_field(
'woofood_enable_order_accepting', // WooFood Enable Accept/Decline
esc_html__("Enable Accept/Decline Orders", "woofood-plugin"), // Title 
array( $this, 'wf_enable_order_accepting_callback' ), // Callback
'woofood_settings_accept_decline_page', // Page
'setting_section_enable_order_accepting' // Section           
); 
 


       add_settings_field(
'woofood_disable_accept_decline_if_time_selected', // WooFood Enable Accept/Decline
esc_html__("Disable Accept/Decline when", "woofood-plugin"), // Title 
array( $this, 'wf_disable_accept_decline_if_time_selected_callback' ), // Callback
'woofood_settings_accept_decline_page', // Page
'setting_section_enable_order_accepting' // Section           
); 


     add_settings_field(
'woofood_minutes_to_arrive', // WooFood Enable Accept/Decline
esc_html__("Minutes to Arrive (Comma seperated)", "woofood-plugin"), // Title 
array( $this, 'wf_minutes_to_arrive_callback' ), // Callback
'woofood_settings_accept_decline_page', // Page
'setting_section_enable_order_accepting' // Section           
); 

add_settings_field(
'woofood_declined_page', // WooFood Enable Accept/Decline
esc_html__("Declined Order Redirect Page", "woofood-plugin"), // Title 
array( $this, 'wf_declined_page_callback' ), // Callback
'woofood_settings_accept_decline_page', // Page
'setting_section_enable_order_accepting' // Section           
); 


//monday//
  add_settings_section(
'setting_section_delivery_hours_monday', 
esc_html__("Monday", "woofood-plugin"), // Title
array( $this, 'print_wf_delivery_hours_monday' ), // Callback
'woofood_settings_delivery_hours_page' // Page
); 

  
    add_settings_field(
'woofood_delivery_hours_monday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_monday_from_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_monday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_monday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_monday_to_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_monday' // Section           
); 


     add_settings_field(
'woofood_delivery_hours_monday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_monday_from2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_monday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_monday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_monday_to2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_monday' // Section           
);    



     add_settings_field(
'woofood_delivery_hours_monday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_monday_from3_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_monday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_monday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_monday_to3_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_monday' // Section           
);    
//tuesday//

  add_settings_section(
'setting_section_delivery_hours_tuesday', 
esc_html__("Tuesday", "woofood-plugin"), // Title
array( $this, 'print_wf_delivery_hours_tuesday' ), // Callback
'woofood_settings_delivery_hours_page' // Page
); 
    add_settings_field(
'woofood_delivery_hours_tuesday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_tuesday_from_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_tuesday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_tuesday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_tuesday_to_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_tuesday' // Section           
); 


        add_settings_field(
'woofood_delivery_hours_tuesday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_tuesday_from2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_tuesday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_tuesday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_tuesday_to2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_tuesday' // Section           
); 


        add_settings_field(
'woofood_delivery_hours_tuesday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_tuesday_from3_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_tuesday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_tuesday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_tuesday_to3_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_tuesday' // Section           
); 

//wednesday//


  add_settings_section(
'setting_section_delivery_hours_wednesday', 
esc_html__("Wednesday", "woofood-plugin"), // Title
array( $this, 'print_wf_delivery_hours_wednesday' ), // Callback
'woofood_settings_delivery_hours_page' // Page
); 
    add_settings_field(
'woofood_delivery_hours_wednesday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_wednesday_from_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_wednesday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_wednesday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_wednesday_to_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_wednesday' // Section           
); 


        add_settings_field(
'woofood_delivery_hours_wednesday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_wednesday_from2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_wednesday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_wednesday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_wednesday_to2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_wednesday' // Section           
); 


            add_settings_field(
'woofood_delivery_hours_wednesday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_wednesday_from3_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_wednesday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_wednesday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_wednesday_to3_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_wednesday' // Section           
); 




//thursday//

  add_settings_section(
'setting_section_delivery_hours_thursday', 
esc_html__("Thursday", "woofood-plugin"), // Title
array( $this, 'print_wf_delivery_hours_thursday' ), // Callback
'woofood_settings_delivery_hours_page' // Page
); 
    add_settings_field(
'woofood_delivery_hours_thursday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_thursday_from_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_thursday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_thursday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_thursday_to_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_thursday' // Section           
); 


        add_settings_field(
'woofood_delivery_hours_thursday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_thursday_from2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_thursday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_thursday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_thursday_to2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_thursday' // Section           
); 


        add_settings_field(
'woofood_delivery_hours_thursday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_thursday_from3_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_thursday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_thursday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_thursday_to3_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_thursday' // Section           
); 



//friday//

  add_settings_section(
'setting_section_delivery_hours_friday', 
esc_html__("Friday", "woofood-plugin"), // Title
array( $this, 'print_wf_delivery_hours_friday' ), // Callback
'woofood_settings_delivery_hours_page' // Page
); 
    add_settings_field(
'woofood_delivery_hours_friday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_friday_from_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_friday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_friday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_friday_to_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_friday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_friday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_friday_from2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_friday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_friday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_friday_to2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_friday' // Section           
); 


   add_settings_field(
'woofood_delivery_hours_friday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_friday_from3_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_friday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_friday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_friday_to3_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_friday' // Section           
); 


//staurday//

 add_settings_section(
'setting_section_delivery_hours_saturday', 
esc_html__("Saturday", "woofood-plugin"), // Title
array( $this, 'print_wf_delivery_hours_saturday' ), // Callback
'woofood_settings_delivery_hours_page' // Page
); 
    add_settings_field(
'woofood_delivery_hours_saturday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_saturday_from_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_saturday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_saturday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_saturday_to_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_saturday' // Section           
); 

        add_settings_field(
'woofood_delivery_hours_saturday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_saturday_from2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_saturday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_saturday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_saturday_to2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_saturday' // Section           
); 


           add_settings_field(
'woofood_delivery_hours_saturday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_saturday_from3_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_saturday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_saturday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_saturday_to3_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_saturday' // Section           
); 



//sunday//

 add_settings_section(
'setting_section_delivery_hours_sunday', 
esc_html__("Sunday", "woofood-plugin"), // Title
array( $this, 'print_wf_delivery_hours_sunday' ), // Callback
'woofood_settings_delivery_hours_page' // Page
); 
    add_settings_field(
'woofood_delivery_hours_sunday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_sunday_from_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_sunday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_sunday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_sunday_to_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_sunday' // Section           
); 


     add_settings_field(
'woofood_delivery_hours_sunday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_sunday_from2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_sunday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_sunday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_sunday_to2_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_sunday' // Section           
); 


    add_settings_field(
'woofood_delivery_hours_sunday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_sunday_from3_callback' ), // Callback
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_sunday' // Section           
); 

    add_settings_field(
'woofood_delivery_hours_sunday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_delivery_hours_sunday_to3_callback' ), // Callbackmic
'woofood_settings_delivery_hours_page', // Page
'setting_section_delivery_hours_sunday' // Section           
); 







//monday//
  add_settings_section(
'setting_section_pickup_hours_monday', 
esc_html__("Monday", "woofood-plugin"), // Title
array( $this, 'print_wf_pickup_hours_monday' ), // Callback
'woofood_settings_pickup_hours_page' // Page
); 

  
    add_settings_field(
'woofood_pickup_hours_monday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_monday_from_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_monday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_monday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_monday_to_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_monday' // Section           
); 


     add_settings_field(
'woofood_pickup_hours_monday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_monday_from2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_monday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_monday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_monday_to2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_monday' // Section           
);    



     add_settings_field(
'woofood_pickup_hours_monday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_monday_from3_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_monday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_monday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_monday_to3_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_monday' // Section           
);    
//tuesday//

  add_settings_section(
'setting_section_pickup_hours_tuesday', 
esc_html__("Tuesday", "woofood-plugin"), // Title
array( $this, 'print_wf_pickup_hours_tuesday' ), // Callback
'woofood_settings_pickup_hours_page' // Page
); 
    add_settings_field(
'woofood_pickup_hours_tuesday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_tuesday_from_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_tuesday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_tuesday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_tuesday_to_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_tuesday' // Section           
); 


        add_settings_field(
'woofood_pickup_hours_tuesday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_tuesday_from2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_tuesday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_tuesday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_tuesday_to2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_tuesday' // Section           
); 


        add_settings_field(
'woofood_pickup_hours_tuesday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_tuesday_from3_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_tuesday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_tuesday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_tuesday_to3_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_tuesday' // Section           
); 

//wednesday//


  add_settings_section(
'setting_section_pickup_hours_wednesday', 
esc_html__("Wednesday", "woofood-plugin"), // Title
array( $this, 'print_wf_pickup_hours_wednesday' ), // Callback
'woofood_settings_pickup_hours_page' // Page
); 
    add_settings_field(
'woofood_pickup_hours_wednesday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_wednesday_from_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_wednesday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_wednesday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_wednesday_to_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_wednesday' // Section           
); 


        add_settings_field(
'woofood_pickup_hours_wednesday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_wednesday_from2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_wednesday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_wednesday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_wednesday_to2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_wednesday' // Section           
); 


            add_settings_field(
'woofood_pickup_hours_wednesday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_wednesday_from3_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_wednesday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_wednesday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_wednesday_to3_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_wednesday' // Section           
); 




//thursday//

  add_settings_section(
'setting_section_pickup_hours_thursday', 
esc_html__("Thursday", "woofood-plugin"), // Title
array( $this, 'print_wf_pickup_hours_thursday' ), // Callback
'woofood_settings_pickup_hours_page' // Page
); 
    add_settings_field(
'woofood_pickup_hours_thursday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_thursday_from_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_thursday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_thursday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_thursday_to_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_thursday' // Section           
); 


        add_settings_field(
'woofood_pickup_hours_thursday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_thursday_from2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_thursday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_thursday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_thursday_to2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_thursday' // Section           
); 


        add_settings_field(
'woofood_pickup_hours_thursday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_thursday_from3_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_thursday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_thursday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_thursday_to3_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_thursday' // Section           
); 



//friday//

  add_settings_section(
'setting_section_pickup_hours_friday', 
esc_html__("Friday", "woofood-plugin"), // Title
array( $this, 'print_wf_pickup_hours_friday' ), // Callback
'woofood_settings_pickup_hours_page' // Page
); 
    add_settings_field(
'woofood_pickup_hours_friday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_friday_from_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_friday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_friday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_friday_to_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_friday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_friday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_friday_from2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_friday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_friday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_friday_to2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_friday' // Section           
); 


   add_settings_field(
'woofood_pickup_hours_friday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_friday_from3_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_friday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_friday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_friday_to3_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_friday' // Section           
); 


//staurday//

 add_settings_section(
'setting_section_pickup_hours_saturday', 
esc_html__("Saturday", "woofood-plugin"), // Title
array( $this, 'print_wf_pickup_hours_saturday' ), // Callback
'woofood_settings_pickup_hours_page' // Page
); 
    add_settings_field(
'woofood_pickup_hours_saturday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_saturday_from_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_saturday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_saturday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_saturday_to_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_saturday' // Section           
); 

        add_settings_field(
'woofood_pickup_hours_saturday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_saturday_from2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_saturday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_saturday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_saturday_to2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_saturday' // Section           
); 


           add_settings_field(
'woofood_pickup_hours_saturday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_saturday_from3_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_saturday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_saturday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_saturday_to3_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_saturday' // Section           
); 



//sunday//

 add_settings_section(
'setting_section_pickup_hours_sunday', 
esc_html__("Sunday", "woofood-plugin"), // Title
array( $this, 'print_wf_pickup_hours_sunday' ), // Callback
'woofood_settings_pickup_hours_page' // Page
); 
    add_settings_field(
'woofood_pickup_hours_sunday_start', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_sunday_from_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_sunday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_sunday_end', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_sunday_to_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_sunday' // Section           
); 


     add_settings_field(
'woofood_pickup_hours_sunday_start2', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_sunday_from2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_sunday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_sunday_end2', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_sunday_to2_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_sunday' // Section           
); 


    add_settings_field(
'woofood_pickup_hours_sunday_start3', 
esc_html__("from", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_sunday_from3_callback' ), // Callback
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_sunday' // Section           
); 

    add_settings_field(
'woofood_pickup_hours_sunday_end3', 
esc_html__("to", "woofood-plugin"), // Title 
array( $this, 'wf_pickup_hours_sunday_to3_callback' ), // Callbackmic
'woofood_settings_pickup_hours_page', // Page
'setting_section_pickup_hours_sunday' // Section           
); 




//firebase server key//
  add_settings_section(
'setting_section_push_notifications', 
esc_html__("FireBase Server Key", "woofood-plugin"), // Title
array( $this, 'print_wf_push_notificarions_key' ), // Callback
'woofood_settings_push_notifications_page' // Page
); 

  
    add_settings_field(
'woofood_push_notifications_key', 
esc_html__("Key", "woofood-plugin"), // Title 
array( $this, 'wf_push_notifications_key_callback' ), // Callback
'woofood_settings_push_notifications_page', // Page
'setting_section_push_notifications' // Section           
); 
//firebase puush notifications order//

   add_settings_section(
'setting_section_push_notifications_settings', 
esc_html__("Notification Settings", "woofood-plugin"), // Title
array( $this, 'print_wf_push_notifications_settings' ), // Callback
'woofood_settings_push_notifications_page' // Page
);    

  add_settings_field(
'woofood_push_notifications_on_completed', 
esc_html__("Order Completed Notification", "woofood-plugin"), // Title 
array( $this, 'wf_push_notifications_settings_completed_callback' ), // Callback
'woofood_settings_push_notifications_page', // Page
'setting_section_push_notifications_settings' // Section           
); 


} //page init closing here

/**
* Sanitize each setting field as needed
*
* @param array $input Contains all settings fields as array keys
*/

public function sanitize_license( $input )
{
    $new_input = array();
    print_r($input);



    if( isset( $input['woofood_license_number'] ) )
    {
        // Open cURL channel
    $ch = curl_init();
     
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, "http://www.wpslash.com/licensing/envato-license-check.php?purchase_code=".$input['woofood_license_number']."&domain=".home_url( '', 'scheme' ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

       //Set the user agent
       $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
       curl_setopt($ch, CURLOPT_USERAGENT, $agent);  
    // Decode returned JSON
    $output = curl_exec($ch);
    // Close Channel
    curl_close($ch);
           

            if ($output=="activated"){

        $new_input['woofood_license_number'] = $input['woofood_license_number'] ;
                }


            elseif($output=="already-active") {

        $new_input['woofood_license_number'] = "Already Active on other Domain";


                }


            elseif($output=="invalid") {

        $new_input['woofood_license_number'] = "Invalid License";


                }


    }




    return $new_input;
}
public function sanitize_tweaks( $input )
{
   $new_input = array();

    

    return $new_input;

}

public function sanitize( $input )
{
                  delete_transient( "woofood_accordion_" );

        delete_transient( "woofood_cached_date_times_pickup" );
        delete_transient( "woofood_cached_date_times" );
          $all_transients = get_transient('woofood_all_transient_keys');

if(is_array($all_transients))
{
  foreach($all_transients as $transient)
  {
                delete_transient($transient);


  }
}

   $new_input = array();

    $new_input = get_option( 'woofood_options' );

    if(is_array($new_input))
    {

    }
    else
    {
          $new_input = array();

    }



    if( isset( $input['woofood_license_number'] ) )
    {
        // Open cURL channel
    $ch = curl_init();
     
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, "http://www.wpslash.com/licensing/envato-license-check.php?purchase_code=".$input['woofood_license_number']."&domain=".home_url( '/'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

       //Set the user agent
       $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
       curl_setopt($ch, CURLOPT_USERAGENT, $agent);  
    // Decode returned JSON
    $output = curl_exec($ch);
    // Close Channel
    curl_close($ch);
           

            if ($output=="activated"){

        $new_input['woofood_license_number'] = $input['woofood_license_number'] ;
                }


            elseif($output=="already-active") {

        $new_input['woofood_license_number'] = "Already Active on other Domain";


                }


            elseif($output=="invalid") {

        $new_input['woofood_license_number'] = "Invalid License";


                }


    }

  


 if(  isset($input['woofood_enable_hide_images'])  &&  $input['woofood_enable_hide_images'] =="1"  )
    {
        $new_input['woofood_enable_hide_images'] =  $input['woofood_enable_hide_images'];
    }
    elseif( $input['woofood_enable_hide_images'] =="0" )
    {
        $new_input['woofood_enable_hide_images'] = "0";
    }
    else
    {
      
    }
    



 if(  isset($input['woofood_enable_hide_extra_cat_title_option'])  &&  $input['woofood_enable_hide_extra_cat_title_option'] =="1"  )
    {
        $new_input['woofood_enable_hide_extra_cat_title_option'] =  $input['woofood_enable_hide_extra_cat_title_option'];
    }
    elseif( $input['woofood_enable_hide_extra_cat_title_option'] =="0" )
    {
        $new_input['woofood_enable_hide_extra_cat_title_option'] = "0";
    }
    else
    {
      
    }

     if(  isset($input['woofood_enable_product_short_description_option'])  &&  $input['woofood_enable_product_short_description_option'] =="1"  )
    {
        $new_input['woofood_enable_product_short_description_option'] =  $input['woofood_enable_product_short_description_option'];
    }
    elseif( $input['woofood_enable_product_short_description_option'] =="0" )
    {
        $new_input['woofood_enable_product_short_description_option'] = "0";
    }
    else
    {
      
    }


     if(  isset($input['woofood_disable_address_changer_option'])  &&  $input['woofood_disable_address_changer_option'] =="1"  )
    {
        $new_input['woofood_disable_address_changer_option'] =  $input['woofood_disable_address_changer_option'];
    }
    elseif( $input['woofood_disable_address_changer_option'] =="0" )
    {
        $new_input['woofood_disable_address_changer_option'] = "0";
    }
    else
    {
      
    }

    if(  isset($input['woofood_enable_rtl'])  &&  $input['woofood_enable_rtl'] =="1"  )
    {
        $new_input['woofood_enable_rtl'] =  $input['woofood_enable_rtl'];
    }
    elseif( $input['woofood_enable_rtl'] =="0" )
    {
        $new_input['woofood_enable_rtl'] = "0";
    }
    else
    {
      
    }




    if(  isset($input['woofood_force_disable_delivery_option'])  &&  $input['woofood_force_disable_delivery_option'] =="1"  )
    {
        $new_input['woofood_force_disable_delivery_option'] =  $input['woofood_force_disable_delivery_option'];
    }
    elseif( $input['woofood_force_disable_delivery_option'] =="0" )
    {
        $new_input['woofood_force_disable_delivery_option'] = "0";
    }
    else
    {
      
    }


     if(  isset($input['woofood_force_disable_pickup_option'])  &&  $input['woofood_force_disable_pickup_option'] =="1"  )
    {
        $new_input['woofood_force_disable_pickup_option'] =  $input['woofood_force_disable_pickup_option'];
    }
    elseif( $input['woofood_force_disable_pickup_option'] =="0" )
    {
        $new_input['woofood_force_disable_pickup_option'] = "0";
    }
    else
    {
      
    }


 if(  isset($input['woofood_enable_date_to_deliver_option'])  &&  $input['woofood_enable_date_to_deliver_option'] =="1"  )
    {
        $new_input['woofood_enable_date_to_deliver_option'] =  $input['woofood_enable_date_to_deliver_option'];
    }
    elseif( $input['woofood_enable_date_to_deliver_option'] =="0" )
    {
        $new_input['woofood_enable_date_to_deliver_option'] = "0";
    }
    else
    {
      
    }


     if(  isset($input['woofood_enable_date_to_pickup_option'])  &&  $input['woofood_enable_date_to_pickup_option'] =="1"  )
    {
        $new_input['woofood_enable_date_to_pickup_option'] =  $input['woofood_enable_date_to_pickup_option'];
    }
    elseif( $input['woofood_enable_date_to_pickup_option'] =="0" )
    {
        $new_input['woofood_enable_date_to_pickup_option'] = "0";
    }
    else
    {
      
    }
    



     if( isset( $input['woofood_delivery_time'] ) )
    {
        $new_input['woofood_delivery_time'] = absint($input['woofood_delivery_time'] );
    }


     if( isset( $input['woofood_pickup_time'] ) )
    {
        $new_input['woofood_pickup_time'] = absint($input['woofood_pickup_time'] );
    }
    if( isset( $input['woofood_delivery_fee'] ) )
    {
        $new_input['woofood_delivery_fee'] = floatval($input['woofood_delivery_fee'] );
    }

     if( isset( $input['woofood_delivery_fee_distance_based'] ) )
    {
        $new_input['woofood_delivery_fee_distance_based'] = sanitize_text_field($input['woofood_delivery_fee_distance_based'] );
    }




    if( isset( $input['woofood_minimum_delivery_amount'] ) )
    {
        $new_input['woofood_minimum_delivery_amount'] = $input['woofood_minimum_delivery_amount'] ;
    }



if( isset( $input['woofood_google_api_key'] ) )
    {
        $new_input['woofood_google_api_key'] = $input['woofood_google_api_key'] ;
    }

    if( isset( $input['woofood_maximum_orders_delivery_timeslot'] ) )
    {
        $new_input['woofood_maximum_orders_delivery_timeslot'] = $input['woofood_maximum_orders_delivery_timeslot'] ;
    }

     if( isset( $input['woofood_delivery_date_up_to_days'] ) )
    {
        $new_input['woofood_delivery_date_up_to_days'] = $input['woofood_delivery_date_up_to_days'] ;
    }

    if( isset( $input['woofood_pickup_date_up_to_days'] ) )
    {
        $new_input['woofood_pickup_date_up_to_days'] = $input['woofood_pickup_date_up_to_days'] ;
    }
      if( isset( $input['woofood_maximum_orders_pickup_timeslot'] ) )
    {
        $new_input['woofood_maximum_orders_pickup_timeslot'] = $input['woofood_maximum_orders_pickup_timeslot'] ;
    }

    if( isset( $input['woofood_google_distance_matrix_api_key'] ) )
    {
        $new_input['woofood_google_distance_matrix_api_key'] = $input['woofood_google_distance_matrix_api_key'] ;
    }

    if( isset( $input['woofood_max_delivery_distance'] ) )
    {
        $new_input['woofood_max_delivery_distance'] = $input['woofood_max_delivery_distance'] ;
    }

    if( isset( $input['woofood_postalcodes'] ) )
    {
        $new_input['woofood_postalcodes'] = $input['woofood_postalcodes'] ;
    }

    if( isset( $input['woofood_distance_type'] ) )
    {
        $new_input['woofood_distance_type'] = $input['woofood_distance_type'] ;
    }

     if( isset( $input['woofood_polygon_area'] ) )
    {
        $new_input['woofood_polygon_area'] = $input['woofood_polygon_area'] ;
    }

    if( isset( $input['woofood_store_address'] ) )
    {
        $new_input['woofood_store_address'] = $input['woofood_store_address'] ;
    }



   







 if(  isset($input['woofood_auto_delivery_time'])  &&  $input['woofood_auto_delivery_time'] =="1"  )
    {
        $new_input['woofood_auto_delivery_time'] =  $input['woofood_auto_delivery_time'];
    }
    elseif( $input['woofood_auto_delivery_time'] =="0" )
    {
        $new_input['woofood_auto_delivery_time'] = "0";
    }
    else
    {
      
    }



 if(  isset($input['woofood_delivery_off_out_of_hours'])  &&  $input['woofood_delivery_off_out_of_hours'] =="1"  )
    {
        $new_input['woofood_delivery_off_out_of_hours'] =  $input['woofood_delivery_off_out_of_hours'];
    }
    elseif( $input['woofood_delivery_off_out_of_hours'] =="0" )
    {
        $new_input['woofood_delivery_off_out_of_hours'] = "0";
    }
    else
    {
      
    }

     if(  isset($input['woofood_availability_checker_keep_opened'])  &&  $input['woofood_availability_checker_keep_opened'] =="1"  )
    {
        $new_input['woofood_availability_checker_keep_opened'] =  $input['woofood_availability_checker_keep_opened'];
    }
    elseif( $input['woofood_availability_checker_keep_opened'] =="0" )
    {
        $new_input['woofood_availability_checker_keep_opened'] = "0";
    }
    else
    {
      
    }


       if(  isset($input['woofood_availability_checker_hide_address_pickup'])  &&  $input['woofood_availability_checker_hide_address_pickup'] =="1"  )
    {
        $new_input['woofood_availability_checker_hide_address_pickup'] =  $input['woofood_availability_checker_hide_address_pickup'];
    }
    elseif( $input['woofood_availability_checker_hide_address_pickup'] =="0" )
    {
        $new_input['woofood_availability_checker_hide_address_pickup'] = "0";
    }
    else
    {
      
    }



     if(  isset($input['woofood_pickup_off_out_of_hours'])  &&  $input['woofood_pickup_off_out_of_hours'] =="1"  )
    {
        $new_input['woofood_pickup_off_out_of_hours'] =  $input['woofood_pickup_off_out_of_hours'];
    }
    elseif( $input['woofood_pickup_off_out_of_hours'] =="0" )
    {
        $new_input['woofood_pickup_off_out_of_hours'] = "0";
    }
    else
    {
      
    }
    
    


     if(  isset($input['woofood_enable_maximum_orders_delivery_timeslot'])  &&  $input['woofood_enable_maximum_orders_delivery_timeslot'] =="1"  )
    {
        $new_input['woofood_enable_maximum_orders_delivery_timeslot'] =  intval($input['woofood_enable_maximum_orders_delivery_timeslot']);
    }
    elseif( $input['woofood_enable_maximum_orders_delivery_timeslot'] =="0" )
    {
        $new_input['woofood_enable_maximum_orders_delivery_timeslot'] = "0";
    }
    else
    {
      
    }

     if(  isset($input['woofood_enable_maximum_orders_pickup_timeslot'])  &&  $input['woofood_enable_maximum_orders_pickup_timeslot'] =="1"  )
    {
        $new_input['woofood_enable_maximum_orders_pickup_timeslot'] =  intval($input['woofood_enable_maximum_orders_pickup_timeslot']);
    }
    elseif( $input['woofood_enable_maximum_orders_pickup_timeslot'] =="0" )
    {
        $new_input['woofood_enable_maximum_orders_pickup_timeslot'] = "0";
    }
    else
    {
      
    }

  




     if(  isset($input['woofood_enable_pickup_option'])  &&  $input['woofood_enable_pickup_option'] =="1"  )
    {
        $new_input['woofood_enable_pickup_option'] =  $input['woofood_enable_pickup_option'];
    }
    elseif( $input['woofood_enable_pickup_option'] =="0" )
    {
        $new_input['woofood_enable_pickup_option'] = "0";
    }
    else
    {
      
    }

       if(  isset($input['woofood_hide_address_on_pickup_option'])  &&  $input['woofood_hide_address_on_pickup_option'] =="1"  )
    {
        $new_input['woofood_hide_address_on_pickup_option'] =  $input['woofood_hide_address_on_pickup_option'];
    }
    elseif( $input['woofood_hide_address_on_pickup_option'] =="0" )
    {
        $new_input['woofood_hide_address_on_pickup_option'] = "0";
    }
    else
    {
      
    }





    

     if(  isset($input['woofood_enable_time_to_deliver_option'])  &&  $input['woofood_enable_time_to_deliver_option'] =="1"  )
    {
        $new_input['woofood_enable_time_to_deliver_option'] =  $input['woofood_enable_time_to_deliver_option'];
    }
    elseif( $input['woofood_enable_time_to_deliver_option'] =="0" )
    {
        $new_input['woofood_enable_time_to_deliver_option'] = "0";
    }
    else
    {
      
    }


     if(  isset($input['woofood_enable_time_to_pickup_option'])  &&  $input['woofood_enable_time_to_pickup_option'] =="1"  )
    {
        $new_input['woofood_enable_time_to_pickup_option'] =  $input['woofood_enable_time_to_pickup_option'];
    }
    elseif( $input['woofood_enable_time_to_pickup_option'] =="0" )
    {
        $new_input['woofood_enable_time_to_pickup_option'] = "0";
    }
    else
    {
      
    }


    


    if(  isset($input['woofood_disable_now_from_time'])  &&  $input['woofood_disable_now_from_time'] =="1"  )
    {
        $new_input['woofood_disable_now_from_time'] =  $input['woofood_disable_now_from_time'];
    }
    elseif( $input['woofood_disable_now_from_time'] =="0" )
    {
        $new_input['woofood_disable_now_from_time'] = "0";
    }
    else
    {
      
    }
 if(  isset($input['woofood_enable_avada_compatibility_option'])  &&  $input['woofood_enable_avada_compatibility_option'] =="1"  )
    {
        $new_input['woofood_enable_avada_compatibility_option'] =  $input['woofood_enable_avada_compatibility_option'];



//Get entire array
$avada_options = get_option('fusion_options');

//Alter the options array appropriately
$avada_options['woocommerce_one_page_checkout'] = 1;

//Update entire array
update_option('fusion_options', $avada_options);



    }
    elseif( $input['woofood_enable_avada_compatibility_option'] =="0" )
    {
        $new_input['woofood_enable_avada_compatibility_option'] = "0";

        //Get entire array
$avada_options = get_option('fusion_options');

//Alter the options array appropriately
$avada_options['woocommerce_one_page_checkout'] = 0;

//Update entire array
update_option('fusion_options', $avada_options);
    }
    else
    {
      
    }




    if(  isset($input['woofood_enable_asap_on_time'])  &&  $input['woofood_enable_asap_on_time'] =="1"  )
    {
        $new_input['woofood_enable_asap_on_time'] =  $input['woofood_enable_asap_on_time'];
    }
    elseif( $input['woofood_enable_asap_on_time'] =="0" )
    {
        $new_input['woofood_enable_asap_on_time'] = "0";
    }
    else
    {
      
    }









 if(  isset($input['woofood_disable_now_from_pickup_time'])  &&  $input['woofood_disable_now_from_pickup_time'] =="1"  )
    {
        $new_input['woofood_disable_now_from_pickup_time'] =  $input['woofood_disable_now_from_pickup_time'];
    }
    elseif( $input['woofood_disable_now_from_pickup_time'] =="0" )
    {
        $new_input['woofood_disable_now_from_pickup_time'] = "0";
    }
    else
    {
      
    }

    

    if(  isset($input['woofood_enable_asap_on_pickup_time'])  &&  $input['woofood_enable_asap_on_pickup_time'] =="1"  )
    {
        $new_input['woofood_enable_asap_on_pickup_time'] =  $input['woofood_enable_asap_on_pickup_time'];
    }
    elseif( $input['woofood_enable_asap_on_pickup_time'] =="0" )
    {
        $new_input['woofood_enable_asap_on_pickup_time'] = "0";
    }
    else
    {
      
    }

    

    

 if(  isset($input['woofood_enable_ajax_option'])  &&  $input['woofood_enable_ajax_option'] =="1"  )
    {
        $new_input['woofood_enable_ajax_option'] =  $input['woofood_enable_ajax_option'];
    }
    elseif( $input['woofood_enable_ajax_option'] =="0" )
    {
        $new_input['woofood_enable_ajax_option'] = "0";
    }
    else
    {
      
    }



     if(  isset($input['woofood_enable_ajax_upsell_option'])  &&  $input['woofood_enable_ajax_upsell_option'] =="1"  )
    {
        $new_input['woofood_enable_ajax_upsell_option'] =  $input['woofood_enable_ajax_upsell_option'];
    }
    elseif( $input['woofood_enable_ajax_upsell_option'] =="0" )
    {
        $new_input['woofood_enable_ajax_upsell_option'] = "0";
    }
    else
    {
      
    }


       if(  isset($input['woofood_enable_ajax_related_option'])  &&  $input['woofood_enable_ajax_related_option'] =="1"  )
    {
        $new_input['woofood_enable_ajax_related_option'] =  $input['woofood_enable_ajax_related_option'];
    }
    elseif( $input['woofood_enable_ajax_related_option'] =="0" )
    {
        $new_input['woofood_enable_ajax_related_option'] = "0";
    }
    else
    {
      
    }





     if(  isset($input['woofood_enable_doorbell_option'])  &&  $input['woofood_enable_doorbell_option'] =="1"  )
    {
        $new_input['woofood_enable_doorbell_option'] =  $input['woofood_enable_doorbell_option'];
    }
    elseif( $input['woofood_enable_doorbell_option'] =="0" )
    {
        $new_input['woofood_enable_doorbell_option'] = "0";
    }
    else
    {
      
    }

     




     if(  isset($input['woofood_hide_country_option'])  &&  $input['woofood_hide_country_option'] =="1"  )
    {
        $new_input['woofood_hide_country_option'] =  $input['woofood_hide_country_option'];
    }
    elseif( $input['woofood_hide_country_option'] =="0" )
    {
        $new_input['woofood_hide_country_option'] = "0";
    }
    else
    {
      
    }

   
   
      if( isset( $input['woofood_minutes_to_arrive'] ) )
    {
        $new_input['woofood_minutes_to_arrive'] = $input['woofood_minutes_to_arrive'] ;
    }

       if( isset( $input['woofood_declined_page'] ) )
    {
        $new_input['woofood_declined_page'] = intval($input['woofood_declined_page']) ;
    }

     if( isset( $input['woofood_break_down_times_every'] ) )
    {
        $new_input['woofood_break_down_times_every'] = intval($input['woofood_break_down_times_every']) ;
    }
    if( isset( $input['woofood_break_down_pickup_times_every'] ) )
    {
        $new_input['woofood_break_down_pickup_times_every'] = intval($input['woofood_break_down_pickup_times_every']) ;
    }

if( isset( $input['woofood_delivery_fee_type'] ) )
    {
        $new_input['woofood_delivery_fee_type'] = $input['woofood_delivery_fee_type'] ;
    }

 

       if( isset( $input['woofood_minutes_display_format'] ) )
    {
        $new_input['woofood_minutes_display_format'] = $input['woofood_minutes_display_format'] ;
    }


    

   

    if(  isset($input['woofood_enable_order_accepting'])  &&  $input['woofood_enable_order_accepting'] =="1"  )
    {
        $new_input['woofood_enable_order_accepting'] =  $input['woofood_enable_order_accepting'];
    }
    elseif( $input['woofood_enable_order_accepting'] =="0" )
    {
        $new_input['woofood_enable_order_accepting'] = "0";
    }
    else
    {
      
    }





    if(  isset($input['woofood_enable_order_accepting'])  &&  $input['woofood_enable_order_accepting'] =="1"  )
    {
        $new_input['woofood_enable_order_accepting'] =  $input['woofood_enable_order_accepting'];
    }
    elseif( $input['woofood_enable_order_accepting'] =="0" )
    {
        $new_input['woofood_enable_order_accepting'] = "0";
    }
    else
    {

    }


      if(  isset($input['woofood_disable_accept_decline_if_time_selected'])  &&  $input['woofood_disable_accept_decline_if_time_selected'] =="1"  )
    {
        $new_input['woofood_disable_accept_decline_if_time_selected'] =  $input['woofood_disable_accept_decline_if_time_selected'];
    }
    elseif( $input['woofood_disable_accept_decline_if_time_selected'] =="0" )
    {
        $new_input['woofood_disable_accept_decline_if_time_selected'] = "0";
    }
    else
    {

    }

      if(  isset($input['woofood_disable_accept_decline_if_time_selected_restaurant_closed'])  &&  $input['woofood_disable_accept_decline_if_time_selected_restaurant_closed'] =="1"  )
    {
        $new_input['woofood_disable_accept_decline_if_time_selected_restaurant_closed'] =  $input['woofood_disable_accept_decline_if_time_selected_restaurant_closed'];
    }
    elseif( $input['woofood_disable_accept_decline_if_time_selected_restaurant_closed'] =="0" )
    {
        $new_input['woofood_disable_accept_decline_if_time_selected_restaurant_closed'] = "0";
    }
    else
    {

    }


    

    



    if(  isset($input['woofood_woocommerce_product_addons_compatibility_enabled'])  &&  $input['woofood_woocommerce_product_addons_compatibility_enabled'] =="1"  )
    {
        $new_input['woofood_woocommerce_product_addons_compatibility_enabled'] =  $input['woofood_woocommerce_product_addons_compatibility_enabled'];
    }
    elseif( $input['woofood_woocommerce_product_addons_compatibility_enabled'] =="0" )
    {
        $new_input['woofood_woocommerce_product_addons_compatibility_enabled'] = "0";
    }
    else
    {

    }



    

    



    return $new_input;
}







public function sanitize_delivery_hours( $input )
{
    $new_input = array();
    delete_transient( "woofood_cached_date_times" );

     if( isset( $input['woofood_delivery_hours_monday_start'] ) )
    {
        $new_input['woofood_delivery_hours_monday_start'] = $input['woofood_delivery_hours_monday_start'] ;
    }

      if( isset( $input['woofood_delivery_hours_monday_end'] ) )
    {
        $new_input['woofood_delivery_hours_monday_end'] = $input['woofood_delivery_hours_monday_end'] ;
    }


 if( isset( $input['woofood_delivery_hours_tuesday_start'] ) )
    {
        $new_input['woofood_delivery_hours_tuesday_start'] = $input['woofood_delivery_hours_tuesday_start'] ;
    }


      if( isset( $input['woofood_delivery_hours_tuesday_end'] ) )
    {
        $new_input['woofood_delivery_hours_tuesday_end'] = $input['woofood_delivery_hours_tuesday_end'] ;
    }



     if( isset( $input['woofood_delivery_hours_wednesday_start'] ) )
    {
        $new_input['woofood_delivery_hours_wednesday_start'] = $input['woofood_delivery_hours_wednesday_start'] ;
    }


      if( isset( $input['woofood_delivery_hours_wednesday_end'] ) )
    {
        $new_input['woofood_delivery_hours_wednesday_end'] = $input['woofood_delivery_hours_wednesday_end'] ;
    }



  if( isset( $input['woofood_delivery_hours_thursday_start'] ) )
    {
        $new_input['woofood_delivery_hours_thursday_start'] = $input['woofood_delivery_hours_thursday_start'] ;
    }


      if( isset( $input['woofood_delivery_hours_thursday_end'] ) )
    {
        $new_input['woofood_delivery_hours_thursday_end'] = $input['woofood_delivery_hours_thursday_end'] ;
    }



      if( isset( $input['woofood_delivery_hours_friday_start'] ) )
    {
        $new_input['woofood_delivery_hours_friday_start'] = $input['woofood_delivery_hours_friday_start'] ;
    }


      if( isset( $input['woofood_delivery_hours_friday_end'] ) )
    {
        $new_input['woofood_delivery_hours_friday_end'] = $input['woofood_delivery_hours_friday_end'] ;
    }



     if( isset( $input['woofood_delivery_hours_saturday_start'] ) )
    {
        $new_input['woofood_delivery_hours_saturday_start'] = $input['woofood_delivery_hours_saturday_start'] ;
    }


      if( isset( $input['woofood_delivery_hours_saturday_end'] ) )
    {
        $new_input['woofood_delivery_hours_saturday_end'] = $input['woofood_delivery_hours_saturday_end'] ;
    }



       if( isset( $input['woofood_delivery_hours_sunday_start'] ) )
    {
        $new_input['woofood_delivery_hours_sunday_start'] = $input['woofood_delivery_hours_sunday_start'] ;
    }


      if( isset( $input['woofood_delivery_hours_sunday_end'] ) )
    {
        $new_input['woofood_delivery_hours_sunday_end'] = $input['woofood_delivery_hours_sunday_end'] ;
    }



    if( isset( $input['woofood_delivery_hours_monday_start2'] ) )
    {
        $new_input['woofood_delivery_hours_monday_start2'] = $input['woofood_delivery_hours_monday_start2'] ;
    }

      if( isset( $input['woofood_delivery_hours_monday_end2'] ) )
    {
        $new_input['woofood_delivery_hours_monday_end2'] = $input['woofood_delivery_hours_monday_end2'] ;
    }


 if( isset( $input['woofood_delivery_hours_tuesday_start2'] ) )
    {
        $new_input['woofood_delivery_hours_tuesday_start2'] = $input['woofood_delivery_hours_tuesday_start2'] ;
    }


      if( isset( $input['woofood_delivery_hours_tuesday_end2'] ) )
    {
        $new_input['woofood_delivery_hours_tuesday_end2'] = $input['woofood_delivery_hours_tuesday_end2'] ;
    }



     if( isset( $input['woofood_delivery_hours_wednesday_start2'] ) )
    {
        $new_input['woofood_delivery_hours_wednesday_start2'] = $input['woofood_delivery_hours_wednesday_start2'] ;
    }


      if( isset( $input['woofood_delivery_hours_wednesday_end2'] ) )
    {
        $new_input['woofood_delivery_hours_wednesday_end2'] = $input['woofood_delivery_hours_wednesday_end2'] ;
    }



  if( isset( $input['woofood_delivery_hours_thursday_start2'] ) )
    {
        $new_input['woofood_delivery_hours_thursday_start2'] = $input['woofood_delivery_hours_thursday_start2'] ;
    }


      if( isset( $input['woofood_delivery_hours_thursday_end2'] ) )
    {
        $new_input['woofood_delivery_hours_thursday_end2'] = $input['woofood_delivery_hours_thursday_end2'] ;
    }



      if( isset( $input['woofood_delivery_hours_friday_start2'] ) )
    {
        $new_input['woofood_delivery_hours_friday_start2'] = $input['woofood_delivery_hours_friday_start2'] ;
    }


      if( isset( $input['woofood_delivery_hours_friday_end2'] ) )
    {
        $new_input['woofood_delivery_hours_friday_end2'] = $input['woofood_delivery_hours_friday_end2'] ;
    }



     if( isset( $input['woofood_delivery_hours_saturday_start2'] ) )
    {
        $new_input['woofood_delivery_hours_saturday_start2'] = $input['woofood_delivery_hours_saturday_start2'] ;
    }


      if( isset( $input['woofood_delivery_hours_saturday_end2'] ) )
    {
        $new_input['woofood_delivery_hours_saturday_end2'] = $input['woofood_delivery_hours_saturday_end2'] ;
    }



       if( isset( $input['woofood_delivery_hours_sunday_start2'] ) )
    {
        $new_input['woofood_delivery_hours_sunday_start2'] = $input['woofood_delivery_hours_sunday_start2'] ;
    }


      if( isset( $input['woofood_delivery_hours_sunday_end2'] ) )
    {
        $new_input['woofood_delivery_hours_sunday_end2'] = $input['woofood_delivery_hours_sunday_end2'] ;
    }

    if( isset( $input['woofood_delivery_hours_monday_start3'] ) )
    {
        $new_input['woofood_delivery_hours_monday_start3'] = $input['woofood_delivery_hours_monday_start3'] ;
    }

      if( isset( $input['woofood_delivery_hours_monday_end3'] ) )
    {
        $new_input['woofood_delivery_hours_monday_end3'] = $input['woofood_delivery_hours_monday_end3'] ;
    }


 if( isset( $input['woofood_delivery_hours_tuesday_start3'] ) )
    {
        $new_input['woofood_delivery_hours_tuesday_start3'] = $input['woofood_delivery_hours_tuesday_start3'] ;
    }


      if( isset( $input['woofood_delivery_hours_tuesday_end3'] ) )
    {
        $new_input['woofood_delivery_hours_tuesday_end3'] = $input['woofood_delivery_hours_tuesday_end3'] ;
    }



     if( isset( $input['woofood_delivery_hours_wednesday_start3'] ) )
    {
        $new_input['woofood_delivery_hours_wednesday_start3'] = $input['woofood_delivery_hours_wednesday_start3'] ;
    }


      if( isset( $input['woofood_delivery_hours_wednesday_end3'] ) )
    {
        $new_input['woofood_delivery_hours_wednesday_end3'] = $input['woofood_delivery_hours_wednesday_end3'] ;
    }



  if( isset( $input['woofood_delivery_hours_thursday_start3'] ) )
    {
        $new_input['woofood_delivery_hours_thursday_start3'] = $input['woofood_delivery_hours_thursday_start3'] ;
    }


      if( isset( $input['woofood_delivery_hours_thursday_end3'] ) )
    {
        $new_input['woofood_delivery_hours_thursday_end3'] = $input['woofood_delivery_hours_thursday_end3'] ;
    }



      if( isset( $input['woofood_delivery_hours_friday_start3'] ) )
    {
        $new_input['woofood_delivery_hours_friday_start3'] = $input['woofood_delivery_hours_friday_start3'] ;
    }


      if( isset( $input['woofood_delivery_hours_friday_end3'] ) )
    {
        $new_input['woofood_delivery_hours_friday_end3'] = $input['woofood_delivery_hours_friday_end3'] ;
    }



     if( isset( $input['woofood_delivery_hours_saturday_start3'] ) )
    {
        $new_input['woofood_delivery_hours_saturday_start3'] = $input['woofood_delivery_hours_saturday_start3'] ;
    }


      if( isset( $input['woofood_delivery_hours_saturday_end3'] ) )
    {
        $new_input['woofood_delivery_hours_saturday_end3'] = $input['woofood_delivery_hours_saturday_end3'] ;
    }



       if( isset( $input['woofood_delivery_hours_sunday_start3'] ) )
    {
        $new_input['woofood_delivery_hours_sunday_start3'] = $input['woofood_delivery_hours_sunday_start3'] ;
    }


      if( isset( $input['woofood_delivery_hours_sunday_end3'] ) )
    {
        $new_input['woofood_delivery_hours_sunday_end3'] = $input['woofood_delivery_hours_sunday_end3'] ;
    }



    return $new_input;
}



public function sanitize_pickup_hours( $input )
{
      delete_transient( "woofood_cached_date_times_pickup" );

    $new_input = array();

     if( isset( $input['woofood_pickup_hours_monday_start'] ) )
    {
        $new_input['woofood_pickup_hours_monday_start'] = $input['woofood_pickup_hours_monday_start'] ;
    }

      if( isset( $input['woofood_pickup_hours_monday_end'] ) )
    {
        $new_input['woofood_pickup_hours_monday_end'] = $input['woofood_pickup_hours_monday_end'] ;
    }


 if( isset( $input['woofood_pickup_hours_tuesday_start'] ) )
    {
        $new_input['woofood_pickup_hours_tuesday_start'] = $input['woofood_pickup_hours_tuesday_start'] ;
    }


      if( isset( $input['woofood_pickup_hours_tuesday_end'] ) )
    {
        $new_input['woofood_pickup_hours_tuesday_end'] = $input['woofood_pickup_hours_tuesday_end'] ;
    }



     if( isset( $input['woofood_pickup_hours_wednesday_start'] ) )
    {
        $new_input['woofood_pickup_hours_wednesday_start'] = $input['woofood_pickup_hours_wednesday_start'] ;
    }


      if( isset( $input['woofood_pickup_hours_wednesday_end'] ) )
    {
        $new_input['woofood_pickup_hours_wednesday_end'] = $input['woofood_pickup_hours_wednesday_end'] ;
    }



  if( isset( $input['woofood_pickup_hours_thursday_start'] ) )
    {
        $new_input['woofood_pickup_hours_thursday_start'] = $input['woofood_pickup_hours_thursday_start'] ;
    }


      if( isset( $input['woofood_pickup_hours_thursday_end'] ) )
    {
        $new_input['woofood_pickup_hours_thursday_end'] = $input['woofood_pickup_hours_thursday_end'] ;
    }



      if( isset( $input['woofood_pickup_hours_friday_start'] ) )
    {
        $new_input['woofood_pickup_hours_friday_start'] = $input['woofood_pickup_hours_friday_start'] ;
    }


      if( isset( $input['woofood_pickup_hours_friday_end'] ) )
    {
        $new_input['woofood_pickup_hours_friday_end'] = $input['woofood_pickup_hours_friday_end'] ;
    }



     if( isset( $input['woofood_pickup_hours_saturday_start'] ) )
    {
        $new_input['woofood_pickup_hours_saturday_start'] = $input['woofood_pickup_hours_saturday_start'] ;
    }


      if( isset( $input['woofood_pickup_hours_saturday_end'] ) )
    {
        $new_input['woofood_pickup_hours_saturday_end'] = $input['woofood_pickup_hours_saturday_end'] ;
    }



       if( isset( $input['woofood_pickup_hours_sunday_start'] ) )
    {
        $new_input['woofood_pickup_hours_sunday_start'] = $input['woofood_pickup_hours_sunday_start'] ;
    }


      if( isset( $input['woofood_pickup_hours_sunday_end'] ) )
    {
        $new_input['woofood_pickup_hours_sunday_end'] = $input['woofood_pickup_hours_sunday_end'] ;
    }



    if( isset( $input['woofood_pickup_hours_monday_start2'] ) )
    {
        $new_input['woofood_pickup_hours_monday_start2'] = $input['woofood_pickup_hours_monday_start2'] ;
    }

      if( isset( $input['woofood_pickup_hours_monday_end2'] ) )
    {
        $new_input['woofood_pickup_hours_monday_end2'] = $input['woofood_pickup_hours_monday_end2'] ;
    }


 if( isset( $input['woofood_pickup_hours_tuesday_start2'] ) )
    {
        $new_input['woofood_pickup_hours_tuesday_start2'] = $input['woofood_pickup_hours_tuesday_start2'] ;
    }


      if( isset( $input['woofood_pickup_hours_tuesday_end2'] ) )
    {
        $new_input['woofood_pickup_hours_tuesday_end2'] = $input['woofood_pickup_hours_tuesday_end2'] ;
    }



     if( isset( $input['woofood_pickup_hours_wednesday_start2'] ) )
    {
        $new_input['woofood_pickup_hours_wednesday_start2'] = $input['woofood_pickup_hours_wednesday_start2'] ;
    }


      if( isset( $input['woofood_pickup_hours_wednesday_end2'] ) )
    {
        $new_input['woofood_pickup_hours_wednesday_end2'] = $input['woofood_pickup_hours_wednesday_end2'] ;
    }



  if( isset( $input['woofood_pickup_hours_thursday_start2'] ) )
    {
        $new_input['woofood_pickup_hours_thursday_start2'] = $input['woofood_pickup_hours_thursday_start2'] ;
    }


      if( isset( $input['woofood_pickup_hours_thursday_end2'] ) )
    {
        $new_input['woofood_pickup_hours_thursday_end2'] = $input['woofood_pickup_hours_thursday_end2'] ;
    }



      if( isset( $input['woofood_pickup_hours_friday_start2'] ) )
    {
        $new_input['woofood_pickup_hours_friday_start2'] = $input['woofood_pickup_hours_friday_start2'] ;
    }


      if( isset( $input['woofood_pickup_hours_friday_end2'] ) )
    {
        $new_input['woofood_pickup_hours_friday_end2'] = $input['woofood_pickup_hours_friday_end2'] ;
    }



     if( isset( $input['woofood_pickup_hours_saturday_start2'] ) )
    {
        $new_input['woofood_pickup_hours_saturday_start2'] = $input['woofood_pickup_hours_saturday_start2'] ;
    }


      if( isset( $input['woofood_pickup_hours_saturday_end2'] ) )
    {
        $new_input['woofood_pickup_hours_saturday_end2'] = $input['woofood_pickup_hours_saturday_end2'] ;
    }



       if( isset( $input['woofood_pickup_hours_sunday_start2'] ) )
    {
        $new_input['woofood_pickup_hours_sunday_start2'] = $input['woofood_pickup_hours_sunday_start2'] ;
    }


      if( isset( $input['woofood_pickup_hours_sunday_end2'] ) )
    {
        $new_input['woofood_pickup_hours_sunday_end2'] = $input['woofood_pickup_hours_sunday_end2'] ;
    }

    if( isset( $input['woofood_pickup_hours_monday_start3'] ) )
    {
        $new_input['woofood_pickup_hours_monday_start3'] = $input['woofood_pickup_hours_monday_start3'] ;
    }

      if( isset( $input['woofood_pickup_hours_monday_end3'] ) )
    {
        $new_input['woofood_pickup_hours_monday_end3'] = $input['woofood_pickup_hours_monday_end3'] ;
    }


 if( isset( $input['woofood_pickup_hours_tuesday_start3'] ) )
    {
        $new_input['woofood_pickup_hours_tuesday_start3'] = $input['woofood_pickup_hours_tuesday_start3'] ;
    }


      if( isset( $input['woofood_pickup_hours_tuesday_end3'] ) )
    {
        $new_input['woofood_pickup_hours_tuesday_end3'] = $input['woofood_pickup_hours_tuesday_end3'] ;
    }



     if( isset( $input['woofood_pickup_hours_wednesday_start3'] ) )
    {
        $new_input['woofood_pickup_hours_wednesday_start3'] = $input['woofood_pickup_hours_wednesday_start3'] ;
    }


      if( isset( $input['woofood_pickup_hours_wednesday_end3'] ) )
    {
        $new_input['woofood_pickup_hours_wednesday_end3'] = $input['woofood_pickup_hours_wednesday_end3'] ;
    }



  if( isset( $input['woofood_pickup_hours_thursday_start3'] ) )
    {
        $new_input['woofood_pickup_hours_thursday_start3'] = $input['woofood_pickup_hours_thursday_start3'] ;
    }


      if( isset( $input['woofood_pickup_hours_thursday_end3'] ) )
    {
        $new_input['woofood_pickup_hours_thursday_end3'] = $input['woofood_pickup_hours_thursday_end3'] ;
    }



      if( isset( $input['woofood_pickup_hours_friday_start3'] ) )
    {
        $new_input['woofood_pickup_hours_friday_start3'] = $input['woofood_pickup_hours_friday_start3'] ;
    }


      if( isset( $input['woofood_pickup_hours_friday_end3'] ) )
    {
        $new_input['woofood_pickup_hours_friday_end3'] = $input['woofood_pickup_hours_friday_end3'] ;
    }



     if( isset( $input['woofood_pickup_hours_saturday_start3'] ) )
    {
        $new_input['woofood_pickup_hours_saturday_start3'] = $input['woofood_pickup_hours_saturday_start3'] ;
    }


      if( isset( $input['woofood_pickup_hours_saturday_end3'] ) )
    {
        $new_input['woofood_pickup_hours_saturday_end3'] = $input['woofood_pickup_hours_saturday_end3'] ;
    }



       if( isset( $input['woofood_pickup_hours_sunday_start3'] ) )
    {
        $new_input['woofood_pickup_hours_sunday_start3'] = $input['woofood_pickup_hours_sunday_start3'] ;
    }


      if( isset( $input['woofood_pickup_hours_sunday_end3'] ) )
    {
        $new_input['woofood_pickup_hours_sunday_end3'] = $input['woofood_pickup_hours_sunday_end3'] ;
    }



    return $new_input;
}

public function sanitize_push_notifications( $input )
{
    $new_input = array();

     if( isset( $input['woofood_push_notifications_key'] ) )
    {
        $new_input['woofood_push_notifications_key'] = $input['woofood_push_notifications_key'] ;
    }


     if( isset( $input['woofood_push_notifications_completed_message'] ) )
    {
        $new_input['woofood_push_notifications_completed_message'] = $input['woofood_push_notifications_completed_message'] ;
    }
    
      if( isset( $input['woofood_push_notifications_completed_enabled'] ) )
    {
        $new_input['woofood_push_notifications_completed_enabled'] =  $input['woofood_push_notifications_completed_enabled'];
    }
    else
    {
        $new_input['woofood_push_notifications_completed_enabled'] = "0";
    }

        return $new_input;


    }

/** 
* Get the settings option array and print one of its values
*/


public function print_wf_license_number_info()
{
    esc_html_e('Enter your License number:', 'woofood-plugin');
}


public function print_wf_delivery_time_info()
{
    esc_html_e('Average Delivery Time in minutes:', 'woofood-plugin');
}

public function print_wf_force_disable_delivery()
{
    esc_html_e('This will force disable delivery even if time is within delivery hours.', 'woofood-plugin');
}


public function print_wf_force_disable_pickup()
{
    esc_html_e('This will force disable pickup even if time is within pickup hours.', 'woofood-plugin');
}

public function print_wf_pickup_time_info()
{
    esc_html_e('Average Pickup Time in minutes:', 'woofood-plugin');
}


public function print_wf_delivery_fee()
{
    esc_html_e('Complete a delivery cost if any .Leave it empty ito disable it or to use default WooCommerce Shipping Methods', 'woofood-plugin');
}

public function print_wf_delivery_off_out_of_hours()
{
    esc_html_e('Enabling this option will force the store to accept orders only when restaurant is opened (within Delivery Hours) .Pre-orders also will not be possible on times out of Delivery Hours', 'woofood-plugin');
}

public function  print_wf_availability_checker_keep_opened()
{
      esc_html_e('Enabling this option to disallow closing of the Avaialbity Checker until customer types a valid address.', 'woofood-plugin');

}

public function  print_wf_availability_checker_hide_address_pickup()
{
      esc_html_e('Enabling this option to hide address input when Pickup option is selected', 'woofood-plugin');

}


public function print_wf_pickup_off_out_of_hours()
{
    esc_html_e('Enabling this option will force the store to accept orders only when restaurant is opened (within Pickup Hours)', 'woofood-plugin');
}


public function print_wf_auto_delivery_time_info()
{
    esc_html_e('This will override the  Average Delivery Time and will calculate the automatically based on Process Time field of each product:', 'woofood-plugin');
}
public function print_wf_minimum_delivery_amount_info()
{
    
    esc_html_e('Customers must reach this amount to be able to checkout:', 'woofood-plugin');


}
public function print_wf_enable_maximum_orders_delivery_timeslot()
{
    
    esc_html_e('You can enable to accept a maximum number of orders for each timeslot.', 'woofood-plugin');


}

public function print_wf_enable_maximum_orders_pickup_timeslot()
{
    
    esc_html_e('You can enable to accept a maximum number of orders for each timeslot.', 'woofood-plugin');


}

public function print_wf_pickup_option()
{
    
    esc_html_e('Enable this option for customers to be able to select pickup option:', 'woofood-plugin');


}



public function print_wf_time_to_deliver_option()
{
    
    esc_html_e('Enable this option for customers to be able to select a specific time to deliver the products based on Delivery/Pickup Hours', 'woofood-plugin');


}

public function print_wf_date_to_deliver_option()
{
    
    esc_html_e('Enable this option for customers to be able to select a specific date  to deliver the products based on Days store is opened', 'woofood-plugin');


}
public function print_wf_date_to_pickup_option()
{
    
    esc_html_e('Enable this option for customers to be able to select a specific date  to pickup the products based on Days store is opened', 'woofood-plugin');


}
public function print_wf_time_to_pickup_option()
{
    
    esc_html_e('Enable this option for customers to be able to select a specific time  to pickup the products based on Pickup Hours', 'woofood-plugin');


}

public function print_wf_hide_country_option()
{
    esc_html_e('By selecting this option country field will be hidden . But first ensure that you have selected on <strong>WooCommerce -> Settings <strong> under <strong>General<strong> tab  on <strong>General options<strong> section the selling locations and on shipping locations Sell to specific countries and set your country ', 'woofood-plugin');


}

public function print_wf_ajax_option()
{
    
    esc_html_e('Load Product in Pop-up using AJAX.', 'woofood-plugin');


}

public function print_wf_ajax_upsell_option()
{
    
    esc_html_e('Enable Upsell Products in Pop-up', 'woofood-plugin');


}

public function print_wf_ajax_related_option()
{
    
    esc_html_e('Enable Related Products in Pop-up', 'woofood-plugin');


}

public function print_wf_doorbell_option()
{
    
    esc_html_e('Enable Doorbell input field.', 'woofood-plugin');


}

public function print_wf_hide_images()
{
    
    esc_html_e('Hide Product Images on Product Pages, Cart and Archive ', 'woofood-plugin');


}

public function print_wf_hide_extra_cat_title_option()
{
    
    esc_html_e('Hide Extra Category Title above extra options  on cart ', 'woofood-plugin');


}
public function print_wf_rtl_option()
{
    
    esc_html_e('RTL Support', 'woofood-plugin');


}

public function print_wf_product_short_description_option()
{
    
    esc_html_e('Enable this to make visible the product short description after title', 'woofood-plugin');


}
public function print_wf_avada_compatibility_option()
{
    
    esc_html_e('We noticed that you are using Avada Theme. It is suggested to enable this feature.', 'woofood-plugin');


}



public function print_wf_setting_section_enable_minutes_display_format_option()
{
    
    esc_html_e('How do you want minutes to get displayed?', 'woofood-plugin');


}
public function print_wf_setting_section_disable_address_changer()
{
    
    esc_html_e('Disable Address Changer on Mini Cart Widget', 'woofood-plugin');


}

 
public function print_wf_setting_section_enable_woocommerce_product_addons_option()
{
      esc_html_e('Enabling this option will add compatibility with WooCommerce Product Add-ons. Do not enable it if you don\'t use it', 'woofood-plugin');

} 
public function print_wf_shortcodes_usage()
{ 
          $woofood_plugin_rtl = woofood_plugin_is_rtl();

  wp_enqueue_style( 'woofood_css_admin_shortcodes', plugin_dir_url( __FILE__ ) . 'css/admin_shortcodes'.$woofood_plugin_rtl.'.css', array(), '1.0.0', 'all' );

  ?>

<div class="woofood_shortcodes_usage_wrapper">
    <?php
    esc_html_e('Here is a simple guide on how to use WooFood shortcodes', 'woofood-plugin');
    ?>
    <ul class="shortcodes_guide_menu">

 <li>

    <h2><?php esc_html_e('WooFood Accordion All Product Categories', 'woofood-plugin'); ?><br/></h2>
    <p><?php  esc_html_e('This is the most common usage. It will display all of your product categories as accordion.', 'woofood-plugin');?> </p>
    <h3><?php esc_html_e('Example Shortcode', 'woofood-plugin');?> </h3>

    <code>[woofood_accordion]</code>
    </li>

        <li>
    <h2><?php esc_html_e('WooFood Accordion Opened by Default', 'woofood-plugin'); ?><br/></h2>
    <p><?php printf(esc_html__('Here you can notice that we are using %s attribute and set it to yes.', 'woofood-plugin'),'<code>open</code>');?></p>
        <h3><?php esc_html_e('Example Shortcode', 'woofood-plugin');?> </h3>

    <code>[woofood_accordion open="yes"]</code>
    </li>



     <li>
    <h2><?php esc_html_e('WooFood Accordion with Specific Category', 'woofood-plugin'); ?><br/></h2>
    <p><?php printf(esc_html__('Here you can notice that we are using %s attribute along with the category slug. You can set multiple categories by  comma.', 'woofood-plugin'),'<code>category_slug</code>');?></p>
        <h3><?php esc_html_e('Example Shortcode', 'woofood-plugin');?> </h3>

    <code>[woofood_accordion category_slug="burger,pizza"]</code>
    </li>


     <li>
    <h2><?php esc_html_e('WooFood Accordion with Custom Styling', 'woofood-plugin'); ?><br/></h2>
    <p><?php printf(esc_html__('You can use %s , %s, %s to change the styling of your accordions.', 'woofood-plugin'),'<code>text_color</code>', '<code>background_color</code>', '<code>border_color</code>');?></p>
        <h3><?php esc_html_e('Example Shortcode', 'woofood-plugin');?> </h3>

    <code>[woofood_accordion category_slug="burger,pizza" text_color="white" background_color="#cc0000" border_color="#cc0000" ]</code>
    </li>


    <li>
    <h2><?php esc_html_e('WooFood Accordion with Custom Product Selection Styling and Icon', 'woofood-plugin'); ?><br/></h2>
    <p><?php printf(esc_html__('On the following example we are using specific product ids(comma seperated) using the attribute %s and additionaly using the %s and %s  attributes  to set an icon and a title.', 'woofood-plugin'), '<code>ids</code>', '<code>icon</code>', '<code>title</code>');?></p>
    <p>
    <strong><?php _e('Note:', 'woofood-plugin');?></strong>:<?php printf(esc_html__('You can use %s and %s attributes only if you are trying to display only one single product category or a custom product selection.', 'woofood-plugin'),'<code>icon</code>', '<code>title</code>');?></p>

    <code>[woofood_accordion  text_color="white"  ids="314,44,468" title="Sample Category"  background_color="#cc0000"  icon="https://yoursiteurl.com/wp-content/uploads/icon.png"]</code>
    </li>


     <li>
    <h2><?php esc_html_e('WooFood Availability Popup', 'woofood-plugin'); ?><br/></h2>
    <p><?php esc_html_e('Using the following shortcode an availability checker popup will automatically open to all new users.', 'woofood-plugin'); ?></p>
    <p>

    <code>[woofood_availability_popup]</code>
    </li>


    </ul>
    </div>
    <?php


}


public function print_wf_enable_order_accepting()
{
    
    esc_html_e('By enabling Accept/Decline feature you will have to manually Accept or Decline each order. Keep it disabled if you want to automatically accept orders. ', 'woofood-plugin');


}

public function print_wf_google_api_key_info()
{


  $google_places_link = 'https://developers.google.com/maps/documentation/javascript/places';
  $tutorial_link = 'https://www.wpslash.com/how-to-create-distance-matrix-api-and-maps-javascript-and-places-api-for-woofood/';
    echo "<p>";

    printf(
    esc_html__( 'Queste sono le chiavi API di Google per il sistema di gestione automatico della distanza, degli indirizzi e della posizione. %1$s', 'woofood-plugin' ),
    sprintf(
        '<a href="/wp-admin/admin.php?page=assistenza" target="_blank">Contattaci se hai problemi inerenti questa funzionalità e riporta le chiavi sotto indicate</a>',
        $tutorial_link,
        esc_html__( 'Contattaci se hai problemi inerenti questa funzionalità e riporta le chiavi sotto indicate', 'woofood-plugin' )
        )
 
    );
         echo "</p>";



    echo "<p><b><u>";
    esc_html_e('NON modificare, eliminare o condividere le chiavi sotto indicate.', 'woofood-plugin');
     echo "</u></b></p>";




}


public function print_wf_max_delivery_distance_info()
{
    esc_html_e('Select how the Distance restrictions will be applied', 'woofood-plugin');
}


public function print_wf_store_address_info()
{
    esc_html_e('Your Store Address. Required both for Distance in km calculation and Design Area(Polygon)', 'woofood-plugin');
}

public function print_wf_push_notificarions_key()
{
    esc_html_e('Type your FireBase Server Key.:', 'woofood-plugin');
}

public function print_wf_push_notifications_settings()
{
    esc_html_e('Check Automatic Push Notifications you want to enable on Order Status Update:', 'woofood-plugin');
}




public function print_wf_delivery_hours_monday()
{
    
    esc_html_e('Monday Opening hours:', 'woofood-plugin');


}



public function print_wf_delivery_hours_tuesday()
{
    
    esc_html_e('Tuesday Opening hours:', 'woofood-plugin');


}


public function print_wf_delivery_hours_wednesday()
{
    
    esc_html_e('Wednesday Opening hours:', 'woofood-plugin');


}

public function print_wf_delivery_hours_thursday()
{
    
    esc_html_e('Thursday Opening hours:', 'woofood-plugin');


}

public function print_wf_delivery_hours_friday()
{
    
    esc_html_e('Friday Opening hours:', 'woofood-plugin');


}


public function print_wf_delivery_hours_saturday()
{
    
    esc_html_e('Saturday Opening hours:', 'woofood-plugin');


}

public function print_wf_delivery_hours_sunday()
{
    
    esc_html_e('Sunday Opening hours:', 'woofood-plugin');


}






public function print_wf_pickup_hours_monday()
{
    
    esc_html_e('Monday Opening hours:', 'woofood-plugin');


}



public function print_wf_pickup_hours_tuesday()
{
    
    esc_html_e('Tuesday Opening hours:', 'woofood-plugin');


}


public function print_wf_pickup_hours_wednesday()
{
    
    esc_html_e('Wednesday Opening hours:', 'woofood-plugin');


}

public function print_wf_pickup_hours_thursday()
{
    
    esc_html_e('Thursday Opening hours:', 'woofood-plugin');


}

public function print_wf_pickup_hours_friday()
{
    
    esc_html_e('Friday Opening hours:', 'woofood-plugin');


}


public function print_wf_pickup_hours_saturday()
{
    
    esc_html_e('Saturday Opening hours:', 'woofood-plugin');


}

public function print_wf_pickup_hours_sunday()
{
    
    esc_html_e('Sunday Opening hours:', 'woofood-plugin');


}




public function wf_license_number_callback()
{
    printf(
        '<input type="text" id="woofood_license_number" name="woofood_options[woofood_license_number]" value="%s" />',
        isset( $this->options_woofood['woofood_license_number'] ) ? esc_attr( $this->options_woofood['woofood_license_number']) : ''
        );
}


public function wf_delivery_time_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_time" name="woofood_options[woofood_delivery_time]" value="%s" />',
        isset( $this->options_woofood['woofood_delivery_time'] ) ? esc_attr( $this->options_woofood['woofood_delivery_time']) : ''
        );
}

public function wf_pickup_time_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_time" name="woofood_options[woofood_pickup_time]" value="%s" />',
        isset( $this->options_woofood['woofood_pickup_time'] ) ? esc_attr( $this->options_woofood['woofood_pickup_time']) : ''
        );
}

public function wf_delivery_fee_callback()
{
  ?>
  <script>
    var distance_fee_element = '<div class="woofood_distance_based_fees_item"><label for="woofood_km_new_from"><?php esc_html_e('From (Km)', 'woofood-plugin'); ?></label><input type="text" class="woofood_km_input_element" name="km_from[]" placeholder="<?php esc_html_e('Up to (Km)', 'woofood-plugin'); ?>" value="%%km_from%%" /><label for="woofood_km_new"><?php esc_html_e('Up to (Km)', 'woofood-plugin'); ?></label><input type="text" class="woofood_km_input_element" name="km_to[]" placeholder="<?php esc_html_e('Up to (Km)', 'woofood-plugin'); ?>" value="%%km_to%%" /> <label for="woofood_fee_new"><?php esc_html_e('Fee', 'woofood-plugin'); ?></label><input type="text" class="woofood_fee_input_element"  name="charge[]" placeholder="<?php esc_html_e('Fee', 'woofood-plugin'); ?>" value="%%charge%%" ><a class="button woofood_distance_fee_delete"><?php esc_html_e('Delete', 'woofood-plugin'); ?></a>';

  </script>
  <select id="woofood_delivery_fee_type"  name="woofood_options[woofood_delivery_fee_type]">
<option value="default" <?php if(isset($this->options_woofood['woofood_delivery_fee_type']) && $this->options_woofood['woofood_delivery_fee_type'] == "default") {echo " selected" ;} ?>><?php esc_html_e('Default(Fixed Fee)', 'woofood-plugin'); ?></option>
<option value="distance" <?php if(isset($this->options_woofood['woofood_delivery_fee_type']) && $this->options_woofood['woofood_delivery_fee_type']  == "distance") {echo " selected" ;} ?>><?php esc_html_e('Distance Based', 'woofood-plugin'); ?></option>

</select>
<div class="woofood_distance_based_fees">
  <div class="woofood_distance_based_notes">
    <?php 
       esc_html_e("Important:Be sure first that you have configured Google API Keys Correctly. ",'woofood-plugin');
    ?>
  </div>
   <div class="woofood_distance_based_fees_item">
      <label for="woofood_km_new_from"><?php esc_html_e('From (Km)', 'woofood-plugin'); ?></label>

      <input type="text" id="woofood_km_new_from" name="" placeholder="<?php esc_html_e('From (Km)', 'woofood-plugin'); ?>" value="" />
          <label for="woofood_km_new"><?php esc_html_e('Up to (Km)', 'woofood-plugin'); ?></label>

      <input type="text" id="woofood_km_new_to" name="" placeholder="<?php esc_html_e('Up to (Km)', 'woofood-plugin'); ?>" value="" />
                <label for="woofood_fee_new"><?php esc_html_e('Fee', 'woofood-plugin'); ?></label>

      <input type="text" id="woofood_fee_new" name="" placeholder="<?php esc_html_e('Fee', 'woofood-plugin'); ?>" value="" >
      <a class="button woofood_distance_fee_add"><?php esc_html_e('Add', 'woofood-plugin'); ?></a>

    </div>
      <div class="woofood_distance_based_fees_list">

  <?php 
  $woofood_delivery_fee_distance_based = isset( $this->options_woofood['woofood_delivery_fee_distance_based'] ) ? $this->options_woofood['woofood_delivery_fee_distance_based']: null; 
  $delivery_fees = json_decode($woofood_delivery_fee_distance_based);
  $delivery_fees_exploded = array();
  if(!empty($woofood_delivery_fee_distance_based) && true == false)
  {
      $delivery_fees_exploded = trim(explode(",", $woofood_delivery_fee_distance_based));

  }
  if(!empty($delivery_fees))
  {
    ?>

      <?php foreach($delivery_fees as $delivery_fee):

      

       ?>

    <div class="woofood_distance_based_fees_item">
            <label for="woofood_km_new_from"><?php esc_html_e('From (Km)', 'woofood-plugin'); ?></label>

      <input type="text"  name="km_from[]" placeholder="<?php esc_html_e('Up to (Km)', 'woofood-plugin'); ?>" value="<?php echo  $delivery_fee->km_from ?>" />
                <label for="woofood_km_new"><?php esc_html_e('Up to (Km)', 'woofood-plugin'); ?></label>

            <input type="text"  name="km_to[]" placeholder="<?php esc_html_e('Up to (Km)', 'woofood-plugin'); ?>" value="<?php echo  $delivery_fee->km_to ?>" />
                <label for="woofood_fee_new"><?php esc_html_e('Fee', 'woofood-plugin'); ?></label>

      <input type="text"  name="charge[]" placeholder="<?php esc_html_e('Fee', 'woofood-plugin'); ?>" value="<?php echo  $delivery_fee->fee ?>" >
      <a class="button woofood_distance_fee_delete"><?php esc_html_e('Delete', 'woofood-plugin'); ?></a>

    </div>
      <?php endforeach; ?>
      

    <?php

  }

  ?>
      </div>

  

  </div>
  <?php

  printf(
        '<input type="hidden" id="woofood_delivery_fee_distance_based" name="woofood_options[woofood_delivery_fee_distance_based]" value="%s" />',
        isset( $this->options_woofood['woofood_delivery_fee_distance_based'] ) ? esc_attr( $this->options_woofood['woofood_delivery_fee_distance_based']) : ''
        );
  ?>
  <?php
    printf(
        '<input type="text" id="woofood_delivery_fee" name="woofood_options[woofood_delivery_fee]" value="%s" />',
        isset( $this->options_woofood['woofood_delivery_fee'] ) ? esc_attr( $this->options_woofood['woofood_delivery_fee']) : ''
        );
}

 public function wf_delivery_off_out_of_hours_callback()
 {

  echo '<input type="hidden" id="woofood_delivery_off_out_of_hours" name="woofood_options[woofood_delivery_off_out_of_hours]" value="0" />';
$this->options_woofood['woofood_delivery_off_out_of_hours'] = isset($this->options_woofood['woofood_delivery_off_out_of_hours']) ? $this->options_woofood['woofood_delivery_off_out_of_hours'] : null;

    printf(
        '<input type="checkbox" id="woofood_delivery_off_out_of_hours" name="woofood_options[woofood_delivery_off_out_of_hours]" value="1" '. checked( 1, $this->options_woofood['woofood_delivery_off_out_of_hours'], false ) .' />',
        isset( $this->options_woofood['woofood_delivery_off_out_of_hours'] ) ? esc_attr( $this->options_woofood['woofood_delivery_off_out_of_hours']) : ''
        );

 }

  public function wf_availability_checker_keep_opened_callback()
 {

  echo '<input type="hidden" id="woofood_availability_checker_keep_opened" name="woofood_options[woofood_availability_checker_keep_opened]" value="0" />';
$this->options_woofood['woofood_availability_checker_keep_opened'] = isset($this->options_woofood['woofood_availability_checker_keep_opened']) ? $this->options_woofood['woofood_availability_checker_keep_opened'] : null;

    printf(
        '<input type="checkbox" id="woofood_availability_checker_keep_opened" name="woofood_options[woofood_availability_checker_keep_opened]" value="1" '. checked( 1, $this->options_woofood['woofood_availability_checker_keep_opened'], false ) .' />',
        isset( $this->options_woofood['woofood_availability_checker_keep_opened'] ) ? esc_attr( $this->options_woofood['woofood_availability_checker_keep_opened']) : ''
        );

 }

   public function wf_availability_checker_hide_address_pickup_callback()
 {

  echo '<input type="hidden" id="woofood_availability_checker_hide_address_pickup" name="woofood_options[woofood_availability_checker_hide_address_pickup]" value="0" />';
$this->options_woofood['woofood_availability_checker_hide_address_pickup'] = isset($this->options_woofood['woofood_availability_checker_hide_address_pickup']) ? $this->options_woofood['woofood_availability_checker_hide_address_pickup'] : null;

    printf(
        '<input type="checkbox" id="woofood_availability_checker_hide_address_pickup" name="woofood_options[woofood_availability_checker_hide_address_pickup]" value="1" '. checked( 1, $this->options_woofood['woofood_availability_checker_hide_address_pickup'], false ) .' />',
        isset( $this->options_woofood['woofood_availability_checker_hide_address_pickup'] ) ? esc_attr( $this->options_woofood['woofood_availability_checker_hide_address_pickup']) : ''
        );

 }

  public function wf_pickup_off_out_of_hours_callback()
 {

  echo '<input type="hidden" id="woofood_pickup_off_out_of_hours" name="woofood_options[woofood_pickup_off_out_of_hours]" value="0" />';
$this->options_woofood['woofood_pickup_off_out_of_hours'] = isset($this->options_woofood['woofood_pickup_off_out_of_hours']) ? $this->options_woofood['woofood_pickup_off_out_of_hours'] : null;

    printf(
        '<input type="checkbox" id="woofood_pickup_off_out_of_hours" name="woofood_options[woofood_pickup_off_out_of_hours]" value="1" '. checked( 1, $this->options_woofood['woofood_pickup_off_out_of_hours'], false ) .' />',
        isset( $this->options_woofood['woofood_pickup_off_out_of_hours'] ) ? esc_attr( $this->options_woofood['woofood_pickup_off_out_of_hours']) : ''
        );

 }



public function wf_auto_delivery_time_callback()
{
        echo '<input type="hidden" id="woofood_auto_delivery_time" name="woofood_options[woofood_auto_delivery_time]" value="0" />';
$this->options_woofood['woofood_auto_delivery_time'] = isset($this->options_woofood['woofood_auto_delivery_time']) ? $this->options_woofood['woofood_auto_delivery_time'] : null;

    printf(
        '<input type="checkbox" id="woofood_auto_delivery_time" name="woofood_options[woofood_auto_delivery_time]" value="1" '. checked( 1, $this->options_woofood['woofood_auto_delivery_time'], false ) .' />',
        isset( $this->options_woofood['woofood_auto_delivery_time'] ) ? esc_attr( $this->options_woofood['woofood_auto_delivery_time']) : ''
        );



}

public function wf_enable_order_accepting_callback()
{
      echo '<input type="hidden" id="woofood_enable_order_accepting" name="woofood_options[woofood_enable_order_accepting]" value="0" />';
$this->options_woofood['woofood_enable_order_accepting'] = isset($this->options_woofood['woofood_enable_order_accepting']) ? $this->options_woofood['woofood_enable_order_accepting'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_order_accepting" name="woofood_options[woofood_enable_order_accepting]" value="1" '. checked( 1, $this->options_woofood['woofood_enable_order_accepting'], false ) .' />',
        isset( $this->options_woofood['woofood_enable_order_accepting'] ) ? esc_attr( $this->options_woofood['woofood_enable_order_accepting']) : ''
        );


}

public function wf_enable_avada_compatiblity_option()
{
      echo '<input type="hidden" id="woofood_enable_avada_compatibility_option" name="woofood_options[woofood_enable_avada_compatibility_option]" value="0" />';
$this->options_woofood['woofood_enable_avada_compatibility_option'] = isset($this->options_woofood['woofood_enable_avada_compatibility_option']) ? $this->options_woofood['woofood_enable_avada_compatibility_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_avada_compatibility_option" name="woofood_options[woofood_enable_avada_compatibility_option]" value="1" '. checked( 1, $this->options_woofood['woofood_enable_avada_compatibility_option'], false ) .' />',
        isset( $this->options_woofood['woofood_enable_avada_compatibility_option'] ) ? esc_attr( $this->options_woofood['woofood_enable_avada_compatibility_option']) : ''
        );


}
public function wf_disable_accept_decline_if_time_selected_callback()
{
      echo '<input type="hidden" id="woofood_disable_accept_decline_if_time_selected" name="woofood_options[woofood_disable_accept_decline_if_time_selected]" value="0" />';
$this->options_woofood['woofood_disable_accept_decline_if_time_selected'] = isset($this->options_woofood['woofood_disable_accept_decline_if_time_selected']) ? $this->options_woofood['woofood_disable_accept_decline_if_time_selected'] : null;

 echo '<input type="hidden" id="woofood_disable_accept_decline_if_time_selected_restaurant_closed" name="woofood_options[woofood_disable_accept_decline_if_time_selected_restaurant_closed]" value="0" />';
$this->options_woofood['woofood_disable_accept_decline_if_time_selected_restaurant_closed'] = isset($this->options_woofood['woofood_disable_accept_decline_if_time_selected_restaurant_closed']) ? $this->options_woofood['woofood_disable_accept_decline_if_time_selected_restaurant_closed'] : null;

    

  

    printf(
        '<div class="wpslash-inp-wrapper"><input type="checkbox" id="woofood_disable_accept_decline_if_time_selected" name="woofood_options[woofood_disable_accept_decline_if_time_selected]" value="1" '. checked( 1, $this->options_woofood['woofood_disable_accept_decline_if_time_selected'], false ) .' /> <label for="woofood_disable_accept_decline_if_time_selected">'.esc_html_e('Delivery/Pickup Time is selected', 'woofood-plugin').'</label></div>',
        isset( $this->options_woofood['woofood_disable_accept_decline_if_time_selected'] ) ? esc_attr( $this->options_woofood['woofood_disable_accept_decline_if_time_selected']) : ''
       
        );

        printf(
        '<div class="wpslash-inp-wrapper"><input type="checkbox" id="woofood_disable_accept_decline_if_time_selected_restaurant_closed" name="woofood_options[woofood_disable_accept_decline_if_time_selected_restaurant_closed]" value="1" '. checked( 1, $this->options_woofood['woofood_disable_accept_decline_if_time_selected_restaurant_closed'], false ) .' /> <label for="woofood_disable_accept_decline_if_time_selected_restaurant_closed">'.esc_html_e('Delivery/Pickup Time is selected and Restaurant is Closed', 'woofood-plugin').'</label></div>',
        isset( $this->options_woofood['woofood_disable_accept_decline_if_time_selected_restaurant_closed'] ) ? esc_attr( $this->options_woofood['woofood_disable_accept_decline_if_time_selected_restaurant_closed']) : ''
       
        );

  
       
        



        ?>
        <?php


}

public function wf_minutes_to_arrive_callback()
{
     printf(
        '<input type="text" id="woofood_minutes_to_arrive" name="woofood_options[woofood_minutes_to_arrive]" value="%s" />',
        isset( $this->options_woofood['woofood_minutes_to_arrive'] ) ? esc_attr( $this->options_woofood['woofood_minutes_to_arrive']) : ''
        );
     echo "<br/>";
     _e('Complete minutes to arrive comma seperated like <strong>20,30,45,60,70</strong>. These values will be displayed when you are accepting an order to inform the customer in live mode the approximate delivery time. ', 'woofood-plugin');



}

public function wf_declined_page_callback()
{
$this->options_woofood['woofood_declined_page'] = isset($this->options_woofood['woofood_declined_page']) ? $this->options_woofood['woofood_declined_page'] : null;


$args = array(
  'sort_order' => 'asc',
  'sort_column' => 'post_title',
  'hierarchical' => 1,
  'exclude' => '',
  'include' => '',
  'meta_key' => '',
  'meta_value' => '',
  'authors' => '',
  'child_of' => 0,
  'parent' => -1,
  'exclude_tree' => '',
  'number' => '',
  'offset' => 0,
  'post_type' => 'page',
  'post_status' => 'publish'
); 
$pages = get_pages($args); ?>

<select id="woofood_declined_page" name="woofood_options[woofood_declined_page]">
<?php foreach($pages as $page): ?>
<option value="<?php echo $page->ID; ?>" <?php if($this->options_woofood['woofood_declined_page'] == $page->ID) {echo " selected" ;} ?>><?php echo $page->post_title; ?></option>
<?php endforeach; ?>
</select>
<?php



   
     esc_html_e('Select a page for Declined Orders. Customer will redirected to this page when you decline an order.', 'woofood-plugin');



}


public function wf_pickup_option()
{
          echo '<input type="hidden" id="woofood_enable_pickup_option" name="woofood_options[woofood_enable_pickup_option]" value="0" />';
  $woofood_enable_pickup_option = isset($this->options_woofood['woofood_enable_pickup_option']) ? $this->options_woofood['woofood_enable_pickup_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_pickup_option" name="woofood_options[woofood_enable_pickup_option]" value="1" '. checked( 1, $woofood_enable_pickup_option , false ) .' />',
        isset( $woofood_enable_pickup_option  ) ? esc_attr( $woofood_enable_pickup_option ) : ''
        );



}

public function wf_hide_address_on_pickup_option()
{
     echo '<input type="hidden" id="woofood_hide_address_on_pickup_option" name="woofood_options[woofood_hide_address_on_pickup_option]" value="0" />';
  $woofood_hide_address_on_pickup_option = isset($this->options_woofood['woofood_hide_address_on_pickup_option']) ? $this->options_woofood['woofood_hide_address_on_pickup_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_hide_address_on_pickup_option" name="woofood_options[woofood_hide_address_on_pickup_option]" value="1" '. checked( 1, $woofood_hide_address_on_pickup_option , false ) .' />',
        isset( $woofood_hide_address_on_pickup_option  ) ? esc_attr( $woofood_hide_address_on_pickup_option ) : ''
        );

}

public function wf_time_to_deliver_option()
{
          echo '<input type="hidden" id="woofood_enable_time_to_deliver_option" name="woofood_options[woofood_enable_time_to_deliver_option]" value="0" />';
  $woofood_enable_time_to_deliver_option = isset($this->options_woofood['woofood_enable_time_to_deliver_option']) ? $this->options_woofood['woofood_enable_time_to_deliver_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_time_to_deliver_option" name="woofood_options[woofood_enable_time_to_deliver_option]" value="1" '. checked( 1, $woofood_enable_time_to_deliver_option, false ) .' />',
        isset( $woofood_enable_time_to_deliver_option) ? esc_attr( $woofood_enable_time_to_deliver_option) : ''
        );



}

public function wf_date_to_deliver_option()
{
          echo '<input type="hidden" id="woofood_enable_date_to_deliver_option" name="woofood_options[woofood_enable_date_to_deliver_option]" value="0" />';
  $woofood_enable_date_to_deliver_option = isset($this->options_woofood['woofood_enable_date_to_deliver_option']) ? $this->options_woofood['woofood_enable_date_to_deliver_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_date_to_deliver_option" name="woofood_options[woofood_enable_date_to_deliver_option]" value="1" '. checked( 1, $woofood_enable_date_to_deliver_option, false ) .' />',
        isset( $woofood_enable_date_to_deliver_option) ? esc_attr( $woofood_enable_date_to_deliver_option) : ''
        );



}
public function wf_date_to_pickup_option()
{
          echo '<input type="hidden" id="woofood_enable_date_to_pickup_option" name="woofood_options[woofood_enable_date_to_pickup_option]" value="0" />';
  $woofood_enable_date_to_pickup_option = isset($this->options_woofood['woofood_enable_date_to_pickup_option']) ? $this->options_woofood['woofood_enable_date_to_pickup_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_date_to_pickup_option" name="woofood_options[woofood_enable_date_to_pickup_option]" value="1" '. checked( 1, $woofood_enable_date_to_pickup_option, false ) .' />',
        isset( $woofood_enable_date_to_pickup_option) ? esc_attr( $woofood_enable_date_to_pickup_option) : ''
        );



}

public function wf_time_to_pickup_option()
{
          echo '<input type="hidden" id="woofood_enable_time_to_pickup_option" name="woofood_options[woofood_enable_time_to_pickup_option]" value="0" />';
  $woofood_enable_time_to_pickup_option = isset($this->options_woofood['woofood_enable_time_to_pickup_option']) ? $this->options_woofood['woofood_enable_time_to_pickup_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_time_to_pickup_option" name="woofood_options[woofood_enable_time_to_pickup_option]" value="1" '. checked( 1, $woofood_enable_time_to_pickup_option, false ) .' />',
        isset( $woofood_enable_time_to_pickup_option) ? esc_attr( $woofood_enable_time_to_pickup_option) : ''
        );



}

public function wf_disable_now_from_time()
{
          echo '<input type="hidden" id="woofood_disable_now_from_time" name="woofood_options[woofood_disable_now_from_time]" value="0" />';
$this->options_woofood['woofood_disable_now_from_time'] = isset($this->options_woofood['woofood_disable_now_from_time']) ? $this->options_woofood['woofood_disable_now_from_time'] : null;
    printf(
        '<input type="checkbox" id="woofood_disable_now_from_time" name="woofood_options[woofood_disable_now_from_time]" value="1" '. checked( 1, $this->options_woofood['woofood_disable_now_from_time'], false ) .' />',
        isset( $this->options_woofood['woofood_disable_now_from_time'] ) ? esc_attr( $this->options_woofood['woofood_disable_now_from_time']) : ''
        );



}
public function wf_disable_now_from_pickup_time()
{
          echo '<input type="hidden" id="woofood_disable_now_from_pickup_time" name="woofood_options[woofood_disable_now_from_pickup_time]" value="0" />';
$this->options_woofood['woofood_disable_now_from_pickup_time'] = isset($this->options_woofood['woofood_disable_now_from_pickup_time']) ? $this->options_woofood['woofood_disable_now_from_pickup_time'] : null;
    printf(
        '<input type="checkbox" id="woofood_disable_now_from_pickup_time" name="woofood_options[woofood_disable_now_from_pickup_time]" value="1" '. checked( 1, $this->options_woofood['woofood_disable_now_from_pickup_time'], false ) .' />',
        isset( $this->options_woofood['woofood_disable_now_from_pickup_time'] ) ? esc_attr( $this->options_woofood['woofood_disable_now_from_pickup_time']) : ''
        );



}

public function wf_enable_asap_on_time()
{
          echo '<input type="hidden" id="woofood_enable_asap_on_time" name="woofood_options[woofood_enable_asap_on_time]" value="0" />';
$this->options_woofood['woofood_enable_asap_on_time'] = isset($this->options_woofood['woofood_enable_asap_on_time']) ? $this->options_woofood['woofood_enable_asap_on_time'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_asap_on_time" name="woofood_options[woofood_enable_asap_on_time]" value="1" '. checked( 1, $this->options_woofood['woofood_enable_asap_on_time'], false ) .' />',
        isset( $this->options_woofood['woofood_enable_asap_on_time'] ) ? esc_attr( $this->options_woofood['woofood_enable_asap_on_time']) : ''
        );



}

public function wf_enable_asap_on_pickup_time()
{
          echo '<input type="hidden" id="woofood_enable_asap_on_pickup_time" name="woofood_options[woofood_enable_asap_on_pickup_time]" value="0" />';
$this->options_woofood['woofood_enable_asap_on_pickup_time'] = isset($this->options_woofood['woofood_enable_asap_on_pickup_time']) ? $this->options_woofood['woofood_enable_asap_on_pickup_time'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_asap_on_pickup_time" name="woofood_options[woofood_enable_asap_on_pickup_time]" value="1" '. checked( 1, $this->options_woofood['woofood_enable_asap_on_pickup_time'], false ) .' />',
        isset( $this->options_woofood['woofood_enable_asap_on_pickup_time'] ) ? esc_attr( $this->options_woofood['woofood_enable_asap_on_pickup_time']) : ''
        );



}
public function wf_break_down_times_every()
{
$this->options_woofood['woofood_break_down_times_every'] = isset($this->options_woofood['woofood_break_down_times_every']) ? $this->options_woofood['woofood_break_down_times_every'] : null;

        ?>
        <select id="woofood_break_down_times_every" name="woofood_options[woofood_break_down_times_every]">
 <option value="180" <?php if($this->options_woofood['woofood_break_down_times_every'] == "180") {echo " selected" ;} ?>> <?php esc_html_e('3 hours', 'woofood-plugin'); ?></option>
 <option value="150" <?php if($this->options_woofood['woofood_break_down_times_every'] == "150") {echo " selected" ;} ?>> <?php esc_html_e('2.5 hours', 'woofood-plugin'); ?></option>
 <option value="120" <?php if($this->options_woofood['woofood_break_down_times_every'] == "120") {echo " selected" ;} ?>> <?php esc_html_e('2 hours', 'woofood-plugin'); ?></option>
<option value="90" <?php if($this->options_woofood['woofood_break_down_times_every'] == "90") {echo " selected" ;} ?>> <?php esc_html_e('1.5 hours', 'woofood-plugin'); ?></option>
<option value="60" <?php if($this->options_woofood['woofood_break_down_times_every'] == "60") {echo " selected" ;} ?>> <?php esc_html_e('60 minutes', 'woofood-plugin'); ?></option>
<option value="50" <?php if($this->options_woofood['woofood_break_down_times_every'] == "50") {echo " selected" ;} ?>> <?php esc_html_e('50 minutes', 'woofood-plugin'); ?></option>
<option value="40" <?php if($this->options_woofood['woofood_break_down_times_every'] == "40") {echo " selected" ;} ?>> <?php esc_html_e('40 minutes', 'woofood-plugin'); ?></option>
<option value="30" <?php if($this->options_woofood['woofood_break_down_times_every'] == "30") {echo " selected" ;} ?>> <?php esc_html_e('30 minutes', 'woofood-plugin'); ?></option>
<option value="20" <?php if($this->options_woofood['woofood_break_down_times_every'] == "20") {echo " selected" ;} ?>> <?php esc_html_e('20 minutes', 'woofood-plugin'); ?></option>
<option value="10" <?php if($this->options_woofood['woofood_break_down_times_every'] == "10") {echo " selected" ;} ?>> <?php esc_html_e('10 minutes', 'woofood-plugin'); ?></option>
<option value="5" <?php if($this->options_woofood['woofood_break_down_times_every'] == "5") {echo " selected" ;} ?>> <?php esc_html_e('5 minutes', 'woofood-plugin'); ?></option>

</select>
        <?php



}

public function wf_break_down_pickup_times_every()
{
$this->options_woofood['woofood_break_down_pickup_times_every'] = isset($this->options_woofood['woofood_break_down_pickup_times_every']) ? $this->options_woofood['woofood_break_down_pickup_times_every'] : null;

        ?>
        <select id="woofood_break_down_pickup_times_every" name="woofood_options[woofood_break_down_pickup_times_every]">
          <option value="180" <?php if($this->options_woofood['woofood_break_down_pickup_times_every'] == "180") {echo " selected" ;} ?>> <?php esc_html_e('3 hours', 'woofood-plugin'); ?></option>
 <option value="150" <?php if($this->options_woofood['woofood_break_down_pickup_times_every'] == "150") {echo " selected" ;} ?>> <?php esc_html_e('2.5 hours', 'woofood-plugin'); ?></option>
 <option value="120" <?php if($this->options_woofood['woofood_break_down_pickup_times_every'] == "120") {echo " selected" ;} ?>> <?php esc_html_e('2 hours', 'woofood-plugin'); ?></option>
<option value="90" <?php if($this->options_woofood['woofood_break_down_pickup_times_every'] == "90") {echo " selected" ;} ?>> <?php esc_html_e('1.5 hours', 'woofood-plugin'); ?></option>
<option value="60" <?php if($this->options_woofood['woofood_break_down_pickup_times_every'] == "60") {echo " selected" ;} ?>> <?php esc_html_e('60 minutes', 'woofood-plugin'); ?></option>
<option value="50" <?php if($this->options_woofood['woofood_break_down_pickup_times_every'] == "50") {echo " selected" ;} ?>> <?php esc_html_e('50 minutes', 'woofood-plugin'); ?></option>
<option value="40" <?php if($this->options_woofood['woofood_break_down_pickup_times_every'] == "40") {echo " selected" ;} ?>> <?php esc_html_e('40 minutes', 'woofood-plugin'); ?></option>
<option value="30" <?php if($this->options_woofood['woofood_break_down_pickup_times_every'] == "30") {echo " selected" ;} ?>> <?php esc_html_e('30 minutes', 'woofood-plugin'); ?></option>
<option value="20" <?php if($this->options_woofood['woofood_break_down_pickup_times_every'] == "20") {echo " selected" ;} ?>> <?php esc_html_e('20 minutes', 'woofood-plugin'); ?></option>
<option value="10" <?php if($this->options_woofood['woofood_break_down_pickup_times_every'] == "10") {echo " selected" ;} ?>> <?php esc_html_e('10 minutes', 'woofood-plugin'); ?></option>
<option value="5" <?php if($this->options_woofood['woofood_break_down_pickup_times_every'] == "5") {echo " selected" ;} ?>> <?php esc_html_e('5 minutes', 'woofood-plugin'); ?></option>

</select>
        <?php



}



public function wf_hide_country_option()
   {
              echo '<input type="hidden" id="woofood_hide_country_option" name="woofood_options[woofood_hide_country_option]" value="0" />';
$this->options_woofood['woofood_hide_country_option'] = isset($this->options_woofood['woofood_hide_country_option']) ? $this->options_woofood['woofood_hide_country_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_hide_country_option" name="woofood_options[woofood_hide_country_option]" value="1" '. checked( 1, $this->options_woofood['woofood_hide_country_option'], false ) .' />',
        isset( $this->options_woofood['woofood_hide_country_option'] ) ? esc_attr( $this->options_woofood['woofood_hide_country_option']) : ''
        );



}
public function wf_force_disable_delivery_callback()
   {
              echo '<input type="hidden" id="woofood_force_disable_delivery_option" name="woofood_options[woofood_force_disable_delivery_option]" value="0" />';
$this->options_woofood['woofood_force_disable_delivery_option'] = isset($this->options_woofood['woofood_force_disable_delivery_option']) ? $this->options_woofood['woofood_force_disable_delivery_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_force_disable_delivery_option" name="woofood_options[woofood_force_disable_delivery_option]" value="1" '. checked( 1, $this->options_woofood['woofood_force_disable_delivery_option'], false ) .' />',
        isset( $this->options_woofood['woofood_force_disable_delivery_option'] ) ? esc_attr( $this->options_woofood['woofood_force_disable_delivery_option']) : ''
        );



}


public function wf_force_disable_pickup_callback()
   {
              echo '<input type="hidden" id="woofood_force_disable_pickup_option" name="woofood_options[woofood_force_disable_pickup_option]" value="0" />';
$this->options_woofood['woofood_force_disable_pickup_option'] = isset($this->options_woofood['woofood_force_disable_pickup_option']) ? $this->options_woofood['woofood_force_disable_pickup_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_force_disable_pickup_option" name="woofood_options[woofood_force_disable_pickup_option]" value="1" '. checked( 1, $this->options_woofood['woofood_force_disable_pickup_option'], false ) .' />',
        isset( $this->options_woofood['woofood_force_disable_pickup_option'] ) ? esc_attr( $this->options_woofood['woofood_force_disable_pickup_option']) : ''
        );



}


public function wf_ajax_option()
{
                echo '<input type="hidden"  id="woofood_enable_ajax_option" name="woofood_options[woofood_enable_ajax_option]" value="0" />';
      $woofood_enable_ajax_option =    isset( $this->options_woofood['woofood_enable_ajax_option'] ) ? $this->options_woofood['woofood_enable_ajax_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_ajax_option" name="woofood_options[woofood_enable_ajax_option]" value="1" '. checked( 1, $woofood_enable_ajax_option, false ) .' />',
        isset( $woofood_enable_ajax_option ) ? esc_attr( $woofood_enable_ajax_option) : ''
        );



}

public function wf_ajax_upsell_option()
{
                echo '<input type="hidden"  id="woofood_enable_ajax_upsell_option" name="woofood_options[woofood_enable_ajax_upsell_option]" value="0" />';
      $woofood_enable_ajax_upsell_option =    isset( $this->options_woofood['woofood_enable_ajax_upsell_option'] ) ? $this->options_woofood['woofood_enable_ajax_upsell_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_ajax_upsell_option" name="woofood_options[woofood_enable_ajax_upsell_option]" value="1" '. checked( 1, $woofood_enable_ajax_upsell_option, false ) .' />',
        isset( $woofood_enable_ajax_upsell_option ) ? esc_attr( $woofood_enable_ajax_upsell_option) : ''
        );



}

public function wf_ajax_related_option()
{
                echo '<input type="hidden"  id="woofood_enable_ajax_related_option" name="woofood_options[woofood_enable_ajax_related_option]" value="0" />';
      $woofood_enable_ajax_related_option =    isset( $this->options_woofood['woofood_enable_ajax_related_option'] ) ? $this->options_woofood['woofood_enable_ajax_related_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_ajax_related_option" name="woofood_options[woofood_enable_ajax_related_option]" value="1" '. checked( 1, $woofood_enable_ajax_related_option, false ) .' />',
        isset( $woofood_enable_ajax_related_option ) ? esc_attr( $woofood_enable_ajax_related_option) : ''
        );



}

public function wf_doorbell_option()
{
                  echo '<input type="hidden"  id="woofood_enable_doorbell_option" name="woofood_options[woofood_enable_doorbell_option]" value="0" />';
$this->options_woofood['woofood_enable_doorbell_option'] = isset($this->options_woofood['woofood_enable_doorbell_option']) ? $this->options_woofood['woofood_enable_doorbell_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_doorbell_option" name="woofood_options[woofood_enable_doorbell_option]" value="1" '. checked( 1, $this->options_woofood['woofood_enable_doorbell_option'], false ) .' />',
        isset( $this->options_woofood['woofood_enable_doorbell_option'] ) ? esc_attr( $this->options_woofood['woofood_enable_doorbell_option']) : ''
        );



}


public function wf_hide_images()
{
  $woofood_enable_hide_images = isset($this->options_woofood['woofood_enable_hide_images']) ? $this->options_woofood['woofood_enable_hide_images'] : null;
                    echo '<input type="hidden"  id="woofood_enable_hide_images" name="woofood_options[woofood_enable_hide_images]" value="0" />';

    printf(
        '<input type="checkbox" id="woofood_enable_hide_images" name="woofood_options[woofood_enable_hide_images]" value="1" '. checked( 1, $woofood_enable_hide_images, false ) .' />',
        isset( $woofood_enable_hide_images ) ? esc_attr( $woofood_enable_hide_images) : ''
        );



}


public function wf_hide_extra_cat_title_option()
{
                      echo '<input type="hidden"  id="woofood_enable_hide_extra_cat_title_option" name="woofood_options[woofood_enable_hide_extra_cat_title_option]" value="0" />';


               $woofood_enable_hide_extra_cat_title_option  =  isset($this->options_woofood['woofood_enable_hide_extra_cat_title_option']) ? $this->options_woofood['woofood_enable_hide_extra_cat_title_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_hide_extra_cat_title_option" name="woofood_options[woofood_enable_hide_extra_cat_title_option]" value="1" '. checked( 1, $woofood_enable_hide_extra_cat_title_option, false ) .' />',
        isset( $this->options_woofood['woofood_enable_hide_extra_cat_title_option'] ) ? esc_attr( $woofood_enable_hide_extra_cat_title_option) : ''
        );



}
public function wf_enable_product_short_description_option()
{
                      echo '<input type="hidden"  id="woofood_enable_product_short_description_option" name="woofood_options[woofood_enable_product_short_description_option]" value="0" />';


               $woofood_enable_product_short_description_option  =  isset($this->options_woofood['woofood_enable_product_short_description_option']) ? $this->options_woofood['woofood_enable_product_short_description_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_product_short_description_option" name="woofood_options[woofood_enable_product_short_description_option]" value="1" '. checked( 1, $woofood_enable_product_short_description_option, false ) .' />',
        isset( $this->options_woofood['woofood_enable_product_short_description_option'] ) ? esc_attr( $woofood_enable_product_short_description_option) : ''
        );



}
public function wf_disable_address_changer_option()
{
                      echo '<input type="hidden"  id="woofood_disable_address_changer_option" name="woofood_options[woofood_disable_address_changer_option]" value="0" />';


               $woofood_disable_address_changer_option  =  isset($this->options_woofood['woofood_disable_address_changer_option']) ? $this->options_woofood['woofood_disable_address_changer_option'] : null;

    printf(
        '<input type="checkbox" id="woofood_disable_address_changer_option" name="woofood_options[woofood_disable_address_changer_option]" value="1" '. checked( 1, $woofood_disable_address_changer_option, false ) .' />',
        isset( $this->options_woofood['woofood_disable_address_changer_option'] ) ? esc_attr( $woofood_disable_address_changer_option) : ''
        );



}

public function wf_rtl_option()
{


            echo '<input type="hidden"  id="woofood_enable_rtl" name="woofood_options[woofood_enable_rtl]" value="0" />';


               $woofood_enable_rtl  =  isset($this->options_woofood['woofood_enable_rtl']) ? $this->options_woofood['woofood_enable_rtl'] : null;

    printf(
        '<input type="checkbox" id="woofood_enable_rtl" name="woofood_options[woofood_enable_rtl]" value="1" '. checked( 1, $woofood_enable_rtl, false ) .' />',
        isset( $this->options_woofood['woofood_enable_rtl'] ) ? esc_attr( $woofood_enable_rtl) : ''
        );



}


public function wf_minutes_display_format()
{
$woofood_minutes_display_format =   isset($this->options_woofood['woofood_minutes_display_format']) ? $this->options_woofood['woofood_minutes_display_format'] : null;
?>

  <select id="woofood_minutes_display_format" name="woofood_options[woofood_minutes_display_format]">
<option value="default" <?php if($woofood_minutes_display_format =="default") {echo " selected" ;} ?>><?php esc_html_e("Default(')", "woofood-plugin"); ?></option>
<option value="mins" <?php if($woofood_minutes_display_format=="mins") {echo " selected" ;} ?>><?php esc_html_e("mins", "woofood-plugin"); ?></option>
<option value="minutes" <?php if($woofood_minutes_display_format =="minutes") {echo " selected" ;} ?>><?php esc_html_e("minutes", 'woofood-plugin'); ?></option>


</select>
<?php


}


public function wf_woocommerce_product_addons_compatibility_enabled()
{
  $woofood_woocommerce_product_addons_compatibility_enabled =   isset($this->options_woofood['woofood_woocommerce_product_addons_compatibility_enabled']) ? $this->options_woofood['woofood_woocommerce_product_addons_compatibility_enabled'] : null;

 echo '<input type="hidden"  id="woofood_woocommerce_product_addons_compatibility_enabled" name="woofood_options[woofood_woocommerce_product_addons_compatibility_enabled]" value="0" />';

    printf(
        '<input type="checkbox" id="woofood_woocommerce_product_addons_compatibility_enabled" name="woofood_options[woofood_woocommerce_product_addons_compatibility_enabled]" value="1" '. checked( 1, $woofood_woocommerce_product_addons_compatibility_enabled, false ) .' />',
        isset( $woofood_woocommerce_product_addons_compatibility_enabled ) ? esc_attr( $woofood_woocommerce_product_addons_compatibility_enabled) : ''
        );

}



public function wf_minimum_delivery_amount_callback()
{
    printf(
        '<input type="text" id="woofood_minimum_delivery_amount" name="woofood_options[woofood_minimum_delivery_amount]" value="%s" />',
        isset( $this->options_woofood['woofood_minimum_delivery_amount'] ) ? esc_attr( $this->options_woofood['woofood_minimum_delivery_amount']) : ''
        );
}


public function wf_enable_maximum_orders_delivery_timeslot()
{
    $woofood_enable_maximum_orders_delivery_timeslot =   isset($this->options_woofood['woofood_enable_maximum_orders_delivery_timeslot']) ? $this->options_woofood['woofood_enable_maximum_orders_delivery_timeslot'] : null;

 echo '<input type="hidden"  id="woofood_enable_maximum_orders_delivery_timeslot" name="woofood_options[woofood_enable_maximum_orders_delivery_timeslot]" value="0" />';

    printf(
        '<input type="checkbox" id="woofood_enable_maximum_orders_delivery_timeslot" name="woofood_options[woofood_enable_maximum_orders_delivery_timeslot]" value="1" '. checked( 1, $woofood_enable_maximum_orders_delivery_timeslot, false ) .' />',
        isset( $woofood_enable_maximum_orders_delivery_timeslot ) ? esc_attr( $woofood_enable_maximum_orders_delivery_timeslot) : ''
        );
}

public function wf_enable_maximum_orders_pickup_timeslot()
{
    $woofood_enable_maximum_orders_pickup_timeslot =   isset($this->options_woofood['woofood_enable_maximum_orders_pickup_timeslot']) ? $this->options_woofood['woofood_enable_maximum_orders_pickup_timeslot'] : null;

 echo '<input type="hidden"  id="woofood_enable_maximum_orders_pickup_timeslot" name="woofood_options[woofood_enable_maximum_orders_pickup_timeslot]" value="0" />';

    printf(
        '<input type="checkbox" id="woofood_enable_maximum_orders_pickup_timeslot" name="woofood_options[woofood_enable_maximum_orders_pickup_timeslot]" value="1" '. checked( 1, $woofood_enable_maximum_orders_pickup_timeslot, false ) .' />',
        isset( $woofood_enable_maximum_orders_pickup_timeslot ) ? esc_attr( $woofood_enable_maximum_orders_pickup_timeslot) : ''
        );
}

public function wf_maximum_orders_delivery_timeslot()
{
    printf(
        '<input type="number" id="woofood_maximum_orders_delivery_timeslot" name="woofood_options[woofood_maximum_orders_delivery_timeslot]" value="%s" />',
        isset( $this->options_woofood['woofood_maximum_orders_delivery_timeslot'] ) ? esc_attr( $this->options_woofood['woofood_maximum_orders_delivery_timeslot']) : ''
        );
}

public function wf_delivery_date_up_to_days_option()
{
    printf(
        '<input type="number" id="woofood_delivery_date_up_to_days" name="woofood_options[woofood_delivery_date_up_to_days]" value="%s" />',
        isset( $this->options_woofood['woofood_delivery_date_up_to_days'] ) ? esc_attr( $this->options_woofood['woofood_delivery_date_up_to_days']) : ''
        );
}

public function wf_pickup_date_up_to_days_option()
{
    printf(
        '<input type="number" id="woofood_pickup_date_up_to_days" name="woofood_options[woofood_pickup_date_up_to_days]" value="%s" />',
        isset( $this->options_woofood['woofood_pickup_date_up_to_days'] ) ? esc_attr( $this->options_woofood['woofood_pickup_date_up_to_days']) : ''
        );
}

public function wf_maximum_orders_pickup_timeslot()
{
    printf(
        '<input type="number" id="woofood_maximum_orders_pickup_timeslot" name="woofood_options[woofood_maximum_orders_pickup_timeslot]" value="%s" />',
        isset( $this->options_woofood['woofood_maximum_orders_pickup_timeslot'] ) ? esc_attr( $this->options_woofood['woofood_maximum_orders_pickup_timeslot']) : ''
        );
}


public function wf_google_api_key_callback()
{
    printf(
        '<input type="text" id="woofood_google_api_key" name="woofood_options[woofood_google_api_key]" value="%s" />',
        isset( $this->options_woofood['woofood_google_api_key'] ) ? esc_attr( $this->options_woofood['woofood_google_api_key']) : ''
        );
}

public function wf_google_distance_matrix_api_key_callback()
{
    printf(
        '<input type="text" id="woofood_google_distance_matrix_api_key" name="woofood_options[woofood_google_distance_matrix_api_key]" value="%s" />',
        isset( $this->options_woofood['woofood_google_distance_matrix_api_key'] ) ? esc_attr( $this->options_woofood['woofood_google_distance_matrix_api_key']) : ''
        );
}

public function wf_max_delivery_distance_callback()
{       
$this->options_woofood['woofood_distance_type'] = isset($this->options_woofood['woofood_distance_type']) ? $this->options_woofood['woofood_distance_type'] : "default";
 $store_lat = 0;
$store_lng = 0;
              if(!empty($this->options_woofood['woofood_store_address']) && $this->options_woofood['woofood_distance_type'] === "polygon")
              {
                            $details = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($this->options_woofood['woofood_store_address'])."&key=".$this->options_woofood['woofood_google_distance_matrix_api_key']."";
                      $details = htmlspecialchars_decode($details);
                      $details = str_replace("&amp;", "&", $details );
                      $json = woofood_get_contents($details);
                      $details = json_decode($json, TRUE);
                     
                      if(!empty($details["error_message"]))
                      {

                        echo '<div class="woofood-error">'.$details["error_message"].'</div>';
                        
                      }




                     elseif ( !empty($details['results'][0]['geometry']['location']["lat"] ) && !empty($details['results'][0]['geometry']['location']["lng"] ))
                      {
                         $store_lat = floatval($details['results'][0]['geometry']['location']["lat"]);
                      $store_lng = floatval($details['results'][0]['geometry']['location']["lng"]) ;
                      }

              }
              
  ?>

  <select id="woofood_distance_type" name="woofood_options[woofood_distance_type]">
<option value="default" <?php if($this->options_woofood['woofood_distance_type'] =="default") {echo " selected" ;} ?>><?php esc_html_e("Distance in km (Default)", "woofood-plugin"); ?></option>
<option value="polygon" <?php if($this->options_woofood['woofood_distance_type'] =="polygon") {echo " selected" ;} ?>><?php esc_html_e("Design Area", "woofood-plugin"); ?></option>
<option value="postalcode" <?php if($this->options_woofood['woofood_distance_type'] =="postalcode") {echo " selected" ;} ?>><?php esc_html_e("Limited by Postal Codes", "woofood-plugin"); ?></option>


</select>
<style>
  .woofood-error
  { width: auto;
    padding: 10px;
    background: red;
    color: white;
    margin-bottom: 10px;
    border-left: 5px solid black;

  }
</style>
<?php
    printf(
        '<input type="text" id="woofood_max_delivery_distance" name="woofood_options[woofood_max_delivery_distance]" value="%s" placeholder="'.esc_html__('Inserisci distanza (in km)', '').'"  />',
        isset( $this->options_woofood['woofood_max_delivery_distance'] ) ? esc_attr( $this->options_woofood['woofood_max_delivery_distance']) : ''
        );
      printf(
        '<input type="text" id="woofood_postalcodes" name="woofood_options[woofood_postalcodes]" value="%s" placeholder="'.esc_html__('CAP (Separati da virgola)', '').'"  />',
        isset( $this->options_woofood['woofood_postalcodes'] ) ? esc_attr( $this->options_woofood['woofood_postalcodes']) : ''
        );

      printf(
        '<input type="hidden" id="woofood_polygon_area" name="woofood_options[woofood_polygon_area]" value="%s"  />',
        isset( $this->options_woofood['woofood_polygon_area'] ) ? esc_attr( $this->options_woofood['woofood_polygon_area']) : ''
        );
        ?>
            <div class="woofood_polygon_wrapper">
              <div class="woofood_polygon_header" style="
    padding: 10px;
    background: #eee;
    border-top: 1px solid #00000063;
    border-left: 1px solid #00000063;
    border-right: 1px solid #00000063;
    display: flex;
    flex-wrap: wrap;
">


                <a class="button " id="clearPolygon"><?php esc_html_e('Clear Map', 'woofood-plugin'); ?></a>
              </div>
            <div id="map"  class="woofood_polygon_map" style="position: relative;overflow: hidden;width: 100%;height: auto;"></div>
          </div>
            <script>
jQuery( document ).ready(function() {
  if(jQuery('#woofood_distance_type').val() === "polygon" )

{
  jQuery("#woofood_max_delivery_distance").css("display", "none");
            jQuery(".woofood_polygon_wrapper").css("display", "block");
            jQuery('#map').css({'height':jQuery('#map').width()/2+'px'});
                    jQuery("#woofood_postalcodes").css("display", "none");


            initMap();
}
else if(jQuery('#woofood_distance_type').val() === "postalcode")
{
  jQuery(".woofood_polygon_wrapper").css("display", "none");
    jQuery("#woofood_max_delivery_distance").css("display", "none");
    jQuery("#woofood_postalcodes").css("display", "block");

}
else
{
  jQuery(".woofood_polygon_wrapper").css("display", "none");
    jQuery("#woofood_max_delivery_distance").css("display", "block");
        jQuery("#woofood_postalcodes").css("display", "none");

}


   jQuery('#woofood_distance_type').on('change', function() {
  
  if(this.value == "polygon")
  {
    jQuery("#woofood_max_delivery_distance").css("display", "none");
            jQuery(".woofood_polygon_wrapper").css("display", "block");
                jQuery("#woofood_postalcodes").css("display", "none");

            initMap();
            jQuery('#map').css({'height':jQuery('#map').width()/2+'px'});


  }
  else if(this.value  === "postalcode")
{
  jQuery(".woofood_polygon_wrapper").css("display", "none");
    jQuery("#woofood_max_delivery_distance").css("display", "none");
    jQuery("#woofood_postalcodes").css("display", "block");

}
  else
  {
        jQuery(".woofood_polygon_wrapper").css("display", "none");
    jQuery("#woofood_max_delivery_distance").css("display", "block");
    jQuery("#woofood_postalcodes").css("display", "none");


  }
});
});
 var selectedShape;
 var drawingManager;
 var map;
 var all_overlays = [];
 var polygon_json_string = '<?php echo $this->options_woofood['woofood_polygon_area']; ?>';
 var alreadypolygon;

 if(polygon_json_string)
 {
   var polygons_exists = JSON.parse(polygon_json_string);
 console.log(polygons_exists);
 }

 


 function initMap() {
   

  
  

  var map = new google.maps.Map(document.getElementById('map'), {
    <?php if ($store_lat) :?>
    center: {
      lat: <?php echo $store_lat; ?>,
      lng: <?php echo $store_lng; ?>
    },
        <?php endif;?>

    zoom: 10
  });

   google.maps.Polygon.prototype.getBounds = function() {
    var bounds = new google.maps.LatLngBounds();
    var paths = this.getPaths();
    var path;        
    for (var i = 0; i < paths.getLength(); i++) {
        path = paths.getAt(i);
        for (var ii = 0; ii < path.getLength(); ii++) {
            bounds.extend(path.getAt(ii));
        }
    }
    return bounds;
}

 

  if(Array.isArray(polygons_exists))
  {
   alreadypolygon = new google.maps.Polygon({
    paths: polygons_exists,
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.35,
      editable: true
  });
  alreadypolygon.setMap(map);
map.fitBounds(alreadypolygon.getBounds());
    overlayClickListener(alreadypolygon);


      google.maps.event.addListener(alreadypolygon, 'click', function() {
        setSelection(alreadypolygon);
      });
      setSelection(alreadypolygon);

  }


  drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: google.maps.drawing.OverlayType.POLYGON,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: ['polygon'],

    },
    polygonOptions: {
      editable: true,
      strokeWeight: 0,
    fillOpacity: 0.65,
    fillColor: "#cc0000"
    }

  });
  drawingManager.setMap(map);


    




  


  
  jQuery('#enablePolygon').click(function() {
    drawingManager.setMap(map);
    drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
  });

  jQuery('#clearPolygon').click(function() {
    if (selectedShape) {
      selectedShape.setMap(null);
    }
    if(alreadypolygon)
    {      alreadypolygon.setMap(null);


    }
  });

  jQuery('#cleapMap').click(function() {
    if (selectedShape) {
      selectedShape.setMap(null);
    }
    drawingManager.setMap(null);
    jQuery('#showonPolygon').hide();
    jQuery('#resetPolygon').hide();
  });
  google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
    all_overlays.push(polygon);

     if (selectedShape) {
      selectedShape.setMap(null);
    }
    if(alreadypolygon)
    {      alreadypolygon.setMap(null);

      
    }

    overlayClickListener(polygon);


      google.maps.event.addListener(polygon, 'click', function() {
        setSelection(polygon);
      });
      setSelection(polygon);
    //  var area = google.maps.geometry.spherical.computeArea(selectedShape.getPath());
    //  $('#areaPolygon').html(area.toFixed(2)+' Sq meters');
   
    var polygonBounds = polygon.getPath();
            var bounds = [];
    for (var i = 0; i < polygonBounds.length; i++) {
          var point = {
            lat: polygonBounds.getAt(i).lat(),
            lng: polygonBounds.getAt(i).lng()
          };
          bounds.push(point);
     }
     alert(bounds[0]["lat"]);

     jQuery('#woofood_polygon_area').val(JSON.stringify(bounds));


  });

 /* google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
    all_overlays.push(e);

                overlayClickListener(e.overlay);



    if (e.type != google.maps.drawing.OverlayType.MARKER) {
      // Switch back to non-drawing mode after drawing a shape.
      drawingManager.setDrawingMode(null);

      // Add an event listener that selects the newly-drawn shape when the user
      // mouses down on it.
      var newShape = e.overlay;
      newShape.type = e.type;
      google.maps.event.addListener(newShape, 'click', function() {
        setSelection(newShape);
      });
      setSelection(newShape);
    }

   

  });*/

function overlayClickListener(overlay) {
    google.maps.event.addListener(overlay, "mouseup", function(event){

            var polygonBounds = overlay.getPath();
            var bounds = [];
    for (var i = 0; i < polygonBounds.length; i++) {
          var point = {
            lat: polygonBounds.getAt(i).lat(),
            lng: polygonBounds.getAt(i).lng()
          };
          bounds.push(point);
     }
     alert(bounds[0]["lat"]);
          jQuery('#woofood_polygon_area').val(JSON.stringify(bounds));

    });
}
  function clearSelection() {
    if (selectedShape) {
      selectedShape.setEditable(false);
      selectedShape = null;
    }
  }


  function setSelection(shape) {
    clearSelection();
    selectedShape = shape;
    shape.setEditable(true);
  }

/*  google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
    event.overlay.set('editable', false);
    drawingManager.setMap(null);
    console.log(event.overlay);
  });*/



}

    </script>


        <?php
}



public function wf_store_address_callback()
{
    printf(
        '<input type="text" id="woofood_store_address" name="woofood_options[woofood_store_address]" value="%s" onFocus="geolocate()" />',
        isset( $this->options_woofood['woofood_store_address'] ) ? esc_attr( $this->options_woofood['woofood_store_address']) : ''
        );
}

public function wf_push_notifications_key_callback()
{
    printf(
        '<input type="text" id="woofood_push_notifications_key" name="woofood_options_push_notifications[woofood_push_notifications_key]" value="%s" />',
        isset( $this->options_woofood_push_notifications['woofood_push_notifications_key'] ) ? esc_attr( $this->options_woofood_push_notifications['woofood_push_notifications_key']) : ''
        );
}


public function wf_push_notifications_settings_completed_callback()
{

    printf(
        '<input type="checkbox" id="woofood_push_notifications_completed_enabled" name="woofood_options_push_notifications[woofood_push_notifications_completed_enabled]" value="1" '. checked( 1, isset($this->options_woofood_push_notifications['woofood_push_notifications_completed_enabled']), false ) .' />',
        isset( $this->options_woofood_push_notifications['woofood_push_notifications_completed_enabled'] ) ? esc_attr( $this->options_woofood_push_notifications['woofood_push_notifications_completed_enabled']) : ''
        );

    printf(
        '<input type="text" id="woofood_push_notifications_completed_message" placeholder="Your Message ......." name="woofood_options_push_notifications[woofood_push_notifications_completed_message]" value="%s" />',
        isset( $this->options_woofood_push_notifications['woofood_push_notifications_completed_message'] ) ? esc_attr( $this->options_woofood_push_notifications['woofood_push_notifications_completed_message']) : ''
        );
}




public function wf_delivery_hours_monday_from_callback()
{

    printf(
        '<input type="text" id="woofood_delivery_hours_monday_start" name="woofood_options_delivery_hours[woofood_delivery_hours_monday_start]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_monday_start'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_monday_start']) : ''
        );
}


public function wf_delivery_hours_monday_to_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_monday_end" name="woofood_options_delivery_hours[woofood_delivery_hours_monday_end]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_monday_end'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_monday_end']) : ''
        );
}



public function wf_delivery_hours_tuesday_from_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_tuesday_start" name="woofood_options_delivery_hours[woofood_delivery_hours_tuesday_start]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_tuesday_start'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_tuesday_start']) : ''
        );
}


public function wf_delivery_hours_tuesday_to_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_tuesday_end" name="woofood_options_delivery_hours[woofood_delivery_hours_tuesday_end]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_tuesday_end'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_tuesday_end']) : ''
        );
}


public function wf_delivery_hours_wednesday_from_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_wednesday_start" name="woofood_options_delivery_hours[woofood_delivery_hours_wednesday_start]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_wednesday_start'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_wednesday_start']) : ''
        );
}


public function wf_delivery_hours_wednesday_to_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_wednesday_end" name="woofood_options_delivery_hours[woofood_delivery_hours_wednesday_end]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_wednesday_end'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_wednesday_end']) : ''
        );
}

public function wf_delivery_hours_thursday_from_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_thursday_start" name="woofood_options_delivery_hours[woofood_delivery_hours_thursday_start]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_thursday_start'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_thursday_start']) : ''
        );
}


public function wf_delivery_hours_thursday_to_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_thursday_end" name="woofood_options_delivery_hours[woofood_delivery_hours_thursday_end]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_thursday_end'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_thursday_end']) : ''
        );
}


public function wf_delivery_hours_friday_from_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_friday_start" name="woofood_options_delivery_hours[woofood_delivery_hours_friday_start]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_friday_start'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_friday_start']) : ''
        );
}


public function wf_delivery_hours_friday_to_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_friday_end" name="woofood_options_delivery_hours[woofood_delivery_hours_friday_end]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_friday_end'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_friday_end']) : ''
        );
}


public function wf_delivery_hours_saturday_from_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_saturday_start" name="woofood_options_delivery_hours[woofood_delivery_hours_saturday_start]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_saturday_start'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_saturday_start']) : ''
        );
}


public function wf_delivery_hours_saturday_to_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_saturday_end" name="woofood_options_delivery_hours[woofood_delivery_hours_saturday_end]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_saturday_end'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_saturday_end']) : ''
        );
}


public function wf_delivery_hours_sunday_from_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_sunday_start" name="woofood_options_delivery_hours[woofood_delivery_hours_sunday_start]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_sunday_start'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_sunday_start']) : ''
        );
}


public function wf_delivery_hours_sunday_to_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_sunday_end" name="woofood_options_delivery_hours[woofood_delivery_hours_sunday_end]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_sunday_end'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_sunday_end']) : ''
        );
}


public function wf_delivery_hours_monday_from2_callback()
{

    printf(
        '<input type="text" id="woofood_delivery_hours_monday_start2" name="woofood_options_delivery_hours[woofood_delivery_hours_monday_start2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_monday_start2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_monday_start2']) : ''
        );
}


public function wf_delivery_hours_monday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_monday_end2" name="woofood_options_delivery_hours[woofood_delivery_hours_monday_end2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_monday_end2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_monday_end2']) : ''
        );
}



public function wf_delivery_hours_tuesday_from2_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_tuesday_start2" name="woofood_options_delivery_hours[woofood_delivery_hours_tuesday_start2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_tuesday_start2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_tuesday_start2']) : ''
        );
}


public function wf_delivery_hours_tuesday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_tuesday_end2" name="woofood_options_delivery_hours[woofood_delivery_hours_tuesday_end2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_tuesday_end2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_tuesday_end2']) : ''
        );
}


public function wf_delivery_hours_wednesday_from2_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_wednesday_start2" name="woofood_options_delivery_hours[woofood_delivery_hours_wednesday_start2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_wednesday_start2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_wednesday_start2']) : ''
        );
}


public function wf_delivery_hours_wednesday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_wednesday_end2" name="woofood_options_delivery_hours[woofood_delivery_hours_wednesday_end2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_wednesday_end2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_wednesday_end2']) : ''
        );
}

public function wf_delivery_hours_thursday_from2_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_thursday_start2" name="woofood_options_delivery_hours[woofood_delivery_hours_thursday_start2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_thursday_start2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_thursday_start2']) : ''
        );
}


public function wf_delivery_hours_thursday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_thursday_end2" name="woofood_options_delivery_hours[woofood_delivery_hours_thursday_end2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_thursday_end2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_thursday_end2']) : ''
        );
}


public function wf_delivery_hours_friday_from2_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_friday_start2" name="woofood_options_delivery_hours[woofood_delivery_hours_friday_start2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_friday_start2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_friday_start2']) : ''
        );
}


public function wf_delivery_hours_friday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_friday_end2" name="woofood_options_delivery_hours[woofood_delivery_hours_friday_end2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_friday_end2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_friday_end2']) : ''
        );
}


public function wf_delivery_hours_saturday_from2_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_saturday_start2" name="woofood_options_delivery_hours[woofood_delivery_hours_saturday_start2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_saturday_start2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_saturday_start2']) : ''
        );
}


public function wf_delivery_hours_saturday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_saturday_end2" name="woofood_options_delivery_hours[woofood_delivery_hours_saturday_end2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_saturday_end2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_saturday_end2']) : ''
        );
}


public function wf_delivery_hours_sunday_from2_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_sunday_start2" name="woofood_options_delivery_hours[woofood_delivery_hours_sunday_start2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_sunday_start2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_sunday_start2']) : ''
        );
}


public function wf_delivery_hours_sunday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_sunday_end2" name="woofood_options_delivery_hours[woofood_delivery_hours_sunday_end2]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_sunday_end2'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_sunday_end2']) : ''
        );
}

public function wf_delivery_hours_monday_from3_callback()
{

    printf(
        '<input type="text" id="woofood_delivery_hours_monday_start3" name="woofood_options_delivery_hours[woofood_delivery_hours_monday_start3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_monday_start3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_monday_start3']) : ''
        );
}


public function wf_delivery_hours_monday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_monday_end3" name="woofood_options_delivery_hours[woofood_delivery_hours_monday_end3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_monday_end3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_monday_end3']) : ''
        );
}



public function wf_delivery_hours_tuesday_from3_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_tuesday_start3" name="woofood_options_delivery_hours[woofood_delivery_hours_tuesday_start3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_tuesday_start3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_tuesday_start3']) : ''
        );
}


public function wf_delivery_hours_tuesday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_tuesday_end3" name="woofood_options_delivery_hours[woofood_delivery_hours_tuesday_end3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_tuesday_end3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_tuesday_end3']) : ''
        );
}


public function wf_delivery_hours_wednesday_from3_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_wednesday_start3" name="woofood_options_delivery_hours[woofood_delivery_hours_wednesday_start3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_wednesday_start3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_wednesday_start3']) : ''
        );
}


public function wf_delivery_hours_wednesday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_wednesday_end3" name="woofood_options_delivery_hours[woofood_delivery_hours_wednesday_end3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_wednesday_end3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_wednesday_end3']) : ''
        );
}

public function wf_delivery_hours_thursday_from3_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_thursday_start3" name="woofood_options_delivery_hours[woofood_delivery_hours_thursday_start3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_thursday_start3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_thursday_start3']) : ''
        );
}


public function wf_delivery_hours_thursday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_thursday_end3" name="woofood_options_delivery_hours[woofood_delivery_hours_thursday_end3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_thursday_end3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_thursday_end3']) : ''
        );
}


public function wf_delivery_hours_friday_from3_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_friday_start3" name="woofood_options_delivery_hours[woofood_delivery_hours_friday_start3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_friday_start3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_friday_start3']) : ''
        );
}


public function wf_delivery_hours_friday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_friday_end3" name="woofood_options_delivery_hours[woofood_delivery_hours_friday_end3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_friday_end3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_friday_end3']) : ''
        );
}


public function wf_delivery_hours_saturday_from3_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_saturday_start3" name="woofood_options_delivery_hours[woofood_delivery_hours_saturday_start3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_saturday_start3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_saturday_start3']) : ''
        );
}


public function wf_delivery_hours_saturday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_saturday_end3" name="woofood_options_delivery_hours[woofood_delivery_hours_saturday_end3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_saturday_end3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_saturday_end3']) : ''
        );
}


public function wf_delivery_hours_sunday_from3_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_sunday_start3" name="woofood_options_delivery_hours[woofood_delivery_hours_sunday_start3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_sunday_start3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_sunday_start3']) : ''
        );
}


public function wf_delivery_hours_sunday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_delivery_hours_sunday_end3" name="woofood_options_delivery_hours[woofood_delivery_hours_sunday_end3]" value="%s" />',
        isset( $this->options_woofood_delivery_hours['woofood_delivery_hours_sunday_end3'] ) ? esc_attr( $this->options_woofood_delivery_hours['woofood_delivery_hours_sunday_end3']) : ''
        );
}




public function wf_pickup_hours_monday_from_callback()
{

    printf(
        '<input type="text" id="woofood_pickup_hours_monday_start" name="woofood_options_pickup_hours[woofood_pickup_hours_monday_start]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_monday_start'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_monday_start']) : ''
        );
}


public function wf_pickup_hours_monday_to_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_monday_end" name="woofood_options_pickup_hours[woofood_pickup_hours_monday_end]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_monday_end'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_monday_end']) : ''
        );
}



public function wf_pickup_hours_tuesday_from_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_tuesday_start" name="woofood_options_pickup_hours[woofood_pickup_hours_tuesday_start]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_tuesday_start'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_tuesday_start']) : ''
        );
}


public function wf_pickup_hours_tuesday_to_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_tuesday_end" name="woofood_options_pickup_hours[woofood_pickup_hours_tuesday_end]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_tuesday_end'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_tuesday_end']) : ''
        );
}


public function wf_pickup_hours_wednesday_from_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_wednesday_start" name="woofood_options_pickup_hours[woofood_pickup_hours_wednesday_start]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_wednesday_start'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_wednesday_start']) : ''
        );
}


public function wf_pickup_hours_wednesday_to_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_wednesday_end" name="woofood_options_pickup_hours[woofood_pickup_hours_wednesday_end]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_wednesday_end'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_wednesday_end']) : ''
        );
}

public function wf_pickup_hours_thursday_from_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_thursday_start" name="woofood_options_pickup_hours[woofood_pickup_hours_thursday_start]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_thursday_start'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_thursday_start']) : ''
        );
}


public function wf_pickup_hours_thursday_to_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_thursday_end" name="woofood_options_pickup_hours[woofood_pickup_hours_thursday_end]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_thursday_end'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_thursday_end']) : ''
        );
}


public function wf_pickup_hours_friday_from_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_friday_start" name="woofood_options_pickup_hours[woofood_pickup_hours_friday_start]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_friday_start'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_friday_start']) : ''
        );
}


public function wf_pickup_hours_friday_to_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_friday_end" name="woofood_options_pickup_hours[woofood_pickup_hours_friday_end]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_friday_end'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_friday_end']) : ''
        );
}


public function wf_pickup_hours_saturday_from_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_saturday_start" name="woofood_options_pickup_hours[woofood_pickup_hours_saturday_start]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_saturday_start'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_saturday_start']) : ''
        );
}


public function wf_pickup_hours_saturday_to_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_saturday_end" name="woofood_options_pickup_hours[woofood_pickup_hours_saturday_end]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_saturday_end'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_saturday_end']) : ''
        );
}


public function wf_pickup_hours_sunday_from_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_sunday_start" name="woofood_options_pickup_hours[woofood_pickup_hours_sunday_start]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_sunday_start'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_sunday_start']) : ''
        );
}


public function wf_pickup_hours_sunday_to_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_sunday_end" name="woofood_options_pickup_hours[woofood_pickup_hours_sunday_end]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_sunday_end'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_sunday_end']) : ''
        );
}


public function wf_pickup_hours_monday_from2_callback()
{

    printf(
        '<input type="text" id="woofood_pickup_hours_monday_start2" name="woofood_options_pickup_hours[woofood_pickup_hours_monday_start2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_monday_start2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_monday_start2']) : ''
        );
}


public function wf_pickup_hours_monday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_monday_end2" name="woofood_options_pickup_hours[woofood_pickup_hours_monday_end2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_monday_end2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_monday_end2']) : ''
        );
}



public function wf_pickup_hours_tuesday_from2_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_tuesday_start2" name="woofood_options_pickup_hours[woofood_pickup_hours_tuesday_start2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_tuesday_start2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_tuesday_start2']) : ''
        );
}


public function wf_pickup_hours_tuesday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_tuesday_end2" name="woofood_options_pickup_hours[woofood_pickup_hours_tuesday_end2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_tuesday_end2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_tuesday_end2']) : ''
        );
}


public function wf_pickup_hours_wednesday_from2_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_wednesday_start2" name="woofood_options_pickup_hours[woofood_pickup_hours_wednesday_start2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_wednesday_start2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_wednesday_start2']) : ''
        );
}


public function wf_pickup_hours_wednesday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_wednesday_end2" name="woofood_options_pickup_hours[woofood_pickup_hours_wednesday_end2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_wednesday_end2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_wednesday_end2']) : ''
        );
}

public function wf_pickup_hours_thursday_from2_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_thursday_start2" name="woofood_options_pickup_hours[woofood_pickup_hours_thursday_start2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_thursday_start2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_thursday_start2']) : ''
        );
}


public function wf_pickup_hours_thursday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_thursday_end2" name="woofood_options_pickup_hours[woofood_pickup_hours_thursday_end2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_thursday_end2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_thursday_end2']) : ''
        );
}


public function wf_pickup_hours_friday_from2_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_friday_start2" name="woofood_options_pickup_hours[woofood_pickup_hours_friday_start2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_friday_start2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_friday_start2']) : ''
        );
}


public function wf_pickup_hours_friday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_friday_end2" name="woofood_options_pickup_hours[woofood_pickup_hours_friday_end2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_friday_end2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_friday_end2']) : ''
        );
}


public function wf_pickup_hours_saturday_from2_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_saturday_start2" name="woofood_options_pickup_hours[woofood_pickup_hours_saturday_start2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_saturday_start2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_saturday_start2']) : ''
        );
}


public function wf_pickup_hours_saturday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_saturday_end2" name="woofood_options_pickup_hours[woofood_pickup_hours_saturday_end2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_saturday_end2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_saturday_end2']) : ''
        );
}


public function wf_pickup_hours_sunday_from2_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_sunday_start2" name="woofood_options_pickup_hours[woofood_pickup_hours_sunday_start2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_sunday_start2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_sunday_start2']) : ''
        );
}


public function wf_pickup_hours_sunday_to2_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_sunday_end2" name="woofood_options_pickup_hours[woofood_pickup_hours_sunday_end2]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_sunday_end2'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_sunday_end2']) : ''
        );
}

public function wf_pickup_hours_monday_from3_callback()
{

    printf(
        '<input type="text" id="woofood_pickup_hours_monday_start3" name="woofood_options_pickup_hours[woofood_pickup_hours_monday_start3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_monday_start3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_monday_start3']) : ''
        );
}


public function wf_pickup_hours_monday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_monday_end3" name="woofood_options_pickup_hours[woofood_pickup_hours_monday_end3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_monday_end3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_monday_end3']) : ''
        );
}



public function wf_pickup_hours_tuesday_from3_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_tuesday_start3" name="woofood_options_pickup_hours[woofood_pickup_hours_tuesday_start3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_tuesday_start3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_tuesday_start3']) : ''
        );
}


public function wf_pickup_hours_tuesday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_tuesday_end3" name="woofood_options_pickup_hours[woofood_pickup_hours_tuesday_end3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_tuesday_end3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_tuesday_end3']) : ''
        );
}


public function wf_pickup_hours_wednesday_from3_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_wednesday_start3" name="woofood_options_pickup_hours[woofood_pickup_hours_wednesday_start3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_wednesday_start3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_wednesday_start3']) : ''
        );
}


public function wf_pickup_hours_wednesday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_wednesday_end3" name="woofood_options_pickup_hours[woofood_pickup_hours_wednesday_end3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_wednesday_end3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_wednesday_end3']) : ''
        );
}

public function wf_pickup_hours_thursday_from3_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_thursday_start3" name="woofood_options_pickup_hours[woofood_pickup_hours_thursday_start3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_thursday_start3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_thursday_start3']) : ''
        );
}


public function wf_pickup_hours_thursday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_thursday_end3" name="woofood_options_pickup_hours[woofood_pickup_hours_thursday_end3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_thursday_end3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_thursday_end3']) : ''
        );
}


public function wf_pickup_hours_friday_from3_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_friday_start3" name="woofood_options_pickup_hours[woofood_pickup_hours_friday_start3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_friday_start3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_friday_start3']) : ''
        );
}


public function wf_pickup_hours_friday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_friday_end3" name="woofood_options_pickup_hours[woofood_pickup_hours_friday_end3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_friday_end3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_friday_end3']) : ''
        );
}


public function wf_pickup_hours_saturday_from3_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_saturday_start3" name="woofood_options_pickup_hours[woofood_pickup_hours_saturday_start3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_saturday_start3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_saturday_start3']) : ''
        );
}


public function wf_pickup_hours_saturday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_saturday_end3" name="woofood_options_pickup_hours[woofood_pickup_hours_saturday_end3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_saturday_end3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_saturday_end3']) : ''
        );
}


public function wf_pickup_hours_sunday_from3_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_sunday_start3" name="woofood_options_pickup_hours[woofood_pickup_hours_sunday_start3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_sunday_start3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_sunday_start3']) : ''
        );
}


public function wf_pickup_hours_sunday_to3_callback()
{
    printf(
        '<input type="text" id="woofood_pickup_hours_sunday_end3" name="woofood_options_pickup_hours[woofood_pickup_hours_sunday_end3]" value="%s" />',
        isset( $this->options_woofood_pickup_hours['woofood_pickup_hours_sunday_end3'] ) ? esc_attr( $this->options_woofood_pickup_hours['woofood_pickup_hours_sunday_end3']) : ''
        );
}




} // Class ends here






/** 
* Get the settings option array and print one of its values
*/

if( is_admin() )

    $woofood_settings = new WooFood_Settings();

