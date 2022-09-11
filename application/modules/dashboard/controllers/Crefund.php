<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crefund extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->auth->check_user_auth();
        $this->load->model(array('dashboard/Invoices'));
        $this->load->library('dashboard/linvoice');
        $this->load->library('dashboard/occational');
    }

    //Default invoice add from loading
    public function index() {
        $this->new_invoice();
    }

    //Add new invoice
    public function new_refund() {
  
            $data['module'] = "dashboard";
            $data['page'] = "refund/add_refund_form";
            echo Modules::run('template/layout', $data);
    }

   

    public function get_invoice_products() {
        $filter = array(
            'invoice_no' =>  $this->input->post('invoice_no', TRUE),
           );
        $sql="select b.*,(I.quantity - I.return_quantity) as quantity,I.variant_id from invoice_details I join product_information b on b.product_id = I.product_id where quantity > 0 and I.invoice_id='".$filter['invoice_no']."' GROUP BY b.product_id;";
        $sql14 = $this->db->query($sql);
        $query = $sql14->result_array();
            echo json_encode($query);
    }

    public function get_product_variants() {
        $filter = array(
            'invoice_no' =>  $this->input->post('invoice_no', TRUE),
            'product_id' =>  $this->input->post('product_id', TRUE),
           );
        $sql="select (I.quantity - I.return_quantity) as quantity,I.variant_id,v.variant_name from invoice_details I join variant v on v.variant_id = I.variant_id where I.product_id ='".$filter['product_id']."' and I.invoice_id ='".$filter['invoice_no']."';";
        $sql14 = $this->db->query($sql);
        $query = $sql14->result_array();
            echo json_encode($query);
    }

    public function new_return() 
    {
        $find_active_fiscal_year = $this->db->select('*')->from('acc_fiscal_year')->where('status', 1)->get()->row();
        $filter = array(
            'invoice_no' =>  $this->input->get('invoice_no', TRUE),
            'product_id' =>  $this->input->get('product_id', TRUE),
            'variant_id' =>  $this->input->get('variant_id', TRUE),
            'status' =>  $this->input->get('status', TRUE),
            'quantity' =>  $this->input->get('quantity', TRUE),
           );

           //get customer headcode
           $sql="SELECT `customer_id` FROM `invoice` WHERE `invoice_id` ='".$filter['invoice_no']."';";
           $result= $this->db->query($sql);
           $customer_id  = $result->result_array();
           $customer_id  = $customer_id[0]['customer_id'];
           
           $customer_head = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('customer_id', $customer_id)->get()->row();

           // get invoice details record 
           $sql="select I.* from invoice_details I where I.variant_id ='".$filter['variant_id']."' and I.product_id ='".$filter['product_id']."' and I.invoice_id ='".$filter['invoice_no']."';";
           $result= $this->db->query($sql);
           $invoice_details  = $result->result_array();

        //    $invoice_details =  $this->db->select('*')->from('invoice_details')->where('invoice_id', $filter['invoice_no'])->where('product_id', $filter['product_id'])->where('variant_id', $filter['variant_id'])->get()->result_array();
            $this->db->select('*');
            $this->db->from('assembly_products');
            $this->db->where('parent_product_id', $filter['product_id']);
            $this->db->join('product_information', 'product_information.product_id = assembly_products.child_product_id');
            $query = $this->db->get();
            $product_list = $query->result();

           if($filter['status'] == 0) //fit
           {
                if (!empty($product_list)) {
                    foreach ($product_list as $product) 
                    {
                        $sql="update invoice_stock_tbl set quantity=quantity-".$filter['quantity']." where store_id='".$invoice_details[0]['store_id']."' and variant_id='".$invoice_details[0]['variant_id']."' and product_id='".$product['product_id']."';";
                        $result= $this->db->query($sql);
                    }
                }
                else
                {
                // update stock
                $sql="update invoice_stock_tbl set quantity=quantity-".$filter['quantity']." where store_id='".$invoice_details[0]['store_id']."' and variant_id='".$invoice_details[0]['variant_id']."' and product_id='".$invoice_details[0]['product_id']."';";
                $result= $this->db->query($sql);
                }
                // echo json_encode($sql);
           }

           else
           {
            if (!empty($product_list)) {
                foreach ($product_list as $product) 
                {
                    $sql="INSERT INTO `product_return`(`invoice_id`, `product_id`, `variant_id`, `quantity`, `status`) VALUES ('".$filter['invoice_no']."','".$product['product_id']."','".$filter['variant_id']."',".$filter['quantity'].",".$filter['status'].")";
                    $result = $this->db->query($sql);
                }
            }
            else
            {
                $sql="INSERT INTO `product_return`(`invoice_id`, `product_id`, `variant_id`, `quantity`, `status`) VALUES ('".$filter['invoice_no']."','".$filter['product_id']."','".$filter['variant_id']."',".$filter['quantity'].",".$filter['status'].")";
                $result = $this->db->query($sql);
            }
            
 
           } 
            
           //update invoice_details
           $sql="update invoice_details set return_quantity= return_quantity+".$filter['quantity']." where invoice_details_id='".$invoice_details[0]['invoice_details_id']."';";
            $result= $this->db->query($sql);


            //acc_transaction
                $receive_by = $this->session->userdata('user_id');  
            //calc total price of returned qunty of product
                $product_price=$invoice_details[0]['total_price']/$invoice_details[0]['quantity'];
                $total_return=($invoice_details[0]['total_price']/$invoice_details[0]['quantity'])*$filter['quantity'];
                $total_discount=$invoice_details[0]['discount']*$filter['quantity'];
                $total_discount+=$invoice_details[0]['invoice_discount']*$filter['quantity'];
                
                $total_return_with_discount=$total_return+$total_discount;
          
            //total vat
                $i_vat = $this->db->select('tax_percentage')->from('tax_product_service')->where('product_id', $filter['product_id'])->get()->row();
                if(!empty($i_vat))
                    {$tota_vat = ($product_price*($i_vat->tax_percentage/100))*$filter['quantity'];}
                else
                    {$tota_vat = 0;}
                $createdate=date('Y-m-d H:i:s');
            // total supplier price
            $cogs_price= $invoice_details[0]['supplier_rate']*$filter['quantity'];    
            //1st customer credit total_with_vat
                $customer_credit = array(
                    'fy_id' => $find_active_fiscal_year->id,
                    'VNo' => 'Inv-' . $filter['invoice_no'],
                    'Vtype' => 'Sales',
                    'VDate' => $createdate,
                    'COAID' => $customer_head->HeadCode,
                    'Narration' => 'Sales "total with vat" debited by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
                    'Debit' => 0,
                    'Credit' => $total_return+$tota_vat,
                    'IsPosted' => 1,
                    'CreateBy' => $receive_by,
                    'CreateDate' => $createdate,
                    //'IsAppove' => 0
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $customer_credit);

            //7th paid_amount depit if full paid 
                $customer_depit = array(
                    'fy_id' => $find_active_fiscal_year->id,
                    'VNo' => 'Inv-' . $filter['invoice_no'],
                    'Vtype' => 'Sales',
                    'VDate' => $createdate,
                    'COAID' => $customer_head->HeadCode,
                    'Narration' => 'Sales "paid_amount" depit by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
                    'Debit' => $total_return_with_discount,
                    'Credit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $receive_by,
                    'CreateDate' => $createdate,
                    //'IsAppove' => 0
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $customer_depit);
            //2nd Allowed Discount credit
                 $allowed_discount_credit = array(
                    'fy_id' => $find_active_fiscal_year->id,
                    'VNo' => 'Inv-' . $filter['invoice_no'],
                    'Vtype' => 'Sales',
                    'VDate' => $createdate,
                    'COAID' => 4114,
                    'Narration' => 'Sales "total discount" credited by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
                    'Debit' => 0,
                    'Credit' => $total_discount,
                    'IsPosted' => 1,
                    'CreateBy' => $receive_by,
                    'CreateDate' => $createdate,
                    //'IsAppove' => 0
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $allowed_discount_credit);

            //3rd Showroom Sales depit
                $showroom_sales_depit = array(
                    'fy_id' => $find_active_fiscal_year->id,
                    'VNo' => 'Inv-' . $filter['invoice_no'],
                    'Vtype' => 'Sales',
                    'VDate' => $createdate,
                    'COAID' => 5111, // account payable game 11
                    'Narration' => 'Sales "total price before discount" store_depit depited by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
                    'Debit' => $total_return_with_discount,
                    'Credit' => 0 ,
                    'IsPosted' => 1,
                    'CreateBy' => $receive_by,
                    'CreateDate' => $createdate,
                    //'IsAppove' => 0
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $showroom_sales_depit);

                 //4th VAT on Sales
                 $vat_depit = array(
                    'fy_id' => $find_active_fiscal_year->id,
                    'VNo' => 'Inv-' . $filter['invoice_no'],
                    'Vtype' => 'Sales',
                    'VDate' => $createdate,
                    'COAID' => 2114, // account payable game 11
                    'Narration' => 'Sales "total vat" depited by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
                    'Debit' => $tota_vat,
                    'Credit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $receive_by,
                    'CreateDate' => $createdate,
                    //'IsAppove' => 0
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $vat_depit);

            //5th cost of goods sold Credit
                $cogs_credit = array(
                    'fy_id' => $find_active_fiscal_year->id,
                    'VNo' => 'Inv-' . $filter['invoice_no'],
                    'Vtype' => 'Sales',
                    'VDate' => $createdate,
                    'COAID' => 4111,
                    'Narration' => 'Sales "cost of goods sold" Credited by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
                    'Debit' => 0,
                    'Credit' => $cogs_price, //sales price asbe
                    'IsPosted' => 1,
                    'CreateBy' => $receive_by,
                    'CreateDate' => $createdate,
                    //'IsAppove' => 0
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $cogs_credit);
            
            //6th cost of goods sold Main warehouse depit
                $cogs_main_warehouse_depit = array(
                    'fy_id' => $find_active_fiscal_year->id,
                    'VNo' => 'Inv-' . $filter['invoice_no'],
                    'Vtype' => 'Sales',
                    'VDate' => $createdate,
                    'COAID' => 1141,
                    'Narration' => '"cost of goods sold" Main warehouse depited by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
                    'Debit' => $cogs_price,
                    'Credit' => 0, //supplier price asbe
                    'IsPosted' => 1,
                    'CreateBy' => $receive_by,
                    'CreateDate' => $createdate,
                    //'IsAppove' => 0
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $cogs_main_warehouse_depit);

          

        redirect('dashboard/Crefund/new_refund' . $filter['invoice_id']);
    }

    public function change_stock($invoice_id) {
        //find previous invoice history and REDUCE the stock
        $invoice_history = $this->db->select('*')->from('invoice_details')->where('invoice_id', $invoice_id)->get()->result_array();
        if (count($invoice_history) > 0) {
            foreach ($invoice_history as $invoice_item) {
                //update
                $check_stock = $this->check_stock($invoice_item['store_id'], $invoice_item['product_id'], $invoice_item['variant_id'], $invoice_item['variant_color']);
                $stock = array(
                    'quantity' => $check_stock->quantity - $invoice_item['quantity']
                );
                if (!empty($invoice_item['store_id'])) {
                    $this->db->where('store_id', $invoice_item['store_id']);
                }
                if (!empty($invoice_item['product_id'])) {
                    $this->db->where('product_id', $invoice_item['product_id']);
                }
                if (!empty($invoice_item['variant_id'])) {
                    $this->db->where('variant_id', $invoice_item['variant_id']);
                }
                if (!empty($invoice_item['variant_color'])) {
                    $this->db->where('variant_color', $invoice_item['variant_color']);
                }
                $this->db->update('invoice_stock_tbl', $stock);
                //update
            }
        }
    }
  
}
