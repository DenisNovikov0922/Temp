<?php
/**
 * This PHP script helps you do the iframe checkout
 *
 */


/**
 * Put your API credentials here:
 * Get these from your API app details screen
 * https://stage.wepay.com/app
 */
class RM_Wepay_Checkout
{

    protected $msg = array();
    private $wepay;
    private $live_url;
    private $test_url;
     
    public function __construct(){
        $this->wepay= rm_wepay();
        $this->live_url          = 'https://www.wepay.com/min/js/iframe.wepay.js';
        $this->test_url          = 'https://stage.wepay.com/js/iframe.wepay.js';
    }
    
    function check_authorize_response(){
        if(!isset($_REQUEST['checkout_id'])){
            return;
        }
        
        global $wp; 
        $options= new RM_Wepay_Options();
        $p= get_the_ID();

        include_once dirname( __DIR__ ).'/wepay.php';
        $wepay_test_mode = $options->get_value_of('wepay_test_mode');
        $wepay_client_id = $options->get_value_of('wepay_client_id');                    
        $wepay_client_secret= $options->get_value_of('wepay_client_secret');
        $wepay_access_token= $options->get_value_of('wepay_access_token');
        $wepay_account_id= $options->get_value_of('wepay_account_id');
        //var_dump("yyy",$wepay_test_mode,$wepay_client_secret,$wepay_access_token,$wepay_account_id);
        
        if($wepay_test_mode=="yes"):
                WePay::useStaging($wepay_client_id, $wepay_client_secret);
        else:
                WePay::useProduction($wepay_client_id, $wepay_client_secret);
        endif;

        $wepay = new WePay();

        $wepay->rm_get_token($wepay_access_token);


        try {
                $checkout = $wepay->request('/checkout', array(
                                'checkout_id' => $_REQUEST['checkout_id'], // ID of the account that you want the money to go to
                        )
                );//echo '<pre>';print_r($checkout);die;
        } catch (WePayException $e) { // if the API call returns an error, get the error message for display later
                $error = $e->getMessage();
        }
        
        $log_id= $_SESSION['LOG_ID'];
        $log = RM_DBManager::get_row('PAYPAL_LOGS', $log_id);
        
        if(empty($log))
            return;
        
        
        $ex_data = maybe_unserialize($log->ex_data);
        $user_id = (int)$ex_data['user_id'];
        $form_id = $log->form_id;
        $form = new RM_Forms;
        $form->load_from_db($form_id);
        
        
        
        $curr_date = RM_Utilities::get_current_time(); // date_i18n(get_option('date_format'));
        $checkout->first_name = $checkout->payer->name;
        $checkout->payer_email= $checkout->payer->email;
        
        if ( $checkout->state == 'authorized' ){
            $status= 'completed';
            if($form->form_options->user_auto_approval == 'default'){
                $check_setting = $options->get_value_of('user_auto_approval');
            } else {
                $check_setting = $form->form_options->user_auto_approval;
            }

            if ($user_id && in_array($check_setting, array('yes','verify'))){
                $user_service = new RM_User_Services();
                $user_service->activate_user_by_id($user_id);                                    
            }
        } else{
            $status= "pending";
        }
        $checkout->status= $status;
        
        $checkoutArr =  (array) $checkout;
        $log_array = maybe_serialize($checkoutArr);
        
        RM_DBManager::update_row('PAYPAL_LOGS', $log_id, array(
        'status' => $status,
        'txn_id' => $checkout->checkout_id,
        'posted_date' => $curr_date,  
        'log' => $log_array), array('%s', '%s', '%s', '%s'));

        $page_url= add_query_arg(array("rm_wepay"=>1,"log_id"=>$log_id), $checkout->hosted_checkout->redirect_uri);
        $redirect_url= add_query_arg(array("rm_wepay"=>1,"log_id"=>$log_id), $page_url);
        echo '<script>window.location = "'. $redirect_url.'";</script>';
        //echo '<pre>';print_r($checkout);echo '</pre>';die;
    }
    
