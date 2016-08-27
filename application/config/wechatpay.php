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
$config = array(
	'debug' => 'true',
	'aapid' => 'wxed09814feff70b34',
	'appsecret'=>'a8ab3e878dc5ec3048570166eda4d465', //填写高级调用功能的app id
	'mchid' => '1370184102',
	'key'=>'7E0D3FCCA82A0BA17C442BBD25A00382', //填写你设定的key
	'sslcert_path'=>'/disk2/www/peiLeFangApi/Cert/apiclient_cert.pem', //填写高级调用功能的密钥
	'sslkey_path'=>'/disk2/www/peiLeFangApi/Cert/apiclient_key.pem' //填写加密用的EncodingAESKey
);
?>
