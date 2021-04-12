<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RM_Frontend_Form_Multipage extends RM_Frontend_Form_Base
{

    protected $form_pages;
    protected $ordered_form_pages;
    
    public function __construct(RM_Forms $be_form, $ignore_expiration=false)
    {
        parent::__construct($be_form, $ignore_expiration);

        if ($this->form_options->form_pages == null)
        {
            $this->form_pages = array('Page 1');
            $this->ordered_form_pages = array(0);
        }
        else
        {
            $this->form_pages = $this->form_options->form_pages;
            if ($this->form_options->ordered_form_pages == null)
                $this->ordered_form_pages = array_keys($this->form_pages);
            else
                $this->ordered_form_pages = $this->form_options->ordered_form_pages;
        }
    }

    public function get_form_pages()
    {
        return $this->form_pages;
    }

    public function pre_sub_proc($request, $params)
    {
        return true;
    }

    public function post_sub_proc($request, $params)
    {
        return true;
    }
    
    //Following two methods can be overloaded by child classes in order to add custom fields to any page of the form.
    protected function hook_pre_field_addition_to_page($form, $page_no)
    {
        
    }
    
    protected function hook_post_field_addition_to_page($form, $page_no,$editing_sub=null)
    {
        
    }
    
    public function render($data = array())
    {
        $editing_sub=false;
        $settings = new RM_Options;
        $theme = $settings->get_value_of('theme');
        $layout = $settings->get_value_of('form_layout');
        $class= "rm_theme_{$theme} rm_layout_{$layout}"; 
        echo '<div class="rmagic '.$class.'">';
        
        //$this->form_number = $rm_form_diary[$this->form_id];
        $form = new RM_PFBC_Form('form_' . $this->form_id . "_" . $this->form_number);
        $btn_align_class = "rmagic-form-btn-".(isset($this->form_options->form_btn_align)?$this->form_options->form_btn_align:"center");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery", "focus"),
            "action" => "",/*add_query_arg('rmcb', time()),*/
            "class" => "rmagic-form $btn_align_class",
            "name" => "rm_form",
            "number" => $this->form_number,
            "view" => ($layout == 'two_columns')? new View_UserFormTwoCols: new View_UserForm,
            "style" => isset($this->form_options->style_form)?$this->form_options->style_form:null
        ));
        
        //Render content above the form
        if (!empty($this->form_options->form_custom_text))
                $form->addElement(new Element_HTML('<div class="rmheader">' . $this->form_options->form_custom_text . '</div>'));

       
        //check if form has expired
        $edit_submission= false;
        if($_POST && !empty($_POST['rm_slug']) && $_POST['rm_slug']=='rm_user_form_edit_sub'){
            $edit_submission= true;
        }
        if (!$this->preview && empty($edit_submission) && $this->is_expired())
        {
            if ($this->form_options->form_message_after_expiry)
                echo $this->form_options->form_message_after_expiry;
            else
                echo '<div class="rm-no-default-from-notification">'.RM_UI_Strings::get('MSG_FORM_EXPIRY').'</div>';
            echo '</div>';
            return;
        }

        if (isset($data['stat_id']) && $data['stat_id'])
        {
            $form->addElement(new Element_HTML('<div id="rm_stat_container" style="display:none">'));
            $form->addElement(new Element_Textbox('RM_Stats', 'stat_id', array('value' => $data['stat_id'], 'style' => 'display:none')));
            $form->addElement(new Element_HTML('</div>'));
            $editing_sub=false;
        }
        
        if (isset($data['submission_id']) && $data['submission_id'])
        {
            $form->addElement(new Element_HTML('<div id="rm_stdat_container" style="display:none">'));
            $form->addElement(new Element_Textbox('RM_Slug', 'rm_slug', array('value' => 'rm_user_form_edit_sub', 'style' => 'display:none')));
            $form->addElement(new Element_Textbox('RM_form_id', 'form_id', array('value' => $this->form_id, 'style' => 'display:none')));
            $form->addElement(new Element_HTML('</div>'));
            $editing_sub=true;
        }
        
        parent::pre_render( );
        $this->base_render($form,$editing_sub);
        parent::post_render();

        echo '</div>';
    }
    
    public function get_form_object($data = array())
    {  
        $settings = new RM_Options;
        $theme = $settings->get_value_of('theme');
        $layout = $settings->get_value_of('form_layout');
        $class= "rm_theme_{$theme} rm_layout_{$layout}"; 
        
        //$this->form_number = $rm_form_diary[$this->form_id];
        $form_model = new RM_PFBC_Form('form_' . $this->form_id . "_" . $this->form_number);
        
        $form_model->configure(array(
            "prevent" => array("bootstrap", "jQuery", "focus"),
            "action" => "",
            "class" => "rmagic-form",
            "name" => "rm_form",
            "number" => $this->form_number,
            "view" => ($layout == 'two_columns')? new View_UserFormTwoCols: new View_UserForm,
            "style" => isset($this->form_options->style_form)?$this->form_options->style_form:null
        ));
        
        //Render content above the form
        if (!empty($this->form_options->form_custom_text))
                $form_model->addElement(new Element_HTML('<div class="rmheader">' . $this->form_options->form_custom_text . '</div>'));

        $form_model->addElement(new Element_HTML('<div id="rm_stat_container" style="display:none">'));
        $form_model->addElement(new Element_Textbox('RM_Stats', 'stat_id', array('value' => "__form_model", 'style' => 'display:none')));
        $form_model->addElement(new Element_HTML('</div>'));
        
        //Since pre-render only adds style and expiry countdown no need to call it.
        //parent::pre_render();
        //$this->base_render($form_model);
        $this->prepare_fields_for_render($form_model);
        if (get_option('rm_option_enable_captcha') == "yes" && $this->form_options->enable_captcha[0]=='yes')
            $form_model->addElement(new Element_Captcha());
        //$this->prepare_button_for_render($form_model);
        //Nothing special in post render for now, do not call.
        //parent::post_render();
        return $form_model;
    }

    protected function prepare_fields_for_render($form,$editing_sub=null)
    { 
        $form->addElement(new Element_Hidden("rm_tp_timezone","",array("id"=>"id_rm_tp_timezone")));     
        $n = 1; //page no(ordinal no. not actual) maintained for js traversing through pages.
        
        foreach ($this->ordered_form_pages as $fp_no)
        {   $k = intval($fp_no);
            $page = $this->form_pages[$fp_no];
            $i = $k+1;//actual page no.
            if ($n == 1)
            {
               $form->addElement(new Element_HTML("<div class=\"rmformpage_form_".$this->form_id."_".$this->form_number."\" id=\"rm_form_page_form_".$this->form_id ."_".$this->form_number. "_".$n."\">"));
               $form->addElement(new Element_HTML("<fieldset class='rmfieldset'>"));
               
               if(count($this->form_pages) > 1)
                 $form->addElement(new Element_HTML("<legend style='".$this->form_options->style_section."'>".$page."</legend>"));
               $this->hook_pre_field_addition_to_page($form, $n);
           
                    foreach ($this->fields as $field)
                    {
                        if(is_array($field)){
                           foreach($field as $single_field){ 
                               $pf = $single_field->get_pfbc_field();
                                if ($pf === null || $single_field->get_page_no() != $i)
                                    continue;

                                if (is_array($pf))
                                {
                                    foreach ($pf as $f)
                                    {
                                        if (!$f)
                                            continue;
                                        $form->addElement($f);
                                    }
                                } else
                                    $form->addElement($pf);
                           }
                           continue;
                       }                       
                        $pf = $field->get_pfbc_field();                            
                        if ($pf === null || $field->get_page_no() != $i)
                            continue;

                        if (is_array($pf))
                        {
                            foreach ($pf as $f)
                            {
                                if (!$f)
                                    continue;
                                $form->addElement($f);
                            }
                        } else
                            $form->addElement($pf);
                        
                    }
                    
                    $this->hook_post_field_addition_to_page($form, $n, $editing_sub);
                    $form->addElement(new Element_HTML("</fieldset>"));
                    $form->addElement(new Element_HTML("</div>"));
                    
            } else
            {
                $form->addElement(new Element_HTML("<div class=\"rm_form_page rmformpage_form_".$this->form_id."_".$this->form_number."\"id=\"rm_form_page_form_".$this->form_id ."_".$this->form_number. "_".$n."\" style=\"display:none\">"));
               $form->addElement(new Element_HTML("<fieldset class='rmfieldset'>"));
                 $form->addElement(new Element_HTML("<legend style='".$this->form_options->style_section."'>".$page."</legend>"));
               
                ?>
                
                    <?php
                    $this->hook_pre_field_addition_to_page($form, $n);
                    foreach ($this->fields as $field)
                    {
                        if(is_array($field)){
                           foreach($field as $single_field){ 
                               $pf = $single_field->get_pfbc_field();
                                if ($pf === null || $single_field->get_page_no() != $i)
                                    continue;

                                if (is_array($pf))
                                {
                                    foreach ($pf as $f)
                                    {
                                        if (!$f)
                                            continue;
                                        $form->addElement($f);
                                    }
                                } else
                                    $form->addElement($pf);
                           }
                           continue;
                       }
                        $pf = $field->get_pfbc_field();

                        if ($pf === null || $field->get_page_no() != $i)
                            continue;

                        if (is_array($pf))
                        {
                            foreach ($pf as $f)
                            {
                                if (!$f)
                                    continue;
                                $form->addElement($f);
                            }
                        } else
                            $form->addElement($pf);
                    }
                    $this->hook_post_field_addition_to_page($form, $n, $editing_sub);
                    $form->addElement(new Element_HTML("</fieldset>"));
                    $form->addElement(new Element_HTML("</div>"));          
            }

            $n++;
        }

        
    }
    
    protected function prepare_button_for_render($form,$editing_sub=null)
    {
        if ($this->service->get_setting('theme') != 'matchmytheme')
        {
            if(isset($this->form_options->style_btnfield))
                unset($this->form_options->style_btnfield);
        }
        $sub_btn_label = $this->form_options->form_submit_btn_label ? stripslashes($this->form_options->form_submit_btn_label) : __( 'Submit', 'registrationmagic-gold' );
        $prev_btn_label = $this->form_options->form_prev_btn_label ? stripslashes($this->form_options->form_prev_btn_label) : RM_UI_Strings::get('LABEL_PREV_FORM_PAGE');
        $next_btn_label = $this->form_options->form_next_btn_label ? stripslashes($this->form_options->form_next_btn_label) : __( 'Next', 'registrationmagic-gold' );
        $max_pages = count($this->get_form_pages());
        
        $sub_btn_label = !empty($editing_sub) ? __('Update','registrationmagic-gold') : $sub_btn_label;
        $btn_label = ($max_pages > 1) ? $next_btn_label : $sub_btn_label;
        
        if($max_pages > 1 && !$this->form_options->no_prev_button)
            $form->addElement(new Element_Button($prev_btn_label, "button", array("style" => isset($this->form_options->style_btnfield)?$this->form_options->style_btnfield:null,"name"=>"rm_prev_btn",'class'=>'rm_prev_btn', "id"=>"rm_prev_form_page_button_".$this->form_id.'_'.$this->form_number, "onclick"=>'gotoprev_form_'.$this->form_id.'_'.$this->form_number.'()', "disabled"=>"1")));
        
        
        $form->addElement(new Element_Button($btn_label, "submit", array("name"=>"rm_sb_btn","class"=>"rm_next_btn","data-label-next" => $next_btn_label,"data-label-sub" => $sub_btn_label,  "style" => isset($this->form_options->style_btnfield)?$this->form_options->style_btnfield:null)));
        $form->addElement(new Element_Button(stripslashes($sub_btn_label), "submit", array(
 "style" => isset($this->form_options->style_btnfield)?$this->form_options->style_btnfield:null,"class"=>"rm_noscript_btn")));
        
        $this->insert_JS($form);
    }
    
    protected function get_jqvalidator_config_JS()
    { $error= RM_Utilities::js_error_messages();
$str = <<<JSHD
        jQuery.extend(jQuery.validator.messages, {
            required:"{$error['required']}",
        });
        jQuery.validator.setDefaults({errorClass: 'rm-form-field-invalid-msg',
                                        ignore:':hidden,.ignore,:not(:visible),.rm_untouched', wrapper:'div',
                                        errorPlacement: function(error, element) { 

                                                            var elementId= element.attr('id');
                                                            if(elementId){
                                                                var target_element_id= elementId.replace('-error','');
                                                                var target_element= jQuery("#" + target_element_id);
                                                                if(target_element.length>0){
                                                                    if(target_element.hasClass('rm_untouched')){
                                                                        return true;
                                                                        }
                                                                }
                                                            }
                                                                
                                                            
                                                            error.appendTo(element.closest('div'));
                                                          }
                                    });
JSHD;
        return $str;
    }

    protected function insert_JS($form)
    {
        if(is_admin() && !(isset($_GET['action']) && $_GET['action']=='registrationmagic_embedform')) // Restricting front js loading in dashboard.
            return;
        $next_btn_label = $this->form_options->form_next_btn_label ? stripslashes($this->form_options->form_next_btn_label) : __( 'Next', 'registrationmagic-gold' );
        $max_page_count = count($this->get_form_pages());
        $form_identifier = "form_".$this->get_form_id();
        $form_id = $this->get_form_id();
        $validator_js = $this->get_jqvalidator_config_JS();
        
      
        $jqvalidate = RM_Utilities::enqueue_external_scripts('rm_jquery_validate', RM_BASE_URL."public/js/jquery.validate.min.js");
      
        $jqvalidate .= RM_Utilities::enqueue_external_scripts('rm_jquery_validate_add', RM_BASE_URL."public/js/additional-methods.min.js");
        $jq_front_form_script = RM_Utilities::enqueue_external_scripts('rm_front_form_script', RM_BASE_URL."public/js/rm_front_form.js");
        wp_enqueue_script('rm_front');
        wp_enqueue_script('rm_jquery_conditionalize');
        $mainstr = <<<JSHD
                
   <pre class='rm-pre-wrapper-for-script-tags'><script>
        
   /*form specific onload functionality*/
jQuery(document).ready(function () {
if(jQuery("#{$form_identifier}_{$this->form_number} [name='rm_payment_method']").length>0 && jQuery("#{$form_identifier}_{$this->form_number} [name='rm_payment_method']:checked").val()=='stripe'){jQuery('#rm_stripe_fields_container_{$form_id}_{$this->form_number}').show();}

    jQuery('[data-rm-unique="1"]').change(function(event) {
        rm_unique_field_check(jQuery(this));
    });
    
   });
                
if (typeof window['rm_multipage'] == 'undefined') {

    rm_multipage = {
        global_page_no_{$form_identifier}_{$this->form_number}: 1
    };

}
else
 rm_multipage.global_page_no_{$form_identifier}_{$this->form_number} = 1;

function gotonext_{$form_identifier}_{$this->form_number}(){
        var maxpage = {$max_page_count} ;
        {$validator_js}        
        
        var jq_prev_button = jQuery("#rm_prev_form_page_button_{$form_id}_{$this->form_number}");
        var jq_next_button = jQuery("#rm_next_form_page_button_{$form_id}_{$this->form_number}");
        
        var next_label = jq_next_button.data("label-next");
        var payment_method = jQuery('[name=rm_payment_method]:checked').val();
        var form_object= jQuery("#rm_form_page_{$form_identifier}_{$this->form_number}_"+rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}).closest("form");
        var submit_btn= form_object.find("[type=submit]:not(.rm_noscript_btn)");
        var sub_label = submit_btn.data("label-sub");
        if(form_object.find('.rm_privacy_cb').is(':visible') && !form_object.find('.rm_privacy_cb').prop('checked')){
             form_object.find('.rm_privacy_cb').trigger('change');
             return false;
        } 
        if(typeof payment_method == 'undefined' || payment_method != 'stripe')
        {            
            elements_to_validate = jQuery("#rm_form_page_{$form_identifier}_{$this->form_number}_"+rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}+" :input").not('#rm_stripe_fields_container_{$form_id}_{$this->form_number} :input');
        }
        else
            var elements_to_validate = jQuery("#rm_form_page_{$form_identifier}_{$this->form_number}_"+rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}+" :input");
        
        
        if(elements_to_validate.length > 0)
        {
            var valid = elements_to_validate.valid();  
            elements_to_validate.each(function(){
            var if_mobile= jQuery(this).attr('data-mobile-intel-field');
                if(if_mobile){
                    var tel_error= rm_toggle_tel_error(jQuery(this).intlTelInput('isValidNumber'),jQuery(this),jQuery(this).data('error-message'));
                    if(tel_error){
                        valid= false;
                    }
                    else
                    {
                        jQuery(this).val(jQuery(this).intlTelInput('getNumber'));
                    }
                }
            });

            if(!valid)
            {   
                setTimeout(function(){ submit_btn.prop('disabled',false); }, 1000);
                var error_element= jQuery(document).find("input.rm-form-field-invalid-msg")[0];
                if(error_element){
                    error_element.focus();
                }
                return false;
            }
            else{
                if(maxpage==rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}){
                    return true;
                }
            }
           
        } else{
            if(maxpage==rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}){
                    return true;
            }
        }
        
        /* Server validation for Username and Email field */
        for(var i=0;i<rm_validation_attr.length;i++){
            var validation_flag= true;
            jQuery("[" + rm_validation_attr[i] + "=0]").each(function(){
               validation_flag= false;
               return false;
            });
            
           
            if(!validation_flag)
              return;
        }
        
       
        rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}++;
        if(rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}>=maxpage){
            submit_btn.prop('value',sub_label);
        }
        else{
            submit_btn.prop('value','{$next_btn_label}');
        }
       
        /*skip blank form pages*/
        /*while(jQuery("#rm_form_page_{$form_identifier}_{$this->form_number}_"+rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}+" :input").length == 0)
        {
            if(maxpage <= rm_multipage.global_page_no_{$form_identifier}_{$this->form_number})
            {
                    if(jQuery("#rm_form_page_{$form_identifier}_{$this->form_number}_"+rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}+" :input").length == 0){
                        jq_next_button.prop('type','submit');
                        jq_prev_button.prop('disabled',true);
                        return;
                    }        
                    else
                        break;
            }    
           rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}++;		       
        }*/
          		
	if(rm_multipage.global_page_no_{$form_identifier}_{$this->form_number} >= maxpage){
            jq_next_button.attr("value", sub_label);
        }
	if(maxpage < rm_multipage.global_page_no_{$form_identifier}_{$this->form_number})
	{
		rm_multipage.global_page_no_{$form_identifier}_{$this->form_number} = maxpage;
		jq_next_button.prop('type','submit');
                jq_prev_button.prop('disabled',true);
	}
        
	jQuery(".rmformpage_{$form_identifier}_{$this->form_number}").each(function (){
        
		var visibledivid = "rm_form_page_{$form_identifier}_{$this->form_number}_"+rm_multipage.global_page_no_{$form_identifier}_{$this->form_number};
		var current_page= jQuery(this);
                    if(jQuery(this).attr('id') == visibledivid){
                        setTimeout(function(){ // Delaying field show to skip validation for untouched fields
                            current_page.show();
                            current_page.find(':input').addClass('rm_untouched');
                            setTimeout(function(){ current_page.find(':input').removeClass('rm_untouched'); }, 1000);
                        },100);
                }
		else
                    current_page.hide();  
        });         
        
        jQuery('.rmformpage_{$form_identifier}_{$this->form_number}').find(':input').filter(':visible').eq(0).focus();
        jQuery('html, body').animate({
            scrollTop: (jQuery('.rmformpage_{$form_identifier}_{$this->form_number}').first().offset().top)
        },500);
        jq_prev_button.prop('disabled',false);
        rmInitGoogleApi();
        
        setTimeout(function(){ submit_btn.prop('disabled',false); }, 1000);
        
        if(rm_multipage.global_page_no_{$form_identifier}_{$this->form_number} == maxpage){
            return false;
        }
        if(jq_prev_button.length>0 && '{$this->form_number}'==1){
            jq_prev_button.show();
        }
        
        if(jq_prev_button.length>0 && rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}>=1){
            jq_prev_button.show();
        }
        
        if(maxpage=='{$this->form_number}'){
            return true;
        }
        return false;
           
}
    </script></pre>
