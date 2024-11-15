<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Machine_refill extends RestController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('machine_refill/MachineRefill_model');
  }

      private function validate_headers($required_headers)
    {
        // Get all request headers
        $request_headers = $this->input->request_headers();

        // Check if all required headers are present
        foreach ($required_headers as $header) {
            if (!isset($request_headers[$header]) || empty($request_headers[$header])) {
                $this->response([
                    'status' => false,
                    'response' => 'Missing or empty value for header: ' . $header,
                ], RestController::HTTP_BAD_REQUEST);
            }
        }

        return true;
    }


    public function refillinfo_get(){
        $header = $this->get();
        $machine_id = $header['machine_id'];

        if(isset($machine_id) && !empty($machine_id) && is_numeric($machine_id)) {
            $machine_id = $header['machine_id'];

            $refill = $this->MachineRefill_model->get_refillrecord($machine_id);
            $inventory = $this->MachineRefill_model->inventory($machine_id);

            if($refill  || $inventory) {
                $response = [
                    'refill' => $refill,
                    'inventory' => $inventory,
                    'status' => true,
                    'message' => 'Refill info retrieved successfully',
                ];
                $this->response($response, 200);
            } else {
                $response = [
                    'status' => false,
                    'message' => 'No refill or inventory data found for the specified machine ID',
                ];
                $this->response($response, 404);
            }
        } else {
            $response = [
                'status' => false,
                'message' => 'Invalid or missing machine ID',
            ];
            $this->response($response, 400);
        }
    }
    

    public function get_machine_refill_threshold_get($id){

        if($id != '' && $id != 0){

            $threshold = $this->MachineRefill_model->get_machinethreshold($id);


            if(!empty( $threshold)){
                $response = [
                    'status' => true,
                    'message' => 'Machine refill threshold acquired.',
                    'threshold' => $threshold,
                ];

                $this->response($response, 200);
            }else{
                $response = [
                    'status' => false,
                    'message' => 'No machine threshold found.',
                    'threshold' => [],
                ];

                $this->response($response, 400);
            }


        }else{
            $response = [
                'status' => false,
                'message' => 'Invalid or missing machine ID',
            ];

            $this->response($response, 404);
        }
    }


    public function update_machine_threshold_post(){

        $body = $this->post();

        $data = [
            'machine_id' => $body['machine_id'],
            'refill_threshold_value' => $body['refill_threshold_value']
        ];

        
        $this->form_validation->set_rules('machine_id', 'Machine ID', 'required');
        $this->form_validation->set_rules('refill_threshold_value', 'Machine Refill Threshold', 'required');

        if($this->form_validation->run() == true){

            $threshold = $this->MachineRefill_model-> update_machine_refill_threshold($data);


            if($threshold){
                $response = [
                    'status' => true,
                    'message' => 'Machine refill threshold updated.',
                ];

                $this->response($response, 200);
            }else{
                $response = [
                    'status' => false,
                    'message' => 'Failed to update machine refill threshold.',
                ];

                $this->response($response, 400);
            }
        }else{
            $response = [
                 'status' => false,
                 'message' => $this->validation_errors(),
            ];

            $this->response($response, 400);
        }
    }


    public function update_post()
{
    // Validate headers
    $this->validate_headers(['user_id', 'machine_id']);
    $get = [
        'user_id' => $this->input->request_headers()['user_id'],
        'machine_id' => $this->input->request_headers()['machine_id']
    ];

    // Validate JSON data
    $json_data = $this->input->post("inventory");
    if (!$json_data) {
        return $this->response([
            'status' => false,
            'response' => "Missing JSON data named 'inventory'"
        ], RestController::HTTP_NOT_FOUND);
    }

    // Decode data and validate machine ID
    $data = json_decode($json_data);
    if ($data->main_machine->refill_required[0]->machine_id != $get['machine_id']) {
        return $this->response([
            'status' => false,
            'response' => 'Machine ID does not match!'
        ], RestController::HTTP_BAD_REQUEST);
    }

    // Update main machine inventory
    $total_updates = $this->main($get, $data->main_machine->refill_required);
    if ($total_updates == 0) {
        return $this->response([
            'status' => false,
            'response' => 'No changes detected!'
        ], RestController::HTTP_BAD_REQUEST);
    }

    return $this->response([
        'status' => true,
        'response' => 'Inventory updated',
        'main' => "$total_updates inventory updated!"
    ], RestController::HTTP_OK);
}

public function main($get, $inventory_main)
{
    $updated_datetime = date("Y-m-d H:i:s");
    $total_qty = 0;

    // Calculate total quantity for refill
    foreach ($inventory_main as $row) {
        $total_qty += $row->current_qty;
    }

    // Update inventory records in database
    if (!$this->MachineRefill_model->updateMainInventory($get['machine_id'], $total_qty, $updated_datetime)) {
        return 0;
    }

    // Insert into refill table and obtain refill ID
    $get['refill_id'] = $this->MachineRefill_model->insertRefillMain($get);
    if (!$get['refill_id']) return 0;

    // Insert refill record with overall inventory data
    $refill_record = [
        'refill_id' => $get['refill_id'],
        'total_qty' => $total_qty,
        'updated_by' => $get['user_id'],
        'updated_datetime' => $updated_datetime
    ];

    // Finalize refill record and total item update
    return $this->MachineRefill_model->insertRefillRecordMain($refill_record) ? 1 : 0;
}


}