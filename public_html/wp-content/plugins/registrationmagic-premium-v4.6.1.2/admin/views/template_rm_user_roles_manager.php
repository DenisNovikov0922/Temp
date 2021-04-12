<?php
if (!defined('WPINC')) {
    die('Closed');
}
//echo'<pre>';var_dump($data->custom_role_data);
?>

<div class="rmagic">

    <!-----Operations bar Starts----->
    <div class="operationsbar">
        <div class="rmtitle"><?php echo RM_UI_Strings::get("LABEL_USER_ROLES"); ?></div>
        <div class="icons">
            <a href="?page=rm_options_user"><img alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/rm-user-accounts.png'; ?>"></a>
        </div>
        <div class="nav">
            <ul>
                <li id="rm-delete-user-role" class="rm_deactivated" onclick="jQuery.rm_do_action('rm_user_role_mananger_form','rm_user_role_delete')"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get('LABEL_REMOVE'); ?></a></li>
            </ul>
        </div>

    </div>
    <!--------Operationsbar Ends----->

    <!----Field Selector Starts---->
    <div class="rm-field-selector rm-user-role-form">
        <?php
        $form = new RM_PFBC_Form("rm_user_role_add_form");
        $form->configure(array(
        "prevent" => array("bootstrap", "jQuery"),
        "action" => ""
        ));

        $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get("HEADING_ADD_ROLE_FORM") . '</div>'));
        $form->addElement(new Element_Hidden("rm_slug", "rm_user_role_add"));
        $form->addElement(new Element_HTML(wp_nonce_field('rm_user_role_manage')));
        $form->addElement(new Element_Hidden("rm_submitted", "true"));
        $form->addElement(new Element_Textbox("<b>".RM_UI_Strings::get('LABEL_ROLE_NAME')."</b>", "rm_role_name", array("id" => "rm_role_name","required"=>"1","longDesc" => RM_UI_Strings::get('HELP_ROLE_KEY'))));
        $form->addElement(new Element_Textbox("<b>".RM_UI_Strings::get('LABEL_ROLE_DISPLAY_NAME')."</b>", "rm_display_name", array("id" => "rm_display_name","required"=>"1","longDesc" => RM_UI_Strings::get('HELP_ROLE_NAME'))));
        $form->addElement(new Element_Select("<b>".RM_UI_Strings::get('LABEL_PERMISSION_LEVEL')."</b>", "rm_user_capability", array_merge($data->roles->default,$data->roles->custom),array("longDesc" => RM_UI_Strings::get('HELP_ROLE_PERMISSION'))));
                 
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_IS_PAID_ROLE') . "</b>", "rm_role_is_paid", array(1 => ""), array('id'=>'rm_is_paid_cb', "value" => null, "onclick" => "handle_paid_role_fields(this)","longDesc" => RM_UI_Strings::get('HELP_IS_PAID_ROLE'))));
        $form->addElement(new Element_HTML("<div id='rm_role_price_container' class='childfieldsrow' style='display:none;border: 1px solid transparent;'>"));
        $form->addElement(new Element_Number("<b>" . RM_UI_Strings::get('LABEL_ROLE_PRICE') . "</b>", "rm_role_amt", array('id'=>'rm_role_price', "min" => "0","step"=> "0.01", "longDesc" => RM_UI_Strings::get('HELP_ROLE_PRICE'))));
        $form->addElement(new Element_HTML("</div>"));
        
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit", "onClick" => "prevent_save(event)")));

        $form->render();

        ?>
    </div>
    
    <ul class="rm-field-container rm-user-role-manager" id="rm_nonsortable_list">
        <?php
        $gopts = new RM_Options;
        $linked_forms=$data->roles->linked_forms;
        if (is_array($data->roles->default) || is_object($data->roles->default))
        {
            foreach ($data->roles->default as $role => $role_name)
            {
                if(isset($linked_forms[$role]))
                $linked_form=$linked_forms[$role];
                else
                $linked_form=null;
                $default_forms=array();
                $opt_default_forms=$gopts->get_value_of('rm_option_default_forms');
                $default_forms= maybe_unserialize($opt_default_forms);
                $role_form=isset($default_forms[$role])?$default_forms[$role]:null;
                if(isset($role_form) && $role_form != ''){
                $forms_options=new RM_Forms;
                $forms_options->load_from_db($role_form);
                $role_form_name=$forms_options->get_form_name();
                }
                else
                    $role_form_name=null;
                $role_without_spaces=str_replace (" ", "", $role);
                $role_div_id = "default_form_".$role_without_spaces;
                $role_select_id = "select_".$role_without_spaces;
                $role_label_id = "label_".$role_without_spaces;
                $role_change_id = "change_".$role_without_spaces;
                $role_add_id = "add_".$role_without_spaces;
                ?>
                <li id="<?php echo $role;?>">
                    <div class="rm-slab">
                        <div class="rm-slab-grabber">
                            <span class="rm_sortable_handle rm_handle">
                                <img alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/user_role.png'; ?>">
                            </span>
                        </div>
                        <div class="rm-slab-content">
                            <input type="checkbox" name="rm_role[]" value="<?php echo $role; ?>" id="checkbox_<?php echo $role; ?>" disabled>
                            <span><?php echo $role; ?></span>
                            <span><?php echo $role_name; ?></span>
                            <div id="<?php echo $role_div_id; ?>" style="display: inline-block;">
                                <?php  if(isset($role_form)  && $role_form != ''){?>
                            <span id="<?php echo $role_label_id; ?>"><?php echo $role_form_name; ?></span><a style="cursor: pointer;" id="<?php echo $role_change_id; ?>" onclick="show_default_forms_dropdown(this,'<?php echo $role; ?>')"><?php echo RM_UI_Strings::get("LABEL_CHANGE_DEFAULT_FORM"); ?></a>
                            <a id="<?php echo $role_add_id; ?>" style="cursor: pointer;Display:none" onclick="show_default_forms_dropdown(this,'<?php echo $role; ?>')"><?php echo RM_UI_Strings::get("LABEL_ADD_DEFAULT_FORM"); ?></a>
                            <?php }else{
                            ?><span id="<?php echo $role_label_id; ?>"><?php echo RM_UI_Strings::get("LABEL_NO_DEFAULT_FORM"); ?></span>
                            <a style="cursor: pointer; Display:none;" id="<?php echo $role_change_id; ?>" onclick="show_default_forms_dropdown(this,'<?php echo $role; ?>')"><?php echo RM_UI_Strings::get("LABEL_CHANGE_DEFAULT_FORM"); ?></a>
                            <a style="cursor: pointer;" id="<?php echo $role_add_id; ?>" onclick="show_default_forms_dropdown(this,'<?php echo $role; ?>')"><?php echo RM_UI_Strings::get("LABEL_ADD_DEFAULT_FORM"); ?></a>
                                <?php }
                                ?>
                            
                            <select class="rm-user-role-form-selectbox" style="Display:none" id="<?php echo $role_select_id; ?>" onchange="save_default_form(this,'<?php echo $role; ?>')">
                                  <option value=""><?php _e('None', 'registrationmagic-gold'); ?></option>
                                 <?php
                                 if(isset($linked_form)){
                                 foreach($linked_form as $l_form_id => $lform_name)
                                 {
                                     if(isset($role_change_id) && $role_form == $l_form_id)
                                     echo "<option value='".$l_form_id."' selected>".$lform_name."</option>";
                                     else
                                     echo "<option value='".$l_form_id."'>".$lform_name."</option>";
                                 }
                                 }
                                 ?>
                             </select>    
                            </div>
                            
                        </div>
                        <div class="rm-slab-buttons">
                            <a href="javascript:void(0)" class="rmdisabled"><?php echo RM_UI_Strings::get("LABEL_DELETE"); ?></a>
                        </div>
                    </div>
                </li>

                <?php
            }
        }
        ?>

    <!----Slab View---->
