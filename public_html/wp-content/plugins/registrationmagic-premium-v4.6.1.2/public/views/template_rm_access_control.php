<?php
if (!defined('WPINC')) {
    die('Closed');
}

/**
 * @internal Plugin Template File [For user forms]
 * 
 * This file renders the user made custom forms of the plugin on the front end
 * using the shortcode. * 
 */

RM_Utilities::enqueue_external_scripts('rm_date_sanitizer', RM_BASE_URL.'public/js/rm_date_sanitizer.js');

?>

<div class="rmagic">
<?php if(!isset($data->no_form_tag) || !$data->no_form_tag): ?>
    <form name="rm_fac" id="rm-fac" action="" method="post">   
<?php endif; ?>
        <input type="hidden" name="rm_fac_di_<?php echo $data->form_id; ?>" value="awesome">
        <input type="hidden" name="RM_CLEAR_ERROR" value='true'>
        <?php 
            if($_POST && !empty($_REQUEST['submission_id'])){
                $submission_id= absint($_REQUEST['submission_id']);
        ?>
        <input type="hidden" name="submission_id" value='<?php echo $submission_id; ?>'>
        <input type="hidden" name="form_id" value='<?php echo absint($_POST['form_id']) ?>'>
        <input type="hidden" name="rm_slug" value='rm_user_form_edit_sub'>
        <?php } ?>
<?php if(isset($data->actrl->date)) { 
        
            if(isset($data->actrl->date->question) && trim($data->actrl->date->question))
                echo $data->actrl->date->question;
            else
                echo RM_UI_Strings::get('LABEL_ACTRL_DATE_QUESTION_DEF');
        
    ?>
        
        <div class="rm-fac-dob">
<!--            <input type="number" name="rm_fac_dday" min="1" max="31" required>-->
        <select name="rm_fac_dday" id="id_rm_fac_day_<?php echo $data->form_id; ?>" required>
        <?php for($i=1; $i<32; $i++)
            echo "<option value=".$i.">".$i."</option>";
        ?>
        </select>/
        <select name="rm_fac_dmonth" id="id_rm_fac_month_<?php echo $data->form_id; ?>" onchange="rm_sanitize_date_selector(<?php echo $data->form_id; ?>)" required>
            <option value="1"><?php  _e('January', 'registrationmagic-gold') ?></option>
            <option value="2"><?php  _e('February', 'registrationmagic-gold') ?></option>
            <option value="3"><?php  _e('March', 'registrationmagic-gold') ?></option>
            <option value="4"><?php  _e('April', 'registrationmagic-gold') ?></option>            
            <option value="5"><?php  _e('May', 'registrationmagic-gold') ?></option>
            <option value="6"><?php  _e('June', 'registrationmagic-gold') ?></option>
            <option value="7"><?php  _e('July', 'registrationmagic-gold') ?></option>
            <option value="8"><?php  _e('August', 'registrationmagic-gold') ?></option>            
            <option value="9"><?php  _e('September', 'registrationmagic-gold') ?></option>
            <option value="10"><?php  _e('October', 'registrationmagic-gold') ?></option>
            <option value="11"><?php  _e('November', 'registrationmagic-gold') ?></option>
            <option value="12"><?php  _e('December', 'registrationmagic-gold') ?></option>
        </select>/
        
        <input type="number" id="id_rm_fac_year_<?php echo $data->form_id; ?>" onchange="rm_sanitize_date_selector(<?php echo $data->form_id; ?>)" name="rm_fac_dyear" min="1800" max="2100" placeholder="<?php echo RM_UI_Strings::get('LABEL_STRIPE_CARD_YEAR'); ?>" required>
        </div>
<?php } 
      if(isset($data->actrl->passphrase)) { 
          
          if(isset($data->actrl->passphrase->question) && trim($data->actrl->passphrase->question))
                echo $data->actrl->passphrase->question;
            else
                echo RM_UI_Strings::get('LABEL_ACTRL_PASS_QUESTION_DEF');
        
    ?>
        <div class="rm-fac-pass"><input type="text" name="rm_fac_pass" required></div>
<?php } ?>
        <button type='submit'><?php echo RM_UI_Strings::get('LABEL_ACTRL_BUTTON_CONT'); ?></button>
<?php if(!isset($data->no_form_tag) || !$data->no_form_tag): ?>
    </form>   
<?php endif; ?>
    
</div>
