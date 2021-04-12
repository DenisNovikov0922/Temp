(function ($) {
	$('.postbox-container .inside').each(function () {
		if ($(this).children().hasClass('udb-content-wrapper')) {
			$(this).parent().addClass('udb-content');

			var widgetHeight = $(this).children().attr('data-udb-content-height');
			$(this).children().height(widgetHeight);

		}
	});

	$('.udb-video-preview-image-wrapper').click(function () {
		var url = $(this).next().children().attr('data-udb-video-src');
		$(this).next().children().andSelf().fadeIn(200);
		$(this).next().children().attr("src", url);
		$(window).trigger('resize');
	});

	// Window Resize
	$(window).resize(function () {
		$('.udb-video').each(function () {
			var videoWidth = $(this).width();
			$(this).height(videoWidth * 0.5625);
		});
	});

	function close_udb_popup() {
		$('.udb-video-overlay, .udb-video').fadeOut(200);
		setTimeout(function () {
			$('.udb-video').attr('src', '');
		}, 200);
	}

	$('.udb-video-overlay').click(function () {
		close_udb_popup();
	});

	// Close on Escape
	$(document).keyup(function (e) {
		if (e.keyCode == 27) {
			if ($('.udb-video-overlay').is(':visible')) {
				close_udb_popup();
			}
		}
	});

	// Handle form widget
	$('.udb-form-widget').on('submit', function (e) {
		e.preventDefault();

		var $form = $(this);
		var $submitBtn = $form.find('.submit-button');
		var $notice = $form.find('.udb-form-notice');

		$submitBtn.html(udbProContactForm.labels.submitting);
		$submitBtn.attr('disabled', true);

		var data = {};

		$($form.serializeArray()).each(function (i, item) {
			if (item.name) {
				data[item.name] = item.value;
			}
		});

		data.action = 'udb_submit_contact_form';

		jQuery.ajax({
			type: "POST",
			dataType: "json",
			url: udbProDashboard.ajaxUrl,
			data: data
		}).done(function (data) {
			if (data.success) {
				$notice.show();
				$notice.html(data.data.message);
				$form.trigger('reset');
			} else {
				$notice.show();
				$notice.html(data.data.message);
			}
		}).fail(function () {
			// This will be executed on server error like "500 Internal Server Error".
			$notice.show();
			$notice.html("Server error or network problem");
		}).always(function () {
			$submitBtn.html(udbProContactForm.labels.submit);
			$submitBtn.attr('disabled', false);
			$notice.delay(4000).fadeOut(1000);
		});
	})
})(jQuery);