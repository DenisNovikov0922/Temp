<?php
if (!defined('WPINC')) {
    die('Closed');
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php
        $acc_activation_methods = array('yes' => RM_UI_Strings::get('LABEL_ACC_ACT_AUTO'),
                                    '' => RM_UI_Strings::get('LABEL_ACC_ACT_MANUALLY'),//RM_UI_Strings::get('RATING_STAR_FACE_HEART'),
                                    'verify' => RM_UI_Strings::get('LABEL_ACC_ACT_BY_VERIFICATION')
                                    );
        
        $form = new RM_PFBC_Form("options_users");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        //var_dump($data['user_auto_approval']);
        $options_sp = array("id" => "id_rm_send_pass_cb", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_USER_SEND_PASS'));
        
        if($data['auto_generated_password'] === 'yes')
            $options_sp['disabled'] = true;
        
        if( $data['send_password'] === 'yes')
            $options_sp['value'] = 'yes';
        
        
        //print_r($data);
        
        $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get('GLOBAL_SETTINGS_USER') . '</div>'));
        $form->addElement(new Element_Radio(RM_UI_Strings::get('LABEL_ACC_ACT_METHOD'), "user_auto_approval", $acc_activation_methods, array("value" =>$data['user_auto_approval'],'onchange'=>'show_verification_options(this)', "longDesc"=>RM_UI_Strings::get('HELP_ACC_ACT_METHOD'))));    
        
        if($data['user_auto_approval']=="verify")
          $form->addElement(new Element_HTML('<div class="childfieldsrow" id="field_verify_options" >'));  
        else
          $form->addElement(new Element_HTML('<div class="childfieldsrow" style="display:none" id="field_verify_options" >'));
            $form->addElement(new Element_Number(RM_UI_Strings::get('LABEL_ACC_ACT_LINK_EXPIRY'), "acc_act_link_expiry", array("value" => $data['acc_act_link_expiry'], "min"=>0, "longDesc"=>RM_UI_Strings::get('HELP_ACC_ACT_LINK_EXPIRY'))));
            $form->addElement(new Element_Textarea(RM_UI_Strings::get('LABEL_ACC_ACT_NOTICE'), "acc_act_notice", array( "value" => $data['acc_act_notice'], "longDesc"=>RM_UI_Strings::get('HELP_ACC_ACT_NOTICE'))));
            $form->addElement(new Element_Textarea(RM_UI_Strings::get('LABEL_ACC_ACT_CODE'), "acc_invalid_act_code", array("value" => $data['acc_invalid_act_code'], "longDesc"=>RM_UI_Strings::get('HELP_ACC_ACT_CODE'))));
            $form->addElement(new Element_Textarea(RM_UI_Strings::get('LABEL_ACC_ACT_LINK_EXP_NOTICE'), "acc_act_link_exp_notice", array("value" => $data['acc_act_link_exp_notice'], "longDesc"=>RM_UI_Strings::get('HELP_ACC_ACT_LINK_EXP_NOTICE'))));
            $form->addElement(new Element_Textarea(RM_UI_Strings::get('LABEL_LOGIN_ERROR_MSG'), "login_error_message", array("value" => $data['login_error_message'], "longDesc"=>RM_UI_Strings::get('HELP_LOGIN_ERROR_MSG'))));
            $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_PROV_ACT_ACC'), "prov_act_acc", array("yes" => ''),array('onchange'=>'show_provisional_acc_options(this)','value'=>$data['prov_act_acc'], "longDesc" => RM_UI_Strings::get('HELP_PROV_ACT_ACC'))));
                 if($data['prov_act_acc']=='yes')
                     $form->addElement(new Element_HTML('<div class="childfieldsrow" id="prov_acc_act_options" >'));
                 else
                     $form->addElement(new Element_HTML('<div class="childfieldsrow" style="display:none" id="prov_acc_act_options" >'));
                        $form->addElement(new Element_Radio(RM_UI_Strings::get('LABEL_PROV_ACT_CRITERIA'), "prov_acc_act_criteria", array('until_user_logsout'=> RM_UI_Strings::get('LABEL_UNTIL_USER_LOGS_OUT'),'until_act_link_expires'=> RM_UI_Strings::get('LABEL_UNTIL_ACT_LINK_EXPIRES')), array("value" =>$data['prov_acc_act_criteria'],'onchange'=>'show_act_link_exp_options(this)', "longDesc"=>RM_UI_Strings::get('HELP_PROV_ACT_CRITERIA'))));    
                        
                        if($data['prov_acc_act_criteria']=='until_act_link_expires')
                            $form->addElement(new Element_HTML('<div id="prov_acc_link_exp_options" >'));
                        else
                            $form->addElement(new Element_HTML('<div style="display:none" id="prov_acc_link_exp_options" >'));
                           
                        
                        $form->addElement(new Element_HTML('</div>'));
                    $form->addElement(new Element_HTML('</div>'));
                 
        $form->addElement(new Element_HTML('</div>'));
        
        
        
        //$form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_AUTO_PASSWORD'), "auto_generated_password", array("yes" => ''), $data['auto_generated_password'] == 'yes' ? array("id" => "id_rm_autogen_pass_cb", "value" => "yes", "onchange" => "checkbox_disable_elements(this, 'id_rm_send_pass_cb-0', 1)", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_USER_AUTOGEN')) : array("id" => "id_rm_autogen_pass_cb", "onchange" => "checkbox_disable_elements(this, 'id_rm_send_pass_cb-0', 1)", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_USER_AUTOGEN'))));

        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_SEND_PASS_EMAIL'), "send_password", array("yes" => ''), $options_sp));
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page=rm_options_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE')));

        $form->render();
        ?>
    </div>
</div>
<script>
    function show_verification_options(obj){
        var selected_method= jQuery(obj).val();
        if(selected_method=="verify"){
            jQuery("#field_verify_options").show();
            return;
        }
        jQuery("#field_verify_options").hide();
    }
    
    function show_provisional_acc_options(obj)
    {
        if(jQuery(obj).is(':checked')){
            jQuery("#prov_acc_act_options").show();
            return;
        }
         jQuery("#prov_acc_act_options").hide();
    }
    
    function show_act_link_exp_options(obj){
        var criteria= jQuery(obj).val();
        if(criteria=='until_act_link_expires'){
            jQuery("#prov_acc_link_exp_options").show();
            return;
        }
        jQuery("#prov_acc_link_exp_options").hide();
    }
    
    jQuery(document).ready(function(){
        jQuery( "#options_users-element-1-0" ).next('label').after( '<span class="rm-option-subtext"><?php _e('Once user successfully submits a registration form, his/her account is created and activated automatically.','registrationmagic-gold');  ?></span>' );
        jQuery( "#options_users-element-1-1" ).next('label').after( '<span class="rm-option-subtext"><?php _e('Once user successfully submits a registration form, his/her account is created but in deactivated state. Useful for manually approving accounts via User Manager or admin notification email link. You can also activate accounts based on form properties using Automation.','registrationmagic-gold');  ?></span>' );
        jQuery( "#options_users-element-1-2" ).next('label').after( '<span class="rm-option-subtext"><?php _e('Once user successfully submits a registration form, he/she will receive an email with account verification link. Clicking the link will activate his/her account. Admin can also manually approve unverified accounts.<br>Please note, if you are using paid registration, the user will be auto activated upon successful payment.','registrationmagic-gold');  ?></span>' );
    });
</script>    
<?php   
