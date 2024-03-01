<?php
/**
 * 
 * This controller is used for all the Google Calendar API related operations.
 * 
 * @author sumit mishra cr7sumitmishra@gmail.com
 * @version 1.0
 * 
 */
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\ResponseInterface;

class GoogleCalendar extends BaseController
{
    public function index()
    {
        //
    }
    private $OAUTH_URI = "https://accounts.google.com/o/oauth2/v2/auth";
    private $OAUTH_TOKEN_URI = "https://accounts.google.com/o/oauth2/token";
    private $CALENDAR_LIST = "https://www.googleapis.com/calendar/v3/users/me/calendarList";
    private $CALENDAR_EVENT = "https://www.googleapis.com/calendar/v3/calendars/";
    //Function to authorize google calendar for the user 
    public function googleAuth()
    {
        $authUrl = $this->OAUTH_URI;
        $authUrl .= '?client_id=' . urlencode($_ENV['GOOGLE_CLIENT_ID']);
        $authUrl .= '&response_type=code';
        $authUrl .= '&access_type=offline';
        $authUrl .= '&scope=' . urlencode($_ENV['GOOGLE_OAUTH_SCOPE']);
        $authUrl .= '&redirect_uri=' . urlencode($_ENV['REDIRECT_URI']);

        return redirect()->to($authUrl);

        //Call the getAccessToken function to fetch the access token from the code provided
        // $auth_code = $this->request->getGet('code');
        // print_r($auth_code);
        // getAccessToken($_ENV['GOOGLE_CLIENT_ID'], )
        
    }

    // Function to get access token
    public function getTokens(){
        $auth_url = $this->OAUTH_TOKEN_URI;
        $auth_url .= '?code='. $_GET['code'];
        $auth_url .= '&client_id='. urlencode($_ENV['GOOGLE_CLIENT_ID']);
        $auth_url .= '&client_secret='. urlencode($_ENV['GOOGLE_CLIENT_SECRET']);
        $auth_url .= '&redirect_uri='. $_ENV['REDIRECT_URI'];
        $auth_url .= '&grant_type=authorization_code';

        // print_r($auth_url);
        try{
            $client = Services::curlrequest();
            $response = $client->request('POST', $auth_url);
            // print_r($response);
            // Check the response status data['code']
            if($response->getStatusCode() == 200) {
                //Decode the response body
                $responseData = json_decode($response->getBody(), true);
                $accessToken = $responseData['access_token'];
                $refreshToken = $responseData['refresh_token'];
                // print_r($accessToken);
                //Get the access token and store it in the database
                $model = new UsersModel();

                $data = [
                    'GoogleCalendarAccessToken' => $accessToken,
                    'GoogleCalendarRefreshToken' => $refreshToken
                ];
                // print_r($data);
                // print_r(session()->get('id'));
                $model->update(session()->get('id'), $data);

                //return to doctors_list page
                // return redirect()->to('/doctors_list');
                // return true;
            }
            else{
                // return false;
            }
        }
        catch(\Exception $e){
            print_r('Error '. $e->getMessage() . ' Inside getTokens()');
        }
        // return false;
        return redirect()->to('apt-book');
    }

    //Function to get the access Token by using Refresh Token
    public function updateAccessToken(){
        $model = new UsersModel();
        $recordData = $model->where('User_ID', session()->get('id'))->first();
        $refreshToken = $recordData['GoogleCalendarRefreshToken'];
        //Call the token endpoint to fetch the access token
        try{
            $client = Services::curlrequest();
            $response = $client->request('POST', 'https://accounts.google.com/o/oauth2/token', [
                'headers' => [
                    'Content-Type' => 'aplication/json'
                ],
                'json' => [
                    'client_id' => $_ENV['GOOGLE_CLIENT_ID'],
                    'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'],
                    'refresh_token' => $refreshToken,
                    'grant_type' => 'refresh_token'
                ]
            ]);

            if($response->getStatusCode() == 200) {
                // print_r($response->getStatusCode()); exit();
                $responseData = json_decode($response->getBody(), true);
                $accessToken = $responseData['access_token'];

                //Update the access token in the database
                $dbdata = [
                    'GoogleCalendarAccessToken' => $accessToken,
                ];
                $model->update(session()->get('id'), $dbdata);
            }
            else{
                print_r(''. $response->getStatusCode());
            }
            
        }
        catch(\Exception $e){
            print_r(''. $e->getMessage() . ' Inside updateAccessToken()');
        }
        // return $accessToken;
    }

    //Function to create an event in the user's google calendar
    public function createCalendarEvent($data, $retryCount = 0){

        $client = Services::curlrequest();
        $model = new UsersModel();
        $recordData = $model->where('User_ID', session()->get('id'))->first();
        $accessToken = $recordData['GoogleCalendarAccessToken'];
        $bodyData = [
            'end' => [
                'dateTime' => date('Y-m-d',strtotime($data['Date'])) . 'T' . date("H:i:s", strtotime($data['Time_slot']) + 3600),
                'timeZone' => "Asia/Kolkata"
            ],
            'start' => [
                'dateTime' => date('Y-m-d',strtotime($data['Date'])) . 'T' . date("H:i:s",strtotime($data['Time_slot'])),
                'timeZone' => "Asia/Kolkata"
            ],
            'colorId' => '2',
            'description' => 'Event created by Heal',
            'summary' => 'Appointment with ' . $data['Therapist_Name']
        ];
        // print_r(json_encode($bodyData)); exit();

            try{
                $response = $client->request('POST', 'https://www.googleapis.com/calendar/v3/calendars/cr7sumitmishra@gmail.com/events', [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $accessToken,
                    ],
                    'json' => $bodyData
                ]);
                if($response->getStatusCode() == 200){
                    $data['msg'] = 'added to google calendar successfully!';
                }
                else{
                    $data['msg'] = 'Couldn\'t add appointment to Google calendar';
                }
            }
            catch(\Exception $e){
                if(strpos($e->getMessage(), '401') !== false && $retryCount < 2){
                    $data['msg'] = 'access token expired! Getting new access token'; 
                    //Update the access token using refresh token
                    $this->updateAccessToken() ;
                    //Retry the event creation operation
                    $this->createCalendarEvent($data, $retryCount + 1);
                }
                else{
                    $data['msg'] = $e->getMessage() . ' Inside createCalendarEvent()';
                }
            }
            return $data;
    }
}