<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Machine_listing extends RestController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('machine/MachineListing_model');
    $this->load->model('machine_refill/MachineRefill_model');
  }


  public function machine_get($id = 0){

    $header = $this->get();

      // Check if the ID is provided or not
      if ($id == 0 || empty($id)) {
          $machine = $this->MachineListing_model->getFullMachineData($header['user']);

          if (!empty($machine)) {

              $this->response([
                  'status' => true,
                  'message' => 'All machines retrieved successfully',
                  'machines' => $machine
              ], 200);
          } else {

              $this->response([
                  'status' => false,  
                  'error' => 'No machines found'
              ], 404);
          }
      } else {
          $machine_row = $this->MachineListing_model->getMachineById($id);
          $gkash = $this->MachineListing_model->getActiveGkashCredential($id);
          $rms = $this->MachineListing_model->getActiveRmsCredential($id);
          $machine_payment = $this->MachineListing_model->get_machinepayment($id);
          $machine_specs = $this->MachineListing_model->get_machinespecs($id);

          if (!empty($machine_row)) {

              $this->response([
                  'status' => true,
                  'message' => 'Machine retrieved successfully',
                  'machine' => $machine_row,
                  'specs' => $machine_specs,
                  'gkash_credential' => $gkash,
                  'rms_credential' => $rms,
                  'payment_option' => $machine_payment,
              ], 200);

          } else {
              $this->response([
                  'status' => false,
                  'error' => 'Machine ID not found'
              ], 404);
          }
      }
}

    public function search_machine_by_identifier_get() {
        $header = $this->get();
        $machine_identifier = $header['machine_identifier'];

        $machine_data = $this->MachineListing_model->searchByMachineIdentifier($machine_identifier);

        if ($machine_data) {
            // Return the data as JSON if found
            $response = array(
                'status' => true,
                'message' => 'Machines found',
                'machines' => $machine_data
            );

            $this->response($response, 200);

            // Return an error message if no machine found
            $response = array(
                'status' => false,
                'message' => 'No machines found for the provided identifier',
                'machines' => []
            );

            $this->response($response, 400);

        }
    }


  
public function machine_door_status_post()
  {
    $body = $this->post();

      $door_status = [
          'machine_id' => $body['machine_id'],
          'machine_door_status_id' => $body['machine_door_status_id'],
          'updated_by' => $body['updated_by'],
      ];

      $this->form_validation->set_rules('machine_id');
      $this->form_validation->set_rules('machine_door_status_id');


      if($this->form_validation->run() == true){
          $updated = $this->MachineListing_model->updateMachineDoorStatus($door_status);

          if ($updated) {
              $this->response(['status' => 'success', 'message' => 'Machine door status updated successfully.'], 200);
          } else {
              $this->response(['status' => 'error', 'message' => 'Failed to update machine door status.'], 500);
          }
      }else{
        $this->response(['error' =>  validation_errors()], 400);
      }

  }

  public function machine_temps_log_get($id = 0)
{
    if($id != ''){
      $selected_log = $this->MachineListing_model->get_machinetemps_log($id);

      if(!empty($selected_log)){
        $response = [
          'machine_temps_log' => $selected_log,
          'status' => true,
        ];

        $this->response($response, 200);
      }
    }else{

        $response = [
          'status' => true,
          'message' => 'No machine id.'
        ];      

      $this->response($response, 400);

    }
}

public function machine_planogram_get($id = 0){
    $machine_id = $id;
    
    if($machine_id != ''){
        $machine_planogram = $this->MachineListing_model->get_machineplanogram($machine_id);

        if(!empty($machine_planogram)){
          $response = [
            'machine_planogram' => $machine_planogram,
            'status' => true,    
            'message' => 'Product_ownership data acquired.'
          ];

          $this->response($response, 200);
        }else{
          $response = [
            'status' => false,
            'message' => 'No machine planogram found.',
            'machine_planogram' => [],
          ];

          $this->response($response, 404);
        }
    }else{
          $response = [
            'status' => false,
            'message' => 'Machine ID not valid.',
          ];

          $this->response($response, 500);      
    }
}

