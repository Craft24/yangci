<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  微信公众平台PHP-SDK, Codeigniter实例
 *  @author nigelvon@gmail.com
 *  @link https://github.com/dodgepudding/wechat-php-sdk
 *  @edit liang
 *  usage:
 *  $this->load->library('wechat/Wechat',['type'=>'mp'],'Wechat');
 */
require_once(dirname(__FILE__) . '/Wechat_origin.php');

class Wechat extends Wechat_origin {
    protected $_CI;

    public function __construct($setting=array()) {
        $this->_CI =& get_instance();
        $this->_CI->load->helper('wechat');
        $this->_CI->load->driver('cache');

        $this->_CI->load->config('wechat',true);
        $config=$this->_CI->config->item('wechat');

        if(!isset($setting['type'])){
            $setting_type='mp';
        }
        else{
            $setting_type=$setting['type'];
        }
        $options=$config[$setting_type];
        parent::__construct($options);

    }

    const GROUP_DELETE_URL='/groups/delete?';
    ///多客服相关地址
    const CS_KF_ACCOUNT_INVITEWORKER_URL = '/customservice/kfaccount/inviteworker?';
    ///门店相关
    const ADD_POI = '/cgi-bin/poi/addpoi?';//创建门店
    const UPDATE_POI = '/cgi-bin/poi/updatepoi?';//修改门店
    const DEL_POI = '/cgi-bin/poi/delpoi?';//修改门店
    ///红包相关
    const API_LUCKY_MONEY_URL_PREFIX = 'https://api.mch.weixin.qq.com';//红包接口
    const ADD_PRE_LUCKY_MONEY = '/mmpaymkttransfers/hbpreorder';//红包预下单接口
    const CREATE_LUCKY_MONEY = '/shakearound/lottery/addlotteryinfo?';//创建红包活动
    const SET_PRIZE_BUCKET = '/shakearound/lottery/setprizebucket?';//录入红包信息
    const SET_LOTTERY_SWITCH = '/shakearound/lottery/setlotteryswitch?';//设置红包活动抽奖开关
    const QUERY_LOTTERY = '/shakearound/lottery/querylottery?';//设置红包活动抽奖开关
    ///卡劵相关
    const SELF_CONSUME_CELL = '/card/selfconsumecell/set?';//设置自助核销接口
    const PAY_CELL = '/card/paycell/set?';//设置买单接口
    const LANDINGPAGE_CREATE= '/card/landingpage/create?';//创建货架接口
    
    ///微信摇一摇周边
    const SHAKEAROUND_DEVICE_APPLY_STATUS = '/shakearound/device/applystatus?';//查询设备ID申请审核状态   
    const SHAKEAROUND_ACCOUNT_REGISTER = '/shakearound/account/register?';//​申请开通功能
    const SHAKEAROUND_ACCOUNT_AUDITSTATUS = '/shakearound/account/auditstatus?';//​申请开通功能
    
    //标签管理
    const TAGS_GET_URL='/tags/get?';
    const USER_TAGS_URL='/tags/getidlist?';
    const TAGS_CREATE_URL='/tags/create?';
    const TAGS_UPDATE_URL='/tags/update?';
    const TAGS_DELETE_URL='/tags/delete?';
    const TAGS_USER_GET_URL='/user/tag/get?';
    const TAGS_MEMBERS_BATCHTAGGING_URL='/tags/members/batchtagging?';
    const TAGS_MEMBERS_BATCHUNTAGGING_URL='/tags/members/batchuntagging?';

