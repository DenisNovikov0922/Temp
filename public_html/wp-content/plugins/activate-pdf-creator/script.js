jQuery(document).ready(
    function(){
        // console.log("ready_ready");
        function set_rm_form_submit() {
            // return gotonext_form_3_1()
            // console.log('page_slug');
            var frm = jQuery("#form_3_1");
            if(!frm){
                return false;
            }
            frm.removeAttr("onsubmit");
            frm.attr("onsubmit", "return gotonext_form_prev()")
            clearInterval(enable_submit_timer);
        }
        var enable_submit_timer = setInterval(set_rm_form_submit, 2000);
        
    }
);

function gotonext_form_prev(){
    gotonext_form_3_1();
    return false;
    if(gotonext_form_3_1()){
        create_nexi_pdf();
        return true;
    }
    return false;
}

function create_nexi_pdf(){
    var userdata = {
        "name": jQuery('#form_3_1-element-6').val(),
        "surename": jQuery('#form_3_1-element-7').val(),
        "phonenumber": jQuery('#rm_mobile_19_3_1').val(),
        "email": jQuery('#form_3_1-element-9').val(),
        "company": jQuery('#form_3_1-element-10').val(),
        "vat_number": jQuery('#form_3_1-element-11').val(),
        "sale_code": jQuery('#form_3_1-element-12').val(),
        "terminal_code": jQuery('#form_3_1-element-13').val(),
    };

    userdata = JSON.stringify(userdata);


    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : myAjax.ajaxurl,
        data : {action: "my_user_create_pdf", nonce: myAjax.nonce, data: userdata},
        success: function(response) {
           if(response.type == "success") {
            console.log(response);
           }
           else {
              alert("Your vote could not be added")
           }
        }
     });
     return true;
}