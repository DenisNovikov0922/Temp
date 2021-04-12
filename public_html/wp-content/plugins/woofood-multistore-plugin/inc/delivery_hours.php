<?php
  add_action( 'woocommerce_checkout_process', 'wf_multi_check_order_datetime', 98 );
  function wf_multi_check_order_datetime() {
    $order_type = woofood_get_default_order_type();
    $store_id  = 0;
    

    remove_action( 'woocommerce_checkout_process', 'wf_check_order_datetime' , 99 );
     remove_action( 'woocommerce_before_cart' , 'wf_check_order_datetime' , 99 );
      $woofood_options = get_option('woofood_options');

       $woofood_delivery_off_out_of_hours = isset($woofood_options["woofood_delivery_off_out_of_hours"]) ? $woofood_options["woofood_delivery_off_out_of_hours"] : null;
       $woofood_pickup_off_out_of_hours = isset($woofood_options["woofood_pickup_off_out_of_hours"]) ? $woofood_options["woofood_pickup_off_out_of_hours"] : null;
       $woofood_force_disable_delivery_option = isset($woofood_options["woofood_force_disable_delivery_option"]) ? $woofood_options["woofood_force_disable_delivery_option"] : null;
       $woofood_force_disable_pickup_option = isset($woofood_options["woofood_force_disable_pickup_option"]) ? $woofood_options["woofood_force_disable_pickup_option"] : null;




 
          if(isset($_POST["woofood_order_type"]))
    {
      if($_POST["woofood_order_type"] =="delivery")
      {
               $order_type = "delivery";


      }

      if($_POST["woofood_order_type"] =="pickup")
      {
       $order_type = "pickup";

      }


    }
     else
    {
       $order_type =woofood_get_default_order_type();
    }
      if(isset($_POST["extra_store_name"] ))
    {
      
      $store_id = intval($_POST["extra_store_name"]);




    }
    if(isset($_POST["extra_store_name_pickup"] ) && ($order_type =="pickup"))
    {
      
      $store_id = intval($_POST["extra_store_name_pickup"]);




    }
    if(isset($_POST["extra_store_name"] ) && ($order_type =="delivery"))
    {
      
      $store_id = intval($_POST["extra_store_name"]);




    }



   



    if($order_type =="delivery")
    {

      if(((woofood_check_if_store_within_delivery_hours($store_id, false, true, current_time('Y-m-d') ) !=true ) && $woofood_delivery_off_out_of_hours ) ||  $woofood_force_disable_delivery_option  )
      {
         wc_add_notice( 
        esc_html__('Delivery Service is Currently Unavailable from this store..', 'woofood-plugin'), 'error' 
        );

      }

       elseif (woofood_check_if_store_within_delivery_hours($store_id)) 
    {
//do nothing..order can be proccessed
    }
    else{



      wc_add_notice( 
        esc_html__('Delivery Service is Currently Unavailable from this store..', 'woofood-plugin'), 'error' 
        );
    }

    }

     if($order_type =="pickup")
    {

      if(((woofood_check_if_store_within_pickup_hours($store_id, false, true, current_time('Y-m-d') ) !=true ) && $woofood_pickup_off_out_of_hours) || $woofood_force_disable_pickup_option )
      {
         wc_add_notice( 
        esc_html__('Pickup Service is Currently Unavailable from this store..', 'woofood-plugin'), 'error' 
        );


      }

      elseif (woofood_check_if_store_within_pickup_hours($store_id)) 
    {
//do nothing..order can be proccessed
    }
    else{


      wc_add_notice( 
       esc_html__('Pickup Service is Currently Unavailable from this store..', 'woofood-plugin'), 'error' 
        );
    }

    }

   

  






 } //end function










