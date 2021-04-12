<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RM_Frontend_Field_Bdate extends RM_Frontend_Field_Base
{
     public function __construct($id, $type,$field_name, $label, $options, $value, $page_no = 1, $is_primary = false, $extra_opts = null)
    {
         
        parent::__construct($id, $type,$field_name, $label, $options, $page_no, $is_primary, $extra_opts);
      
        
    }
    public function get_pfbc_field()
    {
        if ($this->pfbc_field)
            return $this->pfbc_field;
        else
        {
            $class_name = "Element_jQueryUIBirthDate" ;
            //$this->field_options['validation']=new Validation_RegExp("/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i", "Error: The %element% must be atleast 7 characters long.");
            $this->set_conditional_properties();
            $label = $this->get_formatted_label();
            $this->pfbc_field = new $class_name($label, $this->field_name, $this->field_options);
            $this->add_custom_validations();
            
            return $this->pfbc_field;
        } 
    }

}
