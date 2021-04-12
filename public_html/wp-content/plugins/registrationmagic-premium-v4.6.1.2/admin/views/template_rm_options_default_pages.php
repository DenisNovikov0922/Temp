<?php
if (!defined('WPINC')) {
    die('Closed');
}
?>
<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php
        $wp_pages = RM_Utilities::wp_pages_dropdown();
        $form = new RM_PFBC_Form("rm_default_pages");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        $form->addElement(new Element_HTML('<div class="rmheader">'.__('Default Pages', 'registrationmagic-gold').'</div>'));
        $selected = ($data['default_registration_url'] !== null) ? $data['default_registration_url'] : 0;
        $form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_DEFAULT_REGISTER_URL'), "default_registration_url", $wp_pages, array("value" => $selected, "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_REG_URL'))));
        
        $options= new RM_Options();
        $front_sub_page= $options->get_value_of('front_sub_page_id');
        $form->addElement(new Element_Select(__('Default User Account Page', 'registrationmagic-gold'), "default_user_acc_page", $wp_pages, array("value" => $front_sub_page,"longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_ACCOUNT_URL'))));

        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page=rm_options_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit", "onClick" => "jQuery.prevent_field_add(event,'".__('This is a required field.','registrationmagic-gold') ."')")));
        $form->render();
        ?>
    </div>
</div>

<?php

