<?php
/**
 * 
 * This controller is used for all navigational and miscellaneous operations
 * 
 * @author sumit mishra cr7sumitmishra@gmail.com
 * @version 1.0
 * 
 */
namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use Config\Services;
use CodeIgniter\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Controllers\FMDBOps;


class Home extends BaseController
{
    use ResponseTrait; 
    
    
  
    public function index(): string
    {
        return view('welcome_message');
    }

    //Heal Landing page controller
    public function heal()
    {
        return view('Heal');
    }

    public function doctor_view()
    {   
        //create fmdbops object
        $fmobj = new FMDBOps();
        // print_r($fmobj->fmGetTherapistData()); exit();
        return view('DoctorView\DoctorsListView', $fmobj->fmGetTherapistData());
    }

    public function appointment_list()
    {
        $fmobj = new FMDBOps();
        return view('Appointments\AppointmentList', $fmobj->fmGetAptData());
    }

    public function appointment_cal()
    {
        $fmobj = new FMDBOps();
        return view('Appointments\AppointmentCal', $fmobj->fmGetAptData());
    }

    public function doctor_detail()
    {
        return view('DoctorView\DoctorDetail');
    }

    public function apt_book()
    {
        return view('Appointments\AptBooking');
    }
}
