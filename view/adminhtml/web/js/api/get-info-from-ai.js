define([
    'jquery',
    'mage/url',
    'Magento_Ui/js/modal/alert',
    'mage/translate'
], function ($, url, alert) {
    'use strict';

    return function (name, field, type, keywordsExtra = null) {
        $('body').trigger('processStart');
        var updateUrl = url.build('/admin/expandingweb_ai/api/getresponse');
        var data = {
            name: name,
            field_index: field,
            type: type,
            keywords_extra: keywordsExtra
        };

        return $.post(updateUrl, data).always(function(json) {
            if (!json.answer) {
                var message = 'An error occurred, please try again or check your Token';
                message += ' at Stores -> Configuration -> Expanding Web -> Ai -> General Configuration -> Token.';
                alert({
                    title: $.mage.__('Error Occurred'),
                    content: $.mage.__(message)
                });
            } else {
                if (!json.success) {
                    alert({
                        title: $.mage.__('Error Occurred'),
                        content: json.answer
                    });
                }
            }
            $('body').trigger('processStop');
        });
    };
});
