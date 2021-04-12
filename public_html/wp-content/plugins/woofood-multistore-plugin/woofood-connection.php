<?php
/*
Plugin Name: WooFood MultiStore Plugin for WooFood and WooCommerce
Plugin URI: https://www.wpslash.com/plugin/woofood/
Description: Add Support for Multiple Stores to WooFood Plugin
Author: WPSlash
Version: 2.3.5
Author URI: https://www.wpslash.com
 * WC requires at least: 3.0
 * WC tested up to: 4.1.1
*/

add_action('plugins_loaded', 'wf_load_multistore_textdomain');
function wf_load_multistore_textdomain() {
  load_plugin_textdomain( 'woofood-multistore-plugin', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );

}

function wf_multistore_css(){
  wp_enqueue_style( 'woofood_css', plugin_dir_url( __FILE__ ) . 'css/default.css', array(), '2.1.6', 'all' );

  wp_enqueue_script('main_js_wf_multistore', plugin_dir_url(__FILE__).'js/main.js', array(), "2.1.6");


}


define("WOOFOOD_MULTISTORE_PLUGIN_VERSION", "2.3.5");


add_action('wp_enqueue_scripts', 'wf_multistore_css');


require_once( __DIR__ . DIRECTORY_SEPARATOR . 'woofood-settings.php' );
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/functions.php' );
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/delivery_hours.php' );
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/time_to_deliver.php' );
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/thank_you.php' );
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'inc/update.php' );

if (!function_exists('get_home_path')) {
  require_once( ABSPATH . '/wp-admin/includes/file.php' );
}



if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {


add_action( 'woocommerce_checkout_before_order_review', 'wf_multistore_woofood_store_address_checkout_display', 12, 0 );

function wf_multistore_woofood_store_address_checkout_display() {

       $args2 = array(
      'posts_per_page' => - 1,
      'orderby'        => 'title',
      'order'          => 'asc',
      'post_type'      => 'extra_store',
      'post_status'    => 'publish',
      'meta_query' => array(
        array(
          'key' => 'extra_store_enabled'
          )
        )                  
      );
    $get_enabled_stores = get_posts( $args2 );
    $all_stores_with_addresses = array();

    foreach($get_enabled_stores as $current_enabled_store)

    {
      
      $current_store_address = get_post_meta( $current_enabled_store->ID, 'extra_store_address', true );

    echo '<div class="woofood_store_address_checkout" id="woofood_store_address_checkout_'.$current_enabled_store->ID.'" style="display:none;" >';
echo '<h4>'.esc_html__('Address To Pickup', 'woofood-plugin').':</h4>';
echo $current_store_address ;
echo '</div>';


}

}

//Create Custom Post Type extra_store//
  function wf_register_extra_store_post_type() {
    $labels = array(
      'name'                  => esc_html_x( 'Stores', 'Post type general name', 'woofood-multistore-pugin' ),
      'singular_name'         => esc_html_x( 'Store', 'Post type singular name', 'woofood-multistore-pugin' ),
      'menu_name'             => esc_html_x( 'Stores', 'Admin Menu text', 'woofood-multistore-pugin' ),
      'name_admin_bar'        => esc_html_x( 'Store', 'Add New on Toolbar', 'woofood-multistore-pugin' ),
      'add_new'               => esc_html__( 'Add Store', 'woofood-multistore-pugin' ),
      'add_new_item'          => esc_html__( 'Add New Store', 'woofood-multistore-pugin' ),
      'new_item'              => esc_html__( 'New Store', 'woofood-multistore-pugin' ),
      'edit_item'             => esc_html__( 'Edit Store', 'woofood-multistore-pugin' ),
      'view_item'             => esc_html__( 'View Store', 'woofood-multistore-pugin' ),
      'all_items'             => esc_html__( 'All Stores', 'woofood-multistore-pugin' ),
      'search_items'          => esc_html__( 'Search Stores', 'woofood-multistore-pugin' ),
      'parent_item_colon'     => esc_html__( 'Parent Stores:', 'woofood-multistore-pugin' ),
      'not_found'             => esc_html__( 'No Stores found.', 'woofood-multistore-pugin' ),
      'not_found_in_trash'    => esc_html__( 'No Stores found in Trash.', 'woofood-multistore-pugin' ),
      'featured_image'        => esc_html_x( 'Store Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'woofood-multistore-pugin' ),
      'set_featured_image'    => esc_html_x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'woofood-multistore-pugin' ),
      'remove_featured_image' => esc_html_x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'woofood-multistore-pugin' ),
      'use_featured_image'    => esc_html_x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'woofood-multistore-pugin' ),
      'archives'              => esc_html_x( 'Store archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'woofood-plugin' ),
      'insert_into_item'      => esc_html_x( 'Insert into Store', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'woofood-multistore-pugin' ),
      'uploaded_to_this_item' => esc_html_x( 'Uploaded to this Store', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'woofood-multistore-pugin' ),
      'filter_items_list'     => esc_html_x( 'Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'woofood-multistore-pugin' ),
      'items_list_navigation' => esc_html_x( 'Store list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'woofood-multistore-pugin' ),
      'items_list'            => esc_html_x( 'Store list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'woofood-multistore-pugin' ),
      );

    $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => false,
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'extra_store' ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => null,
      'menu_icon'           => get_template_directory_uri().'/icons/woofood_logo_black/res/mipmap-mdpi/woofood_logo_black.png',
      'taxonomies' => array(),
      'supports'           => array( 'title', 'custom-fields' ),
      );

    register_post_type( 'extra_store', $args );
  }

  add_action( 'init', 'wf_register_extra_store_post_type' );

//Create Custom Post Type extra_option//


//add custom columns//
  add_filter( 'manage_edit-extra_store_columns', 'extra_store_columns' ) ;

  function extra_store_columns( $columns ) {

    $columns = array(
      'title' => esc_html__( 'Store Name' , 'woofood-multistore-plugin'),
      'extra_store_address' => esc_html__( 'Store Address', 'woofood-multistore-plugin' ),
      'extra_store_email' => esc_html__( 'Store Email', 'woofood-multistore-plugin' ),
      'extra_store_phone' => esc_html__( 'Store Phone', 'woofood-multistore-plugin' ),
      'extra_store_enabled' => esc_html__( 'Store Enabled', 'woofood-multistore-plugin' ),


      );

    return $columns;
  }
//add custom columns//


//load extra_store_address columns data  //


  add_action( 'manage_extra_store_posts_custom_column', 'wf_manage_extra_store_columns', 10, 2 );

  function wf_manage_extra_store_columns( $column, $post_id ) {
    global $post;

    switch( $column ) {

      /* If displaying the 'extra_store_address' column. */
      case 'extra_store_address' :

      /* Get the post meta. */
      $extra_store_address = get_post_meta( $post_id, 'extra_store_address', true );


      echo '<strong>'.$extra_store_address.'</strong>';

      break;


      /* If displaying the 'extra_store_email' column. */
      case 'extra_store_email' :

      /* Get the post meta. */
      $extra_store_email = get_post_meta( $post_id, 'extra_store_email', true );


      echo '<strong>'.$extra_store_email.'</strong>';

      break;

      /* If displaying the 'extra_store_phone' column. */
      case 'extra_store_phone' :

      /* Get the post meta. */
      $extra_store_phone = get_post_meta( $post_id, 'extra_store_phone', true );


      echo '<strong>'.$extra_store_phone.'</strong>';

      break;

      /* If displaying the 'extra_store_enabled' column. */
      case 'extra_store_enabled' :

      /* Get the post meta. */
      $extra_store_enabled = get_post_meta( $post_id, 'extra_store_enabled', true );

      if ($extra_store_enabled ==true)
      {
        echo '<strong>'.esc_html__('Enabled', 'woofood-multistore-plugin').'</strong>';
      }
      else {

        echo '<strong>'.esc_html__('Disabled', 'woofood-multistore-plugin').'</strong>';

      }

      break;

      /* If displaying the 'genre' column. */
      case 'genre' :

      /* Get the genres for the post. */
      $terms = get_the_terms( $post_id, 'genre' );

      /* If terms were found. */
      if ( !empty( $terms ) ) {

        $out = array();

        /* Loop through each term, linking to the 'edit posts' page for the specific term. */
        foreach ( $terms as $term ) {
          $out[] = sprintf( '<a href="%s">%s</a>',
            esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'genre' => $term->slug ), 'edit.php' ) ),
            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'genre', 'display' ) )
            );
        }

        /* Join the terms, separating them with a comma. */
        echo join( ', ', $out );
      }

      /* If no terms were found, output a default message. */
      else {
        esc_html_e( 'No Stores' ,'woofood-multistore-plugin');
      }

      break;

      /* Just break out of the switch statement for everything else. */
      default :
      break;
    }
  }

//load extra_store_address columns data  //



//add meta box extra price //

  function wf_extra_store_address_meta() {
    add_meta_box( 'extra_store_address', esc_html__( 'Store Details', 'woofood-multistore-plugin' ), 'wf_extra_store_address_callback', 'extra_store' );
  }
  add_action( 'add_meta_boxes', 'wf_extra_store_address_meta' );
//add meta box extra price //

//metabox extra_store_address callback//
  function wf_extra_store_address_callback() {


// Noncename needed to verify where the data originated
    wp_nonce_field( basename(__FILE__), 'extra_store_meta_nonce' );


    global $post;

//Get extra_store_address if already exists
    $extra_store_address = get_post_meta($post->ID, 'extra_store_address', true);
    $extra_store_email = get_post_meta($post->ID, 'extra_store_email', true);
    $extra_store_phone = get_post_meta($post->ID, 'extra_store_phone', true);
    $extra_store_enabled= get_post_meta($post->ID, 'extra_store_enabled', true);
    $extra_store_max_delivery_distance= get_post_meta($post->ID, 'extra_store_max_delivery_distance', true);
    $extra_store_key= get_post_meta($post->ID, 'extra_store_key', true);
    $extra_store_user = get_post_meta($post->ID, 'extra_store_user', true);


//display the extra_store_address //
    echo '<div class="extra-option-field">'.esc_html__('Store Address', 'woofood-multistore-plugin').'<input type="text" name="extra_store_address" placeholder="'.esc_html__('Enter your Store Address', 'woofood-multistore-plugin').'" id="extra_store_address" onFocus="geolocate()" value="' . $extra_store_address  . '"  /></div>';

//display the extra_store_email //
    echo '<div class="extra-option-field">'.esc_html__('Store Email Address', 'woofood-multistore-plugin').'<input type="text" name="extra_store_email" placeholder="'.esc_html__('Enter your Store Email Address', 'woofood-multistore-plugin').'" id="extra_store_email" onFocus="" value="' . $extra_store_email  . '"  /></div>';

//display the extra_store_phone //
    echo '<div class="extra-option-field">'.esc_html__('Store Phone Number', 'woofood-multistore-plugin').'<input type="text" name="extra_store_phone" placeholder="'.esc_html__('Enter your Store Phone Number', 'woofood-multistore-plugin').'" id="extra_store_phone" onFocus="" value="' . $extra_store_phone  . '"  /></div>';


//display the extra_store_max_delivery_distance //
    echo '<div class="extra-option-field">'.esc_html__('Maximum Delivery Distance', 'woofood-multistore-plugin').'<input type="text" name="extra_store_max_delivery_distance" placeholder="'.esc_html__('Distance in km', 'woofood-multistore-plugin').'" id="extra_store_max_delivery_distance" onFocus="" value="' . $extra_store_max_delivery_distance  . '"  /></div>';

//display the extra_store_max_delivery_distance //
    echo '<div class="extra-option-field">'.esc_html__('Extra Store Key', 'woofood-multistore-plugin').'<input type="text" name="extra_store_key" placeholder="'.esc_html__('Type a Unique Key of your choise', 'woofood-multistore-plugin').'" id="extra_store_key" onFocus="" value="' . $extra_store_key  . '"  /></div>';

//display the extra_store_enabled //

    if( $extra_store_enabled == true ) { 
      $exta_store_check_text = 'checked="checked"';
    }
    echo '<div class="extra-option-field">'.esc_html__('Enable Store', 'woofood-multistore-plugin').'<input type="checkbox" name="extra_store_enabled" placeholder="'.esc_html__('Enable this Store', 'woofood-multistore-plugin').'" id="extra_store_enabled" '.$exta_store_check_text .' /></div>';



    echo '<div class="extra-option-field">';
    echo '<select id="extra_store_user" name="extra_store_user">';

    $selected_none = "";
    if ($extra_store_user ==0) 
    {
      $selected_none=" selected";

    }
    echo '<option value="0" '.$selected_none.'>'.esc_html__('Select User','woofood-plugin').'</option>';

    $args = array(
      'role'    => 'multistore_user',
      'orderby' => 'user_nicename',
      'order'   => 'ASC'
      );
    $users = get_users( $args );
    foreach ( $users as $user ) {
      $selected ="";
      if($user->ID== $extra_store_user)

      {
        $selected =" selected";

      }

      echo '<option value="'.$user->ID.'" '.$selected.' >' . esc_html( $user->display_name ) . '[' . esc_html( $user->user_email ) . ']</option>';
    }
    echo '</select>';
    echo "</div>";
  }


//display the extra_store_user //

//metabox extra_store_address callback//

