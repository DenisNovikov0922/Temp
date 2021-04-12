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
        
        
        $model_form = WYSIJA::get('forms','model');
        $forms= $model_form->getRows();
        if($dropdown)
        {   $form_dropdown= array();
            foreach($forms as $form){
                $form_dropdown[$form['form_id']]= $form['name'];
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
        
        $model_forms = WYSIJA::get('forms', 'model');
        $forms= $model_forms->getRows();
        foreach($forms as $form){
            if($form['form_id']==$form_id)
                return $form;
        } 
        return null;
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
            // decode form data
            $data = unserialize(base64_decode($form['data']));
            // loop through each block
            foreach($data['body'] as $j => $block) {
              if(in_array($block['field'], array('submit','divider','html')))
                  continue;
              $fields[$block['field']]= array($block['name'],$block['type']);
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
                if($type=="input" && in_array(strtolower($form_field->field_type),array('email','textbox','nickname','secemail','lname','fname')))
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
            if(!in_array($key, array('email','firstname','lastname')))
            {
                $user_fields[$key]= $value;
            }
        }
       
        //in this array firstname and lastname are optional
        $user_data = array(
            'email' => $data['email'],
            'firstname' => isset($data['firstname']) ? $data['firstname'] : '',
            'lastname' => isset($data['lastname']) ? $data['lastname'] : '');
        $data_subscriber = array(
          'user' => $user_data,
          'user_field'=> $user_fields,   
          'user_list' => array('list_ids' => $this->get_lists_by_form_id($mp_form_id))
        );
       
       
        $helper_user = WYSIJA::get('user','helper');
        $helper_user->addSubscriber($data_subscriber);
     }
     
     public function get_lists_by_form_id($form_id)
     {
        $form= $this->get_form($form_id);
        $lists= array();
        if(!empty($form))
        {
            // decode form data
            $data = unserialize(base64_decode($form['data']));
            $lists= $data['settings']['lists'];
            if(is_array($lists))
                return $lists;
        }
        return $lists;
     }
        
}