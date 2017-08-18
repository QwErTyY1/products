<?php defined('BASEPATH') OR exit('No direct script access allowed');

require __DIR__ . '/Abstact_controller.php';

class Home extends Abstact_controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
    }


    public function index($pages = 0)
    {


        $this->add_js("sweetalert.min.js");
        $this->add_css("sweetalert.css");

        $page = ($pages != 0 && is_integer($pages))? intval($pages) : $pages;

        $total = $this->HomeModel->get_total_content("product");


        $per_page = 5;

        $config = $this->config->item('pagination');
        $config['base_url'] = base_url().'/';
        $config['total_rows'] = $total;
        $config['per_page'] = $per_page;

        $this->pagination->initialize($config);

        $product = new stdClass();

        if (!empty($this->HomeModel->getAll()) && is_array($this->HomeModel->getAll())){
            $product->allProduct = $this->HomeModel->getAll("",$per_page,$page);

            foreach ($product->allProduct as $item){

                if ($item->product_image !== null){
                    $item->product_image = $this->convertString($item->product_image);
                }
            }
        }

        if (!empty($this->HomeModel->getTypeProducts()) && is_array($this->HomeModel->getAll())){
            $product->productType = $this->HomeModel->getTypeProducts();
        }else{
            $product->productType = "Fields is empty!!";
        }

        if (!empty($this->HomeModel->getCategoryProducts()) && is_array($this->HomeModel->getCategoryProducts())){
            $product->categorys = $this->HomeModel->getCategoryProducts();
        } else {
            $product->categorys = "Fields is empty!!";
        }



        $this->template("home/index", $product);
//        $this->template("home/index", $product, true);

    }


    public function adding()
    {

        $res = [];

        $this->load->helper('form');
        $this->load->library('form_validation');

        $config['encrypt_name'] = true;
        $config['upload_path'] = './img/product/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_filename'] = '10';
        $config['remote_spaces'] = true;

        $this->load->library('upload' ,$config);


        if ($this->upload->do_upload("images") === true){
            $strArray = array('upload_data'=>$this->upload->data()['file_name']);

            if ($this->reSizeImages($this->upload->data()['full_path'],$strArray["upload_data"]) == true) {

            }

        } else{
//                $strArray = array('error' =>$this->upload->display_errors());
            $strArray = null;
        }



        $config = array(
            array(
                'field'   => 'select_content',
                'label'   => 'Select content',
                'rules'   => ''
            ),
            array(
                'field'   => 'select_content_category',
                'label'   => 'Select content category',
                'rules'   => ''
            ),
            array(
                'field'   => 'select_content_type',
                'label'   => 'Select content type',
                'rules'   => ''
            ),
            array(
                'field'   => 'adding',
                'label'   => 'Adding',
                'rules'   => 'trim|min_length[4]|max_length[200]'
            ),
            array(
                'field'   => 'pr_id',
                'label'   => 'pr_id',
                'rules'   => 'numeric'
            ));

        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() === false) {

            $res['status'] = "ERR";
            $res['error_valid'] = $this->form_validation->error_array();

        }else {

            $pr_id = $this->input->post('pr_id');
            $schema = $this->input->post('schema');

//            if ($this->HomeModel->get_element_in_db($pr_id,$schema)){
            if (!empty($pr_id)){
                $update_data = [];


                if ($strArray !== null){

                    $update_data = [
                        "product_image" =>   $strArray["upload_data"],
                        "product_name"    => $this->input->post('other_products_conten'),
                    ];

                } else{
                     $update_data[$schema.'_name'] = $this->input->post('other_products_conten');

                }


                if ($this->HomeModel->update_product($pr_id,$schema, $update_data)){
                        $res['status'] = "OK";
                        $res['success'] = $schema." update!";
                }

            }else{

            $select_content = $this->input->post("select_content");

            if ($select_content === "product"){

                if ($strArray !== null){
                    $adding = [
                        "product_image" => $strArray["upload_data"],
                        "product_name"    => $this->input->post('adding'),
                        "product_type_id" => $this->input->post("select_content_type"),
                        "product_category_id" => $this->input->post("select_content_category"),
                    ];
                }else{
                    $adding = [
                        "product_name"    => $this->input->post('adding'),
                        "product_type_id" => $this->input->post("select_content_type"),
                        "product_category_id" => $this->input->post("select_content_category"),
                    ];
                }


            } else {
                $adding = $this->input->post('adding');
            }

            if ($this->HomeModel->addContent($select_content, $adding)){

                $res['status'] = "OK";
                $res['success'] = "Create new content!";
            }
        }
    }

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(array('html'=> $res)));

    }

    public function deleted()
    {
        $res = [];

        $pr_id = $this->input->post('pr_id');
        $schema = $this->input->post('schema');
        $name_content = $this->input->post('other_products_conten');

        $std = new stdClass();
        $img = $this->HomeModel->get_element_in_db($pr_id,"product",$schema);

        $std->image = $img->product_image;

        $puthImg = __DIR__."/../../img/product/";
        $puthThumbs = __DIR__."/../../img/thumbs/";

        if ($this->HomeModel->delContent($pr_id,$schema)){

            if ($schema == "product"){
                if (!empty($std->image) && $std->image !== "default_product.jpg"){
                    $puthImg .= $std->image;
                    $puthThumbs .= $this->convertString($std->image);
                    unlink($puthImg);
                    unlink($puthThumbs);
                }
            }
            $res['status'] = "OK";
            $res['success'] = $name_content." successfully deleted!";

        }else {
            $res['status'] = "Error Data Base!!";
        }


//        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(array('html'=> $res)));

    }


    public function search_product()
    {
        $search = $this->input->post('search');


        $product = new stdClass();
        $product->productType = null;
        $product->categorys  = null;

        $product->allProduct = $this->HomeModel->getAll($search);

        foreach ($product->allProduct as $item){

            if ($item->product_image !== null){
                $item->product_image = $this->convertString($item->product_image);
            }
        }

        echo  $this->template("home/index", $product, true);

    }

    public function ajaxChangeProduct()
    {

        $res = [];

        $type_product = $this->HomeModel->getTypeProducts();

        $category_product = $this->HomeModel->getCategoryProducts();

        $res['type_product'] = $type_product;
        $res['category_product'] = $category_product;


        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(array('html'=> $res)));


    }


}