function woofood_check_if_store_within_delivery_hours($store_id, $return_today_hours = false, $current_time_only = false, $date = null, $time =null, $check_day_available =false)
{


  $current_day_name= current_time("l");

    if(isset($_POST["woofood_date_to_deliver"]))
      {
     $current_day_name= get_date_from_gmt( $_POST["woofood_date_to_deliver"], "l");

     //wc_add_notice($current_day_name, "error");
        }







  if($date)
  {
   $current_day_name= get_date_from_gmt($date, "l");

  }
  $hours_to_check = array();

 

    $current_day_name_lowercase= strtolower($current_day_name);

    $woofood_delivery_hour_start =get_post_meta($store_id,'woofood_delivery_hours_'.$current_day_name_lowercase.'_start', true);
    $woofood_delivery_hour_end =get_post_meta($store_id,'woofood_delivery_hours_'.$current_day_name_lowercase.'_end', true);
    if(!empty($woofood_delivery_hour_start) && !empty($woofood_delivery_hour_end))
    {
      $hours_to_check[] = array("start"=>$woofood_delivery_hour_start, "end"=>$woofood_delivery_hour_end); 

    }


    $woofood_delivery_hour_start2 =get_post_meta($store_id,'woofood_delivery_hours_'.$current_day_name_lowercase.'_start2', true);
    $woofood_delivery_hour_end2 =get_post_meta($store_id,'woofood_delivery_hours_'.$current_day_name_lowercase.'_end2', true);
    if(!empty($woofood_delivery_hour_start2) && !empty($woofood_delivery_hour_end2))
    {
      $hours_to_check[] = array("start"=>$woofood_delivery_hour_start2, "end"=>$woofood_delivery_hour_end2); 

    }


    $woofood_delivery_hour_start3 =get_post_meta($store_id,'woofood_delivery_hours_'.$current_day_name_lowercase.'_start3', true);
    $woofood_delivery_hour_end3 =get_post_meta($store_id,'woofood_delivery_hours_'.$current_day_name_lowercase.'_end3', true);


    if(!empty($woofood_delivery_hour_start3) && !empty($woofood_delivery_hour_end3))
    {
      $hours_to_check[] = array("start"=>$woofood_delivery_hour_start3, "end"=>$woofood_delivery_hour_end3); 

    }
    $current_time = null;
    if(isset($_POST["woofood_time_to_deliver"]))
    {
      if($_POST["woofood_time_to_deliver"] =="now" ||$_POST["woofood_time_to_deliver"] =="asap" )
      {

        $current_time = current_time("H:i");

      }
      else
      {
        $current_time = $_POST["woofood_time_to_deliver"];


      }

    }
    else if($time)
    {
            $current_time = $time;

    }
    else
    {
      $current_time = current_time("H:i");

    }

     if($current_time_only)
    {
            $current_time = current_time("H:i");

    }

  

        if(!empty($hours_to_check))
    { 
      if($check_day_available )
      {

        return true;

      }
     

      if($return_today_hours)
      {

        return $hours_to_check;
      }

      foreach($hours_to_check as $hour)
      {
        if((strtotime($hour["start"]) <= strtotime($current_time))  && (strtotime($current_time) <=  strtotime($hour["end"])))
        {

   

          return true;



        }


      }
      return false;


    }
    else
    {
      if($return_today_hours)
      {                return array();


       // return array(array("start"=>"00:00:00", "end"=>"00:00:00"));
      }
      return woofood_check_if_within_delivery_hours();
    }
  
 
  
  return false;
}



