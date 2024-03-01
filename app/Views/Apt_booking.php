<?php
use App\Models\AppointmentsModel;
?>

<!DOCTYPE html>
<html>
  <head>
    <!-- Load Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/cover.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/doctor_view.css') ?>">
    <style>
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
    <title>Book Appointment </title>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script>

      document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar')
        const calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        })
        calendar.render()
      })

    </script>
  </head>
  <body>
   <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Appointment Created</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body" id="modalContent">
              <!-- Appointment details will be populated here -->
          </div>
          <div class="modal-footer">
                  <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Dismiss</button>
          </div>
          </div>
      </div>
      </div>
    <?php
    $therapistName = $_GET['therapist'] ?? '';
    $flashdata = session()->getFlashdata('data');
    
    if (!empty($flashdata)) {
      // print_r($flashdata['msg']);
      
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
          var myModal = new bootstrap.Modal(document.getElementById("myModal"));
          var modalContent = document.getElementById("modalContent");

          // Populate modal content with event details
          modalContent.innerHTML = `Your appointment has been created and <?php echo $flashdata['msg'] ?>`;

          // Show the modal
          myModal.show();

          // Close modal when close button is clicked
          var closeButton = document.querySelector(".modal .close");
          closeButton.addEventListener("click", function() {
              myModal.hide();
          });
        });
        
        </script>
        <?php
      }
    ?>
    
    <div class="container h-100 p-4 bg-white rounded">
        <div class="row">
          <div class="col-md-1">
            <a href="<?= base_url('doctors_list') ?>" class="btn" style="border-radius: 50%;"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="40" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
            </svg></a>
            
          </div>
          <div class="col-md-11">
            <h2>Book Appointment</h2>
          </div>
        </div>
        <div class="row mt-4">
            <!-- Mini Calendar -->
            <div class="col-md-6">
                <div id="calendar"></div>
            </div>
            <!-- Time Slots Tab -->
            <div class="col-md-6">
              <?php if(isset($msg)): ?>
                <div class="col-12">
                    <div class="alert alert-warning" role="alert">
                        <?= $msg ?> <!-- Display success/errors -->
                    </div>
                </div>
              <?php endif; ?>
              <div class="row">
                <div class="tab-content" id="timeSlotsTab">
                    <!-- Time slots content will be displayed here -->
                </div class="position-absolute">
              </div>
              <div class="row">
                <div class = "position-relative start-25">
                  <form id="apt_form" action="/apt-create" method="post">
                    <button class="btn btn-success py-2 w-100" id="submitbutton" type="submit" style="visibility: hidden;">Book Appointment</button>
                    <input type="number" class="form-control" name="userid" style="visibility: hidden;">
                    <!-- <input type="number" class="form-control" name="thrid" style="visibility: hidden;"> -->
                    <input type="text" class="form-control" name="thrname" value="<?= htmlspecialchars($therapistName) ?>" style="visibility: hidden;">
                    <input type="date" class="form-control" name="date" style="visibility: hidden;">
                    <input type="time" class="form-control" name="time" style="visibility: hidden;">
                  </form>
                </div>
              </div>
            </div>
        </div>
        
        
    </div>
    <!-- Include Bootstrap JS and FullCalendar JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.js"></script>
    <script src="public\assets\js\getDBdata.js"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            dateClick: function(info) {
                // When a date is clicked, show time slots for that date
                showTimeSlots(info.dateStr);
                // console.log(info.dateStr);

                //Also store the date in the form to be submitted for appointment booking
                document.getElementsByName('date')[0].value = info.dateStr;
                document.getElementById('submitbutton').style ="visibility: hidden;";
            }
        });
        calendar.render();

        //Take a time string and convert the string into time format hh:mm:ss
        function convertTimeTo24HourFormat(time12h) {
            var [time, modifier] = time12h.split(' ');
            var [hours, minutes] = time.split(':');

            if (hours === '12') {
                hours = '00';
            }

            if (modifier === 'PM') {
                hours = parseInt(hours, 10) + 12;
            }

            return `${hours}:${minutes}:00`;
        }

        function generateTimeSlotsHTML(timeSlots, aptTimes, selectedDate){
          // Create HTML for time slots
          var html = '<div class="tab-pane fade show active" id="timeSlotsContent" role="tabpanel">';
          html += '<h3>Available Time Slots for ' + selectedDate + '</h3>';
          html += '<div class="row">'; // Start a row

          // Loop through time slots and generate columns
          timeSlots.forEach(function(slot, index) {
              // Add a new row after every third column
              if (index % 3 === 0 && index !== 0) {
                  html += '</div><div class="row">'; // Close current row and start a new one
              }
              //Check if the time slot is present in the aptTimes, if not print it.
              // console.log(convertTimeTo24HourFormat(slot));
              // console.log(typeof(convertTimeTo24HourFormat(slot)));
              // console.log(aptTimes.includes(convertTimeTo24HourFormat(slot)));
              if(aptTimes.includes(convertTimeTo24HourFormat(slot)) == false){
                // Add tab for the time slot
                
                html += '<div class="col-md-4">'; // Column
                html += '<button class="btn btn-outline-success m-3 timeslot-btn">' + slot + '</button>';
                html += '</div>'; // End column
              }
              
          });

          html += '</div>'; // Close the last row
          html += '</div>'; // Close the tab pane

          // Display time slots in the time slots tab
          document.getElementById('timeSlotsTab').innerHTML = html;

          // Add event listeners to the time slot buttons
          var timeSlotButtons = document.querySelectorAll('.timeslot-btn');
          timeSlotButtons.forEach(function(button) {
              button.addEventListener('click', function() {
                  var selectedTime = this.textContent;
                  document.getElementsByName('time')[0].value = convertTimeTo24HourFormat(selectedTime);
                  document.getElementById('submitbutton').style ="visibility: visible;";
              });
          });
        }

        function showTimeSlots(selectedDate) {
          // Predefined time slots
          var timeSlots = ['9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM'];

          //Fetch the related timings for the user ID and data selected from the appointment records
          var userid = <?php echo $_SESSION['id'] ?>;
          var aptTimes = [];
          //AJAX request to the PHP controller
          fetch("/getapttimes", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({user_id: userid, date: selectedDate})
          })
          .then(response => response.json())
          .then(data => {
            aptTimes = data.times;
            console.log(aptTimes);
            generateTimeSlotsHTML(timeSlots, aptTimes, selectedDate);
          })
          .catch(error => {
            console.error('Error', error);
          });
          
          
        }
      });
    </script>
  </body>
</html>