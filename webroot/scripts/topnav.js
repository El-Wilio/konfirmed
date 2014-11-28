  $('li').mouseover(
    function () {
      var classname = $(this).attr('class');
      $(".child-of-" + classname).css("visibility", "visible");
    }
  );
  
  var sidebar = true;
  
  $('.menu-icon').on('click', function() {
    $('.left-sidebar').toggleClass('hide-sidebar');
    $('.wrapper').toggleClass('adjust-wrapper');
    $('.wrapper-single').toggleClass('adjust-wrapper-single');
  });
  
  
  $('li').mouseout(
    function () {
      var classname = $(this).attr('class');
      $(".child-of-"+ classname).css("visibility", "hidden");
    }
  );
