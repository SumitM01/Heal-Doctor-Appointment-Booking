<?php
/**
 * 
 * This controller is used for all the appointment related operations
 * 
 * @author sumit mishra cr7sumitmishra@gmail.com
 * @version 1.0
 * 
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AppointmentsModel;
use App\Controllers\HomeController;
use App\Controllers\FMDBOps;

class Appointment extends BaseController
{
    //Model variables to be used
    private $AppointmentsModel;
    private $UsersModel;
    private $GoogleCalendarController;
    private $FMDBOpsController;

    //Constructor for Appointment controller
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        $this->AppointmentsModel = new AppointmentsModel();
        $this->UsersModel = new UsersModel();
        $this->GoogleCalendarController = new GoogleCalendar();
        $this->FMDBOpsController = new FMDBOps();
    }
    public function index()
    {
        //
    }
    //Function that creates an apppointment and stores the record in Filemaker DB, local MySQL DB and creates a google calendar event for that appointment
    public function create()
    {
        //Check if the method is POST then continue with create event operation
        if ($this->request->getMethod() == "post") {
            
            //Prepare the body to be sent with the request
            $data = [
                'User_ID' => session()->get('id'),
                'Therapist_Name' => $this->request->getPost('thrname'),
                'Date' => $this->request->getPost('date'),
                'Time_slot' => date("H:i:s",strtotime($this->request->getPost('time'))),
                'Status' => 'Active',
            ];

            //Insert data into google calendar 

            //Get the access token from the local MySQL DB
            $dbdata = $this->UsersModel->where('User_ID', session()->get('id'))->first();
            $accessToken = $dbdata['GoogleCalendarAccessToken'];

            //If the access token in non-empty try creating the event in the google calendar
            if (!empty($accessToken) ) {
                try {
                    $response = $this->GoogleCalendarController->createCalendarEvent($data);
                    // print_r($response);
                    $data['msg'] = $response['msg'];
                }   
                catch (\Exception $e) {
                    $data['msg'] = $e->getMessage();
                }
            }

            // Insert the provided data into the Filemaker Database using Data API.
            try {
                $response = $this->FMDBOpsController->fmInsertData($data);
                $data['msg'] = $response['code'];
                $data['fmRecordId'] = $response['fmRecordId'];
            }
            catch (\Exception $e) {
                $data['msg'] = $e->getMessage();
            }

            // Insert data into local MySQL database
            try {
                if($this->AppointmentsModel->insert($data)){
                    $data['msg'] = 'Success! Data inserted successfully!';
                }
                else{
                    $data['msg'] = 'Failure during data insertion!';
                }
            }
            catch (\Exception $e) {
                $data['msg'] = $e->getMessage();
            }

            
            return redirect()->back()->with('data', $data);
            
        }
        //Else return to apt_booking
        else{
            return view('Appointments\AptBooking');
        }
        
    }

    public function update()
    {
        if ($this->request->getMethod() == "post") {

            // Get the Appointment ID from the local db using the recordId
            try {
                // print_r($this->request->getPost('aptid'));
                $apt_id = $this->AppointmentsModel->getAppointmentByFMRecordId($this->request->getPost('aptid'))['Appointment_ID'];
                
            }
            catch (\Exception $e) { 
                $data['msg'] = $e->getMessage();
            }
            $data = [
                'Date' => $this->request->getPost('date'),
                'Time_slot' => date("H:i:s",strtotime($this->request->getPost('time'))),
            ];
            
            //update data in the local database
            try {
                if ($this->AppointmentsModel->update($apt_id, $data)) {
                    $data['msg'] = 'Success! Data updated successfully!';
                    // print_r($model->getLastQuery()); exit();
                }
                else {
                    $data['msg'] = 'Failure during data updation!';
                }
            }
            catch (\Exception $e) {
                $data['msg'] = $e->getMessage();
            }

            // Update the provided data for the record present in Filemaker Database using Data API.
            try {
                $data['fmRecordId'] = $this->request->getPost('aptid');
                $data['msg'] = $this->FMDBOpsController->fmUpdateData($data);
            }
            catch (\Exception $e) {
                $data['msg'] = $e->getMessage();
            }
            return redirect()->back()->with('data', $data);
        }
        else {
            return view('Appointments\AptUpdate');
        }
        // return view('Appointments\AptBooking', $data);
    }

    public function delete()
    {
        if ($this->request->getMethod() == 'get') {
            $id = $this->request->getGet('id');
            // print_r($id); 

            // Delete the Appointment ID from the local db using the fmRecordId
            try {
                // print_r($this->request->getPost('aptid'));
                $this->AppointmentsModel->where('fmRecordId', $id)->delete();
                
            }
            catch (\Exception $e) {
                $data['msg'] = $e->getMessage();
            }

            // Delete the Appointment from the FileMaker Database using fmRecordId
            try {
                // $data['fmRecordId'] = $this->request->getPost('aptid');
                $data['msg'] = $this->FMDBOpsController->fmDeleteApt($id);
            }
            catch (\Exception $e) { 
                $data['msg'] = $e->getMessage();
            }
        }
        return redirect('appointments');
    }

    public function fetchAppointmentsByUserIdDate()
    {
        // Load the model

        // Get User ID and Date from request or session, for example
        $userId = $this->request->getVar('user_id');
        $date = $this->request->getVar('date');  
        
        // Call the model function
        $appointments = $this->AppointmentsModel->getAppointmentsByUserIdDate($userId, date('Y-m-d',strtotime($date)));
        $data = [
            'times'=> []
        ];
        foreach ($appointments as $appointment) {
          array_push($data['times'], $appointment['Time_slot']);
        }
        print_r(json_encode($data)); 
        exit();
        // return $data;
    }

}