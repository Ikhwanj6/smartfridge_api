<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
     $this->db = $this->load->database('smartfridge', TRUE);
  }


  public function get_username($get){
    $this->db->where('username', $get['username']);
    $this->db->where('status_id', 1);
    $query = $this->db->get('user_credential');

    return $query->row();
  }

  public function check_inactive_status($get){
    $this->db->where('username', $get['username']);
    $this->db->where('status_id', 2);
    $query = $this->db->get('user_credential');

    return $query->row();    
  }

  public function get_password($get){
    $this->db->join('user', 'user.user_id = user_credential.user_id');
    $this->db->join('user_role', 'user_role.user_role_id = user.user_role_id', 'left'); 
    $this->db->where('username',  $get['username']);
    $this->db->where('password', $get['password']);
    $this->db->where('user_credential.status_id', 1);
    $query = $this->db->get('user_credential');

    return $query->row();    

  }

  public function get_machine_user($get){
    $this->db->select('machine.machine_id, machine.machine_identifier');
    $this->db->where('user_id', $get);
    $this->db->where('status_id', 1);
    $query = $this->db->get('machine');

    return $query->result_array();
  }

    public function get_page($data){
        $this->db->select('module_master_id');
        $this->db->where('user_role_id', $data);
        $this->db->where('status_id', 1);
        $this->db->where('read', 1);
        
        $result = $this->db->get('authorize_module');
        return $result->result();
    }  

    public function forgot_password($get){
        $this->db->where('username', $get['username']);
        $this->db->set('password', $get['password'] );
        return $this->db->update('user_credential');
    }

    public function change_password($get){
        $this->db->where('user_id', $get['user_id']);
        $this->db->set('password',  encrypt($get['password'], ENCRYPTION_KEY) );
        return $this->db->update('user_credential');
    }


}
