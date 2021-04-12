 jQuery(document).ready(function($){


// Post order_list form
    $('#wf_order_list').on('submit', function(e){

       e.preventDefault();

      //  var button = $(this).find('button');
      //      button.button('loading');

        $.post(wfajax.ajaxurl, $('#wf_order_list').serialize(), function(data){
            
            var obj = data;

            $('#ajax-order-list').html(obj);
            
            

            
        });

    });


$('#wf_order_list').submit();





});
  


