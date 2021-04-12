<?php
if (!defined('WPINC')) {
    die('Closed');
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$actrl = $data->access_control;

$date_cb_value = (isset($actrl->date) && $actrl->date) ? 1 : 0;
$pass_cb_value = (isset($actrl->passphrase) && $actrl->passphrase) ? 1 : 0;
$role_cb_value = isset($actrl->roles) ? 1 : 0;
$domain_cb_value = isset($actrl->domain) ? 1 : 0;
$domain =  isset($actrl->domain) ? $actrl->domain : '';
$date_question = (isset($actrl->date->question) && $actrl->date->question) ? $actrl->date->question : RM_UI_Strings::get('LABEL_ACTRL_DATE_QUESTION_DEF');
$pass_question = (isset($actrl->pass->question) && $actrl->pass->question) ? $actrl->pass->question : RM_UI_Strings::get('LABEL_ACTRL_PASS_QUESTION_DEF');
$fail_msg = (isset($actrl->fail_msg) && $actrl->fail_msg) ? $actrl->fail_msg : RM_UI_Strings::get('LABEL_ACTRL_FAIL_MSG_DEF');
$date_ll = '';
$date_ul = '';
$diff_ll = '';
$diff_ul = '';

if(isset($actrl->date->type))
{
    $date_type = $actrl->date->type;
    
    if($actrl->date->type == 'date')
    {
        $date_ll = $actrl->date->lowerlimit;
        $date_ul = $actrl->date->upperlimit;
    }
     else 
     {
        $diff_ll = $actrl->date->lowerlimit;
        $diff_ul = $actrl->date->upperlimit;
     }
}else
    $date_type = "diff";

if(isset($actrl->passphrase->passphrase))
    $passphrase = $actrl->passphrase->passphrase;
else
    $passphrase = null;

if(isset($actrl->roles))
    $roles = $actrl->roles;
else
    $roles = null;
?>

<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php
        $form = new RM_PFBC_Form("form_sett_access_control");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        
        if (isset($data->model->form_id)) {
            $form->addElement(new Element_HTML('<div class="rmheader">' . $data->model->form_name . '</div>'));
            $form->addElement(new Element_HTML('<div class="rmsettingtitle">' . RM_UI_Strings::get('LABEL_F_ACTRL_SETT') . '</div>'));
            $form->addElement(new Element_Hidden("form_id", $data->model->form_id));
        } else {
            $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get("TITLE_NEW_FORM_PAGE") . '</div>'));
        }
        
        //Date based restrictions.
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_ACTRL_DATE_CB') . "</b>", "form_actrl_date_cb", array(1=>''), array("id" => "id_form_actrl_date_cb", "value" => $date_cb_value, "onclick" => "actrl_date_click_handler()", "longDesc" => RM_UI_Strings::get('HELP_FORM_ACTRL_DATE'))));
        
        
        //border is inlined to prevent jumpy animation.
        //Wonderful hack from: http://stackoverflow.com/questions/1335461/jquery-slide-is-jumpy
        if($date_cb_value)
        {
            $form->addElement(new Element_HTML("<div id='form_actrl_date_container' class='childfieldsrow' style='border: 1px solid transparent'>"));
        } 
        else
        {
            $form->addElement(new Element_HTML("<div id='form_actrl_date_container' class='childfieldsrow' style='display:none;border: 1px solid transparent;'>"));
        }
        
        $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_ACTRL_DATE_QUESTION') . "</b>", "form_actrl_date_question", array("value" => $date_question,"required"=>"required" ,"longDesc" => RM_UI_Strings::get('HELP_FORM_ACTRL_DATE_QSTN'))));
        
        $form->addElement(new Element_Radio("<b>" . RM_UI_Strings::get('LABEL_ACTRL_DATE_TYPE') . "</b>", "form_actrl_date_type", array('diff'=>RM_UI_Strings::get('LABEL_ACTRL_DATE_TYPE_DIFF'),'date'=>RM_UI_Strings::get('LABEL_ACTRL_DATE_TYPE_DATE')), array("id"=>"id_form_actrl_date_type","value" => $date_type, 'onclick' => 'handle_date_type_change(this)', "longDesc" => RM_UI_Strings::get('HELP_FORM_ACTRL_DATE_TYPE'))));
        $form->addElement(new Element_HTML('<div class="rmrow" id="rm_jqnotice_row_date_type" style="display:none;padding: 0px 20px 0px 20px;min-height: 0px;"><div class="rmfield" for="rm_field_value_options_textarea"><label></label></div><div class="rminput" id="rm_jqnotice_text">'.RM_UI_Strings::get('MSG_INVALID_ACTRL_DATE_TYPE').'</div></div>'));
        if($date_type === 'date')
        {
            $form->addElement(new Element_HTML("<div id='form_actrl_date_type_1_container' style='border: 1px solid transparent'>"));
        } 
        else
        {
            $form->addElement(new Element_HTML("<div id='form_actrl_date_type_1_container' style='display:none;border: 1px solid transparent;'>"));
        }
        $form->addElement(new Element_jQueryUIDate("<b>" . RM_UI_Strings::get('LABEL_ACTRL_DATE_LLIMIT') . "</b>", "form_actrl_date_ll_date", array("value" => $date_ll, "id" => "form_actrl_date_ll_date","longDesc" => '')));
        
        $form->addElement(new Element_jQueryUIDate("<b>" . RM_UI_Strings::get('LABEL_ACTRL_DATE_ULIMIT') . "</b>", "form_actrl_date_ul_date", array("value" => $date_ul,"id" => "form_actrl_date_ul_date", "longDesc" => '')));
         $form->addElement(new Element_HTML("<div id='date_error' style='display:none' align='center'>"));
          $form->addElement(new Element_HTML("</div>"));
        
        
        $form->addElement(new Element_HTML("</div>"));
         if($date_type === 'diff')
        {
            $form->addElement(new Element_HTML("<div id='form_actrl_date_type_2_container' style='border: 1px solid transparent'>"));
        } 
        else
        {
            $form->addElement(new Element_HTML("<div id='form_actrl_date_type_2_container' style='display:none;border: 1px solid transparent;'>"));
        }
        $form->addElement(new Element_Number("<b>" . RM_UI_Strings::get('LABEL_ACTRL_DATE_LLIMIT') . "</b>", "form_actrl_date_ll_diff", array("value" => $diff_ll, "id" => "form_actrl_date_ll_diff","longDesc" => '')));
        
        $form->addElement(new Element_Number("<b>" . RM_UI_Strings::get('LABEL_ACTRL_DATE_ULIMIT') . "</b>", "form_actrl_date_ul_diff", array("value" => $diff_ul,  "id" => "form_actrl_date_ul_diff","longDesc" => '')));
        $form->addElement(new Element_HTML("</div>"));
        $form->addElement(new Element_HTML("<div id='date_limit_error' style='display:none' align='center'>"));
          $form->addElement(new Element_HTML("</div>"));
        $form->addElement(new Element_HTML('<div class="rmrow" id="rm_jqnotice_row_date_limit" style="display:none;padding: 0px 20px 0px 20px;min-height: 0px;"><div class="rmfield" for="rm_field_value_options_textarea"><label></label></div><div class="rminput" id="rm_jqnotice_text">'.RM_UI_Strings::get('MSG_INVALID_ACTRL_DATE_LIMIT').'</div></div>'));
        
        $form->addElement(new Element_HTML("</div>"));
        
        
        //Passphrase based restrictions
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_ACTRL_PASS_CB') . "</b>", "form_actrl_pass_cb", array(1=>''), array("id" => "id_form_actrl_pass_cb", "value" => $pass_cb_value, "onclick" => "actrl_pass_click_handler()", "longDesc" => RM_UI_Strings::get('HELP_FORM_ACTRL_PASS'))));
        
        if($pass_cb_value)
        {
            $form->addElement(new Element_HTML("<div id='form_actrl_pass_container' class='childfieldsrow' style='border: 1px solid transparent;'>"));
        } 
        else
        {
            $form->addElement(new Element_HTML("<div id='form_actrl_pass_container' class='childfieldsrow' style='display:none;border: 1px solid transparent;'>"));
        }
        
        $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_ACTRL_PASS_QUESTION') . "</b>", "form_actrl_pass_question", array("value" => $pass_question, "longDesc" => RM_UI_Strings::get('HELP_FORM_ACTRL_PASS_QSTN'))));
        
        $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_ACTRL_PASS_PASS') . "</b>", "form_actrl_pass_passphrase", array("value" => $passphrase, "longDesc" => RM_UI_Strings::get('HELP_FORM_ACTRL_PASS_PASS'))));
        $form->addElement(new Element_HTML('<div class="rmrow" id="rm_jqnotice_row_pass_pass" style="display:none;padding: 0px 20px 0px 20px;min-height: 0px;"><div class="rmfield" for="rm_field_value_options_textarea"><label></label></div><div class="rminput" id="rm_jqnotice_text">'.RM_UI_Strings::get('MSG_INVALID_ACTRL_PASS_PASS').'</div></div>'));
        $form->addElement(new Element_HTML("</div>"));
        
        //User role based restrictions
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_ACTRL_ROLE_CB') . "</b>", "form_actrl_role_cb", array(1=>''), array("id" => "id_form_actrl_role_cb", "value" => $role_cb_value, "onclick" => "actrl_role_click_handler()", "longDesc" => RM_UI_Strings::get('HELP_FORM_ACTRL_ROLE'))));
        
        if($role_cb_value)
        {
            $form->addElement(new Element_HTML("<div id='form_actrl_role_container' class='childfieldsrow' style='border: 1px solid transparent;'>"));
        } 
        else
        {
            $form->addElement(new Element_HTML("<div id='form_actrl_role_container' class='childfieldsrow' style='display:none;border: 1px solid transparent;'>"));
        }
        
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_ACTRL_ROLE_ROLES') . "</b>", "form_actrl_roles", $data->all_roles, array("id"=>"id_form_actrl_date_type", "value" => $roles, "longDesc" => RM_UI_Strings::get('HELP_FORM_ACTRL_ROLE_ROLES'))));
        
        $form->addElement(new Element_HTML('<div class="rmrow" id="rm_jqnotice_row_roles" style="display:none;padding: 0px 20px 0px 20px;min-height: 0px;"><div class="rmfield" for="rm_field_value_options_textarea"><label></label></div><div class="rminput" id="rm_jqnotice_text">'.RM_UI_Strings::get('MSG_INVALID_ACTRL_ROLES').'</div></div>'));

        $form->addElement(new Element_HTML("</div>"));
        
        $form->addElement(new Element_Checkbox("<b>" . __('Domain based form access control','registrationmagic-gold') . "</b>", "form_actrl_domain_cb", array(1=>''), array("id" => "id_form_actrl_domain_cb", "value" =>$domain_cb_value, "onclick" => "actrl_domain_click_handler(this)", "longDesc" =>__('Only users with specified email domain will be able to submit the form','registrationmagic-gold'))));
            if($domain_cb_value){
                $form->addElement(new Element_HTML("<div id='form_actrl_domain_container' class='childfieldsrow' style='border: 1px solid transparent;'>"));
            } else{
                $form->addElement(new Element_HTML("<div id='form_actrl_domain_container' class='childfieldsrow' style='display:none;border: 1px solid transparent;'>"));
            }
                $form->addElement(new Element_Textbox("<b>" . __('Domain','registrationmagic-gold') . "</b>", "form_actrl_domain", array("value" => $domain, "longDesc" => __('Only users with email on this domain will be allowed to submit form/ register.','registrationmagic-gold'))));
            $form->addElement(new Element_HTML("</div>"));
        $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_ACTRL_FAIL_MSG') . "</b>", "form_actrl_fail_msg", array("value" => $fail_msg, "longDesc" => RM_UI_Strings::get('HELP_FORM_ACTRL_FAIL_MSG'))));
        
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), "?page={$data->next_page}&rm_form_id=".$data->model->form_id, array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit", "onClick" => "prevent_save(event)")));
        $form->render();
        ?>
    </div>
