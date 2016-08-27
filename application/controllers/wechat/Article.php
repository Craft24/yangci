<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 查询文章
 * 知识库
 * @author: 成邦 <577426936@qq.com>
 */ 
class Article extends Wx_Api_1_0_Controller{
  	
  	public function __construct(){
		parent::__construct();
		$this->_auth('session',__CLASS__,TRUE);
   	}

   	/**
     * 查询文章分类列表
     * @author: 成邦 <577426936@qq.com>
     */ 
	public function get_article_category(){
		$this->load->model('v1_0/common/Get_article_category_model','get_article_category_model');
		$this->load->library('page');
        $list=$this->get_one_page_data($this->page,$this->get_article_category_model,'getArticleCategory','getArticleCategoryLength',array());
        foreach ($list as $key => $value){
        	$result=$this->get_article_category_model->getChildArticleCategory($value['cat_id']);
        	if(!empty($result)){
        		$list[$key]['child']=$result;
            } 
        }      
		$this->r(array('lists'=>$list,'page'=>$this->page->getPage()));
        $this->state=true;
        $this->r();
	}

    /**
     * 查询文章详细或列表
     * @author: 成邦 <577426936@qq.com>
     */ 
	public function get_index(){ 
		$data=$this->input->get();
		$rule=array(
			'article_id'=>array('egNum',null,false)
		);
		$this->verify->verify($rule,$data);	
		if(isset($data['article_id'])){
		    $this->__getOne($data['article_id']);
		}else{
		    $this->__getLists();
		}
	}

	/**
     * 查询文章详细
     * @author: 成邦 <577426936@qq.com>
     */ 
	public function __getOne($article_id){
		$this->load->model('v1_0/common/Get_article_category_model','get_article_category_model');
		$this->r['data']=$this->get_article_category_model->getArticleOne($article_id);
		$this->state=true;
		$this->r();
	}

	/**
     * 查询文章列表
     * @author: 成邦 <577426936@qq.com>
     */ 
	public function __getLists(){
		$data=$this->input->get();
		$rule=array(
			'cat_id'=>array('egNum',null),
		);
		$this->verify->verify($rule,$data);	
		$this->load->model('v1_0/common/Get_article_category_model','get_article_category_model');
		$this->load->library('page');
        $list=$this->get_one_page_data($this->page,$this->get_article_category_model,'getList','getListLength',array($data['cat_id']));
		$this->r(array('lists'=>$list,'page'=>$this->page->getPage()));
		$this->state=true;
        $this->r();
	}

}?>