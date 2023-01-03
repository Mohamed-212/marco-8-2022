<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('web/lproduct');
        $this->load->model('web/Products_model');
        $this->load->model('dashboard/Subscribers');
    }

    //Default loading for Product Index.
    public function index()
    {
        $content = $this->lproduct->Product_page();
        $this->template_lib->full_website_html_view($content);
    }

    //Product Search
    public function get_search_item()
    {
        $search_item = $this->input->get('term', TRUE);

        $v = $this->db->select('*')->from('variant')->like('variant_name', $search_item, 'both')->get()->result();
        $vArr = ['"22"'];
        if ($v && is_array($v) && count($v) > 0) {
            foreach ($v as $value) {
                $vArr[] = '"' . $value->variant_id . '"';
            }
        }

        // echo json_encode($vArr);exit;

        $products = $this->db->select('product_id as id, product_name as value')
        ->from('product_information')
        ->where('category_id !=', 'DPCIHH462YEXA24')->where('category_id !=', '7OYMIICEX171GYC')
<<<<<<< HEAD
        ->like('product_name',$search_item, 'both')
=======
        ->where("(product_name like '%$search_item%' or variants in (" . implode(',', $vArr) . "))", null, false)
>>>>>>> ahmed
        ->where('status', 1)
             ->limit(10)
        ->get()->result();

        $searchitems = [];
        if(!empty($products)){
            foreach ($products as $product) {
                $item['id'] = $product->id;
                $item['value'] = $product->value;
                $item['prodname'] = remove_space($product->value);
                array_push($searchitems, $item);
            }
        }
        echo json_encode($searchitems);
    }

    //Default loading for Product Details.
    public function product_details($p_id)
    {
        $p_id = urldecode($p_id);
        $content = $this->lproduct->product_details($p_id);
        $this->template_lib->full_website_html_view($content);
    }

    //Check 2d variant stock info
    public function check_2d_variant_info()
    {
        $product_id = $this->input->post('product_id',TRUE);
        $variant_id = $this->input->post('variant_id',TRUE);
        $variant_color = $this->input->post('variant_color',TRUE);

        if (!empty($variant_id)) {
            $variant_id = str_replace(',', '', $variant_id);
        }

        $stock = $this->Products_model->check_variant_wise_stock($variant_id, $product_id, $variant_color);
        
        if ($stock > 0) {
            $result[0] = "yes";
            $price = $this->Products_model->check_variant_wise_price($product_id, $variant_id, $variant_color);

            $result[1] = get_amount($price['price']);
            $result[2] = get_amount($price['regular_price']);
            $result[3] = (($price['regular_price']>$price['price'])?ceil((($price['regular_price']-$price['price'])/$price['regular_price'])*100):0);
            $result[4] = $stock;

        } else {
            $result[0] = 'no';
        }
        echo json_encode($result);
    }

    //Check variant wise stock
    public function check_variant_wise_stock()
    {
        $variant_id    = $this->input->post('variant_id',TRUE);
        $product_id    = $this->input->post('product_id',TRUE);
        $variant_color = $this->input->post('variant_color',TRUE);
        $stock         = $this->Products_model->check_variant_wise_stock($variant_id, $product_id);

        if ($stock > 0) {
            echo "1";
        } else {
            echo "2";
        }
    }

    //Check quantity wise stock
    public function check_quantity_wise_stock()
    {
        $quantity      = $this->input->post('product_quantity',TRUE);
        $product_id    = $this->input->post('product_id',TRUE);
        $variant       = $this->input->post('variant',TRUE);
        $variant_color = $this->input->post('variant_color',TRUE);
        $stock         = $this->Products_model->check_quantity_wise_stock($quantity, $product_id, $variant, $variant_color); 
        if ($stock >= $quantity) {
            echo "yes";
        } else {
            echo "no";
        }
    }

    public function brand_product($brand_id=null)
    {
        $price_range = $this->input->get('price',TRUE);
        $size        = $this->input->get('size',TRUE);
        $sort        = $this->input->get('sort',TRUE);
        $rate        = $this->input->get('rate',TRUE);
        $cat        = $this->input->get('cat',TRUE);
        $content = $this->lproduct->brand_product($brand_id,$price_range,$size,$sort,$rate, $cat);
        $this->template_lib->full_website_html_view($content);
    }

    //Submit a subscriber.
    public function add_subscribe()
    {
        $data = array(
            'subscriber_id' => $this->generator(15),
            'apply_ip' => $this->input->ip_address(),
            'email' => $this->input->post('sub_email',TRUE),
            'status' => 1
        );

        $result = $this->Subscribers->subscriber_entry($data);

        if ($result) {
            echo "2";
        } else {
            echo "3";
        }
    }

    //This function is used to Generate Key
    public function generator($lenth)
    {
        $number = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "N", "M", "O", "P", "Q", "R", "S", "U", "V", "T", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 34);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }
}