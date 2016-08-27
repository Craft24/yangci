<?php  defined('BASEPATH') OR exit('No direct script access allowed');

include "Snoopy.php";
class Wechatext_origin
{
	private $cookie;
	private $_cookiename;
	private $_cookieexpired = 600;
	private $_account;
	private $_password;
	private $_datapath = './data/cookie_';
	private $debug;
	private $_logcallback;
	private $_token;

	public function __construct($options)
	{
		$this->_account = isset($options['account'])?$options['account']:'';
		$this->_password = isset($options['password'])?$options['password']:'';
		$this->_datapath = isset($options['datapath'])?$options['datapath']:$this->_datapath;
		$this->debug = isset($options['debug'])?$options['debug']:false;
		$this->_logcallback = isset($options['logcallback'])?$options['logcallback']:false;
		$this->_cookiename = isset($options['cookiepath'])?$options['cookiepath'].$this->_account:$this->_datapath.$this->_account;
		$this->cookie = $this->getCookie($this->_cookiename);
	}

	/**
	 * 是否登录
	 * @author: 亮 <chenjialiang@han-zi.cn>
	 */
	public function login(){
		//判断是否登陆成功
		if(!$this->cookie || !$this->_token){
			return false;
		}

		return true;
	}

	/**
	 * 主动发消息
	 * @param  string $openid      用户的openid
	 * @param  string $content 发送的内容
	 * @return string | bool true|| eg :{"base_resp":{"ret":10706,"err_msg":"customer block"}}
	 */
	public function send($openid,$content)
	{
		$send_snoopy = new Snoopy;
		$send_snoopy->referer = "Referer: https://mp.weixin.qq.com/cgi-bin/singlesendpage?t=message/send&action=index&tofakeid=".$openid."&token=".$this->_token."&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$post = array();
		$post=[
			'tofakeid' => $openid,
			'type' => 1,
			'f' => 'json',
			'token' => $this->_token,
			'content' => $content,
			'ajax' => 1,
			'imgcode' => '',
			'random' => time()
		];

		$submit = "https://mp.weixin.qq.com/cgi-bin/singlesend?t=ajax-response&f=json&token=".$this->_token."&lang=zh_CN";
		$send_snoopy->submit($submit,$post);
		$result=$send_snoopy->results;
		$this->log($result);
		$json=json_decode($result,true);
		if ($json && $json['base_resp']['ret']==0)
			return true;
		return $json;
	}

	/**
	 * 群发功能 纯文本
	 * @param string $content
	 * @return string
	 */
	public function mass($content) {
		$send_snoopy = new Snoopy;
		$post = array();
		$post['type'] = 1;
		$post['token'] = $this->_token;
		$post['content'] = $content;
		$post['ajax'] = 1;
		$post['city']='';
		$post['country']='';
		$post['f']='json';
		$post['groupid']='-1';
		$post['imgcode']='';
		$post['lang']='zh_CN';
		$post['province']='';
		$post['random']=  rand(0, 1);
		$post['sex']=0;
		$post['synctxnews']=0;
		$post['synctxweibo']=0;
		$post['t']='ajax-response';
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/masssendpage?t=mass/send&token={$this->_token}&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/masssend";
		$send_snoopy->submit($submit,$post);
		$this->log($send_snoopy->results);
		return $send_snoopy->results;
	}

	/**
	 * 群发功能 图文素材
	 * @param int $appmsgid 图文素材ID
	 * @return string
	 */
	function massSeq(){
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/masssendpage?t=mass/send&token=".$this->_token."&lang=zh_CN";

		$submit = "https://mp.weixin.qq.com/cgi-bin/masssendpage?t=mass/send&token=".$this->_token."&lang=zh_CN&f=json";
		$send_snoopy->fetch($submit);
		$this->log($send_snoopy->results);
		$result = $send_snoopy->results;

		$result=json_decode($result,true);
		if(isset($result) && $result['base_resp']['ret']==0 && !empty($result['operation_seq'])){
			return $result['operation_seq'];
		}
		return false;
	}

	/**
	 * 群发功能 图文素材 版权验证
	 * @param int $appmsgid 图文素材ID
	 * @return string
	 */
	function massNewsFirst($appmsgid,$isfirst=true){
		$send_snoopy = new Snoopy;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/masssendpage?t=mass/send&token=".$this->_token."&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$post = array(
			'token' => $this->_token,
			'lang' => 'zh_CN',
			'f' => 'json',
			'ajax' => '1',
			'random' => time(),
			'first_check' => $isfirst==true?1:0,
			'type' => 10,
			'appmsgid' => $appmsgid,
		);
		$submit = "https://mp.weixin.qq.com/cgi-bin/masssend?action=get_appmsg_copyright_stat&token=".$this->_token."&lang=zh_CN";
		$send_snoopy->submit($submit,$post);
		$result=$send_snoopy->results;
		$this->log($result);
		$json = json_decode($result,true);
		if (isset($json['base_resp']) && $json['base_resp']['ret'] > 0) {
			return true;
		}
		return false;
	}

