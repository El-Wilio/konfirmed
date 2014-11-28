
$(function() {

    $(document).on('mousewheel', function(e) {
       // e.preventDefault();
    });

    $('div.box').on('click', function() {
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
          
  $('li').mouseover(
    function () {
      var classname = $(this).attr('class');
      $(".child-of-" + classname).css("visibility", "visible");
    }
  );
  
  $('li').mouseout(
    function () {
      var classname = $(this).attr('class');
      $(".child-of-"+ classname).css("visibility", "hidden");
    }
  );    
  
  //sending email function
  
  $('.contact-submit').on('click', function() {
    $.ajax({
        url: 'ajaxRequest.php',
        type: 'POST',
        data: {
            type: 'sendEmail',
            subject: $('input[name="contact-subject"]').val(),
            message: $('textarea[name="contact-area"]').val(),
            email: $('input[name="contact-email"]').val()
        },
        success: function(data) {
            if(data == "success") {
                $('.yup').html('your message has been sent');
            }
            else {
                $('.yup').html('error!');
            }
        }
    });
   });
   
   //scroll
   
     $(window).load(function(){
        $(".left-box").mCustomScrollbar({
      theme: 'dark', axis:"x"});
  });
  
  $('li > a.categories-click').on('click', function() {
     $(this).closest('ul').children('div').toggleClass('show');
  });
    
 });