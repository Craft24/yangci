<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Wx_Api_1_0_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->_auth('session',__CLASS__,TRUE);
    }
    
    /**
     * 获取首页
     * @author jieyang 
     */
    public function get_index(){          
        $user_id = $this->user['user_id'];
        $user_id = 1;
        $this->load->library('module/shop/C_Index',null,'index');
        $data = $this->index->info($user_id);
        $this->r['data'] = $data;
        $this->state=true;
        $this->r();
    }

    /**
     * 获取首页商品
     * @author jieyang 
     */
    public function get_goods(){          
        $data=$this->input->get();
        $rule=array(
            'goods_name'=>array(null,null,false),
            'page_size'=>array(null,null,false),
            'page_now'=>array(null,null,false)
        );
        $this->verify->verify($rule,$data);
        $save = array();
        foreach($rule as $k=>$v){
            isset($data[$k])?$save[$k]=$data[$k]:'';
        }
        $this->load->library('module/shop/C_Index',null,'index');
        $lists = $this->index->goods($save);
        $this->r['lists'] = $lists['lists'];
        $this->r['page'] = $lists['page'];
        $this->state=true;
        $this->r();
    }

    /**
     * 获取首页搜索商品
     * @author jieyang 
     */
    public function get_find_goods(){          
        $data=$this->input->get();
        $rule=array(
            'goods_name'=>array(null,null,false),
            'page_size'=>array(null,null,false),
            'page_now'=>array(null,null,false)
        );
        $this->verify->verify($rule,$data);
        $save = array();
        foreach($rule as $k=>$v){
            isset($data[$k])?$save[$k]=$data[$k]:'';
        }
        $this->load->library('module/shop/C_Index',null,'index');
        $lists = $this->index->search($save);
        $this->r['lists'] = $lists['lists'];
        $this->r['page'] = $lists['page'];
        $this->state=true;
        $this->r();
    }
}

?>