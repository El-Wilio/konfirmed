$(function() {


var $stuff = $('.content-wrapper');

var $container = $('.content-single').masonry({
    itemSelector: '.content-wrapper',
    transitionDuration: 0
});

$stuff.each(function() {
    $(this).imagesLoaded( function() {
        $('.content-single').masonry('reloadItems');
         $('.content-single').masonry('layout');
    });
});

// initialize

  $.getScript("scripts/topnav.js");
  $.getScript("scripts/left.js");

//scroller

$(".wrapper").perfectScrollbar({
    suppressScrollX: true

});


//caroussel

$('.left-box').slick({
  slidesToShow: 4,
  slidesToScroll: 1,
});
				
// or with jQuery
var $container = $('#container');
// initialize Masonry after all images have loaded  
$container.imagesLoaded( function() {
  $container.masonry();
});
  
  var latest = $('.content-wrapper').last().data("id");
  var account = $('div.profile-info').data("account-id");
  var stopIt = false;
  
     $('div.box-2').on('click', function() {
      var category = $(this).attr('class').split(' ')[0];
      var selectedID;
      switch (category) {
        case "audio":
            selectedID = '4';
            break;
        case "image":
            selectedID = '1';
            break;
        case "video":
            selectedID = '2';
            break;
        case "text":
            selectedID = '3';
            break;          
      }            

       $.ajax({
          url: 'ajaxRequest.php',
          type: 'POST',
          data: {
           mediumID: selectedID,
           type: 'iconPopulator'
           },
           success: function(data) {
            $('.selected-category').html(data);
           $(".spotlights").mCustomScrollbar({
               theme: 'dark'});
           $(".submissions").mCustomScrollbar({
               theme: 'dark'});
           }
      });

      $('.selected-category').addClass(category);
      $('.selected-category').animate({left: '0'}, 500);      
      });
    $('.selected-category').on('click', '.close-selected-category', function() {
      var category = $('.selected-category').attr('class').split(' ')[1];
      $('.selected-category').animate({left: '-100%'}, 500).queue(function() {
        $('.selected-category').removeClass(category);
        $(this).dequeue(); });
      });


});