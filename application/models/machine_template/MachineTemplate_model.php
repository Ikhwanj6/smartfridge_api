<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MachineTemplate_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
     $this->db = $this->load->database('smartfridge', TRUE);
  }

    public function get_template($userid, $get)
  {	

	$this->db->where('user_id', $userid);
	$user_row = $this->db->get('user')->row();

	if($user_row->user_role_id != 1 ){
    	$this->db->where('machine_template.created_by', $userid);
	}
    $this->db->where('machine_template.status_id', $get);
    $this->db->where_in('machine_template.status_id', [1,2]);
    $query = $this->db->get('machine_template');
    return $query->row();
  }

  public function get_selected_template($id){
    $this->db->join('manufacturer', 'manufacturer.manufacturer_id = machine_template.manufacturer_id');
    $this->db->join('machine_model', 'machine_model.machine_model_id = machine_template.machine_model_id');
    $this->db->join('controller_board_variation', 'controller_board_variation.controller_board_variation_id = machine_template.controller_board_variation_id');    
    $this->db->where('machine_template.machine_template_id', $id);
    $this->db->where_in('machine_template.status_id', [1,2]);
    $query = $this->db->get('machine_template');

    return $query->row();
  }


  public function get_all_template(){
    $this->db->join('manufacturer', 'manufacturer.manufacturer_id = machine_template.manufacturer_id');
    $this->db->join('machine_model', 'machine_model.machine_model_id = machine_template.machine_model_id');
    $this->db->join('controller_board_variation', 'controller_board_variation.controller_board_variation_id = machine_template.controller_board_variation_id');
    $query = $this->db->get('machine_template');

    return $query->result();
  }

  public function get_template_planogram($template_id){
	$this->db->where('machine_template_id', $template_id);
	$query = $this->db->get('machine_template_planogram');

	return $query->result();
  }

  public function get_template_payment($template_id){
	$this->db->where('machine_template_id', $template_id);
	$query = $this->db->get('machine_template_payment');

	return $query->result();
  }
  
  public function get_gkash_credential_template($template_id){
	 $this->db->where('machine_template_id', $template_id);
	 $query = $this->db->get('payment_gateway_gkash_credential_template');

	 return $query->row();
  }

  public function get_rms_credential_template($template_id){
	 $this->db->where('machine_template_id', $template_id);
	 $query = $this->db->get('payment_gateway_rms_credential_template');

	 return $query->row();	
  }

  public function get_controllerById($id){
	$this->db->where('controller_board_variation_id', $id);
	$this->db->join('controller_board', 'controller_board.controller_board_id = controller_board_variation.controller_board_id');
	$query = $this->db->get('controller_board_variation');

	return $query->row();
}  
  
}