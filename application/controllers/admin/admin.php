<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin:loc.testerp.com'); 

class Admin extends MY_Controller
{
    public function __construct(){
        parent::__construct();
    }

   /*  public function get_one(){
        $this->load->model('common/admin/Get_admin_model','get_admin');
        $info=$this->get_admin->testMc();
        $this->r['data']=$info;
        $this->state=true;
        $this->r();
    } */
   
    public function post_index(){
        $this->load->model('admin/Manage_admin_model','manage_admin_model');
        $data=$this->input->post();
        $rule=array(
            ''
        );
    }
    
    /**
     * 获取一条数据
     * @author jieyang
     */
    public function get_admin_one(){
        $this->load->model('common/admin/Get_admin_model','get_admin_model');
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
     * @author jieyang
     */
    public function get_admin_list(){
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
    
    /**
     * 删除管理员
     * @author jieyang
     */
    public function delete_index(){
        $this->load->model('admin/Manage_admin_model','manage_admin_model');
        $data=$this->input->post();
        $rule=array(
            'id'=>array('egNum',null,true)
        );
        $this->verify->verify($rule,$data);
        $admin_id=$data['id'];
        $updata=array(
            'is_on'=>0,
            'update_time'=>time()
        );
        $this->manage_admin_model->delete($admin_id,$updata);
        $this->state=true;
        $this->r();
    }

    /**
     * 导出列表
     */
    public function get_admin_export(){  
        $this->load->model('common/admin/Get_admin_model','get_admin_model');  
        //文件名
        $tile_name='管理员列表';
        //表身数据
        $list= $this->get_admin_model->getListExcel();
        $this->load->library('Excel',true,'exp');
        //表头数据
        $table_arr=array();
        $table_arr[0]='真实名称';
        $table_arr[1]='手机号'; 
        $table_arr[2]='添加时间'; 
        //调用导出的方法
        //table_arr 表头的数组 list 表身的数组 tile_name 导出的excel名 
        $url = $this->exp->get_export($table_arr,$list,$tile_name);
        $this->r['data']['url'] = $url;
        $this->state = true;
        $this->r();
         
    }

}
