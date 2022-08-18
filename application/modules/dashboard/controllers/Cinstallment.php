<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cinstallment extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth->check_user_auth();
        $this->load->model(array('dashboard/Invoices'));
        $this->load->library('dashboard/linvoice');
        $this->load->library('dashboard/occational');
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

    //Invoice List count
    public function count_invoice_list($filter = [])
    {
        $this->db->select('a.invoice_id');
        $this->db->from('invoice a');
        $this->db->where('a.is_installment', 1);
        if (!empty($filter['invoice_no'])) {
            $this->db->where('a.invoice', $filter['invoice_no']);
        }
        if (!empty($filter['customer_id'])) {
            $this->db->where('a.customer_id', $filter['customer_id']);
        }
        if (!empty($filter['date'])) {
            $this->db->where("STR_TO_DATE(a.date, '%m-%d-%Y')=DATE('" . $filter['date'] . "')");
        }
        $query = $this->db->count_all_results();
        return $query;
    }

    //Invoice List
    public function get_invoice_list($filter, $start, $limit)
    {
        $this->db->select('a.*,b.*,c.order');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->join('order c', 'c.order_id = a.order_id', 'left');
        $this->db->where('a.is_installment', 1);
        if (!empty($filter['invoice_no'] != '')) {
            $this->db->where('a.invoice', $filter['invoice_no']);
        }
        if ($filter['customer_id'] != '') {
            $this->db->where('a.customer_id', $filter['customer_id']);
        }
        if ($filter['date'] != '') {
            $this->db->where("STR_TO_DATE(a.date, '%m-%d-%Y')=DATE('" . $filter['date'] . "')");
        }
        $this->db->order_by('a.invoice', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return false;
    }

    public function manage_installment()
    {
        $this->permission->check_label('manage_installment')->read()->redirect();
        $filter = array(
            'invoice_no' => $this->input->get('invoice_no', TRUE),
            'customer_id' => $this->input->get('customer_id', TRUE),
            'date' => $this->input->get('date', TRUE),
        );
        $config["base_url"] = base_url('dashboard/Cinstallment/manage_installment');
        $config["total_rows"] = $this->count_invoice_list($filter);
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
        $invoices_list = $this->get_invoice_list($filter, $page, $config["per_page"]);
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
            'position' => $currency_details[0]['currency_position'],
        );

        $data['module'] = "dashboard";
        $data['page'] = "installment/index";
        echo Modules::run('template/layout', $data);
    }

    //get payment info
    public function payment_info()
    {
        $store_id = $this->session->userdata('store_id');
        if (empty($store_id)) {
            return $this->db->select('HeadCode,HeadName')->from('acc_coa')->where_in('PHeadCode', array('111', '1121', '1122', '1123'))->get()->result();
        } else {
            //$cash_head = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('store_id', $store_id)->where('PHeadCode', '111')->get()->result();
            $cash_head = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('PHeadCode', '111')->get()->result();
            //$bank_head = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where_in('PHeadCode', array('1121', '1122', '1123'))->get()->result();
            $bank_head = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where_in('PHeadCode', array('112'))->get()->result();
            return array_merge($bank_head, $cash_head);
        }
    }

    //Installment Update Form
    public function installment_update_form($invoice_id)
    {
        $this->permission->check_label('manage_installment')->update()->redirect();

        $this->db->select('*');
        $this->db->from('invoice_installment');
        $this->db->where('invoice_id', $invoice_id);
        $installment_details = $this->db->get()->result_array();

        $this->db->select('a.*,b.*');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('a.invoice_id', $invoice_id);
        $invoice = $this->db->get()->result_array();

        $employee = $this->empdropdown();

        $payment_info = $this->payment_info();

        $this->load->model(array('dashboard/Soft_settings', 'dashboard/Customers'));
        $currency_details = $this->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('edit_installment'),
            'installment_details' => $installment_details,
            'invoice' => $invoice[0],
            'employee' => $employee,
            'payment_info' => $payment_info,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );

        $data['module'] = "dashboard";
        $data['page'] = "installment/edit_installment_form";
        echo Modules::run('template/layout', $data);
    }

    // Installment Update
    public function installment_update()
    {
        $this->permission->check_label('manage_installment')->update()->redirect();

        if (check_module_status('accounting') == 1) {
            $find_active_fiscal_year = $this->db->select('*')->from('acc_fiscal_year')->where('status', 1)->get()->row();
            if (!empty($find_active_fiscal_year)) {
                //Invoice and customer info
                $invoice_id = $this->input->post('invoice_id', TRUE);
                $customer_id = $this->input->post('customer_id', TRUE);
                $amount = $this->input->post('amount', TRUE);
                $due_date = $this->input->post('due_date', TRUE);
                $payment_amount = $this->input->post('payment_amount', TRUE);
                $payment_date = $this->input->post('payment_date', TRUE);
                $account = $this->input->post('account', TRUE);
                $check_no = $this->input->post('check_no', TRUE);
                $payment_type = $this->input->post('payment_type', TRUE);
                $employee_id = $this->input->post('employee_id', TRUE);
                $status = $this->input->post('status', TRUE);
                $expiry_date = $this->input->post('expiry_date', TRUE);

                $this->db->select('*');
                $this->db->from('invoice_installment');
                $this->db->where('invoice_id', $invoice_id);
                $installment_details = $this->db->get()->result_array();

                foreach ($installment_details as $index => $installment) {
                    if ($installment['status'] != 2) {
                        if ($payment_amount[$index] && $payment_date[$index]
                            && $payment_type[$index] && $account[$index]
                            && $employee_id[$index]) {
                            if (($payment_type[$index] == '3' || $payment_type[$index] == '4') && empty($check_no[$index]) && empty($expiry_date[$index])) {
                                $this->session->set_userdata(array('error_message' => display('enter_check_number_if_payment_type_is_check_or_wire_transfer')));
                                redirect('dashboard/cinstallment/manage_installment');
                            } else {
                                //update installment with values
                                $this->db->where('id', $installment['id']);
                                $update_installment = array(
                                    'payment_date' => $payment_date[$index],
                                    'payment_amount' => $payment_amount[$index],
                                    'status' => $status[$index],
                                    'payment_type' => $payment_type[$index],
                                    'account' => $account[$index],
                                    'check_no' => ($check_no[$index])? $check_no[$index] : null,
                                    'expiry_date' => ($expiry_date[$index])? $expiry_date[$index] : null,
                                    'employee_id' => $employee_id[$index]
                                );
                                $this->db->update('invoice_installment', $update_installment);

                                // if status complete
                                if($status[$index] == 2){

                                    //create new installment if payed amount is less than amount
                                    if ($payment_amount[$index] < $amount[$index]) {
                                        //add new installment with the rest of amount
                                        $installment_data = array(
                                            'invoice_id' => $invoice_id,
                                            'amount' => ($amount[$index] - $payment_amount[$index]),
                                            'due_date' => date('Y-m-d', strtotime($due_date[count($due_date)-1] . ' + 1 months')),
                                        );
                                        $this->db->insert('invoice_installment', $installment_data);
                                    }

                                    $this->db->select('*');
                                    $this->db->from('invoice');
                                    $this->db->where('invoice_id', $invoice_id);
                                    $invoice = $this->db->get()->result_array();

                                    //update customer ledger
                                    //Delete old customer ledger data
                                    $this->db->where('invoice_no', $invoice_id);
                                    $result = $this->db->delete('customer_ledger');
                                    //Insert customer ledger data where payment_amount > 0
                                    if ($payment_amount[$index] > 0) {
                                        //Insert to customer_ledger Table
                                        $data1 = array(
                                            'transaction_id' => $this->auth->generator(15),
                                            'customer_id' => $customer_id,
                                            'invoice_no' => $invoice_id,
                                            'receipt_no' => $this->auth->generator(15),
                                            'date' => $invoice[0]['invoice_date'],
                                            'amount' => $invoice[0]['paid_amount'] + $payment_amount[$index],
                                            'payment_type' => 1,
                                            'description' => 'ITP',
                                            'status' => 1
                                        );
                                        $this->db->insert('customer_ledger', $data1);
                                    }
                                    //Update to customer ledger Table
                                    $data2 = array(
                                        'transaction_id' => $this->auth->generator(15),
                                        'customer_id' => $customer_id,
                                        'invoice_no' => $invoice_id,
                                        'date' => $invoice[0]['invoice_date'],
                                        'amount' => $invoice[0]['total_amount'],
                                        'status' => 1
                                    );
                                    $this->db->insert('customer_ledger', $data2);

                                    //update invoice paid amount
                                    $this->db->where('invoice_id', $invoice_id);
                                    $new_paid_amount = array(
                                        'paid_amount' => $invoice[0]['paid_amount'] + $payment_amount[$index],
                                        'due_amount' => $invoice[0]['due_amount'] - $payment_amount[$index]
                                    );
                                    $this->db->update('invoice', $new_paid_amount);

                                    //add customer credit
                                    $customer_head = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('customer_id', $customer_id)->get()->row();
                                    if (empty($customer_head)) {
                                        $this->load->model('accounting/account_model');
                                        $customer_name = $this->db->select('customer_name')->from('customer_information')->where('customer_id', $result->customer_id)->get()->row();
                                        if ($customer_name) {
                                            $customer_data = $data = array(
                                                'customer_id' => $result->customer_id,
                                                'customer_name' => $customer_name->customer_name,
                                            );
                                            $this->account_model->insert_customer_head($customer_data);
                                        }
                                        $customer_head = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('customer_id', $customer_id)->get()->row();
                                    }
                                    // add paid_amount Credit
                                    $customer_credit = array(
                                        'fy_id' => $find_active_fiscal_year->id,
                                        'VNo' => 'Inv-' . $invoice_id,
                                        'Vtype' => 'Sales',
                                        'VDate' => date('Y-m-d H:i:s'),
                                        'COAID' => $customer_head->HeadCode,
                                        'Narration' => 'Sales "paid_amount" Credit by customer id: ' . $customer_head->HeadName . '(' . $customer_id . ')',
                                        'Debit' => 0,
                                        'Credit' => $payment_amount[$index],
                                        'IsPosted' => 1,
                                        'CreateBy' => $this->session->userdata('user_id'),
                                        'CreateDate' => date('Y-m-d H:i:s'),
                                        //'IsAppove' => 0
                                        'IsAppove' => 1
                                    );
                                    $this->db->insert('acc_transaction', $customer_credit);
                                    // add paid_amount Credit
                                    $payment_head = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('HeadCode', $account[$index])->get()->row();
                                    $bank_debit = array(
                                        'fy_id' => $find_active_fiscal_year->id,
                                        'VNo' => 'Inv-' . $invoice_id,
                                        'Vtype' => 'Sales',
                                        'VDate' => date('Y-m-d H:i:s'),
                                        'COAID' => $payment_head->HeadCode,
                                        'Narration' => 'Sales "paid_amount" debited by cash/bank id: ' . $payment_head->HeadName . '(' . $account[$index] . ')',
                                        'Debit' => $payment_amount[$index],
                                        'Credit' => 0,
                                        'IsPosted' => 1,
                                        'CreateBy' => $this->session->userdata('user_id'),
                                        'CreateDate' => date('Y-m-d H:i:s'),
                                        //'IsAppove' => 0
                                        'IsAppove' => 1
                                    );
                                    $this->db->insert('acc_transaction', $bank_debit);
                                }
                            }
                        }
                    }
                }
            } else {
                $this->session->set_userdata(array('error_message' => display('no_active_fiscal_year_found')));
                redirect(base_url('Admin_dashboard'));
            }
        }
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect('dashboard/cinstallment/manage_installment');
    }


    //Email testing for email
    public function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    //Search Inovoice Item
    public function search_inovoice_item()
    {

        $customer_id = $this->input->post('customer_id', TRUE);
        $content = $this->linvoice->search_inovoice_item($customer_id);
        $this->template_lib->full_admin_html_view($content);
    }

    //This function is used to Generate Key
    public function generator($lenth)
    {
        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 8);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }

    public function convert_number($number)
    {
        if ($number < 0) {
            $number = -($number);
        }
        if (($number < 0) || ($number > 9999999999999)) {
            throw new Exception("Number is out of range");
        }
        $Gn = floor($number / 1000000);
        /* Millions (giga) */
        $number -= $Gn * 1000000;
        $kn = floor($number / 1000);
        /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);
        /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);
        /* Tens (deca) */
        $n = $number % 10;
        /* Ones */
        $res = "";
        if ($Gn) {
            $res .= $this->convert_number($Gn) . "Million";
        }
        if ($kn) {
            $res .= (empty($res) ? "" : " ") . $this->convert_number($kn) . " Thousand";
        }
        if ($Hn) {
            $res .= (empty($res) ? "" : " ") . $this->convert_number($Hn) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($Dn || $n) {
            if (!empty($res)) {
                $res .= " and ";
            }
            if ($Dn < 2) {
                $res .= $ones[$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];
                if ($n) {
                    $res .= "-" . $ones[$n];
                }
            }
        }
        if (empty($res)) {
            $res = "zero";
        }
        return $res;
    }

}