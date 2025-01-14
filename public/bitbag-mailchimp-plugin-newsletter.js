import { $ } from 'jquery';
(function ($) {
    'use strict';

    $.fn.extend({
        joinNewsletter: function () {
            var form = $(this);
            form.submit(function (event) {
                event.preventDefault();

                var successElement = form.find('.success-element');
                var input = form.find('input[type=text]');
                var validationElement = form.find('.validation-element');

                successElement.text('');
                validationElement.text('');

                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: form.serialize()
                })
                    .done(function (response) {
                        if (response.hasOwnProperty('message')) {
                            successElement.html(response.message);
                            input.val('');
                        }
                    })
                    .fail(function (response) {
                        if (!response.responseJSON) {
                            var message = 'An unexpected error occurred. Please try again later.';

                            validationElement.text(message);
                        } else if (response.responseJSON.hasOwnProperty('errors')) {
                            var errors = $.parseJSON(response.responseJSON.errors);
                            var message = '';

                            $(errors).each(function (key, value) {
                                message += value + " ";
                            });

                            validationElement.text(message);
                        }
                    });
            });
        }
    });
})(jQuery);
