<?php
/**
 * 
 * User Login : Handles the user login for the web application 
 * @author sumit mishra cr7sumitmishra@gmail.com
 * @version 1.0
 * 
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- <title>Hello World</title> -->
        <!-- Load Bootstrap CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/cover.css') ?>">

        <!-- Load Bootstrap JS and dependencies -->
        <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
        <title>User Login</title>
        <link rel="icon" href="https://i.imgur.com/ry5gKrT.png" type="image/x-icon">
    </head>

    <body>
        <div class="cover-container d-flex p-3 mx-auto flex-column justify-content-center">
            <a href="<?= base_url('heal')?>"><h3 class="float-md-start mb-0">Heal</h3></a>
            
            <form class="w-50 mx-auto my-auto p-3 bg-light border border-success-subtle rounded text-center" id="form" action="/user-login" method="post">
                
                <?php if(isset($emailinvalid)): ?>
                <p class="alert alert-danger" role="alert"><?= $emailinvalid ?></p>
                <?php endif; ?>
                <?php if(isset($passwordincorrect)): ?>
                <p class="alert alert-danger" role="alert"><?= $passwordincorrect ?></p>
                <?php endif; ?>

                <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
                
                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingEmail" placeholder="name@example.com" name="email" value="">
                    <label for="floatingInput">Email address</label>
                    
                </div>
                
                <div class="form-floating input-group">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password"  name="password" value="">
                    <label for="floatingPassword">Password</label>
                    <span class="input-group-text" id="basic-addon1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16" onclick="showhidePass()">
                          <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"></path>
                          <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"></path>
                        </svg>
                    </span>
                </div>
                

                <div class="form-check d-flex justify-content-between my-3">
                    <div class="text-end">
                        <p>New user?<a href="<?= base_url('user-signup') ?>"> Create an account</a></p>
                    </div>
                </div>
                <button class="btn btn-success w-100 py-2" type="submit">Sign in</button>
                
            </form>
            <p class="mx-auto mt-5 mb-3 text-body-secondary">© 2024-2025</p>
        </div>
        <!-- <script src="<?= base_url('assets/js/user_login.js') ?>"></script> -->
        <script>
            function showhidePass() {
                var x = document.getElementById("floatingPassword");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }
        </script>
    </body>
</html>

