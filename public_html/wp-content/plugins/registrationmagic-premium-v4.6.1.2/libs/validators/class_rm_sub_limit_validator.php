<?php

class RM_Sub_Limit_Validator implements RM_Validator
{
    protected $field_id;
    protected $form_id;
    
    
    public function __construct($field_id,$form_id) {
        $this->field_id= $field_id;
        $this->form_id= $form_id;
    }
    public function is_valid($value)
    {
        // Checking if user/admin is trying to edit the submission
        if(!empty($_POST['rm_slug'])){
            $req_slug= sanitize_text_field($_POST['rm_slug']);
            if($req_slug=='rm_user_form_edit_sub'){
                return true;
            }               
        }
        
        $front_form_service= new RM_Front_Form_Service();
        $count= RM_DBManager::count("SUBMISSIONS",array('user_email' => $value,'form_id'=>$this->form_id),array('%s','%d'));
        $form= new RM_Forms();
        $form->load_from_db($this->form_id);
        $limit= $form->form_options->sub_limit_ind_user;
        
        if(empty($limit) || $limit>=$count+1)
        {
            return true;
        }

        return false;
    }
}