public function update_machine_planogram_post() {
    $body = $this->post();

    // Validate required fields
    $this->form_validation->set_rules('machine_id', 'Machine ID', 'required');


    if ($this->form_validation->run() === TRUE) {
        // Prepare planogram data
        $planogram = [
            'machine_planogram_id' => $body['machine_planogram_id'],
            'machine_id' => $body['machine_id'],
            'product_id' => $body['product_id'],
            'product_sku' => $body['product_sku'],
            'product_price' => $body['product_price'],
            'product_price_cost' => $body['product_price_cost'],
            'inventory' => $body['inventory'],
            'max_capacity' => $body['max_capacity'],
            'product_threshold_value' => $body['product_threshold_value'],  
            'updated_by' => $body['user_id'],
        ];

        $default_threshold = $body['product_threshold_default'];    
        // $user = $body['user_id'];

        // Update planogram
        $update = $this->MachineListing_model->update_machine_planogram($planogram,);
        $threshold = $this->MachineRefill_model->get_machinethreshold($planogram['machine_id'], $default_threshold, $planogram['product_threshold_value']);
    
        if ($update) {
             $this->update_threshold($planogram, $default_threshold, $threshold,);

            if (!empty($threshold)) {
                // Update threshold value
                $this->update_threshold($planogram, $default_threshold, $threshold,);
            }

            // Return success response
            $response = [
                'status' => true,
                'message' => 'Machine planogram updated successfully.',
            ];
            $this->response($response, 200);
        } else {

            // Return failure response
            $response = [
                'status' => false,
                'message' => 'Failed to update machine planogram.',
            ];
            $this->response($response, 400);
        }
    } else {
        // Validation error response
        $response = [
            'status' => false,
            'message' => $this->form_validation->error_string(),
        ];
        $this->response($response, 400);
    }
}

// private function update_threshold($planogram, $default_threshold, $threshold_value) {
//     // Case 1: No change in product threshold
//     // if ((int)$default_threshold == 0) {
//     //     $total = $threshold_value + $planogram['product_threshold_value'];

//     //     $threshold_data = [
//     //         'machine_id' => $planogram['machine_id'],
//     //         'refill_threshold_value' => $total,
//     //     ];
//     //     // Update threshold
//     //     $this->MachineRefill_model->update_machine_refill_threshold($threshold_data);
//     // } 
//     // else
//     if((int)$default_threshold > 0)
//     {
//         // Case 2: Update threshold with a new value
//         $total = $threshold_value - $default_threshold;
//         $updated_threshold = $total + $planogram['product_threshold_value'];

//         $threshold_data = [
//             'machine_id' => $planogram['machine_id'],
//             'refill_threshold_value' => $updated_threshold,
//         ];

//         // Update threshold with new value
//         $this->MachineRefill_model->update_machine_refill_threshold($threshold_data);
//     }
// }


private function update_threshold($planogram, $default_threshold, $threshold_value) {
    if ((int)$default_threshold > 0) {
        $total = $threshold_value - $default_threshold;
        $updated_threshold = $total + $planogram['product_threshold_value'];

        $threshold_data = [
            'machine_id' => $planogram['machine_id'],
            'refill_threshold_value' => $updated_threshold,
        ];

        // Log threshold update data
        log_message('debug', 'Threshold Update Data: ' . print_r($threshold_data, true));

        // Update threshold with new value and log result
        $result = $this->MachineRefill_model->update_machine_refill_threshold($threshold_data);
        if (!$result) {
            log_message('error', 'Failed to update refill threshold');
        }
    }
}






public function add_machine_planogram_post() {
    $body = $this->post();

    // Prepare the data array
    $data = [
        'machine_id' => $body['machine_id'],
        'product_id' => $body['product_id'],
        'product_sku' => $body['product_sku'],
        'product_price' => $body['product_price'],
        'product_price_cost' => $body['product_price_cost'],
        'inventory' => $body['inventory'],
        'max_capacity' => $body['max_capacity'],
        'product_threshold_value' => $body['product_threshold_value'],
        'updated_by' => $body['updated_by'],
        'created_by' => $body['created_by'],
    ];

    // Validation rules
    $this->form_validation->set_rules('machine_id', 'Machine ID', 'required');
    $this->form_validation->set_rules('product_id', 'Product ID', 'required');
    // Add validation rules for other fields if necessary

    // Check validation
    if ($this->form_validation->run() == true) {
        // Call model method to add ownership
        $add_ownership = $this->MachineListing_model->add_product_ownership($data);

        if ($add_ownership) {
            // Success response
            $response = [
                'status' => true,
                'message' => 'Product ownership added successfully.',
            ];
            $this->response($response, 200);
        } else {
            // Database insertion failed
            $response = [
                'status' => false,
                'message' => 'Product ownership addition failed.',
            ];
            $this->response($response, 400);
        }
    } else {
        // Validation failed
        $response = [
            'status' => false,
            'message' => $this->form_validation->error_array(), // Updated for correct error reporting
        ];
        $this->response($response, 400);
    }
}


public function machine_status_get($id = 0){
  if($id != '' && $id != 0){
    $machine_status = $this->MachineListing_model-> get_machinestatus($id);

    if(!empty($machine_status)){
        $response = [
          'machine_status' => $machine_status,
          'status' => true,
          'message' => 'machine_status acquired',
        ];

        $this->response($response, 200);
    }else{
        $response = [
          'status' => false,
          'message' => 'machine status not found',
        ];      

        $this->response($response, 404);
    }
  }else{
         $response = [
          'status' => false,
          'message' => 'id is not valid',
        ];      

        $this->response($response, 404);   
  }
}
}