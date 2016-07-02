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
 * material_icq 材料质的建立,修改和查询页面的主视图文件
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
<script src="../../jquery1.9/jquery-1.9.0.min.js" type="text/javascript"></script>
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/Rightgrid.js?s=2" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
    var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
    
	var passing;
	var base_url='<?php echo base_url();?>';
	passing=function(){
		             			 
				$('#supplier_id').val($(this).find("td").first().text());
				$('#supplier_name').val($(this).children("td").eq(1).text());
				        
		                 }
	
    $(function(){
	
       var clickedId=null;
	   ///////////////////////////validate update///
	  
	   //////////////////////
	   
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','material_in_list?unused=NO&qc_passed=n&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '进料ID,日期,原材料,原材料名称,规格,暂收数量,采购单号,入库作业员,质量,质检员',////表格标题
							 '300px','supplier_id,currency_id,unused'   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','material_in_list?unused=NO&qc_passed=n'+search_string,////url and search string 
							 '进料ID,日期,原材料,原材料名称,规格,暂收数量,采购单号,入库作业员,质量,质检员',////标题
							 '300px','supplier_id,currency_id,unused'   );                                    ///高度,,需要隐藏的td列
                            }					
	   build_grid();
	   	
	  		   
      ////////////update handling
	  $("#button_update").click(
	                      function (){
						             var data=$("#form_update").serialize();
						             var erro=verify("#form_update",'check_result,检验结果,nozero');
									 if (erro==''){
									 var update_url='welcome/material_icq_check';///暂收入库
	                                 update(update_url);
									 build_grid();
									 }else{ note(erro);}
	                                }
	                           );
      ////////////add new handling
	  $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									 //var data=$("#form_add").serialize();alert(data);
									  var erro=verify("#form_add",'supplier,供应商,required||material,原材料,required||qty,数量,digital||tax,税率,digital||delivery_date,交期,isdate||material_id,material_id,digital');
									 if (erro==''){
									 var add_url='welcome/purchase_list_add';///新增操作指向的页面
	                                 adds(add_url);
									 build_grid();
	                                }else{  note(erro);}
									}
	                          );  
	  $("#button_remove").click(
	                      function(){
									 var remove_url='welcome/purchase_list_remove';///移除操作指向的页面
	                                 remove(remove_url);
									 build_grid();
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
									 var 
									 search_string="&"+$("#search1").attr('name')+"="+$("#search1").val();///搜索操作使用的字符串//请替换=号和Search1,2,3的input的name属性
	                                 build_search_grid(search_string);alert(search_string);
									 
	                                }
	                     ); 
		$("#refresh").click(function(){
	                       build_grid();
						   }
	                     ); 				 
		////////////////如果没有select元素,请注释掉下面一行				 
		//loads_select("#measurement_waiting1,#measurement_waiting2",mainurl+'welcome/show_measurement',''); ///对select元素添加option ///有几个select元素加几行 	  			 
        $(".get_supplier").click(function(){
		                     $("#selection").remove();
		                    $(this).parent().append("<div id=selection class=comboboxnote></div>");
//						   
						   cjTable_light('selection','supplier_list?s=0&dates='+Math.floor(Math.random()*999+1),////url of data source
							 'supplier_id,supplier_name',////表格标题
							 '300px'   );
						   
						   }
	                     );
        $(".get_material").click(function(){
		                    $("#selection").remove();
	                      $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light('selection','material_list?s=0&dates='+Math.floor(Math.random()*999+1),////url of data source
							 'material_id,material_name',////表格标题
							 '300px'   );
						   
						   }
	                     );
        $(".get_currency").click(function(){
		                  $("#selection").remove();
		                  $(this).parent().append("<div id=selection class=comboboxnote></div>");
	                       
						   $('#selection').show();
						   cjTable_light('selection','currency_list?s=0&dates='+Math.floor(Math.random()*999+1),////url of data source
							 'currency',///////表格标题
							 '300px'   );
						   
						   }
	                     );
				 
         
	   })  
	   
	   
	               
</script>


<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>待检原材料</h2><p>(双击,可操作)</p>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		    <input type=text class=short id=keyword  /><input type=button class=button id=button_filter value=" 筛选 " />
		   <select name=search_item_name>
		   <option value=material_name>材料名称</option>
		   <option value=supplier_name>供应商</option>
		   <option value=purchase_date>订单日期</option>
		   </select>
		   <select name=search_action>
		    <option value='='>等于</option>
			<option value='~'>大约等于</option>
			<option value='contains'>包含</option>
	  </select>
		   <input type=text class=short id=search_value name=search_value /><!--修改name属性,以设定搜索字符串-->
		   <input type=button class=button id=button_search value=" 搜索 " /><input type=button class=button id=refresh value=" 刷新 " />


		<!--用于更新的pop up-->
		<div id="update_hidden" class="pop_up">
		              <div class="div_title">原材料进料检验<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_update>
						<table class=table_update>
						<tr height=35><td>
						</td><td> <input type=hidden id=in_id name=in_id readonly /></td></tr><tr height=35><td>
						<input type=hidden id=in_date name=in_date readonly />
						<input type=hidden id=material_id name=material_id readonly />
						检验结果</td><td><select name=check_result  />
						                <option value='0'>请选择</option>
						                <option value='y'>合格</option>
										<option value='n'>不合格</option>
										</select>
						</td></tr>

						<tr height=35><td colspan=3><input class=button type=button id=button_update value=提交 /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于更新的pop up--结束 -->
 
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">新增<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update><tr height=35><td>
						</td><td></td></tr><tr height=35><td>
						供应商</td><td width=250> <input type=text id=add_supplier_name name=supplier_name readonly /><input type=button class=get_supplier value=...></td></tr><tr height=35><td>
						原材料</td><td><input type=text id=add_material_name name=material_name   readonly /><input type=button class=get_material value=...></td></tr><tr height=35><td>
						数量</td><td> <input type=text id=qty name=qty /></td></tr><tr height=35><td>
						价格</td><td><input type=text id=price name=price  /></td></tr><tr height=35><td>
						币种</td><td><input type=text id=add_currency name=currency size=3 readonly /><button type=button class=get_currency>...</button></td></tr><tr height=35><td>
						税率</td><td><input type=text id=tax name=tax  /></td></tr><tr height=35><td>
						采购日</td><td><input type=text id=purchase_date name=purchase_date  /></td></tr><tr height=35><td>
						交期</td><td><input type=text id=delivery_date name=delivery_date  /></td></tr><tr height=35><td>
						备注</td><td><input type=text name=remark   />
						<input type=hidden id=add_material_id name=material_id value='' />
						<input type=hidden id=add_supplier_id name=supplier_id value='' />
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
<?php
/*  material_icq.php文件的结尾 */
/*  在系统中的位置: ./application/views/material_icq.php */
?>