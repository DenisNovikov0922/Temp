<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_rm_frontend_field_ggeo
 *
 * @author RegistrationMagic
 */
class RM_Frontend_Field_Rating extends RM_Frontend_Field_Base
{
    public function get_pfbc_field()
    {
        if ($this->pfbc_field)
            return $this->pfbc_field;
        else
        {
            if($this->field_model->field_options->rating_conf){
                $rating_conf = (array)$this->field_model->field_options->rating_conf;
                $this->field_options = array_merge($this->field_options, $rating_conf);
            }            
            $field_cls = 'Element_Rating';
            $label = $this->get_formatted_label();
                $this->pfbc_field = new $field_cls($label, $this->field_name, $this->field_options);
                
            return $this->pfbc_field;
        }
    }
    
    public function get_prepared_data($request)
    {
        $data = new stdClass;
        $data->field_id = $this->get_field_id();
        $data->type = $this->get_field_type();
        $data->label = $this->get_field_label();
        $data->value = isset($request[$this->field_name]) ? $request[$this->field_name] : null;
        $rc = $this->field_model->field_options->rating_conf;
        
        $data->meta = (object) array('max_stars' => isset($rc->max_stars) ? $rc->max_stars : 5,
                      'star_face' => isset($rc->star_face) ? $rc->star_face : 'star',
                      'star_color' => isset($rc->star_color) ? $rc->star_color : 'FBC326');
        return $data;
    }
    
}
