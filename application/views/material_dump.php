<?php
/**
 * EachERP
 * EachERP是开源软件,基于PHP 5.1.6 以上版本, CodeIgniter 2.0框架, Jquery1.9
 * @软件包	EachERP
 * @授权		http://EachERP.net/user_guide/license.html
 * @链接		http://EachERP.net
 * @版本	    0.1beta

 * 版权所有(C) 2015 作者:陈国彤
本程序为自由软件；您可依据自由软件基金会所发表的GNU 通用公共授权条款，对本程序再次发布和/ 或修改；无论您依据的是本授权的第三版，或（您可选的）任一日后发行的版本。
本程序是基于使用目的而加以发布，然而不负任何担保责任；亦无对适售性或特定目的适用性所为的默示性担保。详情请参照GNU 通用公共授权。
您应已收到附随于本程序的GNU 通用公共授权的副本；如果没有，请参照<http://www.gnu.org/licenses/>.
 * material_dump 材料列表的主视图文件
 * @category welcome
 * @源代码
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>物料管理系统</title>
</head>
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/Rightgrid.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
	var mainurl="";
    var search_string;var page_string;var string;
	var clickedId=null;
	var base_url='<?php echo base_url();?>';

	$(function(){
	   //////////////////////
	   $("#head").makemenu2(base_url);////顶部菜单
       $("#add_hidden").show();            
	            })  
</script>
<body>
<div id=container>
<div id=head>


</div>
<div id=main>
<h2>物料导入</h2>

    <div id=main_left>
	
			
	<div id=grid>

		</div>

 
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		           <br />   
			      <div class="table_margin">
				        
                        <?php echo form_open_multipart('settings/do_upload');?>
						<!--<form id=form_submit action="do_upload" target="_blank" method="post" accept-charset="utf-8" enctype="multipart/form-data">-->

						<table class=table_update>
						<tr height=35><td>文件:</td><td><input id=userfile type="file" class=userfile name="userfile" size="20" />  
						                               
						<a target="_blank" href="../../help/help.php#material_dump"><img src="../../img/help.gif" height="20/"></a>
						</td></tr>
						
						<tr height=35><td colspan=3><input type="submit" value="导入" /></td></tr>
						</table>
						</form>
						<iframe  name="iframe_upload" style="border:0;width:0px;height:50px;"></iframe>
				  </div>
		</div>
      <!--用于添加的pop up--结束 -->
	</div>
	
	<div id=main_right>
	</div>
</div>


<div id=tipok class=tipok>
<img src=../../img/tick.jpg width=80 />

</div>
<div id=tipnote class=tipnote>
<div align=center>
<img src=../../img/note.jpg width=80 />
</div>
<div id=tipnote_word align=center></div>
</div>
</div>
<?php include "foot.html" ?>

</body>
</html>
<?php
/*  material_dump.php文件的结尾 */
/*  在系统中的位置: ./application/views/material_dump.php */
?>