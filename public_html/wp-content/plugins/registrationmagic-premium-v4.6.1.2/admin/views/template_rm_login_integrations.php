<?php
if (!defined('WPINC')) {
    die('Closed');
}
$type= $data->type;
$options= $data->options;
?>
<div class="rmagic">
    <!--Dialogue Box Starts-->
    <div class="rmcontent">
            <div class="rmheader"><?php echo _e('Social Integration', 'registrationmagic-gold'); ?></div>
        <?php
        if(!RM_Utilities::is_ssl() && $type=='fb'){
            echo _e('<div class="rmrow"><div class="rmnotice">Warning: SSL not detected! You need to have SSL installed on your site to make Facebook login work properly.</div></div>', 'registrationmagic-gold');
        }
        
        $form = new RM_PFBC_Form("login-integrations");

        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        
        if($type=='fb'){
            if(RM_Utilities::is_ssl()){
                $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_LOGIN_FACEBOOK_OPTION'), "enable_facebook", array("yes" => ''),array('id'=>'enable_facebook',"class" => "id_rm_enable_fb_cb" , "value" =>$options['enable_facebook'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_FB_ENABLE'))));
                $form->addElement(new Element_HTML('<div class="childfieldsrow" style="display:none;" id="rm_div_enable_facebook-0">'));
                    $form->addElement(new Element_Textbox(RM_UI_Strings::get('LABEL_FACEBOOK_APP_ID'), "facebook_app_id", array("value" =>$options['facebook_app_id'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_FB_APPID'))));
                $form->addElement(new Element_HTML("</div>"));
            }else{
                $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_LOGIN_FACEBOOK_OPTION'), "enable_facebook", array("yes" => ''),array('id'=>'enable_facebook',"class" => "id_rm_enable_fb_cb" , "value" =>"","disabled"=>"disabled", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_FB_ENABLE'))));
                $form->addElement(new Element_Hidden("facebook_app_id", ""));
            }
        }
        else if($type=='google'){
            $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_LOGIN_GPLUS_OPTION'), "enable_gplus", array("yes" => ''),array('id'=>'enable_gplus',"class" => "id_rm_enable_gp_cb" , "value" =>$options['enable_gplus'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_GP_ENABLE'))));
                $form->addElement(new Element_HTML('<div class="childfieldsrow" style="display:none;" id="rm_div_enable_gplus-0">'));
                    $form->addElement(new Element_Textbox(RM_UI_Strings::get('LABEL_GPLUS_CLIENT_ID'), "gplus_client_id", array("value" => $options['gplus_client_id'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_GP_CLIENT_ID'))));        
                $form->addElement(new Element_HTML("</div>"));
        }
        else if($type=='win'){
            $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_LOGIN_WINDOWS_OPTION'), "enable_window_login", array("yes" => ''),array('id'=>'enable_window_login',"class" => "id_rm_enable_win_cb" , "value" => $options['enable_window_login'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_WINDOWS_ENABLE'))));
                $form->addElement(new Element_HTML('<div class="childfieldsrow" style="display:none;" id="rm_div_enable_window_login-0">'));
                    $form->addElement(new Element_Textbox(RM_UI_Strings::get('LABEL_WIN_CLIENT_ID'), "windows_client_id", array("value" => $options['windows_client_id'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_WIN_CLIENT_ID'))));        
                $form->addElement(new Element_HTML("</div>"));
        }
        else if($type=='tw'){
            $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_LOGIN_TWITTER_OPTION'), "enable_twitter_login", array("yes" => ''),array('id'=>'enable_twitter_login',"class" => "id_rm_enable_tw_cb" , "value" =>$options['enable_twitter_login'],"longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_TWITTER_ENABLE'))));
                $form->addElement(new Element_HTML('<div class="childfieldsrow" style="display:none;" id="rm_div_enable_twitter_login-0">'));
                    $form->addElement(new Element_Textbox(RM_UI_Strings::get('LABEL_TW_CONSUMER_KEY'), "tw_consumer_key", array("value" => $options['tw_consumer_key'],"longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_TW_CONSUMER_KEY'))));        
                    $form->addElement(new Element_Textbox(RM_UI_Strings::get('LABEL_TW_CONSUMER_SEC'), "tw_consumer_secret", array("value" => $options['tw_consumer_secret'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_TW_CONSUMER_SEC'))));        
                $form->addElement(new Element_HTML("</div>"));
        } 
        else if($type=='inst'){
            $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_LOGIN_INSTAGRAM_OPTION'), "enable_instagram_login", array("yes" => ''),array('id'=>'enable_instagram_login',"class" => "id_rm_enable_ins_cb" , "value" =>$options['enable_instagram_login'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_INSTAGRAM_ENABLE'))));
                $form->addElement(new Element_HTML('<div class="childfieldsrow" style="display:none;" id="rm_div_enable_instagram_login-0">'));
            $form->addElement(new Element_Textbox(RM_UI_Strings::get('LABEL_INS_CLIENT_ID'), "instagram_client_id", array("value" =>$options['instagram_client_id'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_INS_CLIENT_ID'))));        

            $form->addElement(new Element_HTML("</div>"));
        }
        
        else if($type=='linked'){
            $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_LOGIN_LINKEDIN_OPTION'), "enable_linked", array("yes" => ''),array('id'=>'enable_linked',"class" => "id_rm_enable_lin_cb" , "value" =>$options['enable_linked'],"longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_LINKEDIN_ENABLE'))));
                $form->addElement(new Element_HTML('<div class="childfieldsrow" style="display:none;" id="rm_div_enable_linked-0">'));
                    $form->addElement(new Element_Textbox(RM_UI_Strings::get('LABEL_LIN_API_KEY'), "linkedin_api_key", array("value" =>$options['linkedin_api_key'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_LIN_API_KEY'))));        
                    $form->addElement(new Element_Textbox(RM_UI_Strings::get('LABEL_LIN_SEC_KEY'), "linkedin_secret_key", array("value" =>$options['linkedin_secret_key'], "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_LIN_SEC_KEY'))));
                $form->addElement(new Element_HTML("</div>"));
        }
        
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page=rm_login_sett_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit")));
        $form->render();
        ?>
    </div>
</div>

<script>
    jQuery(document).ready(function(){
        jQuery("input[type=checkbox]").change(function(){ 
            var id= jQuery(this).prop('id');
            
            if(jQuery(this).is(':checked')){
                jQuery("#rm_div_" + id).slideDown();
                return;
            }
            jQuery("#rm_div_" + id).slideUp();
        });
        jQuery("input[type=checkbox]").trigger('change');
        
    });
</script>    