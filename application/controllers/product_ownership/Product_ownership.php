<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Product_ownership extends RestController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('product_ownership/ProductOwnership_model');
  }

  public function product_ownership_get($id = 0){

    if($id != 0 && $id != ''){
        $data = $this->ProductOwnership_model->get_product_ownership($id);

        if(!empty($data)){
            $response = [
                'status' => true,
                'message' => 'Product Ownership Data acquired',
                'product_ownership' => $data,
            ];

            $this->response($response, 200);
            
        }else{

            $response = [
                'status' => false,
                'message' => 'Data invalid',
            ];

            $this->response($response, 400);
        }
    }else{
            $response = [
                'status' => false,
                'message' => 'ID invalid',
            ];

            $this->response($response, 400);
    }
  }

}