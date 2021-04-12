<?php

/*
 * Service class to handle Mailchimp operations
 *
 *
 */

class RM_Aweber_Service extends RM_Services{

    private $aw; 
    
    public function __construct(){
       $opt= new RM_Options();
       $options=$opt->get_all_options();
       try
       {
       $this->aw= new RM_Aweber($options);
       }
        catch(Exception $e)
             {
                 $this->aw=null;
             } 
       
    }
 public function get_list()
     {
     if(isset($this->aw))
     {
          $lists = $this->aw->fetch_list();
          return $lists;
     }
     else
         return null;
     }
     public function subscribe($request,$options)
     {
        
         $aw_relations=$options->aw_relations;
         $list=$options->aw_list;
         $contacts=array();
         foreach($aw_relations as $tag => $field)
         {
             if(isset($request[$field]))
          $contacts[$tag]=$request[$field];
         }
      
         if(isset($this->aw))
         {
      $returnContact= $this->aw->add_contact($contacts,$list);
       
      return $returnContact;
         }
         else
             return null;
     }
    /*
     * list all the mailing lists
     */
   public function aw_field_mapping($form, $form_options, $list = null)
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
                case 'Fname':
                case 'Lname':
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
 public function get_aw_mapping($options) {
        $aw_relations = new stdClass();
        $aw_relations->first_name=$options['first_name'];
        $aw_relations->email=$options['email'];
        $aw_relations->last_name=$options['last_name'];
       return $aw_relations;
    }
}