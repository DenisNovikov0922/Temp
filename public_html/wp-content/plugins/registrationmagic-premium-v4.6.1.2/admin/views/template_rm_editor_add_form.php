<?php
if (!defined('WPINC')) {
    die('Closed');
}

$option_string='<option value="__0" class="rm_login_form_option_in_drop_down">'.__('Login Form', 'registrationmagic-gold').'</option>';
if($data->forms){
    foreach($data->forms as $form){
        $option_string .= '<option value="'.$form->form_id.'">'.$form->form_name.'</option>';
    }
}
?>
<?php if( current_user_can('administrator') ) {  ?>
<select id="rm_editor_add_form">
    <option value="0"><?php echo RM_UI_Strings::get("LABEL_ADD_FORM"); ?></option>
    <?php echo $option_string; ?>
</select>
<?php } ?>

<?php
