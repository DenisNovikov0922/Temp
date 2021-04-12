<?php
class Validation_Captcha extends Validation {
	protected $message = '';
	protected $privateKey;
        protected $version;

	public function __construct($privateKey, $message = "",$version=2) {
		$this->privateKey = $privateKey;
                $this->version=$version;
		//if(!empty($message))
		$this->message = RM_UI_Strings::get('ERROR_INVALID_RECAPTCHA');
                //print_r($version); die;
	}

	public function isValid($value='') {
		require_once(dirname(__FILE__) . "/../Resources/recaptchalib.php");
                if(!isset($_POST["g-recaptcha-response"])){
                    return false;
                }

		$resp = rm_recaptcha_check_answer ($this->privateKey, $_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]);
		if($resp->is_valid)
			return true;
		else{
                    if(!empty($_POST['rm_slug']) && $_POST['rm_slug']=='rm_login_form'){
                        $login_service= new RM_Login_Service();
                        $username= sanitize_text_field($_POST['username']);
                        $user= get_user_by('login', $username);
                        if(empty($user)){
                            $user= get_user_by('email', $username);
                        }
                        $args= array('ip'=>$_SERVER["REMOTE_ADDR"],'time'=> current_time('timestamp'),'type'=>'normal','result'=>'failure','failure_reason'=>'incorrect_reCAPCTCHA');
                        if(!empty($user)){
                          $args['email']= $user->user_email;
                        }
                        else
                        {
                            $args['username_used']= $username;
                        }
                        if(empty($_POST['rm_captcha_form_processed'])){
                            $_POST['rm_captcha_form_processed']=1;
                            $login_service->insert_login_log($args);
                        }
                        
                    }
                    
                    
                    return false;
                }	
			
	}
}