	/**
	 * 群发功能 图文素材
	 * @param int $appmsgid 图文素材ID
	 * @return string
	 */
	function massNews($appmsgid,$operation_seq){
		$send_snoopy = new Snoopy;
		$post = array();
		$post['type'] = 10;
		$post['token'] = $this->_token;
		$post['appmsgid'] = $appmsgid;
		$post['ajax'] = 1;
		$post['city']='';
		$post['province']='';
		$post['country']='';
		$post['f']='json';
		$post['groupid']='-1';
		$post['imgcode']='';
		$post['lang']='zh_CN';
		$post['random']=  time();
		$post['sex']=0;
		$post['synctxweibo']=0;
		$post['direct_send']=1;
		$post['cardlimit']=1;
		$post['operation_seq']=$operation_seq;

		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/masssendpage?t=mass/send&token=".$this->_token."&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/masssend?t=ajax-response&token=".$this->_token."&lang=zh_CN";

		$send_snoopy->submit($submit,$post);
		$result=$send_snoopy->results;
		$this->log($result);
		$json=json_decode($result,true);
		if (isset($json['base_resp']) && $json['base_resp']['ret'] == 0) {
			return true;
		}
		return $result;
	}

	function massStatistics(){
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/misc/menuanalysis?&token=".$this->_token."&lang=zh_CN";

		$submit = "hhttps://mp.weixin.qq.com/misc/appmsganalysis?action=all&order_direction=2&token=".$this->_token."&lang=zh_CN&f=json";
		$send_snoopy->fetch($submit);
		$this->log($send_snoopy->results);
		$result = $send_snoopy->results;
		$result=json_decode($result,true);
		//if(isset($result) && $result['base_resp']['ret']==0 && $result['base_resp']['err_msg']=='ok'){
			return $result;
		//}
		return false;
	}

	/**
	 * 获取用户列表列表
	 * @param $begin_openid int openid开始,拿取第一页时默认-1
	 * @param $begin_create_time int 创建时间开始,拿取第一页时默认-1
	 * @param $limit int 拿取多少条
	 * @param $groupid int 分组id -2为全部
	 * @return array ({contacts:[{id:12345667,nick_name:"昵称",remark_name:"备注名",group_id:0},{}....]})
	 */
	function getUserList($begin_openid=-1,$begin_create_time=-1,$limit=1000,$groupid=-2){
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/user_tag?action=get_all_data&lang=zh_CN&token=".$this->_token;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit="https://mp.weixin.qq.com/cgi-bin/user_tag?action=get_user_list&groupid=".$groupid."&begin_openid=".$begin_openid."&begin_create_time=".$begin_create_time."&limit=".$limit."&offset=0&backfoward=1&token=".$this->_token."&lang=zh_CN&f=json&ajax=1&random=".$t;

		$send_snoopy->fetch($submit);
		$result = $send_snoopy->results;
		$this->log('userlist:'.$result);

		$json = json_decode($result,true);
		if (isset($json['user_list'])) {
			return $json['user_list']['user_info_list'];
		}
		return false;
	}

	/**
	 * 获取分组列表
	 */
	public function getGroupList(){
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/user_tag?action=get_all_data&lang=zh_CN&token=".$this->_token;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit="https://mp.weixin.qq.com/cgi-bin/user_tag?action=get_user_list&groupid=-2&begin_openid=-1&begin_create_time=-1&limit=20&offset=0&backfoward=1&token=".$this->_token."&lang=zh_CN&f=json&ajax=1&random=".$t;

		$send_snoopy->fetch($submit);
		$result = $send_snoopy->results;
		$this->log('grouplist:'.$result);
		$json = json_decode($result,true);
		if (isset($json['group_info'])) {
			return $json['group_info']['group_info_list'];
		}
		return false;
	}

	/**
	 * 移动用户到分组
	 * @param string $openid 用户openid
	 * @param int $groupid 需要移动的微信分组ID
	 * @return boolean
	 */
	public function moveGroup($openid,$groupid){
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/user_tag?action=get_all_data&lang=zh_CN&token=".$this->_token;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit="https://mp.weixin.qq.com/cgi-bin/user_tag?action=batch_set_tag";
		$post=[
			'token' => $this->_token,
			'lang' => 'zh_CN',
			'f' => 'json',
			'ajax' => '1',
			'random' => $t,
			'user_openid_list' => $openid,
			'groupid_list' => $groupid,
			'cexpandcol' => 1,
		];

		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results; 
		$this->log('moveGroup:'.$result);
		$json = json_decode($result,true);
		if ($json && $json['base_resp']['ret']==0) {
			return true;
		}
		return false;
	}
	
	/**
	 * 添加分组标签
	 * @param $name 标签名称
	 * @author 伟健
	 */
	public function addGroup($name){
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/user_tag?action=get_all_data&lang=zh_CN&token=".$this->_token;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit="https://mp.weixin.qq.com/cgi-bin/user_tag";
		$post=[
				'token' => $this->_token,
				'action' =>'create_group',
				'ajax' => '1',
				'f' => 'json',
				'group_name'=>$name,
				'lang' => 'zh_CN',
				'random' => $t
		];
		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results;
		$this->log('addGroup:'.$result);
		$json = json_decode($result,true);
		if ($json && $json['base_resp']['ret']==0) {
			return $json['add_groupid'];
		}
		return false;
	}
	
