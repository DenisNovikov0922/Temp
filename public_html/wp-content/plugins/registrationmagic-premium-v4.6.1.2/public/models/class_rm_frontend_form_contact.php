<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RM_Frontend_Form_Contact extends RM_Frontend_Form_Multipage//RM_Frontend_Form_Base
{

    public function __construct(RM_Forms $be_form, $ignore_expiration=false)
    {
        parent::__construct($be_form, $ignore_expiration);
        $this->set_form_type(RM_CONTACT_FORM);
    }

    public function pre_sub_proc($request, $params)
    {  
        return true;
    }

    public function post_sub_proc($request, $params)
    {
        if(isset($params['paystate']) && $params['paystate'] != 'post_payment')      
            if ($this->service->get_setting('enable_mailchimp') == 'yes' && $this->form_options->enable_mailchimp[0]==1)
        {
            $form_options_mc=  $this->form_options;
           
          if ($form_options_mc->form_is_opt_in_checkbox == 1 || (isset($form_options_mc->form_is_opt_in_checkbox[0]) && $form_options_mc->form_is_opt_in_checkbox[0] == 1))
        $should_subscribe = isset($request['rm_subscribe_mc']) && $request['rm_subscribe_mc'][0] == 1 ? 'yes' : 'no';
        else
        $should_subscribe = 'yes';
        if($should_subscribe == 'yes'){
             try
             {
           $this->service->subscribe_to_mailchimp($request, $this->get_form_options());
             }
             catch(Exception $e)
             {
                
             } 
                  
        }
             
        }
        if ($this->service->get_setting('enable_ccontact') == 'yes' && $this->form_options->enable_ccontact[0]==1)
        {
            $form_options_mc=  $this->form_options;
           
          if ($form_options_mc->form_is_opt_in_checkbox_cc[0] == 1)
        $should_subscribe = isset($request['rm_subscribe_cc']) && $request['rm_subscribe_cc'][0] == 1 ? 'yes' : 'no';
        else
        $should_subscribe = 'yes';
      
        if($should_subscribe == 'yes'){
           
           
            try
             {
           $this->service->subscribe_to_ccontact($request, $this->get_form_options());
             }
             catch(Exception $e)
             {
                
             } 
        }
        }
        
        do_action('rm_subscribe_newsletter',$this->get_form_id(),$request);
        if ($this->service->get_setting('enable_aweber') == 'yes' && $this->form_options->enable_aweber[0]==1)
        {
            $form_options_mc=  $this->form_options;
           
          if ($form_options_mc->form_is_opt_in_checkbox_aw[0] == 1)
        $should_subscribe = isset($request['rm_subscribe_aw']) && $request['rm_subscribe_aw'][0] == 1 ? 'yes' : 'no';
        else
        $should_subscribe = 'yes';
      
        if($should_subscribe == 'yes'){
           try
             {
           $this->service->subscribe_to_aweber($request, $this->get_form_options());
             }
             catch(Exception $e)
             {
                
             } 
           
        }
        }
        
        return null;
    }

    protected function hook_post_field_addition_to_page($form, $page_no,$editing_sub=null)
    {
        $last_page_no = max(array_keys($this->form_pages))+1;
        if ($last_page_no == $page_no)
        { 
            if ($this->has_price_field() && !$editing_sub)
                $this->add_payment_fields($form);
            
           $check_setting=null;
           if($this->form_options->enable_captcha=='default')
            {
                $check_setting=get_option('rm_option_enable_captcha');
            }
            else
            {
                $check_setting=$this->form_options->enable_captcha;
            }
          
            if ($check_setting == 'yes')   
                $form->addElement(new Element_Captcha());

         if ($this->service->get_setting('enable_mailchimp') == 'yes' && $this->form_options->form_is_opt_in_checkbox == 1 && $this->form_options->enable_mailchimp[0] == 1 && !$editing_sub)
            {
                //This outer div is added so that the optin text can be made full width by CSS.
                $form->addElement(new Element_HTML('<div class="rm_optin_text">'));
                
                if($this->form_options->form_opt_in_default_state == 'Checked')
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_mc', array(1 => $this->form_options->form_opt_in_text ? : RM_UI_Strings::get('MSG_SUBSCRIBE')),array("value"=>1)));
                else 
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_mc', array(1 => $this->form_options->form_opt_in_text ? : RM_UI_Strings::get('MSG_SUBSCRIBE'))));
            
                $form->addElement(new Element_HTML('</div>'));
            }
            
            do_action('rm_show_subscribe_opt',$this->form_id,$form,$editing_sub);
            
            if ($this->service->get_setting('enable_ccontact') == 'yes' && $this->form_options->form_is_opt_in_checkbox_cc[0] == 1 && $this->form_options->enable_ccontact[0] == 1 && !$editing_sub)
           {
                //This outer div is added so that the optin text can be made full width by CSS.
                $form->addElement(new Element_HTML('<div class="rm_optin_text">'));
                
                if($this->form_options->form_opt_in_default_state_cc == 'Checked')
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_cc', array(1 => $this->form_options->form_opt_in_text_cc ? : RM_UI_Strings::get('MSG_SUBSCRIBE')),array("value"=>1)));
                else 
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_cc', array(1 => $this->form_options->form_opt_in_text_cc ? : RM_UI_Strings::get('MSG_SUBSCRIBE'))));
                
                $form->addElement(new Element_HTML('</div>'));
            }
            if ($this->service->get_setting('enable_aweber') == 'yes' && $this->form_options->form_is_opt_in_checkbox_aw[0] == 1 && $this->form_options->enable_aweber[0] == 1 && !$editing_sub)
           {
                //This outer div is added so that the optin text can be made full width by CSS.
                $form->addElement(new Element_HTML('<div class="rm_optin_text">'));
                
                if($this->form_options->form_opt_in_default_state_aw == 'Checked')
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_aw', array(1 => $this->form_options->form_opt_in_text_aw ? : RM_UI_Strings::get('MSG_SUBSCRIBE')),array("value"=>1)));
                else 
                    $form->addElement(new Element_Checkbox('', 'rm_subscribe_aw', array(1 => $this->form_options->form_opt_in_text_aw ? : RM_UI_Strings::get('MSG_SUBSCRIBE'))));
            
                $form->addElement(new Element_HTML('</div>'));
           }
            
            if($this->form_options->show_total_price)
            {
                $gopts = new RM_Options;
                $total_price_localized_string = RM_UI_Strings::get('FE_FORM_TOTAL_PRICE');
                $curr_symbol = $gopts->get_currency_symbol();
                $curr_pos = $gopts->get_value_of('currency_symbol_position');
                $price_formatting_data = json_encode(array("loc_total_text" => $total_price_localized_string, "symbol" => $curr_symbol, "pos" => $curr_pos));
                $form->addElement(new Element_HTML("<div class='rmrow rm_total_price' style='{$this->form_options->style_label}' data-rmpriceformat='$price_formatting_data'></div>"));
            }
       
        
        }
    }

    protected function base_render($form,$editing_sub=null)
    {
        $this->prepare_fields_for_render($form,$editing_sub);
        
        $this->prepare_button_for_render($form,$editing_sub);

        if (count($this->fields) !== 0)
            $form->render();
        else
            echo RM_UI_Strings::get('MSG_NO_FIELDS');
    }

    protected function get_prepared_data_primary($request)
    {
        $data = array();

        foreach ($this->fields as $field)
        {
            if ($field->get_field_type() == 'Email' && $field->is_primary())
            {
                $field_data = $field->get_prepared_data($request);

                $data['user_email'] = (object) array('label' => $field_data->label,
                            'value' => $field_data->value,
                            'type' => $field_data->type);

                break;
            }
        }
        return $data;
    }

    protected function get_prepared_data_dbonly($request,$fields=null)
    {
        $data = array();
        
        if($fields!=null):
            $this->fields= $fields;
        endif;
        
        foreach ($this->fields as $field)
        {  
           //if (in_array($field->get_field_type(),array('Spacing','Timer')) /*$field->get_field_type() == 'HTMLH' || $field->get_field_type() == 'HTMLP'|| $field->get_field_type() == 'HTML'|| $field->get_field_type() == 'HTMLCustomized'|| */)
            if (in_array($field->get_field_type(),RM_Utilities::csv_excluded_widgets()))
            {
                continue;
            }

            $field_data = $field->get_prepared_data($request);
            /*if($field->get_field_type()=="HTMLCustomized"){
               $html_field= new RM_Fields();
               $html_field->load_from_db($field->get_field_id());
               $field_data->value= $html_field->get_field_value();
               
               if(strtolower($html_field->get_field_type())=="link")
               {    
                    $field_options=  $html_field->field_options;
                    $field_data->value= $html_field->field_options->link_type=="url" ? $html_field->field_options->link_href : get_permalink($html_field->field_options->link_page);
               }
            }*/
            
            if ($field_data === null)
                continue;
            $data[$field_data->field_id] = (object) array('label' => $field_data->label,
                        'value' => $field_data->value,
                        'type' => $field_data->type,
                        'meta' => isset($field_data->meta)?$field_data->meta:null);
        }

        return $data;
    }

}
