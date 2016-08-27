<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 个人中心
 */
class User extends Wx_Api_1_0_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->_auth('session',__CLASS__,TRUE);
    }
    
    /**
     * 查询用户个人信息
     * @author 柯志杰
     */
    public function get_index(){
        $uid=$this->user['user_id'];
        $this->load->model('v1_0/common/Get_user_base_model','get_user_base_model');
        $this->r['data']=$this->get_user_base_model->getWeChatOne($uid);
        $this->state=true;
        $this->r();
    }

    /**
     * 修改用户基本信息
     * @author jieyang
     */
    public function put_index(){
        $data=$this->input->post(); 
        $rule=array(
            'user_name'=>array(null,null,false),
            'user_img'=>array(null,null,false),
            'sex'=>array('in',array('0,1,2'),false),
            'age'=>array(null,null,false),
            'province'=>array(null,null,false),
            'city'=>array(null,null,false),
            'area'=>array(null,null,false)
        );
        $this->verify->verify($rule,$data);
        foreach($rule as $k=>$v){
            isset($data[$k])?$save[$k]=$data[$k]:'';
        }
        $uid=$this->user['user_id'];
        $save['update_time']=$this->time(); 
        $this->load->model('v1_0/wechat/Manage_user_base_model','manage_user_base_model');
        $this->manage_user_base_model->edit($uid,$save);
        $this->state=true;
        $this->r();
    }

    /**
     * 用户提现
     * @author jieyang
     */
    public function post_getcash(){
        $data=$this->input->post();
        $rule=array(
            'amount'=>array('egNum',null,true)
        );
        $this->verify->verify($rule,$data);
        $amount=$data['amount'];
        if($amount<100){  //提现最低额度 1元（100分）
            throw new RJsonErrorException('提现金额不能小于1元','GETCASH_LESS_LIMIT_ERR');
        }
        $uid=$this->user['user_id'];
        //查看用户的信息
        $this->load->model('v1_0/common/Get_user_base_model','get_user_base_model');
        $user_info=$this->get_user_base_model->getOne($uid);
        //判断是否设置密码
       /*  if(empty($user_info['apply_password'])){
            throw new RJsonErrorException('你还未设置交易密码','GET_USER_PASSWORD_ERR');
        }
        //校验密码
        $this->load->helper('common');
        $apply_password=encrypt_password($data['apply_password'],$user_info['user_salt']);
        if($apply_password!==$user_info['apply_password']){
            throw new RJsonErrorException('账户密码输入有误','USER_PASSWORD_ERR');
        } */
        //判断用户余额
        if($user_info['user_balance']<100){
            throw new RJsonErrorException('账户余额不足','BALANCE_LESS_LIMIT_ERR');
        }
        if($user_info['user_balance']<$amount){
            throw new RJsonErrorException('提现金额大于账户余额','GETCASH_BIG_BALANCE_ERR');
        }
        $openid=$user_info['openid'];
        //先操作数据库,成功之后再调用微信提现接口
        $this->load->model('v1_0/wechat/Manage_user_base_model','manage_user_base_model');
        $this->load->model('v1_0/wechat/Manage_log_user_capital_model','manage_log_user_capital_model');
        $this->load->model('v1_0/wechat/Manage_user_getcash_model','manage_user_getcash_model');
        $time=$this->time();
        $title_time=date('Y-m-d H:i:s',$time);
        //记录数据库
        $this->manage_user_base_model->model_db->trans_begin();
        $this->manage_log_user_capital_model->model_db->trans_begin();
        $this->manage_user_getcash_model->model_db->trans_begin();
        try{
            //调用微信接口
            $this->load->library('wechatpay/Wechatpay','','paylib');
            $amount_title=($amount/100).'元';
            $out_trade_no=time().rand(100000,999999);
            $desc='企业付款';
            $result=$this->paylib->enterprise_pay($openid,$amount,$out_trade_no,$desc); //提现不涉及回调
            if($result['return_code']!="SUCCESS"||$result['result_code']!="SUCCESS"){
                throw new RJsonErrorException('提现失败','GETCASH_FAIL');
            }
            //添加用户提现记录
            $getcash_data=array(
                'uid'=>$uid,
                'amount'=>$amount,
                'partner_trade_no'=>$out_trade_no, //商户订单号
                'payment_no'=>$result['payment_no'], //微信订单号
                'state'=>1,
                'add_time'=>$time
            );
            $this->manage_user_getcash_model->add($getcash_data);
            //修改用户余额
            $this->manage_user_base_model->reduceRemainder($uid,$amount);
            //添加资金表动记录
            $data_log_capital=array(
                'uid'=>$uid,
                'amount'=>$amount,
                'type'=>2,
                'content'=>'提现',
                'add_time'=>$time
            );
            $this->manage_log_user_capital_model->add($data_log_capital);   
        }catch(Exception $e){
            //回滚事务
            $this->manage_user_base_model->model_db->trans_rollback();
            $this->manage_user_getcash_model->model_db->trans_rollback();
            $this->manage_log_user_capital_model->model_db->trans_rollback();
            //添加用户提现失败记录
            if($result['return_code']=="FAIL"){
                $err_msg=$result['return_msg'];
            }else{
                $err_msg=$result['err_code_des'];
            }
            $getcash_data=array(
                'uid'=>$uid,
                'amount'=>$amount,
                'partner_trade_no'=>$out_trade_no, //商户订单号
                'error_msg'=>$err_msg, //提现失败原因
                'state'=>-1,
                'add_time'=>$time
            );
            $this->manage_user_getcash_model->add($getcash_data);
            //记录错误日志
            $this->load->helper('common');
            log_msg(date('H:i:s',time()).' 用户申请提现失败,提现金额:'.$amount_title.'元,用户id:'.$user_info['uid'],'wechat');
            throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
        }
        //提交事务
        $this->manage_user_base_model->model_db->trans_commit();
        $this->manage_user_getcash_model->model_db->trans_commit();
        $this->manage_log_user_capital_model->model_db->trans_commit();
        $this->state=true;
        $this->r();
    }
    
    /**
     * 查询用户红包记录
     * @author jieyang
     */
    public function get_red_list(){
        $this->load->library('page');
        $this->load->model('v1_0/common/Get_user_red_model','get_user_red_model');
        $data['uid']=$this->user['user_id'];
        $list=$this->get_one_page_data($this->page,$this->get_user_red_model,'getList','getListLength',array($data));
        $this->r(array('lists'=>$list,'page'=>$this->page->getPage()));
        $this->state=true;
        $this->r();  
    }
    
    /**
     * 查询用户积分记录
     * @author jieyang
     */
    public function get_integral_list(){
        $this->load->library('page');
        $this->load->model('v1_0/common/Get_user_integral_model','get_user_integral_model');
        $data['uid']=$this->user['user_id'];
        $list=$this->get_one_page_data($this->page,$this->get_user_integral_model,'getList','getListLength',array($data));
        $this->r(array('lists'=>$list,'page'=>$this->page->getPage()));
        $this->state=true;
        $this->r();
    }
    
    /**
     * 查询用户提现记录
     * @author jieyang
     */
    public function get_getcash_list(){
        $this->load->library('page');
        $this->load->model('v1_0/common/Get_user_getcash_model','get_user_getcash_model');
        $data['uid']=$this->user['user_id'];
        $list=$this->get_one_page_data($this->page,$this->get_user_gecash_model,'getList','getListLength',array($data));
        $this->r(array('lists'=>$list,'page'=>$this->page->getPage()));
        $this->state=true;
        $this->r();
    }
    
    /**
     * 查询用户资金变动记录
     * @author jieyang
     */
    public function get_capital_list(){
        $this->load->library('page');
        $this->load->model('v1_0/common/Get_log_user_capital_model','get_log_user_capital_model');
        $data['uid']=$this->user['user_id'];
        $list=$this->get_one_page_data($this->page,$this->get_log_user_capital_model,'getList','getListLength',array($data));
        $this->r(array('lists'=>$list,'page'=>$this->page->getPage()));
        $this->state=true;
        $this->r();
    }
    
    
    
}
?>

