<?php
/**
 * 
 * Calendar View for booked appointments
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
        <!-- <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'> -->
        <style>
            .btn.active{
                background-color:rgb(0, 60, 82);
                color: white;
            }
            /* The Modal (background) */
            .modal {
                display: none; /* Hidden by default */
                position: absolute; /* Stay in place */
                /* z-index: 1; Sit on top */
                left: 40%;
                top: 10%;
                width: fit-content; 
                height: fit-content; 
                
                /* background-color: rgba(0, 0, 0, 0.4); */
                justify-content: center;
                align-items: center;
                /* display: flex; Flexbox for centering */
            }

            /* Modal Content/Box */
            .modal-content {
                box-shadow: 20px 20px 20px 15px grey;
                background-color: #fefefe;
                margin: 15% auto; 
                padding: 20px;
                border: 1px solid #888;
                width: 100%; 
            }

            /* The Close Button */
            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                background-color: white;
                border: none;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
                background-color: white;
                border: none;
            }
        </style>

        <!-- Load Bootstrap JS and dependencies -->
        <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
        <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/index.global.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.11/index.global.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
        <script>

            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                eventClick: function(info) {
                    var eventObj = info.event;
                    
                    // Get the modal and its content
                    var myModal = new bootstrap.Modal(document.getElementById("myModal"));
                    var modalContent = document.getElementById("modalContent");
                    var dateString = eventObj.start.toLocaleDateString('en-US');
                    var timeString = eventObj.start.toLocaleTimeString('en-US');

                    //Get the current date and time
                    const currentDate = new Date();
                    const currentDateString = currentDate.toISOString().split('T')[0];
                    const currentTimeString = currentDate.toTimeString().split(' ')[0];

                    //Convert date and time to date object
                    const eventDate = new Date(eventObj.start);
                    console.log(eventDate);

                    //Compare eventDate with current time and date and populate the status
                    var status;
                    if(eventDate < currentDate)
                    {
                        status = 'Expired';
                    }
                    else if(eventDate.toISOString().split('T')[0] === currentDateString && eventDate.toTimeString().split(' ')[0] < currentTimeString)
                    {
                        status = 'Pending';
                    }
                    else{
                        status = "Upcoming";
                    }
                    // Populate modal content with event details
                    modalContent.innerHTML = `
                        <p><b>${eventObj.title}</b></p>
                        <p><b>Date</b>: ${dateString}</p>
                        <p><b>Time</b>: ${timeString}</p>
                        <p><b>Status</b>: ${status}</p>
                    `;

                    // Show the modal
                    myModal.show();

                    // Close modal when close button is clicked
                    var closeButton = document.querySelector(".modal .close");
                    closeButton.addEventListener("click", function() {
                        myModal.hide();
                    });

                    // Close modal when clicked outside
                    window.addEventListener("click", function(event) {
                        if (event.target === myModal._element) {
                            myModal.hide();
                        }
                    });
                },
                // height: '100%',
                // themeSystem: 'standard',
                initialView: 'dayGridMonth',
                events: [
                    <?php
                        if(isset($responseData))
                        {   
                            // print_r($responseData);
                            foreach($responseData as $apt){
                                if($apt['fieldData']['UserID'] == session()->get('id')){ ?>
                    { 
                    id: '<?php echo $apt['fieldData']['AppointmentID']; ?>',
                    title: 'Appointment with Dr. <?php echo $apt['fieldData']['TherapistName']; ?>' ,
                    start: '<?php echo date('Y-m-d', strtotime($apt['fieldData']['Date']));?>T<?php echo $apt['fieldData']['TimeSlot'];?>',
                    // end: '2024-02-09T01:00:00',
                    color: 'green'
                    },
                    <?php }
                        }
                    } ?>
                ]
                
                
                });
                // console.log(calendar);
                calendar.render();
            });

            var button = document.getElementById('activeButton');
            button.addEventListener('click', function(){
                button.classList.toggle("btn active");
            })

        </script>
        <title>Appointments</title>
        <link rel="icon" href="https://i.imgur.com/ry5gKrT.png" type="image/x-icon">
    </head>

    <body>
    <div id="eventPopover" class="popover fade show bs-popover-top" role="tooltip" style="display: none;">
    <div class="arrow"></div>
    <div id="eventPopoverContent" class="popover-body"></div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Appointment Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="modalContent">
            <!-- Appointment details will be populated here -->
        </div>
        </div>
    </div>
    </div>
    <div class="d-flex" id="main">
        <!-- sidebar -->
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-white" style="width: 20%;" id="sidebar">
            <a href="<?php base_url('doctor_view')?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
            <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
            <span class="fs-4"><h3>Heal</h3></span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="<?= base_url("/doctors_list") ?>" class="nav-link" aria-current="page">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-pulse-fill" viewBox="0 0 16 16">
                    <path d="M1.475 9C2.702 10.84 4.779 12.871 8 15c3.221-2.129 5.298-4.16 6.525-6H12a.5.5 0 0 1-.464-.314l-1.457-3.642-1.598 5.593a.5.5 0 0 1-.945.049L5.889 6.568l-1.473 2.21A.5.5 0 0 1 4 9z"/>
                    <path d="M.88 8C-2.427 1.68 4.41-2 7.823 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C11.59-2 18.426 1.68 15.12 8h-2.783l-1.874-4.686a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8z"/>
                    </svg>
                    Doctors
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link active">
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
                    <!-- <li><a class="dropdown-item" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                        </svg>Profile</a>
                    </li> -->
                    <li><a class="dropdown-item" href="<?= site_url('user-logout') ?>" >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                        <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                        </svg>Sign out</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Appointments view -->
        <div class="flex-grow-1 bg-light m-4 p-3 rounded">
            <!-- List of appointments -->
            <div class="d-flex justify-content-between m-3">
            <div class="row">
            <div class="col-md-3">
                <a href="<?= base_url('doctors_list') ?>" class="btn" style="border-radius: 50%;"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="40" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                </svg></a>
            </div>
            <div class="col-md-9">
                <h2>Appointments</h2>
            </div>
            </div>
            <div class="button-group">
                <a href="/appointments" class="btn">List</a>
                <a href="#" class="btn active">Calendar</a>
            </div>
                
            </div>
            <!-- div containing appointments calendar -->
            <div id="calendar" style="background-color: white"></div>
        </div>
    </div>
        
    </body>
</html>

