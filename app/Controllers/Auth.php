<?php
/**
 * 
 * This controller handles user login, logout and signup related operations
 * 
 * @author sumit mishra cr7sumitmishra@gmail.com
 * @version 1.0
 * 
 */
namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Models\UsersModel;

class Auth extends BaseController
{
    public function index()
    {
        //
    }

    // Authenticate the entered the user data with the filemaker db and authenticate the user accordingly
    public function user_login()
    {
        if($this->request->getMethod() == 'get'){
            return view('user_login');
        }
        
        else if($this->request->getMethod()=='post')
        {

            $data = $this->request->getPost();
            $email = $data['email'];
            $password = $data['password'];

            $session = session();
            $userModel = new UsersModel();

            $dbdata = $userModel->getUserByEmail($email);

            if($dbdata)
            {
                $dbpass = $dbdata['Password'];
                $authenticatePassword = password_verify($password, $dbpass);
                if($authenticatePassword){
                    $ses_data = [
                        'id' => $dbdata['User_ID'],
                        'name' => $dbdata['Name'],
                        'email' => $dbdata['Email'],
                        'isLoggedIn' => TRUE
                    ];
                    $session->set($ses_data);
                    return redirect()->to('/doctors_list');
                }
                else{
                    $user['passwordincorrect'] = 'Password is incorrect!';
                }
            }
            else{
                $user['emailinvalid'] = 'User not found!';
            }
            return view('user_login', $user);
        }
        
    }

    // Sign up user process for the user
    public function user_signup()
    {
        if($this->request->getMethod() == 'get'){
            return view('user_signup');
        }
        
        else if($this->request->getMethod()=='post'){
            helper('form');
            $rules = [
                'fullname' => 'required|min_length[6]|max_length[50]',
                'email' => 'required|min_length[6]|max_length[50]|valid_email',
                'password' => 'required|regex_match[^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})]',
                'conf_password' => 'matches[password]',
            ];
            $data = $this->request->getPost(array_keys($rules));
            if(!$this->validateData($data, $rules)){
                $data['validation'] = $this->validator;
                return view('user_signup', $data);
            }
            else{
                
                $model = new UsersModel();
                if($model->getUserByEmail($data['email'])){
                    $data['error'] = 'User with this email already exists.';
                }
                else{
                    $newUser = [
                        'Name' => $data['fullname'],
                        'Email'    => $data['email'],
                        'Password' => crypt($data['password'], PASSWORD_DEFAULT)
                    ];
    
                    if ($model->createUser($newUser)) {
                        // User created successfully
                        // Redirect to login page or any other page
                        return redirect()->to(site_url('user-login'))->with('success', 'User created successfully. You can now login.');
                    } else {
                        // Failed to create user
                        $data['error'] = 'Failed to create user.';
                    }
                }
                
                return view('user_signup', $data);
                // return view('user_signup', $data);
            }
        }
        else{
            return view('user_signup');
        }
    }
    
    public function user_logout()
    {
        // Unset all user session data
        session()->destroy();

        // Redirect to login page
        return redirect()->to(site_url('user-login'))->with('success', 'You have been successfully logged out.');
    }
}