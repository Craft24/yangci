<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin:loc.testerp.com'); 

class Admin extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取一条数据
     */
    function get_admin_one(){
        $this->load->model('common/admin/Get_admin_model','get_admin');
        $data=$this->input->get();
        $rule=array(
            'id'=>array('egNum',null,true)
        );
        $this->verify->verify($rule,$data);
        $admin_id=$data['id'];
        $info=$this->get_admin->getOne($admin_id);
        $this->r['data']=$info;
        $this->state=true;
        $this->r();
    }
    
    /**
     * 获取数据列表
     */
    function get_admin_list(){
        $this->load->library('page');
        $this->load->model('common/admin/Get_admin_model','get_admin');
        $data=$this->input->get();
        $rule=array(
            'admin_name'=>array(null,null,false) 
        );
        $this->verify->verify($rule,$data);
        $list=$this->get_one_page_data($this->page,$this->get_admin,'getList','getListLength',array($data));
        $this->r(array('lists'=>$list,'page'=>$this->page->getPage()));
        $this->state=true;
        $this->r();
    }
    
    function delete_index(){
        $this->load->model('admin/Manage_admin_model','manage_admin_model');
        $data=$this->input->post();
        $rule=array(
            'id'=>array('egNum',null,true)
        );
        $this->verify->verify($rule,$data);
        $admin_id=$data['id'];
        $updata=array(
            'is_on'=>0,
            'update_time'=>$this->time()
        );
        $this->manage_admin_model->delete($admin_id,$updata);//删除管理员表
        $this->state=true;
        $this->r();
    }

}
