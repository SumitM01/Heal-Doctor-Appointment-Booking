<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AppointmentsModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use CodeIgniter\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use CodeIgniter\Database\Exceptions\DatabaseException;

class FMdbops extends BaseController
{
    //Global token to be used for every call.
    private $Token;
    // private $DBURL = "kibiz.smtech.cloud";
    private $DBURL = "172.16.8.153";
    // private $User = 'APIaccess2';
    private $User = 'APIaccess';
    private $Pass = 'dataAPI24';
    public function index($token)
    {
        $this->Token = $token;
    }

    //Function which validates the session and gets the session token.
    public function fmValidateSession(){
        //Send request to Filemaker data API to authenticate the user and get an access token to store.
        
        try{
            $client = Services::curlrequest();
            $response = $client->request('POST','https://'.urlencode($this->DBURL).'/fmi/data/vLatest/databases/Therapy_Service_Tracker_Data/sessions', [
            'headers'=> [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->User . ':' . $this->Pass)
            ],
                'verify' =>  false //Ignore SSL certificate validation
            ]);
            // Check the response status data['code']
            if($response->getStatusCode() == 200) {
                
                //Decode the response body
                $responseData = json_decode($response->getBody(), true);
                //Get the session token and store it
                // $_SESSION['fmToken'] = $responseData['response']['token'];
                $this->index($responseData['response']['token']);
                // $this->Token = $responseData['response']['token'];
            }
        }
        catch(\Exception $e){
            print_r('Validation Error');
        }
        // print_r("Stored Token: " . $this->Token);
        // return true;
    }

    //Function to insert data into FM database
    public function fmInsertData($data){
        $this->fmValidateSession();
        $requestBody = [
            "fieldData"=> [
                "User_ID"=>$data['User_ID'],
                "Therapist_Name"=>$data['Therapist_Name'],
                "Date"=>date('m-d-Y',strtotime($data['Date'])),
                "Time_Slot"=>$data['Time_slot'],
            ]
        ];
        // $body = json_encode($requestBody);
        $data = [];
        $client = Services::curlrequest();
        // print_r("Bearer ".$this->Token);
        try{
           
            $response = $client->request('POST','https://'.urlencode($this->DBURL).'/fmi/data/vLatest/databases/Therapy_Service_Tracker_Data/layouts/APPOINTMENTS/records', [
                'headers'=> [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->Token
                ],
                    'verify' =>  false, //Ignore SSL certificate validation
                    'body'=> json_encode($requestBody)
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() == 200) {
                $data['code'] = $response->getStatusCode();
                //Insert the returned recordID in the MySQL DB
                $recordID = json_decode($response->getBody(), true)['response']['recordId'];
                $data['fmRecordId'] = $recordID;
                // print_r($data); exit();
            } else {
                // Error occurred
                $data['code'] = $response->getStatusCode();
            }
        }
        catch(\Exception $e){
            // Check if the exception is due to an expired session token
            if (strpos($e->getMessage(), '401 Unauthorized') !== false) {
                // Renew the session token
                $this->fmValidateSession();
                print_r($e->getMessage());

                // Retry the insert operation with the new session token
                $this->fmInsertData($data);
            } else {
                // Handle other exceptions
                // echo "Error: " . $e;
                print_r($e->getMessage());
            }
        }
        return $data;
    }
//  Get the therapist list from the FileMaker Database
    public function fmGetTherapistData(){
        //Validate the session and get the validation token
        $this->fmValidateSession();
        $data = [];
        //Fetch the data from FileMaker Database using Data API call
        try{
            $client = Services::curlrequest();

            $response = $client->request('GET', 'https://'.urlencode($this->DBURL).'/fmi/data/vLatest/databases/Therapy_Service_Tracker_Data/layouts/Therapist_Table/records',[
                'headers'=> [
                    'Authorization' => 'Bearer ' . $this->Token
                ],
                    'verify' =>  false
            ]);
            $responseData = json_decode($response->getBody(), true);
            
            $data['responseData'] =  $responseData['response']['data'];
            // print_r($data['responseData']);
        }
        catch(\Exception $e){
            // print_r($e->getMessage());
            $data['responseData'] = $e->getMessage();
        }
        return $data;

    }

// Get the appointments list for a user from the Filemaker Database
    public function fmGetAptData(){
        //Validate the session and get the validation token
        $this->fmValidateSession();
        $data = [];
        //Fetch the data from FileMaker Database using Data API call
        try{
            $client = Services::curlrequest();

            $response = $client->request('GET', 'https://'.urlencode($this->DBURL).'/fmi/data/vLatest/databases/Therapy_Service_Tracker_Data/layouts/APPOINTMENTS/records',[
                'headers'=> [
                    'Authorization' => 'Bearer ' . $this->Token
                ],
                    'verify' =>  false
            ]);
            $responseData = json_decode($response->getBody(), true);
            
            $data['responseData'] =  $responseData['response']['data'];
            
            // print_r($data['responseData']);
        }
        catch(\Exception $e){
            // print_r($e->getMessage());
            $data['responseData'] = $e->getMessage();
        }
        return $data;
    }

   

    public function fmUpdateData($data){
        $this->fmValidateSession();
        $requestBody = [
            "fieldData"=> [
                "Therapist_Name"=>$data['Therapist_Name'],
                "Date"=>date('m-d-Y',strtotime($data['Date'])),
                "Time_Slot"=>$data['Time_slot'],
            ]
        ];
        $data['code'] = '';
        $client = Services::curlrequest();
        // print_r("Bearer ".$this->Token);
        try{
           
            $response = $client->request('PATCH','https://'.urlencode($this->DBURL).'/fmi/data/vLatest/databases/Therapy_Service_Tracker_Data/layouts/APPOINTMENTS/records/'.urlencode($data['fmRecordId']), [
                'headers'=> [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->Token
                ],
                    'verify' =>  false, //Ignore SSL certificate validation
                    'body'=> json_encode($requestBody)
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() == 200) {
                $data['code'] = $response->getStatusCode();
            } else {
                // Error occurred
                $data['code'] = $response->getStatusCode();
            }
        }
        catch(\Exception $e){
            // Check if the exception is due to an expired session token
            if (strpos($e->getMessage(), '401 Unauthorized') !== false) {
                // Renew the session token
                $this->fmValidateSession();
                print_r($e->getMessage());

                // Retry the insert operation with the new session token
                $this->fmInsertData($data);
            } else {
                // Handle other exceptions
                // echo "Error: " . $e;
                print_r($e->getMessage());
            }
        }
        return $data['code'];
    }

    // FileMaker API function to delete data in the FileMaker Database using the recordId
    public function fmDeleteApt($id){
        $this->fmValidateSession();
        $client = Services::curlrequest();

        //Delete the data from the database
        try{
            $response = $client->request('PATCH','https://'.urlencode($this->DBURL).'/fmi/data/vLatest/databases/Therapy_Service_Tracker_Data/layouts/APPOINTMENTS/records/'.urlencode($id), [
                'headers'=> [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->Token
                ],
                    'verify' =>  false, //Ignore SSL certificate validation
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() == 200) {
                $data['code'] = $response->getStatusCode();
            } else {
                // Error occurred
                $data['code'] = $response->getStatusCode();
            }
            
        }
        catch(\Exception $e){
            $data['msg'] = $response->getBody();
        }
        return $data;
    }
    
    // public function WrapperInsertData(){
    //     $User = 'APIaccess2';
    //     $Pass = 'dataAPI24';
    //     $requestBody = [
    //         "fmServer"=> "208.85.249.144",
    //         "method" => "createRecord",
    //         "methodBody" => [
    //             "database"=>"Therapy_Service_Tracker_Data",
    //             "layout"=>"APPOINTMENTS",
    //             "record"=>[
    //                 "Therapist_Name"=> "Varun Sharma",
    //                 "Date"=> "02-12-2024",
    //                 "Time_Slot"=> "16:34:00"
    //             ]
    //         ]
    //     ];

    //     try{
    //         $client = Services::curlrequest();
    //         $response = $client->request("POST","https://dataapi-o2iw.onrender.com/api/dataApi", [
    //             'headers'=> [
    //                 'Content-Type' => 'application/json',
    //                 'Authorization' => 'Basic ' . base64_encode($User . ':' . $Pass)
    //             ],
    //             'body'=> json_encode($requestBody)
    //         ]);
    //     }
    //     catch(\Exception $e){
    //         print_r($e);
    //     }
    // }
}
