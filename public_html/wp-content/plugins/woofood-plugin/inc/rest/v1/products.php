<?php


add_action( 'rest_api_init', 'register_rest_woofood_products');
function register_rest_woofood_products() {



  register_rest_route( 'woofood/v1', 'products', 


    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'woofood_api_get_products_by_category',


      )      
    );


 register_rest_route( 'woofood/v1', 'products/edit', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_edit_product',

      )
    );




}




function woofood_api_edit_product( $request ) {
    ob_clean();
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();
// Get username and password from the submitted headers.
  if ( array_key_exists( 'Username', $headers ) && array_key_exists( 'Password', $headers ) ) {
    $creds['user_login'] = $headers["Username"];
    $creds['user_password'] =  $headers["Password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.
$extra_option_id = 0;
// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {
  echo $user->get_error_message();
  return $user;
}

wp_set_current_user( $user->ID, $user->user_login );

// A subscriber has 'read' access so a very basic user account can be used.
if ( ! current_user_can( "manage_woocommerce" ) ) {
  return new WP_Error( 'rest_forbidden', 'You do not have permissions to view this data.', array( 'status' => 401 ) );
}





//if we set the id then we have to update the option and not create it//
if( isset( $request[ 'product_id' ] ) && isset( $request[ 'is_enabled' ] )  ) {

$product_id = intval($request[ 'product_id' ]);
$is_enabled = $request[ 'is_enabled' ];

if($is_enabled =="true")
{
      update_post_meta( $product_id, 'woofood_product_availability', false );

}
else
{
        update_post_meta( $product_id, 'woofood_product_availability', true );


}
$new_status = null;
if(get_post_meta($product_id, 'woofood_product_availability', true ))
{
$new_status = false;
}
else
{
  $new_status = true;

}
$all_transients = get_transient('woofood_all_transient_keys');

if(is_array($all_transients))
{
  foreach($all_transients as $transient)
  {
                delete_transient($transient);


  }
}


          $export_array = array("success" =>true, "status"=> $new_status);
            return rest_ensure_response($export_array);


}
else
{
  return new WP_Error( 'invalid-method', 'You must specify a valid product_id and and is_enabled.', array( 'status' => 400 /* Bad Request */ ) );

}

//if we set id








}
else {
  return rest_ensure_response($headers);
  return new WP_Error( 'invalid-method', 'You must specify a valid username and password.', array( 'status' => 400 /* Bad Request */ ) );
}


}

