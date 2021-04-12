<?php
if (!defined('WPINC')) {
    die('Closed');
}
wp_enqueue_script('chart_js'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo RM_BASE_URL . 'admin/css/'; ?>style_rm_form_dashboard.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<pre class="rm-pre-wrapper-for-script-tags"><script src="<?php echo RM_BASE_URL . 'admin/js/'; ?>script_rm_form_dashboard.js"></script></pre>
<pre class='rm-pre-wrapper-for-script-tags'><script>
    //Takes value of various status variables (form_id, timeline_range) and reloads page with those parameteres updated.
    function rm_refresh_stats(){
    var trange = jQuery('#rm_stat_timerange').val();
    if(typeof trange == 'undefined')
        trange = <?php echo $data->timerange; ?>;
    window.location = '?page=rm_login_sett_manage&rm_tr='+trange;
}
</script></pre>
<div class="rm-form-configuration-wrapper rm-login-configuration-wrap">
    <div class="rm-grid-top dbfl">
        <div class="rm-grid-title difl"><?php _e('Login Form', 'registrationmagic-gold'); ?><span class="rm-login-form-guide"><a href="https://registrationmagic.com/wordpress-user-login-plugin-guide/" target="_blank"><?php _e('Login Form Guide', 'registrationmagic-gold'); ?><span class="dashicons dashicons-book-alt"></span></a></span></div>
        <!--    Forms toggle-->
    <div class="rm-fd-form-toggle difr" id="rm_form_toggle">
        <?php echo RM_UI_Strings::get('LABEL_TOGGLE_FORM'); ?>
        <select onchange="rm_fd_switch_form(jQuery(this).val())">
            <?php
            echo "<option selected value='rm_login_form'>".__('Login Form','registrationmagic-gold')."</option>";
            foreach ($data->all_forms as $form_id => $form_name):
                echo "<option value='$form_id'>$form_name</option>";
            endforeach;
            ?>
        </select>
    </div>
        
    </div>
   
    <div class="rm-grid difl">
        <div class="rm-grid-section dbfl" id="rm_tour_timewise_stats">
            <div class="rm-grid-section-title dbfl rm-box-title"><?php _e('Login Success vs. Failures Graph over time', 'registrationmagic-gold'); ?></div>
            <div class="rm-timerange-toggle rm-fd-form-toggle rm-timerange-dashboard">
                <?php echo RM_UI_Strings::get('LABEL_SELECT_TIMERANGE'); ?>
                <select id="rm_stat_timerange" onchange="rm_refresh_stats()">
                    <?php
                    $trs = array(7,30,60,90);
                    foreach($trs as $tr){
                        echo "<option value=$tr";
                        if($data->timerange == $tr)
                            echo " selected";
                        printf(">".RM_UI_Strings::get("STAT_TIME_RANGES")."</option>",$tr);
                    }
                    ?>
                </select>
            </div>
            <canvas class="rm-box-graph" id="rm_subs_over_time_chart_div"></canvas>
        </div>
     
        <div class="rm-grid-section dbfl">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_SEC_1_TITLE'); ?>
            </div>

            <div class="rm-grid-icon difl" id="rm-customfields-icon">
                <a href="<?php echo admin_url('admin.php?page=rm_login_field_manage'); ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <div class="rm-grid-icon-badge"><?php echo $data->field_count; ?></div>
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>form-custom-fields.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('FD_LABEL_LOGIN_FORM_FIELDS'); ?></div>
                </a>
            </div>

            <div class="rm-grid-icon difl" id="rm-design-icon"> 
                <a href="<?php echo admin_url('admin.php?page=rm_login_field_view_sett'); ?>" class="rm_fd_link">   
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>form-view.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('FD_LABEL_DESIGN'); ?></div>
                </a>
            </div>

            <div class="rm-grid-icon difl" id="rm-logged-in-view"> 
                <a href="<?php echo admin_url('admin.php?page=rm_login_view'); ?>" class="rm_fd_link">   
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>rm-loggedin-view.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Logged In View', 'registrationmagic-gold'); ?></div>
                </a>
            </div>

        </div>

        <!-- Configure  -->
        <div class="rm-grid-section dbfl" id="rm-general-icon">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_SEC_2_TITLE'); ?>               
            </div>

            <div class="rm-grid-icon difl" id="rm-general-settings">
                <a href="<?php echo admin_url('admin.php?page=rm_login_sett_redirections') ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>rm-login-redirections.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Redirections', 'registrationmagic-gold'); ?></div>
                </a>
            </div>


            <div class="rm-grid-icon difl" id="rm-accounts-icon">
                <a href="<?php echo admin_url('admin.php?page=rm_login_val_sec') ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>rm-validation-and-security.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Validation & Security', 'registrationmagic-gold'); ?></div>

                </a>
            </div>  

            <div class="rm-grid-icon difl" id="rm-postsubmit-icon">
                <a href="<?php echo admin_url('admin.php?page=rm_login_recovery') ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>rm-password-recovery.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Password Recovery', 'registrationmagic-gold'); ?></div>
                </a>
            </div>  

            <div class="rm-grid-icon difl" id="rm-autoresponder-icon">
                <a href="<?php echo admin_url('admin.php?page=rm_login_two_factor_auth') ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>rm-two-factor-authentication.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Two-Factor Authentication', 'registrationmagic-gold'); ?></div>
                </a>
            </div> 

            <div class="rm-grid-icon difl" id="rm-limits-icon">
                <a href="<?php echo admin_url('admin.php?page=rm_login_email_temp') ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>email_templates.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Email Templates', 'registrationmagic-gold'); ?></div>
                </a>
            </div>  
        </div>
        <!-- Configure  Ends here-->

        <!-- Publish Section -->
        <div class="rm-grid-section dbfl" id="rm-publish-section">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_SEC_4_TITLE'); ?>
            </div>            

            <div class="rm-grid-icon difl">
                <a href="javascript:void(0)" class="rm_fd_link rm_publish_popup_link" data-publish_type="login">   
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>publish_login.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Login Box', 'registrationmagic-gold'); ?></div>
                </a>
            </div> 
            <div class="rm-grid-icon difl">
                <a href="javascript:void(0)" class="rm_fd_link rm_publish_popup_link" data-publish_type="loginbtn">
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>login_btn.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Login Button', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                </a>
            </div>
            <div class="rm-grid-icon difl">
                <a href="javascript:void(0)" class="rm_fd_link rm_publish_popup_link" data-publish_type="otp">   
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>publish_otp.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('OTP Login', 'registrationmagic-gold'); ?></div>
                </a>
            </div> 
            <div class="rm-grid-icon difl">
                <a href="javascript:void(0)" class="rm_fd_link rm_publish_popup_link" data-publish_type="magicpopup">   
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>publish_magicpopup.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Magic Popup', 'registrationmagic-gold'); ?></div>
                </a>
            </div> 
        </div>
        <!-- Publish ends here -->

        <!-- Integrate section -->
        <div class="rm-grid-section dbfl" id="rm-thirdparty-section">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_SEC_3_TITLE'); ?>
            </div>

            <div class="rm-grid-icon difl">  
                <a href="<?php echo admin_url('admin.php?page=rm_login_integrations&type=fb'); ?>" class="rm_fd_link">  
                    <div class="rm-grid-icon-area rm-grid-icon-fb dbfl">
                        <i class="fa fa-facebook-square"></i>
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Facebook', 'registrationmagic-gold'); ?></div>

                </a>
            </div> 

            <div class="rm-grid-icon difl"> 
                <a href="<?php echo admin_url('admin.php?page=rm_login_integrations&type=tw'); ?>" class="rm_fd_link">   
                    <div class="rm-grid-icon-area  rm-grid-icon-twitter dbfl">
                        <i class="fa fa-twitter"></i>
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Twitter', 'registrationmagic-gold'); ?></div>

                </a>
            </div> 

            <div class="rm-grid-icon difl">  
                <a href="<?php echo admin_url('admin.php?page=rm_login_integrations&type=win'); ?>" class="rm_fd_link">  
                    <div class="rm-grid-icon-area rm-grid-icon-windows dbfl">
                         <i class="fa fa-windows"></i>
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Windows Live', 'registrationmagic-gold'); ?> </div>
                </a>
            </div>
            
            <div class="rm-grid-icon difl">  
                <a href="<?php echo admin_url('admin.php?page=rm_login_integrations&type=inst'); ?>" class="rm_fd_link">  
                    <div class="rm-grid-icon-area rm-grid-icon-insta dbfl">
                        <i class="fa fa-instagram"></i>
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Instagram', 'registrationmagic-gold'); ?></div>
                </a>
            </div>
            
            <div class="rm-grid-icon difl">  
                <a href="<?php echo admin_url('admin.php?page=rm_login_integrations&type=google'); ?>" class="rm_fd_link">  
                    <div class="rm-grid-icon-area rm-grid-icon-google-p dbfl">
                        <i class="fa fa-google-plus-square"></i>
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Google Plus', 'registrationmagic-gold'); ?></div>
                </a>
            </div>
            
            <div class="rm-grid-icon difl">  
                <a href="<?php echo admin_url('admin.php?page=rm_login_integrations&type=linked'); ?>" class="rm_fd_link">  
                    <div class="rm-grid-icon-area rm-grid-icon-linkein dbfl">
                      <i class="fa fa-linkedin" aria-hidden="true"></i>
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Linked In', 'registrationmagic-gold'); ?></div>

                </a>
            </div> 
        </div>
        <!-- Integrate ends here -->
        

        <!-- Analyze section -->
        <div class="rm-grid-section dbfl" id="rm-login_analytics-section">
            <div class="rm-grid-section-title dbfl">
                <?php _e('Analyze', 'registrationmagic-gold'); ?>
            </div>

            <div class="rm-grid-icon difl">  
                <a href="?page=rm_login_analytics" class="rm_fd_link">  
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>form-analytics.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Login Analytics', 'registrationmagic-gold'); ?></div>
                </a>
            </div> 
            
            <div class="rm-grid-icon difl">  
                <a href="?page=rm_login_retention" class="rm_fd_link">  
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>rm-logs-retention.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Logs Retention', 'registrationmagic-gold'); ?></div>
                </a>
            </div>
            
            <div class="rm-grid-icon difl">
                <a href="javascript:void(0)" class="rm_fd_link rm_timeline_popup_link" data-publish_type="timelinepopup">
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>login-timeline.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('User Login Timeline', 'registrationmagic-gold'); ?></div>
                </a>
            </div>
        </div>    
    </div>
    
    <div class="rm-grid-sidebar-1 difl">
        <div class="rm-grid-section-cards dbfl">        
            <?php
            if($data->login_count == 0):
                ?>
            <div class="rm-grid-sidebar-card dbfl">
                <div class='rmnotice-container'><div class="rmnotice-container"><div class="rm-counter-box">0</div><div class="rm-counter-label"><?php echo RM_UI_Strings::get('LABEL_REGISTRATIONS'); ?></div></div></div>  
            </div>
                <?php
            endif;
            foreach ($data->login_log as $login_detail):
                ?>
                <div class="rm-grid-sidebar-card dbfl">
                    <a href="javascript:void(0)" class="fd_sub_link">
                    <div class="rm-grid-card-profile-image dbfl">
                        <?php echo get_avatar($login_detail->email)?get_avatar($login_detail->email):'<img src="'.RM_IMG_URL.'default_person.png">'; ?>
                    </div>
                    <div class="rm-grid-card-content difl">
                        <?php $user = get_user_by( 'email', $login_detail->email ); ?>
                        <div class="dbfl"><?php echo ($user)?$user->display_name:$login_detail->email; ?></div>
                        <div class="rm-grid-card-content-subtext dbfl"><?php echo date('F d Y @ g:i a',strtotime($login_detail->time)) ?></div></div>
                    </a>
                </div>
                <?php
            endforeach;
            ?>
            <div class="rm-grid-quick-tasks dbfl">
                <div class="rm-grid-sidebar-row dbfl">
                    <div class="rm-grid-sidebar-row-label difl">
                        <a class="<?php echo $data->login_count ? '' : 'rm_deactivated'?>" href="?page=rm_login_analytics"><?php echo RM_UI_Strings::get('FD_LABEL_VIEW_ALL'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="rm-grid-sidebar-2 difl">
        <div class="rm-grid-section dbfl">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_LABEL_STATUS'); ?>
                <span class="rm-grid-section-toggle rm-collapsible"></span>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl" id="rm-sidebar-sc-icon">
                    <img src="<?php echo RM_IMG_URL; ?>shortcode.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl"><?php echo RM_UI_Strings::get('FD_LABEL_FORM_SHORTCODE'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><span id="rmformshortcode">[RM_Login]</span><a href="javascript:void(0)" onclick="rm_copy_to_clipboard(document.getElementById('rmformshortcode'))" id="rm-copy-sc"><?php echo RM_UI_Strings::get('FD_LABEL_COPY'); ?></a>
                    <div style="display:none" id="rm_msg_copied_to_clipboard"><?php _e('Copied to clipboard', 'registrationmagic-gold'); ?></div><div style="display:none" id="rm_msg_not_copied_to_clipboard"><?php _e('Could not be copied. Please try manually.', 'registrationmagic-gold'); ?></div></div>
            </div>
        </div>

        <div class="rm-grid-section dbfl">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_LABEL_CONTENT'); ?>
                <span class="rm-grid-section-toggle rm-collapsible"></span>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>field.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-fields"><?php echo RM_UI_Strings::get('FD_LABEL_F_FIELDS'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo isset($data->field_count)?$data->field_count:''; ?></div>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>submit.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-add-submit"><?php echo RM_UI_Strings::get('FD_FORM_SUBMIT_BTN_LABEL'); ?>:</div>
               <div class="rm-grid-sidebar-row-value difl"><div class="difl" id="rm-submit-label"><?php echo isset($data->buttons->login_btn) ? $data->buttons->login_btn : 'Submit'; ?></div><a href='javascript:;' onclick='edit_label()' ><?php echo RM_UI_Strings::get('LABEL_FIELD_ICON_CHANGE'); ?></a></div>
                <div id="rm-submit-label-textbox" style="display:none"><input type="text" id="submit_label_textbox"/><div><input type="button" value ="<?php _e("Save",'registrationmagic-gold'); ?>" onclick="save_submit_label()"><input type="button" value ="<?php _e("Cancel",'registrationmagic-gold') ?>" onclick="cancel_edit_label()"></div></div>
            </div>
        </div>
        <div class="rm-grid-section dbfl">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_LABEL_STATS'); ?>
                <span class="rm-grid-section-toggle rm-collapsible"></span>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>submissions.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-submissions"><?php echo RM_UI_Strings::get('LABEL_RECORDS'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo isset($data->login_count)?$data->login_count:''; ?><a href="javascript:void(0)" class="<?php echo $data->login_count ? '' : 'rm_deactivated'; ?>" onclick="jQuery.rm_do_action('rm_fd_action_form', 'rm_login_log_export')"><?php echo RM_UI_Strings::get('FD_DOWNLOAD_REGISTRATIONS'); ?></a></div>
            </div>

            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>conversion.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-conversion"><?php echo RM_UI_Strings::get('LABEL_SUCCESS_RATE'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo isset($data->success_rate)?$data->success_rate:0; ?>%</div>
            </div>

            <div class="rm-grid-quick-tasks dbfl">
                <div class="rm-grid-sidebar-row dbfl">
                    <div class="rm-grid-sidebar-row-label difl">
                        <a id="rm-sidebar-reset" href="javascript:void(0)" onclick="jQuery.rm_do_action_with_alert('<?php _e('You are going to delete all stats for login form. Do you want to proceed?','registrationmagic-gold'); ?>', 'rm_fd_action_form', 'rm_login_log_reset')"><?php echo RM_UI_Strings::get('LABEL_RESET'); ?></a>
                    </div>
                </div>
            </div>
        </div>

        

    </div>
    
    
</div>

<!-- Form Publish Pop-up -->
    <div class="rmagic rm-hide-version-number">
    <div id="rm_form_publish_popup" class="rm-modal-view" style="display: none;">
        <div class="rm-modal-overlay"></div>
        <div class="rm-modal-wrap rm-publish-form-popup">

            <div class="rm-modal-titlebar rm-new-form-popup-header">
                <div class="rm-modal-title">
                    <?php _e('Publish', 'registrationmagic-gold'); ?>
                </div>
                <span class="rm-modal-close">&times;</span>
            </div>
            <div class="rm-modal-container">
                <?php $form_id_to_publish = 'login_form'; ?>
                <?php include_once RM_ADMIN_DIR . 'views/template_rm_formflow_publish.php'; ?>
            </div>
        </div>

    </div>
    </div>
<!-- End Form Publish Pop-up -->

<!-- Timeline Pop-up -->
    <div class="rmagic rm-hide-version-number">
    <div id="rm_timeline_popup" class="rm-modal-view" style="display: none;">
        <div class="rm-modal-overlay"></div>
        <div class="rm-modal-wrap rm-publish-form-popup">

            <div class="rm-modal-titlebar rm-new-form-popup-header">
                <div class="rm-modal-title">
                    <?php _e('User Login timeline', 'registrationmagic-gold'); ?>
                </div>
                <span class="rm-modal-close">&times;</span>
            </div>
            <div class="rm-modal-container">
                <?php include_once RM_ADMIN_DIR . 'views/template_rm_formflow_timeline.php'; ?>
            </div>
        </div>

    </div>
    </div>
<!-- End Timeline Pop-up -->
<?php
            wp_enqueue_script('jquery-ui-tooltip',array('jquery'));
?>
<pre class='rm-pre-wrapper-for-script-tags'><script>
    function edit_label(){
        jQuery('#rm-submit-label-textbox').show();
    }
    
    function cancel_edit_label(){
        jQuery('#submit_label_textbox').val('');
        jQuery('#rm-submit-label-textbox').hide();
    }
    
    function save_submit_label(){
        var data = {
                        'register_btn_label': '<?php echo $data->buttons->register_btn ?>',
                        'login_btn_label': jQuery("#submit_label_textbox").val().trim(),
                        'btn_align': '<?php echo $data->buttons->align ?>',
                        'display_register': '<?php echo $data->buttons->display_register ?>'
                    };

        var data = {
                        'action': 'rm_update_login_button',
                        'data': data,
                    };
        jQuery.post(ajaxurl, data, function (response) {
            jQuery('#rm-submit-label').text(jQuery("#submit_label_textbox").val().trim());
            jQuery('#rm-submit-label-textbox').hide();
        });
    }
    
    jQuery(function () {
        jQuery(document).tooltip({
            content: function () {
                return jQuery(this).prop('title');
            },
            show: null, 
            close: function (event, ui) {
                ui.tooltip.hover(

                function () {
                    jQuery(this).stop(true).fadeTo(400, 1);
                },

                function () {
                    jQuery(this).fadeOut("400", function () {
                       jQuery(this).remove();
                    })
                });
            }
        });
    });   
</script></pre>

<?php
/* * ****************************************************************
 * *************     Chart drawing - Line Chart        **************
 * **************************************************************** */
$show_chart=0;
$date_labels= array();
$success_data= array();
$failure_data= array();
foreach ($data->day_wise_stat as $date => $per_day) {
    array_push($date_labels,$date);
    array_push($success_data,$per_day->success);
    array_push($failure_data,$per_day->fail);
    if(empty($show_chart) && (!empty($per_day->success) || !empty($per_day->fail))){
        $show_chart=1;
    }
}
$date_labels= json_encode($date_labels);
$success_data= json_encode($success_data);
$failure_data= json_encode($failure_data);
?>
<pre class='rm-pre-wrapper-for-script-tags'><script>
    function drawTimewiseStat()
    {
        if('<?php echo $show_chart; ?>'==0){
            jQuery("#rm_subs_over_time_chart_div,#rm_tour_timewise_stats").remove();
            return;
        }
        
       var data= {
                    labels: <?php echo $date_labels; ?>,
                    datasets:[{
                                label: 'Login Success',
                                data: <?php echo $success_data; ?>,
                                fill: false,
                                borderColor: 'rgb(53,167,227)',
                                backgroundColor: 'rgb(53,167,227)'
                    },
                    {
                                label: 'Login Failures',
                                data: <?php echo $failure_data; ?>,
                                fill: false,
                                borderColor: 'rgb(72,84,104)',
                                backgroundColor: 'rgb(72,84,104)'
                    }]
        }
        var ctx = document.getElementById('rm_subs_over_time_chart_div');
       // ctx.height = 5000;
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {}
        });
    }
</script></pre>

<script>
    jQuery(document).ready(function(){
        jQuery(".rm_publish_popup_link").each(function(){
            jQuery(this).click(function(){
                rm_set_publish_popup('login_form',jQuery(this).data("publish_type"));
                jQuery("#rm_form_publish_popup").show();
            });
            
        });
        
        jQuery(".rm_timeline_popup_link").each(function(){
            jQuery(this).click(function(){
                rm_set_publish_popup('login_form',jQuery(this).data("publish_type"));
                jQuery("#rm_timeline_popup").show();
            });
        });
        
        jQuery('.rm-modal-close, .rm-modal-overlay').click(function () {
            jQuery(this).parents('.rm-modal-view').hide();
        });
    });
</script>    

<!-- action form to execute rm_slug_actions -->
<form style="display:none" method="post" action="" id="rm_fd_action_form">
    <input type="hidden" name="action" value="" id="rm_slug_input_field">
</form>

