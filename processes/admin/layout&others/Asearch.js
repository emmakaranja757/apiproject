$(document).ready(function () {
    $('#searchProperty').on('input', function () {
        let query = $(this).val().trim();
        let resultsContainer = $('#searchResults');

        if (query.length > 0) {
            $.ajax({
                url: 'layout&others/Ajax.php',
                method: 'GET',
                data: { query: query },
                dataType: 'json',
                success: function (response) {
                    resultsContainer.empty().show();

                    if (response.length === 0) {
                        resultsContainer.append('<p class="search-item">No results found</p>');
                    } else {
                        response.forEach(function (property) {
                            let item = $('<div class="search-item"></div>')
                                .text(property.property_name + ' - ' + property.location + ' - Ksh' + property.price)
                                .on('click', function () {
                                    $('#searchProperty').val(property.property_name);
                                    resultsContainer.hide();

                                    // ✅ Get action from URL (if user clicked Edit/Delete first)
                                    let action = new URLSearchParams(window.location.search).get("action");

                                    if (!action) {
                                        alert("Please select an action (Edit or Delete) before searching.");
                                        return;
                                    }

                                    // ✅ Redirect after selecting property
                                    window.location.href = 'FilterSearch.php?property_id=' + property.property_id + '&action=' + action;
                                });
                            resultsContainer.append(item);
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                    console.log("Full Response:", xhr.responseText);
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
