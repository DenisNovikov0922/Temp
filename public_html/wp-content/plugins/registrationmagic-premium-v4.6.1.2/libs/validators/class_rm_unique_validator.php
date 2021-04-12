<?php

class RM_Unique_Validator implements RM_Validator
{
    protected $field_id;
    protected $exclude_value;
    
    public function __construct($field_id,$exclude_value='') {
        $this->field_id= $field_id;
        $this->exclude_value= $exclude_value;
    }
    public function is_valid($value)
    {
        if(empty($value)) return true;
        if(!empty($this->exclude_value) && $value==$this->exclude_value)
        {
            return true;
        }
        
        $count= RM_DBManager::count("SUBMISSION_FIELDS",array('field_id' => $this->field_id,'value'=>$value),array('%d','%s'));
        return $count>0?false: true;
    }
}
