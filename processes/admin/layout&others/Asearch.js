$(document).ready(function () {
    $('#searchProperty').on('input', function () {
        let query = $(this).val().trim();
        if (query.length > 0) {
            $.ajax({
                url: 'search_property.php',
                method: 'GET',
                data: { query: query },
                success: function (response) {
                    let results = JSON.parse(response);
                    let resultsContainer = $('#searchResults');
                    resultsContainer.empty().show();
                    
                    if (results.length === 0) {
                        resultsContainer.append('<p class="search-item">No results found</p>');
                    } else {
                        results.forEach(function (property) {
                            let item = $('<div class="search-item"></div>').text(property.property_name + ' - ' + property.location + ' - $' + property.price);
                            item.on('click', function () {
                                $('#searchProperty').val(property.property_name);
                                resultsContainer.hide();
                            });
                            resultsContainer.append(item);
                        });
                    }
                }
            });
        } else {
            $('#searchResults').hide();
        }
    });

    $(document).click(function (event) {
        if (!$(event.target).closest('#searchProperty, #searchResults').length) {
            $('#searchResults').hide();
        }
    });
});
