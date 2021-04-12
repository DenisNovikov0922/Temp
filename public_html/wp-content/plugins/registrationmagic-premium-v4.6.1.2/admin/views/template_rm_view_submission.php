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
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="rmagic">
    <?php
    ?>
    <!-----Operations bar Starts----->
    <div class="operationsbar">
        <div class="rmtitle"><span class="rmtitle-from"><?php echo RM_UI_Strings::get('TEXT_FROM').': </span> '. $data->submission->get_user_email(); ?></div>
        <div class="icons">
            <a href="?page=rm_options_manage"><img alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/global-settings.png'; ?>"></a>
        </div>
        <div class="nav">
            <ul>
                <li onclick="window.history.back()"><a href="javascript:void(0)"><?php echo RM_UI_Strings::get("LABEL_BACK"); ?></a></li>
                <li><a href="?page=rm_note_add&rm_submission_id=<?php echo $data->submission->get_submission_id(); ?>"><?php echo RM_UI_Strings::get("LABEL_ADD_NOTE"); ?></a></li>
                <li><a target="_blank" href="<?php echo admin_url('admin-ajax.php?rm_submission_id='.$data->submission->get_submission_id().'&action=rm_print_pdf'); ?>"><?php echo RM_UI_Strings::get("LABEL_PRINT"); ?></a></li>
                <li><a href="?page=rm_submission_view&rm_submission_id=<?php echo $data->submission->get_submission_id(); ?>&rm_action=delete"><?php echo RM_UI_Strings::get("LABEL_DELETE"); ?></a></li>
              <?php
              $user_email=$data->submission->get_user_email();;
              if($data->submission->is_blocked_email($user_email)){
              ?>
                <li><a href="?page=rm_submission_view&rm_user_email=<?php echo $data->submission->get_user_email(); ?>&rm_submission_id=<?php echo $data->submission->get_submission_id(); ?>&rm_action=unblock_email"><?php echo RM_UI_Strings::get("LABEL_UNBLOCK_EMAIL"); ?></a></li>
              <?php }
              else
              {
                   ?>
                <li><a href="?page=rm_submission_view&rm_user_email=<?php echo $data->submission->get_user_email(); ?>&rm_submission_id=<?php echo $data->submission->get_submission_id(); ?>&rm_action=block_email"><?php echo RM_UI_Strings::get("LABEL_BLOCK_EMAIL"); ?></a></li>
              <?php }
             
              $sub_ip=$data->submission->get_submission_ip();
              if($data->submission->is_blocked_ip($sub_ip)){
              ?>
                <li><a href="?page=rm_submission_view&rm_sub_ip=<?php echo $sub_ip; ?>&rm_submission_id=<?php echo $data->submission->get_submission_id(); ?>&rm_action=unblock_ip"><?php echo RM_UI_Strings::get("LABEL_UNBLOCK_IP"); ?></a></li>
              <?php }
              else
              {
                   if ( $data->submission->get_submission_ip()) {
                   ?>
                <li><a href="?page=rm_submission_view&rm_sub_ip=<?php echo $sub_ip; ?>&rm_submission_id=<?php echo $data->submission->get_submission_id(); ?>&rm_action=block_ip"><?php echo RM_UI_Strings::get("LABEL_BLOCK_IP"); ?></a></li>
                   <?php } }
              ?>
               <li><a href="?page=rm_note_add&rm_note_type=message&rm_submission_id=<?php echo $data->submission->get_submission_id(); ?>"><?php echo RM_UI_Strings::get("LABEL_SEND_MESSAGE"); ?></a></li>
               <?php if($data->related > 0){ ?>
               <li><a href="?page=rm_submission_related&rm_user_email=<?php echo $data->submission->get_user_email(); ?>&rm_submission_id=<?php echo $data->submission->get_submission_id(); ?>"><?php echo RM_UI_Strings::get("LABEL_RELATED").' ('.$data->related.')'; ?></a></li>
               <?php
               } 
               else 
               {?>
                <li><a><?php echo RM_UI_Strings::get("LABEL_RELATED").' (0)'; ?></a></li>
               <?php } ?>
            </ul>
        </div>

    </div>
    <!--****Operations bar Ends**-->

    <!--**Content area Starts**-->
    <div class="rm-submission">
        <?php
        $form_id = $data->submission->get_form_id();
        $submission_id = $data->submission->get_submission_id();
        $user_email = $data->submission->get_user_email();
        $form= new RM_Forms();
        $form->load_from_db($form_id);
        $form_options= $form->get_form_options();
        if(!empty($form_options->custom_status)){
            ?>
            <div class="rm-submission-field-row rm-submission-status-row">
                <div class="rm-submission-label rm-custom_status-wrap">
                    <div class="rm-custom-status-lf"><?php
                    $service = new RM_Services();
                    $custom_statuses = $service->get_custom_statuses($submission_id,$form_id);
                    if(!empty($custom_statuses)){
                        foreach($custom_statuses as $custom_status){
                            echo '<div class="rm-custom-status" data-index="'.$custom_status->status_index.'" style="background-color: #'.$form_options->custom_status[$custom_status->status_index]['color'].'">'.$form_options->custom_status[$custom_status->status_index]['label'].'<span onclick="rm_status_delete(this,'.$custom_status->status_index.')"><i class="material-icons">&#xE5CD;</i></span></div>';
                        }
                        //echo '<pre>'; print_r($custom_statuses);echo '</pre>';
                    }else{
                        echo '<div class="rm-no-status-assigned">'.__('No Status Assigned', 'registrationmagic-gold').'</div>';
                    }
                    ?></div>
                    <div class="rm-submission-value rm-custom-status-lr">
                        <span class="rm-add-custom-status"><span class="dashicons dashicons-plus-alt"></span></span>
                        <div class="rm-submission-value rm-add-custom-status-value" style="display:none">
                            <span class="rm-custom-status-box-nub"></span>
                            <div class="rm-custom-status-search"><input type="search" placeholder="Search Status"></div>
                                <?php
                                foreach ($form_options->custom_status as $key => $value) {
                                    echo '<div class="rm-custom-status-value" onClick="rm_status_append(\'' . $value['label'] . '\',\'' . $value['color'] . '\',' . $key . ',\'' . $user_email . '\')" style="background-color: #' . $value['color'] . ';">' . $value['label'] . '</div>';
                                }
                                //echo '<pre>'; print_r($form_options->custom_status);echo '</pre>';
                                ?>
                            </div>
                    </div>
                </div>
             
            </div>
            <?php
        }
        ?>
        
        <form method="post" action="" name="rm_view_submission" id="rm_view_submission_page_form">
            <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
            <?php
            if ($data->form_is_unique_token) {
                ?>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_UNIQUE_TOKEN_SHORT'); ?> :</div>
                    <div class="rm-submission-value rm-submission-metavalue"><?php echo $data->submission->get_unique_token(); ?></div>
                </div>
                <?php
            }
            ?>
             <?php
            if ( $data->submission->get_submission_ip()) {
                ?>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_IP'); ?> </div>
                    <div class="rm-submission-value rm-submission-metavalue"><?php echo $data->submission->get_submission_ip(); ?></div>
                </div>
                <?php
            }
            ?>
                  <?php
            if ( $data->submission->get_submission_browser()) {
                ?>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_BROWSER'); ?> </div>
                    <div class="rm-submission-value rm-submission-metavalue"><?php echo $data->submission->get_submission_browser(); ?></div>
                </div>
                <?php
            }
            ?>  
            <div class="rm-submission-field-row">
                <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_ENTRY_ID'); ?></div>
                <div class="rm-submission-value rm-submission-metavalue"><?php echo $data->submission->get_submission_id(); ?></div>
            </div>

            <div class="rm-submission-field-row">
                <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_ENTRY_TYPE'); ?></div>
                <div class="rm-submission-value rm-submission-metavalue"><?php echo $data->form_type; ?></div>
            </div>
            
            <div class="rm-submission-field-row">
                <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_SUBMITTED_ON'); ?></div>
                <div class="rm-submission-value rm-submission-metavalue"><?php echo $data->submission->get_submitted_on(); ?> (UTC)</div>
            </div>
            <?php
            if ($data->form_type_status == "1" && !empty($data->user)) {
                $user_roles_dd = RM_Utilities::user_role_dropdown();
                ?>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_USER_NAME'); ?></div>
                    <div class="rm-submission-value"><?php echo $data->user->display_name; ?></div>
                </div>

                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_USER_ROLES'); ?></div>
                    <div class="rm-submission-value">
                        <?php
                        if(isset($data->user->roles[0],$user_roles_dd[$data->user->roles[0]]))
                            echo $user_roles_dd[$data->user->roles[0]];
                        else
                            echo "<em>".RM_UI_Strings::get('MSG_USER_ROLE_NOT_ASSIGNED')."</em>";
                        ?>
                    </div>
                </div>

                <?php
            }
            ?>
            <?php
            $submission_data = $data->submission->get_data();
         
            if (is_array($submission_data) || $submission_data)
                foreach ($submission_data as $field_id => $sub):

                    $sub_key = $sub->label;
                    $sub_data = $sub->value;
                    if(!isset($sub->type)){
                                $sub->type = '';
                            }
                    ?>

                    <!--submission row block-->
                    <?php if(!empty($sub_data)){ ?>
                    <div class="rm-submission-field-row">
                        <div class="rm-submission-label"><?php echo $sub_key; ?></div>
                        <div class="rm-submission-value">
                            <?php
                            //if submitted data is array print it in more than one row.
                            
                            if (is_array($sub_data)) {

                                //If submitted data is a file.

                                if (isset($sub_data['rm_field_type']) && $sub_data['rm_field_type'] == 'File') {
                                    unset($sub_data['rm_field_type']);

                                    foreach ($sub_data as $sub) {

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
                                } elseif (isset($sub_data['rm_field_type']) && $sub_data['rm_field_type'] == 'Address') {
                                    $sub = $sub_data['original'] . '<br/>';
                                    if (count($sub_data) === 8) {
                                        $sub .= '<b>'.__('Street Address','registrationmagic-gold').'</b> : ' . $sub_data['st_number'] . ', ' . $sub_data['st_route'] . '<br/>';
                                        $sub .= '<b>'.__('City','registrationmagic-gold').'</b> : ' . $sub_data['city'] . '<br/>';
                                        $sub .= '<b>'.__('State','registrationmagic-gold').'</b> : ' . $sub_data['state'] . '<br/>';
                                        $sub .= '<b>'.__('Zip Code','registrationmagic-gold').'</b> : ' . $sub_data['zip'] . '<br/>';
                                        $sub .= '<b>'.__('Country','registrationmagic-gold').'</b> : ' . $sub_data['country'];
                                    }
                                    echo $sub;
                                }  elseif ($sub->type == 'Time') {                                  
                                    echo $sub_data['time'].", ".__('Timezone','registrationmagic-gold').": ".$sub_data['timezone'];
                                } elseif ($sub->type == 'Checkbox') {   
                                    echo implode(', ',RM_Utilities::get_lable_for_option($field_id, $sub_data));
                                }
                                //If submitted data is a Star Rating.
                                
                                
                                
                                else {
                                    $field_data = implode(', ', $sub_data);
                                    if($sub->type=="Repeatable"):
                                        $field_data = '<pre>'.implode('<hr> ', $sub_data).'</pre>';
                                    endif;
                                    
                                    echo $field_data;
                                }
                            } else {
                                
                                if($sub->type == 'Rating')
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
                                    echo RM_Utilities::get_lable_for_option($field_id, $sub_data);
                                }
                                else
                                {
                                echo $sub_data;
                                }
                            }
                            ?>
                        </div>
                    </div>  <!-- End of one submission block-->
                    <?php
                    }
                endforeach;
            if ($data->payment) {
                if ($data->payment->log): 
                    ?>
                    <div class="rm-submission-field-row">
                        <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_PAYER_NAME'); ?></div>
                        <div class="rm-submission-value"><?php if (isset($data->payment->log['first_name']))
                echo $data->payment->log['first_name'];
            if (isset($data->payment->log['last_name']))
                echo ' ' . $data->payment->log['last_name'];
            ?></div>
                    </div>
                    <div class="rm-submission-field-row">
                        <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_PAYER_EMAIL'); ?></div>
                        <div class="rm-submission-value"><?php if (isset($data->payment->log['payer_email'])) echo $data->payment->log['payer_email']; ?></div>
                    </div>
        <?php
    endif;
    ?>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_INVOICE'); ?></div>
                    <div class="rm-submission-value"><?php if (isset($data->payment->invoice)) echo $data->payment->invoice; ?></div>
                </div>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_TAXATION_ID'); ?></div>
                    <div class="rm-submission-value"><?php if (isset($data->payment->txn_id)) echo $data->payment->txn_id; ?></div>
                </div>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_STATUS_PAYMENT'); ?></div>
                    <div class="rm-submission-value">
                        <?php if (isset($data->payment->status)) echo apply_filters('rm_payment_status_sub_detail_admin', $data->payment->status, $data->payment);?>
                        <?php if (isset($data->payment->log) && $data->payment->log):?>
                        <a href="javascript:void(0)" onclick="rm_toggle_pp_log_box()"><?php echo RM_UI_Strings::get('LABEL_PAYPAL_TRANSACTION_LOG'); ?></a>
                        <div id="rm_sub_pp_log_detail" style="display:none;
                                                              height: 200px;
                                                              border: #dcdbdb 1px solid;
                                                              overflow-y: auto;
                                                              overflow-x: auto;">
                            <?php echo RM_Utilities::var_to_html($data->payment->log); ?>
                        </div>
                        <?php endif; ?> 
                    </div>
                </div>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_PAID_AMOUNT'); ?></div>
                    <div class="rm-submission-value"><?php if (isset($data->payment->total_amount)) echo $data->payment->total_amount; ?></div>
                </div>
                <div class="rm-submission-field-row">
                    <div class="rm-submission-label"><?php echo RM_UI_Strings::get('LABEL_DATE_OF_PAYMENT'); ?></div>
                    <div class="rm-submission-value"><?php if (isset($data->payment->posted_date)) echo RM_Utilities::localize_time($data->payment->posted_date, get_option('date_format')); ?></div>
                </div>
    <?php
}
?>


        </form>
        <form id="rmeditsubmission" method="post" action="<?php echo add_query_arg('submission_id', $data->submission->get_submission_id(),get_permalink(get_option('rm_option_front_sub_page_id'))); ?>">
                    <input type="hidden" name="rm_slug" value="rm_user_form_edit_sub">
                    <input type="hidden" name="form_id" value="<?php echo $data->submission->get_form_id(); ?>">
        </form>
        <div id="rm_edit_sub_link">
            <a href="javascript:void(0)" onclick="document.getElementById('rmeditsubmission').submit();"><?php echo RM_UI_Strings::get('MSG_EDIT_SUBMISSION'); ?></a>
        </div>
    </div>
    <?php
    if ($data->notes && (is_object($data->notes) || is_array($data->notes))) {
        foreach ($data->notes as $note) {
            $opt=  maybe_unserialize($note->note_options);
            $note_type=isset($opt->type)?$opt->type:null;
            ?>
            <div class="rm-submission-note" style="border-left: 4px solid #<?php echo maybe_unserialize($note->note_options)->bg_color; ?>">
                <div class="rm-submission-note-text"><?php echo $note->notes; ?></div>
                <div class="rm-submission-note-attribute">

                    <?php
                       switch ($note_type) {
                        case 'message' :
                            echo RM_UI_Strings::get('LABEL_SENT_BY') . " <b>" . $note->author . "</b> <em>" . RM_Utilities::localize_time($note->publication_date) . "</em>";
                            break;
                        case 'notification':
                            printf(RM_UI_Strings::get('MSG_SUB_EDITED_BY'), $note->author ?: "the user", RM_Utilities::localize_time($note->publication_date));
                            break;
                        default:
                            echo RM_UI_Strings::get('LABEL_CREATED_BY') . " <b>" . $note->author . "</b> <em>" . RM_Utilities::localize_time($note->publication_date) . "</em>";
                            break;
                    }
 
                    if ($note->editor)
                        echo " (" . RM_UI_Strings::get('LABEL_EDITED_BY') . " <b>" . $note->editor . "</b> <em>" . RM_Utilities::localize_time($note->last_edit_date) . "</em>";
                    ?>
                </div>

                <div class="rm-submission-note-attribute">
                    <?php if ($note_type !== 'message' && $note_type !== 'notification') { ?>
                        <a href="?page=rm_note_add&rm_submission_id=<?php echo $data->submission->get_submission_id(); ?>&rm_note_id=<?php echo $note->note_id; ?>"><?php echo RM_UI_Strings::get('LABEL_EDIT'); ?></a>
                    <?php } ?>
                    <a href="javascript:void(0)" onclick="document.getElementById('rmnotesectionform<?php echo $note->note_id; ?>').submit()"><?php echo RM_UI_Strings::get('LABEL_DELETE'); ?></a>
                </div>
 
                
                <form method="post" id="rmnotesectionform<?php echo $note->note_id; ?>">
                    <input type="hidden" name="rm_slug" value="rm_note_delete">
               
                  <input type="hidden" name="rm_note_id" value="<?php echo $note->note_id; ?>"> 
                
                </form>
            </div>
            <?php
        }
    }
    ?>
