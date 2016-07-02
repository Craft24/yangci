<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 商品数据操作
 */
class Manage_admin_model extends MY_Model{
	
	public $model_db;
	public $table;
	
	public function __construct(){
		parent::__construct();
		$this->__db_init();
	}

?>