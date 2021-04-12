<?php

class RM_Login_Controller {

    public $mv_handler;

    function __construct() {
        $this->mv_handler = new RM_Model_View_Handler();
    }

    public function form($model, $service, $request, $params) {
        if (RM_Public::$login_form_counter == 0) {// In case login attempt is coming from submissions page, Increase counter value from 0 to 1
            RM_Public::$login_form_counter++;
        }
        $login_service = new RM_Login_Service();
        $login_form_slug = "rm_login_form_" . RM_Public::$login_form_counter;
        $otp_form_slug = "rm_otp_form_" . RM_Public::$login_form_counter;
        $hide_forms = array();
        $gopts = new RM_Options();
        // handle twitter callback
        if (isset($request->req['rm_target'])) {
            if ($request->req['rm_target'] == 'fbcb') {
                $service->facebook_login_callback();
            }
        }

        //handle twitter callback
        $session_token = isset($_SESSION['token']) ? $_SESSION['token'] : null;
        $req_token = isset($request->req['oauth_token']) ? $request->req['oauth_token'] : null;
        if ($session_token == $req_token && $req_token != null) {
            $data = new stdClass();
            $data->twitter = $service->get_twitter_keys();
            if ($data->twitter['enable_twitter'] == 'yes') {
                $view = $this->mv_handler->setView('login', true);
                $data->login_form_slug = $login_form_slug;
                return $view->read($data);
            }
        }

        $data = new stdClass();
        $data->show_otp = false;  // For two factor authentication
        $data->otp_error = false;
        $auth_options = $login_service->get_auth_options();
        $auth_otp = isset($request->req['auth_otp']) ? sanitize_text_field($request->req['auth_otp']) : false;
        $v_options = $login_service->get_validations();
        /*
         * Validation OTP for 2 Factor Authentication
         */
        if ($this->mv_handler->validateForm($otp_form_slug)) {

            $user = $login_service->get_user($request->req['username']);
            $username = $request->req['username'];
            $data->username = $username;
            //  var_dump($login_service->check_otp($auth_otp,$username),!empty($auth_options['otp_expiry']), $login_service->is_otp_expired($auth_otp,$username)); die;
            if ($login_service->check_otp($auth_otp, $user)) { // OTP matched
                if (!empty($auth_options['otp_expiry']) && $login_service->is_otp_expired($auth_otp, $user)) {

                    $regenrate_link = '';
                    $data->otp_error = true;
                    if ($auth_options['otp_expiry_action'] == 'regenerate') {
                        $data->show_otp = true;
                        $regenrate_link = "<div class='rm-generate' onclick='rm_regernate_expired_otp(this,\"" . $username . "\")'><a href='javascript:void(0)'>" . $auth_options['otp_regen_text'] . "</a></div>";
                        RM_PFBC_Form::setError($otp_form_slug, $auth_options['otp_exp_msg'] . $regenrate_link);
                        if (empty($_POST['rm_otp_form_processed'])) {
                            $_POST['rm_otp_form_processed'] = 1;
                            $login_service->insert_login_log(array('email' => $user->user_email, 'username_used' => $username, 'ip' => $_SERVER['REMOTE_ADDR'], 'time' => current_time('timestamp'), 'status' => 0, 'type' => '2fa', 'result' => 'failure', 'failure_reason' => 'expired_otp'));
                        }
                    } else {
                        $data->otp_form_submit = 1;
                        $login_service->insert_login_log(array('email' => $user->user_email, 'username_used' => $username, 'ip' => $_SERVER['REMOTE_ADDR'], 'time' => current_time('timestamp'), 'status' => 0, 'type' => '2fa', 'result' => 'failure', 'failure_reason' => 'expired_otp'));
                        $login_service->insert_login_log(array('email' => $user->user_email, 'username_used' => $username, 'ip' => $_SERVER['REMOTE_ADDR'], 'time' => current_time('timestamp'), 'status' => 0, 'type' => '2fa', 'result' => 'dummy', 'failure_reason' => 'otp_resent'));
                        RM_PFBC_Form::setError($login_form_slug, $auth_options['otp_exp_restart_msg']);
                    }
                } else {
                    $login_service->delete_otp($auth_otp, $username);
                    $user_service = new RM_User_Services();
                    $user_service->auto_login_by_id($user->ID);
                    $login_service->insert_login_log(array('email' => $user->user_email, 'username_used' => $username, 'ip' => $_SERVER['REMOTE_ADDR'], 'time' => current_time('timestamp'), 'status' => 1, 'type' => '2fa', 'result' => 'success'));
                    // After login redirection 
                    $redirect_to = RM_Utilities::after_login_redirect($user);
                    if (!$redirect_to)
                        $redirect_to = apply_filters('login_redirect', admin_url(), "", $user);
                    RM_Utilities::redirect($redirect_to);
                    die;
                }
            }
            else { // OTP mismatch
                if (empty($_POST['rm_otp_incorrect_processed'])) {
                    $_POST['rm_otp_incorrect_processed'] = 1;
                    $login_service->insert_login_log(array('email' => $user->user_email, 'username_used' => $username, 'ip' => $_SERVER['REMOTE_ADDR'], 'time' => current_time('timestamp'), 'status' => 0, 'type' => '2fa', 'result' => 'failure', 'failure_reason' => 'incorrect_otp'));
                }

                // Check if failed OTP attempts exceeded
                if (!empty($auth_options['allowed_incorrect_attempts']) && $login_service->incorrect_otp_attempts_exceeded($user, $auth_options['allowed_incorrect_attempts'])) {
                    //$login_service->insert_login_log(array('email'=>$user->user_email,'username_used'=>$username,'ip'=> $_SERVER['REMOTE_ADDR'],'time'=> current_time('timestamp'),'status'=>0,'type'=>'2fa','result'=>'failure','failure_reason'=>'incorrect_otp'));
                    $login_service->insert_login_log(array('email' => $user->user_email, 'username_used' => '', 'ip' => '', 'time' => current_time('timestamp'), 'status' => 0, 'type' => 'otp', 'result' => 'dummy', 'failure_reason' => 'dummy'));
                    $data->otp_form_submit = 1;
                    RM_PFBC_Form::setError($login_form_slug, __('Sorry, your OTP limit exceeded. You need to login again to proceed.', 'registrationmagic-gold'));
                    $data->show_otp = false;
                } else {
                    $data->show_otp = true;
                    $data->otp_error = true;

                    RM_PFBC_Form::setError($otp_form_slug, $auth_options['invalid_otp_error']);
                }
            }
        }

        /*
         * User login form authentication
         */
        if (isset($request->req['rm_slug']) && $request->req['rm_slug'] == 'rm_login_form' && isset($request->req['rm_form_sub_id']) && $login_form_slug != $request->req['rm_form_sub_id']) {
            array_push($hide_forms, $login_form_slug);
        }

        if (empty($auth_otp) && $this->mv_handler->validateForm($login_form_slug)) {
            $normal_login = true;
            // Check if 2 Factor authentication enabled
            if ($auth_options['en_two_fa']) {
                $status = $login_service->check_login($request->req['username'], $request->req['pwd']);
                if (empty($status)) {
                    do_action('rm_user_signon_failure', array('username' => $request->req['username'], 'password' => $request->req['pwd']));
                    RM_PFBC_Form::setError($login_form_slug, __('Incorrect Username or Password', 'registrationmagic-gold'));
                } else {
                    // check if user activated
                    $user = $login_service->get_user($request->req['username']);
                    $user_status = get_user_meta($user->ID, 'rm_user_status', true);
                    if (!empty($user_status)) {
                        RM_PFBC_Form::setError($login_form_slug, __('Account not activated.', 'registrationmagic-gold'));
                    } else {
                        $applicable = $login_service->two_fact_auth_applicable($user);
                        if ($applicable) {
                            $login_service->send_2fa_otp($user);
                            $data->show_otp = true;
                            $data->username = $request->req['username'];
                            $normal_login = false;
                        }
                    }
                }
            } else {
                $status = $login_service->check_login($request->req['username'], $request->req['pwd']);
                if (empty($status)) {
                    do_action('rm_user_signon_failure', array('username' => $request->req['username'], 'password' => $request->req['pwd']));
                    RM_PFBC_Form::setError($login_form_slug, __('Incorrect Username or Password', 'registrationmagic-gold'));
                } else {
                    // check if user activated
                    $user = $login_service->get_user($request->req['username']);
                    $user_status = get_user_meta($user->ID, 'rm_user_status', true);
                    if (intval($user_status) == 1) {
                        RM_PFBC_Form::setError($login_form_slug, __('Account not activated.', 'registrationmagic-gold'));
                        $normal_login = false;
                    }
                }
            }

            if ($normal_login) { // Prodeeding with normal username and password authentication die('in');
                $status = $login_service->check_login($request->req['username'], $request->req['pwd']);

                if (empty($status)) {
                    RM_PFBC_Form::resetErrors($login_form_slug);
                    $user = $login_service->get_user($request->req['username']);
                    $recovery_options= $login_service->get_recovery_options();
                    $lostpassword_url= wp_lostpassword_url();
                    if(!empty($recovery_options['en_pwd_recovery'])){
                        $page_id= $recovery_options['recovery_page'];
                        if(!empty($page_id)){
                            $lostpassword_url= get_permalink($page_id);
                        }
                    }
                    if (empty($user)) {
                        if (empty($_POST['rm_login_form_processed'])) {
                            $_POST['rm_login_form_processed'] = 1;
                            $login_service->insert_login_log(array('email' => $request->req['username'], 'username_used' => $request->req['username'], 'ip' => $_SERVER['REMOTE_ADDR'], 'time' => current_time('timestamp'), 'status' => 0, 'type' => 'normal', 'result' => 'failure', 'failure_reason' => 'incorrect_username'));
                        }

                        $error_message = $v_options['un_error_msg'];
                        if (!empty($v_options['en_recovery_link'])) {
                            $error_message .= ' <div class="rm_inline_forgot_pass"><a href="' . $lostpassword_url . '" target="blank">' . __('Lost Your Password?', 'registrationmagic-gold') . '</a></div>';
                        }
                        RM_PFBC_Form::setError($login_form_slug, $error_message);
                    } else {
                        if (!empty($v_options['en_failed_user_notification'])) {
                            RM_Email_Service::notify_failed_login_to_user($user);
                        }
                        if (!empty($v_options['en_failed_admin_notification'])) {
                            RM_Email_Service::notify_failed_login_to_admin($user);
                        }
                        $error_message = $v_options['pass_error_msg'];

                        if (!empty($v_options['en_recovery_link'])) {
                            $error_message .= ' <div class="rm_inline_forgot_pass"><a href="' . $lostpassword_url . '" target="blank">' . __('Lost Your Password?', 'registrationmagic-gold') . '</a></div>';
                        }
                        RM_PFBC_Form::setError($login_form_slug, $error_message);
                        if (empty($_POST['rm_login_form_processed'])) {
                            $_POST['rm_login_form_processed'] = 1;
                            $login_service->insert_login_log(array('email' => $user->user_email, 'username_used' => $request->req['username'], 'ip' => $_SERVER['REMOTE_ADDR'], 'time' => current_time('timestamp'), 'status' => 0, 'type' => 'normal', 'result' => 'failure', 'failure_reason' => 'incorrect_password'));
                        }
                    }
                    //if(!empty($v_options['en_captcha'])){
                    //do_action('wp_login_failed',$request->req['username']);
                    do_action('rm_user_signon_failure', array('username' => $request->req['username'], 'password' => $request->req['pwd']));
                    //}
                } else {

                    $login_form = json_decode($login_service->get_form(), true);
                    // Get username field
                    $username_field = array();
                    foreach ($login_form['form_fields'] as $form_field) {
                        if ($form_field['field_type'] == 'username') {
                            $username_field = $form_field;
                            break;
                        }
                    }

                    if ($username_field['username_accepts'] == 'ue') {
                        $user = get_user_by('login', $request->req['username']);
                        if (empty($user)) {
                            $user = get_user_by('email', $request->req['username']);
                        }
                    } else if ($username_field['username_accepts'] == 'email') {
                        $user = get_user_by('email', $request->req['username']);
                    } else {
                        $user = get_user_by('login', $request->req['username']);
                    }

                    $is_disabled = 0;

                    if (!empty($user)) {
                        $prov_acc_act = RM_Utilities::rm_is_prov_login_active($user->ID);
                        $is_disabled = (int) get_user_meta($user->ID, 'rm_user_status', true);

                        if ($is_disabled == 1) {
                            if (empty($prov_acc_act)) {
                                $user_auto_approval = $gopts->get_value_of('user_auto_approval');
                                $user_hash = get_user_meta($user->ID, 'rm_activation_hash', true);
                                if ($user_auto_approval == 'verify' && !empty($user_hash)) {
                                    $login_err_msg = $gopts->get_value_of('login_error_message');
                                    $sub_page_id = $gopts->get_value_of('front_sub_page_id');
                                    $sub_page_url = get_permalink($sub_page_id);
                                    if (empty($login_err_msg)) {
                                        $login_err_msg = RM_UI_Strings::get('DEFAULT_LOGIN_ERR_MSG_VALUE');
                                    }
                                    $sub_page_url = add_query_arg(array(
                                        'rm_user' => $user->ID,
                                        'resend' => '1'
                                            ), $sub_page_url);
                                    $sub_page_url = '<a href="' . $sub_page_url . '">' . __('Click Here', 'registrationmagic-gold') . '</a>';
                                    $login_err_msg = str_replace('{{send verification email}}', $sub_page_url, $login_err_msg);
                                    $login_err_msg = str_replace('{{SEND_VERIFICATION_EMAIL}}', $sub_page_url, $login_err_msg);

                                    RM_PFBC_Form::setError($login_form_slug, $login_err_msg);
                                } else {
                                    RM_PFBC_Form::setError($login_form_slug, RM_UI_Strings::get('INCATIVE_ACC_MSG'));
                                }
                            } else {
                                $is_disabled = false;
                            }
                        }
                    }
                    if (empty($is_disabled)) {
                        $user = $service->login($request);
                        if (is_wp_error($user)) {
                            RM_PFBC_Form::setError($login_form_slug, $user->get_error_message());
                            do_action('rm_user_signon_failure', array('username' => $request->req['username'], 'password' => $request->req['pwd']));
                        } else {
                            if (empty($_POST['rm_login_form_processed'])) {
                                $_POST['rm_login_form_processed'] = 1;
                                $login_service->insert_login_log(array('email' => $user->user_email, 'username_used' => $request->req['username'], 'ip' => $_SERVER['REMOTE_ADDR'], 'time' => current_time('timestamp'), 'status' => 1, 'type' => 'normal', 'result' => 'success'));
                            }
                            $redirect_to = RM_Utilities::after_login_redirect($user);
                            if (!$redirect_to)
                                $redirect_to = apply_filters('login_redirect', admin_url(), "", $user);
                            RM_Utilities::redirect($redirect_to);
                            die;
                        }
                    }
                }
            }
        }

        // External login integrations
        $data->facebook_html = $service->facebook_login_html();
        $data->google_html = $service->google_login_html();
        $data->linkedin_html = $service->linkedin_login_html();
        $data->windows_html = $service->windows_login_html();
        $data->twitter_html = $service->twitter_login_html();
        $data->instagarm_html = $service->instagarm_login_html();
        $data->auth_options = $auth_options;
        $data->gopts = $gopts;
        if (!is_user_logged_in()) {
            if (!empty($v_options['en_captcha'])) {
                $failed_count = $login_service->check_max_failed_login();
                $max_failed_attempt = $v_options['allowed_failed_attempts'];
                if (!empty($failed_count) && !empty($max_failed_attempt) && $failed_count == $max_failed_attempt) {
                    $data->show_captcha = true;
                }

                if ($max_failed_attempt == 0) {
                    $data->show_captcha = true;
                }
            }

            if (!empty($v_options['en_ban_ip'])) {
                $failed_count = $login_service->failed_login_before_ban();
                $allowed_attempts_bef_ban = $v_options['allowed_attempts_before_ban'];
                if (!empty($failed_count) && !empty($allowed_attempts_bef_ban) && $failed_count >= $allowed_attempts_bef_ban) {
                    $login_service->ban_ip(array('failed_count' => $failed_count));
                }
            }
            if ($login_service->is_ip_banned()) {
                $data->ban = 1;
                $data->ban_error_msg = $v_options['ban_error_msg'];
            }
        }

        $pass_options = $login_service->get_recovery_options();

        if (!empty($pass_options['en_pwd_recovery'])) {
            $data->en_pwd_recovery = 1;
            $data->recovery_link_text = $pass_options['recovery_link_text'];
            $data->recovery_page = $pass_options['recovery_page'];
        }


        $login_form = json_decode($login_service->get_form(), true);
        if (!empty($login_form)) {
            $data->fields = $login_form['form_fields'];
        }


        $data->login_form_slug = $login_form_slug;
        $data->design = $login_service->get_form_design();
        if (!empty($params['attr']) && !empty($params['attr']['btn_widget'])) {
            unset($data->design);
        }
        $data->otp_form_slug = $otp_form_slug;
        $data->buttons = $login_service->get_button_config();
        $data->hidden_forms = $hide_forms;
        $view = $this->mv_handler->setView('login', true);
        return $view->read($data);
    }