//get all orders//
function woofood_api_get_products( $request ) {
  global $woocommerce;

  $currency_symbol = html_entity_decode(get_woocommerce_currency_symbol()) ;


  $args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,

    );
  $products_query = new WP_Query( $args );
  $products_ids = $products_query->posts;
  $products_new_array =array();
  foreach($products_ids as $product)
  {
//$products_new_array[$product->ID]['name']= $product->post_title;
    $product_array = array();
    $product_array['id']= $product->ID;
$product_normal = wc_get_product($product->ID ); // Assuming the ID is 12.
$product_array['name']= $product->post_title;
$product_array['price']= get_post_meta($product->ID, '_price', true);
$product_array['price_html'] = wc_price($product_array['price']);

$images_array = wp_get_attachment_image_src( get_post_thumbnail_id( $product->ID ), 'single-post-thumbnail' );
$product_array['image_url']= $images_array[0];


if($product_normal->is_type('variable'))
{
  $all_variations = $product_normal->get_available_variations();
  $all_attributes = array();
  $attribute_values = array();



  foreach($all_variations as $current_variation)
  {
    $variations_extra_options =array();
    $variation = wc_get_product($current_variation['variation_id']);

    $variation->get_formatted_name();
    $current_variation_data = array();
    $current_variation_data['variation_id'] = $current_variation['variation_id'];
    $current_variation_data['name'] = $variation->get_formatted_name();
    $current_variation_data['price'] = $current_variation['display_price'];
    $current_variation_data['price_html'] = wc_price($current_variation['display_price']);
    $extra_option_categories = $current_variation['variation_custom_select'];
    $current_variation_data['extra_option_categories'] = $extra_option_categories;
    if(!empty($extra_option_categories))
    {
      foreach($extra_option_categories as $current_extra_option_category)
      {       
        if($current_extra_option_category!="no")




        {
          $current_extra_option_category_object = get_term_by( 'id', absint( $current_extra_option_category ), 'extra_option_categories' );
          $current_extra_option_category_name = $current_extra_option_category_object->name;  
          $term_meta = get_option( "taxonomy_".$current_extra_option_category);
          $category_type = $term_meta["category_type"];

          $maximum_options = (int)$term_meta["maximum_options"];
          if($maximum_options<1)
          {
            $maximum_options = 9999;

          }
          $options = array();

          $args = array(
            'numberposts' => -1,
            'post_type'   => 'extra_option',
            'suppress_filters' => false,
            'tax_query' => array(
              'relation' => 'AND',

              array(
                'taxonomy' => 'extra_option_categories',
                'field'    => 'term_id',
                'terms'    => $current_extra_option_category,
                ),
              ),

            );

          $all_extra_options = get_posts( $args );
          foreach($all_extra_options as $current_extra)
          {

            $current_extra_option_visible_as = get_post_meta( $current_extra->ID, 'extra_option_visible_as', true );
            if (!empty($current_extra_option_visible_as))
            {
              $current_extra_option_title = $current_extra_option_visible_as;

            }
            else {
              $current_extra_option_title = $current_extra->post_title;


            }

            $current_extra_option_price = floatval(get_post_meta( $current_extra->ID, 'extra_option_price', true ));
            $current_extra_option_id = $current_extra->ID;


            $options[] = array('id'=>$current_extra_option_id, 'name'=>$current_extra_option_title, 'price'=> $current_extra_option_price);

          }



          $variations_extra_options = array('type'=>$category_type, 'maximum_options'=>$maximum_options, 'name'=>$current_extra_option_category_name, 'options' =>$options );



        }



        $current_variation_data['extra_options'][] = $variations_extra_options;

      }

    }



// return rest_ensure_response( $current_variation );
    foreach($current_variation['attributes'] as $attribute_key =>$attribute_value)
    {           

      $attr_label ="";
      $attribute_term_label ="";
      $attribute_term =  get_term_by( 'slug', $attribute_value, str_replace("attribute_", "", $attribute_key) );


      if($attribute_term)
      {
        $attribute_term_label = $attribute_term->name;
        $tax_object = get_taxonomy(str_replace("attribute_", "", $attribute_key));
        $attr_label =  $tax_object->label;
        if ( 0 === strpos( $attr_label, 'Product ' ) ) 
        {
          $attr_label = substr( $attr_label, 8 );
        } 

      }
      else
      {
        $attr_label = $attribute_key;
        $attribute_term_label = $attribute_value;
        $attr_label = wc_attribute_label(str_replace("attribute_", "pa_", $attribute_key));


      }



      $current_variation_data['attributes'][] = array('name'=>   $attr_label, 'option'=>$attribute_term_label);




}//end for each attribute in variation//










$product_array['variations'][]= $current_variation_data;



}  //end for eaach variation //


$attributes = $product_normal->get_attributes();
foreach ( $attributes as $attribute ) {
  if($attribute->get_variation()) {
    $tax_label="";
    $name = $attribute->get_name();

    if ( $attribute->is_taxonomy() ) {

      $terms = wp_get_post_terms( $product_normal->get_id(), $name, 'all' );
// get the taxonomy
      $tax = $terms[0]->taxonomy;
// get the tax object
      $tax_object = get_taxonomy($tax);
// get tax label
      if ( isset ( $tax_object->labels->singular_name ) ) {
        $tax_label = $tax_object->labels->singular_name;
      } elseif ( isset( $tax_object->label ) ) {
        $tax_label = $tax_object->label;
// Trim label prefix since WC 3.0
        if ( 0 === strpos( $tax_label, 'Product ' ) ) {
          $tax_label = substr( $tax_label, 8 );
        }                
      }



      $tax_terms = array();
      foreach ( $terms as $term ) {
        $single_term = esc_html( $term->name );
// Insert extra code here if you want to show terms as links.
        $tax_terms [] = array('name'=> $single_term );
      }



      $all_attributes[] = array('name'=>$tax_label, 'options'=>$tax_terms); 

    }
    else
    {


      $tax_label =  $name;
      $attribute_options = $attribute->get_options();
      $attr_terms = array();
      foreach ( $attribute_options as $attribute_option ) {

        $attr_terms [] = array('name'=> $attribute_option );
      }
//return rest_ensure_response( $attr_terms);
      $all_attributes[] = array('name'=>$tax_label, 'options'=>$attr_terms); 

    }


}//end if attribute is variation//

}//end for each





$product_array['attributes']= $all_attributes;


}


$product_array['currency_symbol'] = $currency_symbol;
$product_array["is_enabled"] = true;

$products_new_array[] = $product_array;


}





return rest_ensure_response( $products_new_array );












}
//get all products//




