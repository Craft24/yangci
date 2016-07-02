<?php
/**
 * EachERP
 * EachERP是开源软件,基于PHP 5.1.6 以上版本, CodeIgniter 3.0框架, Jquery1.9
 * @软件包	EachERP
 * @授权		http://EachERP.net/user_guide/license.html
 * @链接		http://EachERP.net
 * @版本	    2.0

 * 版权所有(C) 2015 作者:陈国彤
本程序为自由软件；您可依据自由软件基金会所发表的GNU 通用公共授权条款，对本程序再次发布和/ 或修改；无论您依据的是本授权的第三版，或（您可选的）任一日后发行的版本。
本程序是基于使用目的而加以发布，然而不负任何担保责任；亦无对适售性或特定目的适用性所为的默示性担保。详情请参照GNU 通用公共授权。
您应已收到附随于本程序的GNU 通用公共授权的副本；如果没有，请参照<http://www.gnu.org/licenses/>.
 * download.php 文档中心的下载文件视图 
 * @category oa
 * @源代码
 */
$this->load->helper('download');
$name = $this->input->get_post('wenjian',true);
$data = file_get_contents("./uploads/files/".$name);
force_download($name, $data);

?>