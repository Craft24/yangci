<?php
/**
 * EachERP
 * EachERP是开源软件,基于PHP 5.1.6 以上版本和CodeIgniter 2.0框架
 * @软件包	EachERP
 * @授权		http://EachERP.net/user_guide/license.html
 * @链接		http://EachERP.net
 * @版本	    0.1beta

 * 版权所有(C) 2015 作者:陈国彤
本程序为自由软件；您可依据自由软件基金会所发表的GNU 通用公共授权条款，对本程序再次发布和/ 或修改；无论您依据的是本授权的第三版，或（您可选的）任一日后发行的版本。
本程序是基于使用目的而加以发布，然而不负任何担保责任；亦无对适售性或特定目的适用性所为的默示性担保。详情请参照GNU 通用公共授权。
您应已收到附随于本程序的GNU 通用公共授权的副本；如果没有，请参照<http://www.gnu.org/licenses/>.
 * user_pass.php 用户更改密码 主视图件
 *@category	welcome
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
    var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
    
	var passing;
	var inner; var clickedId=null;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
					

    $(function(){
	   $("#head").makemenu2(base_url);////顶部菜单
	   $("#check_hidden").show();
	  
 	   $("#button_confirm").click(///密码提交
	                      function (){
						            
						             var data=$("#form_check").serialize();
						             var erro='';
									 erro=verify("#form_check",'user_name,用户名称,required||old_pass,老密码,required||new_pass,新密码,required||new_pass_confirm,新密码确认,required');
								  	 if($("#new_pass").val()!=$("#new_pass_confirm").val()) erro="两次新密码不一致";
									 if($("#new_pass").val()==$("#old_pass").val()) erro="新旧密码必须不同";
									 if(erro=='')
									 {
													 var add_url='user_pass_update?'+data;///新增操作指向的页面
													  update(add_url);
													                                    
													
													 
																											
									 }else{
									       note(erro);
									       }					 
							           $("#erro").show();
									   $("#erro").html('<a href=login>登录</a>');	   						 
														 
	                                });	   


	   })  
	   
	   
	               
</script>


<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>用户</h2><p></p>
    <div id=main_left>

       
　  


     
 	   <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>
		<div id="check_hidden" class="pop_up">
		              <div class="div_title">修改密码<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        
				        <form action="" id=form_check>
						<table class=table_update>
						<tr  height=35>
						<td>用户名</td><td><input type=text id=check_user_name name=user_name readonly value="<?php echo $jason;?>"/></td></tr>
						<tr height=35>
						<td>老密码：</td><td><input type=password name=old_pass /></td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td>新密码：</td><td><input type=password name=new_pass id=new_pass /></td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td>新密码：</td><td><input type=password name=new_pass_confirm id=new_pass_confirm />(再输入一次)</td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td></td><td></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=button_confirm value="确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
     
	 


         
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
