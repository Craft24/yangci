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
        $this->load->model('admin/Manage_brand_model','manage_brand_model');
        $data=$this->input->post();
        $rule=array(
            'brand_name'=>array(null,null,true),
            'brand_logo'=>array(null,null,true),
            'brand_code'=>array(null,null,true),
            'remarks'=>array(null,null,true),
            'sort'=>array('egNum',null,true)
        );
        $this->verify->verify($rule,$data);
        foreach($rule as $k=>$v){
            isset($data[$k])?$save[$k]=$data[$k]:'';
        }
        $save['add_time']=time();
        $this->manage_brand_model->add($save);
        $this->state=true;
        $this->r();
    }
    
    /**
     * 获取品牌
     * @author jieyang
     */
    public function get_index(){
        $id=$this->input->get('id');
        if($id){
            $this->__getOne();
        }else{
            $this->__getList();
        }
    }
    
    /**
     * 获取品牌细节
     * @author jieyang
     */
    private function __getOne(){
        $this->load->model('common/brand/Get_brand_model','get_brand_model');
        $data=$this->input->get();
        $rule=array(
            'id'=>array('egNum',null,true)
        );
        $this->verify->verify($rule,$data);
        $brand_id=$data['id'];
        $info=$this->get_brand_model->getOne($brand_id);
        $this->r['data']=$info;
        $this->state=true;
        $this->r();
    }
    
    /**
     * 获取品牌列表
     * @author jieyang
     */
    private function __getList(){
        $this->load->library('page');
        $this->load->model('common/brand/Get_brand_model','get_brand_model');
        $data=$this->input->get();
        $rule=array(
            'brand_name'=>array(null,null,false),
            'brand_code'=>array(null,null,false)
        );
        $this->verify->verify($rule,$data);
        $list=$this->get_one_page_data($this->page,$this->get_brand_model,'getList','getListLength',array($data));
        $this->r(array('lists'=>$list,'page'=>$this->page->getPage()));
        $this->state=true;
        $this->r();
    }
    
    /**
     * 修改品牌
     * @author jieyang
     */
    public function put_index(){
        $this->load->model('admin/Manage_brand_model','manage_brand_model');
        $data=$this->input->post();
        $rule=array(
            'id'=>array('egNum',null,true),
            'brand_name'=>array(null,null,false),
            'brand_logo'=>array(null,null,false),
            'brand_code'=>array(null,null,false),
            'remarks'=>array(null,null,false),
            'sort'=>array('num',null,false)
        );
        $this->verify->verify($rule,$data);
        foreach($rule as $k=>$v){
            isset($data[$k])?$save[$k]=$data[$k]:'';
        }
        $brand_id=$save['id'];
        unset($save['id']);
        $save['update_time']=time();
        $this->manage_brand_model->edit($brand_id,$save);
        $this->state=true;
        $this->r();
    }
    
    /**
     * 删除品牌
     * @author jieyang
     */
    public function delete_index(){
        $this->load->model('admin/Manage_brand_model','manage_brand_model');
        $data=$this->input->post();
        $rule=array(
            'id'=>array('egNum',null,true)
        );
        $this->verify->verify($rule,$data);
        $brand_id=$data['id'];
        $update=array(
            'is_on'=>0,
            'update_time'=>time()
        );
        $this->manage_brand_model->delete($brand_id,$update);
        $this->state=true;
        $this->r();
    }
    

}
