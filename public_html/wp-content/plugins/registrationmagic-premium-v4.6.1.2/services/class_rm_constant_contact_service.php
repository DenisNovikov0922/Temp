<?php

/*
 * Service class to handle Mailchimp operations
 *
 *
 */

class RM_Constant_Contact_Service extends RM_Services{

    private $cc; 
    
    public function __construct(){
       $opt= new RM_Options();
       $options=$opt->get_all_options();
       try
       {
            $installed_php_version = phpversion();
           // var_dump(version_compare('9.4', $installed_php_version, '<') && RM_REQ_EXT_CURL);die();
           if(version_compare('5.4', $installed_php_version, '<') && RM_REQ_EXT_CURL)
           $this->cc= new RM_CContact($options);
           else
             $this->cc=null;  
       }
        catch(Exception $e)
             {
                 $this->aw=null;
             } 
      
        
    }
 public function get_list()
     {
          if(isset($this->cc))
     {
          $lists = $this->cc->fetch_list();
          return $lists;
     }
     else
         return null;
     }
     public function subscribe($request,$options)
     {
         $cc_relations=$options->cc_relations;
         $list=$options->cc_list;
         $contacts=array();
         foreach($cc_relations as $tag => $field)
         {
             if(isset($request[$field]))
          $contacts[$tag]=$request[$field];
         }
         
     
      if(isset($this->cc))
     {
          $returnContact= $this->cc->add_contact($contacts,$list);
      return $returnContact;
     }
     else
         return null;
     }
    /*
     * list all the mailing lists
     */
   public function cc_field_mapping($form, $form_options, $list = null)
    {
        $service = new RM_Services();
        $all_field_objects = $service->get_all_form_fields($form);
        if (is_array($all_field_objects) || is_object($all_field_objects))
            $form_fields = '';
        $form_fields_email = '';
        $field_type_array = array();
        $field_type_array['string'] = array(''=>"Select a field");
        $field_type_array['email'] = array(''=>"Select a field");
        $field_type_array['date'] = array(''=>"Select a field");
        $field_type_array['address'] = array(''=>"Select a field");
        $field_type_array['number'] = array(''=>"Select a field");
        $field_type_array['phone'] = array(''=>"Select a field");
        foreach ($all_field_objects as $obj) {
            $field_type = $obj->field_type;


            switch ($field_type) {
                case 'Textbox':
                case 'Country':
                case 'Fname':
                case 'Lname':
                case 'BInfo':
                case 'Number':
                    $field_type = 'string';
                    $form_type_id = $obj->field_type . '_' . $obj->field_id; //
                    $field_type_array[$field_type][$form_type_id] = $obj->field_label;
                    break;
                case 'Email':
                case 'SecEmail':
                    $field_type = 'email';
                    $form_type_id = $obj->field_type . '_' . $obj->field_id; //
                    $field_type_array[$field_type][$form_type_id] = $obj->field_label;
                    break;
                case 'jQueryUIDate':
                case 'jQueryUIBirthDate':
                    $field_type = 'date';
                    $form_type_id = $obj->field_type . '_' . $obj->field_id; //
                    $field_type_array[$field_type][$form_type_id] = $obj->field_label;
                    break;
                case 'Price':
                    $field_type = 'number';
                    $form_type_id = $obj->field_type . '_' . $obj->field_id; //
                    $field_type_array[$field_type][$form_type_id] = $obj->field_label;
                    break;
                case 'Phone':
                case 'Mobile':
                    $field_type = 'phone';
                    $form_type_id = $obj->field_type . '_' . $obj->field_id; //
                    $field_type_array[$field_type][$form_type_id] = $obj->field_label;
                    break;
                case 'Address':
                    $field_type = 'address';
                    $form_type_id = $obj->field_type . '_' . $obj->field_id; //
                    $field_type_array[$field_type][$form_type_id] = $obj->field_label;
                    break;
            }
            //$data->all_fields[$obj->field_type . '_' . $obj->field_id] = $obj->field_label;
        }
return $field_type_array;
    }
 public function get_cc_mapping($options) {
        $cc_relations = new stdClass();
        $cc_relations->first_name=$options['first_name'];
       $cc_relations->email=$options['email'];
       $cc_relations->last_name=$options['last_name'];
       $cc_relations->middle_name=$options['middle_name'];
       $cc_relations->company_name=$options['company_name'];
       $cc_relations->job_title=$options['job_title'];
       $cc_relations->work_phone=$options['work_phone'];
       $cc_relations->cell_phone=$options['cell_phone'];
       $cc_relations->home_phone=$options['home_phone'];
      // $cc_relations->fax=$options['fax'];
       //$cc_relations->address=$options['address'];
      // $cc_relations->created_date=$options['created_date'];
       return $cc_relations;
    }
}