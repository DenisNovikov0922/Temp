jQuery(document).ready(function($) {

 jQuery(document).on('click', '.wpslash-tip-percentage-btn, .wpslash-tip-submit', function( ){
            jQuery('.fee').block({
                message: null,
                overlayCSS: {
                    background: "#fff",
                    opacity: .6
                }
            });

              var data = {
            'action': 'wpslash_tip_submit_handler',
            'percentage':jQuery(this).attr('percentage'),
            'amount':jQuery('.wpslash-tip-input').val(),
            'security': wpslash_tipping_obj.security

        };
        jQuery.post(wpslash_tipping_obj.ajaxurl, data, function(response) {
           jQuery(document.body).trigger("update_checkout");
            jQuery('.fee').unblock();

        });





        });






        jQuery(document).on('click', '.wpslash_tip_remove_btn', function( ){

            jQuery('.fee').block({
                message: null,
                overlayCSS: {
                    background: "#fff",
                    opacity: .6
                }
            });


     
            



              var data = {
            'action': 'wpslash_tip_remove',
            'percentage':jQuery(this).attr('percentage'),
            'security': wpslash_tipping_obj.security

        };

        jQuery.post(wpslash_tipping_obj.ajaxurl, data, function(response) {
           jQuery(document.body).trigger("update_checkout");
                            jQuery('.fee').unblock();


        });





        });



    });