(function ($) {
    "use strict";
    jQuery(document).ready(function () {
        wutb_update();
    });

    jQuery.fn.extend({
        animateCss: function (animationName, callback) {
            var animationEnd = (function (el) {
                var animations = {
                    animation: 'animationend',
                    OAnimation: 'oAnimationEnd',
                    MozAnimation: 'mozAnimationEnd',
                    WebkitAnimation: 'webkitAnimationEnd',
                };

                for (var t in animations) {
                    if (el.style[t] !== undefined) {
                        return animations[t];
                    }
                }
            })(document.createElement('div'));

            this.addClass('animated ' + animationName).one(animationEnd, function () {
                jQuery(this).removeClass('animated ' + animationName);

                if (typeof callback === 'function')
                    callback();
            });

            return this;
        },
    });
    function wutb_update() {
        wutb_initFields();
        wutb_initLayoutPanels();
    }

    function wutb_initLayoutPanels() {
    }
    function wutb_initFields() {

        jQuery(':not(.wutb-radio)> input[type="radio"]').parent().append('<span class="form-check-label"></span>');
        jQuery(':not(.wutb-radio) > input[type="radio"]').each(function () {
            if (!jQuery(this).parent().is('label')) {
                var label = jQuery('<label></label>');
                label.attr('class', jQuery(this).parent().attr('class'));
                jQuery(this).parent().after(label);
                jQuery(this).parent().children().each(function () {
                    var label = jQuery(this).parent().next('label');
                    jQuery(this).detach().appendTo(label);
                });
                jQuery(this).parent().prev('div').remove();
            }
            if (jQuery(this).parent().children('label').length > 0) {
                jQuery(this).parent().children('span').html(jQuery(this).parent().children('label').html());
                jQuery(this).parent().children('label').remove();
            }
        });
        jQuery(':not(.wutb-radio)> input[type="radio"]').parent().addClass('wutb-radio');

        jQuery(':not(.wutb-checkbox)> input[type="checkbox"]:not(.switch)').parent().append('<span class="form-check-label"></span>');
        jQuery(':not(.wutb-checkbox) > input[type="checkbox"]:not(.switch)').each(function () {
            if (!jQuery(this).parent().is('label')) {
                var parent = jQuery(this).parent();
                var label = jQuery('<label></label>');
                var container = jQuery('<div></div>');
                container.attr('class', jQuery(this).parent().attr('class'));
                container.append(label);
                jQuery(this).parent().after(container);
                jQuery(this).parent().children().each(function () {
                    jQuery(this).detach().appendTo(label);
                });
                parent.remove();
            }
            if (jQuery(this).parent().children('label').length > 0) {
                jQuery(this).parent().children('span').html(jQuery(this).parent().children('label').html());
                jQuery(this).parent().children('label').remove();
            }
        });
        jQuery(':not(.wutb-checkbox)> input[type="checkbox"]:not(.switch)').parent().addClass('wutb-checkbox');

        jQuery('input.switch').each(function () {
            if (!jQuery(this).is('[id]')) {
                jQuery(this).attr('id', Math.random().toString(36).substr(2, 9));
            }
            if (jQuery(this).parent().children('label').length > 0) {
                jQuery(this).parent().children('label').addClass('switch-label');
                jQuery(this).parent().children('label').attr('for', jQuery(this).attr('id'));
            } else {
                jQuery(this).after('<label class="switch-label" for="' + jQuery(this).attr('id') + '"></label>');
            }
            if (jQuery(this).is('.switch-lg')) {
                jQuery(this).parent().children('label').addClass('switch-lg');
            }
        });
    }
})(jQuery);

