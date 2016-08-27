<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 个人中心
 * 柯志杰
 */
class Register extends Wx_Base_Api_1_0_Controller{
    public function __construct(){
        parent::__construct();
        $this->_auth('session',__CLASS__,TRUE);
    }
    /**
     * 绑定手机号
     * 不存在则创建新用户
     * 存在手机账号
     * @author 柯志杰
     */
     public function post_bind_phone(){
        $this->load->model('v1_0/common/Get_msg_log_model','get_msg_log_model');
        $this->load->model('v1_0/common/Get_user_base_model','get_user_base_model');
        $this->load->model('v1_0/wechat/Manage_user_base_model','manage_user_base_model');
        $this->load->model('v1_0/wechat/Manage_msg_log_model','manage_msg_log_model');
        $data=$this->input->post();
        $rule=array(
            'mobile_phone'=>array('mobile',null,true),
            'codemsg'=>array(null,null,true)
        );
        $this->verify->verify($rule,$data);
        foreach($rule as $k=>$v){
            isset($data[$k])?$save[$k]=$data[$k]:'';
        }
        //获取验证码
        $check_code=$this->get_msg_log_model->getCode(4,$save['mobile_phone']);
        //校验验证码
        if($save['codemsg']!=$check_code['codemsg']){
            throw new RJsonErrorException('验证码输入不正确','CODEMS_MATCH_ERR');
        }
        $openid=$_SESSION['sign_info']['openid'];
        //var_dump($openid);exit;
        try{
            //用户已经存在
            $user_info=$this->get_user_base_model->getUserByOpenidSign($openid);
            $mobile_phone['update_time']=$this->time();
            $mobile_phone['mobile_phone']=$save['mobile_phone'];
            $mobile_phone['openid'] = $_SESSION['sign_info']['openid'];
            $mobile_phone['user_name'] = $_SESSION['sign_info']['user_name'];
            $mobile_phone['user_img'] = $_SESSION['sign_info']['user_img'];
            $this->manage_user_base_model->edit($user_info['user_id'],$mobile_phone);
        }catch(ModelErrorException $e){ 
            //获取sessison中保存的值
            $user_data['openid']=$openid;
            try {
                //后台已添加用户信息
                $add_info=$this->get_user_base_model->getUserByPhone($save['mobile_phone']);
                //追加微信信息
                if(empty($add_info['user_img'])){ //批量导入的用户
                    $user_data['user_img']=isset($this->user['user_img'])?($this->user['user_img']):'';
                }
                $user_data = array();
                $user_data['update_time']=$this->time();
                $user_data['openid'] = $_SESSION['sign_info']['openid'];
                $user_data['user_name'] = $_SESSION['sign_info']['user_name'];
                $user_data['user_img'] = $_SESSION['sign_info']['user_img'];
                $this->manage_user_base_model->edit($add_info['user_id'],$user_data);
                $_SESSION['user_info']['user_id']=$add_info['user_id'];
                $_SESSION['user_info']['openid']=$user_data['openid'];
                $_SESSION['user_info']['user_name']=$user_data['user_name'];
                $_SESSION['user_info']['user_img']=$user_data['user_img'];
            }catch(ModelErrorException $e){
                //全新添加用户信息
                $user_data['user_img']=isset($_SESSION['sign_info']['user_img'])?($_SESSION['sign_info']['user_img']):'';
                $user_data['user_name']=isset($_SESSION['sign_info']['user_name'])?($_SESSION['sign_info']['user_name']):'';
                $user_data['mobile_phone']=$data['mobile_phone'];
                $user_data['add_time']=$this->time();
                $uid=$this->manage_user_base_model->add($user_data);
                if(!empty($uid)) {
                    $_SESSION['user_info']['user_id']=$uid;
                    $_SESSION['user_info']['openid']=$user_data['openid'];
                    $_SESSION['user_info']['user_name']=$user_data['user_name'];
                    $_SESSION['user_info']['user_img']=$user_data['user_img'];
                }
            }
        }
        $check_code['is_on']=0;
        $check_code['update_time']=$this->time(); 
        $this->manage_msg_log_model->edit($check_code['id'],$check_code);
        $this->state=true;
        $this->r();
    }

    /**
     * 生成手机验证码
     * @author: 志杰
     */
    public function post_mobile_verify(){
        $data=$this->input->post();
        $rule=array(
            'mobile_phone'=>array('mobile',null,true),
        );
        $this->verify->verify($rule,$data);
        $mobile = $data['mobile_phone'];     
        //判断短信获取频率（60秒）
        $time = $this->time()-60;
        $this->load->model('v1_0/common/Get_msg_log_model','get_msg_log_model');
        $codemsg=$this->get_msg_log_model->getCodeInfo(4,$mobile,null,$time);
        if(count($codemsg)>0){
            throw new RJsonErrorException('60秒内只能获取一次验证码!','PHONE_CODE_EXCEED_ERR');
        }
        //判断单次短信获取（每天操作只能发3次验证码）
        $time_limit=$this->time()-60*30;
        $msg_code_num=$this->get_msg_log_model->getCodeInfo(4,$mobile,3,$time_limit);
        if(count($msg_code_num)>=3){
            throw new RJsonErrorException('单次操作获取次数达到上限!','PHONE_CODE_EXCEED_ERR');
        }
        $this->load->model('v1_0/wechat/Manage_msg_log_model','manage_msg_log_model');
        $data_add=array(
            'mobile_phone'=>$mobile,
            'types'=>4,
            'remark'=>'微信登录绑定手机',
            'add_time'=>$this->time(),
            'codemsg'=>rand(1111,9999)
        );
        $data_return['verify_code']=$this->manage_msg_log_model->add($data_add);  
        //调用短信内容配置文件
        $this->config->load('sms',true);
        $content='您的培乐方新用户手机注册验证码是：'.sprintf($data_add['codemsg']);   
        try{
            //调用短信接口发送短信
            $this->load->helper('sms');
            send_msg($mobile,$content);
        }catch(ModelErrorException $e){
            throw new RJsonErrorException ($e->getMessage(),$e->getErrorId(),$e->getCode());
        }
        $this->state=true;
        $this->r();
    }
    
}
?>

