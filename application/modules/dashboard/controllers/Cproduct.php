<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cproduct extends MX_Controller
{

    public $product_id;
    private $table = "language";

    function __construct()
    {
        parent::__construct();
        $this->auth->check_user_auth();
        $this->load->model(array(
            'dashboard/Products',
            'dashboard/Galleries',
            'dashboard/Variants',
            'dashboard/Suppliers',
            'dashboard/Categories',
            'dashboard/Brands',
            'dashboard/Units',
            'dashboard/Soft_settings',
            'template/Template_model',
            'dashboard/cfiltration_model',
            'dashboard/Stock_opening_model',
        ));
        $this->load->library('dashboard/lproduct'); // All need about product
        $this->load->library('dashboard/occational'); // dateConvert
    }

    //Index page load
    public function index()
    {
        $this->permission->check_label('add_product')->create()->redirect();

        $content = $this->lproduct->product_add_form(); // get content
        $this->template_lib->full_admin_html_view($content);
    }

    //Index page load
    public function add_product_assemply()
    {
        $this->permission->check_label('add_product')->create()->redirect();

        $content = $this->lproduct->product_assemply_add_form(); // get content
        $this->template_lib->full_admin_html_view($content);
    }

    //Insert Product and upload
    public function insert_product()
    {
        $this->permission->check_label('add_product')->create()->redirect();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_name', display('product_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('category_id', display('category'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('onsale', display('onsale'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('price', display('sell_price'), 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('supplier_price', display('supplier_price'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('model', display('model'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('supplier_id', display('supplier'), 'trim|required|xss_clean');
        // $this->form_validation->set_rules('variant[]', display('variant'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->session->set_userdata(array('error_message' => 'failed_try_again'));
            $this->index();
        } else {
            if ($_FILES['image_thumb']['name']) {
                //Chapter chapter add start
                $config['upload_path'] = './my-assets/image/product/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
                $config['max_size'] = "*";
                $config['max_width'] = "*";
                $config['max_height'] = "*";
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image_thumb')) {
                    $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                    redirect('dashboard/Cproduct');
                } else {
                    $image = $this->upload->data();
                    $image_url = "my-assets/image/product/" . $image['file_name'];

                    //Resize image config
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $image['full_path'];
                    $config['maintain_ratio'] = FALSE;
                    $config['width'] = 400;
                    $config['height'] = 400;
                    $config['new_image'] = 'my-assets/image/product/thumb/' . $image['file_name'];
                    $this->upload->initialize($config);
                    $this->load->library('image_lib', $config);
                    $resize = $this->image_lib->resize();
                    //Resize image config

                    $thumb_image = $config['new_image'];
                }
            }
            $category_id = $this->input->post('category_id', TRUE);
            // check if category_id is not found
            $categoryIsFound = $this->db->select('category_id')->from('product_category')->where('category_id', $category_id)->get()->num_rows();
            if ($categoryIsFound == 0) {
                // create new category with that name
                $new_category_id = generator(15);
                $category_data = array(
                    'category_id' => $new_category_id,
                    'category_name' => $category_id,
                    'top_menu' => 1,
                    'menu_pos' => 1,
                    'cat_favicon' => 'my-assets/image/category.png',
                    'parent_category_id' => '',
                    'cat_image' => 'my-assets/image/category.png',
                    'cat_type' => 1,
                    'status' => 1
                );
                $this->Categories->category_entry($category_data);
                $category_id = $new_category_id;
            }



            $variant = $this->input->post('variant', TRUE);
            if ($variant) {
                $variant = $variant[0];
            }

            // check if size variant is exists
            $found = $this->db->select('variant_id')->from('variant')->where('variant_id', $variant)->get()->num_rows();
            if (!$found) {
                // create new varient size and then attach it`s id to product
                $variant_id = $this->auth->generator(15);
                $variant_data = array(
                    'variant_id' => $variant_id,
                    'variant_name' => $variant,
                    'variant_type' => 'size',
                    'color_code' => null,
                    'status' => 1
                );

                $result = $this->Variants->variant_entry($variant_data);
                $variant = $variant_id;

                if ($result) {
                    // add this size variant to only current category
                    // $category_ids = $this->db->select('category_id')->from('product_category')->where()->get()->result();
                    // foreach ($category_ids as $category_id):
                    $this->db->query("INSERT INTO `category_variant` (`category_id`, `variant_id`, `created_at`, `updated_at`) VALUES (" . $this->db->escape($category_id) . ", " . $this->db->escape($variant_id) . ", now(), now())");
                    // endforeach;
                }
            }

            // check if brand is not found then create new one
            $brand_id = $this->input->post('brand', TRUE);
            $brandIsFound = $this->db->select('brand_id')->from('brand')->where('brand_id', $brand_id)->get()->num_rows();
            if ($brandIsFound == 0) {
                // create new brand with that name
                $new_brand_id = $this->auth->generator(15);
                $brand_data = array(
                    'brand_id'   => $new_brand_id,
                    'brand_name' => $brand_id,
                    'brand_image' => null,
                    'website'    => '',
                    'status'     => 1
                );
                $this->Brands->brand_entry($brand_data);

                $brand_id = $new_brand_id;
            }

            $variant_colors = $this->input->post('variant_colors', TRUE);
            if (!empty($variant_colors)) {
                $full_variant = array_merge($variant, $variant_colors);
            } else {
                $full_variant = $variant;
            }
            $onsale = $this->input->post('onsale', TRUE);
            if ($onsale) {
                $onsale_price = $this->input->post('onsale_price', TRUE);
                $onsale_price = (!empty($onsale_price) ? $onsale_price : null);
            } else {
                $onsale_price = null;
            }
            $default_variant = $this->input->post('default_variant', TRUE);
            $product_id = $this->generator(8);
            // Product variant prices
            $variant_prices = $this->input->post('variant_prices', TRUE);

            $model_and_color = explode('-', $this->input->post('model', TRUE));
            $product_model_only = trim($model_and_color[0]);
            $product_color = trim($model_and_color[1]);

            $data = array(
                'product_id' => $product_id,
                'product_name' => $this->input->post('product_name', TRUE),
                'supplier_id' => $this->input->post('supplier_id', TRUE),
                'category_id' => $category_id,
                'warrantee' => $this->input->post('warrantee', TRUE),
                'bar_code' => $this->input->post('bar_code', TRUE),
                'price' => $this->input->post('price', TRUE),
                'supplier_price' => $this->input->post('supplier_price', TRUE),
                'unit' => $this->input->post('unit', TRUE),
                'product_model' => $this->input->post('model', TRUE),
                'product_details' => $this->input->post('details', TRUE),
                'brand_id' => $brand_id,
                'variants' => implode(",", (array) $full_variant),
                'default_variant' => $default_variant,
                'variant_price' => (!empty($variant_prices) ? 1 : 0),
                'type' => $this->input->post('type', TRUE),
                'best_sale' => $this->input->post('best_sale', TRUE),
                'onsale' => $onsale,
                'onsale_price' => $onsale_price,
                'review' => $this->input->post('review', TRUE),
                'video' => $this->input->post('video', TRUE),
                'description' => stripslashes($this->input->post('description', TRUE)),
                'tag' => $this->input->post('tag', TRUE),
                'specification' => stripslashes($this->input->post('specification', TRUE)),
                'invoice_details' => $this->input->post('invoice_details', FALSE),
                'image_large_details' => (!empty($image_url) ? $image_url : 'my-assets/image/product.png'),
                'image_thumb' => (!empty($thumb_image) ? $thumb_image : 'my-assets/image/product.png'),
                'status' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'product_model_only' => $product_model_only,
                'product_color' => $product_color,
            );
            $languages = $this->input->post('language', TRUE);
            $trans_names = $this->input->post('trans_name', TRUE);
            $trans_details = $this->input->post('trans_details', TRUE);
            $trans_description = $this->input->post('trans_description', TRUE);
            $trans_specification = $this->input->post('trans_specification', TRUE);
            if (!empty($languages)) {
                $data2 = [];
                $language_array = [];
                foreach ($languages as $key => $language) {
                    if (!in_array($languages[$key], $language_array)) {
                        $data2[] = array(
                            'language' => $languages[$key],
                            'product_id' => $product_id,
                            'trans_name' => $trans_names[$key],
                            'trans_details' => $trans_details[$key],
                            'trans_description' => $trans_description[$key],
                            'trans_specification' => $trans_specification[$key]
                        );
                    } else {
                        $this->session->set_userdata(array('error_message' => 'Multiple input of same language'));
                        redirect(base_url('dashboard/Cproduct'));
                    }
                    $language_array[] = $data2[$key]['language'];
                }
                $result2 = $this->db->insert_batch('product_translation', $data2);
            }
            $result = $this->Products->product_entry($data);
            if ($this->input->post('category_id', TRUE) == 'XJIMM9X3ZAWUYXQ') {
                $data = array(
                    't_p_s_id' => $this->auth->generator(15),
                    'product_id' => $product_id,
                    'tax_id' => '52C2SKCKGQY6Q9J',
                    'tax_percentage' => '14',
                );

                $this->db->insert('tax_product_service', $data);
            }

            // filter section start
            $filter_types = $this->input->post('filter_type', true);
            $filter_names = $this->input->post('filter_name', true);
            $filter_list = [];
            $tdata3 = [];
            for ($d = 0; $d < count($filter_types); $d++) {
                if (!empty($filter_types[$d]) && !empty($filter_names[$d])) {
                    $filter_list[] = array(
                        'category_id' => $this->input->post('category_id', TRUE),
                        'product_id' => $product_id,
                        'filter_type_id' => $filter_types[$d],
                        'filter_item_id' => $filter_names[$d]
                    );
                    $tdata3[] = array(
                        'type_id' => $filter_types[$d],
                        'category_id' => $this->input->post('category_id', TRUE)
                    );
                }
            }
            if (!empty($filter_list)) {
                $this->db->insert_batch('filter_product', $filter_list);
            }
            if (!empty($tdata3)) {
                $this->db->insert_batch('filter_type_category', $tdata3);
            }
            // filter section end
            //Product variant prices
            if (isset($variant_prices) && !empty($variant_prices)) {
                $size_variant = $this->input->post('size_variant[]', TRUE);
                $color_variant = $this->input->post('color_variant[]', TRUE);
                $variant_price_amt = $this->input->post('variant_price_amt[]', TRUE);

                if (!empty($size_variant)) {
                    $vprice_list = [];
                    for ($c = 0; $c < count($size_variant); $c++) {
                        if (!empty($size_variant[$c]) || !empty($color_variant[$c])) {
                            $vprice_list[] = array(
                                'product_id' => $product_id,
                                'var_size_id' => $size_variant[$c],
                                'var_color_id' => $color_variant[$c],
                                'price' => $variant_price_amt[$c]
                            );
                        }
                    }

                    if (!empty($vprice_list)) {
                        $this->db->insert_batch('product_variants', $vprice_list);
                    }
                }
            }

            ///////Start for pricing////////////////////////////////////////////////////////////////////////

            $price_types = $this->input->post('pricetype[]', TRUE);
            $pricepri = $this->input->post('pricepri[]', TRUE);
            foreach ($price_types as $key => $value) {
                if (!empty($price_types[$key]) && !empty($pricepri[$key])) {
                    $price_types_list[] = array(
                        'product_id' => $product_id,
                        'pri_type_id' => $price_types[$key],
                        'product_price' => $pricepri[$key]
                    );
                }
            }
            if (!empty($price_types_list)) {
                $this->db->insert_batch('pricing_types_product', $price_types_list);
                $pricingbit = array(
                    'pricing' => 1,
                );
                $this->db->where('product_id', $product_id);
                $this->db->update('product_information', $pricingbit);
            } else {
                $pricingbit = array(
                    'pricing' => 0,
                );
                $this->db->where('product_id', $product_id);
                $this->db->update('product_information', $pricingbit);
            }
            ///////End for pricing/////////////////////////////////////////////////////////////////////
            ///////Start for assembly////////////////////////////////////////////////////////////////////////

            $assembly_products = $this->input->post('assembly_product_id[]', TRUE);
            $assembly_products_price = $this->input->post('product_rate[]', TRUE);
            foreach ($assembly_products as $key => $value) {
                if (!empty($assembly_products[$key])) {
                    $assembly_products_list[] = array(
                        'parent_product_id' => $product_id,
                        'child_product_id' => $assembly_products[$key],
                        'child_product_price' => $assembly_products_price[$key],
                    );
                }
            }
            if (!empty($assembly_products_list)) {
                $this->db->insert_batch('assembly_products', $assembly_products_list);
                $assemblybit = array(
                    'assembly' => 1,
                );
                $this->db->where('product_id', $product_id);
                $this->db->update('product_information', $assemblybit);
            } else {
                $assemblybit = array(
                    'assembly' => 0,
                );
                $this->db->where('product_id', $product_id);
                $this->db->update('product_information', $assemblybit);
            }
            ///////End for assembly/////////////////////////////////////////////////////////////////////
            //gallery image insert start
            $dataInfo = [];
            $this->load->library('upload');
            $files = $_FILES;
            if (!empty($_FILES['imageUpload']['name'][0])) {
                $cpt = count($_FILES['imageUpload']['name']);

                for ($i = 0; $i < $cpt; $i++) {
                    $_FILES['imageUpload']['name'] = $files['imageUpload']['name'][$i];
                    $_FILES['imageUpload']['type'] = $files['imageUpload']['type'][$i];
                    $_FILES['imageUpload']['tmp_name'] = $files['imageUpload']['tmp_name'][$i];
                    $_FILES['imageUpload']['error'] = $files['imageUpload']['error'][$i];
                    $_FILES['imageUpload']['size'] = $files['imageUpload']['size'][$i];
                    $_FILES['encrypt_name'] = TRUE;
                    $this->upload->initialize($this->set_upload_options());
                    $this->upload->do_upload('imageUpload');
                    $dataInfo[] = $this->upload->data();
                    $image_url = "my-assets/image/gallery/" . $dataInfo[$i]['file_name'];

                    $imagedata = [
                        'image_gallery_id' => generator(15),
                        'product_id' => $product_id,
                        'image_url' => $image_url,
                        'img_thumb' => 'null',
                    ];
                    $result2 = $this->Galleries->image_entry($imagedata);
                }
            }
            //gallery image insert end=================


            if ($result) {
                $this->session->set_userdata(array('message' => display('successfully_added')));
                if (isset($_POST['add-product'])) {
                    redirect(base_url('dashboard/Cproduct/manage_product'));
                    exit;
                } elseif (isset($_POST['add-product-another'])) {
                    redirect(base_url('dashboard/Cproduct'));
                    exit;
                }
            } else {
                $this->session->set_userdata(array('error_message' => display('product_model_already_exist')));
                redirect(base_url('dashboard/Cproduct'));
            }
        }
    }

    private function set_upload_options()
    {
        //upload an image options
        $config = array();
        $config['upload_path'] = './my-assets/image/gallery/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '0';
        $config['overwrite'] = FALSE;
        $config['encrypt_name'] = TRUE;

        return $config;
    }

    //Manage Product
    public function manage_product($page = 0)
    {
        $this->permission->check_label('manage_product')->read()->redirect();
        $filter = array(
            'product_name' => $this->input->get('product_name', TRUE),
            'supplier_id' => $this->input->get('supplier_id', TRUE),
            'category_id' => $this->input->get('category_id', TRUE),
            'unit_id' => $this->input->get('unit_id', TRUE),
            'model_no' => $this->input->get('model_no', TRUE)
        );

        #
        #pagination starts
        #
        $config["base_url"] = base_url('dashboard/Cproduct/manage_product/');
        $config["total_rows"] = $this->Products->product_list_count($filter);
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
        #
        #pagination ends
        #

        $content = $this->lproduct->product_list($filter, $links, $config["per_page"], $page);
        $this->template_lib->full_admin_html_view($content);
    }

    //Product Update Form
    public function product_update_form($product_id)
    {
        $this->permission->check_label('manage_product')->update()->redirect();

        $product_id = str_replace('#', '', $product_id);

        $CI = &get_instance();
        $content = $CI->lproduct->product_edit_data($product_id);
        $this->template_lib->full_admin_html_view($content);
    }

    // Product Update
    public function product_update($product_id)
    {


        $this->permission->check_label('manage_product')->update()->redirect();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_name', display('product_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('category_id', display('category'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('onsale', display('onsale'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('price', display('sell_price'), 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('supplier_price', display('supplier_price'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('model', display('model'), 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('supplier_id', display('supplier'), 'trim|required|xss_clean');
        //  $this->form_validation->set_rules('variant[]', display('variant'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->session->set_userdata(array('error_message' => 'failed_try_again'));
            $this->product_update_form($product_id);
        } else {

            $image = null;
            if ($_FILES['image_thumb']['name']) {
                //Chapter chapter add start
                $config['upload_path'] = './my-assets/image/product/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
                $config['max_size'] = "*";
                $config['max_width'] = "*";
                $config['max_height'] = "*";
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image_thumb')) {
                    $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                    redirect('dashboard/Cproduct');
                } else {
                    $image = $this->upload->data();
                    $image_url = "my-assets/image/product/" . $image['file_name'];
                    //Resize image config
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $image['full_path'];
                    $config['maintain_ratio'] = FALSE;
                    $config['width'] = 400;
                    $config['height'] = 400;
                    $config['new_image'] = 'my-assets/image/product/thumb/' . $image['file_name'];
                    $this->upload->initialize($config);
                    $this->load->library('image_lib', $config);
                    $resize = $this->image_lib->resize();
                    //Resize image config
                    $thumb_image = $config['new_image'];

                    //Old image delete
                    $old_image = $this->input->post('old_img_lrg', TRUE);
                    $old_file = substr($old_image, strrpos($old_image, '/') + 1);
                    @unlink(FCPATH . 'my-assets/image/product/' . $old_file);

                    //Thumb image delete
                    $old_img_thumb = $this->input->post('old_thumb_image', TRUE);
                    $old_file_thumb = substr($old_img_thumb, strrpos($old_img_thumb, '/') + 1);
                    @unlink(FCPATH . 'my-assets/image/product/thumb/' . $old_file_thumb);
                }
            }

            $old_img_lrg = $this->input->post('old_img_lrg', TRUE);
            $old_thumb_image = $this->input->post('old_thumb_image', TRUE);
            $product_id = $this->input->post('product_id', TRUE);
            $onsale = $this->input->post('onsale', TRUE);
            if ($onsale) {
                $onsale_price = $this->input->post('onsale_price', TRUE);
                $onsale_price = (!empty($onsale_price) ? $onsale_price : null);
            } else {
                $onsale_price = null;
            }



            $category_id = $this->input->post('category_id', TRUE);
            // check if category_id is not found
            $categoryIsFound = $this->db->select('category_id')->from('product_category')->where('category_id', $category_id)->get()->num_rows();
            if ($categoryIsFound == 0) {
                // create new category with that name
                $new_category_id = generator(15);
                $category_data = array(
                    'category_id' => $new_category_id,
                    'category_name' => $category_id,
                    'top_menu' => 1,
                    'menu_pos' => 1,
                    'cat_favicon' => 'my-assets/image/category.png',
                    'parent_category_id' => '',
                    'cat_image' => 'my-assets/image/category.png',
                    'cat_type' => 1,
                    'status' => 1
                );
                $this->Categories->category_entry($category_data);
                $category_id = $new_category_id;
            }



            $variant = $this->input->post('variant', TRUE);
            if ($variant) {
                $variant = $variant[0];
            }

            // check if size variant is exists
            $found = $this->db->select('variant_id')->from('variant')->where('variant_id', $variant)->get()->num_rows();
            if (!$found) {
                // create new varient size and then attach it`s id to product
                $variant_id = $this->auth->generator(15);
                $variant_data = array(
                    'variant_id' => $variant_id,
                    'variant_name' => $variant,
                    'variant_type' => 'size',
                    'color_code' => null,
                    'status' => 1
                );

                $result = $this->Variants->variant_entry($variant_data);
                $variant = $variant_id;

                if ($result) {
                    // add this size variant to only current category
                    // $category_ids = $this->db->select('category_id')->from('product_category')->where()->get()->result();
                    // foreach ($category_ids as $category_id):
                    $this->db->query("INSERT INTO `category_variant` (`category_id`, `variant_id`, `created_at`, `updated_at`) VALUES (" . $this->db->escape($category_id) . ", " . $this->db->escape($variant_id) . ", now(), now())");
                    // endforeach;
                }
            }

            // check if brand is not found then create new one
            $brand_id = $this->input->post('brand', TRUE);
            $brandIsFound = $this->db->select('brand_id')->from('brand')->where('brand_id', $brand_id)->get()->num_rows();
            if ($brandIsFound == 0) {
                // create new brand with that name
                $new_brand_id = $this->auth->generator(15);
                $brand_data = array(
                    'brand_id'   => $new_brand_id,
                    'brand_name' => $brand_id,
                    'brand_image' => null,
                    'website'    => '',
                    'status'     => 1
                );
                $this->Brands->brand_entry($brand_data);

                $brand_id = $new_brand_id;
            }


            $variant_colors = $this->input->post('variant_colors', TRUE);

            if (!empty($variant_colors)) {
                $full_variant = array_merge($variant, $variant_colors);
            } else {
                $full_variant = $variant;
            }


            // filter section start
            $filter_types = $this->input->post('filter_type', true);
            $filter_names = $this->input->post('filter_name', true);
            // delete previous filter items 
            $this->db->delete('filter_product', array('product_id' => $product_id));
            $this->db->delete('filter_type_category', array('category_id' => $category_id));
            $filter_list = [];
            $tdata3 = [];
            for ($d = 0; $d < count($filter_types); $d++) {
                if (!empty($filter_types[$d]) && !empty($filter_names[$d])) {
                    $filter_list[] = array(
                        'category_id' => $category_id,
                        'product_id' => $product_id,
                        'filter_type_id' => $filter_types[$d],
                        'filter_item_id' => $filter_names[$d]
                    );
                    $tdata3[] = array(
                        'type_id' => $filter_types[$d],
                        'category_id' => $category_id
                    );
                }
            }
            if (!empty($filter_list)) {
                $this->db->insert_batch('filter_product', $filter_list);
            }
            if (!empty($tdata3)) {
                $this->db->insert_batch('filter_type_category', $tdata3);
            }
            // filter section end
            // translation section start
            $languages = $this->input->post('language', TRUE);
            $trans_names = $this->input->post('trans_name', TRUE);
            $trans_details = $this->input->post('trans_details', TRUE);
            $trans_description = $this->input->post('trans_description', TRUE);
            $trans_specification = $this->input->post('trans_specification', TRUE);
            if (!empty($languages)) {
                $data2 = [];
                $language_array = [];
                foreach ($languages as $key => $language) {
                    if (!in_array($languages[$key], $language_array)) {
                        $data2[] = array(
                            'language' => $languages[$key],
                            'product_id' => $product_id,
                            'trans_name' => $trans_names[$key],
                            'trans_details' => $trans_details[$key],
                            'trans_description' => $trans_description[$key],
                            'trans_specification' => $trans_specification[$key]
                        );
                    } else {
                        $this->session->set_userdata(array('error_message' => 'Multiple input of same language'));
                        redirect(base_url('dashboard/Cproduct'));
                    }
                    $language_array[] = $data2[$key]['language'];
                }
                $this->db->delete('product_translation', array('product_id' => $product_id));
                $result2 = $this->db->insert_batch('product_translation', $data2);
            }
            // translation section end
            // Product variant prices
            $variant_prices = $this->input->post('variant_prices', TRUE);
            $provar_prices = $this->Products->get_product_variant_prices($product_id);

            $model_and_color = explode('-', $this->input->post('model', TRUE));
            $product_model_only = trim($model_and_color[0]);
            $product_color = trim($model_and_color[1]);

            $data = array(
                'product_name' => $this->input->post('product_name', TRUE),
                'supplier_id' => empty($this->input->post('supplier_id', TRUE)) ? null : $this->input->post('supplier_id', TRUE),
                'category_id' => $category_id,
                'warrantee' => $this->input->post('warrantee', TRUE),
                'bar_code' => $this->input->post('bar_code', TRUE),
                'price' => $this->input->post('price', TRUE),
                'supplier_price' => $this->input->post('supplier_price', TRUE),
                'unit' => $this->input->post('unit', TRUE),
                'product_model' => $this->input->post('model', TRUE),
                'product_details' => $this->input->post('details', TRUE),
                'brand_id' => $brand_id,
                'variants' => implode(",", (array) $full_variant),
                'default_variant' => $this->input->post('default_variant', TRUE),
                'variant_price' => (!empty($variant_prices) ? 1 : 0),
                'video' => $this->input->post('video', TRUE),
                'type' => $this->input->post('type', TRUE),
                'best_sale' => $this->input->post('best_sale', TRUE),
                'onsale' => $onsale,
                'onsale_price' => $onsale_price,
                'invoice_details' => $this->input->post('invoice_details', TRUE),
                'review' => $this->input->post('review', TRUE),
                'description' => stripslashes($this->input->post('description', TRUE)),
                'tag' => $this->input->post('tag', TRUE),
                'specification' => stripslashes($this->input->post('specification', TRUE)),
                'image_large_details' => (!empty($image_url) ? $image_url : $old_img_lrg),
                'image_thumb' => (!empty($thumb_image) ? $thumb_image : $old_thumb_image),
                'status' => 1,
                'product_model_only' => $product_model_only,
                'product_color' => $product_color,
            );
            $result = $this->Products->update_product($data, $product_id);
            //// start update tax for sunglasses by shady azzam
            // get sun glasses category latest id
            $sun_glasses = $this->db->select('category_id')->from('product_category')->where('category_name', 'SUNGLASSES')->get()->row();
            if ($category_id == $sun_glasses->category_id) {
                $this->db->select('tax.*,tax_product_service.product_id,tax_percentage');
                $this->db->from('tax_product_service');
                $this->db->join('tax', 'tax_product_service.tax_id = tax.tax_id', 'left');
                $this->db->where('tax_product_service.tax_id', '52C2SKCKGQY6Q9J');
                $this->db->where('tax_product_service.product_id', $product_id);
                $tax_information = $this->db->get()->result();

                //New tax calculation for discount
                if (!empty($tax_information)) {
                    $data = array(
                        'tax_percentage' => '14',
                    );
                    $this->db->where('tax_id', '52C2SKCKGQY6Q9J');
                    $this->db->where('product_id', $product_id);
                    $this->db->update('tax_product_service', $data);
                } else {
                    $data = array(
                        't_p_s_id' => $this->auth->generator(15),
                        'product_id' => $product_id,
                        'tax_id' => '52C2SKCKGQY6Q9J',
                        'tax_percentage' => '14',
                    );

                    $this->db->insert('tax_product_service', $data);
                }
            } else {
                $this->db->select('tax.*,tax_product_service.product_id,tax_percentage');
                $this->db->from('tax_product_service');
                $this->db->join('tax', 'tax_product_service.tax_id = tax.tax_id', 'left');
                $this->db->where('tax_product_service.tax_id', '52C2SKCKGQY6Q9J');
                $this->db->where('tax_product_service.product_id', $product_id);
                $tax_information = $this->db->get()->result();

                //New tax calculation for discount
                if (!empty($tax_information)) {
                    $this->db->where('tax_id', '52C2SKCKGQY6Q9J');
                    $this->db->where('product_id', $product_id);
                    $this->db->delete('tax_product_service');
                }
            }
            //// End update tax for sunglasses by shady azzam


            //Product variant prices
            if (isset($variant_prices) && !empty($variant_prices)) {
                $size_variant = $this->input->post('size_variant[]', TRUE);
                $color_variant = $this->input->post('color_variant[]', TRUE);
                $variant_price_amt = $this->input->post('variant_price_amt[]', TRUE);
                if (!empty($size_variant)) {
                    $vprice_list = [];
                    for ($c = 0; $c < count($size_variant); $c++) {
                        if (!empty($size_variant[$c]) || !empty($color_variant[$c])) {
                            $vprice_list[] = array(
                                'product_id' => $product_id,
                                'var_size_id' => (!empty($size_variant[$c]) ? $size_variant[$c] : NULL),
                                'var_color_id' => (!empty($color_variant[$c]) ? $color_variant[$c] : NULL),
                                'price' => $variant_price_amt[$c]
                            );
                        }
                    }
                    if (!empty($vprice_list)) {
                        $this->db->delete('product_variants', array('product_id' => $product_id));
                        $this->db->insert_batch('product_variants', $vprice_list);
                    }
                }
            } else {
                if (!empty($provar_prices)) {
                    $this->db->delete('product_variants', array('product_id' => $product_id));
                }
            }



            ///////Start for pricing////////////////////////////////////////////////////////////////////////

            $price_types = $this->input->post('pricetype[]', TRUE);
            $pricepri = $this->input->post('pricepri[]', TRUE);
            if (!empty($price_types)) {
                foreach ($price_types as $key => $value) {
                    if (!empty($price_types[$key]) && !empty($pricepri[$key])) {
                        $price_types_list[] = array(
                            'product_id' => $product_id,
                            'pri_type_id' => $price_types[$key],
                            'product_price' => $pricepri[$key]
                        );
                    }
                }
            }
            if (!empty($price_types_list)) {
                $this->db->delete('pricing_types_product', array('product_id' => $product_id));
                $this->db->insert_batch('pricing_types_product', $price_types_list);
                $pricingbit = array(
                    'pricing' => 1,
                );
                $this->db->where('product_id', $product_id);
                $this->db->update('product_information', $pricingbit);
            } else {
                $this->db->delete('pricing_types_product', array('product_id' => $product_id));
                $pricingbit = array(
                    'pricing' => 0,
                );
                $this->db->where('product_id', $product_id);
                $this->db->update('product_information', $pricingbit);
            }
            ///////End for pricing/////////////////////////////////////////////////////////////////////
            ///////Start for assembly////////////////////////////////////////////////////////////////////////

            $assembly_products = $this->input->post('assembly_product_id[]', TRUE);
            $assembly_products_price = $this->input->post('product_rate[]', TRUE);
            if (!empty($assembly_products)) {
                foreach ($assembly_products as $key => $value) {
                    if (!empty($assembly_products[$key])) {
                        $assembly_products_list[] = array(
                            'parent_product_id' => $product_id,
                            'child_product_id' => $assembly_products[$key],
                            'child_product_price' => $assembly_products_price[$key],
                        );
                    }
                }
            }
            if (!empty($assembly_products_list)) {
                $this->db->delete('assembly_products', array('parent_product_id' => $product_id));
                $this->db->insert_batch('assembly_products', $assembly_products_list);
                $assemblybit = array(
                    'assembly' => 1,
                );
                $this->db->where('product_id', $product_id);
                $this->db->update('product_information', $assemblybit);
            } else {
                $this->db->delete('assembly_products', array('parent_product_id' => $product_id));
                $assemblybit = array(
                    'assembly' => 0,
                );
                $this->db->where('product_id', $product_id);
                $this->db->update('product_information', $assemblybit);
            }
            ///////End for assembly/////////////////////////////////////////////////////////////////////

            $old_gallery_image = $this->input->post('old_gallery_image', TRUE);
            $dataInfo = [];
            $dataInfo2 = [];
            $this->load->library('upload');
            $files = $_FILES;
            //print_r($files);
            //echo $_FILES['imageUpload']['name'];
            $cpt = count($_FILES['imageUpload']['name']);

            $m = 0;
            $n = 0;
            for ($i = 0, $j = 0; $i < $cpt; $i++, $j++) {
                if (!empty($old_gallery_image[$j])) {
                    //update existing image
                    if (!empty($files['imageUpload']['name'][$i])) {
                        $_FILES['imageUpload']['name'] = $files['imageUpload']['name'][$i];
                        $_FILES['imageUpload']['type'] = $files['imageUpload']['type'][$i];
                        $_FILES['imageUpload']['tmp_name'] = $files['imageUpload']['tmp_name'][$i];
                        $_FILES['imageUpload']['error'] = $files['imageUpload']['error'][$i];
                        $_FILES['imageUpload']['size'] = $files['imageUpload']['size'][$i];
                        $_FILES['encrypt_name'] = TRUE;
                        $this->upload->initialize($this->set_upload_options());
                        $this->upload->do_upload('imageUpload');
                        $dataInfo[] = $this->upload->data();
                        $image_url = "my-assets/image/gallery/" . $dataInfo[$m]['file_name'];
                        $data = array(
                            'product_id' => $product_id,
                            'image_url' => $image_url,
                            'img_thumb' => 'null',
                        );

                        $result2 = $this->Galleries->update_gallery_image($data, $old_gallery_image[$i]);
                        unlink(FCPATH . $old_gallery_image[$i]);
                        $m++;
                    }
                } else {
                    //insert new image
                    $_FILES['imageUpload']['name'] = $files['imageUpload']['name'][$i];
                    $_FILES['imageUpload']['type'] = $files['imageUpload']['type'][$i];
                    $_FILES['imageUpload']['tmp_name'] = $files['imageUpload']['tmp_name'][$i];
                    $_FILES['imageUpload']['error'] = $files['imageUpload']['error'][$i];
                    $_FILES['imageUpload']['size'] = $files['imageUpload']['size'][$i];
                    $_FILES['encrypt_name'] = TRUE;
                    $this->upload->initialize($this->set_upload_options());
                    $this->upload->do_upload('imageUpload');
                    $dataInfo2[] = $this->upload->data();

                    $image_url = "my-assets/image/gallery/" . $dataInfo2[$n]['file_name'];
                    $imagedata = [
                        'image_gallery_id' => $this->auth->generator(15),
                        'product_id' => $product_id,
                        'image_url' => $image_url,
                        'img_thumb' => 'null',
                    ];
                    $result2 = $this->Galleries->image_entry($imagedata);
                    $n++;
                }
            }
            if ($result == true) {
                $this->session->set_userdata(array('message' => display('successfully_updated')));
                redirect('dashboard/Cproduct/manage_product');
            } else {
                $this->session->set_userdata(array('error_message' => display('product_model_already_exist')));
                redirect('dashboard/Cproduct/manage_product');
            }
        }
    }

    // Product Delete
    public function product_delete($product_id)
    {

        $this->permission->check_label('manage_product')->delete()->redirect();

        $this->db->delete('product_translation', array('product_id' => $product_id));
        $this->Products->delete_product($product_id);
    }

    //Retrieve Single Item  By Search
    public function product_by_search()
    {
        $this->permission->check_label('manage_product')->read()->redirect();

        $product_id = $this->input->post('product_id', TRUE);

        $products_list = $this->Products->product_search_item($product_id);
        $all_product_list = $this->Products->all_product_list();
        $i = 0;
        if ($products_list) {
            foreach ($products_list as $k => $v) {
                $i++;
                $products_list[$k]['sl'] = $i;
            }
            $currency_details = $this->Soft_settings->retrieve_currency_info();
            $data = array(
                'title' => display('manage_product'),
                'products_list' => $products_list,
                'all_product_list' => $all_product_list,
                'currency' => $currency_details[0]['currency_icon'],
                'position' => $currency_details[0]['currency_position'],
            );
            $data['module'] = "dashboard";
            $data['page'] = "product/product";

            echo Modules::run('template/layout', $data);
        } else {
            redirect('dashboard/Cproduct/manage_product');
        }
    }

    //Retrieve Single Item  By Search
    public function product_details($product_id)
    {
        $this->permission->check_label('manage_product')->read()->redirect();

        $product_id = urldecode($product_id);
        $details_info = $this->Products->product_details_info($product_id);
        $purchaseData = $this->Products->product_purchase_info($product_id);

        $totalPurchase = 0;
        $totalPrcsAmnt = 0;

        if (!empty($purchaseData)) {
            foreach ($purchaseData as $k => $v) {
                $purchaseData[$k]['final_date'] = $this->occational->dateConvert($purchaseData[$k]['purchase_date']);
                $totalPrcsAmnt = ($totalPrcsAmnt + $purchaseData[$k]['total_amount']);
                $totalPurchase = ($totalPurchase + $purchaseData[$k]['quantity']);
            }
        }
        $salesData = $this->Products->invoice_data($product_id);
        $totalSales = 0;
        $totaSalesAmt = 0;

        if (!empty($salesData)) {
            foreach ($salesData as $k => $v) {
                $salesData[$k]['final_date'] = $this->occational->dateConvert($salesData[$k]['date']);
                $totalSales = ($totalSales + $salesData[$k]['t_qty']);
                $totaSalesAmt = ($totaSalesAmt + $salesData[$k]['total_price']);
            }
        }

        $openQuantity = $details_info[0]['open_quantity'];
        // $stock = ($totalPurchase + $openQuantity) - $totalSales;
        $stock = ($totalPurchase - $totalSales);

        $currency_details = $this->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('product_details'),
            'product_name' => $details_info[0]['product_name'],
            'product_model' => $details_info[0]['product_model'],
            'price' => $details_info[0]['price'],
            'purchaseTotalAmount' => number_format($totalPrcsAmnt, 2, '.', ','),
            'salesTotalAmount' => number_format($totaSalesAmt, 2, '.', ','),
            'total_purchase' => $totalPurchase,
            'total_sales' => $totalSales,
            'purchaseData' => $purchaseData,
            'salesData' => $salesData,
            'stock' => $stock,
            'product_statement' => 'dashboard/Cproduct/product_sales_supplier_rate/' . $product_id,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );

        $content = $this->parser->parse('dashboard/product/product_details', $data, true);
        $this->template_lib->full_admin_html_view($content);
    }

    //Retrieve Single Item  By Search
    public function product_details_single()
    {
        $this->permission->check_label('product_ledger')->read()->redirect();

        $product_id = $this->input->post('product_id', TRUE);

        $details_info = $this->Products->product_details_info($product_id);
        $purchaseData = $this->Products->product_purchase_info($product_id);
        $products_list = $this->Products->product_list();

        $totalPurchase = 0;
        $totalPrcsAmnt = 0;

        if (!empty($purchaseData)) {
            foreach ($purchaseData as $k => $v) {
                $purchaseData[$k]['final_date'] = $this->occational->dateConvert($purchaseData[$k]['purchase_date']);
                $totalPrcsAmnt = ($totalPrcsAmnt + $purchaseData[$k]['total_amount']);
                $totalPurchase = ($totalPurchase + $purchaseData[$k]['quantity']);
            }
        }

        $salesData = $this->Products->invoice_data($product_id);
        $totalSales = 0;
        $totaSalesAmt = 0;

        if (!empty($salesData)) {
            foreach ($salesData as $k => $v) {
                $salesData[$k]['final_date'] = $this->occational->dateConvert($salesData[$k]['date']);
                $totalSales = ($totalSales + $salesData[$k]['quantity']);
                $totaSalesAmt = ($totaSalesAmt + $salesData[$k]['total_amount']);
            }
        }

        $openQuantity = $details_info[0]['open_quantity'];
        // $stock = ($totalPurchase + $openQuantity) - $totalSales;
        $stock = ($totalPurchase - $totalSales);
        $currency_details = $this->Soft_settings->retrieve_currency_info();
        $data = array(
            'title' => display('product_report'),
            'product_name' => @$details_info[0]['product_name'],
            'product_model' => @$details_info[0]['product_model'],
            'price' => @$details_info[0]['price'],
            'purchaseTotalAmount' => number_format($totalPrcsAmnt, 2, '.', ','),
            'salesTotalAmount' => number_format($totaSalesAmt, 2, '.', ','),
            'total_purchase' => $totalPurchase,
            'total_sales' => $totalSales,
            'purchaseData' => $purchaseData,
            'salesData' => $salesData,
            'stock' => $stock,
            'product_list' => $products_list,
            'product_statement' => 'dashboard/Cproduct/product_sales_supplier_rate/' . $product_id,
            'currency' => $currency_details[0]['currency_icon'],
            'position' => $currency_details[0]['currency_position'],
        );


        $data['module'] = "dashboard";
        $data['page'] = "product/product_details_single";

        echo Modules::run('template/layout', $data);
    }

    //Add supplier by ajax
    public function add_supplier()
    {
        $this->load->model('Suppliers');
        $this->form_validation->set_rules('supplier_name', display('supplier_name'), 'required');
        $this->form_validation->set_rules('mobile', display('mobile'), 'required');

        if ($this->form_validation->run() == FALSE) {
            echo '3';
        } else {
            $data = array(
                'supplier_id' => $this->auth->generator(20),
                'supplier_name' => $this->input->post('supplier_name', TRUE),
                'address' => $this->input->post('address', TRUE),
                'mobile' => $this->input->post('mobile', TRUE),
                'details' => $this->input->post('details', TRUE),
                'status' => 1
            );

            $supplier = $this->Suppliers->supplier_entry($data);

            if ($supplier == TRUE) {
                $this->session->set_userdata(array('message' => display('successfully_added')));
                echo '1';
            } else {
                $this->session->set_userdata(array('error_message' => display('already_exists')));
                echo '2';
            }
        }
    }

    // Insert category by ajax
    public function insert_category()
    {

        $category_id = $this->auth->generator(15);
        $this->form_validation->set_rules('category_name', display('category_name'), 'required');
        if ($this->form_validation->run() == FALSE) {
            echo '3';
        } else {
            //Customer  basic information adding.
            $data = array(
                'category_id' => $category_id,
                'category_name' => $this->input->post('category_name', TRUE),
                'status' => 1
            );
            $result = $this->Categories->category_entry($data);
            if ($result == TRUE) {
                $this->session->set_userdata(array('message' => display('successfully_added')));
                echo '1';
            } else {
                $this->session->set_userdata(array('error_message' => display('already_exists')));
                echo '2';
            }
        }
    }

    //Add Product CSV
    public function add_product_csv()
    {
        $this->permission->check_label('import_product_csv')->create()->redirect();
        $data = array(
            'title' => display('import_product_csv')
        );
        $content = $this->parser->parse('dashboard/product/add_product_csv', $data, true);
        $this->template_lib->full_admin_html_view($content);
    }

    //CSV Upload File
    function uploadCsv()
    {
        $this->permission->check_label('import_product_csv')->create()->redirect();
        $count = 0;
        $fp = fopen($_FILES['upload_csv_file']['tmp_name'], 'r') or die("can't open file");

        if (($handle = fopen($_FILES['upload_csv_file']['tmp_name'], 'r')) !== FALSE) {

            while ($csv_line = fgetcsv($fp, 1024)) {
                //keep this if condition if you want to remove the first row
                for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                    $insert_csv = array();
                    $insert_csv['supplier_id'] = (!empty($csv_line[0]) ? $csv_line[0] : '');
                    $insert_csv['category_id'] = (!empty($csv_line[1]) ? $csv_line[1] : '');
                    $insert_csv['product_name'] = (!empty($csv_line[2]) ? $csv_line[2] : '');
                    $insert_csv['price'] = (!empty($csv_line[3]) ? $csv_line[3] : '');
                    $insert_csv['supplier_price'] = (!empty($csv_line[4]) ? $csv_line[4] : '');
                    $insert_csv['unit'] = (!empty($csv_line[5]) ? $csv_line[5] : '');
                    $insert_csv['product_model'] = (!empty($csv_line[6]) ? $csv_line[6] : '');
                    $insert_csv['product_details'] = (!empty($csv_line[7]) ? $csv_line[7] : '');
                    $insert_csv['image_thumb'] = (!empty($csv_line[8]) ? $csv_line[8] : '');
                    $insert_csv['brand_id'] = (!empty($csv_line[9]) ? $csv_line[9] : '');
                    $insert_csv['variants'] = (!empty($csv_line[10]) ? $csv_line[10] : '');
                    $insert_csv['variant_prices'] = (!empty($csv_line[11]) ? $csv_line[11] : []);
                    $insert_csv['type'] = (!empty($csv_line[12]) ? $csv_line[12] : '');
                    $insert_csv['best_sale'] = (!empty($csv_line[13]) ? $csv_line[13] : 0);
                    $insert_csv['onsale'] = (!empty($csv_line[14]) ? $csv_line[14] : 0);
                    $insert_csv['onsale_price'] = (!empty($csv_line[15]) ? $csv_line[15] : '');
                    $insert_csv['invoice_details'] = (!empty($csv_line[16]) ? $csv_line[16] : '');
                    $insert_csv['image_large_details'] = (!empty($csv_line[17]) ? $csv_line[17] : '');
                    $insert_csv['review'] = (!empty($csv_line[18]) ? $csv_line[18] : '');
                    $insert_csv['description'] = (!empty($csv_line[19]) ? $csv_line[19] : '');
                    $insert_csv['tag'] = (!empty($csv_line[20]) ? $csv_line[20] : '');
                    $insert_csv['specification'] = (!empty($csv_line[21]) ? $csv_line[21] : '');
                    $insert_csv['status'] = (!empty($csv_line[22]) ? $csv_line[22] : 0);
                }
                if (!empty($insert_csv['image_thumb'])) {

                    $image_thumb = ((strpos($insert_csv['image_thumb'], 'my-assets/image/product/thumb/') > 0) ? $insert_csv['image_thumb'] : 'my-assets/image/product/thumb/' . $insert_csv['image_thumb']);
                } else {
                    $image_thumb = base_url('my-assets/image/product.png');
                }

                if (!empty($insert_csv['image_large_details'])) {

                    $image_large_details = ((strpos($insert_csv['image_large_details'], 'my-assets/image/product/') > 0) ? $insert_csv['image_large_details'] : 'my-assets/image/product/' . $insert_csv['image_large_details']);
                } else {
                    $image_large_details = base_url('my-assets/image/product.png');
                }

                //Data organizaation for insert to database
                $product_id = $this->generator(8);
                $data = array(
                    'product_id' => $product_id,
                    'supplier_id' => $insert_csv['supplier_id'],
                    'category_id' => $insert_csv['category_id'],
                    'product_name' => $insert_csv['product_name'],
                    'price' => $insert_csv['price'],
                    'supplier_price' => $insert_csv['supplier_price'],
                    'unit' => $insert_csv['unit'],
                    'product_model' => $insert_csv['product_model'],
                    'product_details' => $insert_csv['product_details'],
                    'image_thumb' => $image_thumb,
                    'brand_id' => $insert_csv['brand_id'],
                    'variants' => $insert_csv['variants'],
                    'variant_price' => (!empty($insert_csv['variant_prices']) ? 1 : 0),
                    'type' => $insert_csv['type'],
                    'best_sale' => $insert_csv['best_sale'],
                    'onsale' => $insert_csv['onsale'],
                    'onsale_price' => $insert_csv['onsale_price'],
                    'invoice_details' => $insert_csv['invoice_details'],
                    'image_large_details' => $image_large_details,
                    'review' => $insert_csv['review'],
                    'description' => $insert_csv['description'],
                    'tag' => $insert_csv['tag'],
                    'specification' => $insert_csv['specification'],
                    'status' => $insert_csv['status']
                );

                if ($count > 0) {
                    $result = $this->db->select('*')
                        ->from('product_information')
                        ->where('product_model', $data['product_model'])
                        ->get()
                        ->num_rows();

                    if ($result == 0 && !empty($data['product_model']) && !empty($data['supplier_id'])) {

                        $this->db->insert('product_information', $data);

                        $this->db->select('*');
                        $this->db->from('product_information');
                        $this->db->where('status', 1);
                        $query = $this->db->get();
                        foreach ($query->result() as $row) {
                            $json_product[] = array('label' => $row->product_name . "-(" . $row->product_model . ")", 'value' => $row->product_id);
                        }
                        $cache_file = './my-assets/js/admin_js/json/product.json';
                        $productList = json_encode($json_product);
                        file_put_contents($cache_file, $productList);
                    } else {

                        $this->db->where('supplier_id', $data['supplier_id']);
                        $this->db->where('product_model', $data['product_model']);
                        $this->db->update('product_information', $data);

                        $this->db->select('*');
                        $this->db->from('product_information');
                        $this->db->where('status', 1);
                        $query = $this->db->get();

                        foreach ($query->result() as $row) {
                            $json_product[] = array('label' => $row->product_name . "-(" . $row->product_model . ")", 'value' => $row->product_id);
                        }

                        $cache_file = './my-assets/js/admin_js/json/product.json';
                        $productList = json_encode($json_product);
                        file_put_contents($cache_file, $productList);
                    }

                    //Product variant prices
                    if (!empty($insert_csv['variant_prices'])) {

                        $variant_prices = explode('&', $insert_csv['variant_prices']);
                        if (is_array($variant_prices)) {

                            $vprice_list = [];

                            foreach ($variant_prices as $vitem) {

                                $vitem_list = explode(',', $vitem);

                                if (is_array($vitem_list)) {

                                    $size_variant = trim($vitem_list[0]);
                                    $color_variant = trim($vitem_list[1]);

                                    if (empty($vitem_list[2])) {
                                        $color_variant = NULL;
                                        $variant_price_amt = trim($vitem_list[1]);
                                    } else {
                                        $variant_price_amt = trim($vitem_list[2]);
                                    }

                                    if (!empty($size_variant)) {
                                        $vprice_list[] = array(
                                            'product_id' => $product_id,
                                            'var_size_id' => $size_variant,
                                            'var_color_id' => (!empty($color_variant) ? $color_variant : NULL),
                                            'price' => $variant_price_amt
                                        );
                                    }
                                }
                            }

                            if (!empty($vprice_list)) {
                                $this->db->delete('product_variants', array('product_id' => $product_id));
                                $this->db->insert_batch('product_variants', $vprice_list);
                            }
                        }
                    }
                }

                $count++;
            }
        }

        fclose($fp) or die("can't close file");
        $this->session->set_userdata(array('message' => display('successfully_added')));

        if (isset($_POST['add-product'])) {
            redirect(base_url('dashboard/Cproduct/manage_product'));
            exit;
        } elseif (isset($_POST['add-product-another'])) {
            redirect(base_url('dashboard/Cproduct'));
            exit;
        }
    }

    //This function is used to Generate Key
    public function generator($lenth)
    {

        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 8);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }

        $result = $this->Products->product_id_check($con);

        if ($result === true) {
            $this->generator(8);
        } else {
            return $con;
        }
    }

    public function get_default_variant()
    {
        $variants = $this->input->post('variants', TRUE);

        $variant_list = $this->db->select('*')->from('variant')->where_in('variant_id', $variants)->get()->result();
        $html = '';
        foreach ($variant_list as $variant) {
            $html .= '<option value="' . $variant->variant_id . '">' . $variant->variant_name . '</option>';
        }
        echo $html;
    }

    public function delete_gallery_image()
    {
        $this->permission->check_label('manage_product')->delete()->redirect();

        $imageId = $this->input->post('imageId', TRUE);

        $gallery_image = $this->db->select('image_url')->from('image_gallery')->where('image_gallery_id', $imageId)->get()->result();
        if ($gallery_image) {
            unlink(FCPATH . $gallery_image->image_url);
        }

        $this->db->where('image_gallery_id', $imageId);
        $this->db->delete('image_gallery');
    }

    public function find_filter_items()
    {
        $type_id = $this->input->post('type_id', TRUE);
        $filter_items = $this->cfiltration_model->filter_type_wise_items($type_id);
        $html = '';
        $html1 = '';
        foreach ($filter_items as $filter_items) {
            $html1 .= '<option value="' . $filter_items['item_id'] . '">' . $filter_items['item_name'] . '</option>';
        }
        $html .= '<option value=""></option>' . $html1;
        echo json_encode($html);
    }

    ////////////////////////////pricing_types///////////////////////////////////

    public function get_no_types()
    {
        $nooftypes = $this->cfiltration_model->get_no_types();

        echo json_encode($nooftypes);
    }

    public function find_pricing_types1()
    {


        $items = $this->cfiltration_model->get_all_pricing_types();
        if (count($items) > 0) {

            $select_box = '';
            $select_box .= '<option value="">' . display('select_one') . '</option>';

            foreach ($items as $item) {

                $select_box .= '<option value="' . $item->pri_type_id . '">' . $item->pri_type_name . '</option>';
            }

            echo json_encode($select_box);
        } else {

            $select_box = '';
            $select_box .= '<option value="">No Values</option>';
            echo json_encode($select_box);
        }
    }

    public function find_pricing_types2()
    {
        $idarray = $this->input->post('idarray', TRUE);

        $Data = $this->cfiltration_model->get_all_pricing_types2($idarray);

        echo json_encode($Data);
    }

    public function getpricetypes()
    {
        $product_id = $this->input->post('product_id', TRUE);
        if ($product_id) {

            $pricingtypes = $this->cfiltration_model->get_pricing_types();
            $pricingdata = $this->cfiltration_model->get_pricing_data($product_id);
            $table = ' <label for="pricing_type" class="col-sm-4 col-form-label">' . display('pricing') . '</label>'
                . ' <div class="col-sm-4">'
                . '  <div class="row pricing_type_row">'
                . ' <table class="" id="addprice"> <tbody>';

            $x = 1;

            if (isset($pricingdata) && !empty($pricingdata)) {
                foreach ($pricingdata as $key => $value) {
                    $table .= '<tr id="row' . $x . '" class="' . $x . '">'
                        . '<td class="col-sm-6">'
                        . '<div class="col-sm-12 custom_select">'
                        . '<div class="form-group row">'
                        . '<select class="form-control pricing-control width_100p pricing_type" name="pricetype[' . $x . ']" id="pricetype' . $x . '" onchange="" >';
                    if (isset($pricingtypes) && !empty($pricingtypes)) {
                        foreach ($pricingtypes as $key => $price) {
                            if ($price['pri_type_id'] == $value['pri_type_id']) {
                                $selected = 'selected';
                            } else {
                                $selected = '';
                            }
                            $table .= ' <option ' . $selected . ' value="' . $price['pri_type_id'] . '">
                                    ' . $price['pri_type_name'] . '
                                    </option>';
                        }
                    }
                    $table .= '</select></div></div>
                           </td>
                           <td class="col-sm-6">
                           <div class="col-sm-12">
                           <div class="form-group row">
                           <div class="input-group">
                           <input type="number" class="form-control text-left" onchange="check_price2(' . $x . ');"  value="' . $value['product_price'] . '" id="pricepri' . $x . '" name="pricepri[' . $x . ']" placeholder="0.00" />';
                    if ($x == 1) {
                        $table .= '<div class="input-group-addon btn btn-success" onclick="addpricerow()">
                                <i class="ti-plus"></i>
                              </div>';
                    } else {
                        $table .= '<div class="input-group-addon btn btn-danger remove_filter_row" onclick="removepricerow(' . $x . ')">
                              <i class="ti-minus"></i>
                              </div>';
                    }
                    $table .= '</div></div></div></td>
                           </tr>';
                    $x++;
                } // /.foreach        
            } else {
                $table .= '<tr id="row' . $x . '" class="' . $x . '">'
                    . '<td class="col-sm-6">'
                    . '<div class="col-sm-12 custom_select">'
                    . '<div class="form-group row">'
                    . '<select class="form-control pricing-control width_100p pricing_type" name="pricetype[' . $x . ']" id="pricetype' . $x . '" onchange="" >'
                    . '<option value=""> </option>';
                if (isset($pricingtypes) && !empty($pricingtypes)) {
                    foreach ($pricingtypes as $key => $price) {

                        $table .= ' <option value="' . $price['pri_type_id'] . '">
                                    ' . $price['pri_type_name'] . '
                                    </option>';
                    }
                }
                $table .= '</select></div></div>
                           </td>
                           <td class="col-sm-6">
                           <div class="col-sm-12">
                           <div class="form-group row">
                           <div class="input-group">
                           <input type="number" class="form-control text-left" onchange="check_price2(' . $x . ');"  value="" id="pricepri' . $x . '" name="pricepri[' . $x . ']" placeholder="0.00" />';

                $table .= '<div class="input-group-addon btn btn-success" onclick="addpricerow()">
                                <i class="ti-plus"></i>
                              </div>';

                $table .= '</div></div></div></td>
                           </tr>';
            }
            $table .= '</tbody>
	    </table></div></div>';

            echo $table;
        }
    }

    ////////////////////////////////End ////////////////////////////////////////////////////////////////////////
    public function get_assembly_products()
    {
        $product_id = $this->input->post('product_id', TRUE);
        if ($product_id) {
            $check_assembly = $this->cfiltration_model->check_assembly($product_id);
            if ($check_assembly['assembly'] == 1) {
                $assembly_products_data = $this->cfiltration_model->get_assembly_products($product_id);
                $table = ' <table class="table" id="addassemblypro">
                         <thead>
                           <tr>
                            <th class="col-sm-6 text-center">
                             <div class="btn btn-success" onclick="addassemblyprorow()">
                             Add <i class="ti-plus"></i>
                             </div>
                                ' . display('product_name') . '
                            </th>
                            <!-- <th class="col-sm-3 text-center">' . display('supplier_price') . '</th>
                            <th class="col-sm-3 text-center">' . display('sell_price') . '</th> -->
                            <th class="col-sm-3 text-center">' . display('whole_price') . '</th>
                           </tr>
                         </thead>
                        <tbody>';
                $x = 1;

                if (isset($assembly_products_data) && !empty($assembly_products_data)) {
                    foreach ($assembly_products_data as $key => $value) {
                        $pricing = $this->db->select('*')->from('pricing_types_product')->where('product_id', $value['product_id'])->get()->result_array();
                        $wholePrice = 0;
                        $customerPrice = 0;
                        foreach ($pricing as $pri) {
                            if ($pri['pri_type_id'] == 1) {
                                $wholePrice = $pri['product_price'];
                            } else {
                                $customerPrice = $pri['product_price'];
                            }
                        }
                        $table .= '<tr id="pro' . $x . '" class="' . $x . '">'
                            . '<td class="col-sm-6">'
                            . '<div class="col-sm-12">'
                            . '<div class="form-group row">'
                            . '<div class="input-group">'
                            . '<input type="text" class="form-control assemblyproductSelection"  value="' . $value['product_name'] . '-(' . $value['product_model'] . ')" onkeyup="assembly_productList(' . $x . ');"  value="" id="assemblypro' . $x . '" name="assemblypro' . $x . '" placeholder="' . display('product_name') . '" />'
                            . '<input type="hidden" class="autocomplete_hidden_value assembly_product_id_' . $x . '" value="' . $value['child_product_id'] . '" name="assembly_product_id[' . $x . ']" />';
                        $table .= '<div class="input-group-addon btn btn-danger remove_filter_row" onclick="removeassemblyprorow(' . $x . ')">
                              <i class="ti-minus"></i>
                              </div>';
                        $table .= '</div></div></div></td>'
                            . '<td class="col-sm-3">'
                            . '<div class="col-sm-12">'
                            . '<div class="form-group row">'
                            . '<input type="text" class="price_whole_item_' . $x . ' form-control" value="' . $wholePrice . '"  id="price_whole_item_' . $x . '" name="product_whole[' . $x . ']" min="0" readonly="" />'
                            . '<input type="hidden" class="price_item' . $x . ' form-control" value="' . $value['supplier_price'] . '"  id="price_item_' . $x . '" name="product_rate[' . $x . ']" min="0" readonly="" />'
                            . '</div></div></td></td>'
                            . '<td class="col-sm-3">'
                            . '<div class="col-sm-12">'
                            . '<div class="form-group row">'
                            . '<input type="hidden" class="product_price' . $x . ' form-control" value="' . $value['price'] . '"  id="product_price_' . $x . '" name="product_price[' . $x . ']" min="0" readonly="" />'
                            . '<input type="hidden" class="product_customer_price_' . $x . ' form-control" value="' . $customerPrice . '"  id="product_customer_price__' . $x . '" name="product_customer_price_[' . $x . ']" min="0" readonly="" />'
                            . '</div></div></td></tr>';
                        $x++;
                    } // /.foreach        
                } else {
                    $table .= '<tr id="pro' . $x . '" class="' . $x . '">
                        <td class="col-sm-6">
                        <div class="col-sm-12">
                        <div class="form-group row">
                        <div class="input-group">
                        <input type="text" class="form-control assemblyproductSelection"  onkeyup="assembly_productList(' . $x . ');"  value="" id="assemblypro' . $x . '" name="assemblypro' . $x . '" placeholder="' . display('product_name') . '" />
                        <input type="hidden" class="autocomplete_hidden_value assembly_product_id_' . $x . '" value="" name="assembly_product_id[' . $x . ']" />
                        <div class="input-group-addon btn btn-danger remove_filter_row" onclick="removeassemblyprorow(' . $x . ')">
                              <i class="ti-minus"></i>
                              </div>';
                    $table .= '</div></div></div></td>'
                        . '<td class="col-sm-6">'
                        . '<div class="col-sm-12">'
                        . '<div class="form-group row">'
                        . '<input type="text" class="price_whole_item_' . $x . ' form-control" value=""  id="price_whole_item_' . $x . '" name="product_whole[' . $x . ']" min="0" readonly="" />'
                        . '<input type="hidden" class="price_item' . $x . ' form-control" value=""  id="price_item_' . $x . '" name="product_rate[' . $x . ']" min="0" readonly="" />'
                        . '<input type="hidden" class="product_price' . $x . ' form-control" value=""  id="product_price_' . $x . '" name="product_rate[' . $x . ']" min="0" readonly="" />'
                        . '<input type="hidden" class="product_customer_price_' . $x . ' form-control" value=""  id="product_customer_price__' . $x . '" name="product_customer_price_[' . $x . ']" min="0" readonly="" />'
                        . '</div></div></td></tr>';
                }
                $table .= '</tbody>
	    </table></div></div>';
                echo $table;
            } else {
                $table = ' <div class="alert alert-warning" role="alert">
               <p style="text-align: center;color: black;">This is not an assembly product</p>
                <!-- Alert Message -->
            </div>';
                echo $table;
            }
        }
    }

    ////////////////////////////////End ////////////////////////////////////////////////////////////////////////



    public function find_filter_types()
    {
        $f_count = $this->input->post('f_count', true);
        $filter_types = $this->cfiltration_model->get_all_types();
        $filter_types_html = '<div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="filter_type" class="col-sm-3 col-form-label"> ' . display('filter_type') . '</label>
                                    <div class="col-sm-9">
                                        <select class="form-control filter-control width_100p filter_type" name="filter_type[]" data-sl="' . $f_count . '">
                                            <option value=""> ' . display('select_one') . ' </option>';
        foreach ($filter_types as $filter_type) {
            $filter_types_html .= '<option value="' . $filter_type['fil_type_id'] . '">
                                                     ' . $filter_type['fil_type_name'] . '
                                                </option>';
        }
        $filter_types_html .= '</select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="filter_name" class="col-sm-3 col-form-label"> ' . display('filter_names') . '
                                    </label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <select class="form-control filter-control width_100p" name="filter_name[]" id="filter_name_' . $f_count . '">
                                                <option value=""> ' . display('select_one') . ' </option>
                                            </select>
                                            <div class="input-group-addon btn btn-danger remove_filter_row">
                                                <i class="ti-minus"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
        echo json_encode($filter_types_html);
    }

    public function languages()
    {
        $settings = $this->db->select('language')->from('soft_setting')->where('setting_id', 1)->get()->row();
        if ($this->db->table_exists($this->table)) {
            $fields = $this->db->field_data($this->table);
            $i = 1;
            foreach ($fields as $field) {
                if ($i++ > 2)
                    $result[$field->name] = ucfirst($field->name);
            }
            if (!empty($result)) {
                $langusges = array_diff($result, array($settings->language => ucfirst($settings->language)));
                return $langusges;
            }
            return false;
        } else {
            return false;
        }
    }

    public function add_translation()
    {
        $count = $this->input->post('row_count', TRUE);
        $languages = $this->languages();
        $new_row_html = '<div style="margin-bottom: 35px;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="language" class="col-sm-4 col-form-label">' . display('language') . '</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <select class="product-control" id="language" style="width: 100%" name="language[' . $count . ']">
                                                    <option value=""></option>';
        if (!empty($languages)) {
            foreach ($languages as $lkey => $lvalue) {
                $new_row_html .= '<option value="' . $lvalue . '" >' . $lvalue . '</option>';
            }
        }
        $new_row_html .= '</select>
                                                <div class="input-group-addon btn btn-danger remove_row">
                                                    <i class="ti-minus"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">    
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="product_name" class="col-sm-4 col-form-label"> ' . display('product_name') . ' </label>
                                        <div class="col-sm-8">
                                            <input class="form-control" name="trans_name[' . $count . ']" autofocus type="text" id="product_name" placeholder="' . display('product_name') . '">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <label for="details" class="col-sm-2 col-form-label">' . display('details') . '</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control summernote" name="details[' . $count . ']" id="details" rows="1" placeholder="' . display('details') . '"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="description" class="col-sm-4 col-form-label">' . display('description') . '</label>
                                        <div class="col-md-8">
                                            <textarea name="description[' . $count . ']" class="form-control summernote" placeholder="' . display('description') . '" id="description" row="1"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="specification" class="col-sm-4 col-form-label"> ' . display('specification') . ' </label>
                                        <div class="col-md-8">
                                            <textarea name="specification[' . $count . ']" class="form-control summernote" placeholder="' . display('specification') . '" id="specification" row="1"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
        echo $new_row_html;
    }

    public function product_excel_import()
    {
        $this->permission->check_label('import_product_excel')->read()->redirect();
        $data = array(
            'title' => display('import_product_excel')
        );
        $content = $this->parser->parse('dashboard/product/add_product_excel', $data, true);
        $this->template_lib->full_admin_html_view($content);
    }

    public function importImg($src)
    {

        if (!empty($src)) {
            $img = file_get_contents($src);
            $im = imagecreatefromstring($img);
            $image = 'dodizzbeauty' . time() . '.jpg';

            #-----------------------------------
            #  Large Image
            #-----------------------------------
            $newwidth = '825';
            $newheight = '630';
            list($width, $height) = getimagesize($src);
            $ratio = max($newwidth / $width, $newheight / $height);
            $h = ceil($newheight / $ratio);
            $x = ($width - $newwidth / $ratio) / 2;
            $w = ceil($newwidth / $ratio);
            $large = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresized($large, $im, 0, 0, $x, 0, $newwidth, $newheight, $w, $h);
            imagejpeg($large, 'my-assets/image/product/' . $image); //save image as jpg
            imagedestroy($large);

            $image_large_details = $image;
            return $image_large_details;
        } else {
            return $src;
        }
    }

    public function importThumbImg($src)
    {
        if (!empty($src)) {
            $img = file_get_contents($src);
            $im = imagecreatefromstring($img);
            $image = 'dodizzbeauty' . time() . '.jpg';
            #-----------------------------------
            #  Thumb Image
            #-----------------------------------
            $newwidth1 = '370';
            $newheight1 = '300';
            list($width, $height) = getimagesize($src);
            $ratio = max($newwidth1 / $width, $newheight1 / $height);
            $h1 = ceil($newheight1 / $ratio);
            $x1 = ($width - $newwidth1 / $ratio) / 2;
            $w1 = ceil($newwidth1 / $ratio);
            $thumb = imagecreatetruecolor($newwidth1, $newheight1);
            imagecopyresized($thumb, $im, 0, 0, $x1, 0, $newwidth1, $newheight1, $w1, $h1);
            imagejpeg($thumb, 'my-assets/image/product/thumb/' . $image); //save image as jpg
            imagedestroy($thumb);
            #------------------------------------
            $image_thumb = $image;
            return $image_thumb;
        } else {
            return $src;
        }
    }

    //This function is used to Generate Key
    public function generator_voucher($lenth)
    {
        $number = array("6", "2", "9", "4", "5", "1", "8", "7", "3", "0");
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

    //    public function product_excel_insert() {
    //        $upload_file = $_FILES["upload_excel_file"]["name"];
    //        $extension = pathinfo($upload_file, PATHINFO_EXTENSION);
    //        if ($extension == 'csv') {
    //            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    //        } elseif ($extension == 'xls') {
    //            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
    //        } else {
    //            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    //        }
    //        $spreadsheet = $reader->load($_FILES["upload_excel_file"]["tmp_name"]);
    //        $sheetdata = $spreadsheet->getActiveSheet()->toArray();
    //        $datacount = count($sheetdata);
    //        if ($datacount > 1) {
    //            for ($i = 1; $i < $datacount; $i++) {
    //
    //                $supplier_id = $sheetdata[$i][0];
    //                $category_id = $sheetdata[$i][1];
    //                $product_name = $sheetdata[$i][2];
    //                $price = $sheetdata[$i][3];
    //                $supplier_price = $sheetdata[$i][4];
    //                $unit = $sheetdata[$i][5];
    //                $product_model = $sheetdata[$i][6];
    //                $product_details = $sheetdata[$i][7];
    //                $image_thumb = $sheetdata[$i][8];
    //                $brand_id = $sheetdata[$i][9];
    //                $variants = $sheetdata[$i][10];
    //                $variant_price = $sheetdata[$i][11];
    //                $type = $sheetdata[$i][12];
    //                $best_sale = $sheetdata[$i][13];
    //                $onsale = $sheetdata[$i][14];
    //                $onsale_price = $sheetdata[$i][15];
    //                $invoice_details = $sheetdata[$i][16];
    //                $image_large_details = $sheetdata[$i][17];
    //                $review = $sheetdata[$i][18];
    //                $description = $sheetdata[$i][19];
    //                $tag = $sheetdata[$i][20];
    //                $specification = $sheetdata[$i][21];
    //                $status = $sheetdata[$i][22];
    //                $arabic_product_name = $sheetdata[$i][23];
    //                $arabic_product_detail = $sheetdata[$i][24];
    //                $arabic_product_description = $sheetdata[$i][25];
    //                $arabic_product_specification = $sheetdata[$i][26];
    //
    //                $image_large = $this->importImg(str_replace(' ', '%20', $image_large_details));
    //                $thumb_image = $this->importThumbImg(str_replace(' ', '%20', $image_thumb));
    //
    //                $excel = array(
    //                    'supplier_id' => $supplier_id,
    //                    'category_id' => $category_id,
    //                    'product_name' => $product_name,
    //                    'price' => $price,
    //                    'supplier_price' => $supplier_price,
    //                    'unit' => $unit,
    //                    'product_model' => $product_model,
    //                    'product_details' => $product_details,
    //                    'image_thumb' => $thumb_image,
    //                    'brand_id' => $brand_id,
    //                    'variants' => $variants,
    //                    'variant_price' => $variant_price,
    //                    'type' => $type,
    //                    'best_sale' => $best_sale,
    //                    'onsale' => $onsale,
    //                    'onsale_price' => $onsale_price,
    //                    'invoice_details' => $invoice_details,
    //                    'image_large_details' => $image_large,
    //                    'review' => $review,
    //                    'description' => $description,
    //                    'tag' => $tag,
    //                    'specification' => $specification,
    //                    'status' => $status,
    //                    'trans_name' => $arabic_product_name,
    //                    'trans_detail' => $arabic_product_detail,
    //                    'trans_description' => $arabic_product_description,
    //                    'trans_specification' => $arabic_product_specification,
    //                );
    //                if (!empty($excel['image_thumb'])) {
    //                    $image_thumb = ((strpos($excel['image_thumb'], 'my-assets/image/product/thumb/') > 0) ? $excel['image_thumb'] : 'my-assets/image/product/thumb/' . $excel['image_thumb']);
    //                } else {
    //                    $image_thumb = base_url('my-assets/image/product.png');
    //                }
    //                if (!empty($excel['image_large_details'])) {
    //                    $image_large_details = ((strpos($excel['image_large_details'], 'my-assets/image/product/') > 0) ? $excel['image_large_details'] : 'my-assets/image/product/' . $excel['image_large_details']);
    //                } else {
    //                    $image_large_details = base_url('my-assets/image/product.png');
    //                }
    //                $product_id = $this->generator(8);
    //                $product_details = array(
    //                    'product_id' => $product_id,
    //                    'supplier_id' => $excel['supplier_id'],
    //                    'category_id' => $excel['category_id'],
    //                    'product_name' => $excel['product_name'],
    //                    'price' => $excel['price'],
    //                    'supplier_price' => $excel['supplier_price'],
    //                    'unit' => $excel['unit'],
    //                    'product_model' => $excel['product_model'],
    //                    'product_details' => $excel['product_details'],
    //                    'image_thumb' => $image_thumb,
    //                    'brand_id' => $excel['brand_id'],
    //                    'variants' => $excel['variants'],
    //                    'variant_price' => (!empty($excel['variant_price']) ? 1 : 0),
    //                    'type' => $excel['type'],
    //                    'best_sale' => $excel['best_sale'],
    //                    'onsale' => $excel['onsale'],
    //                    'onsale_price' => $excel['onsale_price'],
    //                    'invoice_details' => $excel['invoice_details'],
    //                    'image_large_details' => $image_large_details,
    //                    'review' => $excel['review'],
    //                    'description' => $excel['description'],
    //                    'tag' => $excel['tag'],
    //                    'specification' => $excel['specification'],
    //                    'status' => $excel['status']
    //                );
    //                $this->db->insert('product_information', $product_details);
    //                $this->db->select('*');
    //                $this->db->from('product_information');
    //                $this->db->where('status', 1);
    //                $query = $this->db->get();
    //                foreach ($query->result() as $row) {
    //                    $json_product[] = array('label' => $row->product_name . "-(" . $row->product_model . ")", 'value' => $row->product_id);
    //                }
    //                $cache_file = './my-assets/js/admin_js/json/product.json';
    //                $productList = json_encode($json_product);
    //                file_put_contents($cache_file, $productList);
    //
    //                //Product variant prices
    //                if (!empty($excel['variant_price'])) {
    //
    //                    $variant_prices = explode('&', $excel['variant_price']);
    //                    if (is_array($variant_prices)) {
    //
    //                        $vprice_list = [];
    //
    //                        foreach ($variant_prices as $vitem) {
    //
    //                            $vitem_list = explode(',', $vitem);
    //
    //                            if (is_array($vitem_list)) {
    //
    //                                $size_variant = trim($vitem_list[0]);
    //                                $color_variant = trim($vitem_list[1]);
    //
    //                                if (empty($vitem_list[2])) {
    //                                    $color_variant = NULL;
    //                                    $variant_price_amt = trim($vitem_list[1]);
    //                                } else {
    //                                    $variant_price_amt = trim($vitem_list[2]);
    //                                }
    //
    //                                if (!empty($size_variant)) {
    //                                    $vprice_list[] = array(
    //                                        'product_id' => $product_id,
    //                                        'var_size_id' => $size_variant,
    //                                        'var_color_id' => (!empty($color_variant) ? $color_variant : NULL),
    //                                        'price' => $variant_price_amt
    //                                    );
    //                                }
    //                            }
    //                        }
    //
    //                        if (!empty($vprice_list)) {
    //                            $this->db->delete('product_variants', array('product_id' => $product_id));
    //                            $this->db->insert_batch('product_variants', $vprice_list);
    //                        }
    //                    }
    //                }
    //                // Product translation
    //                if (!empty($excel['trans_name'])) {
    //                    $trans_name = $excel['trans_name'];
    //                    $trans_detail = $excel['trans_detail'];
    //                    $trans_description = $excel['trans_description'];
    //                    $trans_specification = $excel['trans_specification'];
    //                    $translation_list = array(
    //                        'language' => 'Arabic',
    //                        'product_id' => $product_id,
    //                        'trans_name' => $trans_name,
    //                        'trans_details' => $trans_detail,
    //                        'trans_description' => $trans_description,
    //                        'trans_specification' => $trans_specification,
    //                    );
    //                    $this->db->insert('product_translation', $translation_list);
    //                }
    //            }
    //            $this->session->set_userdata(array('message' => display('successfully_added')));
    //            redirect('dashboard/Cproduct/manage_product');
    //        }
    //    }

    public function product_excel_insert_old()
    {
        ini_set('memory_limit', '5000000000M');
        set_time_limit(5000000000);
        $upload_file = $_FILES["upload_excel_file"]["name"];
        $extension = pathinfo($upload_file, PATHINFO_EXTENSION);
        if ($extension == 'csv') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } elseif ($extension == 'xls') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $reader->load($_FILES["upload_excel_file"]["tmp_name"]);
        $sheetdata = $spreadsheet->getActiveSheet()->toArray();
        $datacount = count($sheetdata);
        $voucher_no        = 'StockOP-' . $this->generator_voucher(7);
        $voucher_date      = date('Y-m-d H:i:s');
        $store_id = "SDMQ4TIBSH6LAJ1";
        if ($datacount > 1) {
            for ($i = 1; $i < $datacount; $i++) {
                $cogs_price = 0;
                $price_types_list = [];
                $filter_list = [];
                $brand_id = $sheetdata[$i][0];
                $product_model = $sheetdata[$i][1] . ' - ' . $sheetdata[$i][2];
                $category_id = $sheetdata[$i][3];
                $filter_1 = $sheetdata[$i][4];
                $filter_2 = $sheetdata[$i][5];
                $variants = $sheetdata[$i][6]; //size
                $price = $sheetdata[$i][7];
                $g_price = $sheetdata[$i][8];
                $s_price = $sheetdata[$i][9];
                $product_quantity = $sheetdata[$i][10];
                $product_rate = $sheetdata[$i][11];
                //GET BRAND NAME
                $this->db->select('brand_name');
                $this->db->from('brand');
                $this->db->where('brand_id', $brand_id);
                $query = $this->db->get();
                $product_name = $query->result_array()[0]['brand_name'] . ' - ' . $product_model;
                //$product_name .= ' - Full'; //for assembly

                $excel = array(
                    'brand_id' => $brand_id,
                    'product_model' => $product_model,
                    'category_id' => $category_id,
                    'price' => $price,
                    'g_price' => $g_price,
                    's_price' => $s_price,
                    'filter_1' => $filter_1,
                    'filter_2' => $filter_2,
                    'product_name' => $product_name,
                    'variants' => $variants,
                );

                $product_id = $this->generator(8);
                $product_details = array(
                    'product_id' => $product_id,
                    'brand_id' => $excel['brand_id'],
                    'product_model' => $excel['product_model'],
                    'category_id' => $excel['category_id'],
                    'price' => $excel['price'],
                    'product_name' => $excel['product_name'],
                    'variants' => $excel['variants'],
                    'open_quantity' => $product_quantity,
                    'open_rate' => $product_rate,
                    'supplier_price' => $product_rate,
                    'pricing' => 1,
                    //'assembly' => 1, //for assembly
                );
                $this->db->insert('product_information', $product_details);
                //opening balance
                if ($product_quantity > 0 && $product_rate > 0) {
                    $find_active_fiscal_year = $this->db->select('*')->from('acc_fiscal_year')->where('status', 1)->get()->row();
                    if (!empty($find_active_fiscal_year)) {
                        //Stock opening Details
                        $cogs_price       += ($product_rate * $product_quantity);
                        $store = array(
                            'transfer_id'   => $this->auth->generator(15),
                            'voucher_no'    => $voucher_no,
                            'store_id'      => $store_id,
                            'product_id'    => $product_id,
                            'variant_id'    => $variants,
                            'variant_color' => NULL,
                            'date_time'     => $voucher_date,
                            'quantity'      => $product_quantity,
                            'status'        => 3
                        );
                        $this->db->insert('transfer', $store);
                        // stock
                        $stock = array(
                            'store_id'     => $store_id,
                            'product_id'   => $product_id,
                            'variant_id'   => $variants,
                            'variant_color' => NULL,
                            'quantity'     => $product_quantity,
                            'warehouse_id' => '',
                        );
                        $this->db->insert('purchase_stock_tbl', $stock);
                    }
                }
                if ($category_id == 'XJIMM9X3ZAWUYXQ') {
                    $data = array(
                        't_p_s_id' => $this->auth->generator(15),
                        'product_id' => $product_id,
                        'tax_id' => '52C2SKCKGQY6Q9J',
                        'tax_percentage' => '14',
                    );

                    $this->db->insert('tax_product_service', $data);
                }

                $price_types_list[] = array(
                    'product_id' => $product_id,
                    'pri_type_id' => 1,
                    'product_price' => $excel['g_price'],
                );
                $price_types_list[] = array(
                    'product_id' => $product_id,
                    'pri_type_id' => 2,
                    'product_price' => $excel['s_price'],
                );
                $this->db->insert_batch('pricing_types_product', $price_types_list);
                //GENDER
                $filter_list[] = array(
                    'category_id' => $category_id,
                    'product_id' => $product_id,
                    'filter_type_id' => 1,
                    'filter_item_id' => $filter_1
                );
                //MATERIAL
                $filter_list[] = array(
                    'category_id' => $category_id,
                    'product_id' => $product_id,
                    'filter_type_id' => 2,
                    'filter_item_id' => $filter_2
                );
                $this->db->insert_batch('filter_product', $filter_list);

                $this->db->select('*');
                $this->db->from('product_information');
                $this->db->where('status', 1);
                $query = $this->db->get();
                foreach ($query->result() as $row) {
                    //$json_product[] = array('label' => $row->product_name . "-(" . $row->product_model . ")", 'value' => $row->product_id);
                    $json_product[] = array('label' => $row->product_name, 'value' => $row->product_id);
                }
                $cache_file = './my-assets/js/admin_js/json/product.json';
                $productList = json_encode($json_product);
                file_put_contents($cache_file, $productList);
                if ($product_quantity > 0 && $product_rate > 0) {
                    $find_active_fiscal_year = $this->db->select('*')->from('acc_fiscal_year')->where('status', 1)->get()->row();
                    if (!empty($find_active_fiscal_year)) {
                        $this->load->model('accounting/account_model');
                        //$store_head   = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('store_id', $store_id)->get()->row();
                        $createdate   = date('Y-m-d H:i:s');
                        $receive_by   = $this->session->userdata('user_id');
                        $date         = $createdate;
                        //1st Inventory-Openning total price debit
                        $store_debit = array(
                            'fy_id'     => $find_active_fiscal_year->id,
                            'VNo'       => $voucher_no,
                            'Vtype'     => 'Inventory-Openning',
                            'VDate'     => $date,
                            'COAID' => 1141, //Main Warehouse
                            'Narration' => 'Inventory-Openning total price debited at Main warehouse',
                            //                    'COAID'     => $store_head->HeadCode, //Main Warehouse
                            //                    'Narration' => 'Inventory-Openning total price debited at ' . $store_head->HeadName,
                            'Debit'     => $cogs_price,
                            'Credit'    => 0, //purchase price asbe
                            'IsPosted'  => 1,
                            'CreateBy'  => $receive_by,
                            'CreateDate' => $createdate,
                            'store_id'  => $store_id,
                            'IsAppove'  => 1
                        );

                        //2nd Inventory-Openning COGS Credit
                        $COGSCredit = array(
                            'fy_id'     => $find_active_fiscal_year->id,
                            'VNo'       => $voucher_no,
                            'Vtype'     => 'Inventory-Openning',
                            'VDate'     => $date,
                            'COAID'     => 4111,
                            'Narration' => 'Inventory-Openning total price credited at COGS',
                            'Debit'     => 0,
                            'Credit'    => $cogs_price,
                            'IsPosted'  => 1,
                            'CreateBy'  => $receive_by,
                            'CreateDate' => $createdate,
                            'store_id'  => $store_id,
                            'IsAppove'  => 1
                        );
                        $this->db->insert('acc_transaction', $store_debit);
                        $this->db->insert('acc_transaction', $COGSCredit);
                    }
                }
            }
            $this->session->set_userdata(array('message' => display('successfully_added')));
            redirect('dashboard/Cproduct/manage_product');
        }
    }

    public function product_excel_insert()
    {
        ini_set('memory_limit', '5000000000M');
        set_time_limit(5000000000);
        $upload_file = $_FILES["upload_excel_file"]["name"];
        $extension = pathinfo($upload_file, PATHINFO_EXTENSION);
        if ($extension == 'csv') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } elseif ($extension == 'xls') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $reader->load($_FILES["upload_excel_file"]["tmp_name"]);
        $sheetdata = $spreadsheet->getActiveSheet()->toArray();
        $datacount = count($sheetdata);
        $voucher_no        = 'StockOP-' . $this->generator_voucher(7);
        $voucher_date      = date('Y-m-d H:i:s');
        $store_id = "SDMQ4TIBSH6LAJ1";
        if ($datacount > 1) {
            // echo "<pre>";print_r($sheetdata);exit;
            for ($i = 1; $i < $datacount; $i++) {
                $cogs_price = 0;
                $price_types_list = [];
                $filter_list = [];
                $brand_id = trim($sheetdata[$i][0]);
                $product_model = trim($sheetdata[$i][1]) . ' - ' . trim($sheetdata[$i][2]);
                $product_model_only = trim($sheetdata[$i][1]);
                $product_color = trim($sheetdata[$i][2]);
                $category_id = trim($sheetdata[$i][3]);
                $filter_1 = trim($sheetdata[$i][4]); // gender or any other name
                $filter_2 = trim($sheetdata[$i][5]);  // material or any other name
                $variants = trim($sheetdata[$i][6]); //size
                $price = (float)$sheetdata[$i][7]; // sell price
                $g_price = (float)$sheetdata[$i][8]; // whole price
                $s_price = (float)$sheetdata[$i][9]; // customer price
                $product_quantity = (int)$sheetdata[$i][10];
                $product_rate = (float)$sheetdata[$i][11]; // supplier price

                // echo "<pre>";var_dump($variants);exit;

                //GET BRAND NAME
                $this->db->select('brand_id');
                $this->db->from('brand');
                $this->db->where('brand_name', $brand_id);
                $brandName = $brand_id;
                $brandIsFound = $this->db->get()->row();
                if (!$brandIsFound) {
                    // check if brand name is empty
                    if (empty($brand_id)) {
                        // check if there is a brand with the name Default
                        $defaultBrand = $this->db->select('brand_id')->from('brand')->where('brand_name', 'Default')->get()->row();
                        if (!$defaultBrand) {
                            // create a new brand with the name Default
                            $brand_id = 'Default';
                        } else {
                            $brand_id = $defaultBrand->brand_id;
                            $brandIsFound = true;
                        }
                    }
                    if (!$brandIsFound) {
                        // create new brand with that name
                        $new_brand_id = $this->auth->generator(15);
                        $brand_data = array(
                            'brand_id'   => $new_brand_id,
                            'brand_name' => $brand_id,
                            'brand_image' => null,
                            'website'    => '',
                            'status'     => 1
                        );
                        $this->Brands->brand_entry($brand_data);
                        $brand_id = $new_brand_id;
                    }
                } else {
                    $brand_id = $brandIsFound->brand_id;
                }

                // check if category_id is not found
                $category_name = $category_id;
                $categoryIsFound = $this->db->select('category_id')->from('product_category')->where('category_name', $category_id)->get()->row();
                if (!$categoryIsFound) {
                    // check if new category name is empty
                    if (empty($category_id)) {
                        // check if there is a category with the name Default
                        $defaultCategory = $this->db->select('category_id')->from('product_category')->where('category_name', 'Default')->get()->row();
                        if (!$defaultCategory) {
                            // create a new category with the name default
                            $category_id = 'Default';
                        } else {
                            $category_id = $defaultCategory->category_id;
                            $categoryIsFound = true;
                        }
                    }

                    if (!$categoryIsFound) {
                        // create new category with that name
                        $new_category_id = generator(15);
                        $category_data = array(
                            'category_id' => $new_category_id,
                            'category_name' => $category_id,
                            'top_menu' => 1,
                            'menu_pos' => 1,
                            'cat_favicon' => 'my-assets/image/category.png',
                            'parent_category_id' => '',
                            'cat_image' => 'my-assets/image/category.png',
                            'cat_type' => 1,
                            'status' => 1
                        );
                        $this->Categories->category_entry($category_data);
                        $category_id = $new_category_id;
                    }
                } else {
                    $category_id = $categoryIsFound->category_id;
                }

                // check if size variant is exists
                $variantIsFound = $this->db->select('variant_id')->from('variant')->where('variant_name', $variants)->get()->row();
                if (!$variantIsFound) {
                    // check if new variant name is empty
                    if (empty($variants)) {
                        // check if there is a variant with the name Default
                        $defaultVariant = $this->db->select('variant_id')->from('variant')->where('variant_name', 'Default')->get()->row();
                        if (!$defaultVariant) {
                            // create a new variant with the name default
                            $variants = 'Default';
                        } else {
                            $variants = $defaultVariant->variant_id;
                            $variantIsFound = true;
                        }
                    }

                    if (!$variantIsFound) {
                        // create new varient size and then attach it`s id to product
                        $variant_id = $this->auth->generator(15);
                        $variant_data = array(
                            'variant_id' => $variant_id,
                            'variant_name' => $variants,
                            'variant_type' => 'size',
                            'color_code' => '#000000',
                            'status' => 1
                        );

                        $result = $this->Variants->variant_entry($variant_data);
                        $variants = $variant_id;
                    }

                    if ($result) {
                        // add this size variant to only current category
                        // check if varient was added to category before
                        $variant_category_added = $this->db->select('variant_id')->from('category_variant')->where('category_id', $category_id)->where('variant_id', $variants)->get()->num_rows();
                        if (!$variant_category_added) {
                            $this->db->query("INSERT INTO `category_variant` (`category_id`, `variant_id`, `created_at`, `updated_at`) VALUES (" . $this->db->escape($category_id) . ", " . $this->db->escape($variants) . ", now(), now())");
                        }
                    }
                } else {
                    $variants = $variantIsFound->variant_id;
                }

                // get first filter and material filter id
                $filter_1_type_id = $this->db->select('*')->from('filter_types')->where('fil_type_name', $sheetdata[0][4])->get()->row();
                if (!$filter_1_type_id) {
                    // first filter type is not found
                    // then create one
                    $filter_type_data = array(
                        'fil_type_name' => $sheetdata[0][4]
                    );
                    $this->db->insert('filter_types', $filter_type_data);
                    $filter_1_type_id = $this->db->insert_id();
                } else {
                    $filter_1_type_id = $filter_1_type_id->fil_type_id;
                }
                // check for filter_1 item
                $filter_1_item_id = $this->db->select('*')->from('filter_items')->where('type_id', $filter_1_type_id)->where('item_name', $filter_1)->get()->row();
                if (!$filter_1_item_id) {
                    // check if name is empty then set as default
                    if (empty($filter_1_item_id)) {
                        $defaultFilter_1 = $this->db->select('*')->from('filter_items')->where('type_id', $filter_1_type_id)->where('item_name', 'Default')->get()->row();
                        if (!$defaultFilter_1) {
                            // if not found then create one
                            $filter_1 = 'Default';
                        } else {
                            $filter_1 = $defaultFilter_1->item_id;
                            $filter_1_item_id = $defaultFilter_1->item_id;
                        }
                    }

                    if (!$filter_1_item_id) {
                        // create new filter item
                        $filter_item_data = [
                            'item_name' => $filter_1,
                            'type_id' => $filter_1_type_id,
                        ];
                        $this->db->insert('filter_items', $filter_item_data);
                        $filter_1 = $this->db->insert_id();
                    }
                } else {
                    $filter_1 = $filter_1_item_id->item_id;
                }

                // get second filter and material filter id
                $filter_2_type_id = $this->db->select('*')->from('filter_types')->where('fil_type_name', $sheetdata[0][5])->get()->row();
                if (!$filter_2_type_id) {
                    // second filter type is not found
                    // then create one
                    $filter_type_data_2 = array(
                        'fil_type_name' => $sheetdata[0][5]
                    );
                    $this->db->insert('filter_types', $filter_type_data_2);
                    $filter_2_type_id = $this->db->insert_id();
                } else {
                    $filter_2_type_id = $filter_2_type_id->fil_type_id;
                }
                // check for filter_2 item
                $filter_2_item_id = $this->db->select('*')->from('filter_items')->where('type_id', $filter_2_type_id)->where('item_name', $filter_2)->get()->row();
                if (!$filter_2_item_id) {
                    // check if name is empty then set as default
                    if (empty($filter_2_item_id)) {
                        $defaultFilter_2 = $this->db->select('*')->from('filter_items')->where('type_id', $filter_2_type_id)->where('item_name', 'Default')->get()->row();
                        if (!$defaultFilter_2) {
                            // if not found then create one
                            $filter_2 = 'Default';
                        } else {
                            $filter_2 = $defaultFilter_2->item_id;
                            $filter_2_item_id = $defaultFilter_2->item_id;
                        }
                    }

                    if (!$filter_2_item_id) {
                        // create new filter item
                        $filter_item_data_2 = [
                            'item_name' => $filter_2,
                            'type_id' => $filter_2_type_id,
                        ];
                        $this->db->insert('filter_items', $filter_item_data_2);
                        $filter_2 = $this->db->insert_id();
                    }
                } else {
                    $filter_2 = $filter_2_item_id->item_id;
                }

                $product_name = $brandName . ' - ' . $product_model;
                //$product_name .= ' - Full'; //for assembly

                $excel = array(
                    'brand_id' => $brand_id,
                    'product_model' => $product_model,
                    'category_id' => $category_id,
                    'price' => $price,
                    'g_price' => $g_price,
                    's_price' => $s_price,
                    'filter_1' => $filter_1,
                    'filter_2' => $filter_2,
                    'product_name' => $product_name,
                    'variants' => $variants,
                );

                $product_id = $this->generator(8);
                $product_details = array(
                    'product_id' => $product_id,
                    'brand_id' => $excel['brand_id'],
                    'product_model' => $excel['product_model'],
                    'category_id' => $excel['category_id'],
                    'price' => $excel['price'],
                    'product_name' => $excel['product_name'],
                    'variants' => $excel['variants'],
                    'open_quantity' => $product_quantity,
                    'open_rate' => $product_rate,
                    'supplier_price' => $product_rate,
                    'pricing' => 1,
                    'product_model_only' => $product_model_only,
                    'product_color' => $product_color,
                    'created_at' => date('Y-m-d H:i:s'),
                    //'assembly' => 1, //for assembly
                );
                // echo "<pre>";
                // var_dump('si => ' . $variants, 'cat => ' . $category_id, 'bra => ' . $brand_id, 'gen => ' . $filter_1_type_id. ' -- itm => ' . $filter_1, 'mat => ' .$filter_2_type_id . '  -- itm => ' . $filter_2);
                // print_r($sheetdata[0]);
                // print_r($sheetdata[$i]);
                // print_r($product_details);
                // exit;

                // check if product is already exists
                $exists = $this->db->select('product_id')
                    ->from('product_information')
                    ->where('product_name', $product_details['product_name'])
                    ->where('category_id', $product_details['category_id'])
                    ->get()->result_array();

                if (count($exists)) {
                    // product is found then update
                    $product_details['product_id'] = $exists[0]['product_id'];
                    $this->db->set($product_details);
                    $this->db->where('product_id', $exists[0]['product_id']);
                    $this->db->update('product_information');

                } else {
                    $this->db->insert('product_information', $product_details);
                }
                //opening balance
                if ($product_quantity > 0 && $product_rate > 0) {
                    $find_active_fiscal_year = $this->db->select('*')->from('acc_fiscal_year')->where('status', 1)->get()->row();
                    if (!empty($find_active_fiscal_year)) {
                        //Stock opening Details
                        $cogs_price       += ($product_rate * $product_quantity);
                        $store = array(
                            'transfer_id'   => $this->auth->generator(15),
                            'voucher_no'    => $voucher_no,
                            'store_id'      => $store_id,
                            'product_id'    => $product_id,
                            'variant_id'    => $variants,
                            'variant_color' => NULL,
                            'date_time'     => $voucher_date,
                            'quantity'      => $product_quantity,
                            'status'        => 3
                        );
                        $this->db->insert('transfer', $store);
                        // stock
                        $stock = array(
                            'store_id'     => $store_id,
                            'product_id'   => $product_id,
                            'variant_id'   => $variants,
                            'variant_color' => NULL,
                            'quantity'     => $product_quantity,
                            'warehouse_id' => '',
                        );
                        $this->db->insert('purchase_stock_tbl', $stock);
                    }
                }
                if ($category_name == 'SUNGLASSES') {
                    $data = array(
                        't_p_s_id' => $this->auth->generator(15),
                        'product_id' => $product_id,
                        'tax_id' => '52C2SKCKGQY6Q9J',
                        'tax_percentage' => '14',
                    );

                    $this->db->insert('tax_product_service', $data);
                }

                $price_types_list[] = array(
                    'product_id' => $product_id,
                    'pri_type_id' => 1,
                    'product_price' => $excel['g_price'],
                );
                $price_types_list[] = array(
                    'product_id' => $product_id,
                    'pri_type_id' => 2,
                    'product_price' => $excel['s_price'],
                );
                $this->db->insert_batch('pricing_types_product', $price_types_list);
                //GENDER
                $filter_list[] = array(
                    'category_id' => $category_id,
                    'product_id' => $product_id,
                    'filter_type_id' => 1,
                    'filter_item_id' => $filter_1
                );
                //MATERIAL
                $filter_list[] = array(
                    'category_id' => $category_id,
                    'product_id' => $product_id,
                    'filter_type_id' => 2,
                    'filter_item_id' => $filter_2
                );
                $this->db->insert_batch('filter_product', $filter_list);

                $this->db->select('*');
                $this->db->from('product_information');
                $this->db->where('status', 1);
                $query = $this->db->get();
                foreach ($query->result() as $row) {
                    //$json_product[] = array('label' => $row->product_name . "-(" . $row->product_model . ")", 'value' => $row->product_id);
                    $json_product[] = array('label' => $row->product_name, 'value' => $row->product_id);
                }
                $cache_file = './my-assets/js/admin_js/json/product.json';
                $productList = json_encode($json_product);
                file_put_contents($cache_file, $productList);
                if ($product_quantity > 0 && $product_rate > 0) {
                    $find_active_fiscal_year = $this->db->select('*')->from('acc_fiscal_year')->where('status', 1)->get()->row();
                    if (!empty($find_active_fiscal_year)) {
                        $this->load->model('accounting/account_model');
                        //$store_head   = $this->db->select('HeadCode,HeadName')->from('acc_coa')->where('store_id', $store_id)->get()->row();
                        $createdate   = date('Y-m-d H:i:s');
                        $receive_by   = $this->session->userdata('user_id');
                        $date         = $createdate;
                        //1st Inventory-Openning total price debit
                        $store_debit = array(
                            'fy_id'     => $find_active_fiscal_year->id,
                            'VNo'       => $voucher_no,
                            'Vtype'     => 'Inventory-Openning',
                            'VDate'     => $date,
                            'COAID' => 1141, //Main Warehouse
                            'Narration' => 'Inventory-Openning total price debited at Main warehouse',
                            //                    'COAID'     => $store_head->HeadCode, //Main Warehouse
                            //                    'Narration' => 'Inventory-Openning total price debited at ' . $store_head->HeadName,
                            'Debit'     => $cogs_price,
                            'Credit'    => 0, //purchase price asbe
                            'IsPosted'  => 1,
                            'CreateBy'  => $receive_by,
                            'CreateDate' => $createdate,
                            'store_id'  => $store_id,
                            'IsAppove'  => 1
                        );

                        //2nd Inventory-Openning COGS Credit
                        $COGSCredit = array(
                            'fy_id'     => $find_active_fiscal_year->id,
                            'VNo'       => $voucher_no,
                            'Vtype'     => 'Inventory-Openning',
                            'VDate'     => $date,
                            'COAID'     => 4111,
                            'Narration' => 'Inventory-Openning total price credited at COGS',
                            'Debit'     => 0,
                            'Credit'    => $cogs_price,
                            'IsPosted'  => 1,
                            'CreateBy'  => $receive_by,
                            'CreateDate' => $createdate,
                            'store_id'  => $store_id,
                            'IsAppove'  => 1
                        );
                        $this->db->insert('acc_transaction', $store_debit);
                        $this->db->insert('acc_transaction', $COGSCredit);
                    }
                }
            }
            $this->session->set_userdata(array('message' => display('successfully_added')));
            redirect('dashboard/Cproduct/manage_product');
        }
    }

    public function viewpro()
    {
        $product_id = $this->input->post('proid');
        $viewdata = $this->cfiltration_model->get_assembly_products($product_id);
        $tabledata = '';
        $tabledata .= '
 <table  class="table table-striped table-bordered text-inputs-table">
                        <thead>
                            <tr>
                             <th class="col-sm-6 text-center">' . display('product_name') . '  </th>
                             <!-- <th class="col-sm-3 text-center">' . display('supplier_price') . ' </th>
                             <th class="col-sm-3 text-center"> ' . display('sell_price') . ' </th> -->
                             <th class="col-sm-6 text-center">' . display('whole_price') . ' </th>
                            </tr>
                        </thead>
                        <tbody >';

        if (isset($viewdata) && !empty($viewdata)) {
            foreach ($viewdata as $key => $value) {
                $pricing = $this->db->select('*')->from('pricing_types_product')->where('product_id', $value['product_id'])->get()->result_array();
                $wholePrice = 0;
                foreach ($pricing as $pri) {
                    if ($pri['pri_type_id'] == 1) {
                        $wholePrice = $pri['product_price'];
                    }
                }
                $tabledata .= '
                        <tr>
                        <td class="col-sm-6">
                        <div class="col-sm-12">
                        <div class="form-group row">
                        <div class="input-group">
                        <input type="text" name="" value="' . $value['product_name'] . '-(' . $value['product_model'] . ')" id="" class="form-control"  min="0" readonly="" />
                        </div>
                        </div>
                        </div>
                        </td>
                        <td class="col-sm-6">
                        <div class="col-sm-12">
                        <div class="form-group row">
                        <input type="text" name="" value="' . $wholePrice . '" id="" class="form-control"  min="0" readonly="" />
                        <input type="hidden" name="" value="' . $value['supplier_price'] . '" id="" class="form-control"  min="0" readonly="" />
                        </div>
                        </div>
                        </td>
                        <!-- <td class="col-sm-3">
                        <div class="col-sm-12">
                        <div class="form-group row">
                        <input type="hidden" name="" value="' . $value['price'] . '" id="" class="form-control"  min="0" readonly="" />
                        </div>
                        </div>
                        </td> -->        
                        </tr>';
            }
        }
        $tabledata .= '</tbody> </table>';
        echo json_encode($tabledata);
    }
}
