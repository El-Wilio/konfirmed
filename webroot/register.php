<a href="#" class="close-it">close</a>
<div class="registration-info">
  <h1>Registration</h1>
  <p>In order to access this website features you need to create an account which will only take
     5 seconds of your time.
     <ul>
         <li>You need to enter a valid email.</li>
         <li>Your password needs to be a minimum of 5 characters, there are no other restrictions.</li>
     </ul>
  </p>
</div>
<div class="registration-form">
    <input type="email" name="email" class="register-input email-input" 
        placeholder="Please type in your email." autocomplete="off">
    <span class="email_taken" style="font-size: 12px; display: block; position: relative; bottom: 10px;"></span>
    <input type="email" name="email_confirmation" class="register-input email-confirmation-input" 
        placeholder="Please confirm your email.">
    <input type="password" name="password" class="register-input password-input" 
        placeholder="Please type in your password.">
    <input type="password" name="password_confirmation" class="register-input password-confirmation-input" 
        placeholder="Please confirm your password.">
        <div class="button-field"><button class="register-submit">Register</button></div>
</div>
 
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
   
        $('input').change(function() {
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
                    $('.email_taken').html(data);
                    if(data == 'This email was already taken') {
                        isItTaken = true;
                    }
                    else {
                        isItTaken = false;
                    }
                }
            });
                
        });        
            
        $('.register-submit').on('click', function(e) {
            if(verification() && !isItTaken) {
                $('.button-field').html('<img src="images/spinner.gif" width="25px" height="25px">');
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
                            $('.button-field').html('<p class="notice">thank you, you have been registered.</p>');
                        }
                        else {
                            $('.button-field').html('<p class="notice">an error has occured.</p>');
                        }
                        $('.notice').animate({opacity: 1.0}, 500);
                    },
                    error: function() {console.log('nope')}
                })
            }
        });
        
        $('.close-it').on('click', function(e) {
            e.preventDefault();
            $('.darkfilter').toggle(0);
            $('.darkfilter').animate({'opacity': 0.0}, 0);
            $('.login-register-box').toggle(0);
            $('.login-register-box').animate({'opacity': 0.0}, 0);
            $('.login-register-box').empty();
        });
        
    </script>
        