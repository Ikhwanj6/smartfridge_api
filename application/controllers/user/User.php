<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class User extends RestController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('user/User_model');
  }

  public function user_get($id){

    if($id != '' && $id != 0){
        $data = $this->User_model->get_selected_user($id);

        if(!empty($data)){
            $response = [
                'status' => true,
                'message' => 'User found',
                'user' => $data,
            ];

            $this->response($response, 200);
        }else{
            $response = [
                'status' => false,
                'message' => 'User not found',
            ];

            $this->response($response, 404);
        }
    }else{
        $response = [
            'status' => false,
            'message' => 'Invalid user ID',
        ];

        $this->response($response, 400);
    }
  }



}
