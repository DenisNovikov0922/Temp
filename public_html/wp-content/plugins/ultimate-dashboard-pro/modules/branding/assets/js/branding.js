(function($){

	$('.udb-branding-login-logo-upload').click(function(e) {
		e.preventDefault();

		var custom_uploader = wp.media({
			title: 'Login Logo',
			button: {
				text: 'Upload Image'
			},
			multiple: false  // Set this to true to allow multiple files to be selected
		})
		.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$('.udb-branding-login-logo-url').val(attachment.url);

		})
		.open();
	});

	$('.udb-branding-admin-bar-logo-upload').click(function(e) {
		e.preventDefault();

		var custom_uploader = wp.media({
			title: 'Admin Bar Logo',
			button: {
				text: 'Upload Image'
			},
			multiple: false  // Set this to true to allow multiple files to be selected
		})
		.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$('.udb-branding-admin-bar-logo-url').val(attachment.url);

		})
		.open();
	});

	$('.udb-branding-image-remove').click(function(e) {
		e.preventDefault();
		$(this).prev().prev().val('');
	});

})(jQuery);