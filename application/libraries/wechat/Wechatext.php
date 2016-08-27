<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  微信公众平台PHP-SDK, Codeigniter实例
 *  $options=[
 *      'account' => '262329131@qq.com',
 *      'password' => 'abc049595',
 *  ];
 *  $this->load->library('wechat/Wechatext',$options,'wechatext');
 *
 */
require_once(dirname(__FILE__) . '/Wechatext_origin.php');

class Wechatext extends Wechatext_origin {
    protected $_CI;

    public function __construct($options) {
        $this->_CI =& get_instance();
        $this->_CI->load->helper('wechat');
        $this->_CI->load->config('wechatext',true);
        $config=$this->_CI->config->item('wechatext');
        $options=array_merge($options,$config);
        parent::__construct($options);
    }

}
