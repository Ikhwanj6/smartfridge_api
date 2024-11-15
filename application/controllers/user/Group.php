<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Group extends RestController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('user/Group_model');
  }


    public function group_get($id = 0) {

        // Fetching all groups if no specific ID is provided
        if (empty($id)) {
            $data = $this->Group_model->get_group_all();

            if ($data) {
                $this->response($data, 200);
            } else {
                $this->response(['error' => 'No data found'], 404);
            }
        } 
        // Fetching a specific group based on ID
        else {
            $data = $this->Group_model->get_specific_group($id);

            if ($data) {
                $this->response($data, 200);
            } else {
                $this->response(['error' => 'No data found'], 404);
            }
        }
    }


    public function create_group_post(){
        $body = $this->post();

        $group = [
            'user_group_name' => $body['user_group_name'],
            'user_group_description' => $body['user_group_description'],
            'updated_by' =>  $body['updated_by'],
            'created_by' =>  $body['created_by'],
            'status_id' =>  $body['status_id'],
        ];


        if(!empty($group['user_group_name'])){
            $created_group =  $this->Group_model->insert_usergroup($group);

            if($created_group){
                 $this->response(['status' => 'success', 'message' => 'Group created successfully.'], 200);
            }else{
                 $this->response(['status' => 'error', 'message' => 'Failed to create group.'], 500);
            }

        }else{
              $this->response(['status' => 'error', 'message' => 'Invalid or missing data.'], 400);
        }

    }  


    public function update_group_post(){
        $body = $this->post();
        
        $group = [
            'user_group_id' => $body['user_group_id'],
            'user_group_name' => $body['user_group_name'],
            'user_group_description' => $body['user_group_description'],  
            'updated_by' =>  $body['updated_by'],    
        ];

        if(!empty($group['user_group_name'])){

            $update_group = $this->Group_model->update_usergroup($group);

            if($update_group){
                $this->response(['status' => 'success', 'message' => 'Group updated successfully'], 200);
            }else{
                $this->response(['status' => 'error', 'message' => 'Failed to update'],500);
            }
        }else{
            $this->response(['status' => 'error', 'message' => 'Data not exist'], 400);
        }
    }

    public function group_delete($id) {
        if (is_null($id) || $id == '') {
            $this->response(['status' => 'error', 'message' => 'Group ID is required'], 400);
            return;
        }

        $delete_group = $this->Group_model->delete_selected_group($id);


        if ($delete_group) {
            $this->response(['status' => 'success', 'message' => 'Group deleted successfully'], 200);
        } else {
            $this->response(['status' => 'failed', 'message' => 'Group delete failed or group does not exist'], 500);
        }
    }

  
}
