<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller{

	public function __construct(){
		parent::__construct();
		//session验证
		//$this->_auth('session',__CLASS__,TRUE);
	}

	/**
	 * 登录login
	 */
	public function post_index(){
		$this->load->model('common/admin/Get_admin_model', 'get_admin_model');
		$data = $this->input->post();
		$rule = array(
			'admin_name'=>array(null,null,true),
			'password'=>array(null,null,true),
		);
		$this->verify->verify($rule, $data);	
		foreach($rule as $k=>$v){
		    isset($data[$k])?$save[$k]= $data[$k]:'';
		}
		$this->load->helper('common');
		try{
		    $info=$this->get_admin_model->getOneBaseByName($save['admin_name']);
		}catch(ModelErrorException $e) {
		    throw new RJsonErrorException('用户信息获取失败','GET_ADMIN_INFO_FAIL');
		}
		$password=encryptPassword($save['password'],$info['salt']);
		if ($password!=$info['password']) {
			throw new RJsonErrorException('密码错误', 'PASSWORD_MISTAKE');
		}
		$_SESSION['admin_id']=$info['admin_id'];
		$this->state=true;
		$this->r();
	}
	
	/**
	 * 判断是否已经登录
	 */
	public function get_check_login(){
		if(isset($_SESSION['admin_id'])&&!empty($_SESSION['admin_id'])){
			$data['is_login'] = true;
 		}else{
 			$data['is_login'] = false;
 		}
		$this->r['data'] = $data;
		$this->state = true;
		$this->r();
	}

	/**
	 * 退出登录
	 */
	public function delete_index(){
		unset($_SESSION['admin_id']);
		$this->state = true;
		$this->r();
	}
	
}?>