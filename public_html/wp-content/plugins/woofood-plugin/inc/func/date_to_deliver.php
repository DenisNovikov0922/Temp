<?php
if ($woofood_enable_date_to_deliver_option) {

add_action( 'woocommerce_checkout_before_order_review', 'wf_select_date_to_deliver', 12, 0 );

function wf_select_date_to_deliver() {
  $woofood_options = get_option('woofood_options');
  $woofood_delivery_time = intval($woofood_options['woofood_delivery_time']);
      $woofood_disable_now_from_time = $woofood_options['woofood_disable_now_from_time'];
      $woofood_enable_asap_on_time = $woofood_options['woofood_enable_asap_on_time'];
      $woofood_break_down_times_every = $woofood_options['woofood_break_down_times_every'];
    $woofood_delivery_date_up_to_days = isset($woofood_options['woofood_delivery_date_up_to_days']) ? intval($woofood_options['woofood_delivery_date_up_to_days']) : 1 ;

      $woofood_enable_maximum_orders_delivery_timeslot = isset($woofood_options['woofood_enable_maximum_orders_delivery_timeslot']) ? $woofood_options['woofood_enable_maximum_orders_delivery_timeslot']: null  ;
      $woofood_maximum_orders_delivery_timeslot = $woofood_options['woofood_maximum_orders_delivery_timeslot'] ? intval($woofood_options['woofood_maximum_orders_delivery_timeslot']) : 0;

      if(!$woofood_break_down_times_every)
      {
        $woofood_break_down_times_every =  "30";
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
echo '<div id="wf-date-to-deliver" class="delivery"> ';

$date_options = array();


$current_time = current_time("H:i");
$current_date = current_time("Y-m-d");
if($woofood_delivery_time > 0)
{
$current_time = strtotime("+".$woofood_delivery_time." minutes", strtotime($current_time));
}
else
{
  
}




  $period = new DatePeriod(
    new DateTime(current_time("Y-m-d")),
    new DateInterval('P1D'),
    new DateTime(date("Y-m-d", strtotime("+".$woofood_delivery_date_up_to_days." days", strtotime(current_time("Y-m-d")))))

);









$delivery_hours_per_day = get_transient( "woofood_cached_date_times" );
if(empty($delivery_hours_per_day))
{
  $delivery_hours_per_day = array();
}
  //$delivery_hours_per_day = array();
//$delivery_hours_per_day = array();
  if(empty($delivery_hours_per_day) || !is_array($delivery_hours_per_day)  ) {
  $delivery_hours_per_day = array();
  foreach ($period as $date) {
    $today_delivery_hours = woofood_check_if_within_delivery_hours(true, false,$date->format("Y-m-d"), false );
    if($date->format("Y-m-d") !=$current_date &&  !empty($today_delivery_hours))
    {
        //  $date_options[$date->format("Y-m-d")] = date_i18n( "l", strtotime($date->format("l") ) )." - ".date_i18n( $default_date_format, strtotime($date->format($default_date_format) ) );
 

                          $time_to_delivery_options = "";


                          //$today_delivery_hours = woofood_check_if_within_delivery_hours(true, false,$date->format("Y-m-d"), false );
                          $current_time = current_time("H:i");

                          if($woofood_delivery_time > 0)
                          {
                          $current_time = strtotime("+".$woofood_delivery_time." minutes", strtotime($current_time));
                          //$current_time = date($default_time_format, $current_time);
                          }
                          else
                          {

                          }
                          $we_are_open_currently = woofood_check_if_within_delivery_hours(false, true );
                          if(!$woofood_disable_now_from_time && (current_time("Y-m-d") == $date->format("Y-m-d")) && $we_are_open_currently)
                          {
                          $time_to_delivery_options .='<option value="now">'.esc_html__('Now', 'woofood-plugin').'</option>';
                          }

                          if($woofood_enable_asap_on_time && current_time("Y-m-d") == $date->format("Y-m-d") && $we_are_open_currently)
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
                          if(strtotime($current_time) < strtotime($date_2->format("H:i"))  && current_time("Y-m-d") == $date->format("Y-m-d") )
                          {   
                          if($woofood_maximum_orders_delivery_timeslot && $woofood_maximum_orders_delivery_timeslot > 0)
                          {
                          if(woofood_get_orders_count("delivery", $date->format("Y-m-d"), $date_2->format("H:i")) < $woofood_maximum_orders_delivery_timeslot)
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
                          else
                          {

                            if($woofood_maximum_orders_delivery_timeslot && $woofood_maximum_orders_delivery_timeslot > 0)
                          {
                          if(woofood_get_orders_count("delivery", $date->format("Y-m-d"), $date_2->format("H:i")) < $woofood_maximum_orders_delivery_timeslot)
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

if(!empty($time_to_delivery_options))
                      {

                                            $delivery_hours_per_day[$date->format("Y-m-d")] =   addslashes($time_to_delivery_options);

                                          }













    }
        set_transient( "woofood_cached_date_times", $delivery_hours_per_day , 600);


  } //end if delivery hours cached not exists//










 

    
}


$date_options = array();
if(woofood_check_if_within_delivery_hours(true, false,current_time("Y-m-d"), null, true ))
{
  $date_options[$today] = esc_html__('Today', 'woofood-plugin');

}
foreach ($period as $date) {
  $today_delivery_hours = woofood_check_if_within_delivery_hours(true, false,$date->format("Y-m-d"), false );
  if($date->format("Y-m-d") !=current_time("Y-m-d") &&  !empty($today_delivery_hours)     )
  {
      if(!empty($delivery_hours_per_day[$date->format("Y-m-d")]))
    {
    $date_options[$date->format("Y-m-d")] = date_i18n( "l", strtotime($date->format("l") ) )." - ".date_i18n( $default_date_format, strtotime($date->format("Y-m-d") ) );

    }


  }
}

//add today hours without caching them///

        $today_delivery_hours_only = woofood_check_if_within_delivery_hours(true, false,current_time("Y-m-d"), false );




//add  hours without caching them///

        $time_to_delivery_options_today ="";

        if(is_array($today_delivery_hours_only))
        {
          foreach($today_delivery_hours_only as $time)
          {

            $period_2 = new DatePeriod(
              new DateTime($time["start"]),
              new DateInterval('PT'.$woofood_break_down_times_every .'M'),
              new DateTime($time["end"])
            );
$we_are_open_now = woofood_check_if_within_delivery_hours(false, true );

                         if(!$woofood_disable_now_from_time && $we_are_open_now)
                          {
                          $time_to_delivery_options_today .='<option value="now">'.esc_html__('Now', 'woofood-plugin').'</option>';
                          }

                          if($woofood_enable_asap_on_time && $we_are_open_now)
                          {
                          $time_to_delivery_options_today .='<option value="asap">'.esc_html__('ASAP', 'woofood-plugin').'</option>';

                          }


                          $current_time = strtotime(current_time("H:i"));

                          if($woofood_delivery_time > 0)
                          {
                          $current_time = strtotime("+".$woofood_delivery_time." minutes", strtotime(current_time("H:i")));
                          //$current_time = date($default_time_format, $current_time);
                          }

            foreach ($period_2 as $date_2) {
              if($current_time < strtotime($date_2->format("H:i")) )
              {   
                if($woofood_maximum_orders_delivery_timeslot && $woofood_maximum_orders_delivery_timeslot > 0)
                {
                  if(woofood_get_orders_count("delivery", current_time("Y-m-d"), $date_2->format("H:i")) < $woofood_maximum_orders_delivery_timeslot)
                  {

                    $time_to_delivery_options_today .='<option value="'.$date_2->format("H:i").'">'.date_i18n( $default_time_format, strtotime($date_2->format("H:i") ) ).'</option>';


                  }

                }
                else
                {
                  $time_to_delivery_options_today .='<option value="'.$date_2->format("H:i").'">'.date_i18n( $default_time_format, strtotime($date_2->format("H:i") ) ).'</option>';

                }





              }








            }






          }

        }







          

        if(!empty($time_to_delivery_options_today))
        {

        $delivery_hours_per_day["".current_time("Y-m-d").""] = addslashes($time_to_delivery_options_today);

        }
        else
        {
                  unset($date_options[current_time("Y-m-d")]);

        }





//add today hours without caching them///





















$delivery_hours_encoded = json_encode($delivery_hours_per_day);









































?>
<script>
 var delivery_hours_per_day  = '<?php echo $delivery_hours_encoded; ?>';
  var delivery_hours_per_day_array = JSON.parse(delivery_hours_per_day);

</script>


<script>

 //var woofood_store_selected = 0;
jQuery(document).ready(function () {


      


  var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();
 var woofood_day_selected = jQuery('#woofood_date_to_deliver option:selected').val();
jQuery('#woofood_time_to_deliver').html(delivery_hours_per_day_array[woofood_day_selected]);



              




    jQuery(document).on('change', '#woofood_date_to_deliver', function (){

     var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();
 var woofood_day_selected = jQuery('#woofood_date_to_deliver option:selected').val();
jQuery('#woofood_time_to_deliver').html(delivery_hours_per_day_array[woofood_day_selected]);

    

        return false;
    });














});

</script>

 <?php



if(empty($date_options))
{
  $date_options["none"] = esc_html__('No available dates', 'woofood-plugin');
}

echo '<span class="wf_tdlvr_title">'.esc_html__('Date to Deliver', 'woofood-plugin').'</span>';
woocommerce_form_field( 'woofood_date_to_deliver', array(
//'label'         => esc_html__('Time to Deliver', 'woofood-plugin'),
 'type'         => 'select',

'class'         => array('woofood_date_to_deliver'),

'required'     => true,
'options'  => $date_options,

), '');

echo '</div>';




}




} //end if time to delivery option//
?>