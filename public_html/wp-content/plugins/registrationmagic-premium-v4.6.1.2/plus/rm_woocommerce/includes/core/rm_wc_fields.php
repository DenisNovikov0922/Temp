<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists( 'RM_WC_Fields' ) ) :

abstract class RM_WC_Fields{
    
    protected $form=null;
    
    abstract public function render();
    
    /**
     * 
     * @param  Array $exclude (Excludes fields on type basis)
     * @param Boolean $render (True if it is called for render purpose)
     * @return Array 
     */
    protected function get_fields($exclude=array(),$render= false){
        global $woocommerce;
        global $post;
        //Initiate form service
        $form_service= new RM_Front_Form_Service();
        $fields = array();
        $form_id= $this->form->get_form_id();
        $form= $this->form;
        $db_fields = $form_service->get_all_form_fields($form_id);
        
        // Removing hidden fields (conditional logics) to skip their validation
        $conditional_fields= array();
        if(!empty($_POST['rm_cond_hidden_fields']))
           $conditional_fields= explode(',',$_POST['rm_cond_hidden_fields']);
        if($db_fields)
        { 
           foreach($db_fields as $db_field)
           {   
               $field_options = $this->get_field_options($db_field);
               $form_options = $form->get_form_options();
               $field_type= str_replace('-','',strtolower($db_field->field_type));
               $field_name= $db_field->field_type."_".$db_field->field_id; 
               if($render && $db_field->is_field_primary==1):
                   continue;
               endif;
               
               if(in_array($field_type,array('wcbilling','wcshipping','wcbillingphone'))){
                    if($render && $post->ID!=get_option('woocommerce_myaccount_page_id'))
                    {   
                        if($field_type=='wcshipping'){
                            if(true === WC()->cart->needs_shipping_address()){
                               continue; 
                            }
                            else
                            {
                                $billing_only= get_option( 'woocommerce_ship_to_destination');
                                if($billing_only=='billing_only'){
                                    continue;
                                }
                            }
                        }
                        else
                            continue;
                    }
                    else
                    {  
                        $address_fields = array();
                        $field_name= strtolower($db_field->field_type)."_".$db_field->field_id; 
                        if(!isset($_POST[$field_name])){
                            if($field_type=='wcbilling'){
                                $address_fields= array('firstname'=>'billing_first_name','lastname'=>'billing_last_name','company'=>'billing_company','add1'=>'billing_address_1','add2'=>'billing_address_2','city'=>'billing_city','state'=>'billing_state','country'=>'billing_country','zip'=>'billing_postcode','phone'=>'billing_phone');
                            }
                            else if($field_type=='wcbillingphone'){
                                $address_fields= array('phone'=>'billing_phone');
                                if(isset($_POST['billing_phone'])){
                                    $_POST[$db_field->field_type."_".$db_field->field_id]= $_POST['billing_phone'];
                                    $_POST[$field_name]= $_POST['billing_phone'];
                                }
                                else
                                {
                                    if(isset($_POST[$db_field->field_type."_".$db_field->field_id])){
                                        $_POST[$field_name]= $_POST[$db_field->field_type."_".$db_field->field_id];
                                    }
                                    
                                }
                                
                            }
                            else{
                                if(!empty($_POST['ship_to_different_address'])){
                                     $address_fields= array('firstname'=>'shipping_first_name','lastname'=>'shipping_last_name','company'=>'shipping_company','add1'=>'shipping_address_1','add2'=>'shipping_address_2','city'=>'shipping_city','state'=>'shipping_state','country'=>'shipping_country','zip'=>'shipping_postcode');
                                }
                            }
                            
                            $address_value= array();
                            if($field_type!='wcbillingphone'){
                                foreach($address_fields as $f_name=>$u_meta){
                                    if(isset($_POST[$u_meta])){
                                        $address_value[$f_name]= $_POST[$u_meta];
                                    }
                                }
                                $_POST[$field_name]= $address_value;
                            }
                            
                            
                         }
                        
                    }
                }
                
                
              
               if(count($exclude)>0 && in_array($field_type, $exclude)):
                   continue;
               endif;
               

               $opts = $form_service->set_properties($field_options);
               
               if(!$render && (in_array($field_name,$conditional_fields) || in_array($field_name.'[]',$conditional_fields))){
                        unset($_POST[$field_name]);
                        continue;
                }
               $opts['id']= $field_name;
               $opts['value']= '';
               
               $field_default_values = maybe_unserialize($field_options->field_default_value);
               if(!empty($field_default_values))
               {
		 if($_SERVER['REQUEST_METHOD'] != 'POST')    
                   $opts['value']= $field_default_values;
               }
               
               if($db_field->is_field_primary!=1 && isset($_POST[$field_name]) && strtolower($field_type)!=="file"):
                       $opts['value'] = $_POST[$field_name];
               endif;

               
               if($db_field->is_field_primary==1):
                   $opts['value'] = isset($_POST['email'])?$_POST['email']:(isset($_POST['billing_email'])?$_POST['billing_email']:null);
               endif;
               
               if(isset($_FILES[$field_name])):
                   $opts['value'] = $_FILES[$field_name];
               endif;
               
               $field_factory= new RM_Field_Factory($db_field,$opts);
               $field_type= str_replace('-','',strtolower($db_field->field_type));
             
               if(is_callable(array($field_factory,"create_".$field_type."_field"))):
                    $fields[$field_name] = call_user_func_array(array($field_factory,"create_".$field_type."_field"),array());
               else:
                   $fields[$field_name] = call_user_func_array(array($field_factory,"create_default_field"),array());
               endif;

           }   
         } 
         //echo '<pre>';
         //print_r($_POST); die;
         return $fields;
         
    }
    