</div>

<pre class='rm-pre-wrapper-for-script-tags'><script>
    
function actrl_date_click_handler(){
    if(jQuery('#id_form_actrl_date_cb-0').prop("checked"))
    {
        jQuery('#form_actrl_date_container').slideDown();
    }
    else
    {
        jQuery('#form_actrl_date_container').slideUp();
    }
};

function actrl_pass_click_handler(){
    if(jQuery('#id_form_actrl_pass_cb-0').prop("checked"))
    {
        jQuery('#form_actrl_pass_container').slideDown();
    }
    else
    {
        jQuery('#form_actrl_pass_container').slideUp();
    }
};

function actrl_domain_click_handler(obj){
    obj = jQuery(obj);
    if(obj.is(':checked')){
        jQuery('#form_actrl_domain_container').slideDown();
        return;
    }
    jQuery('#form_actrl_domain_container').slideUp();
};

function actrl_role_click_handler(){
    if(jQuery('#id_form_actrl_role_cb-0').prop("checked"))
    {
        jQuery('#form_actrl_role_container').slideDown();
    }
    else
    {
        jQuery('#form_actrl_role_container').slideUp();
    }
};

function handle_date_type_change(x){
    var type = jQuery(x).val();
    
    if(type == 'diff'){
        jQuery('#form_actrl_date_type_1_container').hide();
        jQuery('#form_actrl_date_type_2_container').show();
        jQuery('#form_actrl_date_type_2_container :input').attr('disabled',false);
        jQuery('#form_actrl_date_type_1_container :input').attr('disabled',true);
    }else if(type == 'date'){
        jQuery('#form_actrl_date_type_2_container').hide();
        jQuery('#form_actrl_date_type_1_container').show();
        jQuery('#form_actrl_date_type_1_container :input').attr('disabled',false);
        jQuery('#form_actrl_date_type_2_container :input').attr('disabled',true);
    }
}

