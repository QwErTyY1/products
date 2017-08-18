<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Abstact_controller extends CI_Controller
{

    private $includes;

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('session'));
        $this->load->helper(array('url'));

        $models = $this->getModels();

        if ($models !== "" || !empty($models)){

            foreach ($models as $model){

                $this->load->model(substr($model,0,9));

            }

        }

    }

    protected function add_js($file='')
    {

        $str = '';

        $header_js  = $this->config->item('header_js');

        if(empty($file)){
            return;
        }

        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
                $header_js[] = $item;
            }
            $this->config->set_item('header_js',$header_js);
        }else{
            $str = $file;
            $header_js[] = $str;
            $this->config->set_item('header_js',$header_js);
        }

        foreach($header_js AS $item){
            $str .= '<script type="text/javascript" src="'.base_url().'assets/js/'.$item.'"></script>'."\n";
        }
        echo $str;

    }

    function add_css($file='')
    {

        $str = "";

        $header_css = $this->config->item('header_css');
        if(empty($file)){
            return;
        }
        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
                $header_css[] = $item;
            }
            $this->config->set_item('header_css',$header_css);
        }else{
            $str = $file;
            $header_css[] = $str;
            $this->config->set_item('header_css',$header_css);
        }

        foreach($header_css AS $item){
            $str .= '<link rel="stylesheet" href="'.base_url().'assets/css/'.$item.'" type="text/css" />'."\n";
        }

        echo $str;

    }

    protected function convertString($str)
    {
//        $str = "14993346597.png";
        $string = strrev($str);
        $a = substr($string,0,3).''.substr($string, 3, 1)."bmuht_".substr($string, 4, strlen($string));
        $string = strrev($a);

        return $string;
    }

    protected function reSizeImages($fullPath,$nameImage)
    {

        $config['image_library'] = 'gd2';
        $config['source_image']	= $fullPath.'/'.$nameImage;
        $config['new_image'] = './img/thumbs';
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width']	= 75;
        $config['height']	= 50;

        $this->load->library('image_lib', $config);

        $this->image_lib->resize();

        if ( ! $this->image_lib->resize())
        {
            return false;
        }
        return true;

    }

    protected function getModels()
    {
        $dir    = __DIR__.'/../models/';
        $files = scandir($dir);
        $files2 = [];


        foreach ($files as $file){

            if ($file == "." || $file == ".." || $file == "index.html"){
                continue;
            }

            $files2[] = $file;

        }

        return $files2;

    }


    protected function _is_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            return true;
        }
    }

    public function template($template_name, $vars = array(), $return = FALSE)
    {

        if($return):
            $content  = $this->load->view('_templates/header', $vars, $return);
            $content .= $this->load->view($template_name, $vars, $return);
            $content .= $this->load->view('_templates/footer', $vars, $return);

            return $content;
        else:
            $this->load->view('_templates/header', $vars);
            $this->load->view($template_name, $vars);
            $this->load->view('_templates/footer', $vars);
        endif;

    }


}