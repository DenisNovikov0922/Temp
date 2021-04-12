<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php
        $form = new RM_PFBC_Form("form_sett_newsletter");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        
        if (isset($data->model->form_id)) {
            $form->addElement(new Element_HTML('<div class="rmheader">' . $data->model->form_name . '</div>'));
            $form->addElement(new Element_HTML('<div class="rmsettingtitle">' . RM_NLetter_UI_Strings::get('NAME_NLETTER') . '</div>'));
            $form->addElement(new Element_Hidden("form_id", $data->model->form_id));
        } else {
            $form->addElement(new Element_HTML('<div class="rmheader">' . RM_NLetter_UI_Strings::get("TITLE_NEW_FORM_PAGE") . '</div>'));
        }
       
         //Check if Newsletter plugin is installed/activated
        if(!rm_is_nletter_active())
        {
            $form->addElement(new Element_HTML(RM_NLetter_UI_Strings::get('NL_ERROR')));
            $form->addElement (new Element_HTMLL ('&#8592; &nbsp; Cancel', '?page=rm_form_sett_manage&rm_form_id='.$data->model->form_id, array('class' => 'cancel')));
            $form->render();
            return;
        }
        
        $service= new RM_NLetter_Service();
        $form->addElement(new Element_HTML('<input type="hidden" id="form_id" value="' . $data->model->get_form_id() . '"/>'));
        $form->addElement(new Element_Checkbox(RM_NLetter_UI_Strings::get('LABEL_NLETTER_INTEGRATION'), "enable_newsletter", array(1 => ""),array("id" => "id_rm_enable_nl_cb", "class" => "id_rm_enable_nl_cb" ,"onclick" => "hide_show(this)", "value" =>$data->model->form_options->enable_newsletter,  "longDesc" => RM_NLetter_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_NL_ENABLE'))));   
        
        if((isset($data->model->form_options->enable_newsletter) && $data->model->form_options->enable_newsletter[0] == 1))
           $form->addElement(new Element_HTML('<div class="childfieldsrow" id="id_rm_enable_nl_cb_childfieldsrow">'));
        else
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="id_rm_enable_nl_cb_childfieldsrow" style="display:none">'));
        
        $form->addElement(new Element_Select("<b>" . RM_NLetter_UI_Strings::get('LABEL_NLETTER_LIST') . ":</b>", "newsletter_list_id", $data->lists, array("id" => "newsletter_list_id", "value" => $data->model->form_options->newsletter_list_id, "longDesc" => RM_NLetter_UI_Strings::get('HELP_ADD_FORM_MP_LIST'))));
        
        $newsletter_field_mappings= isset($data->model->form_options->newsletter_field_mappings) ? $data->model->form_options->newsletter_field_mappings : array();
        $form->addElement(new Element_Select("<b>" .RM_NLetter_UI_Strings::get('LABEL_FIRST_NAME'). ":</b>", 'nn', $service->get_supported_rm_fields($data->form_id,'nn',true), array("value"=>isset($newsletter_field_mappings['nn']) ? $newsletter_field_mappings['nn'] : null,"longDesc"=>"")));
        $form->addElement(new Element_Select("<b>" .RM_NLetter_UI_Strings::get('LABEL_LAST_NAME'). ":</b>", 'ns', $service->get_supported_rm_fields($data->form_id,'ns',true), array("value"=>isset($newsletter_field_mappings['ns']) ? $newsletter_field_mappings['ns'] : null,"longDesc"=>"")));
        $form->addElement(new Element_Select("<b>" .RM_NLetter_UI_Strings::get('LABEL_GENDER'). ":</b>", 'nx', $service->get_supported_rm_fields($data->form_id,'nx',true), array("value"=>isset($newsletter_field_mappings['nx']) ? $newsletter_field_mappings['nx'] : null,"longDesc"=>"")));
        
        
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_OPT_IN_CB') . ":</b>", "form_is_opt_in_checkbox_nl", array(1 => ""), array("id" => "rm_", "class" => "rm_op", "onclick" => "hide_show(this);", "value" => $data->model->form_options->form_is_opt_in_checkbox_nl, "longDesc" => RM_UI_Strings::get('HELP_OPT_IN_CB'))));
        if ($data->model->form_options->form_is_opt_in_checkbox_nl[0] == '1')
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_op_childfieldsrow" >'));
        else
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_op_childfieldsrow" style="display:none">'));
        $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_OPT_IN_CB_TEXT') . ":</b>", "form_opt_in_text_nl", array("id" => "rm_form_name", "value" =>isset($data->model->form_options->form_opt_in_text_nl)?$data->model->form_options->form_opt_in_text_nl:'Subscribe for emails' , "longDesc" => RM_NLetter_UI_Strings::get('HELP_OPT_IN_CB_TEXT'))));
        $form->addElement(new Element_Radio("<b>" . RM_UI_Strings::get('LABEL_DEFAULT_STATE') . "</b>", "form_opt_in_default_state_nl", array('Checked'=>RM_UI_Strings::get('LABEL_CHECKED'),'Unchecked'=>RM_UI_Strings::get('LABEL_UNCHECKED')), array("id"=>"id_rm_default_state",  "value" => isset($data->model->form_options->form_opt_in_default_state_nl)?$data->model->form_options->form_opt_in_default_state_nl:'Unchecked', "longDesc" => RM_NLetter_UI_Strings::get('MSG_OPT_IN_DEFAULT_STATE'))));
       
        $form->addElement(new Element_HTML('</div>'));
 
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement (new Element_HTMLL ('&#8592; &nbsp; Cancel', '?page='.$data->next_page.'&rm_form_id='.$data->model->form_id, array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit", "onClick" => "jQuery.prevent_field_add(event,'This is a required field.')")));
       
        
        $form->render();
        ?>
    </div>
</div>

