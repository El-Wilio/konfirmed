
var dynamicSize = function() {
    if($(window).width() <= 640) {
        var real_width = $(window).width() * 0.50;
    }
    else {
        real_width = 160;
    }
    $('.box').css('height', real_width + "px");
    console.log(real_width);
    console.log($('.box').css('width'));
}

$(function() {
    //dynamicSize();
    $(document).on('mousewheel', function(e) {
        e.preventDefault();
    });
    
    $(window).resize(function() {
        //dynamicSize();
        $('.viewport').html($(this).width());
    });
    
 });