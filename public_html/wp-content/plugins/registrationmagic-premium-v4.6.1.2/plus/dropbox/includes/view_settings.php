<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php
        $form = new RM_PFBC_Form("form_sett_dpx");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery", "focus"),
            "action" => ""
        ));

        if (isset($data->model->form_id)) {
            $form->addElement(new Element_HTML('<div class="rmheader">' . $data->model->form_name . '</div>'));
            $form->addElement(new Element_HTML('<div class="rmsettingtitle">' . RM_Dpx_UI_Strings::get('LABEL_F_DPX_SETT') . '</div>'));
            $form->addElement(new Element_Hidden("form_id", $data->model->form_id));
        } else {
            $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get("TITLE_NEW_FORM_PAGE") . '</div>'));
        }

        $form->addElement(new Element_Checkbox(RM_Dpx_UI_Strings::get('LABEL_ENABLE_DPX'), "enable_dpx", array(1 => ''), array("id" => "id_rm_enable_dpx_cb", "class" => "id_rm_enable_dpx_cb", "value" =>$data->model->form_options->enable_dpx, "onclick" => "hide_show(this)", "longDesc" => RM_Dpx_UI_Strings::get('HELP_OPTIONS_DPX'))));
        
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page='.$data->next_page.'&rm_form_id='.$data->model->form_id, array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit", "onClick" => "jQuery.prevent_field_add(event,'This is a required field.')")));
        $form->render();
        ?>
    </div>
</div>

<?php


