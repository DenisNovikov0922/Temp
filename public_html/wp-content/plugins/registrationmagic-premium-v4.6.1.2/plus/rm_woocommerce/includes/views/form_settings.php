<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">
        <?php
        $form = new RM_PFBC_Form("form_sett_wc");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        
        if (isset($data->model->form_id)) {
            $form->addElement(new Element_HTML('<div class="rmheader">' . $data->model->form_name . '</div>'));
            $form->addElement(new Element_HTML('<div class="rmsettingtitle">' . RM_WC_UI_Strings::get('NAME_WC') . '</div>'));
            $form->addElement(new Element_Hidden("form_id", $data->model->form_id));
        } else {
            $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get("TITLE_NEW_FORM_PAGE") . '</div>'));
        }
        
       
        
        if(!rm_is_wc_active())
        {
            $form->addElement(new Element_HTML(RM_WC_UI_Strings::get('WC_ERROR')));
            $form->addElement (new Element_HTMLL ('&#8592; &nbsp; '.__( 'Cancel', 'registrationmagic-gold' ), '?page='.$data->next_page.'&rm_form_id='.$data->model->form_id, array('class' => 'cancel')));
        }
        else
        {
            $form->addElement(new Element_HTML(RM_WC_UI_Strings::get('WC_FORM_SETTING_TEXT')));
            $form->addElement (new Element_HTMLL ('&#8592; &nbsp; '.__( 'Cancel', 'registrationmagic-gold' ), '?page='.$data->next_page.'&rm_form_id='.$data->model->form_id, array('class' => 'cancel')));
        }
        
       
        $form->render();
        ?>
    </div>
</div>

