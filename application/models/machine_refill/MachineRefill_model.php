
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MachineRefill_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
     $this->db = $this->load->database('smartfridge', TRUE);
  }

	public function get_machine_planogram_product($get){
		// $this->db->join('machine', 'machine.machine_id = machine_planogram.machine_id');
		$this->db->join('product', 'product.product_id = machine_planogram.product_id');
		$this->db->where('machine_planogram.machine_id' , $get);
		$this->db->where('machine_planogram.status_id', 1);
    // $this->db->where('product.status_id', 1);
		$query = $this->db->get('machine_planogram');
		return $query->result();
	}

    public function get_refillrecord($get){
      
        $this->db->where('machine_id', $get);
        $this->db->join('refill_record', 'refill_record.refill_id = refill.refill_id');
        $this->db->join('product', 'product.product_name = refill_record.product_name');
        $this->db->order_by('datetime', 'DESC');

        $query = $this->db->get('refill');

        return $query->result();
    }

public function add_refill_and_record($refill_data, $refill_record_data){
    $this->db->trans_start();

    // Insert into 'refill' table
    $this->db->insert('refill', $refill_data);
    $refill_id = $this->db->insert_id();

    // Add the refill ID to the record data if needed
    $refill_record_data['refill_id'] = $refill_id;

    // Insert into 'refill_record' table
    $this->db->insert('refill_record', $refill_record_data);


    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        return false;
    } else {
        return $refill_id;
    }
}



    function inventory($data){
        $this->db->select('machine_planogram.*, (max_capacity - inventory) as refill, product.*');
        $this->db->join('product', 'machine_planogram.product_id = product.product_id');
  
        $this->db->where('machine_planogram.machine_id', $data);
        $this->db->where('(max_capacity - inventory) > 0');  
        $query = $this->db->get('machine_planogram');
        return $query->result();
    }

    function get_machinethreshold($data){
      $this->db->select('refill_threshold.machine_id, refill_threshold.refill_threshold_value');
      $this->db->where('machine_id', $data);
      $this->db->where('status_id', 1);
      $query = $this->db->get('refill_threshold');

      return $query->result();

    }

    public function update_machine_refill_threshold($data){
        $this->db->where('machine_id', $data['machine_id']);
        $this->db->set('refill_threshold_value', $data['refill_threshold_value']);
        $this->db->update('refill_threshold');

        if($this->db->affected_rows() > 0 ){
          return true;
        }else{
          return false;
        }
    }


    public function add_refill($data){
      $refill = [
        'machine_id' => $data['machine_id'],
        'refill_by' => $data['user_id'],
      ];

      $this->db->insert('refill', $refill);
      $refill_id = $this->db->insert_id();

      return $refill_id;

    }

public function add_refillrecord($data, $refill_id) {
    // Initialize the array to hold refill records
    $arrayupdate = [];

    for ($i = 0; $i < $data['inventory_length']; $i++) {
     
        $update = array(
            'refill_id' => $refill_id,
            'machine_planogram_id' => $data['planogram_'],  
            'max_capacity' => $data['max_'],
            'before_refill' => $data['old_inventory_'],
            'after_refill' => $data['inventory_'],
        );

        // Add to the update array
        $arrayupdate[] = $update;
    }

    if (!empty($arrayupdate)) {
        $this->db->insert_batch('refillrecord', $arrayupdate);
    }
}


  ////////////////// UPDATE MAIN INVENTORY V2
    public function updatebatchinventory($obj, $arrayupdate){

        $this->db->where('machine_id', $obj['machine_id']);
        return $this->db->update_batch('machine_planogram', $arrayupdate, 'motor_slot_id');
    }

    public function insertRefillMain($obj){

        $update = array(
            'refill_by' => $obj['user_id'],
            'machine_id' => $obj['machine_id']
        );

        $this->db->insert('refill', $update);
        return $this->db->insert_id();
    }

    public function insertRefillRecordMain($obj, $arrayupdate){

        return $this->db->insert_batch('refill_record', $arrayupdate);
    }

    public function updatetotalitemrefill($obj, $arrayupdate){
    $this->db->where('machine_id',$obj['machine_id']); 
    return $this->db->update('machine_item_sold', $arrayupdate);
    }

    ////////////////// UPDATE MAIN INVENTORY V2

    ////////////////// UPDATE MAIN INVENTORY V1
    // Insert data to 'Refill' table and return its refill_id (main machine)
    public function insertRefill($obj){

        $update = array(
            'refill_by' => $obj['user_id'],
            'machine_id' => $obj['machine_id']
        );

        $this->db->insert('refill', $update);
        return $this->db->insert_id();
    }

    // Update the inventory (main machine)
    public function updatebatchslot($obj){

        $arraymotor_slot_id = explode(",", $obj['motor_slot_id']);
        $arrayinventory = explode(",", $obj['new_inventory']);
        // print_r($arrayslot);

        $arrayupdate = [];

        foreach($arraymotor_slot_id as $key => $slot){

            $newinventory = $arrayinventory[$key];

            $update = array(
                'motor_slot_id' => $slot,
                'inventory' => $newinventory,
            );

            $arrayupdate[] = $update;
        }

        // print_r($arrayupdate);
        // return $arrayupdate;

        $this->db->where('machine_id', $obj['machine_id']);
        return $this->db->update_batch('machine_planogram', $arrayupdate, 'motor_slot_id');
    }

    // Insert the record of the refill into 'Refill_record' table (main machine)
    public function insertRefillRecord($obj){

        $arraymotor_slot_id = explode(",", $obj['motor_slot_id']);
        $arrayproduct_name = explode(",", $obj['product_name']);
        $arraymax_capacity = explode(",", $obj['max_capacity']);
        $arrayold_inventory = explode(",", $obj['old_inventory']);
        $arrayinventory = explode(",", $obj['new_inventory']);

        $arrayupdate = [];

        foreach($arraymotor_slot_id as $key => $slot){

            $productname = $arrayproduct_name[$key];
            $maxcapacity = $arraymax_capacity[$key];
            $beforerefill = $arrayold_inventory[$key];
            $newinventory = $arrayinventory[$key];

            $update = array(
                'refill_id' => $obj['refill_id'],
                'motor_slot_id' => $slot,
                'product_name' => $productname,
                'max_capacity' => $maxcapacity,
                'before_refill' => $beforerefill,
                'after_refill' => $newinventory,
            );
            $arrayupdate[] = $update;
        }

        return $this->db->insert_batch('refill_record', $arrayupdate);
    }




    }