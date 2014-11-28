$(function() {
   
   
   $('.remove-it').on('click', function() {
    $.ajax({
        url: 'ajaxRequest.php',
        type: 'POST',
        data: {
            type: 'remove-artist',
            id: $('.remove-it').data('id')
        },
        success: function(data) {
            location.reload();
        }
    });
  });
  
    $('.spotlight-it').on('click', function() {
    $.ajax({
        url: 'ajaxRequest.php',
        type: 'POST',
        data: {
            type: 'insert-artist',
            id: $('.spotlight-it').data('id')
        },
        success: function(data) {
            alert(data);
            location.reload();
        }
    });
    
});