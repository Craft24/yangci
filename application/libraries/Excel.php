<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * excel文件的导入导出操作
 */
class Excel{
	 
	public function __construct($params = array()){
		$this->CI = & get_instance(); 
        $this->CI->load->library('PHPExcel');
        $this->CI->load->library('PHPExcel/Reader'); 
	}

    /**
     * 导出excel
     * table_arr 表头的数组
     * query 表身的数组
     * tile_name 导出的excel名  
     */
    public function get_export($table_title,$table_content,$tile_name){        
        set_time_limit(0); //永不过期  
        if(!$table_title) { 
            throw new RJsonErrorException('参数table_title不能为空','TABLE_TITLE_NULL');
        }
        if(!$table_content) {
            throw new RJsonErrorException('参数table_content不能为空','TABLE_CONTENT_NULL');
        } 
        if(!$tile_name) {
            throw new RJsonErrorException('参数title_name不能为空','TITLE_NAME_NULL');
        }
        // Starting the PHPExcel library  
        $this->CI->load->library('PHPExcel/IOFactory');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

        //设置表头
        //参数1是列，从0开始
        foreach ($table_arr as $k => $v) {
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($k, 1,$v);
        }        
        //设置表身
        $row = 2;
        foreach ($query as $k=> $v) {
             $col = 0;
             foreach ($v as $e => $f) {
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $f);
                 $col++;
             }
             $row++; 
        }    
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
        //生成文件名称
        $file_name =date("Y-m-dH-i-s");
        //保存文件
        $url=FCPATH.'/upload/'.$file_name.'.xls';
        $objWriter->save($url);
        //上传到阿里云并返回url
        //$return_url =  $this->file_upload($file_name,$url);
        //删除本地文件
        //@unlink($url);
        return $url;
    }

    /**
     * @param array $table_title 数据表头
     * @param array $table_content 数据表内容
     * @param string $tile_name 数据表名称
     */
    public function get_export_child($table_title,$table_content,$tile_name){
        set_time_limit(0); //永不过期
        if(!$table_title) {
            throw new RJsonErrorException('参数表头字段不能为空','TABLE_TITLE_NULL');
        }
        if(!$table_content) {
            throw new RJsonErrorException('参数数据内容不能为空','TABLE_CONTENT_NULL');
        }
        if(!$tile_name) {
            throw new RJsonErrorException('参数tile_name不能为空','TABLE_NAME_NULL');
        }
        // Starting the PHPExcel library
        $this->CI->load->library('PHPExcel/IOFactory');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30); //设置订单编号列宽度
        //设置文字对齐
        $objPHPExcel->setActiveSheetIndex()->getDefaultStyle()
        ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->setActiveSheetIndex()->getDefaultStyle()
        ->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置表头
        //参数1是列，从0开始
        foreach ($table_title as $k => $v) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($k, 1,$v);
        }
        //设置表身
        $row = 2;
        foreach ($table_content as $k=> $v) { //$v 每一条记录
            $row_start = $row;
            $col = 0;
            foreach ($v as $e => $f) { //$f 每一个字段
                if($v['child']&&$col==count($v)-1){  //子列表放在最后一个字段
                    foreach($v['child'] as $x=>$y){  //子列表中的每一条记录
                        foreach($y as $l=>$j){ //子列表中的每条记录的每个字段
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $j);
                            if($col==(count($v)+count($y)-2)){ //子列表最后一个(父列表长度+子列表长度)
                                $col=count($v)-1; //父列表长度(去除子列表字段)  
                                if($x!=count($v['child'])-1){ //最后一条子记录
                                    $row++;
                                    //合并单元格
                                    $objPHPExcel->getActiveSheet()->mergeCells('A'.$row_start.':A'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('B'.$row_start.':B'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('C'.$row_start.':C'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('D'.$row_start.':D'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('E'.$row_start.':E'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('F'.$row_start.':F'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('G'.$row_start.':G'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('H'.$row_start.':H'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('I'.$row_start.':I'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('J'.$row_start.':J'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('K'.$row_start.':K'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('L'.$row_start.':L'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('M'.$row_start.':M'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('N'.$row_start.':N'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('O'.$row_start.':O'.$row);
                                    $objPHPExcel->getActiveSheet()->mergeCells('P'.$row_start.':P'.$row);
                                } 
                            }else{
                                $col++;
                            } 
                        }   
                    } 
                }else{
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $f);
                    $col++;
                }
            }
            $row++;
        }
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
        //生成文件名称
        $file_name =date("Y-m-dH-i-s");

         //保存文件
        $url=FCPATH.'/upload/'.$file_name.'.xls';
        $objWriter->save($url);
        //上传到阿里云并返回url
        //$return_url =  $this->file_upload($file_name,$url);
        //删除本地文件
        //@unlink($url);
        return $url;
    }

	/** 
     * 获取远程服务器文件
     * $url 远程存储地址
     * $save_dir 保存地址 
     * $filename 保存文件名  ==> example.xls
     */
    public function get_down_excel($url,$save_dir,$filename){

        if(!$url) {
            throw new RJsonErrorException('参数url不能为空','URL_NULL');
        }
        if(!$save_dir) {
            throw new RJsonErrorException('参数save_dir不能为空','SAVE_DIR_NULL');
        } 
        if(!$filename) {
            throw new RJsonErrorException('参数filename不能为空','FILENAME_NULL');
        } 
        //获取远程文件所采用的方法
        $ch=curl_init();
        $timeout=5;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $content=curl_exec($ch);
        curl_close($ch);
        file_put_contents($save_dir.$filename, $content);
        unset($content,$url); 
        $result_arr= $this->__getImport($save_dir.$filename);
        return $result_arr;
    }

	/**
 	 * 解析excel文件数据并返回
 	 */
    private function __getImport($file_url){  
        if(!$file_url) {
            throw new RJsonErrorException('参数file_url不能为空','FILE_URL_NULL');
        } 
	    $Reader=new Reader(); 
	    $result= $Reader->get_reader($file_url);
        //删除本地文件
        @unlink($file_url);
	    return $result;
    }

    /**
     * 上传远程服务器
     */
    protected function file_upload($file_name,$url){
        //提取阿里云的配置信息
        $this->CI->config->load('aliyun', TRUE);
        $aliyun_config = $this->CI->config->item('oss','aliyun');
        $object = 'admin/excel/'.$file_name.'.xls';
        //引入阿里云操作类库
        $this->CI->load->library('aliyun/OSS/Oss_client.php', $aliyun_config, 'oss_client');
        try{
            $this->CI->oss_client->uploadFile($aliyun_config['bucket'], $object, $url);
        } catch(OssException $e){
            throw new RJsonErrorException("上传错误",'UPLOAD_FILE_FAIL');
        }
        
        //返回上传后的文件地址
        $return_url = $aliyun_config['file_url'].'/'.$object;

        return $return_url;
    }
    
}