	/**
	 * 修改分组标签
	 * @param $groupid 微信公众号groupid
	 * @param $name 标签名称
	 * @author 伟健
	 */
	public function editGroup($groupid,$name){
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/user_tag";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit="https://mp.weixin.qq.com/cgi-bin/user_tag";
		$post=[
				'token' => $this->_token,
				'action' =>'rename_group',
				'ajax' => '1',
				'f' => 'json',
				'groupid'=>$groupid,
				'group_name'=>$name,
				'lang' => 'zh_CN',
				'random' => $t
		];
	
		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results;
		$this->log('editGroup:'.$result);
		$json = json_decode($result,true);
		if ($json && $json['base_resp']['ret']==0) {
			return true;
		}
		return false;
	}
	
	/**
	 * 删除分组标签
	 * @param $groupid 微信公众号groupid
	 * @author 伟健
	 */
	public function delGroup($groupid){
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/user_tag";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit="https://mp.weixin.qq.com/cgi-bin/user_tag";
		$post=[
				'token' => $this->_token,
				'action' =>'del_group',
				'ajax' => '1',
				'f' => 'json',
				'groupid'=>$groupid,
				'lang' => 'zh_CN',
				'random' => $t
		];	
		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results;
		$this->log('delGroup:'.$result);
		$json = json_decode($result,true);
		if ($json && $json['base_resp']['ret']==0) {
			return true;
		}
		return false;
	}

	/**
	 * 获取图文信息列表
	 * @param $page 页码(从0开始)
	 * @param $pagesize 每页大小
	 * @return array
	 */
	public function getNewsList($page,$pagesize=10) {
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$type=10;
		$begin = $page*$pagesize;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/masssendpage?t=mass/send&token=".$this->_token."&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/appmsg?token=".$this->_token."&lang=zh_CN&type=$type&action=list&begin=$begin&count=$pagesize&f=json&random=0.".$t;
		$send_snoopy->fetch($submit);
		$result = $send_snoopy->results;
		$this->log('newslist:'.$result);
		$json = json_decode($result,true);
		if (isset($json['app_msg_info'])) {
			return $json['app_msg_info'];
		}
		return false;
	}

	/**
	 * 获取与指定用户的对话内容
	 * @param  $fakeid
	 * @return  array
	 */
	public function getDialogMsg($fakeid) {
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/masssendpage?t=mass/send&token=".$this->_token."&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/singlesendpage?t=message/send&action=index&tofakeid=".$fakeid."&token=".$this->_token."&lang=zh_CN&f=json&random=".$t;
		$send_snoopy->fetch($submit);
		$result = $send_snoopy->results;
		$this->log('DialogMsg:'.$result);
		$json = json_decode($result,true);
		if (isset($json['page_info'])) {
			return $json['page_info'];
		}
		return false;
	}

	/**
	 * 发送图文信息,必须从图文库里选取消息ID发送
	 * @param  string $id      用户的uid(即FakeId)
	 * @param  string $msgid 图文消息id
	 */
	public function sendNews($id,$msgid)
	{
		$send_snoopy = new Snoopy;
		$post = array();
		$post['tofakeid'] = $id;
		$post['type'] = 10;
		$post['token'] = $this->_token;
		$post['fid'] = $msgid;
		$post['appmsgid'] = $msgid;
		$post['error'] = 'false';
		$post['ajax'] = 1;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/singlemsgpage?fromfakeid={$id}&msgid=&source=&count=20&t=wxm-singlechat&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/singlesend?t=ajax-response";
		$send_snoopy->submit($submit,$post);
		$this->log($send_snoopy->results);
		return $send_snoopy->results;
	}

	/**
	 * 上传图片
	 * @param string $media_ticket
	 * @param int $type $ticket_id
	 * @param string $filepath 图片路径
	 */
	public function uploadFile($media_ticket,$ticket_id,$filepath) {
		$send_snoopy = new Snoopy;
		//$send_snoopy->proxy_host = "hanzikeji.imwork.net";
		//$send_snoopy->proxy_port = "1111";
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/filepage?type=2&begin=0&count=12&t=media/img_list&group_id=1&token=".$this->_token."&lang=zh_CN";
		$fileInfo=pathinfo($filepath);
		$post = array(
			'id' => 'WU_FILE_0',
			'name' => $fileInfo['basename'],
			'type' => getimagesize($filepath)['mime'],
			'lastModifiedDate' => gmdate('D M d Y H:i:s',filemtime($filepath)).' GMT+0800 (中国标准时间)',
			'size' => filesize($filepath),
		);
		$postfile = array(
			'file'=>$filepath,
		);

		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->set_submit_multipart();

		$submit="https://mp.weixin.qq.com/cgi-bin/filetransfer?action=upload_material&f=json&writetype=doublewrite&groupid=1&ticket_id=".$ticket_id."&ticket=".$media_ticket."&svr_time=".time()."&token=".$this->_token."&lang=zh_CN&seq=1&scene=1";

		$send_snoopy->submit($submit,$post,$postfile);
		$tmp = $send_snoopy->results;
		$this->log('upload:'.$tmp);
		$result=json_decode($tmp,true);
		if (isset($result) && $result['base_resp']['ret']==0 && !empty($result['content'])) {
			return [
				'content' => $result['content'],
				'url' => $result['cdn_url']
			];
		}
		return false;
	}

