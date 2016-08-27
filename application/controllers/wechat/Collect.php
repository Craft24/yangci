<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户商品收藏
 */
class Collect extends Wx_Api_1_0_Controller{
  	
  	public function __construct(){
		parent::__construct();
		$this->_auth('session',__CLASS__,TRUE);
   	}

	/**
	 * 添加商品收藏
	 * @author 柯志杰
	 */
	public function post_index(){
		$data=$this->input->post(); 
		$rule=array(
		    'goods_id'=>array('egNum',null,true),
		);
		$this->verify->verify($rule,$data);
		foreach($rule as $k=>$v){
			isset($data[$k])?$save[$k]=$data[$k]:'';
		}
		$save['uid']=$this->user['user_id'];
		$save['add_time']=$this->time();	
		$this->load->model('v1_0/wechat/Manage_goods_collect_model','manage_goods_collect_model');
		$this->manage_goods_collect_model->add($save);
		$this->state=true;
		$this->r();
	}

	
	/**
 	 * 删除商品收藏
 	 * @author: 柯志杰 <1074786869@qq.com>
 	 */
	public function delete_index(){
		$data=$this->input->post();
		$rule = array(
			'collect_id'=>array('egNum',null,true),
		);
		$this->verify->verify($rule,$data);
		foreach ($rule as $key => $v) {
			isset($_POST[$key])?$save[$key]= $_POST[$key]:'';
		}
		$collect_id=$save['collect_id'];
		$this->load->model('v1_0/wechat/Manage_goods_collect_model','manage_goods_collect_model');
		$this->manage_goods_collect_model->delete($collect_id,$save);
		$this->state=true;
		$this->r();
	}

	/**
 	 * 商品收藏列表
 	 * @author: 柯志杰 <1074786869@qq.com>
 	 */
	public function get_index(){
		$this->load->library('page');
	    $data['uid']=$this->user['user_id'];
		$this->load->model('v1_0/common/Get_goods_collect_model','get_goods_collect_model');
		$this->load->model('v1_0/common/Get_goods_base_model','get_goods_base_model');	
		$list=$this->get_one_page_data($this->page,$this->get_goods_collect_model,'getList','getListLength',array($data));
		if (!empty($list)) {
			foreach ($list as $key => $value) {
				try {
					$data=$this->get_goods_base_model->getCollectOne($value['goods_id']);
					$list[$key]['goods_name']=$data['goods_name'];
					$list[$key]['shop_price']=$data['shop_price'];
					$list[$key]['goods_sales']=$data['goods_sales'];
					$list[$key]['goods_thumb']=$data['goods_thumb'];
				} catch (Exception $e) {
					$list[$key]['goods_name']='';
					$list[$key]['shop_price']='';
					$list[$key]['goods_sales']='';
					$list[$key]['goods_thumb']='';
				}
			}
		}
		$this->r(['lists'=>$list,'page'=>$this->page->getPage()]);
		$this->state=true;
		$this->r();
	}
	
}?>