JSHD;

$prev_button_str = <<<JSPBHD
<pre class='rm-pre-wrapper-for-script-tags'><script>
function gotoprev_{$form_identifier}_{$this->form_number}(){
	
	var maxpage = {$max_page_count} ;
        var jq_prev_button = jQuery("#rm_prev_form_page_button_{$form_id}_{$this->form_number}");
        var jq_next_button = jQuery("#rm_next_form_page_button_{$form_id}_{$this->form_number}");
        //var sub_label = jq_next_button.data("label-sub");
        var next_label = jq_next_button.data("label-next");
        var form_object= jQuery("#rm_form_page_{$form_identifier}_{$this->form_number}_"+rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}).closest("form");
        var submit_btn= form_object.find("[type=submit]:not(.rm_noscript_btn)");
        var sub_label = submit_btn.data("label-sub");
        if(form_object.find('.rm_privacy_cb').is(':visible') && !form_object.find('.rm_privacy_cb').prop('checked')){
             form_object.find('.rm_privacy_cb').trigger('change');
             return false;
        } 
	rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}--;
        jq_next_button.attr('type','button');        
        
        if(maxpage==rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}){
            submit_btn.prop('value',sub_label);
        }
        else{
            submit_btn.prop('value','{$next_btn_label}');
        }
        /*skip blank form pages*/
        while(jQuery("#rm_form_page_{$form_identifier}_{$this->form_number}_"+rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}+" :input,.rm-total-price ").length == 0)
        {
            if(1 >= rm_multipage.global_page_no_{$form_identifier}_{$this->form_number})
            {
                    if(jQuery("#rm_form_page_{$form_identifier}_{$this->form_number}_"+rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}+" :input,.rm-total-price ").length == 0){
                        rm_multipage.global_page_no_{$form_identifier}_{$this->form_number} = 1;
                        //jq_prev_button.prop('disabled',true);
                        return;
                    }        
                    else
                        break;
            }
        
            rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}--;
        }
        
        if(rm_multipage.global_page_no_{$form_identifier}_{$this->form_number} <= maxpage-1)
            jq_next_button.attr("value", next_label);
            
	jQuery(".rmformpage_{$form_identifier}_{$this->form_number}").each(function (){
		var visibledivid = "rm_form_page_{$form_identifier}_{$this->form_number}_"+rm_multipage.global_page_no_{$form_identifier}_{$this->form_number};
		if(jQuery(this).attr('id') == visibledivid){
			jQuery(this).show();
                }
		else
			jQuery(this).hide();
	});
        jQuery('.rmformpage_{$form_identifier}_{$this->form_number}').find(':input').filter(':visible').eq(0).focus();
        if(rm_multipage.global_page_no_{$form_identifier}_{$this->form_number} <= 1)
        {
            rm_multipage.global_page_no_{$form_identifier}_{$this->form_number} = 1;
           // jq_prev_button.prop('disabled',true);
        }
        jQuery('html, body').animate({
            scrollTop: (jQuery('.rmformpage_{$form_identifier}_{$this->form_number}').first().offset().top)
        },500);
        
        if(rm_multipage.global_page_no_{$form_identifier}_{$this->form_number}==1){
            jq_prev_button.hide();
        }
}
         
