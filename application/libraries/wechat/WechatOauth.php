<?php
/**
 * 微信授权
 */

require_once 'Wechat.php';

class WechatOauth{
    protected $_CI;
    public $callback;

    /**
     * 注释说明
     * @param $params
     *
     * callback_path : 回调地址的path,为contrller的路径
     * is_oauth_user_info : 是否通过授权获取用户信息
     * check_for : 通过openid或unionid
     * check_user_model : 检查用户是否存在的model类
     * check_user_function : 检查用户是否存在的model类里面的function,存在必须返回用户信息数组,且包含uid字段
     * oauth_get_user_silent_function : 授权获取到用户openid的回调函数
     * oauth_get_user_info_function : 创建新用户回调函数
     * create_user_function : 授权成功后,创建seesion回调函数
     * type : 配置来源
     *
     * @param $_GET['callback']  $_GET['key']
     *
     * @author: 亮 <chenjialiang@han-zi.cn>
     */
    public function __construct($params){
        $this->_CI =& get_instance();
        $this->_CI->load->helper('common');

        $rule=array(
            'callback'=>array('url',null,false),
            'key'=>array(null,null,false)
        );
        $this->_CI->verify->verify($rule, $this->_CI->input->get());
        $get_callback=$this->_CI->input->get('callback');
        $get_key=$this->_CI->input->get('key');
        $query=array();

        if(!empty($get_callback)){
            $query['callback']=$this->_CI->input->get('callback');
        }

        if(!empty($get_key)){
            $query['key']=$this->_CI->input->get('key');
        }

        $this->params = $params;

        $query['type']=$params['type'];

        $callback=base_url().$params['callback_path'].'?'.http_build_query($query);
        $this->callback = $callback;

        //实例化微信类
        $weObj=new Wechat($params['type']);
        $this->weObj=$weObj;
    }

    public function run(){
        if(empty($_GET['code'])&&empty($_GET['state'])){    //第一步
            $this->first_step();
        }elseif(!empty($_GET['code'])&&$_GET['state']=='snsapi_base') { //静默请求获得openid
            $this->silent_oauth();
        }elseif(!empty($_GET['code'])&&$_GET['state']=='snsapi_userinfo'){   //弹出授权获取用户消息
            $this->click_oauth();
        }
    }

    /**
     * 授权登录第一步
     * @author: 亮 <chenjialiang@han-zi.cn>
     */
    public function first_step(){
        $reurl=$this->weObj->getOauthRedirect($this->callback, "snsapi_base","snsapi_base");
        redirect($reurl);
    }

    /**
     * 静默获取授权后逻辑
     * @author: 亮 <chenjialiang@han-zi.cn>
     * @return array  用户给的回调函数返回true,则返回该用户openid,反之则跳转用户点击授权
     */
    public function silent_oauth(){
        $accessToken=$this->weObj->getOauthAccessToken();
        if(!$accessToken || empty($accessToken['openid'])){
            throw new LibrariesErrorException('code错误','CODE_ERROR');
        }
        $term=$accessToken['openid'];
        $user_info=array();
        if($this->params['check_for']==='unionid'){
            $user_info=$this->weObj->getUserInfo($accessToken['openid']);
            $term=$user_info['unionid'];
        }

        $is_user=$this->check_user($term);
        if(!$is_user && $this->params['is_oauth_user_info'] === true){
            $reurl=$this->weObj->getOauthRedirect($this->callback,"snsapi_userinfo","snsapi_userinfo");
            redirect($reurl);
        }
        elseif($is_user){
            $is_user['openid']=$accessToken['openid'];
            $is_user['unionid']=!isset($user_info['unionid'])?'':$user_info['unionid'];
            $result=call_user_func_array($this->params['oauth_get_user_silent_function'],array($is_user));

            if($result){
                redirect($this->_CI->input->get('callback'));
            }
        }

        throw new LibrariesErrorException('授权失败','AUTH_ERROR');
    }

    /**
     * 用户点击授权后逻辑
     * @author: 亮 <chenjialiang@han-zi.cn>
     */
    public function click_oauth(){
        $accessToken=$this->weObj->getOauthAccessToken();
        if(!$accessToken){
            throw new LibrariesErrorException('获取access_token错误','ERROR_ACCESS_TOKEN');
        }

        $user_info=$this->weObj->getOauthUserinfo($accessToken['access_token'],$accessToken['openid']);
        //检查是否存在用户
        $term=$accessToken['openid'];
        if($this->params['check_for']==='unionid'){
            $term=$user_info['unionid'];
        }

        $is_user=$this->check_user($term);
        if(!$is_user){
            //创健新用户
            $add_user=call_user_func_array($this->params['create_user_function'],array($user_info));
            $user_id=$add_user;
        }
        else{
            $user_id=$is_user['uid'];
        }

        $result=call_user_func_array($this->params['oauth_get_user_info_function'],array($user_id,$user_info));

        if($result){
            redirect($this->_CI->input->get('callback'));
        }

        throw new LibrariesErrorException('授权失败','AUTH_ERROR');
    }

    /**
     * 检查是否存在用户
     * @author: 亮 <chenjialiang@han-zi.cn>
     */
    public function check_user($value){
        $this->_CI->load->model($this->params['check_user_model'],'check_user');
        $result=call_user_func_array(array($this->_CI->check_user,$this->params['check_user_function']),array($value));
        return $result;
    }


}
