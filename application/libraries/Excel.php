<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * excel文件的导入导出操作  俊
 */
class Excel{
	 
	public function __construct($params = array()){
		$this->CI = & get_instance(); 
        $this->CI->load->library('PHPExcel');
        $this->CI->load->library('PHPExcel/Reader'); 
        
	}


	/**
     * 获取远程excel文件到项目中
     * @author 俊
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
 	 * [get_import_excel description]
 	 * 解析excel文件数据并返回
 	 * @author 俊
 	 * @DateTime 2016-05-03T20:49:59+0800
 	 * @return   [type]                   [description]
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
     * 导出excel
     * table_arr 表头的数组
     * query 表身的数组
     * tile_name 导出的excel名
     *  
     * @DateTime 2016-04-29T21:01:20+0800
     *  
     */
    public function get_export($table_arr, $query,$tile_name){        
        set_time_limit(0); //永不过期  
        if(!$table_arr) { 
            throw new RJsonErrorException('参数table_arr不能为空','TABLE_ARR_NULL');
        }
        if(!$query) {
            throw new RJsonErrorException('参数query不能为空','QUERY_NULL');
        } 
        if(!$tile_name) {
            throw new RJsonErrorException('参数tile_name不能为空','TITLE_NAME_NULL');
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

        //$url='D:/phpstudy/phpweb/yangci'.'/upload/'.$file_name.'.xls';
        $url=FCPATH.'/upload/'.$file_name.'.xls';
        $objWriter->save($url);
        //上传到阿里云并返回url
        //$return_url =  $this->file_upload($file_name,$url);
        //删除本地文件
        //@unlink($url);
        //return $return_url;

        return $url;
    }

    /**
     * 上传阿里云
     * @author: 毅 <2447294105@qq.com>
     * @DateTime 2016-06-17T20:09:44+0800
     * @return   [type]                   [description]
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