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
 * user.php   用户管理的主视图
 * @category	user
 * @源代码
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>物料管理系统</title>
<style>
.right{width:180px;padding-left:30px;float:left;}
#rights_list{width:810px;}
</style>
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
	function build_grid_tr(in_id){cjTable_tr( 'user_list?user_id='+$(clickedId).find('td').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',0);                        ///表格高度,需要隐藏的td
                            }
	function build_grid_tr_add(in_id){cjTable_tr( 'user_list?user_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',1);                        ///表格高度,需要隐藏的td
                            }
					

    $(function(){
		   function build_grid(){cjTable( '#grid','user_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,姓名,部门,注册日期,状态,用户名',////表格标题
							 '300px',''   );                                    ///表格高度,需要隐藏的td
                            }
	        function build_search_grid(search_string){cjTable( '#grid','user_list?s=0'+string,////url of data source
							 '序号,姓名,部门,注册日期,状态,用户名',////表格标题
							 '300px',''   );                                   ///高度,,需要隐藏的td列
                            }	
      
	   ///////////////////////////validate update///
	   
	   $("#head").makemenu2(base_url);////顶部菜单
	  
	    build_grid();
	  		   
      ////////////权限列表
       $("#user_rights").click(
	                      function (){
						             
						             if(clickedId==null)
									 {
									 $("#error").show();resize("#error");
									 }else{ $(':input','#add_hidden')  ////清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#add_hidden").show();
									 resize("#add_hidden");$("#error").hide();
									 $("#update_user_name").val($(clickedId).find('td').eq(1).text());
									 $("#update_user_id").val($(clickedId).find('td').eq(0).text()); 
									  var add_url='user_rights?user_id='+$("#update_user_id").val();///新增操作指向的页面
													 $.getJSON(add_url,function(result){ var li=''; 
													                                    $.each(result, function(k, v) {
																						$.each(v,function(kk, vv) {
																						
																						if (kk=='right_id') li+='<div class=right><input type=checkbox name=check[';
																						if (kk=='right_code') li+=vv+'] ';
																						if (kk=='right_checked') li+=vv+'>';
																						if (kk=='right_display_name') li+=vv+'</div>';
																					   	});
																				     	});
													$("#rights_list").html(li);                                  
													} );
									 }
	                                }
	                           );
       $("#user_update").click(//显示
	                      function (){
						             
						             if(clickedId==null)
									 {
									 $("#error").show();resize("#error");
									 }else{ $(':input','#check_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#check_hidden").show();
									 resize("#check_hidden");$("#error").hide();
									 $("#check_user_name").val($(clickedId).find('td').eq(1).text());
									 $("#check_user_id").val($(clickedId).find('td').eq(0).text());
									 $(".department").val($(clickedId).find('td').eq(2).text());
									  setTimeout(function() { 
															var state=$(clickedId).find('td').eq(3).find('div').eq(0).text();
															if (state.indexOf('离职')>=0)　{$("#states option[value='1']").attr("selected",true);}
															
														}, 1);	
									 }
									 
	                               $("#form_button_check").val('确定');
								   
								    }
	                           );
       $("#user_new").click(//显示
	                      function (){
						             
						             $(':input','#new_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#new_hidden").show();
									 resize("#new_hidden");$("#error").hide();
									 $("#check_user_name").val($(clickedId).find('td').eq(1).text());
									 $("#check_user_id").val($(clickedId).find('td').eq(0).text());
									 
									  setTimeout(function() { 
															var state=$(clickedId).find('td').eq(3).find('div').eq(0).text();
															if (state.indexOf('离职')>=0)　{$("#states option[value='1']").attr("selected",true);}
															
														}, 1);	
									 
	                                }
	                           );
	 $("#form_button_check").click(///用户在职状态提交
	                      function (){
						            
						             var data=$("#form_check").serialize();
						             var erro='';
									 erro=verify("#form_check",'user_id,用户名称,required');
								  	 if(erro=='')
									 {
													 var add_url='user_update?'+data;///新增操作指向的页面
													 updates(add_url);
																										
									 }else{
									       note(erro);
									       }					 
							          							 
														 
	                                });	   

	 $("#new_user_button").click(///新用户提交
	                      function (){
						            
						            
									 var erro='';
									 erro=verify("#form_add",'user_name,用户名称,required||author_name,员工姓名,required||pd,初始密码,required');
								  	 if(erro=='')
									 {
													 var add_url='user_new';///新增操作指向的页面
													 adds_2(add_url);
									 }else{
									       note(erro);
									       }					 
	                                });	   

	  	  
	   $("#form_button_add").click(///权限提交
	                      function (){
						            
						             var data=$("#form_rights").serialize();
						             var erro='';
									 erro=verify("#form_rights",'user_id,用户名称,required');
								  	 if(erro=='')
									 {
													 var url='../welcome/user_rights_update?'+data;
													 update(url);
												
									 }else{
									       note(erro);
									       }					 
							          							 
														 
	                                });	   

	  $("#next_page").click(function(){
	                       if ( $("#table1").children('tbody').children('tr').length > 1 ) {
	                       var p=parseInt($("#page").val())+1;
						   $("#page").val(p);
						   page_string='&page='+p;
						   string=search_string+page_string;
						   build_search_grid();
						   $("#error").hide();
						   	}			 				 
                                      });
	  $("#previous_page").click(function(){
	                      if(parseInt($("#page").val())>0){
						   var p=parseInt($("#page").val())-1;
						   $("#page").val(p);
						   string=search_string+'&page='+p;
						   build_search_grid();
						   $("#error").hide();
						   				 		}		 
                                      });								  
	  $("#search").click(function(){
	                       $("#error").hide();
						   $("#page").val(0);
						   clickedId=null;
						   var erro='';
						   if(erro==''){
						   search_string=search_string+'&state='+$("#state").val();
						   string=search_string;
						   build_search_grid();
						               }else{
									   alert(erro);
									   }
									   
                                      });			 
	  				 				 
         $("#searchs").submit(function () {
                                       return false;
                                        }); 
		 $("#search_start_date").focus(function(){
		                                        
		                                        $(this).val($("#search_end_date").val());
												});	
		 $(".department").focus(function(){
	                       
	                       
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light5('selection','department_list?s=1'+'&dates='+Math.floor(Math.random()*999+1),
							 '',////表格标题
							 '500px','depat','yes','.department,0');
						    $("#selection").show();
							   }

	                     );
		 $("#search_material_name").focus(function(){
	                       if ($(this).val()=='输入品名') $(this).val('');
						    });		
									 
	   })  
	   
	   
	               
</script>


<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>用户管理</h2><p></p>
    <div id=main_left>
	    <form id=form_print>
	    <div id=grid>
		
		</div>
		</form>
		<input type="button" id=user_new value="新用户"/>
	    <input type="button" id=user_update  value="更新"/>
		<input type="button" id=user_rights  value="权限"/>
　　　　<!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
	   <select id=state name=state>
	   <option value=0>在职</option>
	   <option value=1>离职</option>
	   </select>
		<input type=button id=search value="搜索"/>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="hidden" id="page" name="page" value=0  >
        <button id=previous_page>前页</button><button id=next_page>后页</button>  
		</form> 
       </div>
　    <!--查询套件结束 -><-->


      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>
 	  <!--用于用户管理的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">权限管理<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_rights>
						<table class=table_update>
						<tr  height=35>
						<td>用户名称</td><td><input type=text id=update_user_name name=user_name readonly /><input type=hidden id=update_user_id name=user_id /></td></tr>
						<tr height=35>
						<td></td><td id=rights_list > </td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于用户管理的pop up--结束 -->
 	  <!--用于更新的pop up-->
		<div id="check_hidden" class="pop_up">
		              <div class="div_title">用户更新<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_check>
						<table class=table_update>
						<tr  height=35>
						<td>用户名称</td><td><input type=text id=check_user_name name=user_name readonly /><input type=hidden id=check_user_id name=user_id /></td></tr>
						<tr height=35>
						<td>在职状态</td><td><select id=states name=state><option value=0 selected=true>在职</option> <option value=1 selected=false>离职</option></select></td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td>就职部门</td><td><input type=text class=department name=department value='' /></td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td></td><td></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_check value="确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于更新的pop up--结束 -->
	  <!--用于新用户的pop up-->
		<div id="new_hidden" class="pop_up">
		              <div class="div_title">新用户<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update>
						<tr  height=35>
						<td>用户名：</td><td><input type=text id=new_user_name name=user_name />(登录名)</td></tr>
						<tr  height=35>
						<td>员工姓名：</td><td><input type=text id=new_author_name name=author_name /></td></tr>
						<tr height=35>
						<td>初始密码：</td><td><input type=password name=pd id=password></td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td>就职部门：</td><td><input type=test name=department class=department /></td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td></td><td></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=new_user_button value="确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于新用户的pop up--结束 -->
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
