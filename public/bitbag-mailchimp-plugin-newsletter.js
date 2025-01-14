(function () {
    'use strict';

    // Create a method to handle the newsletter form
    function joinNewsletter(form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            var successElement = form.querySelector('.success-element');
            var input = form.querySelector('input[type=text]');
            var validationElement = form.querySelector('.validation-element');

            successElement.textContent = '';
            validationElement.textContent = '';

            var formData = new FormData(form);
            var actionUrl = form.getAttribute('action');
            var method = form.getAttribute('method');

            fetch(actionUrl, {
                method: method,
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.hasOwnProperty('message')) {
                        successElement.innerHTML = data.message;
                        input.value = '';
                    }
                })
                .catch(error => {
                    var message = 'An unexpected error occurred. Please try again later.';
                    if (error.responseJSON && error.responseJSON.hasOwnProperty('errors')) {
                        var errors = error.responseJSON.errors;
                        message = '';
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                message += errors[key] + " ";
                            }
                        }
                    }
                    validationElement.textContent = message;
                });
        });
    }

    // Applying the method to all forms with the .join-newsletter class
    document.querySelectorAll('.join-newsletter').forEach(function (form) {
        joinNewsletter(form);
    });
})();
