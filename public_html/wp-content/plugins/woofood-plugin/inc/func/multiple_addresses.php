<?php
//add address changer modal//
function wf_address_change_modal_old() {
// only show the registration/login form to non-logged-in members
  if( is_user_logged_in() ){ 
    $user = wp_get_current_user();
    $user_id = $user->ID;
    $current_first_name =   get_user_meta($user_id, 'billing_first_name', true);  
    $current_last_name =  get_user_meta($user_id, 'billing_last_name', true); 
    $current_billing_address_1 =  get_user_meta($user_id, 'billing_address_1', true); 
    $current_billing_city =   get_user_meta($user_id, 'billing_city', true);  
    $current_billing_country =  get_user_meta($user_id, 'billing_country', true); 
    $current_billing_postcode =   get_user_meta($user_id, 'billing_postcode', true);  
    $current_doorbell = get_user_meta($user_id, 'doorbell', true);  
    $previously_stored_addresses = get_user_meta($user_id, 'previously_stored_addresses', true);  
    ?>
    <div class="wf_address_changer_modal">
      <div class="dialog" data-active-tab="">
        <div class="content">
          <div class="body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>


            <!-- Address Edit form -->
            <div class="wf_address_changer_edit">

              <h3><?php esc_html_e('Change your Address','woofood-plugin'); ?></h3>
              <hr>

              <form id="pt_address_form" action="<?php echo home_url( '/' ); ?>" method="POST">

                <?php if (!empty($previously_stored_addresses)) { ?>
                <div class="wf_address_field">
                  <select name="previously_address" id="previously_address" class="wf_address_input" data-placeholder="<?php esc_html_e('Previously Used Addresses','woofood-plugin'); ?>">
                                    <option value=''><?php esc_html_e('Select Address','woofood-plugin') ?></option>


                    <?php foreach($previously_stored_addresses as $current_address)
                    {
                      if (array_key_exists("billing_address_1", $current_address) && !empty($current_address['billing_address_1']) )
                      {
                        
                   
                      ?>

                      <option value='<?php echo json_encode($current_address); ?>'><?php echo $current_address['billing_address_1'].",".$current_address['billing_city'].",".$current_address['billing_postcode'].",".$current_address['billing_country'] ?></option>

                      <?php
   }

                    }
                    ?>


                  </select>
                </div>  
                <?php } ?>

                <div class="wf_address_field">
                  <input class="wf_address_input" placeholder="<?php esc_html_e('First Name','woofood-plugin'); ?>" name="first_name" type="text" value="<?php echo $current_first_name; ?>"/>
                </div>
                <div class="wf_address_field">
                  <input class="wf_address_input" placeholder="<?php esc_html_e('Last Name','woofood-plugin'); ?>" name="last_name" type="text" value="<?php echo $current_last_name; ?>"/>
                </div>

                <div class="wf_address_field">
                  <input type="text" name="billing_address_1" id="billing_address_1"  class="wf_address_input" placeholder="<?php esc_html_e('Address','woofood-plugin'); ?>" value="<?php echo $current_billing_address_1; ?>"  />
                </div>

                <div class="wf_address_field">
                  <input class="wf_address_input" placeholder="<?php esc_html_e('City','woofood-plugin'); ?>" name="billing_city" id="billing_city" type="text" value="<?php echo $current_billing_city; ?>"/>
                </div>

                <div class="wf_address_field">
                  <input class="wf_address_input" placeholder="<?php esc_html_e('Postal Code','woofood-plugin'); ?>" name="billing_postcode" id="billing_postcode" type="text" value="<?php echo $current_billing_postcode; ?>"/>
                </div>

                <?php
                $woofood_options = get_option('woofood_options');
                $woofood_enable_doorbell_option = $woofood_options['woofood_enable_doorbell_option'];
                if ($woofood_enable_doorbell_option){
                  ?>
                  <div class="wf_address_field">
                    <input class="wf_address_input" placeholder="<?php esc_html_e('Name on Doorbell','woofood-plugin'); ?>" name="doorbell" id="doorbell" type="text" value="<?php echo $current_doorbell; ?>"/>
                  </div>
                  <?php } ?>

                  <?php  
                  $countries_obj   = new WC_Countries();
                  $countries   = $countries_obj->get_allowed_countries();

                  woocommerce_form_field('billing_country', array(
                    'type'       => 'select',
                    'id'     =>'billing_country',
                    'class' => array(),
                    'placeholder'    => esc_html__('Select Country', 'woofood-plugin'),
                    'options'    => $countries,
                    ),
                  $current_billing_country
                  ); ?>
                  <div class="form-field">
                    <input type="hidden" name="action" value="wf_address_changer"/>
                    <button class="wf_address_changer_btn"  type="submit"><?php esc_html_e('Change Address', 'woofood-plugin'); ?></button>
                  </div>
                  <?php wp_nonce_field( 'ajax-login-nonce', 'register-security' ); ?>
                </form>
                <div class="wf-errors"></div>
              </div>



              <div class="wf_address_loading">
                <p><i class="fa fa-refresh fa-spin"></i><br><?php esc_html_e('Loading...', 'woofood-plugin') ?></p>
              </div>
            </div>
            <div class="wf_address_chnager_footer">

            </div>        
          </div>
        </div>
      </div>
      <?php
    }
  }
  add_action('wp_footer', 'wf_address_change_modal', 0);



