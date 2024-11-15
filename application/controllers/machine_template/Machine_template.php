<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Machine_template extends RestController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('machine_template/MachineTemplate_model');
  }

    public function machineTemplate_get($id = 0){
        if($id != '' && $id != 0 ){

            $template_data = $this->MachineTemplate_model->get_selected_template($id);

            if(!empty($template_data)){
                $planogram = $this->MachineTemplate_model->get_template_planogram($id);
                $payment = $this->MachineTemplate_model->get_template_payment($id); 
                $gkash_cred = $this->MachineTemplate_model->get_gkash_credential_template($id);
                $rms_cred = $this->MachineTemplate_model->get_rms_credential_template($id);


                $result = [
                    'machine_template' => $template_data,
                    'planogram' =>  $planogram,
                    'payment' => $payment,
                    'gkash_cred' => $gkash_cred,
                    'rms_cred' => $rms_cred,     
                ];
                
                $this->response($result, 200);

            } else {
                $this->response(['message' => 'data invalid'], 500);
            }

        } else {
            $all_template = $this->MachineTemplate_model->get_all_template();
            $this->response($all_template, 200);
        }
    }


}