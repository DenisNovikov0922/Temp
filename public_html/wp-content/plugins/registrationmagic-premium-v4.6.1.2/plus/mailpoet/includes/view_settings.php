<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">
        <?php
        $form = new RM_PFBC_Form("form_sett_mailpoet");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        
        if (isset($data->model->form_id)) {
            $form->addElement(new Element_HTML('<div class="rmheader">' . $data->model->form_name . '</div>'));
            $form->addElement(new Element_HTML('<div class="rmsettingtitle">' . RM_MailPoet_UI_Strings::get('NAME_MAILPOET') . '</div>'));
            $form->addElement(new Element_Hidden("form_id", $data->model->form_id));
        } else {
            $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get("TITLE_NEW_FORM_PAGE") . '</div>'));
        }
        
       
         //Check if MailPoet plugin is installed/activated
        if(!rm_is_mailpoet_active())
        {
            $form->addElement(new Element_HTML(RM_MailPoet_UI_Strings::get('MP_ERROR')));
            $form->addElement (new Element_HTMLL ('&#8592; &nbsp; Cancel', '?page=rm_form_sett_manage&rm_form_id='.$data->model->form_id, array('class' => 'cancel')));
            $form->render();
            return;
        }
        
        $form->addElement(new Element_HTML('<input type="hidden" id="form_id" value="' . $data->model->get_form_id() . '"/>'));
        $form->addElement(new Element_Checkbox(RM_MailPoet_UI_Strings::get('LABEL_MAILPOET_INTEGRATION'), "enable_mailpoet", array(1 => ""),array("id" => "id_rm_enable_mp_cb", "class" => "id_rm_enable_mp_cb" ,"onclick" => "hide_show(this)", "value" =>empty($data->mp_form_id) ? $data->model->form_options->enable_mailpoet : '1',  "longDesc" => RM_MailPoet_UI_Strings::get('HELP_OPTIONS_THIRDPARTY_MC_ENABLE'))));   
        
        $service= new RM_Mailpoet_Service();
        
        if((isset($data->model->form_options->enable_mailpoet) && $data->model->form_options->enable_mailpoet[0] == 1) || !empty($data->mp_form_id))
           $form->addElement(new Element_HTML('<div class="childfieldsrow" id="id_rm_enable_mp_cb_childfieldsrow">'));
        else
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="id_rm_enable_mp_cb_childfieldsrow" style="display:none">'));
        
        $form->addElement(new Element_Select("<b>" . RM_MailPoet_UI_Strings::get('LABEL_MAILPOET_LIST') . ":</b>", "mailpoet_form", $data->mailpoet_forms, array("id" => "mailchimp_list", "value" => !empty($data->mp_form_id) ? $data->mp_form_id : $data->model->form_options->mailpoet_form, "onchange" => "get_fields(this);", "longDesc" => RM_MailPoet_UI_Strings::get('HELP_ADD_FORM_MP_LIST'))));
        
        $mailpoet_field_mappings= isset($data->model->form_options->mailpoet_field_mappings) ? $data->model->form_options->mailpoet_field_mappings : array();
        $long_desc='';
        foreach($data->mailpoet_fields as $key=>$field):
            if($field[1]=="date")
                $long_desc= RM_MailPoet_UI_Strings::get('HELP_SUPP_DATE_FIELDS');
            $form->addElement(new Element_Select("<b>" . $field[0] . ":</b>", $key, $service->get_supported_rm_fields($data->form_id,$field[1],true), array("value"=>isset($mailpoet_field_mappings[$key]) ? $mailpoet_field_mappings[$key] : null,"longDesc"=>$long_desc)));
            $long_desc='';
        endforeach;
        
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_OPT_IN_CB') . ":</b>", "form_is_opt_in_checkbox_mp", array(1 => ""), array("id" => "rm_", "class" => "rm_op", "onclick" => "hide_show(this);", "value" => $data->model->form_options->form_is_opt_in_checkbox_mp, "longDesc" => RM_UI_Strings::get('HELP_OPT_IN_CB'))));
        if ($data->model->form_options->form_is_opt_in_checkbox_mp[0] == '1')
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_op_childfieldsrow" >'));
        else
            $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_op_childfieldsrow" style="display:none">'));
        $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_OPT_IN_CB_TEXT') . ":</b>", "form_opt_in_text_mp", array("id" => "rm_form_name", "value" =>isset($data->model->form_options->form_opt_in_text_mp)?$data->model->form_options->form_opt_in_text_mp:'Subscribe for emails' , "longDesc" => RM_UI_Strings::get('HELP_OPT_IN_CB_TEXT'))));
        $form->addElement(new Element_Radio("<b>" . RM_UI_Strings::get('LABEL_DEFAULT_STATE') . "</b>", "form_opt_in_default_state_mp", array('Checked'=>RM_UI_Strings::get('LABEL_CHECKED'),'Unchecked'=>RM_UI_Strings::get('LABEL_UNCHECKED')), array("id"=>"id_rm_default_state",  "value" => isset($data->model->form_options->form_opt_in_default_state_mp)?$data->model->form_options->form_opt_in_default_state_mp:'Unchecked', "longDesc" => RM_UI_Strings::get('MSG_OPT_IN_DEFAULT_STATE'))));
       
        $form->addElement(new Element_HTML('</div>'));
 
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement (new Element_HTMLL ('&#8592; &nbsp; Cancel', '?page='.$data->next_page.'&rm_form_id='.$data->model->form_id, array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit", "onClick" => "jQuery.prevent_field_add(event,'This is a required field.')")));
       
        
        $form->render();
        ?>
    </div>
</div>
<script type="text/javascript">
    function get_fields($obj)
    {
           var form_id= "<?php echo $data->model->form_id; ?>"; 
           var current_page= "<?php echo $_SERVER['REQUEST_URI']; ?>";
           var url= current_page + "&mp_form_id=" + $obj.value;
           window.location= url;
    }
</script>
