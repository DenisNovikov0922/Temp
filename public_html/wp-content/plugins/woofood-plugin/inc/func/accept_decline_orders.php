<?php
$options_woofood = get_option('woofood_options');

$woofood_enable_order_accepting = isset($options_woofood['woofood_enable_order_accepting']) ? $options_woofood['woofood_enable_order_accepting']: null;
$woofood_declined_page = isset($options_woofood['woofood_declined_page']) ? $options_woofood['woofood_declined_page']: null;


/*if delivery time has been set*/

if($woofood_enable_order_accepting!=0 ) {
//add new order status accepting//
if($woofood_declined_page!="" && $woofood_declined_page!=0 )
{
  add_action( 'woocommerce_thankyou', 'woofood_decline_order_redirect', 10, 1);

}


function action_woocommerce_before_pay_action( $order ) { 

  if ( $order->get_status() == 'cancelled' ) {
    $url = $order->get_cancel_order_url_raw();
     wp_safe_redirect( $url);
        exit;
      }
}; 
         
// add the action 
//add_action( 'woocommerce_before_pay_action', 'action_woocommerce_before_pay_action', 10, 1 ); 
  
function woofood_decline_order_redirect( $order_id ){
  $options_woofood = get_option('woofood_options');
  $woofood_declined_page_id =  intval($options_woofood['woofood_declined_page']);

  
    $order = wc_get_order( $order_id );
    
    $url = get_page_link($woofood_declined_page_id);
  
    if ( $order->get_status() == 'cancelled' ) {

 /*   $url = $order->get_cancel_order_url_raw();
     wp_safe_redirect( $url);
        exit;*/

        wp_safe_redirect( $url );
        exit;

    }
}

function register_accepting_order_status() {
    register_post_status( 'wc-accepting', array(
        'label'                     => 'Accepting',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Accepting Order <span class="count">(%s)</span>', 'Accepting Order <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_accepting_order_status' );
// Add to list of WC Order statuses
function add_accepting_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    // add new order status after processing
        $new_order_statuses['wc-accepting']= 'accepting';

    foreach ( $order_statuses as $key => $status ) {

            $new_order_statuses[$key] = $status;
        //if ( 'wc-processing' === $key ) {
            
        //}
    }
    // $new_order_statuses['wc-accepting'] = 'Accepting';

    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_accepting_to_order_statuses' );






function woofood_check_if_accepted_new($order_id)
{
  global $woocommerce;
  $order= wc_get_order($order_id);
   if($order->get_status()=="accepting")
           {
            sleep(2);
            woofood_check_if_accepted_new($order_id);

           } 
           else if($order->get_status()=="processing")
           {
              

           }
          unset($order); 

}

//add_action( 'woocommerce_new_order', 'wf_update_order_status' );
 
function wf_update_order_status( $order_id ) {
 
    $order = wc_get_order( $order_id );
 
   $order->update_status('accepting');
 

 
  }
//add_action( 'woocommerce_order_status_processing', 'woofood_accepting_to_processing_order', 20, 2 );


  function woofood_accepting_to_processing_order($order_id, $order)
  {

    $wc_emails = WC()->mailer()->get_emails();

      

    foreach ( $wc_emails as $wc_mail )
    {

        if ( $wc_mail->id == "customer_processing_order" )
        {

            $wc_mail->trigger( $order_id );
        }
    }
  }






  add_action('woocommerce_order_status_changed', 'woofood_accepting_to_processing_email', 10, 4);
function woofood_accepting_to_processing_email( $order_id, $from_status, $to_status, $order ) {

   if( $from_status =="accepting" && $to_status =="processing") {

         $wc_emails = WC()->mailer()->get_emails();

      

    foreach ( $wc_emails as $wc_mail )
    {

        if ( $wc_mail->id == "customer_processing_order" || $wc_mail->id == "new_order" )
        {

            $wc_mail->trigger( $order_id );
        }
    }



    }

}


//add_filter( 'woocommerce_cod_process_payment_order_status', 'change_cod_payment_order_status', 10, 2 );
function change_cod_payment_order_status( $order_status, $order ) {
    return 'accepting';
}



////////regsiter woofood checkout.min.js/////////////

add_filter('woocommerce_default_order_status', 'woofood_change_to_accepting', 10, 1);


function woofood_change_to_accepting($pending)
{
  return "accepting";
}





}//end if
//add new order status accepting//


function woofood_check_order_status() {




    global $woocommerce;

    $order_id = $_POST['order_id'];
    $order = wc_get_order( $order_id );

    if ( $order->has_status( 'processing' ) ) {

        $minutes_to_arrive = get_post_meta($order_id, 'minutes_to_arrive', true);
        $message = '<h1 class="title is-4">'.esc_html__('Your Order has been accepted!','woofood-plugin').'</h1><p class="is-size-3">'.esc_html__('You will receive your order in approximately','woofood-plugin').' <strong>'.$minutes_to_arrive.' '.__('minutes', 'woofood-plugin').'</strong> </p>';

        $response_array = array('minutes_to_arrive'=>$minutes_to_arrive, 'status'=>'processing', 'message'=> $message );
        wp_send_json($response_array);
    }
    else if ( $order->has_status( 'accepting' ) ) 
    {
        $message = '<h1 class="title is-4">'.esc_html__('Your Order is waiting for acceptance !','woofood-plugin').'</h1>';
          $response_array = array( 'status'=>'accepting', 'message'=> $message );

                       wp_send_json($response_array);




    }
    else if ( $order->has_status( 'cancelled' ) ) 
    {
         $message = '<h1 class="title is-4">'.esc_html__('Your Order has been declined!','woofood-plugin').'</h1>';
          $response_array = array('status'=>'cancelled', 'message'=> $message );

                     wp_send_json($response_array);



    }
       wp_die();


}
add_action('wp_ajax_woofood_check_order_status', 'woofood_check_order_status');
add_action('wp_ajax_nopriv_woofood_check_order_status', 'woofood_check_order_status');










add_action( 'wp_enqueue_scripts', 'woofood_enqueue_scripts_for_frontend', 99 );
function woofood_enqueue_scripts_for_frontend(){
 // wp_deregister_script('wc-checkout');
  //wp_register_script('wc-checkout',  WOOFOOD_PLUGIN_URL. "js/checkout.min.js", 
   wp_register_script('woofood-checkout-hooks',  WOOFOOD_PLUGIN_URL. "js/checkout-hooks.js", 

        array( 'jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n' ), WOOFOOD_PLUGIN_VERSION, TRUE);

   wp_enqueue_script('woofood-checkout-hooks');

    // wp_localize_script('wc-checkout', 'woofoodcheckout', array( 
         //   'ajaxurl' => admin_url( 'admin-ajax.php' )
        //  ));
     
  if (is_checkout() )
  {
       // Checkout Page        
        //define('WOOCOMMERCE_CHECKOUT', TRUE);
        
      // wp_enqueue_script('wc-checkout');


      //  wp_enqueue_script( 'woocommerce_frontend_styles' );

      //  apply_filters( 'woocommerce_is_checkout', true );



  }

     

  
}

////////regsiter woofood checkout.min.js/////////////




add_action('wp_footer', 'woofood_loading_to_footer');
function woofood_loading_to_footer() 
{

    if(is_checkout()) {
    ?>
  <div class="woofood-loading">
    <div class="loading-content">

     <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
   <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
</svg>
            <div class='loading-text'>
            <?php esc_html_e('Please wait while your order is getting accepted by restaurant!', 'woofood-plugin'); ?>
            </div>

      </div>
    </div>
  <?php
}


}


//add_filter('woocommerce_valid_order_statuses_for_payment_complete', 'woofood_remove_statuses_to_payment_complete');
function woofood_remove_statuses_to_payment_complete($statuses)
{
   
$statuses = array_diff($statuses, "cancelled");
$statuses = array_diff($statuses, "pending");

return $statuses;
}



function woofood_add_accepting_to_payment_complete($statuses)
{
   

$statuses[] = "accepting";
return $statuses;
}





function woofood_check_if_restaurant_is_disabled($order_id) { 

  global $woocommerce;

          $options_woofood = get_option('woofood_options');
          $order = wc_get_order($order_id);

$woofood_enable_order_accepting = $options_woofood['woofood_enable_order_accepting'];
$woofood_declined_page =  $options_woofood['woofood_declined_page'];
$woofood_disable_accept_decline_if_time_selected = $options_woofood['woofood_disable_accept_decline_if_time_selected'];
$woofood_time_to_deliver = isset($_POST["woofood_time_to_deliver"]) ? $_POST["woofood_time_to_deliver"] :null;
$woofood_order_type = isset($_POST["woofood_order_type"]) ? $_POST["woofood_order_type"] : woofood_get_default_order_type();
$woofood_disable_accept_decline_if_time_selected_restaurant_closed = $options_woofood['woofood_disable_accept_decline_if_time_selected_restaurant_closed'];

if($woofood_order_type =="pickup")
{
  $woofood_time_to_deliver= isset($_POST["woofood_time_to_pickup"]) ? $_POST["woofood_time_to_pickup"] :null;

}


if($woofood_time_to_deliver)
{
  if($woofood_time_to_deliver!=="now" && $woofood_time_to_deliver!=="asap" && $woofood_disable_accept_decline_if_time_selected_restaurant_closed!=0 )
  {
    if($woofood_order_type === "delivery" && (woofood_check_if_within_delivery_hours(false, true) === false))
    {   
        remove_filter('woocommerce_default_order_status', 'woofood_change_to_accepting', 10, 1);

          remove_action( 'woocommerce_checkout_order_processed', 'woofood_check_if_accepted', 0, 1);


    }
    if($woofood_order_type === "pickup" && (woofood_check_if_within_pickup_hours(false, true) === false))
    {
              remove_filter('woocommerce_default_order_status', 'woofood_change_to_accepting', 10, 1);

              remove_action( 'woocommerce_checkout_order_processed', 'woofood_check_if_accepted', 0, 1);


    }

  }

  if($woofood_time_to_deliver!=="now" && $woofood_time_to_deliver!=="asap" && $woofood_disable_accept_decline_if_time_selected!=0 )
  {
    if($woofood_order_type === "delivery")
    {   
        remove_filter('woocommerce_default_order_status', 'woofood_change_to_accepting', 10, 1);

          remove_action( 'woocommerce_checkout_order_processed', 'woofood_check_if_accepted', 0, 1);


    }
    if($woofood_order_type === "pickup")
    {
              remove_filter('woocommerce_default_order_status', 'woofood_change_to_accepting', 10, 1);

              remove_action( 'woocommerce_checkout_order_processed', 'woofood_check_if_accepted', 0, 1);


    }

  }
}




    // make action magic happen here... 
}
         
// add the action 
add_action( 'woocommerce_checkout_process', 'woofood_check_if_restaurant_is_disabled', 10, 1 ); 








add_action( 'woocommerce_checkout_order_processed', 'woofood_check_if_accepted', 0, 1);
//add_action( 'woocommerce_new_order', 'woofood_check_if_accepted', 99, 1);


        function woofood_check_if_accepted($order_id)
        { 
          $order_needs_payment = false;

          $options_woofood = get_option('woofood_options');
          $order = wc_get_order($order_id);

$woofood_enable_order_accepting = $options_woofood['woofood_enable_order_accepting'];
$woofood_declined_page =  $options_woofood['woofood_declined_page'];
$woofood_disable_accept_decline_if_time_selected = $options_woofood['woofood_disable_accept_decline_if_time_selected'];
$woofood_time_to_deliver = get_post_meta($order_id , 'woofood_time_to_deliver', true); 
$woofood_order_type = get_post_meta($order_id , 'woofood_order_type', true); 

if($woofood_time_to_deliver)
{
  if($woofood_time_to_deliver!=="now" && $woofood_time_to_deliver!=="asap" && $woofood_disable_accept_decline_if_time_selected!=0)
  {
  	if($woofood_order_type === "delivery" && (woofood_check_if_within_delivery_hours(false, true) === false))
  	{
  		    $woofood_enable_order_accepting = 0;
  		    remove_action( 'woocommerce_checkout_order_processed', 'woofood_check_if_accepted', 0, 1);


  	}
  	if($woofood_order_type === "pickup" && (woofood_check_if_within_pickup_hours(false, true) === false))
  	{
  		    $woofood_enable_order_accepting = 0;
  		    remove_action( 'woocommerce_checkout_order_processed', 'woofood_check_if_accepted', 0, 1);


  	}

  }
}

 if($woofood_enable_order_accepting!=0)
{
  
   

  do_action( 'woofood_check_if_order_is_accepted_loop', $order_id, $order_needs_payment);
}

       
if ( $order->get_payment_method() !="cod" ) {
                    $order_needs_payment = true;
                    update_post_meta($order_id, 'woofood_order_need_payment', true);
                  }

 // do_action( 'woofood_check_if_order_is_accepted_loop', $order_id, $order_needs_payment );

 
}

    /*      $order = wc_get_order($order_id);

        

          $order_needs_payment = false;
            
                  


          $options_woofood = get_option('woofood_options');

$woofood_enable_order_accepting = $options_woofood['woofood_enable_order_accepting'];
$woofood_declined_page =  $options_woofood['woofood_declined_page'];

 if($woofood_enable_order_accepting!=0)
{
   //$order->set_status('accepting', '', true);

  //do_action( 'woofood_check_if_order_is_accepted_loop', $order_id);
}*/



        

                  /*  throw new Exception(  $order->get_status());
print_r($posted_data);*/
      


           

        



              


             
  

       

                                   





 




                

           


     


            




        


add_action('woofood_check_if_order_is_accepted_loop', 'woofood_check_if_order_is_accepted_loop', 10, 2);
        function woofood_check_if_order_is_accepted_loop($order_id, $order_needs_payment)
        {
          $decoded = null;
          /*if( ini_get('allow_url_fopen')) {


          $postdata = http_build_query(
    array(
        'order_id' => $order_id,
        'action' => 'woofood_check_order_status'
    )
);

$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postdata
    ),
     'ssl' => array(
            'allow_self_signed'=> true,
                 "verify_peer"=>false,
        "verify_peer_name"=>false,
        )
);

$context  = stream_context_create($opts);

$result = file_get_contents(admin_url('admin-ajax.php'), false, $context);

$decoded = json_decode($result);
}
else
{
  $url = admin_url('admin-ajax.php');
$myvars = 'order_id=' . $order_id . '&action=woofood_check_order_status';

$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec( $ch );
$decoded = json_decode($response);


}

*/

$response = wp_remote_post( 
        admin_url( 'admin-ajax.php' ), 
        array(
          'method' => 'POST',
            'body' => array(
                'action' => 'woofood_check_order_status',
                'order_id'   => $order_id,
            ),
            'headers'     => array(
        'User-Agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13'
    ),
              'timeout'     => 60,
    'redirection' => 5,
    'blocking'    => true,
    'sslverify'   => false,

        ) 
    );

$decoded = json_decode($response["body"]);


          $order = wc_get_order($order_id);


          if($decoded->status=="accepting")
{

                        
  sleep(5);
                        do_action('woofood_check_if_order_is_accepted_loop',$order_id, $order_needs_payment);

                        


               
             }
             else if($decoded->status =="cancelled")
             {
              add_filter('woocommerce_cart_needs_payment', '__return_false');


             }
                   


            else {

                //remove_action( 'woofood_check_if_order_is_accepted_loop', $order_id, $order_needs_payment );
add_filter('woocommerce_valid_order_statuses_for_payment_complete', 'woofood_add_accepting_to_payment_complete', 99, 1);

          




             }


        }

        








?>