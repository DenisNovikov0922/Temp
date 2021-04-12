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
        $form = new RM_PFBC_Form("options_thirdparty");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));

        $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get('GLOBAL_SETTINGS_EXTERNAL_INTEGRATIONS') . '</div>'));
        
        
        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_MAILCHIMP_INTEGRATION'), "enable_mailchimp", array("yes" => ''),array("id" => "id_rm_enable_mc_cb", "class" => "id_rm_enable_mc_cb" , "value" =>  $data['enable_mailchimp'],  "onclick" => "hide_show(this)", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_MC_ENABLE'))));

       if ($data['enable_mailchimp'] == 'yes')
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="id_rm_enable_mc_cb_childfieldsrow">'));
        else
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="id_rm_enable_mc_cb_childfieldsrow" style="display:none">'));
       
        $form->addElement(new Element_Textbox(RM_UI_Strings::get('LABEL_MAILCHIMP_API'), "mailchimp_key", array("value" => $data['mailchimp_key'], "id" => "id_rm_mc_key_tb", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_MC_API'))));
        $form->addElement(new Element_HTML("</div>"));
       
   
   
     $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_AWEBER_OPTION_INTEGRATION'), "enable_aweber", array("yes" => ''),array("id" => "id_rm_enable_aw_cb", "class" => "id_rm_enable_aw_cb" , "value" =>  $data['enable_aweber'],  "onclick" => "hide_show(this)", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_AW_ENABLE'))));
    
     if($data['enable_aweber'] == 'yes')
           $form->addElement(new Element_HTML('<div class="childfieldsrow" id="id_rm_enable_aw_cb_childfieldsrow">'));
    else
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="id_rm_enable_aw_cb_childfieldsrow" style="display:none">'));
    $form->addElement(new Element_Textbox(__('Paste your authorization key','registrationmagic-gold'), "aw_oauth_id", array("value" => $data['aw_oauth_id'], "id"=>"id_rm_aw_oauth_id", "longDesc"=>'<a target="_blank"
                    href="https://auth.aweber.com/1.0/oauth/authorize_app/954abd29">'.__('Click here to get your authorization code','registrationmagic-gold').'</a>.')));
    $form->addElement(new Element_HTML("</div>"));
    
    

        $form->addElement(new Element_Textbox(RM_UI_Strings::get('LABEL_GOOGLE_API_KEY'), "google_map_key", array("value" => $data['google_map_key'], "id" => "id_rm_ggl_key_tb", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_GGL_API'))));
        foreach($data['thirdparty_configs'] as $elements):
            foreach($elements as $element):
                  $form->addElement($element);
            endforeach;
        endforeach;
        
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page=rm_options_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE')));

        $form->render();
        ?>
    </div>
    
</div>
<?php   
