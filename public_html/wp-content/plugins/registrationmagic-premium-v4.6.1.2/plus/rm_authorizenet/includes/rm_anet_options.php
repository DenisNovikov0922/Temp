<?php

class RM_Anet_Options extends RM_Options
{
    private $enc_data_keys= array('anet_login_id','anet_trans_key','anet_sign_key');
    public function __construct() {
        parent::__construct();
        
        $this->options_name_and_methods['anet_login_id']= null;
        $this->options_name_and_methods ['anet_trans_key']= null;
        $this->options_name_and_methods['anet_sign_key']= null;
        $this->options_name_and_methods ['anet_test_mode']= 'sanitize_checkbox';                
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
