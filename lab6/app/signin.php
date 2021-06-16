<?php
/**
 * Author: Jeanmarc Duong
 * Date: 12/15/2020
 * File:
 * Description:
 */
?>
<!--------- signin form ----------------------------------------------------------->
<form class="form-signin" style="display: none;">
    <!--    <input type="hidden" name="form-name" value="signin">-->
    <h1 class="h3 mb-3 font-weight-normal" style="padding: 20px; color: #FFFFFF; background-color: #343a40">Please Sign in using your employee credentials</h1>
    <div style="width: 250px; margin: auto">
        <label for="username" class="sr-only">Username</label>
        <input type="text" id="signin-username" class="form-control" placeholder="Username" required autofocus>
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="signin-password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-dark btn-block bg-dark" type="submit">Sign in</button>
        <p style="padding-top: 10px;">Don't have an account? <a id="mychatter-signup" href="#signup">Sign up</a></p>
    </div>
</form>