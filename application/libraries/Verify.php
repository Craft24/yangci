<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Verify {	
	public function __construct($params = array())
	{
	}

	public function get_data(){
		if(!empty($this->rule) /*&& !empty($this->data)*/){
			$data=array();
			foreach($this->rule as $key => $v){
				if(isset($this->data[$key])){
					$data[$key]=$this->data[$key];
				}
			}
			return $data;
		}

		throw new LibrariesErrorException('没有数据','NO_DATA');
	}
	
	
	/**
	 * 验证
	 * email,mobile,num,ip,url,idCard
	 * @param array $rule
	 * @param array $data
	 */
	public function verify($rule,$data=null){
		//默认post数据
		if($data===null){
			$data=$_POST;
		}

		$r_data = array();
		//var_dump($rule);
		//var_dump($data);
		foreach ($rule as $key=>$v){
			//----分析参数

			//定义参数的值
			$value=isset($data[$key])?$data[$key]:'';

			//默认结果为false
			$rt=false;

			//额外参数
			$extra = isset($v[1]) ? $v[1] : null;

			//该参数是否必须传入
			$is_required= ( !isset($v[2]) || $v[2]===true ) ? true : false ;

			//验证传入
			if(!is_string($key)){
				throw new LibrariesErrorException('验证规则格式错误！', 'format-error');
			}


			if (!isset($data[$key])){
				if($is_required){ 	//设置了必须传入的,验证是否传入
					throw new LibrariesErrorException($key.'验证字段不存在！', $key.'-error');
				}
				else{		//设置了必须传入的,验证是否传入,如果传入则验证
					if(array_key_exists($key,$data)){
						throw new LibrariesErrorException($key.'验证字段不能为空！', $key.'-error');
					}
					continue;
				}
			}

			//验证类型
			if(!isset($v[0])){
				$ver_type='notNull';   //默认为非空
			}
			else{
				$ver_type=$v[0];
			}

			//该参数是否允许传空值,仅允许传字符串的'',不能传null
			$can_null= ( isset($v[3]) && $v[3] === true ) ? true : false ;
			if( $can_null && $data[$key] === '' ){
				continue;
			}

			//如果非空类型,又设置了可传空值,报错
			if( $can_null && $ver_type === 'notNull' ){
				throw new LibrariesErrorException('不能同时设置非空验证类型和可传空值的选项！', 'format-error');
			}

			switch ($ver_type){
				case 'canNull' :   //可为空而不验证类型
					$rt=true;
					break;
				case 'notNull' :   //不能为空
					$rt=$this->notNull($value);
					break;
				case 'email' :		//验证邮箱
					$rt=$this->isEmail($value);
					break;
				case 'mobile' :		//验证手机
					$rt=$this->isMobile($value);
					break;
				case 'num' :   //判断是否为数字
					$rt=$this->isNum($value,$extra);
					break;
				case 'egNum' : 	//正整数数字(大于0的整数)
					$rt=$this->isEgNum($value,$extra);
					break;
				case 'zcode':
					$rt=$this->isZcode($value);
					break;
				case 'domain':
					$rt=$this->isDomain($value);
					break;
				case 'money':
					$rt=$this->isMoney($value);
					break;
				case 'ip' :
					$rt=$this->isIP($value);
					break;
				case 'url' :
					$rt=$this->isURL($value);
					break;
				case 'idCard' :
					$rt=$this->isIDcard($value);
					break;
				case 'in' :
					$rt=$this->in($value, $extra);
					break;
				case 'reg' :
					$rt=$this->reg($value, $extra);
					break;
				default:
					$rt=$this->notNull($value);
					break;
			}
	
			if ($rt==false){
				throw new LibrariesErrorException($key.'字段验证错误！', $key.'-error');
			}

			$r_data[$key]=$value;
		}
		 
		return $r_data;
	}

	/**
	 * 验证是否可为空
	 * @author: 亮 <chenjialiang@han-zi.cn>
	 */
	public function notNull($value){
		if ( !empty($value) || $value === 0 || $value === "0" ){
			return true;
		}
		return false;
	}

	/**
	 * 是否正整数
	 * @author: 亮 <chenjialiang@han-zi.cn>
	 */
	public function isEgNum($value,$max_value=null){
		if(!preg_match('/^\+?[1-9]\d*$/',$value)){
			return false;
		}

		//判断范围
		if ($max_value!==null){
			if (!(intval($value) < $max_value)){
				return false;
			}

		}
		return true;
	}

	/**
	 * 是否数字
	 * @author: 亮 <chenjialiang@han-zi.cn>
	 */
	public function isNum($value,$extra=null){
		if (!is_numeric($value)) {
			return false;
		}

		return true;
	}
	
	/**
	 * 验证是否为指定语言,$value传递值;$minLen最小长度;$maxLen最长长度;$charset默认字符类别（en只能英文;cn只能汉字;alb数字;ALL不限制）
	 * @param string $value
	 * @param int $length
	 * @return boolean
	 */
	public function islanguage($value, $charset = 'all', $minLen = 1, $maxLen = 50) {
		if (!$value)
			return false;
		switch ($charset) {
			case 'en' :
				$match = '/^[a-zA-Z]{' . $minLen . ',' . $maxLen . '}$/iu';
				break;
			case 'cn' :
				$match = '/^[\x{4e00}-\x{9fa5}]{' . $minLen . ',' . $maxLen . '}$/iu';
				break;
			case 'alb' :
				$match = '/^[0-9]{' . $minLen . ',' . $maxLen . '}$/iu';
				break;
			case 'enalb' :
				$match = '/^[a-zA-Z0-9]{' . $minLen . ',' . $maxLen . '}$/iu';
				break;
			case 'all' :
				$match = '/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]{' . $minLen . ',' . $maxLen . '}$/iu';
				break;
				// all限制为：只能是英文或者汉字或者数字的组合
		}
		return preg_match($match, $value);
	}
	
	/**
	 * 验证eamil,$value传递值;$minLen最小长度;$maxLen最长长度;$match正则方式
	 * @param string $value
	 * @param int $length
	 * @return boolean
	 */
	public function isEmail($value, $minLen = 6, $maxLen = 60, $match = '/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/i') {
		if (!$value)
			return false;
		return (strlen($value) >= $minLen && strlen($value) <= $maxLen && preg_match($match, $value)) ? true : false;
	}
	
	/**
	 * 格式化money,$value传递值;小数点后最多2位
	 * @param string $value
	 * @return boolean
	 */
	public function formatMoney($value) {
		return sprintf("%1\$.2f", $value);
	}
	
	/**
	 * 验证电话号码,$value传递值;$match正则方式
	 * @param string $value
	 * @return boolean
	 */
	public function isTelephone($value, $match = '/^(0[1-9]{2,3})(-| )?\d{7,8}$/') {
		// 支持国际版：$match='/^[+]?([0-9]){1,3}?[ |-]?(0[1-9]{2,3})(-| )?\d{7,8}$/'
		if (!$value)
			return false;
		return preg_match($match, $value);
	}
	
	/**
	 * 验证手机,$value传递值;$match正则方式
	 * @param string $value
	 * @param string $match
	 * @return boolean
	 */
	public function isMobile($value, $match = '/^(0)?1([3|4|5|7|8])+([0-9]){9,10}$/') {
		// 支持国际版：([0-9]{1,5}|0)?1([3|4|5|8])+([0-9]){9,10}
		if (!$value)
			return false;
		return preg_match($match, $value);
	}
	
	/**
	 * 验证IP,$value传递值;$match正则方式
	 * @param string $value
	 * @param string $match
	 * @return boolean
	 */
	public function isIP($value, $match = '/^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/') {
		if (!$value)
			return false;
		return preg_match($match, $value);
	}
	
	/**
	 * 验证身份证号码,$value传递值;$match正则方式
	 * @param string $value
	 * @param string $match
	 * @return boolean
	 */
	public function isIDcard($value) {
		if (!$value)
			return false;
		else if (strlen($value) > 18)
			return false;

		if(!preg_match('/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/',$value)){
			return false;
		}

		return true;
	}
	
	/**
	 * 验证URL,$value传递值;$match正则方式
	 * @param string $value
	 * @param string $match
	 * @return boolean
	 */
	public function isURL($value, $match = '/^(http:\/\/)?(https:\/\/)?([\w\d-]+\.)+[\w-]+(\/[\d\w-.\/?%&=]*)?$/') {
		$value = strtolower(trim($value));
		if (!$value)
			return false;
		return preg_match($match, $value);
	}
	
	/**
	 * 验证邮政编码,$value传递值;$match正则方式
	 * @param string $value
	 * @param string $match
	 * @return boolean
	 */
	public function isZcode($value, $match = '/^([0-9]{5})(-[0-9]{4})?$/i') {
		$value = strtolower(trim($value));
		if (!$value)
			return false;
		return preg_match($match, $value);
	}
	
	/**
	 * 验证域名,$value传递值;$match正则方式
	 * @param string $value
	 * @param string $match
	 * @return boolean
	 */
	public function isDomain($value, $match = '/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i') {
		$value = strtolower(trim($value));
		if (!$value)
			return false;
		return preg_match($match, $value);
	}
	
	/**
	 * 验证金额,$value传递值;$match正则方式
	 * @param string $value
	 * @param string $match
	 * @return boolean
	 */
	public function isMoney($value, $match = '/^(([1-9]\d{0,9})|0)(\.\d{1,2})?$/') {
		$value = strtolower(trim($value));
		if (!$value)
			return false;
		return preg_match($match, $value);
	}
	
	/**
	 * 包含验证
	 * @param mix $value
	 * @param array $match
	 * @return number
	 */
	public function in($value, $match){
		return in_array($value, $match);
	}
	
	/**
	 * 多个参数其中一个不为空
	 * @param mix $value
	 * @param array $data
	 * @return number
	 */
	//public function multiToOne($value, $data){
	//	$nullValue=false;
	//	$count=count($value);
	//	$i=0;
	//	foreach ($value as $key=>$a){
	//		if($this->verify($value, $data)){
	//			$nullValue=true;
	//			$i++;
	//		}
	//	}
	//	
	//	if ($i==0){
	//        throw new LibrariesErrorException('请输入内容', 'notice_content-not-empty');
	//		$this->verError='字段验证错误！';
	//	}
	//	elseif($i!=1){
	//		$nullValue=false;
	//        throw new LibrariesErrorException('请输入内容', 'notice_content-not-empty');
	//		$this->verError='字段验证错误！';
	//	}
	//	
	//	return $nullValue;
	//}
	
	/**
	 * 自定义验证
	 * @param mix $value
	 * @param unknown $match
	 * @return number
	 */
	public function reg($value, $match){
		return preg_match($match, $value);
	}
	

}
