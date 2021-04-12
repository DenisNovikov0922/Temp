<?php
//Multistore//
 $woofood_options = get_option('woofood_options');
  $woofood_enable_time_to_deliver_option = isset($woofood_options['woofood_enable_time_to_deliver_option']) ?  $woofood_options['woofood_enable_time_to_deliver_option'] : null;
  $woofood_enable_pickup_option = isset($woofood_options['woofood_enable_pickup_option']) ? $woofood_options['woofood_enable_pickup_option'] : null ;
  $woofood_enable_time_to_pickup_option = isset($woofood_options['woofood_enable_time_to_pickup_option']) ? $woofood_options['woofood_enable_time_to_pickup_option'] : null ;
  $woofood_options_multistore = get_option('woofood_options_multistore');
  $woofood_auto_store_select = isset($woofood_options_multistore['woofood_auto_store_select']) ?  $woofood_options_multistore['woofood_auto_store_select'] : null;
  if ($woofood_enable_time_to_deliver_option && !$woofood_auto_store_select ) {
//add doorbell checkout field


 add_action( 'woocommerce_after_checkout_form', 'woofood_add_delivery_hours_for_each_store', 90);
 
function woofood_add_delivery_hours_for_each_store() {
 $woofood_options = get_option('woofood_options');
  $woofood_delivery_time = intval($woofood_options['woofood_delivery_time']);
      $woofood_disable_now_from_time = $woofood_options['woofood_disable_now_from_time'];
      $woofood_enable_asap_on_time = $woofood_options['woofood_enable_asap_on_time'];
      $woofood_break_down_times_every = $woofood_options['woofood_break_down_times_every'];

      $woofood_enable_maximum_orders_delivery_timeslot = isset($woofood_options['woofood_enable_maximum_orders_delivery_timeslot']) ? $woofood_options['woofood_enable_maximum_orders_delivery_timeslot']: null  ;
      $woofood_maximum_orders_delivery_timeslot = $woofood_options['woofood_maximum_orders_delivery_timeslot'] ? intval($woofood_options['woofood_maximum_orders_delivery_timeslot']) : 0;

      if(!$woofood_break_down_times_every)
      {
        $woofood_break_down_times_every =  "30";
      }

      $default_time_format = get_option( 'time_format' );
  if(!$default_time_format)
  {
    $default_time_format = "H:i";
    
  }


/*$delivery_hours_per_store = array();
$delivery_stores = woofood_get_delivery_stores();


foreach($delivery_stores as $store_id => $store_name)
{


                $time_to_delivery_options = "";


                $today_delivery_hours = woofood_check_if_store_within_delivery_hours($store_id, true);
                $current_time = current_time($default_time_format);

                if($woofood_delivery_time > 0)
                {
                $current_time = strtotime("+".$woofood_delivery_time." minutes", strtotime($current_time));
                $current_time = date($default_time_format, $current_time);
                }
                else
                {

                }
                if(!$woofood_disable_now_from_time)
                {
                  $time_to_delivery_options .='<option value="now">'.esc_html__('Now', 'woofood-plugin').'</option>';
                }

                if($woofood_enable_asap_on_time)
                {
                $time_to_delivery_options .='<option value="asap">'.esc_html__('ASAP', 'woofood-plugin').'</option>';

                }

                if(is_array($today_delivery_hours))
                {
                foreach($today_delivery_hours as $time)
                {

                $period = new DatePeriod(
                new DateTime($time["start"]),
                new DateInterval('PT'.$woofood_break_down_times_every .'M'),
                new DateTime($time["end"])
                );



                foreach ($period as $date) {
                if(strtotime($current_time) < strtotime($date->format($default_time_format)) )
                { 
                if($woofood_maximum_orders_delivery_timeslot && $woofood_maximum_orders_delivery_timeslot > 0)
                {
                if(woofood_get_orders_count("delivery", current_time("Y-m-d"), $date->format($default_time_format)) < $woofood_maximum_orders_delivery_timeslot)
                {

                                $time_to_delivery_options .='<option value="'.$date->format($default_time_format).'">'.date_i18n( $default_time_format, strtotime($date->format($default_time_format) ) ).'</option>';


                }

                }
                else
                {
                                $time_to_delivery_options .='<option value="'.$date->format($default_time_format).'">'.date_i18n( $default_time_format, strtotime($date->format($default_time_format) ) ).'</option>';

                }



                //  $time_to_delivery_options[$date->format($default_time_format)] = $date->format($default_time_format);


                }


                }


                }

                }
                $delivery_hours_per_store[$store_id] =   addslashes($time_to_delivery_options);


} //enforeach //*/


//$delivery_hours_encoded = json_encode($delivery_hours_per_store);


?>
<script>
 

 jQuery(document).ready(function () {


if ( jQuery( "#woofood_date_to_deliver" ).length ) {
      
       wooofood_get_delivery_days_from_store();
     }
if (!jQuery( "#woofood_date_to_deliver" ).length && jQuery( "#woofood_time_to_deliver" ).length ) {

              wooofood_get_delivery_hours_for_day_from_store();


}



  var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();
 var woofood_delivery_store_selected = parseInt(jQuery('#extra_store_name option:selected').val());
//jQuery('#woofood_time_to_pickup').html(pickup_hours_per_store_array[woofood_pickup_store_selected]);



    




    jQuery(document).on('change', '#extra_store_name', function (){

     var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();
 var woofood_delivery_store_selected = parseInt(jQuery('#extra_store_name option:selected').val());
//jQuery('#woofood_time_to_pickup').html(pickup_hours_per_store_array[woofood_pickup_store_selected]);






if ( jQuery( "#woofood_date_to_deliver" ).length ) {


       wooofood_get_delivery_days_from_store();


}

if (!jQuery( "#woofood_date_to_deliver" ).length && jQuery( "#woofood_time_to_deliver" ).length ) {

              wooofood_get_delivery_hours_for_day_from_store();


}










    

        return false;
    });



jQuery(document).on('change', '#woofood_date_to_deliver', function (){


        jQuery( "#wf-date-to-deliver.delivery" ).addClass("disabled");



            wooofood_get_delivery_hours_for_day_from_store();


    

        return false;
    });


function wooofood_get_delivery_days_from_store()
{
        jQuery( "#wf-date-to-deliver.delivery" ).addClass("disabled");
 var woofood_delivery_store_selected = parseInt(jQuery('#extra_store_name option:selected').val());

  var data = {
      'action': 'woofood_multistore_get_delivery_days',
      'store_id': woofood_delivery_store_selected
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(woofoodmain.ajaxurl, data, function(response) {
      jQuery( "#woofood_date_to_deliver" ).html(response);
            wooofood_get_delivery_hours_for_day_from_store();

      jQuery( "#wf-date-to-deliver.delivery" ).removeClass("disabled");

    });
}


function wooofood_get_delivery_hours_for_day_from_store()
{
  var date = null;
  if(jQuery( "#woofood_date_to_deliver" ).length)
  {
      var date = jQuery( "#woofood_date_to_deliver" ).val();

  }
        jQuery( "#wf-time-to-deliver.delivery" ).addClass("disabled");
 var woofood_delivery_store_selected = parseInt(jQuery('#extra_store_name option:selected').val());

  var data = {
      'action': 'woofood_multistore_get_delivery_hours_for_day',
      'store_id': woofood_delivery_store_selected,
      'date': date
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(woofoodmain.ajaxurl, data, function(response) {
      jQuery( "#woofood_time_to_deliver" ).html(response);
              jQuery( "#wf-time-to-deliver.delivery" ).removeClass("disabled");
                      jQuery( "#wf-date-to-deliver.delivery" ).removeClass("disabled");


    });
}









});

</script>

<?php

}   



 }






 if ($woofood_enable_pickup_option && $woofood_enable_time_to_pickup_option) {
//add doorbell checkout field


 add_action( 'woocommerce_after_checkout_form', 'woofood_add_pickup_hours_for_each_store', 90);
 
function woofood_add_pickup_hours_for_each_store() {
  $woofood_options = get_option('woofood_options');
  $woofood_pickup_time = intval($woofood_options['woofood_pickup_time']);
  $woofood_disable_now_from_pickup_time = $woofood_options['woofood_disable_now_from_pickup_time'];
  $woofood_enable_asap_on_pickup_time = $woofood_options['woofood_enable_asap_on_pickup_time'];
  $woofood_break_down_pickup_times_every = $woofood_options['woofood_break_down_pickup_times_every'];
  $woofood_enable_date_to_pickup_option = isset($woofood_options['woofood_enable_date_to_pickup_option']) ? $woofood_options['woofood_enable_date_to_pickup_option'] : null ;


  $woofood_enable_maximum_orders_pickup_timeslot = isset($woofood_options['woofood_enable_maximum_orders_pickup_timeslot']) ? $woofood_options['woofood_enable_maximum_orders_pickup_timeslot'] : null ;
  $woofood_maximum_orders_pickup_timeslot = isset($woofood_options['woofood_maximum_orders_pickup_timeslot']) ? intval($woofood_options['woofood_maximum_orders_pickup_timeslot']) : 0 ;






/*
            if(!$woofood_break_down_pickup_times_every)
      {
        $woofood_break_down_pickup_times_every =  "30";
      }

        $default_time_format = get_option( 'time_format' );
  if(!$default_time_format)
  {
    $default_time_format = "H:i";
    
  }


$pickup_hours_per_store = array();
$pickup_stores = woofood_get_pickup_stores();


foreach($pickup_stores as $store_id => $store_name)
{


                $time_to_pickup_options = "";


                $today_pickup_hours = woofood_check_if_store_within_pickup_hours($store_id, true);
                $current_time = current_time($default_time_format);

                if($woofood_pickup_time > 0)
                {
                $current_time = strtotime("+".$woofood_pickup_time." minutes", strtotime($current_time));
                $current_time = date($default_time_format, $current_time);
                }
                else
                {

                }
                if(!$woofood_disable_now_from_pickup_time)
                {
                  $time_to_pickup_options .='<option value="now">'.esc_html__('Now', 'woofood-plugin').'</option>';
                }

                if($woofood_enable_asap_on_pickup_time)
                {
                $time_to_pickup_options .='<option value="asap">'.esc_html__('ASAP', 'woofood-plugin').'</option>';

                }

                if(is_array($today_pickup_hours))
                {
                foreach($today_pickup_hours as $time)
                {

                $period = new DatePeriod(
                new DateTime($time["start"]),
                new DateInterval('PT'.$woofood_break_down_pickup_times_every .'M'),
                new DateTime($time["end"])
                );



                foreach ($period as $date) {
                if(strtotime($current_time) < strtotime($date->format($default_time_format)) )
                { 
                if($woofood_enable_maximum_orders_pickup_timeslot && $woofood_maximum_orders_pickup_timeslot > 0)
                {
                if(woofood_get_orders_count("delivery", current_time("Y-m-d"), $date->format($default_time_format)) < $woofood_maximum_orders_pickup_timeslot)
                {

                                $time_to_pickup_options .='<option value="'.$date->format($default_time_format).'">'.date_i18n( $default_time_format, strtotime($date->format($default_time_format) ) ).'</option>';


                }

                }
                else
                {
                                $time_to_pickup_options .='<option value="'.$date->format($default_time_format).'">'.date_i18n( $default_time_format, strtotime($date->format($default_time_format) ) ).'</option>';

                }



                //  $time_to_delivery_options[$date->format($default_time_format)] = $date->format($default_time_format);


                }


                }


                }

                }
                $pickup_hours_per_store[$store_id] =   addslashes($time_to_pickup_options);


} //enforeach //*/


//$pickup_hours_encoded = json_encode($pickup_hours_per_store);


?>
<script>
 //var pickup_hours_per_store   = '<?php  $pickup_hours_encoded; ?>';
 //var pickup_hours_per_store_array = JSON.parse(pickup_hours_per_store);
jQuery(document).ready(function () {


if ( jQuery( "#woofood_date_to_pickup" ).length ) {
      
       wooofood_get_pickup_days_from_store();
     }
if (!jQuery( "#woofood_date_to_pickup" ).length && jQuery( "#woofood_time_to_pickup" ).length ) {

              wooofood_get_pickup_hours_for_day_from_store();


}



  var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();
 var woofood_pickup_store_selected = parseInt(jQuery('#extra_store_name_pickup option:selected').val());
//jQuery('#woofood_time_to_pickup').html(pickup_hours_per_store_array[woofood_pickup_store_selected]);



    




    jQuery(document).on('change', '#extra_store_name_pickup', function (){

     var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();
 var woofood_pickup_store_selected = parseInt(jQuery('#extra_store_name_pickup option:selected').val());
//jQuery('#woofood_time_to_pickup').html(pickup_hours_per_store_array[woofood_pickup_store_selected]);






if ( jQuery( "#woofood_date_to_pickup" ).length ) {


       wooofood_get_pickup_days_from_store();


}

if (!jQuery( "#woofood_date_to_pickup" ).length && jQuery( "#woofood_time_to_pickup" ).length ) {

              wooofood_get_pickup_hours_for_day_from_store();


}










    

        return false;
    });



jQuery(document).on('change', '#woofood_date_to_pickup', function (){


        jQuery( "#wf-date-to-deliver.pickup" ).addClass("disabled");



            wooofood_get_pickup_hours_for_day_from_store();


    

        return false;
    });


function wooofood_get_pickup_days_from_store()
{
        jQuery( "#wf-date-to-deliver.pickup" ).addClass("disabled");
 var woofood_pickup_store_selected = parseInt(jQuery('#extra_store_name_pickup option:selected').val());

  var data = {
      'action': 'woofood_multistore_get_pickup_days',
      'store_id': woofood_pickup_store_selected
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(woofoodmain.ajaxurl, data, function(response) {
      jQuery( "#woofood_date_to_pickup" ).html(response);
            wooofood_get_pickup_hours_for_day_from_store();

      jQuery( "#wf-date-to-deliver.pickup" ).removeClass("disabled");

    });
}


function wooofood_get_pickup_hours_for_day_from_store()
{
  var date = null;
  if(jQuery( "#woofood_date_to_pickup" ).length)
  {
      var date = jQuery( "#woofood_date_to_pickup" ).val();

  }
        jQuery( "#wf-time-to-deliver.pickup" ).addClass("disabled");
 var woofood_pickup_store_selected = parseInt(jQuery('#extra_store_name_pickup option:selected').val());

  var data = {
      'action': 'woofood_multistore_get_pickup_hours_for_day',
      'store_id': woofood_pickup_store_selected,
      'date': date
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(woofoodmain.ajaxurl, data, function(response) {
      jQuery( "#woofood_time_to_pickup" ).html(response);
              jQuery( "#wf-time-to-deliver.pickup" ).removeClass("disabled");
                      jQuery( "#wf-date-to-deliver.pickup" ).removeClass("disabled");


    });
}









});

</script>

<?php









}
















 add_action( 'woocommerce_after_checkout_form', 'woofood_multistore_store_change_script', 110);


function woofood_multistore_store_change_script()
{

  ?>
  <script>


    jQuery(document).on('change', '#extra_store_name_pickup', function (){


 var woofood_pickup_store_selected = parseInt(jQuery('#extra_store_name_pickup option:selected').val());

var exists = false; 
jQuery('#extra_store_name  option').each(function(){
  if (this.value == woofood_pickup_store_selected) {
    exists = true;
  }
});
if(exists)
{  jQuery('#extra_store_name').val(woofood_pickup_store_selected);



}
    

        return false;
    });




    jQuery(document).on('change', '#extra_store_name', function (){


 var woofood_store_selected = parseInt(jQuery('#extra_store_name option:selected').val());

var exists = false; 
jQuery('#extra_store_name  option').each(function(){
  if (this.value == woofood_store_selected) {
    exists = true;
  }
});
if(exists)
{  jQuery('#extra_store_name_pickup').val(woofood_store_selected);



}
    

        return false;
    });

</script>

  <?php

}


 }



?>