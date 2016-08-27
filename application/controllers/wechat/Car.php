<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 购物车
 */
class Car extends Wx_Api_1_0_Controller{

  	public function __construct(){
		parent::__construct();
		$this->_auth('session',__CLASS__,TRUE);
   	}
   	  	
   	/**
   	 * 添加购物车数据
   	 * @author jieyang
   	 */
   	public function post_index(){
   	    $data=$this->input->post();
   	    $rule=array(
   	        'goods_id'=>array('egNum',null,true),
   	        'goods_num'=>array('egNum',null,true),
   	    );
   	    $this->verify->verify($rule,$data);
   	    foreach($data as $k=>$v){
   	        isset($data[$k])?$save[$k]=$data[$k]:'';
   	    }
   	    $save['uid']=$this->user['user_id'];
   	    $save['add_time']=$this->time();
   	    $this->load->model('v1_0/wechat/Manage_shopping_car_model','manage_shopping_car_model');
   	    $this->manage_shopping_car_model->add($save);
   	    $this->state = true;
   	    $this->r();
   	}
   	
   	/**
   	 * 编辑商品列表购买数据
   	 * @author jieyang
   	 */
   	public function put_index(){
   	    $data=$this->input->post();
   	    $rule=array(
   	        'goods_id'=>array('egNum',null,true),
   	        'goods_num'=>array('num',null,true)
   	    );
   	    $this->verify->verify($rule,$data);
   	    foreach($data as $k => $v){
   	        isset($data[$k])?$save[$k]=$data[$k]:'';
   	    }
   	    $this->load->model('v1_0/wechat/Manage_shopping_car_model','manage_shopping_car_model');
   	    $uid=$this->user['user_id'];
   	    $data_up=array(
   	        'goods_num'=>$save['goods_num'],
   	        'update_time'=>$this->time()
   	    );
   	    $this->manage_shopping_car_model->edit($uid,$save['goods_id'],$data_up);
   	    $this->state=true;
   	    $this->r();
   	}
   	
   	/**
   	 * 删除商品列表购买数据
   	 * @author jieyang
   	 */
   	public function delete_index(){
   	    $data=$this->input->post();
   	    $rule=array(
   	        'goods_id'=>array(null,null,true)
   	    );
   	    $this->verify->verify($rule,$data);
   	    foreach($data as $k => $v){
   	        isset($data[$k])?$save[$k]=$data[$k]:'';
   	    }
   	    $this->load->model('v1_0/wechat/Manage_shopping_car_model','manage_shopping_car_model');
   	    $uid=$this->user['user_id'];
   	    $this->manage_shopping_car_model->delete($uid,$data['goods_id']);
   	    $this->state=true;
   	    $this->r();
   	}
   	
   	/**
   	 * 获取购物车列表
   	 * @author jieyang
   	 */
   	public function get_index(){
   	    $uid=$this->user['user_id'];
   	    $this->load->model('v1_0/common/Get_shopping_car_model','get_shopping_car_model');
   	    $goods_arr=$this->get_shopping_car_model->getGoodsIdList($uid);
   	    if($goods_arr){
   	        $this->load->model('v1_0/wechat/Manage_shopping_car_model','manage_shopping_car_model');
   	        $this->load->model('v1_0/common/Get_goods_base_model','get_goods_base_model');
   	        foreach ($goods_arr as $k=>$v){
   	            try {
   	                $info=$this->get_goods_base_model->getGoodsCheck($v['goods_id']);
   	                if($info['is_bracket']!=1){
   	                    //商品已下架
   	                    $this->__delete_car_goods($v['goods_id']);
   	                }else{
   	                    $list[$k]['car_id']=$v['car_id'];
   	                    $list[$k]['goods_id']=$v['goods_id'];
   	                    $list[$k]['goods_num']=$v['goods_num'];
   	                    $list[$k]['goods_name']=$info['goods_name'];
   	                    $list[$k]['shop_price']=$info['shop_price'];
   	                    //$list[$k]['goods_price']=$info['shop_price']*$v['goods_num'];
   	                }
   	            } catch (Exception $e) {
   	                //商品不存在
   	                $this->__delete_car_goods($v['goods_id']);
   	            }
   	        }
   	    }
   	    $this->r['lists']=$list;
   	    $this->state = true;
   	    $this->r();
   	}
   		   	 	