	/**
	 * 创建图文消息
	 * @param array $title 标题
	 * @param array $summary 摘要
	 * @param array $content 内容
	 * @param array $photoid 素材库里的图片id(可通过uploadFile上传后获取)
	 * @param array $srcurl 原文链接
	 * @return json
	 */
	public function addPreview($title,$author,$summary,$content,$photoid,$cdnurl,$srcurl='') {
		$send_snoopy = new Snoopy;
		$send_snoopy->referer = 'https://mp.weixin.qq.com/cgi-bin/operate_appmsg?lang=zh_CN&sub=edit&t=wxm-appmsgs-edit-new&type=10&subtype=3&token='.$this->_token;

		$submit="https://mp.weixin.qq.com/cgi-bin/operate_appmsg?t=ajax-response&sub=create&type=10&token=".$this->_token."&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;

		$send_snoopy->set_submit_normal();
		$post = array(
			'token'=>$this->_token,
			'lang'=>'zh_CN',
			'f'=>'json',
			'ajax'=>1,
			'AppMsgId'=>'',
			'random'=>time(),
		);

		if (count($title)==count($author)&&count($title)==count($summary)&&count($title)==count($content)&&count($title)==count($photoid))
		{
			$i = 0;
			foreach($title as $v) {
				$post['title'.$i] = $title[$i];
				$post['author'.$i] = $author[$i];
				$post['digest'.$i] = $summary[$i];
				$post['content'.$i] = $content[$i];
				$post['fileid'.$i] = $photoid[$i];
				$post['cdn_url'.$i] = $cdnurl[$i];
				if (isset($srcurl[$i])) $post['sourceurl'.$i] = $srcurl[$i];

				$post['music_id'.$i]='';
				$post['video_id0'.$i]='';
				$post['show_cover_pic'.$i]=0;
				$post['shortvideofileid'.$i]='';
				$post['copyright_type'.$i]='';
				$post['need_open_comment'.$i]=0;
				$post['free_content'.$i]='';
				$post['fee'.$i]=0;

				$i++;
			}
		}
		$post['count'] = $i;
		$send_snoopy->submit($submit,$post);
		$tmp = $send_snoopy->results;
		$this->log('step2:'.$tmp);
		$json = json_decode($tmp,true);

		if(isset($json) && $json['base_resp']['ret']=='0' && !empty($json['appMsgId'])){
			return $json['appMsgId'];
		}

		return false;
	}

	/**
	 * 发送媒体文件
	 * @param $id 用户的uid(即FakeId)
	 * @param $fid 文件id
	 * @param $type 文件类型
	 */
	public function sendFile($id,$fid,$type) {
		$send_snoopy = new Snoopy;
		$post = array();
		$post['tofakeid'] = $id;
		$post['type'] = $type;
		$post['token'] = $this->_token;
		$post['fid'] = $fid;
		$post['fileid'] = $fid;
		$post['error'] = 'false';
		$post['ajax'] = 1;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/singlemsgpage?fromfakeid={$id}&msgid=&source=&count=20&t=wxm-singlechat&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/singlesend?t=ajax-response";
		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results;
		$this->log('sendfile:'.$result);
		$json = json_decode($result,true);
		if ($json && $json['ret']==0)
			return true;
		else
			return false;
	}

	/**
	 * 获取素材库文件列表
	 * @param $type 文件类型: 2:图片 3:音频 4:视频
	 * @param $page 页码(从0开始)
	 * @param $pagesize 每页大小
	 * @return array
	 */
	public function getFileList($type,$page,$pagesize=10) {
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$begin = $page*$pagesize;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/masssendpage?t=mass/send&token=".$this->_token."&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/filepage?token=".$this->_token."&lang=zh_CN&type=$type&random=0.".$t."&begin=$begin&count=$pagesize&f=json";
		$send_snoopy->fetch($submit);
		$result = $send_snoopy->results;
		$this->log('filelist:'.$result);
		$json = json_decode($result,true);
		if (isset($json['page_info']))
			return $json['page_info'];
		else
			return false;
	}

	/**
	 * 发送图文信息,必须从库里选取文件ID发送
	 * @param  string $id      用户的uid(即FakeId)
	 * @param  string $fid 文件id
	 */
	public function sendImage($id,$fid)
	{
		return $this->sendFile($id,$fid,2);
	}

	/**
	 * 发送语音信息,必须从库里选取文件ID发送
	 * @param  string $id      用户的uid(即FakeId)
	 * @param  string $fid 语音文件id
	 */
	public function sendAudio($id,$fid)
	{
		return $this->sendFile($id,$fid,3);
	}

