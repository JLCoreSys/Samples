var htmlCheckerRunning = false;
var htmlCheckerValid = true;

(function ($) {
    $.fn.htmlChecker = function (options) {
        var settings = $.extend({}, $.fn.htmlChecker.defaults, options);

        if (!settings.check) {
            $(this).focusout(function (event) {
                if (htmlCheckerRunning) {
                    setTimeout($(this).blur(), 500);
                }
                var element = $(this);
                var value = element.val();
                settings = $.extend({}, settings, {'value': value});
                if (value.length >= 5) {
                    htmlCheckerValid = true;
                    element.htmlCheckerCheck(settings);
                }
            });
        } else {
            var element = $(this);
            var value = element.val();
            settings = $.extend({}, settings, {'value': value});
            if (value.length >= 5) {
                htmlCheckerValid = true;
                element.htmlCheckerCheck(settings);
            }
        }

        return this;
    };

    $.fn.htmlCheckerValid = function () {
        return htmlCheckerValid;
    }

    $.fn.htmlChecker.defaults = {
        url: '',
        acceptable: {},
        url_type: 'post',
        value: '',
        check: false
    };

    $.fn.htmlCheckerCheck = function (options) {
        var settings = $.extend({}, $.fn.htmlChecker.defaults, options);
        var element = $(this);
        htmlCheckerRunning = true;

        $.ajax({
            'url': settings.url,
            'type': settings.url_type,
            'data': { string: settings.value, 'acceptable': settings.acceptable },
            success: function (res) {
                res = JSON.parse(res);
                settings.res = res;
                if (res.success) {
                    if (res.has_html) {
                        $('.modal-window .block-content .block-footer>button').eq(0).addClass('disabled');
                        element.checkHtmlConfirm(settings);
                    } else {
                        htmlCheckerRunning = false;
                        htmlCheckerValid = true;
                        $('.modal-window .block-content .block-footer>button').eq(0).removeClass('disabled');
                    }
                }
            }
        })

        return this;
    }

    $.fn.checkHtmlConfirm = function (options) {
        var settings = $.extend({}, $.fn.htmlChecker.defaults, options);
        var element = $(this);

        $.modal({
            title: 'Confirmation',
            content: '<p class="message error">Your input contains invalid HTML.<br><br>Would you like us to auto correct your input?</p>',
            closeButton: false,
            resizeOnLoad: true,
            width: 650,
            buttons: {
                'Yes Please': function (win) {
                    element.val(settings.res.clean);
                    $('.modal-window .block-content .block-footer>button').eq(0).removeClass('disabled');
                    htmlCheckerRunning = false;
                    htmlCheckerValid = true;
                    win.closeModal();
                },
                'No Thanks': function (win) {
                    $('.modal-window .block-content .block-footer>button').eq(0).addClass('disabled');
                    htmlCheckerValid = false;
                    htmlCheckerRunning = false;
                    win.closeModal();
                }
            }
        });

        return this;
    };
})(jQuery);