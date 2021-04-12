<?php
/*
 * Service class to handle Mailchimp operations
 *
 *
 */
class RM_Mailpoet_Service {
    
    public $mailpoet_active= false;
    
    public function __construct() {
      $this->mailpoet_active= rm_is_mailpoet_active();
    }
    /*
     * list all the mailing lists
     */
    public function get_list($dropdown= false) {
        if(!$this->mailpoet_active)
            return array();
        
        $model_list = WYSIJA::get('list','model');
        $mailpoet_lists = $model_list->get(array('name','list_id'),array('is_enabled'=>1));
            
        if($dropdown)
        {   $list_dropdown= array();
            foreach($mailpoet_lists as $list){
                $list_dropdown[$list['list_id']]= $list['name'];
            } 
            return $list_dropdown;
        }
        return $mailpoet_lists;
               
    }
    
    /*
     * List all the forms
     */
     public function get_forms($dropdown= false) {
        if(!$this->mailpoet_active)
            return array();
        $forms = RM_DBManager::get_mailpoet_forms();
        if($dropdown)
        {   $form_dropdown= array(''=>'Select Form');
            foreach($forms as $form){
                $form_dropdown[$form->id]= $form->name;
            } 
            return $form_dropdown;
        }
        return $forms;     
    }
    
    /*
     * Get Form by ID
     */
    public function get_form($form_id)
    {
        if(!$this->mailpoet_active)
            return array();
        
        return RM_DBManager::get_mailpoet_fields($form_id);
    }
    /*
     * Get Form Fields by Form ID
     */
    public function get_fields_by_form($form_id)
    {
        $form= $this->get_form($form_id);
        $fields= array();
        if(!empty($form))
        {
            foreach($form as $j => $block) {
                if(in_array($block['id'], array('submit','divider','html')))
                    continue;
                $fields[$block['id']]= array($block['name'],$block['type']);
            }
        }
        return $fields;
    }
   
     public function get_supported_rm_fields($form,$type,$dropdown=false)
     {
         $form_service= new RM_Services();
         $form_fields = $form_service->get_all_form_fields($form);
         
         $fields= array();
         if($dropdown):
            $fields[""]= "Select a Field";  
           
            foreach($form_fields as $form_field)
            {   
                $insert_field= false;
                if(($type=="input" || $type=="text") && in_array(strtolower($form_field->field_type),array('email','textbox','nickname','secemail','lname','fname')))
                {
                    $insert_field= true;
                }
                
                else if($type=="textarea" && in_array(strtolower($form_field->field_type),array('textarea','binfo')))
                {
                    $insert_field= true;
                }
                
                else if($type=="radio" && in_array(strtolower($form_field->field_type),array('radio')))
                {
                    $insert_field= true;
                }
                
                
                else if($type=="select" && in_array(strtolower($form_field->field_type),array('select')))
                {
                    $insert_field= true;
                }
                
                else if($type=="date" && in_array(strtolower($form_field->field_type),array('jqueryuidate','bdate')))
                {
                    $insert_field= true;
                }
                
                else if($type=="checkbox" && in_array(strtolower($form_field->field_type),array('checkbox')))
                {
                    $insert_field= true;
                }

                if($insert_field)
                    $fields[$form_field->field_type.'_'.$form_field->field_id]= $form_field->field_label;
            }
            return $fields;
         endif;
         return $form_fields;
     }
     
     public function map_fields($request)
     {
         $data= array();
         $enable_mailpoet= isset($request['enable_mailpoet']) ? (int) $request['enable_mailpoet'] : null;
         $mailpoet_form= isset($request['mailpoet_form']) ? (int) $request['mailpoet_form'] : null;
         if(empty($mailpoet_form) && empty($enable_mailpoet))
             return null;
        
         $mailpoet_f_fields= $this->get_fields_by_form($mailpoet_form);
         if(count($mailpoet_f_fields))
         {
             foreach($mailpoet_f_fields as $key=>$value)
             {
                 $data[$key]= isset($request[$key]) ? $request[$key] : null;
             }
             
         }
         return $data;
     }
     
     public function subscribe($data=array(),$mp_form_id)
     {
        $user_fields = array();
        foreach($data as $key=>$value)
        {
            if(!in_array($key, array('email','first_name','last_name')))
            {
                //$user_fields['cf_'.$key]= $value;
                if(!is_array($value)){
                    $user_fields['cf_'.$key]= $value;
                }else{
                    foreach($value as $arr_key=>$arr_val){
                        if($arr_val!=''){
                            $user_fields['cf_'.$key]= 1;
                        }
                    }    
                }
            }
        }
       
        //in this array firstname and lastname are optional
        $user_data = array(
            'email' => $data['email'],
            'first_name' => isset($data['first_name']) ? $data['first_name'] : '',
            'last_name' => isset($data['last_name']) ? $data['last_name'] : ''
        );
        $subscriber_data = array_merge($user_data,$user_fields);
        $lists = $this->get_lists_by_form_id($mp_form_id);
        try {
            $subscriber = \MailPoet\API\API::MP('v1')->addSubscriber($subscriber_data, $lists);
        } catch(Exception $exception) {
            //echo '<pre>';print_r($exception->getMessage());
            // return $exception->getMessage();
        }
     }
     
     public function get_lists_by_form_id($form_id)
     {
        return RM_DBManager::get_mailpoet_lists($form_id);
     }
        
}