	/**
	 * 发送视频信息,必须从库里选取文件ID发送
	 * @param  string $id      用户的uid(即FakeId)
	 * @param  string $fid 视频文件id
	 */
	public function sendVideo($id,$fid)
	{
		return $this->sendFile($id,$fid,4);
	}

	/**
	 * 发送预览图文消息
	 * @param string $account 账户名称(user_name)
	 * @param string $title 标题
	 * @param string $summary 摘要
	 * @param string $content 内容
	 * @param string $photoid 素材库里的图片id(可通过uploadFile上传后获取)
	 * @param string $srcurl 原文链接
	 * @return json
	 */
	public function sendPreview($account,$title,$summary,$content,$photoid,$srcurl='') {
		$send_snoopy = new Snoopy;
		$submit = "https://mp.weixin.qq.com/cgi-bin/operate_appmsg?sub=preview&t=ajax-appmsg-preview";
		$send_snoopy->set_submit_normal();
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = 'https://mp.weixin.qq.com/cgi-bin/operate_appmsg?sub=edit&t=wxm-appmsgs-edit-new&type=10&subtype=3&lang=zh_CN';
		$post = array(
			'AppMsgId'=>'',
			'ajax'=>1,
			'content0'=>$content,
			'count'=>1,
			'digest0'=>$summary,
			'error'=>'false',
			'fileid0'=>$photoid,
			'preusername'=>$account,
			'sourceurl0'=>$srcurl,
			'title0'=>$title,
		);
		$post['token'] = $this->_token;
		$send_snoopy->submit($submit,$post);
		$tmp = $send_snoopy->results;
		$this->log('sendpreview:'.$tmp);
		$json = json_decode($tmp,true);
		return $json;
	}

	/**
	 * 获取用户的信息
	 * @param  string $id 用户的uid(即FakeId)
	 * @return array  {fake_id:100001,nick_name:'昵称',user_name:'用户名',signature:'签名档',country:'中国',province:'广东',city:'广州',gender:'1',group_id:'0'},groups:{[id:0,name:'未分组',cnt:20]}
	 */
	public function getInfo($id)
	{
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$t = time().strval(mt_rand(100,999));
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/getmessage?t=wxm-message&lang=zh_CN&count=50&token=".$this->_token;
		$submit = "https://mp.weixin.qq.com/cgi-bin/getcontactinfo";
		$post = array('ajax'=>1,'lang'=>'zh_CN','random'=>'0.'.$t,'token'=>$this->_token,'t'=>'ajax-getcontactinfo','fakeid'=>$id);
		$send_snoopy->submit($submit,$post);
		$this->log($send_snoopy->results);
		$result = json_decode($send_snoopy->results,true);
		if(isset($result['contact_info'])){
			return $result['contact_info'];
		}
		return false;
	}

	/**
	 * 获得头像数据
	 *
	 * @param FakeId $fakeid
	 * @return JPG二进制数据
	 */
	public function getHeadImg($fakeid){
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/getmessage?t=wxm-message&lang=zh_CN&count=50&token=".$this->_token;
		$url = "https://mp.weixin.qq.com/misc/getheadimg?fakeid=$fakeid&token=".$this->_token."&lang=zh_CN";
		$send_snoopy->fetch($url);
		$result = $send_snoopy->results;
		$this->log('Head image:'.$fakeid.'; length:'.strlen($result));
		if(!$result){
			return false;
		}
		return $result;
	}

	/**
	 * 获取消息更新数目
	 * @param int $lastid 最近获取的消息ID,为0时获取总消息数目
	 * @return int 数目
	 */
	public function getNewMsgNum($lastid=0){
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/getmessage?t=wxm-message&lang=zh_CN&count=50&token=".$this->_token;
		$submit = "https://mp.weixin.qq.com/cgi-bin/getnewmsgnum?t=ajax-getmsgnum&lastmsgid=".$lastid;
		$post = array('ajax'=>1,'token'=>$this->_token);
		$send_snoopy->submit($submit,$post);
		$this->log($send_snoopy->results);
		$result = json_decode($send_snoopy->results,1);
		if(!$result){
			return false;
		}
		return intval($result['newTotalMsgCount']);
	}

	/**
	 * 获取最新一条消息
	 * @return array {"id":"最新一条id","type":"类型号(1为文字,2为图片,3为语音)","fileId":"0","hasReply":"0","fakeId":"用户uid","nickName":"昵称","dateTime":"时间戳","content":"文字内容","playLength":"0","length":"0","source":"","starred":"0","status":"4"}
	 */
	public function getTopMsg(){
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&lang=zh_CN&token=".$this->_token;
		$submit = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&f=json&count=20&day=7&lang=zh_CN&token=".$this->_token;
		$send_snoopy->fetch($submit);
		$this->log($send_snoopy->results);
		$result = $send_snoopy->results;
		$json = json_decode($result,true);
		if (isset($json['msg_items'])) {
			$json = json_decode($json['msg_items'],true);
			if(isset($json['msg_item']))
				return array_shift($json['msg_item']);
		}
		return false;
	}