(function ($) {
    "use strict";
    function layoutPanel(element, options) {
        var defaults = {
            style: 'primary'
        };
        var plugin = this;
        this.$element = $(element);

        if (!$.isPlainObject(options)) {
            options = {
                leftWidth: 240,
                rightWidth: 240,
                topHeight: 80,
                bottomHeight: 180,
                onMinify: function () {},
                onMaximize: function () {},
                onUnMinify: function () {},
                onUnMaximize: function () {},
                onClose: function () {},
            };
        }
        plugin.settings = {};
        plugin.settings = $.extend({}, defaults, options);
        plugin.init();

    }
    ;

    layoutPanel.prototype.init = function () {
        var plugin = this;
        var layoutPanel;

        this.$element.children('[class^="panel-"]').each(function () {
            if (jQuery(this).children('.panel-header').length > 0) {
                jQuery(this).children('.panel-header').html('<div class="panel-header-title">' + jQuery(this).children('.panel-header').html() + '</div>');
                if (jQuery(this).is('[data-buttons]') && !jQuery(this).is('.panel-center')) {
                    var buttons = new Array();
                    if (jQuery(this).attr('data-buttons').indexOf(',') < 0) {
                        buttons.push(jQuery(this).attr('data-buttons'));
                    } else {
                        buttons = jQuery(this).attr('data-buttons').split(',');
                    }
                    var btnMin = false;
                    var btnMax = false;
                    var btnClose = false;
                    jQuery.each(buttons, function () {
                        if (this == 'minify') {
                            btnMin = true;
                        } else if (this == 'maximize') {
                            btnMax = true;
                        } else if (this == 'close') {
                            btnClose = true;
                        }
                    });
                    if (jQuery(this).children('.panel-body').length == 0) {
                        btnMax = false;
                        btnMin = false;
                    }
                    var container = jQuery(this).children('.panel-header');
                    if (jQuery(this).children('.panel-header').length == 0) {
                        container = jQuery(this).children('.panel-body');
                    }
                    container.prepend('<div class="panel-buttons"></div>');

                    var btnClass = 'btn-primary';
                    if (container.is('.bg-secondary')) {
                        btnClass = 'btn-primary';
                    } else if (container.is('.bg-success')) {
                        btnClass = 'btn-warning';
                    } else if (container.is('.bg-warning')) {
                        btnClass = 'btn-success';
                    } else if (container.is('.bg-info')) {
                        btnClass = 'btn-dark';
                    } else if (container.is('.bg-light')) {
                        btnClass = 'btn-dark';
                    } else if (container.is('.bg-dark')) {
                        btnClass = 'btn-light';
                    }
                    var minIconClass = 'fa-chevron-left';
                    if (jQuery(this).is('.panel-bottom') || jQuery(this).is('.panel-top')) {
                        minIconClass = 'fa-minus';
                    } else if (jQuery(this).is('.panel-right')) {
                        minIconClass = 'fa-chevron-right';
                    }
                    var maxIconClass = 'fa-window-maximize';
                    var closeIconClass = 'fa-times';

                    if (btnMin) {
                        container.find('.panel-buttons').append('<a href="javascript:" class="btn ' + btnClass + '" data-action="minify"><span class="fa ' + minIconClass + '"></span></a>');
                    }
                    if (btnMax) {
                        container.find('.panel-buttons').append('<a href="javascript:" class="btn ' + btnClass + '" data-action="maximize"><span class="fa ' + maxIconClass + '"></span></a>');
                    }
                    if (btnClose) {
                        container.find('.panel-buttons').append('<a href="javascript:" class="btn ' + btnClass + '" data-action="close"><span class="fa ' + closeIconClass + '"></span></a>');
                    }
                }
                var panel = this;
                jQuery(this).find('.btn[data-action="minify"]').click(function () {
                    plugin.togglePanel(panel, plugin);
                });
                jQuery(this).find('.btn[data-action="close"]').click(function () {
                    plugin.closePanel(panel, plugin);
                });
                jQuery(this).find('.btn[data-action="maximize"]').click(function () {
                    plugin.toggleMaximizePanel(panel, plugin);
                });

                if (jQuery(this).is('.minified')) {
                    plugin.minifyPanel(panel, plugin);
                }
                if (jQuery(this).is('.maximized')) {
                    plugin.maximizePanel(panel, plugin);
                }
            }

        });

        if (this.$element.children('.panel-left[data-width]').length > 0) {
            plugin.settings.leftWidth = parseInt(this.$element.children('.panel-left').attr('data-width'));
        }
        if (this.$element.children('.panel-right[data-width]').length > 0) {
            plugin.settings.rightWidth = parseInt(this.$element.children('.panel-right').attr('data-width'));
        }
        if (this.$element.children('.panel-top[data-height]').length > 0) {
            plugin.settings.topHeight = parseInt(this.$element.children('.panel-top').attr('data-height'));
        }
        if (this.$element.children('.panel-bottom[data-height]').length > 0) {
            plugin.settings.bottomHeight = parseInt(this.$element.children('.panel-bottom').attr('data-height'));
        }

        this.$element.addClass('wutb-initialized');
        jQuery(window).on('resize', function () {
            plugin.resizeLayoutPanel(plugin.$element, plugin);
        });
        this.resizeLayoutPanel(plugin.$element, plugin);
        var _this = this;
        setTimeout(function () {
            _this.$element.animate({opacity: 1}, 250);
        }, 350);
    };

    layoutPanel.prototype.resizeLayoutPanel = function (layoutPanel, plugin) {
        if (jQuery(window).width() > 768) {
            var panelBottomHeight = 0;
            if (jQuery(layoutPanel).children('.panel-bottom:not(.hidden)').children('.panel-header').length > 0) {
                if (jQuery(layoutPanel).children('.panel-bottom:not(.hidden)').children('.panel-body').length > 0) {
                    panelBottomHeight = plugin.settings.bottomHeight + jQuery(layoutPanel).children('.panel-bottom:not(.hidden)').children('.panel-header').outerHeight();
                } else {
                    panelBottomHeight = jQuery(layoutPanel).children('.panel-bottom:not(.hidden)').children('.panel-header').outerHeight();
                }
            } else if (jQuery(layoutPanel).children('.panel-bottom:not(.hidden)').length > 0) {
                panelBottomHeight = plugin.settings.bottomHeight;
            }
            if (jQuery(layoutPanel).children('.panel-bottom:not(.hidden).minified').length > 0) {
                panelBottomHeight = 48;
            }
            if (jQuery(layoutPanel).children('.panel-bottom:not(.hidden).maximized').length > 0) {
                //    panelBottomHeight = 0;
            }
            var panelTopHeight = 0;
            if (jQuery(layoutPanel).children('.panel-top:not(.hidden)').children('.panel-header').length > 0) {
                if (jQuery(layoutPanel).children('.panel-top:not(.hidden)').children('.panel-body').length > 0) {
                    panelTopHeight = jQuery(layoutPanel).children('.panel-top:not(.hidden)').children('.panel-body').outerHeight() + jQuery(layoutPanel).children('.panel-top:not(.hidden)').children('.panel-header').outerHeight();
                } else {
                    panelTopHeight = jQuery(layoutPanel).children('.panel-top:not(.hidden)').children('.panel-header').outerHeight();
                }
            } else if (jQuery(layoutPanel).children('.panel-top:not(.hidden)').length > 0) {
                panelTopHeight = plugin.settings.topHeight;
            }
            if (jQuery(layoutPanel).children('.panel-top:not(.hidden).minified').length > 0) {
                panelTopHeight = 48;
            }
            if (jQuery(layoutPanel).children('.panel-top:not(.hidden).maximized').length > 0) {
                // panelTopHeight = 0;
            }
            var panelLeftWidth = jQuery(layoutPanel).children('.panel-left:not(.hidden)').outerWidth();
            panelLeftWidth = plugin.settings.leftWidth;
            if (jQuery(layoutPanel).children('.panel-left:not(.hidden).minified').length > 0) {
                panelLeftWidth = 52;
            }
            if (jQuery(layoutPanel).children('.panel-left:not(.hidden).maximized').length > 0 || jQuery(layoutPanel).children('.panel-left.hidden').length > 0) {
                panelLeftWidth = 0;
            }
            if (jQuery(layoutPanel).children('.panel-left').length == 0) {
                panelLeftWidth = 0;
            }
            var panelRightWidth = jQuery(layoutPanel).children('.panel-right:not(.hidden)').outerWidth();
            panelRightWidth = plugin.settings.rightWidth;
            if (jQuery(layoutPanel).children('.panel-right:not(.hidden).minified').length > 0) {
                panelRightWidth = 52;
            }
            if (jQuery(layoutPanel).children('.panel-right:not(.hidden).maximized').length > 0 || jQuery(layoutPanel).children('.panel-right.hidden').length > 0) {
                panelRightWidth = 0;
            }
            if (jQuery(layoutPanel).children('.panel-right').length == 0) {
                panelRightWidth = 0;
            }

            jQuery(layoutPanel).children('.panel-left').children('.panel-body').css({
                height: jQuery(layoutPanel).height() - (panelTopHeight + panelBottomHeight + jQuery(layoutPanel).children('.panel-left:not(.hidden)').children('.panel-header').outerHeight())
            });
            jQuery(layoutPanel).children('.panel-right').children('.panel-body').find('.mCustomScrollbar').css({
                height: jQuery(layoutPanel).height() - (panelTopHeight + panelBottomHeight + jQuery(layoutPanel).children('.panel-right:not(.hidden)').children('.panel-header').outerHeight())
            });
            jQuery(layoutPanel).children('.panel-left,.panel-right').css({
                top: panelTopHeight
            });

            jQuery(layoutPanel).children('.panel-center').css({
                top: panelTopHeight,
                left: panelLeftWidth,
                right: panelRightWidth
            });
            var centerBodyHeight = jQuery(layoutPanel).height() - (panelTopHeight + panelBottomHeight);
            if (jQuery(layoutPanel).children('.panel-center:not(.hidden)').children('.panel-header').length > 0) {
                centerBodyHeight = jQuery(layoutPanel).height() - (panelTopHeight + panelBottomHeight + jQuery(layoutPanel).children('.panel-center:not(.hidden)').children('.panel-header').outerHeight());
            }
            jQuery(layoutPanel).children('.panel-center').children('.panel-body').css({
                height: centerBodyHeight

            });
            jQuery(layoutPanel).children('.panel-bottom:not(.hidden).maximized').children('.panel-body').css({
                height: jQuery(layoutPanel).height() - (panelTopHeight + jQuery(layoutPanel).children('.panel-bottom:not(.hidden).maximized').children('.panel-header').outerHeight())
            });
            jQuery(layoutPanel).children('.panel-bottom:not(.hidden):not(.maximized)').children('.panel-body').css({
                height: plugin.settings.bottomHeight
            });

            jQuery(layoutPanel).children('.panel-top:not(.hidden).maximized').children('.panel-body').css({
                height: jQuery(layoutPanel).height() - (jQuery(layoutPanel).children('.panel-top:not(.hidden).maximized').children('.panel-header').outerHeight())
            });
            jQuery(layoutPanel).children('.panel-top:not(.hidden):not(.maximized)').children('.panel-body').css({
                height: plugin.settings.topHeight
            });
            if (plugin.$element.is('.row')) {
                plugin.$element.removeClass('row');
                plugin.$element.children('.panel-left,.panel-right').removeClass('col-md-3').removeClass('col-md-4');
                plugin.$element.children('.panel-center').removeClass('col-md-12').removeClass('col-md-8').removeClass('col-md-6');
            }

        } else {
            plugin.$element.addClass('row');
            var classColLeft = 'col-md-3';
            var classColRight = 'col-md-3';
            var classColCenter = 'col-md-6';
            if (plugin.$element.children('.panel-left').length == 0 && plugin.$element.children('.panel-right').length == 0) {
                classColCenter = 'col-md-12';
            } else if (plugin.$element.children('.panel-left').length > 0 && plugin.$element.children('.panel-right').length == 0) {
                classColLeft = 'col-md-4';
                classColCenter = 'col-md-8';
            } else if (plugin.$element.children('.panel-right').length > 0 && plugin.$element.children('.panel-left').length == 0) {
                classColRight = 'col-md-4';
                classColCenter = 'col-md-8';
            }
            plugin.$element.children('.panel-left').addClass(classColLeft);
            plugin.$element.children('.panel-right').addClass(classColRight);
            plugin.$element.children('.panel-center').addClass(classColCenter);
        }
    };


    layoutPanel.prototype.showPanel = function (panel, plugin) {
        jQuery(panel).fadeIn(250);
        jQuery(panel).removeClass('hidden');
        setTimeout(function () {
            plugin.unminifyPanel(panel, plugin);
        }, 300);
    };

    layoutPanel.prototype.unminifyPanel = function (panel, plugin) {
        jQuery(panel).removeClass('minified');
        var minIconClass = 'fa-chevron-left';
        if (jQuery(panel).is('.panel-bottom') || jQuery(panel).is('.panel-top')) {
            minIconClass = 'fa-minus';
        } else if (jQuery(panel).is('.panel-right')) {
            minIconClass = 'fa-chevron-right';
        }
        jQuery(panel).find('.btn[data-action="minify"] .fa').attr('class', 'fa ' + minIconClass);
        jQuery(panel).css({backgroundColor: 'transparent'});

        plugin.resizeLayoutPanel(panel.closest('.layout-panel'), plugin);
        setTimeout(function () {
            plugin.resizeLayoutPanel(panel.closest('.layout-panel'), plugin);
        }, 300);
        if (typeof (plugin.settings.onUnMinify) != 'undefined') {
            plugin.settings.onUnMinify();
        }
    };
    layoutPanel.prototype.minifyPanel = function (panel, plugin) {
        if (jQuery(panel).is('.maximized')) {
            plugin.unmaximizePanel(panel, plugin);
        }
        jQuery(panel).addClass('minified');
        var minIconClass = 'fa-chevron-right';
        if (jQuery(panel).is('.panel-bottom') || jQuery(panel).is('.panel-top')) {
            minIconClass = 'fa-window-restore';
        } else if (jQuery(panel).is('.panel-right')) {
            minIconClass = 'fa-chevron-left';
        }
        jQuery(panel).find('.btn[data-action="minify"] .fa').attr('class', 'fa ' + minIconClass);
        var bgColor = jQuery(panel).children('.panel-body').css('background-color');
        if (jQuery(panel).children('.panel-header').length > 0) {
            bgColor = jQuery(panel).children('.panel-header').css('background-color');
        }
        jQuery(panel).css({backgroundColor: bgColor});
        plugin.resizeLayoutPanel(panel.closest('.layout-panel'), plugin);
        if (jQuery(panel).is('.panel-left') || jQuery(panel).is('.panel-right')) {
            if (jQuery(panel).children('.panel-header').length > 0 && jQuery(panel).children('.panel-header').children('.panel-header-title').length > 0) {
                jQuery(panel).children('.panel-header').children('.panel-header-title').fadeOut(100);
            }
        }
        setTimeout(function () {
            plugin.resizeLayoutPanel(jQuery(panel).closest('.layout-panel'), plugin);
            if (jQuery(panel).children('.panel-header').length > 0 && jQuery(panel).children('.panel-header').children('.panel-header-title').length > 0) {
                jQuery(panel).children('.panel-header').children('.panel-header-title').fadeIn(100);
            }
        }, 360);
        if (typeof (plugin.settings.onMinify) != 'undefined') {
            plugin.settings.onMinify();
        }
    };

    layoutPanel.prototype.toggleMaximizePanel = function (panel, plugin) {
        if (!jQuery(panel).is('.maximized')) {
            plugin.maximizePanel(panel, plugin);
        } else {
            plugin.unmaximizePanel(panel, plugin);
        }
    };

    layoutPanel.prototype.maximizePanel = function (panel, plugin) {
        if (jQuery(panel).closest('.layout-panel').children('.maximized')) {
            plugin.unmaximizePanel(jQuery(panel).closest('.layout-panel').children('.maximized'), plugin);
        }
        if (jQuery(panel).is('.minified')) {
            plugin.unminifyPanel(panel, plugin);
        }
        jQuery(panel).addClass('maximized');
        plugin.resizeLayoutPanel(panel.closest('.layout-panel'), plugin);
        var maxIconClass = 'fa-window-restore';
        jQuery(panel).find('.btn[data-action="maximize"] .fa').attr('class', 'fa ' + maxIconClass);

        if (jQuery(panel).is('.panel-left') || jQuery(panel).is('.panel-right')) {
            if (jQuery(panel).closest('.layout-panel').children('.panel-bottom:not(.hidden):not(.minified)').length > 0) {
                var bottomPanel = jQuery(panel).closest('.layout-panel').children('.panel-bottom:not(.hidden):not(.minified)');
                if (bottomPanel.children('.panel-header').length > 0 && bottomPanel.children('.panel-header').children('.panel-buttons').length > 0 && bottomPanel.children('.panel-header').children('.panel-buttons').children('a[data-action="minify"]').length > 0) {
                    plugin.minifyPanel(bottomPanel, plugin);
                }
            }
        }
        if (typeof (plugin.settings.onMaximize) != 'undefined') {
            plugin.settings.onMaximize();
        }
    };
    layoutPanel.prototype.unmaximizePanel = function (panel, plugin) {
        jQuery(panel).removeClass('maximized');
        plugin.resizeLayoutPanel(panel.closest('.layout-panel'), plugin);
        var maxIconClass = 'fa-window-maximize';
        jQuery(panel).find('.btn[data-action="maximize"] .fa').attr('class', 'fa ' + maxIconClass);

        if (typeof (plugin.settings.onUnMaximize) != 'undefined') {
            plugin.settings.onUnMaximize();
        }
    };
    layoutPanel.prototype.closePanel = function (panel, plugin) {
        jQuery(panel).addClass('hidden');
        plugin.minifyPanel(panel, plugin);
        if (jQuery(panel).is('.panel-left')) {
            jQuery(panel).closest('.layout-panel').children('.panel-center').css({
                left: 0
            });
        } else if (jQuery(panel).is('.panel-right')) {
            jQuery(panel).closest('.layout-panel').children('.panel-center').css({
                right: 0
            });
        } else if (jQuery(panel).is('.panel-bottom')) {
            jQuery(panel).closest('.layout-panel').children('.panel-center').css({
                bottom: 0
            });
        } else if (jQuery(panel).is('.panel-top')) {
            jQuery(panel).closest('.layout-panel').children('.panel-center').css({
                top: 0
            });
        }
        setTimeout(function () {
            jQuery(panel).fadeOut(250);
        }, 300);
        if (typeof (plugin.settings.onClose) != 'undefined') {
            plugin.settings.onClose();
        }
    };
    layoutPanel.prototype.togglePanel = function (panel, plugin) {
        if (!jQuery(panel).is('.minified')) {
            plugin.minifyPanel(panel, plugin);
        } else {
            plugin.unminifyPanel(panel, plugin);
        }
    };

    $.fn.layoutPanel = function (options) {
        return this.each(function () {
            var plugin = new layoutPanel(this, options);
            $(this).data('layoutPanel', plugin);
        });
    };

})(jQuery);

