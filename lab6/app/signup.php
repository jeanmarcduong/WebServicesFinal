<?php
/**
 * Author: Jeanmarc Duong
 * Date: 12/15/2020
 * File:
 * Description:
 */
?>
<!--------- signup form ----------------------------------------------------------->
<form class="form-signup" style="display: none">
    <!--<input type="hidden" name="form-name" value="signup">-->
    <h1 class="h3 mb-3 font-weight-normal" style="padding: 20px; color: #FFFFFF; background-color: #343a40">Create your employee account</h1>
    <div style="width: 250px; margin: auto">
        <label for="name" class="sr-only"> Username</label>
        <input type="text" id="signup-username" class="form-control" placeholder="Username" required autofocus>

        <label for="username" class="sr-only"> Name</label>
        <input type="text" id="signup-name" class="form-control" placeholder="Name" required>

        <label for="email" class="sr-only"> Role </label>
        <input type="text" id="signup-role" class="form-control" placeholder="Role: employee or owner" required>

        <label for="password" class="sr-only">Password</label>
        <input type="password" id="signup-password" class="form-control" placeholder="Password" required>


        <button class="btn btn-lg btn-dark btn-block bg-dark" type="submit">Sign up</button>

        <p style="padding-top: 10px;">Already have an account? <a id="mychatter-signin" href="#signin">Sign in</a></p>
    </div>
</form>
