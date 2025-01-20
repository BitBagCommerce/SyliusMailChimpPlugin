window.joinNewsletter = function joinNewsletter(form) {
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
            .then(async (response) => {
                if (!response.ok) {
                    response = await response.json();
                    throw(response);
                }

                return await response.json()
            })
            .then((data) => {
                if (data.hasOwnProperty('message')) {
                    successElement.innerHTML = data.message;
                    input.value = '';
                }
            })
            .catch(error => {
                var message = 'An unexpected error occurred. Please try again later.';

                if (error.errors) {
                    try {
                        let jsonErrors = JSON.parse(error.errors);
                        message = '';
                        message = Object.values(jsonErrors).join(" ");
                    } catch (e) {}
                }

                validationElement.textContent = message;
            });
    });
}

