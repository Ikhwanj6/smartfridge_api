<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
     $this->db = $this->load->database('smartfridge', TRUE);
  }

  public function get_productbyname($data){
   $this->db->select('product_name, product_id, product_image_url, product_category_id');
   $this->db->where('product_name',$data);
   $this->db->where('status_id', 1);
   $query = $this->db->get('product');

   return $query->row();
  }

  public function get_allproduct(){
    $this->db->select('p.product_id, pc.product_category_name, p.product_name, p.product_sku, p.product_description, p.product_price, 
    p.product_price_cost, p.product_image_url, p.created_datetime, p.updated_datetime, p.status_id');
    $this->db->where('p.status_id', 1);
    $this->db->join('product_category pc', 'pc.product_category_id = p.product_category_id');
    $query = $this->db->get('product p');

    return $query->result();
  }

}