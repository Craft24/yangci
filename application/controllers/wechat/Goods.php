<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Goods extends Wx_Api_1_0_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->_auth('session',__CLASS__,TRUE);
    }
    
    /**
     * 获取商品细节信息
     * @author jieyang 
     */
    public function get_goods_detail(){
        $data=$this->input->get();
        $rule=array(
            'goods_id'=>array('egNum',null,true)
        );
        $this->verify->verify($rule,$data);
        $goods_id=$data['goods_id'];
        $uid = $this->user['user_id'];
        $this->load->library('module/shop/C_Goods',null,'goods');
        $data = $this->goods->detail($goods_id,$uid);
		$this->r['data'] = $data;
		$this->state=true;
		$this->r();		
    }

    /**
     * 获取首页商品列表
     * @author jieyang
     */
    public function get_index(){
        $this->load->model('v1_0/common/Get_goods_base_model','get_goods_base_model');
		$this->load->library('page');
		$data=$this->input->get();
		$rule=array(
		    'cat_id'=>array(null,null,false)
		);
		$this->verify->verify($rule,$data);
		$uid=$this->user['user_id'];
		$list=$this->get_one_page_data($this->page,$this->get_goods_base_model,'getList','getListLength',array($data));
		$this->r(array('lists'=>$list,'page'=>$this->page->getPage()));
		$this->state=true;
		$this->r();
    }
    
    /**
     * 获取商品顶级分类
     * @author jieyang
     */
    public function get_category(){
        $this->load->model('v1_0/common/Get_category_model','get_category_model');
        $list=$this->get_category_model->getParent();
        $this->r['lists']=$list;
        $this->state=true;
        $this->r();
    }
    
    /**
     * 获取挑选商品列表
     * @author jieyang
     */
    public function get_book_goods(){
        $this->load->model('v1_0/common/Get_goods_base_model','get_goods_base_model');
        $this->load->library('page');
        $data=$this->input->get();
        $rule=array(
            'cat_id'=>array(null,null,false)
        );
        $this->verify->verify($rule,$data);
        $uid=$this->user['user_id'];
        $list=$this->get_goods_base_model->getListAll($data);
        if($list){
            $this->load->model('v1_0/common/Get_shopping_car_model','get_shopping_car_model');
            //判断是否购买
            foreach($list as $k=>$v){
                try {
                    $list[$k]['buy_num']=$this->get_shopping_car_model->getgoodsNum($uid,$v['goods_id']);
                } catch (Exception $e) {
                    $list[$k]['buy_num']=0;
                }                
            }
        }
        $this->r(array('lists'=>$list));
        $this->state=true;
        $this->r();
    }
    
    /**
     * 删除购物车无效数据
     * @author jieyagn
     */
    private function __delete_car_goods($goods_id){
        $this->load->model('v1_0/wechat/Manage_shopping_car_model','manage_shopping_car_model');
        $uid=$this->user['user_id'];
        $this->manage_shopping_car_model->delete($uid,$goods_id);
    }
    
  
}
?>