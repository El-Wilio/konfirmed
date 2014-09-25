<style>
    .login-register-input {
        border: 1px solid darkgray;
        padding: 5px;
        margin: 10px 0 10px 0;
        color: blue;
    }
    
    .submit-button {
        width: 208px;
        padding: 5px 10px 5px 10px;
        background-color: blue;
        color: white;
        font-weight: bold;
        border: none;
    }
</style>

<form>
    <div class="input-field">
        <input type="text" placeholder="Please enter your username or email" size="30" class="login-register-input">
    </div>
    <div class="input-field">    
        <input type="password" placeholder="Place enter your password" size="30" class="login-register-input">
    </div>
    <div class="input-field">       
        <input type="submit" name="login" value="login" class="submit-button">
    </div>    
</form>