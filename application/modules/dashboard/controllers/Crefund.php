<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crefund extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth->check_user_auth();
        $this->load->model(array('dashboard/Invoices'));
        $this->load->library('dashboard/lproduct');
        $this->load->library('dashboard/linvoice');
        $this->load->library('dashboard/occational');
    }

    //Default invoice add from loading
    public function index()
    {
        $this->new_refund();
    }

    //Add new invoice
    public function new_refund()
    {

        $data = [
            // 'bank_list' => $this->Invoices->bank_list(),
            // 'payment_info' => $this->Invoices->payment_info(),
        ];
        $data['module'] = "dashboard";
        $data['page'] = "refund/add_refund_form";
        echo Modules::run('template/layout', $data);
    }

    public function manage_return()
    {
        $this->permission->check_label('manage_sale')->read()->redirect();
        $filter = array(
            'invoice_no' => $this->input->get('invoice_no', TRUE),
            'employee_id' => $this->input->get('employee_id', TRUE),
            'customer_id' => $this->input->get('customer_id', TRUE),
            'from_date' => $this->input->get('from_date', TRUE),
            'to_date' => $this->input->get('to_date', TRUE),
            'invoice_status' => $this->input->get('invoice_status', TRUE)
        );
        $config["base_url"] = base_url('dashboard/Crefund/manage_return');
        $config["total_rows"] = $this->Invoices->count_invoice_return_list($filter);
        $config["per_page"] = 20;
        $config["uri_segment"] = 4;
        $config["num_links"] = 5;
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $links = $this->pagination->create_links();
        $invoices_list = $this->Invoices->get_invoice_return_list($filter, $page, $config["per_page"]);
        if (!empty($invoices_list)) {
            foreach ($invoices_list as $k => $v) {
                $invoices_list[$k]['final_date'] = $this->occational->dateConvert($invoices_list[$k]['date']);
            }
            $i = 0;
            foreach ($invoices_list as $k => $v) {
                $i++;
                $invoices_list[$k]['sl'] = $i;
            }
        }
        $this->load->model(array('dashboard/Soft_settings', 'dashboard/Customers'));
        $currency_details = $this->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('manage_invoice'),
            'invoices_list' => $invoices_list,
            'currency' => $currency_details[0]['currency_icon'],
            'employees' => $this->empdropdown(),
            'position' => $currency_details[0]['currency_position'],
            'links' => $links
        );

        $data['module'] = "dashboard";
        $data['page'] = "refund/manage_return";
        echo Modules::run('template/layout', $data);
    }
    public function empdropdown()
    {
        $this->db->select('*');
        $this->db->from('employee_history');
        $query = $this->db->get();
        $data = $query->result();

        $list = array('' => 'Select One...');
        if (!empty($data)) {
            foreach ($data as $value) {
                $list[$value->id] = $value->first_name . " " . $value->last_name;
            }
        }
        return $list;
    }
    public function get_invoice_products()
    {
        $filter = array(
            'invoice_no' =>  $this->input->post('invoice_no', TRUE),
        );
        $sql = "select invoice_id from invoice where invoice='inv-" . $filter['invoice_no'] . "';";

        $result = $this->db->query($sql);
        $invoice_id = $result->result_array()[0]['invoice_id'];

        if (!empty($this->input->post('invoice_id', TRUE))) {
            $invoice_id = $this->input->post('invoice_id', TRUE);
        }

        $sql = "select v.variant_name,b.*,I.variant_id,I.invoice_id,(I.quantity - I.return_quantity) as quantity from invoice_details I join variant v on v.variant_id=I.variant_id join product_information b on b.product_id = I.product_id where quantity > 0 and I.invoice_id='" . $invoice_id . "';";
        $sql14 = $this->db->query($sql);
        $query = $sql14->result_array();
        echo json_encode($query);
    }

    public function get_product_variants()
    {
        $filter = array(
            'invoice_no' =>  $this->input->post('invoice_no', TRUE),
            'product_id' =>  $this->input->post('product_id', TRUE),
        );
        $sql = "select (I.quantity - I.return_quantity) as quantity,I.variant_id,v.variant_name from invoice_details I join variant v on v.variant_id = I.variant_id where I.product_id ='" . $filter['product_id'] . "' and I.invoice_id ='" . $filter['invoice_no'] . "';";
        $sql14 = $this->db->query($sql);
        $query = $sql14->result_array();
        echo json_encode($query);
    }

    public function new_return()
    {
        $find_active_fiscal_year = $this->db->select('*')->from('acc_fiscal_year')->where('status', 1)->get()->row();
        $filter = array(
            'invoice_no' =>  $this->input->post('invoice_no', TRUE),
            'invoice_id' =>  $this->input->post('invoice_id', TRUE),
            'product_id' =>  $this->input->post('product_id', TRUE),
            'variant_id' =>  $this->input->post('variant_id', TRUE),
            'status' =>  $this->input->post('status', TRUE),
            'quantity' =>  $this->input->post('quantity', TRUE),
            'payment_id' =>  $this->input->post('payment_id', TRUE),
            'price_type' =>  $this->input->post('price_type', TRUE),
        );

        if ($filter['quantity'] < 1) {
            $this->session->set_userdata(array('error_message' => display('failed_try_again')));
            $this->new_refund();
            return;
        }

        // $sql = "SELECT `customer_id` FROM `invoice` WHERE `invoice_id` ='" . $filter['invoice_id'] . "';";
        // $result = $this->db->query($sql);
        // $customer_id  = $result->result_array();
        // $customer_id  = $customer_id[0]['customer_id'];
        $customer_id = $this->input->post('customer_id', TRUE);

        $customer_head = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('customer_id', $customer_id)->get()->row();

        $return_invoice_id = generator(15);

        // echo "<pre>"; 
        // var_dump($customer_head, $filter);exit;
        //get customer headcode
        // for ($j = 0; $j < count($filter['product_id']); $j++) {
        $j = 0;
        if ($filter['quantity'] > 0) {
            // echo $filter['quantity'];
            // dd($filter['quantity']);
            // get invoice details record 
            $sql = "select I.* from invoice_details I where I.variant_id ='" . $filter['variant_id'] . "' and I.product_id ='" . $filter['product_id'] . "' and I.invoice_id ='" . $filter['invoice_id'] . "';";
            $result = $this->db->query($sql);
            $invoice_details  = $result->result_array();

            $this->db->select('*');
            $this->db->from('assembly_products');
            $this->db->where('parent_product_id', $filter['product_id']);
            $this->db->join('product_information', 'product_information.product_id = assembly_products.child_product_id');
            $query = $this->db->get();
            $product_list = $query->result();

            if ($filter['status'] == 0) //fit
            {
                if (!empty($product_list)) {
                    foreach ($product_list as $product) {
                        $sql = "update invoice_stock_tbl set quantity=quantity-" . $filter['quantity'] . " where store_id='" . $invoice_details[0]['store_id'] . "' and variant_id='" . $invoice_details[0]['variant_id'] . "' and product_id='" . $product['product_id'] . "';";
                        $result = $this->db->query($sql);
                    }
                } else {
                    // update stock
                    $sql = "update invoice_stock_tbl set quantity=quantity-" . $filter['quantity'] . " where store_id='" . $invoice_details[0]['store_id'] . "' and variant_id='" . $invoice_details[0]['variant_id'] . "' and product_id='" . $invoice_details[0]['product_id'] . "';";
                    $result = $this->db->query($sql);
                }
                // echo json_encode($sql);
            } else {
                if (!empty($product_list)) {
                    foreach ($product_list as $product) {
                        $sql = "INSERT INTO `product_return`(`invoice_id`, `product_id`, `variant_id`, `quantity`, `status`) VALUES ('" . $filter['invoice_id'] . "','" . $product['product_id'] . "','" . $filter['variant_id'] . "'," . $filter['quantity'] . "," . $filter['status'] . ")";
                        $result = $this->db->query($sql);
                    }
                } else {
                    $sql = "INSERT INTO `product_return`(`invoice_id`, `product_id`, `variant_id`, `quantity`, `status`) VALUES ('" . $filter['invoice_id'] . "','" . $filter['product_id'] . "','" . $filter['variant_id'] . "'," . $filter['quantity'] . "," . $filter['status'] . ")";
                    $result = $this->db->query($sql);
                }
            }

            //update invoice_details
            $sql = "update invoice_details set return_quantity= return_quantity+" . $filter['quantity'] . " where invoice_details_id='" . $invoice_details[0]['invoice_details_id'] . "';";
            $result = $this->db->query($sql);

            //invoice
            $sql = "select I.* from invoice I where I.invoice_id ='" . $filter['invoice_id'] . "';";
            $result = $this->db->query($sql);
            $invoice  = $result->result_array();

            //acc_transaction
            $receive_by = $this->session->userdata('user_id');
            //calc total price of returned qunty of product
            $without_cases_price = $this->db->select('price')
                ->from('product_information')
                ->where('product_id', $filter['product_id'])
                ->limit(1)
                ->get()->row();
            $with_cases_price = $this->db->select('product_price')
                ->from('pricing_types_product')
                ->where('product_id', $filter['product_id'])
                ->where('pri_type_id', 1)
                ->limit(1)
                ->get()->row();
            if ($filter['price_type'] == 1) {
                // with Cases
                $invoice_details[0]['total_price'] = $with_cases_price->product_price * $invoice_details[0]['quantity'];
            } else {
                // without cases
                $invoice_details[0]['total_price'] = $without_cases_price->price * $invoice_details[0]['quantity'];
            }
            $product_price = $invoice_details[0]['total_price'] / $invoice_details[0]['quantity'];
            $total_discount = $invoice_details[0]['discount'] * $filter['quantity'];
            $total_discount += $invoice_details[0]['invoice_discount'] * $filter['quantity'];
            $total_return = ((($invoice_details[0]['total_price'] / $invoice_details[0]['quantity']) * $filter['quantity'])) - $total_discount;
            $total_return_without_discount = $total_return + $total_discount;

            //total vat
            $i_vat = $this->db->select('tax_percentage')->from('tax_product_service')->where('product_id', $filter['product_id'])->get()->row();
            if (!empty($i_vat) && $invoice[0]['is_quotation'] == 0) {
                $tota_vat = ($product_price * ($i_vat->tax_percentage / 100)) * $filter['quantity'];
            } else {
                $tota_vat = 0;
            }
            $createdate = date('Y-m-d H:i:s');
            // total supplier price
            $cogs_price = $invoice_details[0]['supplier_rate'] * $filter['quantity'];
            $bank_return = $total_return + $tota_vat;
            //return installment
            if ($invoice[0]['is_installment']) {
                $total_installment_return = $total_return + $tota_vat;
                $temp = 0;
                $return = 0;
                $sql = "select * from invoice_installment where invoice_id ='" . $filter['invoice_id'] . "';";
                $result = $this->db->query($sql);
                $invoice_installment  = $result->result_array();
                $invoice_installment  = array_reverse($invoice_installment);

                for ($i = 0; $i < count($invoice_installment); $i++) {
                    $temp += $invoice_installment[$i]['amount'];


                    if ($invoice_installment[$i]['status']) {
                        $return += $invoice_installment[$i]['payment_amount'];
                    }

                    if ((int)$temp < (int)$total_installment_return) {
                        $sql = "delete from invoice_installment where id='" . $invoice_installment[$i]['id'] . "';";
                        $result = $this->db->query($sql);
                    }

                    if ((int)$temp == (int)$total_installment_return) {
                        $sql = "delete from invoice_installment where id='" . $invoice_installment[$i]['id'] . "';";
                        $result = $this->db->query($sql);
                        break;
                    }

                    if ((int)$temp > (int)$total_installment_return) {

                        $total_installment_return = $temp - $total_installment_return;
                        $return = $total_installment_return;
                        $sql = "update invoice_installment set amount='" . $total_installment_return . "' where id='" . $invoice_installment[$i]['id'] . "';";
                        $result = $this->db->query($sql);
                        break;
                    }
                }
                $bank_return = $return;
            }

            $customer_ledger_data = array(
                'transaction_id' => generator(15),
                'customer_id' => $customer_id,
                'date' => date('Y-m-d'),
                'amount' => $bank_return,
                'payment_type' => 1,
                'description' => 'ITP',
                'status' => 1
            );
            $this->db->insert('customer_ledger', $customer_ledger_data);

            //1st debit (Sales return for Showroom sales) with total price before discount
            $customer_credit = array(
                'fy_id' => $find_active_fiscal_year->id,
                'VNo' => 'SR-' . $return_invoice_id,
                'Vtype' => 'Sales return',
                'VDate' => $createdate,
                'COAID' => $customer_head->HeadCode, // account payable game 11
                'Narration' => 'Sales return" total with vat" debit by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
                'Debit' => 0,
                'Credit' => $total_return + $tota_vat,
                'IsPosted' => 1,
                'CreateBy' => $receive_by,
                'CreateDate' => $createdate,
                'IsAppove' => 1
            );
            //7th paid_amount depit if full paid 
            // $customer_depit = array(
            //     'fy_id' => $find_active_fiscal_year->id,
            //     'VNo' => 'Inv-' . $filter['invoice_id'],
            //     'Vtype' => 'Sales',
            //     'VDate' => $createdate,
            //     'COAID' => $customer_head->HeadCode,
            //     'Narration' => 'Sales "paid_amount" depit by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
            //     'Debit' => $total_return+$tota_vat,
            //     'Credit' => 0,
            //     'IsPosted' => 1,
            //     'CreateBy' => $receive_by,
            //     'CreateDate' => $createdate,
            //     //'IsAppove' => 0
            //     'IsAppove' => 1
            // );
            $this->db->insert('acc_transaction', $customer_credit);
            // 2nd Allowed Discount credit
            $allowed_discount_credit = array(
                'fy_id' => $find_active_fiscal_year->id,
                'VNo' => 'SR-' . $return_invoice_id,
                'Vtype' => 'Sales return',
                'VDate' => $createdate,
                'COAID' => 4114,
                'Narration' => 'Sales return "total discount" debit by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
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
                'VNo' => 'SR-' . $return_invoice_id,
                'Vtype' => 'Sales return',
                'VDate' => $createdate,
                'COAID' => 5121, // account payable game 11
                'Narration' => 'Sales return for Showroom sales "total price before discount" debited by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
                'Debit' => $total_return_without_discount,
                'Credit' => 0,
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
                'VNo' => 'SR-' . $return_invoice_id,
                'Vtype' => 'Sales return',
                'VDate' => $createdate,
                'COAID' => 2114, // account payable game 11
                'Narration' => 'Sales return "total vat" debited by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
                'Debit' => $tota_vat,
                'Credit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $receive_by,
                'CreateDate' => $createdate,
                'IsAppove' => 1
            );
            $this->db->insert('acc_transaction', $vat_depit);

            //5th cost of goods sold Credit
            $cogs_credit = array(
                'fy_id' => $find_active_fiscal_year->id,
                'VNo' => 'SR-' . $return_invoice_id,
                'Vtype' => 'Sales return',
                'VDate' => $createdate,
                'COAID' => 4111,
                'Narration' => 'Sales return inventory "COGS" debited by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
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
                'VNo' => 'SR-' . $return_invoice_id,
                'Vtype' => 'Sales return',
                'VDate' => $createdate,
                'COAID' => 1141,
                'Narration' => 'Sales return "COGS" debited in Main Warehouse by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
                'Debit' => $cogs_price,
                'Credit' => 0, //supplier price asbe
                'IsPosted' => 1,
                'CreateBy' => $receive_by,
                'CreateDate' => $createdate,
                //'IsAppove' => 0
                'IsAppove' => 1
            );
            $this->db->insert('acc_transaction', $cogs_main_warehouse_depit);

            if ($filter['payment_id'] != "" && $this->input->post('payment_type', TRUE) == 1) {
                $payment_head = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('HeadCode', $filter['payment_id'])->get()->row();
                $bank_credit = array(
                    'fy_id' => $find_active_fiscal_year->id,
                    'VNo' => 'SR-' . $return_invoice_id,
                    'Vtype' => 'Sales',
                    'VDate' => $createdate,
                    'COAID' => $payment_head->HeadCode,
                    'Narration' => 'Sales "return_amount" credited by cash/bank id: ' . $payment_head->HeadName . '(' . $filter['payment_id'] . ')',
                    'Debit' => 0,
                    'Credit' => $bank_return,
                    'IsPosted' => 1,
                    'CreateBy' => $receive_by,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1
                );
                $this->db->insert('acc_transaction', $bank_credit);
            }
            $invoice_return = array(
                'return_invoice_id' => $return_invoice_id,
                'invoice_id'        => $filter['invoice_id'],
                'return_quantity'   => $filter['quantity'],
                'product_id'        => $filter['product_id'],
                'rate'              => $product_price,
                'customer_id'       => $customer_id,
                'employee_id'       => $invoice[0]['employee_id'],
                'total_discount'    => $total_discount,
                'total_return'      => $total_return + $tota_vat + $total_discount,
            );
            $this->db->insert('invoice_return', $invoice_return);
        }

        // $returninvoice_id=$this->db->insert_id();
        // update invoice paid amount
        $invoiceData = $this->db->select('*')->from('invoice')->where('invoice_id', $filter['invoice_id'])->limit(1)->get()->row();
        $this->db->set('paid_amount', $invoiceData->paid_amount + $total_return + $tota_vat)
            ->set('due_amount', $invoiceData->due_amount - $total_return + $tota_vat)
            ->where('invoice_id', $filter['invoice_id'])
            ->update('invoice');
        // }


        return redirect(base_url('dashboard/Crefund/return_invoice/' . $return_invoice_id));

        // redirect('dashboard/Crefund/new_refund' . $filter['invoice_id']);
    }

    public function return_invoice($returninvoice_id)
    {
        //find previous invoice history and REDUCE the stock
        $invoice_return = $this->db->select('*')->from('invoice_return')->where('return_invoice_id', $returninvoice_id)->get()->result_array();
        $sql = "SELECT * FROM `employee_history` where `id`='" . $invoice_return[0]['employee_id'] . "' ;";
        $result = $this->db->query($sql);
        $user  = $result->result_array();
        foreach ($invoice_return as $inv_return) {
            $sql = "SELECT * FROM `customer_information` where `customer_id`='" . $inv_return['customer_id'] . "' ;";
            $result = $this->db->query($sql);
            $customer[]  = $result->result_array()[0];

            $sql = "SELECT * FROM `product_information` where `product_id`='" . $inv_return['product_id'] . "' ;";
            $result = $this->db->query($sql);
            $product[]  = $result->result_array()[0];



            $sql = "SELECT * FROM `pricing_types_product` where `product_id`='" . $inv_return['product_id'] . "' and `pri_type_id` ='2' ;";
            $result = $this->db->query($sql);
            $customer_price[] = $result->result_array()[0];
        }

        $data =
            [
                'sl'            =>  $invoice_return[0]['id'],
                'customer'      =>  $customer[0],
                'createdate'    =>  $invoice_return[0]['created_at'],
                'receive_by'    =>  $user[0]['first_name'] . " " . $user[0]['last_name'],
                'product'       =>  $product,
                'customer_price' =>  $customer_price,
                'invoice_return' =>  $invoice_return
            ];

        $data['module'] = "dashboard";
        $data['page'] = "refund/returninvoice_html";
        echo Modules::run('template/layout', $data);
    }
    public function check_stock($store_id = null, $product_id = null, $variant = null, $variant_color = null)
    {
        $this->db->select('stock_id,quantity');
        $this->db->from('invoice_stock_tbl');
        if (!empty($store_id)) {
            $this->db->where('store_id', $store_id);
        }
        if (!empty($product_id)) {
            $this->db->where('product_id', $product_id);
        }
        if (!empty($variant)) {
            $this->db->where('variant_id', $variant);
        }
        if (!empty($variant_color)) {
            $this->db->where('variant_color', $variant_color);
        }
        $query = $this->db->get();
        return $query->row();
    }

    public function change_stock($invoice_id)
    {
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

    public function update_database()
    {
        $sql = "INSERT INTO customer_ledger (`transaction_id`,`receipt_no`,`customer_id`,`date`,`amount` ,`payment_type` ,`description` ,`status` ) SELECT '" . generator(15) . "','" . generator(15) . "',acc0.customer_id,A.VDate,A.Credit,1,'ITP',1 from acc_coa acc0 join acc_transaction A on SUBSTR(A.VNo, 4)=acc0.HeadCode where acc0.`PHeadCode` LIKE '1131' and acc0.HeadCode in (SELECT SUBSTR(VNo, 4) AS HeadCode from acc_transaction acc1 WHERE COAID =3 and HeadCode not in (SELECT COAID as HeadCode from acc_transaction acc2 where COAID !=3 and Narration  LIKE 'Opening balance%'));";
        $result = $this->db->query($sql);

        dd($this->db->affectedRows);
    }

    public function return_report()
    {
        $this->permission->check_label('customer_balance_report')->read()->redirect();
        $from_date = $this->input->post('from_date', TRUE);
        $to_date  = $this->input->post('to_date', TRUE);
        $status  = $this->input->post('status', TRUE);
        $content  = $this->lproduct->return_product_report($from_date, $to_date, $status);
        $this->template_lib->full_admin_html_view($content);
    }

    public function get_invoice_by_customer()
    {
        $customer_id  = $this->input->post('customer_id', TRUE);

        $result = $this->Invoices->get_invoice_list(['customer_id' => $customer_id], 0, 1000);

        // var_dump($result);exit;

        // header('content-type: application/json');
        echo json_encode($result);
    }

    public function get_invoice_by_product()
    {
        $customer_id = $this->input->post('customer_id', true);
        $product_id = $this->input->post('product_id', true);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_id[]', display('product_name'), 'required');
        $this->form_validation->set_rules('customer_id', display('customer_name'), 'required');

        $product_id = is_array($product_id) ? $product_id[0] : $product_id;

        if ($this->form_validation->run() == false) {
            $this->session->set_userdata(array('error_message' => display('failed_try_again')));
            $this->new_refund();
            return;
        }

        $invoices = $this->db->select('a.*,b.*, p.*, b.invoice_discount as item_invoice_discount, (b.quantity - b.return_quantity) as ava_quantity')
            ->from('invoice a')
            ->join('invoice_details b', 'b.invoice_id = a.invoice_id', 'left')
            ->join('product_information p', 'p.product_id = b.product_id', 'left')
            ->where('b.product_id', $product_id)
            ->where('a.customer_id', $customer_id)
            ->order_by('a.invoice', 'desc')
            ->get()
            ->result();

        // echo "<pre>";var_dump($invoices);exit;

        // var_dump($this->input->post('variant_id', true));exit;

        $data = [
            'invoices' => $invoices,
            'customer_id' => $customer_id,
            'product_id' => $product_id,
            'customer_name' => $this->input->post('customer_name', true),
            'product_name' => $this->input->post('product_name', true),
            'variant_id' => is_array($this->input->post('variant_id', true)) ? $this->input->post('variant_id', true)[0] : $this->input->post('variant_id', true),
        ];
        $data['module'] = "dashboard";
        $data['page'] = "refund/add_refund_form";
        echo Modules::run('template/layout', $data);
    }
}
