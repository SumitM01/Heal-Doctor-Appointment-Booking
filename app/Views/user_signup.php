/**
 * 
 * This view is for displaying user sign up form 
 * @author sumit mishra cr7sumitmishra@gmail.com
 * @version 1.0
 * 
 */
<!DOCTYPE html>
<html>
    <head>
        <!-- <title>Hello World</title> -->
        <!-- Load Bootstrap CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/cover.css') ?>">

        <!-- Load Bootstrap JS and dependencies -->
        <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
        <title>Heal User Sign Up</title>
    </head>

    <body>
        <div class="cover-container d-flex p-3 mx-auto flex-column justify-content-center">
            <a href="<?php base_url('Home::hello_world')?>"><h3 class="float-md-start mb-0">Heal</h3></a>
            
            <form class="w-50 mx-auto my-auto p-3 bg-light rounded" id="form" action="/user-signup" method="post">
                <?php if(isset($validation)): ?>
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            <?= $validation->listErrors() ?> <!-- Display validation errors -->
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(isset($signup_success)): ?>
                    <div class="col-12">
                        <div class="alert alert-success" role="alert">
                            Congratulations! Sign up successful. You can go to Login Page to login with the user.
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(isset($error)): ?>
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            <?= $error ?>
                        </div>
                    </div>
                <?php endif; ?>
                <h1 class="h3 mb-3 fw-normal text-center">Sign up</h1>

                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingName" name="fullname" placeholder="John Smith" value="">
                    <label for="floatingInput">Full name</label>
                    <small></small>
                </div>
                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="name@example.com"  value="">
                    <label for="floatingInput">Email address</label>
                    <small></small>
                </div>
                <div class="form-floating input-group">
                    <input type="text" class="form-control" id="floatingPassword" name="password" placeholder="Password"  value="">
                    <label for="floatingPassword">Password</label>
                    <span class="input-group-text" id="basic-addon1">
                    <a tabindex="0" role="button" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-title="Password Criteria" data-bs-html="true" data-bs-content="<ul>
                    <li>Minimum length of 8 characters</li>
                    <li>Must contain at least one capital letter</li>
                    <li>Must contain at least one small letter</li>
                    <li>Must contain at least one digit</li>
                    <li>Must contain at least one symbol</li>
                </ul>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">  
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"></path>
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"></path>
                        </svg>
                    </a>
                    </span>
                    <small></small>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingRepassword" name="conf_password" placeholder="Password"  value="">
                    <label for="floatingPassword">Repeat password</label>
                    <small></small>
                </div>
                <div class="form-check d-flex justify-content-between my-3">
                    <div class="text-start">
                        <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            Remember me
                        </label>
                    </div>
                    <div class="text-end">
                        <p>Have an account?<a href="<?= base_url('user-login') ?>"> Sign In</a></p>
                    </div>
                </div>
                <button class="btn btn-success w-100 py-2" type="submit">Sign in</button>
            </form>
            <p class="mx-auto mt-5 mb-3 text-body-secondary">© 2024-2025</p>
        </div>

       <script>
            const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
            const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
        </script>
        <!-- <script src="<?= base_url('assets/js/app.js') ?>"></script> -->
    </body>
</html>