<form method="post" id="rm_user_role_mananger_form">
    <?php wp_nonce_field('rm_user_role_manage');?>
        <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
        <?php
        if (is_array($data->roles->custom) || is_object($data->roles->custom))
        {   $gopts = new RM_Options;
              
            foreach ($data->roles->custom as $role => $role_name)
            {
                 if(isset($linked_forms[$role]))
                $linked_form=$linked_forms[$role];
                else
                $linked_form=null;
                $default_forms=array();
                $opt_default_forms=$gopts->get_value_of('rm_option_default_forms');
                $default_forms= maybe_unserialize($opt_default_forms);
                $role_form=isset($default_forms[$role])?$default_forms[$role]:null;
                if(isset($role_form)  && $role_form != ''){
                $forms_options=new RM_Forms;
                $forms_options->load_from_db($role_form);
                $role_form_name=$forms_options->get_form_name();
                }
                else
                    $role_form_name=null;
                $role_without_spaces=str_replace (" ", "", $role);
                $role_div_id = "default_form_".$role_without_spaces;
                $role_select_id = "select_".$role_without_spaces;
                $role_label_id = "label_".$role_without_spaces;
                $role_change_id = "change_".$role_without_spaces;
                $role_add_id = "add_".$role_without_spaces;
                
                if(isset($data->custom_role_data[$role]) && $data->custom_role_data[$role]->is_paid)
                   $paid_role_str = ' ('.$gopts->get_formatted_amount($data->custom_role_data[$role]->amount).')';
                else
                    $paid_role_str = '';
                ?>
                <li id="<?php echo $role;?>">
                    <div class="rm-slab">
                        <div class="rm-slab-grabber">
                            <span class="rm_sortable_handle rm_handle">
                                <img alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/user_role.png'; ?>">
                            </span>
                        </div>
                        <div class="rm-slab-content">
                            <input type="checkbox" name="rm_roles[]" onclick="rm_on_user_role_deletion()" value="<?php echo $role; ?>" id="checkbox_<?php echo $role_without_spaces; ?>">
                            <span><?php echo $role.$paid_role_str; ?></span>
                            <span><?php echo $role_name; ?></span>
                        <div id="<?php echo $role_div_id; ?>" style="display: inline-block;">
                                <?php  if(isset($role_form)  && $role_form != ''){?>
                            <span id="<?php echo $role_label_id; ?>"><?php echo $role_form_name; ?></span><a style="cursor: pointer;" id="<?php echo $role_change_id; ?>" onclick="show_default_forms_dropdown(this,'<?php echo $role_without_spaces; ?>')"><?php echo RM_UI_Strings::get("LABEL_CHANGE_DEFAULT_FORM"); ?></a>
                            <a id="<?php echo $role_add_id; ?>" style="cursor: pointer;Display:none" onclick="show_default_forms_dropdown(this,'<?php echo $role_without_spaces; ?>')"><?php echo RM_UI_Strings::get("LABEL_ADD_DEFAULT_FORM"); ?></a>
                            <?php }else{
                            ?><span id="<?php echo $role_label_id; ?>"><?php echo RM_UI_Strings::get("LABEL_NO_DEFAULT_FORM"); ?></span>
                            <a style="cursor: pointer; Display:none;" id="<?php echo $role_change_id; ?>" onclick="show_default_forms_dropdown(this,'<?php echo $role_without_spaces; ?>')"><?php echo RM_UI_Strings::get("LABEL_CHANGE_DEFAULT_FORM"); ?></a>
                            <a style="cursor: pointer;" id="<?php echo $role_add_id; ?>" onclick="show_default_forms_dropdown(this,'<?php echo $role_without_spaces; ?>')"><?php echo RM_UI_Strings::get("LABEL_ADD_DEFAULT_FORM"); ?></a>
                                <?php }
                                ?>
                            
                            <select class ="rm-user-role-form-selectbox" style="Display:none" id="<?php echo $role_select_id; ?>" onchange="save_default_form(this,'<?php echo $role_without_spaces; ?>')">
                                  <option value="">None</option>
                                 <?php
                                 if(isset($linked_form)){
                                 foreach($linked_form as $l_form_id => $lform_name)
                                 {
                                     if(isset($role_change_id) && $role_form == $l_form_id)
                                     echo "<option value='".$l_form_id."' selected>".$lform_name."</option>";
                                     else
                                     echo "<option value='".$l_form_id."'>".$lform_name."</option>";
                                         
                                 }
                                 }
                                 ?>
                             </select>    
                        </div>
                        </div>
                        <div class="rm-slab-buttons" onclick="delete_role(this,'checkbox_<?php echo $role_without_spaces; ?>')">
                            <a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_DELETE"); ?></a>
                        </div>
                    </div>
                </li>

                <?php
            }
        } 
        ?>
