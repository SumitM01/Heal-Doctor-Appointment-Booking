<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use Config\Services;
use CodeIgniter\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Controllers\FMdbops;


class Home extends BaseController
{
    use ResponseTrait; 
    
    
  
    public function index(): string
    {
        return view('welcome_message');
    }

    //hello world controller
    public function heal()
    {
        return view('heal');
    }

    public function doctor_view()
    {   
        //create fmdbops object
        $fmobj = new FMdbops();
        //call fmdbops get details to get doctor details
        //Process the provided data and display the doctors list to the user
        return view('doctors_list_view', $fmobj->fmGetTherapistData());
    }

    public function appointment_list()
    {
        $fmobj = new FMdbops();
        return view('appointment_list', $fmobj->fmGetAptData());
    }

    public function doctor_detail()
    {
        return view('doctor_detail');
    }

    public function apt_book()
    {
        return view('Apt_booking');
    }
}