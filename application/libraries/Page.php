<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 分页类
*/
class Page {
	private $c = array() ;
	public function __construct($params = array())
	{
		$this->init($params);
	}

	public function init($params = array()){
		(isset($this->c)&&is_array($this->c)) || ($this->c = array());
		(isset($c)&&is_array($c)) || ($c = array());
		$this->C(array(
			//当前页数
			'now'=>1,
			//数据库数据总条数
			'count'=>0,
			//每页显示条数
			'size'=>10,
			//最后一页是第几页
			'end'=>10,
			//上一页页数是
			'before'=>1,
			//下一页页数是
			'after'=>1,
			//默认页数列条数
			'lists_size'=>10,
			//页数列表
			'lists'=>array(),
			//输入的页数
			'input_page'=>1,
			//是否到达尾页
			'is_end'=>false,
			//是否为传入的页数
			'is_input_page'=>true,
			//limit开始位置
			'limit_start'=>0
		));
		$this->C($params);
		$this->__setup();
		return $this;
	}
	private function __setup(){
		$c = $this->C();
		$c['now'] = intval($c['now']);
		$c['input_page'] = $c['now'];
		$c['count'] = intval($c['count']);
		$c['size'] = intval($c['size']);
		if ($c['now']<1) {
			$c['now'] = 1 ;
		}
		if ($c['count']<1) {
			$c['count'] = 0 ;
		}
		if ($c['size']<1) {
			$c['size'] = 10 ;
		}
		$c['end'] = intval(ceil($c['count']/$c['size']));
		if ($c['now']>$c['end']) {
			$c['now'] = $c['end'] ;
		}
		$c['limit_start'] = ( abs(( $c['now'] - 1 )) * $c['size'] ) ;

		//重新计算上一页
		$c['before'] = $c['now'] - 1 ;
		$c['before'] = ( $c['before'] < 1 ) ? $c['now'] : $c['before'] ;
		//重新计算下一页
		$c['after'] = $c['now'] + 1 ;
		$c['after'] = ( $c['after'] > $c['end'] ) ? $c['end'] : $c['after'] ;
		$c['lists'] = array();
		//统计当前页前
		$i = intval($c['now']) ;
		$ilen = $i-intval($c['lists_size']/2) ;
		for ($i = intval($i) ; $i >= $ilen ; $i--) {
			if ($i>=1) {
				array_unshift($c['lists'],$i);
			};
		};
		//补充当前页后
		$i = intval($c['now']) + 1 ;
		$ilen = $i + intval(intval($c['lists_size'])-intval(count($c['lists'])));
		for ($i = intval($i); $i < $ilen; $i++) {
			if ($i<=$c['end']) {
				$c['lists'][] = $i;
			};
		};
		//向前边补充差额
		if (isset($c['lists'][0])){
			$i = $c['lists'][0] ;
			$ilen = $i - intval(intval($c['lists_size'])-intval(count($c['lists'])));
			for ($i = intval($i) ; $i > $ilen ; $i--) {
				if ($i>=1) {
					array_unshift($c['lists'],$i);
				};
			};
		};
		$c['lists'] = array_unique($c['lists']);

		//判断是否到了尾页
		$c['is_end'] = (bool)($c['now'] == $c['end']) ;
		//判断是否为传入输入的页数
		$c['is_input_page'] = (bool)($c['input_page'] == $c['now']) ;
		$this->C($c);
	}
	public function getLimit(){
		$c = &$this->C();
		$r = array($c['limit_start'],$c['size']);
		return $r ;
	}
	public function getPage(){
		return $this->C();
	}


	/*
	C('name','value');储存
	C('name.name1.name2','value');储存
	C('name.name1.name2');读取
	C(array('a'=>2));存储
	*/
	private function &C(){
		$r = NULL ;
		$c = &$this->c ;
		$c_tmp_1 = '';
		$c_tmp_2 = '';
		$i = 0 ;
		$c_names = array();
		$c_names_len = 0 ;
		$num = func_num_args() ;
		$args = func_get_args() ;
		if($num==0){
			$r = $c ;
		}elseif ($num==1&&isset($args[0])&&is_array($args[0])) {
			$c = array_merge($c,$args[0]) ;
			$r = TRUE ;
		}elseif (($num==1||$num==2)&&isset($args[0])&&is_string($args[0])) {
			$c_names = explode('.',$args[0]);
			$c_names_len = count($c_names) ;
			$c_tmp_1 = &$c ;
			for ($i=0; $i < $c_names_len ; $i++) {
				if(!empty($c_names[$i])){
					$c_tmp_2='';
					unset( $c_tmp_2 ) ;
					if (isset($c_tmp_1[$c_names[$i]])) {
						$c_tmp_2 = &$c_tmp_1[$c_names[$i]] ;
						unset($c_tmp_1) ;
						$c_tmp_1 = &$c_tmp_2 ;
						$r =  $c_tmp_1;
					}elseif ($num==2) {
						$c_tmp_1[$c_names[$i]] = array() ;
						$c_tmp_2 = &$c_tmp_1[$c_names[$i]] ;
						unset($c_tmp_1) ;
						$c_tmp_1 = &$c_tmp_2 ;
					}else{
						$r = NULL ;
					}
					unset( $c_tmp_2 );
					$c_tmp_2='';
				}
			}
			if ($num==2) {
				$c_tmp_1 = $args[1] ;
			}
		}
		unset($c) ;unset($num) ;unset($args) ;
		return $r ;
	}
}
?>