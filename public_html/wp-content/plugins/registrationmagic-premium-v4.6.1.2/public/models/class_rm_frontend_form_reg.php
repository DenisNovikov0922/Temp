<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RM_Frontend_Form_Reg extends RM_Frontend_Form_Multipage {

    protected $form_user_role;
    protected $default_form_user_role;
    protected $user_exists;
    protected $user_id;

    public function __construct(RM_Forms $be_form, $ignore_expiration = false) {
        parent::__construct($be_form, $ignore_expiration);
        $this->form_user_role = $be_form->get_form_user_role();
        $this->default_form_user_role = $be_form->get_default_form_user_role();
        $this->set_form_type(RM_REG_FORM);
        $this->user_exists = false;
        $this->user_id = 0;
    }

    public function get_registered_user_id() {
        return $this->user_id;
    }

    //Returning false here will prevent submission in form controller.
    public function pre_sub_proc($request, $params) {
        $form_name = 'form_' . $this->form_id . "_" . $this->form_number;
        $prime_data = $this->get_prepared_data_primary($request);
        if (!is_user_logged_in()) {
            if (!isset($prime_data['user_email'], $prime_data['username']))
                return false;

            $email = $prime_data['user_email']->value;
            $username = $prime_data['username']->value;

            RM_PFBC_Form::clearErrors($form_name);

            if (RM_Utilities::is_username_reserved($username)) {
                RM_PFBC_Form::setError($form_name, RM_UI_Strings::get("LABEL_BAN_USERNAME_MSG"));
                return false;
            }

            if (isset($prime_data['password'])) {

                $password = $prime_data['password']->value;
                if (isset($prime_data['password_confirmation'])) {
                    $password_conf = $prime_data['password_confirmation']->value;
                    if ($password !== $password_conf) {
                        RM_PFBC_Form::setError($form_name, RM_UI_Strings::get("ERR_PW_MISMATCH"));
                        return false;
                    }
                }
            }

            $valid_character_error = RM_Utilities::validate_username_characters($username, $this->form_id);
            if (!empty($valid_character_error) && empty($this->form_options->hide_username)) {
                RM_PFBC_Form::setError($form_name, $valid_character_error);
                return false;
            }

            $user = get_user_by('login', $username);
            if (!empty($user)) {
                $this->user_exists = true;
                $username_field = RM_DBManager::get_field_by_type($this->form_id, 'Username');
                $username_error = RM_UI_Strings::get("USERNAME_EXISTS");
                if (!empty($username_field)) {
                    $username_options = maybe_unserialize($username_field->field_options);
                    if (!empty($username_options->user_exists_error)) {
                        $username_error = $username_options->user_exists_error;
                    }
                }

                RM_PFBC_Form::setError($form_name, $username_error);
                return false;
            }

            $user = get_user_by('email', $email);

            if (!empty($user)) {
                $this->user_exists = true;
                RM_PFBC_Form::setError($form_name, RM_UI_Strings::get("USERNAME_EXISTS"));
                return false;
            }


            RM_PFBC_Form::clearErrors($form_name);
            return true;
        }

        return true;
    }

    public function post_sub_proc($request, $params) {
        $prime_data = $this->get_prepared_data_primary($request);
        $x = null;
		##Codex
        if (!is_user_logged_in() || (isset($_POST['opregusr_form_field']) && $_POST['opregusr_form_field']=='rm')) {
            if (isset($params['paystate'])) {
                if ($params['paystate'] == 'pre_payment' || $params['paystate'] == 'na') {
                    if (!isset($prime_data['user_email'], $prime_data['username']))
                        return false;

                    $email = $prime_data['user_email']->value;
                    $username = $prime_data['username']->value;

                    $password_field = $this->service->get_primary_field_options('userpassword', $this->get_form_id());
                    if (empty($password_field))
                        $password = null;
                    else {
                        if (!isset($prime_data['password']))
                            return false;
                        $password = $prime_data['password']->value;
                    }
                    do_action('rm_subscribe_newsletter', $this->get_form_id(), $request);
                    if ($params['paystate'] == 'pre_payment')
                        $user_id = $this->service->register_user($username, $email, $password, false, $this->form_options->user_auto_approval, $this->form_id);
                    else
                        $user_id = $this->service->register_user($username, $email, $password, true, $this->form_options->user_auto_approval, $this->form_id);

                    $this->user_id = $user_id;
                    update_user_meta($user_id, 'RM_UMETA_FORM_ID', $this->form_id);
                    update_user_meta($user_id, 'RM_UMETA_SUB_ID', $params['sub_detail']->submission_id);
                    update_user_meta($user_id, 'rm_activation_time', date('Y-m-d H:i:s'));
                    if ($this->form_options->user_auto_approval == 'default') {
                        do_action('rm_user_registered', $user_id);
                    }

                    $x = array('user_id' => $user_id);
                    if (isset($request['role_as']) && !empty($request['role_as']) && in_array($request['role_as'], $this->form_user_role)) {
                        $this->service->get_user_service()->set_user_role($user_id, $request['role_as']);
                        $role_cost = $this->get_role_cost($request['role_as']);
                        if (empty($role_cost)) {
                            $params['paystate'] = 'na';
                            $params['free_role'] = true;
                        }
                    } else {
                        if (!empty($this->default_form_user_role)) {
                            $this->service->get_user_service()->set_user_role($user_id, $this->default_form_user_role);
                        }
                    }
                }
                $opt = new RM_Options;
                if ($params['paystate'] == 'post_payment' || $params['paystate'] == 'na') {
                    if ($this->form_options->user_auto_approval == 'default') {
                        $check_setting = $opt->get_value_of('user_auto_approval');
                    } else {
                        $check_setting = $this->form_options->user_auto_approval;
                    }

                    if ($check_setting == 'yes') {
                        if ($params['paystate'] == 'post_payment' && (isset($params['is_paid']) && $params['is_paid'] == true))
                            $user_id = $this->service->get_user_service()->activate_user_by_id($this->user_id);
                        else if ($params['paystate'] == 'na' && !empty($params['free_role'])) {
                            $user_id = $this->service->get_user_service()->activate_user_by_id($this->user_id);
                        }
                    }

                    if ($this->form_options->auto_login) {
                        if ($check_setting == 'yes' || $check_setting == 'verify') {
                            $_SESSION['RM_SLI_UID'] = $this->user_id;
                            $user = get_user_by('ID', $this->user_id);
                            $login_service = new RM_Login_Service();
                            $login_service->insert_login_log(array('email' => $user->user_email, 'username_used' => $user->user_email, 'ip' => $_SERVER['REMOTE_ADDR'], 'time' => current_time('timestamp'), 'status' => 1, 'type' => 'normal', 'result' => 'success', 'social_type' => ''));
                        }
                    }
                }
            }
        }

        if (isset($params['paystate']) && $params['paystate'] != 'post_payment') {
            if ($this->service->get_setting('enable_mailchimp') == 'yes' && $this->form_options->enable_mailchimp[0] == 1) {
                $form_options_mc = $this->form_options;

                if ($form_options_mc->form_is_opt_in_checkbox == 1 || (isset($form_options_mc->form_is_opt_in_checkbox[0]) && $form_options_mc->form_is_opt_in_checkbox[0] == 1))
                    $should_subscribe = isset($request['rm_subscribe_mc']) && $request['rm_subscribe_mc'][0] == 1 ? 'yes' : 'no';
                else
                    $should_subscribe = 'yes';
                if ($should_subscribe == 'yes') {
                    try {
                        $this->service->subscribe_to_mailchimp($request, $this->get_form_options());
                    } catch (Exception $e) {
                        
                    }
                }
            }
            if ($this->service->get_setting('enable_ccontact') == 'yes' && $this->form_options->enable_ccontact[0] == 1) {
                $form_options_mc = $this->form_options;

                if ($form_options_mc->form_is_opt_in_checkbox_cc[0] == 1)
                    $should_subscribe = isset($request['rm_subscribe_cc']) && $request['rm_subscribe_cc'][0] == 1 ? 'yes' : 'no';
                else
                    $should_subscribe = 'yes';

                if ($should_subscribe == 'yes') {

                    try {
                        $this->service->subscribe_to_ccontact($request, $this->get_form_options());
                    } catch (Exception $e) {
                        
                    }
                }
            }

            do_action('rm_subscribe_newsletter', $this->get_form_id(), $request);

            if ($this->service->get_setting('enable_aweber') == 'yes' && $this->form_options->enable_aweber[0] == 1) {
                $form_options_mc = $this->form_options;

                if ($form_options_mc->form_is_opt_in_checkbox_aw[0] == 1)
                    $should_subscribe = isset($request['rm_subscribe_aw']) && $request['rm_subscribe_aw'][0] == 1 ? 'yes' : 'no';
                else
                    $should_subscribe = 'yes';

                if ($should_subscribe == 'yes') {

                    try {
                        $this->service->subscribe_to_aweber($request, $this->get_form_options());
                    } catch (Exception $e) {
                        
                    }
                }
            }
            return $x;
        }
    }

    protected function hook_pre_field_addition_to_page($form, $page_no) {
        if (1 == $page_no) {
            if ($this->preview || !is_user_logged_in()) { /*
             * Let users choose their role
             */

                if (!empty($this->form_options->form_should_user_pick) || !(isset($this->form_user_role) && !empty($this->form_user_role))) {
                    $role_pick = $this->form_options->form_should_user_pick;

                    if ($role_pick) {
                        $role_as = empty($this->form_options->form_user_field_label) ? RM_UI_Strings::get('LABEL_ROLE_AS') : $this->form_options->form_user_field_label;

                        $form->addElement(new Element_Radio($role_as, "role_as", $this->get_allowed_roles(), array("id" => "rm_role_selector_" . $this->form_id . "_" . $this->form_number, "class" => "rm_role_selector", "style" => $this->form_options->style_textfield, "required" => "1", "labelStyle" => $this->form_options->style_label,'data-rmfieldtype'=>"price")));
                    }
                }
            }
        }
    }

    protected function hook_post_field_addition_to_page($form, $page_no, $editing_sub = null) {
        // Changing order of password and confirm password field
        if ($page_no == 1 && !is_user_logged_in() && (int) $this->form_options->hide_username == 1):
            $elements = $form->getElements();
            $postions = array();
            if (is_array($elements)):
                foreach ($elements as $index => $element):

                    // Check for first occurence of Password type field
                    if ($element->getAttribute('name') == "password"):
                        $postions['password'] = $index;
                    endif;

                    // Get index of UserEmail element
                    if (get_class($element) == "Element_UserEmail"):
                        $postions['email'] = $index;
                    endif;

                    // Swaping password and UserEmail field order
                    if (isset($postions['email'], $postions['password']) && $postions['password']):
                        $tmpPass = $elements[$postions['password']];
                        $tmpEmail = $elements[$postions['email']];
                        $elements[$postions['email']] = $elements[$postions['password'] + 1];
                        $elements[$postions['password'] + 1] = $tmpPass;
                        $elements[$postions['password']] = $tmpEmail;

                        break;
                    endif;
                endforeach;
            endif;
            $form->setElements($elements);
        endif;
        // echo '<pre>';
        // print_r($elements);
        $last_page_no = max(array_keys($this->form_pages)) + 1;
        if ($last_page_no == $page_no) {
            if ($this->has_price_field() && !$editing_sub)
                $this->add_payment_fields($form);

            if (!is_user_logged_in() && $this->has_paid_role()) {
                $custom_role_data = json_encode($this->service->get_setting('user_role_custom_data'));

                $form->addElement(new Element_Hidden("paid_role" . $this->form_id, '1', array("id" => "paid_role_" . $this->form_id . "_" . $this->form_number, "data-rmdefrole" => $this->default_form_user_role, "data-rmcustomroles" => $custom_role_data)));
            }

            $check_setting = null;
            if ($this->form_options->enable_captcha == 'default') {
                $check_setting = get_option('rm_option_enable_captcha');
            } else {
                $check_setting = $this->form_options->enable_captcha;
            }

            if ($check_setting == 'yes')
                $form->addElement(new Element_Captcha());

            if ($this->service->get_setting('enable_mailchimp') == 'yes' && $this->form_options->form_is_opt_in_checkbox == 1 && $this->form_options->enable_mailchimp[0] == 1 && !$editing_sub) {
                //This outer div is added so that the optin text can be made full width by CSS.
                $form->addElement(new Element_HTML('<div class="rm_optin_text">'));

                if ($this->form_options->form_opt_in_default_state == 'Checked')
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_mc', array(1 => $this->form_options->form_opt_in_text ?: RM_UI_Strings::get('MSG_SUBSCRIBE')), array("value" => 1, "class" => "rm_mc_optin_checkbox")));
                else
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_mc', array(1 => $this->form_options->form_opt_in_text ?: RM_UI_Strings::get('MSG_SUBSCRIBE')), array("class" => "rm_mc_optin_checkbox")));

                $form->addElement(new Element_HTML('</div>'));
            }

            do_action('rm_show_subscribe_opt', $this->form_id, $form, $editing_sub);

            if ($this->service->get_setting('enable_ccontact') == 'yes' && $this->form_options->form_is_opt_in_checkbox_cc[0] == 1 && $this->form_options->enable_ccontact[0] == 1 && !$editing_sub) {
                //This outer div is added so that the optin text can be made full width by CSS.
                $form->addElement(new Element_HTML('<div class="rm_optin_text">'));

                if ($this->form_options->form_opt_in_default_state_cc == 'Checked')
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_cc', array(1 => $this->form_options->form_opt_in_text_cc ?: RM_UI_Strings::get('MSG_SUBSCRIBE')), array("value" => 1)));
                else
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_cc', array(1 => $this->form_options->form_opt_in_text_cc ?: RM_UI_Strings::get('MSG_SUBSCRIBE'))));

                $form->addElement(new Element_HTML('</div>'));
            }
            if ($this->service->get_setting('enable_aweber') == 'yes' && $this->form_options->form_is_opt_in_checkbox_aw[0] == 1 && $this->form_options->enable_aweber[0] == 1 && !$editing_sub) {
                //This outer div is added so that the optin text can be made full width by CSS.
                $form->addElement(new Element_HTML('<div class="rm_optin_text">'));

                if ($this->form_options->form_opt_in_default_state_aw == 'Checked')
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_aw', array(1 => $this->form_options->form_opt_in_text_aw ?: RM_UI_Strings::get('MSG_SUBSCRIBE')), array("value" => 1)));
                else
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_aw', array(1 => $this->form_options->form_opt_in_text_aw ?: RM_UI_Strings::get('MSG_SUBSCRIBE'))));

                $form->addElement(new Element_HTML('</div>'));
            }

            if ($this->form_options->show_total_price) {
                $gopts = new RM_Options;
                $total_price_localized_string = RM_UI_Strings::get('FE_FORM_TOTAL_PRICE');
                $curr_symbol = $gopts->get_currency_symbol();
                $curr_pos = $gopts->get_value_of('currency_symbol_position');
                $price_formatting_data = json_encode(array("loc_total_text" => $total_price_localized_string, "symbol" => $curr_symbol, "pos" => $curr_pos));
                $form->addElement(new Element_HTML("<div class='rmrow rm_total_price' style='{$this->form_options->style_label}' data-rmpriceformat='$price_formatting_data'></div>"));
            }
        }
    }

    protected function base_render($form, $editing_sub = null) {
        //parent::base_render($form);

        $this->prepare_fields_for_render($form, $editing_sub);

        $this->prepare_button_for_render($form,$editing_sub);

        if (count($this->fields) !== 0)
            $form->render();
        else
            echo RM_UI_Strings::get('MSG_NO_FIELDS');
    }

    protected function get_jqvalidator_config_JS() {
        $password_match_error = RM_UI_Strings::get('ERR_PW_MISMATCH');
        $rm_service = new RM_Services();
        $field = $rm_service->get_primary_field_options('userpassword', $this->form_id);

        if (!empty($field)) {
            $field_options = maybe_unserialize($field->field_options);
            $password_match_error = $field_options->pass_mismatch_err;
        }
        if (!is_user_logged_in()) {
            $form_num = $this->form_number;
            $form_id = $this->form_id;
            $form_counter = RM_Public::$form_counter;
            $str = <<<JSHD
        jQuery.validator.setDefaults({errorClass: 'rm-form-field-invalid-msg',
                                        ignore:':hidden,.ignore,:not(:visible),.rm_untouched',wrapper:'div',
                                       errorPlacement: function(error, element) {
                                                            //error.appendTo(element.closest('.rminput'));
                                                            error.appendTo(element.closest('div'));
                                                          },
                                      rules: {       
        password_confirmation: {
            required: true,
            equalTo: "#rm_reg_form_pw_{$form_id}_{$form_counter}"
        }
            },
        messages: {
        password_confirmation: {
            equalTo: "{$password_match_error}"
        }
            }
                                    });
JSHD;
            return $str;
        } else
            return parent::get_jqvalidator_config_JS();
    }

    //Primary array must be indexed by some unique identifier instead of field_id.
    protected function get_prepared_data_primary($request) {
        $data = array();
        if (isset($this->primary_field_indices['user_email']) && isset($request[$this->primary_field_indices['user_email']])) {
            $field = $this->fields[$this->primary_field_indices['user_email']];
            $field_data = $field->get_prepared_data($request);

            $data['user_email'] = (object) array('label' => $field_data->label,
                        'value' => $field_data->value,
                        'type' => $field_data->type);

            // If Hidden username is configured then copying Email in Username
            $form_id = $this->get_form_id();
            if (RM_Utilities::is_username_hidden($form_id)):
                $data['username'] = (object) array('label' => RM_UI_Strings::get('LABEL_USERNAME'),
                            'value' => $field_data->value,
                            'type' => 'username');
            endif;
        }

        if (isset($request['pwd'])) {
            $data['password'] = (object) array('label' => RM_UI_Strings::get('LABEL_PASSWORD'),
                        'value' => $request['pwd'],
                        'type' => 'password');
        }

        if (isset($request['password_confirmation'])) {
            $data['password_confirmation'] = (object) array('label' => RM_UI_Strings::get('LABEL_PASSWORD_AGAIN'),
                        'value' => $request['password_confirmation'],
                        'type' => 'password');
        }

        if (isset($request['username'])) {
            $data['username'] = (object) array('label' => RM_UI_Strings::get('LABEL_USERNAME'),
                        'value' => $request['username'],
                        'type' => 'username');
        }


        return $data;
    }

    //Make sure that this data is indexed by field_id only
    protected function get_prepared_data_dbonly($request) {
        $data = array();

        foreach ($this->fields as $field) {
            //if (in_array($field->get_field_type(),array('Spacing','Timer'))/*$field->get_field_type() == 'HTMLH' || $field->get_field_type() == 'HTMLP'|| $field->get_field_type() == 'HTML'|| $field->get_field_type() == 'HTMLCustomized'|| $field->get_field_type() == 'Spacing'*/)
            if(is_array($field)){
                $exit = false;
                foreach($field as $single_field){
                    if (in_array($single_field->get_field_type(), RM_Utilities::csv_excluded_widgets())) {
                        $exit = true;
                        continue;
                    } else {
                        $exit = false;
                    }
                    $field_data = $single_field->get_prepared_data($request);

                    if ($field_data === null)
                        continue;

                    $data[$field_data->field_id] = (object) array('label' => $field_data->label,
                                'value' => $field_data->value,
                                'type' => $field_data->type,
                                'meta' => isset($field_data->meta) ? $field_data->meta : null);
                }
                if ($exit)
                    continue;
            } else {
                if (in_array($field->get_field_type(), RM_Utilities::csv_excluded_widgets())) {
                    continue;
                }
                $field_data = $field->get_prepared_data($request);

                /* if($field->get_field_type()=="HTMLCustomized"){
                  $html_field= new RM_Fields();
                  $html_field->load_from_db($field->get_field_id());
                  $field_data->value= $html_field->get_field_value();

                  if(strtolower($html_field->get_field_type())=="link")
                  {
                  $field_options=  $html_field->field_options;
                  $field_data->value= $html_field->field_options->link_type=="url" ? $html_field->field_options->link_href : get_permalink($html_field->field_options->link_page);
                  }
                  } */

                if ($field_data === null)
                    continue;

                $data[$field_data->field_id] = (object) array('label' => $field_data->label,
                            'value' => $field_data->value,
                            'type' => $field_data->type,
                            'meta' => isset($field_data->meta) ? $field_data->meta : null);
            }
        }
        return $data;
    }

    //Need to overload the method to add username and password fields as they are not included in the default fields list of the form.
    //Since this method return data for all fields, do not rely on any fixed type of indexing while iterating.
    protected function get_prepared_data_all($request) {
        $data = parent::get_prepared_data_all($request);

        if (isset($request['password'])) {
            $data['password'] = (object) array('label' => RM_UI_Strings::get('LABEL_PASSWORD'),
                        'value' => $request['password'],
                        'type' => 'password');
        }

        if (isset($request['password_confirmation'])) {
            $data['password_confirmation'] = (object) array('label' => RM_UI_Strings::get('LABEL_PASSWORD_AGAIN'),
                        'value' => $request['password_confirmation'],
                        'type' => 'password');
        }

        if (isset($request['username'])) {
            $data['username'] = (object) array('label' => RM_UI_Strings::get('LABEL_USERNAME'),
                        'value' => $request['username'],
                        'type' => 'username');
        }

        return $data;
    }

    public function get_allowed_roles($include_paid_roles = true) {
        global $wp_roles;
        $allowed_roles = array();
        $form_roles = array();
        $default_wp_roles = $wp_roles->get_names();
        $form_roles = $this->form_user_role;
        if (is_array($form_roles)) {
            if (!empty($this->default_form_user_role) && !in_array($this->default_form_user_role, $form_roles))
                $form_roles[] = $this->default_form_user_role;
        } else
            $form_roles[] = '';

        if (is_array($form_roles) && count($form_roles) > 0) {
            $gopts = new RM_Options;
            $custom_role_data = $this->service->get_setting('user_role_custom_data');
            foreach ($form_roles as $val) {
                if (array_key_exists($val, $default_wp_roles)) {
                    $allowed_roles[$val] = $default_wp_roles[$val];
                    $paid_role_str = '';
                    if (isset($custom_role_data[$val]) && $custom_role_data[$val]->is_paid) {
                        $paid_role_str = ' (' . $gopts->get_formatted_amount($custom_role_data[$val]->amount) . ')';
                        if ($include_paid_roles)
                            $allowed_roles[$val] .= $paid_role_str;
                        else
                            unset($allowed_roles[$val]);
                    }
                }
            }
        }

        return $allowed_roles;
    }

    //Overridden method for adding support for paid user roles.
    public function has_price_field() {
        $field_test = parent::has_price_field();
        $role_test = $this->has_paid_role();

        return ($field_test || $role_test);
    }

    public function has_paid_role() {
        $allowed_roles = $this->get_allowed_roles();
        $custom_role_data = $this->service->get_setting('user_role_custom_data');

        if (is_array($allowed_roles) && count($allowed_roles) > 0)
            foreach ($allowed_roles as $role => $disp_name) {
                if (isset($custom_role_data[$role]) && $custom_role_data[$role]->is_paid)
                    return true;
            }
        return false;
    }

    //get price of a paid role.
    public function get_role_cost($role) {
        $custom_role_data = $this->service->get_setting('user_role_custom_data');
        if (isset($custom_role_data[$role]) && $custom_role_data[$role]->is_paid)
            return $custom_role_data[$role]->amount;
        else
            return null;
    }

    //Overridden method for adding support for paid user roles.
    public function get_pricing_detail($request) {
        $data = parent::get_pricing_detail($request);
        $label = null;
        if (isset($request['role_as']) && !empty($request['role_as'])) {
            $role_cost = $this->get_role_cost($request['role_as']);
            $label = $request['role_as'];
        } else if (!empty($this->default_form_user_role)) {
            $role_cost = $this->get_role_cost($this->default_form_user_role);
            $label = $this->default_form_user_role;
        } else
            $role_cost = null;

        $price_flag = false;

        if ($data === null) {
            $data = new stdClass;
            $data->billing = array();
            $data->total_price = 0.0;
        } else
            $price_flag = true;

        if ($role_cost) {
            $data->billing[] = (object) array('label' => $label, 'price' => $role_cost);
            $data->total_price += $role_cost;
            $price_flag = true;
        }

        return $price_flag ? $data : null;
    }

}
