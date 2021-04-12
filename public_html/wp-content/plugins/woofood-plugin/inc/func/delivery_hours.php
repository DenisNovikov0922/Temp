<?php

//add_action( 'wp_footer', 'woofood_disabled_store_overlay');

function woofood_disabled_store_overlay()
{
   $woofood_options = get_option('woofood_options');
  $order_types = woofood_get_order_types();
  $default_order_type=woofood_get_default_order_type();
  $order_types_enabled_now = array();
  foreach( $order_types as $order_type => $order_type_name)
  {
    $is_enabed = isset($woofood_options["woofood_force_disable_".$order_type."_option"]) ? $woofood_options["woofood_force_disable_".$order_type."_option"] : null;
    if(!$is_enabed)
    {
       $order_types_enabled_now[$order_type] =true;
    }


  }

  if(empty($order_types_enabled_now))
  {
    ?>
<div class="woofood-disabled-overlay">
    <div class="woofood-overlay-content-disabled">
       <?php echo apply_filters('woofood_disabled_overlay_message', esc_html__('Store is currently disabled..', 'woofood-plugin')); ?>
    </div>
    </div>
    <?php
  }


}

  add_action( 'woocommerce_checkout_process', 'wf_check_order_datetime', 99 );
  //add_action( 'woocommerce_before_cart' , 'wf_check_order_datetime', 99 );



  function wf_check_order_datetime() {

    $woofood_options = get_option('woofood_options');
    $options_woofood_delivery_hours = get_option('woofood_options_delivery_hours');
    $options_woofood_pickup_hours = get_option('woofood_options_pickup_hours');

if (!empty($options_woofood_delivery_hours) || !empty($options_woofood_pickup_hours) )
{
    $order_type = woofood_get_default_order_type();


    if(is_checkout())
    {
    
       

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

      elseif($_POST["woofood_order_type"] =="pickup")
      {
       $order_type = "pickup";

      }


    }
   

    if($order_type =="delivery")
    {
      if(((woofood_check_if_within_delivery_hours(false, true, current_time('Y-m-d') ) !=true ) && $woofood_delivery_off_out_of_hours ) ||  $woofood_force_disable_delivery_option  )
      {
         wc_add_notice( 
        esc_html__('Delivery Service is Currently Unavailable..', 'woofood-plugin'), 'error' 
        );

      }

       elseif(woofood_check_if_within_delivery_hours() ) 
    {
//do nothing..order can be proccessed
    }
    else{



      wc_add_notice( 
        esc_html__('Delivery Service is Currently Unavailable..', 'woofood-plugin'), 'error' 
        );
    }

    }

     if($order_type =="pickup")
    {
      if(((woofood_check_if_within_pickup_hours(false, true, current_time('Y-m-d') ) !=true ) && $woofood_pickup_off_out_of_hours) || $woofood_force_disable_pickup_option )
      {
         wc_add_notice( 
        esc_html__('Pickup Service is Currently Unavailable..', 'woofood-plugin'), 'error' 
        );

      }

     else if (woofood_check_if_within_pickup_hours()) 
    {
//do nothing..order can be proccessed
    }
    else{


      wc_add_notice( 
       esc_html__('Pickup Service is Currently Unavailable..', 'woofood-plugin'), 'error' 
        );
    }

    }

   

  }

  if(is_cart())
  {





    if (woofood_check_if_within_delivery_hours()) 
    {
         wc_add_notice( 
        esc_html__('Delivery Service is Currently Available..', 'woofood-plugin'), 'success' 
        );

    }
    else
    {
         wc_add_notice( 
        esc_html__('Delivery Service is Currently Unavailable..', 'woofood-plugin'), 'error' 
        );

    }

    if (woofood_check_if_within_pickup_hours()) 
    {
         wc_add_notice( 
        esc_html__('Pickup Service is Currently Available..', 'woofood-plugin'), 'success' 
        );

    }
    else
    {
         wc_add_notice( 
        esc_html__('Pickup Service is Currently Unavailable..', 'woofood-plugin'), 'error' 
        );

    }



  }




 } //end function



}








