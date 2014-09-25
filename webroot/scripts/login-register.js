$( function() {

    var resizeLoginRegisterBox = function() {
        if($(window).width() <= 640) {
            $('.login-register-box').css( {'width': '400px', 'height': '400px',
                 'left': 'calc(50% - 200px)', top: 'calc(50% - 200px)'
            })
        }
        else {
            $('.login-register-box').css( {'width': '500px', 'height': '500px',
                 'left': 'calc(50% - 250px)', top: 'calc(50% - 250px)'
            })
        }
    }

    var triggerDarkFilter = function() {
        $('.darkfilter').toggle(0);
        $('.darkfilter').animate({'opacity': 0.6}, 600);
    }

    var triggerLoginRegisterBox = function() {
        
        $('.login-register-box').toggle(0);
        $('.login-register-box').animate({'opacity': 1.0}, 600); 
    }
    
    $('.login').on('click', function() {
      
        $.ajax({
          url: 'login.php',
          success: function(result) {$('.login-register-box').append(result)}
        })
      
        triggerDarkFilter();
        triggerLoginRegisterBox();
    });
    
    $('.register').on('click', function() {
      
        $.ajax({
          url: 'register.php',
          success: function(result) {$('.login-register-box').append(result)}
        })
      
        triggerDarkFilter();
        triggerLoginRegisterBox();
    });
    
    $(window).on('resize', function() {
        resizeLoginRegisterBox();
    });
    
    resizeLoginRegisterBox();
    
    
});