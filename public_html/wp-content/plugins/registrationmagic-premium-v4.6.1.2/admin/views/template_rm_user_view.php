<?php
if (!defined('WPINC')) {
    die('Closed');
}
//echo '<pre>';print_r($data);die;
?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="rmagic">

    <!-----Operationsbar Starts----->

    <div class="operationsbar">
        <div class="rmtitle"><?php echo $data->user->data->display_name; ?></div>
        <div class="icons">
        </div>
        <div class="nav">
            <ul>
                <li><a href="<?php echo get_admin_url() . 'user-edit.php?user_id=' . $data->user->ID; ?>"><?php echo RM_UI_Strings::get('LABEL_EDIT'); ?></a></li>
                <?php
                if ($data->curr_user != $data->user->ID) {
                    ?>
                    <li onclick="jQuery.rm_do_action('form_user_page_action', 'rm_user_delete')"><a href="#"><?php echo RM_UI_Strings::get('LABEL_DELETE'); ?></a></li>
                    <?php
                }
                ?>
                <?php
                if ($data->user->rm_user_status != 1) {
                    if ($data->curr_user != $data->user->ID) {
                        ?>
                        <li onclick="jQuery.rm_do_action('form_user_page_action', 'rm_user_deactivate')"><a href="#"><?php echo RM_UI_Strings::get('DEACTIVATE'); ?></a></li>
                        <?php
                    }
                } else {
                    ?>
                    <li onclick="jQuery.rm_do_action('form_user_page_action', 'rm_user_activate')"><a href="#"><?php echo RM_UI_Strings::get('ACTIVATE'); ?></a></li>
                    <?php
                }
                ?>
                    <li onclick="rm_show_send_mail_popup()"><a href="#"><?php echo RM_UI_Strings::get('SEND_MAIL'); ?></a></li>
            </ul>
        </div>

    </div>
    <!--------Operationsbar Ends----->

    <!----User Area Starts---->

    <div class="rm-user-area">

        <div class="rm-user-info">
            <div class="rm-profile-image">
                <?php echo get_avatar($data->user->ID, 250); ?>
            </div>
            
            <?php echo apply_filters('rm_filter_user_view_before_profile_fields','',$data->user->ID); ?>
            
            <div class="rm-profile-fields">

                <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_FNAME'); ?></div>
                    <div class="rm-field-value"><?php echo $data->user->first_name; ?></div>
                </div>

                <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_LNAME'); ?></div>
                    <div class="rm-field-value"><?php echo $data->user->last_name; ?></div>
                </div>

                <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('LABEL_EMAIL'); ?></div>
                    <div class="rm-field-value"><?php echo $data->user->user_email; ?></div>
                </div>

                <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('LABEL_ROLE'); ?></div>
                    <?php
                    foreach ($data->user->roles as $role) {
                        $user_roles = RM_Utilities::user_role_dropdown();
                        ?>
                        <div class="rm-field-value"><?php echo $user_roles[$role]; ?></div>
                        <?php
                    }
                    ?>
                </div>

               <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('LABEL_BIO'); ?></div>
                    <div class="rm-field-value"><?php echo $data->user->description; ?></div>
                </div>
                <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('LABEL_SECEMAIL'); ?></div>
                    <div class="rm-field-value"><?php echo $data->user->sec_email; ?></div>
                </div>
                 <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_NICKNAME'); ?></div>
                    <div class="rm-field-value"><?php echo $data->user->nickname; ?></div>
                </div>
                 <div class="rm-profile-field-row">

                    <div class="rm-field-label"><?php echo RM_UI_Strings::get('FIELD_TYPE_WEBSITE'); ?></div>
                    <div class="rm-field-value"><?php echo $data->user->user_url; ?></div>
                </div>

            </div>

        </div>
        <div class="rm_tabbing_container">

            <ul class="rm-profile-nav rm-profile-nav-ex">
                <?php foreach ($data->tab_titles as $tab_id => $tab): ?>
                <li class="rm-profile-nav-item"><a href="#<?php echo $tab_id; ?>"><?php echo $tab->icon; ?></a></li>
                <?php endforeach; ?>
                <li class="rm-profile-nav-item"><a href="#rmloginlog"><i class="rm-user-view-tab-icons fa fa-sign-in"></i></a></li>
            </ul>
            
            <div class="rm-user-content">
            
            <?php foreach ($data->tab_contents as $tab_id => $tab_content){                     
                    switch($tab_id){
                    case 'rmfirsttabcontent': ?>
                        <div class="rm-profile-fields" id="rmfirsttabcontent">

                            <?php
                            if (is_array($data->custom_fields) || is_object($data->custom_fields))
                                foreach ($data->custom_fields as $field_id => $sub) {
                                    $key = $sub->label;
                                    $meta = $sub->value;
                                    if(!isset($sub->type)){
                                        $sub->type = '';
                                    }
                                    $type = $sub->type;
                                    $meta = RM_Utilities::strip_slash_array(maybe_unserialize($meta));
                                    ?>
                            <div class="rm-profile-field-row">

                                <div class="rm-field-label"><?php echo $key; ?></div>
                                <div class="rm-field-value">
                                    <?php
                                    
                                    if (is_array($meta) || is_object($meta)) {
                                        if (isset($meta['rm_field_type']) && $meta['rm_field_type'] == 'File') {
                                            unset($meta['rm_field_type']);

                                            foreach ($meta as $sub) {

                                                $att_path = get_attached_file($sub);
                                                $att_url = wp_get_attachment_url($sub);
                                                ?>
                                                <div class="rm-submission-attachment">
                                                    <?php echo wp_get_attachment_link($sub, 'thumbnail', false, true, false); ?>
                                                    <div class="rm-submission-attachment-field"><?php echo basename($att_path); ?></div>
                                                    <div class="rm-submission-attachment-field"><a href="<?php echo $att_url; ?>"><?php echo RM_UI_Strings::get('LABEL_DOWNLOAD'); ?></a></div>
                                                </div>

                                                <?php
                                            }
                                        } elseif (isset($meta['rm_field_type']) && $meta['rm_field_type'] == 'Address') {
                                            $sub = $meta['original'] . '<br/>';
                                            if (count($meta) === 8) {
                                                $sub .= '<b>'.__('Street Address','registrationmagic-gold').'</b> : ' . $meta['st_number'] . ', ' . $meta['st_route'] . '<br/>';
                                                $sub .= '<b>'.__('City','registrationmagic-gold').'</b> : ' . $meta['city'] . '<br/>';
                                                $sub .= '<b>'.__('State','registrationmagic-gold').'</b> : ' . $meta['state'] . '<br/>';
                                                $sub .= '<b>'.__('Zip Code','registrationmagic-gold').'</b> : ' . $meta['zip'] . '<br/>';
                                                $sub .= '<b>'.__('Country','registrationmagic-gold').'</b> : ' . $meta['country'];
                                            }
                                                echo $sub;
                                        } elseif ($sub->type == 'Time') {                                  
                                    echo $meta['time'].", Timezone: ".$meta['timezone'];
                                } elseif ($sub->type == 'Checkbox') {   
                                    echo implode(', ',RM_Utilities::get_lable_for_option($field_id, $meta));
                                }else {
                                            $sub = implode(', ', $meta);
                                            echo $sub;
                                        }
                                    } else {
                                        if($type=='Rating')
                                        {
                                            $r_sub = array('value' => $sub->value,
                                                   'readonly' => 1,
                                                   'max_stars' => 5,
                                                   'star_face' => 'star',
                                                   'star_color' => 'FBC326');
                                            if(isset($sub->meta) && is_object($sub->meta)) {
                                                if(isset($sub->meta->max_stars))
                                                    $r_sub['max_stars'] = $sub->meta->max_stars;
                                                if(isset($sub->meta->star_face))
                                                    $r_sub['star_face'] = $sub->meta->star_face;
                                                if(isset($sub->meta->star_color))
                                                    $r_sub['star_color'] = $sub->meta->star_color;
                                            }
                                            $rf = new Element_Rating("", "", $r_sub);
                                            $rf->render();                                                 
                                        }
                                        elseif ($sub->type == 'Radio' || $sub->type == 'Select') {   
                                            echo RM_Utilities::get_lable_for_option($field_id, $meta);
                                        } 
                                        else
                                        echo $meta;
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        } else
                        echo "<div class='rmnotice'>" . RM_UI_Strings::get('MSG_NO_CUSTOM_FIELDS') . "</div>";
                        ?>

                    </div>
                <?php  break;
                    
                case 'rmsecondtabcontent': ?>
                    <table class="user-content" id="rmsecondtabcontent">
                    <?php
                    if (count($data->submissions) !== 0) {
                        ?>

                        <th>#</th> <th><?php echo RM_UI_Strings::get('LABEL_FORM'); ?></th> <th><?php echo RM_UI_Strings::get('LABEL_DATE'); ?></th> <th>&nbsp;</th> <th>&nbsp;</th>

                        <?php
                        $i = 0;
                        foreach ($data->submissions as $sub) {
                            $form_name = ($sub->form_name) ? : 'FORM DELETED'
                            ?>
                        <tr> <td><?php echo $i++; ?></td><td><?php echo $form_name; ?></td><td><?php echo $sub->submitted_on; ?></td><td><a href="?page=rm_submission_view&rm_submission_id=<?php echo $sub->submission_id; ?>&rm_form_id=<?php echo $sub->form_id; ?>"><i class="material-icons">link</i></a></td>
                                                <td><a href="<?php echo admin_url('admin-ajax.php?rm_submission_id='.$sub->submission_id.'&action=rm_print_pdf'); ?>"><i class="material-icons">picture_as_pdf</i></a></td>
                                        </tr>
                            <?php
                        }
                    } else {
                        echo "<tr> <td class='rmnotice'>" . RM_UI_Strings::get('MSG_NO_SUBMISSIONS_USER') . "</td></tr>";
                    }
                    ?>

                    </table>               
                <?php  break;
                    
                case 'rmthirdtabcontent': ?>
                    <table class="user-content" id="rmthirdtabcontent">
                    <?php
                    if (count($data->payments) != 0) {
                        ?>
                        <th>#</th> <th><?php echo RM_UI_Strings::get('LABEL_FORM'); ?></th> <th><?php echo RM_UI_Strings::get('LABEL_DATE'); ?></th> <th><?php echo RM_UI_Strings::get('LABEL_PAYMENT'); ?></th> <th>&nbsp;</th>
                        <?php
                        $i = 0;
                        foreach ($data->payments as $payment) {
                            ?>
                            <tr> <td><?php echo $i++; ?></td><td><?php echo $payment['form_name']; ?></td><td><?php echo $payment['payment']->posted_date; ?></td><td><?php echo $payment['payment']->status; ?></td><td><a href="?page=rm_submission_view&rm_submission_id=<?php echo $payment['submission_id']; ?>&rm_form_id=<?php echo $payment['form_id']; ?>"><img class="icon" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/view_form.png'; ?>"></a></td></tr>
                            <?php
                        }
                    } else
                        echo "<tr> <td class='rmnotice'>" . RM_UI_Strings::get('MSG_NO_PAYMENTS_USER') . "</td></tr>";
                    ?>
                </table>
                
                <?php  break;
                    
                case 'rmfourthtabcontent': ?>                
                    <table class="user-content" id="rmfourthtabcontent">
                    <?php
                    if (is_array($data->sent_emails) && count($data->sent_emails) !== 0) {
                        ?>

                        <th><?php echo RM_UI_Strings::get('LABEL_EMAIL_SUB'); ?></th> <th><?php echo RM_UI_Strings::get('LABEL_EMAIL_BODY'); ?></th><th><?php echo RM_UI_Strings::get('LABEL_EMAIL_SENT_ON'); ?></th> <th>&nbsp;</th>

                        <?php
                        $i = 1;
                        foreach ($data->sent_emails as $email) {
                            
                            ?>
                            <tr><td><?php echo strip_tags(htmlspecialchars_decode($email->sub)); ?></td><td><?php echo strip_tags(htmlspecialchars_decode($email->body)); ?></td><td><?php echo $email->sent_on; ?></td><td><a href="?page=rm_sent_emails_view&rm_sent_email_id=<?php echo $email->mail_id; ?>"><?php echo RM_UI_Strings::get("VIEW"); ?></a></td>                            
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr> <td class='rmnotice'>" . RM_UI_Strings::get('MSG_NO_SENT_EMAIL_USER') . "</td></tr>";
                    }
                    ?>

                </table>
                <?php  break;
                    
                    default: 
                        echo $tab_content;
                       break;
                    }
                } ?>
                
                <!-- Login-timeline Start -->
                
                <div class="rm-login-timeline rm-login-user-view">
                <table class="user-content rm-login-analytics" id="rmloginlog">
                        <tbody>
                            <?php
                            if(!empty($data->login_logs)){
                                if (is_array($data->login_logs) || is_object($data->login_logs)){
                                    foreach ($data->login_logs as $login_log){
                                        ?>
                                        <tr class="rm-login-result <?php echo ($login_log->status==1)?'rm-login-success':'rm-login-failed'; ?>">
                                            <td><div class="rm-login-user-time-log"><?php echo date('j M Y, h:i a', strtotime($login_log->time)); ?></div></td>
                                            <td>
                                                <div class="rm-login-user-browser">
                                                    <img src="<?php echo RM_IMG_URL. str_replace(' ', '_', strtolower($login_log->browser)).'.png'; ?>">
                                                </div>
                                            </td>
                                            <td><div class="rm-login-user-ip <?php echo ($login_log->ban==1)?'rm-login-boolean-result rm-login-false':''; ?>"><?php echo $login_log->ip ?><?php echo ($login_log->ban==1)?'<i class="fa fa-times"></i>':''; ?></div></td>
                                            <td><div class="rm-login-method rm-login-<?php echo strtolower($login_log->type) ?>"><?php echo $login_log->type ?></div></td>
                                            <?php
                                            if($login_log->status==1){
                                                $login_icon = '<i class="fa fa-unlock-alt"></i>';
                                                if(strtolower($login_log->type)=='otp'){
                                                    $login_icon = '<i class="fa fa-unlock-alt"></i>';
                                                }else if(strtolower($login_log->type)=='2fa' || strtolower($login_log->type)=='fa'){
                                                    $login_icon = '<i class="fa fa-unlock-alt"></i><i class="fa fa-unlock-alt"></i>';
                                                }else if(strtolower($login_log->type)=='social'){
                                                    $login_icon = '<i class="fa fa-'.$login_log->social_type.'"></i>';
                                                }
                                            }else{
                                                $login_icon = '<i class="fa fa-lock"></i>';
                                                if(strtolower($login_log->type)=='otp'){
                                                    $login_icon = '<i class="fa fa-lock"></i>';
                                                }else if(strtolower($login_log->type)=='2fa' || strtolower($login_log->type)=='fa'){
                                                    $login_icon = '<i class="fa fa-lock"></i><i class="fa fa-lock"></i>';
                                                }else if(strtolower($login_log->type)=='social'){
                                                    $login_icon = '<i class="fa fa-'.$login_log->social_type.'"></i>';
                                                }
                                            }
                                            ?>
                                            <td> <div class="rm-login-result-icon"><?php echo $login_icon; ?></div></td>
                                            <td><div class="rm-login-boolean-result <?php echo ($login_log->status==1)?'rm-login-true':'rm-login-false'; ?>"><i class="fa fa-<?php echo ($login_log->status==1)?'check':'times'; ?>"></i></div></td>
                                            <td> <div class="rm-login-user-sep"><i class="fa fa-circle"></i></div></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                            else
                            {
                                echo '<tr><td class="rmnotice">';
                                echo '<span class="rm-line-break">';
                                echo __('No login records available for this user.','registrationmagic-gold').'</br>';
                                echo '</span>';
                                printf(__('This area displays user specific login records with details. <a target="_blank" href="%s">More Info</a>','registrationmagic-gold'),'https://registrationmagic.com/wordpress-user-login-plugin-guide/#user-manager');
                                echo '</div></td></tr>';
                            }
                            ?>
                        </tbody>
                    </table> 
                </div>
                
                <!-- Login-timeline End -->

            </div>
        </div>
        
        <!-- Pop-up div to send email -->        
        <div id="rm_popup_send_email" style="display:none">
            <div class="rm_popup_send_email_row">
                    <span class="rm_popup_send_email"><?php _e('Subject', 'registrationmagic-gold'); ?></span> <input type="text" id="rm_popup_send_email_sub">
            </div>
            <div class="rm_popup_send_email_row">
                <span class="rm_popup_send_email"><?php _e('Message', 'registrationmagic-gold'); ?></span> <textarea id="rm_popup_send_email_body"></textarea>
            </div>
            <div class="rm_popup_send_email_row">
                  <button class="popup-submit rm-send_email-popup-close" type="button" id="rm_popup_cancel_email_button" onclick="jQuery('#rm_popup_send_email').hide()">← &nbsp;<?php _e('Cancel', 'registrationmagic-gold') ?></button>
                    <button class="popup-submit" type="button" id="rm_popup_send_email_button" onclick="rm_send_email('admin@gmail.com') "><?php _e('Send', 'registrationmagic-gold'); ?></button>
          
            </div>
        </div>
        <!-- Pop-up end -->        
        
    </div>
    
    <form id="form_user_page_action" method="post">
        <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
        <input type="hidden" name="rm_users[]" value="<?php echo $data->user->ID; ?>">
    </form>
    
</div>

<pre class='rm-pre-wrapper-for-script-tags'><script>
function rm_show_send_mail_popup()
{
    //Enable send button if disabled previously
    jQuery("#rm_popup_send_email_button").prop('disabled', false);
    jQuery("#rm_popup_cancel_email_button").prop('disabled', false);
    jQuery("#rm_popup_send_email_button").html("<?php _e('Send','registrationmagic-gold') ?>");
    
    jQuery('#rm_popup_send_email').show();
}

function rm_send_email(email)
{
    if(!rm_validate_fields())
        return;
    //Disable send button to prevent multiple send requests.
    jQuery("#rm_popup_send_email_button").prop('disabled', true);
    jQuery("#rm_popup_cancel_email_button").prop('disabled', true);
    jQuery("#rm_popup_send_email_button").html("<i><?php _e('Sending...','registrationmagic-gold') ?></i>");
    
    var address = '<?php echo $data->user->user_email; ?>';
    var subject = jQuery('#rm_popup_send_email_sub').val();
    var message = jQuery('#rm_popup_send_email_body').val();
    var ajaxnonce = '<?php echo wp_create_nonce('rm_send_email_user_view'); ?>';
    var data = {action: 'send_email_user_view', to: address, sub: subject, body: message,rm_ajaxnonce:ajaxnonce};
    jQuery.post(ajaxurl, data, function(){jQuery('#rm_popup_send_email').hide();alert("<?php _e('Email has been sent successfully.','registrationmagic-gold') ?>");});
}

function rm_validate_fields()
{
    var jqel_subject = jQuery('#rm_popup_send_email_sub');
    var jqel_message = jQuery('#rm_popup_send_email_body');
    var is_valid = true;
    if(jqel_message.val().toString().trim() === '')
    {
        flash_element(jqel_message);
        is_valid = false;
    }
    
    if(jqel_subject.val().toString().trim() === '')
    {
        flash_element(jqel_subject);
        is_valid = false;
    }
    
    return is_valid;
    
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
</script></pre>
