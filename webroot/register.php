<h1 class="registration-title">Create an Account!</h1>
<div class="registration-form">
    <div class="input-box">
    <input type="email" name="email" class="register-input email-input" 
        placeholder="Please type in your email." autocomplete="off">
    <span class="register-helper email-helper">E.G: example@example.com</span>
    </div>
    <div class="input-box">
    <input type="email" name="email_confirmation" class="register-input email-confirmation-input" 
        placeholder="Please confirm your email.">
    <span class="register-helper email-confirmation-helper">Type in the same email address.</span>
    </div>
    <div class="input-box">
    <input type="password" name="password" class="register-input password-input" 
        placeholder="Please type in your password.">
    <span class="register-helper password-helper">Enter a password of 5 characters or more.</span>
    </div>
    <div class="input-box">
    <input type="password" name="password_confirmation" class="register-input password-confirmation-input" 
        placeholder="Please confirm your password.">
    <span class="register-helper password-confirmation-helper">Enter the same password again.</span>
    </div>
        <div class="button-field"><button class="register-submit">Register</button>
        </div>
</div>
<a href="#"><img src="images/x-icon.png" class="close-it" width="20px" height="auto"></a>
    <script>
    
        var isItTaken = false;
        
        var verification = function() {
            var email = $('input[name="email"]').val().trim();
            var email_confirmation = $('input[name="email_confirmation"]').val().trim();
            var password = $('input[name="password"]').val();
            var password_confirmation = $('input[name="password_confirmation"]').val();
            
            if(!email.match(/^\w+@\w+\.\w{2,4}(\.\w{2,4})?$/)) {
                return false;
            }
                
            else if(email != email_confirmation) {
                return false;
            }
            
            else if(password.length < 5) {
                return false;
            }
              
            else if(password != password_confirmation) {
                return false;
            }
                
            else {return true;}
        }
        
        $('input').keyup(function() {
            var input_name = $(this).attr('name');
            /** email **/
            if($('input[name="email"]').val().length == 0){
                $('.email-input').removeClass('bad').removeClass('good');
            }
            
            else if ($('input[name="email"]').val().match(/^\w+@\w+\.\w{2,4}(\.\w{2,4})?$/)) {
                $('.email-input').addClass('good').removeClass('bad');
            }
                
            else {
               $('.email-input').addClass('bad').removeClass('good');
            }
            
            /**email confirmation **/
            
            if($('input[name="email_confirmation"]').val().length == 0 || $('input[name="email"]').val().match(/^\w+@\w+\.\w{2,4}(\.\w{2,4})?$/) == false ){
                $('.email-confirmation-input').removeClass('bad').removeClass('good');
            }
            
            else if ($('input[name="email_confirmation"]').val() == $('input[name="email"]').val()) {
                $('.email-confirmation-input').addClass('good').removeClass('bad');
            }
                    
            else {
                $('.email-confirmation-input').addClass('bad').removeClass('good');
            }
            
            /**password**/
            
            if($('input[name="password"]').val().length == 0){
                $('.password-input').removeClass('bad').removeClass('good');
            }
            
            else if ($('input[name="password"]').val().length >= 5) {
                $('.password-input').addClass('good').removeClass('bad');
            }
                    
            else {
                $('.password-input').addClass('bad').removeClass('good');
            }
            
            /**password confrimation**/
            
            if($('input[name="password_confirmation"]').val().length == 0){
                $('.password-confirmation-input').removeClass('bad').removeClass('good');
            }
            
            else if ($('input[name="password_confirmation"]').val() == $('input[name="password"]').val()) {
                $('.password-confirmation-input').addClass('good').removeClass('bad');
            }
                    
            else {
                $('.password-confirmation-input').addClass('bad').removeClass('good');
            }
            
            /** some ajax to see if the username is available or not **/
            $.ajax({
                url: 'ajaxRequest.php',
                type: 'post',
                data: {
                    email: $('input[name="email"]').val().trim(),
                    type: 'checkEmailAvailability'
                },
                success: function(data) { 
                    $('.email-helper').html('this e-mail is already taken').css({'background-color': '#bf1e2e', 'border': '1px dashed #fff'});
                    if(data == 'This email was already taken') {
                        isItTaken = true;
                    }
                    else {
                        isItTaken = false;
                        $('.email-helper').html('E.G: example@example.com').css({'background-color': '#5f7193', 'border': '1px dashed #183058'});;
                    }
                }
            });
                
        });        
        
        $('input').on('focus', function() {
            if(!isItTaken) {
                $('.email-helper').animate({opacity: '0.0'}, 0);
            }
            $('.email-confirmation-helper').animate({opacity: '0.0'}, 0);
            $('.password-helper').animate({opacity: '0.0'}, 0);
            $('.password-confirmation-helper').animate({opacity: '0.0'}, 0);
            var input_name = $(this).attr('name');
            if(input_name == "email") {
                $('.email-helper').animate({opacity: '1.0'}, 0);
            }
            if(input_name == "email_confirmation") {
                $('.email-confirmation-helper').animate({opacity: '1.0'}, 0);
            }
            if(input_name == "password") {
                $('.password-helper').animate({opacity: '1.0'}, 0);
            }
            if(input_name == "password_confirmation") {
                $('.password-confirmation-helper').animate({opacity: '1.0'}, 0);
            }            
        });
                  
            
        $('.register-submit').on('click', function(e) {
            if(verification() && !isItTaken) {
                $('.button-field').html('');
                $.ajax({ 
                    url: 'ajaxRequest.php',
                    type: 'post',
                    data: {
                        email: $('input[name="email"]').val().trim(), 
                        email_confirmation: $('input[name="email_confirmation"]').val().trim(), 
                        password: $('input[name="password"]').val(), 
                        password_confirmation: $('input[name="password_confirmation"]').val(),
                        type: 'sendRegistrationInfo'
                    },
                    success: function(data) {
                        if (data == "noerror") {
                            $('.button-field').html('<p class="notice">Thank you, you have been registered.<br>This window will now close in 3 seconds.</p>');
                        }
                        else {
                            $('.button-field').html('<p class="notice">an error has occured.</p>');
                        }
                        $('.notice').animate({opacity: 1.0}, 500);
                        setTimeout(function() {
                          $('.close-it').trigger('click');
                        }, 3000);
                    },
                    error: function() {console.log('nope')}
                })
            }
            else {
            
                var email = $('input[name="email"]');
                var email_confirmation = $('input[name="email_confirmation"]');
                var password = $('input[name="password"]');
                var password_confirmation = $('input[name="password_confirmation"]');
            
                if(!email.val().trim().match(/^\w+@\w+\.\w{2,4}(\.\w{2,4})?$/) || isItTaken) {
                    email.focus();
                }
                
                else if(email.val().trim() != email_confirmation.val().trim()) {
                    email_confirmation.focus();
                }
            
                else if(password.val().length < 5) {
                    password.focus();
                }
                
                else if(password.val() != password_confirmation.val()) {
                    password_confirmation.focus();
                }
            }
        });
        
        $('.close-it').on('click', function(e) {
            e.preventDefault();
            $('.darkfilter').toggle(0);
            $('.darkfilter').animate({'opacity': 0.0}, 0);
            $('.register-box').toggle(0);
            $('.register-box').animate({'opacity': 0.0}, 0);
            $('.register-box').empty();
        });

        $(window).keydown(function(e) {       
            var key = e.which;
            if (key == 13) {
              e.preventDefault();
              $('.register-submit').trigger('click');
            }
     
        });    
        
    </script>
        