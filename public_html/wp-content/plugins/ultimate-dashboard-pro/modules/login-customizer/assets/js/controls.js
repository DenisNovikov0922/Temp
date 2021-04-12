/**
 * Scripts within customizer control panel.
 *
 * Used global objects:
 * - jQuery
 * - wp
 * - udbLoginCustomizer
 */
(function ($) {
	var events = {};

	wp.customize.bind('ready', function () {
		listen();
	});

	function listen() {
		events.bgFieldsChange();
		events.templateFieldsChange();
		events.layoutFieldsChange();
	}

	events.bgFieldsChange = function () {
		wp.customize.section('udb_login_customizer_bg_section', function (section) {
			section.expanded.bind(function (isExpanded) {
				if (isExpanded) {

					if (wp.customize('udb_login[bg_image]').get()) {
						wp.customize.control('udb_login[bg_position]').activate();
						wp.customize.control('udb_login[bg_size]').activate();
						wp.customize.control('udb_login[bg_repeat]').activate();
					} else {
						wp.customize.control('udb_login[bg_position]').deactivate();
						wp.customize.control('udb_login[bg_size]').deactivate();
						wp.customize.control('udb_login[bg_repeat]').deactivate();
					}

				}
			})
		});

		wp.customize('udb_login[bg_image]', function (setting) {
			setting.bind(function (val) {

				if (val) {
					document.querySelector('[data-control-name="udb_login[bg_image]"]').classList.remove('is-empty');

					wp.customize.control('udb_login[bg_position]').activate();
					wp.customize.control('udb_login[bg_size]').activate();
					wp.customize.control('udb_login[bg_repeat]').activate();
				} else {
					document.querySelector('[data-control-name="udb_login[bg_image]"]').classList.add('is-empty');

					wp.customize.control('udb_login[bg_position]').deactivate();
					wp.customize.control('udb_login[bg_size]').deactivate();
					wp.customize.control('udb_login[bg_repeat]').deactivate();
				}

			});
		});
	};

	events.templateFieldsChange = function () {
		wp.customize('udb_login[template]', function (setting) {
			setting.bind(function (val) {

				var selected = document.querySelector('[data-control-name="udb_login[template]"] .is-selected img');
				var bgImage = selected ? selected.dataset.bgImage : '';

				if (bgImage) wp.customize('udb_login[bg_image]').set(bgImage);

				switch (val) {
					case 'left':
						wp.customize('udb_login[form_position]').set('left');
						break;

					case 'right':
						wp.customize('udb_login[form_position]').set('right');
						break;

					default:
						wp.customize('udb_login[form_position]').set('default');
				}

			});
		});
	}

	events.layoutFieldsChange = function () {
		wp.customize.section('udb_login_customizer_layout_section', function (section) {
			section.expanded.bind(function (isExpanded) {
				if (isExpanded) {

					if (wp.customize('udb_login[form_position]').get() === 'default') {
						wp.customize.control('udb_login[box_width]').deactivate();
						wp.customize.control('udb_login[form_border_width]').activate();
						wp.customize.control('udb_login[form_horizontal_padding]').activate();
						wp.customize.control('udb_login[form_border_color]').activate();
						wp.customize.control('udb_login[form_border_radius]').activate();
					} else {
						wp.customize.control('udb_login[box_width]').activate();
						wp.customize.control('udb_login[form_border_width]').deactivate();
						wp.customize.control('udb_login[form_horizontal_padding]').deactivate();
						wp.customize.control('udb_login[form_border_color]').deactivate();
						wp.customize.control('udb_login[form_border_radius]').deactivate();
					}

				}
			})
		});

		wp.customize('udb_login[form_position]', function (setting) {
			setting.bind(function (val) {
				
				if (val === 'default') {
					wp.customize.control('udb_login[box_width]').deactivate();
					wp.customize.control('udb_login[form_horizontal_padding]').activate();
					wp.customize.control('udb_login[form_border_width]').activate();
					wp.customize.control('udb_login[form_border_color]').activate();
					wp.customize.control('udb_login[form_border_radius]').activate();
				} else {
					wp.customize.control('udb_login[box_width]').activate();
					wp.customize.control('udb_login[form_horizontal_padding]').deactivate();
					wp.customize.control('udb_login[form_border_width]').deactivate();
					wp.customize.control('udb_login[form_border_color]').deactivate();
					wp.customize.control('udb_login[form_border_radius]').deactivate();
				}

			});
		});
	}
})(jQuery, wp.customize);
