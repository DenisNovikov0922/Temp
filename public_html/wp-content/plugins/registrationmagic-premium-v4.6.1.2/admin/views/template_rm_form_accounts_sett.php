<?php
if (!defined('WPINC')) {
    die('Closed');
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//var_dump($data->model->form_options);
?>

<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php
        $form = new RM_PFBC_Form("form_sett_accounts");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));

        if (isset($data->model->form_id)) {
            $form->addElement(new Element_HTML('<div class="rmheader">' . $data->model->form_name . '</div>'));
            $form->addElement(new Element_HTML('<div class="rmsettingtitle">' . RM_UI_Strings::get('LABEL_F_ACC_SETT') . '</div>'));
            $form->addElement(new Element_Hidden("form_id", $data->model->form_id));
        } else {
            $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get("TITLE_NEW_FORM_PAGE") . '</div>'));
        }
        
        $form->addElement(new Element_HTML('<div class="rmrow"><div class="rmnotice">Account approval method can be modified in <a target="_blank" href="'.admin_url("admin.php?page=rm_options_user").'">Global Settings</a>.</div></div>'));    
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_CREATE_WP_ACCOUNT') . "?</b>", "form_type", array(1 => ""), array("id" => "rm_user_create", "class" => "rm_user_create", "onclick" => "hide_show(this);", "value" => $data->model->form_type, "longDesc" => RM_UI_Strings::get('HELP_ADD_FORM_CREATE_WP_USER'))));

        if ($data->model->form_type == 1)
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_user_create_childfieldsrow">'));
        else
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_user_create_childfieldsrow" style="display:none">'));
        
        $default_user_role = $data->model->get_default_form_user_role();
        $option_default_role = get_option('default_role');
        $wp_default_role = (!empty($default_user_role)?$default_user_role:(!empty($option_default_role)?$option_default_role:'subscriber'));
        $form->addElement(new Element_Select("<b>" . RM_UI_Strings::get('LABEL_DO_ASGN_WP_USER_ROLE') . "</b>", "default_form_user_role", $data->roles, array("id" => "rm_user_role", "value" => $wp_default_role, "longDesc" => RM_UI_Strings::get('HELP_ADD_FORM_WP_USER_ROLE_AUTO'))));

        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_LET_USER_PICK') . "</b>", "form_should_user_pick", array(1 => ""), array("id" => "rm_form_should_user_pick", "class" => "rm_form_should_user_pick", "onclick" => "hide_show(this);", "value" => $data->model->form_options->form_should_user_pick, "longDesc" => RM_UI_Strings::get('HELP_ADD_FORM_WP_USER_ROLE_PICK'))));
        
        if(is_array($data->model->form_options->form_should_user_pick) || is_object($data->model->form_options->form_should_user_pick)){
            if (count($data->model->form_options->form_should_user_pick) === 1)
                $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_form_should_user_pick_childfieldsrow">'));
            else
                $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_form_should_user_pick_childfieldsrow" style="display:none">'));
        }else{
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_form_should_user_pick_childfieldsrow" style="display:none">'));
        }

        $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_USER_ROLE_FIELD') . "</b>", "form_user_field_label", array("id" => "rm_role_label", "value" => $data->model->form_options->form_user_field_label, "longDesc" => RM_UI_Strings::get('HELP_ADD_FORM_ROLE_SELECTION_LABEL'))));
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_ALLOW_WP_ROLE') . "</b>", "form_user_role", array_slice($data->roles, 1), array("class" => "rm_allowed_roles", "id" => "rm_", "value" => $data->model->get_form_user_role(), "longDesc" => RM_UI_Strings::get('HELP_ADD_FORM_ALLOWED_USER_ROLE'))));
        $form->addElement(new Element_HTML('</div>'));

        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_AUTO_LOGIN') . "?</b>", "auto_login", array(1 => ""), array("value" => $data->model->form_options->auto_login, "longDesc" => RM_UI_Strings::get('HELP_ADD_FORM_AUTO_LOGIN'))));
        
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_HIDE_USERNAME') . "</b>:", "hide_username", array(1 => ""), array("id" => "rm_hide_username", "class" => "rm_hide_username", "value" => $data->model->form_options->hide_username, "longDesc" => RM_UI_Strings::get('HELP_HIDE_USERNAME'))));
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page='.$data->next_page.'&rm_form_id='.$data->model->form_id, array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit", "onClick" => "jQuery.prevent_field_add(event,'".__('This is a required field.','registrationmagic-gold')."')")));
        $form->render();
        ?>
    </div>
</div>

<?php






        
