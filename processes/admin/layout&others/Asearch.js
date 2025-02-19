$(document).ready(function () {
    $('#searchProperty').on('input', function () {
        let query = $(this).val().trim();
        let resultsContainer = $('#searchResults');

        if (query.length > 0) {
            $.ajax({
                url: 'layout&others/Ajax.php', // Correct path
                method: 'GET',
                data: { query: query },
                dataType: 'json', // Ensure JSON response
                success: function (response) {
                    console.log("AJAX Response:", response); // Debugging
                    resultsContainer.empty().show();

                    if (response.length === 0) {
                        resultsContainer.append('<p class="search-item">No results found</p>');
                    } else {
                        response.forEach(function (property) {
                            let item = $('<div class="search-item"></div>')
                                .text(property.property_name + ' - ' + property.location + ' - $' + property.price)
                                .on('click', function () {
                                    $('#searchProperty').val(property.property_name);
                                    resultsContainer.hide();
                                });
                            resultsContainer.append(item);
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                    console.log("Full Response:", xhr.responseText); // Debugging
                }
            });
        } else {
            resultsContainer.hide();
        }
    });

    $(document).click(function (event) {
        if (!$(event.target).closest('#searchProperty, #searchResults').length) {
            $('#searchResults').hide();
        }
    });
});