function wf_address_change_modal()

{
  global $woocommerce;

    if( is_user_logged_in() ){ 
    $user = wp_get_current_user();
    $user_id = $user->ID;
    $current_first_name =   get_user_meta($user_id, 'billing_first_name', true);  
    $current_last_name =  get_user_meta($user_id, 'billing_last_name', true); 
    $current_billing_address_1 =  get_user_meta($user_id, 'billing_address_1', true); 
    $current_billing_city =   get_user_meta($user_id, 'billing_city', true);  
    $current_billing_country =  get_user_meta($user_id, 'billing_country', true); 
    $current_billing_postcode =   get_user_meta($user_id, 'billing_postcode', true);  
    $current_doorbell = get_user_meta($user_id, 'doorbell', true);  
    $previously_stored_addresses = get_user_meta($user_id, 'previously_stored_addresses', true);  

  }
  else
  {
     $current_first_name =  $woocommerce->customer->get_billing_first_name();
     $current_last_name =  $woocommerce->customer->get_billing_last_name();

     $current_billing_address_1 =  $woocommerce->customer->get_billing_address_1();
      $current_billing_city = $woocommerce->customer->get_billing_city();
      $current_billing_country = $woocommerce->customer->get_billing_country();
    $current_billing_postcode =   $woocommerce->customer->get_billing_postcode();

 $current_doorbell ="";


  }
  ?>



  <div class="modal micromodal-slide wf_address_change_modal" id="wf_address_change_modal" aria-hidden="true" >
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      
          <div class="content">
<div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-1-title">
          <?php esc_html_e('Change Address', 'woofood-plugin'); ?>
          </h2>
          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>

        <main class="modal__content" id="modal-1-content">


           <div class="wf_address_changer_edit">


              <form id="wf_address_form" action="<?php echo home_url( '/' ); ?>" method="POST">
                <?php  if( is_user_logged_in() ){ ?>
                <?php if (!empty($previously_stored_addresses)) { ?>
                <div class="wf_address_field">
                  <select name="previously_address" id="previously_address" class="wf_address_input" data-placeholder="<?php esc_html_e('Previously Used Addresses','woofood-plugin'); ?>">
                                    <option value=''><?php esc_html_e('Select Address','woofood-plugin') ?></option>


                    <?php foreach($previously_stored_addresses as $current_address)
                    {
                      if (array_key_exists("billing_address_1", $current_address) && !empty($current_address['billing_address_1']) )
                      {
                        
                   
                      ?>

                      <option value='<?php echo json_encode($current_address); ?>'><?php echo $current_address['billing_address_1'].",".$current_address['billing_city'].",".$current_address['billing_postcode'].",".$current_address['billing_country'] ?></option>

                      <?php
   }

                    }
                    ?>


                  </select>
                </div>  
                <?php } ?>
                <?php } ?>
<?php
$woofood_options = get_option('woofood_options');
  $woofood_enable_pickup_option = isset($woofood_options['woofood_enable_pickup_option']) ? $woofood_options['woofood_enable_pickup_option'] : null ;
  if ($woofood_enable_pickup_option){
  ?>

          <div class="woofood_order_type">
<?php 
  $default_order_type=woofood_get_default_order_type();

wf_form_field_radio( 'order_type', array(

'type'         => 'radio',

'class'         => array('wf_order_type_radio_50'),

'required'     => true,
'options'  => woofood_get_order_types(),

), $default_order_type); 

?>
</div>
<?php
}

else
{?>
<input type="hidden" name="order_type" value="delivery"/>
<?php 
}
?>

</div>


                <div class="wf_address_field">
                  <input class="wf_address_input" placeholder="<?php esc_html_e('First Name','woofood-plugin'); ?>" name="first_name" type="text" value="<?php echo $current_first_name; ?>"/>
                </div>
                <div class="wf_address_field">
                  <input class="wf_address_input" placeholder="<?php esc_html_e('Last Name','woofood-plugin'); ?>" name="last_name" type="text" value="<?php echo $current_last_name; ?>"/>
                </div>

                <div class="wf_address_field">
                  <input type="text" name="billing_address_1" id="billing_address_1"  class="wf_address_input" placeholder="<?php esc_html_e('Address','woofood-plugin'); ?>" value="<?php echo $current_billing_address_1; ?>"  />
                </div>

                <div class="wf_address_field">
                  <input class="wf_address_input" placeholder="<?php esc_html_e('City','woofood-plugin'); ?>" name="billing_city" id="billing_city" type="text" value="<?php echo $current_billing_city; ?>"/>
                </div>

                <div class="wf_address_field">
                  <input class="wf_address_input" placeholder="<?php esc_html_e('Postal Code','woofood-plugin'); ?>" name="billing_postcode" id="billing_postcode" type="text" value="<?php echo $current_billing_postcode; ?>"/>
                </div>

                <?php
                $woofood_options = get_option('woofood_options');
                $woofood_enable_doorbell_option = isset($woofood_options['woofood_enable_doorbell_option']) ? $woofood_options['woofood_enable_doorbell_option'] : null ;
                if ($woofood_enable_doorbell_option){
                  ?>
                  <div class="wf_address_field">
                    <input class="wf_address_input" placeholder="<?php esc_html_e('Name on Doorbell','woofood-plugin'); ?>" name="doorbell" id="doorbell" type="text" value="<?php echo $current_doorbell; ?>"/>
                  </div>
                  <?php } ?>

                  <?php  
                  $countries_obj   = new WC_Countries();
                  $countries   = $countries_obj->get_allowed_countries();

                  woocommerce_form_field('billing_country', array(
                    'type'       => 'select',
                    'id'     =>'billing_country',
                    'class' => array(),
                    'placeholder'    => esc_html__('Select Country', 'woofood-plugin'),
                    'options'    => $countries,
                    ),
                  $current_billing_country
                  ); ?>
                  <div class="form-field">
                    <input type="hidden" name="action" value="wf_address_changer"/>
                    <button class="wf_address_changer_btn"  type="submit"><?php esc_html_e('Change Address', 'woofood-plugin'); ?></button>
                  </div>
                  <?php wp_nonce_field( 'ajax-login-nonce', 'register-security' ); ?>
                </form>
                <div class="wf-errors"></div>
              </div>



              <div class="wf_address_loading">
                <p><i class="fa fa-refresh fa-spin"></i><br><?php esc_html_e('Loading...', 'woofood-plugin') ?></p>
              </div>
            </div>

       

</main>


</div>
</div>
       
      
  </div>
  </div>


  <?php



}









  function wf_address_changer(){
    global $woocommerce;
    $first_name   = $_POST['first_name'];
    $last_name    = $_POST['last_name'];
    $billing_address_1    = $_POST['billing_address_1'];
    $billing_city   = $_POST['billing_city'];
    $billing_postcode   = $_POST['billing_postcode'];
    $billing_country    = $_POST['billing_country'];
    $doorbell   = $_POST['doorbell'];
    $order_type    = $_POST['order_type'];

// Check CSRF token
/*    if( !check_ajax_referer( 'ajax-login-nonce', 'register-security', false) ){
      echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.__('Session token has expired, please reload the page and try again', 'woofood-plugin').'</div>'));
      die();
    }
*/


    


     if( is_user_logged_in() ){ 
    $user = wp_get_current_user();

    $user_id = $user->ID;

    $display_name = $first_name . " " . $last_name;
    wp_update_user(array('ID' => $user_id, 'display_name' => $display_name, 'first_name' => $first_name, 'last_name' => $last_name));

    update_user_meta( $user_id, 'billing_address_1', sanitize_text_field( $billing_address_1 ) );
    update_user_meta( $user_id, 'billing_city', sanitize_text_field( $billing_city ) );
    update_user_meta( $user_id, 'billing_country', sanitize_text_field( $billing_country ) );
    update_user_meta( $user_id, 'billing_postcode', sanitize_text_field( $billing_postcode ) );
    update_user_meta( $user_id, 'doorbell', sanitize_text_field( $doorbell ) );

    }

    else
    {
    $woocommerce->customer->set_billing_first_name( $first_name );
    $woocommerce->customer->set_billing_last_name( $last_name );

    $woocommerce->customer->set_billing_address_1( $billing_address_1 );
    $woocommerce->customer->set_billing_city( $billing_city );
    $woocommerce->customer->set_billing_postcode( $billing_postcode );
    $woocommerce->customer->set_billing_country( $billing_country );
 if (!class_exists("WooFood_Multistore_Settings"))
        {
   WC()->session->set( 'woofood_form_customer_address', $billing_address_1.",".$billing_city.",".$billing_postcode );

}
    }

  WC()->session->set( 'woofood_order_type', $order_type );


    $full_address  = sanitize_text_field( $billing_address_1 ).",".sanitize_text_field( $billing_city ).",".sanitize_text_field( $billing_postcode ).",".sanitize_text_field( $billing_country );

         if (class_exists("WooFood_Multistore_Settings"))
        {
          $available_stores =  wf_availability_checker_multi($full_address , $order_type);

          if($available_stores)
            {

             WC()->session->set( 'woofood_form_customer_address', $billing_address_1.",".$billing_city.",".$billing_postcode );

          if($available_stores["nearest_store_name"])
            {
           $nearest_store = $available_stores["nearest_store_name"];
                      $nearest_store_id = $available_stores["nearest_store_id"];

           if( is_user_logged_in() ){ 
    $user = wp_get_current_user();

    $user_id = $user->ID;
           update_user_meta( $user_id, 'store_near_user', $nearest_store  );
         }

         WC()->session->set( 'woofood_nearest_store', $nearest_store  );
         WC()->session->set( 'woofood_nearest_store_id', $nearest_store_id  );





             }

if($available_stores["nearest_store_id"])
            {
    WC()->session->set( 'woofood_nearest_store_id', $available_stores["nearest_store_id"] );

  }


          }

        }

      if( is_user_logged_in() ){ 
    $user = wp_get_current_user();

    $user_id = $user->ID;

    $previously_stored_addresses = array();

//save previously stored addressed//
    $previously_stored_addresses['billing_address_1'] = sanitize_text_field( $billing_address_1 );
    $previously_stored_addresses['billing_city'] = sanitize_text_field( $billing_city );
    $previously_stored_addresses['billing_country'] = sanitize_text_field( $billing_country );
    $previously_stored_addresses['billing_postcode'] = sanitize_text_field( $billing_postcode );
    $previously_stored_addresses['doorbell'] = sanitize_text_field( $doorbell );

    $pre_previously_stored_addresses = get_user_meta($user_id, 'previously_stored_addresses', true); 

     if(is_array($pre_previously_stored_addresses))
     {

     }
     else
     {
      $pre_previously_stored_addresses = array();

     }
    $pre_previously_stored_addresses[] =$previously_stored_addresses;
    $pre_previously_stored_addresses = array_map("unserialize", array_unique(array_map("serialize", $pre_previously_stored_addresses)));

    update_user_meta( $user_id, 'previously_stored_addresses',  $pre_previously_stored_addresses);

  }



    echo json_encode(array('error' => false,  'new_address'=>$billing_address_1.",".$billing_city.",".$billing_postcode, 'message' => '<div class="alert alert-success">'.esc_html__( 'Address has been Changed.', 'woofood-plugin').'<div>'));





    wp_die();

  }





   function wf_address_changer_new(){
    global $woocommerce;
    $first_name   = $_POST['first_name'];
    $last_name    = $_POST['last_name'];
    $billing_address_1    = $_POST['billing_address_1'];
    $billing_city   = $_POST['billing_city'];
    $billing_postcode   = $_POST['billing_postcode'];
    $billing_country    = $_POST['billing_country'];
    $doorbell   = $_POST['doorbell'];
    $order_type    = $_POST['order_type'];
    $full_address  = sanitize_text_field( $billing_address_1 ).",".sanitize_text_field( $billing_city ).",".sanitize_text_field( $billing_postcode ).",".sanitize_text_field( $billing_country );
    $availability = false;

// Check CSRF token
    if( !check_ajax_referer( 'ajax-login-nonce', 'register-security', false) ){
      echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.__('Session token has expired, please reload the page and try again', 'woofood-plugin').'</div>'));
      die();
    }



 if (!class_exists("WooFood_Multistore_Settings"))
        {


    $availability = true;



        }


 if (class_exists("WooFood_Multistore_Settings"))
        {
          $available_stores =  wf_availability_checker_multi($full_address , $order_type, $billing_postcode);

          if(!empty($available_stores))
            {

             WC()->session->set( 'woofood_form_customer_address', $billing_address_1.",".$billing_city.",".$billing_postcode );

          if($available_stores["nearest_store_name"])
            {
           $nearest_store = $available_stores["nearest_store_name"];
                      $nearest_store_id = $available_stores["nearest_store_id"];

           if( is_user_logged_in() ){ 
    $user = wp_get_current_user();

    $user_id = $user->ID;
           update_user_meta( $user_id, 'store_near_user', $nearest_store  );
         }

         WC()->session->set( 'woofood_nearest_store', $nearest_store  );
         WC()->session->set( 'woofood_nearest_store_id', $nearest_store_id  );





             }

if($available_stores["nearest_store_id"])
            {
    WC()->session->set( 'woofood_nearest_store_id', $available_stores["nearest_store_id"] );
    $availability = true;

  }


          }

        }


    