function flash_element(x){
   x.each(function () {
                jQuery(this).css("border", "1px solid #FF6C6C");        
                jQuery(this).fadeIn(100).fadeOut(1000, function () {
                    jQuery(this).css("border", "");
                    jQuery(this).fadeIn(100);
                    jQuery(this).val('');
                });
            });
                        
}

function prevent_save(event){
    
    //New extensive JS validation
    var rm_actrl_form_valid = true;
    var jq_first_invalid_element = null;
    if(jQuery('#id_form_actrl_date_cb-0').prop("checked"))
    {
        var jqdt = jQuery('[name="form_actrl_date_type"]:input:checked');
        if(jqdt.length == 0)
        {
            jQuery('#rm_jqnotice_row_date_type').show();
            rm_actrl_form_valid = false;
            if(!jq_first_invalid_element)
                jq_first_invalid_element = jqdt;
        }
        
        if(jqdt.val() == 'date')
            var jqll = jQuery('[name="form_actrl_date_ll_date"]:input');
        else if(jqdt.val() == 'diff')
            var jqll = jQuery('[name="form_actrl_date_ll_diff"]:input');
        
        if(jqdt.val() == 'date')
            var jqul = jQuery('[name="form_actrl_date_ul_date"]:input');
        else if(jqdt.val() == 'diff')
            var jqul = jQuery('[name="form_actrl_date_ul_diff"]:input');
          
        if(jqll.val().toString().trim() == '' && jqul.val().toString().trim() == '')
        {
            jQuery('#rm_jqnotice_row_date_limit').show();
            flash_element(jqll);
            flash_element(jqul);
            rm_actrl_form_valid = false;
            
            if(!jq_first_invalid_element)
                jq_first_invalid_element = jqll;
        }
    }
    
    if(jQuery('#id_form_actrl_pass_cb-0').prop("checked"))
    {
        var jqpp = jQuery('[name="form_actrl_pass_passphrase"]:input');
        if(jqpp.val().toString().trim() == '')
        {
            jQuery('#rm_jqnotice_row_pass_pass').show();
            flash_element(jqpp);
            rm_actrl_form_valid = false;
            
             if(!jq_first_invalid_element)
                jq_first_invalid_element = jqpp;
        }
    }
    
    if(jQuery('#id_form_actrl_role_cb-0').prop("checked"))
    {
        if(jQuery('[name="form_actrl_roles[]"]:input:checked').length == 0)
        {
            jQuery('#rm_jqnotice_row_roles').show();
            rm_actrl_form_valid = false;
        }
    }
    
    if(!rm_actrl_form_valid){
        if(jq_first_invalid_element)
            jq_first_invalid_element.focus();
        
        event.preventDefault();
    }
   
    if(jQuery("#id_form_actrl_date_cb-0").is(":checked"))
    {
    if( jQuery("#id_form_actrl_date_type-1").is(":checked") )
    {
    var min_date=new Date(jQuery("#form_actrl_date_ll_date").val());
    var max_date=new Date(jQuery("#form_actrl_date_ul_date").val());
  
        if(max_date<=min_date)
               {
                   jQuery('#date_error').html("<?php _e('Invalid Date Range','registrationmagic-gold'); ?>");
                   jQuery('#date_limit_error').hide();
                   jQuery('#date_error').show();
                  event.preventDefault();
               }
    
    }
   if( jQuery("#id_form_actrl_date_type-0").is(":checked") )
    {
     
        var min_date= parseInt(jQuery("#form_actrl_date_ll_diff").val());
    var max_date= parseInt(jQuery("#form_actrl_date_ul_diff").val());
        if(max_date<=min_date)
               {
                   jQuery('#date_limit_error').html("<?php _e('Invalid Age Limit Range','registrationmagic-gold'); ?>");
                   jQuery('#date_error').hide();
                   jQuery('#date_limit_error').show();
                   
                  event.preventDefault();
               }
    } 
    }
        
}

</script></pre>

<?php

