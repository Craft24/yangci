<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 订单数据操作
 */
class Bill extends Wx_Api_1_0_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->_auth('session',__CLASS__,TRUE);
        /* $_SESSION['user_info']=array(
            'user_id' => 109,
        ); */
    }
   
    /**
     * 获取显示确认订单数据
     * @author jieyang
     */
    public function get_confirm_bill(){
        $this->__confirm_bill_car();
        /* $data=$this->input->get('car_id');
        if($data){
            $this->__confirm_bill_car();
        }else{
            $this->__confirm_bill_now();
        }  */
    }
      
    /**
     * 立即下单确认订单数据
     * @author jieyang
     */
    private function __confirm_bill_now(){
        $data=$this->input->get();
        $rule=array(
            'goods_id'=>array('egNum',null,true),
            'goods_num'=>array('egNum',null,true),
            'pay_point'=>array('in',array(1,2),false), //1使用积分,2不使用积分
            'pay_red'=>array('in',array(1,2),false), //1使用红包,2不使用红包
            'address_id'=>array('egNum',null,false)
        );
        $this->verify->verify($rule,$data);
        $uid=$this->user['user_id'];
        $data['uid']=$uid;
        try{
            //找不到默认地址或者用户指定的地址
            $data['address_id']=isset($data['address_id'])?$data['address_id']:null;
            $address_info=$this->__getUserAddress($data);
        }catch(Exception $e){
            //随机获取用户地址
            $this->load->model('v1_0/common/Get_user_address_model','get_user_address_model');
            try{
                $address_info=$this->get_user_address_model->getOneAddress($uid);
            }catch(Exception $e){
                $address_info='';
            }
        }
        $goods_arr=array();
        $this->load->model('v1_0/common/Get_goods_base_model','get_goods_base_model');
        $this->load->model('v1_0/common/Get_user_base_model','get_user_base_model');
        //立即下单
        $goods_info=$this->get_goods_base_model->getGoodsCheck($data['goods_id']);
        $goods_info['goods_num']=$data['goods_num'];
        $total_price=$goods_info['shop_price']*$data['goods_num'];
        $goods_info['price']=$total_price;
        $list[]=$goods_info;
        $goods_arr[0]['goods_id']=$goods_info['goods_id'];
        $goods_arr[0]['goods_num']=$goods_info['goods_num'];
        //用户使用积分
        if(isset($data['pay_point'])&&$data['pay_point']==1){
            $point=$this->__handel_user_point($goods_arr,$uid);
            $is_pay_point=1;
        }else{
            $point=array(
                'use_point'=>0,
                'goods_point'=>0,
                'user_point'=>0
            );
            $is_pay_point=2;
        }
        //用户使用红包
        if(isset($data['pay_red'])&&$data['pay_red']==1){
            $red=$this->__handel_user_red($goods_arr,$uid);
            $is_pay_red=1;
        }else{
            $red=array(
                'use_red'=>0,
                'goods_red'=>0,
                'user_red'=>0
            );
            $is_pay_red=2;
        }
        //订单服务费
        $freight=$this->__handel_freight();   
        $this->r['data']['goods_info']=$list; 
        $this->r['data']['user_address']=$address_info;
        $this->r['data']['point']=$point;
        $this->r['data']['red']=$red;
        $this->r['data']['total_price']=$total_price+$freight;
        $this->r['data']['freight']=$freight;
        $this->r['data']['is_pay_red']=$is_pay_red;
        $this->r['data']['is_pay_point']=$is_pay_point;
        $this->state=true;
        $this->r();       
    }
      
    /**
     * 购物车下单确认订单数据
     * @author jieyang
     */
    private function __confirm_bill_car(){
        $data=$this->input->get();
        $rule=array(
            'pay_point'=>array('in',array(1,2),false),  //1使用积分,2不使用积分
            'pay_red'=>array('in',array(1,2),false), //1使用红包,2不使用红包
            'address_id'=>array('egNum',null,false)
        );
        $this->verify->verify($rule,$data);
        $uid=$this->user['user_id'];
        $this->load->model('v1_0/common/Get_shopping_car_model','get_shopping_car_model');
        $car_id_arr=$this->get_shopping_car_model->getCarId($uid);
        $car_id_str='';
        if($car_id_arr){
            foreach($car_id_arr as $k=>$v){
                $car_id_str.=$v['car_id'].',';
            }
            $data['car_id']=substr($car_id_str, 0,-1);
        }else{
            throw new RJsonErrorException('您还未选择任何商品','GET_USER_GOODS_CAR_FAIL');
        }
        $uid=$this->user['user_id'];
        $data['uid']=$uid;
        try {
            //找不到默认地址或者用户指定的地址
            $data['address_id']=isset($data['address_id'])?$data['address_id']:null;
            $address_info=$this->__getUserAddress($data);
        }catch(Exception $e){
            //随机获取用户地址
            $this->load->model('v1_0/common/Get_user_address_model','get_user_address_model');
            try{
                $address_info=$this->get_user_address_model->getOneAddress($uid);
            }catch(Exception $e){
                $address_info='';
            }
        }       
        $this->load->model('v1_0/common/Get_goods_base_model','get_goods_base_model');
        $this->load->model('v1_0/common/Get_user_base_model','get_user_base_model');
        //购物车
        $car_arr=explode(',',$data['car_id']);
        $list=array();
        $goods_arr=array();  //商品数据
        $this->load->model('v1_0/common/Get_shopping_car_model','get_shopping_car_model');
        //购物车
        $total_price=0; //订单总价格
        $total_point=0; //订单商品总积分
        $goods_total=0; //订单商品总价格
        foreach($car_arr as $k=>$v){
            $car_one_info=$this->get_shopping_car_model->getOne($v);
            $goods_info=$this->get_goods_base_model->getGoodsCheck($car_one_info['goods_id']);
            $goods_info['goods_num']=$car_one_info['goods_num'];
            $price=$goods_info['shop_price']*$car_one_info['goods_num'];  //订单单品总价格
            $goods_point=$goods_info['pay_point']*$car_one_info['goods_num']; //订单单品总积分
            $goods_info['goods_price']=$price;
            $goods_total+=$price;
            $total_price+=$price;
            $total_point+=$goods_point;
            $list[]=$goods_info;
            //运费
            $goods_arr[$k]['goods_id']=$car_one_info['goods_id'];
            $goods_arr[$k]['goods_num']=$car_one_info['goods_num'];
        }
        //用户使用积分
        if(isset($data['pay_point'])&&$data['pay_point']==1){
            $point=$this->__handel_user_point($goods_arr,$uid);
            $is_pay_point=1;
        }else{
            $point=array(
                'use_point'=>0,
                'goods_point'=>0,
                'user_point'=>0
            );
            $is_pay_point=2;
        }
        $point_money=$this->__handle_integral($point['use_point']);
        //用户使用积分
        if(isset($data['pay_red'])&&$data['pay_red']==1){  
            $red=$this->__handel_user_red($goods_arr,$uid);
            $is_pay_red=1;
        }else{
            $red=array(
                'use_red'=>0,
                'goods_red'=>0,
                'user_red'=>0
            );
            $is_pay_red=2;
        }
        //用户余额
        $user_balance=$this->get_user_base_model->getUserBalance($data['uid']);
        //订单服务费
        $freight=$this->__handel_freight();
        $this->r['data']['goods_info']=$list;
        $this->r['data']['point']=$point;
        $this->r['data']['point_money']=$point_money;
        $this->r['data']['red']=$red;
        $this->r['data']['user_address']=$address_info;
        $this->r['data']['total_price']=$total_price+$freight-$point_money-$red['use_red'];
        $this->r['data']['freight']=$freight;
        $this->r['data']['is_pay_red']=$is_pay_red;
        $this->r['data']['is_pay_point']=$is_pay_point;
        $this->r['data']['goods_total']=$goods_total;
        $this->r['data']['user_balance']=$user_balance;
        $this->r['data']['discount']=$point_money+$red['use_red'];
        $this->state=true;
        $this->r();
    }
    
    /**
     * 生成订单
     * @author jieyang
     */
    public function post_index(){
        $bill_info=$this->__carBill();
       /*  $data=$this->input->post();
        if(isset($data['car_id'])){
            //购物车下单
            $bill_id=$this->__carBill();
        }else{ 
            //立即购买
            $bill_id=$this->__nowBill();
        } */
        $this->r['data']=$bill_info;
        $this->state=true;
        $this->r();
    }
     
    /**
     * 立即购买
     * @author jieyang
     */
    private function __nowBill(){
        $data=$this->input->post();
        $rule=array(
            'goods_id'=>array('egNum',null,true),
            'address_id'=>array('egNum',null,false),
            'goods_num'=>array('egNum',null,true),
            'pay_point'=>array('in',array(1,2),true), //是否使用积分 1是,2否
            'pay_red'=>array('in',array(1,2),true), //是否使用红包1是,2否
            'remarks'=>array(null,null,false), //买家备注
        );
        $this->verify->verify($rule,$data);
        foreach($data as $k=>$v){
            isset($data[$k])?$save[$k]=$data[$k]:'';
        }
        $save['uid']=$this->user['user_id'];
        //获取商品信息
        $this->load->model('v1_0/common/Get_goods_base_model','get_goods_base_model');
        $goods_info=$this->get_goods_base_model->getGoodsCheck($save['goods_id']);
        //检查商品状态
        if($goods_info['is_bracket']!=1){
            throw new RJsonErrorException('商品未上架','GOODS_STATE_ERR');
        }
        //判断商品是否可使用积分
        $pay_point=$goods_info['pay_point']; 
        //判断是否具有积分返还
        $get_point=$goods_info['get_point'];
        //判断商品是否可使用红包
        $pay_red=$goods_info['pay_red'];
        //判断是否具有积红包返还
        $get_red=$goods_info['get_red'];        
        //计算订单金额
        $unit_price=$goods_info['shop_price'];  
        $total_amount=$unit_price*$save['goods_num']; //订单总金额
        $need_pay_amount=$total_amount; //实际支付金额
        //加载用户模型
        $this->load->model('v1_0/common/Get_user_base_model','get_user_base_model');
        //订单积分处理
        if($save['pay_point']==1){ //用户使用积分
            if($pay_point>0){   //判断商品是否可以使用积分
                //获取用户积分剩余量
                $user_info_point=$this->get_user_base_model->getUserIntegral($save['uid']);;
                //计算多件商品总共消耗积分
                $point_sum=$pay_point*$save['goods_num'];
                //计算订单总共可消耗的积分
                if($point_sum>$user_info_point){
                    $pay_point_res=$user_info_point;
                }else{
                    $pay_point_res=$point_sum;
                } 
                //把积分转化为对应的金额(以分为单位)
                $point_money=$this->__handle_integral($pay_point_res);
                $need_pay_amount=$need_pay_amount-$point_money; //实际支付金额 
            }else{
                $pay_point_res=0;
                $point_money=0;
            }   
        }else{
            $pay_point_res=0;
            $point_money=0;
        }
        //判断用户(下单人)获得的积分
        if($get_point>0){
            $get_point_res=$get_point*$save['goods_num'];
        }else{
            $get_point_res=0;
        }
        
        //订单红包处理
        if($save['pay_red']==1){ //用户使用红包
            if($pay_point>0){   //判断商品是否可以使用红包
                //获取用户红包剩余量
                $user_info_red=$this->get_user_base_model->getUserRed($save['uid']);
                //计算多件商品总共消费红包
                $red_sum=$pay_red*$save['goods_num'];
                //计算订单总共可消耗的红包
                if($red_sum>$user_info_red){
                    $pay_red_res=$user_info_red;
                }else{
                    $pay_red_res=$red_sum;
                }
                $need_pay_amount=$need_pay_amount-$pay_red_res; //实际支付金额
            }else{
                $pay_red_res=0;
            }
        }else{
            $pay_red_res=0;
        }
        //判断用户(下单人)获得的红包
        if($get_red>0){
            $get_red_res=$get_red*$save['goods_num'];
        }else{
            $get_red_res=0;
        }
        //获取用户收货地址
        $user_address=$this->__getUserAddress($save);
        $save['goods_name']=$goods_info['goods_name'];
        $save['cover_img']=$goods_info['cover_img'];
        $save['add_time']=$this->time();
        $save['state']=1;
        //加载系统配置模型
        $this->load->model('v1_0/common/Get_config_model','get_config_model');
        $config_info=$this->get_config_model->getOne(); 
        $save['pay_end_time']=$this->time()+$config_info['pay_end_time']*60*60;  //获取支付到期时间
        //生成订单号
        $save['bill_num']=date('YmdHis',$this->time()).rand(10,99);
        $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
        while($this->get_bill_model->checkBillNum($save['bill_num'])){ //验证订单号是否重复
            $save['bill_num']=date('YmdHis',$this->time()).rand(10,99);
        }
        //买家备注
        if(isset($save['remarks'])){
            $remarks=$save['remarks'];
        }else{
            $remarks='';
        }
        //订单服务费
        $freight=$this->__handel_freight();
        $this->load->model('v1_0/wechat/Manage_bill_model', 'manage_bill_model');
        $this->load->model('v1_0/wechat/Manage_bill_goods_model','manage_bill_goods_model');
        $this->load->model('v1_0/wechat/Manage_user_base_model','manage_user_base_model');
        $this->load->model('v1_0/wechat/Manage_goods_base_model','manage_goods_base_model');
        //开始事物
        $this->manage_bill_model->model_db->trans_begin();
        $this->manage_bill_goods_model->model_db->trans_begin();
        $this->manage_user_base_model->model_db->trans_begin();
        $this->manage_goods_base_model->model_db->trans_begin();
        try{      
            //订单主表
            $data_bill=array(
                'bill_num'=>$save['bill_num'],
                'uid'=>$save['uid'],
                'price'=>$total_amount+$freight,//订单价格+服务费
                'bill_goods_name'=>$save['goods_name'], //订单商品名称
                'pay_point'=>$pay_point_res,
                'get_point'=>$get_point_res,
                'pay_red'=>$pay_red_res,
                'get_red'=>$get_red_res,
                'need_pay'=>$need_pay_amount+$freight, //需要支付的金额+服务费
                'bill_time'=>$this->time(),
                'pay_end_time'=>$save['pay_end_time'],
                'freight'=>$freight, //服务费
                'true_name'=>$user_address['true_name'],
                'mobile_phone'=>$user_address['mobile_phone'], 
                'province'=>$user_address['province'],
                'city'=>$user_address['city'], 
                'area'=>$user_address['area'], 
                'street'=>$user_address['street'],
                'remarks'=>$remarks,
                'add_time'=>$this->time(),
                'update_time'=>$this->time()
            );
            $bill_id=$this->manage_bill_model->add($data_bill);
            //订单商品表
            $data_bill_goods=array(
                'bill_id'=>$bill_id,
                'bill_num'=>$save['bill_num'],
                'goods_id'=>$save['goods_id'],
                'goods_name'=>$save['goods_name'],
                'cover_img'=>$save['cover_img'],
                'get_point'=>$get_point_res, // 订单商品获得积分
                'pay_point'=>$pay_point_res, // 订单商品使用积分
                'get_red'=>$get_red_res, // 订单商品获得红包
                'pay_red'=>$pay_red_res, // 订单商品使用红包
                'goods_price'=>$unit_price, 
                'goods_cnt'=>$save['goods_num'],
                'amount'=>$save['goods_num']*$unit_price,
                'total_money'=>$save['goods_num']*$unit_price-$point_money-$pay_red_res, //该商品实际支付金额
                'add_time'=>$this->time(),
                'update_time'=>$this->time()
            );
            $bill_goods_id=$this->manage_bill_goods_model->add($data_bill_goods);       
            //用户积分操作
            if($pay_point_res>0){
                //减少用户积分
                $this->manage_user_base_model->reduceIntegral($save['uid'],$pay_point_res);
                //添加积分使用记录
                $content='订单'.$save['bill_num'].'下单,消耗积分'; 
                $this->__handle_log_point($save['uid'],$pay_point_res,$content,2);   
            } 
            //用户红包操作
            if($pay_red_res>0){
                //减少用户红包
                $this->manage_user_base_model->reduceRed($save['uid'],$pay_red_res);
                //添加红包使用记录
                $content='订单'.$save['bill_num'].'下单,消耗红包';
                $this->__handle_log_red($save['uid'],$pay_red_res,$content,2);
            }    
        }catch(Exception $e){
            $this->manage_bill_model->model_db->trans_rollback();
            $this->manage_bill_goods_model->model_db->trans_rollback();
            $this->manage_user_base_model->model_db->trans_rollback();
            $this->manage_goods_base_model->model_db->trans_rollback();
            throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
        }
        //提交事务
        $this->manage_bill_model->model_db->trans_commit();
        $this->manage_bill_goods_model->model_db->trans_commit();
        $this->manage_user_base_model->model_db->trans_commit();
        $this->manage_goods_base_model->model_db->trans_commit();
        return $bill_id;
    }
    
    /**
     * 购物车下单
     * @author jieyang
     */
    private function __carBill(){
        $data=$this->input->post();
        $rule=array(
            'address_id'=>array('egNum',null,false),
            'pay_point'=>array('in',array(1,2),true), //是否使用积分 1是,2否
            'pay_red'=>array('in',array(1,2),true), //是否使用红包 1是,2否
            'remarks'=>array(null,null,false), //买家备注
            'pay_mode'=>array('in',array(1,2,3),true) //1.余额支付;2.微信支付,3货到付款',
        );
        $this->verify->verify($rule,$data);
        foreach($data as $k=>$v){
            isset($data[$k])?$save[$k]=$data[$k]:'';
        }
        $uid=$this->user['user_id'];
        $this->load->model('v1_0/common/Get_shopping_car_model','get_shopping_car_model');
        $car_id_arr=$this->get_shopping_car_model->getCarId($uid);
        $car_id_str='';
        if($car_id_arr){
            foreach($car_id_arr as $k=>$v){
                $car_id_str.=$v['car_id'].',';
            }
            $save['car_id']=substr($car_id_str, 0,-1);
        }else{
            throw new RJsonErrorException('您还未选择任何商品','GET_USER_GOODS_CAR_FAIL');
        }
        $save['uid']=$this->user['user_id'];
        $car_id_arr=explode(',', $save['car_id']);
        $this->load->model('v1_0/common/Get_shopping_car_model','get_shopping_car_model');
        $this->load->model('v1_0/common/Get_goods_base_model','get_goods_base_model');
        //检测判断
        $total_amount=0; //订单总价
        $need_pay_amount=0; //订单需要支付总价
        $total_get_point=0; //订单总可获得返还积分
        $total_pay_point=0;  //订单总共可使用积分
        $total_get_red=0; //订单总可获得返还红包
        $total_pay_red=0;  //订单总共可使用红包
        $bill_goods=array(); //订单商品数据
        $bill_goods_mun=0; //订单商品数量
        $bill_goods_name='';
        foreach($car_id_arr as $k=>$v){
            //获取购物车中的单条记录信息
            $car_one_info=$this->get_shopping_car_model->getOne($v);
            //商品详情
            $goods_info=$this->get_goods_base_model->getGoodsCheck($car_one_info['goods_id']);
            //检查商品状态
            if($goods_info['is_bracket']!=1){
                throw new RJsonErrorException('商品未上架','GOODS_STATE_ERR');
            }
            //商品主表单价
            $unit_price=$goods_info['shop_price'];
            //计算单条商品价格
            $price=$car_one_info['goods_num']*$goods_info['shop_price'];
            //判断用户可获得返还积分
            $get_point=$goods_info['get_point']*$car_one_info['goods_num'];
            //判断商品是否可使用积分
            $pay_point=$goods_info['pay_point']*$car_one_info['goods_num'];
            //判断用户可获得返还红包
            $get_red=$goods_info['get_red']*$car_one_info['goods_num'];
            //判断商品是否可使用红包
            $pay_red=$goods_info['pay_red']*$car_one_info['goods_num'];
            //判断用户是否使用积分 && 判断商品是否可使用积分
            if($save['pay_point']==1 && $pay_point>0){
                $flag_pay_point=1; //标识可使用积分
                $flag_pay_point_value=$pay_point; //记录此商品可使用的积分数 (包括数量)
            }else{
                $flag_pay_point=2; //标识不可使用积分
                $flag_pay_point_value=0;
            }  
            //判断用户是否使用红包 && 判断商品是否可使用红包
            if($save['pay_red']==1 && $pay_red>0){
                $flag_pay_red=1; //标识可使用红包
                $flag_pay_red_value=$pay_red; //记录此商品可使用的红包 (包括数量)
            }else{
                $flag_pay_red=2; //标识不可使用红包
                $flag_pay_red_value=0;
            }
            //统计总金额
            $total_amount+=$price; //订单总价
            $need_pay_amount+=$price; //订单需要支付总价
            $total_get_point+=$get_point; //订单总共可获得积分
            $total_pay_point+=$flag_pay_point_value; //订单总共可使用积分
            $total_get_red+=$get_red; //订单总共可获得红包
            $total_pay_red+=$flag_pay_red_value; //订单总共可使用红包
            //构建添加数据（订单商品表,部分数据需要换算）
            $data_bill_goods=array(
                'goods_id'=>$car_one_info['goods_id'],
                'goods_price'=>$unit_price,
                'goods_cnt'=>$car_one_info['goods_num'],
                'total_money'=>$car_one_info['goods_num']*$unit_price, //商品总价,添加时计算减去积分抵扣
                'amount'=>$car_one_info['goods_num']*$unit_price,  //商品总价
                'get_point'=>$get_point, //下单者获得的积分
                'get_red'=>$get_red, //下单者获得的红包
                'goods_name'=>$goods_info['goods_name'],
                'cover_img'=>$goods_info['cover_img'],
                'flag_pay_point'=>$flag_pay_point_value, //此订单商品可消耗的积分
                'flag_pay_red'=>$flag_pay_red_value //此订单商品可消耗的红包
            );
            $bill_goods_name.=$goods_info['goods_name'].',';
            $bill_goods[]=$data_bill_goods;//追加数组
            $bill_goods_mun+=$car_one_info['goods_num'];
        }
        //判断订单中的积分和用户账户积分
        $this->load->model('v1_0/common/Get_user_base_model','get_user_base_model');
        //使用积分
        if($save['pay_point']==1&&$total_pay_point>0){ //用户使用积分 && 商品可使用积分
            //获取用户积分剩余量
            $user_info_integral=$this->get_user_base_model->getUserIntegral($save['uid']);
            if($user_info_integral>0){ //用户有积分可用
                if($total_pay_point>$user_info_integral){
                    $pay_point_percen=1; //积分换算采用百分比
                    $total_pay_point_use=$user_info_integral; //可使用积分为用户的账户剩余积分
                }else{
                    $pay_point_percen=2; //积分换算采用商品可使用积分数
                    $total_pay_point_use=$total_pay_point;  //可使用积分为商品总积分
                }
                //把积分转化为对应的金额(以分为单位)
                $point_money=$this->__handle_integral($total_pay_point_use);
                $need_pay_amount=$need_pay_amount-$point_money; //实际支付金额
            }else{
                $total_pay_point_use=0;
                $point_money=0;
            }
        }else{
            $total_pay_point_use=0;
            $point_money=0;
        }
        //使用红包
        if($save['pay_red']==1&&$total_pay_red>0){ //使用积分
            //获取用户积分剩余量
            $user_info_red=$this->get_user_base_model->getUserRed($save['uid']);
            if($user_info_red>0){
                if($total_pay_red>$user_info_red){
                    $pay_red_percen=1; //红包换算采用百分比
                    $total_pay_red_use=$user_info_red; //可使用红包为用户的账户剩余红包
                }else{
                    $pay_red_percen=2; //红包换算采用商品可使用红包数
                    $total_pay_red_use=$total_pay_red;  //可使用红包为商品可使用红包数
                }
                $red_money=$total_pay_red;
                $need_pay_amount=$need_pay_amount-$total_pay_red_use; //实际支付金额
            }else{
                $total_pay_red_use=0;
                $red_money=0;
            }
        }else{
            $total_pay_red_use=0;
            $red_money=0;
        }
        //获取用户收货地址
        $user_address=$this->__getUserAddress($save);
        $save['add_time']=$this->time();
        $save['state']=1;
        //加载系统配置模型
        $this->load->model('v1_0/common/Get_config_model','get_config_model');
        $config_info=$this->get_config_model->getOne();
        $save['pay_end_time']=$this->time()+$config_info['pay_end_time']*60*60;  //获取支付到期时间
        //生成订单号
        $save['bill_num']=date('YmdHis',$this->time()).rand(10,99);
        $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
        while($this->get_bill_model->checkBillNum($save['bill_num'])){ //验证订单号是否重复
            $save['bill_num']=date('YmdHis',$this->time()).rand(10,99);
        }
        //买家备注
        if(isset($save['remarks'])){
            $remarks=$save['remarks'];
        }else{
            $remarks='';
        }
/*         if($save['pay_mode']==3){
            $pay_mode=3;
            $bill_state=2;
        }else{
            $pay_mode=0;
            $bill_state=1;
        } */ 
        //订单服务费
        $freight=$this->__handel_freight();
        $this->load->model('v1_0/wechat/Manage_bill_model', 'manage_bill_model');
        $this->load->model('v1_0/wechat/Manage_bill_goods_model','manage_bill_goods_model');
        $this->load->model('v1_0/wechat/Manage_user_base_model','manage_user_base_model');
        $this->load->model('v1_0/wechat/Manage_goods_base_model','manage_goods_base_model');
        $this->load->model('v1_0/wechat/Manage_shopping_car_model','manage_shopping_car_model');
        //开始事物
        $this->manage_bill_model->model_db->trans_begin();
        $this->manage_bill_goods_model->model_db->trans_begin();
        $this->manage_user_base_model->model_db->trans_begin();
        $this->manage_goods_base_model->model_db->trans_begin();
        $this->manage_shopping_car_model->model_db->trans_begin();
        $bill_goods_name=substr($bill_goods_name,0,-1);
        //执行添加
        try{
            //订单主表
            $data_bill=array(
                'bill_num'=>$save['bill_num'],
                'uid'=>$save['uid'],
                'price'=>$total_amount+$freight,
                'pay_point'=>$total_pay_point_use,
                //'get_point'=>$total_get_point,
                'get_point'=>0,
                'pay_red'=>$total_pay_red_use,
                //'get_red'=>$total_get_red,
                'get_red'=>0,
                'need_pay'=>$need_pay_amount+$freight,
                'bill_time'=>$this->time(),
                'goods_num'=>$bill_goods_mun,
                'pay_end_time'=>$save['pay_end_time'],
                'freight'=>$freight, //服务费
                'bill_goods_name'=>$bill_goods_name,
                'true_name'=>$user_address['true_name'],
                'mobile_phone'=>$user_address['mobile_phone'],
                'province'=>$user_address['province'],
                'city'=>$user_address['city'],
                'area'=>$user_address['area'],
                'street'=>$user_address['street'],
                'remarks'=>$remarks,
                'add_time'=>$this->time(),
                'update_time'=>$this->time()
            );
            $bill_id=$this->manage_bill_model->add($data_bill);
            $goods_money_total=0;
            foreach($bill_goods as $k=>$v){
                $goods_money=0;
                $goods_point=0;
                $goods_red=0;
                $amount=$v['amount']; //商品总价
                //用户使用积分:1用户积分足够(直接使用商品的可使用积分)2,用户积分不足(按比例分配给可使用积分的商品,比例+加分配计算)
                if($save['pay_point']==1){
                    if($total_pay_point_use>0){
                        if($pay_point_percen==1){ //用户有积分但不足(按比例分配给可使用积分的商品,比例)
                            //积分换算采用百分比
                            $goods_point=$v['flag_pay_point']/$total_pay_point*$total_pay_point_use; //单品总积分和订单总积分的比例  订单可使用的总
                        }else{
                            //积分直接使用商品积分
                            $goods_point=$v['flag_pay_point']; //用户积分足够(直接使用商品的可使用积分)
                        }
                    }else{ //用户无积分
                        $goods_point=0;
                    }
                }else{
                    //用户不使用积分
                    $goods_point=0; 
                }   
                //用户使用红包:1用户红包足够(直接使用商品的可使用红包)2,用户红包不足(按比例分配给可使用红包的商品)
                if($save['pay_red']==1){
                    if($total_pay_red_use>0){
                        if($pay_red_percen==1){ //用户有红包但不足(按比例分配给可使用红包的商品)
                            //红包换算采用百分比
                            $goods_red=$v['flag_pay_red']/$total_pay_red*$total_pay_red_use; //单品总红包和订单总红包的比例  订单可使用的总红包
                        }else{  //用户红包足够(直接使用商品的可使用红包)
                            $goods_red=$v['flag_pay_red'];
                        }  
                    }else{ 
                        //用户无红包
                        $goods_red=0;    
                    } 
                }else{
                    //用户不使用红包
                    $goods_red=0;
                }
                if($goods_red>0){
                    $red_money=$goods_red;
                }else{
                    $red_money=0;
                }
                //积分转换为金额
                if($goods_point>0){
                    $point_amount=$this->__handle_integral($goods_point);
                }else{
                    $point_amount=0;
                }    
                //订单商品表
                $data_bill_goods=array(
                    'bill_id'=>$bill_id,
                    'bill_num'=>$save['bill_num'],
                    'goods_id'=>$v['goods_id'],
                    'goods_price'=>$v['goods_price'],
                    'goods_cnt'=>$v['goods_cnt'],
                    'amount'=>$v['amount'], //商品总价(商品单价*数量)
                    'total_money'=>$v['amount']-$point_amount-$red_money, //商品总价(商品单价*数量)-积分(转换为金额)-红包
                    //'get_point'=>$v['get_point'], //下单者获得积分数
                    'get_point'=>0, //下单者获得积分数
                    'pay_point'=>$goods_point,  //下单者使用积分数
                    'pay_red'=>$red_money, //下单者使用红包数
                    //'get_red'=>$v['get_red'],//下单者获得红包数
                    'get_red'=>0,//下单者获得红包数
                    'goods_name'=>$v['goods_name'],
                    'cover_img'=>$v['cover_img'],
                    'add_time'=>$this->time(),
                    'update_time'=>$this->time()
                );
                $bill_goods_id=$this->manage_bill_goods_model->add($data_bill_goods);
            }
            //用户积分操作
            if($save['pay_point']==1&&$total_pay_point_use>0){
                //减少用户积分
                $this->manage_user_base_model->reduceIntegral($save['uid'],$total_pay_point_use);
                //添加积分使用记录
                $content='订单'.$save['bill_num'].'下单,消耗积分';
                $this->__handle_log_point($save['uid'],$total_pay_point_use,$content,2);
            }
            //用户红包操作
            if($save['pay_red']==1&&$total_pay_red_use>0){
                //减少用户红包
                $this->manage_user_base_model->reduceRed($save['uid'],$total_pay_red_use);
                //添加红包使用记录
                $content='订单'.$save['bill_num'].'下单,消耗红包';
                $this->__handle_log_red($save['uid'],$total_pay_red_use,$content,2);
            }
            //清空购物车
            foreach($car_id_arr as $k=>$v){
                $this->manage_shopping_car_model->delCar($v);
            } 
        }catch(Exception $e){
            $this->manage_bill_model->model_db->trans_rollback();
            $this->manage_bill_goods_model->model_db->trans_rollback();
            $this->manage_user_base_model->model_db->trans_rollback();
            $this->manage_goods_base_model->model_db->trans_rollback();
            $this->manage_shopping_car_model->model_db->trans_rollback();
            throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
        }
        //提交事务
        $this->manage_bill_model->model_db->trans_commit();
        $this->manage_bill_goods_model->model_db->trans_commit();
        $this->manage_user_base_model->model_db->trans_commit();
        $this->manage_goods_base_model->model_db->trans_commit();
        $this->manage_shopping_car_model->model_db->trans_commit();
        $data_res['pay_mode']=$save['pay_mode'];
        $data_res['bill_id']=$bill_id;
        return $data_res;
    }
  
    /**
     * 未支付订单支付
     * @author jieyang 
     */
    public function post_confirm_pay(){
        $data=$this->input->post();
        $rule=array( 
            'bill_id'=>array('egNum',null,true),
            'pay_mode'=>array('in',array(1,2,3),true) //1.余额支付;2.微信支付,3货到付款'   
        );
        $this->verify->verify($rule,$data);
        $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
        //获取订单基本信息
        $bill_info=$this->get_bill_model->getBillCheck($data['bill_id']);
        if($bill_info['state']!=1){
            throw new RJsonErrorException('订单状态有误','BILL_STATE_ERR');
        }
        $this->load->model('v1_0/wechat/Manage_bill_model','manage_bill_model');
        if($data['pay_mode']==3){ //货到付款
            $pay_mode=3;
            //变更订单状态
            $update=array(
                'state'=>2,
                'pay_mode'=>3,
                'pay_time'=>$this->time(),
                'update_time'=>$this->time()
            );
            $this->manage_bill_model->edit($data['bill_id'],$update);  
            $is_pay=true;
        }elseif($data['pay_mode']==1){ //余额支付
            $this->load->model('v1_0/common/Get_user_base_model','get_user_base_model');
            $user_balance=$this->get_user_base_model->getUserBalance($bill_info['uid']);
            if($user_balance<$bill_info['need_pay']){
                throw new RJsonErrorException('账户余额不足','USER_BALANCE_ERR');
            }
            $this->load->model('v1_0/wechat/Manage_user_base_model','manage_user_base_model'); 
            $this->load->model('v1_0/wechat/Manage_bill_model','manage_bill_model');
            $this->manage_bill_model->model_db->trans_begin();
            $this->manage_user_base_model->model_db->trans_begin();
            try{
                //变更订单状态
                $update=array(
                    'state'=>2,
                    'pay_mode'=>1,
                    'pay_time'=>$this->time(),
                    'update_time'=>$this->time()
                );
                $this->manage_bill_model->edit($bill_info['bill_id'],$update);
                $this->manage_user_base_model->reduceBalance($bill_info['uid'],$bill_info['need_pay']);
            }catch(Exception $e){
                $this->manage_bill_model->model_db->trans_rollback();
                $this->manage_user_base_model->model_db->trans_rollback();
                throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
            }
            //提交事务
            $this->manage_bill_model->model_db->trans_commit();
            $this->manage_user_base_model->model_db->trans_commit();
            $is_pay=true;
        }else{
            //微信支付
            $is_pay=false;
        } 
        $this->r['data']['is_pay']=$is_pay;
        $this->r['data']['bill_id']=$data['bill_id'];
        $this->state=true;
        $this->r();
    }
      
    /**
     * 显示支付信息
     * @author jieyang
     */
    public function post_show_pay(){
        $data=$this->input->post();
        $rule=array( 
            'bill_id'=>array('egNum',null,true)  
        );
        $this->verify->verify($rule,$data);
        $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
        //获取订单基本信息
        $bill_info=$this->get_bill_model->getOne($data['bill_id']);
        //订单信息
        $data_res['bill_num']=$bill_info['bill_num'];
        $data_res['need_pay']=$bill_info['need_pay'];
        $this->load->model('v1_0/common/Get_user_base_model','get_user_base_model');
        //用户余额
        $user_balance=$this->get_user_base_model->getUserBalance($this->user['user_id']);  
        $data_res['user_balance']=$user_balance;
        $this->r['data']=$data_res;
        $this->state=true;
        $this->r();  
    }
    
    /**
     * 计算积分抵扣金额
     * @author jieyang
     * $integral 积分数
     * return $amount 抵扣金额(以分为单位)
     */
    private function __handle_integral($point){
        //加载系统配置模型
        $this->load->model('v1_0/common/Get_config_model','get_config_model');
        $config_info=$this->get_config_model->getOne();
        $pay_point=$config_info['pay_point'];  //积分抵扣比例    120/100==> 120积分抵扣100元
        $amount=$point*100/$pay_point*100; //实际可抵扣金额
        return $amount;
    }
      
    /**
     * 获取用户收货地址
     * @author jieyang 
     */
    private function __getUserAddress($data){
        $this->load->model('v1_0/common/Get_user_address_model','get_user_address_model');
        if(empty($data['address_id'])){ //获取默认地址
            try {
                $info=$this->get_user_address_model->getDefaultAddress($data['uid']);
            }catch(ModelErrorException $e){
                throw new RJsonErrorException('默认的地址为空,请选择收货地址','GET_DEFAULT_ADDRESS_ERR');
            }
        }else{  //获取特定地址
            $info=$this->get_user_address_model->getOne($data['uid'],$data['address_id']);
        }
        return $info;
    }
    
    /**
     * 获取订单数据
     * @author jieyang 
     */
    public function get_index(){
        $bill_id=$this->input->get('bill_id');
        if(!empty($bill_id)){
            $this->__getOne();
        }else{
            $this->__getList();
        }
    }

    /**
     * 获取订单列表
     * @author jieyang
     */
    private function __getList(){
        $this->load->library('page');
        $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
        $data=$this->input->get();
        $rule=array(
            //订单状态：-1.已取消;1.待付款2.已付款(待处理)3.待服务(已处理)4.已服务(已完成),5用户已确认收货
            'state'=>array('in',array(-1,1,2,3,4,5),false),
            'type'=>array('in',array(1,2),false)
        );
        $this->verify->verify($rule,$data);
		$data['uid']=$this->user['user_id'];
        $list=$this->get_one_page_data($this->page,$this->get_bill_model,'getList','getListLength',array($data));
        $this->r(array('lists'=>$list,'page'=>$this->page->getPage()));
        $this->state=true;
        $this->r();
    }
     
    /**
     * 获取订单细节
     * @author jieyang
     */
    private function __getOne(){
        $data=$this->input->get();
        $rule=array(
            'bill_id'=>array('egNum',null,true)
        );
        $this->verify->verify($rule,$data);
        $bill_id=$data['bill_id'];
        $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
        //获取订单基本信息
        $bill_info=$this->get_bill_model->getOne($bill_id);
        if($bill_info['pay_point']){ //积分抵扣金额
            $point_money=$this->__handle_integral($bill_info['pay_point']);
        }else{
            $point_money=0;
        }
        $discount=$point_money + $bill_info['pay_red'];
        //订单商品
        $bill_goods=$this->get_bill_model->getBillGoods($bill_id);  
        $goods_total=0;
        foreach($bill_goods as $k=>$v){
            $goods_total+=$v['amount'];
        }
        //用户余额
        $this->load->model('v1_0/common/Get_user_base_model','get_user_base_model');
        $user_balance=$this->get_user_base_model->getUserBalance($bill_info['uid']);
        $this->r['data']['goods_total']=$goods_total;
        $this->r['data']['bill_info']=$bill_info;
        $this->r['data']['bill_goods']=$bill_goods;
        $this->r['data']['point_money']=$point_money;
        $this->r['data']['discount']=$discount;
        $this->r['data']['user_balance']=$user_balance;
        $this->state=true;
        $this->r();
    }    
     
    /**
     * 取消订单
     * @author jieyang
     * 待付款才能取消
     */
    public function put_off_bill(){
        $data=$this->input->post(array('bill_id'));
        $rule=array(
            'bill_id'=>array('egNum',null,true)
        );
        $this->verify->verify($rule,$data);
        $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
        $uid=$this->user['user_id'];
        $bill_check=$this->get_bill_model->getBillCheck($data['bill_id']);
        if($bill_check['uid']!=$uid){
            throw new RJsonErrorException("你无权操作此订单","BILL_OWNERSHIP_FAIL");
        }
        //检测订单状态
        if($bill_check['state']!=1){
            throw new RJsonErrorException("只有待付款才能取消订单","CHANGE_BILL_STATE_FAIL");
        }
        $this->load->model('v1_0/wechat/Manage_bill_model','manage_bill_model');
        $update=array(
            'state'=>-1,
            'update_time'=>$this->time()
        );
        $this->manage_bill_model->edit($data['bill_id'],$update);
        //对订单的积分进行返还
        if(!empty($bill_check['pay_point'])){   
            //增加用户积分
            $this->manage_user_base_model->upIntegral($uid,$bill_check['pay_point']);
            //添加记录
            $content='取消'.$bill_check['bill_num'].'订单,退还积分';
            $this->__handle_log_point($uid,$bill_check['pay_point'],$content,1);       
        }
        //对订单的红包进行返还
        if(!empty($bill_check['pay_red'])){
            //增加用户红包
            $this->manage_user_base_model->upRed($uid,$bill_check['pay_red']);
            //添加记录
            $content='取消'.$bill_check['bill_num'].'订单,退还红包';
            $this->__handle_log_point($uid,$bill_check['pay_red'],$content,1);
        }
        $this->state=true;
        $this->r();
    }
   
    /**
     * 添加用户积分记录
     * @author jieyang
     * $amount 积分数值
     */
    private function __handle_log_point($uid,$amount,$content,$type){
        $this->load->model('v1_0/wechat/Manage_user_integral_model','manage_user_integral_model');
        $this->manage_user_integral_model->model_db->trans_begin();
        try{
            $data=array(
                'uid'=>$uid,
                'amount'=>$amount,
                'content'=>$content,
                'type'=>$type,
                'add_time'=>$this->time()
            );
            $this->manage_user_integral_model->add($data); 
        }catch(Exception $e){
            throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
            $this->manage_user_integral_model->model_db->trans_rollback();
        }
        $this->manage_user_integral_model->model_db->trans_commit();   
    }
    
    /**
     * 添加用户红包记录
     * @author jieyang
     * $amount 积分数值
     */
    private function __handle_log_red($uid,$amount,$content,$type){
        $this->load->model('v1_0/wechat/Manage_user_red_model','manage_user_red_model');
        $this->manage_user_red_model->model_db->trans_begin();
        try{
            $data=array(
                'uid'=>$uid,
                'amount'=>$amount,
                'content'=>$content,
                'type'=>$type,
                'add_time'=>$this->time()
            );
            $this->manage_user_red_model->add($data);
        }catch(Exception $e){
            throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
            $this->manage_user_red_model->model_db->trans_rollback();
        }
        $this->manage_user_red_model->model_db->trans_commit();
    }
        
    /**
     * 添加用户余额记录
     * @author jieyang
     */
    private function __handle_log_capital($uid,$amount,$content,$type){
        $this->load->model('v1_0/wechat/Manage_log_user_capital_model','manage_log_user_capital_model');
        $this->manage_log_user_capital_model->model_db->trans_begin();
        try{
            $data=array(
                'uid'=>$uid,
                'amount'=>$amount,
                'content'=>$content,
                'type'=>$type,
                'add_time'=>$this->time()
            );
            $this->manage_log_user_capital_model->add($data);
        }catch(Exception $e){
            $this->manage_log_user_capital_model->model_db->trans_rollback();
            throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
        }
        $this->manage_log_user_capital_model->model_db->trans_commit();
    }
       
    /**
     * 确认收货
     * @author jieyang
     */
    public function put_confirm_bill(){
        $data=$this->input->post(array('bill_id'));
        $rule=array(
            'bill_id'=>array('egNum',null,true)
        );
        $this->verify->verify($rule,$data);
        $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
        $uid=$this->user['user_id'];
        $bill_check=$this->get_bill_model->getBillCheck($data['bill_id']);
        if($bill_check['uid']!=$uid){
            throw new RJsonErrorException("你无权操作此订单","BILL_OWNERSHIP_FAIL");
        }
        //检测订单状态
        if($bill_check['state']!=4){ // 只有配送员确认后用户才可以确认收货
            throw new RJsonErrorException("订单状态有误","CHANGE_BILL_STATE_FAIL");
        }
        $this->load->model('v1_0/wechat/Manage_bill_model','manage_bill_model');
        $this->load->model('v1_0/wechat/Manage_goods_base_model','manage_goods_base_model');
        $this->load->model('v1_0/wechat/Manage_user_base_model','manage_user_base_model');
        $goods_lists=$this->get_bill_model->getBillGoods($data['bill_id']);
        $this->manage_bill_model->model_db->trans_begin();
        $this->manage_goods_base_model->model_db->trans_begin();
        try{
            //变更订单状态
            $update=array(
                'state'=>5,
                'update_time'=>$this->time(),
                'receipe_time'=>$this->time()
            );
            $this->manage_bill_model->edit($data['bill_id'],$update);
            //判断下单人是否获得积分返回
            if(!empty($data['get_point'])){
                //增加下单人用户积分
                $this->manage_user_base_model->upIntegral($uid,$data['get_point']);
                //增加下单人积分记录
                $content_user='订单'.$data['bill_num'].'完成赠送积分';
                $this->__handle_log_point($uid,$data['get_point'],$content_user,1);
            }
            //判断下单人是否获得红包返回
            if(!empty($data['get_red'])){
                //增加下单人用户红包
                $this->manage_user_base_model->upIntegral($uid,$data['get_point']);
                //增加下单人红包记录
                $content_user='订单'.$data['bill_num'].'完成赠送红包';
                $this->__handle_log_red($uid,$data['get_red'],$content_user,1);
            }
            foreach($goods_lists as $k=>$v){
                //为商品增加销量
                $this->manage_goods_base_model->upSales($v['goods_id'],$v['goods_cnt']);
            }
        }catch(ModelErrorException $e){
            $this->manage_bill_model->model_db->trans_roolback();
            $this->manage_goods_base_model->model_db->trans_rollback();
            throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
        }
        $this->manage_bill_model->model_db->trans_commit();
        $this->manage_goods_base_model->model_db->trans_commit();
        $this->state=true;
        $this->r();
    }
      
    /**
     * 处理订单商品和用户的积分
     * @author jieyang
     * $goods_arr 商品信息 goods_id goods_num sku_id
     * $user_id
     * return data use_point 可使用积分; goods_point 商品积分; user_point 用户积分
     */
    private function __handel_user_point($goods_arr,$user_id){
        $this->load->model('v1_0/common/Get_goods_base_model','get_goods_base_model');
        $this->load->model('v1_0/common/Get_user_base_model','get_user_base_model');
        //获取商品可使用积分
        $total_point=0;
        foreach($goods_arr as $k=>$v){
            if(!empty($v['sku_id'])){
                //sku 参数
                $goods_point=$this->get_goods_base_model->getGoodsSkuPoint($v['goods_id'],$v['sku_id']);
            }else{
                //商品主表
                $goods_point=$this->get_goods_base_model->getGoodsPoint($v['goods_id']);
            }
            //单中商品总积分
            $total_point+=$goods_point*$v['goods_num'];
        }
        //获取用户可使用积分
        $user_integral=$this->get_user_base_model->getUserIntegral($user_id);
        if($user_integral<$total_point){
            $use_point=$user_integral;
        }else{
            $use_point=$total_point;
        }
        $data['use_point']=intval($use_point);
        $data['goods_point']=intval($total_point);
        $data['user_point']=intval($user_integral);
        return $data;
    }
    
    /**
     * 处理订单商品和用户的红包
     * @author jieyang
     * $goods_arr 商品信息 goods_id goods_num sku_id
     * $user_id
     * return data use_point 可使用红包; goods_point 商品红包; user_point 用户红包
     */
    private function __handel_user_red($goods_arr,$user_id){
        $this->load->model('v1_0/common/Get_goods_base_model','get_goods_base_model');
        $this->load->model('v1_0/common/Get_user_base_model','get_user_base_model');
        //获取商品可使用红包
        $total_red=0;
        foreach($goods_arr as $k=>$v){
            if(!empty($v['sku_id'])){
                //sku 参数
                $goods_red=$this->get_goods_base_model->getGoodsSkuRde($v['goods_id'],$v['sku_id']);
            }else{
                //商品主表
                $goods_red=$this->get_goods_base_model->getGoodsRed($v['goods_id']);
            }
            //单中商品总积分
            $total_red+=$goods_red*$v['goods_num'];
        }
        //获取用户可使用积分
        $user_red=$this->get_user_base_model->getUserIntegral($user_id);
        if($user_red<$goods_red){
            $use_red=$user_red;
        }else{
            $use_red=$goods_red;
        }
        $data['use_red']=intval($use_red);
        $data['goods_red']=intval($total_red);
        $data['user_red']=intval($user_red);
        return $data;
    }
    
    /**
     * 处理订单服务费
     * @author jieyang
     * return 以分为单位的金额数
     */
    private function __handel_freight(){
        $this->load->model('v1_0/common/Get_config_model','get_config_model');
        $info=$this->get_config_model->getOne();
        return intval($info['service_charge']);
    }
    
    /**
     * 订单催单
     * @author jieyang
     */
    public function post_bill_reminder(){
        $data=$this->input->post();
        $rule=array(
            'bill_id'=>array('egNum',null,true)
        );
        $this->verify->verify($rule,$data);
        //$save=$this->verify->get_data();
        foreach($rule as $k=>$v){
            isset($data[$k])?$save[$k]=$data[$k]:'';
        }
        $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
        $uid=$this->user['user_id'];
        $bill_check=$this->get_bill_model->getBillCheck($save['bill_id']);
        if($bill_check['uid']!=$uid){
            throw new RJsonErrorException("你无权操作此订单","BILL_OWNERSHIP_FAIL");
        }
        if($bill_check['reminder']==1){
            throw new RJsonErrorException("你的催单已收到,后台已在处理中","BILL_REMAINDE_ALREADY_FAIL");
        }
        //检测订单状态
        if(!($bill_check['state']==2||$bill_check['state']==3)){ // 2待分配配送员;3配送员配送中
            throw new RJsonErrorException("订单状态有误","CHANGE_BILL_STATE_FAIL");
        }
        $this->load->model('v1_0/wechat/Manage_bill_model','manage_bill_model');
        $data_up=array(
            'reminder'=>1,
            'update_time'=>$this->time()
        );
        $this->manage_bill_model->edit($save['bill_id'],$data_up);
        $this->state=true;
        $this->r();
    }
    
    /**
     * 订单售后(具体的订单商品)
     * @author jieyang
     */
    public function post_customer_service(){
        $data=$this->input->post();
        $rule=array(
            'bill_id'=>array('egNum',null,true),
            'bill_goods_id'=>array('egNum',null,true) //订单商品记录id
        );
        $this->verify->verify($rule,$data);
        foreach($data as $k=>$v){
            isset($data[$k])?$save[$k]=$data[$k]:'';
        }     
        //订单
        $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
        $uid=$this->user['user_id'];
        $bill_check=$this->get_bill_model->getBillCheck($data['bill_id']);
        if($bill_check['uid']!=$uid){
            throw new RJsonErrorException("你无权操作此订单","BILL_OWNERSHIP_FAIL");
        }
        //检测订单状态   5.已完成(已收货)
        if(!($bill_check['state']==5||$bill_check['state']==6)) {
            throw new RJsonErrorException("只有已完成或售后中才能提交售后","CHANGE_BILL_STATE_FAIL");
        }
        //检测订单商品状态
        $this->load->model('v1_0/common/Get_bill_goods_model','get_bill_goods_model');
        $bill_goods_state=$this->get_bill_goods_model->getService($data['bill_goods_id']);
        if($bill_goods_state['is_service']!=0){
            throw new RJsonErrorException("此订单商品已提交过售后","CHANGE_BILL_GOODS_STATE_FAIL");
        }  
        $this->load->model('v1_0/wechat/Manage_bill_model','manage_bill_model');
        $this->load->model('v1_0/wechat/Manage_bill_goods_model','manage_bill_goods_model');
        //开始事物
        $this->manage_bill_model->model_db->trans_begin();
        $this->manage_bill_goods_model->model_db->trans_begin();
        try{ 
            //订单主表
            $update=array(
                'state'=>6,
                'update_time'=>$this->time()
            );
            $this->manage_bill_model->edit($data['bill_id'],$update);
            //订单商品表
            $update_bill_goods=array(
                'is_service'=>1,
                'update_time'=>$this->time()
            );
            $this->manage_bill_goods_model->edit($data['bill_goods_id'],$update_bill_goods);
        }catch(ModelErrorException $e){
            $this->manage_bill_model->model_db->trans_rollback();
            $this->manage_bill_goods_model->model_db->trans_rollback();
            throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
        }
        //提交事务
        $this->manage_bill_model->model_db->trans_commit();
        $this->manage_bill_goods_model->model_db->trans_commit();
        $this->state=true;
        $this->r();  
    }
   
    
    
    /***********************   保留功能   ***********************/  
    /**
     * 用户首次下单赠送积分
     * @author jieyang
     */
    private function __firstBill($uid,$bill_id){
        //查询判断是否是首次下单
        $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
        $first_bill=$this->get_bill_model->getUserFirstBill($uid);
        if($bill_id==$first_bill['bill_id']){
            //加载系统配置模型
            $this->load->model('v1_0/common/Get_config_model','get_config_model');
            $config_info=$this->get_config_model->getOne();
            //首次下单赠送积分
            $first_pay_point=$config_info['first_pay_point'];
            $this->load->model('v1_0/wechat/Manage_user_base_model','manage_user_base_model');
            $this->manage_user_base_model->model_db->trans_begin();
            try{
                //增加用户积分
                $this->manage_user_base_model->upIntegral($uid,$first_pay_point);
                //增加用户积分记录
                $content='用户首次下单赠送积分';
                $this->__handle_point($uid,$first_pay_point,$content,1);
            }catch(Exception $e){
                $this->manage_user_base_model->model_db->trans_rollback();
                throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
            }
            $this->manage_user_base_model->model_db->trans_commit();
        }
         
    }
    
    /**
     * 用户首次分享赠送积分
     * @author jieyang
     */
    private function __firstShare($bill_uid,$share_uid,$bill_goods_id){
        $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
        $bill_goods=$this->get_bill_model->getUserShareBillgoodsid($bill_uid,$share_uid);
        if($bill_goods['bill_goods_id']==$bill_goods_id){
            //加载系统配置模型
            $this->load->model('v1_0/common/Get_config_model','get_config_model');
            $config_info=$this->get_config_model->getOne();
            //首次下单赠送积分
            $first_share_point=$config_info['first_share_point'];
            $this->load->model('v1_0/wechat/Manage_user_base_model','manage_user_base_model');
            $this->manage_user_base_model->model_db->trans_begin();
            try{
                //增加用户积分
                $this->manage_user_base_model->upIntegral($share_uid,$first_share_point);
                //增加用户积分记录
                $content='用户首次成功分享下单赠送积分';
                $this->__handle_point($share_uid,$first_share_point,$content,1);
                //增加分享者累计返佣总额
                $this->manage_user_base_model->upCommission($share_uid,$first_share_point);
            }catch(Exception $e){
                $this->manage_user_base_model->model_db->trans_rollback();
                throw new RJsonErrorException($e->getMessage(),$e->getErrorId(),$e->getCode());
            }
            $this->manage_user_base_model->model_db->trans_commit();
        }
    }
     
}
?>