<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller_1_0 extends CI_Controller
{
	protected $__tplData = array();
	//返回数组
	public static $r = array();
	protected $state;
	protected $code;
	protected $msg;
	
	public function __construct()
	{
	    parent::__construct();
		//设置异常处理
		//ApiRESTful::setExceptionHandler();
		//set_exception_handler(array('ApiRESTful','_catchException'));
		//初始化返回
		//$this->r();
		//调用上层自动加载
		//var_dump($this->r());
		//加载会话类
		//$this->load->library('session');
	}


	protected function _auth($type = 'anonymous', $class = null, $function = null)
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
		/* $this->r['sysdata']['request_id'] = $this->session->__request_id;
		//返回uid
		$this->r['sysdata']['uid'] = $this->uid;
		//判断是否已经登陆
		//判断是否已经登陆
		$this->r['sysdata']['is_sign'] = $this->session->__is_pass_session_sign;
		if ($this->r['sysdata']['is_sign']) {
			//$session_sign = $r;
			//unset($session_sign['sysdata']['session_sign']);
			//$session_sign_str = $this->session->createSignstring($session_sign);
			//$r['sysdata']['session_sign'] = $this->session->signHash($session_sign_str);
			//unset($session_sign,$session_sign_str);
		} */
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
	    // 		var_dump($params);die();
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
	        //$end = $pn * $psize;
	        $size=$psize;
	        	
	        //计算总页数
	        //$totalPage=ceil( $dataSize / $psize );
	
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
	    var_dump($rt);
	    return $rt;
	}
	
    /**
     * 返回给前端页面json
     * @param string|array $data
     * @param string $errcode
     * @param bool $helper   是否helper调用，如果true,则不返回json
     */
    protected function r($data=array(),$status='200'){
    		/* if (is_numeric($r)) {
    			$status = $r ;
    		}
    		//强制返回数组
    		$r = is_array($r) ? $r : array();
    		//组合返回结果
    		if (isset($r['sysdata'])&&is_array($r['sysdata'])) {
    			$r['sysdata'] = array_merge(self::$r['sysdata'], $r['sysdata']);
    		}
    		$r = array_merge(self::$r, $r);
    		self::$r = &$r;
    		$r['code'] = $status ;
    		$r['code'] = intval($r['code']) ;
    		$r['error_id'] = empty($r['error_id'])?self::_getStatusText($status):$r['error_id'];
    		if (empty($r['state'])&&$r['state']===null) {
    			$r['state'] = ($status>=200&&$status<300) ;
    		}
    		@header('Content-Type:application/json;charset=utf-8',true);
    		//不解析中文
    		$r = json_encode($r,JSON_UNESCAPED_UNICODE);
    		echo $r;
    		unset($r);
    		die(); */

            //$returnData['code'] = $status ;
    		//$returnData['code'] = intval($r['code']) ;
    		//$returnData['error_id'] = empty($r['error_id'])?self::_getStatusText($status):$r['error_id'];
    		$returnData['code'] = 200 ;
    		$returnData['error_id'] = 123;
    		/* if (empty($r['state'])&&$r['state']===null) {
    			$r['state'] = ($status>=200&&$status<300) ;
    		} */
    		$returnData['state']="OK";
    		$returnData['data']=$data;
    		/* if (isset($r['sysdata'])&&is_array($r['sysdata'])) {
    		    $r['sysdata'] = array_merge(self::$r['sysdata'], $r['sysdata']);
    		} */
    		@header('Content-Type:application/json;charset=utf-8',true);
    		$r = json_encode($returnData,JSON_UNESCAPED_UNICODE);
    		echo $r;
    		
    }

    private static function _getStatusText($status){
        $status_text = "OK";
        return $status_text;
    }
    
    
   /*  protected function R($data = "", $errcode = '',$helper=false,$jumpUrl='') {
        if (!empty($errcode)){
            $this->errcode = $errcode;
        }
    
        if ($this->errcode==0){
            $errmsg='请求成功！';
            $isImportant=0;
        }
        else{
            if (empty(self::$errMap)){
                $errMap=require __ROOT__.'/errorCode.php';
                self::$errMap=$errMap;
            }
    
            if (is_array(self::$errMap[$this->errcode])) {
                $errmsg = self::$errMap[$this->errcode][0];
                $isImportant = self::$errMap[$this->errcode][1];
                $defaultUrl=empty(self::$errMap[$this->errcode][2])?0:self::$errMap[$this->errcode][2];
                $jumpUrl=empty($jumpUrl)?$defaultUrl:$jumpUrl;
            } else {
                $errmsg = self::$errMap[$this->errcode];
                $isImportant = 0;
                $jumpUrl='';
            }
        }
    
        $returnData=[
            "errcode" => $this->errcode,
            "errmsg" => $errmsg,
            'data' => $data,
            'isImportant' => $isImportant ,
            'jumpUrl'=>$jumpUrl
        ];
    
        $isView=Router::$isView;
        //$isViewMuti=Router::$isViewMuti;
    
        if (!$helper &&  $isView === false ){
            ajaxReturn($returnData, "JSON", JSON_UNESCAPED_UNICODE);
        }
        elseif (!$helper && $isView === true ){
            //错误跳转
            if ($returnData['errcode'] !=0 && $returnData['isImportant']==1){
                redirect(getHost().$jumpUrl);
            }
    
            self::$viewDataTemp[self::$functionName]=$returnData;
    
            if($returnData['errcode'] !=0){
                $this->viewError($returnData);
            }
             
            return;
        }
    
        return $returnData;
    } */
    



}









?>