if($availability) {

     if( is_user_logged_in() ){ 
    $user = wp_get_current_user();

    $user_id = $user->ID;

    $display_name = $first_name . " " . $last_name;
    wp_update_user(array('ID' => $user_id, 'display_name' => $display_name, 'first_name' => $first_name, 'last_name' => $last_name));

    update_user_meta( $user_id, 'billing_address_1', sanitize_text_field( $billing_address_1 ) );
    update_user_meta( $user_id, 'billing_city', sanitize_text_field( $billing_city ) );
    update_user_meta( $user_id, 'billing_country', sanitize_text_field( $billing_country ) );
    update_user_meta( $user_id, 'billing_postcode', sanitize_text_field( $billing_postcode ) );
    update_user_meta( $user_id, 'doorbell', sanitize_text_field( $doorbell ) );



     $previously_stored_addresses = array();

//save previously stored addressed//
    $previously_stored_addresses['billing_address_1'] = sanitize_text_field( $billing_address_1 );
    $previously_stored_addresses['billing_city'] = sanitize_text_field( $billing_city );
    $previously_stored_addresses['billing_country'] = sanitize_text_field( $billing_country );
    $previously_stored_addresses['billing_postcode'] = sanitize_text_field( $billing_postcode );
    $previously_stored_addresses['doorbell'] = sanitize_text_field( $doorbell );

    $pre_previously_stored_addresses = get_user_meta($user_id, 'previously_stored_addresses', true); 

     if(is_array($pre_previously_stored_addresses))
     {

     }
     else
     {
      $pre_previously_stored_addresses = array();

     }
    $pre_previously_stored_addresses[] =$previously_stored_addresses;
    $pre_previously_stored_addresses = array_map("unserialize", array_unique(array_map("serialize", $pre_previously_stored_addresses)));

    update_user_meta( $user_id, 'previously_stored_addresses',  $pre_previously_stored_addresses);

    }

    else
    {
    $woocommerce->customer->set_billing_first_name( $first_name );
    $woocommerce->customer->set_billing_last_name( $last_name );

    $woocommerce->customer->set_billing_address_1( $billing_address_1 );
    $woocommerce->customer->set_billing_city( $billing_city );
    $woocommerce->customer->set_billing_postcode( $billing_postcode );
    $woocommerce->customer->set_billing_country( $billing_country );

    }

    WC()->session->set( 'woofood_order_type', $order_type );


    $redirect_script = '';
    $redirect_after_found =  apply_filters( "woofood_multistore_redirect_after_store_found", false );
    if($redirect_after_found)
    {            $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );

            $redirect_url = apply_filters( "woofood_multistore_redirect_url",  $shop_page_url, $available_stores );

         $redirect_script = '<script>window.location.href = "'.$redirect_url.'";</script>';

    }

    echo json_encode(array('error' => false, 'redirect_scrpt'=> $redirect_script, 'new_address'=>$billing_address_1.",".$billing_city.",".$billing_postcode, 'message' => '<div class="alert alert-success">'.esc_html__( 'Address has been Changed.', 'woofood-plugin').'<div>'));
  }
