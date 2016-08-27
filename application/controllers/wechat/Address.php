<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户收货地址管理
 */
class Address extends Wx_Api_1_0_Controller{
  	
  	public function __construct(){
		parent::__construct();
		$this->_auth('session',__CLASS__,TRUE);
   	}
   	
   	/**
   	 * 获取对应下级城市
   	 * @author jieyang
   	 */
   	public function get_area(){
   	    $data=$this->input->get();
   	    $rule=array(
   	        'cid'=>array('egNum',null,false),
   	    );
   	    $this->verify->verify($rule,$data);
   	    $cid = empty($data['cid'])?1:$data['cid'];
   	    $this->load->model('v1_0/common/Get_user_address_model','get_user_address_model');
   	    $lists = $this->get_user_address_model->getCity($cid);
   	    $this->r['lists'] = $lists;
   	    $this->state=true;
   	    $this->r();
   	}

	/**
	 * 添加收货地址
	 * @author 柯志杰
	 */
	public function post_index(){
		$data=$this->input->post(); 
		$rule=array(
		    'true_name'=>array(null,null,true),//收货人姓名
		    'mobile_phone'=>array('mobile',null,true),//收货人电话	
		    'province'=>array(null,null,true),//地区 省
		    'city'=>array(null,null,true),//地区 市
		    'area'=>array(null,null,true),//地区 区
		    'street'=>array(null,null,true),//地区 详细街道
		);
		$this->verify->verify($rule,$data);
		foreach($rule as $k=>$v){
			isset($data[$k])?$save[$k]=$data[$k]:'';
		}
		$save['uid']=$this->user['user_id'];
		$this->load->model('v1_0/common/Get_user_address_model','get_user_address_model');
		$list=$this->get_user_address_model->getAddressList($save['uid']);
		if (count($list)>=6) {
			throw new RJsonErrorException('收货地址不能超过6个!','ADDRESS_NO_EXCEED_SIX');
		}
		$save['add_time']=$this->time();	
		$this->load->model('v1_0/wechat/Manage_user_address_model','manage_user_address_model');
		$this->manage_user_address_model->add($save);
		$this->state=true;
		$this->r();
	}

	/**
 	 * 删除收货地址
 	 * @author: 柯志杰 <1074786869@qq.com>
 	 */
	public function delete_index(){
		$data=$this->input->post();
		$rule = array(
			'address_id'=>array('egNum',null,true),
		);
		$this->verify->verify($rule,$data);
		foreach ($rule as $key => $v) {
			isset($_POST[$key])?$save[$key]= $_POST[$key]:'';
		}
		$address_id=$save['address_id'];
		$this->load->model('v1_0/wechat/Manage_user_address_model','manage_user_address_model');
		$this->manage_user_address_model->delete($address_id,$save);
		$this->state=true;
		$this->r();
	}

	/**
	 * 修改收货地址
	 * @author 柯志杰
	 */
	public function put_index(){
		$data=$this->input->post(); 
		$rule=array(
			'address_id'=>array('egNum',null),
		    'true_name'=>array(null,null,false),//收货人姓名
		    'mobile_phone'=>array('mobile',null,false),//收货人电话	
		    'province'=>array(null,null,false),//地区 省
		    'city'=>array(null,null,false),//地区 市
		    'area'=>array(null,null,false),//地区 区
		    'street'=>array(null,null,false),//地区 详细街道
		);
		$this->verify->verify($rule,$data);
		foreach($rule as $k=>$v){
			isset($data[$k])?$save[$k]=$data[$k]:'';
		}
		$address_id=$save['address_id'];
		unset($save['address_id']);
		$save['update_time']=$this->time();	
		$this->load->model('v1_0/wechat/Manage_user_address_model','manage_user_address_model');
		$this->manage_user_address_model->edit($address_id,$save);
		$this->state=true;
		$this->r();
	}

	/**
	 * 查询用户收货地址
	 * @author 柯志杰
	 */
	public function get_index(){    
		$address_id=$this->input->get('address_id');	
		if(isset($address_id)){
		    $this->__getOne();
		}else{
		    $this->__getLists();
		}
	}

	/**
	 * 查询用户收货地址详情
	 * @author 柯志杰
	 */
	public function __getOne(){
		$data=$this->input->get(); 
		$rule=array(
			'address_id'=>array('egNum',null),
		);
		$this->verify->verify($rule,$data);
		foreach($rule as $k=>$v){
			isset($data[$k])?$save[$k]=$data[$k]:'';
		}
		$this->load->model('v1_0/common/Get_user_address_model','get_user_address_model');
		$result=$this->get_user_address_model->getAddressOne($save['address_id']);
		$this->r['data'] = $result;
		$this->state=true;
		$this->r();
	}

	/**
	 * 查询用户收货地址列表
	 * @author 柯志杰
	 */
	public function __getLists(){
		$save['uid']=$this->user['user_id'];
		$this->load->model('v1_0/common/Get_user_address_model','get_user_address_model');
		$list=$this->get_user_address_model->getAddressList($save);
		$this->r(['lists'=>$list]);
		$this->state=true;
		$this->r();
	}

	/**
	 * 设置默认收货地址
	 * @author 柯志杰
	 */
	public function put_default_address(){
		$data=$this->input->post(); 
		$rule=array(
			'address_id'=>array('egNum',null),
		);
		$this->verify->verify($rule,$data);
		foreach($rule as $k=>$v){
			isset($data[$k])?$save[$k]=$data[$k]:'';
		}
		$uid=$this->user['user_id'];
		$info['is_default']=0;
		//清空用户所有默认地址
		$this->load->model('v1_0/wechat/Manage_user_address_model','manage_user_address_model');
		$this->manage_user_address_model->editEmpty($uid,$info);
		$address_id=$save['address_id'];
		$save['is_default']=1;
		$save['update_time']=$this->time();	
		unset($save['address_id']);
		$this->manage_user_address_model->edit($address_id,$save);
		$this->state=true;
		$this->r();
	}
	
}?>