<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Auth extends RestController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('auth/Auth_model');
  }

public function login_validation_post() {
    $body = $this->post();

    $auth = [
        'username' => $body['username'],
        'password' => $body['password'],
    ];

    // Validate username and password fields
    $this->form_validation->set_rules('username', 'Username', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');

    if ($this->form_validation->run() == true) {

        // Check if the username exists
        $username = $this->Auth_model->get_username($auth);    

        if (empty($username)) {
            // If username is not found, check if the user is inactive
            $active_status = $this->Auth_model->check_inactive_status($auth);

            if (!empty($active_status)) {
                $this->response(['status' => false, 'message' => 'User is inactive'], 404);
            } else {
                $this->response(['status' => false, 'message' => 'User doesn\'t exist'], 400);
            }

        } else {
            // If username exists, check the password
            $password_user = $this->Auth_model->get_password($auth);



            // Check if password_user exists (valid credentials)
            if (!empty($password_user)) {
                // Retrieve machine data for the user
                $machine_user = $this->Auth_model->get_machine_user($password_user->user_id);
                $module = $this->Auth_model->get_page($password_user->user_role_id);

                // Return success response
                $this->response([
                    'user' => $password_user,
                    'machine' => $machine_user,
                    'status' => true,
                    'message' => 'Sign in success'
                ], 200);
            } else {
                // Handle invalid password case
                $this->response(['status' => false, 'message' => 'Invalid password'], 400);
            }
        }

    } else {
        // Validation errors
        $this->response(['status' => false, 'message' => validation_errors()], 400);
    }
}



    public function forgot_password_post() {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == true) {
            $body = $this->post();
            
            $form_data = [
                'username' => $body['username'],
                'password' => $body['password'],
            ];

            $forgot_pass = $this->Auth_model->forgot_password($form_data);
      

            if ($forgot_pass) {
                $this->response(['status' => true, 'message' => 'Password changed successfully'], 200);
            } else {
                $this->response(['status' => false, 'message' => 'Failed to change password'], 400);
            }
        } else {
            $this->response(['status' => false, 'message' => validation_errors()], 400);
        }
    }

    public function change_password_post(){
        $this->form_validation->set_rules('user_id', 'UserID', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

        if($this->form_validation->run() == true){
            $body = $this->post();

            $form_data = [
                'user_id' => $body['user_id'],
                'password' => $body['password'],  
            ];

            $change_pass = $this->Auth_model->change_password($form_data);

            if($change_pass){
                $this->response(['status' => true, 'message' => 'Password changed successfully']);
            }else{
                $this->response(['status' => false, 'message' => 'Password change failed']);
            }
        }else{
            $this->response(['status' => false, 'message' => validation_errors()], 400);
        }
    }


}