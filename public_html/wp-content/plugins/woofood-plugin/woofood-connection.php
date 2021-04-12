<?php
/*
Plugin Name: WooFood for WooCommerce
Plugin URI: https://www.wpslash.com/plugin/woofood/
Description: Online Delivery Plugin for WooCommerce
Author: WPSlash
Version: 2.4.4
Author URI: http://www.wpslash.com
 * WC requires at least: 3.0
 * WC tested up to: 4.2
*/

define("WOOFOOD_PLUGIN_URL", plugin_dir_url( __FILE__ ));
define("WOOFOOD_PLUGIN_DIR", plugin_dir_path( __FILE__ ));
define("WOOFOOD_PLUGIN_VERSION", "2.4.4");



remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title',10);
add_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title',10);
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_after_shop_loop_item',  'woocommerce_template_loop_add_to_cart', 10 );

add_action('plugins_loaded', 'wf_load_textdomain');

function wf_load_textdomain() {
/*  if(function_exists("woofood_plugin_is_rtl"))
  {
     define("$woofood_plugin_rtl", woofood_plugin_is_rtl());

  }
  else
  {
      define("$woofood_plugin_rtl", "");

  }*/

    load_plugin_textdomain( 'woofood-plugin', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}



function wf_css(){
  $woofood_plugin_rtl = woofood_plugin_is_rtl();
  wp_enqueue_style( 'woofood_css_plugin', plugin_dir_url( __FILE__ ) . 'css/default'.$woofood_plugin_rtl.'.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );
    wp_enqueue_style( 'woofood_plugin_css_accordion', plugin_dir_url( __FILE__ ) . 'css/accordion'.$woofood_plugin_rtl.'.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );
    wp_enqueue_style( 'woofood_css_tabs', plugin_dir_url( __FILE__ ) . 'css/tabs'.$woofood_plugin_rtl.'.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );
    wp_enqueue_style( 'woofood_plugin_icons', plugin_dir_url( __FILE__ ) . 'css/icons'.$woofood_plugin_rtl.'.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );

    wp_enqueue_style( 'toastify', plugin_dir_url( __FILE__ ) . 'css/toastify.min'.$woofood_plugin_rtl.'.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );

  wp_enqueue_script('main_js_wf', plugin_dir_url(__FILE__).'js/main.js', array('jquery'), WOOFOOD_PLUGIN_VERSION, 'all' );

      wp_enqueue_style( 'flatpickr-css', WOOFOOD_PLUGIN_URL . 'css/flatpickr.min'.$woofood_plugin_rtl.'.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );
    wp_enqueue_script('flatpickr-js', WOOFOOD_PLUGIN_URL.'js/flatpickr.min.js', array('jquery'), WOOFOOD_PLUGIN_VERSION, 'all');

  $theme = wp_get_theme(); // gets the current theme
  if ( 'Avada' != $theme->name && 'Avada' != $theme->parent_theme ) {
     wp_enqueue_script('woofood_accordion_collapse', plugin_dir_url(__FILE__).'js/accordion-collapse.js', array('jquery'), WOOFOOD_PLUGIN_VERSION, 'all');

}
else
{
       wp_enqueue_script('woofood_avada_compatibility', plugin_dir_url(__FILE__).'js/avada_compatibility.js', array('jquery'), WOOFOOD_PLUGIN_VERSION, 'all');
    
           wp_enqueue_style( 'woofood_avada_compatibility_style', plugin_dir_url( __FILE__ ) . 'css/avada_compatibility'.$woofood_plugin_rtl.'.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );


}
if ( 'Divi' == $theme->name || 'Divi' == $theme->parent_theme ||  (strpos($theme->parent_theme, 'DI Basis') !== false) ) {
       wp_enqueue_script('woofood_avada_compatibility', plugin_dir_url(__FILE__).'js/avada_compatibility.js', array('jquery'), WOOFOOD_PLUGIN_VERSION, 'all');

}

    wp_enqueue_script('woofood_tabs_js', plugin_dir_url(__FILE__).'js/tabs-menu.js', array('jquery'), WOOFOOD_PLUGIN_VERSION, 'all');

  wp_enqueue_script('toastify-js', plugin_dir_url(__FILE__).'js/toastify.min.js', array('jquery'), WOOFOOD_PLUGIN_VERSION, 'all');
    wp_enqueue_script(  'micromodal-js', plugin_dir_url(__FILE__).'js/micromodal.js' , array('jquery'), '1.0.0', 'all' );



	wp_localize_script('main_js_wf', 'woofoodmain', array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
          ));


}
function woofood_plugin_is_rtl()
{
                 $woofood_options = get_option('woofood_options');
                 $woofood_enable_rtl = isset($woofood_options['woofood_enable_rtl']) ? $woofood_options['woofood_enable_rtl'] :false ;
                 if($woofood_enable_rtl )
                 {
                  return ".rtl";
                 }
                 return "";
}
 function woofood_plugin_blocks_styles_scripts_admin()
{
    $woofood_plugin_rtl = woofood_plugin_is_rtl();

    wp_enqueue_style( 'woofood_css_plugin', plugin_dir_url( __FILE__ ) . 'css/default'.$woofood_plugin_rtl.'.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );

    wp_enqueue_style( 'woofood_plugin_icons', plugin_dir_url( __FILE__ ) . 'css/icons'.$woofood_plugin_rtl.'.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );

    wp_enqueue_style( 'woofood_css_tabs_admin', plugin_dir_url( __FILE__ ) . 'css/tabs'.$woofood_plugin_rtl.'.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );
    wp_enqueue_style( 'woofood_plugin_css_accordion_admin', plugin_dir_url( __FILE__ ) . 'css/accordion'.$woofood_plugin_rtl.'.css', WOOFOOD_PLUGIN_VERSION, '1.0.0', 'all' );
   

echo "<style>
.editor-writing-flow .wp-block {
  max-width: 1100px;
}
</style>";
   // wp_enqueue_script('woofood_tabs_js_admin', plugin_dir_url(__FILE__).'js/tabs-menu.js', array());
     wp_enqueue_script('woofood_accordion_collapse_admin', plugin_dir_url(__FILE__).'js/accordion-collapse.js', array('jquery'), WOOFOOD_PLUGIN_VERSION, 'all');
?>
<script>

  function add_tab_js()

  {
 var s = document.createElement( 'script' );
  s.setAttribute( 'src', '<?php echo plugin_dir_url(__FILE__)."js/tabs-menu.js"; ?>' );
  document.body.appendChild( s );
  }
 
document.addEventListener('DOMContentLoaded', add_tab_js, false);

document.onreadystatechange = function () {
  add_tab_js();
}


</script>
<?php



}
//add_action( 'enqueue_block_editor_assets', 'woofood_plugin_blocks_styles_scripts_admin' );
add_action('admin_print_styles-post.php', 'woofood_plugin_blocks_styles_scripts_admin');
add_action('admin_print_styles-post-new.php', 'woofood_plugin_blocks_styles_scripts_admin');


add_action('wp_enqueue_scripts', 'wf_css');


require_once( __DIR__ . DIRECTORY_SEPARATOR . 'woofood-settings.php' );

if (!function_exists('get_home_path')) {
    require_once( ABSPATH . '/wp-admin/includes/file.php' );
}

add_theme_support( 'woofood-accordion' );
add_theme_support( 'woofood-quickview' );

add_image_size( 'woofood-accordion', 60, 60, true );
add_image_size( 'woofood-quickview', 450, 300, true );

add_theme_support( 'woofood-accordion', array( 'post' ) ); // Add it for posts
add_theme_support( 'woofood-accordion', array( 'page' ) ); // Add it 
add_theme_support( 'woofood-accordion', array( 'product' ) ); // Add it 

add_theme_support( 'woofood-quickview', array( 'post' ) ); // Add it for posts
add_theme_support( 'woofood-quickview', array( 'page' ) ); // Add it 
add_theme_support( 'woofood-quickview', array( 'product' ) ); // Add it 
if (!function_exists('is_plugin_active_for_network'))
{
  require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

}
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php') ) {

//EXTRA OPTIONS: EVERYTHING IS HERE  //
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/extra_options.php' );

//DELIVERY TIME TO SHOW TO CUSTOMERS  //
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/delivery_time.php' );

//TIME TO DELIVER CHECKOUT FIELD  //
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/time_to_deliver.php' );

//MINIMUM DELIVERY AMOUNT  //
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/minimum_delivery.php' );

//DELIVERY HOURS  //
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/delivery_hours.php' );

//CREATE MULTISELECT FIELD //
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/create_multiselect_field.php' );


//CHECK DELIVERY DISTANCE //
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/distance_check.php' );

//REGISTER CUSTOMER FIELDS ON REGISTRATION//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/register_customer_fields.php' );


//ORDER LIST PANEL FUNCTIONALLITY: AJAX REFRESH ETC //
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/order_list.php' );


//ACCEPT DECLINE ORDERS//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/accept_decline_orders.php' );

//ORDER BUTTONS: ACCEPT, DECLINE, COMPLETE//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/order_buttons.php' );
//require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/all-in-one.php' );


//Push Notifications//
//require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/push_notifications.php' );



require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/avada_compatibility.php' );

//rest api old//
include ('inc/woofood_rest_old.php');


//Order Type : Delivery, Pickup//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/order_type.php' );


//Add Extra Columns on Orders//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/extra_columns_order.php' );
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/update.php' );


//Add More Fields to Search on Orders//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/more_search_fields.php' );

//SEARCH BY ORDER TYPE//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/search_by_order_type.php' );
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/transients.php' );


//MUTIPLE ADDRESSES//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/multiple_addresses.php' );

//DOORBELL FIELD//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/doorbell_field.php' );
 

//AJAX ENABLED//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/ajax_enabled.php' );


//HIDE IMAGES//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/hide_images.php' );


//WOOFOOD hide fields//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/hide_fields.php' );

//WOOFOOD REST API//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/woofood_rest.php' );

//WOOFOOD REST API//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/shortcodes.php' );


//WOOFOOD REST API//
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/shortcode.php' );

//gutenblocks
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/blocks/allcategories/index.php' );


require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/delivery_fee.php' );
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/delivery_boys.php' );
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/availability_checker.php' );
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/func/product_availability.php' );





add_action( 'woocommerce_product_query', 'woofood_hide_products_shop_page_pre_get_posts_query' );

function woofood_hide_products_shop_page_pre_get_posts_query( $q ) {
  
  if( is_shop() || is_page('shop') ) { // set conditions here
    $shop_page_id = wc_get_page_id( 'shop' );
    $shop_content = get_post($shop_page_id);
    
    if(has_shortcode( $shop_content->post_content, 'woofood_accordion' ))
    {
       $tax_query = (array) $q->get( 'tax_query' );
  
      $tax_query[] = array(
             'taxonomy' => 'product_cat',
             'field'    => 'slug',
             'terms'    => array( 'somethingthatdoesnotexist' ), // set product categories here
             'operator' => 'IN'
      );
  
  
      $q->set( 'tax_query', $tax_query );
    remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );
  
    }
  
     
  }
}




function woofood_get_contents ($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}






 add_action( 'wp_ajax_wf_extra_option_add_new_ajax', 'wf_extra_option_add_new_ajax' );

 function wf_extra_option_add_new_ajax()
 {

  $category_id = (int)$_POST['category_id'];
  $name = $_POST['name'];
  $price = $_POST['extra_option_price'];

  // Create post object
$my_post = array(
  'post_title'    => wp_strip_all_tags( $name ),
  'post_content'  => '',
  'post_status'   => 'publish',
  'post_type' => 'extra_option'

);
 
// Insert the post into the database
$extra_option_id = wp_insert_post( $my_post );
if($extra_option_id> 0)
{
  $assign_category = wp_set_post_terms( $extra_option_id, $category_id, "extra_option_categories" );

  if(is_array($assign_category) && !empty( $assign_category))
  {
    update_post_meta($extra_option_id, 'extra_option_price', $price);
    wf_return_extra_option_list_item($extra_option_id);


  }

}


  wp_die();
 }



 add_action( 'wp_ajax_wf_extra_option_remove_ajax', 'wf_extra_option_remove_ajax' );

function wf_extra_option_remove_ajax()
{
  $extra_option_id = (int) $_POST['extra_option_id'];

    wp_delete_post($extra_option_id, true);
    wp_die();
}



 add_action( 'wp_ajax_wf_extra_option_update_ajax', 'wf_extra_option_update_ajax' );

function wf_extra_option_update_ajax()
{
  $extra_option_id = (int) $_POST['extra_option_id'];
  $extra_option_title =  $_POST['extra_option_title'];
  $extra_option_price = $_POST['extra_option_price'];

  $extra_option_post_title = array(
      'ID'           => $extra_option_id,
      'post_title'   => $extra_option_title
  );



// Update the post into the database
  $post_id = wp_update_post( $extra_option_post_title );
  if($post_id>0)
  {
     if(isset($_POST["extra_option_price"]))
     {
          update_post_meta($extra_option_id, 'extra_option_price', $extra_option_price);

     }
      if(isset($_POST["prechecked"]))
     {
          update_post_meta($extra_option_id, 'woofood_prechecked', 1);

     }
     else
     {
                delete_post_meta($extra_option_id, 'woofood_prechecked');


     }


         wf_return_extra_option_list_item($post_id);

  }

   



    wp_die();
}



 add_action( 'wp_ajax_wf_extra_option_update_order_ajax', 'wf_extra_option_update_order_ajax' );

function wf_extra_option_update_order_ajax()
{
  $wf_extra_order = array();
  if(isset($_POST['wf_extra_order']))
  {
      $wf_extra_order = explode(',', $_POST['wf_extra_order']);

      foreach ($wf_extra_order as $order=>$extra_option_id)
       {
        update_post_meta($extra_option_id, '_wf_order', $order);
       // delete_post_meta($extra_option_id, '_wf_order');
      } 

  }



    wp_die();
}


 add_action( 'wp_ajax_wf_extra_option_category_update_ajax', 'wf_extra_option_category_update_ajax' );

function wf_extra_option_category_update_ajax()
{
  if(isset($_POST['wf_extra_category_id']))
  {
    $wf_term_id= (int)$_POST['wf_extra_category_id'];
    $wf_term_title= $_POST['wf_extra_category_title'];
    $wf_term_style= $_POST['wf_extra_category_style'];
    $wf_term_type= $_POST['wf_extra_category_type'];
    $wf_term_maximum_options= (int)$_POST['wf_extra_category_maximum_options'];

   $updated_term_id =  wp_update_term($wf_term_id, 'extra_option_categories', array(
  'name' => $wf_term_title
));

   if($updated_term_id>0)
   {


    $term_meta = get_option( "taxonomy_$wf_term_id" );
  
    
    // Save the option array.





    if(isset($_POST["wf_extra_category_type"]))
    {
              $term_meta["category_type"] = $_POST["wf_extra_category_type"];

    }

     if(isset($_POST["wf_extra_category_style"]))
    {
              $term_meta["category_style"] = $_POST["wf_extra_category_style"];
    }

    if(isset($_POST["wf_extra_category_maximum_options"]))
    {
                    $term_meta["maximum_options"] =(int)$_POST["wf_extra_category_maximum_options"];

    }
     if(isset($_POST["wf_extra_category_minimum_options"]))
    {
                    $term_meta["minimum_options"] =(int)$_POST["wf_extra_category_minimum_options"];

    }
    if(isset($_POST["wf_extra_category_global_categories"]))
    {
                    $term_meta["global_categories"] =$_POST["wf_extra_category_global_categories"];

    }
     if(isset($_POST["wf_extra_option_category_required"]))
    {
                    $term_meta["required"] =$_POST["wf_extra_option_category_required"];

    }
   
    else
    {
      unset($term_meta["required"]);

    }
      if(isset($_POST["wf_extra_option_category_hide_prices"]))
    {
                    $term_meta["hide_prices"] =$_POST["wf_extra_option_category_hide_prices"];

    }
    else
    {
      unset($term_meta["hide_prices"]);

    }

    
    update_option( "taxonomy_$wf_term_id", $term_meta );



   }

  }


wp_die();
}
 function woofood_extra_option_categories_list()
{
 
          $extra_option_categories = get_terms('extra_option_categories' ,  array('hide_empty' => false, 'orderby'=>'name', 'order'=>'ASC'));

           foreach($extra_option_categories as $current_extra_option_category) {    
            echo "<div class='wf_extra_option_category_list_item'>";
          

            echo "<div class='wf_extra_option_category_list_item_name'>";

            echo  $current_extra_option_category->name;

                        echo "</div>";



            echo "<div class='wf_extra_option_category_list_item_buttons'>";

            echo '<a class="wf_extra_option_category edit" cat-id="'.$current_extra_option_category->term_id.'">'.esc_html__('Edit', 'woofood-plugin').'</a>';
            echo '<a class="wf_extra_option_category delete" cat-id="'.$current_extra_option_category->term_id.'">'.esc_html__('Delete', 'woofood-plugin').'</a>';



            echo "</div>";

                 echo "</div>";

           }

      
}
add_action( 'wp_ajax_wf_extra_option_categories_refresh', 'wf_extra_option_categories_refresh' );







function wf_extra_option_categories_refresh()
{
  woofood_extra_option_categories_list();
  wp_die();

}

add_action( 'wp_ajax_wf_extra_option_category_delete', 'wf_extra_option_category_delete' );

function wf_extra_option_category_delete()
{
   if(isset($_POST['wf_extra_category_id']))
  {
    if(wp_delete_term( $_POST['wf_extra_category_id'], 'extra_option_categories' ))
    {
      echo "ok";
    }

  }

wp_die();
}


add_action( 'wp_ajax_wf_extra_option_category_open_popup_ajax', 'wf_extra_option_category_open_popup_ajax' );

function wf_extra_option_category_open_popup_ajax()
{


  $args = array(
         'taxonomy'     => 'product_cat',
         'orderby'      => 'name',
         'show_count'   => 0,
         'pad_counts'   => 0,
         'hierarchical' => 1,
         'title_li'     => '',
         'hide_empty'   => 0
  );
 $product_categories = get_categories( $args );

  $term_id =  null;
  if(isset($_POST['wf_extra_category_id']))
  {
    ?>


    <?php
    if($_POST["wf_extra_category_id"] == "new")
    {
     $term_id_response =  wp_insert_term(
    'New Extra Option Category',   // the term 
    'extra_option_categories', // the taxonomy
    array(
        'description' => '',
        'slug' => 'extra-option-cat-'.rand ( 100000 , 9999999 )
      )
);
    if(is_array($term_id_response))
    {
      $term_id = $term_id_response['term_id'];

    }

    }
    else
    {
        $term_id = (int) $_POST['wf_extra_category_id'];

    }
  $term_meta = get_option( "taxonomy_$term_id" );
  $term = get_term( $term_id, "extra_option_categories" );
  $name = $term->name;

  if(!array_key_exists("category_style", $term_meta))
  {
  $term_meta["category_style"] = "accordion";
  }

   if(!array_key_exists("category_type", $term_meta))
  {
  $term_meta["category_type"] = "checkbox-multichoice";
  }

   if(!array_key_exists("maximum_options", $term_meta))
  {
  $term_meta["maximum_options"] = "";
  }
  if(!array_key_exists("minimum_options", $term_meta))
  {
  $term_meta["minimum_options"] = "";
  }
   if(!array_key_exists("required", $term_meta))
  {
  $term_meta["required"] = null;
  }
   if(!array_key_exists("hide_prices", $term_meta))
  {
  $term_meta["hide_prices"] = null;
  }
  if(!array_key_exists("global_categories", $term_meta))
  {
  $term_meta["global_categories"] = array();

  //end compatiblity with old extra options//

$args_extr = array(
  'numberposts' => -1,
  'post_type'   => 'extra_option',
    'fields' => 'ids',

  


         'orderby' => array( 'meta_value_num' => 'ASC', 'ID' => 'ASC' ),
    'order' => 'ASC',
    'meta_query' => array(
        'relation' => 'OR',
        array( 
            'key'=>'_wf_order',
            'compare' => 'EXISTS'           
        ),
        array( 
            'key'=>'_wf_order',
            'compare' => 'NOT EXISTS'           
        )
    ),


  'tax_query' => array(
    
    array(
        'taxonomy' => 'extra_option_categories',
        'field'    => 'term_id',
        'terms'    => $term_id,
    ),
),
 
);

$all_extra_options = get_posts( $args_extr );
$global_extra_option_categories_old = array();
foreach($all_extra_options as $current_extra)
{
 $terms =  get_the_terms($current_extra, "product_cat");
  if(is_array($terms))
  {
    $extra_option_terms = array();
    foreach($terms as $term)
    {
      $global_extra_option_categories_old[] = $term->term_id;
      $extra_option_terms[] = $term->term_id;


    }
    //remove old associated product categories from extra option//
    wp_remove_object_terms( $current_extra, $extra_option_terms, 'product_cat' );
  }

}
if(is_array($global_extra_option_categories_old) && !empty($global_extra_option_categories_old))
{
  $global_extra_option_categories_old = array_unique($global_extra_option_categories_old);
  $term_meta["global_categories"] = $global_extra_option_categories_old;
  update_term_meta( $term_id, "global_categories", $global_extra_option_categories_old );


}
//end compatiblity with old extra options//
  }



   ?>

  <div class="wf_extra_option_edit_popup_header">

    <div class="wf_extra_option_edit_popup_header_title">
   <?php echo $name; ?>
  </div> 
  <div class="wf_extra_option_edit_popup_header_close">
  X
</div>   
</div>


<div class="wf_extra_option_edit_popup_content">
 

 <div class="wf_extra_option_edit_popup_content_settings">

<div class="wf_option_wrapper"> 
  <label><?php esc_html_e('Group Title', 'woofood-plugin'); ?></label>
<input type="text" placeholder="Extra Option Category Title" name="name" id="wf_extra_option_category_title" value="<?php echo $name; ?>" /> 
</div>

 <div class="wf_option_wrapper"> 
  <label><?php esc_html_e('Type', 'woofood-plugin'); ?></label>
 <select name="category_type" id="wf_extra_option_category_type">
  <option value="checkbox-multichoice" <?php if (esc_attr( $term_meta['category_type']=="checkbox-multichoice" )){ echo " selected";}  ?>><?php esc_html_e('Checkbox Multichoice', 'woofood-plugin'); ?></option>
    <option value="checkbox-limitedchoice" <?php if (esc_attr( $term_meta['category_type']=="checkbox-limitedchoice" )){ echo " selected";}  ?>><?php esc_html_e('Limited Choice', 'woofood-plugin'); ?></option>
    <option value="radio" <?php if (esc_attr( $term_meta['category_type']=="radio" )){ echo " selected";}  ?>><?php esc_html_e('Single Choice Radio', 'woofood-plugin'); ?></option>
    <option value="select" <?php if (esc_attr( $term_meta['category_type']=="select" )){ echo " selected";}  ?>><?php esc_html_e('Single Choice Select', 'woofood-plugin'); ?></option>

 </select> 
</div>







</div>
 <div class="wf_extra_option_edit_popup_content_settings">


 <div class="wf_option_wrapper"> 
  <label><?php esc_html_e('Style', 'woofood-plugin'); ?></label>
 <select name="category_style" id="wf_extra_option_category_style">
  <option value="accordion" <?php if (esc_attr( $term_meta['category_style']=="accordion" )){ echo " selected";}  ?>><?php esc_html_e('Accordion(Default)', 'woofood-plugin'); ?></option>
    <option value="flat" <?php if (esc_attr( $term_meta['category_style']=="flat" )){ echo " selected";}  ?>><?php esc_html_e('Flat', 'woofood-plugin'); ?></option>

 </select> 
</div>

 <div class="wf_option_wrapper wf_maximum_options_wrapper <?php if (!esc_attr( $term_meta['category_type']=="checkbox-multichoice" ) && !esc_attr( $term_meta['category_type']=="checkbox-limitedchoice" ) ){ echo " hidden";}  ?>"> 
  <label><?php esc_html_e('Maximum Options', 'woofood-plugin'); ?></label>
  <input type="number" min="0" step="1" name="maximum_options" value="<?php echo $term_meta['maximum_options']; ?>" id="wf_extra_option_category_maximum_options" />
</div>



</div>


 <div class="wf_extra_option_edit_popup_content_settings required">

<div class="wf_option_wrapper"> 
  <label><?php esc_html_e('Required', 'woofood-plugin'); ?></label>
  <input type="checkbox" name="required" value="1" id="wf_extra_option_category_required" <?php if(isset($term_meta['required'])) {echo "checked";} ?>  />
</div>
<div class="wf_option_wrapper"> 
  <label><?php esc_html_e('Hide Prices', 'woofood-plugin'); ?></label>
  <input type="checkbox" name="hide_prices" value="1" id="wf_extra_option_category_hide_prices" <?php if(isset($term_meta['hide_prices'])) {echo "checked";} ?>  />
</div>


<div class="wf_option_wrapper wf_minimum_options_wrapper <?php if (!esc_attr( $term_meta['category_type']=="checkbox-multichoice" ) && !esc_attr( $term_meta['category_type']=="checkbox-limitedchoice" ) ){ echo " hidden";}  ?>"> 
  <label><?php esc_html_e('Minimum Options Required', 'woofood-plugin'); ?></label>
  <input type="number" min="0" step="1" name="minimum_options" value="<?php echo $term_meta['minimum_options']; ?>" id="wf_extra_option_category_minimum_options" />
</div>
</div>

 <div class="wf_extra_option_edit_popup_content_settings wide"> 
   <div class="wf_option_wrapper wide"> 

  <label><?php esc_html_e('Global Product Categories Enabled', 'woofood-plugin'); ?></label>
  <select multiple name="global_categories[]"  id="wf_extra_option_global_categories">
    <?php foreach ($product_categories as $cat) :?>
      <option value="<?php echo $cat->term_id; ?>" <?php if ($term_meta && is_array($term_meta['global_categories']) &&  in_array($cat->term_id, $term_meta['global_categories'])){ echo " selected";}  ?>><?php echo $cat->name; ?></option>
    <?php endforeach;?>

  </select>
</div>
</div>


 <div class="wf_extra_option_edit_popup_content_settings">
  <a class="wf-add-extra-button"><?php esc_html_e('Add New Extra', 'woofood-plugin'); ?></a>
</div>

<div class="wf_add_new_extra_option_settings">
  <form class="wf_extra_manage_new_option_form" method="POST">
  <div class="wf_add_new_extra_option_settings_content">

   <div class="wf_option_wrapper"> 
  <label><?php esc_html_e('Name', 'woofood-plugin'); ?></label>
<input type="text" placeholder="Topping name.."name="name"  /> 
</div>
 <div class="wf_option_wrapper"> 
  <label><?php esc_html_e('Price', 'woofood-plugin'); ?></label>
<input type="number" placeholder="0.50" step="0.01" name="extra_option_price"  /> 
</div>

 
</div>
 <input type="hidden" name="action" value="wf_extra_option_add_new_ajax"  /> 
  <input type="hidden" name="category_id" value="<?php echo $term_id; ?>" />
<div class="wf_add_new_extra_option_settings_actions">

  <button class="button" type="submit"><?php esc_html_e('Add', 'woofood-plugin');?></button>
 
</div>
</form>

  </div>

<ul class="wf_extra_options_list">

  <?php

   $args = array(
  'numberposts' => -1,
  'post_type'   => 'extra_option',
    'fields' => 'ids',

  


         'orderby' => array( 'meta_value_num' => 'ASC', 'ID' => 'ASC' ),
    'order' => 'ASC',
    'meta_query' => array(
        'relation' => 'OR',
        array( 
            'key'=>'_wf_order',
            'compare' => 'EXISTS'           
        ),
        array( 
            'key'=>'_wf_order',
            'compare' => 'NOT EXISTS'           
        )
    ),


  'tax_query' => array(
    
    array(
        'taxonomy' => 'extra_option_categories',
        'field'    => 'term_id',
        'terms'    => $term_id,
    ),
),
 
);

$all_extra_options = get_posts( $args );

foreach($all_extra_options as $current_extra)
{
 wf_return_extra_option_list_item( $current_extra);

}

  ?>
</ul>




</div>
<div class="wf_extra_option_edit_popup_footer">
  <div class="wf_extra_option_category_remove_wrapper">
      <a class="wf_extra_option_category_remove" cat-id="<?php echo $term_id; ?>"><?php esc_html_e('Remove', 'woofood-plugin'); ?></a>

    </div>

<div class="wf_extra_option_category_save_wrapper">
  <a class="wf_extra_option_category_save" term-id="<?php echo $term_id; ?>"><?php esc_html_e('Save', 'woofood-plugin'); ?></a>
</div>

</div>

    <?php


  }
  wp_die();

}







 function wf_return_extra_option_list_item($current_extra)
 {
   echo '<li class="wf_extra_option_list_item_wrapper" item-id="'.$current_extra.'">';

   echo '<div class="wf_extra_option_list_item">';
 
 echo '<div class="wf_extra_option_list_item_title">';
 echo '<span class="dashicons dashicons-move"></span>';
  echo  get_the_title($current_extra);
   echo '</div>';


   echo '<div class="wf_extra_option_list_actions">';
  echo  '<a class="edit">'.esc_html__('Edit', 'woofood-plugin').'</a>';
    echo  '<a class="remove" id='.$current_extra.'>'.esc_html__('Remove', 'woofood-plugin').'</a>';

   echo '</div>';


   echo '</div>';

   echo '<div class="wf_extra_option_list_item_settings">';
   $prechecked =   get_post_meta($current_extra, 'woofood_prechecked', true);
          $prechecked_text = "";
          if($prechecked)
          {
                     $prechecked_text = ' checked';
          }
 
?>

 <div class="wf_option_wrapper"> 
  <label><?php esc_html_e('Name', 'woofood-plugin'); ?></label>
<input type="text" placeholder="Topping name.."name="name" value="<?php echo get_the_title($current_extra); ?>" /> 
</div>
 <div class="wf_option_wrapper"> 
  <label><?php esc_html_e('Price', 'woofood-plugin'); ?></label>
<input type="number" placeholder="0.50" step="0.01"  onchange="(function(el){el.value = el.value.replace(',', '.');})(this)" name="extra_option_price" value="<?php echo get_post_meta($current_extra, 'extra_option_price', true); ?>" /> 
</div>

 <div class="wf_option_wrapper"> 
  <label><?php esc_html_e('Pre-checked', 'woofood-plugin'); ?></label>
<input type="checkbox"  name="prechecked" value="1" <?php echo $prechecked_text; ?> /> 
</div>

    <div class="wf_option_wrapper"> 
  <a class="save_option" item-id="<?php echo $current_extra; ?>"><?php esc_html_e('Save', 'woofood-plugin'); ?></a>
</div>

<?php
   echo '</div>';
   echo '</li>';

 }

 function woofood_handle_custom_woocommerce_meta( $query, $query_vars ) {
  if ( ! empty( $query_vars['woofood_date_to_deliver'] ) ) {
    $query['meta_query'][] = array(
      'key' => 'woofood_date_to_deliver',
      'value' => esc_attr( $query_vars['woofood_date_to_deliver'] ),
    );
  }
  if ( ! empty( $query_vars['woofood_order_type'] ) ) {
    $query['meta_query'][] = array(
      'key' => 'woofood_order_type',
      'value' => esc_attr( $query_vars['woofood_order_type'] ),
    );
  }

   if ( ! empty( $query_vars['woofood_time_to_deliver'] ) ) {
    $query['meta_query'][] = array(
      'key' => 'woofood_time_to_deliver',
      'value' => esc_attr( $query_vars['woofood_time_to_deliver'] ),
    );
  }

  return $query;
}
add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', 'woofood_handle_custom_woocommerce_meta', 10, 2 );


} 