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
 * Installer    安装页面
 *
 * @category	settings 
 * @源代码
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EachErp管理系统</title>
<style type="text/css">
<!--
.STYLE1 {
	color: #990000;
	font-weight: bold;
}
-->
</style>
</head>
<script src="../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../cjquery/src/Rightgrid.js" type="text/javascript"></script>
<link href="../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
	var passing;var clickedId=null;
	var search_string;var page_string;var string;
    $(function(){                         				
	
	   //$("#head").makemenu2();////顶部菜单
	   $("#add_hidden").show();
      ////////////add new handling
	   $("#form_button_adds").click(
	                      function (){
						             ///////验证表单规则用||号区分
									 //var data=$("#form_add").serialize();alert(data);
									 var error='';
									 if (error==''){
									 $.get("install_right",function(result){
									                                      if (result=='yes') note("安装成功");                              
									  
									                                     });
	                                                                  
	                                }else{  note(erro);}
									}
	                          );  

	   })  
</script>
<body>
<div id=container>
<div id=head>


</div>
<div id=main><h2>初始安装</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
 	  <!--用于安装的pop up-->
		<div id="add_hidden" >
          <div class="div_title">安装方法</div>
			      <div class="table_margin">
				        <p>
		            
				        <p><strong> 1.找到"application/config/config.php"文件,把  $config['base_url']	= 的值改为你的安装地址. </strong>
				        <p>  例如
				    $config['base_url']	= 'http://www.eacherp.net/demo/';
				        <p>保存文件,并上传到你的服务器.                        
		            <p><strong> 2.找到"application/config/database.php"文件, 把数据库参数设置好, </strong>
			        <p>例如:
						    <br />
						    'hostname' =&gt; '127.0.0.1',<br />
'username' =&gt; 'root',<br />
'password' =&gt; 'xxxx',<br />
'database' =&gt; 'eacherp',<br />
					      保存文件,并上传至服务器的对应目录
					<p><strong>3. 点击下面的"安装"按钮, </strong>
					<p class="STYLE1">4. 把服务器中的 "application/controllers/install.php" 文件删除.
<p>
				    <form action=install/install_right>
			          <input  type=submit name=submit id=form_button_add value="安装" />
	                </form>
					  </td>
		              </tr>
				        
				        
          </div>
		</div>
	</div>
	
	<div id=main_right>
	</div>
</div>

<div id=tipnote_word align=center></div>
</div>
</div>

</body>
<?
/*  installer.php文件的结尾 */
/*  在系统中的位置: ./application/views/installer */
?>
</html>