function woofood_check_if_store_within_pickup_hours($store_id, $return_today_hours = false, $current_time_only = false, $date = null, $time =null, $check_day_available =false)
{



$current_day_name= current_time("l");

    if(isset($_POST["woofood_date_to_pickup"]))
      {
     $current_day_name= get_date_from_gmt( $_POST["woofood_date_to_pickup"], "l");

     //wc_add_notice($current_day_name, "error");
        }







  if($date)
  {
   $current_day_name= get_date_from_gmt($date, "l");

  }

 $hours_to_check = array();



    $current_day_name_lowercase= strtolower($current_day_name);

    $woofood_pickup_hour_start = get_post_meta($store_id, 'woofood_pickup_hours_'.$current_day_name_lowercase.'_start', true);
    $woofood_pickup_hour_end = get_post_meta($store_id, 'woofood_pickup_hours_'.$current_day_name_lowercase.'_end', true);
    if(!empty($woofood_pickup_hour_start) && !empty($woofood_pickup_hour_end))
    {
      $hours_to_check[] = array("start"=>$woofood_pickup_hour_start, "end"=>$woofood_pickup_hour_end); 

    }


    $woofood_pickup_hour_start2 = get_post_meta($store_id, 'woofood_pickup_hours_'.$current_day_name_lowercase.'_start2', true);
    $woofood_pickup_hour_end2 = get_post_meta($store_id, 'woofood_pickup_hours_'.$current_day_name_lowercase.'_end2', true);
    if(!empty($woofood_pickup_hour_start2) && !empty($woofood_pickup_hour_end2))
    {
      $hours_to_check[] = array("start"=>$woofood_pickup_hour_start2, "end"=>$woofood_pickup_hour_end2); 

    }


    $woofood_pickup_hour_start3 = get_post_meta($store_id, 'woofood_pickup_hours_'.$current_day_name_lowercase.'_start3', true);
    $woofood_pickup_hour_end3 = get_post_meta($store_id, 'woofood_pickup_hours_'.$current_day_name_lowercase.'_end3', true);


    if(!empty($woofood_pickup_hour_start3) && !empty($woofood_pickup_hour_end3))
    {
      $hours_to_check[] = array("start"=>$woofood_pickup_hour_start3, "end"=>$woofood_pickup_hour_end3); 

    }
  $current_time = null;

    if($current_time_only)
    {
            $current_time = current_time("H:i");

    }
     else if($time)
    {
            $current_time = $time;

    }
    else
    {
      if(isset($_POST["woofood_time_to_pickup"]))
    {
      if($_POST["woofood_time_to_pickup"] =="now" ||$_POST["woofood_time_to_pickup"] =="asap")
      {

        $current_time = current_time("H:i");

      }
      else
      {
        $current_time = $_POST["woofood_time_to_pickup"];

      }

    }
    else
    {
      $current_time = current_time("H:i");

    }

    }
    

     if(!empty($hours_to_check))
    { 

      if($check_day_available)
      {
        return true;
      }

      if($return_today_hours)
      {

        return $hours_to_check;
      }

      foreach($hours_to_check as $hour)
      {



        if((strtotime($hour["start"]) <= strtotime($current_time)  && strtotime($current_time) <=  strtotime($hour["end"])))
        {
          return true;

        }

      }

      return false;


    }
    else
    {
      if($return_today_hours)
      {

        //return array(array("start"=>"00:00:00", "end"=>"00:00:00"));
                return array();

      }
      return woofood_check_if_within_pickup_hours();
    }
 
  return false;
}



function woofood_multistore_get_pickup_days()
{ 

  $woofood_options = get_option('woofood_options');

  $woofood_pickup_date_up_to_days = isset($woofood_options['woofood_pickup_date_up_to_days']) ? intval($woofood_options['woofood_pickup_date_up_to_days']) : 1 ;
  $default_date_format = get_option( 'date_format' );


   $period = new DatePeriod(
    new DateTime(current_time("Y-m-d")),
    new DateInterval('P1D'),
    new DateTime(date("Y-m-d", strtotime("+".$woofood_pickup_date_up_to_days." days", strtotime(current_time("Y-m-d")))))

);

  $store_id = 0 ;
  $date = "";
  if(isset($_POST["store_id"]))
  {
      $store_id = intval($_POST["store_id"]);
      //$date = $_POST["date"];


  }


  $date_options = "";
  $today = current_time('Y-m-d');


foreach ($period as $date) {
  $today_pickup_hours = woofood_check_if_store_within_pickup_hours($store_id, true, false,$date->format("Y-m-d"), false );
   if( !empty($today_pickup_hours)     )
  {
    if($date->format("Y-m-d") ==current_time("Y-m-d") )
    {
              $date_options.= '<option value="'.$date->format("Y-m-d").'">'.esc_html__('Today', 'woofood-plugin').'</option>'; 

    }
    //$date_options[$date->format("Y-m-d")] = date_i18n( "l", strtotime($date->format("l") ) )." - ".date_i18n( $default_date_format, strtotime($date->format($default_date_format) ) );
    if($date->format("Y-m-d") !=current_time("Y-m-d") )
    {
        $date_options.= '<option value="'.$date->format("Y-m-d").'">'.date_i18n( "l", strtotime($date->format("l") ) )." - ".date_i18n( $default_date_format, strtotime($date->format("Y-m-d") ) ).'</option>'; 

    }



  }
}
if(empty($date_options))

{
    $date_options = '<option value="none">'.esc_html__('No available dates', 'woofood-plugin').'</option>';

}
echo $date_options;




wp_die();
}
add_action( 'wp_ajax_woofood_multistore_get_pickup_days', 'woofood_multistore_get_pickup_days' );
add_action( 'wp_ajax_nopriv_woofood_multistore_get_pickup_days', 'woofood_multistore_get_pickup_days' );









