$( function() {

    var resizeLoginRegisterBox = function() {
        if($(window).width() <= 640) {
            $('.login-register-box').css( {'width': '100%',
                 'left': '0'})
        }
        if($(window).width() <= 1024) {
            $('.login-register-box').css( {'width': '640px',
                 'left': 'calc(50% - 320px)'})
        }
        else {
            $('.login-register-box').css( {'width': '1000px',
                 'left': 'calc(50% - 500px)'})
        }
    }

    var triggerDarkFilter = function() {
        $('.darkfilter').toggle(0);
        $('.darkfilter').animate({'opacity': 0.0}, 0).animate({'opacity': 0.6}, 600);
    }

    var triggerLoginRegisterBox = function() {
        
        $('.login-register-box').toggle(0);
        $('.login-register-box').animate({'opacity': 0.0}, 0).animate({'opacity': 1.0}, 600); 
    }
    
    $('.login').on('click', function() {
      
        $.ajax({
          url: 'login.php',
          success: function(result) {$('.login-register-box').html(result)}
        })
      
        triggerDarkFilter();
        triggerLoginRegisterBox();
    });
    
    $('.register').on('click', function() {
      
        $.ajax({
          url: 'register.php',
          success: function(result) {$('.login-register-box').html(result)}
        })
      
        triggerDarkFilter();
        triggerLoginRegisterBox();
    });
    
    $(window).on('resize', function() {
        resizeLoginRegisterBox();
    });
    
    /**ajax coding part **/
    
    resizeLoginRegisterBox();
    
    
});