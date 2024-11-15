<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Sale extends RestController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('sales/Sales_model');
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


    public function total_sales_get($id = 0)
    {

        $header = $this->get();

   
        if ($id != 0 && $id != '') {

            
            $data = $this->Sales_model->total_sales($id, $header['user_id']);
            $item = $this->Sales_model->total_itemcount($id, $header['user_id']);

            if (!empty($data)) {
                
                $response = [
                    'status' => true,
                    'message' => 'Selected machine sales acquired.',
                    'data' => $data,
                    'item_count' => $item,
                ];

                $this->response($response, 200);
            } else {

                $this->response(['status' => false, 'message' => 'Data invalid'], 400);
            }
        } else {

            $data = $this->Sales_model->total_sales($id, $header['user_id']); 
            $item = $this->Sales_model->total_itemcount($id, $header['user_id']);

            if (!empty($data)) {
                $response = [
                    'status' => true,
                    'message' => 'All sales acquired.',
                    'data' => $data,
                    'item_count' => $item,
                ];

                $this->response($response, 200);
            } else {

                $this->response(['status' => false, 'message' => 'Invalid ID or no data found'], 400);
            }
        }
    }

    public function filter_sale_post(){
        $body = $this->post();

        $filter_data = [
            'machine' => $body['machine'],
            'filter_date' => $body['filter_date'],
            'start_date' => $body['start_date'],
            'end_date' => $body['end_date'],
        ];

        $this->form_validation->set_rules('machine', 'Machine Filter' , 'required');
        $this->form_validation->set_rules('filter_date' , 'Filter Date', 'required');

        if($this->form_validation->run() ==  true){

            if(!empty($filter_data)){
                $getTotalDay = $this->Sales_model->gettotalbyday($filter_data, $filter_data['start_date'], $filter_data['end_date']);
                $getTotalTime = $this->Sales_model->gettotalbytime($filter_data, $filter_data['start_date'], $filter_data['end_date']);
                $totalItemSale = $this->Sales_model->getTotalItemSold($filter_data);
                // $totalSales = $this->Sales_model->total_sale($filter_data);

                $response = [
                    'status' => true,
                    'message' => 'Data found.',
                    'total_sale_day' => $getTotalDay,
                    'total_sale_hour' => $getTotalTime,
                    'total_item_sale' => $totalItemSale,
                    
                ];


                $this->response($response, 200);
            }

        }else{
            $response = [
                'status' => false,
                'message' => $this->validation_errors(),

            ];

            $this->response($response, 400);
        }
    }


    // public function fetch_transactionlist_get($userid){

    //     if($userid != '' && $userid != null){
    //         $data = $this->Sales_model->transaction_List($userid);

    //         $sale = $this->Sales_model->get_bysale();

    //         if(!empty($data)){
    //             $response = [
    //                 'status' => true,
    //                 'message' => 'Data found.',
    //                 'transaction' => $data
    //             ];

    //             $this->response($response, 200);
                
    //         }else{
    //             $response = [
    //                 'status' => false,
    //                 'message' => 'Data not found.',
    //                 'transaction' => []
    //             ];

    //             $this->response($response, 400);
    //         }
    //     }else{
    //             $response = [
    //                 'status' => false,
    //                 'message' => 'Invalid ID',
    //                 'transaction' => []
    //             ];

    //             $this->response($response, 400);
    //     }


    // }


    public function fetch_bytransaction_get(){
        $custid = $this->input->get_request_header('custid', TRUE);   // Customer ID
        $machine = $this->input->get_request_header('machine', TRUE); // Machine identifier
        $filter_date = $this->input->get_request_header('filter_date', TRUE); // Day/Week/Date range
        $start_date = $this->input->get_request_header('start_date', TRUE);   // For custom date range
        $end_date = $this->input->get_request_header('end_date', TRUE);       // For custom date range

        $required_headers = array(
            'machine'
        );

        $this->validate_headers($required_headers);


        // Prepare filter array
        $filters = [
            'filter_date' => $filter_date,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

    
        $data = $this->Sales_model->get_bytransaction($custid, $filters, $machine);

        // Return the result as JSON
        if (!empty($data)) {
            $response = [
                'status' => true,
                'data' => $data,
            ];

            $this->response($response, RestController::HTTP_OK);
        } else {
            $response = [
                'status' => false,
                'message' => 'No sales data found for the given filters.'
            ];

            $this->response($response, RestController::HTTP_BAD_REQUEST);
        }


                
    }

    public function getSalesData_get() {
        // Retrieve inputs from GET request
        $custid = $this->input->get_request_header('cust_id', TRUE);   // Customer ID
        $machine = $this->input->get_request_header('machine', TRUE);  // Machine identifier
        $filter_date = $this->input->get_request_header('filter_date', TRUE); // Day/Week/Date range
        $start_date = $this->input->get_request_header('start_date', TRUE);   // For custom date range
        $end_date = $this->input->get_request_header('end_date', TRUE);   

        $required_headers = array(
            'cust_id',
            'machine',
        );

        $this->validate_headers($required_headers);

        // Prepare filter array
        $filters = [
            'filter_date' => $filter_date,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        // Call the model to get sales data
        $salesData = $this->Sales_model->get_bysale($custid, $filters, $machine);


        // Return the result as JSON
        if (!empty($salesData)) {
            $response = [
                'status' => true,
                'data' => $salesData,
            ];

                $this->response($response, RestController::HTTP_OK);
        } else {
            $response = [
                'status' => false,
                'message' => 'No sales data found for the given filters.'
            ];

            $this->response($response, RestController::HTTP_BAD_REQUEST);
        }

    

        // echo json_encode($response);
    }



}