//save meta data //
  function wf_extra_store_meta_save($post_id) {
    if (!isset($_POST['extra_store_meta_nonce']) || !wp_verify_nonce($_POST['extra_store_meta_nonce'], basename(__FILE__))) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

//check and save extra_store_address meta//
    if(isset($_POST['extra_store_address'])) {
      update_post_meta($post_id, 'extra_store_address', $_POST['extra_store_address']);
    } else {
      delete_post_meta($post_id, 'extra_store_address');
    }
//check and save extra_store_address meta//


//check and save extra_store_email meta//
    if(isset($_POST['extra_store_email'])) {
      update_post_meta($post_id, 'extra_store_email', $_POST['extra_store_email']);
    } else {
      delete_post_meta($post_id, 'extra_store_email');
    }
//check and save extra_store_email meta//


//check and save extra_store_phone meta//
    if(isset($_POST['extra_store_phone'])) {
      update_post_meta($post_id, 'extra_store_phone', $_POST['extra_store_phone']);
    } else {
      delete_post_meta($post_id, 'extra_store_phone');
    }
//check and save extra_store_phone meta//


//check and save extra_store_user meta//
    if(isset($_POST['extra_store_user'])) {
      update_post_meta($post_id, 'extra_store_user', $_POST['extra_store_user']);
    } else {
      delete_post_meta($post_id, 'extra_store_user');
    }
//check and save extra_store_user meta//


//check and save extra_store_enabled meta//
    if(isset($_POST['extra_store_enabled'])) {
      update_post_meta($post_id, 'extra_store_enabled', $_POST['extra_store_enabled']);
    } else {
      delete_post_meta($post_id, 'extra_store_enabled');
    }
//check and save extra_store_enabled meta//


//check and save extra_store_max_delivery_distance meta//
    if(isset($_POST['extra_store_max_delivery_distance'])) {
      update_post_meta($post_id, 'extra_store_max_delivery_distance', $_POST['extra_store_max_delivery_distance']);
    } else {
      delete_post_meta($post_id, 'extra_store_max_delivery_distance');
    }
//check and save extra_store_max_delivery_distance meta//


//check and save extra_store_max_delivery_distance meta//
    if(isset($_POST['extra_store_key'])) {
      update_post_meta($post_id, 'extra_store_key', $_POST['extra_store_key']);
    } else {
      delete_post_meta($post_id, 'extra_store_key');
    }
//check and save extra_store_max_delivery_distance meta//



  }
  add_action('save_post', 'wf_extra_store_meta_save');

//save meta data //

  function wf_add_async_forscript($url)
  {
    if ( strpos( $url, '#asyncload') === false )
      return $url;
    else if ( is_admin() )
      return str_replace( '#asyncload', '', $url )."' async defer"; 
    else
      return str_replace( '#asyncload', '', $url )."' async defer"; 
  }
  add_filter('clean_url', 'wf_add_async_forscript', 11, 1);






  function wf_add_woofood_multistore_google_api_scripts( $hook ) {
    $options_woofood = get_option('woofood_options');

    $woofood_google_api_key = isset($options_woofood['woofood_google_api_key']) ? $options_woofood['woofood_google_api_key'] : null ;
    if (!empty($woofood_google_api_key)){

      global $post;

      if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
        if ( 'extra_store' === $post->post_type ) {     


          wp_enqueue_script('google-js-api', 'https://maps.googleapis.com/maps/api/js?libraries=places&key='.$woofood_google_api_key.'&language='.substr(get_bloginfo ( 'language' ), 0, 2).'#asyncload');
          wp_enqueue_script(  'woofood_js_google', plugin_dir_url( __FILE__ ) . 'js/autocomplete.js' , array(), '1.0.0', 'all' );



        }
      }

}//end if not empty google api key
}
add_action( 'admin_enqueue_scripts', 'wf_add_woofood_multistore_google_api_scripts', 10, 1 );

//load google maps on post type extra_store//


add_filter( 'manage_edit-shop_order_columns', 'wf_multistore_store_column',11);
function wf_multistore_store_column($columns)
{
//add columns
  $columns['store_name'] = esc_html__( 'Store','woofood-multistore-plugin');
  return $columns;
}

// adding the data for each orders by column (example)
add_action( 'manage_shop_order_posts_custom_column' , 'wf_multistore_store_column_content', 10, 2 );
function wf_multistore_store_column_content( $column )
{
  global $post, $woocommerce, $the_order;
  $order_id = $post->ID;

  switch ( $column )
  {
    case 'store_name' :

    $extra_store_id =  get_post_meta( $order_id, 'extra_store_name', true );
      if(is_numeric($extra_store_id))
      {
            echo get_the_title($extra_store_id);

      }
      else
      {
        echo $extra_store_id;

      }

    break;


  }
}





//add filter to search on more fields//
add_filter( 'woocommerce_shop_order_search_fields', 'wf_multistore_search_store_name' );
function wf_multistore_search_store_name( $search_fields ) {
  $search_fields[] = '_order_total';
  $search_fields[] = 'extra_store_name';
  return $search_fields;
}
//add filter to search on more fields//



//add dropdown store on order list//

function wf_multistore_filter_orders_by_store() {

  global $typenow;
  if ( 'shop_order' === $typenow ) {
    $args = array(
      'posts_per_page' => - 1,
      'orderby'        => 'title',
      'order'          => 'asc',
      'post_type'      => 'extra_store',
      'post_status'    => 'publish',
      );
    $extra_stores = get_posts( $args );
    if ( ! empty( $extra_stores )) : ?>

    <select name="extra_store_name" id="dropdown_extra_stores">
      <option value="">
        <?php esc_html_e( 'Filter by Store', 'woofood-multistore-plugin' ); ?>
      </option>
      <?php foreach ( $extra_stores as $extra_store ) : ?>
        <option value="<?php echo esc_attr( $extra_store->ID ); ?>">
          <?php echo esc_html( $extra_store->post_title ); ?>
        </option>
      <?php endforeach; ?>
    </select>


  <?php endif;
}
}





function wf_multistore_parse_filter_query( $query ){
  global $pagenow;
  $type = 'shop_order';
  if (isset($_GET['post_type'])) {
    $type = $_GET['post_type'];
  }
  if ( 'shop_order' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['extra_store_name']) && $_GET['extra_store_name'] != '') {
    $query->query_vars['meta_key'] = 'extra_store_name';
    $query->query_vars['meta_value'] = $_GET['extra_store_name'];
  }
}



add_filter( 'parse_query', 'wf_multistore_parse_filter_query' );


if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
  add_action( 'restrict_manage_posts','wf_multistore_filter_orders_by_store');



}
//add dropdown store on order list//




//Add Store Selection on Checkout///
add_action( 'woocommerce_checkout_before_order_review', 'woofood_multistore_store_selection_field', 11, 1 );

function woofood_multistore_store_selection_field( $checkout ) {
  global $woocommerce;
  $woofood_options_multistore = get_option('woofood_options_multistore');
  $woofood_auto_store_select = isset($woofood_options_multistore['woofood_auto_store_select']) ? $woofood_options_multistore['woofood_auto_store_select'] : null ;
  $default_store = WC()->session->get( 'woofood_nearest_store_id');
    $woofood_options = get_option('woofood_options');

  $woofood_enable_pickup_option = isset($woofood_options['woofood_enable_pickup_option']) ? $woofood_options['woofood_enable_pickup_option'] : null ;

//get stores//
    $args = array(
      'posts_per_page' => - 1,
      'orderby'        => 'title',
      'order'          => 'asc',
      'post_type'      => 'extra_store',
      'post_status'    => 'publish',
      'meta_query' => array(
        array(
          'key' => 'extra_store_enabled'
          ),
         array(
          'key' => 'order_type_delivery'
          )
        )          
      );
    $delivery_stores = array();
    $pickup_stores = array();

    $extra_stores = get_posts( $args );
    foreach ($extra_stores as $extra_store)
    {
      $delivery_stores[$extra_store->ID] = $extra_store->post_title;

    }

    if(empty($default_store) && !empty($delivery_stores))
    {
      $default_store = $extra_stores[0]->ID;
    }


    $args2 = array(
      'posts_per_page' => - 1,
      'orderby'        => 'title',
      'order'          => 'asc',
      'post_type'      => 'extra_store',
      'post_status'    => 'publish',
      'meta_query' => array(
        array(
          'key' => 'extra_store_enabled'
          ),
         array(
          'key' => 'order_type_pickup'
          )
        )          
      );
    $extra_stores2 = get_posts( $args2 );

    foreach ($extra_stores2 as $extra_store)
    {
      $pickup_stores[$extra_store->ID] = $extra_store->post_title;

    }
//get stores//

    echo '<div class="woofood_store_select_wrapper delivery">';
    echo '<span class="wf_store_select_title">'.esc_html__('Select Store to Deliver to you', 'woofood-multistore-plugin').'</span>';
    woocommerce_form_field( 'extra_store_name', array(

      'type'         => 'select',
      'class'         => array('woofood_multistore_select_store delivery'),
      'required'     => true,
      'options'  => $delivery_stores,

      ), $default_store);

    echo '</div>';


      if($woofood_enable_pickup_option)
      {
         echo '<div class="woofood_store_select_wrapper pickup">';
    echo '<span class="wf_store_select_title">'.esc_html__('Select Store to Pickup from', 'woofood-multistore-plugin').'</span>';
    woocommerce_form_field( 'extra_store_name_pickup', array(

      'type'         => 'select',
      'class'         => array('woofood_multistore_select_store pikcup'),
      'required'     => true,
      'options'  => $pickup_stores,

      ), $default_store);

    echo '</div>';

      }
    

  

} //end function




add_action( 'woocommerce_after_checkout_form', 'woofood_multistore_display_store_select_on_pickup');
 
function woofood_multistore_display_store_select_on_pickup() {

    $woofood_options = get_option('woofood_options');
  $woofood_enable_pickup_option = isset($woofood_options['woofood_enable_pickup_option']) ? $woofood_options['woofood_enable_pickup_option'] : null ;



  $woofood_options_multistore = get_option('woofood_options_multistore');
  $woofood_auto_store_select = isset($woofood_options_multistore['woofood_auto_store_select']) ?  $woofood_options_multistore['woofood_auto_store_select'] : null;



  if ($woofood_auto_store_select && $woofood_enable_pickup_option){
?>
<script>
jQuery(document).ready(function () {


  var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();

  if(woofood_order_type=="pickup")
  {
            jQuery('.woofood_store_select_wrapper').removeClass('open');

        jQuery('.woofood_store_select_wrapper.pickup').addClass('open');
                //jQuery('#extra_store_name').trigger('change');
           
  }
   else if(woofood_order_type=="delivery")
   {
            jQuery('.woofood_store_select_wrapper').removeClass('open');
                    //jQuery('.woofood_store_select_wrapper.delivery').addClass('open');


   }  



    jQuery(document).on('change', 'input[type=radio][name=woofood_order_type]', function (){

        var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();

          if(woofood_order_type=="pickup")
  {
                jQuery('.woofood_store_select_wrapper').removeClass('open');

            jQuery('.woofood_store_select_wrapper.pickup').addClass('open');
               // jQuery('#extra_store_name').trigger('change');

    //jQuery('.woofood_store_address_checkout').css('display', 'block');
  }
  else
  {
                jQuery('.woofood_store_select_wrapper').removeClass('open');

           // jQuery('.woofood_store_select_wrapper.delivery').addClass('open');


  }
  
    

        return false;
    });


});

</script>

<?php
}//end if pickup is enabled
else if($woofood_auto_store_select && !$woofood_enable_pickup_option )
{

}

else 
{

  ?>
<script>
jQuery(document).ready(function () {


        if(jQuery('input[name=woofood_order_type]:checked').length)
        {
          var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();

          jQuery('.woofood_store_select_wrapper').removeClass('open');

        jQuery('.woofood_store_select_wrapper.'+woofood_order_type).addClass('open');

        }
        else
        {
          jQuery('.woofood_store_select_wrapper').addClass('open');
        }
        







jQuery(document).on('change', 'input[type=radio][name=woofood_order_type]', function (){

        var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();


          jQuery('.woofood_store_select_wrapper').removeClass('open');

        jQuery('.woofood_store_select_wrapper.'+woofood_order_type).addClass('open');
  
    

        return false;
    });



});

</script>

<?php


}

}



function extra_store_name_check() {

// if the field is set, if not then show an error message.

  if ( $_POST['extra_store_name']=="" )

    wc_add_notice( esc_html__( 'Store have not selected.', 'woofood-multistore-plugin' ), 'error' );

}





add_action( 'woocommerce_checkout_update_order_meta', 'woofood_update_store_meta_checkout' , 10, 2 );

function woofood_update_store_meta_checkout( $order_id, $posted ) {

    $order_type = woofood_get_default_order_type();
    if(isset($_POST['woofood_order_type']))
    {
      $order_type = $_POST['woofood_order_type'];
    }

  if ( ! empty( $_POST['extra_store_name']  ) && $order_type =="delivery" ) {

    $order = wc_get_order( $order_id );
    $order->update_meta_data( 'extra_store_name', sanitize_text_field( $_POST['extra_store_name'] ));

    $order->save();
  }
   if ( ! empty( $_POST['extra_store_name_pickup']  ) && $order_type =="pickup" ) {

    $order = wc_get_order( $order_id );
    $order->update_meta_data( 'extra_store_name', sanitize_text_field( $_POST['extra_store_name_pickup'] ));

    $order->save();
  }

}

//Add Store Selection on Checkout//         

//check distance on checkout//

$options_woofood = get_option('woofood_options');