	/**
	 * 获取新消息
	 * @param $lastid 传入最后的消息id编号,为0则从最新一条起倒序获取
	 * @param $offset lastid起算第一条的偏移量
	 * @param $perpage 每页获取多少条
	 * @param $day 最近几天消息(0:今天,1:昨天,2:前天,3:更早,7:五天内)
	 * @param $today 是否只显示今天的消息, 与$day参数不能同时大于0
	 * @param $star 是否星标组信息
	 * @return array[] 同getTopMsg()返回的字段结构相同
	 */
	public function getMsg($lastid=0,$offset=0,$perpage=20,$day=7,$today=0,$star=0){
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&lang=zh_CN&count=50&token=".$this->_token;
		$lastid = $lastid===0 ? '':$lastid;
		$addstar = $star?'&action=star':'';
		$submit = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&f=json&lang=zh_CN{$addstar}&count=$perpage&timeline=$today&day=$day&frommsgid=$lastid&offset=$offset&token=".$this->_token;
		$send_snoopy->fetch($submit);
		$this->log($send_snoopy->results);
		$result = $send_snoopy->results;
		$json = json_decode($result,true);
		if (isset($json['msg_items'])) {
			$json = json_decode($json['msg_items'],true);
			if(isset($json['msg_item']))
				return $json['msg_item'];
		}
		return false;
	}

	/**
	 * 获取图片消息
	 * @param int $msgid 消息id
	 * @param string $mode 图片尺寸(large/small)
	 * @return jpg二进制文件
	 */
	public function getMsgImage($msgid,$mode='large'){
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/getmessage?t=wxm-message&lang=zh_CN&count=50&token=".$this->_token;
		$url = "https://mp.weixin.qq.com/cgi-bin/getimgdata?token=".$this->_token."&msgid=$msgid&mode=$mode&source=&fileId=0";
		$send_snoopy->fetch($url);
		$result = $send_snoopy->results;
		$this->log('msg image:'.$msgid.';length:'.strlen($result));
		if(!$result){
			return false;
		}
		return $result;
	}

	/**
	 * 获取语音消息
	 * @param int $msgid 消息id
	 * @return mp3二进制文件
	 */
	public function getMsgVoice($msgid){
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/getmessage?t=wxm-message&lang=zh_CN&count=50&token=".$this->_token;
		$url = "https://mp.weixin.qq.com/cgi-bin/getvoicedata?token=".$this->_token."&msgid=$msgid&fileId=0";
		$send_snoopy->fetch($url);
		$result = $send_snoopy->results;
		$this->log('msg voice:'.$msgid.';length:'.strlen($result));
		if(!$result){
			return false;
		}
		return $result;
	}

	/**
	 * 开启开发者模式
	 */
	public function openDevModel()
	{
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&lang=zh_CN&token=".$this->_token;
		$submit = "https://mp.weixin.qq.com/misc/skeyform?form=advancedswitchform&lang=zh_CN";
		$post['flag']=1;
		$post['type']=2;
		$post['token']=$this->_token;
		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results;
		$this->log($send_snoopy->results);
		$json = json_decode($result,true);
		if(!$result){
			return false;
		}
		return true;
	}

	/**
	 * 关闭编辑模式
	 */
	public function closeEditModel()
	{
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&lang=zh_CN&token=".$this->_token;
		$submit = "https://mp.weixin.qq.com/misc/skeyform?form=advancedswitchform&lang=zh_CN";
		$post['flag']=0;
		$post['type']=1;
		$post['token']=$this->_token;
		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results;
		$this->log($send_snoopy->results);
		$json = json_decode($result,true);
		if(!$result){
			return false;
		}
		return true;
	}

	/**
	 * 获取配置接口信息的operation_seq
	 */
	public function getSeq(){
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/advanced/advanced?action=interface&t=advanced/interface&lang=zh_CN&token=".$this->_token;

		$submit = "https://mp.weixin.qq.com/advanced/advanced?action=interface&t=advanced/interface&lang=zh_CN&token=".$this->_token;
		$send_snoopy->fetch($submit);
		$this->log($send_snoopy->results);
		$result = $send_snoopy->results;

		$s1=explode(',operation_seq: "', $result);
		$s2=explode('"', $s1[1]);
		$operation_seq=$s2[0];

		if(empty($operation_seq)){
			return false;
		}

		return $operation_seq;
	}

