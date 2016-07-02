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
 * product_in_icq 成品半成品出入库的品检 视图
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
    var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
    var mainurl="http://www.mesoco.net/erp/index.php/"; 
	var passing;
	var inner;
	var base_url='<?php echo base_url();?>';
    $(function(){
	
       var clickedId=null;
	  
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','product_in_pending_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,暂入日期,物料id,品名,规格,数量,类型,生产单号,状态,抽样数,不良率,检验员,委外供应商',////表格标题
							 '300px','currency_id'   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','product_in_pending_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '暂入日期,(半)成品名,规格,数量,类型,生产单号,抽样数,不良率,状态,检验员,委外供应商',////表格标题
							 '300px','in_id,material_id,currency_id'   );                                    ///表格高度,需要隐藏的td
                            }
	   build_grid();
	   $(".supplier").hide();
	   $(".is_subcontract").click(function(){ if ($(this).is(':checked')){ $(".supplier").show();}else{$(".supplier").hide();} 
	                                         });
	  //////////////选择物料
	   $("#update_material_name").keyup(function(){
		                      if($(this).val().length>1)
							  {
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							   cjTable_light5('selection','product_list?s=1&material_name='+$("#update_material_name").val()+'&dates='+Math.floor(Math.random()*999+1),
							 'material_id,material_name1',////表格标题
							 '500px','amaterial_id,material_specification,meausrement','yes','#update_material_name,1||#update_material_id,0');
							  }
							  if ($(this).val().length==0) $("#selection").remove();
						   }
	                     );		
	  
	  		   
      ////////////update handling
       $(".out").click(
	                      function (){
						             $(':input','#add_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#add_hidden").show();
									 resize("#add_hidden");
									 $("#tr_work").hide();
									 $("#tr_production").hide();
									 $("#tr_other").hide();
									 if ($(this).attr("id")=='out_by_production') {$("#tr_production").show();}
									 if ($(this).attr("id")=='out_by_other') {$("#tr_other").show();$("#add_production_id").val('0');$("#other_order").val('其他成品入仓检验'); }//生产单号码设置为0,则表示零星领料
									 
	                                }
	                           );
	  $("#form_button_add").click(
	                      function (){
						             var data=$("#form_add").serialize();
						            
									 var erro=verify("#form_add",'qty,数量,digital||material_type,品质,required||applier,送检人,required||material_name,成品(半成品)名称,required||production_id,成品名称,required');
									  if (erro==''){
									  var add_url='welcome/product_in_pending_add?'+data;///新增操作指向的页面
	                                  adds(add_url);
									  build_grid();
									 }else{ note(erro);}
	                                }
	                           );
      $("#button_search").click(
	                      function(){
									 var 
									 search_string="&"+$("#search1").attr('name')+"="+$("#search1").val();///搜索操作使用的字符串//请替换=号和Search1,2,3的input的name属性
	                                 build_search_grid(search_string);alert(search_string);
									 
	                                }
	                     ); 
	  $("#add_material_name").keyup(function(){
	                       if($(this).val().length>1)
							  {
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light5('selection','production_list_unfinished?material_name=&dates='+Math.floor(Math.random()*999+1),
							 'production_id,material_name1',////表格标题
							 '500px','Amaterial_id,material_specification,meausrement','yes','#add_material_name,1||#add_production_id,0');
							$("#selection").show();
							}
							}
	                     );
	   $(".supplier").click(function(){
		                    $("#selection").remove();
		                    $(this).parent().append("<div id=selection class=comboboxnote></div>");
						    $("#selection").show();
						    cjTable_light5('selection','supplier_list?s=0&dates='+Math.floor(Math.random()*999+1),
							 'supplier_id,supplier_name',////表格标题
							 '500px','','yes','#supplier_id,0||#correct_supplier_id,0||#supplier,1||#correct_supplier,1');
						    $("#selection").show();
							}
						   
	                     );			
	  $("#refresh").click(function(){
	                       build_grid();
						   }
	                     ); 
	  				 				 
         
	   })  
</script>
<body>
<div id=container>
<div id=head>
</div>
<div id=main>
  <h2>成品（半成品）入库检验</h2><p></p>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		<button id=out_by_production class=out>根据生产单做入库检</button> <button id=out_by_other class=out>其他入库检</button> 
 
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">（半）成品检验<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update>
						
						<tr height=35>
						<td>入库品名</td><td><input type=text id=update_material_name name=material_name autocomplete=off /><input type=hidden id=update_material_id name=material_id /></td></tr><tr id=tr_production height=35>
						<td>生产单</td><td><input  type=text id=add_material_name name=production_order />
						                  <input class=input_no_border type=text id=add_production_id name=production_id readonly  />
						</td></tr>
						<tr height=35>
						<td>数量</td><td><input type=text id=qty name=qty /></td></tr>
						<tr height=35>
						<td>委外供应商</td><td><input type=text  class=supplier id=supplier name=supplier /><input type=hidden id=supplier_id name=supplier_id /><input type=checkbox id=is_subcontract_in class=is_subcontract /> </td></tr>
						<tr height=35>
						
						<td>品质</td><td><select name=material_type />
						                <option value=''></option>
						                <option value=G>良品</option>
										<option value=G>报废品</option>
										<option value=G>待定品</option>
										</select>
						</td></tr>
						<tr height=35>
						<td>送检人</td><td><input type=text id=applier name=applier /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="确认" /></td></tr>
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
<?php
/*  product_in_icq.php 文件的结尾 */
/*  在系统中的位置: ./application/views */
?>
</html>