    protected function get_field_options($field)
    {   
        $field_options = (array) maybe_unserialize($field->field_options); 
       
        $cond_option=array();$cond_value=array();$cond_op= array();
        if(!empty($field_options->conditions['rules']) && is_array($field_options->conditions['rules'])){
            $conditions= $field_options->conditions['rules'];
            $values= array();
            foreach($conditions as $condition)
            { 
                $cf_id= $condition['controlling_field'];
                $cf_field= new RM_Fields();
                if($cf_field->load_from_db($cf_id)){
                    $cType= $cf_field->get_field_type();
                    $field_name= $cType.'_'.$cf_id;
                    
                    //Special Fields 
                    if($cType=="Country")
                    {
                        $pfbc_field= new Element_Country("country","country");
                        $country_list= $pfbc_field->getOptions();
                        $country= array_search($condition['values'][0], $country_list);
                        $values= $country;
                    }
                    else if($cType=="Timezone")
                    {
                        $pfbc_field= new Element_Timezone("tz","tz");
                        $timezone_list= $pfbc_field->getOptions();
                        $timezone= array_search($condition['values'][0], $timezone_list);
                        $values= $timezone;
                    }
                    else if($cType=="Language")
                    {
                        $pfbc_field= new Element_Timezone("tz","tz");
                        $lang_list= RM_Utilities::get_language_array();
                        $lang= array_search($condition['values'][0], $lang_list);
                        $values= $lang;
                    }
                    else if($cType=="Checkbox")
                    {  
                        $field_name .='[]'; 
                        $values= $condition['values'][0];
                    }
                    else{
                        $values= $condition['values'][0];
                    }
                    $cond_option[]= $field_name;        
                    $cond_value[]=  empty($values) ? "_" : $values; ;
                    $cond_op[]= $condition['op'];
                }
               
                
            }
            
            if(count($cond_option)>0){
                $field_options['data-cond-option']= implode('|', $cond_option);
                $field_options['data-cond-value']= implode('|', $cond_value);
                $field_options['data-cond-operator']= implode('|', $cond_op);
                $settings= $field_options->conditions['settings'];
                if(count($cond_option)>1)
                $field_options['data-cond-comb']= empty($settings['combinator']) ? 'OR': $settings['combinator'];
                $field_options['class']= "data-conditional";
            }
           
        }
        return (object) $field_options;
    }
    
    abstract public function save_submission($username, $email, $validation_errors);
    
    protected function validate($fields,$validation_errors){ 
        if(count($fields)>0):
            foreach($fields as $field):
                 $field_options= $field->get_field_options();
                 // Handle array values
                  if(is_array($field_options['value']))
                 {   
                      if(empty($field_options['value'][0]) && empty($field_options['value']['original']))
                          $field_options['value']= "";
                 }
                 $field->is_valid($field_options['value'],$this->form->get_form_id(),$validation_errors);
            endforeach;
        endif;
    }
    
    protected function get_db_data($type='dbonly'){
        $fields= $this->get_fields(array('username','userpassword'));
        $form= new RM_Frontend_Form_Contact($this->form);
        $db_data = $form->get_prepared_data($_POST, $type,$fields);
        return $db_data;
    }
    
    protected function render_js(array $fields){
        $urls = array();
        $deps = array();
        foreach($fields as $field):

            $element= $field->get_pfbc_field(); 
            if (is_array($element)) {
                    foreach ($element as $e) {
                        $elementUrls = $e->getJSFiles();
                        $elementDeps = $e->getJSDeps();
                        if (is_array($elementDeps))
                            $deps = array_merge($deps, $elementDeps);

                        if (is_array($elementUrls))
                            $urls = array_merge($urls, $elementUrls);
                    }
                }
                else {
                    $elementUrls = $element->getJSFiles();
                    $elementDeps = $element->getJSDeps();
                    if (is_array($elementDeps))
                        $deps = array_merge($deps, $elementDeps);

                    if (is_array($elementUrls))
                        $urls = array_merge($urls, $elementUrls);
                }

            endforeach;
        
        /* This section prevents duplicate js files from being loaded. */
        if (!empty($urls))
        {

            $urls = array_unique($urls);
            foreach ($deps as $dep)
            {
                if(isset($urls[$dep]))
                {
                echo RM_Utilities::enqueue_external_scripts($dep, $urls[$dep]);
                unset($urls[$dep]);
                }
            }

            foreach ($urls as $handle => $url)
                echo RM_Utilities::enqueue_external_scripts($handle, $url);
        }

     // $element->renderJS();
        
    }
    
    protected function render_css(array $fields){
        $urls = array();
        foreach($fields as $field):
            $element= $field->get_pfbc_field();
            if(is_array($element)){
                foreach($element as $e){
                    $elementUrls = $e->getCSSFiles();
                    if (is_array($elementUrls))
                    $urls = array_merge($urls, $elementUrls);
                }
            }
            else{
                $elementUrls = $element->getCSSFiles();
                    if (is_array($elementUrls))
                    $urls = array_merge($urls, $elementUrls);
            }
            
            
        endforeach;
        
        /* This section prevents duplicate css files from being loaded. */
        if (!empty($urls))
        {
            $urls = array_values(array_unique($urls));
            foreach ($urls as $url)
                echo '<link type="text/css" rel="stylesheet" href="', $url, '"/>';
        }
        //$element->renderCSS();
    }
    
}

endif;
