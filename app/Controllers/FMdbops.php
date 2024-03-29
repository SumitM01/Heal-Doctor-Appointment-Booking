<?php
/**
 * 
 * This controller is used for operations on the filemaker database using Filemaker DataAPI
 * 
 * @author sumit mishra cr7sumitmishra@gmail.com
 * @version 1.0
 * 
 */
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

class FMDBOps extends BaseController
{
    //Global token to be used for every call.
    private $Token;
    // private $DBURL = $_ENV['URL'];
    // private $User = $_ENV['USER'];
    // private $Pass = $_ENV['PASS'];
    public function index()
    {
        
    }

    //Function which validates the session and gets the session token.
    public function fmValidateSession()
    {
        //Send request to Filemaker data API to authenticate the user and get an access token to store.
        try {
            $client = Services::curlrequest();
            $response = $client->request('POST','https://'.urlencode($_ENV['URL']).'/fmi/data/vLatest/databases/Therapy_Service_Tracker_Data/sessions', [
            'headers'=> [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($_ENV['USER'] . ':' . $_ENV['PASS'])
            ],
                'verify' =>  false //Ignore SSL certificate validation
            ]);
            // Check the response status data['code']
            if ($response->getStatusCode() == 200) {
                
                //Decode the json response body
                $responseData = json_decode($response->getBody(), true);
                //Get the session token and store it in session array
                $ses_data = [
                    'FMDataAPIToken' => $responseData['response']['token']
                ];
                session()->set($ses_data);
                
                // $this->index($responseData['response']['token']);
            }
        }
        catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    //Function to insert data into FM database
    public function fmInsertData($data)
    {
        
        //Prepare the json body to be sent with the request
        $requestBody = [
            "fieldData"=> [
                "UserID"=>$data['User_ID'],
                "TherapistName"=>$data['Therapist_Name'],
                "Date"=>date('m-d-Y',strtotime($data['Date'])),
                "TimeSlot"=>$data['Time_slot'],
            ]
        ];
        $data = [];
        $client = Services::curlrequest();
        // print_r("Bearer ".session()->get('FMDataAPIToken')); //debug purpose

        //Send the request to create new record using the body defined above
        try {
           
            $response = $client->request('POST','https://'.urlencode($_ENV['URL']).'/fmi/data/vLatest/databases/Therapy_Service_Tracker_Data/layouts/APPOINTMENTS/records', [
                'headers'=> [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . session()->get('FMDataAPIToken')
                ],
                    'verify' =>  false, //Ignore SSL certificate validation
                    'body'=> json_encode($requestBody)
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() == 200) {
                $data['code'] = $response->getStatusCode();
                
                //Insert the returned recordID in the MySQL DB for future use
                $recordID = json_decode($response->getBody(), true)['response']['recordId'];
                $data['fmRecordId'] = $recordID;
                // print_r($data); exit();
            } 
            else {
                // if any error occurs just record it
                
                $data['code'] = $response->getStatusCode();
                
            }
        }
        catch (\Exception $e) {
            
            // Check if the exception is due to an expired session token
            if (strpos($e->getMessage(), '401') !== false) {
                // print_r($e->getMessage()); exit();
                // Renew the session token
                $this->fmValidateSession();
                
                // Retry the insert operation with the new session token
                return $this->fmInsertData($data);
            } 
            else {
                // Handle other exceptions
                // echo "Error: " . $e;
                $data['code'] = "Error";
            }
        }
        return $data;
    }

    // Get the therapist list from the FileMaker Database
    public function fmGetTherapistData($retryCount = 0)
    {
        $data = [];
        //Fetch the data from FileMaker Database using Data API call
        try {
            $client = Services::curlrequest();

            $response = $client->request('GET', 'https://'.urlencode($_ENV['URL']).'/fmi/data/vLatest/databases/Therapy_Service_Tracker_Data/layouts/Therapist_Table/records',[
                'headers'=> [
                    'Authorization' => 'Bearer ' . session()->get('FMDataAPIToken')
                ],
                    'verify' =>  false
            ]);
            $responseData = json_decode($response->getBody(), true);
            
            $data['responseData'] =  $responseData['response']['data'];
            // print_r($data['responseData']); exit();
        }
        catch (\Exception $e) {
            // Check if the exception is due to an expired session token
            if (strpos($e->getMessage(), '401') !== false && $retryCount < 2) {
                // Renew the session token
                $this->fmValidateSession();
                // print_r($e->getMessage());

                // Retry the operation with the new session token
                return $this->fmGetTherapistData($retryCount+1);
            } 
            else {
                // Handle other exceptions
                // echo "Error: " . $e;
                print_r($e->getMessage());
            }
        
        }
        // print_r($data); exit();
        return $data;
    }

    // Get the appointments list for a user from the Filemaker Database
    public function fmGetAptData() 
    {
        $data = [];
        //Fetch the data from FileMaker Database using Data API call
        try {
            $client = Services::curlrequest();

            $response = $client->request('GET', 'https://'.urlencode($_ENV['URL']).'/fmi/data/vLatest/databases/Therapy_Service_Tracker_Data/layouts/APPOINTMENTS/records',[
                'headers'=> [
                    'Authorization' => 'Bearer ' . session()->get('FMDataAPIToken')
                ],
                    'verify' =>  false
            ]);
            $responseData = json_decode($response->getBody(), true);
            
            $data['responseData'] =  $responseData['response']['data'];
            
            // print_r($data['responseData']);
        }
        catch (\Exception $e) {
            // Check if the exception is due to an expired session token
            if (strpos($e->getMessage(), '401') !== false) {
                // Renew the session token
                $this->fmValidateSession();
                // print_r($e->getMessage());

                // Retry the insert operation with the new session token
                return $this->fmGetAptData();
            } 
            else {
                // Handle other exceptions
                // echo "Error: " . $e;
                print_r($e->getMessage());
            }
        }
        return $data;
    }

   

    public function fmUpdateData($data , $retryCount = 0)
    {
        //Prepare the new session body to be sent
        $requestBody = [
            "fieldData"=> [
                "Date"=>date('m-d-Y',strtotime($data['Date'])),
                "TimeSlot"=>$data['Time_slot'],
            ]
        ];
        $data['code'] = '';
        $client = Services::curlrequest();
        // print_r("Bearer ".session()->get('FMDataAPIToken')); //debugging purpose

        // Send the request to update the record in the database using the fmRecordId
        try {
           
            $response = $client->request('PATCH','https://'.urlencode($_ENV['URL']).'/fmi/data/vLatest/databases/Therapy_Service_Tracker_Data/layouts/APPOINTMENTS/records/'.urlencode($data['fmRecordId']), [
                'headers'=> [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . session()->get('FMDataAPIToken')
                ],
                    'verify' =>  false, //Ignore SSL certificate validation
                    'body'=> json_encode($requestBody)
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() == 200) {
                $data['code'] = $response->getStatusCode();
            } 
            else {
                // Error occurred
                $data['code'] = $response->getStatusCode();
            }
        }
        catch (\Exception $e) {
            print_r($e->getMessage()); exit();
            // Check if the exception is due to an expired session token
            if (strpos($e->getMessage(), '401') !== false && $retryCount < 2) {
                print_r($e->getMessage()); 
                // Renew the session token
                $this->fmValidateSession();
                // print_r($e->getMessage());

                // Retry the insert operation with the new session token
                return $this->fmUpdateData($data, $retryCount + 1);
            }
            else {
                // Handle other exceptions
                $data['code'] = "Error";
            }
        }
        return $data['code'];
    }

    // FileMaker API function to delete data in the FileMaker Database using the recordId
    public function fmDeleteApt($id)
    {
        $client = Services::curlrequest();

        //Delete the data from the database
        try {
            $response = $client->request('DELETE','https://'.urlencode($_ENV['URL']).'/fmi/data/vLatest/databases/Therapy_Service_Tracker_Data/layouts/APPOINTMENTS/records/'.urlencode($id), [
                'headers'=> [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . session()->get('FMDataAPIToken')
                ],
                    'verify' =>  false, //Ignore SSL certificate validation
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() == 200) {
                $data['code'] = $response->getStatusCode();
            }
            else {
                // Error occurred
                $data['code'] = $response->getStatusCode();
            }
            
        }
        catch (\Exception $e) {
            // Check if the exception is due to an expired session token
            if (strpos($e->getMessage(), '401') !== false) {
                // Renew the session token
                $this->fmValidateSession();
                // print_r($e->getMessage());

                // Retry the insert operation with the new session token
                return $this->fmDeleteApt($id);
            } 
            else {
                // Handle other exceptions
                // echo "Error: " . $e;
                print_r($e->getMessage());
            }
        }
        return $data;
    }
    
}
