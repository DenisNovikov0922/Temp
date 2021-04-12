<?php
if (!defined('WPINC')) {
    die('Closed');
}
?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<?php 
       $other_status_list= array(''=>'Select Status'); 
       if(is_array($data->form_options->custom_status))
       { 
           foreach($data->form_options->custom_status as $key=>$value){
               if(!empty($data->custom_status) && $data->custom_status['label']==$value['label'])
                   continue;
               $other_status_list[$key]=$value['label'];
           }
       }
?>
<div class="rmagic">
    <!--Dialogue Box Starts-->
    <div class="rmcontent">
        <?php
        require_once RM_EXTERNAL_DIR.'icons/icons_list.php';
        
     
        $form = new RM_PFBC_Form("add-cstatus");

        $form->configure(array(
        "prevent" => array("bootstrap", "jQuery"),
        "action" => ""
        ));
        
        $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get("TITLE_NEW_CSTATUS") . '</div>'));
        $form->addElement(new Element_HTML('<div class="rmrow"><h3>'.__('Label Properties','registrationmagic-gold').'</h3></div>'));
            
        $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_NAME') . "</b>", "cstatus_label", array("id" => "cstatus_label", "required" => "1", "value" => (isset($data->custom_status['label'])?$data->custom_status['label']:''), "longDesc" => RM_UI_Strings::get('HELP_ADD_CSTATUS_TITLE'))));
        $form->addElement(new Element_Textarea("<b>" . RM_UI_Strings::get('LABEL_FORM_DESC') . "</b>", "cstatus_desc", array("id" => "cstatus_desc", "value" => (isset($data->custom_status['label'])?$data->custom_status['desc']:''), "longDesc" => RM_UI_Strings::get('HELP_ADD_CSTATUS_DESC'))));
        $form->addElement(new Element_Color("<b>" . RM_UI_Strings::get('LABEL_COLOR') . "</b>", "cstatus_color", array("class" => "cstatus_color","required"=>1, "value"=>isset($data->custom_status['label'])?$data->custom_status['color']:'000', "longdesc" => RM_UI_Strings::get('HELP_CSTATUS_ADD_COLOR'))));
        $form->addElement(new Element_Hidden("rm_form_id", $data->form_id));
        if($data->new!=true){
            $form->addElement(new Element_Hidden("cstatus_id", $data->cstatus_id));
        }
        $form->addElement(new Element_HTML('<div class="rmrow"><h3>Associated Actions</h3><span>'.RM_UI_Strings::get('STATUS_ACTION_NOTE').'</span></div>'));
        // Other statuses actions
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_OTHER_STATUSES') . "</b>", "cs_action_status_en", array(1 => ""), array("onchange"=>"show_child(this,'rm_cs_other_st_actions')","class" => "rm-has-child rm-static-field rm_input_type", "value" => isset($data->custom_status['cs_action_status_en']) ? absint($data->custom_status['cs_action_status_en']) : 0, "longdesc" => RM_UI_Strings::get('HELP_OTHER_STATUS'))));
        $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_other_st_actions" style="display:none" >'));
            $form->addElement(new Element_Radio(RM_UI_Strings::get('LABEL_STATUS_ACTION').":", "cs_action_status", array('do_nothing'=>'Do Nothing','clear_all'=>'Clear All Other Statuses','clear_specific'=>'Clear Specific Status(es)'), array("onchange"=>"show_other(this,'rm_cs_other_st_specific')","value" => isset($data->custom_status['cs_action_status']) ? $data->custom_status['cs_action_status'] : 'do_nothing', "longDesc"=>'')));
                $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_other_st_specific" style="display:none" >'));
                    $form->addElement(new Element_Select("<b>" . RM_UI_Strings::get('LABEL_STATUSES') . "</b>", "cs_act_status_specific", $other_status_list, array("multiple"=>"multiple","value" => isset($data->custom_status['cs_act_status_specific']) ? $data->custom_status['cs_act_status_specific'] : 99999, "class" => "rm_static_field", "longDesc"=>RM_UI_Strings::get('HELP_CLEAR_STATUSES'))));
                $form->addElement(new Element_HTML('</div>'));     
        $form->addElement(new Element_HTML('</div>'));
        
        // Email to user options
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_CS_EMAIL_TO_USER') . "</b>", "cs_email_user_en", array(1 => ""), array("onchange"=>"show_child(this,'rm_cs_email_user')","class" => "rm-has-child rm-static-field rm_input_type", "value" => isset($data->custom_status['cs_email_user_en']) ? absint($data->custom_status['cs_email_user_en']): 0, "longdesc" => RM_UI_Strings::get('HELP_CS_EMAIL_USER_EN'))));
        $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_email_user" style="display:none" >'));
            $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_SUBJECT') . "</b>", "cs_email_user_subject", array("value" => isset($data->custom_status['cs_email_user_subject'])?$data->custom_status['cs_email_user_subject']:'', "longDesc" => RM_UI_Strings::get('HELP_CS_USER_EMAIL_SUBJECT'))));
            $form->addElement(new Element_Textarea("<b>" . RM_UI_Strings::get('LABEL_BODY') . "</b>", "cs_email_user_body", array("value" => isset($data->custom_status['cs_email_user_body'])?$data->custom_status['cs_email_user_body']:'', "longDesc" => __('Contents of the email to be sent to the user. To dynamically place the Submission ID or Unique ID use: {{SUB_ID}},{{UNIQUE_TOKEN}}','registrationmagic-gold'))));
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_CS_EMAIL_TO_ADMIN') . "</b>", "cs_email_admin_en", array(1 => ""), array("onchange"=>"show_child(this,'rm_cs_email_admin')","class" => "rm-has-child rm-static-field rm_input_type", "value" => isset($data->custom_status['cs_email_admin_en']) ? absint($data->custom_status['cs_email_admin_en']) :0, "longdesc" => RM_UI_Strings::get('HELP_CS_EMAIL_ADMIN_EN'))));
        $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_email_admin" style="display:none" >'));
            $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_SUBJECT') . "</b>", "cs_email_admin_subject", array("value" => isset($data->custom_status['cs_email_admin_subject'])?$data->custom_status['cs_email_admin_subject']:'', "longDesc" => RM_UI_Strings::get('HELP_CS_ADMIN_EMAIL_SUBJECT'))));
            $form->addElement(new Element_Textarea("<b>" . RM_UI_Strings::get('LABEL_BODY') . "</b>", "cs_email_admin_body", array("value" => isset($data->custom_status['cs_email_admin_body'])?$data->custom_status['cs_email_admin_body']:'', "longDesc" => __('Contents of the email to be sent to the admin. To dynamically place the Submission ID or Unique ID use: {{SUB_ID}},{{UNIQUE_TOKEN}}','registrationmagic-gold'))));
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_CS_USER_ACTIONS_EN') . "</b>", "cs_action_user_act_en", array(1 => ""), array("onchange"=>"show_child(this,'rm_cs_user_actions')","class" => "rm-has-child rm-static-field rm_input_type", "value" => isset($data->custom_status['cs_action_user_act_en']) ? absint($data->custom_status['cs_action_user_act_en']) : 0, "longdesc" => RM_UI_Strings::get('HELP_CS_USER_ACTION'))));
        $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_user_actions" style="display:none" >'));
            $form->addElement(new Element_Radio(RM_UI_Strings::get('LABEL_CS_USER_ACTIONS').":", "cs_action_user_act", array('create_account'=>'Create User Account','deactivate_user'=>'Deactivate User Account','activate_user'=>'Activate User Account','delete_user'=>'Delete User Account'), array("value" => isset($data->custom_status['cs_action_user_act']) ? $data->custom_status['cs_action_user_act'] : '', "longDesc"=>'')));
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_ATTACH_NOTE') . "</b>", "cs_note_en", array(1 => ""), array("onchange"=>"show_child(this,'rm_cs_attach_note')","class" => "rm-has-child rm-static-field rm_input_type", "value" => isset($data->custom_status['cs_note_en']) ? absint($data->custom_status['cs_note_en']) : 0, "longdesc" => RM_UI_Strings::get('HELP_ATTACH_NOTE'))));
        $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_attach_note" style="display:none" >'));
            $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_NOTE_PUBLIC') . "</b>", "cs_note_public", array(1 => ""), array("class" => "rm-has-child rm-static-field rm_input_type", "value" => isset($data->custom_status['cs_note_public']) ? absint($data->custom_status['cs_note_public']) :0, "longdesc" => RM_UI_Strings::get('HELP_LABEL_NOTE_PUBLIC'))));
            $form->addElement(new Element_Textarea("<b>" . RM_UI_Strings::get('LABEL_NOTE_TEXT') . "</b>", "cs_note_text", array("value" => isset($data->custom_status['cs_note_text'])?$data->custom_status['cs_note_text']:'', "longDesc" => RM_UI_Strings::get('HELP_NOTE_TEXT'))));
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_BLACKLIST') . "</b>", "cs_blacklist_en", array(1 => ""), array("onchange"=>"show_child(this,'rm_cs_blacklist')","class" => "rm-has-child rm-static-field rm_input_type", "value" => isset($data->custom_status['cs_blacklist_en']) ? absint($data->custom_status['cs_blacklist_en']) : 0, "longdesc" => RM_UI_Strings::get('HELP_BLACKLIST'))));
        $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_blacklist" style="display:none" >'));
            $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_BLOCK_EMAIL') . "</b>", "cs_block_email",array(1 => ""), array("value" => isset($data->custom_status['cs_block_email'])? absint($data->custom_status['cs_block_email']):0, "longDesc" => RM_UI_Strings::get('HELP_BLOCK_EMAIL'))));
            $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_BLOCK_IP') . "</b>", "cs_block_ip",array(1 => ""), array("value" => isset($data->custom_status['cs_block_ip'])?absint($data->custom_status['cs_block_ip']):0, "longDesc" => RM_UI_Strings::get('HELP_BLOCK_IP'))));
            $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_UNBLOCK_EMAIL') . "</b>", "cs_unblock_email",array(1 => ""), array("value" => isset($data->custom_status['cs_unblock_email'])? absint($data->custom_status['cs_unblock_email']):0, "longDesc" => RM_UI_Strings::get('HELP_UNBLOCK_EMAIL'))));
            $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_UNBLOCK_IP') . "</b>", "cs_unblock_ip",array(1 => ""), array("value" => isset($data->custom_status['cs_unblock_ip'])?absint($data->custom_status['cs_unblock_ip']):0, "longDesc" => RM_UI_Strings::get('HELP_UNBLOCK_IP'))));
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page=rm_form_manage_cstatus&rm_form_id='.$data->form_id, array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit", "onClick" => "jQuery.prevent_field_add(event,'".__('This is a required field.','registrationmagic-gold')."')")));
        $form->render();
        ?>  
    </div>
</div>
<script>
    function show_child(obj,container){
        var containerEl= jQuery('#' + container);

        if(containerEl.length>0){
            if(jQuery(obj).is(':checked'))
                containerEl.slideDown();
            else
                containerEl.slideUp();
        }
    }
    
    function show_other(obj,container){
        var containerEl= jQuery('#' + container);

        if(containerEl.length>0){
            if(jQuery(obj).val()=='clear_specific')
                containerEl.slideDown();
            else
                containerEl.slideUp();
        }
    }
    
    jQuery(document).ready(function(){
       jQuery('.rm-has-child').trigger('change'); 
    });
</script>    
    