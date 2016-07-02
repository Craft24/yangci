<?php defined('BASEPATH') OR exit('No direct script access allowed');


	/**
	 * 登录密码加密
	 */
	function encryptPassword($string,$salt){
	    return md5(sha1($string.$salt));
	}

	/**
	 * 获取随机字符
	 */
	function getRandChar($length){
	    $str = null;
	    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
	    $max = strlen($strPol)-1;
	    for($i=0;$i<$length;$i++){
	        $str.=$strPol[rand(0,$max)];
	    }
	    return $str;
	}

?>