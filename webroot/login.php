<a href="#" class="close-it">close</a>
<input type="email" name="email" class="login-input email-input" 
    placeholder="Please type in your email.">
<span class="email_taken" style="font-size: 12px; display: block; position: relative; bottom: 10px;"></span>
<input type="password" name="password" class="login-input password-input" 
    placeholder="Please enter your password.">
<button class="login-submit">login</button>
<span class="error"></span>

<script>
$(function() {

        $('.login-submit').on('click', function() {
            $.ajax({ 
                url: 'ajaxRequest.php',
                type: 'post',
                data: {
                    email: $('input[name="email"]').val().trim(),  
                    password: $('input[name="password"]').val(), 
                    type: 'login'
                },   
               success: function(data) {
                    console.log(data);
                    if (data == "error") {
                        $('.error').html("I'm sorry, but it appears that the username or password is wrong.");
                    }
                    else if(data == "success") {
                        location.reload();
                    }
                },
                error: function() {console.log('nope')}
            })                
        });

        $('.close-it').on('click', function(e) {
            e.preventDefault();
            $('.darkfilter').toggle(0);
            $('.darkfilter').animate({'opacity': 0.0}, 0);
            $('.login-box').toggle(0);
            $('.login-box').animate({'opacity': 0.0}, 0);
            $('.login-box').empty();
        });
    });
</script>