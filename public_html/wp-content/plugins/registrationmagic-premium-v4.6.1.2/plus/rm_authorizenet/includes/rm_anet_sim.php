<?php
/*
 * Class to handle Server Integration Method (SIM) payemtn
 */
class RM_Anet_Sim
{
     protected $msg = array();
     private $rm_anet;
     private $live_url;
     private $test_url;
     
     public function __construct(){
         $this->rm_anet= rm_anet();
         $this->live_url          = 'https://accept.authorize.net/payment/payment';
         $this->test_url          = 'https://test.authorize.net/payment/payment';
     }
      
     /**
       * Check for valid Authorize.net server callback to validate the transaction response.
      **/
      function check_authorize_response()
      {
        $options= new RM_Anet_Options();
        $status= "pending";
        
        if (count($_POST)){
            $redirect_url = '';
            if(!isset($_POST['x_invoice_num']) || !isset($_POST['log_id'])){
                return;
            }

            $log_id= $_POST['log_id'];
            $sub_id= $_POST['sub_id'];
            $x_invoice_num = $_POST['x_invoice_num'];
            $log = RM_DBManager::get_row('PAYPAL_LOGS', $log_id);
            if($log->invoice!=$x_invoice_num || empty($log)){
                return;
            }

            $ex_data = maybe_unserialize($log->ex_data);
            $user_id = (int)$ex_data['user_id'];
            $form_id = $log->form_id;
            $form = new RM_Forms;
            $form->load_from_db($form_id);
            $response= '';
            $response .= '<div id="rmform">';
            if ( $_POST['x_response_code'] != ''){
                $amount =   $_POST['x_amount'];
                $curr_date = RM_Utilities::get_current_time(); // date_i18n(get_option('date_format'));
                $_POST['payer_email']= $_POST['x_email'];
                $log_array = maybe_serialize($_POST);

                if ( $_POST['x_response_code'] == 1 ){ 
                   $status= 'completed';
                    if($form->form_options->user_auto_approval == 'default'){
                        $check_setting = $options->get_value_of('user_auto_approval');
                    } else {
                        $check_setting = $form->form_options->user_auto_approval;
                    }

                    if ($user_id && $check_setting == "yes"){
                        $user_service = new RM_User_Services();
                        $user_service->activate_user_by_id($user_id);
                    }
                    
                    if (!is_user_logged_in() && $form->form_options->auto_login)
                    {
                        $_SESSION['RM_SLI_UID'] = $user_id;     
                    }
                }

                RM_DBManager::update_row('PAYPAL_LOGS', $log_id, array(
                    'status' => $status,
                    'txn_id' => $_REQUEST['x_trans_id'],
                    'posted_date' => $curr_date,
                    'log' => $log_array), array('%s', '%s', '%s', '%s'));

                
            }
            $response .= "<br><br><div class='rm-post-sub-msg'>";
            $response .= $form->form_options->form_success_message != "" ? apply_filters('rm_form_success_msg',$form->form_options->form_success_message,$form_id,$sub_id) : $form->get_form_name() . " ".__('Submitted','registrationmagic-gold');
            $response .= '</div>';
            $response .= '</div>';
            echo $response; die;
        }
        
      }
      
      
     
