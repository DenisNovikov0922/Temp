<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RM_Frontend_Field_Time extends RM_Frontend_Field_Base
{
    public function get_prepared_data($request)
    {
        $data = new stdClass;
        $data->field_id = $this->get_field_id();
        $data->type = $this->get_field_type();
        $data->label = $this->get_field_label();
        $time_value = isset($request[$this->field_name]) ? $request[$this->field_name] : null;
        $timezone = isset($request['rm_tp_timezone']) ? $request['rm_tp_timezone'] : null;
        $data->value = array('time'=>$time_value,'timezone' => $timezone);
        return $data;
    }
}

