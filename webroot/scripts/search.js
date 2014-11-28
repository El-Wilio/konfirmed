$(function() {
    $('input[name="search-input"]').on('change', function() {
    
        $.ajax({
            url: 'ajaxRequest.php',
            type: 'POST',
            data: {
                query: $('input[name="search-input"]').val(),
                type: 'search'
            },
            success: function(data) {
                $('.search-results').html(data);
            }
            
        });
    
    
    });
});