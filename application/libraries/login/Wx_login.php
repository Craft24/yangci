<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wx_login
{
	protected $ci;
	public function __construct($config=array())
	{
		$this->__init($config);
	}
	private function __init($config=array()){
		$this->ci =& get_instance();
		$this->ci->load->model('v1_0/wx/Manage_login_model','manage_login_model');
	}
	//app微信登录
	public function app_init(){
		// 获取微信资料
		$wx_res=$this->getUnionidByWx();
		//失败的处理
		if (!$wx_res['state']){
			return $wx_res;
		}
		$res = $this->isUserMobileIsBindByUnionid($wx_res['wx_data']['unionid']);

		//用户没有绑定手机
		if(!$res['user_mobile_is_bind']){
			$wx_res['wx_data']['access_token'] = isset($wx_res['wx_data']['access_token'])?$wx_res['wx_data']['access_token']:null;
			$wx_data = $this->getUserInfoByOpenid($wx_res['wx_data']['openid'],$wx_res['wx_data']['access_token']);
			//获取openid
			$app_openid=$this->getAppOpenidByDb($wx_res['wx_data']['unionid']);
			//openid为空的时候,更新
			if(empty($app_openid)){
				$appid['user_wechat_openid_app']=$wx_res['wx_data']['openid'];
				$this->updateUserInfo($res['user_data']['uid'],$appid);
			}

			//将微信资料保存到SESSION
			$_SESSION['wechat_user_data'] = $wx_data['data'] ;
			return array(
					'state'=>true,
					'session'=>session_id(),
					'sysdata'=>array(
						'to_bind'=>true
					)
			);

		}else{//用户已经绑定手机
			$app_openid=$this->getAppOpenidByDb($wx_res['wx_data']['unionid']);
			//openid为空
			if(empty($app_openid)){
				//更新用户unionid
				$appid['user_wechat_openid_app']=$wx_res['wx_data']['openid'];
				$state=$this->updateUserInfo($res['user_data']['uid'],$appid);
				if($state){
					//记录登陆信息
					$_SESSION['uid'] = $res['user_data']['uid'] ;
					return array('state'=>true,'msg'=>'');
				}else{
					return array('state'=>false,'msg'=>'登录失败');
				}
			}else{
				//记录登陆信息
				$_SESSION['uid'] = $res['user_data']['uid'] ;
				return array('state'=>true,'msg'=>'');
			}
		}
	}
	public function mp_init(){
		$callback = $this->ci->input->get('callback');
		$weObj = $this->ci->wechat;
		//如果没有初始化就跳转到微信
		if (empty($_GET['code']) && empty($_GET['state'])) {
			$reurl= $weObj->getOauthRedirect($callback, "snsapi_base","snsapi_base");
            return array('url'=>$reurl);
		}elseif($this->ci->input->get('state') == 'snsapi_base'){//
			$wx_data = $weObj->getOauthAccessToken();
			//获取微信用户的信息
			//if (is_array($wx_data)&&(empty($wx_data['unionid']))&&(!empty($wx_data['openid']))) {
				$wx_data_temp = $this->ci->wechat->getUserInfo($wx_data['openid']);
				if (is_array($wx_data_temp)&&(!empty($wx_data['openid']))&&$wx_data['openid']==$wx_data_temp['openid']) {
					$wx_data = array_merge($wx_data,$wx_data_temp);
				}
			//}
			if (empty($wx_data)) {
                $reurl = $weObj->getOauthRedirect($callback, "snsapi_userinfo","snsapi_userinfo");
                return array('url'=>$reurl);
            }
            $wx=$this->ci->manage_login_model->getOne($wx_data['unionid'],$wx_data['openid']);
            if (!empty($wx)) {
	                $_SESSION['uid']=$wx['uid'];
	                $_SESSION['unionid']=$wx['unionid'];
					$_SESSION['openid_mp']=$wx['openid_mp'];
					//is_get_user_end = true 进网站
					return array('is_get_user_end'=>true);
			}else{
				$reurl = $weObj->getOauthRedirect($callback, "snsapi_userinfo","snsapi_userinfo");
	            return array('url'=>$reurl);
			}
			/*if (empty($wx_data)) {
            	$reurl = $weObj->getOauthRedirect($callback, "snsapi_userinfo","snsapi_userinfo");
                return array('url'=>$reurl);
			}else{
	            $wx=$this->ci->manage_login_model->getOne($wx_data['unionid'],$wx_data['openid']);
	            if (!empty($wx)) {
	                $_SESSION['uid']=$wx['uid'];
	                $_SESSION['unionid']=$wx['unionid'];
					$_SESSION['openid_mp']=$wx['openid_mp'];
					//is_get_user_end = true 进网站
				}else{
	            	$reurl = $weObj->getOauthRedirect($callback, "snsapi_userinfo","snsapi_userinfo");
	                return array('url'=>$reurl);
				}
			}*/
        }elseif ($this->ci->input->get('state') == 'snsapi_userinfo') {
            $wx_data = $weObj->getOauthAccessToken();
            //判断是否有unionid
   			//if (is_array($wx_data)&&(!empty($wx_data['unionid']))&&(!empty($wx_data['openid']))) {
				$wx_data_temp = $this->ci->wechat->getUserInfo($wx_data['openid']);
				if (is_array($wx_data_temp)&&(!empty($wx_data['openid']))&&$wx_data['openid']==$wx_data_temp['openid']) {
					$wx_data = array_merge($wx_data,$wx_data_temp);
					$_SESSION['unionid']=$wx_data['unionid'];
				    $_SESSION['openid_mp']=$wx_data['openid'];
				    $_SESSION['user_img']=$wx_data['headimgurl'];
				    $_SESSION['user_name']=$wx_data['nickname'];
					var_dump(5556);
				    return array('is_get_user_end'=>false);
				}
			//}
			/*$wx=$this->ci->manage_login_model->getOne($wx_data['unionid'],$wx_data['openid']);
			if(!empty($wx)){
	            $_SESSION['uid']=$wx['uid'];
                $_SESSION['unionid']=$wx['unionid'];
				$_SESSION['openid_mp']=$wx['openid_mp'];
			}else{
				$_SESSION['unionid']=$wx_data['unionid'];
				$_SESSION['openid_mp']=$wx_data['openid'];
			}*/
        }
		//return array('is_get_user_end'=>true);
	}
	/*public function mp_init(){
		$callback = $this->ci->input->get('callback');
		$weObj = $this->ci->wechat;
		//如果没有初始化就跳转到微信
		if (empty($_GET['code']) && empty($_GET['state'])) {
			$reurl= $weObj->getOauthRedirect($callback, "snsapi_base","snsapi_base");
            return array('url'=>$reurl);
		}elseif($this->ci->input->get('state') == 'snsapi_base'){//
			$wx_data = $weObj->getOauthAccessToken();
			//获取微信用户的信息
			if (is_array($wx_data)&&empty($wx_data['unionid'])&&(!empty($wx_data['openid']))) {
				$wx_data_temp = $this->ci->wechat->getUserInfo($wx_data['openid']);
				if (is_array($wx_data_temp)&&(!empty($wx_data['openid']))&&$wx_data['openid']==$wx_data_temp['openid']) {
					$wx_data = array_merge($wx_data,$wx_data_temp);
				}
			}
			if (empty($wx_data)) {
            	$reurl = $weObj->getOauthRedirect($callback, "snsapi_userinfo","snsapi_userinfo");
                return array('url'=>$reurl);
			}else{
	            $wx=$this->ci->manage_login_model->getOne($wx_data['unionid'],$wx_data['openid']);
	            if (!empty($wx)) {
	                $_SESSION['uid']=$wx['uid'];
	                $_SESSION['unionid']=$wx['unionid'];
					$_SESSION['openid_mp']=$wx['openid_mp'];
					//is_get_user_end = true 进网站
				}else{
	            	$reurl = $weObj->getOauthRedirect($callback, "snsapi_userinfo","snsapi_userinfo");
	                return array('url'=>$reurl);
				}
			}
        }elseif ($this->ci->input->get('state') == 'snsapi_userinfo') {
            $wx_data = $weObj->getOauthAccessToken();
            //判断是否有unionid
   			if (is_array($wx_data)&&empty($wx_data['unionid'])&&(!empty($wx_data['openid']))) {
				$wx_data_temp = $this->ci->wechat->getUserInfo($wx_data['openid']);
				if (is_array($wx_data_temp)&&(!empty($wx_data['openid']))&&$wx_data['openid']==$wx_data_temp['openid']) {
					$wx_data = array_merge($wx_data,$wx_data_temp);
				}
			}else{
				$wx_data_temp = $this->ci->wechat->getUserInfo($wx_data['openid']);
			}
			$wx=$this->ci->manage_login_model->getOne($wx_data['unionid'],$wx_data['openid']);
			if(!empty($wx)){
	            $_SESSION['uid']=$wx['uid'];
                $_SESSION['unionid']=$wx['unionid'];
				$_SESSION['openid_mp']=$wx['openid_mp'];
			}else{
				$_SESSION['unionid']=$wx_data['unionid'];
				$_SESSION['openid_mp']=$wx_data['openid'];
			}
        }
		return array('is_get_user_end'=>true);
	}*/
	//获取登录地址
	public function toMpLoginUrl($is_auth=false,$callbackurl=null){
		$r = array();
		if (empty($callbackurl)) {
			$callbackurl = $this->ci->input->post('callbackurl');
			$callbackurl = empty($callbackurl)?$this->ci->input->server('HTTP_REFERER'):$callbackurl;
			if (strpos($callbackurl,'#') === false) {
				$callbackurl .='#index.html';
			}
		}
		//如果获取的回调地址为空就报错
		if (empty($callbackurl)) {
			$r['msg'] = '访问失败，请刷新后重试！';
			$r['state']=false;
			return $r;
		};

		//读取请求的地址
		$callbackpath = $this->ci->input->post('callbackpath');
		$callbackpath = empty($callbackpath)?$this->ci->input->server('HTTP_REFERER'):$callbackpath;
		$callbackpath = empty($callbackpath)?'http://www.xsw0306.com/wx/':$callbackpath;

		//读取请求的地址,is_auth是授权地址
		if ($is_auth) {
			$callback = $callbackpath.'?wechat_user_info_call='.str_replace('/','_',base64_encode($callbackurl));
			$r['sysdata']['url'] = $this->ci->wechat->getOauthRedirect($callback,'snsapi_userinfo','snsapi_userinfo');
		}else{
			$callback = $callbackpath.'#?wechat_user_info_call='.str_replace('/','_',base64_encode($callbackurl));
			$r['sysdata']['url'] = $this->ci->wechat->getOauthRedirect($callback,'snsapi_base','snsapi_base');
		}
			$r['sysdata']['callbackurl'] = $callbackurl;
		$r['sysdata']['to_login'] = false ;
		$r['sysdata']['to_wx_login'] = true ;
		$r['state']=true;
		return $r;
	}

	
	// 判断用户是否注册过
	private function isUserMobileIsBindByUnionid($unionid){
		$r = array();
		$r['state'] = true ;
		/***********************判断是否已经绑定手机号码***********************/
		$r['user_data'] = $this->ci->login_model->userMobileIsBind($unionid);
		$r['msg'] = '';
		//状态是否绑定
		if (!$this->ci->login_model->state) {
			$r['msg'] = '您还没有绑定手机号码!';
			$r['user_mobile_is_bind'] = false;
		}else{
			$r['user_mobile_is_bind'] = true;
		}
		return $r;
	}
	
	private function getUserInfoByOpenid($openid='',$access_token=null){
		$r = array();
		// 微信回调返回的code
		$r['scope'] = $this->ci->input->get('state');
		//判断模式
		$r['is_auth'] = ($r['scope']==='snsapi_userinfo'||false);
		/*试图通过非授权方式获取,也就是通过关注后的静默获取方式获取*/
		$r['data'] = $this->ci->wechat->getUserInfo($openid);
		//由于是非授权模式，所有可以根据是否关注，来确认是否已经成功获取资料
		$r['state'] = (is_array($r['data'])&&$r['data']['subscribe']=='1');
		if ((!$r['state'])&&(!is_null($access_token))){
			//如果非授权模式获取失败尝试使用授权模式获取
			$user_info_auth = $this->ci->wechat->getOauthUserinfo($access_token,$openid);
			if ($user_info_auth!==false&&is_array($user_info_auth)) {
				//非授权模式和授权模式都获取到数组就组合
				if (is_array($r['data'])&&is_array($user_info_auth)) {
					$r['data'] = array_merge($r['data'],$user_info_auth);
					//设置为获取成功
					$r['state'] = true ;
				//非授权模式不是数组 ， 授权模式都获取到数组 就以授权为准
				}elseif ((!is_array($r['data']))&&is_array($user_info_auth)) {
					$r['data'] = $user_info_auth;
					//设置为获取成功
					$r['state'] = true ;
				//如果其它就不做处理了
				}
			}
			unset($user_info_auth);
		}
		$r['data'] = is_array($r['data'])?$r['data']:array();
		return $r ;
	}
	// 判断用户是否注册过
	private function getUnionidByWx(){
		//构造返回数组
		$r = array();
		// 微信回调返回的code
		$code = $this->ci->input->get('code');
		// 微信回调返回的state
		// 1代表 这种授权需要用户手动同意
		// 2代表 网页授权，就静默授权的，用户无感知
		// $state = $this->input->get('state');

		if(empty($code)){
			$r['msg'] = '系统错误！正在刷新重试[code_error]！';
			$r['state'] = false ;
			$r['wx_data'] = array();
			return $r;
		}
		//尝试获取用户详细信息
		//强制授权方式失败提示[网页授权获取用户基本信息]
		$wx_data = $this->ci->wechat->getOauthAccessToken();
		if ($wx_data==false||(!is_array($wx_data))||empty($wx_data['unionid']) || empty($wx_data['openid'])) {
			$r['msg'] = 'unionid获取失败!请同意授权！正在刷新重试!';
			$r['state'] = false ;
			$r['wx_data'] = array();
			return $r;
		}
		$r['state'] = true ;
		$r['wx_data'] = $wx_data;

		return $r;

		/***********************判断是否已经绑定手机号码***********************/
		/*$res = $this->ci->login_model->userMobileIsBind($tmp_data['unionid']);
		if (!$this->ci->login_model->state) {
			$r['msg'] = '您还没有绑定手机号码!';
			$r['state'] = true ;
			$r['user_mobile_is_bind'] = 0;
			$r['tmp_data'] = $tmp_data;
			$r['res_data'] = $res;
			return $r;
		}
		$r['msg'] = '';
		$r['state'] = true ;
		$r['code'] = 0 ;
		$r['user_mobile_is_bind'] = 1;
		$r['tmp_data'] = $tmp_data;
		$r['uid'] = $res['uid'];
		$r['res_data'] = $res;
		return $r;*/
	}
	/**
	 * 查找数据库判断openid是否存在
	 */
	public function getAppOpenidByDb($unionid=''){
		$app_openid=$this->ci->login_model->getAppOpenid($unionid);
		if(empty($app_openid)){
			return null;
		}else{
			return $app_openid['user_wechat_openid_app'];
		}
	}
	/**
	 * 更新用户信息
	 */
	public function updateUserInfo($uid=0,$arr=array()){
		$this->ci->login_model->updateUserInfo($uid,$arr);
		return $this->ci->login_model->state;
	}

}

/* End of file Wx_login.php */
/* Location: ./application/libraries/login/Wx_login.php */

?>