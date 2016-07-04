<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Get_article_model extends MY_Model{
	public $model_db;
	public $table;
	
	public function __construct(){
		parent::__construct();
		$this->__db_init();
	}

	private function __db_init(){
		$this->model_db=$this->db;
		$this->table=$this->model_db->dbprefix('article');
	}

}
?>