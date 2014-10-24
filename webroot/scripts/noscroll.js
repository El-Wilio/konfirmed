
$(function() {

    $(document).on('mousewheel', function(e) {
       // e.preventDefault();
    });

    $('div.box').on('click', function() {
      var category = $(this).attr('class').split(' ')[0];
      $('.selected-category').addClass(category);
      $('.selected-category').animate({left: '0'}, 500);      
      });
      
    $('.close-selected-category').on('click', function() {
      var category = $('.selected-category').attr('class').split(' ')[1];
      $('.selected-category').animate({left: '-100%'}, 500).queue(function() {
        $('.selected-category').removeClass(category);
        $(this).dequeue(); });
      });    
    
 });