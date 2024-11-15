<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
     $this->db = $this->load->database('smartfridge', TRUE);
  }

  public function get_all_payment(){
      $this->db->select("*"); 
      $this->db->select("sale.payment_datetime as datetimefromsale"); 
      $this->db->join('payment', "payment.transaction_id = sale.transaction_id");
      $this->db->join('machine', "machine.machine_identifier = sale.machine_identifier");
    //   $this->db->join('machine_permission', "machine_permission.user_id = user.user_id"); 
      $query = $this->db->get('sale');

      return $query->result();

  }

  public function get_selected_payment($id){
      $this->db->select("*"); 
      $this->db->select("sale.payment_datetime as datetimefromsale"); 
      $this->db->join('payment', "payment.transaction_id = sale.transaction_id");
      $this->db->join('machine', "machine.machine_identifier = sale.machine_identifier");
    //   $this->db->join('machine_permission', "machine_permission.user_id = user.user_id"); 
      $this->db->where('sale.machine_identifier', $id);
      $query = $this->db->get('sale');

      return $query->result();
  }

  public function get_machine_planogram_data($id){
    $this->db->where('machine_id', $id);
    $query = $this->db->get('machine_planogram');

    return $query->result();

  }

  public function identifier_to_machine_id($id){
     $this->db->where('machine_identifier', $id);
     $query = $this->db->get('machine');

     return $query->row();
  }

    public function get_machinepayment($data)
	{
    $this->db->select('machine_payment.machine_id, machine_payment.payment_option_id, machine_payment.status_id, payment_option.payment_gateway_id,
    payment_option.payment_option_name, payment_option.payment_option_code, payment_option.payment_type_id, payment_option.payment_option_image_url, 
    payment_option.payment_option_image_url,  payment_option.payment_option_image_name, payment_gateway.payment_gateway_name');
    $this->db->where('machine_id',$data);
    $this->db->where_in('machine_payment.status_id', [1,2]);
    $this->db->join('payment_option', 'payment_option.payment_option_id = machine_payment.payment_option_id');
    $this->db->join('payment_gateway', 'payment_gateway.payment_gateway_id = payment_option.payment_gateway_id');
    $query = $this->db->get('machine_payment');
    return $query->result();
	}  

  public function change_payment_method_status($data) {
      $this->db->where('machine_payment.machine_id', $data['machine_id']);
      $this->db->where('machine_payment.payment_option_id', $data['payment_option_id']);
      $this->db->set('machine_payment.status_id', $data['status_id']);
      $this->db->update('machine_payment');


      return ($this->db->affected_rows() > 0);
  }

}