    public function lost_password($model, $service, $request, $params) {
        
        $login_service = new RM_Login_Service();
        $data = new stdClass();
        $data->form_type = 'rm_recovery_form';

        if (isset($request->req['reset_token'])) {

            $token = $_SERVER['REQUEST_METHOD'] === 'POST' ? $request->req['token_val'] : $request->req['reset_token'];
            $users = get_users(array('meta_key' => 'rm_pass_token', 'meta_value' => $token));
            if (empty($users)) {
                $data->form_type = 'rm_token_form';
                $data->invalid_token = 1;
            } else {

                $tk = get_user_meta($users[0]->ID, 'rm_pass_expiry_token', true);
                $token_expired = false;
                if (!empty($tk) && $tk < time()) {
                    $token_expired = true;
                }
                if ($token_expired) {
                    $data->expired_token = 1;
                    $data->form_type = 'rm_recovery_form';
                } else {
                    $data->form_type = 'rm_reset_password_form';
                    $data->sec_token = $token;
                }
            }
        }
        if (isset($request->req['rm_form_sub_id'])) {
            if ($request->req['rm_form_sub_id'] == 'rm_recovery_form') {
                $user = get_user_by('email', $request->req['user_email']);
                $data->valid_email = empty($user) ? 0 : 1;
                if (!empty($data->valid_email)) {
                    $email_sent = RM_Email_Service::notify_lost_password_token($user);
                }
            } else if ($request->req['rm_form_sub_id'] == 'rm_token_form') {
                $token = $request->req['token_val'];
                $users = get_users(array('meta_key' => 'rm_pass_token', 'meta_value' => $token));
                if (empty($users)) {
                    $data->invalid_copy_token = 1;
                }
            } else if ($request->req['rm_form_sub_id'] == 'rm_reset_password_form') {
                if ($request->req['password'] != $request->req['confirm_password']) {
                    $data->password_mismatch = 1;
                } else {
                    $error = $login_service->validate_password($request->req['password']);
                    if (!empty($error)) {
                        $data->error = $error;
                    } else {
                        $token = $request->req['token_val'];
                        $users = get_users(array('meta_key' => 'rm_pass_token', 'meta_value' => $token));
                        if (!empty($users)) {
                            $user_id = wp_update_user(array('ID' => $users[0]->ID, 'user_pass' => $request->req['password']));
                            if (is_wp_error($user_id)) {
                                $data->password_updated = 0;
                            } else {
                                delete_user_meta($users[0]->ID, 'rm_pass_token');
                                delete_user_meta($users[0]->ID, 'rm_pass_expiry_token');
                                $data->password_updated = 1;
                            }
                        }
                    }
                }
            }
        }
        $data->buttons = $login_service->get_button_config();
        $data->options = $login_service->get_recovery_options();
        $view = $this->mv_handler->setView('pass_recovery', true);
        return $view->read($data);
    }

}
