(function ($) {
    "use strict";
    var initialBodyOverflowY = '';
    var initialHtmlOverflowY = '';
    var selectionInProgress = false;
    var lastSelectedElement = false;
    var currentStep = false;
    var currentTour = false;

    wutb_toursData = wutb_toursData[0];
    for (var i = 0; i < wutb_toursData.tours.length; i++) {
        wutb_toursData.tours[i].tourData = JSON.parse(wutb_toursData.tours[i].tourData);
    }

    $(window).load(function () {
        $('body').addClass('wutb_frontend');
        $('body').on('triggerUpdateStepSetting', updateStepSetting);
        $('body').on('wutb_triggerAction', executePreviewAction);
        $('body').on('wutb_startTour', function () {
            var tourID = $('body').attr('data-starttourid');
            var tour = getTourByID(tourID);
            if (tour) {
                startTour(tour);
            }
        });
        jQuery(window).resize(onResize);

        if (wutb_toursData.previewTour > 0) {
            sessionStorage.removeItem('wutb_selection');
        }
        if (!isIframe() && sessionStorage.getItem('wutb_selection') === null || parseInt(sessionStorage.getItem('wutb_selection')) < 1) {
            initTours();
            $('.start-tour').on('click', function () {
                if (!currentTour) {
                    var classes = $(this).attr('class').split(' ');
                    for (var i = 0; i < classes.length; i++) {
                        var cssClass = classes[i];
                        if (cssClass.indexOf('tour-') == 0) {
                            var tourID = cssClass.substr(5, cssClass.length);
                            var tour = getTourByID($(this).attr('data-tourid'));
                            if (tour) {
                                startTour(tour);
                            }
                        }
                    }
                }
            });
        }
        if (isIframe()) {
            $('body').addClass('wutb_framed');
        }
        $('*').on('click', function (e) {
            if (selectionInProgress) {
                e.preventDefault();

                var self = this;
                if (jQuery(self).is('option')) {

                } else {
                    var $element = false;
                    if (jQuery(self).children().length == 0 || jQuery(self).is('.wp-menu-name') || jQuery(self).is('img') || jQuery(self).is('a') || jQuery(self).is('button') || jQuery(self).is('select') || jQuery(self).is('iframe') || jQuery(self).is('.mce-tinymce')) {

                        if (jQuery(self).is('a') && jQuery(self).find('img').length > 0) {
                            $element = jQuery(self).find('img');
                        } else {
                            $element = jQuery(self);
                        }

                    }
                    if ($element) {
                        onSelectionElementClick($element);
                    }
                }
                return false;

            } else if (!currentTour && !currentStep) {
                for (var i = 0; i < wutb_toursData.tours.length; i++) {
                    var tour = wutb_toursData.tours[i];
                    if (tour.tourData.settings.startMethod == 'elementClick') {
                        if (tour.tourData.settings.tourDomElement != '' && $(tour.tourData.settings.tourDomElement).length > 0 && $(tour.tourData.settings.tourDomElement).is($(this))) {
                            $(tour.tourData.settings.tourDomElement).attr('data-tourid', tour.id);

                            if (!tour.tourData.settings.runOnce || localStorage.getItem('wutb_viewedTour_' + tour.id) === null) {
                                e.preventDefault();
                                if (!currentTour) {
                                    var tour = getTourByID($(this).attr('data-tourid'));
                                    if (tour) {
                                        startTour(tour);
                                        return false;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        });

        $('[class^="open-tour-"],[class*=" open-tour-"]').on('click', function () {
            if (!currentTour && !currentStep) {
                var classes = $(this).attr('class').split(' ');
                for (var i = 0; i < classes.length; i++) {
                    var currentClass = classes[i];
                    if (currentClass.indexOf('open-tour-') == 0) {
                        var tourID = currentClass.substr(10, currentClass.length);
                        var tour = getTourByID(tourID);
                        if (tour) {

                            localStorage.removeItem('wutb_viewedTour_' + tourID);
                            startTour(tour);
                        }
                    }
                }
            }
        });
    });
    function onResize() {
        updateOverlay();
        updateArrow();
        updateElementText();
        updateTooltip();
        updateOverlayElement();
    }
    function getTourByID(tourID) {
        var rep = false;
        for (var i = 0; i < wutb_toursData.tours.length; i++) {
            if (wutb_toursData.tours[i].id == tourID) {
                rep = wutb_toursData.tours[i];
            }
        }
        return rep;
    }
    function getStepByID(stepID, tour) {
        var rep = false;
        for (var i = 0; i < tour.tourData.steps.length; i++) {
            if (tour.tourData.steps[i].id == stepID) {
                rep = tour.tourData.steps[i];
            }
        }
        return rep;
    }
    function getStartStepByTour(tour) {
        var rep = false;
        for (var i = 0; i < tour.tourData.steps.length; i++) {
            if (tour.tourData.steps[i].start) {
                rep = tour.tourData.steps[i];
            }
        }
        return rep;
    }
    function initTours() {
        if (wutb_toursData.previewStep > 0) {
            $('#wpadminbar').hide();
        } else if (wutb_toursData.previewTour > 0) {
            var tour = getTourByID(wutb_toursData.previewTour);
            if (tour) {
                currentTour = tour;
                startTour(tour);
            }
        } else if (sessionStorage.getItem('wutb_currentStep') !== null) {
            var stepInCache = JSON.parse(sessionStorage.getItem('wutb_currentStep'));

            currentTour = getTourByID(stepInCache.tourID);
            if (currentTour) {
                currentTour.clickedButtons = new Array();
                showStep(stepInCache);

            }
        } else if (!isIframe()) {
            for (var i = 0; i < wutb_toursData.tours.length; i++) {
                var tour = wutb_toursData.tours[i];
                if (tour.tourData.settings.activated) {

                    if (tour.tourData.settings.startMethod == 'elementClick') {
                        if (tour.tourData.settings.tourDomElement != '' && $(tour.tourData.settings.tourDomElement).length > 0) {
                            $(tour.tourData.settings.tourDomElement).attr('data-tourid', tour.id);
                            $(tour.tourData.settings.tourDomElement).on('click', function (e) {
                                var tour = getTourByID($(this).attr('data-tourid'));
                                var chk = true;
                                if (tour.tourData.settings.runOnce && localStorage.getItem('wutb_viewedTour_' + tour.id) !== null) {
                                    chk = false;
                                }
                                if (tour.tourData.settings.devices == 'mobiles' && $(window).width() > 480) {
                                    chk = false;
                                } else if (tour.tourData.settings.devices == 'computers' && $(window).width() <= 480) {
                                    chk = false;
                                }
                                if (chk) {
                                    e.preventDefault();
                                    if (!currentTour) {
                                        var tour = getTourByID($(this).attr('data-tourid'));
                                        if (tour) {
                                            startTour(tour);
                                            return false;
                                        }
                                    }
                                }
                            });
                        }
                    } else {
                        if (isCurrentUrl(tour.tourData.settings.startURL)) {
                            var chk = true;
                            if (tour.tourData.settings.runOnce && localStorage.getItem('wutb_viewedTour_' + tour.id) !== null) {
                                chk = false;
                            }
                            if (tour.tourData.settings.devices == 'mobiles' && $(window).width() > 480) {
                                chk = false;
                            } else if (tour.tourData.settings.devices == 'computers' && $(window).width() <= 480) {
                                chk = false;
                            }
                            var startStep = getStartStepByTour(tour);
                            if (startStep) {
                                var adminCheck = wutb_toursData.adminUrl.replace(wutb_toursData.siteUrl, '');
                                if (startStep.settings.url == '' && wutb_toursData.isAdmin == 1) {
                                    chk = false;
                                } else if (wutb_toursData.isAdmin == 1 && startStep.settings.url.indexOf(adminCheck) == -1) {
                                    chk = false;
                                } else if (wutb_toursData.isAdmin == 0 && startStep.settings.url.indexOf(adminCheck) > -1) {
                                    chk = false;
                                } else if (document.location.href.indexOf('wutb_menu') > -1) {
                                    chk = false;
                                }
                            } else {
                                chk = false;
                            }

                            if (chk) {
                                startTour(tour);
                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    function isCurrentUrl(tourURL) {

        var rep = false;
        var url = document.location.href;
        if (url.indexOf('&stepPreview=') > -1) {
            url = url.substr(0, wutb_toursData.siteUrl.indexOf('&stepPreview='));
        }
        if (url.indexOf('?stepPreview=') > -1) {
            url = url.substr(0, wutb_toursData.siteUrl.indexOf('?stepPreview='));
        }
        if (tourURL == '' && wutb_toursData.isAdmin == 0) {
            rep = true;
        } else if (url.indexOf(wutb_toursData.adminUrl) == 0 && wutb_toursData.isAdmin == 0) {
            rep = true;
        } else {
            if (wutb_toursData.siteUrl.indexOf('?') > -1) {
                wutb_toursData.siteUrl = wutb_toursData.siteUrl.substr(0, wutb_toursData.siteUrl.indexOf('?'));
            }
            tourURL = wutb_toursData.siteUrl + tourURL;
            if (url.indexOf('#') > 0 && url.indexOf('#post-') == -1) {
                url = document.location.href.substr(0, document.location.href.lastIndexOf('#'));
            }
            if (url.indexOf('/index.php') > 0) {
                url = url.substr(0, url.lastIndexOf('/'));
            }
            if (tourURL.indexOf('index.php') > 0) {
                tourURL = tourURL.substr(0, tourURL.lastIndexOf('/'));
            }

            if (tourURL.indexOf('[USERURL]') > -1) {
                tourURL = wutb_toursData.profileUrl;
            }
            if (wutb_toursData.username != "") {
                tourURL = tourURL.replace('[USERNAME]', wutb_toursData.username);
            }
            if (wutb_toursData.post_id != "") {
                tourURL = tourURL.replace('[POSTID]', wutb_toursData.post_id);
            }
            if (wutb_toursData.group != "") {
                tourURL = tourURL.replace('[GROUP]', wutb_toursData.group);
            }
            tourURL = tourURL.replace('[USERNAME]', '');
            tourURL = tourURL.replace('[POSTID]', '');
            tourURL = tourURL.replace('[GROUP]', '');
            tourURL = tourURL.replace('[USERURL]', '');

            tourURL = tourURL.replace(/\/\//g, "/");
            tourURL = tourURL.replace(/http:\//g, "http://");
            tourURL = tourURL.replace(/https:\//g, "https://");
            if (/*tourURL == wutb_toursData.siteUrl ||*/ tourURL == url || tourURL + '/' == url || tourURL == url + '/' || (tourURL.indexOf('[ANY]') > -1 && url.indexOf(tourURL.replace('[ANY]', '')) == 0)) {
                rep = true;
            }
        }
        return rep;
    }

    function executePreviewAction() {
        var action = $('body').data('wutb_lastPreviewAction');
        var value = $('body').data('wutb_lastPreviewValue');
        if (action == 'updateStepPreview') {
            showStepContent(value);
        } else if (action == 'startElementSelection') {
            startElementSelection();
        } else if (action == 'continueElementSelection') {
            continueElementSelection();
        } else if (action == 'confirmElementSelection') {
            confirmElementSelection();
        } else if (action == 'startPageSelection') {
            startPageSelection();
        } else if (action == 'updateOverlay') {
            updateOverlay();
        }

    }
    function showStep(step) {
        if (step) {
            var pastSteps = new Array();
            if (sessionStorage.getItem('wutb_previousSteps') !== null) {
                pastSteps = sessionStorage.getItem('wutb_previousSteps').split(',');
            }
            if (pastSteps.indexOf(currentStep.id) == -1) {
                pastSteps.push(currentStep.id);
            }
            sessionStorage.setItem('wutb_previousSteps', pastSteps.join(','));
        }
        sessionStorage.setItem('wutb_currentStep', JSON.stringify(step));

        if (typeof (step.mode) != 'undefined' && step.mode == 'preview') {
            showStepContent(step);
        } else {
            if ($('#wutb_stepContainer').length > 0) {
                $('#wutb_stepContainer > *:not(#wutb_overlay)').fadeOut(1000);

            }
            if (step.settings.useOverlay && $('#wutb_stepContainer').length > 0) {
                $('#wutb_overlay').attr('id', 'wutb_overlay2');

                if (isCurrentUrl(step.settings.url)) {
                    setTimeout(function () {
                        showStepContent(step);
                    }, step.settings.startDelay * 1000 + 1000);
                } else {
                    if (step.settings.type == 'redirection') {
                        $('html,body').animate({
                            scrollTop: 0
                        }, 500);
                    }
                    showStepContent(step);
                }
            } else {
                setTimeout(function () {
                    if (isCurrentUrl(step.settings.url) && step.settings.type == 'redirection') {
                        $('html,body').animate({
                            scrollTop: 0
                        }, 500);
                    }
                    showStepContent(step);
                }, step.settings.startDelay * 1000);
            }

        }
    }
    function drawOverlay() {
        var chkExist = false;
        if ($('#wutb_overlay2').length == 1) {
            $('#wutb_overlay2').animate({opacity: 0}, 1000);
            setTimeout(function () {
                $('#wutb_overlay2').remove();
            }, 1200);
        }
        if ($('#wutb_overlay').length == 0 && currentStep) {

            if (currentStep.settings.type != 'showElement' || $(currentStep.settings.domElement).length > 0) {
                $('#wutb_stepContainer').append('<canvas id="wutb_overlay"></canvas>');
                $('#wutb_overlay').attr({
                    width: jQuery(window).width(),
                    height: jQuery('body').height()
                }).css({
                    width: jQuery(window).width(),
                    height: jQuery('body').height()
                });
                if (!chkExist) {
                    $('#wutb_overlay').css({opacity: 0}).animate({opacity: 1}, 1000);
                }
            }
        } else {
            $('#wutb_overlay').css({opacity: 0}).animate({opacity: 1}, 1000);

        }
    }



    function isIframe() {
        try {
            return window.self !== window.top;
        } catch (e) {
            return true;
        }
    }


    function nl2br(str, is_xhtml) {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }

    function showStepContent(step) {
        var stepSettings = step.settings;
        currentStep = step;
        var chkStepContainer = false;
        if ($('#wutb_stepContainer').length > 0) {
            chkStepContainer = true;
            $('#wutb_stepContainer *:not(#wutb_overlay2)').remove();

        }
        stepSettings.url = stepSettings.url.replace('[USERNAME]', wutb_toursData.username);

        if (stepSettings.url.indexOf('[USERURL]') > -1) {
            stepSettings.url = wutb_toursData.profileUrl;

        }
        stepSettings.url = stepSettings.url.replace('[POSTID]', wutb_toursData.post_id);
        stepSettings.url = stepSettings.url.replace('[GROUP]', wutb_toursData.group);

        if (!isCurrentUrl(stepSettings.url)) {
            sessionStorage.setItem('wutb_currentStep', JSON.stringify(step));
            document.location.href = wutb_toursData.siteUrl + stepSettings.url;
        } else {
            sessionStorage.removeItem('wutb_currentStep');
            var $stepPreview = $('#wutb_stepContainer');
            if (!chkStepContainer) {
                $stepPreview = $('<div id="wutb_stepContainer" data-tour="' + step.tourID + '" class="wutb_bootstraped"></div>');
                $('body').append($stepPreview);
            }

            if (stepSettings.useOverlay) {
                drawOverlay();
                setTimeout(function () {
                    updateOverlay();
                }, 500);
            }
            if (currentTour) {
                stepSettings.dialog_font = currentTour.dialog_font;
                stepSettings.tooltip_font = currentTour.tooltip_font;
                stepSettings.tooltip_font = currentTour.tooltip_font;
                stepSettings.arrow_font = currentTour.arrow_font;
            }
            var mustShow = true;
            stepSettings.text = stepSettings.text.replace(/\n/g, "<br />");
            if (stepSettings.type == 'text') {
                var delay = 0;
                if (typeof (step.mode) == 'undefined' || step.mode != 'preview' || (typeof (step.settings.enableEntry) != 'undefined' && step.settings.enableEntry)) {
                    delay = 800;
                }
                setTimeout(function () {
                    var title = '';
                    if (stepSettings.headerText != '') {
                        title = '<div class="wutb_fullscreenTitle animated">' + stepSettings.headerText + '</div>';
                    }
                    $stepPreview.append('<div class="wutb_fullscreenTextContainer"><div class="wutb_fullscreenText ">' + title + '<div class="wutb_fullscreenTextContent animated">' + nl2br(stepSettings.text) + '</div></div></div>');
                    $stepPreview.find('.wutb_fullscreenText').css({
                        fontSize: stepSettings.textSize + 'px',
                        color: stepSettings.textColor,
                        fontFamily: stepSettings.texts_font
                    });
                    $stepPreview.find('.wutb_fullscreenTitle').css({
                        color: stepSettings.headerTextColor,
                        fontSize: stepSettings.headerTextSize + 'px',
                        fontFamily: stepSettings.dialog_font
                    });
                    $stepPreview.find('.wutb_fullscreenTitle').css('opacity', 0);
                    $stepPreview.find('.wutb_fullscreenTitle').animate({opacity: 1}, 500);
                    if (stepSettings.entryAnimation != '' && (typeof (step.mode) == 'undefined' || step.mode != 'preview')) {
                        var delay = 0;
                        if (title != '') {
                            $stepPreview.find('.wutb_fullscreenTitle').addClass(stepSettings.entryAnimation);
                            $stepPreview.find('.wutb_fullscreenTextContent').css('opacity', 0);
                            delay = 1;
                        }
                        setTimeout(function () {
                            $stepPreview.find('.wutb_fullscreenTextContent').show();
                            $stepPreview.find('.wutb_fullscreenTextContent').animate({opacity: 1}, 500);
                            $stepPreview.find('.wutb_fullscreenTextContent').addClass(stepSettings.entryAnimation);

                        }, delay * 1000);

                    }
                }, delay);
            } else if (stepSettings.type == 'executeJS') {
                if (!isIframe()) {
                    setTimeout(function () {
                        try {
                            eval(stepSettings.codeJS);
                        } catch (exception) {
                            console.log(exception);
                        }
                    }, stepSettings.startDelay * 1000);
                }

            } else if (stepSettings.type == 'redirection') {
                callNextStep();
            } else if (stepSettings.type == 'dialog') {
                $stepPreview.append('<div class="wutb_dialogContainer" role="dialog"><div class="wutb_dialog animated">'
                        + '<div class="wutb_dialog-body">'
                        + stepSettings.text
                        + '</div>'
                        + '</div>');
                if (stepSettings.headerText != '') {
                    $stepPreview.find('.wutb_dialog').prepend('<div class="wutb_dialog-header">' + stepSettings.headerText + '<a href="javascript:" class="wutb_dialogClose"><span class="fas fa-times"></span></a></div>');
                    $stepPreview.find('.wutb_dialogClose').on('click', function () {
                        if (typeof (currentStep.mode) == 'undefined' || currentStep.mode != 'preview') {
                            if (currentStep.settings.entryAnimation != '') {
                                var outAnimation = currentStep.settings.entryAnimation.replace('In', 'Out');
                                $(this).closest('.wutb_dialog').removeClass(currentStep.settings.entryAnimation);
                                $(this).closest('.wutb_dialog').addClass(outAnimation);
                                setTimeout(callNextStep, 1000);
                            } else {
                                callNextStep();
                            }
                        }


                    });
                }
                if (step.buttons.length > 0) {
                    $stepPreview.find('.wutb_dialog').append('<div class="wutb_dialog-footer"></div>');
                }
                $stepPreview.find('.wutb_dialog-header').css({
                    backgroundColor: stepSettings.headerColor,
                    color: stepSettings.headerTextColor,
                    fontFamily: stepSettings.dialog_font
                });
                $stepPreview.find('.wutb_dialog-body').css({
                    backgroundColor: stepSettings.backgroundColor,
                    fontSize: stepSettings.textSize + 'px',
                    fontFamily: stepSettings.dialog_font
                });
                $stepPreview.find('.wutb_dialog-footer').css({
                    backgroundColor: stepSettings.footerColor,
                    color: stepSettings.footerTextColor,
                    fontFamily: stepSettings.dialog_font
                });
                if (stepSettings.entryAnimation != '' && (typeof (step.mode) == 'undefined' || step.mode != 'preview')) {
                    $stepPreview.find('.wutb_dialog').addClass(stepSettings.entryAnimation);
                }
                for (var i = 0; i < step.buttons.length; i++) {
                    var icon = '';
                    if (step.buttons[i].icon != '') {
                        icon = '<span class="fas ' + step.buttons[i].icon + '"></span>';
                    }
                    var $btn = $('<a href="javascript:" class="wutb_btn" data-url="' + step.buttons[i].finalPage + '" data-action="' + step.buttons[i].action + '">' + icon + step.buttons[i].title + '</a>');
                    $btn.css({
                        backgroundColor: step.buttons[i].backgroundColor,
                        color: step.buttons[i].textColor
                    });
                    $btn.attr('data-id', step.buttons[i].id);

                    if (typeof (step.mode) == 'undefined' && step.mode != 'preview') {
                        $btn.on('click', buttonClicked);
                    }
                    if(typeof(currentTour.clickedButtons) != 'undefined'){
                    if (currentTour.clickedButtons.indexOf($btn.attr('data-id')) > -1) {
                        currentTour.clickedButtons = jQuery.grep(currentTour.clickedButtons, function (value) {
                            return value != $btn.attr('data-id');
                        });
                    }
                    }
                    $stepPreview.find('.wutb_dialog-footer').append($btn);
                }
            } else if (stepSettings.type == 'showElement') {
                if (stepSettings.textStyle == 'arrow') {
                    if ($(currentStep.settings.domElement).length > 0) {

                        $('#wutb_stepContainer').append('<div id="wutb_OverlayElement"></div>');

                        $stepPreview.append('<div id="wutb_arrow" class="animated" data-position="' + stepSettings.position + '" ><canvas id="wutb_arrowCanvas" ></canvas></div>');
                        $stepPreview.append('<div id="wutb_elementText"  data-position="' + stepSettings.position + '">' + stepSettings.text + '</div>');
                        $stepPreview.find('#wutb_elementText').css({
                            fontSize: stepSettings.textSize + 'px',
                            color: stepSettings.textColor,
                            fontFamily: stepSettings.arrow_font
                        });
                        drawArrow();
                        updateArrow();
                        updateElementText();
                        updateOverlayElement();
                        if (stepSettings.animation != '') {
                            $stepPreview.find('#wutb_arrow').addClass(stepSettings.animation);
                            $stepPreview.find('#wutb_arrow').get(0).addEventListener('animationend', function () {
                                setTimeout(function () {
                                    $stepPreview.find('#wutb_arrow').removeClass(stepSettings.animation);
                                    setTimeout(function () {
                                        $stepPreview.find('#wutb_arrow').addClass(stepSettings.animation);

                                    }, 2000);
                                }, 2000);
                            });
                        }
                        if (stepSettings.continueAction == 'click') {
                            $('#wutb_OverlayElement').on('click', function () {
                                if (!$(stepSettings.domElement).is('a[href]') || $(stepSettings.domElement).is('a[href="#"]') || $(stepSettings.domElement).is('a[href="javascript:"]')) {
                                    $(stepSettings.domElement).trigger('click');
                                }
                                callNextStep();
                            });
                        }

                        if (isAnyParentFixed(jQuery(stepSettings.domElement)) || jQuery(stepSettings.domElement).css('position') == 'fixed') {
                            $('html,body').animate({
                                scrollTop: 0
                            }, 400);
                        } else {

                            $('html,body').animate({
                                scrollTop: $(stepSettings.domElement).offset().top - 150
                            }, 400);
                        }

                    } else {
                        mustShow = false;
                        callNextStep();
                    }
                } else if (stepSettings.textStyle == 'tooltip') {
                    if ($(currentStep.settings.domElement).length > 0) {
                        $(stepSettings.domElement).attr('title', stepSettings.text);

                        $('#wutb_stepContainer').append('<div id="wutb_OverlayElement"></div>');

                        if (stepSettings.continueAction == 'click') {

                            $('#wutb_OverlayElement').on('click', callNextStep);
                        }
                        var tooltip = $('<div id="wutb_tooltip" class="animated"><div class="wutb_tooltipArrow"></div><div class="wutb_tooltipContent">' + stepSettings.text + '</div></div>');
                        $stepPreview.append(tooltip);
                        tooltip.attr('data-position', stepSettings.position);
                        tooltip.css({
                            fontFamily: stepSettings.arrow_font
                        });

                        if (stepSettings.animation != '') {
                            tooltip.addClass(stepSettings.animation);
                            updateTooltip();
                            tooltip.get(0).addEventListener('animationend', function () {
                                setTimeout(function () {
                                    tooltip.removeClass(stepSettings.animation);
                                    setTimeout(function () {
                                        tooltip.addClass(stepSettings.animation);

                                    }, 4000);
                                }, 4000);
                            });
                        }
                        if (isAnyParentFixed(jQuery(stepSettings.domElement)) || jQuery(stepSettings.domElement).css('position') == 'fixed') {
                            $('html,body').animate({
                                scrollTop: 0
                            }, 400);
                        } else {
                            $('html,body').animate({
                                scrollTop: $(stepSettings.domElement).offset().top - 150
                            }, 400);
                        }
                    } else {
                        mustShow = false;
                        callNextStep();
                    }
                    updateOverlayElement();

                }
                $(window).trigger('resize');
                setTimeout(function () {
                    $(window).trigger('resize');
                }, 500);
            }
            if (currentTour && currentTour.stepTimer) {
                clearTimeout(currentTour.stepTimer);
            }
            if (mustShow) {
                if (currentTour && (step.settings.continueAction == 'delay' || step.settings.type != 'showElement') && (step.settings.type != 'dialog' || $('.wutb_dialog-footer a').length == 0)) {
                    currentTour.stepTimer = setTimeout(callNextStep, step.settings.continueDelay * 1000);
                }
                if (currentTour && currentTour.tourData.settings.showNavbar && $('#wutb_navbar').length == 0) {
                    var navbar = $('<div id="wutb_navbar" class="" data-position="' + currentTour.tourData.settings.navbarPosition + '"></div>');
                    navbar.append('<a href="javascript:" data-action="previousStep" title="' + currentTour.tourData.settings.navbar_txtPreviousStep + '" ><span class="fas fa-step-backward"></span></a>');
                    navbar.append('<a href="javascript:" data-action="nextStep" title="' + currentTour.tourData.settings.navbar_txtNextStep + '" ><span class="fas fa-step-forward"></span></a>');
                    navbar.append('<a href="javascript:" data-action="stopTour" title="' + currentTour.tourData.settings.navbar_txtStopTour + '" ><span class="fas fa-stop"></span></a>');
                    $stepPreview.append(navbar);
                    navbar.find('a').tooltip({
                        tooltipClass: 'wutb-tour-' + currentTour.id
                    });
                }
                if (currentTour && currentTour.tourData.settings.showNavbar) {
                    navbar.css({
                        backgroundColor: currentTour.tourData.settings.navbarColor
                    });
                    navbar.find('a').css({
                        backgroundColor: currentTour.tourData.settings.navbarBtnsColor
                    });
                    navbar.find('a[data-action="stopTour"]').on('click', stopTour);
                    navbar.find('a[data-action="nextStep"]').on('click', callNextStep);
                    navbar.find('a[data-action="previousStep"]').on('click', callPreviousStep);

                    if (sessionStorage.getItem('wutb_previousSteps') !== null) {
                        var pastSteps = sessionStorage.getItem('wutb_previousSteps');
                        if (pastSteps.length == 0) {
                            navbar.find('a[data-action="previousStep"]').hide();
                        }
                    } else {
                        navbar.find('a[data-action="previousStep"]').hide();
                    }

                    var potentialSteps = findPotentialsSteps(step.id, currentTour.id);
                    if (potentialSteps.length == 0) {
                        navbar.find('a[data-action="nextStep"]').hide();
                    }
                }
            }
        }
    }



    function drawArrow() {
        if (currentStep.settings.type == 'showElement' && $(currentStep.settings.domElement).length > 0 && $('#wutb_arrow').length == 1) {
            var ctx = $('#wutb_arrowCanvas').get(0).getContext('2d');
            ctx.scale(2, 2);
            var img = new Image();
            img.onload = function () {
                $('#wutb_arrowCanvas').attr('width', img.width);
                $('#wutb_arrowCanvas').attr('height', img.height);
                $('#wutb_arrowCanvas').css('width', Math.round(img.width / 2));
                $('#wutb_arrowCanvas').css('height', Math.round(img.height / 2));
                ctx.fillStyle = currentStep.settings.textColor;
                ctx.fillRect(0, 0, img.width, img.height);
                ctx.fill();
                ctx.globalCompositeOperation = 'destination-in';

                ctx.drawImage(img, 0, 0, img.width, img.height);
                updateArrow();
                updateElementText();
            };
            img.src = wutb_toursData.assetsUrl + 'img/arrowDown.png';
        }
    }


    function startElementSelection() {
        selectionInProgress = false;
        sessionStorage.setItem('wutb_selection', 1);
        $('#wutb_stepContainer').fadeOut(350);
        setTimeout(function () {
            $('#wutb_stepContainer').remove();
        }, 400);
    }

    function continueElementSelection() {
        sessionStorage.setItem('wutb_selection', 0);
        selectionInProgress = true;
    }
    function stopElementSelection() {
        selectionInProgress = false;
        sessionStorage.setItem('wutb_selection', 0);
    }
    function onSelectionElementClick($el) {
        selectionInProgress = false;
        sessionStorage.setItem('wutb_selection', 0);
        $('.wutb_elementSelected').removeClass('wutb_elementSelected');
        $('.wutb_selectionShadow').removeClass('wutb_selectionShadow');
        $el.addClass('wutb_elementSelected');
        if ($el.find('*').length == 0 && $el.css('background-color') != 'rgba(0, 0, 0, 0)') {
            $el.addClass('wutb_selectionShadow');
        }
        setTimeout(function () {
            $el.removeClass('wutb_elementSelected');
        }, 3000);

        window.top.jQuery('body').data('wutb_selectedElement', $el);
        window.top.jQuery('body').trigger('wutb_elementSelected');

        lastSelectedElement = $el;
    }

    function confirmElementSelection() {
        if (lastSelectedElement) {
            lastSelectedElement.addClass('wutb_targetElement');
        }
    }
    function startPageSelection() {
        $('#wutb_stepContainer').fadeOut(350);
        setTimeout(function () {
            $('#wutb_stepContainer').remove();
        }, 400);
    }

    function updateOverlay2(step) {
        if ($('#wutb_overlay2').length > 0) {

            $('#wutb_overlay2').attr({
                width: $(document).outerWidth(),
                height: $(document).outerHeight()
            }).css({
                width: $(document).outerWidth(),
                height: $(document).outerHeight()
            });
            var ctx = $('#wutb_overlay2').get(0).getContext('2d');
            ctx.globalAlpha = step.settings.overlayOpacity;
            if (step.settings.type == 'showElement' && $(step.settings.domElement).length > 0) {
                ctx.fillStyle = "#FFFFFF";
                ctx.globalCompositeOperation = "source-over";
                if (isAnyParentFixed($(step.settings.domElement)) || $(step.settings.domElement).css('position') == 'fixed') {
                    ctx.fillRect($(step.settings.domElement).offset().left - 5, $(step.settings.domElement).offset().top - 5 - $(step.settings.domElement).scrollTop(), $(step.settings.domElement).outerWidth() + 10, $(step.settings.domElement).outerHeight() + 10);
                } else {
                    ctx.fillRect($(step.settings.domElement).offset().left - 5, $(step.settings.domElement).offset().top - 5, $(step.settings.domElement).outerWidth() + 10, $(step.settings.domElement).outerHeight() + 10);
                }
                ctx.globalCompositeOperation = "source-out";
                ctx.fillStyle = step.settings.overlayColor;
                ctx.fillRect(0, 0, $('#wutb_overlay2').width(), $('#wutb_overlay2').height());
            } else {
                ctx.fillStyle = step.settings.overlayColor;
                ctx.fillRect(0, 0, $('#wutb_overlay2').width(), $('#wutb_overlay2').height());
            }
        }
    }
    function updateOverlayElement() {
        if (currentStep && currentStep.settings.type == 'showElement' && $(currentStep.settings.domElement).length > 0) {
            $('#wutb_OverlayElement').css({
                width: $(currentStep.settings.domElement).outerWidth(),
                height: $(currentStep.settings.domElement).outerHeight(),
                left: $(currentStep.settings.domElement).offset().left,
                top: $(currentStep.settings.domElement).offset().top
            });
        }
    }
    function updateOverlay() {
        if ($('#wutb_overlay').length > 0) {

            $('#wutb_overlay').attr({
                width: $(document).outerWidth(),
                height: $(document).outerHeight()
            }).css({
                width: $(document).outerWidth(),
                height: $(document).outerHeight()
            });
            var ctx = $('#wutb_overlay').get(0).getContext('2d');
            ctx.globalAlpha = currentStep.settings.overlayOpacity;
            if (currentStep.settings.type == 'showElement' && $(currentStep.settings.domElement).length > 0) {
                ctx.fillStyle = "#FFFFFF";

                ctx.globalCompositeOperation = "source-over";
                if (isAnyParentFixed($(currentStep.settings.domElement)) || $(currentStep.settings.domElement).css('position') == 'fixed') {
                    ctx.fillRect($(currentStep.settings.domElement).offset().left - 5, $(currentStep.settings.domElement).offset().top - 5 - $(currentStep.settings.domElement).scrollTop(), $(currentStep.settings.domElement).outerWidth() + 10, $(currentStep.settings.domElement).outerHeight() + 10);
                } else {
                    ctx.fillRect($(currentStep.settings.domElement).offset().left - 5, $(currentStep.settings.domElement).offset().top - 5, $(currentStep.settings.domElement).outerWidth() + 10, $(currentStep.settings.domElement).outerHeight() + 10);
                }
                ctx.globalCompositeOperation = "source-out";
                ctx.fillStyle = currentStep.settings.overlayColor;
                ctx.fillRect(0, 0, $('#wutb_overlay').width(), $('#wutb_overlay').height());



            } else {
                ctx.fillStyle = currentStep.settings.overlayColor;
                ctx.fillRect(0, 0, $('#wutb_overlay').width(), $('#wutb_overlay').height());
            }
        }
    }
    function isAnyParentFixed($el, rep) {
        if (!rep) {
            var rep = false;
        }
        if ($el.closest('.x-sidebar').length > 0) {
            rep = true;
        }
        try {
            if ($el.parent().length > 0 && $el.parent().css('position') == "fixed") {
                rep = true;
            }
        } catch (e) {

        }

        if (!rep && $el.parent().length > 0) {
            rep = isAnyParentFixed($el.parent(), rep);
        }
        return rep;
    }
    function updateArrow() {

        if (currentStep && typeof (currentStep.settings) != 'undefined' && currentStep.settings.type == 'showElement' && $(currentStep.settings.domElement).length > 0 && $('#wutb_arrow').length == 1) {


            var posX = $(currentStep.settings.domElement).offset().left + $(currentStep.settings.domElement).outerWidth() / 2 - 46;
            var posY = $(currentStep.settings.domElement).offset().top - ($('#wutb_arrow').height() + 8);
            if (currentStep.settings.position == 'down') {
                posY = $(currentStep.settings.domElement).offset().top + $(currentStep.settings.domElement).height() + 8;
            } else if (currentStep.settings.position == 'left') {
                posX = $(currentStep.settings.domElement).offset().left - ($('#wutb_arrow').width() + 8);
                posY = $(currentStep.settings.domElement).offset().top + ($(currentStep.settings.domElement).height() / 2 - $('#wutb_arrow').height() / 2);
                posY -= 14;
            } else if (currentStep.settings.position == 'right') {
                posX = $(currentStep.settings.domElement).offset().left + $(currentStep.settings.domElement).outerWidth() + 8;
                posY = $(currentStep.settings.domElement).offset().top + ($(currentStep.settings.domElement).height() / 2 - $('#wutb_arrow').height() / 2);
                posY -= 14;
            }

            if (currentStep.settings.position != 'top') {
                posY += $('#wpadminbar').height();
            }
            $('#wutb_arrow').css({
                top: posY,
                left: posX,
                marginLeft: currentStep.settings.offsetX,
                marginTop: currentStep.settings.offsetY
            });

        }
    }

    function updateElementText() {
        if (currentStep && typeof (currentStep.settings) != 'undefined' && currentStep.settings.type == 'showElement' && $(currentStep.settings.domElement).length > 0 && $('#wutb_elementText').length == 1) {


            var posX = $(currentStep.settings.domElement).offset().left;
            var posY = $('#wutb_arrow').position().top - $('#wutb_elementText').height();
            if (currentStep.settings.position == 'right') {
                posX = $('#wutb_arrow').position().left + $('#wutb_arrow').width();
                posY = $('#wutb_arrow').position().top + $('#wutb_arrow').height() / 2 - ($('#wutb_elementText').height() / 2);
                $('#wutb_elementText').css({
                    top: posY,
                    left: posX
                });
            } else if (currentStep.settings.position == 'left') {
                posX = $('#wutb_arrow').position().left - $('#wutb_elementText').width();
                posY = $('#wutb_arrow').position().top + $('#wutb_arrow').height() / 2 - ($('#wutb_elementText').height() / 2);
                $('#wutb_elementText').css({
                    top: posY,
                    left: posX
                });
            } else if (currentStep.settings.position == 'top') {
                posX = $(currentStep.settings.domElement).offset().left + $(currentStep.settings.domElement).outerWidth() / 2 - $('#wutb_elementText').width() / 2;
                posY = $('#wutb_arrow').position().top - ($('#wutb_elementText').height() + 14);
                if ($(window).width() <= 480) {
                    posX = $(window).width() / 2 - $('#wutb_elementText').width() / 2
                }
                $('#wutb_elementText').css({
                    top: posY,
                    left: posX
                });
            } else if (currentStep.settings.position == 'down') {
                posX = $(currentStep.settings.domElement).offset().left + $(currentStep.settings.domElement).outerWidth() / 2 - $('#wutb_elementText').width() / 2;
                posY = $(window).height() - ($('#wutb_arrow').position().top + $('#wutb_arrow').width() - 24);
                if ($(window).width() <= 480) {
                    posX = $(window).width() / 2 - $('#wutb_elementText').width() / 2
                }
                posY += $('#wpadminbar').height();
                $('#wutb_elementText').css({
                    top: $('#wutb_arrow').position().top + $('#wutb_arrow').height() + 14,
                    left: posX
                });
            }
        }
    }
    function updateTooltip() {
        if (currentStep && typeof (currentStep.settings) != 'undefined' && currentStep.settings.type == 'showElement' && $(currentStep.settings.domElement).length > 0 && $('#wutb_tooltip').length == 1) {
            var posX = $(currentStep.settings.domElement).offset().left + $(currentStep.settings.domElement).width() / 2 - $('#wutb_tooltip').width() / 2;
            var posY = $(currentStep.settings.domElement).offset().top + $(currentStep.settings.domElement).height() + 24;
            if (currentStep.settings.position == 'top') {
                posY = $(currentStep.settings.domElement).offset().top - ($('#wutb_tooltip').outerHeight() + 24);

                $('#wutb_tooltip .wutb_tooltipArrow').css({
                    borderColor: currentStep.settings.backgroundColor + ' transparent transparent transparent'
                });
            } else if (currentStep.settings.position == 'left') {
                posX = $(currentStep.settings.domElement).offset().left - ($('#wutb_tooltip').outerWidth() + 24);
                posY = ($(currentStep.settings.domElement).offset().top + $(currentStep.settings.domElement).height() / 2) - $('#wutb_tooltip').outerHeight() / 2;
                posY -= 14;
                $('#wutb_tooltip .wutb_tooltipArrow').css({
                    borderColor: 'transparent transparent transparent ' + currentStep.settings.backgroundColor
                });
            } else if (currentStep.settings.position == 'right') {
                posX = $(currentStep.settings.domElement).offset().left + $(currentStep.settings.domElement).outerWidth() + 24;
                posY = ($(currentStep.settings.domElement).offset().top + $(currentStep.settings.domElement).height() / 2) - $('#wutb_tooltip').outerHeight() / 2;
                posY -= 14;

                $('#wutb_tooltip .wutb_tooltipArrow').css({
                    borderColor: 'transparent ' + currentStep.settings.backgroundColor + ' transparent transparent'
                });
            } else {

                $('#wutb_tooltip .wutb_tooltipArrow').css({
                    borderColor: 'transparent transparent ' + currentStep.settings.backgroundColor + ' transparent'
                });
            }
            if (currentStep.settings.position != 'top') {
                posY += $('#wpadminbar').height();
            }
            $('#wutb_tooltip').css({
                backgroundColor: currentStep.settings.backgroundColor,
                color: currentStep.settings.textColor,
                fontSize: currentStep.settings.textSize + 'px',
                top: posY,
                left: posX,
                marginLeft: currentStep.settings.offsetX + 'px',
                marginTop: currentStep.settings.offsetY + 'px'
            });

        }
    }
    function startTour(tour) {
        currentTour = tour;
        currentTour.clickedButtons = new Array();
        var startStep = getStartStepByTour(tour);
        if (startStep) {
            if (currentTour.tourData.settings.runOnce) {
                localStorage.setItem('wutb_viewedTour_' + currentTour.id, 1);
            }
            showStep(startStep);
        }
    }
    function callNextStep() {
        $('.tooltip').remove();
        if (typeof (currentStep.mode) == 'undefined' || currentStep.mode != 'preview') {
            var tourID = currentStep.tourID;
            var potentialSteps = findPotentialsSteps(currentStep.id, tourID);
            if (potentialSteps.length > 0) {
                var chkStep = false;
                for (var i = 0; i < potentialSteps.length; i++) {
                    var step = getStepByID(potentialSteps[i], currentTour);
                    if (step) {
                        showStep(step);
                        chkStep = true;
                        break;
                    }

                }
                if (!chkStep) {
                    stopTour();

                }
            } else {
                stopTour();
            }
        }
    }

    function findPotentialsSteps(originStepID, tourID) {
        var potentialSteps = new Array();
        var tour = getTourByID(tourID);
        if (tour) {
            var conditionsArray = new Array();
            var noConditionsSteps = new Array();
            var maxConditions = 0;
            jQuery.each(tour.tourData.links, function () {
                var link = this;
                if (link.originID == originStepID && !isNaN(parseInt(link.destinationID))) {
                    var error = false;
                    var errorOR = true;
                    if (link.conditions && link.conditions != "[]" && !Array.isArray(link.conditions)) {
                        var errors = checkConditions(link.conditions, tourID);
                        error = errors.error;
                        errorOR = errors.errorOR;
                    } else {
                        noConditionsSteps.push(parseInt(link.destinationID));
                    }
                    if ((link.operator == 'OR' && !errorOR) || (link.operator != 'OR' && !error)) {
                        conditionsArray.push({
                            stepID: parseInt(link.destinationID),
                            nbConditions: link.conditions.length
                        });
                        if (link.conditions.length > maxConditions) {
                            maxConditions = link.conditions.length;
                        }
                        potentialSteps.push(parseInt(link.destinationID));

                    }
                }
            });
            if (originStepID == 0) {
                potentialSteps.push(getStartStepByTour(tour).id);
            }
            if (potentialSteps.length == 0) {
            } else if (noConditionsSteps.length > 0 && noConditionsSteps.length < potentialSteps.length) {
                jQuery.each(noConditionsSteps, function () {
                    var removeItem = this;
                    potentialSteps = jQuery.grep(potentialSteps, function (value) {
                        return value != removeItem;
                    });
                });
                if (maxConditions > 0) {
                    jQuery.each(potentialSteps, function (stepID) {
                        jQuery.each(conditionsArray, function (condition) {
                            if (condition.stepID == stepID && condition.nbConditions < maxConditions) {
                                potentialSteps = jQuery.grep(potentialSteps, function (value) {
                                    return value != stepID;
                                });
                            }
                        });
                    });
                }
            }
        }

        return potentialSteps;
    }

    function checkConditions(conditions, tourID) {
        var error = false;
        var errorOR = true;

        jQuery.each(conditions.conditions, function () {
            var condition = this;
            if (condition.elementID.indexOf('btn_') == 0) {
                var btnID = condition.elementID.substr(4, condition.elementID.length);
                if (condition.action == 'isSelected') {
                    if (currentTour.clickedButtons.indexOf(btnID) == -1) {
                        error = true;
                    } else {
                        errorOR = false;
                    }
                } else if (condition.action == 'isNotSelected') {
                    if (currentTour.clickedButtons.indexOf(btnID) == -1) {
                        error = false;
                    } else {
                        errorOR = true;
                    }
                }
            } else {
                if (condition.elementID == 'currentURL') {
                    if (condition.action == 'equals') {
                        if (document.location.href != condition.value) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    } else if (condition.action == 'different') {
                        if (document.location.href != condition.value) {
                            error = false;
                        } else {
                            errorOR = true;
                        }
                    } else if (condition.action == 'contains') {
                        if (document.location.href.indexOf(condition.value) == -1) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    } else if (condition.action == 'dontContains') {
                        if (document.location.href.indexOf(condition.value) == -1) {
                            error = false;
                        } else {
                            errorOR = true;
                        }
                    }
                } else if (condition.elementID == 'currentDate') {

                    if (condition.action == 'equals') {
                        if (!moment(new Date()).equals(moment(condition.value))) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    } else if (condition.action == 'different') {
                        if (moment(new Date()).equals(moment(condition.value))) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    } else if (condition.action == 'superior') {
                        if (!moment(condition.value).isAfter(moment(new Date()))) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    } else if (condition.action == 'inferior') {
                        if (!moment(condition.value).isBefore(moment(new Date()))) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    } else if (condition.action == 'monthEquals') {
                        var todayMonth = moment(new Date()).format('M');
                        if (parseInt(todayMonth) != parseInt(condition.value)) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    } else if (condition.action == 'monthDifferent') {
                        var todayMonth = moment(new Date()).format('M');
                        if (parseInt(todayMonth) == parseInt(condition.value)) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    } else if (condition.action == 'monthSuperior') {
                        var todayMonth = moment(new Date()).format('M');
                        if (parseInt(todayMonth) <= parseInt(condition.value)) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    } else if (condition.action == 'monthInferior') {
                        var todayMonth = moment(new Date()).format('M');
                        if (parseInt(todayMonth) >= parseInt(condition.value)) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    }


                } else if (condition.elementID == 'wpUser') {
                    if (condition.action == 'usernameIs') {
                        if (wutb_toursData.username != condition.value) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    } else if (condition.action == 'lastNameIs') {
                        if (wutb_toursData.lastName != condition.value) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    } else if (condition.action == 'emailIs') {
                        if (wutb_toursData.email != condition.value) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    } else if (condition.action == 'roleIs') {
                        if (wutb_toursData.roles.indexOf(condition.value) == -1) {
                            error = true;
                        } else {
                            errorOR = false;
                        }
                    }
                }
            }
        });

        return {
            error: error,
            errorOR: errorOR
        };
    }

    function buttonClicked() {
        var $btn = $(this);
        if ($btn.is('[data-action="nextStep"]')) {
            if (currentTour.clickedButtons.indexOf($btn.attr('data-id')) == -1) {
                currentTour.clickedButtons.push($btn.attr('data-id'));
            }
            setTimeout(callNextStep, 1000);
        } else if ($btn.is('[data-action="stopTour"]')) {
            stopTour();
            if ($btn.attr('data-url') != "" && $btn.attr('data-url').length > 2) {
                setTimeout(function () {
                    document.location.href = $btn.attr('data-url');
                }, 1000);
            }
        }

        if (currentStep.settings.entryAnimation != '') {
            var outAnimation = currentStep.settings.entryAnimation.replace('In', 'Out');
            $btn.closest('.wutb_dialog').removeClass(currentStep.settings.entryAnimation);
            $btn.closest('.wutb_dialog').addClass(outAnimation);
            setTimeout(function () {
                $btn.closest('.wutb_dialogContainer').fadeOut();
            }, 600);
        } else {
            $btn.closest('.wutb_dialogContainer').fadeOut();
        }
    }
    function stopTour() {
        $('.tooltip').remove();
        currentTour = false;
        currentStep = false;
        sessionStorage.removeItem('wutb_currentStep');
        sessionStorage.removeItem('wutb_previousSteps');

        $('#wutb_stepContainer').fadeOut(1000);
        setTimeout(function () {
            $('#wutb_stepContainer').remove();
        }, 1200);

    }

    function updateStepSetting() {
        var key = jQuery('body').data('lastSettingKey');
        var value = jQuery('body').data('lastSettingValue');


        if (typeof (currentStep) != 'undefined' && currentStep) {
            eval('currentStep.settings.' + key + '=value;');


            if (key == 'offsetX' || key == 'offsetY') {
                drawArrow();
                updateArrow();
                updateTooltip();
                updateElementText();
            } else if (key == 'text') {
                $('.wutb_fullscreenTextContent,#wutb_elementText,.wutb_tooltipContent,.wutb_dialog-body').html(value);

                updateArrow();
                updateTooltip();
                updateElementText();
            } else if (key == 'headerText') {
                $('.wutb_dialog-header,.wutb_fullscreenTitle').html(value);
            } else if (key == 'headerTextColor') {
                $('.wutb_dialog-header,.wutb_fullscreenTitle').css({
                    color: value
                });
            } else if (key == 'headerTextSize') {
                $('.wutb_dialog-header,.wutb_fullscreenTitle').css({
                    fontSize: value + 'px'
                });
            } else if (key == 'backgroundColor') {
                $('.wutb_dialog-body,#wutb_tooltip').css({backgroundColor: value});
                if (currentStep.settings.type == 'showElement' && currentStep.settings.textStyle == 'tooltip') {
                    if (currentStep.settings.position == 'down') {
                        $('.wutb_tooltipArrow').css({
                            borderColor: 'transparent transparent ' + value
                        });
                    } else if (currentStep.settings.position == 'top') {
                        $('.wutb_tooltipArrow').css({
                            borderColor: value + ' transparent transparent '
                        });
                    } else if (currentStep.settings.position == 'left') {
                        $('.wutb_tooltipArrow').css({
                            borderColor: 'transparent transparent transparent ' + value
                        });
                    } else if (currentStep.settings.position == 'right') {
                        $('.wutb_tooltipArrow').css({
                            borderColor: 'transparent ' + value + ' transparent transparent'
                        });
                    }
                }
            } else if (key == 'textColor') {
                $('.wutb_dialog-body,.wutb_tooltipContent,#wutb_elementText,.wutb_fullscreenText').css({color: value});
                drawArrow();
                updateArrow();
            } else if (key == 'textSize') {
                $('.wutb_dialog-body,.wutb_tooltipContent,#wutb_elementText,.wutb_fullscreenText ').css({fontSize: value + 'px'});
                updateTooltip();
                updateArrow();
                updateElementText();
            } else if (key == 'animation') {
                if ($('#wutb_arrow').length > 0) {
                    $('#wutb_arrow').attr('class', 'animated ' + value);
                } else if ($('.wutb_tooltip').length > 0) {
                    $('.wutb_tooltip').attr('class', 'wutb_tooltip animated ' + value);
                }
            } else if (key == 'entryAnimation') {
                if ($('.wutb_fullscreenTextContent').length > 0) {
                    $('.wutb_fullscreenTextContent,.wutb_fullscreenTextTitle').attr('class', 'wutb_fullscreenText animated ' + value);
                } else if ($('#wutb_arrow').length > 0) {
                    $('#wutb_arrow').attr('class', 'animated ' + value);
                } else if ($('.wutb_dialog').length > 0) {
                    $('.wutb_dialog').attr('class', 'wutb_dialog animated ' + value);
                }
            } else if (key == 'useOverlay') {
                if (value) {
                    drawOverlay();
                    updateOverlay();
                } else {
                    $('#wutb_overlay').remove();
                }
            } else if (key == 'overlayColor') {
                updateOverlay();
            } else if (key == 'overlayOpacity') {
                updateOverlay();
            } else if (key == 'headerColor') {
                $('.wutb_dialog-header').css({backgroundColor: value});
            } else if (key == 'footerColor') {
                $('.wutb_dialog-footer').css({backgroundColor: value});

            } else if (key == 'headerTextColor') {
                $('.wutb_dialog-header').css({color: value});
            } else if (key == 'position') {
                $('#wutb_arrow,#wutb_tooltip').attr('data-position', value);
                updateTooltip();
                updateArrow();
                updateElementText();
            } else if (key == 'domElement') {
                updateTooltip();
                updateArrow();
                updateElementText();
            }
        }

    }

    function callPreviousStep() {
        $('.tooltip').remove();
        if (sessionStorage.getItem('wutb_previousSteps') !== null) {
            var pastSteps = sessionStorage.getItem('wutb_previousSteps').split(',');
            if (pastSteps.length > 0) {
                var step = getStepByID(pastSteps[pastSteps.length - 1], currentTour);
                if (step) {
                    showStep(step);
                }
                pastSteps.pop();
                sessionStorage.setItem('wutb_previousSteps', pastSteps.join(','));

            }
        }
    }
})(jQuery);