function woofood_multistore_get_delivery_days()
{ 

  $woofood_options = get_option('woofood_options');

  $woofood_delivery_date_up_to_days = isset($woofood_options['woofood_delivery_date_up_to_days']) ? intval($woofood_options['woofood_delivery_date_up_to_days']) : 1 ;
  $default_date_format = get_option( 'date_format' );


   $period = new DatePeriod(
    new DateTime(current_time("Y-m-d")),
    new DateInterval('P1D'),
    new DateTime(date("Y-m-d", strtotime("+".$woofood_delivery_date_up_to_days." days", strtotime(current_time("Y-m-d")))))

);

  $store_id = 0 ;
  $date = "";
  if(isset($_POST["store_id"]))
  {
      $store_id = intval($_POST["store_id"]);
      //$date = $_POST["date"];


  }


  $date_options = "";
  $today = current_time('Y-m-d');
/*
if(woofood_check_if_store_within_delivery_hours($store_id, false, false, current_time("Y-m-d"), null, true ))
{
  //$date_options[$today] = esc_html__('Today', 'woofood-plugin');

//  $date_options.= '<option value="'.$today.'">'.esc_html__('Today', 'woofood-plugin').'</option>'; 

}*/
foreach ($period as $date) {
  $today_delivery_hours = woofood_check_if_store_within_delivery_hours($store_id, true, false,$date->format("Y-m-d"), false );
  if( !empty($today_delivery_hours)     )
  {
    if($date->format("Y-m-d") ==current_time("Y-m-d") )
    {
              $date_options.= '<option value="'.$date->format("Y-m-d").'">'.esc_html__('Today', 'woofood-plugin').'</option>'; 

    }
    //$date_options[$date->format("Y-m-d")] = date_i18n( "l", strtotime($date->format("l") ) )." - ".date_i18n( $default_date_format, strtotime($date->format($default_date_format) ) );
    if($date->format("Y-m-d") !=current_time("Y-m-d") )
    {
        $date_options.= '<option value="'.$date->format("Y-m-d").'">'.date_i18n( "l", strtotime($date->format("l") ) )." - ".date_i18n( $default_date_format, strtotime($date->format("Y-m-d") ) ).'</option>'; 

    }



  }
}
if(empty($date_options))

{
    $date_options = '<option value="none">'.esc_html__('No available dates', 'woofood-plugin').'</option>';

}
echo $date_options;




wp_die();
}
add_action( 'wp_ajax_woofood_multistore_get_delivery_days', 'woofood_multistore_get_delivery_days' );
add_action( 'wp_ajax_nopriv_woofood_multistore_get_delivery_days', 'woofood_multistore_get_delivery_days' );




