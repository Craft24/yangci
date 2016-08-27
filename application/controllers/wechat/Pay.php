<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 支付接口
 */
class Pay extends Wx_Api_1_0_Controller{
	public function __construct(){
		parent::__construct();
		$this->_auth('session',__CLASS__,TRUE);
		/* $_SESSION['user_info']=array(
		    'user_id' => 109,
		    'openid' => 'o_Oqut_duUw2IdHeMRzF4M_Sg6ZM',
		    'user_name' => '一米阳光湾',
		    'user_img' => 'http://wx.qlogo.cn/mmopen/Aib1iasJokz2YRSMtYhVyEJ0ZqKzG07dlQQowhzQP8DNucjR9hEcMdV8Ru8wRAyUjhkibMU51cMtxriba3j7BtvwahiaHGDaMEb85/0'
		); */
   	}
   	
	/**
	 * 获取微信支付签名(测试)
	 */
	public function get_wechat(){
		$this->load->library('wechatpay/Wechatpay',array(),'wxpay');#载入微信支付类
		$openid=$this->user['openid']='o_Oqut_duUw2IdHeMRzF4M_Sg6ZM';
		$body='测试';
		$attach=json_encode(array(
		    'bill_id'=>429,
		    'bill_sn'=>2016082012052078
		));
		$out_trade_no=time().rand(100000,999999); //随机字符串
		$total_fee=1;
		$notify_url=base_url('/').'v1_0/notify/bill/wechat_callback';
		$jsApiParameters=$this->wxpay->get_pay_sign($openid,$body,$attach,$out_trade_no,$total_fee,$notify_url);
		$this->r['data']['jsApiParameters'] = json_decode($jsApiParameters,true);
		$this->state = true;
		$this->r();
	}
	
	/**
	 * 获取订单消息,生成签名
	 * @author jieyang
	 */
	public function post_bill_pay(){
	    $data=$this->input->post();
	    $rule=array(
	        'bill_id'=>array('egNum',null,true)
	    );
	    $this->verify->verify($rule,$data);
	    $bill_id=$data['bill_id'];
	    $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
	    log_message('error', '请求微信支付');
	    //获取订单信息
	    $info=$this->get_bill_model->getBillCheck($bill_id);
	    if($info['pay_end_time']<time()){ 
	        throw new RJsonErrorException('订单已过期','BILL_EXPIRED_ERR');
	    }
	    if($info['state']!=1){ //1,未付款
	        throw new RJsonErrorException('订单状态有误','BILL_STATE_ERR');
	    }
	    //全额微信支付
	    $this->load->library('wechatpay/Wechatpay',array(),'wxpay');#载入微信支付类
	    $openid=$this->user['openid'];
	    $body='家点菜';
	    $attach=json_encode(array(
	        'bill_id'=>$info['bill_id'],
	        'bill_num'=>$info['bill_num']
	    ));
	    $out_trade_no=time().rand(100000,999999); //随机字符串
	    $total_fee=$info['need_pay']; //支付金额(以分为单位)
	    $total_fee=1;
	    $notify_url=base_url('/').'v1_0/notify/bill/wechat_callback'; 
	    $jsApiParameters=$this->wxpay->get_pay_sign($openid,$body,$attach,$out_trade_no,$total_fee,$notify_url);    
	    $this->r['data']['jsApiParameters']=json_decode($jsApiParameters,true);
	    $this->r['data']['is_pay']=false;
	    $this->state = true;
	    $this->r();	    
	}

