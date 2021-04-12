/**
 * Scripts within customizer preview window.
 *
 * Used global objects:
 * - jQuery
 * - wp
 * - udbLoginCustomizer
 */
(function ($, api) {
	var events = {};

	wp.customize.bind('preview-ready', function () {
		listen();
	});

	function listen() {
		events.bgFieldsChange();
		events.layoutFieldsChange();
	}

	events.bgFieldsChange = function () {
		wp.customize('udb_login[bg_image]', function (setting) {
			setting.bind(function (val) {
				var rule = val ? 'background-image: url(' + val + ');' : 'background-image: none;';

				document.querySelector('[data-listen-value="udb_login[bg_image]"]').innerHTML = 'body.login {' + rule + '}';
			});
		});

		wp.customize('udb_login[bg_position]', function (setting) {
			setting.bind(function (val) {
				var rule = 'background-position: ' + val + ';';

				document.querySelector('[data-listen-value="udb_login[bg_position]"]').innerHTML = 'body.login {' + rule + '}';
			});
		});

		wp.customize('udb_login[bg_size]', function (setting) {
			setting.bind(function (val) {
				var rule = 'background-size: ' + val + ';';

				document.querySelector('[data-listen-value="udb_login[bg_size]"]').innerHTML = 'body.login {' + rule + '}';
			});
		});

		wp.customize('udb_login[bg_repeat]', function (setting) {
			setting.bind(function (val) {
				var rule = 'background-repeat: ' + val + ';';

				document.querySelector('[data-listen-value="udb_login[bg_repeat]"]').innerHTML = 'body.login {' + rule + '}';
			});
		});
	};

	events.layoutFieldsChange = function () {
		wp.customize('udb_login[form_position]', function (setting) {
			var formPositionStyleTag = document.querySelector('[data-listen-value="udb_login[form_position]"]');
			var formWidthStyleTag = document.querySelector('[data-listen-value="udb_login[form_width]"]');
			var formHorizontalPaddingStyleTag = document.querySelector('[data-listen-value="udb_login[form_horizontal_padding]"]');
			var formBorderWidthStyleTag = document.querySelector('[data-listen-value="udb_login[form_border_width]"]');
			var formBgColorStyleTag = document.querySelector('[data-listen-value="udb_login[form_bg_color]"]');

			setting.bind(function (val) {
				var formBgColor = wp.customize('udb_login[form_bg_color]').get();
				var boxWidth = wp.customize('udb_login[box_width]').get();
				var formWidth = wp.customize('udb_login[form_width]').get();
				var formHorizontalPadding = wp.customize('udb_login[form_horizontal_padding]').get();
				var formBorderWidth = wp.customize('udb_login[form_border_width]').get();

				formBgColor = formBgColor ? formBgColor : '#ffffff';
				boxWidth = boxWidth ? boxWidth : '40%';
				formWidth = formWidth ? formWidth : '320px';
				formHorizontalPadding = formHorizontalPadding ? formHorizontalPadding : '24px';
				formBorderWidth = formBorderWidth ? formBorderWidth : '2px';

				if (val === 'default') {

					formBgColorStyleTag.innerHTML = '#login {background-color: transparent;} #loginform {background-color: ' + formBgColor + ';}';

				} else {
					
					formWidthStyleTag.innerHTML = formWidthStyleTag.innerHTML.replace(
						"#login {width:",
						"#loginform {max-width:"
					);

					if (val === "left") {
						formPositionStyleTag.innerHTML =
							"#login {margin-right: auto; margin-left: 0; min-width: 320px; width: " +
							boxWidth +
							"; min-height: 100%;} #loginform {max-width: " +
							formWidth +
							"; box-shadow: none;}";
					} else if (val === "right") {
						formPositionStyleTag.innerHTML =
							"#login {margin-right: 0; margin-left: auto; min-width: 320px; width: " +
							boxWidth +
							"; min-height: 100%;} #loginform {max-width: " +
							formWidth +
							"; box-shadow: none;}";
					}

					formHorizontalPaddingStyleTag.innerHTML =
						"#loginform {padding-left: 24px; padding-right: 24px;}";

					formBorderWidthStyleTag.innerHTML = "#loginform {border-width: 0;}";

					formBgColorStyleTag.innerHTML = '#login {background-color: ' + formBgColor + ';} #loginform {background-color: ' + formBgColor + ';}';

				}

			});
		});

		wp.customize('udb_login[form_bg_color]', function (setting) {
			setting.bind(function (val) {
				var formPosition = wp.customize('udb_login[form_position]').get();
				var content = '';

				val = val ? val : '#ffffff';
				formPosition = formPosition ? formPosition : 'default';

				if (formPosition === 'default') {
					content = '#login {background-color: transparent;} #loginform {background-color: ' + val + ';}';
				} else {
					content = '#login {background-color: ' + val + ';} #loginform {background-color: ' + val + ';}';
				}

				document.querySelector('[data-listen-value="udb_login[form_bg_color]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[box_width]', function (setting) {
			setting.bind(function (val) {
				var formPosition = wp.customize('udb_login[form_position]').get();
				var content = '';

				formPosition = formPosition ? formPosition : 'default';

				if (formPosition !== 'default') {
					content = '#login {width: ' + val + ';}';
				}

				document.querySelector('[data-listen-value="udb_login[box_width]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[form_width]', function (setting) {
			setting.bind(function (val) {
				var formPosition = wp.customize('udb_login[form_position]').get();
				var content = '';

				formPosition = formPosition ? formPosition : 'default';

				if (formPosition === 'default') {
					content = '#login {width: ' + val + ';}';
				} else {
					content = '#loginform {max-width: ' + val + ';}';
				}

				document.querySelector('[data-listen-value="udb_login[form_width]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[form_top_padding]', function (setting) {
			setting.bind(function (val) {
				var content = '#loginform {padding-top: ' + val + ';}';

				document.querySelector('[data-listen-value="udb_login[form_top_padding]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[form_bottom_padding]', function (setting) {
			setting.bind(function (val) {
				var content = '#loginform {padding-bottom: ' + val + ';}';

				document.querySelector('[data-listen-value="udb_login[form_bottom_padding]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[form_horizontal_padding]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '#loginform {padding-left: ' + val + '; padding-right: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[form_horizontal_padding]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[form_border_width]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '#loginform {border-width: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[form_border_width]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[form_border_color]', function (setting) {
			setting.bind(function (val) {
				val = val ? val : '#dddddd';

				document.querySelector('[data-listen-value="udb_login[form_border_color]"]').innerHTML = '#loginform {border-color: ' + val + ';}';
			});
		});

		wp.customize('udb_login[form_border_radius]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '#loginform {border-radius: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[form_border_radius]"]').innerHTML = content;
			});
		});
	};
})(jQuery, wp.customize);
