
	(function($) { 
	"use strict"; 
	$(document).ready(function($){
	/*****    Ajax call on button click      *****/



	// Main Quickview Button
	$('body').on('click','#wf_change_address',function(e){
	e.preventDefault();

	MicroModal.show('wf_address_change_modal');
	if ( jQuery( ".mfp-wrap.mfp-auto-cursor.off-canvas" ).length ) {

		jQuery(".mfp-wrap.mfp-auto-cursor.off-canvas").removeAttr("tabindex");
		}


	});

	$('body').on('click','.wf_address_changer_modal .close',function(e){
	e.preventDefault();


	$(".wf_address_changer_modal").fadeOut(250);



	});


	//if the user changes the value in the select dd, this fires.
	$('#previously_address').on('change', function() {

	var data = $.parseJSON(this.value);

	$(data).each(function(i,val)
	{
	$.each(val,function(key,val)
	{
	console.log(key + " : " + val);    
	$('input[name="'+key+'"]').val(val);

	});
	});        


	});





	// Post address form
	$('#wf_address_form').on('submit', function(e){

	e.preventDefault();

	//var button = $(this).find('button');
	//button.button('loading');

	$.post(wfaddchangerajax.ajaxurl, $('#wf_address_form').serialize(), function(data){

	var obj = $.parseJSON(data);
		if(data.redirect_script)
		{
				$('.wf_address_change_modal').html(data.redirect_script);

		}

	//$('.wf_address_changer_edit .wf-errors').html(obj.message);

	if(obj.error == true){
		console.log(obj);
	//$('.wf_address_changer_modal').addClass('loading');
	//window.location.reload(true);
	//button.hide();
	$('.wf_address_change_modal .wf-errors').html(obj.message);

	}
	else
	{
			$('.wf_address_changer_value').html(obj.new_address);

MicroModal.close('wf_address_change_modal');
jQuery( document.body ).trigger( 'wc_fragment_refresh' );
	}



	

	});

	});






		// Post address form
	$('#wf_availability_form_checker').on('submit', function(e){

	e.preventDefault();

	//var button = $(this).find('button');
	//button.button('loading');

	$.post(wfaddchangerajax.ajaxurl, $('#wf_availability_form_checker').serialize(), function(data){

	var obj = $.parseJSON(data);
	var html = JSON.parse(data);
	jQuery('.woofood-address-results').html(html);
	console.log(data);
    					jQuery( document.body ).trigger( 'wc_fragment_refresh' );



	});

	});

	});


	})(jQuery);

