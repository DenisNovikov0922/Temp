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

class RM_WC_Options extends RM_Options
{

    //Add extra options
    public function __construct()
    {
        parent::__construct();

        $this->default['woo_registration_form'] = null;
        $this->default['woo_enable_cart_in_fab'] = 'yes';
        $this->default['woo_enable_rm_role_override'] = 'no';
        //Initialize options' names and sanitizers if any.
        $this->options_name_and_methods['woo_registration_form'] = null;    
        $this->options_name_and_methods['woo_enable_cart_in_fab'] = 'sanitize_checkbox';
        $this->options_name_and_methods['woo_enable_rm_role_override'] = 'sanitize_checkbox';
    }
    
}
