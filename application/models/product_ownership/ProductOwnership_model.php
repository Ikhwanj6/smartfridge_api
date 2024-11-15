<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductOwnership_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
     $this->db = $this->load->database('smartfridge', TRUE);
  }

    public function get_product_ownership($id)
    {
        $this->db->select('machine.machine_name, machine.machine_id, machine.machine_identifier, machine.user_id, product.product_id, product.product_name, product.product_category_id, product.product_image_url, machine_planogram.product_price,
        machine_planogram.product_price_cost, machine_planogram.inventory, machine_planogram.max_capacity, machine_planogram.product_threshold_value, machine_planogram.status_id');
        
        $this->db->join('machine', 'machine.machine_id = machine_planogram.machine_id');
        $this->db->join('product', 'product.product_id = machine_planogram.product_id');
        $this->db->where('machine.machine_id', $id);
        $this->db->where('machine_planogram.status_id', 1);
        
        $query = $this->db->get('machine_planogram');

        return $query->result();
    }

}