add_action( 'plugins_loaded', 'remove_wf_check_distance');
function remove_wf_check_distance(){
  remove_action( 'woocommerce_checkout_process', 'wf_check_distance');
    remove_action( 'woocommerce_checkout_process', 'wf_check_polygon');
    remove_action( 'woocommerce_checkout_process', 'wf_check_postalcode');


  
  remove_action( 'woocommerce_checkout_before_order_review', 'wf_woofood_store_address_checkout_display', 12);

  remove_action('wp_ajax_nopriv_wf_availiability_cheker_ajax', 'wf_availiability_cheker_ajax');
  remove_action('wp_ajax_wf_availiability_cheker_ajax', 'wf_availiability_cheker_ajax');

}


function wf_availiability_cheker_ajax_multistore(){
  global $woocommerce;
$delivery_available  = false; 
$options_woofood = get_option('woofood_options');
$woofood_options_multistore = get_option('woofood_options_multistore');
$woofood_auto_store_select = isset($woofood_options_multistore['woofood_auto_store_select']) ? $woofood_options_multistore['woofood_auto_store_select'] : false ;
$woofood_google_distance_matrix_api_key = $options_woofood['woofood_google_distance_matrix_api_key'];
$woofood_max_delivery_distance = $options_woofood['woofood_max_delivery_distance'];
$woofood_store_address = $options_woofood['woofood_store_address'];
$customer_address    = $_POST['address'];
$order_type    = $_POST['woofood_order_type'];
$billing_address_number    = $_POST['billing_address_number'];
$billing_address_1   = $billing_address_number." ".$_POST['billing_address_1'];
$billing_city    = $_POST['billing_city'];
$billing_country   = $_POST['billing_country'];
$billing_postcode   = $_POST['billing_postcode'];
$billing_state   = $_POST['billing_postcode'];
$woofood_distance_type = isset($options_woofood['woofood_distance_type']) ?  $options_woofood['woofood_distance_type'] : "default";

$nearest_store = "";
$nearest_store_id = 0;

$manual_store_id = 0;
if(!$woofood_auto_store_select && ($order_type =="delivery"))
{
 $manual_store_id = intval($_POST["extra_store_name_delivery"]);

}
if($order_type =="pickup")
{
 $manual_store_id = intval($_POST["extra_store_name_pickup"]);

}
$available_stores = array();
if($manual_store_id >0)
  {
        $available_stores =  wf_availability_checker_multi($customer_address, $order_type, $billing_postcode, $manual_store_id);

       /* $nearest_store_id = $manual_store_id;
        $nearest_store = get_the_title($nearest_store_id);
        $delivery_available = true;*/


  }
  else
  {
     $available_stores =  wf_availability_checker_multi($customer_address, $order_type, $billing_postcode);

  }


//here is going the function// to check

 $redirect_script ="";
 if($available_stores)
 {
  if($available_stores["availability"])
  {
    $delivery_available = $available_stores["availability"];

  }
  if($available_stores["nearest_store_id"])
  {
    $nearest_store_id = $available_stores["nearest_store_id"];

  }
  if($available_stores["nearest_store_name"])
  {
    $nearest_store = $available_stores["nearest_store_name"];

  }


 }

      
$response = '';
if( $delivery_available)  
{
  $woocommerce->customer->set_billing_address_1( $billing_address_1 );
    $woocommerce->customer->set_billing_city( $billing_city );
    $woocommerce->customer->set_billing_postcode( $billing_postcode );
    $woocommerce->customer->set_billing_country( $billing_country );

    if($woofood_distance_type =="postalcode")
    {
         WC()->session->set( 'woofood_form_customer_address', $billing_postcode );

    }
    else
    {
         WC()->session->set( 'woofood_form_customer_address', $customer_address );

    }

     WC()->session->set( 'woofood_order_type', $order_type );

    WC()->session->set( 'woofood_nearest_store', $nearest_store );
    WC()->session->set( 'woofood_nearest_store_id', $nearest_store_id );




    $redirect_script = '';
    $redirect_after_found =  apply_filters( "woofood_multistore_redirect_after_store_found", false );
    if($redirect_after_found)
    {            $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );

            $redirect_url = apply_filters( "woofood_multistore_redirect_url",  $shop_page_url, $available_stores );

         $redirect_script = '<script>window.location.href = "'.$redirect_url.'";</script>';

    }


$response = '<div class="availability-result"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="50" height="50" viewBox="0 0 24 24" style="fill: #cc0000;text-align: center;margin: 20px;border: 1px solid black;padding: 9px;border-radius: 99999px;"><g id="surface1"><path style=" fill-rule:evenodd;" d="M 22.59375 3.5 L 8.0625 18.1875 L 1.40625 11.5625 L 0 13 L 8.0625 21 L 24 4.9375 Z "></path></g></svg>'.'<div class="availability-result-message">'.sprintf(esc_html__(" %s is available", "woofood-plugin").'</div>', woofood_get_order_type_by_key($order_type)).'<div class="">'.esc_html__("The nearest store is", "woofood-multistore-plugin").' '.$nearest_store.'</div><div class="wf_availability_actions"><a class="wf_start_order_btn" data-micromodal-close>'.esc_html__('Start Order','woofood-plugin').'</a></div></div>';
 WC()->session->set( 'woofood_form_customer_address_response', $response );

          WC()->session->set( 'woofood_form_customer_address', $billing_postcode );


} 
else
{
  $svg_sad = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0px" width="50" height="50" viewBox="0 0 512 512" style="fill: #cc0000; text-align: center; margin: 20px; /* border: 1px solid black; */ /* padding: 9px; */ border-radius: 99999px; " xml:space="preserve"> <g> <g> <path d="M375.71,356.744c-1.79-2.27-44.687-55.622-119.71-55.622s-117.92,53.351-119.71,55.622l31.42,24.756 c0.318-0.404,32.458-40.378,88.29-40.378c55.147,0,87.024,38.807,88.354,40.458l-0.064-0.08L375.71,356.744z"></path> </g> </g> <g> <g> <path d="M437.02,74.98C388.667,26.629,324.38,0,256,0S123.333,26.629,74.98,74.98C26.629,123.333,0,187.62,0,256 s26.629,132.668,74.98,181.02C123.333,485.371,187.62,512,256,512s132.667-26.629,181.02-74.98 C485.371,388.668,512,324.38,512,256S485.371,123.333,437.02,74.98z M256,472c-119.103,0-216-96.897-216-216S136.897,40,256,40 s216,96.897,216,216S375.103,472,256,472z"></path> </g> </g> <g> <g> <circle cx="168" cy="180.12" r="32"></circle> </g> </g> <g> <g> <circle cx="344" cy="180.12" r="32"></circle> </g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </svg>';
  $response = '<div class="availability-result">'.$svg_sad.'<div class="availability-result-message">'.sprintf(esc_html__(" %s is not available from our stores", "woofood-plugin").'</div>', woofood_get_order_type_by_key($order_type)).'</div>';
 WC()->session->set( 'woofood_form_customer_address_response', "" );
   WC()->session->set( 'woofood_form_customer_address', "" );


}
 


    echo json_encode($redirect_script.$response);





    wp_die();

  }

  add_action('wp_ajax_nopriv_wf_availiability_cheker_ajax', 'wf_availiability_cheker_ajax_multistore');
  add_action('wp_ajax_wf_availiability_cheker_ajax', 'wf_availiability_cheker_ajax_multistore');









function wf_availability_checker_multi($customer_address, $order_type, $postalcode = null, $store_id = 0)
{
  $delivery_available = false;
    $nearest_store= "";
    $nearest_store_id= 0;
  $options_woofood = get_option('woofood_options');
$woofood_google_distance_matrix_api_key = $options_woofood['woofood_google_distance_matrix_api_key'];
$woofood_max_delivery_distance = $options_woofood['woofood_max_delivery_distance'];
$woofood_distance_type = isset($options_woofood['woofood_distance_type']) ?  $options_woofood['woofood_distance_type'] : "default";
    $woofood_availability_checker_hide_address_pickup = isset($options_woofood['woofood_availability_checker_hide_address_pickup']) ? $options_woofood['woofood_availability_checker_hide_address_pickup']: false  ;

    $all_stores_with_addresses = array();

    if(($order_type == "pickup") && ($store_id > 0) && $woofood_availability_checker_hide_address_pickup)
    {
      $response_array = array("available_stores"=>array(), "nearest_store_id"=>$store_id, "nearest_store_name"=> get_the_title($store_id), "availability"=>true);
return $response_array ;

    }

   if(!empty($woofood_google_distance_matrix_api_key) && $woofood_distance_type == "default"  )
{ 

  return wf_availability_multi_get_stores_distance($customer_address, $order_type, $store_id);

}//if not empty Distance Matrix API Key
   elseif($woofood_distance_type == "polygon"  )
{ 

  return wf_availability_multi_get_stores_polygon($customer_address, $order_type, $store_id);

}//if not empty Distance Matrix API Key
   elseif($woofood_distance_type == "postalcode"  )
{ 

  return wf_availability_multi_get_stores_postal($postalcode, $order_type, $store_id);

}//if not emp
else
{
  $delivery_available  = true;  

}


$response_array = array("available_stores"=>$all_stores_with_addresses, "nearest_store_id"=>$nearest_store_id, "nearest_store_name"=> $nearest_store, "availability"=>$delivery_available);
return $response_array ;

}


$options_woofood = get_option('woofood_options');
$woofood_google_distance_matrix_api_key = isset($options_woofood['woofood_google_distance_matrix_api_key']) ? $options_woofood['woofood_google_distance_matrix_api_key'] : null ;
$woofood_max_delivery_distance = isset($options_woofood['woofood_max_delivery_distance']) ? $options_woofood['woofood_max_delivery_distance'] : null ;
$woofood_store_address = isset($options_woofood['woofood_store_address']) ? $options_woofood['woofood_store_address'] : null ;
$woofood_distance_type = isset($options_woofood['woofood_distance_type']) ? $options_woofood['woofood_distance_type'] : "default" ;
$woofood_polygon_area = isset($options_woofood['woofood_polygon_area']) ? $options_woofood['woofood_polygon_area'] : null;
$woofood_postalcodes = isset($options_woofood['woofood_postalcodes']) ? $options_woofood['woofood_postalcodes'] : null;

if(!empty($woofood_google_distance_matrix_api_key)   && ($woofood_distance_type  ==="default")  )
{
  add_action( 'woocommerce_checkout_process', 'wf_check_multistore_distance', 10, 1 );

}

if(!empty($woofood_google_distance_matrix_api_key)   && ($woofood_distance_type  ==="polygon")  )
{
  add_action( 'woocommerce_checkout_process', 'wf_check_multistore_polygon', 10, 1 );

}
if(!empty($woofood_postalcodes)   && ($woofood_distance_type  ==="postalcode")  )
{
  add_action( 'woocommerce_checkout_process', 'wf_check_multistore_postalcode', 10, 1 );

}








