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
 * login    登录主页面视图
 * @category welcome
 * @源代码
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>物料管理系统</title>
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/Rightgrid.js" type="text/javascript"></script>

<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet"type="text/css">
<script>
/*   $(function(){		   
    ////////////add new handling
	$("#form_button_add").click(
          function(){
             ///////验证表单规则用||号区分
			 var erro=verify("#form_add",'user,用户名,required||password,密码,required');
			 if (erro==''){
             $("#form_add").submit();
			
             }else{ note(erro);}
			 }
          );  
    })   */ 
    
    
//修改为ajax方式   
window.GLOBAL = {};
 
$(function(){	
	GLOBAL.domain = window.location.origin+'/index.php';
	GLOBAL.hostname = window.location.hostname;
	GLOBAL.html = window.location.origin+'/html';
    $('#form_button_add').click(
	    function(){
            $.ajax({
                type: "POST",
                url: GLOBAL.domain+"/admin/Auth/check_user",
                data: {user:$("#user").val(),password:$("#password").val()},
                dataType: "json",
                success: function(data){
                	window.location.href=GLOBAL.html+"/admin/bbs.html"; 
                },
               	error: function(e) {
                    var error_info=JSON.parse(e.responseText);
                    alert(error_info.msg);
                } 
            });
    	});
}); 
               
</script>
</head>

<body>
	<div id="container">
		<!-- <div id=head></div> -->
		<h1> 测试开发登录页面</h1>
		<div id="main">
			<div id="main_left">
				<div id="grid"></div>
				<!--用于添加的pop up-->
				<div id="add_hidden">
					<br />
					<br />
					<div align="center" class="table_margin">
						<table class="table_add">
							<tr>
								<td>用户:</td>
								<td height="40"><input type="text" name="user" id="user" size="16" value="" /></td>
							</tr>
							<tr>
								<td>密码:</td>
								<td><input type="password" name="password" id="password" size="16" value="" /></td>
							</tr>
							<tr>
								<td colspan="3"><input class="button" type="button" id="form_button_add" value="登录" /></td>
							</tr>
						</table>
						<br />
						
					</div>
				</div>
				<!--用于添加的pop up--结束 -->
			</div>

			<div id="main_right"></div>
		</div>
		<div id="tipok" class="tipok">
			<!-- <img src=../../img/tick.jpg width=80 /> -->
		</div>
		<div id="tipnote" class="tipnote">
			<div align="center">
				<!--  <img src=../../img/note.jpg width=80 /> -->
			</div>
			<div id="tipnote_word" align="center"></div>
		</div>
	</div>
			
			
			
			
			
<?php include "foot.html" ?>
</body>
<?php
/*  login.php文件的结尾 */
/*  在系统中的位置: ./application/views/login */
?>


</html>
