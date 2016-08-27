<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 授权登录
 */
class Login extends Wx_Base_Api_1_0_Controller{
    
    public function islogin(){
        $rule=array(
            'callback'=>array(null,null,true)
        );
        $this->verify->verify($rule, $this->input->get());
        $get_callback=$this->input->get('callback');
   
        //解码后重新验证url
        $get_callback=urldecode($get_callback);
        $rule=array(
            'callback'=>array(null,null,true)
        );
        $this->verify->verify($rule, ['callback'=>$get_callback]);
        $this->_auth('uesr',__CLASS__,true);
        //log_message('error', $_SESSION['user_info']);
        if(!empty($_SESSION['user_info']['user_id'])){
            $is_login=true;
            $redirect='';
        }else{
            $this->load->helper('common');
            $session_id=urlencode(encrypt(session_id(), 'hanzi'));
            $is_login=false;
            $redirect=base_url().'v1_0/wechat/login/auth?key='.$session_id.'&callback='.urlencode($get_callback);
        }
        //log_message('error', $_SESSION['user_info']);
        $this->r(['data'=>['is_login'=>$is_login,'redirect'=>$redirect]]);
    }
    

    /**
     * 微信授权
     * @author: 亮 <chenjialiang@han-zi.cn>
     */
    public function get_auth(){
        $this->load->helper('common');
        //创建session
        $this->_auth('user',__class__,true,decrypt($this->input->get('key'),'hanzi'));

        /**
         * callback_path : 回调地址的path,为contrller的路径
         * is_oauth_user_info : 是否通过授权获取用户信息
         * check_for : 通过openid或unionid
         * check_user_model : 检查用户是否存在的model类
         * check_user_function : 检查用户是否存在的model类里面的function,存在必须返回用户信息数组,且包含uid字段
         * oauth_get_user_silent_function : 授权获取到用户openid的回调函数
         * oauth_get_user_info_function : 创建新用户回调函数
         * create_user_function : 授权成功后,创建seesion回调函数
         * type : 配置来源
         */
        $params=array(
            'callback_path' => 'v1_0/wechat/login/auth',       //回跳地址的path,为contrller的路径
            'is_oauth_user_info' => true,                       //是否通过授权获取用户信息
            'check_for' => 'openid',                            //通过openid或unionid
            'check_user_model' => 'v1_0/common/Get_user_base_model',    //检查用户是否存在的model类
            'check_user_function' => 'getUserWechat',           //检查用户是否存在的model类里面的function
            'create_user_function' => array($this,'oauth_create_user'),  //创建新用户
            'oauth_get_user_silent_function' => array($this,'oauth_get_user_openid'),   //授权获取到用户openid的回调函数
            'oauth_get_user_info_function' => array($this,'oauth_get_user_info'),    //授权获取到用户信息的回调函数
        );

        $params['type']=empty($this->input->get('type'))?'mp':$this->input->get('type');

        $this->load->library('wechat/WechatOauth',$params,'WechatOauth');
        $this->WechatOauth->run();
    }

    /**
     * 创建新用户
     * @author jieyang
     * //如果是需要绑定其他信息才保存用户 直接返回   =》真
     */
    public function oauth_create_user($user_info){
        $this->load->model('v1_0/wechat/Manage_user_base_model','manage_user_base_model');
        //添加用户
        $save=array(
            'openid'=>$user_info['openid'],
            'user_name'=>$user_info['nickname'],
            'user_img' =>$user_info['headimgurl'],
            'add_time'=>$this->time()
        );
        $uid=$this->manage_user_base_model->add($save);

        return $uid;
    }

    /**
     * 登陆成功把基本信息存入session中 (相当于老用户再次登录在数据表查到相应的信息)
     * @author jieyang
     */
    public function oauth_get_user_openid($user_info){
        $_SESSION['user_info'] = array(
            'user_id' => $user_info['uid'],
            'openid' => $user_info['openid'],
            'user_name' => $user_info['user_name'],
            'user_img' => $user_info['user_img'],
        );
        return true;
    }

    /**
     * 用户信息存储至session中(成功走完自定义逻辑后写入)
     * @author jieyang
     * 如果涉及其他的资料绑定 则做相应的跳转 和 逻辑
     */
    public function oauth_get_user_info($user_id,$user_info){
        $_SESSION['user_info']=array(
            'user_id' => $user_id,
            'openid'=>$user_info['openid'],
            'user_name'=>$user_info['nickname'],
            'user_img' =>$user_info['headimgurl'],
        );
        return true;
    }
    
}?>