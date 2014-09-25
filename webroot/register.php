<style>
    .login-register-input {
        width: 50%;
        display: block;
        margin: 20px auto;
        border: 1px solid darkgray;
        padding: 10px 5px 10px 5px;
    }

    .submit-button {
        display: block;
        margin: 20px auto;
        width: 50%;
        padding: 10px 5px 10px 5px;
        background-color: blue;
        color: white;
        font-weight: bold;
        border: none;
    }
</style>

<h1 style="text-align: center;">Register</h1>
<p>Please enter the following information to register</p>
<form>
    <input type="text" name="email" placeholder="Please enter your email" class="login-register-input">
    <input type="text" name="email_confirmation" placeholder="Please confirm your email" class="login-register-input">   
    <input type="password" name="password" placeholder="Place enter your password" class="login-register-input">
    <input type="password" name="password_confirmation" placeholder="Place confirm your email" class="login-register-input">     
    <input type="submit" name="register" value="register" class="submit-button">   
</form>