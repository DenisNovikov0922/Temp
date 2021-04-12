<?php

class RM_Dpx_Options extends RM_Options
{
    private $enc_data_keys= array('dpx_access_token','dpx_client_id','dpx_client_secret');
    public function __construct() {
        parent::__construct();
        $this->options_name_and_methods['dpx_notice_shown']= 0;
        $this->options_name_and_methods['enable_dpx']= 'sanitize_checkbox';
        $this->options_name_and_methods['dpx_client_id']= null; 
        $this->options_name_and_methods['dpx_client_secret']= null;
        $this->options_name_and_methods['dpx_access_token']= null;
    }
    
    public function get_value_of($option)
    {
        
        $value= parent::get_value_of($option);
        if(!empty($value) && in_array($option, $this->enc_data_keys))
        {       
                $value= RM_Utilities::dec_str ($value);
        }
        
        return $value;
    }
    
}
