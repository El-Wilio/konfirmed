$( function() {

    var triggerDarkFilter = function() {
        $('.darkfilter').toggle(0);
        $('.darkfilter').animate({'opacity': 0.0}, 0).animate({'opacity': 0.6}, 600);
    }

    var triggerLoginBox = function() {
        
        $('.login-box').toggle(0);
        $('.login-box').animate({'opacity': 0.0}, 0).animate({'opacity': 1.0}, 600); 
    }
    
    var triggerRegisterBox = function() {
        
        $('.register-box').toggle(0);
        $('.register-box').animate({'opacity': 0.0}, 0).animate({'opacity': 1.0}, 600); 
    }
    
    $('.login').on('click', function() {
      
        $.ajax({
          url: 'login.php',
          success: function(result) {$('.login-box').html(result)}
        })
      
        triggerDarkFilter();
        triggerLoginBox();
    });
    
    $('.register').on('click', function() {
      
        $.ajax({
          url: 'register.php',
          success: function(result) {$('.register-box').html(result)}
        })
      
        triggerDarkFilter();
        triggerRegisterBox();
    });
    
    
    /**ajax coding part **/
    
    
});