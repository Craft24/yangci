<?php defined('BASEPATH') OR exit('No direct script access allowed');

class upload extends MY_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form','file'));
    }
    
    public function get_update_index(){
        $config['upload_path'] =   './upload/';            //这个路径很重要
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1024';
        $config['max_width']  = '1024';
        $config['max_height']  = '768';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if(!$this->upload->do_upload('cs_ap_img')){
            echo $this->upload->display_errors();
        }else{
            $data['upload_data']=$this->upload->data();  //文件的一些信息
            $img=$data['upload_data']['file_name'];           //取得文件名
        }
        $img = $this->upload->file_name;
        var_dump($img);
    }
    
}
?>