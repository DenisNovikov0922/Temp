jQuery( document ).ready(function() {



jQuery(document).on("click", ".woofood_multistore_list_item_actions .edit", function(){
 jQuery(this).parent().parent().next().slideToggle();
 jQuery('.extra_store_distance_type').trigger("change");

});

jQuery(document).on("submit", ".woofood_multistore_new", function(e){
            var data = jQuery(this).serialize();

 jQuery.ajax({
            type: 'POST',
            url: ajaxwfmultistore.ajaxurl,
            data: data,
            success: function(response){

                jQuery('.woofood_multistore_store_list').html(response);
              jQuery(".woofood_multistore_list_item_settings:first").slideToggle();
                        jQuery('.ui-timepicker-input').timepicker({ timeFormat: 'H:i:s',  maxHour: 20, maxHour: 24,  show2400: true  
 });
              
            }
        });
    e.preventDefault();

});



jQuery(document).on("submit", ".woofood_multistore_delete", function(e){
            var data = jQuery(this).serialize();

 jQuery.ajax({
            type: 'POST',
            url: ajaxwfmultistore.ajaxurl,
            data: data,
            success: function(response){

                jQuery('.woofood_multistore_store_list').html(response);
                jQuery('.ui-timepicker-input').timepicker({ timeFormat: 'H:i:s',  maxHour: 20, maxHour: 24,  show2400: true  
 });
              
            }
        });
    e.preventDefault();

});



 // Perform AJAX login on form submit
    jQuery(document).on('submit', '.multistore_settings_form', function(e){

    	var data = jQuery(this).serialize();

      //  $('form#login p.status').show().text(ajax_login_object.loadingmessage);
        jQuery.ajax({
            type: 'POST',
            url: ajaxwfmultistore.ajaxurl,
            data: data,
            success: function(response){

                jQuery('.woofood_multistore_store_list').html(response);
    jQuery('.ui-timepicker-input').timepicker({ timeFormat: 'H:i:s',  maxHour: 20, maxHour: 24,  show2400: true  
 });
               /* $('form#login p.status').text(data.message);
                if (data.loggedin == true){
                    document.location.href = ajax_login_object.redirecturl;
                }*/
            }
        });
         jQuery(this).parent().slideToggle();
        e.preventDefault();
    });


});



