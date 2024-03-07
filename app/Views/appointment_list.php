<?php
/**
 * 
 * List view for all appointments for a user 
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
        <style>
            .btn.active{
                background-color:rgb(0, 60, 82);
                color: white;
            }
        </style>

        <!-- Load Bootstrap JS and dependencies -->
        <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
        <script>
            var button = document.getElementById('activeButton');
            button.addEventListener('click', function(){
                button.classList.toggle("btn active");
            })
        </script>
        <title>Appointments</title>
    </head>

    <body>
        <?php
            if (isset($responseData)) {
                $therapists = [];
                $dates = [];
                $statuses = [];

                foreach ($responseData as $apt) {
                    if ($apt['fieldData']['User_ID'] == session()->get('id')) {
                        // Collect unique therapists
                        $therapists[$apt['fieldData']['Therapist_Name']] = $apt['fieldData']['Therapist_Name'];

                        // Collect unique dates
                        $dates[date('d-m-Y', strtotime($apt['fieldData']['Date']))] = date('d-m-Y', strtotime($apt['fieldData']['Date']));

                        // Collect unique statuses
                        $statuses[$apt['fieldData']['Status']] = $apt['fieldData']['Status'];
                    }
                }
            }
            $hourDict = [
            '00:00:00' => '12:00 AM', '09:00:00' => '09:00 AM', '10:00:00' => '10:00 AM', '11:00:00' => '11:00 AM',
            '12:00:00' => '12:00 PM', '13:00:00' => '01:00 PM', '14:00:00' => '02:00 PM',
            '15:00:00' => '03:00 PM', '16:00:00' => '04:00 PM', '17:00:00' => '05:00 PM'
            ];
        ?>
        <!-- Delete Warning Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Cancel Appointment</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                Cancelling an appointment will remove all appointment data. Are you sure?
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Not now</button>
                <a id="confirmCancel" href="#" class="btn btn-outline-danger">Yes, Cancel</a>

                </div>
            </div>
            </div>
        </div>
        <!-- Main container containing sidebar and user-content -->
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
                    <div class="button-group" role="group">
                        <a href="#" class="btn active">List</a>
                        <a href="/appointments-cal" class="btn">Calendar</a>
                        <div class="btn-group" role="group" id="filters">
                            <a href="#" class="d-flex align-items-center dropdown-toggle btn" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
                                    <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/>
                                </svg>
                                <strong>Filter by</strong>
                            </a>
                            <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser1">
                                <li class="d-flex m-2">
                                    <label for="therapist-filter" class="m-2">Therapist:</label>
                                    <select id="therapist-filter" class="m-2">
                                        <option value="">All Therapists</option>
                                        <?php foreach ($therapists as $therapist) : ?>
                                            <option value="<?= $therapist; ?>"><?= $therapist; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </li>
                                <li class="d-flex m-2">
                                    <label for="date-filter" class="m-2">Date:</label>
                                    <select id="date-filter" class="m-2">
                                        <option value="">All Dates</option>
                                        <?php foreach ($dates as $date) : ?>
                                            <option value="<?= $date; ?>"><?= $date; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </li>
                                <li class="d-flex m-2">
                                    <label for="status-filter" class="m-2">Status:</label>
                                    <select id="status-filter" class="m-2">
                                        <option value="">All Statuses</option>
                                        <?php foreach ($statuses as $status) : ?>
                                            <option value="<?= $status; ?>"><?= $status; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                </div>
                <!-- div containing appointments list -->
                <div class="list-group">
                    <div class="table-responsive">
                        <table class="table align-middle" style="overflow-x:auto" id="table">
                            <thead class="table-light">
                                <tr>
                                    <th data-order="asc">Therapist Name</th>
                                    <th data-order="asc">Date</th>
                                    <th data-order="asc">Time</th>
                                    <th data-order="asc">Status</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($responseData))
                                    {   
                                        // print_r($responseData);
                                        foreach($responseData as $apt){
                                            if($apt['fieldData']['User_ID'] == session()->get('id')){ ?>
                                <tr>
                                    <td><?php echo($apt['fieldData']['Therapist_Name']) ?></td>
                                    <td><?php echo(date('d-m-Y', strtotime($apt['fieldData']['Date']))) ?></td>
                                    <td><?php echo($hourDict[$apt['fieldData']['Time_Slot']]) ?></td>
                                    <td><?php echo($apt['fieldData']['Status']) ?></td>
                                    <td><a href="<?= base_url("/apt-update")?>?id=<?= urlencode($apt['recordId']) ?>" class="btn btn-outline-success">Update</a></td>
                                    <td><button type="button" class="btn btn-outline-danger cancel-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-apt-id="<?= $apt['recordId']; ?>">Cancel</button></td>
                                    <!-- <td><a href="<?= base_url("/apt-delete")?>?id=<?= urlencode($apt['recordId']) ?>" class="btn btn-outline-danger" id="delete" style="visibility:hidden;">Cancel</a></td> -->
                                </tr>
                                <?php } 
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            var cancelButtons = document.querySelectorAll('.cancel-btn');
            var modalConfirmButton = document.getElementById('confirmCancel');

            cancelButtons.forEach(function(cancelButton) {
                cancelButton.addEventListener('click', function() {
                    var aptId = cancelButton.getAttribute('data-apt-id');
                    console.log(aptId);
                    var modalConfirmLink = "<?= base_url("/apt-delete")?>?id=" + encodeURIComponent(aptId);
                    modalConfirmButton.setAttribute('href', modalConfirmLink);
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                // Selecting filter elements
                var therapistFilter = document.getElementById('therapist-filter');
                var dateFilter = document.getElementById('date-filter');
                var statusFilter = document.getElementById('status-filter');

                // Selecting table body
                var tableBody = document.querySelector('tbody');

                // Filter function
                function applyFilters() {
                    var therapistValue = therapistFilter.value;
                    var dateValue = dateFilter.value;
                    var statusValue = statusFilter.value;

                    // Loop through each row in the table
                    var rows = tableBody.querySelectorAll('tr');
                    rows.forEach(function(row) {
                        var therapistCell = row.querySelector('td:nth-child(1)').textContent.trim();
                        var dateCell = row.querySelector('td:nth-child(2)').textContent.trim();
                        var statusCell = row.querySelector('td:nth-child(4)').textContent.trim();

                        // Check if the row should be displayed based on the filter values
                        var showRow =
                            (therapistValue === '' || therapistCell === therapistValue) &&
                            (dateValue === '' || dateCell === dateValue) &&
                            (statusValue === '' || statusCell === statusValue);

                        // Show/hide the row accordingly
                        row.style.display = showRow ? 'table-row' : 'none';
                    });
                }

                // Event listeners for filter changes
                therapistFilter.addEventListener('change', applyFilters);
                dateFilter.addEventListener('change', applyFilters);
                statusFilter.addEventListener('change', applyFilters);


                
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Select the table element
            var appointmentTable = document.querySelector('table');

            // Get the table body for sorting rows
            var tableBody = appointmentTable.querySelector('tbody');

            // Get the table rows for sorting
            var tableRows = Array.from(tableBody.querySelectorAll('tr'));

            // Add click event listeners to table headers for sorting
            var headers = appointmentTable.querySelectorAll('th');
            headers.forEach(function(header, index) {
                header.addEventListener('click', function() {
                    var sortOrder = this.getAttribute('data-order') || 'asc';
                    var column = index;

                    // Toggle the sorting order
                    sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
                    this.setAttribute('data-order', sortOrder);

                    // Sort the table rows based on the selected column
                    tableRows.sort(function(rowA, rowB) {
                        var cellA = rowA.querySelectorAll('td')[column].textContent.trim();
                        var cellB = rowB.querySelectorAll('td')[column].textContent.trim();

                        if (sortOrder === 'asc') {
                            return cellA.localeCompare(cellB);
                        } else {
                            return cellB.localeCompare(cellA);
                        }
                    });

                    // Re-append sorted rows to the table
                    tableRows.forEach(function(row) {
                        tableBody.appendChild(row);
                    });
                });
            });
        });
        </script>
    </body>
</html>

