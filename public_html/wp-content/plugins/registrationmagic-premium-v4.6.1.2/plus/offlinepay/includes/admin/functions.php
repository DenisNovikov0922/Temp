<?php

function rm_olp_filter_payproc_options_admin($pproc_opts, $data) {
    $pproc_opts['offline'] = "<span title='".__( 'Accept payment offline such as through direct bank transfer or cheque', 'registrationmagic-gold' )."'><strong>".__( 'OFFLINE', 'registrationmagic-gold' )."</strong></span>";
    return $pproc_opts;
}

function rm_olp_filter_payproc_configs_admin($pproc_configs, $data) {
    $olp_opts = new RM_OLP_Options;
        
    $send_info_cb_val = $olp_opts->get_value_of('ex_olp_send_info_email');
    $info = $olp_opts->get_value_of('ex_olp_info');
    if(!$info)
        $info = RM_OLP_UI_Strings::get('OLP_INFO_EMAIL_DEF_BODY');
    $ele_sendinfo_cb = new Element_Checkbox(RM_OLP_UI_Strings::get('LABEL_OLP_SEND_EMAIL'),"ex_olp_send_info_email", array('yes' => ''), array("value" => array($send_info_cb_val), "placeholder" => "", "longDesc" => RM_OLP_UI_Strings::get('HELP_OLP_SEND_EMAIL_INFO')));
    //$ele_info = new Element_Textarea("Info","ex_olp_info",array("placeholder" => "A msg from future", "value" => $info));
    
    $ele_info = new Element_TinyMCEWP(RM_OLP_UI_Strings::get('LABEL_OLP_SEND_EMAIL_INFO'), $info, "ex_olp_info", array('editor_class' => 'rm_TinydMCE', 'editor_height' => '100px'), array("longDesc" => RM_OLP_UI_Strings::get('HELP_OLP_EMAIL_INFO')));
    
    $pproc_configs['offline'] = array($ele_sendinfo_cb, $ele_info);
    return $pproc_configs;
}

function rm_olp_save_payment_settings($req) {
    $options = array();
    $olp_opts = new RM_OLP_Options;
    
    $options['ex_olp_send_info_email'] = isset($req['ex_olp_send_info_email']) ? 'yes' : 'no';
    
    if(isset($req['ex_olp_info']))
        $options['ex_olp_info'] = $req['ex_olp_info'];
    
    $olp_opts->set_values($options);
}

function rm_olp_filter_payment_status_admin($status, $pay_log_row)
{
    if($pay_log_row->pay_proc == 'offline')
    {
        ob_start();
        ?>
        <div id="rm_olp_admin_status_handler">
            <div id="rm_olp_edit_payment_status_button" onclick="rm_olp_show_payment_edit_popup()">UPDATE</div>
            <div id="rm_olp_edit_payment_status_popup" style="display:none">
                <div class="rm_olp_edit_payment_row">
                    <label class="rm_sub_edit_label">Status</label>
                    <div class="rm_sub_edit_input">
                        <select id="rm_olp_select_payment_status">
                            <option value="pending"><?php _e( 'Pending', 'registrationmagic-gold' ) ?></option>
                            <option value="success"><?php _e( 'Completed', 'registrationmagic-gold' ) ?></option>
                            <option value="cancel"><?php _e( 'Canceled', 'registrationmagic-gold' ) ?></option>
                            <option value="refund"><?php _e( 'Refunded', 'registrationmagic-gold' ) ?></option>
                        </select>
                    </div>
                </div>
                <div class="rm_olp_edit_payment_row">
                    <label class="rm_sub_edit_label">Note</label>
                    <div class="rm_sub_edit_input">
                        <textarea id="rm_olp_payment_note" placeholder="<?php _e( 'Enter details such as Cheque number etc. These notes will show up in transaction details.', 'registrationmagic-gold' ) ?>"></textarea>
                    </div>
                </div>
                <div class="rm_olp_edit_payment_row">
                    <label>&nbsp;</label>
                    <div class="rm_sub_edit_input">
                        <button type="button" onclick="rm_olp_update_payment_details(<?php echo $pay_log_row->id; ?>,'<?php _e("Error Occured","registrationmagic-gold"); ?>')"><?php _e("Update","registrationmagic-gold"); ?></button>
                        <button type="button" onclick="jQuery('#rm_olp_edit_payment_status_popup').slideUp()"><?php _e("Cancel","registrationmagic-gold"); ?></button>
                    </div>
                </div>
            
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        return '<span class="rm_payment_status">'.$status.'</span>'.$html;
    }
    else
        return $status;
}

function rm_olp_update_payment_callback_ajax()
{
    if(isset($_POST['pproc_id'], $_POST['pay_status']))
    {
        $pproc_id = $_POST['pproc_id'];
        $status = $_POST['pay_status'];
        
        if(!in_array($status, array('pending','success','cancel','refund')))
        {
            echo "error_invalid_status";
            wp_die();
        }
        
        $note = isset($_POST['pay_note']) ? $_POST['pay_note'] : "";
        require_once rm_olp()->includes_dir.'/common/pay_service.php';
        $pay_service = new RM_OLP_Pay_Service;
        $res = $pay_service->callback($status, $pproc_id, $note);
        if(!$res)
            echo "error";
        else
            echo "success";
    }
    
    wp_die();
}

function rm_olp_admin_menu() {
	do_action( 'rm_olp_admin_menu' );
}

function rm_olp_admin_enqueue() {
     wp_enqueue_style('rm_offline_pay_style', rm_olp()->includes_url.'admin/css/style.css',array(), rm_olp()->version, 'all');
}

function rm_olp_enqueue_script_admin($rm_template_identifier) {
    //Only load js on required templates, no need for global inclusion.
    if($rm_template_identifier == "view_submission")
        wp_enqueue_script('rm_offline_pay_script', rm_olp()->includes_url.'admin/js/admin.js',array(), rm_olp()->version, 'all');
}