function wf_check_multistore_polygon() {

    $woofood_order_type = isset($_POST['woofood_order_type']) ? $_POST['woofood_order_type'] : woofood_get_default_order_type();
    if( $woofood_order_type  =="pickup" )
  {

  }


  else 
  {
  $options_woofood = get_option('woofood_options');

  $woofood_options_multistore = get_option('woofood_options_multistore');
  $woofood_auto_store_select = $woofood_options_multistore['woofood_auto_store_select'];

  $woofood_google_distance_matrix_api_key = $options_woofood['woofood_google_distance_matrix_api_key'];

//get customer address//
  $woofood_current_address = $_POST['billing_address_1'];
  $woofood_current_city = $_POST['billing_city'];
  $woofood_current_postcode = $_POST['billing_postcode'];

  $woofood_total_address = $woofood_current_address.",".$woofood_current_city.",".$woofood_current_postcode;

//get customer address//

//if the auto select store is enabled//

  if($woofood_order_type  == "pickup")
  {

  }
  if($woofood_order_type  == "delivery" && $woofood_auto_store_select )
  {


    $args2 = array(
      'posts_per_page' => - 1,
      'orderby'        => 'title',
      'order'          => 'asc',
      'post_type'      => 'extra_store',
      'post_status'    => 'publish',
      'meta_query' => array(
        array(
          'key' => 'extra_store_enabled'
          )
        )                  
      );
    $get_enabled_stores = get_posts( $args2 );
    $all_stores_with_addresses = array();


            ///api address get lat , lng///

              $details = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($woofood_total_address)."&key=".$woofood_google_distance_matrix_api_key."";
              $details = htmlspecialchars_decode($details);
              $details = str_replace("&amp;", "&", $details );
              $json = woofood_get_contents($details);


               
              $details = json_decode($json, TRUE);


              if(!empty($details["error_message"]))
              {
                 wc_add_notice( 
                  sprintf( "Google API Error:".$details["error_message"].".Please Correct the configuration for  API Key on your Google Console Account", 
                    $woofood_delivery_hour_start, 
                    $woofood_delivery_hour_end
                    ), 'error' 
                  );
              }



             elseif ( !empty($details['results'][0]['geometry']['location']["lat"] ) && !empty($details['results'][0]['geometry']['location']["lng"] ))
              {











  if(!empty($get_enabled_stores)) {
    foreach($get_enabled_stores as $current_enabled_store)

    {
      $current_store_address = get_post_meta( $current_enabled_store->ID, 'extra_store_address', true );
      $woofood_polygon_area = get_post_meta( $current_enabled_store->ID, 'extra_store_polygon_area', true );




                $polygon_area_points  = json_decode($woofood_polygon_area , true);
                $points_x =array();
                $points_y =array();

                foreach($polygon_area_points as $current_point)
                {
                   $points_x[] = $current_point["lng"];
                  $points_y[] = $current_point["lat"];

                }

                //we got customers lat/lng//
                $latitude_y =  $details['results'][0]['geometry']['location']["lat"];

                $longitude_x =  $details['results'][0]['geometry']['location']["lng"];
               $points_polygon = count($points_x) -1;  // number vertices - zero-based array

         


                if (!woofood_check_if_is_in_polygon($points_polygon, $points_x, $points_y, $longitude_x, $latitude_y)){


       
              }
              else
              {
                                $all_stores_with_addresses[$current_enabled_store->ID] = 10;              

              }








}//end foreach store
}// if extra stores exists//
else
{


    wc_add_notice( 
   esc_html__('You are using WooFood Multistore Plugin and you have not created any stores. If you are using only one store disable the Multistore Plugin as it is not required', 'woofood-multistore-plugin'),  'error' 
    );

}















































              } //end else if got lat lng from Google API Succesfully...//


            





              else{

            //we cannot deliver// show message//
                wc_add_notice( 
                  sprintf( esc_html__('Your Address seems to be invalid. Please check your address', 'woofood-plugin') , 
                    $woofood_delivery_hour_start, 
                    $woofood_delivery_hour_end
                    ), 'error' 
                  );


            }//end else


            ///api address get lat , lng///






  


//if at least one store can deliver
if (!empty($all_stores_with_addresses))
{

  $store_name_array = array_keys($all_stores_with_addresses, min($all_stores_with_addresses));  

  $store_name = $store_name_array[0];


//set exta_store_name//
  $_POST['extra_store_name'] = $store_name;


}//end if

//none store can deliver...show error notice//
else{
  if(!empty($get_enabled_stores))
  {
      wc_add_notice( 
    sprintf( esc_html__('Delivery Service is not available in your Area from this store..', 'woofood-multistore-plugin') , 
     $woofood_delivery_hour_start, 
     $woofood_delivery_hour_end
     ), 'error' 
    );

     


      
  }



}






}//if the auto select store is enabled//


else{//select manually the store//


  
  $current_store_id = isset($_POST['extra_store_name']) ? intval($_POST['extra_store_name']) : null ; 

  
//get store details//    
if($current_store_id)
{

         

              $details = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($woofood_total_address)."&key=".$woofood_google_distance_matrix_api_key."";
              $details = htmlspecialchars_decode($details);
              $details = str_replace("&amp;", "&", $details );
              $json = woofood_get_contents($details);


               
              $details = json_decode($json, TRUE);


              if(!empty($details["error_message"]))
              {
                 wc_add_notice( 
                  sprintf( "Google API Error:".$details["error_message"].".Please Correct the configuration for  API Key on your Google Console Account", 
                    $woofood_delivery_hour_start, 
                    $woofood_delivery_hour_end
                    ), 'error' 
                  );
              }



             elseif ( !empty($details['results'][0]['geometry']['location']["lat"] ) && !empty($details['results'][0]['geometry']['location']["lng"] ))
              {



                  $woofood_polygon_area = get_post_meta( $current_store_id, 'extra_store_polygon_area', true );




                $polygon_area_points  = json_decode($woofood_polygon_area , true);
                $points_x =array();
                $points_y =array();

                foreach($polygon_area_points as $current_point)
                {
                   $points_x[] = $current_point["lng"];
                  $points_y[] = $current_point["lat"];

                }

                //we got customers lat/lng//
                $latitude_y =  $details['results'][0]['geometry']['location']["lat"];

                $longitude_x =  $details['results'][0]['geometry']['location']["lng"];
               $points_polygon = count($points_x) -1;  // number vertices - zero-based array

         


                if (!woofood_check_if_is_in_polygon($points_polygon, $points_x, $points_y, $longitude_x, $latitude_y)){

                  wc_add_notice( 
    sprintf( esc_html__('Delivery Service is not available in your Area from this store..', 'woofood-multistore-plugin') , 
     $woofood_delivery_hour_start, 
     $woofood_delivery_hour_end
     ), 'error' 
    );


       
              }
            

              }


               else{

            //we cannot deliver// show message//
                wc_add_notice( 
                  sprintf( esc_html__('Your Address seems to be invalid. Please check your address', 'woofood-plugin') , 
                    $woofood_delivery_hour_start, 
                    $woofood_delivery_hour_end
                    ), 'error' 
                  );


            }//end else



















}//end if selected store any not empty//
else
{
     wc_add_notice( 
   esc_html__('You are using WooFood Multistore Plugin and you have not created any stores. If you are using only one store disable the Multistore Plugin as it is not required', 'woofood-multistore-plugin'),  'error' 
    );
}

}//else end ...not auto select    

}//end else ..delivery

}//end function













function wf_check_multistore_postalcode() {

    $woofood_order_type = isset($_POST['woofood_order_type']) ? $_POST['woofood_order_type'] : woofood_get_default_order_type();
    if( $woofood_order_type  =="pickup" )
  {

  }


  else 
  {
  $options_woofood = get_option('woofood_options');

  $woofood_options_multistore = get_option('woofood_options_multistore');
  $woofood_auto_store_select = $woofood_options_multistore['woofood_auto_store_select'];


//get customer address//
  $woofood_current_address = $_POST['billing_address_1'];
  $woofood_current_city = $_POST['billing_city'];
  $woofood_current_postcode = isset($_POST['shipping_postcode']) ? $_POST['shipping_postcode'] : $_POST['billing_postcode'];
 
  $woofood_current_postcode = strtoupper($woofood_current_postcode);
  $woofood_current_postcode = str_replace(" ", "", $woofood_current_postcode);

  $woofood_total_address = $woofood_current_address.",".$woofood_current_city.",".$woofood_current_postcode;

//get customer address//

//if the auto select store is enabled//

  if($woofood_order_type  == "pickup")
  {

  }
  if($woofood_order_type  == "delivery" && $woofood_auto_store_select )
  {


    $args2 = array(
      'posts_per_page' => - 1,
      'orderby'        => 'title',
      'order'          => 'asc',
      'post_type'      => 'extra_store',
      'post_status'    => 'publish',
      'meta_query' => array(
        array(
          'key' => 'extra_store_enabled'
          )
        )                  
      );
    $all_postal_codes =  array();
    $all_postal_codes[0] =  "none";

    $get_enabled_stores = get_posts( $args2 );
    $all_stores_with_addresses = array();




if(!empty($get_enabled_stores)) {
    foreach($get_enabled_stores as $current_enabled_store)

    {
      $current_store_address = get_post_meta( $current_enabled_store->ID, 'extra_store_address', true );
      $woofood_postalcodes = get_post_meta( $current_enabled_store->ID, 'extra_store_postalcodes', true );
            $woofood_postalcodes = str_replace(" ", "", $woofood_postalcodes);
            $woofood_postalcodes = strtoupper($woofood_postalcodes);

      $postal_codes_array = explode(",",  trim($woofood_postalcodes));




   



$woofood_check_postal_prefixes = apply_filters( 'woofood_check_postal_prefixes', false);


 if(!$woofood_check_postal_prefixes)
        {

        if(in_array(trim($woofood_current_postcode), $postal_codes_array))
        {

           
             $all_stores_with_addresses[$current_enabled_store->ID] = 10;              


        }
      }
      else
      {

              if(is_array($postal_codes_array))
        {

          foreach($postal_codes_array as $current_postal_code)
          {
            if (strpos($woofood_current_postcode, $current_postal_code) === 0) {



              
              $all_stores_with_addresses[$current_enabled_store->ID] = 10; 


              
              }             


          }


           


        }



      }













}//end foreach store
}// if extra stores exists//
else
{


    wc_add_notice( 
   esc_html__('You are using WooFood Multistore Plugin and you have not created any stores. If you are using only one store disable the Multistore Plugin as it is not required', 'woofood-multistore-plugin'),  'error' 
    );

}

















































            





            ///api address get lat , lng///






  


//if at least one store can deliver
if (!empty($all_stores_with_addresses))
{

  $store_name_array = array_keys($all_stores_with_addresses, min($all_stores_with_addresses));  

  $store_name = $store_name_array[0];


//set exta_store_name//
  $_POST['extra_store_name'] = $store_name;


}//end if

//none store can deliver...show error notice//
else{
  if(!empty($get_enabled_stores))
  {
      wc_add_notice( 
    sprintf( esc_html__('Delivery Service is not available in your Area from our stores..', 'woofood-multistore-plugin') , 
     $woofood_delivery_hour_start, 
     $woofood_delivery_hour_end
     ), 'error' 
    );

     


      
  }



}






}//if the auto select store is enabled//


else{//select manually the store//


  
  $current_store_id = isset($_POST['extra_store_name']) ? intval($_POST['extra_store_name']) : null ; 

  
//get store details//    
if($current_store_id)
{

   $woofood_postalcodes = get_post_meta( $current_store_id, 'extra_store_postalcodes', true );
       $woofood_postalcodes = str_replace(" ", "", $woofood_postalcodes);
            $woofood_postalcodes = strtoupper($woofood_postalcodes);
      $postal_codes_array = explode(",",  trim($woofood_postalcodes));

        $woofood_current_postcode = $_POST['billing_postcode'];
  $woofood_current_postcode = strtoupper($woofood_current_postcode);
  $woofood_current_postcode = str_replace(" ", "", $woofood_current_postcode);


           



        if(!in_array(trim($woofood_current_postcode), $postal_codes_array))
        {

           
               wc_add_notice( 
    sprintf( esc_html__('Delivery Service is not available in your Area from this store..', 'woofood-multistore-plugin') , 
     $woofood_delivery_hour_start, 
     $woofood_delivery_hour_end
     ), 'error' 
    );


        }

         

              


           


         



















}//end if selected store any not empty//
else
{
     wc_add_notice( 
   esc_html__('You are using WooFood Multistore Plugin and you have not created any stores. If you are using only one store disable the Multistore Plugin as it is not required', 'woofood-multistore-plugin'),  'error' 
    );
}

}//else end ...not auto select    

}//end else ..delivery

}//end function
















function wf_check_multistore_distance() {

    $woofood_order_type = isset($_POST['woofood_order_type']) ? $_POST['woofood_order_type'] : woofood_get_default_order_type();
    if( $woofood_order_type  =="pickup" )
  {

  }


  else 
  {
  $options_woofood = get_option('woofood_options');

  $woofood_options_multistore = get_option('woofood_options_multistore');
  $woofood_auto_store_select = $woofood_options_multistore['woofood_auto_store_select'];

  $woofood_google_distance_matrix_api_key = $options_woofood['woofood_google_distance_matrix_api_key'];

//get customer address//
  $woofood_current_address = $_POST['billing_address_1'];
  $woofood_current_city = $_POST['billing_city'];
  $woofood_current_postcode = $_POST['billing_postcode'];

  $woofood_total_address = $woofood_current_address.",".$woofood_current_city.",".$woofood_current_postcode;

//get customer address//

//if the auto select store is enabled//

  if($woofood_order_type  == "pickup")
  {

  }
  if($woofood_order_type  == "delivery" && $woofood_auto_store_select )
  {


    $args2 = array(
      'posts_per_page' => - 1,
      'orderby'        => 'title',
      'order'          => 'asc',
      'post_type'      => 'extra_store',
      'post_status'    => 'publish',
      'meta_query' => array(
        array(
          'key' => 'extra_store_enabled'
          )
        )                  
      );
    $get_enabled_stores = get_posts( $args2 );
    $all_stores_with_addresses = array();



    if(!empty($get_enabled_stores)) {
    foreach($get_enabled_stores as $current_enabled_store)

    {
      $current_store_address = get_post_meta( $current_enabled_store->ID, 'extra_store_address', true );
      $current_store_max_delivery_distance = get_post_meta( $current_enabled_store->ID, 'extra_store_max_delivery_distance', true );



      $current_customer_address= $woofood_total_address;



      $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($current_store_address)."&destinations=".urlencode($current_customer_address)."&mode=driving&sensor=false&key=".$woofood_google_distance_matrix_api_key;
      $details = htmlspecialchars_decode($details);
      $details = str_replace("&amp;", "&", $details );
      $json = file_get_contents($details);

      $details = json_decode($json, TRUE);
    
     
if($details["error_message"] =="")
  {



      if ($details['rows'][0]['elements'][0]['distance']['value'] < $current_store_max_delivery_distance *1000)
      {
//We this store can deliver /// 
        $all_stores_with_addresses[$current_enabled_store->ID] = $details['rows'][0]['elements'][0]['distance']['value'];



}//end if



//No available stores for this location//
else {



}//end else 

}
else
{
   wc_add_notice( 
  $details["error_message"],  'error' 
    );
}





}//end foreach store
}// if extra stores exists//
else
{


    wc_add_notice( 
   esc_html__('You are using WooFood Multistore Plugin and you have not created any stores. If you are using only one store disable the Multistore Plugin as it is not required', 'woofood-multistore-plugin'),  'error' 
    );

}


//if at least one store can deliver
if (!empty($all_stores_with_addresses))
{

  $store_name_array = array_keys($all_stores_with_addresses, min($all_stores_with_addresses));  

  $store_name = $store_name_array[0];


//set exta_store_name//
  $_POST['extra_store_name'] = $store_name;


}//end if

//none store can deliver...show error notice//
else{
  if(!empty($get_enabled_stores))
  {
      wc_add_notice( 
    sprintf( esc_html__('Delivery Service is not available in your Area from this store..', 'woofood-multistore-plugin') , 
     $woofood_delivery_hour_start, 
     $woofood_delivery_hour_end
     ), 'error' 
    );
  }



}






}//if the auto select store is enabled//


else{//select manually the store//


  
  $current_store_id = isset($_POST['extra_store_name']) ? intval($_POST['extra_store_name']) : null ; 

  
//get store details//    
if($current_store_id)
{



  $woofood_google_api_key = $options_woofood['woofood_google_api_key'];
  $woofood_max_delivery_distance = get_post_meta( $current_store_id, 'extra_store_max_delivery_distance', true );
  $woofood_store_address =  get_post_meta( $current_store_id, 'extra_store_address', true );
  $woofood_google_distance_matrix_api_key = $options_woofood['woofood_google_distance_matrix_api_key'];




  $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($woofood_store_address)."&destinations=".urlencode($woofood_total_address)."&mode=driving&sensor=false&key=".$woofood_google_distance_matrix_api_key;
  $details = htmlspecialchars_decode($details);
  $details = str_replace("&amp;", "&", $details );
  $json = file_get_contents($details);

  $details = json_decode($json, TRUE);

//print_r($details);

  if ($details['rows'][0]['elements'][0]['distance']['value'] < $woofood_max_delivery_distance *1000)
  {
//We can deliver /// 


  }


  else{

//we cannot deliver// show message//
    wc_add_notice( 
      sprintf( esc_html__('Delivery Service is not available in your Area from this store..', 'woofood-plugin') , 
       $woofood_delivery_hour_start, 
       $woofood_delivery_hour_end
       ), 'error' 
      );


}//end else


}//end if selected store any not empty//
else
{
     wc_add_notice( 
   esc_html__('You are using WooFood Multistore Plugin and you have not created any stores. If you are using only one store disable the Multistore Plugin as it is not required', 'woofood-multistore-plugin'),  'error' 
    );
}

}//else end ...not auto select    

}//end else ..delivery

}//end function

