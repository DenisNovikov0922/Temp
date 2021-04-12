 jQuery(document).ready(function($){


// Post order_list form
    $('#woofood_push_notifications_form').on('submit', function(e){

       e.preventDefault();

      //  var button = $(this).find('button');
      //      button.button('loading');

        $.post(wfpush.ajaxurl, $('#woofood_push_notifications_form').serialize(), function(data){
            
            var obj = data;

            $('#woofood_push_output').html(obj);
            
            

            
        });

    });







});