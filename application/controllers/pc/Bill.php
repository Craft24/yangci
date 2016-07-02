<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bill extends MY_Controller{
    
    public function __construct(){
        parent::__construct();
        //$this->_auth('session',__CLASS__,TRUE);
    }

	private function __nowBill($check){
		$check['uid']=!empty($_SESSION['uid'])?$_SESSION['uid']:'';
		if(empty($check['uid'])){
			throw new RJsonErrorException('暂未登录','LOGIN_ERR');
		}
		//判断业务员信息
		$this->load->model('v1_0/common/useres/Get_users_model','get_users_model');
		$this->load->model('v1_0/common/bill/Get_bill_model','get_bill_model');
		$user=$this->get_users_model->getOne($check['uid']);
		$check['sales_id']=$user['sales_id'];
		$level_id=$this->get_users_model->getUserLevel($check['uid']);
		if(!empty($level_id)){
			$discount_rate=$this->get_users_model->getUserLevelRiscountRate($level_id);
		}else{
			$discount_rate=1;
		}
		$check_goods=$this->get_bill_model->checkGoodsState($check['goods_id']);
		if(empty($check_goods['goods_id'])){
			throw new RJsonErrorException('商品不存在','GET_GOODS_ERR');
		}
		if($check['goods_num']>$check_goods['goods_number']){
			throw new RJsonErrorException('商品库存不足','GOODS_NUMBER_ERR');
		}
		$this->load->model('v1_0/common/goods/Get_goods_model','get_goods_model');
		$goods_data=$this->get_goods_model->getOne($check['goods_id']);
		$total_goods[0]['goods_num']=$check['goods_num'];
		$total_goods[0]['goods_id']=$check['goods_id'];
		$total_goods[0]['cost_price']=$goods_data['cost_price']; //商品单价
		if(!empty($check['sku_base_id'])){
			$sku_price=$this->get_goods_model->getSkuStockOne($check['goods_id'],$check['sku_base_id']);
			$total_goods[0]['shop_price']=$sku_price['sku_shop_price'];
			$total_goods[0]['sku_base_id']=$check['sku_base_id'];
			$bill_goods=$this->__getBillMoney($total_goods,$discount_rate);
		}else{
			$total_goods[0]['shop_price']=$goods_data['shop_price'];
			$bill_goods=$this->__getBillMoney($total_goods,$discount_rate);
		}
		$check['price']=$bill_goods['price'];
		$check['need_pay']=$bill_goods['price'];
		$time=$this->time();
		$check['bill_time']=$time;
		//红包
		if(!empty($check['red_packet'])){
			$red_packet=$this->get_users_model->getUserRedPacket($check['uid'],$check['red_packet']);
			$check['need_pay']=$check['price']-$red_packet['price'];
		}
		//积分
		if(!empty($check['bonus_point'])){
			$bonus_point=$this->get_users_model->getUserIntegeral($check['uid']);
			$check['need_pay']=$check['need_pay']-$bonus_point['integral'];
		}
		$this->load->model('v1_0/common/address/Get_addres_model','get_address_model');
		if(empty($check['address_id'])){
			try{
				$address=$this->get_addres_model->getDefaultAddress($check['uid']);
				$check['province']=$address['province_name'];
				$check['city']=$address['city_name'];
				$ckeck['area']=$address['area_name'];
			}catch(ModelErrorException $e){
				throw new RJsonErrorException('默认地址为空','GET_DEFAULT_ADDRESS_FAIL');
			}
		}else{
			$address=$this->get_addres_model->getDefaultAddress($check['address_id'],$check['uid']);
			$check['province']=$address['province_name'];
			$check['city']=$address['city_name'];
			$ckeck['area']=$address['area_name'];
		}
		$check['consignee']=$address['user_name'];
		$check['mobile_phone']=$address['mobile_phone'];
		$check['street']=$address['street'];
		$check['add_time']=$time;
		$check['state']=1;
		if(!empty($check['red_packet'])){
			$check['red_packet']=$red_packet['price'];
		}
		if(!empty($check['bonus_point'])){
			$check['bonus_point']=$bonus_point['integral'];
		}
		unset($check['address_id']);
		$check['bill_num']=date('YmdHis',$time).rand(10,99);
		while($this->get_bill_model->checkBillNumHasExists($check['bill_num'])){
			$check['bill_num']=date('YmdHis',$this->time()).rand(10,99);
		}
        $this->load->model('v1_0/wx/Manage_bill_model','manage_bill_model');
        $this->load->model('v1_0/wx/Manage_bill_goods_model','manage_bill_goods_model');
        $this->load->model('v1_0/wx/Manage_users_model','manage_users_model');
        $this->load->model('v1_0/wx/Manage_goods_model','manage_goods_model');
        //开始事物
        $this->manage_bill_model->model_db->trans_begin();
        $this->manage_bill_goods_model->model_db->trans_begin();
        $this->manage_users_model->model_db->trans_begin();
        $this->manage_goods_model->model_db->trans_begin();
        try{
        	$goods_num=$check['goods_num'];
        	$goods_id=$check['goods_id'];
        	if(!empty($check['sku_base_id'])){
        		$sku_base_id=$check['sku_base_id'];
        		unset($check['sku_base_id']);
        	}
        	unset($check['goods_id']);
        	unset($check['goods_num']);
        	$bill_goods['bill_id']=$this->manage_bill_model->add($check);
        	$bill_goods['bill_num']=$check['bill_num'];
        	$bill_goods['goods']=$bill_goods['goods'];
        	foreach($bill_goods['goods'] as $k=>$v){
        		$temp=array();
        		$temp['bill_id']=$bill_goods['bill_id'];
        		$temp['bill_num']=$bill_goods['bill_num'];
        		$temp['goods_id']=$v['goods_id'];
        		if(!empty($v['sku_base_id'])){
        			$temp['base_id']=$v['sku_base_id'];
        		}
        		$temp['original_price']=$v['shop_price'];
        		$temp['unit_price']=$v['unit_price'];
        		$temp['goods_cnt']=$v['goods_num'];
        		$temp['cost_price']=$v['cost_price'];
        		$temp['add_time']=$time;
        		$this->manage_bill_model->add($temp);
        	}
        	$this->manage_goods_model->downGoodsNumber($goods_id,array('goods_number'=>$goods_num));
        	if(!empty($sku_base_id)){
        		$this->manage_goods_model->downGoodsSkuNumber($goods_id,$sku_base_id,array('goods_number'=>$goods_num));
        	}
        	if(!empty($bonus_point['integral'])){
        		$this->manage_users_model->downUsersIntegral($check['uid'],$bonus_point['integral']);
        	}
        	if(!empty($red_packet['red_id'])){
        		$this->manage_users_model->useUsersRedPacket($check['uid'],$red_packet['red_id']);
        	}
        	$this->manage_goods_model->addGoodsSales($temp['goods_id'],$goods_num);
        }catch(ModelErrorException $e){
            //回滚事务
            $this->manage_bill_model->model_db->trans_rollback();
            $this->manage_bill_goods_model->model_db->trans_rollback();
            $this->manage_users_model->model_db->trans_rollback();
            $this->manage_goods_model->model_db->trans_rollback();
            throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
        }
        //提交事务
        $this->manage_bill_model->model_db->trans_commit();
        $this->manage_bill_goods_model->model_db->trans_commit();
        $this->manage_users_model->model_db->trans_commit();
        $this->manage_goods_model->model_db->trans_commit();
        return $bill_goods['bill_id'];
	}
	
	
	private function __shoppingCarBill($check){
	    $check['uid']=!empty($_SESSION['uid'])?$_SESSION['uid']:'';
	    if(empty($check['uid'])){
	        throw new RJsonErrorException('暂未登录','LOGIN_ERR');
	    }
	    $shopping_id_arr=explode(',',$check['shopping_id']);
	    $this->load->model('v1_0/common/users/Get_users_model','get_users_model');
	    $this->load->moedl('v1_0/common/users/Get_bill_model','get_bill_model');
	    $this->load->model('v1_0/common/goods/Get_goods_model','get_goods_model');
	    $this->load->model('v1_0/common/car/Get_car_model','get_car_model');
	    //获取业务员信息,用户等级
	    $user=$this->get_users_model->getOne($check['uid']);
	    $check['sales_id']=$user['sales_id'];
	    $level_id=$this->get_users_model->getUsersLevel($check['uid']);
	    if(!empty($level_id)){
	        $discount_rate=$this->get_users_model->getUserLevelRiscountRate($level_id);
	    }else{
	        $discount_rate=1;
	    }
	    //获取购物车的商品
	    foreach($shopping_id_arr as $k=>$v){
	        $shopping_info[]=$this->get_car_model->getShoppingCar($v,$check['uid']);
	    }
	    $bill_goods_data=array();
	    foreach($shopping_info as $k=>$v){
	        //检测商品
	        $check_goods=$this->get_bill_model->checkGoodsState($v['goods_id']);
	        if(empty($check_goods['goods_id'])){
	            throw new RJsonErrorException('商品不存在','GOODS_NOT_EXISTX_FAIL');
	        }
	        if(empty($v['goods_num'])>$check_goods['goods_number']){
	            throw new RJsonErrorException('商品库存不足','GOODS_NUMBER_FAIL');
	        }
	        //获取商品信息
	        $goods_data=$this->get_goods_model->getOne($v['goods_id']);
	        $bill_goods_data[0]['goods_id']=$v['goods_id'];
	        $bill_goods_data[0]['goods_num']=$v['goods_num'];
	        $bill_goods_data[0]['cost_price']=$goods_data['cost_price'];
	        if(!empty($v['sku_id'])){ //判断是否存在sku
	            $sku_price=$this->get_goods_model->getSkuStockOne($check_goods['goods_id'],$v['sku_id']);
	            $bill_goods_data[0]['shop_price']=$sku_price['sku_shop_price'];
	            $goods_balance2[]=$this->__getBillMoney($bill_goods_data,$discount_rate);
	        }else{
	            $bill_goods_data[0]['shop_price']=$goods_data['shop_price'];
	            $goods_balance2[]=$this->__getBillMoney($bill_goods_data,$discount_rate);
	        }
	    }
	    $total_money=0;
	    foreach($goods_balance2 as $k=>$v){
	        $total_money+=$v['price'];
	    }
	    $check['price']=$total_money;
	    $check['need_play']=$total_money;
	    $time=$this->time();
	    $check['bill_time']=$time;
	    //如果用户选择红包抵扣。则去获取抵扣金额
	    if (!empty($check['red_packet'])) {
	        $red_packet=$this->get_users_model->getUsersRedPacket($check['uid'],$check['red_packet']);
	        $check['need_pay']=$check['price']-$red_packet['price'];
	    }
	    //如果用户选择积分抵扣，则去获取用户积分
	    if (!empty($check['bonus_point'])) {
	        $bonus_point=$this->get_users_model->getUsersIntegral($check['uid']);
	        $check['need_pay']=$check['need_pay']-$bonus_point['integral'];
	    }
	    //如果没有传地址过来，获取默认地址
	    $this->load->model('v1_0/common/address/Get_address_model','get_address_model');
	    if (empty($check['address_id'])){
	        try{
	            $address=$this->get_address_model->getDefaultAddress($check['uid']);
	            $check['province']=$address['province_name'];
	            $check['city']=$address['city_name'];
	            $check['area']=$address['area_name'];
	        }catch(ModelErrorException $e){
	            throw new RJsonErrorException('默认的地址为空,请选择收货地址','DEFAULT_ADDRESS');
	        }
	    }else{
	        $address=$this->get_address_model->getUsersOne($check['address_id'],$check['uid']);
	        $check['province']=$address['province'];
	        $check['city']=$address['city'];
	        $check['area']=$address['area'];
	    }
	    $check['consignee']=$address['user_name'];
	    $check['mobile_phone']=$address['mobile_phone'];
	    $check['street']=$address['street'];
	    $check['add_time']=$time;
	    $check['state']=1;
	    if(!empty($check['red_packet'])){
	        $check['red_packet']=$red_packet['price'];
	    }
	    if(!empty($check['bonus_point'])){
	        $check['bonus_point']=$bonus_point['integral'];
	    }
	    unset($check['address_id']);
	    unset($check['shopping_id']);
	    
	    $check['bill_num']=date('YmdHis',$this->time()).rand(10,99);
	    //验证订单号是否重复
	    $this->load->model('v1_0/common/bill/Get_bill_model','get_bill_model');
	    while ($this->get_bill_model->checkBillNumHasExists($check['bill_num'])){
	        $check['bill_num'] = date('YmdHis',$this->time()) . rand(10,99);
	    }
	    $this->load->model('v1_0/wx/Manage_bill_model','manage_bill_model');
	    $this->load->model('v1_0/wx/Manage_bill_goods_model','manage_bill_goods_model');
	    $this->load->model('v1_0/wx/Manage_users_model','manage_users_model');
	    $this->load->model('v1_0/wx/Manage_goods_model','manage_goods_model');
	    //开始事物
	    $this->manage_bill_model->model_db->trans_begin();
	    $this->manage_bill_goods_model->model_db->trans_begin();
	    $this->manage_users_model->model_db->trans_begin();
	    $this->manage_goods_model->model_db->trans_begin();
	    try{
	        $bill_goods['bill_id']=$this->manage_bill_model->add($check);
	        $bill_goods['bill_num']=$check['bill_num'];
	        //循环插入bill_goods表记录
	        foreach ($goods_balance2 as $key=>$value) {
	            foreach($value['goods'] as $k=>$v) {
	                $temp = array();
	                $temp['bill_id']=$bill_goods['bill_id'];
	                $temp['bill_num']=$bill_goods['bill_num'];
	                $temp['goods_id']=$v['goods_id'];
	                if(!empty($v['sku_id'])){
	                    $temp['base_id']=$v['sku_id'];
	                }
	                $temp['original_price']=$v['shop_price'];
	                $temp['unit_price']=$v['unit_price'];
	                $temp['goods_cnt']=$v['goods_num'];
	                $temp['cost_price']=$v['cost_price'];
	                $temp['total_money']=$v['total_money'];
	                $temp['add_time']=$time;
	                $this->manage_bill_goods_model->add($temp);
	                //减去商品库存
	                $this->manage_goods_model->downGoodsNumber($temp['goods_id'],array('goods_number' =>$v['goods_num']));
	                if (!empty($v['sku_id'])) {
	                    $this->manage_goods_model->downGoodsSkuNumber($v['goods_id'],$v['sku_id'],array('goods_number' =>$v['goods_num']));
	                }
	                //销量增加
	                $this->manage_goods_model->addGoodsSales($temp['goods_id'],$v['goods_num']);
	            }
	        }
	        if(!empty($bonus_point['integral'])){
	            //减去用户积分
	            $this->manage_users_model->downUsersIntegral($check['uid'],$bonus_point['integral']);
	        }
	        if(!empty($red_packet['red_id'])){
	            //把红包用掉
	            $this->manage_users_model->useUsersRedPacket($check['uid'],$red_packet['red_id']);
	        }
	    }catch(ModelErrorException $e){
	        //回滚事务
	        $this->manage_bill_model->model_db->trans_rollback();
	        $this->manage_bill_goods_model->model_db->trans_rollback();
	        $this->manage_users_model->model_db->trans_rollback();
	        $this->manage_goods_model->model_db->trans_rollback();
	        throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
	    }
	    //提交事务
	    $this->manage_bill_model->model_db->trans_commit();
	    $this->manage_bill_goods_model->model_db->trans_commit();
	    $this->manage_users_model->model_db->trans_commit();
	    $this->manage_goods_model->model_db->trans_commit();
	    //删除购物车商品
	    $this->__deleteCarGoods($check['uid'],$shopping_id_arr);
	    return $bill_goods['bill_id'];
	}

	private function __getBillMoney($data,$discount_rate){
		$result=array();
		$result['price']=0;
		foreach($data as $k=>$v){
			$v['unite_price']=$v['shop_price']*($discount_rate*100)/100;
			$v['total_money']=$v['unit_price']*$v['goods_num'];
			$result['price']=$v['total_money'];
			$result['goods'][0]['total_money']=$v['total_money'];
			$result['goods'][0]=$v;
		}
		return $result;
	}
	
	
	
}
?>