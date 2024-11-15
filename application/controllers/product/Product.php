<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Product extends RestController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('product/Product_model');
  }

  public function fetch_allproduct_get(){
    $product = $this->Product_model->get_allproduct();

    if(!empty($product)){

        $response = [
            'status' => true,
            'message' => 'Product fetch successfully',
            'product' => $product
        ];

        $this->response($response, 200);

    }else{
        $response = [
            'status' => false,
            'message' => 'Product fetch failed',
            'product' => []           
        ];

        $this->response($response, 400);
    }
  }

}