function woofood_check_if_within_delivery_hours($return_today_hours = false, $current_time_only = false, $date = null, $time =null, $check_day_available =false)
{

    $current_day_name= current_time("l");

    if(isset($_POST["woofood_date_to_deliver"]))
      {
     //$current_day_name= get_date_from_gmt( $_POST["woofood_date_to_deliver"], "l");
   $current_day_name= date("l", strtotime($_POST["woofood_date_to_deliver"]));

     //wc_add_notice($current_day_name, "error");
        }







  if($date)
  {
   $current_day_name= date("l", strtotime($date));

  }
  //print_r("".$current_day_name);

  $hours_to_check = array();

  $options_woofood_delivery_hours = get_option('woofood_options_delivery_hours');
  if (!empty($options_woofood_delivery_hours))
  {

    $current_day_name_lowercase= strtolower($current_day_name);

    $woofood_delivery_hour_start = $options_woofood_delivery_hours['woofood_delivery_hours_'.$current_day_name_lowercase.'_start'];
    $woofood_delivery_hour_end = $options_woofood_delivery_hours['woofood_delivery_hours_'.$current_day_name_lowercase.'_end'];
    if(!empty($woofood_delivery_hour_start) && !empty($woofood_delivery_hour_end))
    {
      $hours_to_check[] = array("start"=>$woofood_delivery_hour_start, "end"=>$woofood_delivery_hour_end); 

    }


    $woofood_delivery_hour_start2 = $options_woofood_delivery_hours['woofood_delivery_hours_'.$current_day_name_lowercase.'_start2'];
    $woofood_delivery_hour_end2 = $options_woofood_delivery_hours['woofood_delivery_hours_'.$current_day_name_lowercase.'_end2'];
    if(!empty($woofood_delivery_hour_start2) && !empty($woofood_delivery_hour_end2))
    {
      $hours_to_check[] = array("start"=>$woofood_delivery_hour_start2, "end"=>$woofood_delivery_hour_end2); 

    }


    $woofood_delivery_hour_start3 = $options_woofood_delivery_hours['woofood_delivery_hours_'.$current_day_name_lowercase.'_start3'];
    $woofood_delivery_hour_end3 = $options_woofood_delivery_hours['woofood_delivery_hours_'.$current_day_name_lowercase.'_end3'];


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
      return false;
    }
  }
  else
  {
    return false;
  }
  return false;
}



function woofood_check_if_within_pickup_hours($return_today_hours = false, $current_time_only = false, $date = null, $time =null, $check_day_available =false)
{


$current_day_name= current_time("l");

    if(isset($_POST["woofood_date_to_pickup"]))
      {
     //$current_day_name= get_date_from_gmt( $_POST["woofood_date_to_pickup"], "l");
  // $current_day_name= date_i18n("l", $_POST["woofood_date_to_pickup"]);
   $current_day_name= date("l", strtotime($_POST["woofood_date_to_pickup"]));

     //wc_add_notice($current_day_name, "error");
        }







  if($date)
  {
   //$current_day_name= get_date_from_gmt($date, "l");
  // $current_day_name= date_i18n("l", $date);
   $current_day_name= date("l", strtotime($date));

  }

 $hours_to_check = array();

  $options_woofood_pickup_hours = get_option('woofood_options_pickup_hours');
  if (!empty($options_woofood_pickup_hours))
  {

    $current_day_name_lowercase= strtolower($current_day_name);

    $woofood_pickup_hour_start = $options_woofood_pickup_hours['woofood_pickup_hours_'.$current_day_name_lowercase.'_start'];
    $woofood_pickup_hour_end = $options_woofood_pickup_hours['woofood_pickup_hours_'.$current_day_name_lowercase.'_end'];
    if(!empty($woofood_pickup_hour_start) && !empty($woofood_pickup_hour_end))
    {
      $hours_to_check[] = array("start"=>$woofood_pickup_hour_start, "end"=>$woofood_pickup_hour_end); 

    }


    $woofood_pickup_hour_start2 = $options_woofood_pickup_hours['woofood_pickup_hours_'.$current_day_name_lowercase.'_start2'];
    $woofood_pickup_hour_end2 = $options_woofood_pickup_hours['woofood_pickup_hours_'.$current_day_name_lowercase.'_end2'];
    if(!empty($woofood_pickup_hour_start2) && !empty($woofood_pickup_hour_end2))
    {
      $hours_to_check[] = array("start"=>$woofood_pickup_hour_start2, "end"=>$woofood_pickup_hour_end2); 

    }


    $woofood_pickup_hour_start3 = $options_woofood_pickup_hours['woofood_pickup_hours_'.$current_day_name_lowercase.'_start3'];
    $woofood_pickup_hour_end3 = $options_woofood_pickup_hours['woofood_pickup_hours_'.$current_day_name_lowercase.'_end3'];


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
      return false;
    }
  }
  else
  {
    return false;
  }
  return false;
}

//check date and time //
?>