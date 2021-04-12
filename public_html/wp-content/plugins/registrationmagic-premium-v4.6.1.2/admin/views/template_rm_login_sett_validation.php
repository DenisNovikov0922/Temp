<?php
if (!defined('WPINC')) {
    die('Closed');
}
$params= $data->params; ?>
<div class="rmagic"> 
    <!--Dialogue Box Starts-->
    <div class="rmcontent">
         <div class="rmheader"><?php echo _e('Validation & Security', 'registrationmagic-gold'); ?></div>
        <div class="rmrow">
            <div class="rmnotice">
                <?php printf(__('Note: In rare few cases, server side caching may interfere with reCaptcha. If you notice login page refreshing instead of showing reCaptcha, try disabling the server cache for your login page. This can be done by submitting a request to your server support team. You can also contact our support team <a target="_blank" href="%s">here</a>.', 'registrationmagic-gold'),'https://registrationmagic.com/help-support/'); ?>
            </div>
        </div>    
        <?php
        $form = new RM_PFBC_Form("add-login-validation");

        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        $form->addElement(new Element_Textarea("<b>" .__('Invalid Username error message', 'registrationmagic-gold') . "</b>", "un_error_msg", array("value" => $params['un_error_msg'], "longDesc" => RM_UI_Strings::get('LABEL_USERNAME_ERROR') )));
        $form->addElement(new Element_Textarea("<b>" . __('Invalid Password', 'registrationmagic-gold') . "</b>", "pass_error_msg", array("value" => $params['pass_error_msg'], "longDesc" => RM_UI_Strings::get('LABEL_PASSWORD_ERROR'))));
        $form->addElement(new Element_Textarea("<b>" . __('Invalid Submissions Access', 'registrationmagic-gold') . "</b>", "sub_error_msg", array("value" => $params['sub_error_msg'], "longDesc" => RM_UI_Strings::get('LABEL_SUBMISSION_ERROR'))));
        $form->addElement(new Element_Checkbox("<b>" . __('Display Password recovery link with error message?', 'registrationmagic-gold') . "</b>", "en_recovery_link", array(1 => ""), array("class" => "rm-static-field rm_input_type", "value" =>isset($params['en_recovery_link']) ? $params['en_recovery_link']: 0, "longdesc" => __('Displays password recovery link within the error message.', 'registrationmagic-gold'))));
        $form->addElement(new Element_Checkbox("<b>" . __('Notify users about failed login attempt?', 'registrationmagic-gold') . "</b>", "en_failed_user_notification", array(1 => ""), array("class" => "rm-static-field rm_input_type", "value" =>isset($params['en_failed_user_notification']) ? $params['en_failed_user_notification'] : 0, "longdesc" => __('During failed login attempt, if the username or email is correct, the owner of the account will be informed that there was a failed login attempt related to their account. Content of this email can be modified in Email Templates section of the Login Form Dashboard.', 'registrationmagic-gold'))));
        $form->addElement(new Element_Checkbox("<b>" . __('Notify admin about failed login attempt?', 'registrationmagic-gold') . "</b>", "en_failed_admin_notification", array(1 => ""), array("class" => "rm-static-field rm_input_type", "value" =>isset($params['en_failed_admin_notification']) ? $params['en_failed_admin_notification'] : 0, "longdesc" => __('Notifies the Admin if there is a failed login attempt. Content of this email can be modified in Email Templates section of the Login Form Dashboard.', 'registrationmagic-gold'))));
        $form->addElement(new Element_Checkbox("<b>" . __('Display reCAPTCHA after failed login attempts?', 'registrationmagic-gold') . "</b>", "en_captcha", array(1 => ""), array("class" => "rm-static-field rm_input_type", "value" =>isset($params['en_captcha']) ? $params['en_captcha'] : 0, "longdesc" => __('After a certain number of failed login attempts from a specific IP, displays a reCAPTCHA to confirm if the user is human. Please note, reCAPTCHA must be first properly configured in Global Settings → Security to make this option work.', 'registrationmagic-gold'))));
            $form->addElement(new Element_HTML('<div id="rm_recaptcha_settings" class="childfieldsrow">'));
                        $form->addElement(new Element_Number("<b>" . __('No. of attempts', 'registrationmagic-gold') . "</b>", "allowed_failed_attempts", array("value" => $params['allowed_failed_attempts'], "longDesc" => __('Number of consecutive failed login attempts allowed before displaying reCAPTCHA. Set it to 0 to always display reCAPTCHA on login form.', 'registrationmagic-gold'))));
                        $form->addElement(new Element_Number("<b>" . __('Time period', 'registrationmagic-gold') . "</b>", "allowed_failed_duration", array("value" => $params['allowed_failed_duration'], "longDesc" => __('Define the time period in minutes, during which the failed login attempts will be counted as consecutive failures. For example, 60 means system will count all consecutive failed login attempts within a time period of 1 hour before displaying reCAPTCHA.', 'registrationmagic-gold'))));  
            $form->addElement(new Element_HTML('</div>'));
            
        $form->addElement(new Element_Checkbox("<b>" . __('Block IP after failed login attempts?', 'registrationmagic-gold') . "</b>", "en_ban_ip", array(1 => ""), array("class" => "rm-static-field rm_input_type", "value" =>isset($params['en_ban_ip']) ? $params['en_ban_ip'] : 0, "longdesc" => __('After a certain number of failed login attempts from a specific IP, login form will no longer be displayed for that user. The ban can be temporary or permanent based on options below.', 'registrationmagic-gold'))));    
            $form->addElement(new Element_HTML('<div id="em_block_ip">'));
                    $form->addElement(new Element_HTML('<div class="childfieldsrow">'));
                        $form->addElement(new Element_Number("<b>" . __('No. of attempts', 'registrationmagic-gold') . "</b>", "allowed_attempts_before_ban", array("value" => $params['allowed_attempts_before_ban'], "longDesc" => __('Number of consecutive failed invalid login attempts allowed before banning the IP.', 'registrationmagic-gold'))));
                        $form->addElement(new Element_Number("<b>" . __('Time period', 'registrationmagic-gold') . "</b>", "allowed_duration_before_ban", array("value" => $params['allowed_duration_before_ban'], "longDesc" => __('Define the time period in minutes, during which the failed login attempts will be counted as consecutive failures. For example, 60 means system will count all consecutive failed login attempts within a time period of 1 hour before banning the IP.', 'registrationmagic-gold'))));
                        $form->addElement(new Element_Select("<b>" . __('Type of Ban', 'registrationmagic-gold') . "</b>", "ban_type", array('temp'=>__("Temporary",'registrationmagic-gold'),'per'=>__("Permanent",'registrationmagic-gold')), array("value" => $params['ban_type'], "class" => "rm_static_field", "longDesc"=>'')));
                            $form->addElement(new Element_HTML('<div id="rm_temp_ban" class="childfieldsrow">'));
                                $form->addElement(new Element_Number("<b>" . __('Period of ban', 'registrationmagic-gold') . "</b>", "ban_duration", array("value" => $params['ban_duration'], "longDesc" => __('The subsequent time period for which the IP will be banned in minutes. For example, 1440 will mean that the IP will be blocked for next 24 hours after last allowed unsuccessful login attempt.', 'registrationmagic-gold'))));
                            $form->addElement(new Element_HTML('</div>'));
                        $form->addElement(new Element_TinyMCEWP("<b>" . __('Error message', 'registrationmagic-gold') . "</b>", $params['ban_error_msg'], "ban_error_msg", array('editor_class' => 'rm_TinyMCE', 'editor_height' => '100px'), array("longDesc" => __('User whose IP has been automatically banned due to successive failure login attempts will see this message. For temporary bans, you can use code {{ban_period}} to inform users about the period of ban. {{ban_period_remains}} displays the time remaining until the ban is lifted relative to the current time.', 'registrationmagic-gold'))));
                        $form->addElement(new Element_Checkbox("<b>" . __('Notify Admin', 'registrationmagic-gold') . "</b>", "notify_admin_on_ban", array(1 => ""), array("class" => "rm-static-field rm_input_type", "value" =>isset($params['notify_admin_on_ban']) ? $params['notify_admin_on_ban'] : 0, "longdesc" => __('Send a notification message to Admin when an IP ban is triggered. You can define contents of this message in Email Templates inside Login Form Dashboard.', 'registrationmagic-gold'))));
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
        jQuery("#add-login-validation-element-5-0").change(function(){
            jQuery(this).is(':checked') ? jQuery("#rm_recaptcha_settings").slideDown() : jQuery("#rm_recaptcha_settings").slideUp(); 
        });
        
        jQuery("#add-login-validation-element-10-0").change(function(){
            jQuery(this).is(':checked') ? jQuery("#em_block_ip").slideDown() : jQuery("#em_block_ip").slideUp();
        });
        
        jQuery("#add-login-validation-element-15").change(function(){
            jQuery(this).val()=="temp" ? jQuery("#rm_temp_ban").slideDown() : jQuery("#rm_temp_ban").slideUp();
        });
        
        
        jQuery("#add-login-validation-element-5-0,#add-login-validation-element-10-0,#add-login-validation-element-15").trigger('change');
        
    });
    
    
    
</script>    
