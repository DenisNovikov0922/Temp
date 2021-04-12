(function ($) {

  $.fn.serializeArrayAll = function () {
    var rCRLF = /\r?\n/g;
    return this.map(function () {
      return this.elements ? jQuery.makeArray(this.elements) : this;
    }).map(function (i, elem) {
      var val = jQuery(this).val();
      if (val == null) {
        return val == null
        //next 2 lines of code look if it is a checkbox and set the value to blank 
        //if it is unchecked
      } else if (this.type == "checkbox" && this.checked == false) {
        return {name: this.name, value: this.checked ? this.value : ''}
        //next lines are kept from default jQuery implementation and 
        //default to all checkboxes = on
      } 
      
    else if (this.type == "radio" && this.checked == false) {
        return val == null
        //next lines are kept from default jQuery implementation and 
        //default to all checkboxes = on
      }


      else {
        return jQuery.isArray(val) ?
                jQuery.map(val, function (val, i) {
                  return {name: elem.name, value: val.replace(rCRLF, "\r\n")};
                }) :
                {name: elem.name, value: val.replace(rCRLF, "\r\n")};
      }
    }).get();
  };


  $(document).on('click', '.single_add_to_cart_button:not(.disabled)', function (e) {
  	var woocommerce_product_addons_compatibility_check_required = false;
  	jQuery('form.cart .wc-pao-addon-field').each(function(){
        if( jQuery(this).attr('required') && jQuery(this).val() =="" ){
           woocommerce_product_addons_compatibility_check_required = true;
			return false;
        }
    });
	var extra_option_categories_required = new Array();
	var extra_options_categories_required_ok = true;
  	
  	var variation_id = jQuery('form.cart input[name=variation_id]').val();
		




	

		jQuery('form.cart [id^="extra_option_category_id"]').each(function(){
			        	var response_array = {required:0, selected:0, result:true , id:0};

			        	var cat_id = parseInt(jQuery(this).attr('cat-id'));
  					var required_selected = true;
  						response_array.id = cat_id;
        if( jQuery(this).attr('required')){
        	required_selected = false;
        	var min_options = parseInt(jQuery(this).attr('min-options'));
        	response_array.required = min_options;

  var selected_options = jQuery(this).find('input:checked, select option:selected').filter(function() {
        return (this.value.length!=0);
    }).length;			        	        	 
  response_array.selected = selected_options;





	//is variable//
			if(variation_id > 0)
			{


				if(wf_variation_extra_options.includes(cat_id.toString()) || wf_global_extra_options.includes(cat_id))	
				{
					if(selected_options >= min_options)
			{
				required_selected = true;
			}
			else
			{
								required_selected = false;

			}

				}
				




			}
			else
			{


				if(selected_options >= min_options)
			{
				required_selected = true;
			}

			

			}

			

         
        }
        else
        {
        		required_selected = true;

        }

        	if(variation_id > 0)
			{



if(wf_variation_extra_options.includes(cat_id.toString()) || wf_global_extra_options.includes(cat_id))	
				{
									console.log("ssss");

							        	        	 response_array.result = required_selected;
							        	        	 extra_option_categories_required.push(response_array);


				}





			}
			else
			{
							        	        	 response_array.result = required_selected;
							        	        	         extra_option_categories_required.push(response_array);


			}

        //extra_option_categories_required['cat_'+cat_id+''] = response_array;

    });




		console.log(extra_option_categories_required);





		jQuery.each(extra_option_categories_required,function(i, value){

			//is variable//
			if(variation_id > 0)
			{

				if(wf_variation_extra_options.includes(value.id.toString()) || wf_global_extra_options.includes(value.id))
				{


										if(value.result == false )
					  			{
					  				extra_options_categories_required_ok = false;
					  			}
					  			if(value.result == true)
					  			{
					  				if(jQuery('.wf_requiremnts_error#error_'+value.id).length)
					  				{
					  					jQuery('.wf_requiremnts_error#error_'+value.id).remove();


					  						jQuery('#extra_option_category_id\\['+value.id+'\\]').removeClass("error");
					  				
					  				}
					  			}

				}






			}
			//is simple
			else
			{
									if(value.result == false)
				  			{
				  				extra_options_categories_required_ok = false;
				  			}
				  			if(value.result == true)
				  			{
				  				if(jQuery('.wf_requiremnts_error#error_'+value.id).length)
				  				{
				  					  				jQuery('.wf_requiremnts_error#error_'+value.id).css('display', 'none');

				  						jQuery('#extra_option_category_id\\['+value.id+'\\]').removeClass("error");
				  				
				  				}
				  			}

			}

  		
});


    
		if(woocommerce_product_addons_compatibility_check_required == false && extra_options_categories_required_ok)
			{

  			

  			

  				//jQuery().insertBefore(  );


  		

    var $thisbutton = $(this),
            $form = $thisbutton.closest('form'),
			$form = $('form.cart');
            //quantity = $form.find('input[name=quantity]').val() || 1,
            //product_id = $form.find('input[name=variation_id]').val() || $thisbutton.val(),
           data = $form.find('input:not([name="product_id"]), select, button, textarea').serializeArrayAll() || 0;
           console.log(data);

    $.each(data, function (i, item) {

     /* if (item.name == 'add-to-cart') {
        item.name = 'product_id';
        item.value = $form.find('input[name=variation_id]').val() || $thisbutton.val();
      }*/

    });

    e.preventDefault();

    if(!$(document.body).trigger('adding_to_cart', [$thisbutton, data]))
{
	return;
}

		var add_to_cart_original = $( ".single_add_to_cart_button" ).html();

    $.ajax({
      type: 'POST',
      url: woocommerce_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
      data: data,
      beforeSend: function (response) {

					var ajax_loading_text = $('#ajax_loading_text').val();
           $('.single_add_to_cart_button').prop('disabled', true);

			$( ".single_add_to_cart_button" ).html(ajax_loading_text);
      	      	console.log(response);

        $thisbutton.removeClass('added').addClass('loading');
      },
      complete: function (response) {
      	console.log(response);
      	$( ".single_add_to_cart_button" ).html(add_to_cart_original);
      					           $('.single_add_to_cart_button').prop('disabled', false);


        $thisbutton.addClass('added').removeClass('loading');
            					jQuery( document.body ).trigger( 'wc_fragment_refresh' );

      },
        error: function (response) {
      	console.log(response);
      	$( ".single_add_to_cart_button" ).html(add_to_cart_original);
      					           $('.single_add_to_cart_button').prop('disabled', false);


        $thisbutton.addClass('added').removeClass('loading');
      },
      success: function (response) {

      	      	console.log(response);

    					jQuery( document.body ).trigger( 'wc_fragment_refresh' );

        if (response.error & response.product_url) {
          window.location = response.product_url;
          return;
        }
        else if(!response.error)
        {
        	$( ".single_add_to_cart_button" ).html(add_to_cart_original);
        					           $('.single_add_to_cart_button').prop('disabled', false);


        		Toastify({
  text: wf_product_added_message,
  duration: 3000,
  newWindow: true,
  close: true,
  gravity: "top", // `top` or `bottom`
  positionLeft: false, // `true` or `false`
  backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
  stopOnFocus: false // Prevents dismissing of toast on hover
}).showToast();
        					   			MicroModal.close('product_view'); // [2]


        }

        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
      },
    });

    return false;

}
else
{

	jQuery.each(extra_option_categories_required,function(i, value){
  			if(value.result == false)
  			{
  				if(!jQuery('.wf_requiremnts_error#error_'+value.id).length)
  				{
  				jQuery('#extra_option_category_id\\['+value.id+'\\]').addClass("error");
  				var option_text = ""
  				if(value.required ==1)
  				{
  					option_text = wf_option_text;
  				}
  				else
  				{
  					  					option_text = wf_options_text;

  				}
  				var message  = '<div class="wf_requiremnts_error" id="error_'+value.id+'">'+wf_minimum_options_required.replace("%%options%%", value.required).replace("%%option_text%%",option_text )+'</div>';

  				jQuery(message).insertBefore( '#extra_option_category_id\\['+value.id+'\\]' );
  				}
  			

  				//jQuery().insertBefore(  );


  				
  			}
});

				          //alert(wf_required_fields_not_completed_message);
							return false;


}

  });
})(jQuery);