	/**
	 * 获取订单消息,生成签名(旧版本，测试保留)
	 * @author jieyang
	 */
	public function post_bill_pay_old(){
	    $data=$this->input->post();
	    $rule=array(
	        'bill_id'=>array('egNum',null,true),
	        'pay_balance'=>array('in',array(1,2),false), //是否使用余额 1,使用余额2,不使用余额
	        'pay_cash'=>array('in',array(1),false) //货到付款
	    );
	    $this->verify->verify($rule,$data);
	    $bill_id=$data['bill_id'];
	    $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
	    //获取订单信息
	    $info=$this->get_bill_model->getBillCheck($bill_id);
	    if($info['pay_end_time']<time()){
	        throw new RJsonErrorException('订单已过期','BILL_EXPIRED_ERR');
	    }
	    if($info['state']!=1){ //1,未付款
	        throw new RJsonErrorException('订单状态有误','BILL_STATE_ERR');
	    }
	     
	    $this->load->model('v1_0/wechat/Manage_bill_model','manage_bill_model');
	    if(isset($data['pay_cash'])){
	        //使用货到付款
	        $up_bill_cash=array(
	            'pay_mode'=>3, //货到付款
	            'update_time'=>time(),
	            'state'=>2
	        );
	        $this->manage_bill_model->edit($info['bill_id'],$up_bill_cash);
	        $this->r['data']['is_pay']=true;
	        $this->state=true;
	    }
	    $this->load->model('v1_0/wechat/Manage_user_base_model','manage_user_base_model');
	    //判断是否使用余额支付
	    if($data['pay_balance']==1){
	        $this->load->model('v1_0/common/Get_user_base_model','get_user_base_model');
	        $user_info=$this->get_user_base_model->getOne($info['uid']);
	        //判断订单需要支付的金额和余额的值
	        if($user_info['user_balance']>=$info['need_pay']){ //用户的金额足以抵扣订单金额
	            $this->manage_user_base_model->model_db->trans_begin();
	            $this->manage_bill_model->model_db->trans_begin();
	            try{
	                //扣除用户余额
	                $this->manage_user_base_model->reduceBalance($info['uid'],$info['need_pay']);
	                //修改订单状态,追加余额支付金额
	                $up_bill=array(
	                    'pay_balance'=>$info['need_pay'],
	                    'update_time'=>time(),
	                    'pay_mode'=>1, //余额支付
	                    'state'=>2,
	                    'pay_time'=>time()
	                );
	                $this->manage_bill_model->edit($info['bill_id'],$up_bill);
	                //添加用户余额变动记录
	                $content='订单'.$info['bill_num'].'余额支付支出';
	                $this->__handle_log_capital($info['uid'],$info['need_pay'],$content,2);
	            }catch(ModelErrorException $e){
	                $this->manage_user_base_model->model_db->trans_rollback();
	                $this->manage_bill_model->model_db->trans_rollback();
	                throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
	            }
	            $this->manage_user_base_model->model_db->trans_commit();
	            $this->manage_bill_model->model_db->trans_commit();
	            $this->r['data']['jsApiParameters']='';
	            $this->r['data']['is_pay']=true;
	            $this->state=true;
	            $this->r();
	        }else if($user_info['user_balance']>0){ //用户有余额,但不足抵扣订单价格
	            $this->manage_user_base_model->model_db->trans_begin();
	            $this->manage_bill_model->model_db->trans_begin();
	            try{
	                //追加余额支付金额
	                $up_bill=array(
	                    'pay_balance'=>$user_info['user_balance'],
	                    'update_time'=>time()
	                );
	                $this->manage_bill_model->edit($info['bill_id'],$up_bill);
	                //扣除用户余额
	                $this->manage_user_base_model->reduceBalance($info['uid'],$user_info['user_remainder']);
	                //添加用户余额变动记录
	                $content='订单'.$info['bill_num'].'余额支付支出';
	                $this->__handle_log_capital($info['uid'],$user_info['user_balance'],$content,2);
	                 
	                //微信支付
	                $this->load->library('wechatpay/Wechatpay',array(),'wxpay');#载入微信支付类
	                $openid=$this->user['openid'];
	                $body='家点菜';
	                $attach=json_encode(array(
	                    'bill_id'=>$info['bill_id'],
	                    'bill_num'=>$info['bill_num']
	                ));
	                $out_trade_no=time().rand(100000,999999); //随机字符串
	                $total_fee=$info['need_pay']-$user_info['user_balance']; //支付金额(以分为单位)
	                $total_fee=1;
	                $notify_url=base_url('/').'v1_0/notify/bill/wechat_callback';
	                try{
	                    $jsApiParameters=$this->wxpay->get_pay_sign($openid,$body,$attach,$out_trade_no,$total_fee,$notify_url);
	                }catch(Exception $e){
	                    throw new RJsonErrorException('支付失败','PAYMENT_ERROR');
	                }
	            }catch(Exception $e){
	                $this->manage_user_base_model->model_db->trans_rollback();
	                $this->manage_bill_model->model_db->trans_rollback();
	                throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
	            }
	            $this->manage_user_base_model->model_db->trans_commit();
	            $this->manage_bill_model->model_db->trans_commit();
	            $this->r['data']['jsApiParameters']=json_decode($jsApiParameters,true);
	            $this->r['data']['is_pay']=false;
	            $this->state = true;
	            $this->r();
	        }
	    }
	    //不使用余额,全额微信支付
	    $this->load->library('wechatpay/Wechatpay',array(),'wxpay');#载入微信支付类
	    $openid=$this->user['openid'];
	    $body='家点菜';
	    $attach=json_encode(array(
	        'bill_id'=>$info['bill_id'],
	        'bill_num'=>$info['bill_num']
	    ));
	    $out_trade_no=time().rand(100000,999999); //随机字符串
	    $total_fee=$info['need_pay']; //支付金额(以分为单位)
	    $total_fee=1;
	    $notify_url=base_url('/').'v1_0/notify/bill/wechat_callback';
	    $jsApiParameters=$this->wxpay->get_pay_sign($openid,$body,$attach,$out_trade_no,$total_fee,$notify_url);
	    $this->r['data']['jsApiParameters']=json_decode($jsApiParameters,true);
	    $this->r['data']['is_pay']=false;
	    $this->state = true;
	    $this->r();
	}
	
}
?>