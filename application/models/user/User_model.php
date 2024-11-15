<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
     $this->db = $this->load->database('smartfridge', TRUE);
  }

  public function get_selected_user($id){
    $this->db->select('user.user_id, user.user_name, address.pin_name, address.full_address, address.city, contact.contact_phone, contact.contact_email, user_credential.user_credential_id
    , user_credential.username, user_role.user_role_name');
    $this->db->join('address', 'address.address_id = user.address_id');
    $this->db->join('contact', 'contact.contact_id = user.contact_id');
    $this->db->join('user_credential', 'user_credential.user_id = user.user_id');
    $this->db->join('user_role', 'user_role.user_role_id = user.user_role_id');
    $this->db->where('user_credential.user_id', $id);
    $this->db->where('user.status_id', 1);
    $query = $this->db->get('user');

    return $query->row();
  }



  public function get_all_users(){
    $this->db->where('status_id', 1);
    $query = $this->db->get('user'); 
    
    return $query->result();
  }



}