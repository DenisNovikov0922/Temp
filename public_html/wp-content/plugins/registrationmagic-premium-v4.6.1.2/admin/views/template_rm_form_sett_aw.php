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
        
        
        $form = new RM_PFBC_Form("form_sett_aweber");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        if (isset($data->model->form_id)) {
            $form->addElement(new Element_HTML('<div class="rmheader">' . $data->model->form_name . '</div>'));
            $form->addElement(new Element_HTML('<div class="rmsettingtitle">' . RM_UI_Strings::get('LABEL_AWEBER_OPTION') . '</div>'));
            $form->addElement(new Element_Hidden("form_id", $data->model->form_id));
        } else {
            $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get("TITLE_NEW_FORM_PAGE") . '</div>'));
        }

 if($data->error!=null)
        {
             $form->addElement(new Element_HTML($data->error));
            $form->addElement (new Element_HTMLL ('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page='.$data->next_page.'&rm_form_id='.$data->model->form_id, array('class' => 'cancel')));
      
        }
        else
        {
        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_AWEBER_OPTION'), "enable_aweber", array(1 => ""),array("id" => "id_rm_enable_aw_cb", "class" => "id_rm_enable_aw_cb" ,"onclick" => "hide_show(this)",  "value" =>  $data->model->form_options->enable_aweber,   "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_AW_ENABLE'))));
        //var_dump($data->model->form_options->enable_aweber);
        if ($data->model->form_options->enable_aweber[0] == '1')
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="id_rm_enable_aw_cb_childfieldsrow" >'));
        else
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="id_rm_enable_aw_cb_childfieldsrow" style="display:none">'));

        $form->addElement(new Element_Select("<b>" . RM_UI_Strings::get('LABEL_AW_LIST') . "</b>", "aw_list", $data->aw_list, array("id" => "aw_list", "value" => $data->aw_form_list, "longDesc" => RM_UI_Strings::get('HELP_ADD_FORM_AW_LIST'))));
        $form->addElement(new Element_Select("<b>" . RM_UI_Strings::get('LABEL_EMAIL') . "</b>", "email",$data->field_array['email'], array("id" => "email", "value" =>isset($data->model->form_options->aw_relations->email) ?$data->model->form_options->aw_relations->email : ""  , "longDesc"=>RM_UI_Strings::get('HELP_ADD_FIELD_FIELD'))));
        $form->addElement(new Element_Select("<b>" . RM_UI_Strings::get('FIRST_NAME') . "</b>", "first_name",$data->field_array['string'], array("id" => "first_name", "value" =>isset($data->model->form_options->aw_relations->first_name) ?$data->model->form_options->aw_relations->first_name : "" , "longDesc"=>RM_UI_Strings::get('HELP_ADD_FIELD_FIELD'))));
        $form->addElement(new Element_Select("<b>" . RM_UI_Strings::get('LAST_NAME') . "</b>", "last_name",$data->field_array['string'], array("id" => "last_name", "value" => isset($data->model->form_options->aw_relations->last_name) ?$data->model->form_options->aw_relations->last_name : "" , "longDesc"=>RM_UI_Strings::get('HELP_ADD_FIELD_FIELD'))));
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_OPT_IN_CB') . "</b>", "form_is_opt_in_checkbox_aw", array(1 => ""), array("id" => "rm_", "class" => "rm_op", "onclick" => "hide_show(this);", "value" => $data->model->form_options->form_is_opt_in_checkbox_aw, "longDesc" => RM_UI_Strings::get('HELP_OPT_IN_CB_AW'))));

        if ($data->model->form_options->form_is_opt_in_checkbox_aw[0] == '1')
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_op_childfieldsrow" >'));
        else
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_op_childfieldsrow" style="display:none">'));



        $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_OPT_IN_CB_TEXT') . "</b>", "form_opt_in_text_aw", array("id" => "rm_form_name", "required"=>"required","value" => isset($data->model->form_options->form_opt_in_text_aw)?$data->model->form_options->form_opt_in_text_aw:__('Subscribe for emails','registrationmagic-gold'), "longDesc" => RM_UI_Strings::get('HELP_OPT_IN_CB_TEXT'))));
         $form->addElement(new Element_Radio("<b>" . RM_UI_Strings::get('LABEL_DEFAULT_STATE') . "</b>", "form_opt_in_default_state_aw", array('Checked'=>RM_UI_Strings::get('LABEL_CHECKED'),'Unchecked'=>RM_UI_Strings::get('LABEL_UNCHECKED')), array("id"=>"id_rm_default_state","required"=>"required", "value" => isset($data->model->form_options->form_opt_in_default_state_aw)?$data->model->form_options->form_opt_in_default_state_aw:'Unchecked', "longDesc" => RM_UI_Strings::get('MSG_OPT_IN_DEFAULT_STATE'))));
       
        $form->addElement(new Element_HTML('</div>'));
        $form->addElement(new Element_HTML('</div>'));
        $form->addElement (new Element_HTMLL ('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page='.$data->next_page.'&rm_form_id='.$data->model->form_id, array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit", "onClick" => "jQuery.prevent_field_add(event,'".__('This is a required field.','registrationmagic-gold')."')")));
        
        }
        $form->render();
        ?>
    </div>
</div>

<?php
