<?php defined('BASEPATH') OR exit('No direct script access allowed');


class HomeModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getProduct()
    {
       return $query = $this->db->get('product');
    }

    public function getAll($search_term = "", $limit = 5, $offset = null)
    {
        $this->db->select('
                        product.*,
                        product_type.*,
                        category.*              
        ');
        $this->db->from('product');
        $this->db->join('product_type', 'product.product_type_id = product_type.product_type_id', 'left');
        $this->db->join('category', 'product.product_category_id  = category.category_id', 'left');
//        $this->db->order_by('product_id','desc');
        if ($search_term != ""){
            $this->db->like('product_name', $search_term,"after");
        }

        return $this->db->get("",$limit, $offset)->result();
    }

    public function get_total_content($table)
    {
        return intval($this->db->get($table)->num_rows());
    }

    public function getCategoryProducts()
    {
        $this->db->select('*');
        $this->db->from('category');

        return $this->db->get()->result();
    }


    public function getTypeProducts()
    {

        $this->db->select('*');
        $this->db->from('product_type');

        return $this->db->get()->result();
    }

    public function delContent($id_content,$schema)
    {
        $table = $schema.'_id';

        $this->db->where($table, $id_content);
        $this->db->delete($schema);
        $this->db->delete($schema, array($table => $id_content));
        return true;
    }


    public function update_product($id_content,$schema, $update_data)
    {
        $table = $schema.'_id';

        if (!empty($update_data)) {

            $this->db->where($table, $id_content);
            return $this->db->update($schema, $update_data);
        }
        return false;
    }

    public function get_element_in_db($id_content,$schema)
    {
        $table_id = $schema.'_id';
        $this->db->from($schema);
        $this->db->where($table_id, $id_content);
        return $this->db->get()->row();
    }

    public function search_product($search_term)
    {


        $this->db->select('
                        product.*,
                        product_type.*,
                        category.*              
        ');
        $this->db->from('product');
        $this->db->join('product_type', 'product.product_type_id = product_type.product_type_id', 'left');
        $this->db->join('category', 'product.product_type_id  = category.category_id', 'left');
        $this->db->like('product_name', $search_term['product_name']);

        return $this->db->get()->result();

    }

    public function addContent($table, $content)
    {
        $name = "";

        switch ($table){
            case "category":
                $name = "category_name";
                break;
            case "product_type":
                $name = "product_type_name";
                break;
            case "product":
                $name = "product_name";
                break;
        }
        if ($table !== null && !empty($content)){
            $data[$name]  = $content;
            if ($table === "product" && is_array($content)){
                $this->db->insert($table,$content);
            } else{
                $this->db->insert($table,$data);
            }
        }
        return true;
    }
}