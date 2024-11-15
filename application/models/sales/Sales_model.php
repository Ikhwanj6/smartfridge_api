<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
     $this->db = $this->load->database('smartfridge', TRUE);
  }


public function transaction_List($data) {
    $this->db->select('sale.transaction_id, 
                       sale.sale_id, 
                       sale.product_name, 
                       sale.product_category_name, 
                       sale.product_subcategory_name, 
                       sale.product_image_url, 
                       sale.currency_name, 
                       sale.selling_price,
                       sale.cost_price,
                       sale.payment_datetime,
                       IF(COUNT(sale.transaction_id) > 1, SUM(sale.mp_amount), sale.mp_amount) as total_mp_amount, 
                       IF(COUNT(sale.transaction_id) > 1, SUM(sale.selling_price), sale.selling_price) as total_selling_price, 
                       IF(COUNT(sale.transaction_id) > 1, SUM(sale.cost_price), sale.cost_price) as total_cost_price, 
                       IF(COUNT(sale.transaction_id) > 1, SUM(sale.discount_price), sale.discount_price) as total_discount_price, 
                       sale.discount_description, 
                       IF(COUNT(payment.transaction_id) > 1, SUM(payment.payment_amount), payment.payment_amount) as total_payment_amount, 
                       payment.payment_type_id, 
                       payment.payment_gateway_id, 
                       payment.payment_option_id, 
                       payment.payment_status_id, 
                       machine.machine_name, 
                       machine.machine_identifier, 
                       machine.address_id, 
                       machine.user_id');

    // Joining with related tables
    $this->db->join('payment', 'payment.transaction_id = sale.transaction_id');
    $this->db->join('machine', 'machine.machine_identifier = sale.machine_identifier');
    $this->db->join('machine_permission', 'machine_permission.machine_id = machine.machine_id');

    // Filter by user_id from machine_permission
    $this->db->where('machine_permission.user_id', $data);

    // Grouping by transaction_id to combine totals for the same transaction
    $this->db->group_by('sale.transaction_id');

    // Execute the query
    $query = $this->db->get('sale'); 

    return $query->result();
}



public function total_sales($identifier, $user_id)
{
    // Select sum of sale fields with conditional fields based on identifier
    if ($identifier != 0) {
        $this->db->select('
            SUM(sale.selling_price) as total_sale, 
            SUM(sale.cost_price) as total_cost, 
            SUM(sale.discount_price) as total_discount, 
            sale.machine_identifier, 
            sale.transaction_id, 
            sale.currency_name
        ');
        $this->db->where('sale.machine_identifier', $identifier);
    } else {
        $this->db->select('
            SUM(sale.selling_price) as total_sale, 
            SUM(sale.cost_price) as total_cost, 
            SUM(sale.discount_price) as total_discount
        ');
    }

    // Join related tables for permissions and machine data
    $this->db->join('machine', 'machine.machine_identifier = sale.machine_identifier');
    $this->db->join('machine_permission', 'machine_permission.machine_id = machine.machine_id');
    
    // Apply permission and user filtering
    $this->db->where('machine_permission.allow_permission', 1);
    $this->db->where('machine_permission.user_id', $user_id);

    // Filter sales based on a valid payment date
    $this->db->where('payment_datetime >', date("Y-m-d H:i:s"));

    // Specify the sale table
    $this->db->from('sale');
    
    // Execute and return the query results
    $query = $this->db->get();
    return $query->result();
}



    public function total_itemcount($identifier, $user_id){
    
    if($identifier != 0){
        $this->db->select('sale.sale_id');
        $this->db->where('sale.machine_identifier', $identifier);
    }else{
        $this->db->select('sale.sale_id');        
    }
    $this->db->join('machine', 'machine.machine_identifier = sale.machine_identifier');
    $this->db->join('machine_permission', 'machine_permission.machine_id = machine.machine_id');
    $this->db->where('machine_permission.allow_permission', 1 );
    $this->db->where('machine_permission.user_id', $user_id);
        // Filter sales based on a valid payment date
    $this->db->where('sale.payment_datetime >', date("Y-m-d H:i:s"));
    $this->db->from('sale');
    $query = $this->db->get();

    return $query->num_rows();

  }

    public function getTotalItemSold($get)
  {
    $this->db->select('sale_id');

    //TO FILTER BY MACHINE OR ALL
    if ($get['machine'] != 'all') {
      $this->db->where('sale.machine_identifier',$get['machine']);
    }
    //TO FILTER BY DATE
    if ($get['filter_date'] == 'day') {
      $this->db->where('DATE(sale.payment_datetime)', date('Y-m-d'));
    }elseif ($get['filter_date'] == 'week') {
      $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime('- 7 days')));
      $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d'));
    }else {
      $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime($get['start_date'])));
      $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d', strtotime($get['end_date'])));
    }
    $query = $this->db->get('sale');
    return $query->num_rows();
  }

   public function gettotalbyday($get,$date,$enddate)
  {
    $this->db->select('SUM(selling_price) as totalbyday,');

    //filter
    if ($get['machine'] != 'all') {
      $this->db->where('sale.machine_identifier',$get['machine']);
    }
    $this->db->where('sale.payment_datetime >=', $date);
    $this->db->where('sale.payment_datetime <', $enddate);

    $query = $this->db->get('sale');
    return $query->row();
  }

    public function gettotalbytime($get,$time,$endtime)
  {
    $this->db->select('SUM(selling_price) as totalbyday,');

    //filter
    if ($get['machine'] != 'all') {
      $this->db->where('sale.machine_identifier',$get['machine']);
    }
    $this->db->where('sale.payment_datetime >=', $time);
    $this->db->where('sale.payment_datetime <', $endtime);

    $query = $this->db->get('sale');
    return $query->row();
  }

  public function total_sale($get) {
    $this->db->select('
        SUM(selling_price) as totalSale, 
        SUM(cost_price) as totalCost,
        SUM(selling_price - cost_price) as totalProfit,       
        SUM(discount_price) as totalDiscount,         
        COUNT(sale_id) as soldCount
    ');

    if ($get['machine'] != 'all') {
        $this->db->where('machine_identifier', $get['machine']);
    }

    if ($get['filter_date'] == 'day') {
        $this->db->where('DATE(payment_datetime)', date('Y-m-d'));
    } elseif ($get['filter_date'] == 'week') {
        $this->db->where('DATE(payment_datetime) >=', date('Y-m-d', strtotime('-7 days')));
        $this->db->where('DATE(payment_datetime) <=', date('Y-m-d'));
    } else {
        $this->db->where('DATE(payment_datetime) >=', date('Y-m-d', strtotime($get['start_date'])));
        $this->db->where('DATE(payment_datetime) <=', date('Y-m-d', strtotime($get['end_date'])));
    }
    $result = $this->db->get('sale');
    return $result->row();
}

      // public function get_bysale($custid,$get,$machine)
      // {
      //   $this->db->select('*');
      //   $this->db->join('machine','sale.machine_identifier = machine.machine_identifier');
      //   $this->db->join('machine_permission', 'machine_permission.machine_id = machine.machine_id');
      //   $this->db->join('address','machine.address_id = address.address_id');
      //   $this->db->where('machine_permission.user_id',$custid);
      //   $this->db->where('machine.machine_identifier',$machine);
      //   //TO FILTER BY DATE
      //   if ($get['filter_date'] == 'day') {
      //           $this->db->where('DATE(sale.payment_datetime)', date('Y-m-d'));
      //       }elseif ($get['filter_date'] == 'week') {
      //           $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime('- 7 days')));
      //     $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d'));
      //       }else {
      //     $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime($get['start_date'])));
      //     $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d', strtotime($get['end_date'])));
      //       }
      //   $this->db->group_by("sale.machine_identifier"); 
      //   $query = $this->db->get('sale');
      //   return $query->result();
      // }


public function get_bysale($custid, $get, $machine) {
    $this->db->select('
        sale.*,
        machine.machine_identifier, 
        product.product_name, 
        COUNT(sale.sale_id) as total_sales, 
        SUM(sale.selling_price) as total_amount, 
        SUM(sale.mp_amount) as qty_sales,
        machine.machine_name, 
        address.full_address, 
        MAX(sale.payment_datetime) as last_sale_date,
        SUM(sale.cost_price) as cost_sales,
    ');  // Adjust these fields based on what you need
    $this->db->join('machine', 'sale.machine_identifier = machine.machine_identifier');
    $this->db->join('machine_permission', 'machine_permission.machine_id = machine.machine_id');
    $this->db->join('address', 'machine.address_id = address.address_id');
    $this->db->join('product', 'sale.product_name = product.product_name');  
    
    // Filter by customer ID and machine if provided
    $this->db->where('machine_permission.user_id', $custid);
    
    if ($machine != 'all') {
        $this->db->where('machine.machine_identifier', $machine);
    }

    // Filter by date
    if (isset($get['filter_date']) && $get['filter_date'] == 'day') {
        $this->db->where('DATE(sale.payment_datetime)', date('Y-m-d'));
    } elseif (isset($get['filter_date']) && $get['filter_date'] == 'week') {
        $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime('-7 days')));
        $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d'));
    } elseif (!empty($get['start_date']) && !empty($get['end_date'])) {
        $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime($get['start_date'])));
        $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d', strtotime($get['end_date'])));
    }

    // Group by machine identifier and product ID to show sales per product in each machine
    $this->db->group_by(['machine.machine_identifier', 'sale.product_name']);  

    
    $query = $this->db->get('sale');
    return $query->result();
}

      public function get_bymachine($get)
      {
        $this->db->select('*');
        $this->db->join('machine_permission', 'machine_permission.machine_id = machine.machine_id');
        $this->db->join('address','machine.address_id = address.address_id');
        $this->db->where('machine_permission.user_id',$get['userid']);
  
        //TO FILTER BY MACHINE OR ALL
        if ($get['filtermachine'] != 'all') {
          $this->db->where('machine.machine_identifier',$get['filtermachine']);
        }
        $query = $this->db->get('machine');
        return $query->result();
  
      }

        function total_paymentType($get,$type){
    $this->db->select('payment.payment_type_id, COUNT(sale.sale_id) as total');
    $this->db->from('sale');
    $this->db->join('payment','sale.transaction_id = payment.transaction_id');
    $this->db->where_in('payment.payment_type_id',$type);
    $this->db->where('sale_status_id !=',2);

    //TO FILTER BY MACHINE OR ALL
    if($get['FilterMachine'] != 'all'){
      $this->db->where('sale.machine_identifier',$get['FilterMachine']);
    }
    //TO FILTER BY DATE
    if($get['FilterDate'] == 'day'){
      $this->db->where('DATE(sale.payment_datetime)', date('Y-m-d'));
    }
    elseif($get['FilterDate'] == 'week'){
      $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime('- 7 days')));
      $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d'));
    }
    else{
      $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime($get['StartDate'])));
      $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d', strtotime($get['EndDate'])));
    }

    $this->db->group_by('payment.payment_type_id');
    $result = $this->db->get()->result();

    $totals = array();
    foreach ($result as $row) {
        $totals[$row->payment_type_id] = $row->total;
    }
    return $totals;
  }

      public function get_bytransaction($userid, $get, $machine)
      {
        $this->db->select('*');
  
        $this->db->join('machine','sale.machine_identifier = machine.machine_identifier');
        $this->db->join('machine_permission', 'machine_permission.machine_id = machine.machine_id');
        $this->db->join('address','machine.address_id = address.address_id');
        // $this->db->join('sale_status','sale_status.sale_status_id = sale.sale_status_id');
  
        $this->db->join('payment','sale.transaction_id = payment.transaction_id');
        $this->db->join('payment_type','payment.payment_type_id = payment_type.payment_type_id');
        $this->db->join('payment_option','payment.payment_option_id = payment_option.payment_option_id');
        $this->db->join('payment_status','payment.payment_status_id = payment_status.payment_status_id');
  
        $this->db->where('machine_permission.user_id',$userid);
  
        //TO FILTER BY MACHINE OR ALL
          if ($machine != 'all') {
              $this->db->where('machine.machine_identifier', $machine);
          }
        //TO FILTER BY DATE
        if (isset($get['filter_date']) && $get['filter_date'] == 'day') {
            $this->db->where('DATE(sale.payment_datetime)', date('Y-m-d'));
        } elseif (isset($get['filter_date']) && $get['filter_date'] == 'week') {
            $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime('-7 days')));
            $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d'));
        } elseif (!empty($get['start_date']) && !empty($get['end_date'])) {
            $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime($get['start_date'])));
            $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d', strtotime($get['end_date'])));
        }
        //filter by status
        // if ($get['filter_status'] != 'all') {
        //   $this->db->where('sale.sale_status_id',$get['filter_status']);
        
        // }
  
        $this->db->order_by('sale.transaction_id','DESC');
        $query = $this->db->get('sale');
        return $query->result();
      }


        function transaction_success($get){

        $this->db->select('*');
        $this->db->join('sale','payment.transaction_id = sale.transaction_id');
        $this->db->join('machine', 'sale.machine_identifier = machine.machine_identifier');
        $this->db->join('machine_permission', 'machine_permission.machine_id = machine.machine_id');
        $this->db->group_by('sale.transaction_id');
        $this->db->where('machine_permission.user_id',$get['userid']);
        // $this->db->where('payment.payment_status_id',0);
        $this->db->where('sale.sale_status_id',1);
  
  
        //TO FILTER BY MACHINE OR ALL
        if ($get['filter_machine'] != 'all') {
          $this->db->where('sale.machine_identifier',$get['filter_machine']);
        }
        //TO FILTER BY DATE
        if ($get['filter_date'] == 'day') {
          $this->db->where('DATE(sale.payment_datetime)', date('Y-m-d'));
        }elseif ($get['filter_date'] == 'week') {
          $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime('- 7 days')));
          $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d'));
        }else {
          $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime($get['start_date'])));
          $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d', strtotime($get['end_date'])));
        }
  
        $query = $this->db->get('payment');
        return $query->num_rows();
      }
  
      function transaction_failed($get){
        
        $this->db->select('*');
        $this->db->join('sale','payment.transaction_id = sale.transaction_id');
        $this->db->join('machine', 'sale.machine_identifier = machine.machine_identifier');
        $this->db->join('machine_permission', 'machine_permission.machine_id = machine.machine_id');
        $this->db->group_by('sale.transaction_id');
        $this->db->where('machine_permission.user_id',$get['userid']);
        // $this->db->where('payment.payment_status_id',0);
        $this->db->where('sale.sale_status_id',2);
  
  
        //TO FILTER BY MACHINE OR ALL
        if ($get['filter_machine'] != 'all') {
          $this->db->where('sale.machine_identifier',$get['filter_machine']);
        }
        //TO FILTER BY DATE
        if ($get['filter_date'] == 'day') {
          $this->db->where('DATE(sale.payment_datetime)', date('Y-m-d'));
        }elseif ($get['filter_date'] == 'week') {
          $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime('- 7 days')));
          $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d'));
        }else {
          $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime($get['start_date'])));
          $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d', strtotime($get['end_date'])));
        }
  
        $query = $this->db->get('payment');
        return $query->num_rows();
      }
  
      function transaction_error($get){

        $this->db->select('*');
        $this->db->join('sale','payment.transaction_id = sale.transaction_id');
        $this->db->join('machine', 'sale.machine_identifier = machine.machine_identifier');
        $this->db->join('machine_permission', 'machine_permission.machine_id = machine.machine_id');
        $this->db->group_by('sale.transaction_id');
        $this->db->where('machine_permission.user_id',$get['userid']);
        $this->db->where('payment.payment_status_id',5);
        $this->db->where('sale.sale_status_id',3);
  
  
        //TO FILTER BY MACHINE OR ALL
        if ($get['filter_machine'] != 'all') {
          $this->db->where('sale.machine_identifier',$get['filter_machine']);
        }
        //TO FILTER BY DATE
        if ($get['filter_date'] == 'day') {
          $this->db->where('DATE(sale.payment_datetime)', date('Y-m-d'));
        }elseif ($get['filter_date'] == 'week') {
          $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime('- 7 days')));
          $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d'));
        }else {
          $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime($get['start_date'])));
          $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d', strtotime($get['end_date'])));
        }
  
        $query = $this->db->get('payment');
        return $query->num_rows();
      }


      public function transaction_error2($get) //sale_status_id = 0
      {
        $this->db->select('*');
        $this->db->join('sale','payment.transaction_id = sale.transaction_id');
        $this->db->join('machine', 'sale.machine_identifier = machine.machine_identifier');
        $this->db->join('machine_permission', 'machine_permission.machine_id = machine.machine_id');
        $this->db->group_by('sale.transaction_id');
        $this->db->where('machine_permission.user_id',$get['userid']);
        $this->db->where('payment.payment_status_id',0);
        $this->db->where('sale.sale_status_id',0);
  
  
        //TO FILTER BY MACHINE OR ALL
        if ($get['filter_machine'] != 'all') {
          $this->db->where('sale.machine_identifier',$get['filter_machine']);
        }
        //TO FILTER BY DATE
        if ($get['filter_date'] == 'day') {
          $this->db->where('DATE(sale.payment_datetime)', date('Y-m-d'));
        }elseif ($get['filter_date'] == 'week') {
          $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime('- 7 days')));
          $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d'));
        }else {
          $this->db->where('DATE(sale.payment_datetime) >=', date('Y-m-d', strtotime($get['start_date'])));
          $this->db->where('DATE(sale.payment_datetime) <=', date('Y-m-d', strtotime($get['end_date'])));
        }
  
        $query = $this->db->get('payment');
        return $query->num_rows();
      }



}