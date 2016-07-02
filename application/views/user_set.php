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
 * user_set.php 用户管理 主视图 
 * @category	user
 * @源代码
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ERP系统</title>
</head>
<script src="../../jquery1.9/jquery-1.9.0.min.js" type="text/javascript"></script>
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/Rightgrid.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
    var clickedId=null;
	 var list='';
	  var tds='';
    var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
     
	var passing;
     var base_url='<?php echo base_url();?>';
	 function material_outs(material_id) {
										  	$("#material_out_hidden").show();   
											resize("#material_out_hidden");    
											$("#material_selection").append("<div id=selection class=comboboxnote></div>");
		                                    cjTable_light('selection',"material_list_in_production?material_id="+material_id,'名称,id',100);
											$("#selection").show();                   
		                                 }
	   passing=function(){
		             			 
				$('#supplier_id').val($(this).find("td").first().text());
				$('#supplier_name').val($(this).children("td").eq(1).text());
				        
		                 }
    $(function(){
	
       
	   ///////////////////////////validate update///
	  
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','customer_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '客户号,名称,地址,联系人,联系方式,交货须知',////表格标题
							 '300px',''   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','customer_list?s=2'+search_string,////url and search string 
							  '客户号,名称,地址,联系人,联系方式,交货须知',////标题
							 '300px',''   );                                    ///高度,,需要隐藏的td列
                            }					
	   build_grid();
      ////////////update handling
	  $("#button_update").click(
	                      function (){
						             var data=$("#form_update").serialize();
						             var erro=verify("#form_update",'customer_name,名称,required||customer_address,地址,required||contact,联系人,required,||phone,联系方式,required,||delivery_note,交货提示,required');
									  if (erro==''){
									 var update_url='welcome/customer_update';///更新操作指向的页面
	                                 update(update_url);
									 build_grid();
									 }else{ note(erro);}
	                                }
	                           );
      ////////////add new handling
	  $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									  ///var data=$("#form_add").serialize();alert(data);
									 var erro=verify("#form_add",'customer_name,名称,required||customer_address,地址,required||customer_contact,联系人,required,||customer_phone,联系方式,required,||delivery_note,交货提示,required');
									 if (erro==''){
									 var add_url='welcome/customer_add';///新增操作指向的页面
	                                 adds(add_url);
									 build_grid();
	                                }else{  note(erro);}
									}
	                          );

	  $("#keyword").keyup(
	                      function(){
									 var keyword=$("#keyword").val();/////////////////过滤操作
	                                 filter_material(keyword);
	                                }
	                     );       
      $("#button_search").click(
	                      function(){
									  
													 var search_string=$('#search').serialize();///搜索操作使用的字
													 build_search_grid('&'+search_string);//alert(search_string);
									              
	                                }
	                     ); 
		$("#refresh").click(function(){
	                       build_grid();
						   }
	                     ); 				 
		////////////////如果没有select元素,请注释掉下面一行				 
		//loads_select("#measurement_waiting1,#measurement_waiting2",mainurl+'welcome/show_measurement',''); ///对select元素添加option ///有几个select元素加几行 	  			 
		 
	   })  
</script>
<body>
<div id=container>
<div id=head></div>
<div id=main><h2>用户名单</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		   <input type=button class=button id=button_add value=" 新增 " />
		   <form id=search> <input type=text class=short id=keyword  /><input type=button class=button id=button_filter value=" 筛选 " />
		   包含文字<input id=material_name name=customer_name value='' />
		   		   <input type=button class=button id=button_search value=" 搜索 " />
           </form>

		<!--用于更新的pop up-->
		<div id="update_hidden" class="pop_up">
		              <div class="div_title">用户管理<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_update>
						<table class=table_update>
						<tr height=35><td>
						序号</td><td> <input type=text id=customer_id name=customer_id readonly /></td></tr><tr height=35><td>
						用户名称</td><td><input type=text id=update_customer_name name=customer_name readonly  /></td></tr><TR><td>
						地址</td><td> <input type=text id=update_customer_address name=customer_address /></td></tr><tr height=35><td>
						联系人</td><td> <input type=text id=update_contact name=contact /></td></tr><tr height=35><td>
						联系方式</td><td><input type=text id=update_phone name=phone  /></td></tr><tr height=35><td>
						交货要求</td><td><input type= id=update_delivery_note name=delivery_note  /></td></tr><tr height=35><td>
						</td><td>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=button_update value=更新 /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于更新的pop up--结束 -->
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">新增用户<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update><tr height=35><td>
						</td><td></td></tr><tr height=35><td>
						用户名称</td><td><input type=text id=add_material_name name=customer_name    /></td></tr><tr height=35><td>
						地址</td><td> <input type=text id=qty name=customer_address /></td></tr><tr height=35><td>
						联系人</td><td><input type=text id=delivery_date name=customer_contact /></td></tr><tr height=35><td>
						联系方式</td><td><input type=text id=delivery_date name=customer_phone /></td></tr><tr height=35><td>
						交货要点</td><td><textarea name=delivery_note /></textarea></td></tr><tr height=35><td>
						</td>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="新增" /></td></tr>
						</table>
						</form>
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
</body>
</html>