      /**
      * Generate authorize.net button link
      **/
      private function generate_authorize_form($data)
      {
         global $wp; 
         $options= new RM_Anet_Options();
         $p= get_the_ID();
         $form = new RM_Forms();
         $form->load_from_db($data->form_id);
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
                            'pay_proc' => 'anet_sim',
                            'bill' => maybe_serialize($data->pricing_details),
                            'ex_data' => maybe_serialize($ex_data)),
                             array('%d', '%d', '%s', '%s', '%f', '%s', '%s', '%s', '%s','%s'));
                
                return true;
           } else
            {
                $log_entry_id = RM_DBManager::insert_row('PAYPAL_LOGS', array('submission_id' => $data->submission_id,
                            'form_id' => $data->form_id,
                            'invoice' => $data->order_id,
                            'status' => 'Pending',
                            'total_amount' => $total_amount,
                            'currency' => $data->currency,
                            'posted_date' => $curr_date,
                            'pay_proc' => 'anet_sim',
                            'bill' => maybe_serialize($data->pricing_details),
                            'ex_data' => maybe_serialize($ex_data)),
                             array('%d', '%d', '%s', '%s', '%f', '%s', '%s', '%s', '%s','%s'));
            }
            
         $login_id= $options->get_value_of('anet_login_id'); 
         $trans_key= $options->get_value_of('anet_trans_key'); 
         $anet_test_mode= $options->get_value_of('anet_test_mode');
         
         //format URL for authoriznet
         $redirect_url = rtrim(home_url(add_query_arg(array(),$wp->request)),'/').'/';
         $redirect_url= add_query_arg(array("rm_success"=>1,"rm_sub_id"=>$data->submission_id,
                                     "rm_form_id"=>$data->form_id,"p"=>$p),$redirect_url );
         $redirection_url= RM_Utilities::get_form_redirection_url($form);
         if(!empty($redirection_url)){
             $redirect_url= $redirection_url;
         }
         $authorize_args = array(
            
            'x_login'                  => $login_id,
            'x_amount'                 => $data->pricing_details->total_price,
            'x_invoice_num'            => $data->order_id,
            'x_relay_url'              => $redirect_url,
            'x_email'                  => $data->user_email,
            'x_currency_code'           => $data->currency, 
            );

         if($anet_test_mode=="yes"){
           $processURI = $this->test_url;
         }
         else{
            $processURI = $this->live_url;
         }
         
        include('gettoken.php');
        //ob_start();
        $html_form = '<div id="response"></div>';
        $html_form .= '<div id="payment_html">';
        $html_form .= '<center><h2>'.__('Please wait, your order is being processed and you will be redirected to the payment window.', 'registrationmagic-gold').'</h2></center>';
        $html_form .= '<center><br><br>'.__('If you are not automatically redirected to Authorize.Net Payment window within 5 seconds...', 'registrationmagic-gold').'<br><br>';
        $html_form .= '<form id="payform" target="payframe" action="'.$processURI.'" method="post">'
                . '<input type="hidden" id="token" name="token" value="'.$token.'">'
                . '<input type="submit" class="button" onclick="rm_anet_sim_submit()" value="'.__('Click Here','registrationmagic-gold').'" />'
                . '</form>'
                . '</div>'
                . '<style>#payframe{border-style:none;height:350px;width:100%;display:none;}</style>'
                . '<script type="text/javascript">
                    setTimeout(function() { rm_anet_sim_submit(); }, 5000);
                    function rm_anet_sim_submit()
                    {
                        jQuery("#payform").submit();
                        jQuery("#payframe").show();
                        setTimeout(function() { jQuery("#payment_html").hide(); }, 1000);
                    }
                    </script>
                    <iframe name="payframe" id="payframe" class="test"></iframe>';
        ?>
        <script>
            window.CommunicationHandler = {};
            function parseQueryString(str) {
                var vars = [];
                var arr = str.split('&');
                var pair;
                for (var i = 0; i < arr.length; i++) {
                    pair = arr[i].split('=');
                    vars[pair[0]] = unescape(pair[1]);
                }
                return vars;
            }
            window.CommunicationHandler.onReceiveCommunication = function (argument) {
                $=jQuery;
                params = parseQueryString(argument.qstr);
                parentFrame = argument.parent.split('/')[4];
                switch (params['action']) {
                    case "cancel":
                        //window.location.reload();
                    case "transactResponse":
                        var transResponse = JSON.parse(params['response']);
                        if (transResponse.transId > 0) {
                            $('#payform').hide();
                            $('#payframe').hide();
                            //$('#response').html("<?php echo __('Thank you for payment.<br>Your Transaction Id is: ','registrationmagic-gold') ?>"+transResponse.transId);
                            
                            $.ajax({
                                url: "<?php echo site_url('/','https') ?>wp-admin/admin-ajax.php",
                                    type: "POST",
                                    data: {action:'rm_authnet_ipn',x_trans_id:transResponse.transId,'x_invoice_num': transResponse.orderInvoiceNumber,log_id:'<?php echo $log_entry_id ?>',x_response_code:transResponse.responseCode,x_amount:transResponse.totalAmount,x_email:'<?php echo $data->user_email ?>','sub_id':'<?php echo $data->submission_id; ?>'},
                                    success: function(data) {
                                        $("#response").append(data);
                                        var redirection = '<?php echo $redirect_url; ?>';
                                        if(redirection){
                                            location.href= redirection;
                                        }
                                    }
                            });
                        }
                }
            }
        </script>
        
  
        <?php
        
       // $html_content = ob_get_contents();
        $data=array();
        $data['html']= $html_form;
        $data['status']='do_not_redirect';
       //  ob_end_clean();
        // echo $html_form;
         return $data;
      }
      
      
    public function process_payment($form, $request, $params)
      { 
        $data= new stdClass();
        if ($form->get_form_type() === RM_REG_FORM)
        $data->user_id = $form->get_registered_user_id();
        $data->user_email= $params['user_email'];
        $data->pricing_details = $form->get_pricing_detail($request->req);
        $options= new RM_Anet_Options();
        $data->currency= $options->get_value_of('currency'); 
        $data->order_id = (string) date("His") . rand(1234, 9632);
        $data->form_name= $form->get_form_name();
        $data->form_id= $params['form_id'];
        $data->submission_id= $params['sub_detail']->submission_id;
            
        return $this->generate_authorize_form($data);
    }  
}