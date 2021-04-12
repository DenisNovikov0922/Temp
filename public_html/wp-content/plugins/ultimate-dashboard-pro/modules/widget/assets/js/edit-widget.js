(function ($) {
	function init() {
		setupWidgetRoles();
		setupVideoThumbnail();
		setupCustomRecipient();
		setupContactForm();
	}

	/**
	 * Setup widget roles.
	 */
	function setupWidgetRoles() {
		var fields = document.querySelectorAll('.udb-widget-roles-field');
		if (!fields.length) return;

		fields.forEach(function (field) {
			setupWidgetRole(field);
		});
	}

	/**
	 * Setup widget role.
	 *
	 * @param HTMLElement field The widget role's select box.
	 */
	function setupWidgetRole(field) {
		var $field = $(field);

		$field.select2();

		$field.on('select2:select', function (e) {
			var selections = $field.select2('data');
			var values = [];

			if (e.params.data.id === 'all') {
				$field.val('all');
				$field.trigger('change');
			} else {
				if (selections.length) {
					selections.forEach(function (role) {
						if (role.id !== 'all') {
							values.push(role.id);
						}
					});

					$field.val(values);
					$field.trigger('change');
				}
			}
		});
	}

	function setupVideoThumbnail() {
		$('.udb-video-thumbnail-upload').click(function (e) {
			e.preventDefault();

			var custom_uploader = wp.media({
				title: 'Video Thumbnail',
				button: {
					text: 'Upload Image'
				},
				multiple: false // Set this to true to allow multiple files to be selected
			})
				.on('select', function () {
					var attachment = custom_uploader.state().get('selection').first().toJSON();
					$('.udb-video-thumbnail-url').val(attachment.url);

				})
				.open();
		});

		$('.udb-video-thumbnail-remove').click(function (e) {
			e.preventDefault();
			$(this).prev().prev().val('');
		});
	}

	function setupCustomRecipient() {
		$(document).on('change', '#udb_form_enable_custom_to_address', function () {
			var check = $('#udb_form_enable_custom_to_address').is(':checked');
			if (check === true) {
				$('#udb-form-widget-custom-recipient').slideDown();
				$('#udb_form_custom_to_address').attr('required', true);
			} else {
				$('#udb-form-widget-custom-recipient').slideUp();
				$('#udb_form_custom_to_address').attr('required', false);
			}
		});
	}

	function setupContactForm() {
		$('#udb-clear-log').on('click', clearContactFormLogs);
	}

	function clearContactFormLogs() {
		var $clearBtn = $(this);
		var $logNotice = $('#clear_log_notice');

		$clearBtn.html(udbProEditWidget.labels.clearingLog);
		$clearBtn.attr('disabled', true);

		$.ajax({
      type: "GET",
      dataType: "json",
      url: udbProEditWidget.ajaxUrl,
      data: {
        action: "udb_contact_form_clear_logs",
        nonce: document.querySelector("#udb_clear_contact_form_nonce").value,
        post_id: $clearBtn.attr("data-post-id"),
      },
    })
      .done(function (data) {
        if (data.success) {
          $logNotice.show();
          $logNotice.html(data.data.message);
          $logNotice.delay(4000).fadeOut(1000);
        } else {
          $logNotice.show();
          $logNotice.html(data.data.message);
          $logNotice.delay(4000).fadeOut(1000);
        }
      })
      .always(function () {
        $clearBtn.html(udbProEditWidget.labels.clearLog);
        $clearBtn.attr("disabled", false);
      });
	}

	init();
})(jQuery);