    //群发统计
    const API_URL_MASS_DATACUBE = 'https://api.weixin.qq.com/datacube';//数据统计api接口
    const MASS_GET_ARTICLE_SUMMARY = '/getarticlesummary?';//获取图文群发每日数据


    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    public function http_post($url,$param,$post_file=false){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach($param as $key=>$val){
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);

//**//*/
    /*curl_setopt($oCurl, CURLOPT_HTTPPROXYTUNNEL, TRUE);  
     curl_setopt($oCurl, CURLOPT_PROXY, '183.48.73.135:2233'); */
     //curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'user:password');//如果要密码的话，加上这个  
     
//**//*/



        if($post_file && version_compare(PHP_VERSION, '5.5.0') >= 0){
            foreach($strPOST as $key => $v){
                $args[$key] = new CurlFile($strPOST[$key], mime_content_type($strPOST[$key]));
                curl_setopt($oCurl, CURLOPT_POSTFIELDS, $args);
            }
        }
        else{
            curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
        }

        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

    /**
     * 重载设置缓存
     * @param string $cachename
     * @param mixed $value
     * @param int $expired
     * @return boolean
     */
    protected function setCache($cachename, $value, $expired) {
        return $this->_CI->cache->memcached->save($cachename, $value, $expired);
    }

    /**
     * 重载获取缓存
     * @param string $cachename
     * @return mixed
     */
    protected function getCache($cachename) {
        return $this->_CI->cache->memcached->get($cachename);
    }

    /**
     * 重载清除缓存
     * @param string $cachename
     * @return boolean
     */
    protected function removeCache($cachename) {
        return $this->_CI->cache->memcached->delete($cachename);
    }

    /**
     * 添加客服账号(新版)
     *
     * @param string $account      //完整客服账号，格式为：账号前缀@公众号微信号，账号前缀最多10个字符，必须是英文或者数字字符
     * @param string $nickname     //客服昵称，最长6个汉字或12个英文字符
     * @return boolean|array
     * 成功返回结果
     * {
     *   "errcode": 0,
     *   "errmsg": "ok",
     * }
     */
    public function addKFAccount($account,$nickname){
        $data=array(
                "kf_account" =>$account,
                "nickname" => $nickname
        );
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_post(self::API_BASE_URL_PREFIX.self::CS_KF_ACCOUNT_ADD_URL.'access_token='.$this->access_token,self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }
    
    /**
     * 邀请绑定客服帐号(新版)
     *
     * @param string $account      //完整客服账号，格式为：账号前缀@公众号微信号，账号前缀最多10个字符，必须是英文或者数字字符
     * @param string $wx           //接收绑定邀请的客服微信号
     * @return boolean|array
     * 成功返回结果
     * {
     *   "errcode": 0,
     *   "errmsg": "ok",
     * }
     */
    public function inviteworkerKFAccount($account,$wx){
        $data=array(
                "kf_account" =>$account,
                "invite_wx" => $wx
        );
        if (!$this->access_token && !$this->checkAuth()) return false;
        $str=self::API_BASE_URL_PREFIX.self::CS_KF_ACCOUNT_INVITEWORKER_URL.'access_token='.$this->access_token;
        $result = $this->http_post(self::API_BASE_URL_PREFIX.self::CS_KF_ACCOUNT_INVITEWORKER_URL.'access_token='.$this->access_token,self::json_encode($data));
        
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 查询设备审核信息
     * @author: 凌翔 <553299576@qq.com>
     * @DateTime 2016-06-01T15:54:30+0800
     * * +-------------------------------------------------------------------------------------------------------------
     * ③当需要根据批次ID 查询时: $data = array(
     *                               "apply_id" => 1231
     *                           );
     * apply_id:批次ID
     * +----------------------------------------------------------------------------------------------------------------
     * @param    [type]                   $data [description]
     * @return   [type]                         [description]
     */
    public function searchShakeAroundDeviceApplyStatus($data){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_post(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_DEVICE_APPLY_STATUS . 'access_token=' . $this->access_token, self::json_encode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }
   
    /**
     * 门店类目表
     * 
     */
    public function getWxCategory() {
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_get(self::API_BASE_URL_PREFIX . '/cgi-bin/poi/getwxcategory?' . 'access_token=' . $this->access_token);
        var_dump(self::API_BASE_URL_PREFIX . '/cgi-bin/poi/getwxcategory?' . 'access_token=' . $this->access_token);exit;
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 创建门店
     * @author: 凌翔 <553299576@qq.com>
     * @DateTime 2016-05-28T19:33:59+0800
     * @param    [array]                   $data [拼接后的数组]
     */
    public function addPoi($data){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_post(self::API_BASE_URL_PREFIX .self::ADD_POI. 'access_token=' . $this->access_token, self::json_encode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return $json;
            }
            return $json;
        }
        return false;
    }

    /**
     * 修改门店
     * @author: 凌翔 <553299576@qq.com>
     * @DateTime 2016-05-28T19:33:59+0800
     * @param    [array]                   $data [拼接后的数组]
     */
    public function updatePoi($data){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_post(self::API_BASE_URL_PREFIX .self::UPDATE_POI. 'access_token=' . $this->access_token, self::json_encode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return $json;
            }
            return $json;
        }
        return false;
    }

    /**
     * 删除门店
     * @author: 凌翔 <553299576@qq.com>
     * @DateTime 2016-05-28T19:33:59+0800
     * @param    [array]                   $data [拼接后的数组]
     */
    public function delPoi($data){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_post(self::API_BASE_URL_PREFIX .self::DEL_POI. 'access_token=' . $this->access_token, self::json_encode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return $json;
            }
            return $json;
        }
        return false;
    }

    ////////////////发红包//////////////
    //红包预下单接口
    /**
     * 随机生成16位字符串
     * @return string 生成的字符串
     */
    public function getRandomStr()
    {

        $str = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }

    /**
     * 红包预下单
     * @author: 凌翔 <553299576@qq.com>
     * @DateTime 2016-06-04T14:34:03+0800
     * @param    [type]                   $data   [拼凑数组]
     * @param    [type]                   $cert   [apiclient_cert]
     * @param    [type]                   $key    [apiclient_key]
     * @param    [type]                   $rootca [rootca]
     * @return   [type]                           [description]
     *      * * +-------------------------------------------------------------------------------------------------------------
     * ③当需要根据批次ID 查询时: 
     * <xml>     
     *<sign><![CDATA[E1EE61A91C8E90F299DE6AE075D60A2D]]></sign>     
     *<mch_billno><![CDATA[0010010404201411170000046545]]></mch_billno>     
     *<mch_id><![CDATA[10000097]]></mch_id>     
     *<wxappid><![CDATA[wxcbda96de0b165486]]></wxappid>     
     *<send_name><![CDATA[send_name]]></send_name>     
     *<hb_type><![CDATA[NORMAL]]></hb_type>     
     *<auth_mchid><![CDATA[10000098]]></auth_mchid>     
     *<auth_appid><![CDATA[wx7777777]]></auth_appid>     
     *<total_amount><![CDATA[200]]></total_amount>     
     *<amt_type><![CDATA[ALL_RAND]]></amt_type>     
     *<total_num><![CDATA[3]]></total_num>     
     *<wishing><![CDATA[恭喜发财 ]]></wishing>     
     *<act_name><![CDATA[ 新年红包 ]]></act_name>     
     *<remark><![CDATA[新年红包 ]]></remark>     
     *<risk_cntl><![CDATA[NORMAL]]></risk_cntl>     
     *<nonce_str><![CDATA[50780e0cca98c8c8e814883e5caa672e]]></nonce_str>
     *</xml> 
     * apply_id:批次ID
     * +----------------------------------------------------------------------------------------------------------------
     */
    public function preOrderLuckyMoney($data,$mch_key,$cert,$key,$rootca){
        $getRandomStr = $this->getRandomStr().$this->getRandomStr();
        $data['nonce_str'] = $getRandomStr;
        $send['sign'] = strtoupper($this->getSignatureLuckyMoney($data,'md5',$mch_key));
        $send = array_merge($send,$data);
        $result = $this->curl_post_ssl(self::API_LUCKY_MONEY_URL_PREFIX .self::ADD_PRE_LUCKY_MONEY, '<xml>'.self::data_to_xml($send).'</xml>',$cert,$key,$rootca);
        $responseObj = (array)simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->log($responseObj);
        if ($responseObj) {
            return $responseObj;
        }
        return false;
    }

    /**
     * 创建红包活动
     * @author: 凌翔 <553299576@qq.com>
     * @DateTime 2016-06-04T15:15:56+0800
     * @param    [type]                   $data         [拼接数据]
     * @param    [type]                   $use_template [是否使用模板]
     * @param    [type]                   $LOGO_URL     [使用模板后的logo_url]
     * @return   [type]                                 [description]
     */
    public function addLotteryInfo($data,$use_template,$LOGO_URL=null){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $logo = '';
        if (isset($LOGO_URL)) {
            $logo .= '&logo_url='.$LOGO_URL;
        }
        $result = $this->http_post(self::API_BASE_URL_PREFIX . self::CREATE_LUCKY_MONEY . 'access_token=' . $this->access_token.'&use_template='.$use_template.$logo, self::json_encode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 录入红包信息
     * @author: 凌翔 <553299576@qq.com>
     * @DateTime 2016-06-04T18:08:01+0800
     * @param    [type]                   $data [description]
     */
    public function setPrizeBucket($data){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_post(self::API_BASE_URL_PREFIX . self::SET_PRIZE_BUCKET . 'access_token=' . $this->access_token, self::json_encode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    public function setLotterySwitch($lottery_id,$onoff){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_get(self::API_BASE_URL_PREFIX . self::SET_LOTTERY_SWITCH . 'access_token=' . $this->access_token.'&lottery_id='.$lottery_id.'&onoff='.$onoff);
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }
    /**
     * 红包预下单ssl发送证书方法
     * @author: 凌翔 <553299576@qq.com>
     * @DateTime 2016-06-04T15:16:49+0800
     * @param    [type]                   $url     [请求地址]
     * @param    [type]                   $vars    [发送的xml数据]
     * @param    [type]                   $cert    [证书]
     * @param    [type]                   $key     [密钥]
     * @param    [type]                   $rootca  [CA证书]
     * @param    integer                  $second  [发送时间]
     * @param    array                    $aHeader [description]
     * @return   [type]                            [description]
     */
    function curl_post_ssl($url,$vars,$cert,$key,$rootca,$second=30,$aHeader=array())
    {
        $this->_CI =& get_instance();
        $this->_CI->load->config('cert_path',true);
        $cert_dir=$this->_CI->config->item('cert_dir','cert_path');
        $cert_path=$this->_CI->config->item('cert_path','cert_path');
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);  //超时时间
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); //是否要求返回数据
        //这里设置代理，如果有的话
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);  //是否检测服务器的证书是否由正规浏览器认证过的授权CA颁发的
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);  //是否检测服务器的域名与证书上的是否一致
        $cert_url = __ROOT_PATH__.$cert_dir.$cert;
        $key_url = __ROOT_PATH__.$cert_dir.$key;
        $rootca_url = __ROOT_PATH__.$cert_dir.$rootca;
        if (!file_exists($cert_path.$cert_url)) {
            $file_path = __ROOT_PATH__.$cert_dir.$cert;
            //var_dump($file_path);exit;
            $download = $this->__getCert($cert_path.$cert,$file_path);
            if (!$download) {
                throw new LibrariesErrorException('cert证书下载失败','CERT_DOWNLOAD_FIAL');
            }
        }
        if (!file_exists($cert_path.$key_url)) {
            $file_path = __ROOT_PATH__.$cert_dir.$key;
            $download = $this->__getCert($cert_path.$key,$file_path);
            if (!$download) {
                throw new LibrariesErrorException('key证书下载失败','KEY_DOWNLOAD_FIAL');
            }
        }
        if (!file_exists($cert_path.$rootca_url)) {
            $file_path = __ROOT_PATH__.$cert_dir.$rootca;
            $download = $this->__getCert($cert_path.$rootca,$file_path);
            if (!$download) {
                throw new LibrariesErrorException('CA证书下载失败','CA_DOWNLOAD_FIAL');
            }
        }
        //cert 与 key 分别属于两个.pem文件
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');     //证书类型，"PEM" (default), "DER", and"ENG"
        curl_setopt($ch,CURLOPT_SSLCERT,$cert_url); //证书存放路径
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');     //证书类型，"PEM" (default), "DER", and"ENG"
        curl_setopt($ch,CURLOPT_SSLKEY,$key_url);  //私钥存放路径
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');     //证书类型，"PEM" (default), "DER", and"ENG"
        curl_setopt($ch,CURLOPT_CAINFO,$rootca_url); 

     
        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }
     
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
        $data = curl_exec($ch);
        if($data){
            curl_close($ch);
            return $data;
        }
        else { 
            $error = curl_errno($ch);
            curl_close($ch);
            return false;
        }
    }
    
    /**
     * 下载证书
     * @author: 凌翔 <553299576@qq.com>
     * @DateTime 2016-06-16T15:10:57+0800
     * @param    [type]                   $file_url     [description]
     * @param    [type]                   &$avatar_path [description]
     * @param    [type]                   $dirpath      [description]
     * @return   [type]                                 [description]
     */
/*     private function __getCert($file_url,$path)
    {
        $this->_CI =& get_instance();
        $this->_CI->load->helper('common');
        $file_content = http_get($file_url);
        if ($file_content) {
            $r = file_put_contents($path, $file_content);
            unset($file_content);
            return $r;
        }
        return false;
    } */
    
    private function __getCert($file_url,$path)
    {
    	$this->_CI =& get_instance();
    	$this->_CI->load->helper('common');
    	$file_content = http_get($file_url);
    	$url=explode('/', $path);
    	unset($url[count($url)]);
    	$sspath=implode('/', $url);
    	if ($this->dirExists(dirname($sspath))) {
    		if ($file_content) {
    			$r = file_put_contents($path, $file_content);
    			unset($file_content);
    			return $r;
    		}

    	}
    	return false;
    }

    private function dirExists($path) {
    	$f = true;
    	if (file_exists($path) == false) {//创建图片目录
    		if (mkdir($path, 0777, true) == false)
    			$f = false;
    		else if (chmod($path, 0777) == false)
    			$f = false;
    	}
    
    	return $f;
    }
    
    /**
     * 红包查询接口
     * @param  $lottery_id 红包抽奖id，来自addlotteryinfo返回的lottery_id
     * @return boolean|mixed
     */
    public function queryLottery($lottery_id){
    	if (!$this->access_token && !$this->checkAuth()) return false;
    	$result = $this->http_get(self::API_BASE_URL_PREFIX . self::QUERY_LOTTERY . 'access_token=' . $this->access_token.'&lottery_id='.$lottery_id);
    	$this->log($result);
    	if ($result) {
    		$json = json_decode($result, true);
    		if (!$json || !empty($json['errcode'])) {
    			$this->errCode = $json['errcode'];
    			$this->errMsg  = $json['errmsg'];
    			return false;
    		}
    		return $json;
    	}
    	return false;
    }
    
    
    
    /**
     * 获取签名
     * @param array $arrdata 签名数组
     * @param string $method 签名方法
     * @return boolean|string 签名值
     */
    public function getSignatureLuckyMoney($arrdata,$method="sha1",$mch_key) {
    	if (!function_exists($method)) return false;
    	ksort($arrdata);
    	$paramstring = "";
    	foreach($arrdata as $key => $value)
    	{
    		if(strlen($paramstring) == 0)
    			$paramstring .= $key . "=" . $value;
    		else
    			$paramstring .= "&" . $key . "=" . $value;
    	}
    	$paramstring.='&key='.$mch_key;
    	$Sign = $method($paramstring);
    	return $Sign;
    }

    /**
     * 设置自助核销接口
     * @author: 凌翔 <553299576@qq.com>
     * @DateTime 2016-06-11T23:07:44+0800
     */
    public function selfConsumeCell($data){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_post(self::API_BASE_URL_PREFIX . self::SELF_CONSUME_CELL . 'access_token=' . $this->access_token, self::json_encode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 卡劵-设置买单接口
     * @author: 凌翔 <553299576@qq.com>
     * @DateTime 2016-06-12T11:14:15+0800
     */
    public function payCell($data){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_post(self::API_BASE_URL_PREFIX . self::PAY_CELL . 'access_token=' . $this->access_token, self::json_encode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                var_dump($result);
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 卡劵-创建卡劵货架接口
     * @author: 凌翔 <553299576@qq.com>
     * @DateTime 2016-06-12T15:24:43+0800
     * @param    [type]                   $data [description]
     * @return   [type]                         [description]
     */
    public function landingpage($data){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_post(self::API_BASE_URL_PREFIX . self::LANDINGPAGE_CREATE . 'access_token=' . $this->access_token, self::json_encode($data));
        $this->log($result);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                var_dump($result);
                return false;
            }
            return $json;
        }
        return false;
    }
    
    /**
     * 申请开通摇一摇功能
     * @param  $data
     * @author 伟健
     */
    public function shakeAroundRegister($data){
    	if (!$this->access_token && !$this->checkAuth()) return false;
    	$result = $this->http_post(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_ACCOUNT_REGISTER . 'access_token=' . $this->access_token, self::json_encode($data));
    	$this->log($result);
    	if ($result) {
    		$json = json_decode($result, true);
    		if (!$json || !empty($json['errcode'])) {
    			$this->errCode = $json['errcode'];
    			$this->errMsg  = $json['errmsg'];
    			return false;
    		}
    		return $json;
    	}
    	return false;
    }
    
    /**
     * 查询审核状态
     * @author 伟健
     * @edit 凌翔
     */
    public function shakeAroundAuditstatus(){
    	if (!$this->access_token && !$this->checkAuth()) return false;
    	$result = $this->http_get(self::API_BASE_URL_PREFIX . self::SHAKEAROUND_ACCOUNT_AUDITSTATUS . 'access_token=' . $this->access_token);
    	$this->log($result);
    	if ($result) {
    		$json = json_decode($result, true);
    		if (!$json || !empty($json['errcode'])) {
    			$this->errCode = $json['errcode'];
    			$this->errMsg  = $json['errmsg'];
    			return false;
    		}
    		return $json;
    	}
    	return false;
    }
    
    
    /**
     * 获取公众号已创建的标签
     * @author 元翔
     * @return boolean|array
     */
    public function getTags(){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_get(self::API_URL_PREFIX.self::TAGS_GET_URL.'access_token='.$this->access_token);
        if ($result)
        {
            $json = json_decode($result,true);
            if (isset($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取用户所在标签
     * @author 元翔
     * @param string $openid
     * @return boolean|int 成功则返回用户分组id
     */
    public function getUserTags($openid){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $data = array(
                'openid'=>$openid
        );
        $result = $this->http_post(self::API_URL_PREFIX.self::USER_TAGS_URL.'access_token='.$this->access_token,self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            } else
                if (isset($json['tagid_list'])) return $json['tagid_list'];
        }
        return false;
    }
    
    /**
     * 新增标签
     * @param string $name 分组名称
     * @author 伟健
     * @return boolean|array
     */
    public function createTags($name){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $data = array(
                'tag'=>array('name'=>$name)
        );
        $result = $this->http_post(self::API_URL_PREFIX.self::TAGS_CREATE_URL.'access_token='.$this->access_token,self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 修改标签
     * @param string $name 分组名称
     * @author 元翔
     * @return boolean|array
     */
    public function updateTags($tagid,$name){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $data = array(
                'tag'=>array('id'=>$tagid,'name'=>$name)
        );
        $result = $this->http_post(self::API_URL_PREFIX.self::TAGS_UPDATE_URL.'access_token='.$this->access_token,self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return $json;
    }

    /**
     * 删除标签
     * @param string $name 分组名称
     * @author 元翔
     * @return boolean|array
     */
    public function deleteTags($tagid){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $data = array(
                'tag'=>array('id'=>$tagid)
        );
        $result = $this->http_post(self::API_URL_PREFIX.self::TAGS_DELETE_URL.'access_token='.$this->access_token,self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取标签下粉丝列表
     * @param int $tagid 标签id
     * @param string $openid 第一个拉取的OPENID，不填默认从头开始拉取
     * @author 元翔
     * @return boolean|array
     */
    public function getTagUserList($tagid,$openid=''){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $data = array(
                'tagid'=>$tagid,
                'next_openid'=>$openid
        );
        $result = $this->http_post(self::API_URL_PREFIX.self::TAGS_USER_GET_URL.'access_token='.$this->access_token,self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }else
                if (isset($json)) return $json;
        }
        return false;
    }

    /**
     * 批量为用户打标签(标签功能目前支持公众号为用户打上最多三个标签)
     * @param array $data
     * 数组结构:
     *  array(
     *      'openid_list' =>
     *        array (
     *        0 => 'ocYxcuAEy30bX0NXmGn4ypqx3tI0',
     *        1 => 'ocYxcuBt0mRugKZ7tGAHPnUaOW7Y',
     *       ),
     *       'tagid' => '134',
     *  )
     * @author 元翔
     * @return boolean|array
     */
    public function batchtaggingTagsForMembers($data){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_post(self::API_URL_PREFIX.self::TAGS_MEMBERS_BATCHTAGGING_URL.'access_token='.$this->access_token,self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 批量为用户取消标签
     * @param array $data
     * 数组结构:
     *  array(
     *      'openid_list' =>
     *        array (
     *        0 => 'ocYxcuAEy30bX0NXmGn4ypqx3tI0',
     *        1 => 'ocYxcuBt0mRugKZ7tGAHPnUaOW7Y',
     *       ),
     *       'tagid' => '134',
     *  )
     * @author 元翔
     * @return boolean|array
     */
    public function batchuntaggingTagsForMembers($data){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_post(self::API_URL_PREFIX.self::TAGS_MEMBERS_BATCHUNTAGGING_URL.'access_token='.$this->access_token,self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    public function getArticleSummary($data){
        if (!$this->access_token && !$this->checkAuth()) return false;
        $result = $this->http_post(self::API_URL_MASS_DATACUBE.self::MASS_GET_ARTICLE_SUMMARY.'access_token='.$this->access_token,self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }
}

/* End of file Wechat.php */
/* Location: ./application/libraries/Wechat.php */
