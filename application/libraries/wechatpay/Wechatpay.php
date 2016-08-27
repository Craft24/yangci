<?php
require_once 'lib/WxPayApi.php';

class Wechatpay{

    /**
     * 统一下单接口
     * @author: 亮 <chenjialiang@han-zi.cn>
     * @param $body string 描述
     * @param $attach string 附加消息
     * @param $out_trade_no int 商户订单号
     * @param $total_fee int 订单金额
     * @param $notify_url string 支付回调地址
     * @param $time_start string 订单开始时间
     * @param $time_expire string 订单过期时间
     */
    public function get_pay_sign($openid,$body,$attach,$out_trade_no,$total_fee,$notify_url,$time_start=0,$time_expire=0){
        $wxpay = new WxPayApi();
        //统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetOpenid($openid);
        $input->SetBody($body);//设置商品或支付单简要描述
        $input->SetOut_trade_no($out_trade_no);#设置单号
        $input->SetTotal_fee($total_fee);#设置支付金额
        $input->SetAttach($attach);//自定义的参数

        $input->SetNotify_url($notify_url);#回调地址
        $input->SetTrade_type('JSAPI');#接口

        if($time_start!==0){
            $input->SetTime_start($time_start);         //交易起始时间
        }

        if($time_expire!==0){
            $input->SetTime_expire($time_expire);       //交易结束时间
        }

        $order = Wxpayapi::unifiedOrder($input);#生成订单

        if(empty($order['return_code'])||$order['return_code']!=='SUCCESS'){
            throw new LibrariesErrorException('微信错误,'.$order['return_msg'],'WECHAT_ERROR');
        }

        if(empty($order['result_code'])||$order['result_code']!=='SUCCESS'){
            throw new LibrariesErrorException('生成微信单号失败,'.$order['return_msg'],'WECHAT_ERROR');
        };

        if(!array_key_exists("appid", $order)|| !array_key_exists("prepay_id", $order)|| $order['prepay_id'] == ""){
            throw new LibrariesErrorException("参数错误",'PARAMS_ERROR');
        }

        $jsObj=new WxPayJsApiPay();
        $jsObj->SetAppid($order["appid"]);
        $timeStamp = time();
        $jsObj->SetTimeStamp("$timeStamp");
        $jsObj->SetNonceStr(WxPayApi::getNonceStr());
        $jsObj->SetPackage("prepay_id=" . $order['prepay_id']);
        $jsObj->SetSignType("MD5");
        $jsObj->SetPaySign($jsObj->MakeSign());
        $jsApiParameters = json_encode($jsObj->GetValues());
        log_message('error',$jsApiParameters);
        if (!$jsApiParameters) {
            throw new LibrariesErrorException('微信接口对接失败','WECHAT_ERROR');
        }

        return $jsApiParameters;
    }

    /**
     * 支付回调通知
     * @author: 亮 <chenjialiang@han-zi.cn>
     */
    public function notify($callback,$needSign = true){
        $replyObj=new WxPayNotifyReply();
        $wxpay = new WxPayApi();
        //echo 'ok';
        log_message('error','收到支付回调');

        $msg = "OK";
        //当返回false的时候，表示notify中调用NotifyCallBack回调失败获取签名校验失败，此时直接回复失败
        $get_data = WxpayApi::notify($msg);

        $result=$this->NotifyCallBack($callback,$replyObj,$get_data);

        if($result == false){
            $replyObj->SetReturn_code("FAIL");
            $replyObj->SetReturn_msg($msg);
            $this->ReplyNotify($replyObj,false);
            return;
        } else {
            //该分支在成功回调到NotifyCallBack方法，处理完成之后流程
            $replyObj->SetReturn_code("SUCCESS");
            $replyObj->SetReturn_msg("OK");
        }
        $this->ReplyNotify($replyObj,$needSign);
    }


    /**
     *
     * notify回调方法，该方法中需要赋值需要输出的参数,不可重写
     * @param $replyObj WxPayNotifyReply 对象
     * @param array $data
     * @return true 回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    private function NotifyCallBack($function,$replyObj,$data)
    {
        $msg = "OK";

        $result = call_user_func($function,$data);

        if($result == true){
            $replyObj->SetReturn_code("SUCCESS");
            $replyObj->SetReturn_msg("OK");
        } else {
            $replyObj->SetReturn_code("FAIL");
            $replyObj->SetReturn_msg($msg);
        }
        return $result;
    }

    /**
     *
     * 回复通知
     * @param $replyObj WxPayNotifyReply 对象
     * @param bool $needSign 是否需要签名输出
     */
    private function ReplyNotify($replyObj,$needSign = true)
    {
        //如果需要签名
        if($needSign == true &&
            $replyObj->GetReturn_code(/*$return_code*/) == "SUCCESS")
        {
            $replyObj->SetSign();
        }
        WxpayApi::replyNotify($replyObj->ToXml());
    }

    /**
     * 企业支付
     * @author: 亮 <chenjialiang@han-zi.cn>
     * @param $openid string 用户openid
     * @param $amount int 金额(分)
     * @param $out_trade_no int 商户订单号
     * @param $desc string 订单描述
     * @param $re_user_name string 用户实名名称,传入则验证
     */
    public function enterprise_pay($openid,$amount,$out_trade_no,$desc,$re_user_name=null){
        $wxpay = new WxPayApi();
        //企业付款
        $input = new WxPayEnterprise();
        $input->SetOpenid($openid);
        $input->SetAmount($amount);//设置商品或支付单简要描述
        $input->SetTradeNo($out_trade_no);#设置单号
        $input->SetDesc($desc);#设置支付金额

        if($re_user_name!==null){
            $input->SetCheckName('FORCE_CHECK');
            $input->SetReUserName($re_user_name);//自定义的参数
        }

        return WxPayApi::enterprisePay($input);
    }



}





