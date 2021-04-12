<?php
//Add rest api support  to  extra options//
 add_action( 'init', 'woofood_extra_rest_support', 25 );
  function woofood_extra_rest_support() {
    global $wp_post_types;
  
    //be sure to set this to the name of your post type!
    $post_type_name = 'extra_option';
    if( isset( $wp_post_types[ $post_type_name ] ) ) {
      $wp_post_types[$post_type_name]->show_in_rest = is_user_logged_in();
      $wp_post_types[$post_type_name]->rest_base = "woofood-extra";
      $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
    }
  
  }

 //Add rest api support  to  extra options//








 //Add Extra Option Price Field to Rest Api//


add_action( 'rest_api_init', 'register_extra_option_price_export' ,99);
function register_extra_option_price_export() {
    register_rest_field( 'extra_option',
        'extra_option_price',
        array(
            'get_callback'    => "register_extra_option_price_callback",
            'update_callback' => "update_extra_option_price_callback",
           'schema' => array(
                'description' => 'Extra Option Price',
                'type' => 'string',
                'context' => array('view', 'edit')
            )
        )
    );
}


function register_extra_option_price_callback( $object, $field_name, $request ) {
    $extra_option_id = $object[ 'id' ];
    // $_product = wc_get_product( $id );
    $extra_option_price = get_post_meta($extra_option_id, 'extra_option_price', true);  

    $object = $extra_option_price;

    return $object;

   }

   function update_extra_option_price_callback( $value, $post, $field_name) {
  
 
    return update_post_meta($post->ID ,$field_name, $value); 

   }

  //Add Extra Option Price Field to Rest Api//







?>