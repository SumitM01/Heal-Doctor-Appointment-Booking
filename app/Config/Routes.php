/**
 * 
 * Route file: used to route a URL to the specified function inside a controller 
 * @author sumit mishra cr7sumitmishra@gmail.com
 * @version 1.0
 * 
 */
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/heal','Home::heal', ['filter'=> 'noauth']);
$routes->match(['get', 'post'], '/user-login','Auth::user_login', ['filter'=> 'noauth']);
$routes->match(['get', 'post'], '/user-signup','Auth::user_signup', ['filter'=> 'noauth']);
$routes->get('/doctors_list','Home::doctor_view', ['filter'=> 'auth']);
$routes->get('/doctor-details','Home::doctor_detail', ['filter'=> 'auth']);
$routes->match(['get','post'], '/apt-book','Appointment::create', ['filter'=> 'auth']);
$routes->get('/user-logout','Auth::user_logout');
$routes->post('/getapttimes', 'Appointment::fetchAppointmentsByUserIdDate');
$routes->post('/apt-create', 'Appointment::create');
$routes->match(['get', 'post'], '/apt-update', 'Appointment::update', ['filter'=> 'auth']);
$routes->get('/apt-delete', 'Appointment::delete');
$routes->get('/fmvalidate', 'FMdbops::fmValidateSession');
$routes->get('/fminsert', 'FMdbops::fmInsertData');
$routes->get('/fmgetthr','FMdbops::fmGetTherapistData');
$routes->get('/fmgetapt','FMdbops::fmGetAptData');
$routes->get('/appointments','Home::appointment_list', ['filter'=> 'auth']);
$routes->get('/appointments-cal','Home::appointment_cal', ['filter'=> 'auth']);
// $routes->post('/home/user_authenticate_login', 'Auth::user_authenticate_login');
// $routes->post('/home/user_authenticate_signup', 'Auth::user_authenticate_signup');

//Google calendar
$routes->get('/googleauth', 'GoogleCalendar::googleAuth');
$routes->get('/getGoogleAccessToken','GoogleCalendar::getTokens');