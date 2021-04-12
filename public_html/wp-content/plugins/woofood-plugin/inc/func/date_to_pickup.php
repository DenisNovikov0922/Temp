<?php
if ($woofood_enable_date_to_pickup_option) {

add_action( 'woocommerce_checkout_before_order_review', 'wf_select_date_to_pickup', 12, 0 );

function wf_select_date_to_pickup() {
  $woofood_options = get_option('woofood_options');
  $woofood_pickup_time = intval($woofood_options['woofood_pickup_time']);
      $woofood_disable_now_from_pickup_time = $woofood_options['woofood_disable_now_from_pickup_time'];
      $woofood_enable_asap_on_pickup_time = $woofood_options['woofood_enable_asap_on_pickup_time'];
      $woofood_break_down_pickup_times_every = $woofood_options['woofood_break_down_pickup_times_every'];
    $woofood_pickup_date_up_to_days = isset($woofood_options['woofood_pickup_date_up_to_days']) ? intval($woofood_options['woofood_pickup_date_up_to_days']) : 1 ;
  $woofood_delivery_time = intval($woofood_options['woofood_delivery_time']);

      $woofood_enable_maximum_orders_pickup_timeslot = isset($woofood_options['woofood_enable_maximum_orders_pickup_timeslot']) ? $woofood_options['woofood_enable_maximum_orders_pickup_timeslot']: null  ;
      $woofood_maximum_orders_pickup_timeslot = $woofood_options['woofood_maximum_orders_pickup_timeslot'] ? intval($woofood_options['woofood_maximum_orders_pickup_timeslot']) : 0;

      if(!$woofood_break_down_pickup_times_every)
      {
        $woofood_break_down_pickup_times_every =  "30";
      }

      $default_time_format = get_option( 'time_format' );
    $default_date_format = get_option( 'date_format' );

  if(!$default_time_format)
  {
    $default_time_format = "H:i";
    
  }
  if(!$default_date_format)
  {
    $default_date_format = "Y-m-d";
    
  }
$today = current_time('Y-m-d');
echo '<div id="wf-date-to-deliver" class="pickup"> ';

$date_options = array();


$current_time = current_time("H:i");
$current_date = current_time("Y-m-d");
if($woofood_pickup_time > 0)
{
$current_time = strtotime("+".$woofood_pickup_time." minutes", strtotime($current_time));
}
else
{
  
}




  $period = new DatePeriod(
    new DateTime(current_time("Y-m-d")),
    new DateInterval('P1D'),
    new DateTime(date("Y-m-d", strtotime("+".$woofood_pickup_date_up_to_days." days", strtotime(current_time("Y-m-d")))))

);









$pickup_hours_per_day = get_transient( "woofood_cached_date_times_pickup" );
if(empty($pickup_hours_per_day))
{
  $pickup_hours_per_day = array();
}
  //$delivery_hours_per_day = array();
//$delivery_hours_per_day = array();
  if(empty($pickup_hours_per_day) || !is_array($pickup_hours_per_day)  ) {
  $pickup_hours_per_day = array();
  foreach ($period as $date) {
    $today_pickup_hours = woofood_check_if_within_pickup_hours(true, false,$date->format("Y-m-d"), false );
    if($date->format("Y-m-d") !=$current_date &&  !empty($today_pickup_hours))
    {
        //  $date_options[$date->format("Y-m-d")] = date_i18n( "l", strtotime($date->format("l") ) )." - ".date_i18n( $default_date_format, strtotime($date->format($default_date_format) ) );
 

                          $time_to_pickup_options = "";


                          //$today_delivery_hours = woofood_check_if_within_delivery_hours(true, false,$date->format("Y-m-d"), false );
                          $current_time = strtotime(current_time("H:i"));

                          if($woofood_pickup_time > 0)
                          {
                          $current_time = strtotime("+".$woofood_pickup_time." minutes", strtotime(current_time("H:i")));
                          //$current_time = date($default_time_format, $current_time);
                          }
                          else
                          {

                          }
                                                    $we_are_open_currently = woofood_check_if_within_pickup_hours(false, true );

                          if(!$woofood_disable_now_from_pickup_time && (current_time("Y-m-d") == $date->format("Y-m-d")) && $we_are_open_currently)
                          {
                          $time_to_pickup_options .='<option value="now">'.esc_html__('Now', 'woofood-plugin').'</option>';
                          }

                          if($woofood_enable_asap_on_pickup_time && current_time("Y-m-d") == $date->format("Y-m-d") && $we_are_open_currently)
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
                          if($current_time < strtotime($date_2->format("H:i"))  && current_time("Y-m-d") == $date->format("Y-m-d") )
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
                          else
                          {

                            if($woofood_enable_maximum_orders_pickup_timeslot && $woofood_maximum_orders_pickup_timeslot > 0)
                          {
                          if(woofood_get_orders_count("pickup", $date->format("Y-m-d"), $date_2->format("H:i")) < $woofood_maximum_orders_pickup_timeslot)
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


if(!empty($time_to_pickup_options))
                      {
                                            $pickup_hours_per_day[$date->format("Y-m-d")] =   addslashes($time_to_pickup_options);
                                          }













    }
        set_transient( "woofood_cached_date_times_pickup", $pickup_hours_per_day , 600);


  } //end if delivery hours cached not exists//










 

    
}


$date_options = array();
if(woofood_check_if_within_pickup_hours(false, false, current_time("Y-m-d"), null, true ))
{
  
  $date_options[$today] = esc_html__('Today', 'woofood-plugin');


}
foreach ($period as $date) {
  $today_pickup_hours = woofood_check_if_within_pickup_hours(true, false,$date->format("Y-m-d"), false );
  if( ($date->format("Y-m-d") !=current_time("Y-m-d") ) &&  !empty($today_pickup_hours)     )
  {
     if(!empty($pickup_hours_per_day[$date->format("Y-m-d")]))
    {
    $date_options[$date->format("Y-m-d")] = date_i18n( "l", strtotime($date->format("l") ) )." - ".date_i18n( $default_date_format, strtotime($date->format("Y-m-d") ) );
}



  }
}

//add today hours without caching them///

        $today_pickup_hours_only = woofood_check_if_within_pickup_hours(true, false,current_time("Y-m-d"), false );




//add  hours without caching them///

        $time_to_pickup_options_today ="";

        if(is_array($today_pickup_hours_only))
        {
          foreach($today_pickup_hours_only as $time)
          {

            $period_2 = new DatePeriod(
              new DateTime($time["start"]),
              new DateInterval('PT'.$woofood_break_down_pickup_times_every .'M'),
              new DateTime($time["end"])
            );
$we_are_open_now = woofood_check_if_within_pickup_hours(false, true );

             if(!$woofood_disable_now_from_pickup_time && $we_are_open_now )
                          {
                          $time_to_pickup_options_today .='<option value="now">'.esc_html__('Now', 'woofood-plugin').'</option>';
                          }

                          if($woofood_enable_asap_on_pickup_time && $we_are_open_now )
                          {
                          $time_to_pickup_options_today .='<option value="asap">'.esc_html__('ASAP', 'woofood-plugin').'</option>';

                          }



                          $current_time = strtotime(current_time("H:i"));

                          if($woofood_pickup_time > 0)
                          {
                          $current_time = strtotime("+".$woofood_pickup_time." minutes", strtotime(current_time("H:i")));
                          //$current_time = date($default_time_format, $current_time);
                          }
            foreach ($period_2 as $date_2) {
              if($current_time < strtotime($date_2->format("H:i")) )
              {   
                if($woofood_enable_maximum_orders_pickup_timeslot && $woofood_maximum_orders_pickup_timeslot > 0)
                {
                  if(woofood_get_orders_count("pickup", current_time("Y-m-d"), $date_2->format("H:i")) < $woofood_maximum_orders_pickup_timeslot)
                  {

                    $time_to_pickup_options_today .='<option value="'.$date_2->format("H:i").'">'.date_i18n( $default_time_format, strtotime($date_2->format("H:i") ) ).'</option>';


                  }

                }
                else
                {
                  $time_to_pickup_options_today .='<option value="'.$date_2->format("H:i").'">'.date_i18n( $default_time_format, strtotime($date_2->format("H:i") ) ).'</option>';

                }





              }








            }






          }

        }









if(!empty($time_to_pickup_options_today))
        {

        $pickup_hours_per_day["".current_time("Y-m-d").""] = addslashes($time_to_pickup_options_today);

        }
        else
        {
                  unset($date_options[current_time("Y-m-d")]);

        }


//add today hours without caching them///























$pickup_hours_encoded = json_encode($pickup_hours_per_day);









































?>
<script>
 var pickup_hours_per_day  = '<?php echo $pickup_hours_encoded; ?>';
  var pickup_hours_per_day_array = JSON.parse(pickup_hours_per_day);

</script>


<script>

 //var woofood_store_selected = 0;
jQuery(document).ready(function () {


      




  var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();
 var woofood_day_selected = jQuery('#woofood_date_to_pickup option:selected').val();
jQuery('#woofood_time_to_pickup').html(pickup_hours_per_day_array[woofood_day_selected]);



              




    jQuery(document).on('change', '#woofood_date_to_pickup', function (){

     var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();
 var woofood_day_selected = jQuery('#woofood_date_to_pickup option:selected').val();
jQuery('#woofood_time_to_pickup').html(pickup_hours_per_day_array[woofood_day_selected]);

    

        return false;
    });














});

</script>

 <?php



if(empty($date_options))
{
  $date_options["none"] = esc_html__('No available dates', 'woofood-plugin');
}

echo '<span class="wf_tdlvr_title">'.esc_html__('Date to Pickup', 'woofood-plugin').'</span>';
woocommerce_form_field( 'woofood_date_to_pickup', array(
//'label'         => esc_html__('Time to Deliver', 'woofood-plugin'),
 'type'         => 'select',

'class'         => array('woofood_date_to_pickup'),

'required'     => true,
'options'  => $date_options,

), '');

echo '</div>';




}




} //end if time to delivery option//
?>