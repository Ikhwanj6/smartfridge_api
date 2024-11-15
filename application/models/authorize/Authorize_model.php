<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Authorize_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
     $this->db = $this->load->database('smartfridge', TRUE);
  }

function get_authorize_module($get) {
    $this->db->select(
        'authorize_module.module_master_id,
        authorize_module.user_role_id,
        authorize_module.read,
        authorize_module.write,
        authorize_module.create,
        authorize_module.delete,
        authorize_module.status_id,
        module_master.module_master_name'
    );

    $this->db->where('authorize_module.user_role_id', $get);
    $this->db->join('module_master', 'module_master.module_master_id = authorize_module.module_master_id');
    $query = $this->db->get('authorize_module');
    return $query->result();
}


function get_subauthorize_module($get) {
    $this->db->select(
        'authorize_sub_module.authorize_sub_module_id, 
        authorize_sub_module.user_role_id,
        authorize_sub_module.read,
        authorize_sub_module.write,
        authorize_sub_module.create,
        authorize_sub_module.delete,
        authorize_sub_module.status_id,
        module_sub_master.module_sub_name'
    );
    
    $this->db->where('authorize_sub_module.user_role_id', $get);
    $this->db->join('module_sub_master', 'module_sub_master.module_sub_master_id = authorize_sub_module.module_sub_master_id');
    $query = $this->db->get('authorize_sub_module');
    
    return $query->result();
}

}