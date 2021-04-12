<?php
/**
 * Copyright (c) 2013-2015 WePay, Inc. <api@wepay.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

class WePay
{	
    /**
     * Version number - sent in user agent string
     */
    const VERSION = '0.3.1';

    /**
     * Scope fields
     * Passed into Wepay::getAuthorizationUri as array
     */
    const SCOPE_MANAGE_ACCOUNTS      = 'manage_accounts';      // Open and interact with accounts
    const SCOPE_COLLECT_PAYMENTS     = 'collect_payments';     // Create and interact with checkouts
    const SCOPE_VIEW_USER            = 'view_user';            // Get details about authenticated user
    const SCOPE_PREAPPROVE_PAYMENTS  = 'preapprove_payments';  // Create and interact with preapprovals
    const SCOPE_MANAGE_SUBSCRIPTIONS = 'manage_subscriptions'; // Subscriptions
    const SCOPE_SEND_MONEY           = 'send_money';           // For withdrawals

    /**
     * Application's client ID
     */
    private static $client_id;

    /**
     * Application's client secret
     */
    private static $client_secret;


    /**
     * API Version
     * https://www.wepay.com/developer/reference/versioning
     */
    private static $api_version;

    /**
     * @deprecated Use WePay::getAllScopes() instead.
     */
    public static $all_scopes = array(
        self::SCOPE_MANAGE_ACCOUNTS,
        self::SCOPE_COLLECT_PAYMENTS,
        self::SCOPE_PREAPPROVE_PAYMENTS,
        self::SCOPE_VIEW_USER,
        self::SCOPE_SEND_MONEY,
        self::SCOPE_MANAGE_SUBSCRIPTIONS
    );

    /**
     * Determines whether to use WePay's staging or production servers
     */
    private static $production = null;

    /**
     * cURL handle
     */
    private static $ch = NULL;

    /**
     * Authenticated user's access token
     */
    private $token;
    private $data;
    private static $instance=null; 
    /**
     * Pass WePay::getAllScopes() into getAuthorizationUri if your application desires full access
     */
    
    public static function instance() { 

		// Only run these methods if they haven't been ran previously
		if (self::$instance===null) {
			self::$instance = new WePay();
			self::$instance->set_globals();
			self::$instance->includes();
			self::$instance->set_actions();
		}

		// Always return the instance
		return self::$instance;
    }
    public function __construct() { /* Do nothing here */ }

    
    private function set_globals() {
            $this->file       = __FILE__;
            $this->basename   = apply_filters( 'rm_wepay_plugin_basenname', plugin_basename( $this->file ) );
            $this->plugin_dir = apply_filters( 'rm_wepay_plugin_dir_path',  plugin_dir_path( $this->file ) );
            $this->plugin_url = apply_filters( 'rm_wepay_plugin_dir_url',   plugin_dir_url ( $this->file ) );
            $this->includes_dir = apply_filters( 'rm_wepay_includes_dir', trailingslashit( $this->plugin_dir . 'includes'  ) );
	}

    private function includes() {
                require( $this->includes_dir . '/checkout.php');
                require( $this->includes_dir . '/rm_wepay_options.php'    );
                require( $this->includes_dir . '/common/ui_strings.php' );
    }
        
    private function set_actions() 
    { 
            add_filter('rm_extend_payprocs_options',array($this,'admin_wepay_option'),10,2);
            add_filter('rm_extend_payprocs_config',array($this,'admin_wepay_config'),10,2);
            add_action('rm_gopts_payment_save',array($this,'admin_wepay_save_options'),10,1);
            add_action('init', array($this, 'check_authorize_response'));
            add_action('rm_pre_form_proc',array($this,'pre_form_proc'),10,1);
            add_action('rm_payment_procs_options_frontend', array($this,'show_payment_option'),10,2);
            add_filter('rm_process_payment', array($this,'wepay_process_payment'),10,4);
           // add_action( 'admin_enqueue_scripts', array($this,'admin_anet_enque_script') );
    }
    
    public function check_authorize_response()
    {
         $wepay= new RM_Wepay_Checkout();
         $wepay->check_authorize_response();
    }
    
    public function admin_wepay_option($pay_procs_options, $data)
    {
        $pay_procs_options['rm_wepay']= "<img style='width:auto;' src='" . $this->plugin_url . "images/rm_wepay.png" . "'>";
        return $pay_procs_options;

    }
        
    public function admin_wepay_config($config=null, $data=null)
    {
        $wepay_options= new RM_Wepay_Options();
        $options= $wepay_options->get_all_options();

        $options_wepay_test_mode = array("id" => "wepay_test_mode", "longDesc" => RM_WEPAY_UI_Strings::get('HELP_OPTIONS_WEPAY_TESTMODE'),"value"=>$wepay_options->get_value_of('wepay_test_mode'));
        $options_wepay_client_id = array("id" => "wepay_client_id", "value" => $wepay_options->get_value_of('wepay_client_id'), "longDesc" => RM_WEPAY_UI_Strings::get('HELP_OPTIONS_WEPAY_CLIENT_ID'));
        $options_wepay_client_secret = array("id" => "wepay_client_secret", "value" => $wepay_options->get_value_of('wepay_client_secret'), "longDesc" => RM_WEPAY_UI_Strings::get('HELP_OPTIONS_WEPAY_CLIENT_SECRET'));
        $options_wepay_access_token = array("id" => "wepay_access_token", "value" => $wepay_options->get_value_of('wepay_access_token'), "longDesc" => RM_WEPAY_UI_Strings::get('HELP_OPTIONS_WEPAY_ACCESS_TOKEN'));
        $options_wepay_account_id = array("id" => "wepay_account_id", "value" => $wepay_options->get_value_of('wepay_account_id'), "longDesc" => RM_WEPAY_UI_Strings::get('HELP_OPTIONS_WEPAY_ACCOUNT_ID'));

        $elements= array();
        $elements[]= new Element_Checkbox(RM_WEPAY_UI_Strings::get('LABEL_TEST_MODE'), "wepay_test_mode", array("yes" => ''),$options_wepay_test_mode );
        $elements[]= new Element_Textbox(RM_WEPAY_UI_Strings::get('LABEL_WEPAY_CLIENT_ID'), "wepay_client_id", $options_wepay_client_id);
        $elements[]= new Element_Textbox(RM_WEPAY_UI_Strings::get('LABEL_WEPAY_CLIENT_SECRET'), "wepay_client_secret", $options_wepay_client_secret);
        $elements[]= new Element_Textbox(RM_WEPAY_UI_Strings::get('LABEL_WEPAY_ACCESS_TOKEN'), "wepay_access_token", $options_wepay_access_token);
        $elements[]= new Element_Textbox(RM_WEPAY_UI_Strings::get('LABEL_WEPAY_ACCOUNT_ID'), "wepay_account_id", $options_wepay_account_id);

        $config['rm_wepay']= $elements;
        return $config;
    }

    public function show_payment_option($radio_array)
    {
        $wepay_opts = new RM_Wepay_Options;
        $payment_gateways = $wepay_opts->get_value_of('payment_gateway');
        
        $get_default_currency = $wepay_opts->get_value_of('currency');
        if(in_array('rm_wepay', $payment_gateways) && in_array($get_default_currency,array('USD','GBP','CAD')))
        //if(in_array('rm_wepay', $payment_gateways))
        {
            $radio_array['rm_wepay'] = "<img src='" . $this->plugin_url . "images/rm_wepay.png" . "'>";
        }
        return $radio_array;
    }
        
    public static function getAllScopes()
    {
        return array(
            self::SCOPE_MANAGE_ACCOUNTS,
            self::SCOPE_MANAGE_SUBSCRIPTIONS,
            self::SCOPE_COLLECT_PAYMENTS,
            self::SCOPE_PREAPPROVE_PAYMENTS,
            self::SCOPE_VIEW_USER,
            self::SCOPE_SEND_MONEY
        );
    }

    /**
     * Generate URI used during oAuth authorization
     * Redirect your user to this URI where they can grant your application
     * permission to make API calls
     *
     * @link https://www.wepay.com/developer/reference/oauth2
     *
     * @param  array  $scope        List of scope fields for which your application wants access.
     * @param  string $redirect_uri Where user goes after logging in at WePay (domain must match application settings).
     * @param  array  $options      `user_name,user_email` which will be pre-filled on login form, state to be returned
     *                              in query string of redirect_uri. The default value is an empty array.
     * @return string URI to which you must redirect your user to grant access to your application
     */
    public static function getAuthorizationUri(array $scope, $redirect_uri, array $options = array())
    {
        // This does not use WePay::getDomain() because the user authentication
        // domain is different than the API call domain
        if (self::$production === null) {
            throw new RuntimeException('You must initialize the WePay SDK with WePay::useStaging() or WePay::useProduction()');
        }

        $domain = self::$production ? 'https://www.wepay.com' : 'https://stage.wepay.com';
        $uri = $domain . '/v2/oauth2/authorize?';
        $uri .= http_build_query(array(
            'client_id'    => self::$client_id,
            'redirect_uri' => $redirect_uri,
            'scope'        => implode(',', $scope),
            'state'        => empty($options['state'])        ? '' : $options['state'],
            'user_name'    => empty($options['user_name'])    ? '' : $options['user_name'],
            'user_email'   => empty($options['user_email'])   ? '' : $options['user_email'],
            'user_country' => empty($options['user_country']) ? '' : $options['user_country'],
        ), '', '&');

        return $uri;
    }

    private static function getDomain()
    {
        if (self::$production === true) {
            return 'https://wepayapi.com/v2/';
        } elseif (self::$production === false) {
            return 'https://stage.wepayapi.com/v2/';
        } else {
            throw new RuntimeException('You must initialize the WePay SDK with WePay::useStaging() or WePay::useProduction()');
        }
    }

    /**
     * Exchange a temporary access code for a (semi-)permanent access token
     * @param  string         $code         'code' field from query string passed to your redirect_uri page
     * @param  string         $redirect_uri Where user went after logging in at WePay (must match value from getAuthorizationUri)
     * @return StdClass|false
     *                                     user_id
     *                                     access_token
     *                                     token_type
     */
    public static function getToken($code, $redirect_uri)
    {
        $params = (array(
            'client_id'     => self::$client_id,
            'client_secret' => self::$client_secret,
            'redirect_uri'  => $redirect_uri,
            'code'          => $code,
            'state'         => '', // do not hardcode
        ));
        $result = self::make_request('oauth2/token', $params);
        return $result;
    }

    /**
     * Configure SDK to run against WePay's production servers
     * @param  string           $client_id     Your application's client id
     * @param  string           $client_secret Your application's client secret
     * @return void
     * @throws RuntimeException
     */
    public static function useProduction($client_id, $client_secret, $api_version = null)
    {
        if (self::$production !== null) {
            throw new RuntimeException('API mode has already been set.');
        }
        self::$production    = true;
        self::$client_id     = $client_id;
        self::$client_secret = $client_secret;
        self::$api_version   = $api_version;
    }

    /**
     * Configure SDK to run against WePay's staging servers
     * @param  string           $client_id     Your application's client id
     * @param  string           $client_secret Your application's client secret
     * @return void
     * @throws RuntimeException
     */
    public static function useStaging($client_id, $client_secret, $api_version = null)
    {
        if (self::$production !== null) {
            throw new RuntimeException('API mode has already been set.');
        }
        self::$production    = false;
        self::$client_id     = $client_id;
        self::$client_secret = $client_secret;
        self::$api_version   = $api_version;
    }

    /**
     * Returns the current environment.
     * @return string "none" (not configured), "production" or "staging".
     */
    public static function getEnvironment()
    {
        if (self::$production === null) {
            return 'none';
        } elseif (self::$production) {
            return 'production';
        } else {
            return 'staging';
        }
    }

    /**
     * Set Api Version
     * https://www.wepay.com/developer/reference/versioning
     *
     * @param string $version Api Version to send in call request header
     */
    public static function setApiVersion($version)
    {
        self::$api_version = $version;
    }

    /**
     * Create a new API session
     * @param string $token - access_token returned from WePay::getToken
     */
    public function rm_get_token($token)
    {		
        if ($token && !is_string($token)) {
            throw new InvalidArgumentException('$token must be a string, ' . gettype($token) . ' provided');
        }
        $this->token = $token;
    }

    /**
     * Clean up cURL handle
     */
    public function __destruct()
    {
        if (self::$ch) {
            curl_close(self::$ch);
            self::$ch = NULL;
        }
    }

    /**
     * create the cURL request and execute it
     */
    private static function make_request($endpoint, $values, $headers = array())
    {
        self::$ch = curl_init();
        $headers = array_merge(array("Content-Type: application/json"), $headers); // always pass the correct Content-Type header

        // send Api Version header
        if (!empty(self::$api_version)) {
            $headers[] = "Api-Version: " . self::$api_version;
        }

        curl_setopt(self::$ch, CURLOPT_USERAGENT, 'WePay v2 PHP SDK v' . self::VERSION . ' Client id:' . self::$client_id);
        curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(self::$ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt(self::$ch, CURLOPT_TIMEOUT, 30); // 30-second timeout, adjust to taste
        curl_setopt(self::$ch, CURLOPT_POST, !empty($values)); // WePay's API is not strictly RESTful, so all requests are sent as POST unless there are no request values

        // Force TLS 1.2 connections
        curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, 2);
        
        if ( ! defined('CURL_SSLVERSION_TLSv1_2')){
            curl_setopt(self::$ch, CURLOPT_SSLVERSION, 6);
        }else{
            curl_setopt(self::$ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        }

        $uri = self::getDomain() . $endpoint;
        curl_setopt(self::$ch, CURLOPT_URL, $uri);

        if (!empty($values)) {
            curl_setopt(self::$ch, CURLOPT_POSTFIELDS, json_encode($values));
        }

        $raw = curl_exec(self::$ch);
        if ($errno = curl_errno(self::$ch)) {
            // Set up special handling for request timeouts
            if ($errno == CURLE_OPERATION_TIMEOUTED) {
                throw new WePayServerException("Timeout occurred while trying to connect to WePay");
            }
            throw new Exception('cURL error while making API call to WePay: cURL Errno - ' . $errno . ', ' . curl_error(self::$ch), $errno);
        }

        $result = json_decode($raw);
        $httpCode = curl_getinfo(self::$ch, CURLINFO_HTTP_CODE);

        if ($httpCode >= 400) {
            if (!isset($result->error_code)) {
                $error_description = '';
                if(!empty($result->error_description)){
                    $error_description = $result->error_description;
                }
                throw new WePayServerException("WePay returned an error response with no error_code, please alert api@wepay.com. Original message: $error_description", $httpCode, $result, 0);
            }
            if ($httpCode >= 500) {
                throw new WePayServerException($result->error_description, $httpCode, $result, $result->error_code);
            }
            switch ($result->error) {
                case 'invalid_request':
                    throw new WePayRequestException($result->error_description, $httpCode, $result, $result->error_code);
                case 'access_denied':
                default:
                    throw new WePayPermissionException($result->error_description, $httpCode, $result, $result->error_code);
            }
        }

        return $result;
    }

    /**
     * Make API calls against authenticated user
     * @param  string         $endpoint     - API call to make (ex. 'user', 'account/find')
     * @param  array          $values       - Associative array of values to send in API call
     * @param  string         $risk_token   - WePay-supplied risk token associated with this API call
     * @param  string         $client_ip    - Client's IP address associated with this API call
     * @return StdClass
     * @throws WePayException on failure
     * @throws Exception      on catastrophic failure (non-WePay-specific cURL errors)
     */
    public function request($endpoint, array $values = array(), $risk_token = null, $client_ip = null)
    {
        $headers = array();

        if ($this->token) { // if we have an access_token, add it to the Authorization header
            $headers[] = "Authorization: Bearer $this->token";
        }

        if ($risk_token) { // if we have a risk_token, add it to the WePay-Risk-Token header
            $headers[] = "WePay-Risk-Token: " . $risk_token;
        }

        if ($client_ip) { // if we have a client_ip, add it to the Client-IP header
            $headers[] = "Client-IP: " . $client_ip;
        }

        $result = self::make_request($endpoint, $values, $headers);

        return $result;
    }
    
    public function wepay_process_payment($payment_done, $form, $request, $params){
        if(!isset($request->req['rm_payment_method']))
             return $payment_done;
        
        $payment_method = $request->req['rm_payment_method'];
        if($payment_method=="rm_wepay"){
            $wepay= new RM_Wepay_Checkout();
            return $wepay->process_payment($form, $request, $params);
        }
        return $payment_done;
    }
    
    public function admin_wepay_save_options($req)
    {  
        $wepay_options= new RM_Wepay_Options();
        $options['wepay_test_mode'] = isset($req['wepay_test_mode']) ? "yes" : null;

        if(!empty($req['wepay_client_id']))
            $options['wepay_client_id'] = RM_Utilities::enc_str ($req['wepay_client_id']);

        if(!empty($req['wepay_client_secret']))
            $options['wepay_client_secret'] = RM_Utilities::enc_str($req['wepay_client_secret']);

        if(!empty($req['wepay_access_token']))
            $options['wepay_access_token'] = RM_Utilities::enc_str($req['wepay_access_token']);

        if(!empty($req['wepay_account_id']))
            $options['wepay_account_id'] = RM_Utilities::enc_str($req['wepay_account_id']);
        $wepay_options->set_values($options);
    }   
        
    public function pre_form_proc($request)
    {   
        global $wp;
        $msg= '';
        if(isset($request['rm_wepay']) && $request['rm_wepay']=='1')
        {  
            $log_id= $request['log_id'];
            if(empty($log_id))
                return;


            $log = RM_DBManager::get_row('PAYPAL_LOGS', $log_id);

            $ex_data = maybe_unserialize($log->ex_data);
            if(!empty($log) && !empty($log->log))
            {
                $wepay_log = maybe_unserialize($log->log); 
                $submission_id =  $log->submission_id;
                $submission =  new RM_Submissions();
                $submission->load_from_db($submission_id);

                $service= new RM_Front_Form_Service();
                $form_factory= new RM_Form_Factory();
                $fe_form = $form_factory->create_form($submission->get_form_id());
                $form_options= $fe_form->get_form_options();
                if( $log->status=='completed')
                {
                    $options= new RM_Wepay_Options();
                        
                    $user_id = (int)$ex_data['user_id'];
                    if ($user_id>0 && $form_options->auto_login)
                    {
                        $_SESSION['RM_SLI_UID'] = $user_id;     
                    }

                    echo '<div id="rmform">';
                    echo "<div class='rminfotextfront'>" . RM_WEPAY_UI_Strings::get("MSG_PAYMENT_SUCCESS") . "</br>";
                    echo '</div></div>';
                }
                else if($log->status=='pending')
                {
                    echo '<div id="rmform">';
                    echo "<div class='rminfotextfront'>" . RM_WEPAY_UI_Strings::get("MSG_PAYMENT_PENDING") . "</br>";
                    echo '</div></div>';
                }

                $msg = ob_get_clean();
                $x = new stdClass;
                $x->form_options = $form_options;
                $x->form_name = $fe_form->get_form_name();
                $x->form_id = $submission->get_form_id();
                $x->sub_id = $submission_id;
                $after_sub_msg = $service->after_submission_proc($x);
                echo  $msg . '<br><br>' . $after_sub_msg;
                $current_url = home_url(add_query_arg(array(),$wp->request)); 
                $current_url=add_query_arg( array('rm_success'=>'1','rm_form_id'=>$submission->get_form_id(),'rm_sub_id'=>$submission_id), $current_url);
                RM_Utilities::redirect($current_url, false, 0, false);
                die;
            }
        }
    }
        
}

/**
 * Different problems will have different exception types so you can
 * catch and handle them differently.
 *
 * WePayServerException indicates some sort of 500-level error code and
 * was unavoidable from your perspective. You may need to re-run the
 * call, or check whether it was received (use a "find" call with your
 * reference_id and make a decision based on the response)
 *
 * WePayRequestException indicates a development error - invalid endpoint,
 * erroneous parameter, etc.
 *
 * WePayPermissionException indicates your authorization token has expired,
 * was revoked, or is lacking in scope for the call you made
 */
function rm_wepay() {
	return WePay::instance();
}
rm_wepay();

class WePayException extends Exception
{
    public function __construct($description = '', $http_code = FALSE, $response = FALSE, $code = 0, $previous = NULL)
    {
        $this->response = $response;

        if (!defined('PHP_VERSION_ID')) {
            $version = explode('.', PHP_VERSION);
            define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
        }

        if (PHP_VERSION_ID < 50300) {
            parent::__construct($description, $code);
        } else {
            parent::__construct($description, $code, $previous);
        }
    }
}
class WePayRequestException extends WePayException {}
class WePayPermissionException extends WePayException {}
class WePayServerException extends WePayException {}
