<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Get_brand_model extends MY_Model{
	public $model_db;
	public $table;
	
	public function __construct(){
		parent::__construct();
		$this->__db_init();
	}

	private function __db_init(){
		$this->model_db=$this->db;
		$this->table=$this->model_db->dbprefix('brand');
	}
	
	/**
	 * 获取一条品牌详情
	 * @author jieyang
	 */
	public function getOne($brand_id){
	    $sql='SELECT brand_id,brand_name,brand_logo,brand_code,remarks,sort,add_time,update_time FROM '.$this->table.' WHERE is_on = 1 AND brand_id = ?';
	    $info=$this->model_db->query($sql,array($brand_id))->row_array();
	    if(!$info){
	        throw new ModelErrorException('获取数据失败','GET_BRAND_INFO_FAIL');
	    }
	    return $info;
	}
	
	/**
	 * 获取品牌列表
	 * @author jieyang
	 */
	public function getList($begin=0,$page_size=15,$data){
	    $params=array();
	    $is_where='';
	    if(!empty($data['brand_name'])){
	        $is_where.=' AND brand_name LIKE "%'.$this->model_db->escape_like_str($data['brand_name']).' %"';
	    }
	    if(!empty($data['brand_code'])){
	        $is_where.=' AND brand_code LIKE %'.$this->model_db->escape_like_str($data['brand_code']).' %"';
	    }
	    $sql='SELECT brand_id,brand_name,brand_logo,brand_code,remarks,sort,add_time,update_time FROM '.$this->table.' WHERE is_on = 1';
	    $sql.=$is_where;
	    $sql.=' LIMIT '.$begin.', '.$page_size;
	    $list=$this->model_db->query($sql,$params)->result_array();
	    return $list;
	}
	
	/**
	 * 获取品牌列表长度
	 * @author jieyang
	 */
	public function getListLength($data){
	    $params=array();
	    $is_where='';
	    if(!empty($data['brand_name'])){
	        $is_where.=' AND brand_name LIKE "%'.$this->model_db->escape_like_str($data['brand_name']).' %"';
	    }
	    if(!empty($data['brand_code'])){
	        $is_where.=' AND brand_code LIKE %'.$this->model_db->escape_like_str($data['brand_code']).' $"';
	    }
	    $sql='SELECT brand_id FROM '.$this->table.' WHERE is_on = 1';
	    $sql.=$is_where;
	    $num=$this->model_db->query($sql,$params)->num_rows();
	    return $num;
	}
	

}
?>