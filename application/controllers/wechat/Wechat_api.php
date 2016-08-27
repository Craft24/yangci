<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Wechat_api extends Wx_Base_Api_1_0_Controller{
	public $weixinData = null;  //微信数据
	public $weObj = null;  //实例化微信类

	public function index(){
		$this->load->library('wechat/Wechat',null,'Wechat');
		$weObj=$this->Wechat;
		$this->weObj=$weObj;
		$weObj->valid();
		$type = $weObj->getRev()->getRevType(); // 获取数据类型
		$data = $weObj->getRev()->getRevData(); // 获取微信服务器发来的信息
		$local = $weObj->getRevGeo();
		$this->weixinData = $data;
		switch ($type) {
			case $weObj::MSGTYPE_TEXT : // 文本类型			
				//$weObj->text("已收到")->reply();
				$reply = $this->__getKeywordReply($data['Content']);
				if (is_string($reply)) {
					$weObj->text($reply)->reply();
				}elseif (is_array($reply)&&!empty($reply)) {
					$weObj->news($reply)->reply();die;
				}else{  //没有关键字，自动回复
					$replyAuto = $this->__getReplyAuto();
					if (is_string($replyAuto)) {
						$weObj->text($replyAuto)->reply();
					}elseif (is_array($replyAuto)&&!empty($replyAuto)) {
						$weObj->news($replyAuto)->reply();
					}
				}
				break;
			case $weObj::MSGTYPE_EVENT : // 事件类型
				if ($data ['Event'] == "subscribe") { // 关注事件
					//关注回复
					$reply = $this->__getFollowReply();
					if (is_string($reply)) {
						$weObj->text($reply)->reply();
					}elseif (is_array($reply)&&!empty($reply)) {
						$weObj->news($reply)->reply();
					}
				} elseif ($data ['Event'] == "unsubscribe") { // 取消关注事件

				} elseif ($data ['Event'] == "LOCATION") { // 获取上报的地理位置事件
					
				} elseif ($data ['Event'] == "CLICK") { // 自定义菜单点击时间						
					//触发多客服

					//菜单点击事件
					$content = $data["EventKey"];
					$reply = $this->__getKeywordReply($content);
					if (is_string($reply)) {
						$weObj->text($reply)->reply();
					}elseif (is_array($reply)&&!empty($reply)) {
						error_log(json_encode($reply));
						$weObj->news($reply)->reply();
					}
				} elseif ($data ['Event'] == "VIEW") { // 点击菜单跳转链接时的事件推送
									
				}
				break;
			case $weObj::MSGTYPE_IMAGE : // 图片类型

				break;
			case $weObj::MSGTYPE_LOCATION : // 地理位置类型
				
 				//log_message('error',json_encode($reply));
 				break;
			case $weObj::MSGTYPE_LINK : // 链接消息

				break;
			default :
				$weObj->text("help info")->reply();
		}
	}

	/**
	 * 获取微信js配置
	 */
	public function get_jsconfig(){
		//$url=$this->input->get('url');
		$url='http://cdn.invitehost.ping-qu.com/wap/pay/wechat';
		if (empty($url)) {
			throw new RJsonErrorException('请传入url', 'URL_NOT_EMPTY');
		}
		$this->config->load('wechat',true);
		$config = $this->config->item('mp','wechat');
		$this->load->library('Wechat',$config,'Wechat');
		$wechatConfig = $this->Wechat->getJsSign($url);
		$config_array = array(
			'timeStamp'=>$wechatConfig['timestamp'],
			'noncestr'=>$wechatConfig['noncestr'],
			'paySign'=>$wechatConfig['signature'],
		);
		$this->session->set_userdata($config_array);
		$this->r['wechatConfig'] =$wechatConfig;
		$this->state = true;
		$this->r();
	}

	/**
	 * 关键词回复
	 * @author: 凌翔 <553299576@qq.com>
	 * @DateTime 2016-05-06T10:52:46+0800
	 * @param    [int]                   $mpID    [公众号id]
	 * @param    [string]                   $content [内容]
	 * @return   [array]                            [文字，图文]
	 */
	private function __getKeywordReply($content){
		//查用户自己的关键词回复设置
		$this->load->library('module/shop_admin/C_Wx_keyword',null,'wx_keyword');
		$keyword = $this->wx_keyword->getListByKeyword($content);
		if (!empty($keyword)) {
				switch ($keyword['type']) {
						case '0'://图文
							$this->load->library('module/shop_admin/C_Wx_news',null,'wx_news');
						    $news = $this->wx_news->getListByIds($keyword['news_ids']);
						    foreach ($news as $key => $v) {
						    	if ($v['type'] == 1) {
						    		$this->config->load('news_path',true);
						    		$url = $this->config->item('news_path','news_path');
						    		$news[$key]['Url'] = $url.$v['news_id'];
						    	}
						    }
						    return $news;
							break;
						case '1'://文字
							return $keyword['content'];
							break;
					}
			//var_dump($id);exit;
		}
		return false;
	}
	
	/**
	 * 关注回复
	 */
	private function __getFollowReply(){
		$this->load->library('module/shop_admin/C_Wx_follow',null,'wx_follow');
		$info = $this->wx_follow->getOne();
		if ($info) {
			switch ($info['type']) {
			case '0'://图文
				$this->load->library('module/shop_admin/C_Wx_news',null,'wx_news');
				$news = $this->wx_news->getListByIds($keyword['news_ids']);
				$this->config->load('news_path',true);
				$url = $this->config->item('news_path','news_path');
				foreach ($news as $key => $v) {
					if ($v['type'] == 1) {
						$news[$key]['Url'] = $url.$v['news_id'];
					}
				}
				return $news;
				break;
			case '1'://文字
				return $info['content'];
				break;
			}
		}else{
			//没有设置默认回复
			$content='欢迎关注培乐坊熊工厂';
			return $content;
		}
	}
	
	/**
	 * 自动回复
	 */
	private function __getReplyAuto(){
		$this->load->library('module/shop_admin/C_Wx_auto',null,'wx_auto');
		$info = $this->wx_auto->getOne();
		if ($info) {
			switch ($info['type']) {
				case '0'://图文
					$this->load->library('module/shop_admin/C_Wx_news',null,'wx_news');
				$news = $this->wx_news->getListByIds($info['news_ids']);
					$this->config->load('news_path',true);
					$url = $this->config->item('news_path','news_path');
					foreach ($news as $key => $v) {
						if ($v['type'] == 1) {
							$news[$key]['Url'] = $url.$v['news_id'];
						}
					}
					return $news;
					break;
				case '1'://文字
					return $info['content'];
					break;
			}
		}else{
			//没有设置默认回复
			$content='谢谢关注';
			return $content;
		}
	}
}