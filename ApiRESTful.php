<?php
final class ApiRESTful {
	//返回数组
	public static $r = array();
	//详细错误
	public static $msg ;
	//状态码
	public static $code ;
	//状态 true false
	public static $state ;
	//请求方式 GET POST PUT DELETE
	public static $method ;
	//请求id
	public static $request_id ;

	//是否已经初始化
	private static $is_init = false;
	//初始化
	public static function __init()
	{
		//防止二次初始化
		if (self::$is_init) {
			return ;
		}
		//标记状态
		self::$is_init = true;
		//标记请求方式
		self::$method = strtoupper(empty($_SERVER['REQUEST_METHOD'])? 'GET' : $_SERVER['REQUEST_METHOD']);
		//初始化返回
		self::_r_init();
		//捕获全局异常
		self::setExceptionHandler();		
	}
	public static function echo_404(){
		@header("HTTP/1.0 404 Not Found");
		@header('Content-Type:application/json;charset=utf-8',true);
		$r = array(
			'code'=>'404',
			'error_id'=>'404 Not Found',
			'msg'=>'404 Not Found',
			'state'=>false
		);
		echo self::r($r,404);
	}
	public static function r($r = array(),$status='200')
	{
		if (is_numeric($r)) {
			$status = $r ;
		}
		//强制返回数组
		$r = is_array($r) ? $r : array();
		//组合返回结果
		/* if (isset($r['sysdata'])&&is_array($r['sysdata'])) {
			$r['sysdata'] = array_merge(self::$r['sysdata'], $r['sysdata']);
		} */ 
		$r = array_merge(self::$r, $r);
		self::$r = &$r;
		$r['code'] = $status ;
		$r['code'] = intval($r['code']) ;
		$r['error_id'] = empty($r['error_id'])?self::_getStatusText($status):$r['error_id'];
		if (empty($r['state'])&&$r['state']===null) {
			$r['state'] = ($status>=200&&$status<300) ;
		}
		try{
			@session_write_close();
		}catch(Exception $e){}

		if(function_exists('set_status_header')){
			set_status_header($r['code'],$r['error_id']);
		}else{
			try{
				//nginx模式
				if (strpos(PHP_SAPI, 'cgi') === 0){
					header('Status: '.$r['code'].' '.$r['error_id'], TRUE);
				}else{
					$server_protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
					header($server_protocol.' '.$r['code'].' '.$r['error_id'], TRUE, $r['code']);
					unset($server_protocol);
				}
			}catch(Exception $e){
			}
		}
		
		@header('Content-Type:application/json;charset=utf-8',true);
		//不解析中文
		$r = json_encode($r,JSON_UNESCAPED_UNICODE);
		echo $r;
		unset($r);
		die();
	}
	private static function _getStatusText($status){
		$status_text = "OK";
		return $status_text;
	}
	public static function setExceptionHandler(){
		//设置异常处理
		set_exception_handler(array('ApiRESTful','_catchException'));
	}
	public static function _catchException($e){
		//默认错误行数
		$line = 0 ;
		if (method_exists($e,'getLine')) {
			$line =$e->getLine();
		}
		$r = &self::$r ;
		if (method_exists($e,'getCode')) {
			$r['code'] =$e->getCode();
		}
		if($r['code']===0){
			$r['code'] = 500 ;
		}
		if (method_exists($e,'getErrorId')) {
			$r['error_id'] =$e->getErrorId();
		}else{
			$r['error_id'] ='';
		}
		if (method_exists($e,'getMessage')) {
			$r['msg'] = $e->getMessage();
		}else{
			$r['error_id'] ='unknown error';
		}
		//调试模式
		/* if (ENVIRONMENT==='development') {
			$r['sysdata'] = isset($r['sysdata'])&&is_array($r['sysdata'])?$r['sysdata']:array();
			$r['sysdata']['debug'] = array();
			$r['sysdata']['debug']['type'] = get_class($e);
			$r['sysdata']['debug']['line'] = $line;
			$r['sysdata']['debug']['file'] = $e->getFile();
			$r['sysdata']['debug']['trace'] = $e->getTraceAsString();
			$r['sysdata']['debug']['trace'] = explode("\n", $r['sysdata']['debug']['trace']);
		} */
		$r['msg'] = empty($r['msg'])?'':$r['msg'];
		self::r($r,$r['code']);
	}

