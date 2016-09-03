<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	protected $__tplData = array();
	protected $r;
	protected $state;
	protected $code;
	protected $msg;
	protected $uid = 0;
	protected $adminid = 0;
	public $time = 0;//time在订单的时候，统一时间戳，方便后续操作问题

	public function __construct()
	{
		//设置异常处理
		ApiRESTful::setExceptionHandler();
		//初始化返回
		$this->_r_init();
		//调用上层自动加载
		parent::__construct();
		
		$this->time = time();
	    $this->load->library('session'); //引入session类
		$this->load->library('verify');//引入验证类
	}


	/*protected function _auth($type = 'anonymous', $class = null, $function = null)
	{
		//匿名访问直接通过
		if ($type === 'anonymous') {
			return;
		} else {
			//所有非匿名访问都需要一次性样子会话
			if (!$this->session->isRun()) {
				try {
					$r = $this->session->run();
					if (is_array($r)) {

						@header('Content-Type:application/json;charset=utf-8', true);
						//不解析中文
						$r = json_encode($r, JSON_UNESCAPED_UNICODE);
						//输出结果，结束程序
						echo $r;
						unset($r);
						die();
					}
				} catch (Exception $e) {
					ApiRESTful::_catchException($e);
				}
			}
		}
		//以下可以做更多详细的权限控制了
		//$this->_auth('user',__CLASS__,TRUE);         整个控制器需要授权
		//$this->_auth('user',__CLASS__,__FUNCTION__); 整个控制器的某个方法
		switch ((string)$type) {
			//服务平台用户
			case 'uesr': {
				try {

				} catch (Exception $e) {
					throw new RJsonErrorException('您还没登录,登录后才能操作！', 'not-login-user');
				}
			};
				break;
			//企业
			case 'mp': {
				try {

				} catch (Exception $e) {
					throw new RJsonErrorException('您还没登录公众平台,登录后才能操作！', 'not-login-mp');
				}
			};
				break;
			//管理平台管理员
			case 'manage': {
				if (!empty($_SESSION['admin_uid'])) {
					$this->adminid = $_SESSION['admin_uid'];
				} else {
					throw new RJsonErrorException('您还没登录后台,登录后才能操作！', 'not-login-manage');
				}
			};
				break;
			//其它情况
			default:
				break;
		}

		$this->r['sysdata']['request_id'] = $this->session->__request_id;
		//返回uid
		$this->r['sysdata']['uid'] = $this->uid;
		//判断是否已经登陆
		//判断是否已经登陆
		$this->r['sysdata']['is_sign'] = $this->session->__is_pass_session_sign;
		if ($this->r['sysdata']['is_sign']) {
			
		}
	}*/
	
	
	
	/**
	 * 重定义路由请求规则
	 * @author jieyang
	 * _remap ci系统提供的重映射方法
	 */
	public function _remap($model = 'index', $params = array()){
	    $method = $this->input->method();
	    $method = empty($method) ? 'get' : strtolower($method);
	    $restful_model = $method . '_' . $model;
	    if (method_exists($this, $restful_model)) {
	        call_user_func_array(array(&$this, $restful_model), $params);
	    } elseif (method_exists($this, $model)) {
	        call_user_func_array(array(&$this, $model), $params);
	    } else {
	        ApiRESTful::echo_404();
	    }
	    unset($method, $restful_model, $params);
	}


	/**
	 * 用于获取单页列表数据
	 * @param pageInfo $pageInfo 页面类，控制页面输出
	 * @param object $dataClass 获取数据的Data类
	 * @param string $listFunc 用于获取列表的Data类的函数名称
	 * @param string $lengFunc 用于获取总行数的Data类的函数名称
	 * @param array $params 函数参数数组（$conn除外且默认为第一个参数），默认为空
	 * @return false|array 返回数据列表，失败返回false
	 */
	protected function get_one_page_data(&$pageInfo, &$dataClass, $listFunc, $lengFunc = null, array $params = null) {
		$page_size = $this->input->get('page_size');
		$page_now = $this->input->get('page_now');
		$psize = (!empty($page_size) && intval($page_size) > 0)  ? intval($page_size) : 15;
		$pn = !empty($page_now) && intval($page_now) > 0 ? intval($page_now) : 1;
		$rt = false;
		if (null == $lengFunc){
			$lengFunc = $listFunc . "Length";
		}
		$dataSize = call_user_func_array([$dataClass, $lengFunc], (array) $params);
		if ($dataSize > 0) {
			$begin = ($pn - 1) * $psize; //从第N笔开始检索
			$size=$psize;
			$paramss=[$begin,$size]; //开始数,结束数
			if ($params!=null){
				$params=array_merge($paramss,$params);
			}
			else{
				$params=$paramss;
			}
			$rt = call_user_func_array([ $dataClass, $listFunc], (array) $params);
			if (!$rt) {
				throw new RJsonErrorException('分页错误','PAGE_ERROR');
			}
		} else {
			$rt=[];
		}
		$page=[
			'count' => $dataSize,
			'now' => $pn,
			'size' => $psize,
			'lists_size' => 15
		];
		$pageInfo->init($page)->getPage();
		return $rt;
	}

	//返回初始化
	private function _r_init()
	{
		$this->r = &ApiRESTful::$r;
		$this->r['msg'] = &ApiRESTful::$msg;
		$this->r['code'] = &ApiRESTful::$code;
		$this->r['state'] = &ApiRESTful::$state;

	}

	//返回
	protected function r($r = array(), $status = '200')
	{
		ApiRESTful::r($r, $status);
	}

}



?>