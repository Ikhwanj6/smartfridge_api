<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Authorize extends RestController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('authorize/Authorize_model');
  }


  function fetch_authorize_get(){
        $get['user_role_id'] = $this->input->request_headers()['user_role_id'];

        if(empty($get['user_role_id'])){
			$this->response([
				'status' => false,
				'response' => 'Empty Header!',
			], RestController::HTTP_BAD_REQUEST);
		}

        $authorize_module = $this->Authorize_model->get_authorize_module( $get['user_role_id']);

        if(!empty($authorize_module)){
            $sub_authorize = $this->Authorize_model->get_subauthorize_module( $get['user_role_id']);

            $this->response([
                'status' => true,
                'message' => 'success',
                'authorize' => $authorize_module,
                'sub_authorize' => $sub_authorize,

            ],RestController::HTTP_OK);
        }else{
            $this->response([
                'status' => false,
                'response' => 'No authorize module found!',
                ],RestController::HTTP_NOT_FOUND);
            
        }
  }
}