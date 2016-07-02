<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends MY_Controller{
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * 添加品牌 
     * @author jieyang
     */
    public function post_index(){
        $this->load->modle('admin/Manage_brand_model','manage_brand_model');
        $data=$this->input->post();
        $rule=array(
            'brand_name'=>array(null,null,true),
            'brand_logo'=>array(null,null,true),
            'brand_code'=>array(null,null,true),
            'remarks'=>array(null,null,true),
            'sort'=>array('egNum',null,true)
        );
        $this->verify->verify($rule,$data);
        $this->manage_brand_model->add($data);
        $this->state=true;
        $this->r();
    }

  
}
