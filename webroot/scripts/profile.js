$(function() {

  $.getScript("scripts/topnav.js");
  $.getScript("scripts/left.js");
  
    $(window).load(function(){
    $(".spotlights").mCustomScrollbar({
      theme: 'dark'});
    $(".submissions").mCustomScrollbar({
      theme: 'dark'});
  });

  var latest = $('.content-wrapper').last().data("id");
  var account = $('div.profile-info').data("account-id");
  var stopIt = false;
  
  if($('.content-wrapper').length) {
  
      setInterval(function() {
        var calculate = $(window).height() - $('.content-wrapper').last().offset().top;
        if(calculate > 300 && !stopIt) {
          $.ajax({
            url: 'ajaxRequest.php',
            type: 'POST',
            data: {
              type: 'getSubmissions',
              start: latest,
              async: false,
              id: account },
            beforeSend: function() {stopIt = true;},
            success: function(data) {
              $('.content').append(data);
              var new_latest = $('.content-wrapper').last().data("id");
              if(new_latest > latest) {
              latest = new_latest;
              stopIt = false;
              }
              else {
              stopIt = true;}
            }
          });    
       } 
      }, 100);
  }
  
  
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