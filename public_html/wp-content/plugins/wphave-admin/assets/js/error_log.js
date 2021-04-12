( function($) {
  	'use strict';

	/*****************************************************************/
	/* REFRESH ERROR LOG FILE */
	/*****************************************************************/

	var verifyData = window.wp_ajax_data || null;
	
	// Refresh the content of the wp depug.log file
	/*****************************************************************/
	
	$('#error_log_refresh').on('click', function(e) {
		
		e.preventDefault();

		if( verifyData === null ) {
			verifyData = window.wp_ajax_data || null;
		}

		var tempLabel = $('#error_log_refresh').html();
		
		// Create JSON objects
		/*****************************************************************/

		var jsonObjects = [{
			// Refresh Action
			command : 'refresh_error_log',
		}];

		var jsonData = JSON.stringify( jsonObjects );

		// Create AJAX request
		/*****************************************************************/
		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: verifyData.ajax_url,
			data: { 
				action: 'wphave_admin_error_log_file_content_refresh',
				security: WP_JS_Error_Log.verify_security_nonce,
				fileData: jsonData,
			},
			beforeSend: function() {   
				// Refreshing button text
				$('#error_log_refresh').html( WP_JS_Error_Log.label_update );
			},
			cache: false,
			success: function(data) {
				
				//console.log($(data.file_content));
				//console.log($(data.success));	

				// Refresh the textarea content
				$("textarea#error_log_area").val( data.file_content );			
				
				// Change the button text
				$('#error_log_refresh').html( WP_JS_Error_Log.label_done );
				
				// Set button text to start text
				setTimeout(function(){
					$('#error_log_refresh').html( tempLabel );
				}, 1500);	
				
				//console.log('SUCCESS');
			},
			error: function(data) {				
				$('#error_log_refresh').html( 'ERROR' );				
				//console.log('FAILURE');
			}
		});

	});
	
	// Clear the content of the wp depug.log file
	/*****************************************************************/
	
	$('#error_log_clear').on('click', function(e) {
		
		e.preventDefault();

		if( verifyData === null ) {
			verifyData = window.wp_ajax_data || null;
		}

		var tempLabel = $('#error_log_clear').html();

		// Create JSON objects
		/*****************************************************************/

		var jsonObjects = [{
			// Clear Action
			command : 'clear_error_log',
		}];

		var jsonData = JSON.stringify( jsonObjects );

		// Create AJAX request
		/*****************************************************************/
		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: verifyData.ajax_url,
			data: { 
				action: 'wphave_admin_error_log_file_content_clear',
				security: WP_JS_Error_Log.verify_security_nonce,
				fileData: jsonData,
			},
			beforeSend: function() {   
				// Clearing button text
				$('#error_log_clear').html( WP_JS_Error_Log.label_clear );
			},
			cache: false,
			success: function(data) {
				
				//console.log($(data.file_content));
				//console.log($(data.success));	
				
				// Refresh the textarea content
				$("textarea#error_log_area").val( data.file_content );
				
				// Change the button text
				$('#error_log_clear').html( WP_JS_Error_Log.label_done );
				
				// Set button text to start text
				setTimeout(function(){
					$('#error_log_clear').html( tempLabel );
				}, 1500);	
				
				//console.log('SUCCESS');
			},
			error: function(data) {				
				$('#error_log_clear').html( 'ERROR' );				
				//console.log('FAILURE');
			}
		});

	});
	
})(jQuery);