</script>
    <script src="//www.youtube.com/player_api"></script>
    <script>
    var players = [];
    function onYouTubePlayerAPIReady() {
        // create the global player from the specific iframe (#video)
        var pre_id = '';
        jQuery(".allow-autoplay").each(function(){
            if(pre_id!=jQuery(this).attr("id")){
                players.push(new YT.Player(jQuery(this).attr("id")));
            }
            pre_id = jQuery(this).attr("id");
        });
    }
    jQuery(document).ready(function(){
        var videosArr = [];
        var pre_id = '';
        jQuery(".allow-autoplay").each(function(i){
            if(pre_id!=jQuery(this).attr("id")){
                videosArr[jQuery(this).attr("id")] = i;
            }
            pre_id = jQuery(this).attr("id");
        });
        jQuery('.buttonarea input').click(function(){
            setTimeout(function(){
                jQuery(".rmformpage_{$form_identifier}_{$this->form_number}").each(function(){
                    if(jQuery(this).css('display')=='block'){
                        var page_video_id= jQuery(this).attr("id");
                        if(jQuery('#'+page_video_id+' .allow-autoplay').length){
                            players[videosArr[jQuery('#'+page_video_id+' .allow-autoplay').attr('id')]].playVideo();
                        }
                    }
                });
            }, 300);
        });
    });
    </script> 
   </pre>
JSPBHD;
        if($this->form_options->no_prev_button)    
        $str = $jqvalidate.$jq_front_form_script.$mainstr;
        else
        $str = $jqvalidate.$jq_front_form_script.$mainstr.$prev_button_str;
        
   
        $form->addElement(new Element_HTML($str));
    }

}
