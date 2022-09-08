<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lorder {

    //Order Add Form
    public function order_add_form_old()
    {
        $CI =& get_instance();
        $CI->load->model('dashboard/Orders');
        $CI->load->model('dashboard/Stores');
        $CI->load->model('dashboard/Variants');

        $CI->load->model('dashboard/Customers');
        $CI->load->model('dashboard/Shipping_methods');

        $store_list 		= $CI->Stores->store_list();
        $variant_list 		= $CI->Variants->variant_list();
        $terminal_list    	= $CI->Orders->terminal_list();

        $customer =$CI->Customers->customer_list();
        $shipping_methods 	= $CI->Shipping_methods->shipping_method_list();
        

        $data = array(
            'title' 		=> display('new_order'),
            'store_list' 	=> $store_list,
            'variant_list' 	=> $variant_list,
            'terminal_list' => $terminal_list,
            'customer' 		=> $customer[0],
            'shipping_methods'=>$shipping_methods
        );
        $invoiceForm = $CI->parser->parse('dashboard/invoice/add_invoice_form',$data,true);
		// return $invoiceForm;
        $orderForm = $CI->parser->parse('dashboard/order/add_order_form',$data,true);
        return $orderForm;
    }

    // order add form
	public function order_add_form()
	{
		$CI =& get_instance();
		$CI->load->model('dashboard/Invoices');
		$CI->load->model('dashboard/Stores');
		$CI->load->model('dashboard/Variants');
		$CI->load->model('dashboard/Customers');
		$CI->load->model('dashboard/Shipping_methods');

		$store_list 	    = $CI->Stores->store_list();
		$variant_list 	    = $CI->Variants->variant_list();
		$shipping_methods 	= $CI->Shipping_methods->shipping_method_list();
		// $bank_lists 	    = $CI->Invoices->bank_lists();

		$customer =$CI->Customers->customer_list();
	
		$data = array(
			'title' 		=> display('new_order'),
			'store_list' 	=> $store_list,
			'variant_list' 	=> $variant_list,
			'customer' 		=> $customer[0],
            'shipping_methods'=>$shipping_methods
			);
		$invoiceForm = $CI->parser->parse('dashboard/invoice/add_invoice_form',$data,true);
		return $invoiceForm;
	}

    //Retrieve  order List
    public function order_list($filter=[], $page, $per_page, $links = false)
    {
        $CI =& get_instance();
        $CI->load->model('dashboard/Orders');
        $CI->load->model('dashboard/Soft_settings');
        $CI->load->library('dashboard/occational');

        $orders_list = $CI->Orders->order_list($filter, $page, $per_page);
        if(!empty($orders_list)){
            foreach($orders_list as $k=>$v){
                $orders_list[$k]['final_date'] = $CI->occational->dateConvert($orders_list[$k]['date']);
            }
            $i=0;
            foreach($orders_list as $k=>$v){
                $i++;
                $orders_list[$k]['sl']=$i;
            }
        }
        $currency_details=$CI->Soft_settings->retrieve_currency_info();
        $data = array(
            'title'      => display('manage_order'),
            'orders_list'=> $orders_list,
            'links'      => $links,
            'currency'   => $currency_details[0]['currency_icon'],
            'position'   => $currency_details[0]['currency_position'],
        );
        $orderList = $CI->parser->parse('dashboard/order/order',$data,true);
        return $orderList;
    }
    //Insert order
    public function insert_order($data)
    {
        $CI =& get_instance();
        $CI->load->model('Orders');
        $CI->Orders->order_entry($data);
        return true;
    }
    //order Edit Data
    public function order_edit_data_older($order_id)
    {
        $CI =& get_instance();
        $CI->load->model('dashboard/Orders');
        $CI->load->model('dashboard/Stores');
        $order_detail 	  = $CI->Orders->retrieve_order_editdata($order_id);
        $store_id 		  = $order_detail[0]['store_id'];
        $store_list 	  = $CI->Stores->store_list();
        $store_list_selected = $CI->Stores->store_list_selected($store_id);
        $terminal_list    = $CI->Orders->terminal_list();

        $i=0;
        foreach($order_detail as $k=>$v){$i++;
            $order_detail[$k]['sl']=$i;
        }

        $data=array(
            'title'				=> 	display('order_update'),
            'order_id'			=>	$order_detail[0]['order_id'],
            'customer_id'		=>	$order_detail[0]['customer_id'],
            'store_id'			=>	$order_detail[0]['store_id'],
            'customer_name'		=>	$order_detail[0]['customer_name'],
            'date'				=>	$order_detail[0]['date'],
            'total_amount'		=>	$order_detail[0]['total_amount'],
            'paid_amount'		=>	$order_detail[0]['paid_amount'],
            'due_amount'		=>	$order_detail[0]['due_amount'],
            'total_discount'	=>	$order_detail[0]['total_discount'],
            'order_discount'	=>	$order_detail[0]['order_discount'],
            'service_charge'	=>	$order_detail[0]['service_charge'],
            'details'			=>	$order_detail[0]['details'],
            'order'				=>	$order_detail[0]['order'],
            'status'			=>	$order_detail[0]['status'],
            'order_all_data'	=>	$order_detail,
            'store_list'		=>	$store_list,
            'store_list_selected'=>	$store_list_selected,
            'terminal_list'     =>	$terminal_list,
        );

        $chapterList = $CI->parser->parse('dashboard/order/edit_order_form',$data,true);
        return $chapterList;
    }

    //Order Edit Data
	public function order_edit_data_old($order_id)
	{
		$CI =& get_instance();
		$CI->load->model('dashboard/Invoices');
        $CI->load->model('dashboard/Orders');
		$CI->load->model('dashboard/Stores');
		$CI->load->model('dashboard/Shipping_methods');

		$invoice_detail = $CI->Orders->retrieve_order_editdata($order_id);
        $shipping_methods = $CI->Shipping_methods->shipping_method_list();
        $store_id = $invoice_detail[0]['store_id'];
        $store_list = $CI->Stores->store_list();
        $store_list_selected = $CI->Stores->store_list_selected($store_id);

        $i = 0;
        foreach ($invoice_detail as $k => $v) {
            $i++;
            $invoice_detail[$k]['sl'] = $i;
        }

        $data = array(
            'title' => display('invoice_edit'),
            'invoice_id' => $invoice_detail[0]['invoice_id'],
            'customer_id' => $invoice_detail[0]['customer_id'],
            'store_id' => $invoice_detail[0]['store_id'],
            'invoice' => $invoice_detail[0]['invoice'],
            'customer_name' => $invoice_detail[0]['customer_name'],
            'date' => $invoice_detail[0]['date'],
            'total_amount' => $invoice_detail[0]['total_amount'],
            'paid_amount' => $invoice_detail[0]['paid_amount'],
            'due_amount' => $invoice_detail[0]['due_amount'],
            'total_discount' => $invoice_detail[0]['total_discount'],
            'invoice_discount' => $invoice_detail[0]['invoice_discount'],
            'service_charge' => $invoice_detail[0]['service_charge'],
            'shipping_charge' => $invoice_detail[0]['shipping_charge'],
            'shipping_method_id' => $invoice_detail[0]['shipping_method'],
            'invoice_details' => $invoice_detail[0]['invoice_details'],
            'invoice_status' => $invoice_detail[0]['invoice_status'],
            'invoice_all_data' => $invoice_detail,
            'store_list' => $store_list,
            'store_list_selected' => $store_list_selected,
            'shipping_methods' => $shipping_methods,
            'is_order' => true,
        );

		$chapterList = $CI->parser->parse('dashboard/order/edit_order_invoice_form',$data,true);
		return $chapterList;
	}

    //quotation Edit Data
	public function order_edit_data($order_id)
	{
		$CI =& get_instance();
		$CI->load->model('dashboard/Invoices');
        $CI->load->model('dashboard/Orders');
		$CI->load->model('dashboard/Stores');
		$CI->load->model('dashboard/Shipping_methods');
		$order_detail = $CI->Orders->retrieve_order_editdata($order_id);
		$store_list 	  = $CI->Stores->store_list();
		$terminal_list    = $CI->Orders->terminal_list();
        $employee_list = $this->employee_list();
        $all_pri_type = $CI->Invoices->select_all_pri_type();
        $bank_list = $CI->Invoices->bank_list();
        $payment_info = $CI->Invoices->payment_info();

		$i=0;
		foreach($order_detail as $k=>$v){$i++;
		   $order_detail[$k]['sl']=$i;
		}

        // $tax = 

		$data=array(
			'title'				=> 	display('order_update'),
			'order_id'		=>	$order_detail[0]['invoice_id'],
			'customer_id'		=>	$order_detail[0]['customer_id'],
			'employee_id'		=>	$order_detail[0]['employee_id'],
			'store_id'			=>	$order_detail[0]['store_id'],
			'customer_name'		=>	$order_detail[0]['customer_name'],
			'date'				=>	$order_detail[0]['date'],
			'expire_date'		=>	$order_detail[0]['expire_date'],
			'order'			=>	$order_detail[0]['invoice'],
			'total_amount'		=>	$order_detail[0]['total_amount'],
			'paid_amount'		=>	$order_detail[0]['paid_amount'],
			'due_amount'		=>	$order_detail[0]['due_amount'],
			'total_discount'	=>	$order_detail[0]['total_discount'],
			'order_discount'=>	$order_detail[0]['invoice_discount'],
			'service_charge'	=>	$order_detail[0]['service_charge'],
			'details'			=>	$order_detail[0]['details'],
			'status'			=>	$order_detail[0]['status'],
			'is_quotation'		=>	$order_detail[0]['is_quotation'],
			'order_all_data'=>	$order_detail,
			'employee_list'		=>	$employee_list,
			'store_list'		=>	$store_list,
			'terminal_list'     =>	$terminal_list,
			'all_pri_type'     =>	$all_pri_type,
			'bank_list'        =>	$bank_list,
			'payment_info'     =>	$payment_info,
			);
		$chapterList = $CI->parser->parse('dashboard/order/edit_order_form_updated',$data,true);
		return $chapterList;
	}

    public function employee_list()
    {
        $CI =& get_instance();
        $CI->db->select('*');
        $CI->db->from('employee_history');
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Order Html Data
    public function order_html_data_old($order_id)
    {
        $CI =& get_instance();
        $CI->load->model('dashboard/Orders');
        $CI->load->model('dashboard/Soft_settings');
        $CI->load->library('dashboard/occational');
        $CI->load->library('Pdfgenerator');
        $order_detail = $CI->Orders->retrieve_order_html_data($order_id);
        $subTotal_quantity 	= 0;
        $subTotal_cartoon 	= 0;
        $subTotal_discount 	= 0;

        if(!empty($order_detail)){
            foreach($order_detail as $k=>$v){
                $order_detail[$k]['final_date'] = $CI->occational->dateConvert($order_detail[$k]['date']);
                $subTotal_quantity = $subTotal_quantity+$order_detail[$k]['quantity'];
            }
            $i=0;
            foreach($order_detail as $k=>$v){$i++;
                $order_detail[$k]['sl']=$i;
            }
        }

        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $company_info = $CI->Orders->retrieve_company();
        $data=array(
            'title'				=>	display('order_details'),
            'order_id'			=>	$order_detail[0]['order_id'],
            'order_no'			=>	$order_detail[0]['order'],
            'customer_address'	=>	$order_detail[0]['customer_short_address'],
            'customer_name'		=>	$order_detail[0]['customer_name'],
            'customer_mobile'	=>	$order_detail[0]['customer_mobile'],
            'customer_email'	=>	$order_detail[0]['customer_email'],
            'final_date'		=>	$order_detail[0]['final_date'],
            'total_amount'		=>	$order_detail[0]['total_amount'],
            'order_discount' 	=>	$order_detail[0]['order_discount'],
            'service_charge' 	=>	$order_detail[0]['service_charge'],
            'paid_amount'		=>	$order_detail[0]['paid_amount'],
            'due_amount'		=>	$order_detail[0]['due_amount'],
            'details'			=>	$order_detail[0]['details'],
            'subTotal_quantity'	=>	$subTotal_quantity,
            'order_all_data' 	=>	$order_detail,
            'company_info'		=>	$company_info,
            'currency' 			=> 	$currency_details[0]['currency_icon'],
            'position' 			=> 	$currency_details[0]['currency_position'],
        );

        $chapterList = $CI->parser->parse('dashboard/order/order_pdf',$data,true);

        $CI->load->library('pdfgenerator');
        $file_path = $CI->pdfgenerator->generate_order($order_id, $chapterList);

        //File path save to database
        $CI->db->set('file_path',base_url($file_path));
        $CI->db->where('order_id',$order_id);
        $CI->db->update('order');

        $send_email = '';
        if (!empty($data['customer_email'])) {
            $send_email = $this->setmail($data['customer_email'],$file_path);
        }

        if ($send_email != null) {
            return true;
        }else{
            return false;
        }
    }

    //order html Data
	public function order_html_data($order_id)
	{
		$CI =& get_instance();
		$CI->load->model('dashboard/Invoices');
		$CI->load->model('dashboard/Soft_settings');
		$CI->load->library('dashboard/occational');
		$CI->load->model('dashboard/Shipping_methods');
		$CI->load->model('hrm/Hrm_model');
		$CI->load->model('dashboard/Products');
        $CI->load->model('dashboard/Orders');
		
		$invoice_detail = $CI->Orders->retrieve_order_html_data($order_id);
		$order_no=$CI->db->select('b.order as order_no')->from('invoice a')->where('a.order_id',$invoice_detail[0]['order_id'])->join('order b','a.order_id = b.order_id','left')->get()->result();
		$quotation_no = $CI->db->select('q.quotation as quotation_no')->from('invoice a')->where('a.quotation_id',$invoice_detail[0]['quotation_id'])->join('quotation q','q.quotation_id = a.quotation_id','left')->get()->result();

		$cardpayments=$CI->Invoices->get_invoice_card_payments($order_id);
        $shipping_method  =$CI->Shipping_methods->shipping_method_search_item($invoice_detail[0]['shipping_method']);
		$subTotal_quantity=0;
		$subTotal_cartoon =0;
		$subTotal_discount=0;
        $isTaxed = 1;
        if ($invoice_detail[0]['is_quotation'] > 0){
            $isTaxed = 0;
        }
		if(!empty($invoice_detail)){
			foreach($invoice_detail as $k=>$v){
				$invoice_detail[$k]['final_date']=$CI->occational->dateConvert($invoice_detail[$k]['date']);
				$subTotal_quantity=$subTotal_quantity+$invoice_detail[$k]['quantity'];
			}
			$i=0;
			$products = [];
			foreach($invoice_detail as $k=>$v){
				$i++;
			    $invoice_detail[$k]['sl']=$i;
				$invoice_detail[$k]['product_price'] = ($CI->Products->get_product_model([
					'product_model' => $invoice_detail[0]['product_model'],
					'product_name' => $invoice_detail[0]['product_name'],
				]))->price;
			}
		}

		$currency_details=$CI->Soft_settings->retrieve_currency_info();
		$company_info 	 =$CI->Invoices->retrieve_company();
		
		$created_at      =explode(' ', $invoice_detail[0]['date_time']);
		$invoice_time=@$created_at[1];


		$data=array(
			'title'			   =>display('invoice_details'),
			'invoice_id'	   =>$invoice_detail[0]['invoice_id'],
			'invoice_no'	   =>$invoice_detail[0]['invoice'],
			'customer_id'      => $invoice_detail[0]['customer_id'],
			'customer_name'	   =>$invoice_detail[0]['customer_name'],
			'customer_mobile'  =>$invoice_detail[0]['customer_mobile'],
			'customer_email'   =>$invoice_detail[0]['customer_email'],
			'store_id'	       =>(empty($invoice_detail[0]['store_id'])?'':$invoice_detail[0]['store_id']),
			'vat_no'	       =>$invoice_detail[0]['vat_no'],
			'cr_no'	           =>$invoice_detail[0]['cr_no'],
			'customer_address' =>$invoice_detail[0]['customer_address_1'],
			'final_date'	   =>$invoice_detail[0]['final_date'],
			'invoice_time'	   =>$invoice_time,
			'total_amount'	   =>$invoice_detail[0]['total_amount'],
			'total_discount'   =>$invoice_detail[0]['total_discount'],
			'invoice_discount' =>$invoice_detail[0]['invoice_discount'],
			'service_charge'   =>$invoice_detail[0]['service_charge'],
			'shipping_charge'  =>$invoice_detail[0]['shipping_charge'],
			'shipping_method'  =>@$shipping_method[0]['method_name'],
			'paid_amount'	   =>$invoice_detail[0]['paid_amount'],
			'due_amount'	   =>$invoice_detail[0]['due_amount'],
			'invoice_details'  =>$invoice_detail[0]['invoice_details'],
			'subTotal_quantity'=>$subTotal_quantity,
			'invoice_all_data' =>$invoice_detail,
			'isTaxed'          =>$isTaxed,
			'order_no'         =>$order_no,
			'quotation_no'     =>$quotation_no,
			'company_info'	   =>$company_info,
			'currency' 		   =>$currency_details[0]['currency_icon'],
			'position' 		   =>$currency_details[0]['currency_position'],
            'ship_customer_short_address'=>$invoice_detail[0]['ship_customer_short_address'],
            'ship_customer_name' =>$invoice_detail[0]['ship_customer_name'],
            'ship_customer_mobile'=>$invoice_detail[0]['ship_customer_mobile'],
            'ship_customer_email'=>$invoice_detail[0]['ship_customer_email'],
            'cardpayments'	     =>$cardpayments,
			);
		$data['Soft_settings'] = $CI->Soft_settings->retrieve_setting_editdata();
		$emp_name = $emp_id = null;
		if (!empty($invoice_detail) && isset($invoice_detail[0]['employee_id'])) {
			$emp = $CI->Hrm_model->single_employee_data($invoice_detail[0]['employee_id']);
			if (null !== $emp) {
				$emp_name = $emp->first_name . ' ' . $emp->last_name;
				$emp_id = $emp->id;
			}
		}
		
		$data['emp_name'] = $emp_name;
		$data['emp_id'] = $emp_id;
        
        $data['is_order'] = true;
		
		$chapterList = $CI->parser->parse('dashboard/invoice/invoice_html',$data,true);
		return $chapterList;
	}

    //Send Customer Email with invoice
    public function setmail($email,$file_path)
    {

        $CI =& get_instance();
        $CI->load->model('Soft_settings');
        $setting_detail = $CI->Soft_settings->retrieve_email_editdata();

        $server_status = serverAliveOrNot($setting_detail[0]['smtp_host'], $setting_detail[0]['smtp_port']);
        if(!$server_status){
            $CI->session->set_userdata(array('error_message'=> display('email_not_send')));
            return true;
        }

        $subject = display("order_information");
        $message = display("order_info_details").'<br>'.base_url();

        $config = Array(
            'protocol' 		=> $setting_detail[0]['protocol'],
            'smtp_host' 	=> $setting_detail[0]['smtp_host'],
            'smtp_port' 	=> $setting_detail[0]['smtp_port'],
            'smtp_user' 	=> $setting_detail[0]['sender_email'],
            'smtp_pass' 	=> $setting_detail[0]['password'],
            'mailtype' 		=> $setting_detail[0]['mailtype'],
            'charset' 		=> 'utf-8'
        );

        $CI->load->library('email');
        $CI->email->initialize($config);

        $CI->email->set_newline("\r\n");
        $CI->email->from($setting_detail[0]['sender_email']);
        $CI->email->to($email);
        $CI->email->subject($subject);
        $CI->email->message($message);
        $CI->email->attach($file_path);

        $check_email = $this->test_input($email);
        if (filter_var($check_email, FILTER_VALIDATE_EMAIL)) {
            if($CI->email->send())
            {
                $CI->session->set_userdata(array('message'=>display('email_send_to_customer')));
                return true;
            }else{
                $CI->session->set_userdata(array('error_message'=> display('email_not_send')));
                redirect(base_url('dashboard/Corder/manage_order'));
            }
        }else{
            $CI->session->set_userdata(array('message'=>display('successfully_added')));
            redirect(base_url('dashboard/Corder/manage_order'));
        }
    }

    //Email testing for email
    public function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    //Order Details Data
    public function order_details_data($order_id)
    {
        $CI =& get_instance();
        $CI->load->model('dashboard/Orders');
        $CI->load->model('dashboard/Soft_settings');
        $CI->load->library('dashboard/occational');
        $CI->load->library('Pdfgenerator');
        $order_detail = $CI->Orders->retrieve_order_html_data($order_id);

        $payinfo = $CI->Orders->get_order_payinfo($order_id);

        $subTotal_quantity 	= 0;
        $subTotal_cartoon 	= 0;
        $subTotal_discount 	= 0;

        if(!empty($order_detail)){
            foreach($order_detail as $k=>$v){
                $order_detail[$k]['final_date'] = $CI->occational->dateConvert($order_detail[$k]['date']);
                $subTotal_quantity = $subTotal_quantity+$order_detail[$k]['quantity'];
            }
            $i=0;
            foreach($order_detail as $k=>$v){$i++;
                $order_detail[$k]['sl']=$i;
            }
        }
        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $company_info = $CI->Orders->retrieve_company();
        $data=array(
            'title'				  =>display('order_details'),
            'order_id'			  =>$order_detail[0]['order_id'],
            'order_no'			  =>$order_detail[0]['order'],
            'customer_address'	  =>$order_detail[0]['customer_short_address'],
            'ship_customer_short_address'=>$order_detail[0]['ship_customer_short_address'],
            'ship_customer_name'  =>$order_detail[0]['ship_customer_name'],
            'customer_name'		  =>$order_detail[0]['customer_name'],
            'customer_mobile'	  =>$order_detail[0]['customer_mobile'],
            'ship_customer_mobile'=>$order_detail[0]['ship_customer_mobile'],
            'customer_email'	  =>$order_detail[0]['customer_email'],
            'store_id'            =>$order_detail[0]['store_id'],
            'vat_no'              =>$order_detail[0]['vat_no'],
            'ship_customer_email' =>$order_detail[0]['ship_customer_email'],
            'final_date'		  =>$order_detail[0]['final_date'],
            'total_amount'		  =>$order_detail[0]['total_amount'],
            'order_discount' 	  =>$order_detail[0]['order_discount'],
            'service_charge' 	  =>$order_detail[0]['service_charge'],
            'paid_amount'		  =>$order_detail[0]['paid_amount'],
            'due_amount'		  =>$order_detail[0]['due_amount'],
            'details'			  =>$order_detail[0]['details'],
            'subTotal_quantity'	  =>$subTotal_quantity,
            'order_all_data' 	  =>$order_detail,
            'company_info'		  =>$company_info,
            'currency' 			  =>$currency_details[0]['currency_icon'],
            'position' 			  =>$currency_details[0]['currency_position'],
            'payinfo'             =>$payinfo
        );

        $chapterList = $CI->parser->parse('dashboard/order/order_html',$data,true);
        return $chapterList;
    }

    //POS order html Data
    public function pos_order_html_data($order_id)
    {
        $CI =& get_instance();
        $CI->load->model('dashboard/Orders');
        $CI->load->model('dashboard/Soft_settings');
        $CI->load->library('dashboard/occational');
        $order_detail = $CI->Orders->retrieve_order_html_data($order_id);
        $subTotal_quantity = 0;
        $subTotal_cartoon = 0;
        $subTotal_discount = 0;

        if(!empty($order_detail)){
            foreach($order_detail as $k=>$v){
                $order_detail[$k]['final_date'] = $CI->occational->dateConvert($order_detail[$k]['date']);
                $subTotal_quantity = $subTotal_quantity+$order_detail[$k]['quantity'];
            }
            $i=0;
            foreach($order_detail as $k=>$v){$i++;
                $order_detail[$k]['sl']=$i;
            }
        }

        $currency_details = $CI->Soft_settings->retrieve_currency_info();
        $company_info = $CI->Orders->retrieve_company();
        $data=array(
            'title'				=> display('order_detail'),
            'order_id'			=>	$order_detail[0]['order_id'],
            'order_no'			=>	$order_detail[0]['order'],
            'customer_name'		=>	$order_detail[0]['customer_name'],
            'customer_address'	=>	$order_detail[0]['customer_short_address'],
            'customer_mobile'	=>	$order_detail[0]['customer_mobile'],
            'customer_email'	=>	$order_detail[0]['customer_email'],
            'final_date'		=>	$order_detail[0]['final_date'],
            'total_amount'		=>	$order_detail[0]['total_amount'],
            'subTotal_discount'	=>	$order_detail[0]['total_discount'],
            'paid_amount'		=>	$order_detail[0]['paid_amount'],
            'due_amount'		=>	$order_detail[0]['due_amount'],
            'subTotal_quantity'	=>	$subTotal_quantity,
            'order_all_data' 	=>	$order_detail,
            'company_info'		=>	$company_info,
            'currency' 			=> $currency_details[0]['currency_icon'],
            'position' 			=> $currency_details[0]['currency_position'],
        );
        $chapterList = $CI->parser->parse('dashboard/order/pos_order_html',$data,true);
        return $chapterList;
    }

    //order Edit Data
    public function create_invoice_data($order_id)
    {
        $CI =& get_instance();
        $CI->load->model('dashboard/Orders');
        $CI->load->model('dashboard/Stores');
        $order_detail     = $CI->Orders->retrieve_order_editdata($order_id);
        $store_id         = $order_detail[0]['store_id'];
        $store_list       = $CI->Stores->store_list();
        $store_list_selected = $CI->Stores->store_list_selected($store_id);
        $terminal_list    = $CI->Orders->terminal_list();

        $i=0;
        foreach($order_detail as $k=>$v){$i++;
            $order_detail[$k]['sl']=$i;
        }
        $data=array(
            'title'             =>  display('create_invoice'),
            'order_id'          =>  $order_detail[0]['invoice_id'],
            'customer_id'       =>  $order_detail[0]['customer_id'],
            'store_id'          =>  $order_detail[0]['store_id'],
            'customer_name'     =>  $order_detail[0]['customer_name'],
            'date'              =>  $order_detail[0]['date'],
            'order'             =>  $order_detail[0]['invoice'],
            'order_all_data'    =>  $order_detail,
            'store_list'        =>  $store_list,
            'store_list_selected'=> $store_list_selected,
            'terminal_list'     =>  $terminal_list,
        );

        $chapterList = $CI->parser->parse('dashboard/order/create_invoice_form',$data,true);
        return $chapterList;
    }

    public function create_invoice_data_updated($order_id)
    {

    }
}
?>