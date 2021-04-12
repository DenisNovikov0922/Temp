function rm_olp_show_payment_edit_popup() {
    jQuery('#rm_olp_edit_payment_status_popup').slideToggle('slide');
}

function rm_olp_update_payment_details(pproc_id,message) {
    
    var status = jQuery("#rm_olp_select_payment_status").val();
    var note = jQuery("#rm_olp_payment_note").val().toString().trim();
    
    var data = {
                    action: 'rm_olp_update_payment',
                    pproc_id: pproc_id,
                    pay_status: status,
                    pay_note: note
               };
                
    jQuery.post(ajaxurl, data, function(resp){
        if(resp == 'success')
            window.location.reload();
        else
            alert(message);
        
        jQuery('#rm_olp_edit_payment_status_popup').hide();
    });
}