//get all orders//
function woofood_api_get_products_by_category( $request ) {
  global $woocommerce;
  $total_export = array();
  $currency_symbol = html_entity_decode(get_woocommerce_currency_symbol()) ;
  $style="accordion";

  $taxonomy     = 'product_cat';
  $orderby      = 'name';  
$show_count   = 0;      // 1 for yes, 0 for no
$pad_counts   = 0;      // 1 for yes, 0 for no
$hierarchical = 1;      // 1 for yes, 0 for no  
$title        = '';  
$empty        = 1;


$args = array(
  'taxonomy'     => $taxonomy,
  'orderby'      => $orderby,
  'show_count'   => $show_count,
  'pad_counts'   => $pad_counts,
  'hierarchical' => $hierarchical,
  'title_li'     => $title,
  'hide_empty'   => $empty
  );


$all_categories = get_categories( $args );

foreach($all_categories as $current_category)
{


  $args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'product_cat' => $current_category->slug,


    );
  $products_query = new WP_Query( $args );
  $products_ids = $products_query->posts;
  $products_new_array =array();
  foreach($products_ids as $product)
  {
//$products_new_array[$product->ID]['name']= $product->post_title;
    $product_array = array();
    $product_array['id']= $product->ID;
$product_normal = wc_get_product($product->ID ); // Assuming the ID is 12.
$product_array['name']= $product->post_title;
$product_array['price']= get_post_meta($product->ID, '_price', true);
$product_array['price_html'] = wc_price($product_array['price']);

$images_array = wp_get_attachment_image_src( get_post_thumbnail_id( $product->ID ), 'single-post-thumbnail' );
$product_array['image_url']= $images_array[0];


if($product_normal->is_type('variable'))
{
  $all_variations = $product_normal->get_available_variations();
  $all_attributes = array();
  $attribute_values = array();



  foreach($all_variations as $current_variation)
  {
    $variations_extra_options =array();
    $variation = wc_get_product($current_variation['variation_id']);

    $variation->get_formatted_name();
    $current_variation_data = array();
    $current_variation_data['variation_id'] = $current_variation['variation_id'];
    $current_variation_data['name'] = $variation->get_formatted_name();
    $current_variation_data['price'] = $current_variation['display_price'];
    $current_variation_data['price_html'] = wc_price($current_variation['display_price']);
    $extra_option_categories = $current_variation['variation_custom_select'];
    $current_variation_data['extra_option_categories'] = $extra_option_categories;
    if(!empty($extra_option_categories))
    {
      foreach($extra_option_categories as $current_extra_option_category)
      {       
        if($current_extra_option_category!="no")




        {
          $current_extra_option_category_object = get_term_by( 'id', absint( $current_extra_option_category ), 'extra_option_categories' );
          $current_extra_option_category_name = $current_extra_option_category_object->name;  
          $term_meta = get_option( "taxonomy_".$current_extra_option_category);
          $category_type = $term_meta["category_type"];

          $maximum_options = (int)$term_meta["maximum_options"];
          if($maximum_options<1)
          {
            $maximum_options = 9999;

          }
          $options = array();

          $args = array(
            'numberposts' => -1,
            'post_type'   => 'extra_option',
            'suppress_filters' => false,
            'tax_query' => array(
              'relation' => 'AND',

              array(
                'taxonomy' => 'extra_option_categories',
                'field'    => 'term_id',
                'terms'    => $current_extra_option_category,
                ),
              ),

            );

          $all_extra_options = get_posts( $args );
          foreach($all_extra_options as $current_extra)
          {

            $current_extra_option_visible_as = get_post_meta( $current_extra->ID, 'extra_option_visible_as', true );
            if (!empty($current_extra_option_visible_as))
            {
              $current_extra_option_title = $current_extra_option_visible_as;

            }
            else {
              $current_extra_option_title = $current_extra->post_title;


            }

            $current_extra_option_price = floatval(get_post_meta( $current_extra->ID, 'extra_option_price', true ));
            $current_extra_option_id = $current_extra->ID;


            $options[] = array('id'=>$current_extra_option_id, 'name'=>$current_extra_option_title, 'price'=> $current_extra_option_price);

          }



          $variations_extra_options = array('type'=>$category_type, 'maximum_options'=>$maximum_options, 'name'=>$current_extra_option_category_name, 'options' =>$options );



        }



        $current_variation_data['extra_options'][] = $variations_extra_options;

      }

    }



    foreach($current_variation['attributes'] as $attribute_key =>$attribute_value)
    {           

      $attr_label ="";
      $attribute_term_label ="";
      $attribute_term =  get_term_by( 'slug', $attribute_value, str_replace("attribute_", "", $attribute_key) );


      if($attribute_term)
      {
        $attribute_term_label = $attribute_term->name;
        $tax_object = get_taxonomy(str_replace("attribute_", "", $attribute_key));
        $attr_label =  $tax_object->label;
        if ( 0 === strpos( $attr_label, 'Product ' ) ) 
        {
          $attr_label = substr( $attr_label, 8 );
        } 

      }
      else
      {

        $attr_label = $attribute_key;
        $attribute_term_label = $attribute_value;
        $product_attributes = get_post_meta($product_normal->get_id(), '_product_attributes', true);
        $attr_label = $product_attributes[str_replace("attribute_", "", $attribute_key)]["name"]; 
      }



      $current_variation_data['attributes'][] = array('name'=>   $attr_label, 'option'=>$attribute_term_label);




}//end for each attribute in variation//










$product_array['variations'][]= $current_variation_data;



}  //end for eaach variation //



$attributes = $product_normal->get_attributes();
foreach ( $attributes as $attribute ) {
  if($attribute->get_variation()) {
    $tax_label="";
    $name = $attribute->get_name();

    if ( $attribute->is_taxonomy() ) {

      $terms = wp_get_post_terms( $product_normal->get_id(), $name, 'all' );
// get the taxonomy
      $tax = $terms[0]->taxonomy;
// get the tax object
      $tax_object = get_taxonomy($tax);
// get tax label
      if ( isset ( $tax_object->labels->singular_name ) ) {
        $tax_label = $tax_object->labels->singular_name;
      } elseif ( isset( $tax_object->label ) ) {
        $tax_label = $tax_object->label;
// Trim label prefix since WC 3.0
        if ( 0 === strpos( $tax_label, 'Product ' ) ) {
          $tax_label = substr( $tax_label, 8 );
        }                
      }



      $tax_terms = array();
      foreach ( $terms as $term ) {
        $single_term = esc_html( $term->name );
// Insert extra code here if you want to show terms as links.
        $tax_terms [] = array('name'=> $single_term );
      }



      $all_attributes[] = array('name'=>$tax_label, 'options'=>$tax_terms); 

    }
    else
    {


      $tax_label =  $name;
      $attribute_options = $attribute->get_options();
      $attr_terms = array();
      foreach ( $attribute_options as $attribute_option ) {

        $attr_terms [] = array('name'=> $attribute_option );
      }
//return rest_ensure_response( $attr_terms);
      $all_attributes[] = array('name'=>$tax_label, 'options'=>$attr_terms); 

    }


}//end if attribute is variation//

}//end for each





$product_array['attributes']= $all_attributes;


}
else if($product_normal->is_type('simple'))
{
  $product_array['extra_option_categories'] = get_post_meta( $product_normal->get_id(), 'extra_options_select', true ); 


  foreach($product_array['extra_option_categories'] as $current_extra_option_category)
  {       
    if($current_extra_option_category!="no")




    {
      $current_extra_option_category_object = get_term_by( 'id', absint( $current_extra_option_category ), 'extra_option_categories' );
      $current_extra_option_category_name = $current_extra_option_category_object->name;  
      $term_meta = get_option( "taxonomy_".$current_extra_option_category);
      $category_type = $term_meta["category_type"];

      $maximum_options = (int)$term_meta["maximum_options"];
      if($maximum_options<1)
      {
        $maximum_options = 9999;

      }
      $options = array();

      $args = array(
        'numberposts' => -1,
        'post_type'   => 'extra_option',
        'suppress_filters' => false,
        'tax_query' => array(
          'relation' => 'AND',

          array(
            'taxonomy' => 'extra_option_categories',
            'field'    => 'term_id',
            'terms'    => $current_extra_option_category,
            ),
          ),

        );

      $all_extra_options = get_posts( $args );
      foreach($all_extra_options as $current_extra)
      {

        $current_extra_option_visible_as = get_post_meta( $current_extra->ID, 'extra_option_visible_as', true );
        if (!empty($current_extra_option_visible_as))
        {
          $current_extra_option_title = $current_extra_option_visible_as;

        }
        else {
          $current_extra_option_title = $current_extra->post_title;


        }

        $current_extra_option_price = floatval(get_post_meta( $current_extra->ID, 'extra_option_price', true ));
        $current_extra_option_id = $current_extra->ID;


        $options[] = array('id'=>$current_extra_option_id, 'name'=>$current_extra_option_title, 'price'=> $current_extra_option_price);

      }



      $variations_extra_options = array('type'=>$category_type, 'maximum_options'=>$maximum_options, 'name'=>$current_extra_option_category_name, 'options' =>$options );



    }



    $product_array['extra_options'][] = $variations_extra_options;

  }


}

$product_array['currency_symbol'] = $currency_symbol;
$product_array["is_enabled"] = $product_normal->is_purchasable();

$products_new_array[] = $product_array;

}
$total_export[] = array("cat_id"=>$current_category->term_id, "name"=>$current_category->name, "products"=>$products_new_array);


} //foreach product category end



$options = array("style"=>"tabs");

$final_export = array("options"=>$options, 'data'=>$total_export);
return rest_ensure_response( $final_export );












}
//get all products//







?>