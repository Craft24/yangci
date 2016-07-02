<?php 

class Reader
{
	//读取文件 jun
	public function get_reader($filename){

		//header("Content-Type:text/html;charset=utf-8");
		$dir=dirname(__FILE__);//找到当前脚本所在路径

		//require $dir."/PHPExcel/IOFactory.php";//引入读取excel的类文件
		require $dir."/IOFactory.php";//引入读取excel的类文件
		
		//$filename=$dir.$filename; 
		$filename=$filename; 

		$fileType=IOFactory::identify($filename);//自动获取文件的类型提供给phpexcel用
		$objReader=IOFactory::createReader($fileType);//获取文件读取操作对象
		 
		$objPHPExcel=$objReader->load($filename);//加载文件

		$sheetName=$objPHPExcel->getSheet(0)->toArray(); //默认拿第一个sheet
		//var_dump($sheetName); //获取到sheet的所有数据 
		// exit();
		return $sheetName;  //返回对象 

	}

	
}


  
?>