function woofood_multistore_get_pickup_hours_for_day()
{ 

  $woofood_options = get_option('woofood_options');

  $woofood_pickup_date_up_to_days = isset($woofood_options['woofood_pickup_date_up_to_days']) ? intval($woofood_options['woofood_pickup_date_up_to_days']) : 1 ;
  $default_time_format = get_option( 'time_format' );
      $woofood_break_down_pickup_times_every = $woofood_options['woofood_break_down_pickup_times_every'];
  $woofood_enable_maximum_orders_pickup_timeslot = isset($woofood_options['woofood_enable_maximum_orders_pickup_timeslot']) ? $woofood_options['woofood_enable_maximum_orders_pickup_timeslot']: null  ;
      $woofood_maximum_orders_pickup_timeslot = $woofood_options['woofood_maximum_orders_pickup_timeslot'] ? intval($woofood_options['woofood_maximum_orders_pickup_timeslot']) : 0;
      $woofood_enable_asap_on_pickup_time = $woofood_options['woofood_enable_asap_on_pickup_time'];
      $woofood_disable_now_from_pickup_time = $woofood_options['woofood_disable_now_from_pickup_time'];



  $store_id = 0 ;
  $date = current_time("Y-m-d");
  if(isset($_POST["store_id"]))
  {
      $store_id = intval($_POST["store_id"]);


  }
   if(isset($_POST["date"]))
  {
      $date = $_POST["date"];
  }
 
 $date = get_date_from_gmt($date, "Y-m-d");
    $we_are_open_currently = woofood_check_if_store_within_pickup_hours($store_id, false, true );
    $today_pickup_hours = woofood_check_if_store_within_pickup_hours($store_id, true, false,$date, false );
$time_to_pickup_options = "";
                          if(!$woofood_disable_now_from_pickup_time && (current_time("Y-m-d") == $date) && $we_are_open_currently)
                          {
                          $time_to_pickup_options .='<option value="now">'.esc_html__('Now', 'woofood-plugin').'</option>';
                          }

                          if($woofood_enable_asap_on_pickup_time && (current_time("Y-m-d") == $date) && $we_are_open_currently)
                          {
                          $time_to_pickup_options .='<option value="asap">'.esc_html__('ASAP', 'woofood-plugin').'</option>';

                          }

                          if(is_array($today_pickup_hours))
                          {
                          foreach($today_pickup_hours as $time)
                          {

                          $period_2 = new DatePeriod(
                          new DateTime($time["start"]),
                          new DateInterval('PT'.$woofood_break_down_pickup_times_every .'M'),
                          new DateTime($time["end"])
                          );


                     
                          foreach ($period_2 as $date_2) {

                          

                        
                          if((current_time("Y-m-d") === $date ))
                          {
                            //echo current_time("H:i") ." < ".$date_2->format("H:i")."";
                          
                          if( (strtotime(current_time("H:i")) < strtotime($date_2->format("H:i")) )  )
                          {   


                          if($woofood_enable_maximum_orders_pickup_timeslot && $woofood_maximum_orders_pickup_timeslot > 0)
                          {
                          if(woofood_get_orders_count("pickup", current_time("Y-m-d"), $date_2->format("H:i")) < $woofood_maximum_orders_pickup_timeslot)
                          {
                                

                                    $time_to_pickup_options .='<option value="'.$date_2->format("H:i").'">'.date_i18n( $default_time_format, strtotime($date_2->format("H:i") ) ).'</option>';
                                 


                          }

                          }
                          else
                          {
                           
                                    $time_to_pickup_options .='<option value="'.$date_2->format("H:i").'">'.date_i18n( $default_time_format, strtotime($date_2->format("H:i") ) ).'</option>';
                                  

                          }



                          //  $time_to_delivery_options[$date->format($default_time_format)] = $date->format($default_time_format);


                          }
                          }
                          else
                          {

                            if($woofood_enable_maximum_orders_pickup_timeslot && $woofood_maximum_orders_pickup_timeslot > 0)
                          {
                          if(woofood_get_orders_count("pickup", $date, $date_2->format("H:i")) < $woofood_maximum_orders_pickup_timeslot)
                          {

                                    $time_to_pickup_options .='<option value="'.$date_2->format("H:i").'">'.date_i18n( $default_time_format, strtotime($date_2->format("H:i") ) ).'</option>';


                          }

                          }
                          else
                          {
                                    $time_to_pickup_options .='<option value="'.$date_2->format("H:i").'">'.date_i18n( $default_time_format, strtotime($date_2->format("H:i") ) ).'</option>';

                          }




                          }








                          }






                          }

                          }
                          if(empty($time_to_pickup_options))
                          {
                              $time_to_pickup_options = '<option value="none">'.esc_html__('No Available hours', 'woofood-plugin').'</option>';

                          }
                          echo $time_to_pickup_options;





wp_die();
}
add_action( 'wp_ajax_woofood_multistore_get_pickup_hours_for_day', 'woofood_multistore_get_pickup_hours_for_day' );
add_action( 'wp_ajax_nopriv_woofood_multistore_get_pickup_hours_for_day', 'woofood_multistore_get_pickup_hours_for_day' );






