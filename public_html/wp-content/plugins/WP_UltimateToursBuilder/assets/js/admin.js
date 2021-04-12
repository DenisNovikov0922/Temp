(function ($) {
    "use strict";
    var currentTour = false;
    var currentStep = false;
    var currentStepIndex = 0;
    var currentLinkIndex = 0;

    var isLinking = false;
    var wutb_mouseX, wutb_mouseY;
    var wutb_linkGradientIndex = 1;
    var stepsCanvasTimer = false;
    var layoutPanel;
    var tourModified = false;
    var iframeLoaded = false;
    var codeJSEditor = false;
    var wpMenuWasOpen = false;
    var selectUrlMode = 'step';
    var wutb_selectedElement;


    var selectionInProgress = false;

    wutb_data = wutb_data[0];
    $(document).on('ready', function () {
        $('body').on('wutb_elementSelected', elementSelected);
        initUI();
        initGlobalListeners();
        initStepSettings();
        initSettingsListeners();
        initMenusListeners();
        initStepListeners();
        initButtonListeners();
        $('#wutb_loader').fadeOut(250);
        setTimeout(function () {
            $(window).trigger('resize');
        }, 300);
        showFirstStartPanel();

    });
    function elementSelected() {
        wutb_selectedElement = $('body').data('wutb_selectedElement');
        validElementSelection(wutb_selectedElement);

    }
    function showFirstStartPanel() {
        if ($('#wutb_panelToursList tbody tr[data-tour]').length == 0) {
            $('#wutb_panelFirstTour').addClass('wutb_visible');
        }
    }
    function initGlobalListeners() {
        $('body').on('elementSelected', function () {
            validElementSelection(wutb_selectedElement);
        });
        $('#wutb_exportLink').on('click', function () {
            $('#wutb_winExport').modal('hide');
        });
        $('#adminmenu a, #wpadminbar a').on('click', function () {
            if ($(this).attr('href').indexOf('javascript:') == -1 && $(this).attr('href') != '#') {
                if (tourModified && $('#notice_saveBeforeLeave').length == 0) {
                    askSaveBeforeLeave($(this).attr('href'));
                    return false;
                }
            }
        });
        $(window).on('keydown', function (e) {
            if (e.which == 116 && tourModified && $('#notice_saveBeforeLeave').length == 0) {
                askSaveBeforeLeave(document.location.href);
                return false;
            }
        });
        $('#wutb_elementSelectionFrame').on('load', function () {
            hideLoader();
            iframeLoaded = true;
            $('#wutb_frameLoader').fadeOut();
            if ($('#wutb_elementSelectionFrame').data('action') == 'selectPage') {
                layoutPanel.closePanel($('.panel-bottom'), layoutPanel);
                startSelectUrl();
            } else if ($('#wutb_elementSelectionFrame').data('action') == 'selectStartElement') {
                $('#wutb_elementSelectionFrame').data('action', '');
                layoutPanel.closePanel($('.panel-bottom'), layoutPanel);
                startSelectElement();
            } else if (currentStep && $('#notice_startPageSelection').length == 0 && $('#notice_startElementSelection').length == 0) {
                updateStepPreview();
                $('.panel-right').removeClass('hidden');
                layoutPanel.showPanel($('.panel-right'), layoutPanel);
            }
        });
        $('a[data-action="previewTourByID"]').on('click', function () {
            previewTourByID($(this).attr('data-tourid'));
        });
        $('a[data-action="duplicateTour"]').on('click', function () {
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: 'wutb_duplicateTour',
                    tourID: $(this).attr('data-tourid')
                },
                success: function (tourID) {
                    editTour(tourID);
                }
            });

        });
        $('a[data-action="exportTours"]').on('click', exportTours);
        $('a[data-action="importTours"]').on('click', importTours);
        $('a[data-action="importToursJson"]').on('click', importToursJson);
        $('a[data-action="openWinLicense"]').on('click', openWinLicense);
        $('a[data-action="verifyLicense"]').on('click', verifyLicense);

    }
    function onLayoutPanelChange() {
    }
    function initUI() {
        layoutPanel = $('.layout-panel:not(.wutb-initialized)').layoutPanel({
            leftWidth: 340,
            rightWidth: 340,
            topHeight: 80,
            bottomHeight: 340,
            onMinify: onLayoutPanelChange,
            onMaximize: onLayoutPanelChange,
            onUnMinify: onLayoutPanelChange,
            onUnMaximize: onLayoutPanelChange,
            onClose: onLayoutPanelChange
        });
        $('.wutb .panel-body > .panel-tabs > a').on('click', function () {
            var tab = $(this).attr('data-tab');
            $(this).parent().find('.active').removeClass('active');
            $(this).addClass('active');
            $(this).closest('.panel-tabs').parent().find('div[data-tab]').hide();
            $(this).closest('.panel-tabs').parent().find('div[data-tab="' + tab + '"]').show();
        });
        $('.wutb .m_scrollbar').mCustomScrollbar({
            theme: 'dark'
        });
        $('#wutb_stepManagerPanel').contextmenu(function () {
            return false;
        });
        layoutPanel = $('.layout-panel').data('layoutPanel');
        $(document).mousemove(function (e) {
            if (isLinking) {
                wutb_mouseX = e.pageX - $('#wutb_stepsContainer').offset().left;
                wutb_mouseY = e.pageY - $('#wutb_stepsContainer').offset().top;
            }
        });

        $('[data-toggle="tooltip"],[data-tooltip]').each(function () {
            var cssClass = '';
            if ($(this).is('[data-tooltipcolor]')) {
                cssClass = $(this).attr('data-tooltipcolor');
            }
            var placement = 'bottom';
            if ($(this).is('[data-placement]')) {
                placement = $(this).attr('data-placement');
            }

            $(this).tooltip({
                container: '.wutb',
                placement: placement,
                template: '<div class="tooltip ' + placement + '" data-color="' + cssClass + '" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
            });
        });
        $(document).mousedown(function (e) {
            if (e.button == 2) {
                if (isLinking) {
                    isLinking = false;
                }
                return false;
            }
            return true;
        });
        $(window).resize(function () {
            $('#wutb_loader .wutb_spinner').css({
                top: $(window).height() / 2 - $('#wpadminbar').height() / 2
            });
            $('#wutb_formPanel').css({
                minHeight: $('#wpwrap').height()
            });
        });
        $('#wutb_formPanel').css({
            minHeight: $('#wpwrap').height()
        });
        $(window).on('resize', function () {
            onResize();
        });

        $('#wutb_panelFirstTour a[data-action="createTour"]').on('click', createTour);
        $('#wutb_tourListTable thead a[data-action="createTour"]').on('click', createTour);
        $('#wutb_panelToursList tbody a[data-action="deleteTour"]').on('click', function () {
            askDeleteTour($(this).closest('tr').attr('data-tour'));
        });
        $('#wutb_panelToursList tbody a[data-action="editTour"]').on('click', function () {
            editTour($(this).closest('tr').attr('data-tour'));
        });
        $('#wutb_winConditions [data-action="addConditionInteraction"]').on('click', function () {
            addConditionInteraction($('#wutb_winConditions'), false);
        });
        $('.panel-right [data-name="htmlText"]').summernote({
            height: 226,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                ['view', ['codeview']],
                ['help', ['help']]
            ],
            disableResizeEditor: true,
            callbacks: {
                onChange: function (contents, $editable) {

                }
            }
        });
        $('.panel-top [data-tab="tour"] a[data-action="preview"]').on('click', previewTour);
        stepsCanvasTimer = setInterval(updateStepCanvas, 30);
    }

    function updateStepCanvas() {
        if ($('#wutb_stepsCanvas').length > 0) {
            wutb_linkGradientIndex++;
            if (wutb_linkGradientIndex >= 30) {
                wutb_linkGradientIndex = 1;
            }
            var ctx = $('#wutb_stepsCanvas').get(0).getContext('2d');
            ctx.clearRect(0, 0, $('#wutb_stepsCanvas').attr('width'), $('#wutb_stepsCanvas').attr('height'));
            $.each(currentTour.links, function (index) {
                var link = this;
                if (link.destinationID && $('.wutb_stepBloc[data-stepid="' + link.originID + '"]').length > 0 && $('.wutb_stepBloc[data-stepid="' + link.destinationID + '"]').length > 0) {

                    var posX = parseInt($('.wutb_stepBloc[data-stepid="' + link.originID + '"]').css('left')) + $('.wutb_stepBloc[data-stepid="' + link.originID + '"]').outerWidth() / 2 + 22;
                    var posY = parseInt($('.wutb_stepBloc[data-stepid="' + link.originID + '"]').css('top')) + $('.wutb_stepBloc[data-stepid="' + link.originID + '"]').outerHeight() / 2 + 22;
                    var posX2 = parseInt($('.wutb_stepBloc[data-stepid="' + link.destinationID + '"]').css('left')) + $('.wutb_stepBloc[data-stepid="' + link.destinationID + '"]').outerWidth() / 2 + 22;
                    var posY2 = parseInt($('.wutb_stepBloc[data-stepid="' + link.destinationID + '"]').css('top')) + $('.wutb_stepBloc[data-stepid="' + link.destinationID + '"]').outerHeight() / 2 + 22;
                    var grd = ctx.createLinearGradient(posX, posY, posX2, posY2);
                    var chkBack = false;
                    var wutb_linkGradientIndexA = wutb_linkGradientIndex / 30;
                    var gradPos1 = wutb_linkGradientIndexA;
                    var gradPos2 = wutb_linkGradientIndexA + 0.1;
                    var gradPos3 = wutb_linkGradientIndexA + 0.2;
                    ctx.lineWidth = 2;
                    if (gradPos2 > 1) {
                        gradPos2 = 0;
                        gradPos3 = 0.2;
                    }
                    if (gradPos3 > 1) {
                        gradPos3 = 0;
                    }

                    grd.addColorStop(gradPos1, "#dddddd");
                    grd.addColorStop(gradPos2, "#00adb5");
                    grd.addColorStop(gradPos3, "#dddddd");
                    ctx.strokeStyle = grd;
                    ctx.setLineDash([10, 10]);
                    ctx.beginPath();
                    ctx.moveTo(posX, posY);
                    ctx.lineTo(posX2, posY2);
                    ctx.stroke();
                    if ($('.wutb_linkPoint[data-linkindex="' + index + '"]').length == 0) {
                        var $point = $('<a href="javascript:" data-linkindex="' + index + '" class="wutb_linkPoint"><span class="fas fa-pencil-alt"></span></a>');
                        $('#wutb_stepsContainer').append($point);
                        $point.on('click', function () {
                            openWinLink($(this));
                        });
                    }
                    repositionLinkPoint(index);
                } else {
                    $('.wutb_linkPoint[data-linkindex="' + index + '"]').remove();
                }
            });
            if (isLinking) {

                var step = $('.wutb_stepBloc[data-stepid="' + currentTour.links[currentLinkIndex].originID + '"]');
                var posX = step.position().left + $('#wutb_stepsOverflow').scrollLeft() + step.outerWidth() / 2;
                var posY = step.position().top + $('#wutb_stepsOverflow').scrollTop() + step.outerHeight() / 2;
                ctx.strokeStyle = "#dddddd";
                ctx.lineWidth = 2;
                ctx.setLineDash([10, 10]);
                ctx.beginPath();
                ctx.moveTo(posX, posY);
                ctx.lineTo(wutb_mouseX, wutb_mouseY);
                ctx.stroke();
            }
        }
    }

    function getLinkByIndex(linkIndex) {
        var link = false;
        $.each(currentTour.links, function (i) {
            if (i == linkIndex) {
                link = this;
            }
        });
        return link;
    }
    function openWinLink($item) {
        currentLinkIndex = $item.attr('data-linkindex');
        $('#wutb_winConditions').attr('data-linkindex', $item.attr('data-linkindex'));
        $('#wutb_winConditions #wutb_linkStepsPreview').html('<div id="wutb_linkOriginStep" class="wutb_stepBloc"><div class="wutb_stepBlocWrapper"><h4 id="wutb_linkOriginTitle" style="margin-top: 0px;"></h4></div> </div><div id="wutb_linkStepArrow"></div><div id="wutb_linkDestinationStep" class="wutb_stepBloc  "><div class="wutb_stepBlocWrapper"><h4 id="wutb_linkDestinationTitle" style="margin-top: 0px;"></h4></div></div>');
        $('.wutb_conditionItem').remove();
        var stepID = currentTour.links[$item.attr('data-linkindex')].originID;
        var step = getStepByID(stepID);
        var destID = currentTour.links[$item.attr('data-linkindex')].destinationID;
        var destination = getStepByID(destID);
        $('#wutb_linkInteractions').show();
        $('#wutb_linkOriginTitle').html(step.title);
        $('#wutb_linkDestinationTitle').html(destination.title);
        $.each(currentTour.links[currentLinkIndex].conditions.conditions, function () {
            addConditionInteraction($('#wutb_winConditions'), this);
        });
        $('#wutb_winConditions #wutb_conditionsOperator').val(currentTour.links[currentLinkIndex].conditions.operator);
        openWinConditions($('#wutb_winConditions'), function (conditions) {

            var link = getLinkByIndex(currentLinkIndex);
            link.conditions = conditions;
        }, function () {
            showLoader();
            var linkID = 0;
            $('.wutb_linkPoint[data-linkindex="' + currentLinkIndex + '"]').remove();
            $.each(currentTour.links, function (i) {
                if (i == currentLinkIndex) {
                    linkID = this.id;
                }
            });
            currentTour.links = $.grep(currentTour.links, function (link) {
                return link.id != linkID;
            });
            
            hideLoader();
            updateStepCanvas();
        });
        setTimeout(updateStepsDesign, 255);
    }



    function openWinConditions($panel, saveCallback, deleteCallback, mode) {
        $('#wutb_btnHeaderClose').fadeIn();
        $('#wutb_btnHeaderClose').off('click.wutb_close');
        $('#wutb_btnHeaderClose').on('click.wutb_close', function () {
            closeWin($panel);
        });
        $panel.find('#wutb_linkMainImg [data-type]').hide();
        $panel.find('#wutb_linkMainImg [data-type="' + mode + '"]').show();
        $panel.find('#wutb_conditionsSaveBtn').off('click.wutb_save');
        $panel.find(' #wutb_conditionsDelBtn').off('click.wutb_save');
        $panel.find(' #wutb_conditionsSaveBtn').on('click.wutb_save', function () {
            tourModified = true;
            $panel.fadeOut();
            var conditions = {operator: $panel.find('#wutb_conditionsOperator').val(), conditions: new Array()};
            $panel.find('.wutb_conditionItem').each(function () {
                if ($(this).find('.wutb_conditionSelect').val() && $(this).find('.wutb_conditionSelect').val() != "" && $(this).find('.wutb_conditionSelect').val().substr(0, 1) != 's') {
                    conditions.conditions.push({
                        elementID: $(this).find('.wutb_conditionSelect').val(),
                        component: $(this).find('.wutb_conditionSelect option:selected').attr('data-component'),
                        action: $(this).find('.wutb_conditionoperatorSelect').val(),
                        value: $(this).find('.wutb_conditionValue').val()
                    });
                }
            });
            saveCallback(conditions);
        });
        if (deleteCallback != null) {
            $panel.find('#wutb_conditionsDelBtn').on('click', function () {
                tourModified = true;
                $panel.fadeOut();
                deleteCallback();
            });
        } else {
            $panel.find('#wutb_conditionsDelBtn').hide();
        }

        $panel.fadeIn(250);
    }

    function repositionLinkPoint(linkIndex) {
        var link = currentTour.links[linkIndex];
        var originLeft = ($('.wutb_stepBloc[data-stepid="' + link.originID + '"]').offset().left - $('#wutb_stepsContainer').offset().left) + $('.wutb_stepBloc[data-stepid="' + link.originID + '"]').width() / 2;
        var originTop = ($('.wutb_stepBloc[data-stepid="' + link.originID + '"]').offset().top - $('#wutb_stepsContainer').offset().top) + $('.wutb_stepBloc[data-stepid="' + link.originID + '"]').height() / 2;
        var destinationLeft = ($('.wutb_stepBloc[data-stepid="' + link.destinationID + '"]').offset().left - $('#wutb_stepsContainer').offset().left) + $('.wutb_stepBloc[data-stepid="' + link.destinationID + '"]').width() / 2;
        var destinationTop = ($('.wutb_stepBloc[data-stepid="' + link.destinationID + '"]').offset().top - $('#wutb_stepsContainer').offset().top) + $('.wutb_stepBloc[data-stepid="' + link.destinationID + '"]').height() / 2;
        var posX = originLeft + (destinationLeft - originLeft) / 2;
        var posY = originTop + (destinationTop - originTop) / 2;
        $.each(currentTour.links, function (i) {
            if (this.originID == link.destinationID && this.destinationID == link.originID && i < linkIndex) {

                posX += 15;
                posY += 15;
            }
        });
        $('.wutb_linkPoint[data-linkindex="' + linkIndex + '"]').css({
            left: posX + 'px',
            top: posY + 'px'
        });
    }
    function onResize() {
        updateStepsDesign();
    }
    function createTour() {

        showLoader();
        if (!$('#wutb_tourListTable thead a[data-action="createTour"]').is('.disabled')) {
            $('#wutb_panelFirstTour').removeClass('wutb_visible');
            $('#wutb_tourListTable thead a[data-action="createTour"]').addClass('disabled');
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: 'wutb_createTour'
                },
                success: function (tourID) {
                    editTour(tourID);
                }
            });
        }
    }
    function editTour(tourID) {
        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'wutb_editTour',
                tourID: tourID
            },
            success: function (tour) {
                hideLoader();
                $('[data-info="tourID"]').html(tourID);
                currentTour = JSON.parse(tour);
                $('#wutb_bottomSettings a[data-tab="settings"]').trigger('click');
                $('#wutb_bottomSettings [name]').each(function () {
                    if ($(this).is('[type="checkbox"]')) {
                        var value = 0;
                        eval('value = currentTour.settings.' + $(this).attr('name') + ';');
                        if (value) {
                            $(this).attr('checked', 'checked');
                        } else {
                            $(this).removeAttr('checked');
                        }
                    } else {
                        if ($(this).is('select[multiple]')) {
                            eval('$(this).val(currentTour.settings.' + $(this).attr('name') + '.split(","));');
                        } else {
                            eval('$(this).val(currentTour.settings.' + $(this).attr('name') + ');');
                        }
                    }
                });
                $('#wutb_bottomSettings [name]').trigger('change');

                $('.panel-center .panel-body').mCustomScrollbar('scrollTo', 'top');
                for (var i = 0; i < currentTour.steps.length; i++) {
                    addStepToManager(currentTour.steps[i]);
                }

                $('#wutb_stepManagerPanel').fadeIn();
                setTimeout(function () {
                    $('#wutb_panelToursList').hide();
                }, 380);
                $('.panel-top .panel-header > .panel-header-title > [data-tab] ').hide();
                $('.panel-top .panel-header > .panel-header-title > [data-tab="tour"]').show();
                $('.panel-top,.panel-bottom').removeClass('hidden');
                setTimeout(function () {
                    $(window).trigger('resize');
                    updateStepsDesign();
                }, 360);
            }
        });
    }
    function deleteTour(tourID) {
        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'wutb_deleteTour',
                tourID: tourID
            },
            success: function () {
                $('#wutb_panelToursList tbody tr[data-tour="' + tourID + '"]').remove();
                if ($('#wutb_panelToursList tbody tr[data-tour]').length == 0) {
                    $('#wutb_panelFirstTour').addClass('wutb_visible');
                }
            }
        });
    }

    function initSettingsListeners() {

        $('#wutb_bottomSettings [data-action="startSelectStartURL"]').on('click', function () {
            $('#wutb_elementSelectionPanel').fadeIn();
            selectUrlMode = 'tour';
            $('#wutb_elementSelectionFrame').data('action', 'selectPage');
            var targetUrl = wutb_data.siteUrl;

            $('#wutb_frameLoader').fadeIn();
            $('#wutb_elementSelectionFrame').attr('src', targetUrl);
        });
        $('#wutb_bottomSettings [data-action="startSelectElement"]').on('click', function () {

            $('#wutb_elementSelectionPanel').fadeIn();
            $('#wutb_elementSelectionFrame').data('action', 'selectStartElement');
            $('#wutb_frameLoader').fadeIn();
            $('#wutb_elementSelectionFrame').attr('src', wutb_data.websiteUrl);
        });
        $('#wutb_bottomSettings [name="startMethod"]').on('change', function () {
            if ($(this).val() == 'elementClick') {
                $('#wutb_bottomSettings [name="tourDomElement"]').closest('.form-group').slideDown();
            } else {
                $('#wutb_bottomSettings [name="tourDomElement"]').closest('.form-group').slideUp();
            }
        });

        $('#wutb_bottomSettings input[data-colorpicker]').each(function () {
            var el = this;
            jQuery(this).colpick({
                color: '#FFFFFF',
                layout: 'hex',
                appendTo: $('#wutb_bottomSettings .mCSB_container').get(0),
                onSubmit: function () {
                    jQuery('body > .colpick').fadeOut();
                },
                onChange: function (hsb, hex, rgb, el, bySetColor) {
                    jQuery(el).val('#' + hex);

                }
            });
        });

        $('#wutb_bottomSettings [name="startURL"]').on('change', function () {
            $(this).val($(this).val().replace(wutb_data.siteUrl, ''));
        });
        $('#wutb_bottomSettings a[data-tab]').on('click', function () {
            var tab = $(this).attr('data-tab');
            $('#wutb_bottomSettings div[data-tab]:not([data-tab="' + tab + '"])').hide();
            $('#wutb_bottomSettings div[data-tab][data-tab="' + tab + '"]').show();
            $('#wutb_bottomSettings a[data-tab].active').removeClass('active');
            $(this).addClass('active');

        });


        $('#wutb_bottomSettings input[data-slider]').each(function () {
            $(this).after('<div class="wutb_slider"></div>');
            var min = 0;
            var max = 99;
            var step = 1;
            if ($(this).is('[min]')) {
                min = parseInt($(this).attr('min'));
            }
            if ($(this).is('[max]')) {
                max = parseInt($(this).attr('max'));
            }
            if ($(this).is('[data-step]')) {
                step = parseFloat($(this).attr('data-step'));
            }
            $(this).next('.wutb_slider').slider({
                min: min,
                max: max,
                step: step,
                change: function (event, ui) {
                    $(this).find('.tooltip .tooltip-inner').html(ui.value);
                    $(this).prev('input[data-slider]').val(ui.value);
                },
                slide: function (event, ui) {
                    $(this).find('.tooltip .tooltip-inner').html(ui.value);
                    $(this).prev('input[data-slider]').val(ui.value);
                }
            });
            $(this).next('.wutb_slider').find('.ui-slider-handle').append('<div class="tooltip bs-tooltip-bottom" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>');
            $(this).next('.wutb_slider').attr('data-name', $(this).attr('name'));
            $(this).on('change', function () {
                $(this).next('.wutb_slider').slider('value', $(this).val());
            });
        });
        $('#wutb_bottomSettings [name]').on('change focusout', function () {
            var value = '';
            if ($(this).is('[type="checkbox"]')) {
                value = $(this).is(':checked');
            } else {
                value = nl2br($(this).val());
            }
            eval('currentTour.settings.' + $(this).attr('name') + '=value;');
        });
        $('#wutb_bottomSettings [name="showNavbar"]').on('change click', function () {
            if ($(this).is(':checked')) {
                $('#wutb_bottomSettings [name="navbar_txtStopTour"]').closest('.form-group').slideDown();
                $('#wutb_bottomSettings [name="navbar_txtNextStep"]').closest('.form-group').slideDown();
                $('#wutb_bottomSettings [name="navbar_txtPreviousStep"]').closest('.form-group').slideDown();
            } else {
                $('#wutb_bottomSettings [name="navbar_txtStopTour"]').closest('.form-group').slideUp();
                $('#wutb_bottomSettings [name="navbar_txtNextStep"]').closest('.form-group').slideUp();
                $('#wutb_bottomSettings [name="navbar_txtPreviousStep"]').closest('.form-group').slideUp();
            }
        });

        $('#wutb_bottomSettings [name="allowedRoles"]').on('change', function () {
            if ($('#wutb_bottomSettings [name="allowedRoles"] option[value=""]').is(':selected')) {
                $('#wutb_bottomSettings [name="allowedRoles"]').val('');
            }
        });

    }
    function initMenusListeners() {

        $('.panel-top a[data-action="closeStep"]').on('click', closeStepEdition);
        $('.panel-top a[data-action="saveTour"]').on('click', saveTour);
        $('.panel-top a[data-action="addStep"]').on('click', function () {
            var start = false;
            if (currentTour.steps.length == 0) {
                start = true;
            }
            var step = {
                id: generateID(),
                tourID: currentTour.id,
                position: [200, 80],
                start: start,
                content: '',
                buttons: new Array(),
                settings: {
                    title: wutb_data.texts['New step'],
                    type: 'text',
                    text: 'Hello world !',
                    headerText: wutb_data.texts['My title'],
                    textStyle: 'arrow',
                    textColor: currentTour.settings.texts_textColor,
                    textSize: currentTour.settings.texts_textSize,
                    headerTextSize: currentTour.settings.texts_headerTextSize,
                    position: 'down',
                    continueAction: 'delay',
                    continueDelay: 6,
                    useOverlay: true,
                    overlayOpacity: currentTour.settings.overlayOpacity,
                    overlayColor: currentTour.settings.overlayColor,
                    startDelay: 0,
                    backgroundColor: '#bdc3c7',
                    headerColor: '#1abc9c',
                    headerTextColor: currentTour.settings.texts_headerTextColor,
                    footerColor: '#ccc',
                    animation: 'heartBeat',
                    entryAnimation: 'fadeIn',
                    url: '',
                    codeJS: '',
                    offsetX:0,
                    offsetY:0                    
                }
            };
            currentTour.steps.push(step);
            addStepToManager(step);
            linkLightStep(step.id);
        });
    }

    function nl2br(str, is_xhtml) {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }

    function initStepSettings() {
        $('.panel-right input[data-slider]').each(function () {
            var el = this;
            $(this).after('<div class="wutb_slider"></div>');
            var min = 0;
            var max = 99;
            var step = 1;
            if ($(this).is('[min]')) {
                min = parseInt($(this).attr('min'));
            }
            if ($(this).is('[max]')) {
                max = parseInt($(this).attr('max'));
            }
            if ($(this).is('[data-step]')) {
                step = parseFloat($(this).attr('data-step'));
            }
            $(this).next('.wutb_slider').slider({
                min: min,
                max: max,
                step: step,
                change: function (event, ui) {
                    $(this).find('.tooltip .tooltip-inner').html(ui.value);
                    $(this).prev('input[data-slider]').val(ui.value);
                    updateStepSetting($(this));
                },
                slide: function (event, ui) {
                    $(this).find('.tooltip .tooltip-inner').html(ui.value);
                    $(this).prev('input[data-slider]').val(ui.value);
                    updateStepSetting($(el));
                }
            });
            $(this).next('.wutb_slider').find('.ui-slider-handle').append('<div class="tooltip bs-tooltip-bottom" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>');
            $(this).next('.wutb_slider').attr('data-name', $(this).attr('name'));
            $(this).on('change', function () {
                $(this).next('.wutb_slider').slider('value', $(this).val());
            });
        });
        $('.panel-right input[data-colorpicker]').each(function () {
            var el = this;
            jQuery(this).colpick({
                color: '#FFFFFF',
                layout: 'hex',
                appendTo: $('.panel-right .mCSB_container').get(0),
                onSubmit: function () {
                    jQuery('body > .colpick').fadeOut();
                },
                onChange: function (hsb, hex, rgb, el, bySetColor) {
                    jQuery(el).val('#' + hex);
                    updateStepSetting($(el));
                }
            });
        });
        $('.panel-right [name]:not([data-dontrefresh])').on('change keyup', updateEditedStepData);
        $('.panel-right [name="type"],.panel-right [name="textStyle"]').on('change keyup', updateStepPreview);
        $('.panel-right [name]:not([data-dontrefresh]):not([name="textStyle"])').on('change keyup', function () {
            updateStepSetting($(this));
        });

        codeJSEditor = CodeMirror.fromTextArea($('#wutb_codeJSEditor').get(0), {
            lineNumbers: true
        });
    }
    function initButtonListeners() {

        $('#wutb_winEditButton [name="action"]').on('change', function () {
            if ($(this).val() == 'stopTour') {
                $('#wutb_winEditButton [name="finalPage"]').closest('.form-group').slideDown();
            } else {
                $('#wutb_winEditButton [name="finalPage"]').closest('.form-group').slideUp();
            }
        });
        $('#wutb_winEditButton [data-action="saveButton"]').on('click', saveButton);


        $('#wutb_winEditButton input[data-colorpicker]').each(function () {
            var el = this;
            jQuery(this).colpick({
                color: '#FFFFFF',
                layout: 'hex',
                appendTo: $('#wutb_winEditButton .modal-content').get(0),
                onSubmit: function () {
                    appendTo: $('#wutb_winEditButton .modal-body').get(0),
                            jQuery('body > .colpick').fadeOut();
                },
                onChange: function (hsb, hex, rgb, el, bySetColor) {
                    jQuery(el).val('#' + hex);
                    updateStepPreview();
                }
            });
        });
    }
    function initStepListeners() {
        $('.panel-right [data-action="startSelectUrl"]').on('click', function () {
            selectUrlMode = 'step';
            startSelectUrl();
        });
        $('.panel-right [data-action="startSelectElement"]').on('click', startSelectElement);
        $('.panel-right [name="url"]').on('change', function () {
            var value = $(this).val().replace(wutb_data.siteUrl, '');
            value = value.replace('?stepPreview=' + currentStep.id, '');
            value = value.replace('&stepPreview=' + currentStep.id, '');

            $(this).val(value);
            if ($('#notice_startPageSelection').length == 0 && $('#notice_startElementSelection').length == 0) {
                updateStepPreview();
            }
        });

        $('.panel-right [name="continueAction"]').on('change', function (e) {
            if (currentStep.settings.type != 'redirection') {
                if ($(this).val() == 'click') {
                    $('.panel-right [name="continueDelay"]').closest('.form-group').slideUp();
                } else {
                    $('.panel-right [name="continueDelay"]').closest('.form-group').slideDown();
                }
            } else {
                $('.panel-right [name="continueDelay"]').closest('.form-group').slideUp();
            }

        });
        $('.panel-right [name="useOverlay"]').on('change', function () {
            if ($('.panel-right [name="useOverlay"]').is(':checked')) {
                $('.panel-right [name="overlayOpacity"]').closest('.form-group').slideDown();
                $('.panel-right [name="overlayColor"]').closest('.form-group').slideDown();
            } else {
                $('.panel-right [name="overlayOpacity"]').closest('.form-group').slideUp();
                $('.panel-right [name="overlayColor"]').closest('.form-group').slideUp();
            }
        });
        $('.panel-right [name="textStyle"]').on('change', function (e) {
            if ( $('.panel-right [name="type"]').val() == 'dialog' || ($('.panel-right [name="type"]').val() == 'showElement' && $(this).val() == 'tooltip')) {
                $('.panel-right [name="backgroundColor"]').closest('.form-group').slideDown();
            } else {
                $('.panel-right [name="backgroundColor"]').closest('.form-group').slideUp();
            }

            if (typeof (e.isTrigger) == 'undefined') {
                if ($('.panel-right [name="type"]').val() == 'showElement') {
                    if ($(this).val() == 'tooltip') {
                        $('.panel-right [name="backgroundColor"]').val(currentTour.settings.tooltip_backgroundColor).trigger('change');
                        $('.panel-right [name="textColor"]').val(currentTour.settings.tooltip_textColor).trigger('change');
                        $('.panel-right [name="textSize"]').val(currentTour.settings.tooltip_textSize).trigger('change');

                    } else if ($(this).val() == 'arrow') {
                        $('.panel-right [name="textColor"]').val(currentTour.settings.arrow_textColor).trigger('change');
                        $('.panel-right [name="textSize"]').val(currentTour.settings.arrow_textSize).trigger('change');

                    }
                    setTimeout(function(){
                        updateStepSetting($('.panel-right [name="type"]'));
                    },800);
                }
            }

        });
        $('.panel-right [name="type"]').on('change', function (e) {
            if (typeof (e.isTrigger) == 'undefined') {
                if ($(this).val() == 'dialog') {
                    $('.panel-right [name="headerTextColor"]').val(currentTour.settings.dialog_headerTextColor).trigger('change');
                    $('.panel-right [name="textColor"]').val(currentTour.settings.dialog_textColor).trigger('change');
                    $('.panel-right [name="headerColor"]').val(currentTour.settings.dialog_headerColor).trigger('change');
                    $('.panel-right [name="footerColor"]').val(currentTour.settings.dialog_footerColor).trigger('change');
                    $('.panel-right [name="backgroundColor"]').val(currentTour.settings.dialog_backgroundColor).trigger('change');
                $('.panel-right [name="backgroundColor"]').closest('.form-group').slideDown();
                    
                } else if ($(this).val() == 'showElement') {
                    $('.panel-right [name="textStyle"]').val('arrow');
                    $('.panel-right [name="textColor"]').val(currentTour.settings.arrow_textColor).trigger('change');
                    $('.panel-right [name="textSize"]').val(currentTour.settings.arrow_textSize).trigger('change');
                    if($('.panel-right [name="textStyle"]').val()== 'tooltip'){
                $('.panel-right [name="backgroundColor"]').closest('.form-group').slideDown();
                     } else {
                         $('.panel-right [name="backgroundColor"]').closest('.form-group').slideUp();
                         
                     }
                } else if ($(this).val() == 'text') {
                    $('.panel-right [name="textColor"]').val(currentTour.settings.texts_textColor).trigger('change');
                    $('.panel-right [name="textSize"]').val(currentTour.settings.texts_textSize).trigger('change');
                    $('.panel-right [name="headerTextSize"]').val(currentTour.settings.texts_headerTextSize).trigger('change');
                    $('.panel-right [name="headerTextColor"]').val(currentTour.settings.texts_headerTextColor).trigger('change');
                $('.panel-right [name="backgroundColor"]').closest('.form-group').slideUp();
                }
            }
        });
        $('.panel-right [name="type"]').on('change', function () {
            if ($(this).val() == 'dialog') {
                $('.panel-right a[data-tab="style"]').show();

                $('.panel-right [name="domElement"]').closest('.form-group').slideUp();
                $('.panel-right [name="textStyle"]').closest('.form-group').slideUp();
                $('.panel-right [name="codeJS"]').closest('.form-group').slideUp();
                $('.panel-right [name="continueDelay"]').closest('.form-group').slideUp();
                $('.panel-right [name="continueAction"]').closest('.form-group').slideUp();
                $('.panel-right [name="continueDelay"]').closest('.form-group').slideUp();
                $('.panel-right [name="position"]').closest('.form-group').slideUp();
                $('.panel-right [name="headerTextSize"]').closest('.form-group').slideUp();
                $('.panel-right [name="offsetX"]').closest('.form-group').slideUp();
                $('.panel-right [name="offsetY"]').closest('.form-group').slideUp();                
                $('.panel-right [name="text"]').closest('.form-group').slideDown();
                $('.panel-right [name="useOverlay"]').closest('.form-group').slideDown();
                $('.panel-right [name="headerText"]').closest('.form-group').slideDown();
                $('.panel-right [name="headerTextColor"]').closest('.form-group').slideDown();
                $('.panel-right [name="footerColor"]').closest('.form-group').slideDown();
                $('.panel-right [name="headerColor"]').closest('.form-group').slideDown();
                $('.panel-right [name="startDelay"]').closest('.form-group').slideDown();

                $('.panel-right [name="entryAnimation"]').closest('.form-group').slideDown();
                $('.panel-right a[data-tab="buttons"]').fadeIn();
            } else if ($(this).val() == 'showElement') {
                $('.panel-right a[data-tab="style"]').show();

                $('.panel-right [name="headerText"]').closest('.form-group').slideUp();
                $('.panel-right [name="codeJS"]').closest('.form-group').slideUp();
                $('.panel-right [name="headerTextColor"]').closest('.form-group').slideUp();
                $('.panel-right [name="footerColor"]').closest('.form-group').slideUp();
                $('.panel-right [name="headerColor"]').closest('.form-group').slideUp();
                $('.panel-right [name="entryAnimation"]').closest('.form-group').slideUp();
                $('.panel-right [name="headerTextSize"]').closest('.form-group').slideUp();
                $('.panel-right [data-tab="buttons"]').fadeOut();

                $('.panel-right [name="offsetX"]').closest('.form-group').slideDown();
                $('.panel-right [name="offsetY"]').closest('.form-group').slideDown();
                $('.panel-right [name="animation"]').closest('.form-group').slideDown();
                $('.panel-right [name="domElement"]').closest('.form-group').slideDown();
                $('.panel-right [name="textStyle"]').closest('.form-group').slideDown();
                $('.panel-right [name="text"]').closest('.form-group').slideDown();
                $('.panel-right [name="continueAction"]').closest('.form-group').slideDown();
                $('.panel-right [name="continueDelay"]').closest('.form-group').slideDown();
                $('.panel-right [name="useOverlay"]').closest('.form-group').slideDown();
                $('.panel-right [name="overlayOpacity"]').closest('.form-group').slideDown();
                $('.panel-right [name="overlayColor"]').closest('.form-group').slideDown();
                $('.panel-right [name="position"]').closest('.form-group').slideDown();
                $('.panel-right [name="startDelay"]').closest('.form-group').slideDown();
                if ($('.panel-right [name="domElement"]').val() == '') {
                    startSelectElement();
                }
                $('.panel-right [name="textStyle"]').trigger('change');
            } else if ($(this).val() == 'text') {

                $('.panel-right a[data-tab="style"]').show();

                $('.panel-right [name="continueAction"]').closest('.form-group').slideUp();
                $('.panel-right [name="codeJS"]').closest('.form-group').slideUp();
                $('.panel-right [name="footerColor"]').closest('.form-group').slideUp();
                $('.panel-right [name="headerColor"]').closest('.form-group').slideUp();
                $('.panel-right [name="domElement"]').closest('.form-group').slideUp();
                $('.panel-right [name="textStyle"]').closest('.form-group').slideUp();
                $('.panel-right [name="animation"]').closest('.form-group').slideUp();
                $('.panel-right [name="position"]').closest('.form-group').slideUp();
                $('.panel-right [name="offsetX"]').closest('.form-group').slideUp();
                $('.panel-right [name="offsetY"]').closest('.form-group').slideUp();
                $('.panel-right [data-tab="buttons"]').fadeOut();
                $('.panel-right [name="text"]').closest('.form-group').slideDown();
                $('.panel-right [name="headerText"]').closest('.form-group').slideDown();
                $('.panel-right [name="entryAnimation"]').closest('.form-group').slideDown();
                $('.panel-right [name="continueDelay"]').closest('.form-group').slideDown();
                $('.panel-right [name="useOverlay"]').closest('.form-group').slideDown();
                $('.panel-right [name="overlayOpacity"]').closest('.form-group').slideDown();
                $('.panel-right [name="overlayColor"]').closest('.form-group').slideDown();
                $('.panel-right [name="headerText"]').closest('.form-group').slideDown();
                $('.panel-right [name="headerTextColor"]').closest('.form-group').slideDown();
                $('.panel-right [name="headerTextSize"]').closest('.form-group').slideDown();
                $('.panel-right [name="startDelay"]').closest('.form-group').slideDown();
            } else if ($(this).val() == 'executeJS') {
                $('.panel-right [name="useOverlay"]').removeAttr('checked');

                $('.panel-right a[data-tab="style"]').hide();
                $('.panel-right a[data-tab="buttons"]').hide();
                $('.panel-right [name="continueAction"]').closest('.form-group').slideUp();
                $('.panel-right [name="domElement"]').closest('.form-group').slideUp();
                $('.panel-right [name="text"]').closest('.form-group').slideUp();
                $('.panel-right [name="headerText"]').closest('.form-group').slideUp();
                $('.panel-right [name="headerTextSize"]').closest('.form-group').slideUp();
                $('.panel-right [name="continueDelay"]').closest('.form-group').slideDown();
                $('.panel-right [name="startDelay"]').closest('.form-group').slideDown();

                $('.panel-right [name="codeJS"]').closest('.form-group').slideDown();
                $('.panel-right [name="url"]').closest('.form-group').slideDown();

            } else if ($(this).val() == 'redirection') {
                $('.panel-right [name="useOverlay"]').removeAttr('checked');

                $('.panel-right a[data-tab="style"]').hide();
                $('.panel-right a[data-tab="buttons"]').hide();
                $('.panel-right [name="continueAction"]').closest('.form-group').slideUp();
                $('.panel-right [name="domElement"]').closest('.form-group').slideUp();
                $('.panel-right [name="text"]').closest('.form-group').slideUp();
                $('.panel-right [name="headerText"]').closest('.form-group').slideUp();
                $('.panel-right [name="headerTextSize"]').closest('.form-group').slideUp();
                $('.panel-right [name="continueDelay"]').closest('.form-group').slideUp();
                $('.panel-right [name="codeJS"]').closest('.form-group').slideUp();
                $('.panel-right [name="startDelay"]').closest('.form-group').slideDown();

                $('.panel-right [name="url"]').closest('.form-group').slideDown();
            }
        });



        $('.panel-right [data-tab="buttons"] a[data-action="createButton"]').on('click', createNewButton);
    }
    function addStepToManager(step) {

        var newStep = $('<div class="wutb_stepBloc"><div class="wutb_stepBlocWrapper"><h4>' + step.settings.title + '</h4></div>' +
                '<a href="javascript:" class="wutb_btnEdit" title="' + wutb_data.texts['tip_editStep'] + '"><span class="fas fa-pencil-alt"></span></a>' +
                '<a href="javascript:" class="wutb_btnSup" title="' + wutb_data.texts['tip_delStep'] + '"><span class="fas fa-trash"></span></a>' +
                '<a href="javascript:" class="wutb_btnDup" title="' + wutb_data.texts['tip_duplicateStep'] + '"><span class="fas fa-copy"></span></a>' +
                '<a href="javascript:" class="wutb_btnLink" title="' + wutb_data.texts['tip_linkStep'] + '"><span class="fas fa-link"></span></a>' +
                '<a href="javascript:" class="wutb_btnStart" title="' + wutb_data.texts['tip_flagStep'] + '"><span class="fas fa-flag"></span></a></div>');
        if (step.start) {
            newStep.find('.wutb_btnStart').addClass('wutb_selected');
            newStep.addClass('wutb_selected');
        }
        if (step.elementID) {
            newStep.attr('id', step.elementID);
        } else {
            newStep.uniqueId();
        }

        newStep.children('a[title]').tooltip({
            container: '.wutb',
            placement: 'bottom',
            template: '<div class="tooltip bottom"  role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'

        });
        newStep.children('a.wutb_btnEdit').on('click', function () {
            $('#wutb_mainPanel > .tooltip').remove();
            editStep($(this).parent().attr('data-stepid'));
        });
        newStep.children('a.wutb_btnLink').on('click', function () {
            $('#wutb_mainPanel > .tooltip').remove();
            startLink($(this).parent().attr('data-stepid'));
        });
        newStep.children('a.wutb_btnSup').on('click', function () {
            $('#wutb_mainPanel > .tooltip').remove();
            askDeleteStep($(this).parent().attr('data-stepid'));
        });
        newStep.children('a.wutb_btnDup').on('click', function () {
            $('#wutb_mainPanel > .tooltip').remove();
            duplicateStep($(this).parent().attr('data-stepid'));
        });
        newStep.children('a.wutb_btnStart').on('click', function () {
            $('#wutb_mainPanel > .tooltip').remove();
            $('.wutb_stepBloc').removeClass('wutb_selected');
            $('.wutb_btnStart').removeClass('wutb_selected');
            $(this).addClass('wutb_selected');
            $(this).closest('.wutb_stepBloc').addClass('wutb_selected');
            for (var i = 0; i < currentTour.steps.length; i++) {
                currentTour.steps[i].start = false;
            }
            var step = getStepByID(parseInt($(this).parent().attr('data-stepid')));
            step.start = true;
        });
        newStep.draggable({
            containment: "parent",
            handle: ".wutb_stepBlocWrapper",
            stop: function (event, ui) {

                var step = getStepByID(ui.helper.attr('data-stepid'));
                if (step) {
                    step.position = [ui.position.left, ui.position.top];
                }
            }
        });
        newStep.children('.wutb_stepBlocWrapper').on('click', function () {
            if (isLinking) {
                stopLink(newStep);
            }
        });
        var posX = 80, posY = 80;
        if (step.position) {
            posX = step.position[0];
            posY = step.position[1];
        } else {
            posX = $('#wutb_stepsOverflow').scrollLeft() + $('#wutb_stepsOverflow').width() / 2 - 64;
            posY = $('#wutb_stepsOverflow').scrollTop() + $('#wutb_stepsOverflow').height() / 2 - 64;
        }
        newStep.hide();
        $('#wutb_stepsContainer').append(newStep);
        newStep.css({
            left: (posX) + 'px',
            top: posY + 'px'
        });
        newStep.fadeIn();
        setTimeout(updateStepsDesign, 250);
        $('.wutb_btnWinClose').parent().on('click', function () {
            closeWin($(this).parents('.wutb_window'));
        });

        newStep.attr('data-stepid', step.id);
    }


    function closeWin(win) {
        win.fadeOut();

        $('#wutb_btnHeaderClose').unbind('wutb_close');
        $('#wutb_btnHeaderClose').fadeOut();
        setTimeout(function () {
            updateStepsDesign();
        }, 250);
    }

    function duplicateStep(stepID) {
        var step = getStepByID(stepID);
        if (step) {
            var newStep = JSON.parse(JSON.stringify(step));
            newStep.id = generateID();
            newStep.settings.title += ' (1)';
            newStep.start = false;
            newStep.position = [newStep.position[0] + 100, newStep.position[1]];
            currentTour.steps.push(newStep);
            addStepToManager(newStep);
        }
    }

    function startLink(stepID) {
        isLinking = true;
        currentLinkIndex = currentTour.links.length;
        currentTour.links.push({
            originID: stepID,
            destinationID: null,
            conditions: []
        });
    }

    function stopLink(newStep) {
        isLinking = false;
        var chkLink = false;
        if (newStep.attr('data-stepid') != currentTour.links[currentLinkIndex].originID) {
            $.each(currentTour.links, function () {
                if (this.originID == currentTour.links[currentLinkIndex].originID && this.destinationID == newStep.attr('data-stepid')) {
                    chkLink = this;
                }
            });
            if (!chkLink) {
                currentTour.links[currentLinkIndex].destinationID = newStep.attr('data-stepid');
                currentTour.links[currentLinkIndex].id = generateID();
                tourModified = true;
            } else {
                currentTour.links = $.grep(currentTour.links, function (value) {
                    return value != chkLink;
                });
            }
        }
    }

    function removeStep(stepID) {
        tourModified = true;
        var i = 0;
        $('.wutb_stepBloc[data-stepid="' + stepID + '"]').remove();
        currentTour.steps = jQuery.grep(currentTour.steps, function (step) {
            return step.id != stepID;
        });
    }
    function editStep(stepID) {
        currentStep = getStepByID(stepID);
        if (currentStep != false) {
            iframeLoaded = false;
            var targetUrl = wutb_data.websiteUrl;
            if (currentStep.settings.url != '') {
                targetUrl += currentStep.settings.url;
            }
            if (targetUrl.indexOf('?') > -1) {
                targetUrl += '&stepPreview=' + stepID;
            } else {
                targetUrl += '?stepPreview=' + stepID;
            }

            $('#wutb_frameLoader').fadeIn();
            $('#wutb_elementSelectionFrame').attr('src', targetUrl);
            $('#wutb_elementSelectionPanel').fadeIn();
            $('.panel-center .panel-body').mCustomScrollbar('scrollTo', 'top');
            $('.panel-top .panel-header >.panel-header-title >  [data-tab]').hide();
            $('.panel-top .panel-header >.panel-header-title >  [data-tab="step"]').show();
            $('.panel-bottom').addClass('hidden');
            $('.panel-top').removeClass('hidden');
            setTimeout(function () {
                $('#wutb_stepManagerPanel').hide();
            }, 380);
            
             setTimeout(updateStepPreview, 300);
            setTimeout(function () {
                $(window).trigger('resize');
            }, 600);
            codeJSEditor.setValue(currentStep.settings.codeJS);


            $('.panel-right [name]').each(function () {
                if ($(this).is('[type="checkbox"]')) {
                    var value = 0;
                    eval('value = currentStep.settings.' + $(this).attr('name') + ';');
                    if (value) {
                        $(this).attr('checked', 'checked');
                    } else {
                        $(this).removeAttr('checked');
                    }
                } else {
                    var value = '';
                    eval('value = currentStep.settings.' + $(this).attr('name') + ';');
                    if ($(this).attr('name') == 'text') {
                        value = value.replace(/<br\/>/g, '\n');
                        value = value.replace(/<br \/>/g, '\n');

                    }
                    $(this).val(value);
                    if ($(this).is('[data-slider]')) {
                        $(this).next('.wutb_slider').slider('option', 'value', value);
                    }
                }
            });
            $('.panel-right [name]').trigger('change');
            $('#stepButtonsTable tbody').html('');
            for (var i = 0; i < currentStep.buttons.length; i++) {
                addButtonRow(currentStep.buttons[i]);
            }

            $('.panel-right  .panel-body .panel-tabs a[data-tab="settings"]').first().trigger('click');


        }
    }

    function openWinLicense() {
        $('#wutb_winLicense').modal('show');
        if ($('#wutb_winLicense [name="purchaseCode"]').val() == '') {
            $('#wutb_winLicense [name="purchaseCode"]').addClass('is-invalid');
        } else {
            $('#wutb_winLicense [name="purchaseCode"]').removeClass('is-invalid');
        }
    }

    function verifyLicense() {
        var purchaseCode = $('#wutb_winLicense [name="purchaseCode"]').val();
        var error = false;
        $('#wutb_winLicense [name="purchaseCode"]').removeClass('is-invalid');
        if (!error) {
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: 'wutb_verifyPurchaseCode',
                    purchaseCode: purchaseCode
                },
                success: function (rep) {
                    if (rep.trim() != 1) {
                        $('#wutb_winLicense').modal('hide');
                        $('#wutb_winLicense [name="purchaseCode"]').removeClass('is-invalid');
                    } else {
                        $('#wutb_winLicense [name="purchaseCode"]').val('');
                        $('#wutb_winLicense [name="purchaseCode"]').addClass('is-invalid');
                    }
                }
            });
        }

    }

    function getStepByID(stepID) {
        var rep = false;
        $.each(currentTour.steps, function (i) {
            if (this.id == stepID) {
                rep = this;
            }
        });
        return rep;
    }
    function generateID() {
        currentTour.indexID++;
        return currentTour.indexID;
    }
    function updateStepsDesign() {
        $('#wutb_stepsCanvas').attr('width', $('#wutb_stepsContainer').innerWidth());
        $('#wutb_stepsCanvas').attr('height', $('#wutb_stepsContainer').innerHeight() - 18);
        $('#wutb_stepsCanvas').css({
            width: $('#wutb_stepsContainer').outerWidth(),
            height: $('#wutb_stepsContainer').innerHeight() - 18
        });
        $('.wutb_stepBloc > .wutb_stepBlocWrapper > h4').each(function () {
            $(this).css('margin-top', 0 - $(this).height() / 2);
        });
    }

    function linkLightStep(stepID) {
        $('.wutb_stepBloc[data-stepid="' + stepID + '"]').addClass('linkLight');
        setTimeout(function () {
            $('.wutb_stepBloc[data-stepid="' + stepID + '"]').removeClass('linkLight');
        }, 1000);
    }
    function addConditionInteraction($panel, data) {

        var $item = $('<tr class="wutb_conditionItem"></tr>');
        var $select = $('<select class="wutb_conditionSelect form-control"></select>');
        $select.append('<option value="currentURL">' + wutb_data.texts['Current URL'] + '</option>');
        $select.append('<option value="currentDate">' + wutb_data.texts['Current Date'] + '</option>');
        $select.append('<option value="wpUser">' + wutb_data.texts['Current WP user'] + '</option>');
        
        for (var i = 0; i < currentTour.steps.length; i++) {
            var step = currentTour.steps[i];
            for (var j = 0; j < step.buttons.length; j++) {
                var btn = step.buttons[j];
                $select.append('<option value="btn_' + btn.id + '">' + wutb_data.texts['Button'] + ' "' + btn.title + '"</option>');
            }
        }
        var $operator = $('<select class="wutb_conditionoperatorSelect form-control"></select>');
        $select.change(function () {

            $operator.find('option').remove();
            if ($select.val() && $select.val() == 'currentDate') {
                $operator.append('<option value="equals" data-variable="date">' + wutb_data.texts['Is equals to'] + '</option>');
                $operator.append('<option value="different" data-variable="date">' + wutb_data.texts['Is different than'] + '</option>');
                $operator.append('<option value="superior" data-variable="date">' + wutb_data.texts['Is superior to'] + '</option>');
                $operator.append('<option value="inferior" data-variable="date">' + wutb_data.texts['Is inferior to'] + '</option>');
                $operator.append('<option value="monthEquals" data-variable="month">' + wutb_data.texts['Is month equals to'] + '</option>');
                $operator.append('<option value="monthDifferent" data-variable="month">' + wutb_data.texts['Is month different than'] + '</option>');
                $operator.append('<option value="monthInferior" data-variable="month">' + wutb_data.texts['Is month inferior to'] + '</option>');
                $operator.append('<option value="monthSuperior" data-variable="month">' + wutb_data.texts['Is month superior to'] + '</option>');

            } else if ($select.val() && $select.val() == 'currentURL') {
                $operator.append('<option value="equals" data-variable="text">' + wutb_data.texts['Is equals to'] + '</option>');
                $operator.append('<option value="different" data-variable="text">' + wutb_data.texts['Is different than'] + '</option>');
                $operator.append('<option value="contains" data-variable="text">' + wutb_data.texts['Contains'] + '</option>');
                $operator.append('<option value="dontcontain" data-variable="text">' + wutb_data.texts['Does not Contain'] + '</option>');
            } else if ($select.val() && $select.val() == 'wpUser') {
                $operator.append('<option value="usernameIs" data-variable="text">' + wutb_data.texts['Username is'] + '</option>');
                $operator.append('<option value="lastNameIs" data-variable="text">' + wutb_data.texts['Last name is'] + '</option>');
                $operator.append('<option value="emailIs" data-variable="text">' + wutb_data.texts['Email is'] + '</option>');
                $operator.append('<option value="roleIs" data-variable="text">' + wutb_data.texts['Role is'] + '</option>');
                
            } else if ($select.val().indexOf('btn_') == 0) {
                $operator.append('<option value="isSelected">' + wutb_data.texts['Is selected'] + '</option>');
                $operator.append('<option value="isNotSelected">' + wutb_data.texts['Is not selected'] + '</option>');
            }
            if ($operator.children().length == 0) {
                $operator.slideUp();
            } else {
                $operator.slideDown();
            }
            $operator.trigger('change');
        });

        if (data) {
            $select.val(data.elementID);
            $select.trigger('change');
        }
        $operator.change(function () {
            conditionsUpdateFields($(this));
        });
        var $col1 = $('<td></td>');
        $col1.append($select);
        $item.append($col1);
        var $col2 = $('<td></td>');
        $col2.append($operator);
        $item.append($col2);
        $item.append('<td></td><td><a href="javascript:" class="wutb_conditionDelBtn btn btn-circle btn-danger" ><span class="fas fa-trash"></span></a> </td>');
        $item.find('.wutb_conditionDelBtn').on('click', function () {
            conditionRemove(this);
        });
        $select.trigger('change');
        if (data) {
            $operator.val(data.action);
            $operator.change();
            if (data.value) {
                $operator.closest('.wutb_conditionItem').find('.wutb_conditionValue').val(data.value);
            }
            setTimeout(function () {
                conditionsUpdateFields($operator, data);
                if (data.value) {
                    $operator.closest('.wutb_conditionItem').find('.wutb_conditionValue').val(data.value);
                }
            }, 500);
        }
        $panel.find('#wutb_conditionsTable tbody').append($item);
    }

    function conditionRemove(btn) {
        var $tr = $(btn).closest('.wutb_conditionItem');
        $tr.slideUp(200);
        setTimeout(function () {
            $tr.remove();
        }, 230);
    }
    function conditionsUpdateFields($operatorSelect, data) {
        $operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionValue').parent().remove();
        $operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionValue').remove();
        if ($operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionoperatorSelect option:selected').attr('data-variable') == "text") {
            if ($operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionValue').length == 0) {
                $operatorSelect.closest('.wutb_conditionItem').children('td:eq(2)').html('<div><input type="text" placeholder="' + wutb_data.texts['My text here'] + '" class="wutb_conditionValue form-control" /> </div>');
            }
        }

        if ($operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionoperatorSelect option:selected').attr('data-variable') == "number") {
            if ($operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionValue').length == 0) {
                $operatorSelect.closest('.wutb_conditionItem').children('td:eq(2)').html('<div><input type="number" value="0" class="wutb_conditionValue form-control" /> </div>');
                $operatorSelect.closest('.wutb_conditionItem').children('td:eq(2)').find('input').focus();
            }
        }
        if ($operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionoperatorSelect option:selected').attr('data-variable') == "date") {
            if ($operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionValue').length == 0) {
                $operatorSelect.closest('.wutb_conditionItem').children('td:eq(2)').html('<div><input type="text" step="any" class="wutb_conditionValue form-control"/> </div>');
                $operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionValue').datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            }
        }
        if ($operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionoperatorSelect option:selected').attr('data-variable') == "month") {
            if ($operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionValue').length == 0) {
                $operatorSelect.closest('.wutb_conditionItem').children('td:eq(2)').html('<div><input type="text" step="any" class="wutb_conditionValue form-control"/> </div>');
                $operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionValue').datepicker({
                    dateFormat: 'mm'
                });
            }
        }

        if ($operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionoperatorSelect option:selected').attr('data-variable') == "select") {
            var optionsSelect = '';
            var $select = $operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionSelect');
            var stepID = $select.val().substr(0, $select.val().indexOf('_'));
            var itemID = $select.val().substr($select.val().indexOf('_') + 1, $select.val().length);
            var optionsString = '';
            $.each(currentTour.steps, function () {
                if (this.id == stepID) {
                    $.each(this.items, function () {
                        if (this.id == itemID) {
                            optionsString = this.optionsValues;
                        }
                    });
                }
            });
            var optionsArray = optionsString.split('|');
            $.each(optionsArray, function () {
                var value = this;
                if (value.indexOf(';;') > 0) {
                    var valueArray = value.split(';;');
                    value = valueArray[0];
                }
                if (value.length > 0) {
                    optionsString += '<option value="' + value + '">' + value + '</option>';
                }
            });
            if ($operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionValue').length == 0) {
                $operatorSelect.closest('.wutb_conditionItem').children('td:eq(2)').html('<div><select class="wutb_conditionValue form-control">' + optionsString + '</select></div>');
            }
        }

        if (data && data.value) {
            $operatorSelect.closest('.wutb_conditionItem').find('.wutb_conditionValue').val(data.value);
        }
    }
    function getTextFromBool(isChecked) {
        if (isChecked) {
            return wutb_data.texts['Yes'];
        } else {
            return wutb_data.texts['No'];
        }
    }
    function getEditedStepData() {
        var rep = {
            tourID: currentTour.id,
            texts_font: currentTour.settings.texts_font,
            tooltip_font: currentTour.settings.tooltip_font,
            arrow_font: currentTour.settings.arrow_font,
            dialog_font: currentTour.settings.dialog_font
        };
        $('.panel-right [name]').each(function () {
            var value = '';
            if ($(this).is('[type="checkbox"]')) {
                value = $(this).is(':checked');
            } else {
                value = nl2br($(this).val());
            }
            eval('rep.' + $(this).attr('name') + '=value;');
        });
        return rep;
    }
    function updateStepPreview(enableEntry) {
        if (iframeLoaded) {
            var stepData = getEditedStepData();
            stepData.mode = 'preview';
            if (typeof (enableEntry) != 'undefined' && enableEntry) {
                stepData.enableEntry = true;
            }

            if ($('body', $('#wutb_elementSelectionFrame').contents()).is('.wutb_frontend')) {
                $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('previewStepData', stepData);
                $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewAction', 'updateStepPreview');
                $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewValue', {mode: 'preview', buttons: currentStep.buttons, settings: getEditedStepData()});
                $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').trigger('wutb_triggerAction');

            }
        }

    }
    function startSelectElement() {
        if (!currentStep) {
            $('#wutb_elementSelectionFrame').data('action', 'selectStartElement');
        } else {
            $('#wutb_elementSelectionFrame').data('action', 'selectStepElement');
        }
        $('#wutb_elementSelectionPanel').fadeIn();
        selectionInProgress = true;
        $('#wutb_stepContainer').fadeOut();
        if ($('#collapse-button').is('[aria-expanded="true"]')) {
            wpMenuWasOpen = true;
        } else {
            wpMenuWasOpen = false;
        }
        $('#wpwrap').addClass('wutb_hideWPMenu');
        layoutPanel.closePanel($('.panel-right'), layoutPanel);
        layoutPanel.closePanel($('.panel-bottom'), layoutPanel);

        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewAction', 'startElementSelection');
        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewValue', '');
        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').trigger('wutb_triggerAction');


        var changeSideLabel = wutb_data.texts['Backend'];
        var changeSideIcon = 'fas fa-user-lock';
        if ($('#wutb_elementSelectionFrame').get(0).contentWindow.location.href.indexOf(wutb_data.adminUrl) == 0) {
            changeSideLabel = wutb_data.texts['Frontend'];
            changeSideIcon = 'fas fa-users';
        }

        $('.panel-center').notification({
            html: wutb_data.texts['Navigate to the desired page and click the button below to select the desired item.'],
            style: 'primary',
            autoClose: false,
            id: 'notice_startElementSelection',            
            onShow: function(){
                $('#notice_startElementSelection').resizable({handles: "s"});
            },
            onClose: stopElementSelection,
            buttons: [{
                    icon: changeSideIcon,
                    label: changeSideLabel,
                    style: 'dark',
                    click: function () {
                        $('#wutb_elementSelectionFrame').data('action', 'selectStartElement');
                        var targetUrl = wutb_data.adminUrl;
                        if ($('#wutb_elementSelectionFrame').get(0).contentWindow.location.href.indexOf(wutb_data.adminUrl) == 0) {
                            targetUrl = wutb_data.siteUrl;
                        }
                        $('#wutb_frameLoader').fadeIn();
                        $('#wutb_elementSelectionFrame').attr('src', targetUrl);
                    }
                }, {
                    icon: changeSideIcon,
                    label: wutb_data.texts['Select an element'],
                    style: 'light',
                    click: continueSelectElement
                }]
        });
    }
    function continueSelectElement() {

        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewAction', 'continueElementSelection');
        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewValue', '');
        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').trigger('wutb_triggerAction');

        $('.panel-center').notification({
            html: wutb_data.texts['Click the target element in the page'],
            style: 'primary',
            autoClose: false,
            id: 'notice_continueElementSelection',
            onClose: stopElementSelection,           
            onShow: function(){
                $('#notice_continueElementSelection').resizable({handles: "s"});
            },
            buttons: [{
                    icon: 'fas fa-times',
                    label: wutb_data.texts['Cancel'],
                    style: 'light',
                    click: startSelectElement
                }]
        });
    }
    function stopElementSelection() {
        selectionInProgress = false;

        $('#wpwrap').removeClass('wutb_hideWPMenu');
        $('#wutb_elementSelectionFrame').data('action', '');

        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewAction', 'stopElementSelection');
        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewValue', '');
        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').trigger('wutb_triggerAction');

        if (currentStep) {
            layoutPanel.showPanel($('.panel-right'), layoutPanel);
            updateStepPreview();
        } else {
            layoutPanel.showPanel($('.panel-bottom'), layoutPanel);
        }
        $('#wutb_elementSelectionPanel').fadeOut();
    }

    function validElementSelection($element) {
        selectionInProgress = false;
        $('#notice_continueElementSelection').fadeOut();
        setTimeout(function () {
            $('#notice_continueElementSelection').remove();
            $('.panel-center').notification({
                html: wutb_data.texts['Do you want to select this element ?'],
                style: 'primary',
                autoClose: false,
                id: 'notice_validElementSelection',
                onClose: stopElementSelection,
                buttons: [{
                        icon: 'fas fa-check',
                        label: wutb_data.texts['Yes'],
                        style: 'light',
                        click: function () {
                            confirmSelectElement($element);
                        }
                    }, {
                        icon: 'fas fa-times',
                        label: wutb_data.texts['No'],
                        style: 'dark',
                        click: continueSelectElement
                    }]
            });
        }, 500);
    }
    function confirmSelectElement($element) {
        if (currentStep) {
            $('.panel-right [name="url"]').val($('#wutb_elementSelectionFrame').get(0).contentWindow.location.href).trigger('change');
            $('.panel-right [name="domElement"]').val(getElementPath($element)).trigger('change');
            layoutPanel.showPanel($('.panel-right'), layoutPanel);
            setTimeout(updateStepPreview, 300);
            setTimeout(function () {
                $(window).trigger('resize');
            }, 600);
            updateStepPreview();

        } else {
            $('.panel-bottom [name="tourDomElement"]').val(getElementPath($element)).trigger('change');
            $('#wutb_elementSelectionPanel').fadeOut();
            layoutPanel.showPanel($('.panel-bottom'), layoutPanel);

        }

        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewAction', 'confirmElementSelection');
        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewValue', '');
        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').trigger('wutb_triggerAction');

        $('#wpwrap').removeClass('wutb_hideWPMenu');
    }

    function getElementPath(el) {
        var path = '';
        if (jQuery(el).length > 0 && typeof (jQuery(el).prop('tagName')) != "undefined") {
            if (!jQuery(el).attr('id') || jQuery(el).attr('id').substr(0, 9) == 'ultimate-') {
                path = '>' + jQuery(el).prop('tagName') + ':nth-child(' + (jQuery(el).index() + 1) + ')' + path;
                path = getElementPath(jQuery(el).parent()) + path;
            } else {
                path += '#' + jQuery(el).attr('id');
            }
        }
        return path;
    }
    function closeStepEdition() {
        tourModified = true;
        layoutPanel.closePanel($('.panel-right'), layoutPanel);
        $('.panel-bottom').removeClass('hidden');
        $('.wutb-notification').remove();
        if ($('body', $('#wutb_elementSelectionFrame').contents()).is('.wutb_frontend')) {

            $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewAction', 'stopElementSelection');
            $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewValue', '');
            $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').trigger('wutb_triggerAction');

        }
        var stepData = currentStep.settings;
        $('#wpwrap').removeClass('wutb_hideWPMenu');
        $('.panel-right [name]').each(function () {
            var value = '';
            if ($(this).is('[type="checkbox"]')) {
                if ($(this).is(':checked')) {
                    value = 1;
                } else {
                    value = 0;
                }
            } else {
                value = $(this).val();
            }
            eval('currentStep.settings.' + $(this).attr('name') + '=value;');
        });
        stepData.codeJS = codeJSEditor.getValue();
        $('#wutb_elementSelectionPanel').fadeOut();
        $('#wutb_stepManagerPanel').fadeIn();
        $('.panel-top [data-tab]').hide();
        $('.panel-top [data-tab="tour"]').show();
        $('.wutb_stepBloc[data-stepid="' + currentStep.id + '"] h4').html(stepData.title);
        currentStep = false;
        currentStepIndex = 0;
        updateStepsDesign();
    }
    function saveTour(callback) {
        sessionStorage.removeItem('wutb_viewedTour_' + currentTour.id);
        tourModified = false;
        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'wutb_saveTour',
                tourID: currentTour.id,
                tourData: JSON.stringify(currentTour)
            },
            success: function (rep) {
                $('.panel-center').notification({
                    html: wutb_data.texts['The tour was correctly saved'],
                    style: 'primary'
                });
                if (typeof (callback) == 'function') {
                    callback();
                }
            }
        });
    }
    function updateEditedStepData() {
        currentStep.settings = getEditedStepData();
    }

    function startSelectUrl() {
        layoutPanel.closePanel($('.panel-right'), layoutPanel);
        layoutPanel.closePanel($('.panel-bottom'), layoutPanel);

        if ($('#collapse-button').is('[aria-expanded="true"]')) {
            wpMenuWasOpen = true;
        } else {
            wpMenuWasOpen = false;
        }
        $('#wpwrap').addClass('wutb_hideWPMenu');

        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewAction', 'startPageSelection');
        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('wutb_lastPreviewValue', '');
        $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').trigger('wutb_triggerAction');

        var changeSideLabel = wutb_data.texts['Backend'];
        var changeSideIcon = 'fas fa-user-lock';
        if ($('#wutb_elementSelectionFrame').get(0).contentWindow.location.href.indexOf(wutb_data.adminUrl) == 0) {
            changeSideLabel = wutb_data.texts['Frontend'];
            changeSideIcon = 'fas fa-users';
        }
        $('.panel-center').notification({
            html: wutb_data.texts['Navigate to the desired page then click the button below to select it.'],
            style: 'primary',
            autoClose: false,
            id: 'notice_startPageSelection',
            onClose: stopElementSelection,
            buttons: [{
                    icon: changeSideIcon,
                    label: changeSideLabel,
                    style: 'dark',
                    click: function () {
                        $('#wutb_elementSelectionFrame').data('action', 'selectPage');
                        var targetUrl = wutb_data.adminUrl;
                        if ($('#wutb_elementSelectionFrame').get(0).contentWindow.location.href.indexOf(wutb_data.adminUrl) == 0) {
                            targetUrl = wutb_data.siteUrl;
                        }
                        $('#wutb_frameLoader').fadeIn();
                        $('#wutb_elementSelectionFrame').attr('src', targetUrl);
                    }
                }, {
                    icon: 'fas fa-hand-point-up',
                    label: wutb_data.texts['Select this page'],
                    style: 'light',
                    click: validSelectPage
                }]
        });
    }

    function validSelectPage() {
        if (selectUrlMode == 'step') {
            $('.panel-right [name="url"]').val($('#wutb_elementSelectionFrame').get(0).contentWindow.location.href).trigger('change');
            updateStepPreview();
            layoutPanel.showPanel($('.panel-right'), layoutPanel);
        } else {
            $('#wutb_elementSelectionPanel').fadeOut();

            $('#wutb_bottomSettings [name="startURL"]').val($('#wutb_elementSelectionFrame').get(0).contentWindow.location.href).trigger('change');
            layoutPanel.showPanel($('.panel-bottom'), layoutPanel);
        }

        $('#wpwrap').removeClass('wutb_hideWPMenu');
    }
    function createNewButton() {

        $('#wutb_mainPanel > .tooltip').remove();
        var btn = {
            id: generateID(),
            title: wutb_data.texts['My button'],
            icon: 'fas fa-check',
            finalPage: '',
            action: 'nextStep',
            backgroundColor: '#1abc9c',
            textColor: '#ffffff'
        };
        currentStep.buttons.push(btn);
        addButtonRow(btn);
        updateStepPreview();
    }
    function addButtonRow(btn) {
        var $tr = $('<tr data-id="' + btn.id + '"><td>' + btn.title + '</td><td class="text-right"><a href="javascript:" class="btn btn-primary btn-circle" data-action="editButton"><span class="fas fa-pencil-alt"></span></a><a href="javascript:" data-action="deleteButton" class="btn btn-danger btn-circle"><span class="fas fa-trash"></span></a></td></tr>');
        $('#stepButtonsTable tbody').append($tr);
        $tr.find('a[data-action="editButton"]').on('click', function () {
            editButton($(this).closest('tr').attr('data-id'));
        });
        $tr.find('a[data-action="deleteButton"]').on('click', function () {
            deleteButton($(this).closest('tr').attr('data-id'));
        });
    }
    function getButtonByID(btnID) {
        var rep = false;
        for (var i = 0; i < currentStep.buttons.length; i++) {
            if (currentStep.buttons[i].id == btnID) {
                rep = currentStep.buttons[i];
            }
        }
        return rep;
    }
    function editButton(btnID) {
        var btn = getButtonByID(btnID);
        if (btn) {
            $('#wutb_winEditButton [name]').each(function () {
                if ($(this).is('[type="checkbox"]')) {
                    var value = 0;
                    eval('value = btn.' + $(this).attr('name') + ';');
                    if (value) {
                        $(this).attr('checked', 'checked');
                    } else {
                        $(this).removeAttr('checked');
                    }
                } else {
                    eval('$(this).val(btn.' + $(this).attr('name') + ').trigger("change");');
                }
            });
            $('#wutb_winEditButton').data('buttonID', btnID);
            $('#wutb_winEditButton').modal('show');
        }
    }
    function deleteButton(btnID) {
        currentStep.buttons = $.grep(currentStep.buttons, function (btn) {
            return btn.id != btnID;
        });
        $('#stepButtonsTable tbody tr[data-id="' + btnID + '"]').remove();
        updateStepPreview();
    }
    function saveButton() {
        var btnID = $('#wutb_winEditButton').data('buttonID');
        var btn = getButtonByID(btnID);
        if (btn) {
            $('#wutb_winEditButton [name]').each(function () {
                eval('btn.' + $(this).attr('name') + '= $(this).val();');
            });
        }
        $('#stepButtonsTable [data-id="' + btn.id + '"]').find('td').first().html(btn.title);
        $('#wutb_winEditButton').modal('hide');
        updateStepPreview();

    }
    function previewTourByID(tourID) {

        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'wutb_previewTourByID',
                tourID: tourID
            },
            success: function (startURL) {
                var url = wutb_data.siteUrl + startURL;
                if (url.indexOf('?') > -1) {
                    url += '&tourPreview=' + tourID;
                } else {
                    url += '?tourPreview=' + tourID;
                }

                var win = window.open(url, '_blank');
                win.focus();
            }
        });
    }
    function previewTour() {

        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'wutb_previewTour',
                tourID: currentTour.id,
                tourData: JSON.stringify(currentTour)
            },
            success: function (tour) {
                var url = wutb_data.siteUrl;
                url += currentTour.settings.startURL;
                if (url.indexOf('?') > -1) {
                    url += '&tourPreview=' + currentTour.id;
                } else {
                    url += '?tourPreview=' + currentTour.id;
                }

                var win = window.open(url, '_blank');
                win.focus();
            }
        });

    }
    function askSaveBeforeLeave(destinationUrl) {
        $('.panel-center').notification({
            html: wutb_data.texts['Do you want to save the tour before leaving ?'],
            style: 'primary',
            autoClose: false,
            id: 'notice_saveBeforeLeave',
            buttons: [{
                    icon: 'fa-save',
                    label: wutb_data.texts['Yes'],
                    style: 'light',
                    click: function () {
                        saveTour(function () {
                            window.document.location.href = destinationUrl;
                        });
                    }
                }, {
                    icon: 'fa-times',
                    label: wutb_data.texts['No'],
                    style: 'dark',
                    click: function () {
                        window.document.location.href = destinationUrl;
                    }
                }]
        });
    }
    function updateTourSettings() {
        $('#wutb_bottomSettings [name]').each(function () {
            var value = '';
            if ($(this).is('[type="checkbox"]')) {
                value = $(this).is(':checked');
            } else {
                value = nl2br($(this).val());
            }
            eval('currentTour.settings.' + $(this).attr('name') + '=value;');
        });

    }

    function updateStepSetting($field) {
        var value = false;
        var name = $field.attr('name');
        if ($field.is('[type="checkbox"]')) {
            value = $field.is(':checked');
        } else if ($field.is('.wutb_slider')) {
            value = $field.slider('value');
            name = $field.attr('data-name');

        } else {
            value = $field.val();
        }
        if ($field.attr('name') == 'text') {
            value = value.replace(/\n/g, '<br\/>');
        }
        if ($('body', $('#wutb_elementSelectionFrame').contents()).is('.wutb_frontend')) {

            $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('lastSettingKey', name);
            $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').data('lastSettingValue', value);
            $('#wutb_elementSelectionFrame')[0].contentWindow.jQuery('body').trigger('triggerUpdateStepSetting');

        }
    }

    function askDeleteTour(tourID) {
        $('.panel-center').notification({
            html: wutb_data.texts['Do you want to delete this tour ?'],
            style: 'primary',
            autoClose: false,
            id: 'notice_deleteTour',
            onClose: stopElementSelection,
            buttons: [{
                    icon: 'fas fa-check',
                    label: wutb_data.texts['Yes'],
                    style: 'light',
                    click: function () {
                        deleteTour(tourID);
                    }
                }, {
                    icon: 'fas fa-times',
                    label: wutb_data.texts['No'],
                    style: 'dark'
                }]
        });
    }
    function askDeleteStep(stepID) {
        $('.panel-center').notification({
            html: wutb_data.texts['Do you want to delete this step ?'],
            style: 'primary',
            autoClose: false,
            id: 'notice_deleteStep',
            onClose: stopElementSelection,
            buttons: [{
                    icon: 'fas fa-check',
                    label: wutb_data.texts['Yes'],
                    style: 'light',
                    click: function () {
                        removeStep(stepID);
                    }
                }, {
                    icon: 'fas fa-times',
                    label: wutb_data.texts['No'],
                    style: 'dark'
                }]
        });
    }
    function showLoader() {
        $('#wutb_mainPanel > .tooltip').remove();
        $('#wutb_loader').fadeIn();
    }
    function hideLoader() {
        $('#wutb_loader').fadeOut();
    }
    function exportTours() {
        showLoader();
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'wutb_exportTours'
            },
            success: function (rep) {
                hideLoader();
                if (rep == '1') {
                    jQuery('#wutb_winExport').modal('show');
                } else {
                    alert(wutb_data.texts['errorExport']);
                }
            }
        });
    }
    function importTours() {
        $('#wutb_winImport').modal('show');
    }
    function importToursJson() {
        showLoader();
        jQuery('#wutb_winImport').modal('hide');
        var formData = new FormData(jQuery('#wutb_winImportForm')[0]);

        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            xhr: function () {
                var myXhr = jQuery.ajaxSettings.xhr();
                return myXhr;
            },
            success: function (rep) {
                if (rep != '1') {
                    hideLoader();
                    alert(wutb_data.texts['errorImport']);
                } else {
                    document.location.href = document.location.href;
                }
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        });
    }
})(jQuery);
