<?php
/**
 * 
 * This view is for displaying the Therapist Details for a therapist
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
        <link rel="stylesheet" href="<?= base_url('assets/css/doctor_view.css') ?>">

        <!-- Load Bootstrap JS and dependencies -->
        <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
        <title>Doctor Details</title>
        <link rel="icon" href="https://i.imgur.com/ry5gKrT.png" type="image/x-icon">
    </head>
    <body>
        <div class="ba-container">
            <!-- Main content containing the sidebar and the doctors list container in a flex box -->
            <div class="d-flex" id="main">

                <!-- sidebar -->
                <div class="d-flex flex-column flex-shrink-0 p-3 bg-white" style="width: 280px;" id="sidebar">
                    <a href="<?php base_url('doctor_view')?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                    <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                    <span class="fs-4"><h3>Heal</h3></span>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" aria-current="page">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-pulse-fill" viewBox="0 0 16 16">
                            <path d="M1.475 9C2.702 10.84 4.779 12.871 8 15c3.221-2.129 5.298-4.16 6.525-6H12a.5.5 0 0 1-.464-.314l-1.457-3.642-1.598 5.593a.5.5 0 0 1-.945.049L5.889 6.568l-1.473 2.21A.5.5 0 0 1 4 9z"/>
                            <path d="M.88 8C-2.427 1.68 4.41-2 7.823 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C11.59-2 18.426 1.68 15.12 8h-2.783l-1.874-4.686a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8z"/>
                            </svg>
                            Doctors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-view-list" viewBox="0 0 16 16">
                            <path d="M3 4.5h10a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2m0 1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1zM1 2a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 2m0 12a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 14"/>
                            </svg>
                            Appointments
                            </a>
                        </li>
                    </ul>
                    <hr>
    
                    <!-- dropdown for user operations -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                        <strong>Sumit</strong></a>
                        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                            <li><a class="dropdown-item" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                                </svg>Profile</a>
                            </li>
                            <li><a class="dropdown-item" href="<?= base_url('hello-world') ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                                <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                                </svg>Sign out</a>
                            </li>
                        </ul>
                    </div>
                </div>
    
                <!-- ################################################################################################## -->
    
                <!-- Container containing list of doctors -->
                <div class="flex-grow-1 h-100 p-3 rounded">
                    <!-- div containing select doctors and filters button -->
                    <div class="row">
                        <div class="col-md-1">
                            <a href="<?= base_url('doctors_list') ?>" class="btn" style="border-radius: 50%;"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="40" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                            </svg></a>
                        </div>
                        <div class="col-md-10">
                            <h2>Doctor Details</h2>
                        </div>
                    </div>
                    
                    <!-- div containing doctors list -->
                    <div class="cover-container p-3 mx-auto" style="height: 80%; background-color: white;">
                        <div class="row">
                            <div class="col-md-5 text-center">
                                <img src="https://i.imgur.com/s1Aa8So.png" class="m-3 img-fluid" alt="Doctor Image" style="max-width: 70%">
                                <h5 class="card-title">Dr. Amelia Chopitea Villa</h5>
                                <p class="card-text"><b>Cardiologist</b></p>
                                <ul class="d-flex justify-content-center">
                                    <li class="mx-3">
                                        MBBS
                                    </li>
                                    <li class="mx-3">
                                        10 years Exp
                                    </li>
                                    <li class="mx-3">
                                        Milan
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-7 text-center my-auto">
                                <p class="p-3">Dr. Amelia Chopitea Villa is a highly experienced MBBS doctor specializing in internal medicine. With over 10 years of practice in Milan.Her commitment to excellence and continuous learning ensures that her patients receive the highest quality medical care. Whether it's preventive care, diagnosis, or treatment.</p>
                                <p class="lead">
                                <a href="<?= base_url('/apt-book') ?>?therapist=<?= urlencode('Dr. Amelia Chopitea Villa') ?>" class="btn btn-lg">Book Appointment</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </body>