	//返回
	private static function _r_init(){
		self::$r = array(
			//系统数据，前端开发一般不做理会，用于系统框架
			/* 'sysdata'=>array(
				//请求id
				'request_id'=>'',
				//强制刷新浏览器
				'reload'=>false,
				//强制跳转到以下url,如果url返回的不是空字符串就跳转
				'url'=>'',
				//uid默认是0
				'uid'=>'0',
				//是否已经登陆
				'is_login'=>false,
				//跳转到登陆
				'to_login'=>false,
				//签名是否通过
				'is_sign'=>false,
				//服务器签名返回
				'session_sign'=>''
			), */
			//状态
			'state'=>null,
			//错误识别码
			'error_id'=>'OK',
			//消息
			'msg'=>'',
			//代码
			'code'=>500
		);
		//self::$r['sysdata']['request_id'] = &self::$request_id;
		self::$r['msg'] = &self::$msg;
		self::$r['code'] = &self::$code;
		self::$r['state'] = &self::$state;
		//返回
	}

	//支持跨域访问
	/* private static function __ajax_cors_init(){
		//缓存
		@header('Cache-Control: no-cache');
		//获取请求域名
		$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
		//请求来源
		$x_requested_with = isset($_SERVER['HTTP_X_REQUESTED_WITH'])? $_SERVER['HTTP_X_REQUESTED_WITH'] : 'XMLHttpRequest';
		//设定授权头默认值
		self::$allow_origin = is_array(self::$allow_origin)?self::$allow_origin:array();
		//安卓或者ios跨域请求
		if ($x_requested_with===self::$x_requested_with_app&&in_array($origin, array('file://'))) {
			self::$allow_origin[] = $origin;
		}
		if (!in_array($origin, self::$allow_origin)) {
			return ;
		}

		//通过授权
		@header('Access-Control-Allow-Credentials:true');
		//允许跨域访问的域，可以是一个域的列表，也可以是通配符"*"。这里要注意Origin规则只对域名有效，并不会对子目录有效。即http://foo.example/subdir/ 是无效的。但是不同子域名需要分开设置，这里的规则可以参照同源策略
		@header('Access-Control-Allow-Origin:'.$origin);

		//如果是单纯试探是否有权限的话，终止程序，单纯返回php头信息
		if (self::$method==='OPTIONS') {
			//所有headers参数传输的前缀
			$headers_prefix_len = strlen(self::$headers_prefix);
			//请求头
			$allow_headers = isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])? $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'] : '';
			//拆分数组
			$allow_headers = explode(',', $allow_headers);
			//遍历去除不授权的头
			foreach ($allow_headers as $key => $value) {
				$value = trim($value);
				//判断头前缀是否为签名头
				if (
					//非同意授权前缀
					substr($value,0,$headers_prefix_len)!=self::$headers_prefix
					//并且不是常用允许头
					&&(!in_array(strtolower($value),
						array('accept', 'authorization', 'content-md5', 'content-type', 'x-requested-with', 'cookie')
					))
				){
					unset($allow_headers[$key]);
				}else{
					$allow_headers[$key] = $value;
				}
			}
			//把数组连接为 x-xxx ,x-xxxx 
			$allow_headers = implode(', ', $allow_headers);
			//允许自定义的头部，以逗号隔开，大小写不敏感
			@header('Access-Control-Allow-Headers:'.$allow_headers);
			//允许脚本访问的返回头，请求成功后，脚本可以在XMLHttpRequest中访问这些头的信息(貌似webkit没有实现这个)
			@header('Access-Control-Expose-Headers:set-cookie, '.self::$headers_prefix.'request-id, '.self::$headers_prefix.'session-sign');
			//请求方式
			$allow_method = isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])? $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] : 'GET';
			//允许使用的请求方法，以逗号隔开
			@header('Access-Control-Allow-Methods:'.$allow_method);
			//缓存此次请求的秒数。在这个时间范围内，所有同类型的请求都将不再发送预检请求而是直接使用此次返回的头作为判断依据，非常有用，大幅优化请求次数
			@header('Access-Control-Max-Age:'.self::$allow_control);
			die();
		}
	}
	//restful数据接收初始化
	private static function __restful_request_init(){
		if (self::$method==='WSPULL') {
			self::__restful_request_ws_init();
		}else{
			self::__restful_request_http_init();
		}
	}
	//restful数据接收初始化
	private static function __restful_request_ws_init(){
		$raw_input = file_get_contents("php://input");//只允许15M以内的数据传真
		$ws_raw_data = json_decode($raw_input,true);unset($raw_input);
		self::$ws_raw_data = &$ws_raw_data;
		if ($ws_raw_data===false) {
			return ;
		}
		if (empty($ws_raw_data['authorization'])) {
			throw new AuthErrorException('Authentication Format Error','AUTHENTICATION_FORMAT_ERROR','403');
		}
		//user 是[用户行为] app 是应用信息[app升级之类] sys是系统[签名过期之类] api [类似php的短连接]
		if (empty($ws_raw_data['type'])||(!in_array($ws_raw_data['type'], array('user','app','sys','api')))) {
			throw new RJsonErrorException('Request type Error','REQUEST_TYPE_ERROR','400');
		}
		if (empty($ws_raw_data['path'])) {
			throw new RJsonErrorException('Request path Error','REQUEST_PATH_ERROR','400');
		}
		if (empty($ws_raw_data['request_id']) || !preg_match('/^([\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12})$/',$ws_raw_data['request_id'])) {
			throw new RJsonErrorException('Request id Error','REQUEST_ID_ERROR','400');
		}

		$ws_raw_data['data'] = isset($ws_raw_data['data'])?$ws_raw_data['data']:'';
		//数据
		self::$ws_data = &$ws_raw_data['data'] ;
		//请求id
		self::$request_id = &$ws_raw_data['request_id'] ;
		//获取授权信息
		self::$header['authorization'] = &$ws_raw_data['authorization'];
		//签名模式是ws模式
		self::$sign_options['sign_type'] = 'ws';
		//引用变量
		self::$sign_options['authorization'] = &self::$header['authorization'];
		//计算data的 md5 [md5二进制]
		self::$data_md5_raw = md5($ws_raw_data['data'],true);

		//获取ws试图访问的路径
		$uri = &$ws_raw_data['path'];
		//不能为定义是cmd运行模式，否则session被关闭
		//define('STDIN',true);
		//设定基本参数
		$_SERVER['argv']=array();
		$_SERVER['argv'][] = basename(__ROOT_FILE__);
		$_SERVER['argv'][] = $uri;
		$_SERVER['argc'] = count($_SERVER['argv']);
		$_SERVER['REDIRECT_URL'] = '/'.$uri;
		$_SERVER['REQUEST_URI'] = '/'.$uri;
		$_SERVER['PATH_INFO'] = '/'.$uri;
		$_SERVER['PHP_SELF'] = '/'.$uri;
		$_SERVER['PATH_TRANSLATED'] = '/'.$uri;
		//释放内存
		unset($uri);



		//var_dump($ws_raw_data);die();
		//获取二进制的md5
		////校验md5
		//self::__check_content_md5($header['sys']['content_md5'],$ws_raw_data_md5_raw);
		//请求方式
		//$_SERVER['REQUEST_METHOD'] = '';
		//重置服务器名称
		//$_SERVER['HTTP_HOST'] = '';
		//重置服务器名称
		//$_SERVER['SERVER_NAME'] = '';
		//重置客户端ip
		//$_SERVER['REMOTE_ADDR'] = '';

		//var_dump($data);
	}
	//restful数据接收初始化
	private static function __restful_request_http_init(){
		self::$sign_options['sign_type'] = 'http';
		//默认是http 请求
		$header = self::__getHttpHeaders();
		//获取系统头
		self::$sign_options['h_sys'] = is_array($header['sys'])?$header['sys']:array();
		//获取自定义头
		self::$sign_options['h_x'] = is_array($header['x'])?$header['x']:array();
		//获取授权头
		self::$sign_options['authorization'] = is_string($header['authorization'])?$header['authorization']:'';
		//如果内容长度为0就不读取body请求体
		if ($header['sys']['content_length']<=0) {
			return;
		}
		//默认没有找到分隔符
		$delimiter = false;
		//获取内容的分界
		$input = fopen('php://input', 'r');
		//初始化增量Md5运算上下文
		$md5_ctx = hash_init('md5');
		//分界符
		$boundary = '';
		//每一块数据内容
		$part = '';
		//默认读取的最大字节数
		$boundary_len = 2;
		//当前一块数据长度
		$part_len = 0 ;
		//一个分隔符的长度
		$delimiter_len = 0 ;
		//两个的长度
		$delimiter_len2 = 0 ;
		//两个的长度
		$delimiter_boundary_len2 = 0 ;
		//读流一块数据
		$chunk = '';
		//存储读流上一块数据
		$chunk_old = '';
		//读流结束
		$is_end = false ;
		//multipart/form-data数据
		$is_multipart_form_data = false ;
		//x-www-form-urlencoded数据
		$is_parse_str = false ;
		//是json解析
		$is_parse_json = false;
		//整个流
		$raw_data = '' ;
		//在读取
		$is_read_multipart_form_header = false ;
		$is_read_multipart_form_data = false ;
		$headers = array();
		switch (self::$method) {
			case 'GET':
			case 'OPTIONS':{
				//如果是get请求 或者去 options 直接结束请求体信息
				$is_end = true ;
			};break;
			default:{
				//找不到multipart/form-data就判断是否为x-www-form-urlencoded数据
				if (strpos( $header['sys']['content_type'],'multipart/form-data')!==false) {
					//是
					$is_multipart_form_data = true ;
				}elseif (strpos( $header['sys']['content_type'],'application/json')!==false) {
					//是
					$is_parse_json = true;
				}else{
					$is_multipart_form_data = false ;
					if (self::$method!=='POST') {
						$is_parse_str = true ;
					}
				}
			};break;
		}
		//开始读流操作
		while (!feof($input)){
			//读取指定长度的一块数据
			$chunk = fread($input, $boundary_len);
			//增量Md5运算
			hash_update($md5_ctx, $chunk);
			if ($is_multipart_form_data) {
				if ($delimiter===false) {
					if(strpos($boundary,"\r\n")!==false){
						$delimiter = "\r\n";
					}elseif(strpos($boundary,"\n")!==false){
						$delimiter = "\n";
					}elseif(strpos($boundary,"\r")!==false){
						$delimiter = "\r";
					}
					//判断是否找到分隔符号
					if ($delimiter===false) {
						//堆积内容分界
						$boundary .= $chunk ; 
					}else{
						$delimiter_len = strlen($delimiter) ;
						$delimiter_len2 = $delimiter_len * 2 ;
						//过滤分隔符
						$boundary = substr($boundary, 0, strpos($boundary, $delimiter));
						//压入分块
						$part.= $delimiter.$chunk ;
						//更改读入长度
						$boundary_len = strlen($boundary);
						//在读取头
						$is_read_multipart_form_header = true ;
					}
				}elseif($is_end===false){
					if ($is_read_multipart_form_header===true) {
						$part.= $chunk ;
						$headers_len = strpos($part, $delimiter.$delimiter) ;
						if ($headers_len!==false) {
							$raw_headers = substr($part, 0, $headers_len ) ;
							// 解析标题列表
							$raw_headers = explode($delimiter, $raw_headers);
							$headers = array();
							$header_array = array();
							//循环解析头
							foreach ($raw_headers as $header) {
								if (empty($header)) {
									continue;
								}
								$header_array =  explode(':', $header);
								$name = isset($header_array[0])?$header_array[0]:'';
								$value = isset($header_array[1])?$header_array[1]:'';
								$headers[strtolower($name)] = ltrim($value, ' ');
							}
							unset($header_array);
							//切除头部分数据
							$part = substr($part, ($headers_len+$delimiter_len2) ) ;
							$part_len = strlen($part);
							// 解析内容处置获得的字段名称等。
							if (isset($headers['content-disposition'])) {
								//结束读头模式
								$is_read_multipart_form_header=false;
								//进入读DATA模式
								$is_read_multipart_form_data=true;

								$delimiter_boundary_len2 = $delimiter_len2 + (2*$boundary_len);
								//默认没有文件名
								$filename = null;$tmp_name = null;
								preg_match(
									'/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
									$headers['content-disposition'],
									$matches
									);
								list(, $type, $name) = $matches;
								$is_file = false;
								//解析文件
								if( isset($matches[4]) ){
									//if labeled the same as previous, skip
									if( isset( $_FILES[ $matches[ 2 ] ] ) ){
										continue;
									}
									//取得文件名称
									$filename = $matches[4];

									//get tmp name
									$filename_parts = pathinfo( $filename );
									$tmp_name = tempnam( ini_get('upload_tmp_dir'), $filename_parts['filename']);

									//填充 $_FILES 随着信息, 大小可能会关闭多字节情况
									$_FILES[ $matches[ 2 ] ] = array(
										'error'=>0,
										'name'=>$filename,
										'tmp_name'=>$tmp_name,
										'size'=>0,
										'type'=>$value
										);
									$is_file = true;
									//写入临时目录
									$tmp_file = fopen($tmp_name, 'w');
									//file_put_contents($tmp_name, $body);
								}else{//现场解析
									$is_file = false;
									$_POST[$name] = '';
								}
							}
						}
					}else if($is_read_multipart_form_data===true){
						$part_len +=$boundary_len;
						$part.= $chunk ;
						$part_write_len = strpos($part, $delimiter.$boundary) ;
						if ($part_write_len!==false) {
							$part_write = substr($part, 0 , $part_write_len );
							//切除头部分数据
							$part = substr($part, ($part_write_len + $boundary_len + $delimiter_len) ) ;
							$part =$part === false ? '':$part ;
							if($is_file){
								$_FILES[ $matches[ 2 ] ]['size'] += $part_write_len;
								//写入文件
								fwrite($tmp_file, $part_write);
								fclose($tmp_file);
							}else{
								//放入内存
								$_POST[$name] .= $part_write;
							}
							unset($part_write);
							if ($part=='--') {
								$is_end = true ;
							}else{
								$part = substr($part, $delimiter_len );
								//进入读头模式
								$is_read_multipart_form_header=true;
								//结束读DATA模式
								$is_read_multipart_form_data=false;
							}
						}else{
							//是否part中内容太长
							if ($part_len>$delimiter_boundary_len2) {
								//计算写入多少
								$part_write_len = $part_len - $delimiter_boundary_len2  ;
								//切除写入内容
								$part_write = substr($part,0,$part_write_len);
								//切除写入部分
								$part = substr($part,$part_write_len);
								//计算剩余长度
								$part_len = strlen($part);
								if($is_file){
									$_FILES[ $matches[ 2 ] ]['size'] += $part_write_len;
									//写入文件
									fwrite($tmp_file, $part_write);
								}else{
									//放入内存
									$_POST[$name] .= $part_write;
								}
								unset($part_write);
							}
						}

					}
				}
				//记录旧数据
				$chunk_old = $chunk ;
			}else{
				//塞入整个流
				$raw_data .= $chunk ;
			}
		}
		fclose($input);
		if ($is_parse_str) {
			parse_str($raw_data,$_POST);
		}
		if ($is_parse_json) {
			$_POST = json_decode($raw_data,true);
		}
		foreach($_POST as $key => $value){
			$_REQUEST[$key] = $value ;
		}
		//获取二进制的md5
		self::$data_md5_raw = hash_final($md5_ctx,true);
		self::__check_content_md5(self::$header['sys']['content_md5'],self::$data_md5_raw);
	}
	//获取签名信息
	private static function __getHttpHeaders(){

		$header = &self::$header;
		$header['sys'] = array();
		$header['x'] = array();
		$header['authorization'] = '';

		$headers_prefix = str_replace('-','_',strtolower(self::$headers_prefix));
		//所有headers参数传输的前缀
		$headers_prefix_len = strlen(self::$headers_prefix);

		$http_prefixlen = strlen('http_');
		$header['authorization'] = isset($_SERVER['HTTP_AUTHORIZATION'])?$_SERVER['HTTP_AUTHORIZATION']:'';
		$header['sys']['content_md5'] = isset($_SERVER['HTTP_CONTENT_MD5'])?$_SERVER['HTTP_CONTENT_MD5']:'';
		$header['sys']['content_type'] = isset($_SERVER['HTTP_CONTENT_TYPE'])?$_SERVER['HTTP_CONTENT_TYPE']:'';
		$header['sys']['content_length'] = intval(isset($_SERVER['HTTP_CONTENT_LENGTH'])?$_SERVER['HTTP_CONTENT_LENGTH']:0);
		$header['sys']['host'] = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'';
		foreach ($_SERVER as $key => $value) {
			$key = substr(strtolower($key),$http_prefixlen);
			if (substr($key,0,$headers_prefix_len)==$headers_prefix) {
				$header['x'][$key] = $value ;
			}
		}
		unset($http_prefixlen);

		if (isset($header['sys']['content_type'])) {
			if (strpos( $header['sys']['content_type'],'multipart/restful-form-data')!==false&&isset($_SERVER['REDIRECT_RESTFUL_MULTIPART_TYPE'])) {
				$header['sys']['content_type'] =  $_SERVER['REDIRECT_RESTFUL_MULTIPART_TYPE'];
			}elseif (strpos( $header['sys']['content_type'],'multipart/restful-form-data')!==false&&isset($_SERVER['REDIRECT_HTTP_CONTENT_TYPE'])) {
				$header['sys']['content_type'] =  $_SERVER['REDIRECT_HTTP_CONTENT_TYPE'];
			}
		}
		//试图去除端口
		try{
			$parse_url_temp = parse_url($header['sys']['host']);
			$header['sys']['host'] = isset($parse_url_temp['host'])?$parse_url_temp['host']:$header['sys']['host'];
			unset($parse_url_temp);
		}catch(Exception $e){}
		if(!empty($_GET[self::$headers_prefix.'authorization'])){
			$header['authorization'] = $_GET[self::$headers_prefix.'authorization'] ;
			unset($_GET[self::$headers_prefix.'authorization']);
		}
		//返回
		return $header ;
	}
	private static function __check_content_md5($content_md5=null,$ws_raw_data_md5_raw=null){
		if (empty($content_md5)) {
			return;
		}
		//base64编码二进制的md5生成标准的 content_md5
		$ws_raw_data_md5_base64 = base64_encode($ws_raw_data_md5_raw);
		//生成hex_md5
		$ws_raw_data_md5_hex = bin2hex($ws_raw_data_md5_raw);
		if ( $content_md5!==$ws_raw_data_md5_base64 && $content_md5!==$ws_raw_data_md5_hex ) {
			throw new AuthErrorException('The Content-MD5 you specified did not match what we received.','AUTHORIZATION_CONTENT_MD5_ERROR',400);
		}
		unset($content_md5,$ws_raw_data_md5_hex,$ws_raw_data_md5_base64);
	} */
}
ApiRESTful::__init();

