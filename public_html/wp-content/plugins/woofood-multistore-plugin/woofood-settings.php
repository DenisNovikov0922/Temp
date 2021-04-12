<?php
class WooFood_Multistore_Settings
{
/**
* Holds the values to be used in the fields callbacks
*/
private $options_woofood;
private $options_woofood_multistore;



/**
* Start up
*/

public function __construct()
{
    add_action( 'admin_menu', array( $this, 'add_plugin_page' ), 2 );
    add_action( 'admin_init', array( $this, 'page_init' ) );

}

/**
* Add options page
*/
public function add_plugin_page()
{


   
//  add_submenu_page( 'woofood-options', esc_html__('Stores', 'woofood-multistore-plugin'), esc_html__('Stores', 'woofood-plugin'), 'manage_woocommerce', 'edit.php?post_type=extra_store');
   add_submenu_page( 'woofood-options', esc_html__('Stores Settings', 'woofood-plugin'), esc_html__('Multistore Settings', 'woofood-plugin'), 'manage_woocommerce', 'woofood-multistore-settings', array( $this, 'woofood_stores_settings' ));



}

 public function woofood_isvalidjson($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}





function wf_load_admin_css() {

wp_enqueue_style( 'woofood_css_admin', plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), '1.0.0', 'all' );

}









public function woofood_stores_settings()
{
// Set class property
    $this->options_woofood = get_option( 'woofood_options' );
    $this->options_woofood_multistore = get_option( 'woofood_options_multistore' );
wp_enqueue_style( 'woofood_css_admin', plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), '1.0.0', 'all' );
wp_enqueue_script( 'woofood_multistore_js_admin', plugin_dir_url( __FILE__ ) . 'js/main.js', array(), '1.0.0', 'all' );

   wp_localize_script( 'woofood_multistore_js_admin', 'ajaxwfmultistore', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        


$google_api_key = isset($this->options_woofood['woofood_google_api_key']) ? $this->options_woofood['woofood_google_api_key'] : null;
    if( $google_api_key)
    {
        wp_enqueue_script('google-js-api', 'https://maps.googleapis.com/maps/api/js?libraries=places,drawing,geometry&key='.$google_api_key.'&language='.substr(get_bloginfo ( 'language' ), 0, 2).'');
    wp_enqueue_script(  'woofood_js_google', plugin_dir_url( null ) . '/woofood-plugin/js/autocomplete_address.js' , array(), '1.0.0', 'all' );

    }

    ?>
    <div class="wrap">


       
        <?php if( isset($_GET['settings-updated']) ) { ?>
        <div id="message" class="updated">
            <p><strong><?php esc_html_e('Settings Updated.', 'woofood-plugin') ?></strong></p>
        </div>
        <?php } ?>

        <?php settings_errors($this); ?>
     <?php 
          
    ?>
    <div class="woofood-multistore header-actions">
        <form class="woofood_multistore_new">

<input type="hidden" name="action" value="woofood_multistore_add_new_store" />
<input type="hidden" name="store_id" value="<?php echo $store_id; ?>" />
    <button type="submit" class="button"><?php esc_html_e('Add New Store', 'woofood-multistore-plugin'); ?></button>

      </form>
    </div>
    <div class="woofood_multistore_store_list">
    <?php woofood_multistore_list_stores(); ?>
    </div>
    <?php


        ?>

        <form method="post" action="options.php">
            <?php
            
                settings_fields( 'woofood_settings_stores' );
                do_settings_sections( 'woofood_settings_stores_page' );
           
            submit_button(); 
            ?>
        </form>






    </div><!--#wrap -->


            


    <?php
}





/**
* Register and add settings
*/
public function page_init()
{        
     

register_setting(
'woofood_settings_stores', // Option group
'woofood_options_multistore', // Option name
array( $this, 'sanitize_store_settings' ) // Sanitize
);



add_settings_section(
'setting_section_stores', 
esc_html__("Enable Automatic Store Selection", "woofood-multistore-plugin"), // Title
array( $this, 'print_wf_stores_settings' ), // Callback
'woofood_settings_stores_page' // Page
); 

  
    add_settings_field(
'woofood_auto_store_select', 
esc_html__("Enable", "woofood-multistore-plugin"), // Title 
array( $this, 'wf_stores_settings_callback' ), // Callback
'woofood_settings_stores_page', // Page
'setting_section_stores' // Section           
); 



} //page init closing here


public function sanitize_store_settings( $input )
{
    $new_input = array();


    if( isset( $input['woofood_auto_store_select'] ) )
    {
        $new_input['woofood_auto_store_select'] =  $input['woofood_auto_store_select'];
    }
    else
    {
        $new_input['woofood_auto_store_select'] = "0";
    }




    return $new_input;
}




public function print_wf_stores_settings()
{
    
    esc_html_e('Enable this option to send the order to the nearest store:', 'woofood-plugin');


}






public function wf_stores_settings_callback()
{
    wp_enqueue_style( 'woofood_css_admin_time_picker', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.css', array(), '1.0.0', 'all' );
wp_enqueue_script('woofood_js_admin_time_picker', plugin_dir_url(__FILE__).'js/jquery.timepicker.min.js', array());
wp_enqueue_script('woofood_js_admin_delivery_hours', plugin_dir_url(__FILE__).'js/delivery_hours.js', array());
    printf(
        '<input type="checkbox" id="woofood_auto_store_select" name="woofood_options_multistore[woofood_auto_store_select]" value="1" '. checked( 1, $this->options_woofood_multistore['woofood_auto_store_select'], false ) .' />',
        isset( $this->options_woofood_multistore['woofood_auto_store_select'] ) ? esc_attr( $this->options_woofood_multistore['woofood_auto_store_select']) : ''
        );



}











} // Class ends here

/** 
* Get the settings option array and print one of its values
*/

if( is_admin() )

    $WooFood_Multistore_Settings = new WooFood_Multistore_Settings();

