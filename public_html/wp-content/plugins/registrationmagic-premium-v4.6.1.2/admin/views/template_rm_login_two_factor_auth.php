<?php
if (!defined('WPINC')) {
    die('Closed');
}
$params= $data->params; ?>

<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">
         <div class="rmheader"><?php echo _e('Two-Factor Authentication', 'registrationmagic-gold'); ?></div> 
        
        <?php
        $form = new RM_PFBC_Form("login-auth-options");

        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        $form->addElement(new Element_HTML('<div class="rmrow"><div class="rmnotice">'.__('Note: 2FA works with normal login process. If social login is turned on and a user logs in using his or her social network account, it will bypass 2FA process.', 'registrationmagic-gold').'</div></div>'));
        $form->addElement(new Element_HTML('<div class="rmrow 2fa_notice"><div class="rmnotice">'.sprintf(__('Note: In rare few cases, server side caching may interfere with 2FA. If you notice login page refreshing instead of redirecting to step 2 authentication, try disabling the server cache for your login page. This can be done by submitting a request to your server support team. You can also contact our support team <a target="_blank" href="%s">here</a>.', 'registrationmagic-gold'),'https://registrationmagic.com/help-support/').'</div></div>'));
        $form->addElement(new Element_Checkbox("<b>" . __('Turn on 2FA', 'registrationmagic-gold') . "</b>", "en_two_fa", array(1 => ""), array("class" => "rm-role-based rm-static-field rm_input_type en_two_fa", "value" => isset($params['en_two_fa']) ? $params['en_two_fa'] : 0, "longdesc" => __('Turn on two-factor authentication for user login. When this is turned on, users are asked to enter an additional one-time-password, emailed to them, after their login credentials are verified. OTP once used cannot be reused.', 'registrationmagic-gold'))));
             
            $form->addElement(new Element_HTML('<div id="rm_2fa" style="display:none;" class="childfieldsrow">'));   
                $form->addElement(new Element_Radio(__('OTP Type', 'registrationmagic-gold'), "otp_type", array('alpha_numeric'=>__('Alphanumeric','registrationmagic-gold'),'numeric'=>__('Numeric','registrationmagic-gold')), array("value" => $params['otp_type'], "longDesc"=>__('Define the OTP type that system will generate.', 'registrationmagic-gold'))));
                $form->addElement(new Element_Number(__('OTP Length', 'registrationmagic-gold'), "otp_length", array("value" => $params['otp_length'], "min"=>4, "max"=>10, "longDesc"=>__('Define the length of OTP', 'registrationmagic-gold'))));
                $form->addElement(new Element_Number(__('OTP expiry', 'registrationmagic-gold'), "otp_expiry", array("value" => $params['otp_expiry'], "longDesc"=>__('Define, in minutes, the lifetime of OTP. For example, 10 means the OTP will be valid for 10 minutes from the time of generation.', 'registrationmagic-gold'))));
                $form->addElement(new Element_Radio(__('On OTP expiry', 'registrationmagic-gold'), "otp_expiry_action", array('regenerate'=>__('Allow users to re-generate','registrationmagic-gold'),'restart'=>__('Restart login process','registrationmagic-gold')), array("value" => $params['otp_expiry_action'],'class'=>'otp_expiry_action', "longDesc"=>__('Define what happens when OTP entered by the user has expired.', 'registrationmagic-gold'))));  
                    $form->addElement(new Element_HTML('<div id="rm_otp_expiry_regenerate" class="childfieldsrow">'));
                        $form->addElement(new Element_Textbox("<b>" . __('Re-Generate OTP link text', 'registrationmagic-gold') . "</b>", "otp_regen_text", array("value" => $params['otp_regen_text'], "longDesc" => __('Text of the link that will allow users to re-generate OTP', 'registrationmagic-gold'))));
                        $form->addElement(new Element_Textarea("<b>" . __('OTP re-generation success message', 'registrationmagic-gold') . "</b>", "otp_regen_success_msg", array("value" => $params['otp_regen_success_msg'], "longDesc" => __('Contents of the message user sees after OTP has been re-generated and sent.', 'registrationmagic-gold'))));
                        $form->addElement(new Element_Textarea("<b>" . __('OTP expiry message', 'registrationmagic-gold') . "</b>", "otp_exp_msg", array("value" => $params['otp_exp_msg'], "longDesc" => __('Contents of the message that displays above OTP input box when the OTP entered by the user is incorrect.', 'registrationmagic-gold'))));
                    $form->addElement(new Element_HTML('</div>'));  
                    
                    $form->addElement(new Element_HTML('<div id="rm_otp_expiry_restart" class="childfieldsrow">'));
                        $form->addElement(new Element_Textarea("<b>" . __('Error message', 'registrationmagic-gold') . "</b>", "otp_exp_restart_msg", array("value" => $params['otp_exp_restart_msg'], "longDesc" => __('Contents of the message that displays above the login box after login process has restarted.', 'registrationmagic-gold'))));
                    $form->addElement(new Element_HTML('</div>')); 
                
                $form->addElement(new Element_Textbox("<b>" . __('OTP field label', 'registrationmagic-gold') . "</b>", "otp_field_label", array("value" => $params['otp_field_label'], "longDesc" => __('The label of the OTP input field that appears after validating login credentials.', 'registrationmagic-gold'))));
                $form->addElement(new Element_Textarea("<b>" . __('Custom message above OTP field', 'registrationmagic-gold') . "</b>", "msg_above_otp", array("value" => $params['msg_above_otp'], "longDesc" => __('Message prompt informing user about the OTP process', 'registrationmagic-gold'))));
                $form->addElement(new Element_Checkbox("<b>" . __('Allow Re-sending OTP','registrationmagic-gold') . "</b>", "en_resend_otp", array(1 => ""), array("class" => "rm-static-field rm_input_type en_resend_otp", "value" => isset($params['en_resend_otp']) ? $params['en_resend_otp'] : 0, "longdesc" => __('Allow users to resend OTP in case they did not received it the first time.', 'registrationmagic-gold'))));
                    $form->addElement(new Element_HTML('<div id="rm_resend_otp" class="childfieldsrow">'));
                        $form->addElement(new Element_Textbox("<b>" . __('Resend OTP link text','registrationmagic-gold') . "</b>", "otp_resend_text", array("value" => $params['otp_resend_text'], "longDesc" => __('Anchor text for resending OTP link.', 'registrationmagic-gold'))));
                        $form->addElement(new Element_Textarea("<b>" . __('OTP resent success message','registrationmagic-gold') . "</b>", "otp_resent_msg", array("value" => $params['otp_resent_msg'], "longDesc" => __('Contents of the message user sees after OTP has been resent.', 'registrationmagic-gold'))));
                        $form->addElement(new Element_Number(__('OTP resend limit','registrationmagic-gold'), "otp_resend_limit", array("value" => $params['otp_resend_limit'],"longDesc"=>__('Resend OTP link will stop appearing after this number of resending attempts. Enter 0 to disable this feature.', 'registrationmagic-gold'))));
                    $form->addElement(new Element_HTML('</div>'));
                    
                $form->addElement(new Element_Number(__('Incorrect OTP attempts', 'registrationmagic-gold'), "allowed_incorrect_attempts", array("value" => $params['allowed_incorrect_attempts'],"longDesc"=>__('Consecutive incorrect OTP attempts allowed before the login process resets and asks for user credentials.', 'registrationmagic-gold'))));
                $form->addElement(new Element_Textarea("<b>" . __('Invalid OTP error', 'registrationmagic-gold') . "</b>", "invalid_otp_error", array("value" => $params['invalid_otp_error'], "longDesc" => __('Contents of the error message displayed above OTP input field (or Login box if the login process resets) when user enters an invalid OTP.', 'registrationmagic-gold'))));
                $form->addElement(new Element_Radio(__('Apply 2FA for', 'registrationmagic-gold'), "apply_on", array('all'=>__('All roles','registrationmagic-gold'),'specific'=>__('Specific roles','registrationmagic-gold')), array("value" => $params['apply_on'],'class'=>'apply_on', "longDesc"=>__('Define if 2FA should work with all roles or only specific roles.', 'registrationmagic-gold'))));  
                    $form->addElement(new Element_HTML('<div id="rm_all_roles" class="childfieldsrow">'));
                        $form->addElement(new Element_Checkbox("<b>" . __('Turn off for admins', 'registrationmagic-gold') . "</b>", "disable_two_fa_for_admin", array(1 => ""), array("class" => "rm-role-based rm-static-field rm_input_type", "value" => isset($params['disable_two_fa_for_admin']) ? $params['disable_two_fa_for_admin'] : 0, "longdesc" => __('Turn off 2FA only for admin roles', 'registrationmagic-gold'))));
                    $form->addElement(new Element_HTML('</div>'));
                    
                    $form->addElement(new Element_HTML('<div id="rm_specific_roles" class="childfieldsrow">'));
                            $form->addElement(new Element_Checkbox("<b>" . __('Select roles', 'registrationmagic-gold') . "</b>", "enable_two_fa_for_roles", $data->roles, array("class" => "rm-role-based rm-static-field rm_input_type", "value" => $params['enable_two_fa_for_roles'], "longdesc" => __('Select the user roles for which you wish to turn on 2FA', 'registrationmagic-gold'))));           
                    $form->addElement(new Element_HTML('</div>'));
                    
            $form->addElement(new Element_HTML('</div>'));
            
            $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page=rm_login_sett_manage', array('class' => 'cancel')));
            $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit")));
        $form->render();
        ?>
    </div>
