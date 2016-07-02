<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
	public $data = array();
	public $msg = '' ;
	public $state = false ;
	public $code = '0' ;
	protected $cache_db ;  
	public function __construct()
	{
		parent::__construct();
		$this->__cache_init();
	}
	private function __cache_init(){
		$this->cache_db = new CacheDb();
	}
}
/**
* 
*/
class CacheDb
{
	private $prefix = 'default:';
	private static $app_path_len = 0;
	private static $model_len = 0;
	private static $default_ttl = 2;
	private $is_cache = true;
	public $CI ;
	public function __construct()
	{
		$this->CI =& get_instance();
		self::$app_path_len = strlen(APPPATH) ;
		self::$model_len = -strlen('_model') ;
		if(isset($_GET['nocache'])&&(((string)$_GET['nocache'])==='true'||$_GET['nocache']===true)){
			$this->is_cache = false;
		}elseif(isset($POST['nocache'])&&(((string)$POST['nocache'])==='true'||$POST['nocache']===true)){
			$this->is_cache = false;
		}
	}
	public function init($class='',$file='')
	{
		$file = substr(dirname($file),self::$app_path_len);
		$this->prefix = 'db:'.substr(md5($file.':'.$class.':'),8,16).':'.substr($class,0,self::$model_len).':';
	}
	/*该方法用于从缓存中获取一项条目，如果获取的条目不存在，方法返回 FALSE 。*/
	public function get($id=null)
	{
		if ($this->is_cache) {
			return $this->CI->cache->memcached->get($this->prefix.$id);
		}else{
			return false ;
		}
	}
	/*该方法用于将一项条目保存到缓存中，如果保存失败，方法返回 FALSE 。*/
	public function save($id=null,$value=null, $ttl = null)
	{
		$ttl = is_null($ttl)?self::$default_ttl:$ttl;
		//如果true就不设定到期时间
		if ($ttl===true) {
			return $this->CI->cache->memcached->save($this->prefix.$id, $value);
		}else{
			return $this->CI->cache->memcached->save($this->prefix.$id, $value, $ttl);
		}
	}
	/*该方法用于从缓存中删除一项指定条目，如果删除失败，方法返回 FALSE 。*/
	public function delete($id=null)
	{
		return $this->CI->cache->memcached->delete($this->prefix.$id);
	}
}
?>