<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* Option name and values must be in lower case and should not 
 * contain white spaces. 
 * Values must be strings. Do not use true/false etc as the value instead 
 * use yes/no etc.
 * 
 *
 */

class RM_OLP_Options extends RM_Options
{

    //Add extra options
    public function __construct()
    {
        parent::__construct();

        $this->default['ex_olp_send_info_email'] = 'yes';
        $this->default['ex_olp_info'] = RM_OLP_UI_Strings::get('OLP_INFO_EMAIL_DEF_BODY');
        
        //Initialize options' names and sanitizers if any.
        $this->options_name_and_methods['ex_olp_send_info_email'] = 'sanitize_checkbox';
        $this->options_name_and_methods['ex_olp_info'] = null;
    }
    
}
