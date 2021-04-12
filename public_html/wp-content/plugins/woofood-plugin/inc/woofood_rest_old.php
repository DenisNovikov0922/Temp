<?php
function my_custom_product_api_response( $product ) {
    $id = $product['id'];
  $_product = wc_get_product( $id );

   /* start tweaking the woocommerce api//*/


 $terms = get_the_terms( $product['id'], 'product_cat' );
    $extra_option_categories = get_terms('extra_option_categories' ,  array('hide_empty' => false));

    //check if the product is variable and get selected extra options selected on variable//

    $all_selected_extra_option_categories = array();

    if (  $_product->is_type( 'variable' ) ) {

      $variable_product = new WC_Product_Variable( $id);
      $variations = $variable_product->get_available_variations();

      //foreach variation //
      foreach($variations as $current_variation)

      {


   $all_selected_extra_option_categories[] = $current_variation['variation_custom_select'];


      }


      //make multidimensional to array to simple array and remove duplicates//
if (is_array($all_selected_extra_option_categories))
{
    $all_selected_extra_option_categories = array_unique(array_reduce($all_selected_extra_option_categories, 'array_merge', array()));


}

else {

  
}


   


    }
    //check if the product is variable and get selected extra options selected on variable//

    // if the product is simple
    if ( $_product->is_type('simple'))
    {
       $all_selected_extra_option_categories = get_post_meta( $id, 'extra_options_select', true ); 



    }

    //end if product is simple//

    

  //if user have selected custom options per product use them instead of category//
if (!empty($all_selected_extra_option_categories))

{


  $woofood_api_export_all_extra_options = array();






     
   foreach($all_selected_extra_option_categories as $current_extra_option_category) {   
   

    //get category extra option name by id//
   $current_extra_option_category_object = get_term_by( 'id', absint( $current_extra_option_category ), 'extra_option_categories' );
  $current_extra_option_category_name = $current_extra_option_category_object->name;  

    //add category name to the array//


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
    if (!empty($all_extra_options)){
   
   
        foreach ($all_extra_options as $current_extra_option){
        
       

            $current_extra_option_title = $current_extra_option->post_title;
            $current_extra_option_price = get_post_meta( $current_extra_option->ID, 'extra_option_price', true );
            $current_extra_option_id = $current_extra_option->ID;

               $woofood_api_export_all_extra_options[$current_extra_option_category_name][$current_extra_option_id]['title'] = $current_extra_option_title;
              $woofood_api_export_all_extra_options[$current_extra_option_category_name][$current_extra_option_id]['price'] = $current_extra_option_price;
              $woofood_api_export_all_extra_options[$current_extra_option_category_name][$current_extra_option_id]['id'] = $current_extra_option_id;

           $current_extra_option_array = array('title'=>$current_extra_option_title,'price'=> $current_extra_option_price, 'id'=>$current_extra_option_id  );       
             







            } //end foreach
  

        }// end if not empty

}//end foreach extra option category





    $product['woofood_extra_options_export'] =$woofood_api_export_all_extra_options;

}//end if 



  else {
     foreach($extra_option_categories as $current_extra_option_category) {      

  $args = array(
  'numberposts' => -1,
  'post_type'   => 'extra_option',
  'suppress_filters' => false,
  'tax_query' => array(
    'relation' => 'AND',
    array(
        'taxonomy' => 'product_cat',
        'field'    => 'term_id',
        'terms'    => $terms[0]->term_id,
    ),
    array(
        'taxonomy' => 'extra_option_categories',
        'field'    => 'term_id',
        'terms'    => $current_extra_option_category->term_id,
    ),
),
 
);
  
$all_extra_options = get_posts( $args );
    if (!empty($all_extra_options)){
   
   
        foreach ($all_extra_options as $current_extra_option){
      

            $current_extra_option_title = $current_extra_option->post_title;
            $current_extra_option_price = get_post_meta( $current_extra_option->ID, 'extra_option_price', true );
            $current_extra_option_id = $current_extra_option->ID;

           $current_extra_option_array = array('title'=>$current_extra_option_title,'price'=> $current_extra_option_price, 'id'=>$current_extra_option_id  );       
                

            } //end foreach
   

        }// end if not empty

}//end foreach extra option category

}//end else 











    // Adding your custom field:
    $product['woofood_extra_option_categories_ids'] = get_post_meta( $id, 'extra_options_select', true );
    return $product;
}

add_filter( 'woocommerce_api_product_response', 'my_custom_product_api_response' );






add_action( 'rest_api_init', 'register_extra_addresses_export' );

function register_extra_addresses_export() {
    register_rest_field( 'customer',
        'previously_stored_addresses',
        array(
            'get_callback'    => "previously_stored_addresses_export",
            'update_callback' => null,
           'schema' => null,
        )
    );
}
function previously_stored_addresses_export( $object, $field_name, $request ) {
  global $woocommerce;
    $customer_id = $object[ 'id' ];
    // $_product = wc_get_product( $id );
    $previously_stored_addresses = get_user_meta($customer_id, 'previously_stored_addresses', true);  

    $object = $previously_stored_addresses;

    return $object;

   }



