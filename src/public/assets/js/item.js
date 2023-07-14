jQuery(function($) {
    // AJAX Call to Submit Form
    $('.item-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);

        const ajaxUrl = window.ajax_object.ajax_url;
        const nonce = window.ajax_object.nonce;

        // Get URLs
        const urls = [];
        form.find('.url-fields-container input[name="urls[]"]').each(function() {
            let urlValue = $(this).val();
            urls.push(urlValue);
        });

        const dataToSubmit = {
            action: 'item_endpoints',
            route: 'create_or_update_item',
            id: form.find('[name="id"]').val(),
            name: form.find('[name="name"]').val(),
            folderID: form.find('[name="folderID"]').val(),
            username: form.find('[name="username"]').val(),
            password: form.find('[name="password"]').val(),
            urls: urls,
            notes: form.find('[name="notes"]').val(),
            favorite: form.find('[name="favorite"]').is(':checked') ? 1 : 0,
            nonce: nonce,
        };

        $.ajax({
            url: ajaxUrl,
            data: dataToSubmit,
            method: 'POST'
        })
        .then(function(response) {
            // Reset the form
            form.hide();

            // Create a new div for success message
            const successDiv = $('<div>', {
                id: 'success-message',
                class: 'text-center',
                html: $('<h4>', { text: response.message })
            });
            form.parent().before(successDiv);
        })
        .fail(function(error) {
            // Create a new div for success message
            const errorDiv = $('<div>', {
                id: 'error-message',
                class: 'text-center',
                html: $('<h4>', { text: error.responseJSON?.data?.message })
            });
            form.parent().before(errorDiv);
        });
    });

    // Add New URL Field
    let urlFieldsContainer = $('.url-fields-container');
    let urlFieldTemplate = $('#url-field-template').html();
    let urlCounter = 1;

    $('#new-field').click(function() {
        let newUrlField = $(urlFieldTemplate);
        newUrlField.find('.form-label').text('URL ' + urlCounter++ + ':');
        urlFieldsContainer.append(newUrlField);
    });
});
