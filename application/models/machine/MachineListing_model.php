<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MachineListing_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
     $this->db = $this->load->database('smartfridge', TRUE);
  }
public function getFullMachineData($user_id) {
    $this->db->select('
        m.machine_id, 
        m.machine_name, 
        m.machine_identifier, 
        m.user_id, 
        s.status_name,
        a.pin_name, 
        a.full_address, 
        a.city, 
        a.state_id, 
        a.postcode, 
        a.latitude, 
        a.longitude, 
        a.unit, 
        a.floor, 
        a.building, 
        ur.user_role_name, 
        c.contact_phone, 
        c.contact_email, 
        ms.machine_mode_status_id,
        u.user_name,  
        mct.temperature,
        vn.app_apk_version_number,
        dr.machine_door_status_id,
        m.updated_datetime,
    ');

    $this->db->from('machine m');
    $this->db->join('user u', 'u.user_id = m.user_id');
    $this->db->join('status s', 's.status_id = m.status_id');
    $this->db->join('machine_permission mp', 'mp.machine_id = m.machine_id');
    $this->db->join('address a', 'a.address_id = m.address_id');
    $this->db->join('user_role ur', 'ur.user_role_id = u.user_role_id');
    $this->db->join('contact c', 'c.contact_id = u.contact_id');
    $this->db->join('machine_status ms', 'ms.machine_id = m.machine_id');
    $this->db->join('machine_current_temperature mct', 'mct.machine_id = m.machine_id');
    $this->db->join('app_apk_version apk', ' apk.app_apk_version_id = m.app_apk_version_id');
    $this->db->join('app_apk_version_number vn', 'vn.app_apk_version_number_id = apk.app_apk_version_number_id');
    $this->db->join('machine_current_door_status dr', 'dr.machine_id = m.machine_id');
    
    // Filter records based on permissions
    $this->db->where('mp.user_id', $user_id);
    $this->db->where('mp.allow_permission', 1);
    
    // Only active machines
    $this->db->where('m.status_id', 1);
    
    // Sort by the latest updates
    $this->db->order_by('m.updated_datetime', 'DESC');
    // $this->db->limit(10);

    return $this->db->get()->result();
}

    public function searchByMachineIdentifier($machine_identifier) {
        // Select specific fields
        $this->db->select('
            m.machine_id, 
            m.machine_name, 
            m.machine_identifier, 
            m.user_id, 
            s.status_name,
            a.pin_name, 
            a.full_address, 
            a.city, 
            a.state_id, 
            a.postcode, 
            a.latitude, 
            a.longitude, 
            a.unit, 
            a.floor, 
            a.building, 
            ur.user_role_name, 
            c.contact_phone, 
            c.contact_email, 
            ms.machine_mode_status_id, 
            mct.temperature
        ');

        $this->db->from('machine m');
        $this->db->join('user u', 'u.user_id = m.user_id');
        $this->db->join('status s', 's.status_id = m.status_id');
        $this->db->join('address a', 'a.address_id = m.address_id');
        $this->db->join('user_role ur', 'ur.user_role_id = u.user_role_id');
        $this->db->join('contact c', 'c.contact_id = u.contact_id');
        $this->db->join('machine_status ms', 'ms.machine_id = m.machine_id');
        $this->db->join('machine_current_temperature mct', 'mct.machine_id = m.machine_id');

        $this->db->like('m.machine_identifier', $machine_identifier);
        $this->db->where('m.status_id', 1);
        $this->db->order_by('m.updated_datetime', 'DESC');

        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false; 
        }
    }

    public function getMachineById($id){

      $this->db->select('
        m.machine_id, 
            m.machine_name, 
            m.machine_identifier, 
            m.user_id, 
            s.status_name,
            a.pin_name, 
            a.full_address, 
            a.city, 
            a.state_id, 
            a.postcode, 
            a.latitude, 
            a.longitude, 
            a.unit, 
            a.floor, 
            a.building, 
            ur.user_role_name, 
            c.contact_phone, 
            c.contact_email, 
            ms.machine_mode_status_id,
            u.user_name,  
            mct.temperature,
            vn.app_apk_version_number,
            dr.machine_door_status_id,
            m.updated_datetime,
        ');

        $this->db->from('machine m');
        $this->db->join('user u', 'u.user_id = m.user_id');
        $this->db->join('status s', 's.status_id = m.status_id');
        $this->db->join('machine_permission mp', 'mp.machine_id = m.machine_id');
        $this->db->join('address a', 'a.address_id = m.address_id');
        $this->db->join('user_role ur', 'ur.user_role_id = u.user_role_id');
        $this->db->join('contact c', 'c.contact_id = u.contact_id');
        $this->db->join('machine_status ms', 'ms.machine_id = m.machine_id');
        $this->db->join('machine_current_temperature mct', 'mct.machine_id = m.machine_id');
        $this->db->join('app_apk_version apk', ' apk.app_apk_version_id = m.app_apk_version_id');
        $this->db->join('app_apk_version_number vn', 'vn.app_apk_version_number_id = apk.app_apk_version_number_id');
        $this->db->join('machine_current_door_status dr', 'dr.machine_id = m.machine_id');
    $this->db->where('m.machine_id', $id);
    $this->db->where_in('m.status_id', [1, 2]); 
    $this->db->where('mp.allow_permission', 1);
    return $this->db->get()->row();
    }

  function get_machineplanogram($data){
  $this->db->select('machine_planogram.machine_planogram_id, m.machine_name, machine_planogram.machine_id, machine_planogram.product_id, p.product_name, p.product_description, p.product_image_url, p.product_category_id, machine_planogram.product_sku, machine_planogram.product_price,  machine_planogram.product_price_cost,
  machine_planogram.inventory, machine_planogram.max_capacity, machine_planogram.product_threshold_value, machine_planogram.status_id ');
	$this->db->where('machine_planogram.machine_id', $data);
  $this->db->where('p.status_id', 1);
  $this->db->join('product p', 'p.product_id = machine_planogram.product_id');
    $this->db->join('machine m', 'm.machine_id = machine_planogram.machine_id');
	$query = $this->db->get('machine_planogram');

	return $query->result();
  }   

  public function get_machinestatus($get){
    $this->db->select('machine_status.machine_mode_status_id, machine_status.machine_in_use , machine_status.machine_last_app_opened');
    $this->db->where('machine_id', $get);
    $this->db->where('status_id', 1);
    $this->db->order_by('machine_id', 'ASC');
    $query = $this->db->get('machine_status');

    return $query->row();
  }
  
  
  public function getActiveGkashCredential($data){
    $this->db->where('machine_id', $data);
    $query = $this->db->get('payment_gateway_gkash_credential');
    return $query->row();
  }  

  public function getActiveRmsCredential($data){
    $this->db->where('machine_id', $data);
    $query = $this->db->get('payment_gateway_rms_credential');
    return $query->row();    
  }

    public function get_machinepayment($data)
	{
    $this->db->where('machine_id',$data);
    $this->db->where_in('status_id', [1,2]);
    $query = $this->db->get('machine_payment');
    return $query->row();
	}  

    public function get_machinespecs($id){
      // $this->db->select('machine_specification.machine_specification_id, ');
      $this->db->where('machine_id', $id);
      $this->db->join('manufacturer', 'manufacturer.manufacturer_id = machine_specification.manufacturer_id');
      $this->db->join('machine_model', 'machine_model.machine_model_id = machine_specification.machine_model_id');
      $query =  $this->db->get('machine_specification');

      return $query->row();
    }

    public function get_machinetemps_log($get){
      $this->db->select('machine_temperature_log.temperature, machine_temperature_log.machine_id');
      $this->db->where('machine_temperature_log.machine_id', $get);
      $this->db->order_by('machine_temperature_log.machine_temperature_log_id', 'DESC');
      $query = $this->db->get('machine_temperature_log');

      return $query->row();
    }

    public function updateMachineDoorStatus($data){
      $this->db->where('machine_id', $data['machine_id']);
      $this->db->update('machine_current_door_status', $data);
      
    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }      
    }

    public function update_machine_planogram($data){
      $this->db->where('machine_planogram_id', $data['machine_planogram_id']);
      $this->db->update('machine_planogram', $data);
      if ($this->db->affected_rows() > 0) {
        return true;
      }else{
        return false;
      }
    }


public function add_product_ownership($data) {
    // Check if record already exists (optional)
    $this->db->where('machine_id', $data['machine_id']);
    $this->db->where('product_id', $data['product_id']);
    $query = $this->db->get('machine_planogram');

    if ($query->num_rows() > 0) {
        return false; // Record already exists
    }

    // Insert new record
    $this->db->insert('machine_planogram', $data);

    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        // Optionally log the error message for debugging
        log_message('error', 'Database insertion failed: ' . $this->db->last_query());
        return false;
    }
}


 
}