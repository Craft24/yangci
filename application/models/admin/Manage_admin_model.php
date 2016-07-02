<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_admin_model extends MY_Model{
	//数据库操作对象
	public $model_db;
	//数据库表名
	public $table;
	
	//自动执行
	public function __construct(){
		parent::__construct();
		//初始化数据库
		$this->__db_init();
	}

	/**
	 * 初始化数据库
	 */
	private function __db_init(){
		$this->model_db=$this->db;
		$this->table=$this->model_db->dbprefix('admin');	
	}
	
	/**
	 * 删除管理员
	 * @author jieyang
	 */
    public function delete($admin_id,$data){
         $info=$this->model_db->where('admin_id',$admin_id)->update($this->table,$data);
         if($info){
              throw new ModelErrorException('删除数据失败','DELETE_ADMIN_FAIL');
         }
         //var_dump($this->db->last_query());
         return $info;
    }	
    
}