else
{
   echo json_encode(array('error' => true,  'new_address'=>$billing_address_1.",".$billing_city.",".$billing_postcode, 'message' => '<div class="alert alert-danger">'.$order_type." ".esc_html__( ' is not Available from this Address .', 'woofood-plugin').'<div>'));
}


         

 








    wp_die();

  }


  add_action('wp_ajax_wf_address_changer', 'wf_address_changer_new');
  add_action('wp_ajax_nopriv_wf_address_changer', 'wf_address_changer_new');


  function wf_address_changer_scripts() {
    $woofood_plugin_rtl = woofood_plugin_is_rtl();

    wp_enqueue_style( 'wf-addres-changer-css',WOOFOOD_PLUGIN_URL . 'css/wf_address_changer'.$woofood_plugin_rtl.'.css', array(), WOOFOOD_PLUGIN_VERSION, 'all' );

    wp_enqueue_script( 'wf-address-changer-script', WOOFOOD_PLUGIN_URL . 'js/wf_address_changer.js', array( 'jquery' ), WOOFOOD_PLUGIN_VERSION, 'all' );

    wp_localize_script('wf-address-changer-script', 'wfaddchangerajax', array( 
      'ajaxurl' => admin_url( 'admin-ajax.php' ),
      ));

  }
  add_action( 'wp_enqueue_scripts', 'wf_address_changer_scripts' );

  function wf_address_changer_shortcode(){


  global $woocommerce;
          if (class_exists("WooFood_Multistore_Settings"))
{
   $woofood_options_multistore = get_option('woofood_options_multistore');
   $woofood_auto_store_select = isset($woofood_options_multistore['woofood_auto_store_select']) ? $woofood_options_multistore['woofood_auto_store_select'] : null ;

  $nearest_store_session =  WC()->session->get( 'woofood_nearest_store');


  if( is_user_logged_in()) 
  {
    $user = wp_get_current_user();
  if(!$nearest_store_session)
  {
    $nearest_store_session = get_user_meta($user->ID, 'store_near_user', true);
  }
}

        if($woofood_auto_store_select && !empty($nearest_store_session))
        {
          echo '<div class="">';
          printf(esc_html__("The nearest store to your location is: %s", "woofood-plugin"), "<strong>".$nearest_store_session."</strong>");
          echo "</div>";

        }
if ( !is_user_logged_in()) {
      $billing_address_1 =  $woocommerce->customer->get_billing_address_1();
      $billing_city = $woocommerce->customer->get_billing_city();
      $billing_postcode = $woocommerce->customer->get_billing_postcode();
      $total_address = $billing_address_1.", ".$billing_city.", ".$billing_postcode;



     /* if (!empty($billing_address_1)){
        ?>

        <div class="address-change-header"><span class="wf_total_address_display"><b><?php esc_html_e('Address:', 'woofood-plugin');?></b><span class="wf_address_changer_value"><?php echo $total_address; ?></span></span><div class="pull-right"><a class="edit-address-icon button" id="wf_change_address" aria-hidden="true"><?php esc_html_e('Change Address', 'woofood-plugin'); ?></a></div></div>
        <?php
}//end if !empty
else {

  ?>
  <div class="address-change-header"><span class="wf_total_address_display"><b><?php esc_html_e('Note:', 'woofood-plugin'); ?></b><?php esc_html_e('You have not completed your address yet.','woofood-plugin'); ?></span><div class="pull-right"><a class="edit-address-icon button" aria-hidden="true" id="wf_change_address"><?php esc_html_e('Complete your Address', 'woofood-plugin'); ?></a></div></div>
  <?php 
}//end else*/



}//end if 



  }//if class MultiStore exists



    if ( is_user_logged_in()) {
      $user = wp_get_current_user();
      $total_address_elements = array(); 

      $billing_address_1 = get_user_meta($user->ID, 'billing_address_1', true);
      if(!empty(trim($billing_address_1)))
      {
        $total_address_elements[]= $billing_address_1;
      }
      $billing_city = get_user_meta($user->ID, 'billing_city', true);
        if(!empty(trim($billing_city)))
      {
        $total_address_elements[]= $billing_city;
      }
      $billing_postcode = get_user_meta($user->ID, 'billing_postcode', true);
        if(!empty(trim($billing_postcode)))
      {
        $total_address_elements[]= $billing_postcode;
      }


      $total_address = implode(",", $total_address_elements);

    }

    else
    {
      $total_address_elements = array(); 

        $billing_address_1  = $woocommerce->customer->get_billing_address_1( );
         if(!empty(trim($billing_address_1)))
      {
        $total_address_elements[]= $billing_address_1;
      }

    $billing_city =  $woocommerce->customer->get_billing_city( );
       if(!empty(trim($billing_city)))
      {
        $total_address_elements[]= $billing_city;
      }
   $billing_postcode  =  $woocommerce->customer->get_billing_postcode( );
    if(!empty(trim($billing_postcode)))
      {
        $total_address_elements[]= $billing_postcode;
      }
      $total_address = implode(",", $total_address_elements);

    }


      if (!empty($total_address)){
        ?>

        <div class="address-change-header"><span class="wf_total_address_display"><b><?php esc_html_e('Address:', 'woofood-plugin');?></b><span class="wf_address_changer_value"><?php echo $total_address; ?></span></span><div class="pull-right"><a class="edit-address-icon button" id="wf_change_address" aria-hidden="true"><?php esc_html_e('Change Address', 'woofood-plugin'); ?></a></div></div>
        <?php
}//end if !empty
else {

  ?>
  <div class="address-change-header"><span class="wf_total_address_display"><b><?php esc_html_e('Note:', 'woofood-plugin'); ?></b><?php esc_html_e('You have not completed your address yet.','woofood-plugin'); ?></span><div class="pull-right"><a class="edit-address-icon button" aria-hidden="true" id="wf_change_address"><?php esc_html_e('Complete your Address', 'woofood-plugin'); ?></a></div></div>
  <?php 
}//end else

}
add_shortcode('woofood_address_changer', 'wf_address_changer_shortcode');


function wf_add_address_change_to_mini_cart(){

 $woofood_options = get_option('woofood_options');
$woofood_disable_address_changer_option = isset($woofood_options['woofood_disable_address_changer_option']) ? $woofood_options['woofood_disable_address_changer_option'] : null;

  if(!$woofood_disable_address_changer_option)
  {
      echo do_shortcode('[woofood_address_changer]');

  }

}
add_action('woocommerce_before_mini_cart', 'wf_add_address_change_to_mini_cart');



add_action( 'woocommerce_init', 'wf_force_non_logged_session');

    function wf_force_non_logged_session()
{
    if (is_user_logged_in() || is_admin())
        return;
    if (isset(WC()->session)) {
        if (!WC()->session->has_session()) {
            WC()->session->set_customer_session_cookie(true);
       }
    }
}

  
   


?>