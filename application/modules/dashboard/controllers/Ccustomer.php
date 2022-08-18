<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ccustomer extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth->check_user_auth();
        $this->load->library('dashboard/lcustomer');
        $this->load->model('dashboard/Customers');
    }

    //Default loading for Customer System.
    public function index()
    {
        // $this->permission->check_label('add_customer')->create()->redirect();

        $content = $this->lcustomer->customer_add_form();
        $this->template_lib->full_admin_html_view($content);
    }

    //customer_search_item
    public function customer_search_item()
    {

        $customer_id = $this->input->post('customer_id', TRUE);
        $content = $this->lcustomer->customer_search_item($customer_id);
        $this->template_lib->full_admin_html_view($content);
    }

    //Manage customer
    public function manage_customer()
    {

        $this->load->model('dashboard/Customers');
        $content = $this->lcustomer->customer_list();
        $this->template_lib->full_admin_html_view($content);
    }

    //Insert Product and upload
    public function insert_customer()
    {

        $customer_id = generator(15);
        //Customer  basic information adding.
        $data = array(
            'customer_id'           => $customer_id,
            'customer_name'         => $this->input->post('customer_name', TRUE),
            'customer_mobile'       => $this->input->post('mobile', TRUE),
            'customer_email'        => $this->input->post('email', TRUE),
            'vat_no'                => $this->input->post('vat_no', TRUE),
            'cr_no'                 => $this->input->post('cr_no', TRUE),
            'previous_balance'      => $this->input->post('previous_balance', TRUE),
            'customer_short_address' => $this->input->post('address', TRUE),
            'customer_address_1'    => $this->input->post('customer_address_1', TRUE),
            'customer_address_2'    => $this->input->post('customer_address_2', TRUE),
            'city'                  => $this->input->post('city', TRUE),
            'state'                 => $this->input->post('state', TRUE),
            'country'               => $this->input->post('country', TRUE),
            'zip'                   => $this->input->post('zip', TRUE),
            'status'                => 1
        );
        $result = $this->Customers->customer_entry($data);
        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
            if (isset($_POST['add-customer'])) {
                redirect(base_url('dashboard/Ccustomer/manage_customer'));
                exit;
            } elseif (isset($_POST['add-customer-another'])) {
                redirect(base_url('dashboard/Ccustomer'));
                exit;
            }
        } else {
            $this->session->set_userdata(array('error_message' => display('already_exists')));
            redirect(base_url('dashboard/Ccustomer'));
        }
    }

    //customer Update Form
    public function customer_update_form($customer_id)
    {
        $content = $this->lcustomer->customer_edit_data($customer_id);
        $this->template_lib->full_admin_html_view($content);
    }

    // customer Update
    public function customer_update()
    {

        $customer_id = $this->input->post('customer_id', TRUE);

        //Customer  basic information adding.
        $data = array(
            'customer_name'         => $this->input->post('customer_name', TRUE),
            'customer_mobile'       => $this->input->post('mobile', TRUE),
            'customer_email'        => $this->input->post('email', TRUE),
            'vat_no'                => $this->input->post('vat_no', TRUE),
            'cr_no'                 => $this->input->post('cr_no', TRUE),
            'customer_short_address' => $this->input->post('address', TRUE),
            'customer_address_1'    => $this->input->post('customer_address_1', TRUE),
            'customer_address_2'    => $this->input->post('customer_address_2', TRUE),
            'city'                  => $this->input->post('city', TRUE),
            'state'                 => $this->input->post('state', TRUE),
            'country'               => $this->input->post('country', TRUE),
            'zip'                   => $this->input->post('zip', TRUE),
            'status'                => 1
        );

        $this->Customers->update_customer($data, $customer_id);
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect('dashboard/Ccustomer/manage_customer');
    }

    //Select city by country id
    public function select_city_country_id()
    {
        $country_id = $this->input->post('country_id', TRUE);
        $states = $this->Customers->select_city_country_id($country_id);

        $html = "";
        if ($states) {
            $html .= "<select class=\"form-control select2 width_100p\" id=\"country\" name=\"country\">
					<option value=\"\">" . display('select_one') . "</option>";
            foreach ($states as $state) {
                $html .= "<option value='" . $state->name . "'>" . $state->name . "</option>";
            }
            $html .= "</select>";
        }
        echo $html;
    }

    //Credit Customer Form
    public function credit_customer()
    {
        $this->load->model('dashboard/Customers');

        $content = $this->lcustomer->credit_customer_list();
        $this->template_lib->full_admin_html_view($content);;
    }

    //Paid Customer list. The customer who will pay 100%.
    public function paid_customer()
    {
        $this->load->model('dashboard/Customers');
        $content = $this->lcustomer->paid_customer_list();
        $this->template_lib->full_admin_html_view($content);;
    }

    //Customer Ledger
    public function customer_ledger($customer_id)
    {
        $content = $this->lcustomer->customer_ledger_data($customer_id);
        $this->template_lib->full_admin_html_view($content);
    }

    //Customer Ledger Report
    public function customer_ledger_report()
    {
        $this->permission->check_label('customer_ledger')->read()->redirect();

        $customer_id = $this->input->post('customer_id', TRUE);
        $from_date   = $this->input->post('from_date', TRUE);
        $to_date     = $this->input->post('to_date', TRUE);
        $content = $this->lcustomer->customer_ledger_report($customer_id, $from_date, $to_date);
        $this->template_lib->full_admin_html_view($content);
    }

    //Customer Final Ledger
    public function customerledger($customer_id)
    {
        $content = $this->lcustomer->customerledger_data($customer_id);
        $this->template_lib->full_admin_html_view($content);
    }
    //Customer Previous Balance
    public function previous_balance_form()
    {
        $content = $this->lcustomer->previous_balance_form();
        $this->template_lib->full_admin_html_view($content);
    }
    // customer delete
    public function customer_delete($customer_id)
    {
        $this->load->model('dashboard/Customers');
        $this->Customers->delete_customer($customer_id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect('dashboard/Ccustomer/manage_customer');
    }
    public function customer_balance_report()
    {
        $this->permission->check_label('customer_balance_report')->read()->redirect();

        $from_date = $this->input->post('from_date', TRUE);
        $to_date  = $this->input->post('to_date', TRUE);
        $content  = $this->lcustomer->customer_balance_report($from_date, $to_date);
        $this->template_lib->full_admin_html_view($content);
    }
}