    public function generate_authorize_form($data){
        global $wp; 
        $options= new RM_Wepay_Options();
        $p= get_the_ID();

        // Insert log
        $curr_date = RM_Utilities::get_current_time(); //date_i18n(get_option('date_format'));
        $total_amount= $data->pricing_details->total_price;
        $ex_data['user_id'] = isset($data->user_id) ? $data->user_id : null;
        if ($total_amount <= 0.0)
        {
            $log_entry_id = RM_DBManager::insert_row('PAYPAL_LOGS', array('submission_id' => $data->submission_id,
                          'form_id' => $data->form_id,
                          'invoice' => $data->order_id,
                          'status' => 'Completed',
                          'total_amount' => $total_amount,
                          'currency' => $data->currency,
                          'posted_date' => $curr_date,
                          'pay_proc' => 'wepay',
                          'bill' => maybe_serialize($data->pricing_details),
                          'ex_data' => maybe_serialize($ex_data)),
                           array('%d', '%d', '%s', '%s', '%f', '%s', '%s', '%s', '%s','%s'));

            return true;
        } else {
            $log_entry_id = RM_DBManager::insert_row('PAYPAL_LOGS', array('submission_id' => $data->submission_id,
                          'form_id' => $data->form_id,
                          'invoice' => $data->order_id,
                          'status' => 'Pending',
                          'total_amount' => $total_amount,
                          'currency' => $data->currency,
                          'posted_date' => $curr_date,
                          'pay_proc' => 'wepay',
                          'bill' => maybe_serialize($data->pricing_details),
                          'ex_data' => maybe_serialize($ex_data)),
                           array('%d', '%d', '%s', '%s', '%f', '%s', '%s', '%s', '%s','%s'));
        }
        $_SESSION['LOG_ID'] = $log_entry_id;
        //$login_id= $options->get_value_of('anet_login_id');
        //$trans_key= $options->get_value_of('anet_trans_key');
        //$anet_test_mode= $options->get_value_of('anet_test_mode');
        
        include_once dirname( __DIR__ ).'/wepay.php';
        $wepay_test_mode = $options->get_value_of('wepay_test_mode');
        $wepay_client_id = $options->get_value_of('wepay_client_id');                    
        $wepay_client_secret= $options->get_value_of('wepay_client_secret');
        $wepay_access_token= $options->get_value_of('wepay_access_token');
        $wepay_account_id= $options->get_value_of('wepay_account_id');
        //var_dump("yyy",$wepay_test_mode,$wepay_client_secret,$wepay_access_token,$wepay_account_id);
        
        if($wepay_test_mode=="yes"):
                WePay::useStaging($wepay_client_id, $wepay_client_secret);
        else:
                WePay::useProduction($wepay_client_id, $wepay_client_secret);
        endif;

        $wepay = new WePay();

        $wepay->rm_get_token($wepay_access_token);


        try {
            $checkout = $wepay->request('/checkout/create', array(
                'account_id' => $wepay_account_id, // ID of the account that you want the money to go to
                'amount' => $total_amount, // dollar amount you want to charge the user
                'short_description' => __("WePay Payment","registrationmagic-gold"), // a short description of what the payment is for
                'type' => "service", // the type of the payment - choose from GOODS SERVICE DONATION or PERSONAL
                'currency' => $data->currency,
                'hosted_checkout'=>array('redirect_uri'=>$_SERVER['HTTP_REFERER'])
            ));
        } catch (WePayException $e) { // if the API call returns an error, get the error message for display later
                $error = $e->getMessage();
        }
        ?>


        <html>
	<head>
	</head>
	
	<body>
		
            <h1><?php _e('Checkout', 'registrationmagic-gold'); ?>:</h1>

            <p><?php _e('The user will checkout here', 'registrationmagic-gold'); ?>:</p>

            <?php 


            if (isset($error)): ?>
                    <h2 style="color:red"><?php _e('ERROR', 'registrationmagic-gold'); ?>: <?php echo htmlspecialchars($error); ?></h2>
            <?php else:

                if($wepay_test_mode=="yes"){

            ?>
                        
            <div id="checkout_div"></div>

            <script type="text/javascript" src="https://stage.wepay.com/js/iframe.wepay.js">
            </script>

            <script type="text/javascript">
            WePay.iframe_checkout("checkout_div", "<?php echo $checkout->hosted_checkout->checkout_uri; ?>");
            </script>
            <?php }

                else{

                ?>
                        <div id="checkout_div_live"></div>

                <script type="text/javascript" src="https://www.wepay.com/min/js/iframe.wepay.js">
                </script>

                <script type="text/javascript">
                WePay.iframe_checkout("checkout_div_live", "<?php echo $checkout->hosted_checkout->checkout_uri; ?>");
                </script>

        <?php	}

        endif; ?>
	
	</body>
        </html>
    
        <?php
        // $html_content = ob_get_contents();
        $data=array();
        //$data['html']= $html_form;
        $data['status']='do_not_redirect';
        //  ob_end_clean();
        //  echo $html_form;
        return $data;
    }
    
    public function process_payment($form, $request, $params){
        $data= new stdClass();
        if ($form->get_form_type() === RM_REG_FORM)
            $data->user_id = $form->get_registered_user_id();
        
        $data->user_email= $params['user_email'];
        $data->pricing_details = $form->get_pricing_detail($request->req);
        $options= new RM_Wepay_Options();
        $data->currency= $options->get_value_of('currency'); 
        $data->order_id = (string) date("His") . rand(1234, 9632);
        $data->form_name= $form->get_form_name();
        $data->form_id= $params['form_id'];
        $data->submission_id= $params['sub_detail']->submission_id;
        
        return $this->generate_authorize_form($data);
    }
}


