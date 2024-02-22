<!DOCTYPE html>
<html>
    <head>
        <!-- <title>Hello World</title> -->
        <!-- Load Bootstrap CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/cover.css') ?>">

        <!-- Load Bootstrap JS and dependencies -->
        <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
        <title>Heal</title>
    </head>

    <body>
        <div class="cover-container d-flex p-3 mx-auto flex-column">
            <header class="mb-auto">
                <div>
                <a href="<?php base_url('Home::hello_world')?>"><h3 class="float-md-start mb-0">Heal</h3></a>
                <!-- <nav class="nav nav-masthead justify-content-center float-md-end">
                    <a class="nav-link fw-bold py-1 px-0 active" aria-current="page" href="#">Home</a>
                    <a class="nav-link fw-bold py-1 px-0" href="#">Features</a>
                    <a class="nav-link fw-bold py-1 px-0" href="#">Contact</a>
                </nav> -->
                </div>
            </header>

            <main class="px-3 text-center">
                <h1>Start your healing journey with us!</h1>
                <p class="lead">Heal is a one-stop solution for booking and managing apppointments with world class therapists. One-on-one consultation, Q&A support, prescription management to help you in your healing process.</p>
                <p class="lead">
                <a href="<?= base_url('user-login') ?>" class="btn btn-lg btn-success m-2">Log In</a>
                <a href="<?= base_url('user-signup') ?>" class="btn btn-lg btn-success m-2">Sign Up</a>
                </p>
            </main>

            <footer class="mt-auto text-center">
                <p>Cover page for Heal, by <a href="https://github.com/SumitM01">@SumitM01</a>.</p>
            </footer>
        </div>

    </body>
</html>

