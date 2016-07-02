<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get_author_model extends MY_Model{
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
		$this->table=$this->model_db->dbprefix('author');	
	}
	
	/**
	 * 查询管理员
	 * @author jieyang
	 */
	public function check_user($user,$password){
		$sql='SELECT author_id FROM '.$this->table.' WHERE user = ? AND pass = ?';	
		$info=$this->model_db->query($sql,array($user,$password))->row_array();
		if(!$info){
	        throw new ModelErrorException('获取数据失败','GET_AUTHOR_FAIL');
		}
		return $info;
	}
	
	
}