</div>

<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
 
 function rm_toggle_pp_log_box(){
     var is_log_visible = jQuery('#rm_sub_pp_log_detail').is(":visible");
     
     if(is_log_visible){
         jQuery('#rm_sub_pp_log_detail').slideUp();
     }
     else{
         jQuery('#rm_sub_pp_log_detail').slideDown();
     }
     
 }
function rm_status_append(status_label,color,status_index,user_email){
    var data = {
        action: 'rm_admin_custom_status_update',
        submission_id: '<?php echo $submission_id; ?>',
        form_id: '<?php echo $form_id; ?>',
        status_index: status_index,
        user_email: user_email,
        action_type: 'append'
    };
    var status_arr = [];
    jQuery('.rm-submission .rm-submission-field-row .rm-custom_status-wrap div.rm-custom-status').each(function(){
        status_arr.push(parseInt(jQuery(this).attr('data-index')));
    });
    if(jQuery.inArray( status_index, status_arr ) >= 0){
        return false;
    }
    jQuery.post(ajaxurl, data, function (response) {
        if(response!='fail'){
            location.reload();
        }else{
            alert('<?php echo RM_UI_Strings::get("AJX_CUSTOM_STATUS_FAIL"); ?>');
        }/*
        if(response=='append'){
            if(jQuery.trim(jQuery('.rm-submission-status-row .rm-submission-label').text())=='Not Assigned'){
                jQuery('.rm-submission-status-row .rm-submission-label').text('');
            }
            jQuery(".rm-submission-status-row .rm-submission-label").append('<div class="rm-custom-status" data-index="'+status_index+'" style="background-color: #'+color+'">'+status_label+'<span onClick="rm_status_delete(this,'+status_index+')"><i class="material-icons">&#xE5CD;</i></span></div>');
        }else if(response=='clear_all'){
            jQuery(".rm-submission-status-row .rm-submission-label").html('<div class="rm-custom-status" data-index="'+status_index+'" style="background-color: #'+color+'">'+status_label+'<span onClick="rm_status_delete(this,'+status_index+')"><i class="material-icons">&#xE5CD;</i></span></div>');
        }else{
            alert('<?php echo RM_UI_Strings::get("AJX_CUSTOM_STATUS_FAIL"); ?>');
        }*/
    });
}
function rm_status_delete(object,status_index){
    var data = {
        action: 'rm_admin_custom_status_update',
        submission_id: '<?php echo $submission_id; ?>',
        form_id: '<?php echo $form_id; ?>',
        status_index: status_index,
        action_type: 'delete'
    };

    jQuery.post(ajaxurl, data, function (response) {
        if(response=='delete'){
            jQuery(object).parent('div').remove();
            if(jQuery.trim(jQuery('.rm-submission-status-row .rm-submission-label').text())==''){
                jQuery('.rm-submission-status-row .rm-submission-label').text('<?php _e('No Status Assigned', 'registrationmagic-gold') ?>');
            }
        }else{
            alert('<?php echo RM_UI_Strings::get("AJX_CUSTOM_STATUS_FAIL"); ?>');
        }
    });
}

jQuery(document).ready(function(){
    jQuery('.rm-add-custom-status').on('click', function(e) {
        jQuery('.rm-add-custom-status-value').toggle();
    });
    jQuery('.rm-custom-status-search input').keyup(function(){
        var txt = jQuery(this).val();
        jQuery('.rm-add-custom-status-value .rm-custom-status-value').hide();
        jQuery('.rm-add-custom-status-value .rm-custom-status-value:contains("'+txt+'")').show();
    });
});

 </script></pre>