	/**
	 * 配置接口信息
	 * @param  string $url      接口回调URL
	 * @param  string $token    接口Token
	 */
	public function setUrlToken($url, $token,$operation_seq)
	{
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;

		//发送第一次验证
		$send_snoopy->referer = "https://mp.weixin.qq.com/advanced/advanced?action=interface&t=advanced/interface&lang=zh_CN&token=".$this->_token;
		$submit = "https://mp.weixin.qq.com/advanced/callbackprofile?t=ajax-response&token=".$this->_token."&lang=zh_CN";
		$post=[
			'url' => $url,
			'callback_token' => $token,
			'operation_seq' => intval($operation_seq),
			'encoding_aeskey' => 'STx9GeBydiGz8aNVxLpfZUozWM3ZqOvutcQop09VmMB',
			'callback_encrypt_mode' => 0,

		];

		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results;
		$this->log($send_snoopy->results);
		$json = json_decode($result,true);
		if ($json && $json['base_resp']['ret']==0)
			return true;
		return false;
	}
	/**
	 * 快速设置接口
	 * @param  string $url      接口回调URL
	 * @param  string $token    接口Token
	 * @param  string $operation_seq
	 */
	public function quickSetInterface($url, $token,$operation_seq)
	{
		if ($this->closeEditModel() && $this->openDevModel() && $this->setUrlToken($url, $token,$operation_seq))
			return true;
		return false;
	}
	/**
	 * 获取公众账号基本信息
	 * @param  [string] $dir [指定相对于网站根目录的下载路径，因为需要下载二维码和用户头像]
	 * @return [array]       [公众账号信息，其中包含：nickname,avatar,type,qrcode,appid,appsecret]
	 */
	public function getCommonInfo($dir)
	{
		$userInfo = array();
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&lang=zh_CN&token=".$this->_token;
		$url = "https://mp.weixin.qq.com/cgi-bin/home?t=home/index&lang=zh_CN&token=".$this->_token;
		$send_snoopy->fetch($url);
		$result = $send_snoopy->results;
		// 分析首页内容，获取nickname,avatar,usertype
		preg_match_all('/class=\"nickname\">(.*)<\/a>/', $result, $matches1);
		preg_match_all('/<img src=\"(.*)\" class=\"avatar\"/', $result, $matches2);
		preg_match_all('/<label for=\"\" class=\"type icon_service_label\">(.*)<\/label>/', $result, $matches3);
		$userInfo["nickname"] = $nickname = $matches1[1][0];
		if(strpos($nickname, '<') !== false)
		{
			$userInfo["nickname"] = $nickname = substr($nickname, 0, strpos($nickname, '<'));
		}
		$userInfo["avatar"] = $avatar = $matches2[1][0];
		if( ! empty($matches3[1][0]))
		{
			$userInfo["type"] = $usertype = $matches3[1][0];
		}
		else
		{
			$userInfo["type"] = $usertype = "订阅号";
		}
		$this->log('Analysis account info:'. "\nNickname:". $nickname. "\nAvatar:". $avatar. "\nUsertype:". $usertype);
		// 分析设置页面，获取二维码
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&lang=zh_CN&token=".$this->_token;
		$url = "https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/index&action=index&lang=zh_CN&token=".$this->_token;
		$send_snoopy->fetch($url);
		$result = $send_snoopy->results;
		// $this->log("QRCODE contents:". $result);
		preg_match_all('/<img src=\"(.*)\" width=\"150\"/', $result, $matches4);
		$userInfo["qrcode"] = $qrcode = $matches4[1][0];
		// downloads the avatar
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/index&action=index&lang=zh_CN&token=".$this->_token;
		$url = "https://mp.weixin.qq.com". $avatar;
		$send_snoopy->fetch($url);
		$result = $send_snoopy->results;
		$userInfo["avatar"] = $this->downloadImage($result, $dir. DIRECTORY_SEPARATOR. 'avatars');
		// downloads the qrcode
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/index&action=index&lang=zh_CN&token=".$this->_token;
		$url = "https://mp.weixin.qq.com". $qrcode;
		$send_snoopy->fetch($url);
		$result = $send_snoopy->results;
		$userInfo["qrcode"] = $this->downloadImage($result, $dir. DIRECTORY_SEPARATOR. 'qrcodes');
		// 获取appid和appsecret
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/index&action=index&lang=zh_CN&token=".$this->_token;
		$url = "https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&lang=zh_CN&token=".$this->_token;
		$send_snoopy->fetch($url);
		$result = $send_snoopy->results;
		preg_match_all('/name:\"AppId\",value:\"(.*)\"/', $result, $matches_id);
		preg_match_all('/name:\"AppSecret\",value:\"(.*)\"/', $result, $matches_secret);

		$userInfo["appid"] = $AppId = $matches_id[1][0];
		$userInfo["appsecret"] = $AppSecret = $matches_secret[1][0];

		if(! empty($userInfo)){
			return $userInfo;
		}
		return false;
	}
	/**
	 * 下载图片资源
	 * @param  [string] $from    [资源链接]
	 * @param  [string] $dir     [相对于网站根目录]
	 * @return [string]          [返回相对地址]
	 */
	public function downloadImage($from, $dir)
	{
		$random_name =  str_replace('.', '', microtime(true)). rand(2, 10000);
		if( ! is_dir($dir))
		{
			mkdir($dir, 0755, true);
		}
		$savefile = preg_replace('/[\\\\\/]+/', DIRECTORY_SEPARATOR, $dir. '/'. $random_name);
		file_put_contents($savefile, $from);
		$filesize = filesize($savefile);
		$avatar_type = $this->checkImgType($savefile);
		if ($filesize <= 0 || $avatar_type=='unknown')
		{
			unlink($savefile);
		}
		exec("mv $savefile ". $savefile. '.'. $avatar_type);
		return $dir. '/'. $random_name. '.'. $avatar_type;
	}
	/**
	 * 检测图片类型
	 * @param  [string] $imgName [文件路径]
	 * @return [string]          [文件类型]
	 */
	public function checkImgType($imgName){
		$file = fopen($imgName, "rb");
		$bin = fread($file, 2);
		$strInfo  = @unpack("C2chars", $bin);
		$typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
		switch($typeCode)
		{
			case '255216':
				return 'jpg';
				break;
			case '7173':
				return 'gif';
				break;
			case '13780':
				return 'png';
				break;
			case '6677':
				return 'bmp';
				break;
			default:
				return 'unknown';
				break;
		}
	}