/**
* 错误总会
*/
class HzException extends Exception
{
	/* 属性 */
	protected $error_id ;
	function __construct( $message = '' , $error_id = 'unknown_error' , $code = '500' )
	{
		parent::__construct($message,$code);
		empty($error_id)||$this->error_id = $error_id;
	}
	public function getErrorId(){
		return empty($this->error_id)?'unknown_error':$this->error_id;
	}
}
/**
* 返回正常
*/
class RJsonSuccessException extends HzException
{
	function __construct($message = '' , $error_id = 'OK' , $code = '200' )
	{
		parent::__construct( $message , $error_id , $code );
	}
}
/**
* 错误总会
*/
class HzErrorException extends HzException
{
	function __construct( $message = 'unknown error' , $error_id = 'unknown_error' , $code = '400' )
	{
		parent::__construct( $message , $error_id , $code );
	}
}

/**
* 授权错误
*/
class AuthErrorException extends HzErrorException
{
	private $CI ;
	function __construct( $message = 'unknown error' , $error_id = 'unknown_error' , $code = '403' )
	{	
		if (function_exists('get_instance')) {
			try{
				//关闭会话
				$this->CI =& get_instance();
				is_object($this->CI)&&isset($this->CI->session)&&is_object($this->CI->session)&&method_exists($this->CI->session,'__sessionWriteClose')&&$this->CI->session->__sessionWriteClose();
			}catch(Exception $e){}
		}
		parent::__construct( $message , $error_id , $code );
	}
}

/**
* 返回错误
*/
class RJsonErrorException extends HzErrorException
{
	function __construct( $message = '', $error_id = 'unknown_error' , $code = '400' )
	{
		parent::__construct( $message , $error_id , $code );
	}
}
/**
* 数据错误
*/
class ModelErrorException extends HzErrorException
{
	function __construct( $message = '' , $error_id = 'unknown_error' , $code = '400' )
	{
		parent::__construct( $message , $error_id , $code );
	}
}
/**
* 错误
*/
class LibrariesErrorException extends HzErrorException
{
	function __construct( $message = '' , $error_id = 'unknown_error' , $code = '400' )
	{
		parent::__construct( $message , $error_id , $code );
	}
}
/**
* 错误
*/
class HelpersErrorException extends HzErrorException
{
	function __construct( $message = '' , $error_id = 'unknown_error', $code = '400' )
	{
		parent::__construct( $message , $error_id , $code );
	}
}

?>