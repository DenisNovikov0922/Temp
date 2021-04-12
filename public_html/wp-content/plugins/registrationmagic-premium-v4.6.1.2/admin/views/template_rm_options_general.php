<?php
if (!defined('WPINC')) {
    die('Closed');
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$image_dir = plugin_dir_url(dirname(dirname(__FILE__))) . "images";

$layout_checked_state = array('label_left' => null, 'label_top' => null, 'two_columns' => null);

    if ($data['form_layout'] == 'two_columns')
        $layout_checked_state['two_columns'] = 'checked';
    else if ($data['form_layout'] == 'label_top')
        $layout_checked_state['label_top'] = 'checked';
    else
        $layout_checked_state['label_left'] = 'checked';


$layout_radio_button_html_string = '<div class="rmrow"><div class="rmfield" for="layout_radio"><label>' .
        RM_UI_Strings::get('LABEL_LAYOUT') .
        '</label></div><div class="rminput"><ul class="rmradio">' .
        '<li><div id="layout_left_container"><div class="rmlayoutimage"><img src="' . $image_dir . '/label-left.png" /></div><input id="layout_radio-1" type="radio" name="form_layout" value="label_left" ' . $layout_checked_state['label_left'] . '>' .
        RM_UI_Strings::get('LABEL_LAYOUT_LABEL_LEFT') .
        '</div></li><li><div id="layout_top_container"><div class="rmlayoutimage"><img src="' . $image_dir . '/label-top.png" /></div><input id="layout_radio-2" type="radio" name="form_layout" value="label_top" ' . $layout_checked_state['label_top'] . '>' .
        RM_UI_Strings::get('LABEL_LAYOUT_LABEL_TOP') .
        '</div></li><li><div id="layout_two_columns_container"><div class="rmlayoutimage"><img src="' . $image_dir . '/two-column.png" /></div><input id="layout_radio-3" type="radio" name="form_layout" value="two_columns" ' . $layout_checked_state['two_columns'] . '>' .
        RM_UI_Strings::get('LABEL_LAYOUT_TWO_COLUMNS') .
        '</div></li></ul></div><div class="rmnote"><div class="rmprenote"></div><div class="rmnotecontent">' .
        RM_UI_Strings::get('HELP_OPTIONS_GEN_LAYOUT') .
        '</div></div></div>';


//echo $layout_radio_button_html_string;
?>


<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent">


        <?php
        $pages = get_pages();
        $wp_pages = RM_Utilities::wp_pages_dropdown();

        $form = new RM_PFBC_Form("options_general");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => "",
            "enctype" => "multipart/form-data"
        ));

        $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get('GLOBAL_SETTINGS_GENERAL') . '</div>'));
        $form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_FORM_STYLE'), "theme", array("classic" => __("Classic",'registrationmagic-gold'), "matchmytheme" => __("Match my theme",'registrationmagic-gold')), array("value" => $data['theme'], "id" => "theme_dropdown", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_THEME'))));
        $form->addElement(new Element_HTML(wp_nonce_field('rm_options_general')));
        $form->addElement(new Element_HTML($layout_radio_button_html_string));

        //Disabled for now, IP capture is needed to limit submissions from a given IP address. Unnecessary setting.
        //$form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_CAPTURE_INFO'), "user_ip", array("yes" => ''), $data['user_ip'] == 'yes' ? array("value" => "yes") : array()));

        $form->addElement(new Element_Textarea(RM_UI_Strings::get('LABEL_ALLOWED_FILE_TYPES'), "allowed_file_types", array("value" => $data['allowed_file_types'], "longDesc" => RM_UI_Strings::get('ALLOWED_FILE_TYPES_HELP'), "validation" => new Validation_RegExp("/[a-zA-Z0-9| ]*/", RM_UI_Strings::get('MSG_INVALID_CHAR')), "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_FILETYPES'))));
        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_ALLOWED_MULTI_FILES'), "allow_multiple_file_uploads", array("yes" => ''), $data['allow_multiple_file_uploads'] == 'yes' ? array("value" => "yes", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_FILE_MULTIPLE')) : array("longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_FILE_MULTIPLE'))));

        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_HIDE_TOOLBAR'), "hide_toolbar", array("yes" => ''), $data['hide_toolbar'] == 'yes' ? array("value" => "yes", "longDesc" => RM_UI_Strings::get('HELP_HIDE_TOOLBAR')) : array("longDesc" => RM_UI_Strings::get('HELP_HIDE_TOOLBAR'))));
        
        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_SHOW_PROG_BAR'), "display_progress_bar", array("yes" => ''), $data['display_progress_bar'] == 'yes' ? array("value" => "yes", "longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_PROGRESS_BAR')) : array("longDesc" => RM_UI_Strings::get('HELP_OPTIONS_GEN_PROGRESS_BAR'))));
        $pli_rdr_opts = array();
        
        ob_start();
        ?>

        <?php
        $pli_redir_select = ob_get_clean();
        $form->addElement(new Element_HTML($pli_redir_select));

        // Special image upload field for pdf logo, with removal and existing image view.
        $pdf_logo_url = wp_get_attachment_image_src($data['sub_pdf_header_img']);
        if(is_array($pdf_logo_url) && count($pdf_logo_url)>0)
            $pdf_logo_url = $pdf_logo_url[0];
        else
            $pdf_logo_url = null;
        ob_start();
        ?>
        <div class="rmrow">
            <div class="rmfield" for="options_general-element-8">
                    <label><?php echo RM_UI_Strings::get('LABEL_SUB_PDF_HEADER_IMG'); ?></label>
            </div>
            <div class="rminput">
                <div style="display: table;">
                    <?php if($pdf_logo_url): ?>
                    <div class="rm_img_pick_placeholder" style="position: relative; margin-right: 10px; display: table-cell; padding-bottom: 0; height: auto;">
                                <img id="" style="width: 36px;height: 36px;object-fit: cover;" src="<?php echo $pdf_logo_url; ?>">
                                <span style="position: absolute;left: 48px;color:#ff6c6c;text-align: left;line-height: 15px;top: -4px;cursor: pointer;" onclick="rm_remove_pdf_logo(this)"><?php echo RM_UI_Strings::get('LABEL_REMOVE'); ?></span>
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="rm_pdf_logo_removal" value="false">
                    <input type="file" name="sub_pdf_header_img" accept="image/*" style="    display: table-cell; vertical-align: bottom; padding: 0px; height: auto; float: left; margin: 0; margin-left: 10px;">
                </div>
            </div>
            <div class="rmnote">
                <div class="rmprenote"></div>
                <div class="rmnotecontent"><?php echo RM_UI_Strings::get('SUB_PDF_HEADER_IMG_HELP'); ?></div>
            </div>
        </div>
                
        <?php
        $form->addElement(new Element_HTML(ob_get_clean()));
        ////// End
        
        //$form->addElement(new Element_FileNative(RM_UI_Strings::get('LABEL_SUB_PDF_HEADER_IMG'), "sub_pdf_header_img", array("value"=>  wp_get_attachment_image_src($data['sub_pdf_header_img']),"accept"=>"image/*", "longDesc" => RM_UI_Strings::get('SUB_PDF_HEADER_IMG_HELP'))));
        $form->addElement(new Element_Textarea(RM_UI_Strings::get('LABEL_SUB_PDF_HEADER_TEXT'), "sub_pdf_header_text", array("value" => $data['sub_pdf_header_text'], "longDesc" => RM_UI_Strings::get('SUB_PDF_HEADER_TEXT_HELP'))));

        //$form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_SHOW_FLOATING_ICON'), "display_floating_action_btn", array("yes" => ''), $data['display_floating_action_btn'] == 'yes' ? array("value" => "yes", "longDesc" => RM_UI_Strings::get('HELP_SHOW_FLOATING_ICON')) : array("longDesc" => RM_UI_Strings::get('HELP_SHOW_FLOATING_ICON'))));
        $submission_type=array(
            "all"=>__("All",'registrationmagic-gold'),
            "today"=>__("Today",'registrationmagic-gold'),
            "week"=>__("This week",'registrationmagic-gold'),
            "month"=>__("This month",'registrationmagic-gold'),
            "year"=>__("This year",'registrationmagic-gold'),
            "read"=>__("Read",'registrationmagic-gold'),
            "unread"=>__("Unread",'registrationmagic-gold')
            
        );
        
        $pdf_fonts= array('freeserif'=>__('FreeSerif','registrationmagic-gold'),
                          'courier'=>__('Courier','registrationmagic-gold'),'helvetica'=>__('Helvetica','registrationmagic-gold'),
                          'times'=>__('Times','registrationmagic-gold'));       
        $form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_SUBMISSION_ON_CARD'), "submission_on_card", $submission_type, array("value" => $data['submission_on_card'], "longDesc" => RM_UI_Strings::get('HELP_SUBMISSION_ON_CARD')))); 
        $form->addElement(new Element_Checkbox(RM_UI_Strings::get('LABEL_SHOW_ASTERIX'), "show_asterix", array("yes" => ''), $data['show_asterix'] == 'yes' ? array("value" => "yes", "longDesc" => RM_UI_Strings::get('HELP_SHOW_ASTERIX')) : array("longDesc" => RM_UI_Strings::get('HELP_SHOW_ASTERIX'))));
        $form->addElement(new Element_Select(RM_UI_Strings::get('LABEL_PDF_FONT'), "submission_pdf_font", $pdf_fonts, array("value" => $data['submission_pdf_font'], "longDesc" => RM_UI_Strings::get('HELP_SUBMISSION_PDF_FONT')))); 

        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-gold'), '?page=rm_options_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE')));
        $form->render();
        ?> 

    </div>
</div>
<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
    function rm_remove_pdf_logo(e){
        jQuery(e).parent('.rm_img_pick_placeholder').hide();
        jQuery(":input[name='rm_pdf_logo_removal']").val("true");
    }
    
    jQuery(document).ready(function(){
        rm_setup_pli_rdr_opts();
    });
    
    function rm_setup_pli_rdr_opts(){
        var selected_opt = jQuery("#rm_pli_rdr_select").val();
        if(selected_opt == "0" || selected_opt == "__dashboard") {
            jQuery("#rm_pli_rdr_opts").hide();
        } else {
            jQuery("#rm_pli_rdr_opts").show();
        }
    }
</script></pre>

<?php   