//check distance on checkout//


//add metabox on product to select on which stores are available//
add_action( 'add_meta_boxes', 'add_store_select_meta_box' );

function add_store_select_meta_box( $post ) {
  add_meta_box(
'store_select_meta_box', // ID, should be a string.
esc_html__('Hide Items on these stores','woofood-multistore-plugin'), // Meta Box Title.
'store_select_meta_callback', // Your call back function, this is where your form field will go.
'product', // The post type you want this to show up on, can be post, page, or custom post type.
'side', // The placement of your meta box, can be normal or side.
'core' // The priority in which this will be displayed.
);
}


function store_select_meta_callback($post) {
  $args = array(
    'posts_per_page' => - 1,
    'orderby'        => 'title',
    'order'          => 'asc',
    'post_type'      => 'extra_store',
    'post_status'    => 'publish',
    );
  $extra_stores = get_posts( $args );
  $checkboxMeta =  get_post_meta( $post->ID, 'checked_extra_stores', true );


  if ( ! empty( $extra_stores )) : ?>


  <?php foreach ( $extra_stores as $extra_store ) : ?>
    

    <input type="checkbox" name="checked_extra_stores[<?php echo $extra_store->ID; ?>]" id="checked_extra_stores[]" value="<?php echo $extra_store->ID; ?>" <?php if ( isset ( $checkboxMeta[$extra_store->ID] ) ) checked( $checkboxMeta[$extra_store->ID], $extra_store->ID ); ?> /><?php echo esc_attr( $extra_store->post_title ); ?><br />


  <?php endforeach; ?>
  <?php
  global $current_user;

  $user = $current_user;
  $user_id = $user->ID;
  $store_near_me = get_user_meta($current_user->ID, 'store_near_user', true);

//get hidden products for this store//


  $args3 = array(
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'asc',
    'post_type'      => 'product',
    'fields' => 'ids',
    'post_status'    => 'publish',
    'meta_query' => array(
      array(
        'key' => 'checked_extra_stores',
        'value' => $store_near_me,
        'compare' => 'LIKE'
        )
      )                  
    );
  $hidden_products = get_posts( $args3 );
//print_r($checkboxMeta );
  ?>
  

  <?php esc_html_e('<hr/><b>Note:</b>You can leave it blank to be available to all stores.','woofood-multistore-plugin'); ?>
<?php endif;
?>



<?php }


add_action( 'save_post', 'save_extra_stores_selected' );
function save_extra_stores_selected( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    return;
  if ( ( isset ( $_POST['my_awesome_nonce'] ) ) && ( ! wp_verify_nonce( $_POST['my_awesome_nonce'], plugin_basename( __FILE__ ) ) ) )
    return;
  if ( ( isset ( $_POST['post_type'] ) ) && ( 'page' == $_POST['post_type'] )  ) {
    if ( ! current_user_can( 'edit_page', $post_id ) ) {
      return;
    }    
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return;
    }
  }

//saves bob's value
  if( isset( $_POST[ 'checked_extra_stores' ] ) ) {
    update_post_meta( $post_id, 'checked_extra_stores', $_POST[ 'checked_extra_stores' ] );
  } else {
    update_post_meta( $post_id, 'checked_extra_stores', null );
  }


}
//add metabox on product to select on which stores are available//


//add current_store meta data after user registration//
function wf_multi_after_register($user_id){
  if(!is_admin()) {
// Get the Newly Created User ID
  $the_user = get_userdata($user_id);

  $options_woofood = get_option('woofood_options');
  $woofood_google_distance_matrix_api_key = $options_woofood['woofood_google_distance_matrix_api_key'];

  $woofood_options_multistore = get_option('woofood_options_multistore');
  $woofood_auto_store_select = $woofood_options_multistore['woofood_auto_store_select'];


//get customer address//
  $woofood_current_address = get_user_meta($user_id, 'billing_address_1', true);
  $woofood_current_city = get_user_meta($user_id, 'billing_city', true);
  $woofood_current_postcode = get_user_meta($user_id, 'billing_postcode', true);

  $woofood_total_address = $woofood_current_address.",".$woofood_current_city.",".$woofood_current_postcode.", Greece";

//get customer address//

//if the auto select store is enabled//
  if($woofood_auto_store_select)
  {


    $args2 = array(
      'posts_per_page' => - 1,
      'orderby'        => 'title',
      'order'          => 'asc',
      'post_type'      => 'extra_store',
      'post_status'    => 'publish',
      'meta_query' => array(
        array(
          'key' => 'extra_store_enabled'
          )
        )                  
      );
    $get_enabled_stores = get_posts( $args2 );
    $all_stores_with_addresses = array();

    foreach($get_enabled_stores as $current_enabled_store)

    {
      $current_store_address = get_post_meta( $current_enabled_store->ID, 'extra_store_address', true );
      $current_store_max_delivery_distance = get_post_meta( $current_enabled_store->ID, 'extra_store_max_delivery_distance', true );



      $current_customer_address= $woofood_total_address;



      $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($current_store_address)."&destinations=".urlencode($current_customer_address)."&mode=driving&sensor=false&key=".$woofood_google_distance_matrix_api_key;
      $details = htmlspecialchars_decode($details);
      $details = str_replace("&amp;", "&", $details );
      $json = file_get_contents($details);

      $details = json_decode($json, TRUE);


       if(!empty($details["error_message"]))
  {
     wc_add_notice( 
      sprintf( "Google API Error:".$details["error_message"].".Please Correct the configuration for Distance Matrix API Key on your Google Console Account", 
        $woofood_delivery_hour_start, 
        $woofood_delivery_hour_end
        ), 'error' 
      );
  }




      elseif ($details['rows'][0]['elements'][0]['distance']['value'] < $current_store_max_delivery_distance *1000)
      {
//We this store can deliver /// 
        $all_stores_with_addresses[$current_enabled_store->post_title] = $current_store_max_delivery_distance;



}//end if



//No available stores for this location//
else {
  update_user_meta($the_user->ID, 'store_near_user', '');



}//end else 





}//end foreach store


//if at least one store can delive
if (!empty($all_stores_with_addresses))
{

  $store_name_array = array_keys($all_stores_with_addresses, min($all_stores_with_addresses));  

  $store_name = $store_name_array[0];

//Update User and set store_near_user meta//.
  update_user_meta($the_user->ID, 'store_near_user', $store_name);

}//end if

//none store can deliver...show error notice//
else{

  wc_add_notice( 
    sprintf( esc_html__('Delivery Service is not available in your Area from this store..', 'woofood-multistore-plugin') , 
     $woofood_delivery_hour_start, 
     $woofood_delivery_hour_end
     ), 'error' 
    );


}


}
}

}
add_action('profile_update', 'wf_multi_after_register');
//add current_store meta data after user registration//

//update profile when user meta from woocommerce is changed //
function wf_update_user_on_customer_address($user_id){
  wp_update_user(array('ID' => $user_id));


}
add_action('woocommerce_customer_save_address', 'wf_update_user_on_customer_address');


//update profile when user meta from woocommerce is changed //






//check if store_near_user is empty and show notice//

function wf_check_store_near_user_empty() {
  if ( is_user_logged_in()) {

    $user = wp_get_current_user();
    $user_id = $user->ID;
    $store_near_me = get_user_meta($user_id, 'store_near_me', true);


    if (empty($store_near_me)) {
      wc_add_notice( 
        sprintf( esc_html__('Delivery Service is not available in your area..', 'woofood-plugin') , 
         $woofood_delivery_hour_start, 
         $woofood_delivery_hour_end
         ), 'error' 
        );



    }



}//is is logged in


}//end function

add_action('wp_header', 'wf_check_store_near_user_empty');



function woofood_multistore_filter_orders($query) {
  global $pagenow;
  $qv = &$query->query_vars;

  $currentUserRoles = wp_get_current_user()->roles;

  if (in_array('multistore_user', $currentUserRoles)) {

    remove_action( 'pre_get_posts', 'woofood_multistore_filter_orders' );


    if ( $query ) {

//if (isset($qv['post_type']) && $qv['post_type'] == 'shop_order' ) {   

      $args = array(
        'post_type'        => 'extra_store',

        'meta_query' => array(
          array(
            'key' => 'extra_store_user',
            'value' => wp_get_current_user()->ID,
            'compare' => '==',
            )
          )
        );
      $store_name ="";

      $stores = get_posts($args);
      if(!empty($stores))
      {
        $store_name = $stores[0]->ID;
      }
      else
      {
        $store_name ="storethatnotexists";
      }


      $query->set('meta_key', 'extra_store_name');
      $query->set('meta_value', $store_name);

    }
  }

  return $query;
}
add_filter('pre_get_posts', 'woofood_multistore_filter_orders');

add_filter('views_edit-shop_order', 'views_filter_for_own_posts' );

function views_filter_for_own_posts( $views ) {

  $post_type = get_query_var('post_type');

  unset($views['mine']);

  $new_views = array(
    'all'   => esc_html__('All', 'woocommerce'),

    'wc-completed'   => esc_html__('Completed', 'woocommerce'),
    'wc-processing'   => esc_html__('Processing', 'woocommerce'),
    'wc-accepting'   => esc_html__('Accepting', 'woofood-plugin'),
    'wc-cancelled'   => esc_html__('Cancelled', 'woocommerce'),

    );

  foreach( $new_views as $view => $name ) {

$args = array(
        'post_type'        => 'extra_store',

        'meta_query' => array(
          array(
            'key' => 'extra_store_user',
            'value' => wp_get_current_user()->ID,
            'compare' => '==',
            )
          )
        );
      $store_name ="";

      $stores = get_posts($args);
      if(!empty($stores))
      {
        $store_name = $stores[0]->ID;
      }
      else
      {
        $store_name ="storethatnotexists";
      }



    $query = array(
      'post_type'   => $post_type,
      'meta_query' => array(
        array(
          'key' => 'extra_store_name',
          'value' => $store_name,
          'compare' => '=='
          )
        )


      );

    if($view == 'all') {

      $query['post_status'] = "all";
      $class = ( get_query_var('all_posts') == 1 || get_query_var('post_status') == '' ) ? ' class="current"' : '';
      $url_query_var = '';

    } else if ($view){

      $query['post_status'] = $view;
      $class = ( get_query_var('post_status') == $view ) ? ' class="current"' : '';
      $url_query_var = 'post_status='.$view;

    }

    $result = new WP_Query($query);

    if($result->found_posts > 0) {

      $views[$view] = sprintf(
        '<a href="%s"'. $class .'>'.esc_html__($name, 'woocommerce').' <span class="count">(%d)</span></a>',
        admin_url('edit.php?'.$url_query_var.'&post_type='.$post_type),
        $result->found_posts
        );

    } else {

      unset($views[$view]);

    }

  }

  return $views;
}


add_filter( 'woocommerce_include_processing_order_count_in_menu', 'woofood_hide_processing_count' );
function woofood_hide_processing_count( $show ) {
  $show = false;
  return $show;
}



function woofood_get_orders_custom_query( $query, $query_vars ) {
  if ( ! empty( $query_vars['extra_store_name'] ) ) {
    $query['meta_query'][] = array(
      'key' => 'extra_store_name',
      'value' => esc_attr( $query_vars['extra_store_name'] ),
      'compare' =>'=='
      );
  }

  return $query;
}
add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', 'woofood_get_orders_custom_query', 10, 2 );


