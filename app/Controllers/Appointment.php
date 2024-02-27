<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AppointmentsModel;
use App\Controllers\HomeController;
use App\Controllers\FMdbops;

class Appointment extends BaseController
{
    public function index()
    {
        //
    }

    public function create(){
        if($this->request->getMethod() == "post"){
            
            $model = new AppointmentsModel();
            
            $data = [
                'User_ID' => session()->get('id'),
                // 'Therapist_ID'=>$this->request->getPost('thrid'),
                'Therapist_Name' => $this->request->getPost('thrname'),
                'Date' => $this->request->getPost('date'),
                'Time_slot' => date("H:i:s",strtotime($this->request->getPost('time'))),
                'Status' => 'Active',
            ];

            // Insert the provided data into the Filemaker Database using Data API.
            try{
                $controller = new FMdbops();
                $response = $controller->fmInsertData($data);
                $data['msg'] = $response['code'];
                $data['fmRecordId'] = $response['fmRecordId'];
            }
            catch(\Exception $e){
                $data['msg'] = $e->getMessage();
            }

            // Insert data into local MySQL database
            try{
                if($model->insert($data)){
                    $data['msg'] = 'Success! Data inserted successfully!';
                    
                }
                else{
                    $data['msg'] = 'Failure during data insertion!';
                }
            }
            catch(\Exception $e){
                $data['msg'] = $e->getMessage();
            }

            return view('doctors_list_view', $data);
        }
        else{
            return view('Apt_booking');
        }
        
    }

    public function update(){
        if($this->request->getMethod() == "post"){
            $model = new AppointmentsModel();

            // Get the Appointment ID from the local db using the recordId
            try{
                // print_r($this->request->getPost('aptid'));
                $apt_id = $model->getAppointmentByFMRecordId($this->request->getPost('aptid'))['Appointment_ID'];
            }
            catch(\Exception $e){
                $data['msg'] = $e->getMessage();
            }
            $data = [
                'Date' => $this->request->getPost('date'),
                'Time_slot' => date("H:i:s",strtotime($this->request->getPost('time'))),
            ];
            
            
            //update data in the local database
            try{
                if($model->update($apt_id, $data)){
                    $data['msg'] = 'Success! Data updated successfully!';
                    // print_r($model->getLastQuery());
                }
                else{
                    $data['msg'] = 'Failure during data updation!';
                }
            }
            catch(\Exception $e){
                $data['msg'] = $e->getMessage();
            }

            // Insert the provided data into the Filemaker Database using Data API.
            try{
                $controller = new FMdbops();
                $data['fmRecordId'] = $this->request->getPost('aptid');
                $data['msg'] = $controller->fmUpdateData($data);
            }
            catch(\Exception $e){
                $data['msg'] = $e->getMessage();
            }
        }
        else{
            return view('apt_update');
        }
        return view('Apt_Booking', $data);
    }

    public function delete(){
        
        if($this->request->getMethod() == 'get'){
            $id = $this->request->getGet('id');
            // print_r($id); 
            $model = new AppointmentsModel();

            // Delete the Appointment ID from the local db using the fmRecordId
            try{
                // print_r($this->request->getPost('aptid'));
                $model->where('fmRecordId', $id)->delete();
                // print_r($apt_id); exit();
                // $model->delete($apt_id);
                // exit();
            }
            catch(\Exception $e){
                $data['msg'] = $e->getMessage();
            }

            // Delete the Appointment from the FileMaker Database using fmRecordId
            try{
                $controller = new FMdbops();
                // $data['fmRecordId'] = $this->request->getPost('aptid');
                $data['msg'] = $controller->fmDeleteApt($id);
            }
            catch(\Exception $e){
                $data['msg'] = $e->getMessage();
            }
        }
        return redirect('appointments');
    }

    public function fetchAppointmentsByUserIdDate()
    {
        // Load the model
        $appointmentsModel = new AppointmentsModel();

        // Get User ID and Date from request or session, for example
        $userId = $this->request->getVar('user_id');
        $date = $this->request->getVar('date');  
        

        // Call the model function
        $appointments = $appointmentsModel->getAppointmentsByUserIdDate($userId, date('Y-m-d',strtotime($date)));
        $data = [
            'times'=> []
        ];
        foreach($appointments as $appointment){
          array_push($data['times'], $appointment['Time_slot']);
        }
        print_r(json_encode($data)); exit();

        // Do something with $appointments, like passing it to a view
        // header('Content-Type: application/json');
        // echo json_encode($data);
        // exit();

    }

}