</form>
    </ul>
</div>

<pre class='rm-pre-wrapper-for-script-tags'><script>
    
function handle_paid_role_fields(cb)
{
    if(jQuery(cb).prop('checked') == true)
        jQuery('#rm_role_price_container').slideDown();
    else
        jQuery('#rm_role_price_container').slideUp();
}

function flash_element(x){
   x.each(function () {
                jQuery(this).css("border", "1px solid #FF6C6C");        
                jQuery(this).fadeIn(100).fadeOut(1000, function () {
                    jQuery(this).css("border", "");
                    jQuery(this).fadeIn(100);
                    jQuery(this).val('');
                });
            });
                        
}

function prevent_save(event){
    jqrollname = jQuery('#rm_role_name');
    if(jqrollname.val().toString().trim() === '')
    {
        flash_element(jqrollname);
        event.preventDefault();
    }
    
    jqrolldispname = jQuery('#rm_display_name');
    if(jqrolldispname.val().toString().trim() === '')
    {
        flash_element(jqrolldispname);
        event.preventDefault();
    }
    
    
    jqamt = jQuery('#rm_role_price');
    jqamtval = jqamt.val().toString().trim();
    if(jQuery('#rm_is_paid_cb-0').prop('checked') == true)
    {
        var amt = parseFloat(jqamtval);
        if((jqamtval === '' || amt == 0 || amt == NaN))
        {
            flash_element(jqamt);
            event.preventDefault();
        }
    }       

}

function show_default_forms_dropdown(element,role)
{
    jQuery(element).hide();
    jQuery("#label_"+role).hide();
    jQuery("#select_"+role).show();
}

function rm_on_user_role_deletion()
{
        var selected_user_roles = jQuery("input[name='rm_roles[]']:checked");
        if(selected_user_roles.length > 0) {   
             jQuery("#rm-delete-user-role").removeClass("rm_deactivated"); 
        }else
        {
               jQuery("#rm-delete-user-role").addClass("rm_deactivated"); 
        }
        
    }
    
</script></pre>

