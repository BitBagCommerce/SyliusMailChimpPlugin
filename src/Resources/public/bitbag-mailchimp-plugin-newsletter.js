(function ($) {
    'use strict';

    $.fn.extend({
        joinNewsletter: function () {
            var form = $(this);
            form.submit(function (event) {
                event.preventDefault();

                var successElement = form.find('.success-element');
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
                        }
                    })
                    .fail(function (response) {
                        if (response.responseJSON.hasOwnProperty('errors')) {
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
