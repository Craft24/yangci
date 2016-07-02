<?php
/**
 * EachERP
 * EachERP是开源软件,基于PHP 5.1.6 以上版本和CodeIgniter 3.0框架
 * @软件包	EachERP
 * @授权		http://EachERP.net/user_guide/license.html
 * @链接		http://EachERP.net
 * @版本	    2.0

 * 版权所有(C) 2015 作者:陈国彤
本程序为自由软件；您可依据自由软件基金会所发表的GNU 通用公共授权条款，对本程序再次发布和/ 或修改；无论您依据的是本授权的第三版，或（您可选的）任一日后发行的版本。
本程序是基于使用目的而加以发布，然而不负任何担保责任；亦无对适售性或特定目的适用性所为的默示性担保。详情请参照GNU 通用公共授权。
您应已收到附随于本程序的GNU 通用公共授权的副本；如果没有，请参照<http://www.gnu.org/licenses/>.
 * backup.php 备份功能主视图
 *
 * @category	welcome 
 * @源代码
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ERP系统</title>
</head>
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/Rightgrid.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
 var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
	var passing;
	var search_string;var page_string;var string;
    var error='';
    var clickedId=null;var clicked_line_index=null;
	var base_url='<?php echo base_url();?>';
 $(function(){
	   
	   $("#head").makemenu2(base_url);////顶
	   $("#act1").click(function(){
	                              
	                              $.post(url,data,function(result){
	                              
								  
								                                   });
								  });
								  
	   $("#form12").submit(function(){return false;});
		                                 						  
	    //////////////选择备份
	    $("#source").focus(function(){
		                      
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							    cjTable_light5('selection','../settings/beifen_list?dates='+Math.floor(Math.random()*999+1),////url of data source
								 '序号,备份列表',///////表格标题
								 '300px','',//要隐藏的字段
								 'yes','#source,1'  ); ////是否不要标题行
							  
							  
						   });	
	   
	   
	   });
</script>
</head>	   
<body>
<div id=container>
<div id=head></div>
<div id=main><h2>备份</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		
<form name="form1" method="post" action="backup_act">
    
    <p><input type="submit" name="act" value="备份">
</form>
备份好的文件有两个，一个留在服务器上，一个会自动下载以保留在你的客户端电脑上。
<table width="99%" border="1" cellpadding='0' cellspacing='0'>
      <tr align="center" class='header'><td width="100%"></td></tr>
    </table>
<h2>恢复备份</h2>
	<form name="form2" method="post" action="restore">
    <div><input type=text id=source name=source placeholder="点击选择备份" /></div>
    <p><input type="submit" name="act" value="回滚到此备份">
</form>


</div>



</div ></div></div>

</body>
</html>
