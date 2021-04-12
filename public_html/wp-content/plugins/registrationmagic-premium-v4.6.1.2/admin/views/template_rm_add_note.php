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
        $form = new RM_PFBC_Form("add-note");

        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
  
if($data->type=='message')
{
  $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get("TITLE_NEW_MESSAGE_PAGE") . '</div>'));
  $form->addElement(new Element_Hidden("submission_id", $data->submission_id));
  $form->addElement(new Element_Hidden("type",'message'));
  $form->addElement(new Element_Textarea("<b>" . RM_UI_Strings::get('LABEL_MESSAGE_TEXT') . "</b>", "notes", array("class" => "rm-static-field rm_field_value", "value" => '')));
  $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page=rm_submission_view&rm_submission_id=' . $data->submission_id, array('class' => 'cancel')));
  $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SEND'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit")));
    $form->render();    
  
}
else{
        if ($data->model->get_note_id())
        {
            $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get("TITLE_EDIT_NOTE_PAGE") . '</div>'));
            $form->addElement(new Element_Hidden("note_id", $data->model->get_note_id()));
        } else
            $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get("TITLE_NEW_NOTE_PAGE") . '</div>'));

        $form->addElement(new Element_Hidden("submission_id", $data->submission_id));
        $form->addElement(new Element_Hidden("type",'note'));
        $form->addElement(new Element_Textarea(RM_UI_Strings::get('LABEL_NOTE_TEXT'), "notes", array("class" => "rm-static-field rm_field_value", "value" => $data->model->get_notes(), "longdesc" => RM_UI_Strings::get('HELP_NOTE_ADD_NOTE_TEXT'))));
        $form->addElement(new Element_Color("<b>" . RM_UI_Strings::get('LABEL_NOTE_COLOR') . "</b>", "bg_color", array("class" => "jscolor", "value" => $data->model->get_note_options()->bg_color, "longdesc" => RM_UI_Strings::get('HELP_NOTE_ADD_NOTE_COLOR'))));
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_VISIBLE_FRONT') . "</b>", "status", array(1 => ""), array("class" => "rm-static-field rm_input_type", "value" => $data->model->get_status(), "longdesc" => RM_UI_Strings::get('HELP_NOTE_ADD_IS_VISIBLE'))));

        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page=rm_submission_view&rm_submission_id=' . $data->submission_id, array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit")));

        $form->render();
}
        ?>
    </div>
</div>