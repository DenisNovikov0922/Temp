function pt_open_login_dialog(href){

	jQuery('#pt-user-modal .modal-dialog').removeClass('registration-complete');

	var modal_dialog = jQuery('#pt-user-modal .modal-dialog');
	modal_dialog.attr('data-active-tab', '');

	switch(href){

		case '#pt-register':
			modal_dialog.attr('data-active-tab', '#pt-register');
			break;

			case '#pt-address-edit':
			modal_dialog.attr('data-active-tab', '#pt-address-edit');
			break;

		case '#pt-login':
		default:
			modal_dialog.attr('data-active-tab', '#pt-login');
			break;


	}
	
	jQuery('#pt-user-modal').modal('show');
}	

function pt_close_login_dialog(){

	jQuery('#pt-user-modal').modal('hide');
}	


(function($) { 
"use strict"; 


jQuery(function($){

	"use strict";
	/***************************
	**  LOGIN / REGISTER DIALOG
	***************************/

	// Open login/register modal
	$('[href="#pt-login"], [href="#pt-register"], [href="#pt-address-edit"]').click(function(e){

		e.preventDefault();

		pt_open_login_dialog( $(this).attr('href') );

	});
	  var is_mobile = false;

	window.mobilecheck = function() {
  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) is_mobile = true;})(navigator.userAgent||navigator.vendor||window.opera);
  return is_mobile;
};

	$('[href="#pt-register"]').click(function(e){
		    if (is_mobile == false) {


		jQuery('.modal-dialog').css('width', '800px');
	}

	});

	$('[href="#pt-login"]').click(function(e){
		    if (is_mobile == false) {


		jQuery('.modal-dialog').css('width', '400px');
	}

	});

	// Switch forms login/register
	$('.modal-footer a, a[href="#pt-reset-password"]').click(function(e){
		e.preventDefault();
		$('#pt-user-modal .modal-dialog').attr('data-active-tab', $(this).attr('href'));
	});


	// Post login form
	$('#pt_login_form').on('submit', function(e){

		e.preventDefault();

		var button = $(this).find('button');
			button.button('loading');

		$.post(ptajax.ajaxurl, $('#pt_login_form').serialize(), function(data){

			var obj = $.parseJSON(data);

			$('.pt-login .pt-errors').html(obj.message);
			
			if(obj.error == false){
				$('#pt-user-modal .modal-dialog').addClass('loading');
				window.location.reload(true);
				button.hide();
			}

			button.button('reset');
		});

	});

	// Post address form
	$('#pt_address_form').on('submit', function(e){

		e.preventDefault();

		var button = $(this).find('button');
			button.button('loading');

		$.post(ptajax.ajaxurl, $('#pt_address_form').serialize(), function(data){

			var obj = $.parseJSON(data);

			$('.pt-address-edit .pt-errors').html(obj.message);
			
			if(obj.error == false){
				$('#pt-user-modal .modal-dialog').addClass('loading');
				//window.location.reload(true);
				button.hide();
			}

			button.button('reset');
		});

	});


	// Post register form
	$('#pt_registration_form').on('submit', function(e){

		e.preventDefault();

		var button = $(this).find('button');
			button.button('loading');


			var validation_data = $('#pt_registration_form').serializeArray();
			var required_fields = ptajax.required;
			var validation_messages = ptajax.validation_messages;
			console.log(validation_messages);
			$('.pt-register .pt-errors').html('');

			/*$(validation_data ).each(function(index, obj){

				if((required_fields.indexOf(obj.name) > -1) && obj.value=="" )
				{
					console.log(obj.name);
					console.log(validation_messages[obj.name]);
					  $('.pt-register .pt-errors').append('<div class="alert alert-danger">'+validation_messages[obj.name]+'</div>');


				}
				else
				{

				}

				});*/

		$.post(ptajax.ajaxurl, $('#pt_registration_form').serialize(), function(data){
			console.log(data);
			
			var obj = $.parseJSON(data);

			$('.pt-register .pt-errors').html(obj.message);
			
			if(obj.error == false){
				$('#pt-user-modal .modal-dialog').addClass('registration-complete');
				// window.location.reload(true);
				button.hide();
			}

			button.button('reset');
			
		});

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

      
    





	if(window.location.hash == '#login'){
		pt_open_login_dialog('#pt-login');
	}		
if(window.location.hash == '#pt-address-edit'){
		pt_open_login_dialog('#pt-address-edit');
	}	




});


})(jQuery);
 





