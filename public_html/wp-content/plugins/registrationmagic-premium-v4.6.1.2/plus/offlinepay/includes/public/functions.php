<?php

function rm_olp_create_tables(){
    /**
     * Code to create tables if needed
     */
}

function rm_olp_add_pay_proc_fe($pay_procs){   
    $olp_opts = new RM_OLP_Options;
    $pgws = $olp_opts->get_value_of('payment_gateway');
    if(in_array('offline',$pgws))
        $pay_procs['offline'] = RM_OLP_UI_Strings::get("LABEL_OLP_PAY_OFFLINE");
    return $pay_procs;
}

function rm_olp_process_payment($payment_done, $form, $request, $params) {
    
    if(!$payment_done && isset($request->req['rm_payment_method']))
    {
        if($request->req['rm_payment_method'] == 'offline')
        {
            require_once rm_olp()->includes_dir.'/common/pay_service.php';
            $offline_pay_service = new RM_OLP_Pay_Service();
            $pricing_details = $form->get_pricing_detail($request->req);
            if($pricing_details === null)
                return true;
            $data = new stdClass();
            $data->form_id = $form->get_form_id();
            $data->submission_id = $params['sub_detail']->submission_id;
            $data->user_email = $params['user_email'];
            if ($form->get_form_type() === RM_REG_FORM)
                $data->user_id = $form->get_registered_user_id();

            return $offline_pay_service->charge($data, $pricing_details);
        }
        else 
            return false;
    }
    
    return $payment_done;
}

function rm_olp_register_styles(){ 
    $rm_olp= rm_olp();
    wp_register_style('rm_olp_style', $rm_olp->template_url.'css/style.css');
}

function rm_olp_register_scripts(){
    $rm_olp= rm_olp();
    wp_register_script('rm_olp_script', $rm_olp->template_url.'js/front.js');
}