	/**
	 * 模拟登录获取cookie
	 * @return [type] [description]
	 */
	public function setlogin(){
		$snoopy = new Snoopy;
		$submit = "https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN";
		$post["username"] = $this->_account;
		$post["pwd"] = md5($this->_password);
		$post["f"] = "json";
		$post["imgcode"] = "";
		$snoopy->referer = "https://mp.weixin.qq.com/";
		$snoopy->submit($submit,$post);
		$cookie = '';
		$this->log($snoopy->results);
		$result = json_decode($snoopy->results,true);
		if (!isset($result['base_resp']) || $result['base_resp']['ret'] != 0) {
			return false;
		}

		foreach ($snoopy->headers as $key => $value) {
			$value = trim($value);
			if(preg_match('/^set-cookie:[\s]+([^=]+)=([^;]+)/i', $value,$match))
				$cookie .=$match[1].'='.$match[2].'; ';
		}

		preg_match("/token=(\d+)/i",$result['redirect_url'],$matches);
		if($matches){
			$this->_token = $matches[1];
			$this->log('token:'.$this->_token);
		}
		$cookies='{"cookie":"'.$cookie.'","token":"'.$this->_token.'"}';
		$this->saveCookie($this->_cookiename,$cookies);

		return $cookie;
	}

	/**
	 * 把cookie写入缓存
	 * @param  string $filename 缓存文件名
	 * @param  string $content  文件内容
	 * @return bool
	 */
	public function saveCookie($filename,$content){
		return file_put_contents($filename,$content);
	}

	/**
	 * 读取cookie缓存内容
	 * @param  string $filename 缓存文件名
	 * @return string cookie
	 */
	public function getCookie($filename){
		if (file_exists($filename)) {
			$mtime = filemtime($filename);
			if ($mtime<time()-$this->_cookieexpired)
				$data = '';
			else
				$data = file_get_contents($filename);
		} else
			$data = '';
		if($data){
			$login=json_decode($data,true);
			$send_snoopy = new Snoopy;
			$send_snoopy->rawheaders['Cookie']= $login['cookie'];
			$send_snoopy->maxredirs = 0;
			$url = "https://mp.weixin.qq.com/cgi-bin/home?t=home/index&lang=zh_CN&token=".$login['token'];
			$send_snoopy->fetch($url);
			$header = $send_snoopy->headers;
			$this->log('header:'.print_r($send_snoopy->headers,true));
			if( strstr($header[3], 'EXPIRED')){
				return $this->setlogin();
			}else{
				$this->_token =$login['token'];
				$this->log('token:'.$this->_token);
				return $login['cookie'];
			}
		}else{
			return $this->setlogin();
		}
	}

	/**
	 * 验证cookie的有效性
	 * @return bool
	 */
	public function checkValid()
	{
		if (!$this->cookie || !$this->_token) return false;
		$send_snoopy = new Snoopy;
		$post = array('ajax'=>1,'token'=>$this->_token);
		$submit = "https://mp.weixin.qq.com/cgi-bin/getregions?id=1017&t=ajax-getregions&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results;
		if(json_decode($result,1)){
			return true;
		}else{
			return false;
		}
	}

	protected function log($log){
		if ($this->debug && function_exists($this->_logcallback)) {
			if (is_array($log)) $log = print_r($log,true);
			return call_user_func($this->_logcallback,$log);
		}
	}

	/**
	 * 获取公众号信息
	 * @author: 亮 <chenjialiang@han-zi.cn>
	 * @return bool | array
	 */
	public function getMpInfo(){
		$send_snoopy = new Snoopy;
		$send_snoopy->agent='Mozilla/5.0 (Winows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36';

		$send_snoopy->rawheaders['Cookie']=$this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&lang=zh_CN&token=".$this->_token;



		$submit = "https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/index&action=index&token=".$this->_token."&lang=zh_CN&f=json";
		$send_snoopy->fetch($submit);
		$this->log($send_snoopy->results);
		$result = $send_snoopy->results;


		$json = json_decode($result,true);
		if (isset($json['user_info'])) {
			if(isset($json['base_resp']['media_ticket']))
				$json['user_info']['media_ticket']=$json['base_resp']['media_ticket'];

			if(isset($json['setting_info']['original_username']))
				$json['user_info']['original_username']=$json['setting_info']['original_username'];
				return $json['user_info'];
		}
		return false;
	}



}