add_action( 'rest_api_init', 'register_extra_options_export' );
function register_extra_options_export() {
    register_rest_field( 'product',
        'woofood_extra_options',
        array(
            'get_callback'    => "woofood_extra_options_export",
            'update_callback' => null,
           'schema' => null,
        )
    );
}
function woofood_extra_options_export( $object, $field_name, $request ) {
  global $woocommerce;
  global $post;
  global $product;
    $id = $object[ 'id' ];
     $_product = wc_get_product( $id );

   /* start tweaking the woocommerce api//*/


 $terms = get_the_terms( $id, 'product_cat' );
    $extra_option_categories = get_terms('extra_option_categories' ,  array('hide_empty' => false));

    //check if the product is variable and get selected extra options selected on variable//

    $all_selected_extra_option_categories = array();

    if (  $_product->is_type( 'variable' ) ) {

      $variable_product = new WC_Product_Variable( $id);
      $variations = $variable_product->get_available_variations();

      //foreach variation //
      foreach($variations as $current_variation)

      {


   $all_selected_extra_option_categories[] = array($current_variation["variation_id"] => $current_variation['variation_custom_select']);


      }


if (is_array($all_selected_extra_option_categories))
{
   
}

else {

  
}


   
//return $all_selected_extra_option_categories;

    }
    //check if the product is variable and get selected extra options selected on variable//

    // if the product is simple
    if ( $_product->is_type('simple'))
    {
       $all_selected_extra_option_categories = get_post_meta( $id, 'extra_options_select', true ); 



    }

    //end if product is simple//

    

  //if user have selected custom options per product use them instead of category//
if (!empty($all_selected_extra_option_categories))

{


  $woofood_api_export_all_extra_options = array();


    if ($_product->is_type("variable"))
    {

      foreach($all_selected_extra_option_categories as  $current_variations_extra) {   
   

   foreach($current_variations_extra as  $current_variation_id => $current_variation_extra_categories) {
    foreach($current_variation_extra_categories as $current_extra_option_category){

    //get category extra option name by id//
   $current_extra_option_category_object = get_term_by( 'id', absint( $current_extra_option_category ), 'extra_option_categories' );
  $current_extra_option_category_name = $current_extra_option_category_object->name;  

    //add category name to the array//


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
    if (!empty($all_extra_options)){
   
   
        foreach ($all_extra_options as $current_extra_option){
        
       

            $current_extra_option_title = $current_extra_option->post_title;
            $current_extra_option_price = get_post_meta( $current_extra_option->ID, 'extra_option_price', true );
            $current_extra_option_id = $current_extra_option->ID;

           


            $woofood_api_export_all_extra_options[] = array("id" =>$current_extra_option_id, "variation_id"=>$current_variation_id, "title"=>$current_extra_option_title, "price"=>$current_extra_option_price, "extra_category_name"=>$current_extra_option_category_name);


           $current_extra_option_array = array('title'=>$current_extra_option_title,'price'=> $current_extra_option_price, 'id'=>$current_extra_option_id  );       
             






            } //end foreach
  

        }// end if not empty

      }//end for each extra option category

      }//end for each extra option categories

}//end foreach variation extra option category


    }//end if variable


    if($_product->is_type("simple"))
    {


    foreach($all_selected_extra_option_categories as $current_extra_option_category){

    //get category extra option name by id//
   $current_extra_option_category_object = get_term_by( 'id', absint( $current_extra_option_category ), 'extra_option_categories' );
  $current_extra_option_category_name = $current_extra_option_category_object->name;  

    //add category name to the array//


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
    if (!empty($all_extra_options)){
   
   
        foreach ($all_extra_options as $current_extra_option){
        
       

            $current_extra_option_title = $current_extra_option->post_title;
            $current_extra_option_price = get_post_meta( $current_extra_option->ID, 'extra_option_price', true );
            $current_extra_option_id = $current_extra_option->ID;

            


            $woofood_api_export_all_extra_options[] = array("id" =>$current_extra_option_id, "variation_id"=>0, "title"=>$current_extra_option_title, "price"=>$current_extra_option_price, "extra_category_name"=>$current_extra_option_category_name);


           $current_extra_option_array = array('title'=>$current_extra_option_title,'price'=> $current_extra_option_price, 'id'=>$current_extra_option_id  );       
             






            } //end foreach
  

        }// end if not empty

      }//end for each extra option category







    }//end if is simple


   

//end new 



$final_export_array = array();
$final_export_array['id'] = 0;
$final_export_array['key']= 'woofood_extra_options_export';
$final_export_array['value'] = $woofood_api_export_all_extra_options;



$final_export_array_topic = array();
$final_export_array_topic['key']= 'woofood_extra_options_export_topic';
$final_export_array_topic['value'] = array("cat1"=>"value1", "cat2"=>"value2");

$object = $woofood_api_export_all_extra_options;

return $object;

}//end if 

}
function convert_multi_array($array) {
  $out = implode("&",array_map(function($a) {return implode("~",$a);},$array));
  print_r($out);
}

?>