   	/**
   	 * 编辑购物车
   	 * @author jieyang
   	 */
   	public function put_car_info(){
   	    $data=$this->input->post();
   	    $rule=array(
   	        'car_id'=>array('egNum',null,true),
   	        'goods_num'=>array('num',null,true)
   	    );
   	    $this->verify->verify($rule,$data);
   	    foreach($data as $k => $v){
   	        isset($data[$k])?$save[$k]=$data[$k]:'';
   	    }
   	    $this->load->model('v1_0/wechat/Manage_shopping_car_model','manage_shopping_car_model');
   	    $data_up=array(
   	        'goods_num'=>$save['goods_num'],
   	        'update_time'=>$this->time()
   	    );
   	    $this->manage_shopping_car_model->editCar($save['car_id'],$data_up);
   	    $this->state=true;
   	    $this->r();
   	}
   		
   	/**
   	 * 删除购物车记录
   	 * @author jieyang
   	 */
   	public function delete_car_info(){
   	    $data=$this->input->post();
   	    $rule=array(
   	        'car_id'=>array('egNum',null,true)
   	    );
   	    $this->verify->verify($rule,$data);
   	    foreach($data as $k => $v){
   	        isset($data[$k])?$save[$k]=$data[$k]:'';
   	    }
   	    $this->load->model('v1_0/wechat/Manage_shopping_car_model','manage_shopping_car_model');
   	    $this->manage_shopping_car_model->delCar($save['car_id']);
   	    $this->state=true;
   	    $this->r();
   	}
   	
   	/**
   	 * 判断购车中商数量
   	 * @author jieyang
   	 */
   	public function get_car_num(){
   	    $this->load->model('v1_0/common/Get_shopping_car_model','get_shopping_car_model');
   	    $uid=$this->user['user_id'];
   	    $list=$this->get_shopping_car_model->getGoodsIdList($uid); //goods_id,goods_num
   	    $num=0;
   	    if($list){
   	        $this->load->model('v1_0/common/Get_goods_base_model','get_goods_base_model');
   	        foreach($list as $k=>$v){
   	            try {
   	                $goods_info=$this->get_goods_base_model->getGoodsCheck($v['goods_id']);
   	                if($goods_info['is_bracket']!=1){
   	                    $this->__delete_car_goods($v['goods_id']);
   	                }
   	                $num +=$v['goods_num'];
   	            } catch (Exception $e) {
   	                $this->__delete_car_goods($v['goods_id']);
   	            }
   	        }
   	    }
   	    $this->r['data']['num']=$num;
   	    $this->state=true;
   	    $this->r();
   	}
   	
   	/**
   	 * 获取购物车中的商品总价
   	 * @author jieyang
   	 */
   	public function get_goods_amount(){
   	    $uid=$this->user['user_id'];
   	    $this->load->model('v1_0/common/Get_shopping_car_model','get_shopping_car_model');
   	    $goods_id_arr=$this->get_shopping_car_model->getGoodsIdList($uid);
   	    $total_amount=0;
   	    if($goods_id_arr){
   	        $this->load->model('v1_0/common/Get_goods_base_model','get_goods_base_model');
   	        $this->load->model('v1_0/wechat/Manage_shopping_car_model','manage_shopping_car_model');
   	        foreach ($goods_id_arr as $k=>$v){
   	            try {
   	                $goods_info=$this->get_goods_base_model->getGoodsMainPrice($v['goods_id']);
   	                $total_amount += $goods_info['shop_price']*$v['goods_num'];
   	            } catch (Exception $e) {
   	                $this->__delete_car_goods($v['goods_id']);
   	            }
   	        }
   	    }
   	    $this->r['data']['total_amount']=$total_amount;
   	    $this->state=true;
   	    $this->r();   
   	} 
    	
   	/**
   	 * 清空购物车数据
   	 * @author jieyang 
   	 */
   	public function delete_car_goods(){
   	    $uid=$this->user['user_id'];
   	    $this->load->model('v1_0/wechat/Manage_shopping_car_model','manage_shopping_car_model');
   	    $this->manage_shopping_car_model->deleteAll($uid);
   	    $this->state=true;
   	    $this->r();
   	}
   	
   	/**
   	 * 删除购物车无效数据(已下架/已删除的商品)
   	 * @author jieyagn
   	 */
   	private function __delete_car_goods($goods_id){
   	    $this->load->model('v1_0/wechat/Manage_shopping_car_model','manage_shopping_car_model');
   	    $uid=$this->user['user_id'];
   	    $this->manage_shopping_car_model->delete($uid,$goods_id);
   	}
	
}