add_role( 'multistore_user', 'WooFood MultiStore User', array('read' => true,  'edit_post'=> true) );
$role = get_role("multistore_user");
if($role)
{
  $role->add_cap("manage_woocommerce");
$role->add_cap("edit_shop_order");
$role->add_cap("read_shop_order");
$role->add_cap("delete_shop_order");
$role->add_cap("edit_shop_orders");
$role->add_cap("edit_others_shop_orders");
$role->add_cap("publish_shop_orders");
$role->add_cap("read_private_shop_orders");
$role->add_cap("delete_shop_orders");
$role->add_cap("delete_private_shop_orders");
$role->add_cap("delete_published_shop_orders");
$role->add_cap("delete_others_shop_orders");
$role->add_cap("edit_private_shop_orders");
$role->add_cap("edit_published_shop_orders");
$role->add_cap("manage_shop_order_terms");
$role->add_cap("edit_shop_order_terms");
$role->add_cap("delete_shop_order_terms");
$role->add_cap("assign_shop_order_terms");

$role->remove_cap("edit_post");
$role->remove_cap("read_post");
$role->remove_cap("delete_post");
$role->remove_cap("edit_posts");
$role->remove_cap("edit_others_posts");
$role->remove_cap("publish_posts");
$role->remove_cap("read_private_posts");
$role->remove_cap("view_woocommerce_reports");

}




function woofood_woocommerce_remove_settings() {
  $currentUserRoles = wp_get_current_user()->roles;

  $remove = array( 'wc-settings', 'wc-status', 'wc-addons', );
  foreach ( $remove as $submenu_slug ) {

    if (in_array('multistore_user', $currentUserRoles)) {

      remove_submenu_page( 'woocommerce', $submenu_slug );
    }
  }
}

add_action( 'admin_menu', 'woofood_woocommerce_remove_settings', 99, 0 );    





add_action( 'woocommerce_after_checkout_form', 'woofood_multistore_display_address_store_address_on_change');
 
function woofood_multistore_display_address_store_address_on_change() {

    $woofood_options = get_option('woofood_options');
  $woofood_enable_pickup_option = isset($woofood_options['woofood_enable_pickup_option']) ? $woofood_options['woofood_enable_pickup_option'] : null ;
  if ($woofood_enable_pickup_option){
?>
<script>
jQuery(document).ready(function () {


        var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();
            if(woofood_order_type =="pickup")
            {
              var woofood_store_selected = jQuery('#extra_store_name_pickup option:selected').val();
                    jQuery('.woofood_store_address_checkout').css('display', 'none');

          jQuery('#woofood_store_address_checkout_'+woofood_store_selected).css('display', 'block');

            }

              




    jQuery(document).on('change', '#extra_store_name_pickup', function (){

        var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();

  if(woofood_order_type=="pickup")
  {

  var woofood_store_selected = jQuery('#extra_store_name_pickup option:selected').val();
                    jQuery('.woofood_store_address_checkout').css('display', 'none');


                  jQuery('#woofood_store_address_checkout_'+woofood_store_selected).css('display', 'block');

    }

        return false;
    });








jQuery(document).on('change', 'input[type=radio][name=woofood_order_type]', function (){

        var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();

          if(woofood_order_type=="pickup")
  {
    var woofood_store_selected = jQuery('#extra_store_name_pickup option:selected').val();
                    jQuery('.woofood_store_address_checkout').css('display', 'none');


                  jQuery('#woofood_store_address_checkout_'+woofood_store_selected).css('display', 'block');
         
  }
   else if(woofood_order_type=="delivery")
   {
                                   jQuery('.woofood_store_address_checkout').css('display', 'none');



   }  
    

        return false;
    });




});

</script>

<?php
}//end if pickup is enabled

}
add_filter( 'woocommerce_is_purchasable', 'disable_purchasable_on_product_category_archives', 10, 2 );
function disable_purchasable_on_product_category_archives( $purchasable, $product ) {
    // HERE define your product category terms
      global $woocommerce;
        if (WC()->session) {
      $nearest_store_id =   WC()->session->get( 'woofood_nearest_store_id');
      $hidden_on_stores = get_post_meta( $product->get_id(), 'checked_extra_stores', true );


      if(is_array( $hidden_on_stores) && in_array($nearest_store_id, $hidden_on_stores))
      {
                $purchasable = false;

      }
    }

    

    return $purchasable;
}

add_filter( 'woocommerce_email_recipient_new_order', 'wf_new_order_change_recipient', 99, 2 );
function wf_new_order_change_recipient( $recipient, $order ) {
  $page = $_GET['page'] = isset( $_GET['page'] ) ? $_GET['page'] : '';
if ( 'wc-settings' === $page ) {
    return $recipient; 
}
if ( ! $order instanceof WC_Order ) {
    return $recipient; 
  }
  $store_selected = get_post_meta($order->get_id(), 'extra_store_name', true);
  $store_selected = intval($store_selected);

  if($store_selected)
  {
  $store_email =  get_post_meta($store_selected, 'extra_store_email', true);
  return $store_email;

  }
  else
  {
    return $recipient;

  }
 

}

  add_action("wp_ajax_woofood_multistore_save_store", "woofood_multistore_save_store" );
 
 function woofood_multistore_save_store()
{
    if(isset($_POST["store_id"]))
    {
        $store_id = intval($_POST["store_id"]);
        if(isset($_POST["extra_store_name"]))
    {
        $extra_store_name = $_POST["extra_store_name"];

         $store = array(
      'ID'           => $store_id,
      'post_title'   => $extra_store_name,
  );
 
// Update the post into the database
  wp_update_post( $store );

    }
     if(isset($_POST["extra_store_address"]))
    {
        $extra_store_address = $_POST["extra_store_address"];
        update_post_meta($store_id, 'extra_store_address',$extra_store_address );
    }

    if(isset($_POST["extra_store_phone"]))
    {
        $extra_store_phone = $_POST["extra_store_phone"];
                update_post_meta($store_id, 'extra_store_phone',$extra_store_phone );

    }
     if(isset($_POST["extra_store_email"]))
    {
        $extra_store_email = $_POST["extra_store_email"];
                        update_post_meta($store_id, 'extra_store_email',$extra_store_email );

    }
     if(isset($_POST["extra_store_distance_type"]))
    {
        $extra_store_distance_type = $_POST["extra_store_distance_type"];
                         update_post_meta($store_id, 'extra_store_distance_type',$extra_store_distance_type );
       
    }

    if(isset($_POST["extra_store_max_delivery_distance"]))
    {
        $extra_store_max_delivery_distance = $_POST["extra_store_max_delivery_distance"];

                                 update_post_meta($store_id, 'extra_store_max_delivery_distance',$extra_store_max_delivery_distance );

    }
     if(isset($_POST["extra_store_postalcodes"]))
    {
        $extra_store_postalcodes = $_POST["extra_store_postalcodes"];
                                         update_post_meta($store_id, 'extra_store_postalcodes',$extra_store_postalcodes );

    }
     if(isset($_POST["extra_store_polygon_area"]))
    {
        $extra_store_polygon_area = $_POST["extra_store_polygon_area"];
                                                 update_post_meta($store_id, 'extra_store_polygon_area',$extra_store_polygon_area );

    }

      if(isset($_POST["extra_store_user"]))
    {
        $extra_store_user = $_POST["extra_store_user"];
                                                 update_post_meta($store_id, 'extra_store_user',$extra_store_user );

    }
     if(isset($_POST["extra_store_enabled"]))
    {
        $extra_store_enabled = $_POST["extra_store_enabled"];
        update_post_meta($store_id, 'extra_store_enabled',$extra_store_enabled );
    }
    else
    {
              delete_post_meta($store_id, 'extra_store_enabled');

    }

    $order_types = woofood_get_order_types(); 

     foreach($order_types as $type => $name)
              {
                 if(isset($_POST['order_type_'.$type]))
                     {
                      update_post_meta($store_id, 'order_type_'.$type, $_POST['order_type_'.$type] );

                     }
                     else
                     {
                      delete_post_meta($store_id, 'order_type_'.$type);

                     }


              }

    

     $days = array('Monday', 'Tuesday', 'Wednesday','Thursday','Friday','Saturday', 'Sunday'); 
              foreach($days as $day)
              {
                $day = strtolower($day);
                if(isset($_POST['woofood_delivery_hours_'.$day.'_start']))
                     {

                      update_post_meta($store_id, 'woofood_delivery_hours_'.$day.'_start', $_POST['woofood_delivery_hours_'.$day.'_start'] );


                     }

                      if(isset($_POST['woofood_delivery_hours_'.$day.'_end']))
                     {

                      update_post_meta($store_id, 'woofood_delivery_hours_'.$day.'_end', $_POST['woofood_delivery_hours_'.$day.'_end'] );


                     }

                     if(isset($_POST['woofood_delivery_hours_'.$day.'_start2']))
                     {

                      update_post_meta($store_id, 'woofood_delivery_hours_'.$day.'_start2', $_POST['woofood_delivery_hours_'.$day.'_start2'] );


                     }

                      if(isset($_POST['woofood_delivery_hours_'.$day.'_end2']))
                     {

                      update_post_meta($store_id, 'woofood_delivery_hours_'.$day.'_end2', $_POST['woofood_delivery_hours_'.$day.'_end2'] );


                     }

                      if(isset($_POST['woofood_delivery_hours_'.$day.'_start3']))
                     {

                      update_post_meta($store_id, 'woofood_delivery_hours_'.$day.'_start3', $_POST['woofood_delivery_hours_'.$day.'_start3'] );


                     }

                      if(isset($_POST['woofood_delivery_hours_'.$day.'_end3']))
                     {

                      update_post_meta($store_id, 'woofood_delivery_hours_'.$day.'_end3', $_POST['woofood_delivery_hours_'.$day.'_end3'] );


                     }
                         if(isset($_POST['woofood_pickup_hours_'.$day.'_start']))
                     {

                      update_post_meta($store_id, 'woofood_pickup_hours_'.$day.'_start', $_POST['woofood_pickup_hours_'.$day.'_start'] );


                     }

                      if(isset($_POST['woofood_pickup_hours_'.$day.'_end']))
                     {

                      update_post_meta($store_id, 'woofood_pickup_hours_'.$day.'_end', $_POST['woofood_pickup_hours_'.$day.'_end'] );


                     }

                     if(isset($_POST['woofood_pickup_hours_'.$day.'_start2']))
                     {

                      update_post_meta($store_id, 'woofood_pickup_hours_'.$day.'_start2', $_POST['woofood_pickup_hours_'.$day.'_start2'] );


                     }

                      if(isset($_POST['woofood_pickup_hours_'.$day.'_end2']))
                     {

                      update_post_meta($store_id, 'woofood_pickup_hours_'.$day.'_end2', $_POST['woofood_pickup_hours_'.$day.'_end2'] );


                     }

                      if(isset($_POST['woofood_pickup_hours_'.$day.'_start3']))
                     {

                      update_post_meta($store_id, 'woofood_pickup_hours_'.$day.'_start3', $_POST['woofood_pickup_hours_'.$day.'_start3'] );


                     }

                      if(isset($_POST['woofood_pickup_hours_'.$day.'_end3']))
                     {

                      update_post_meta($store_id, 'woofood_pickup_hours_'.$day.'_end3', $_POST['woofood_pickup_hours_'.$day.'_end3'] );


                     }

              }
                   
                   

                  

          











    }
    echo woofood_multistore_list_stores();


    wp_die();
}

add_action("wp_ajax_woofood_multistore_add_new_store", "woofood_multistore_add_new_store" );
 
 function woofood_multistore_add_new_store()
{
  // Create post object
$my_post = array(
  'post_title'    => "New Store",
  'post_status'   => 'publish',
  'post_type'   => 'extra_store',

);
 
// Insert the post into the database
$id = wp_insert_post( $my_post );
if($id)
{
      echo woofood_multistore_list_stores();

}

  wp_die();

}



add_action("wp_ajax_woofood_multistore_delete_store", "woofood_multistore_delete_store" );
 
 function woofood_multistore_delete_store()
{
  if(isset($_POST["store_id"]))
  {
    $store_id = intval($_POST["store_id"]);
    wp_delete_post($store_id);
  }

      echo woofood_multistore_list_stores();



  wp_die();

}


