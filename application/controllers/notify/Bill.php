<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 微信回调
 */
class Bill extends HZ_Controller{
    
	/**
	 * 微信订单回调
	 */
	public function wechat_callback(){
		$this->load->library('wechatpay/Wechatpay',array(),'wxpay');#载入微信支付类
		$this->wxpay->notify(array($this,'wechat_callback_handle'));
	}

	/**
	 * 微信回调系统内部逻辑
	 * @author jieyang
	 */
	public function wechat_callback_handle($data){
	    log_message('error', '触发到了自定义函数');
	    log_message('error',json_encode($data));
	    if($data["return_code"]!="SUCCESS"){  //商户接收通知
	        return false;
	    }
	    if($data["result_code"]!="SUCCESS"){ //业务结果
	        return false;
	    }
	    try{
	        //添加微信回调记录
	        $notify_data=$data;
	        $this->load->model('v1_0/wechat/Manage_bill_notify_wechat_model','manage_bill_notify_wechat_model');
	        $this->manage_bill_notify_wechat_model->add($notify_data);
	    }catch(ModelErrorException $e){
	        return false;
	    }
	    $attach=json_decode($data['attach'],true);
	    $bill_id=$attach['bill_id']; //微信返回订单id(提交支付时发送,微信原样返回);
	    $trade_no=$data['out_trade_no']; //微信订单号
	    //判断订单信息
	    $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
	    try{
	        $bill_info=$this->get_bill_model->getBillCheck($bill_id); //查询订单信息
	    }catch(ModelErrorException $e){
	       return false;  //不存在订单信息 
	    }    
	    if($bill_info['state']==2){  //订单已支付
	        return true; 
	    }
	    $this->load->model('v1_0/wechat/Manage_bill_model','manage_bill_model');
	    $this->load->model('v1_0/wechat/Manage_user_base_model','manage_user_base_model');
        //开始事物
        $this->manage_bill_model->model_db->trans_begin();
        try{
            //更新用户订单表
            $data_bill=array(
                'out_trade_no'=>$trade_no,
                'state'=>2,
                'pay_mode'=>2, //微信支付
                'update_time'=>time(),
                'pay_time'=>time()
            );
            $this->manage_bill_model->edit($bill_id,$data_bill);
        }catch(Exception $e){
            //回滚事务
            $this->manage_bill_model->model_db->trans_rollback();
            return false;
        }
        //提交事务
        $this->manage_bill_model->model_db->trans_commit();
        return true;
	}

	/**
	 * 订单支付成功回调(接口测试)
	 * @param $bill_id  订单id
	 * @author jieyang
	 */
	public  function get_ordreSuccess(){
	    $bill_id=401;
	    //判断订单信息
	    $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
	    try{
	        $bill_info=$this->get_bill_model->getBillCheck($bill_id); //查询订单信息
	    }catch(ModelErrorException $e){
	       throw new RJsonErrorException('获取订单信息有误','BILL_INFO_ERR');  //不存在订单信息 
	    }
	    if($bill_info['state']==2){  //订单已支付
	        throw new RJsonErrorException('订单状态有误','BILL_STATE_ERR');
	    }
	    $this->load->model('v1_0/wechat/Manage_bill_model','manage_bill_model');
	    $this->load->model('v1_0/wechat/Manage_user_base_model','manage_user_base_model');
        //开始事物
        $this->manage_bill_model->model_db->trans_begin();
        try{
            //更新用户订单表
            $data_bill=array(
                'out_trade_no'=>'123456789',
                'state'=>2,
                'update_time'=>time(),
                'pay_mode'=>2,
                'pay_time'=>time()
            );
            $this->manage_bill_model->edit($bill_id,$data_bill);
        }catch(Exception $e){
            //回滚事务
            $this->manage_bill_model->model_db->trans_rollback();
            throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
        }
        //提交事务
        $this->manage_bill_model->model_db->trans_commit();
	    $this->state=true;
	    $this->r();
	}
	

	
}?>	