function woofood_multistore_get_delivery_hours_for_day()
{ 

  $woofood_options = get_option('woofood_options');

  $woofood_delivery_date_up_to_days = isset($woofood_options['woofood_delivery_date_up_to_days']) ? intval($woofood_options['woofood_delivery_date_up_to_days']) : 1 ;
  $default_time_format = get_option( 'time_format' );
      $woofood_break_down_times_every = $woofood_options['woofood_break_down_times_every'];
  $woofood_enable_maximum_orders_delivery_timeslot = isset($woofood_options['woofood_enable_maximum_orders_delivery_timeslot']) ? $woofood_options['woofood_enable_maximum_orders_delivery_timeslot']: null  ;
      $woofood_maximum_orders_delivery_timeslot = $woofood_options['woofood_maximum_orders_delivery_timeslot'] ? intval($woofood_options['woofood_maximum_orders_delivery_timeslot']) : 0;
      $woofood_enable_asap_on_time = $woofood_options['woofood_enable_asap_on_time'];
      $woofood_disable_now_from_time = $woofood_options['woofood_disable_now_from_time'];
if(!$woofood_break_down_times_every)
      {
        $woofood_break_down_times_every =  "30";
      }


  $store_id = 0 ;
  $date = current_time("Y-m-d");
  if(isset($_POST["store_id"]))
  {
      $store_id = intval($_POST["store_id"]);


  }
   if(isset($_POST["date"]))
  {
      $date = $_POST["date"];
  }
 
 $date = get_date_from_gmt($date, "Y-m-d");
    $we_are_open_currently = woofood_check_if_store_within_delivery_hours($store_id, false, true );
    $today_delivery_hours = woofood_check_if_store_within_delivery_hours($store_id, true, false,$date, false );
$time_to_delivery_options = "";
                          if(!$woofood_disable_now_from_time && (current_time("Y-m-d") == $date) && $we_are_open_currently)
                          {
                          $time_to_delivery_options .='<option value="now">'.esc_html__('Now', 'woofood-plugin').'</option>';
                          }

                          if($woofood_enable_asap_on_time && (current_time("Y-m-d") == $date) && $we_are_open_currently)
                          {
                          $time_to_delivery_options .='<option value="asap">'.esc_html__('ASAP', 'woofood-plugin').'</option>';

                          }

                          if(is_array($today_delivery_hours))
                          {
                          foreach($today_delivery_hours as $time)
                          {

                          $period_2 = new DatePeriod(
                          new DateTime($time["start"]),
                          new DateInterval('PT'.$woofood_break_down_times_every .'M'),
                          new DateTime($time["end"])
                          );


                     
                          foreach ($period_2 as $date_2) {

                          

                        
                          if((current_time("Y-m-d") === $date ))
                          {
                            //echo current_time("H:i") ." < ".$date_2->format("H:i")."";
                          
                          if( (strtotime(current_time("H:i")) < strtotime($date_2->format("H:i")) )  )
                          {   


                          if($woofood_enable_maximum_orders_delivery_timeslot && $woofood_maximum_orders_delivery_timeslot > 0)
                          {
                          if(woofood_get_orders_count("delivery", current_time("Y-m-d"), $date_2->format("H:i")) < $woofood_maximum_orders_delivery_timeslot)
                          {
                                

                                    $time_to_delivery_options .='<option value="'.$date_2->format("H:i").'">'.date_i18n( $default_time_format, strtotime($date_2->format("H:i") ) ).'</option>';
                                 


                          }

                          }
                          else
                          {
                           
                                    $time_to_delivery_options .='<option value="'.$date_2->format("H:i").'">'.date_i18n( $default_time_format, strtotime($date_2->format("H:i") ) ).'</option>';
                                  

                          }



                          //  $time_to_delivery_options[$date->format($default_time_format)] = $date->format($default_time_format);


                          }
                          }
                          else
                          {

                            if($woofood_enable_maximum_orders_delivery_timeslot && $woofood_maximum_orders_delivery_timeslot > 0)
                          {
                          if(woofood_get_orders_count("delivery", $date, $date_2->format("H:i")) < $woofood_maximum_orders_delivery_timeslot)
                          {

                                    $time_to_delivery_options .='<option value="'.$date_2->format("H:i").'">'.date_i18n( $default_time_format, strtotime($date_2->format("H:i") ) ).'</option>';


                          }

                          }
                          else
                          {
                                    $time_to_delivery_options .='<option value="'.$date_2->format("H:i").'">'.date_i18n( $default_time_format, strtotime($date_2->format("H:i") ) ).'</option>';

                          }




                          }








                          }






                          }

                          }
                          if(empty($time_to_delivery_options))
                          {
                              $time_to_delivery_options = '<option value="none">'.esc_html__('No Available hours', 'woofood-plugin').'</option>';

                          }
                          echo $time_to_delivery_options;





wp_die();
}
add_action( 'wp_ajax_woofood_multistore_get_delivery_hours_for_day', 'woofood_multistore_get_delivery_hours_for_day' );
add_action( 'wp_ajax_nopriv_woofood_multistore_get_delivery_hours_for_day', 'woofood_multistore_get_delivery_hours_for_day' );

//check date and time //
?>