</div>

<script>
    jQuery(document).ready(function(){
        $=jQuery;
        $(".otp_expiry_action").change(function(){
           var val= $(this).val();
           if(val=="regenerate" && jQuery(this).is(':checked')){
               $("#rm_otp_expiry_regenerate").slideDown();
               $("#rm_otp_expiry_restart").slideUp();
           } else if(val=="restart" && jQuery(this).is(':checked')){
               $("#rm_otp_expiry_restart").slideDown();
               $("#rm_otp_expiry_regenerate").slideUp();
           }
        });
        
        $(".apply_on").change(function(){
            var val= $(this).val();
            if(val=="all" && $(this).is(':checked')){
                $("#rm_" + val + "_roles").slideDown();
                $("#rm_specific_roles").slideUp();
            } else if(val=="specific" && jQuery(this).is(':checked')){
                $("#rm_specific_roles").slideDown();
                $("#rm_all_roles").slideUp();
            }
           
        });

        $(".en_two_fa").change(function(){
            if($(this).is(':checked')){
                $("#rm_2fa").slideDown();
                return;
            }
            $("#rm_2fa").slideUp();
        });
        $(".en_resend_otp").change(function(){
           if($(this).is(':checked')){
               $("#rm_resend_otp").slideDown();
               return;
           } 
           $("#rm_resend_otp").slideUp();
        });
        
        $(".en_two_fa,.otp_expiry_action,.en_resend_otp,.apply_on").trigger('change');
        $("#login-auth-options-element-2-0").change(function(){
           if($(this).is(':checked')){
               $('.2fa_notice').show();
               return;
           } 
           $('.2fa_notice').hide();
        });
        $("#login-auth-options-element-2-0").trigger('change');
    });
    
    
    
</script>    