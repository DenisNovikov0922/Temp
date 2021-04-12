( function($) {
  	'use strict';

	/*****************************************************************/
	/* THEME VERIFICATION */
	/*****************************************************************/

	var verifyData = window.wp_ajax_data || null;
	
	
	// Show / Hide Purchase Code Input Value
	/*****************************************************************/
	
	var codeBtn = $('#purchase_code_show');
	
	$(document).ready(function() {
		if( codeBtn.hasClass('isVisible') ) {
			codeBtn.find('.dashicons-visibility').hide();
			codeBtn.find('.dashicons-hidden').show();
		} else {
			codeBtn.find('.dashicons-visibility').show();
			codeBtn.find('.dashicons-hidden').hide();
		}
	});
	
	codeBtn.on("click", function() {
		
		$(this).toggleClass('isVisible');		
		
    	if( $(this).hasClass('isVisible') ) {
			codeBtn.find('.dashicons-visibility').hide();
			codeBtn.find('.dashicons-hidden').show();
  			return $('#purchase_code').attr('type', 'text');
    	} else {
			codeBtn.find('.dashicons-visibility').show();
			codeBtn.find('.dashicons-hidden').hide();
		}
    	$('#purchase_code').attr('type', 'password');
	});
	

	// Manage verification form submit
	/*****************************************************************/
	
	$('#purchase_verify').on('submit', function(e) {

		e.preventDefault();

		if( verifyData === null ) {
			verifyData = window.wp_ajax_data || null;
		}

		// Get user purchase code
		/*****************************************************************/
		
		var get_purchase_code = $("#purchase_code").val();
		
		// Get user purchase root URL (domain)
		/*****************************************************************/
		
		var get_root_url = $("#purchase_root_url").val();
		
		// Get user E-Mail
		/*****************************************************************/
		
		var get_client_mail = $("#purchase_client_mail").val();

		// Create JSON objects
		/*****************************************************************/

		var jsonObjects = [{
			purchase_code : get_purchase_code,
			purchase_root_url : get_root_url,
			purchase_client_mail : get_client_mail
		}];

		var jsonData = JSON.stringify( jsonObjects );

		// Create AJAX request
		/*****************************************************************/
		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: verifyData.ajax_url,
			data: { 
				action: 'wphave_admin_license_install_process',
				security: WP_JS_Plugin_Activation.verify_security_nonce,
				fieldData: jsonData,
			},
			beforeSend: function() { 				
				// Checking button label
				$('#btn_purchase').val( WP_JS_Plugin_Activation.label_check );
			},
			cache: false,
			success: function(data) {
				
				//console.log($(data.fieldData));
				//console.log($(data.verify_status));
				//console.log($(data.license_action));
				//console.log($(data.success));
				
				// License server notice
				$('#license_notice').css('display', 'block');
				$('#license_notice .notice-holder').html( data.license_action );
				
				if( data.license_status == 'success' ) {
					// Success notic color
					$('#license_notice').css('background', '#8db51e');
					
					// Done button label
					$('#btn_purchase').val( WP_JS_Plugin_Activation.label_done );
				} else {
					// Try again button label
					$('#btn_purchase').val( WP_JS_Plugin_Activation.label_try );
				}			
				
				// Reload the current page if the purchase code is valid
				if( data.license_status == 'success' ) {
					setTimeout(function(){
						location.reload();
					}, 1500);				
				}
				
				//console.log('SUCCESS');
			},
			error: function(data) {
				// Try again button label
				$('#btn_purchase').val( WP_JS_Plugin_Activation.label_try );
				
				$('#license_notice .notice-holder').html( 'ERROR' );
				
				//console.log('FAILURE');
			}
		});

	});
	
	// Manage purchase code unlocking
	/*****************************************************************/
	
	$('#btn_delete_license').on('click', function(e) {

		e.preventDefault();

		if( verifyData === null ) {
			verifyData = window.wp_ajax_data || null;
		}

		// Create JSON objects
		/*****************************************************************/

		var jsonObjects = [{
			// Purchase Code Unlock Action
			command : 'unlock_purchase_code',
		}];

		var jsonData = JSON.stringify( jsonObjects );

		// Create AJAX request
		/*****************************************************************/
		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: verifyData.ajax_url,
			data: { 
				action: 'wphave_admin_license_uninstall_process',
				security: WP_JS_Plugin_Activation.verify_security_nonce,
				fieldData: jsonData,
			},
			beforeSend: function() {   
				// Unlocking button label
				$('#btn_delete_license').html( WP_JS_Plugin_Activation.label_unlock );
			},
			cache: false,
			success: function(data) {

				//console.log($(data.license_action));
				//console.log($(data.success));				
				
				// License server notice
				$('#license_notice').css('display', 'block');
				$('#license_notice .notice-holder').html( data.license_action );
				
				if( data.license_status == 'success' ) {
					// Success notic color
					$('#license_notice').css('background', '#8db51e');
					
					// Unlocked button label
					$('#btn_delete_license').html( WP_JS_Plugin_Activation.label_unlocked );
				} else {
					// Try again button label
					$('#btn_delete_license').html( WP_JS_Plugin_Activation.label_try );
					
					// If license can not unlocked, show the reset license button
					$('.license-reset').css('display', 'block');
				}
				
				// Reload the current page if the purchase code is unlocked				
				if( data.license_status == 'success' ) {
					setTimeout(function(){
						location.reload();
					}, 1500);			
				}
				
				//console.log('SUCCESS');
			},
			error: function(data) {
				// Try again button label
				$('#btn_delete_license').html( WP_JS_Plugin_Activation.label_try );
				
				$('#license_notice .notice-holder').html( 'ERROR' );
				
				//console.log('FAILURE');
			}
		});

	});
	
	// Manage purchase code reset
	/*****************************************************************/
	
	$('#btn_reset_license').on('click', function(e) {

		e.preventDefault();

		if( verifyData === null ) {
			verifyData = window.wp_ajax_data || null;
		}

		// Create JSON objects
		/*****************************************************************/

		var jsonObjects = [{
			// Purchase Code Unlock Action
			command : 'reset_purchase_code',
		}];

		var jsonData = JSON.stringify( jsonObjects );

		// Create AJAX request
		/*****************************************************************/
		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: verifyData.ajax_url,
			data: { 
				action: 'wphave_admin_license_reset_process',
				security: WP_JS_Plugin_Activation.verify_security_nonce,
				fieldData: jsonData,
			},
			beforeSend: function() {   
				// Unlocking button label
				$('#btn_reset_license').html( WP_JS_Plugin_Activation.label_reset );
			},
			cache: false,
			success: function(data) {

				//console.log($(data.license_action));
				//console.log($(data.success));				
				
				// License server notice
				$('#license_notice').css('display', 'block');
				$('#license_notice .notice-holder').html( data.license_action );
				
				if( data.license_status == 'success' ) {
					// Success notic color
					$('#license_notice').css('background', '#8db51e');
					
					// Reset button label
					$('#btn_reset_license').html( WP_JS_Plugin_Activation.label_done );
				} else {
					// Try again button label
					$('#btn_reset_license').html( WP_JS_Plugin_Activation.label_try );
				}
				
				// Reload the current page if the purchase code is unlocked				
				if( data.license_status == 'success' || data.license_status == 'error' ) {
					setTimeout(function(){
						location.reload();
					}, 1500);			
				}
				
				//console.log('SUCCESS');
			},
			error: function(data) {
				// Try again button label
				$('#btn_reset_license').html( WP_JS_Plugin_Activation.label_try );
				
				$('#license_notice .notice-holder').html( 'ERROR' );
				
				//console.log('FAILURE');
			}
		});

	});
	
})(jQuery);
