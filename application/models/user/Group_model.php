<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Group_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
     $this->db = $this->load->database('smartfridge', TRUE);
  }

  public function get_group_all(){
    $this->db->where('status_id', 1);
    return $this->db->get('user_group')->result();
  }

  function get_specific_group($id){
    $this->db->where('status_id' ,1);
    $this->db->where('user_group_id', $id);
    return  $this->db->get("user_group")->row();
  }

    function insert_usergroup($data)
  {
	$this->db->insert('user_group',$data);

    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }        

  }

    function update_usergroup($obj)
  {

	$this->db->where('user_group_id', $obj['user_group_id']); 
	$this->db->update('user_group', $obj);

    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }           
  }

  public function delete_selected_group($id){
	$this->db->where('user_group_id', $id);
    $this->db->delete('user_group');

    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    } 
}

}