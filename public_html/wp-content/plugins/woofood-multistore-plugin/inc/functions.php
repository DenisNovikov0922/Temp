<?php

function wf_availability_multi_get_stores_distance($customer_address, $order_type, $store_id = 0)
{
  $delivery_available = false;
  $nearest_store= "";
  $nearest_store_id= 0;

  $all_stores_with_addresses = array();
  $options_woofood = get_option('woofood_options');
  $woofood_google_distance_matrix_api_key = $options_woofood['woofood_google_distance_matrix_api_key'];
  $all_stores_not_available = array();


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


   if($store_id >0)
 {
   $get_enabled_stores = array(get_post($store_id));
 }

  foreach($get_enabled_stores as $current_enabled_store)

  {
    $current_store_address = get_post_meta( $current_enabled_store->ID, 'extra_store_address', true );
    $current_store_max_delivery_distance = get_post_meta( $current_enabled_store->ID, 'extra_store_max_delivery_distance', true );




    $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($current_store_address)."&destinations=".urlencode($customer_address )."&mode=driving&sensor=false&key=".$woofood_google_distance_matrix_api_key;
    $details = htmlspecialchars_decode($details);
    $details = str_replace("&amp;", "&", $details );
    $json = woofood_get_contents($details);

    $details = json_decode($json, TRUE);



    if($order_type!="pickup")
    {


      if ($details['rows'][0]['elements'][0]['distance']['value'] < $current_store_max_delivery_distance *1000)
      {
//We this store can deliver /// 
        $all_stores_with_addresses[$current_enabled_store->ID] = $details['rows'][0]['elements'][0]['distance']['value'];



}//end if


}
else if($order_type=="pickup")
{


//We this store can deliver /// 
  $all_stores_with_addresses[$current_enabled_store->ID] = $details['rows'][0]['elements'][0]['distance']['value'];






}


//No available stores for this location//
else {



}//end else 





}//end foreach store


//if at least one store can deliver
if (!empty($all_stores_with_addresses))
{

  $store_name_array = array_keys($all_stores_with_addresses, min($all_stores_with_addresses));  

  $nearest_store_id = $store_name_array[0];



  $nearest_store =  get_the_title($nearest_store_id);

  $delivery_available  = true;  



}//end if

//none store can deliver...show error notice//
else{

  $delivery_available  = false;  



}

  return array("available_stores"=>$all_stores_with_addresses, "nearest_store_id"=>$nearest_store_id, "nearest_store_name"=> $nearest_store, "availability"=>$delivery_available);

}
function wf_availability_multi_get_stores_polygon($customer_address, $order_type, $store_id = 0)
{
    $delivery_available = false;
  $nearest_store= "";
  $nearest_store_id= 0;

  $all_stores_with_addresses = array();
  $options_woofood = get_option('woofood_options');
  $woofood_google_distance_matrix_api_key = $options_woofood['woofood_google_distance_matrix_api_key'];
  $all_stores_not_available = array();


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

  if($store_id >0)
 {
   $get_enabled_stores = array(get_post($store_id));
 }






              $details = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($customer_address)."&key=".$woofood_google_distance_matrix_api_key."";
              $details = htmlspecialchars_decode($details);
              $details = str_replace("&amp;", "&", $details );
              $json = woofood_get_contents($details);


               
              $details = json_decode($json, TRUE);


              if(!empty($details["error_message"]))
              {
                 /*wc_add_notice( 
                  sprintf( "Google API Error:".$details["error_message"].".Please Correct the configuration for  API Key on your Google Console Account", 
                    $woofood_delivery_hour_start, 
                    $woofood_delivery_hour_end
                    ), 'error' 
                  );*/

                  $nearest_store_name = $details["error_message"];
              }




             elseif ( !empty($details['results'][0]['geometry']['location']["lat"] ) && !empty($details['results'][0]['geometry']['location']["lng"] ))
              {





  foreach($get_enabled_stores as $current_enabled_store)

  {
    $current_store_address = get_post_meta( $current_enabled_store->ID, 'extra_store_address', true );

      $woofood_polygon_area = get_post_meta( $current_enabled_store->ID, 'extra_store_polygon_area', true );










    if($order_type!="pickup")
    {

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
               $points_polygon = count($points_x);  // number vertices - zero-based array

         


                if (!woofood_check_if_is_in_polygon($points_polygon, $points_x, $points_y, $longitude_x, $latitude_y)){


       
              }
              else
              {
                                $all_stores_with_addresses[$current_enabled_store->ID] = 10;        


              }


}
else if($order_type=="pickup")
{


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
               $points_polygon = count($points_x);  // number vertices - zero-based array

         


                if (!woofood_check_if_is_in_polygon($points_polygon, $points_x, $points_y, $longitude_x, $latitude_y)){

                  $all_stores_not_available[$current_enabled_store->ID] = 9999;        

       
              }
              else
              {
                                $all_stores_with_addresses[$current_enabled_store->ID] = 10;        


              }

        $all_stores_with_addresses = array_merge($all_stores_with_addresses,  $all_stores_not_available);





}


//No available stores for this location//
else {



}//end else 





}//end foreach store

}//end if customer address is ok //

 else{

            //we cannot deliver// show message//
                wc_add_notice( 
                  sprintf( esc_html__('Your Address seems to be invalid. Please check your address', 'woofood-plugin') , 
                    $woofood_delivery_hour_start, 
                    $woofood_delivery_hour_end
                    ), 'error' 
                  );


            }//end else






















//if at least one store can deliver
if (!empty($all_stores_with_addresses))
{

  $store_name_array = array_keys($all_stores_with_addresses, min($all_stores_with_addresses));  

  $nearest_store_id = $store_name_array[0];



  $nearest_store =  get_the_title($nearest_store_id);

  $delivery_available  = true;  



}//end if

//none store can deliver...show error notice//
else{

  $delivery_available  = false;  



}

  return array("available_stores"=>$all_stores_with_addresses, "nearest_store_id"=>$nearest_store_id, "nearest_store_name"=> $nearest_store, "availability"=>$delivery_available);

}
function wf_availability_multi_get_stores_postal($postalcode, $order_type, $store_id = 0)
{

  $postalcode = str_replace(" ", "", $postalcode);
  $postalcode = strtoupper($postalcode);

  $delivery_available = false;
  $nearest_store= "";
  $nearest_store_id= 0;

  $all_stores_with_addresses = array();
  $options_woofood = get_option('woofood_options');
  $all_stores_not_available = array();


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


 if($store_id >0)
 {
   $get_enabled_stores = array(get_post($store_id));
 }




  foreach($get_enabled_stores as $current_enabled_store)

  {
    $current_store_address = get_post_meta( $current_enabled_store->ID, 'extra_store_address', true );

      $woofood_postalcodes = get_post_meta( $current_enabled_store->ID, 'extra_store_postalcodes', true );
  $woofood_postalcodes = get_post_meta( $current_enabled_store->ID, 'extra_store_postalcodes', true );
      $woofood_postalcodes = str_replace(" ", "",$woofood_postalcodes );
            $woofood_postalcodes = strtoupper($woofood_postalcodes );

    $postal_codes_array = explode(",",  trim($woofood_postalcodes));


   


$woofood_check_postal_prefixes = apply_filters( 'woofood_check_postal_prefixes', false);


if($order_type!="pickup")
    {

        if(!$woofood_check_postal_prefixes)
        {
              if(in_array(trim($postalcode), $postal_codes_array))
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
            if (strpos($postalcode, $current_postal_code) === 0) {



              
              $all_stores_with_addresses[$current_enabled_store->ID] = 10; 


              
              }             


          }


           


        }



        }

    

      }
      else
      {



                   

        if(!$woofood_check_postal_prefixes)
        {
              if(in_array(trim($postalcode), $postal_codes_array))
        {


           
             $all_stores_with_addresses[$current_enabled_store->ID] = 10;              


        }
        else
        {             $all_stores_not_available[$current_enabled_store->ID] = 99999;              



        }

        }
        else
        {


              if(is_array($postal_codes_array))
        {

          foreach($postal_codes_array as $current_postal_code)
          {
            if (strpos($postalcode, $current_postal_code) === 0) {



              
              $all_stores_with_addresses[$current_enabled_store->ID] = 10; 


              
              }
              else
              {
                                   $all_stores_not_available[$current_enabled_store->ID] = 10;              

              }             


          }


           


        }


        $all_stores_with_addresses = array_merge($all_stores_with_addresses,  $all_stores_not_available);
        }
















      }

















}//end foreach store
























//if at least one store can deliver
if (!empty($all_stores_with_addresses))
{

  $store_name_array = array_keys($all_stores_with_addresses, min($all_stores_with_addresses));  

  $nearest_store_id = $store_name_array[0];



  $nearest_store =  get_the_title($nearest_store_id);

  $delivery_available  = true;  



}//end if

//none store can deliver...show error notice//
else{

  $delivery_available  = false;  



}

  return array("available_stores"=>$all_stores_with_addresses, "nearest_store_id"=>$nearest_store_id, "nearest_store_name"=> $nearest_store, "availability"=>$delivery_available);


}
?>