function woofood_multistore_item($store, $users)
{
      $woofood_options = get_option( 'woofood_options' );

     $store_address = get_post_meta( $store->ID, 'extra_store_address', true );
     $store_title = $store->post_title;
     $store_enabled = get_post_meta( $store->ID, 'extra_store_enabled', true );   
     $store_email = get_post_meta( $store->ID, 'extra_store_email', true );   
     $store_phone = get_post_meta( $store->ID, 'extra_store_phone', true );   
     $store_max_delivery_distance = get_post_meta( $store->ID, 'extra_store_max_delivery_distance', true );   
     $store_user = get_post_meta( $store->ID, 'extra_store_user', true );   
     $store_distance_type = isset($woofood_options["woofood_distance_type"]) ? $woofood_options["woofood_distance_type"] : "default" ;   
     $store_polygon_area = get_post_meta( $store->ID, 'extra_store_polygon_area', true );   
     $store_postalcodes = get_post_meta( $store->ID, 'extra_store_postalcodes', true );   
     $store_id =     $store->ID;
     $store_lat = get_post_meta( $store->ID, 'extra_store_lat', true );   
     $store_lng = get_post_meta( $store->ID, 'extra_store_lng', true );   

     $stores_lat_lng = array();

              if(!empty($store_address) && $store_distance_type === "polygon" && empty($store_lat) && empty($store_lng))
              {

                      $details = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($store_address)."&key=".$woofood_options['woofood_google_distance_matrix_api_key']."";
                      $details = htmlspecialchars_decode($details);
                      $details = str_replace("&amp;", "&", $details );
                      $json = woofood_get_contents($details);
                      $details = json_decode($json, TRUE);
                     
                      if(!empty($details["error_message"]))
                      {

                        echo $details["error_message"];
                        
                      }




                     elseif ( !empty($details['results'][0]['geometry']['location']["lat"] ) && !empty($details['results'][0]['geometry']['location']["lng"] ))
                      {
                        $store_lat = floatval($details['results'][0]['geometry']['location']["lat"]);
                      $store_lng = floatval($details['results'][0]['geometry']['location']["lng"]) ;
                      update_post_meta($store->ID, "extra_store_lat", $store_lat);
                      update_post_meta($store->ID, "extra_store_lng", $store_lng);


                      }

              }
              else
              {
                $store_lat = 0;
                $store_lng = 0;

              }
     ?>
     <div class="woofood_multistore_list_item">
        <div class="woofood_multistore_list_item_title">
            <?php echo $store_title; ?>
        </div>
         <div class="woofood_multistore_list_item_actions">

            <a store-id="<?php echo $store_id; ?>" class="button edit"><?php esc_html_e('Edit Store', 'woofood-multistore-plugin'); ?> </a>

             <form class="woofood_multistore_delete">

<input type="hidden" name="action" value="woofood_multistore_delete_store" />
<input type="hidden" name="store_id" value="<?php echo $store_id; ?>" />
    <button type="submit" class="button"><?php esc_html_e('Delete Store', 'woofood-multistore-plugin'); ?></button>

      </form>

        </div>
     </div>
     <div class="woofood_multistore_list_item_settings" store-id="<?php echo $store_id; ?>">
        <form class="multistore_settings_form">
           <div class="woofood_multistore_list_item_settings_field">
            <label><?php esc_html_e('Enabled', 'woofood-multistore-plugin'); ?></label>
            <input type="checkbox"  name="extra_store_enabled" value="1" <?php if($store_enabled) {echo " checked";} ?> />
        </div>
        <div class="woofood_multistore_list_item_settings_field">
            <label><?php esc_html_e('Store Name', 'woofood-multistore-plugin'); ?></label>
            <input type="text"  name="extra_store_name" value="<?php echo  $store_title; ?>" placeholder="<?php esc_html_e('Store Name', 'woofood-multistore-plugin'); ?>" />
        </div>
        <div class="woofood_multistore_list_item_settings_field">
            <label><?php esc_html_e('Store Address', 'woofood-multistore-plugin'); ?></label>
            <input type="text" name="extra_store_address" onfocus="geolocate()" value="<?php echo  $store_address; ?>" placeholder="<?php esc_html_e('Store Address', 'woofood-multistore-plugin'); ?>" />
        </div>
         <div class="woofood_multistore_list_item_settings_field">
            <label><?php esc_html_e('Store Phone', 'woofood-multistore-plugin'); ?></label>
            <input type="text" name="extra_store_phone" value="<?php echo  $store_phone; ?>" placeholder="<?php esc_html_e('Store Phone', 'woofood-multistore-plugin'); ?>" />
        </div>
           <div class="woofood_multistore_list_item_settings_field">
            <label><?php esc_html_e('Store Email', 'woofood-multistore-plugin'); ?></label>
            <input type="text" name="extra_store_email" value="<?php echo  $store_email; ?>" placeholder="<?php esc_html_e('Store Email', 'woofood-multistore-plugin'); ?>" />
        </div>
        <div class="woofood_multistore_list_item_settings_field">
            <label><?php esc_html_e('Select Multistore User', 'woofood-multistore-plugin'); ?></label>
            <select name="extra_store_user"   placeholder="<?php esc_html_e('Maximum Delivery Distance', 'woofood-multistore-plugin'); ?>" >
               <?php
                foreach ( $users as $user ) {
      $selected ="";
      if($user->ID== $store_user)

      {
        $selected =" selected";

      }

      echo  '<option value="'.$user->ID.'" '.$selected.' >' . esc_html( $user->display_name ) . '[' . esc_html( $user->user_email ) . ']</option>';
    }
               ?>
            </select>
        </div>
      
        <input type="hidden"  name="extra_store_distance_type" class="extra_store_distance_type" id="extra_store_distance_type_<?php echo $store_id ;?>" value="<?php echo $store_distance_type; ?>" />
        <div class="woofood_multistore_list_item_settings_field">
            <label><?php esc_html_e('Maximum Delivery Distance', 'woofood-multistore-plugin'); ?></label>
            <input type="text" name="extra_store_max_delivery_distance" id="extra_store_max_delivery_distance_<?php echo $store_id ;?>" value="<?php echo  $store_max_delivery_distance; ?>" placeholder="<?php esc_html_e('Maximum Delivery Distance', 'woofood-multistore-plugin'); ?>" />
        </div>
        <div class="woofood_multistore_list_item_settings_field">
            <label><?php esc_html_e('Postal Codes (Seperate them with comma)', 'woofood-multistore-plugin'); ?></label>
            <input type="text" name="extra_store_postalcodes" id="extra_store_postalcodes_<?php echo $store_id ;?>" value="<?php echo  $store_postalcodes; ?>" placeholder="<?php esc_html_e('Postal Codes (Seperate them with comma)', 'woofood-multistore-plugin'); ?>" />
        </div>
                     <input type="hidden" value='<?php echo $store_polygon_area; ?>' name="extra_store_polygon_area"  id="extra_store_polygon_area_<?php echo $store_id ;?>" >

                      <div class="woofood_polygon_wrapper" id="woofood_polygon_wrapper_<?php echo $store_id ;?>">
              <div class="woofood_polygon_header" style="
    padding: 10px;
    background: #eee;
    border-top: 1px solid #00000063;
    border-left: 1px solid #00000063;
    border-right: 1px solid #00000063;
    display: flex;
    flex-wrap: wrap;
">


                <a class="button " name="clearPolygon" id="clearPolygon_<?php echo $store_id ;?>"><?php esc_html_e('Clear Map', 'woofood-mulistore-plugin'); ?></a>
              </div>
            <div id="map_<?php echo $store_id;?>"  class="woofood_polygon_map" style="position: relative;overflow: hidden;width: 100%;height: auto;"></div>
          </div>



<div class="woofood-muultistore-order-type-options">
       <div class="woofood-muultistore-delivery-hours-header">
        <h1> <?php esc_html_e("Order Types Available", 'woofood-multistore-plugin'); ?> </h1>

       </div>
              <div class="woofood-multistore-delivery-hours-content">
              <?php $order_types = woofood_get_order_types();  ?>

                <?php foreach($order_types as $order_type => $name): ?>
                   <div class="woofood_multistore_list_item_settings_field">
            <label><?php echo  $name. " ".esc_html__('Enabled', 'woofood-multistore-plugin'); ?></label>
            <input type="checkbox"  name="order_type_<?php echo $order_type; ?>" value="1" <?php if(get_post_meta($store_id, 'order_type_'.$order_type, true))  {echo " checked";} ?> />
            </div>

                <?php endforeach; ?>

              </div>
            </div>


   <div class="woofood-muultistore-delivery-hours">
       <div class="woofood-muultistore-delivery-hours-header">
        <h1> <?php esc_html_e("Delivery Hours", 'woofood-multistore-plugin'); ?> </h1>
       </div>
              <div class="woofood-multistore-delivery-hours-content">

                  <?php  $days = array('Monday', 'Tuesday', 'Wednesday','Thursday','Friday','Saturday', 'Sunday'); ?>
                  <?php foreach($days as $day): ?>
                    <?php

                    $day_start = get_post_meta($store_id, 'woofood_delivery_hours_'.strtolower($day).'_start', true );
                    $day_end = get_post_meta($store_id, 'woofood_delivery_hours_'.strtolower($day).'_end', true );
                    $day_start2 = get_post_meta($store_id, 'woofood_delivery_hours_'.strtolower($day).'_start2', true );
                    $day_end2 = get_post_meta($store_id, 'woofood_delivery_hours_'.strtolower($day).'_end2', true );
                    $day_start3 = get_post_meta($store_id, 'woofood_delivery_hours_'.strtolower($day).'_start3', true );
                    $day_end3 = get_post_meta($store_id, 'woofood_delivery_hours_'.strtolower($day).'_end3', true );

                    ?>
                    <?php woofood_multistore_day_fields($day, $day_start, $day_end, $day_start2, $day_end2, $day_start3, $day_end3); ?>

                  <?php endforeach; ?>
                



                </div>

    </div>

       <div class="woofood-muultistore-delivery-hours">
       <div class="woofood-muultistore-pickup-hours-header">
        <h1> <?php esc_html_e("Pickup Hours", 'woofood-multistore-plugin'); ?> </h1>
       </div>
              <div class="woofood-multistore-pickup-hours-content">

                  <?php  $days = array('Monday', 'Tuesday', 'Wednesday','Thursday','Friday','Saturday', 'Sunday'); ?>
                  <?php foreach($days as $day): ?>
                    <?php

                    $day_start = get_post_meta($store_id, 'woofood_pickup_hours_'.strtolower($day).'_start', true );
                    $day_end = get_post_meta($store_id, 'woofood_pickup_hours_'.strtolower($day).'_end', true );
                    $day_start2 = get_post_meta($store_id, 'woofood_pickup_hours_'.strtolower($day).'_start2', true );
                    $day_end2 = get_post_meta($store_id, 'woofood_pickup_hours_'.strtolower($day).'_end2', true );
                    $day_start3 = get_post_meta($store_id, 'woofood_pickup_hours_'.strtolower($day).'_start3', true );
                    $day_end3 = get_post_meta($store_id, 'woofood_pickup_hours_'.strtolower($day).'_end3', true );

                    ?>
                    <?php woofood_multistore_day_fields_pickup($day, $day_start, $day_end, $day_start2, $day_end2, $day_start3, $day_end3); ?>

                  <?php endforeach; ?>
                



                </div>

    </div>


<div class="woofood_multistore_list_item_settings_footer">
    <button type="submit" class="button"><?php esc_html_e("Save Changes", 'woofood-multistore-plugin'); ?></button>
</div>
<input type="hidden" name="action" value="woofood_multistore_save_store" />
<input type="hidden" name="store_id" value="<?php echo $store_id; ?>" />

      </form>
     </div>
       <script>
jQuery( document ).ready(function() {
  if(jQuery('#extra_store_distance_type_<?php echo $store_id ;?>').val() === "polygon" )

{
  jQuery("#extra_store_max_delivery_distance_<?php echo $store_id ;?>").css("display", "none");
    jQuery("#extra_store_max_delivery_distance_<?php echo $store_id ;?>").prev().css("display", "none");

            jQuery("#woofood_polygon_wrapper_<?php echo $store_id ;?>").css("display", "block");

            jQuery('#map_<?php echo $store_id ;?>').css({'height':jQuery('#map').width()/2+'px'});
                    jQuery("#extra_store_postalcodes_<?php echo $store_id ;?>").css("display", "none");
                    jQuery("#extra_store_postalcodes_<?php echo $store_id ;?>").prev().css("display", "none");


}
else if(jQuery('#extra_store_distance_type_<?php echo $store_id ;?>').val() === "postalcode")
{
  jQuery("#woofood_polygon_wrapper_<?php echo $store_id ;?>").css("display", "none");
    jQuery("#extra_store_max_delivery_distance_<?php echo $store_id ;?>").css("display", "none");
        jQuery("#extra_store_max_delivery_distance_<?php echo $store_id ;?>").prev().css("display", "none");

    jQuery("#extra_store_postalcodes_<?php echo $store_id ;?>").css("display", "block");
    jQuery("#extra_store_postalcodes_<?php echo $store_id ;?>").prev().css("display", "block");

}
else
{
  jQuery("#woofood_polygon_wrapper_<?php echo $store_id ;?>").css("display", "none");
    jQuery("#extra_store_max_delivery_distance_<?php echo $store_id ;?>").css("display", "block");
        jQuery("#extra_store_max_delivery_distance_<?php echo $store_id ;?>").prev().css("display", "block");

        jQuery("#extra_store_postalcodes_<?php echo $store_id ;?>").css("display", "none");
                jQuery("#extra_store_postalcodes_<?php echo $store_id ;?>").prev().css("display", "none");


}


   jQuery('#extra_store_distance_type_<?php echo $store_id ;?>').on('change', function() {
  
  if(this.value == "polygon")
  {

    jQuery("#extra_store_max_delivery_distance_<?php echo $store_id ;?>").css("display", "none");
        jQuery("#extra_store_max_delivery_distance_<?php echo $store_id ;?>").prev().css("display", "none");

            jQuery("#woofood_polygon_wrapper_<?php echo $store_id ;?>").css("display", "block");
                jQuery("#extra_store_postalcodes_<?php echo $store_id ;?>").css("display", "none");
                jQuery("#extra_store_postalcodes_<?php echo $store_id ;?>").prev().css("display", "none");

            initMap_<?php echo $store_id ;?>();
            jQuery('#map_<?php echo $store_id ;?>').css({'height':jQuery('#map_<?php echo $store_id ;?>').width()/2+'px'});


  }
  else if(this.value  === "postalcode")
{
  jQuery("#woofood_polygon_wrapper_<?php echo $store_id ;?>").css("display", "none");

    jQuery("#extra_store_max_delivery_distance_<?php echo $store_id ;?>").css("display", "none");
        jQuery("#extra_store_max_delivery_distance_<?php echo $store_id ;?>").prev().css("display", "none");

    jQuery("#extra_store_postalcodes_<?php echo $store_id ;?>").css("display", "block");
    jQuery("#extra_store_postalcodes_<?php echo $store_id ;?>").prev().css("display", "block");

}
  else
  {
        jQuery("#woofood_polygon_wrapper_<?php echo $store_id ;?>").css("display", "none");
    jQuery("#extra_store_max_delivery_distance_<?php echo $store_id ;?>").css("display", "block");
        jQuery("#extra_store_max_delivery_distance_<?php echo $store_id ;?>").prev().css("display", "block");

    jQuery("#extra_store_postalcodes_<?php echo $store_id ;?>").css("display", "none");
    jQuery("#extra_store_postalcodes_<?php echo $store_id ;?>").prev().css("display", "none");


  }
});
});
 var selectedShape_<?php echo $store_id ;?>;
 var drawingManager_<?php echo $store_id ;?>;
 var map_<?php echo $store_id ;?>;
 var all_overlays_<?php echo $store_id ;?> = [];

 var polygon_json_string_<?php echo $store_id ;?> = '<?php  if(woofood_isvalidjson($store_polygon_area)) {echo  $store_polygon_area; } ?>';


 var alreadypolygon_<?php echo $store_id ;?>;

 if(polygon_json_string_<?php echo $store_id ;?>)
 {
   var polygons_exists_<?php echo $store_id ;?> = JSON.parse(polygon_json_string_<?php echo $store_id ;?>);
 console.log(polygons_exists_<?php echo $store_id ;?>);
 }
 

 


 function initMap_<?php echo $store_id ;?>() {
   

  
  

  var map_<?php echo $store_id ;?> = new google.maps.Map(document.getElementById('map_<?php echo $store_id ;?>'), {
    center: {
      lat: <?php echo $store_lat; ?>,
      lng: <?php echo $store_lng; ?>
    },

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

 

  if(Array.isArray(polygons_exists_<?php echo $store_id ;?>))
  {
   alreadypolygon_<?php echo $store_id ;?> = new google.maps.Polygon({
    paths: polygons_exists_<?php echo $store_id ;?>,
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.35,
      editable: true
  });
  alreadypolygon_<?php echo $store_id ;?>.setMap(map_<?php echo $store_id ;?>);
map_<?php echo $store_id ;?>.fitBounds(alreadypolygon_<?php echo $store_id ;?>.getBounds());
    overlayClickListener_<?php echo $store_id ;?>(alreadypolygon_<?php echo $store_id ;?>);


      google.maps.event.addListener(alreadypolygon_<?php echo $store_id ;?>, 'click', function() {
        setSelection_<?php echo $store_id ;?>(alreadypolygon_<?php echo $store_id ;?>);
      });
      setSelection_<?php echo $store_id ;?>(alreadypolygon_<?php echo $store_id ;?>);

  }


  drawingManager_<?php echo $store_id ;?> = new google.maps.drawing.DrawingManager({
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
  drawingManager_<?php echo $store_id ;?>.setMap(map_<?php echo $store_id ;?>);


    




  


  
  jQuery('#enablePolygon').click(function() {
    drawingManager_<?php echo $store_id ;?>.setMap(map_<?php echo $store_id ;?>);
    drawingManager_<?php echo $store_id ;?>.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
  });

  jQuery('#clearPolygon_<?php echo $store_id ;?>').click(function() {
    if (selectedShape_<?php echo $store_id ;?>) {
      selectedShape_<?php echo $store_id ;?>.setMap(null);
    }
    if(alreadypolygon_<?php echo $store_id ;?>)
    {      alreadypolygon_<?php echo $store_id ;?>.setMap(null);


    }
  });

  jQuery('#cleapMap_<?php echo $store_id ;?>').click(function() {
    if (selectedShape_<?php echo $store_id ;?>) {
      selectedShape_<?php echo $store_id ;?>.setMap(null);
    }
    drawingManager_<?php echo $store_id ;?>.setMap(null);
    jQuery('#showonPolygon').hide();
    jQuery('#resetPolygon').hide();
  });
  google.maps.event.addListener(drawingManager_<?php echo $store_id ;?>, 'polygoncomplete', function(polygon) {
    all_overlays_<?php echo $store_id ;?>.push(polygon);

     if (selectedShape_<?php echo $store_id ;?>) {
      selectedShape_<?php echo $store_id ;?>.setMap(null);
    }
    if(alreadypolygon_<?php echo $store_id ;?>)
    {      alreadypolygon_<?php echo $store_id ;?>.setMap(null);

      
    }

    overlayClickListener_<?php echo $store_id ;?>(polygon);


      google.maps.event.addListener(polygon, 'click', function() {
        setSelection_<?php echo $store_id ;?>(polygon);
      });
      setSelection_<?php echo $store_id ;?>(polygon);
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

     jQuery('#extra_store_polygon_area_<?php echo $store_id ;?>').val(JSON.stringify(bounds));


  });



function overlayClickListener_<?php echo $store_id ;?>(overlay) {
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
          jQuery('#extra_store_polygon_area_<?php echo $store_id ;?>').val(JSON.stringify(bounds));

    });
}
  function clearSelection_<?php echo $store_id ;?>() {
    if (selectedShape_<?php echo $store_id ;?>) {
      selectedShape_<?php echo $store_id ;?>.setEditable(false);
      selectedShape_<?php echo $store_id ;?> = null;
    }
  }


  function setSelection_<?php echo $store_id ;?>(shape) {
    clearSelection_<?php echo $store_id ;?>();
    selectedShape_<?php echo $store_id ;?> = shape;
    shape.setEditable(true);
  }





}

    </script>
     <?php

}

  function woofood_isvalidjson($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}
 function woofood_multistore_list_stores()
 {
  $args = array(
      'posts_per_page' => - 1,
      'orderby'        => 'title',
      'order'          => 'asc',
      'post_type'      => 'extra_store',
      'post_status'    => 'publish'                 
      );
    $stores = get_posts( $args );

    $args = array(
      'role'    => 'multistore_user',
      'orderby' => 'user_nicename',
      'order'   => 'ASC'
      );
    $users = get_users( $args );

    foreach ($stores as $store) 
    {

               
woofood_multistore_item($store, $users);


    }
     
 }

 function woofood_multistore_day_fields($day, $day_start, $day_end, $day_start2, $day_end2, $day_start3, $day_end3)
 {?>
  <div class="woofood-multistore-delivery-hours-inner">
                  <h3><?php echo $day;?></h3>

                <table class="form-table" role="presentation">
                  <tbody>
                    <tr>
                      <th scope="row"><?php esc_html_e("from", "woofood-multistore-plugin"); ?></th>
                      <td>
                        <input type="text" id="woofood_delivery_hours_<?php echo strtolower($day); ?>_start" name="woofood_delivery_hours_<?php echo strtolower($day); ?>_start" value="<?php echo $day_start; ?>" class="ui-timepicker-input" autocomplete="off"></td>
                      </tr>
                      <tr>
                        <th scope="row"><?php esc_html_e("to", "woofood-multistore-plugin"); ?></th>
                        <td>
                          <input type="text" id="woofood_delivery_hours_<?php echo strtolower($day); ?>_end" name="woofood_delivery_hours_<?php echo strtolower($day); ?>_end" value="<?php echo $day_end; ?>" class="ui-timepicker-input" autocomplete="off">
                        </td>
                      </tr>
                      <tr>
                        <th scope="row"><?php esc_html_e("from", "woofood-multistore-plugin"); ?></th>
                        <td>
                          <input type="text" id="woofood_delivery_hours_<?php echo strtolower($day); ?>_start2" name="woofood_delivery_hours_<?php echo strtolower($day); ?>_start2" value="<?php echo $day_start2; ?>" class="ui-timepicker-input" autocomplete="off">
                        </td>
                      </tr>
                      <tr>
                        <th scope="row"><?php esc_html_e("to", "woofood-multistore-plugin"); ?></th>
                        <td>
                          <input type="text" id="woofood_delivery_hours_<?php echo strtolower($day); ?>_end2" name="woofood_delivery_hours_<?php echo strtolower($day); ?>_end2" value="<?php echo $day_end2; ?>" class="ui-timepicker-input" autocomplete="off">
                        </td>
                      </tr>
                      <tr>
                        <th scope="row"><?php esc_html_e("from", "woofood-multistore-plugin"); ?></th>
                        <td>
                          <input type="text" id="woofood_delivery_hours_<?php echo strtolower($day); ?>_start3" name="woofood_delivery_hours_<?php echo strtolower($day); ?>_start3" value="<?php echo $day_start3; ?>" class="ui-timepicker-input" autocomplete="off">
                        </td>
                      </tr>
                      <tr>
                        <th scope="row"><?php esc_html_e("to", "woofood-multistore-plugin"); ?></th>
                        <td>
                          <input type="text" id="woofood_delivery_hours_<?php echo strtolower($day); ?>_end3" name="woofood_delivery_hours_<?php echo strtolower($day); ?>_end3" value="<?php echo $day_end3; ?>" class="ui-timepicker-input" autocomplete="off">
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

  <?php

 }

  function woofood_multistore_day_fields_pickup($day, $day_start, $day_end, $day_start2, $day_end2, $day_start3, $day_end3)
 {?>
  <div class="woofood-multistore-pickup-hours-inner">
                  <h3><?php echo $day;?></h3>

                <table class="form-table" role="presentation">
                  <tbody>
                    <tr>
                      <th scope="row"><?php esc_html_e("from", "woofood-multistore-plugin"); ?></th>
                      <td>
                        <input type="text" id="woofood_pickup_hours_<?php echo strtolower($day); ?>_start" name="woofood_pickup_hours_<?php echo strtolower($day); ?>_start" value="<?php echo $day_start; ?>" class="ui-timepicker-input" autocomplete="off"></td>
                      </tr>
                      <tr>
                        <th scope="row"><?php esc_html_e("to", "woofood-multistore-plugin"); ?></th>
                        <td>
                          <input type="text" id="woofood_pickup_hours_<?php echo strtolower($day); ?>_end" name="woofood_pickup_hours_<?php echo strtolower($day); ?>_end" value="<?php echo $day_end; ?>" class="ui-timepicker-input" autocomplete="off">
                        </td>
                      </tr>
                      <tr>
                        <th scope="row"><?php esc_html_e("from", "woofood-multistore-plugin"); ?></th>
                        <td>
                          <input type="text" id="woofood_pickup_hours_<?php echo strtolower($day); ?>_start2" name="woofood_pickup_hours_<?php echo strtolower($day); ?>_start2" value="<?php echo $day_start2; ?>" class="ui-timepicker-input" autocomplete="off">
                        </td>
                      </tr>
                      <tr>
                        <th scope="row"><?php esc_html_e("to", "woofood-multistore-plugin"); ?></th>
                        <td>
                          <input type="text" id="woofood_pickup_hours_<?php echo strtolower($day); ?>_end2" name="woofood_pickup_hours_<?php echo strtolower($day); ?>_end2" value="<?php echo $day_end2; ?>" class="ui-timepicker-input" autocomplete="off">
                        </td>
                      </tr>
                      <tr>
                        <th scope="row"><?php esc_html_e("from", "woofood-multistore-plugin"); ?></th>
                        <td>
                          <input type="text" id="woofood_pickup_hours_<?php echo strtolower($day); ?>_start3" name="woofood_pickup_hours_<?php echo strtolower($day); ?>_start3" value="<?php echo $day_start3; ?>" class="ui-timepicker-input" autocomplete="off">
                        </td>
                      </tr>
                      <tr>
                        <th scope="row"><?php esc_html_e("to", "woofood-multistore-plugin"); ?></th>
                        <td>
                          <input type="text" id="woofood_pickup_hours_<?php echo strtolower($day); ?>_end3" name="woofood_pickup_hours_<?php echo strtolower($day); ?>_end3" value="<?php echo $day_end3; ?>" class="ui-timepicker-input" autocomplete="off">
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

  <?php

 }


 function woofood_get_pickup_stores()
 {
  $pickup_stores = array();
   $args2 = array(
      'posts_per_page' => - 1,
      'orderby'        => 'title',
      'order'          => 'asc',
      'post_type'      => 'extra_store',
      'post_status'    => 'publish',
      'meta_query' => array(
        array(
          'key' => 'extra_store_enabled'
          ),
         array(
          'key' => 'order_type_pickup'
          )
        )          
      );
    $extra_stores2 = get_posts( $args2 );

    foreach ($extra_stores2 as $extra_store)
    {
      $pickup_stores[$extra_store->ID] = $extra_store->post_title;

    }
    return $pickup_stores;
 }


  function woofood_get_delivery_stores()
 {
  $pickup_stores = array();
   $args2 = array(
      'posts_per_page' => - 1,
      'orderby'        => 'title',
      'order'          => 'asc',
      'post_type'      => 'extra_store',
      'post_status'    => 'publish',
      'meta_query' => array(
        array(
          'key' => 'extra_store_enabled'
          ),
         array(
          'key' => 'order_type_delivery'
          )
        )          
      );
    $extra_stores2 = get_posts( $args2 );

    foreach ($extra_stores2 as $extra_store)
    {
      $pickup_stores[$extra_store->ID] = $extra_store->post_title;

    }
    return $pickup_stores;
 }

}//if woocommerce is enabled