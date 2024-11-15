<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Payment extends RestController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('payment/Payment_model');
    $this->load->model('machine/MachineListing_model');
  }

  public function payment_get($id = 0){
    
    if(empty($id)){

        $payment_data = $this->Payment_model->get_all_payment();

        $this->response($payment_data, 200);

    }else{
        $payment_data = $this->Payment_model->get_selected_payment($id);


        $machine = $this->Payment_model->identifier_to_machine_id($id);
        $planogram = $this->Payment_model->get_machine_planogram_data($machine->machine_id);

        if(!empty($payment_data)){
            $this->response(['payment' => $payment_data, 'machine' => $planogram], 200);
        }else{
            $this->response(['status' => 'error', 'message' => 'machine identifier not found or existed'], 404);
        }
    }
  }

  public function machine_payment_method_get($machine_id = 0){

    if($machine_id != 0 && $machine_id != ''){
      $payment =  $this->Payment_model->get_machinepayment($machine_id);

      if(!empty($payment)){

        $response = [
          'status'=> true,
          'message' => 'machine payment method found',
          'data' => $payment,
        ];

        $this->response($response, 200);

      }else{
        $response = [
          'status'=> false,
          'message' => 'machine payment method not found',         
        ];

        $this->response($response, 404);
      }
    }else{
      $response = [
        'status'=> false,
        'message' => 'machine id not found',
      ];

      $this->response($response, 400);
    }
  }

public function change_payment_method_status_post() {
    $body = $this->post();

    $data = [
        'machine_id' => $body['machine_id'],
        'payment_option_id' => $body['payment_option_id'],
        'status_id' => $body['status_id'],
    ];

    // Validate inputs
    $this->form_validation->set_rules('machine_id', 'MachineID', 'required');
    $this->form_validation->set_rules('payment_option_id', 'PaymentOptionID', 'required');
    $this->form_validation->set_rules('status_id', 'StatusID', 'required');

    // If validation passes
    if ($this->form_validation->run() == true) {
        // Toggle the status
        $toggle_status = ((int)$data['status_id'] == 1) ? 2 : 1;

        $updated_data = [
            'machine_id' => $data['machine_id'],
            'status_id' => $toggle_status,
            'payment_option_id' => $data['payment_option_id']
        ];

        // Update the status using the model function
        $update_status = $this->Payment_model->change_payment_method_status($updated_data);

        if ($update_status) {
            $response = [
                'status' => true,
                'message' => 'Payment method status updated.',
            ];
            $this->response($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => 'Payment method status failed to update.',
            ];
            $this->response($response, 400);
        }

    } else {
        // Validation failed
        $response = [
            'status' => false,
            'message' => validation_errors(),
        ];
        $this->response($response, 400);
    }
}

}