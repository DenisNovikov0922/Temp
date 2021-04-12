(function($) { 
  "use strict";
$(document).ready(function($){
						MicroModal.init({  scrollBehaviour: 'enable'});

							//MicroModal.init();

/*****    Ajax call on button click      *****/	
function woofood_quickview_ajax(product_id,anim_type,direction,anim_class){
		$(".wf_quickview_loading").css("display","block");
											//	$('#product_view .content').html('<div class="modal__header">Loading</div><div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title"><div class="wf_quickview_product_loader"></div></div>');


		var ajax_data ={};
		ajax_data['action'] = 'woofood_quickview_ajax';
		ajax_data['product_id'] = product_id;
		ajax_data['security'] = wfquickajax.ajax_nonce;



		                  console.log(ajax_data);

		$.ajax({
		cache: false,
		url: wfquickajax.ajaxurl,
		type: 'POST',
		data: ajax_data,
		success: function(response){
			if(response)
			{

							$('#product_view .content').html(response);
							jQuery( "#product_view .content .single_add_to_cart_button" ).appendTo( "#product_view .content .modal__footer" );



			}
					MicroModal.show('product_view');


						//

						//$('#product_view').fadeIn(250);
						//MicroModal.init({  disableScroll: true});

				$(".wf_quickview_loading").css("display","none");


		
			 
		},
	})
}


function woofood_quickview_category_ajax(category_slug,anim_type,direction,anim_class){
		$(".wf_quickview_category_loading").css("display","block");

		var ajax_data ={};
		ajax_data['action'] = 'woofood_quickview_category_ajax';
		ajax_data['category_slug'] = category_slug;

		                  console.log(ajax_data);

		jQuery.ajax({
		url: wfquickajax.ajaxurl,
		type: 'POST',
		data: ajax_data,
		success: function(response){
	
			$('#category_view .content').html(response);
						//MicroModal.init({  disableScroll: true});

					MicroModal.show('category_view');
						//$('#category_view').fadeIn(250);

				$(".wf_quickview_category_loading").css("display","none");

			
			 
		},
	})
}
            


// Main Quickview Button
$('body').on('click','.woofood-quickview-button',function(e){
		       e.preventDefault();


		var p_id	  = $(this).attr('qv-id');

		woofood_quickview_ajax(p_id);

});



// Category Quickview Button
$('body').on('click','.woofood-quickview-category-button',function(e){
		       e.preventDefault();


		var p_slug	  = $(this).attr('qv-id');

		woofood_quickview_category_ajax(p_slug);

});



$('body').on('click','#wf_quickview_close',function(e){
		       e.preventDefault();


		$('#product_view').css("display", "none");
   						 $("#product_view").fadeOut(250);



});


$('body').on('click','#wf_quickview_category_close',function(e){
		       e.preventDefault();



   						 $("#category_view").fadeOut(250);



});

jQuery(document).keypress(function(e) { 
    if (e.keyCode === 27) { 
        jQuery("#product_view").fadeOut(250);
     
      
    } 
});




jQuery( ".extra-options-accordion li").each(function( index ) {

if(jQuery(this).attr("max-options"))
{


	 if(jQuery(this).find('input:checked').length > jQuery(this).attr('max-options')) {
//jQuery('#extra_option_category_id\\[<?php echo $current_extra_option_category;?>\\] input:checkbox:not(:checked)').attr('disabled', true);
   }

    if(jQuery(this).find('input:checked').length == jQuery(this).attr('max-options')) {

jQuery(this).find('input:checkbox:not(:checked)').attr('disabled', true);
   }

    if(jQuery(this).closest('li').find('input:checked').length < jQuery(this).attr('max-options')) {
jQuery(this).find('input:checkbox:not(:checked)').attr('disabled', false);
   }

}

});







jQuery(document).on('change', '.extra-options-accordion li input', function(evt) {
   if(jQuery(this).closest('li').find('input:checked').length > jQuery(this).closest('li').attr('max-options')) {
   }

    if(jQuery(this).closest('li').find('input:checked').length == jQuery(this).closest('li').attr('max-options')) {

jQuery(this).closest('li').find('input:checkbox:not(:checked)').attr('disabled', true);
   }

    if(jQuery(this).closest('li').find('input:checked').length < jQuery(this).closest('li').attr('max-options')) {
jQuery(this).closest('li').find('input:checkbox:not(:checked)').attr('disabled', false);
   }

});










});

    })(jQuery);











    jQuery( this ).ajaxComplete(function() {


    	  	jQuery( ".extra-options-accordion li").each(function( index ) {

if(jQuery(this).attr("max-options"))
{


	 if(jQuery(this).find('input:checked').length > jQuery(this).attr('max-options')) {
   }

    if(jQuery(this).find('input:checked').length == jQuery(this).attr('max-options')) {

jQuery(this).find('input:checkbox:not(:checked)').attr('disabled', true);
   }

    if(jQuery(this).closest('li').find('input:checked').length < jQuery(this).attr('max-options')) {
jQuery(this).find('input:checkbox:not(:checked)').attr('disabled', false);
   }

}

});


	  jQuery(this).on( 'found_variation', function( event, variation ) {
  	jQuery( ".extra-options-accordion li").each(function( index ) {

if(jQuery(this).attr("max-options"))
{


	 if(jQuery(this).find('input:checked').length > jQuery(this).attr('max-options')) {
   }

    if(jQuery(this).find('input:checked').length == jQuery(this).attr('max-options')) {

jQuery(this).find('input:checkbox:not(:checked)').attr('disabled', true);
   }

    if(jQuery(this).closest('li').find('input:checked').length < jQuery(this).attr('max-options')) {
jQuery(this).find('input:checkbox:not(:checked)').attr('disabled', false);
   }

}

});

});

	  });

 jQuery(this).on( 'found_variation', function( event, variation ) {
  	jQuery( ".extra-options-accordion li").each(function( index ) {

if(jQuery(this).attr("max-options"))
{


	 if(jQuery(this).find('input:checked').length > jQuery(this).attr('max-options')) {
   }

    if(jQuery(this).find('input:checked').length == jQuery(this).attr('max-options')) {

jQuery(this).find('input:checkbox:not(:checked)').attr('disabled', true);
   }

    if(jQuery(this).closest('li').find('input:checked').length < jQuery(this).attr('max-options')) {
jQuery(this).find('input:checkbox:not(:checked)').attr('disabled', false);
   }

}

});

});
