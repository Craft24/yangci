<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 订单评价
 */
class Comment extends Wx_Api_1_0_Controller{
  	
  	public function __construct(){
		parent::__construct();
		$this->_auth('session',__CLASS__,TRUE);
   	}

    /**
     * 评价订单
     * @author: 元翔
     */
    public function post_index(){
        $data=$this->input->post();
        $rule=array(
            'bill_goods_id'=>array('egNum'),
            'goods_id'=>array('egNum'),
            'star_level'=>array('in',array(1,2,3,4,5)),
            'tag_add'=>array(),
            'content'=>array(null,null,false),
            'img'=>array(null,null,false)
        );
        $this->verify->verify($rule,$data);
        foreach($rule as $k=>$v){
            isset($data[$k])?$save[$k]=$data[$k]:'';
        }
        if(mb_strlen($save['content'],'UTF8')>50){
            throw new RJsonErrorException("评价不超过50个字","OVER_COMMENT");
        }
        $save['uid']=$this->user['user_id'];
        // var_dump($save['uid']);exit;
        $save['add_time'] = $this->time();
        //判断订单是否存在
        $this->load->model('v1_0/common/Get_bill_model','get_bill_model');
        $res = $this->get_bill_model->checkBillGoods($save['bill_goods_id'],$save['goods_id'],$save['uid']);
        if (!$res) {
            throw new RJsonErrorException("订单出错","BILL_FAIL");
        }
        $this->load->model('v1_0/common/Get_comment_model','get_comment_model');
        $res = $this->get_comment_model->checkCommentOne($save['bill_goods_id'],$save['uid']);
        if ($res) {
        	throw new RJsonErrorException("订单已评价","BILL_FAIL");
        }
        $this->load->model('v1_0/wechat/Manage_comment_model','manage_comment_model');
        if (!empty($save['tag_add'])) {
            $tag_id=explode(",",$save['tag_add']);
            // var_dump($tag_id);
            unset($save['tag_add']);
            $this->load->model('v1_0/common/Get_comment_tag_model','get_comment_tag_model');
            foreach ($tag_id as $k => $v) {
                $isset_tag = $this->get_comment_tag_model->checkOne($v);
                if (empty($isset_tag)) {
                    unset($tag_id[$k]);
                }
            }
        }
        $this->manage_comment_model->model_db->trans_begin();
        try {
            $comment_id = $this->manage_comment_model->add($save);
            foreach ($tag_id as $v) {
                $datat['tag_id'] = $v;
                $datat['comment_id'] = $comment_id;
                $datat['add_time'] = $this->time();
                // var_dump($datat);exit;
                $this->manage_comment_model->addNewsTagRelation($datat);
            }
        } catch (Exception $e) {
            $this->manage_comment_model->model_db->trans_rollback();
        }
        $this->manage_comment_model->model_db->trans_commit();
        $this->state=true;
        $this->r();
    }

    /**
     * 查询评价
     * @author: 元翔
     */
    public function get_index(){
        $data=$this->input->get();
        $rule=array(
            'goods_id'=>array('egNum'),
            'tag_id'=>array('egNum',null,false)
        );
        $this->verify->verify($rule,$data);
        foreach($rule as $k=>$v){
            isset($data[$k])?$save[$k]=$data[$k]:'';
        }
        $this->load->model('v1_0/common/Get_comment_model','get_comment_model');
        $this->load->library('page');
        $list=$this->get_one_page_data($this->page,$this->get_comment_model,'getList','getListLength',array($save));
        if (!$list) {
            $list = null;
        }else{
            foreach ($list as $k => $v) {
                $a = mb_substr($v['user_name'],0,1,'utf-8');
                $b = mb_substr($v['user_name'],-1,1,'utf-8');
                $list[$k]['user_name'] = $a."***".$b;
                $list[$k]['add_time'] = date('Y/m/d',$v['add_time']);
            }
        }
        //var_dump($list);
        $this->load->model('v1_0/common/Get_comment_tag_model','get_comment_tag_model');
        // $this->load->library('page');
        $lists = $this->get_comment_tag_model->getListGoods($save['goods_id']);
        $num = 0;
        if ($lists) {
            foreach ($lists as $k => $v) {
                $num +=$v['num'];
            }
        }
        $count['comment_num']= $num;
        $this->state=true;
        $this->r(['lists'=>$list,'data'=>$count,'page'=>$this->page->getPage()]);
    }

    /**
     * 查询标签
     * @author: 元翔
     */
    public function get_tag(){
    	$data=$this->input->get();
        $rule=array(
            'goods_id'=>array('egNum')
        );
        $this->verify->verify($rule,$data);
        $goods_id = $this->input->get('goods_id');
    	$this->load->model('v1_0/common/Get_comment_tag_model','get_comment_tag_model');
    	// $this->load->library('page');
        $list = $this->get_comment_tag_model->getListGoods($goods_id);
        $num = 0;
        if ($list) {
            foreach ($list as $k => $v) {
                $num +=$v['num'];
            }
        }
        $list_all = $this->get_comment_tag_model->getAll();
        // var_dump($list);
        // var_dump($list_all);
        // if ($list_all) {
        //     foreach ($list_all as $tag_key => $tag_v) {
        //         if ($list) {
        //             foreach ($list as $c => $d) {
        //                 //var_dump($d);exit;
        //                 if ($list[$c]['tag_name'] == $tag_v['tag_name'] ) {
        //                     //var_dump($d['num'],$d['tag_name'],11111,$list[$c]['tag_name']);
        //                     $list_all[$tag_key]['num'] = $d['num'];
        //                     var_dump($list_all,111111111);
        //                 }else{
        //                     $list_all[$tag_key]['num'] = 0;
        //                 }
        //                 unset($list[$c]);
        //             }
        //         }else{
        //             $list_all[$tag_key]['num'] = 0;
        //         }
        //     }
        // }else{
        //     $list_all = null;
        // }
        if (!empty($list) && !empty($list_all)) {
            //tag_name相同则赋值相应的num
            foreach ($list as $c => $d) {
                foreach ($list_all as $k => $v) {
                    if ($list[$c]['tag_name'] == $v['tag_name'] ) {
                        //var_dump($d['num'],$d['tag_name'],11111,$list[$c]['tag_name']);
                        $list_all[$k]['num'] = $d['num'];
                    }
                }
            }
            //再次处理没有数量的标签（赋值为0）
            foreach ($list_all as $key => $value) {
                if (empty($value['num'])) {
                    $list_all[$key]['num'] = 0;
                }
            }
        }elseif(empty($list) && !empty($list_all)){
            foreach ($list_all as $k => $v) {
                $list_all[$k]['num'] = 0;
            }
        }else{
            $list_all = null;
        }

	  	// $list=$this->get_one_page_data($this->page,$this->get_comment_tag_model,'getListGoods','getListGoodsLength',array($goods_id));
	  	//var_dump($list);
        $count['comment_num']= $num;
	    $this->state=true;
	    $this->r(['lists'=>$list_all,'data'=>$count]);
    }
}