(function ($) {
    "use strict";
    function notification(element, options) {
        var defaults = {
            autoClose: true,
            closeDelay: 6000,
            buttons: null,
            closable: 'click',
            id: false,
            html: '',
            icon: 'fa-info',
            onShow: function () {},
            onClose: function () {},
            position: 'topRight',
            title: null,
            titleIcon: null,
            style: 'primary'
        };

        var plugin = this;
        this.$element = $(element);
        plugin.timerProgress = false;

        if (!$.isPlainObject(options)) {
            options = {
                html: options
            };
        }
        plugin.settings = {};
        plugin.settings = $.extend({}, defaults, options);

        if ((plugin.settings.id) && (plugin.$element.find('#' + plugin.settings.id).length > 0)) {
        } else {
            plugin.init();
        }
    }
    ;

    notification.prototype.init = function () {
        var plugin = this;
        var notice, header = false, content = false, closeBtn = false, icon = false, buttons = false, headerheight = 0;


        notice = $('<div>');
        notice.addClass('wutb-notification');
        notice.data('position', plugin.settings.position);
        notice.data('closeDelay', plugin.settings.closeDelay);
        if (plugin.settings.id) {
            notice.prop('id', plugin.settings.id);
        }

        if ((plugin.settings.titleIcon) || (plugin.settings.title)) {
        } else {
            notice.addClass('bg-' + plugin.settings.style);
            notice.addClass('notification-noheader');
        }
        if (plugin.$element.get(0).tagName == 'BODY') {
            notice.css({
                position: 'fixed'
            });
        } else if (plugin.$element.css('position') == 'static') {
            plugin.$element.css('position', 'relative');
        }

        if (plugin.settings.closable) {
            closeBtn = $('<a href="javascript:" class="notification-close"><span class="fa fa-times"></span></a>');
            closeBtn.on('click', function () {
                plugin.close(notice);
                setTimeout(function () {
                    plugin.settings.onClose();
                }, 350);
            });
        }

        if ((plugin.settings.titleIcon) || (plugin.settings.title)) {

            header = $('<div class="notification-header"></div>');
            header.addClass('bg-' + plugin.settings.style);
            if (plugin.settings.titleIcon) {
                if (plugin.settings.titleIcon.indexOf('.') > 0) {
                    header.append('<img src="' + plugin.settings.titleIcon + '" alt="' + plugin.settings.title + '" class="icon" />');
                } else {
                    header.append('<div class="fa ' + plugin.settings.titleIcon + '"></div>');
                }
                if (!plugin.settings.title) {
                    header.append('<h3>&nbsp;</h3>');
                }
            }
            if (closeBtn) {
                header.append(closeBtn);
            }

            if (plugin.settings.title) {
                header.append('<div class="notification-title">' + plugin.settings.title + '</div>');
            }
            notice.append(header);
        }

        content = $('<div class="notification-content"></div>');

        if (header) {
            content.css({
                borderTopLeftRadius: 0,
                WebkitBorderTopLeftRadius: 0,
                MozBorderTopLeftRadius: 0,
                borderTopRightRadius: 0,
                WebkitBorderTopRightRadius: 0,
                MozBorderTopRightRadius: 0
            });
        }

        content.append('<table class="notification-content-table"><tr><td>' + plugin.settings.html + '</td></tr></table>');

        if (plugin.settings.buttons) {
            buttons = $('<div class="notification-buttons"></div>');
            $.each(plugin.settings.buttons, function (i, btnData) {
                var href = 'javascript:';
                var target = '_self';
                var click = '';
                if (btnData.href) {
                    href = btnData.href;
                }
                if (btnData.target) {
                    target = btnData.target;
                }
                var button = $('<a href="' + href + '" target="' + target + '" class="btn btn-' + btnData.style + '">' + btnData.label + '</a>');
                if (btnData.icon) {
                    var btnIcon = false;
                    if (btnData.icon.indexOf('.') > 0) {
                        btnIcon = $('<img src="' + btnData.icon + '" alt="' + btnData.label + '" class="" />');
                    } else
                    {
                        btnIcon = $('<div class="fa ' + btnData.icon + '"></div>');
                    }
                    button.prepend(btnIcon);
                }
                if (typeof (btnData.closeOnClick) == 'undefined' || btnData.closeOnClick) {
                    button.bind('click', function () {
                        plugin.close(notice);
                    });
                }
                if (btnData.click) {
                    button.bind('click', function () {
                        btnData.click(notice);
                    });
                }
                buttons.append(button);
            });
            content.append(buttons);
        }

        notice.append(content);
        this.$element.append(notice);

        if (plugin.settings.icon) {
            content.append('<p style="clear:both; margin: 0px;"></p>');
            if (plugin.settings.icon.indexOf('.') > 0) {
                icon = $('<img src="' + plugin.settings.icon + '" alt="' + plugin.settings.title + '" class="icon" />');
            } else {
                icon = $('<div class="icon fa ' + plugin.settings.icon + '"></div>');
            }
            content.find('.notification-content-table tr').prepend('<td class="notification-content-td-icon"></td>');
            content.find('.notification-content-td-icon').append(icon);
        }
        if (header) {
            headerheight = header.outerHeight();
        }
        if ((!header) && (closeBtn)) {
            content.prepend(closeBtn);
        }

        if (plugin.settings.autoClose) {
            notice.append('<div class="notification-progressbar"></div>');
        }

        plugin.refreshPositions();

        notice.addClass(plugin.settings.position);


        setTimeout(function () {
            notice.css({
                opacity: 1
            });
            notice.addClass('active');
            setTimeout(function () {
                plugin.refreshPositions();
                plugin.settings.onShow();
            }, 300);
        }, 100);
        setTimeout(function () {
            plugin.updateProgressBar(plugin, notice);
        }, 100);

        if (plugin.settings.autoClose) {
            notice.timer = setTimeout(function () {
                plugin.close(notice);
            }, plugin.settings.closeDelay);
        }
    };
    notification.prototype.refreshPositions = function () {
        var plugin = this;
        var positions = new Array('topLeft', 'topRight', 'bottomLeft', 'bottomRight');
        var positionLeft = 0, positionTop = 0, positionRight = 0, positionBottom = 0;

        $.each(positions, function (i, position) {
            positionLeft = 18, positionTop = 18, positionRight = 18, positionBottom = 8;
            if ($(window).width() <= 380) {
                positionTop = 0;
                positionBottom = 0;
            }
            plugin.$element.find('div.wutb-notification').each(function (j, el) {
                if ($(el).data('position') == position) {

                    if (position.substr(position.length - 5) == 'Right') {
                        positionRight = 8;
                        positionLeft = 'auto';
                    } else {
                        positionRight = 'auto';
                    }
                    if (position.substr(0, 3) == 'top') {
                        positionBottom = 'auto';
                    } else
                    {
                        positionTop = 'auto';
                    }
                    if ((position.substr(0, 3) == 'top') && (positionTop < parseInt($(el).css('top')))) {

                        $(el).css({
                            top: positionTop,
                            bottom: positionBottom,
                            left: positionLeft,
                            right: positionRight

                        });
                    } else if ((position.substr(0, 3) == 'bot') && (positionBottom < parseInt($(el).css('bottom'))))
                    {
                        $(el).css({
                            top: positionTop,
                            bottom: positionBottom,
                            left: positionLeft,
                            right: positionRight

                        });
                    } else {

                        $(el).css({
                            top: positionTop,
                            bottom: positionBottom,
                            left: positionLeft,
                            right: positionRight
                        });
                    }

                    if (position.substr(0, 3) == 'top') {
                        positionTop += $(el).height() + parseInt($(el).css('marginBottom'));
                    } else {
                        positionBottom += $(el).height() + parseInt($(el).css('marginTop'));
                    }
                }
            });
        });
    };

    notification.prototype.close = function (notice) {
        if ((notice) && (notice.length > 0)) {
            var plugin = this;
            if (!notice.jquery) {
                notice = plugin.$element.find('#' + notice);
            }
            if (notice.timer) {
                clearTimeout(notice.timer);
            }

            var headerheight = 0;
            if (notice.find('.notification-header')) {
                headerheight = notice.find('.notification-header').outerHeight();
            }
            if (plugin.timerProgress) {
                clearTimeout(plugin.timerProgress);
            }
            notice.removeClass('active');
            setTimeout(function () {
                notice.remove();
                plugin.refreshPositions();
            }, 350);
        }
    };
    notification.prototype.updateProgressBar = function (plugin, notice) {
        if (plugin.timerProgress) {
            var index = 0;
            if (typeof (notice.data('progressBarIndex')) != 'undefined') {
                index = notice.data('progressBarIndex');
            } else {
                notice.data('progressBarIndex', 0);
            }
            var ratio = notice.width() / (notice.data('closeDelay') / 100);

            var width = notice.width() - index * ratio;
            notice.find('.notification-progressbar').css({
                width: width
            });
            notice.data('progressBarIndex', notice.data('progressBarIndex') + 1);
            if (width > 0) {
                timerProgress = setTimeout(function () {
                    plugin.updateProgressBar(plugin, notice);
                }, 100);
            }
        }
    };

    notification.prototype.getNotification = function (noticeID) {
        var plugin = this;
        return plugin.$element.find('#' + noticeID);
    };

    $.fn.notification = function (options) {
        return this.each(function () {
            var plugin = new notification(this, options);
            $(this).data('notification', plugin);
        });
    };

})(jQuery);