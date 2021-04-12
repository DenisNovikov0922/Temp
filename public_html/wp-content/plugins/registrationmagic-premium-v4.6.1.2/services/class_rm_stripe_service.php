<?php

class RM_Stripe_Service implements RM_Gateway_Service
{
    private $paypal;
    private $options;
    private $currency;
    private static $instance;
    
    
    public static function get_instance(){
        if (!empty(self::$instance)) {
            return self::$instance;
        }
       return new RM_Stripe_Service();
    }
    
    private function __construct() {
        $this->options= new RM_Options();
        $this->currency = $this->options->get_value_of('currency');
    }


    function setOptions($options) {
        $this->options = $options;
    }

    public function cancel() {

    }
    
    public function convert_price_into_lowest_unit($price, $currency)
    {
        switch(strtoupper($currency))
        {
            case 'BIF':
            case 'DJF':
            case 'JPY':
            case 'KRW':
            case 'PYG':
            case 'VND':
            case 'XAF':
            case 'XPF':
            case 'CLP':
            case 'GNF':
            case 'KMF':
            case 'MGA':
            case 'RWF':
            case 'VUV':
            case 'XOF':
                return $price;
                
            default:
                return $price*100;
        }
    }

    public function charge($data=null,$pricing_details=null) {
        $amount = isset($_POST['total_price']) ? absint($_POST['total_price']) : 0;
        if(empty($amount)){
            wp_send_json_error(array('msg'=>__('Amount is not valid.','registrationmagic-gold')));
        }
        $pm_id= isset($_POST['payment_method_id']) ? $_POST['payment_method_id'] : '';
        $pi_id= isset($_POST['payment_intent_id']) ? $_POST['payment_intent_id'] : '';
        empty($pm_id) && empty($pi_id) ? wp_send_json_error(array('msg'=>__('Payment request is invalid.','registrationmagic-gold'))) : '';
        $submission_id= isset($_POST['sub_id']) ? absint($_POST['sub_id']) : 0;
        empty($submission_id) ? wp_send_json_error(array('msg'=>__('Submission not valid.','registrationmagic-gold'))) : '';
        $submission = new RM_Submissions();
        if(!$submission->load_from_db($submission_id)){
            wp_send_json_error(array('msg'=>__('Submission not valid.','registrationmagic-gold')));
        }
        $log_id = isset($_POST['log_id']) ? absint($_POST['log_id']) : 0;
        if(!class_exists('Stripe\Stripe'))
          require_once RM_EXTERNAL_DIR . 'stripe/init.php';
        \Stripe\Stripe::setApiKey($this->options->get_value_of('stripe_api_key'));
        $charge_error= '';
        try{
            if(!empty($pm_id)){
                $charge_details= array('payment_method'=>$pm_id,'confirmation_method'=>'manual','confirm'=>true, 
                                       'amount' => $this->convert_price_into_lowest_unit($amount,$this->currency), 'currency' => $this->currency,'metadata'=>array('Submission ID'=>$submission_id));
                $intent = \Stripe\PaymentIntent::create($charge_details);
            }
            if(!empty($pi_id)){
                $intent = \Stripe\PaymentIntent::retrieve($pi_id);
                $intent->confirm();
            }
        } 
        catch(\Stripe\Error\RateLimit $e){
            $charge_error= array('msg'=>__('Stripe API request limit exceeded.','registrationmagic-gold'));
        }
        catch(\Stripe\Error\Authentication $e){
            $charge_error= array('msg'=>__('Authentication failed.','registrationmagic-gold'));
        }
        catch(Exception $e){
             $charge_error= array('msg'=>$e->getMessage());
        }
        
        if(!empty($charge_error)){
            wp_send_json_error($charge_error);
        }
        
        if ($intent->status == 'requires_source_action' && $intent->next_action->type == 'use_stripe_sdk') {
            wp_send_json_success(array('requires_action' => true,'payment_intent_client_secret' => $intent->client_secret,'sub_id'=>$submission_id));
        } 
        
            $log_data= array('submission_id' =>$submission_id,
            'form_id' => $submission->get_form_id(),
            'txn_id' => '',
            'status' => $intent->status,
            'invoice' => (string) date("His") . rand(1234, 9632),
            'total_amount' => $amount,
            'currency' => $this->currency,
            'log' => maybe_serialize($intent->jsonSerialize()),
            'posted_date' => RM_Utilities::get_current_time(),
            'pay_proc' => 'stripe');
             $log_entry_id = RM_DBManager::update_row('PAYPAL_LOGS',$log_id,$log_data , array('%d', '%d', '%s', '%s', '%s', '%f', '%s', '%s', '%s', '%s', '%s'));
             
         $payment_status = $intent->status=='succeeded' ? true: false;
         $response= apply_filters('rm_payment_completed_response',array('msg'=>'','redirect'=>''),$submission,$submission->get_form_id(),$payment_status);
         if(!empty($log_id)){
             $response['log_id']= $log_id;
         }
         wp_send_json_success($response);
    }

    public function refund() {
        
    }

    public function subscribe() {
        
    }
    
    public function show_card_elements($details,$pricing){
        $data=array();
        echo RM_Utilities::enqueue_external_scripts('stripe_script', esc_url_raw('https://js.stripe.com/v3/'), array());
        echo RM_Utilities::enqueue_external_scripts('stripe_utility_script',RM_BASE_URL. 'public/js/stripe_payment_utility.js', array());
        $curr_date = RM_Utilities::get_current_time();
        $invoice = (string) date("His") . rand(1234, 9632);
        $log_entry_id = RM_DBManager::insert_row('PAYPAL_LOGS', array('submission_id' => $details->submission_id,
                        'form_id' => $details->form_id,
                        'invoice' => $invoice,
                        'status' => $pricing->total_price<=0.0 ? 'Completed' : 'Pending',
                        'total_amount' => $pricing->total_price,
                        'currency' => $this->currency,
                        'posted_date' => $curr_date,
                        'pay_proc' => 'paypal',
                        'bill' => maybe_serialize($pricing)), array('%d', '%d', '%s', '%s', '%f', '%s', '%s', '%s', '%s'));
        
                
        $label = __('Please enter the details to complete the payment:','registrationmagic-gold');
        $btn_label = __('Pay','registrationmagic-gold');
        $data['html']= "<div class='rm_stripe_fields'>   
                            <div class='rm_stripe_label'>$label</div>
                            <div class='rm-stripe-card-row'>                              
                            <div id='rm-stripe-card-element' class='rm-stripe-card-element clearfix'></div>
                            <span class='payment-errors' id='rm_stripe_payment_errors'></span>
                            </div>
                            <button type='button' data-log-id='$log_entry_id' data-total-price='$pricing->total_price' data-submission-id='$details->submission_id' class='rm_stripe_pay_btn'>$btn_label</button>
                            <div id='payment-tk_'></div>
                        </div>";
        $data['status']='do_not_redirect';
        return $data;
    }
    
    public function localize_data(){
        return array('public' => $this->options->get_value_of('stripe_publish_key'));
    }
    
    public function localize_data_json(){
        $data= $this->localize_data();
        wp_send_json($data);
    }
}

