<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Get_admin_model extends MY_Model{
	public $model_db;
	public $table;
	public $cached;
	
	public function __construct(){
		parent::__construct();
		$this->cache_db->init(__CLASS__,__FILE__);
		$this->__db_init();
	}

	private function __db_init(){
		$this->model_db=$this->db;
		$this->table=$this->model_db->dbprefix('admin');	
	}
	
	public function testMc(){  
	    $this->load->driver('cache');  
	    //实例化memcached缓存
	    $this->cache->memcached->is_supported();
	    $key='dd';
	    $data=time();
	    //保存数据
	    $is_success=$this->cache->memcached->save($key,serialize($data),60);
	    //获取数据
	    $str=$this->cache->memcached->get($key);
	    //print_r("dd=".unserialize($str));
	    //删除数据
	    //$this->cache->memcached->delete($key);
	    //print_r($this->cache->memcached->get($key));
	    return $str;
    }
    
	public function getList($begin=0,$page_size=10,$data){
	    $param=array();
	    $is_where='';
	    if(!empty($data['admin_name'])){
	        $is_where.=' AND admin_name LIKE "%'.$this->model_db->escape_like_str($data['admin_name']).'%"';
	    }
	    if(!empty($data['true_name'])){
	        $is_where.=' AND true_name LIKE "%'.$this->model_db->escape_like_str($data['true_name']).'%"';
	    }
	    $sql='
        SELECT
            admin_id,
            admin_name,
            true_name,
            mobile_phone,
	        add_time
        FROM '.$this->table.'
        WHERE 
            is_on = 1';
	    $sql.=$is_where;
	    $sql.=' ORDER BY admin_id ASC';
	    $sql.=' limit '.$begin.','.$page_size;
	    $list=$this->model_db->query($sql,$param)->result_array();
	    return $list;
	}
	
	//获取长度
	public function getListLength($data){
	    $param=array();
	    $is_where='';
	    if(!empty($data['admin_name'])){
	        $is_where.=' AND admin_name LIKE "%'.$this->model_db->escape_like_str($data['admin_name']).'%"';
	    }
	    if(!empty($data['true_name'])){
	        $is_where.=' AND true_name LIKE "%'.$this->model_db->escape_like_str($data['true_name']).'%"';
	    }
	    $sql='SELECT admin_id FROM '.$this->table.' WHERE is_on = 1';
	    $sql.=$is_where;
	    $num=$this->model_db->query($sql,$param)->num_rows();
	    return $num;
	}
	
	public function getOne($admin_id){
	    $sql='
        SELECT
            admin_id,
            admin_name,
            true_name,
            mobile_phone,
            email,
            state,
            add_time
        FROM '.$this->table.'
        WHERE
            is_on = 1 AND
            admin_id = ?';
	    $info=$this->model_db->query($sql,array($admin_id))->row_array();
	    if(!$info){
	        throw new ModelErrorException('数据获取失败','GET_ADMIN_FAIL');
	    }
	    return $info;
	}

	public function getOneBaseByName($admin_name){
		$sql='SELECT admin_id,admin_name,password,salt FROM '.$this->table.' WHERE is_on = 1 AND admin_name = ?';
		$info=$this->model_db->query($sql,array($admin_name))->row_array();
		if(!$info){
			throw new ModelErrorException('获取数据失败','GET_ADMIN_INFO_FAIL');
		}
		return $info;
	}
}