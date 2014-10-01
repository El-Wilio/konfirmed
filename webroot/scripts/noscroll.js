
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
 var height = $(window).height();
        var width = $(window).width();
        if(height > width) {
            $('.viewport').attr('content', 'height=device-width, initial-scale=1'); 
        }//landscape mode
    $(document).on('mousewheel', function(e) {
        e.preventDefault();
    });
    
    $(window).on("orientationchange",function(){
        var height = $(window).height();
        var width = $(window).width();
        if(height > width) {
            $('.viewport').attr('content', 'height=device-width, initial-scale=1'); 
        }//landscape mode
        
    });
    
 });