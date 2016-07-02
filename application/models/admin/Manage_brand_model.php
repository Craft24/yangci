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
	
}
?>