/**
 * Used global objects:
 * - jQuery
 * - ajaxurl
 */
(function ($) {
	if (window.NodeList && !NodeList.prototype.forEach) {
		NodeList.prototype.forEach = Array.prototype.forEach;
	}

	if (!String.prototype.includes) {
		String.prototype.includes = function (search, start) {
			'use strict';

			if (search instanceof RegExp) {
				throw TypeError('first argument must not be a RegExp');
			}
			if (start === undefined) { start = 0; }
			return this.indexOf(search, start) !== -1;
		};
	}

	var elms = {};
	var loading = {};
	var state = {};
	var usersSelect2 = null;
	var usersData = [];
	var savedUsers = [];

	/**
	 * Init the script.
	 * Call the main functions here.
	 */
	function init() {
		elms.form = document.querySelector('.udb-admin-menu--edit-form');
		elms.saveButton = elms.form.querySelector('.udb-admin-menu--submit-button');
		elms.resetRoleButton = elms.form.querySelector('.udb-admin-menu--reset-role');
		elms.resetButtons = elms.form.querySelectorAll('.udb-admin-menu--reset-button');

		elms.searchBox = document.querySelector('.udb-admin-menu-box--search-box');
		elms.roleTabs = document.querySelector('.udb-admin-menu--role-tabs');
		elms.userTabs = document.querySelector('.udb-admin-menu--user-tabs');
		elms.userTabsMenu = elms.userTabs.querySelector('.udb-admin-menu--user-menu');
		elms.userTabsContent = elms.userTabs.querySelector('.udb-admin-menu--edit-area');

		state.usersLoaded = false;
		state.isSaving = false;

		setupResetRoleButton();

		udbAdminMenu.roles.forEach(function (role) {
			getMenu('role', role.key);
		});

		var savedUserTabsContentItems = elms.userTabsContent.querySelectorAll('.udb-admin-menu--tab-content-item');

		savedUserTabsContentItems.forEach(function (item) {
			savedUsers.push(parseInt(item.dataset.userId, 10));
			getMenu('user_id', item.dataset.userId);
		});

		elms.form.addEventListener('submit', submitForm);

		elms.resetButtons.forEach(function (resetButton) {
			resetButton.addEventListener('click', resetMenu);
		});

		$(document).on('click', '.udb-admin-menu--tab-menu-item', switchTab);
		$(document).on('click', '.udb-admin-menu--remove-tab', removeTab);
		$(document).on('click', '.udb-admin-menu-box--header-tab', switchHeaderTab);
		$(document).on('click', '.udb-admin-menu--expand-menu', expandCollapseMenuItem);
		$(document).on('click', '.hide-menu', showHideMenuItem);
		$(document).on('click', '.udb-admin-menu--add-new-menu', addNewMenu);
		$(document).on('click', '.udb-admin-menu--add-new-submenu', addNewSubmenu);
		$(document).on('click', '.udb-admin-menu--remove-menu-item', removeMenuItem);

		setupUsersSelect2();
	}

	function setupUsersSelect2() {
		if (state.usersLoaded) return;
		loadUsers();
	}

	function switchHeaderTab(e) {
		var tabs = document.querySelectorAll('.udb-admin-menu-box--header-tab');
		if (!tabs.length) return;

		tabs.forEach(function (tab) {
			if (tab !== e.target) {
				tab.classList.remove('is-active');
			}
		});

		e.target.classList.add('is-active');

		if (e.target.dataset.headerTab === 'users') {
			elms.searchBox.classList.remove('is-hidden');
			elms.userTabs.classList.remove('is-hidden');
			elms.roleTabs.classList.add('is-hidden');

			hideResetButtons();
		} else {
			elms.searchBox.classList.add('is-hidden');
			elms.userTabs.classList.add('is-hidden');
			elms.roleTabs.classList.remove('is-hidden');

			showResetButtons();
		}
	}

	/**
	 * Hide reset buttons.
	 */
	function hideResetButtons() {
		elms.resetButtons.forEach(function (button) {
			button.classList.add('is-hidden');
		});
	}

	/**
	 * Show reset buttons.
	 */
	function showResetButtons() {
		elms.resetButtons.forEach(function (button) {
			button.classList.remove('is-hidden');
		});
	}

	/**
	 * Add new menu item.
	 * @param {Event} e The event object.
	 */
	function addNewMenu(e) {
		var by = this.parentNode.dataUserId ? 'user_id' : 'role';
		var value = by === 'user_id' ? this.parentNode.dataset.userId : this.parentNode.dataset.role;
		var randomId = Math.random().toString(36).substr(2, 10);
		var menu = {
			class: "menu-top menu-icon-custom udb-menu-top udb-menu-icon-custom",
			class_default: "menu-top menu-icon-custom udb-menu-top udb-menu-icon-custom",
			dashicon: "dashicons-admin-generic",
			dashicon_default: "dashicons-admin-generic",
			icon_svg: "",
			icon_svg_default: "",
			icon_type: "dashicon",
			icon_type_default: "dashicon",
			id: "menu-custom-" + randomId,
			id_default: "menu-custom-" + randomId,
			is_hidden: "0",
			submenu: [],
			title: "Custom Menu",
			title_default: "Custom Menu",
			type: "menu",
			url: "",
			url_default: "/wp-admin/",
			was_added: "1"
		};
		var template = replaceMenuPlaceholders(by, value, menu);

		$(this.parentNode.querySelector('.udb-admin-menu--menu-list')).append($(template));

		var menuItem = this.parentNode.querySelector('[data-default-id="menu-custom-' + randomId + '"]');

		setupMenuItem(menuItem);

		var submenuList = menuItem.querySelectorAll('.udb-admin-menu--submenu-list');

		if (submenuList.length) {
			submenuList.forEach(function (submenu) {
				setupMenuItems(submenu, true);
			});
		}
	}

	/**
	 * Add new submenu item.
	 * @param {Event} e The event object.
	 */
	function addNewSubmenu(e) {
		var workspace = this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		var by = workspace.dataUserId ? 'user_id' : 'role';
		var value = by === 'user_id' ? workspace.dataset.userId : workspace.dataset.role;
		var randomId = Math.random().toString(36).substr(2, 10);
		var submenu = {
			id: "submenu-custom-" + randomId,
			is_hidden: "0",
			title: "Custom Submenu",
			title_default: "Custom Submenu",
			url: "",
			url_default: "/wp-admin/",
			was_added: "1"
		};
		var template = replaceSubmenuPlaceholders(by, value, submenu);

		$(this.parentNode.querySelector('.udb-admin-menu--submenu-list')).append($(template));

		var submenuItem = this.parentNode.querySelector('[data-submenu-id="submenu-custom-' + randomId + '"]');

		setupMenuItem(submenuItem, true);
	}

	/**
	 * Remove menu item.
	 * @param {Event} e The event object.
	 */
	function removeMenuItem(e) {
		var menuItem = this.parentNode.parentNode.parentNode;
		if (!parseInt(menuItem.dataset.added, 10)) return;
		var menuList = menuItem.parentNode;

		menuList.removeChild(menuItem);
	}

	/**
	 * Load users select2 data via ajax.
	 */
	function loadUsers() {
		$.ajax({
			type: 'get',
			url: ajaxurl,
			cache: false,
			data: {
				action: 'udb_admin_menu_get_users',
				nonce: udbAdminMenu.nonces.getUsers
			}
		}).done(function (r) {
			if (!r.success) return;

			var field = document.querySelector('.udb-admin-menu--search-user');
			if (!field) return;

			field.options[0].innerHTML = field.dataset.placeholder;
			field.disabled = false;
			usersData = r.data;

			usersData.forEach(function (data, index) {
				if (savedUsers.indexOf(data.id) >= 0) {
					usersData[index].disabled = true;
				}
			});

			usersSelect2 = $(field).select2({
				placeholder: field.dataset.placeholder,
				data: usersData
			});

			$(field).on('select2:select', onUserSelected);

			state.usersLoaded = true;
		}).fail(function () {
			console.log('Failed to load users');
		}).always(function () {
			//
		});
	}

	/**
	 * Event handler to run when a user (inside select2) is selected.
	 * @param {Event} e The event object.
	 */
	function onUserSelected(e) {
		appendUserTabsMenu(e.params.data);
		appendUserTabsContent(e.params.data);

		usersData.forEach(function (data, index) {
			if (data.id == e.params.data.id) {
				usersData[index].disabled = true;
			}
		});

		usersSelect2.select2('destroy');
		usersSelect2.empty();

		usersSelect2.select2({
			placeholder: usersSelect2.data('placeholder'),
			data: usersData
		});

		getMenu('user_id', e.params.data.id);
	}

	/**
	 * Build user tab menu item template string and append it to user tab menu.
	 * @param {object} data The id and text pair (select2 data format).
	 */
	function appendUserTabsMenu(data) {
		var template = udbAdminMenu.templates.userTabMenu;

		template = template.replace(/{user_id}/g, data.id);
		template = template.replace(/{display_name}/g, data.text);

		elms.userTabsMenu.querySelectorAll('.udb-admin-menu--tab-menu-item').forEach(function (el) {
			el.classList.remove('is-active');
		});

		$(elms.userTabsMenu).append(template);
	}

	/**
	 * Build user tab menu item template string and append it to user tab menu.
	 * @param {object} data The id and text pair (select2 data format).
	 */
	function appendUserTabsContent(data) {
		var template = udbAdminMenu.templates.userTabContent;

		template = template.replace(/{user_id}/g, data.id);

		document.querySelectorAll('.udb-admin-menu--user-tabs > .udb-admin-menu--tab-content > .udb-admin-menu--tab-content-item').forEach(function (el) {
			el.classList.remove('is-active');
		});

		$(elms.userTabsContent).append(template);
	}

	/**
	 * Switch tabs.
	 */
	function switchTab(e) {
		if (e.target.classList.contains('delete-icon')) return;
		var tabArea = this.parentNode.parentNode;
		var tabId = this.dataset.udbTabContent;

		var tabHasIdByDefault = false;

		if (tabArea.id) {
			tabHasIdByDefault = true;
		} else {
			tabArea.id = 'udb-admin-menu--tab' + Math.random().toString(36).substring(7);
		}

		var menus = document.querySelectorAll('#' + tabArea.id + ' > .udb-admin-menu--tab-menu > .udb-admin-menu--tab-menu-item');
		var contents = document.querySelectorAll('#' + tabArea.id + ' > .udb-admin-menu--tab-content > .udb-admin-menu--tab-content-item');

		if (!tabHasIdByDefault) tabArea.removeAttribute('id');

		menus.forEach(function (menu) {
			if (menu.dataset.udbTabContent !== tabId) {
				menu.classList.remove('is-active');
			} else {
				menu.classList.add('is-active');
			}
		});

		contents.forEach(function (content) {
			if (content.id !== tabId) {
				content.classList.remove('is-active');
			} else {
				content.classList.add('is-active');
			}
		});
	}

	/**
	 * Remove tab.
	 * @param {Event} e The event object.
	 */
	function removeTab(e) {
		var tabArea = this.parentNode.parentNode.parentNode;
		var menuItem = this.parentNode;
		var menuWrapper = tabArea.querySelector('.udb-admin-menu--tab-menu');
		var contentWrapper = tabArea.querySelector('.udb-admin-menu--tab-content');

		usersData.forEach(function (data, index) {
			if (data.id == menuItem.dataset.userId) {
				usersData[index].disabled = false;
			}
		});

		usersSelect2.select2('destroy');
		usersSelect2.empty();

		usersSelect2.select2({
			placeholder: usersSelect2.data('placeholder'),
			data: usersData
		});

		menuWrapper.removeChild(this.parentNode);
		contentWrapper.removeChild(tabArea.querySelector('#' + this.parentNode.dataset.udbTabContent));

		if (contentWrapper.querySelectorAll('.udb-admin-menu--tab-content-item').length === 1) {
			document.querySelector('#udb-admin-menu--user-empty-edit-area').classList.add('is-active');
		}
	}

	/**
	 * Setup reset role button.
	 * The button text should be changed when the role tab is switched.
	 */
	function setupResetRoleButton() {
		var tabs = document.querySelectorAll('.udb-admin-menu--role-menu > .udb-admin-menu--tab-menu-item');
		if (!tabs) return;

		tabs.forEach(function (tab) {
			tab.addEventListener('click', function () {
				elms.resetRoleButton.innerHTML = 'Reset ' + this.querySelector('button').innerHTML + ' Menu';
				elms.resetRoleButton.dataset.role = this.dataset.role;
			});
		});
	}

	/**
	 * Get menu & submenu either by role or user id.
	 *
	 * @param {string} by The identifier, could be "role" or "user_id".
	 * @param {string} value The specified role or user id.
	 */
	function getMenu(by, value) {
		var data = {};

		data.action = 'udb_admin_menu_get_menu';
		data.nonce = udbAdminMenu.nonces.getMenu;
		data[by] = value;

		$.ajax({
			url: ajaxurl,
			type: "post",
			dataType: 'json',
			data: data
		}).done(function (r) {
			if (!r || !r.success) return;
			buildMenu(by, value, r.data);
		}).always(function () {
			//
		});
	}

	/**
	 * Build menu list.
	 *
	 * @param {string} by The identifier, could be "role" or "user_id".
	 * @param {string} value The specified role or user id.
	 * @param {array} menuList The menu list returned from ajax response.
	 */
	function buildMenu(by, value, menuList) {
		var identifier = by === 'role' ? value : 'user-' + value;
		var editArea = document.querySelector('#udb-admin-menu--' + identifier + '-edit-area');
		if (!editArea) return;
		var listArea = editArea.querySelector('.udb-admin-menu--menu-list');
		var builtMenu = '';

		menuList.forEach(function (menu) {
			builtMenu += replaceMenuPlaceholders(by, value, menu);
		});

		listArea.innerHTML = builtMenu;

		setupMenuItems(listArea);

		var submenuList = listArea.querySelectorAll('.udb-admin-menu--submenu-list');

		if (submenuList.length) {
			submenuList.forEach(function (submenu) {
				setupMenuItems(submenu, true);
			});
		}
	}

	/**
	 * Replace menu placeholders.
	 *
	 * @param {string} by Either by role or user_id.
	 * @param {string} value The role or user_id value.
	 * @param {object} menu The menu item.
	 */
	function replaceMenuPlaceholders(by, value, menu) {
		var template;
		var submenuTemplate;
		var icon;

		if (menu.type === 'separator') {
			template = udbAdminMenu.templates.menuSeparator;
			template = template.replace(/{separator}/g, menu.url_default);


			template = template.replace(/{menu_is_hidden}/g, menu.is_hidden);
			template = template.replace(/{hidden_icon}/g, (menu.is_hidden == '1' ? 'hidden' : 'visibility'));
			template = template.replace(/{menu_was_added}/g, menu.was_added);
			template = template.replace(/{default_menu_id}/g, menu.id_default);
			template = template.replace(/{default_menu_url}/g, menu.url_default);
		} else {
			template = udbAdminMenu.templates.menuList;
			template = template.replace(/{menu_title}/g, menu.title);
			template = template.replace(/{default_menu_title}/g, menu.title_default);

			var parsedTitle = menu.title ? menu.title : menu.title_default;
			template = template.replace(/{parsed_menu_title}/g, parsedTitle);

			template = template.replace(/{menu_url}/g, menu.url);
			template = template.replace(/{default_menu_url}/g, menu.url_default);

			template = template.replace(/{menu_id}/g, menu.id);
			template = template.replace(/{default_menu_id}/g, menu.id_default);

			template = template.replace(/{menu_dashicon}/g, menu.dashicon);
			template = template.replace(/{default_menu_dashicon}/g, menu.dashicon_default);

			template = template.replace(/{menu_icon_svg}/g, menu.icon_svg);
			template = template.replace(/{default_menu_icon_svg}/g, menu.icon_svg_default);

			template = template.replace(/{menu_is_hidden}/g, menu.is_hidden);
			template = template.replace(/{trash_icon}/g, (parseInt(menu.was_added, 10) ? '<span class="dashicons dashicons-trash udb-admin-menu--remove-menu-item"></span>' : ''));
			template = template.replace(/{hidden_icon}/g, (menu.is_hidden == '1' ? 'hidden' : 'visibility'));
			template = template.replace(/{menu_was_added}/g, menu.was_added);

			var menuIconSuffix = menu.icon_type && menu[menu.icon_type] ? '' : '_default';

			if (menu['icon_type' + menuIconSuffix] === 'icon_svg') {
				icon = '<img alt="" src="' + menu['icon_svg' + menuIconSuffix] + '">';
				template = template.replace(/{icon_svg_tab_is_active}/g, 'is-active');
				template = template.replace(/{dashicon_tab_is_active}/g, '');
			} else {
				icon = '<i class="dashicons ' + menu['dashicon' + menuIconSuffix] + '"></i>';
				template = template.replace(/{icon_svg_tab_is_active}/g, '');
				template = template.replace(/{dashicon_tab_is_active}/g, 'is-active');
			}

			template = template.replace(/{menu_icon}/g, icon);

			if (menu.submenu) {
				submenuTemplate = buildSubmenu(by, value, menu);
				template = template.replace(/{submenu_template}/g, submenuTemplate);
			} else {
				template = template.replace(/{submenu_template}/g, '');
			}
		}

		if (by === 'role') {
			template = template.replace(/{role}/g, value);
		} else if (by === 'user_id') {
			template = template.replace(/{role}/g, 'user-' + value);
			template = template.replace(/{user_id}/g, value);
		}

		return template;
	}

	/**
	 * Build submenu list.
	 *
	 * @param {string} by The identifier, could be "role" or "user_id".
	 * @param {string} value The specified role or user id.
	 * @param {array} menu The menu item which contains the submenu list.
	 *
	 * @return {string} template The submenu template.
	 */
	function buildSubmenu(by, value, menu) {
		var template = '';

		menu.submenu.forEach(function (submenu) {
			template += replaceSubmenuPlaceholders(by, value, submenu, menu);
		});

		return template;
	}

	/**
	 * Replace submenu placeholders.
	 *
	 * @param {string} by Either by role or user_id.
	 * @param {string} value The role or user_id value.
	 * @param {object} submenu The submenu item.
	 * @param {array} menu The menu item which contains the submenu list.
	 */
	function replaceSubmenuPlaceholders(by, value, submenu, menu) {
		var template = udbAdminMenu.templates.submenuList;

		if (by === 'role') {
			template = template.replace(/{role}/g, value);
		} else if (by === 'user_id') {
			template = template.replace(/{role}/g, 'user-' + value);
			template = template.replace(/{user_id}/g, value);
		}

		template = template.replace(/{default_menu_id}/g, (menu ? menu.id_default : submenu.id));

		var submenuId = submenu.id ? submenu.id : submenu.url_default;
		submenuId = submenuId.replace(/\//g, 'udbslashsign');
		template = template.replace(/{submenu_id}/g, submenuId);

		template = template.replace(/{submenu_title}/g, submenu.title);
		template = template.replace(/{default_submenu_title}/g, submenu.title_default);

		var parsedTitle = submenu.title ? submenu.title : submenu.title_default;
		template = template.replace(/{parsed_submenu_title}/g, parsedTitle);

		template = template.replace(/{submenu_url}/g, submenu.url);
		template = template.replace(/{default_submenu_url}/g, submenu.url_default);

		template = template.replace(/{submenu_is_hidden}/g, submenu.is_hidden);
		template = template.replace(/{trash_icon}/g, (parseInt(submenu.was_added, 10) ? '<span class="dashicons dashicons-trash udb-admin-menu--remove-menu-item"></span>' : ''));
		template = template.replace(/{hidden_icon}/g, (submenu.is_hidden == '1' ? 'hidden' : 'visibility'));
		template = template.replace(/{submenu_was_added}/g, submenu.was_added);

		return template;
	}

	/**
	 * Setup menu items.
	 */
	function setupMenuItems(listArea, isSubmenu) {
		setupSortable(listArea);

		if (!isSubmenu) {
			setupItemChanges(listArea);
			$(listArea).find('.dashicons-picker').dashiconsPicker();
		}
	}

	/**
	 * Setup individual menu item.
	 */
	function setupMenuItem(menuItem, isSubmenu) {
		setupSortable(menuItem.parentNode);

		if (!isSubmenu) {
			setupItemChange(menuItem);
			$(menuItem).find('.dashicons-picker').dashiconsPicker();
		}
	}

	/**
	 * Sortable setup for both active & available widgets.
	 */
	function setupSortable(listArea) {
		$(listArea).sortable({
			receive: function (e, ui) {
				//
			},
			update: function (e, ui) {
				//
			}
		});
	}

	/**
	 * Expand / collapse menu item.
	 * @param {Event} e The event object.
	 */
	function expandCollapseMenuItem(e) {
		var parent = this.parentNode.parentNode;
		var target = parent.querySelector('.udb-admin-menu--expanded-panel');

		if (parent.classList.contains('is-expanded')) {
			$(target).stop().slideUp(350, function () {
				parent.classList.remove('is-expanded');
			});
		} else {
			$(target).stop().slideDown(350, function () {
				parent.classList.add('is-expanded');
			});
		}
	}

	/**
	 * show / hide menu item.
	 *
	 * @param {Event} listArea The event object.
	 */
	function showHideMenuItem(e) {
		var parent = this.parentNode.parentNode.parentNode;
		var isHidden = parent.dataset.hidden === '1' ? true : false;

		if (isHidden) {
			this.classList.add('dashicons-visibility');
			this.classList.remove('dashicons-hidden');
			parent.dataset.hidden = 0;
		} else {
			parent.dataset.hidden = 1;
			this.classList.remove('dashicons-visibility');
			this.classList.add('dashicons-hidden');
		}
	}

	/**
	 * Setup item changes.
	 * @param {HTMLElement} listArea The list area element.
	 */
	function setupItemChanges(listArea) {
		var menuItems = listArea.querySelectorAll('.udb-admin-menu--menu-item');
		if (!menuItems.length) return;

		menuItems.forEach(function (menuItem) {
			setupItemChange(menuItem);
		});
	}

	/**
	 * Setup item change.
	 * @param {HTMLElement} menuItem The menu item element.
	 */
	function setupItemChange(menuItem) {
		var iconFields = menuItem.querySelectorAll('.udb-admin-menu--icon-field');
		iconFields = iconFields.length ? iconFields : [];

		iconFields.forEach(function (field) {
			field.addEventListener('change', function () {
				var iconWrapper = menuItem.querySelector('.udb-admin-menu--menu-icon');
				var iconOutput;

				if (this.dataset.name === 'dashicon') {
					iconOutput = '<i class="dashicons ' + this.value + '"></i>';
				} else if (this.dataset.name === 'icon_svg') {
					iconOutput = '<img alt="" src="' + this.value + '">';
				}

				iconWrapper.innerHTML = iconOutput;
			});
		});

		var titleFields = menuItem.querySelectorAll('[data-name="menu_title"]');
		titleFields = titleFields.length ? titleFields : [];

		titleFields.forEach(function (field) {
			field.addEventListener('change', function () {
				menuItem.querySelector('.udb-admin-menu--menu-name').innerHTML = this.value;
			});
		});
	}

	loading.start = function (button) {
		button.classList.add('is-loading');
	};

	loading.stop = function (button) {
		button.classList.remove('is-loading');
	};

	/**
	 * Function to execute on form submission.
	 *
	 * @param {Event} e The on submit event.
	 */
	function submitForm(e) {
		e.preventDefault();

		var menuArray = {};
		var workspaces = this.querySelectorAll('.udb-admin-menu--workspace');

		// The "udb-admin-menu--workspace" class is not exists in UDB free version 3.1.3 and below.
		if (!workspaces.length) {
			workspaces = this.querySelectorAll('.udb-admin-menu--role-workspace');
		}

		if (!workspaces.length) return;

		workspaces.forEach(function (workspace) {
			var menuList = [];

			var menuItems = document.querySelectorAll('#' + workspace.id + ' > .udb-admin-menu--menu-list > .udb-admin-menu--menu-item');
			menuItems = menuItems.length ? menuItems : [];

			menuItems.forEach(function (menuItem) {
				var menuData = {};

				menuData.type = menuItem.classList.contains('udb-admin-menu--separator-item') ? 'separator' : 'menu';
				menuData.is_hidden = menuItem.dataset.hidden;
				menuData.was_added = menuItem.dataset.added;

				menuData.id = '';
				menuData.class = '';
				menuData.url_default = menuItem.dataset.defaultUrl;

				if (menuData.type === 'separator') {
					menuData.id_default = '';
					menuData.title = '';
					menuData.url = '';
					menuData.dashicon = '';
					menuData.icon_svg = '';
					menuData.icon_type = '';
				} else {
					menuData.id_default = menuItem.dataset.defaultId;
					menuData.title = menuItem.querySelector('[data-name="menu_title"]').value;
					menuData.url = '';

					// The menu_url didn't exist in v3.1.3 and below.
					if (menuItem.querySelector('[data-name="menu_url"]')) {
						menuData.url = menuItem.querySelector('[data-name="menu_url"]').value;
					}

					menuData.dashicon = menuItem.querySelector('[data-name="dashicon"]').value;
					menuData.icon_svg = menuItem.querySelector('[data-name="icon_svg"]').value;
					menuData.icon_type = '';

					var iconSvgTab = menuItem.querySelector('[data-tab-name="icon_svg"]');

					if (menuData.dashicon || menuData.icon_svg) {
						menuData.icon_type = 'dashicon';

						if (iconSvgTab.classList.contains('is-active')) {
							if (menuData.icon_svg) {
								menuData.icon_type = 'icon_svg';
							}
						}
					}

					if (parseInt(menuItem.dataset.added, 10)) {
						menuData.id = menuItem.dataset.defaultId;
						menuData.class_default = 'menu-top menu-icon-custom udb-menu-top udb-menu-icon-custom';
					}
				}

				var submenuItems = menuItem.querySelectorAll('.udb-admin-menu--submenu-item');
				submenuItems = submenuItems.length ? submenuItems : [];
				var submenuList = [];

				submenuItems.forEach(function (submenuItem) {
					var submenuData = {};

					submenuData.is_hidden = submenuItem.dataset.hidden;
					submenuData.was_added = submenuItem.dataset.added;
					submenuData.title = submenuItem.querySelector('[data-name="submenu_title"]').value;
					submenuData.url = '';

					// The submenu_url didn't exist in v3.1.3 and below.
					if (submenuItem.querySelector('[data-name="submenu_url"]')) {
						submenuData.url = submenuItem.querySelector('[data-name="submenu_url"]').value;
					}

					submenuData.url_default = submenuItem.dataset.defaultUrl;

					submenuList.push(submenuData);
				});

				if (submenuList) menuData.submenu = submenuList;
				menuList.push(menuData);
			});

			if (workspace.dataset.userId) {
				menuArray['user_id_' + workspace.dataset.userId] = menuList;
			} else {
				menuArray[workspace.dataset.role] = menuList;
			}
		});

		saveMenu(menuArray);
	}

	/**
	 * Send ajax request to save the menu list.
	 *
	 * @param {array} menuArray The menu array.
	 */
	function saveMenu(menuArray) {
		if (state.isSaving) return;
		state.isSaving = true;

		loading.start(elms.saveButton);

		$.ajax({
			url: ajaxurl,
			type: 'post',
			dataType: 'json',
			data: {
				action: 'udb_admin_menu_save_menu',
				nonce: udbAdminMenu.nonces.saveMenu,
				menu: JSON.stringify(menuArray)
			}
		}).done(function (r) {
			location.reload();
		}).always(function () {
			loading.stop(elms.saveButton);
			state.isSaving = false;
		});
	}

	/**
	 * Send ajax request to reset the menu list.
	 */
	function resetMenu() {
		var button = this;
		var role = this.dataset.role;

		if (state.isSaving) return;
		state.isSaving = true;

		loading.start(button);

		$.ajax({
			url: ajaxurl,
			type: 'post',
			dataType: 'json',
			data: {
				action: 'udb_admin_menu_reset_menu',
				nonce: udbAdminMenu.nonces.resetMenu,
				role: role
			}
		}).done(function (r) {
			location.reload();
		}).always(function () {
			loading.stop(button);
			state.isSaving = false;
		});
	}

	init();
})(jQuery);