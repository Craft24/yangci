<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_brand_model extends MY_Model{
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
	 * 添加品牌
	 * @author jieyang
	 */
	public function add($data){
	    $info=$this->model_db->insert($this->table,$data);
	    if(!$info){
	        throw new ModelErrorException('添加数据失败','ADD_BRAND_FAIL');
	    }
	    return $info;
	}
	
	/**
	 * 编辑品牌信息
	 * @author jieayng
	 */
	public function edit($brand_id,$data){
	    $info=$this->model_db->where('brand_id',$brand_id)->update($this->table,$data);
	    if(!$info){
	        throw new ModelErrorException('修改数据失败','UPDATE_BRAND_FAIL');
	    }
	    return $info;
	}
	
	/**
	 * 删除品牌
	 * @author jieyang
	 */
	public function delete($brand_id,$data){
	    $info=$this->model_db->where('brand_id',$brand_id)->update($this->table,$data);
	    if(!$info){
	        throw new RJsonErrorException('删除数据失败','DELETE_BRAND_FAIL');
	    }
	    return $info;
	}
	
}
?>