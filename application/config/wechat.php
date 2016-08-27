<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Memcached settings
| -------------------------------------------------------------------------
| Your Memcached servers can be specified below.
|
|	See: http://codeigniter.com/user_guide/libraries/caching.html#memcached
|
*/
// $config = array(
// 	'debug' => 'true',
// 	'logcallback' => 'wechat_error_log',
// 	'token'=>'playfun', //填写你设定的key
// 	'appid'=>'wxed09814feff70b34', //填写高级调用功能的app id
// 	'appsecret'=>'a8ab3e878dc5ec3048570166eda4d465', //填写高级调用功能的密钥
// 	'encodingaeskey'=>'8lsCynKXg1CbzEAYC0dpnvXf2FrbSmKCDDgMRjt7be6' //填写加密用的EncodingAESKey
// );

/**
 * app
 * @var array
 */
$config = array(
	'mp'=>array(
		'debug' => 'true',
		'logcallback' => 'wechat_error_log',
		'token'=>'playfun', //填写你设定的key
		'appid'=>'wxed09814feff70b34', //填写高级调用功能的app id
		'appsecret'=>'a8ab3e878dc5ec3048570166eda4d465', //填写高级调用功能的密钥
		'encodingaeskey'=>'8lsCynKXg1CbzEAYC0dpnvXf2FrbSmKCDDgMRjt7be6' //填写加密用的EncodingAESKey
	),
	'web'=>array(
		'debug' => 'true',
		'logcallback' => 'wechat_error_log',
		'appid'=>'', //填写高级调用功能的app id
		'appsecret'=>'' //填写高级调用功能的密钥
	),
	'app'=>array(
		'debug' => 'true',
		'logcallback' => 'wechat_error_log',
		'appid'=>'', //填写高级调用功能的app id
		'appsecret'=>'' //填写高级调用功能的密钥
	)
);
?>