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
 * product_out.php 产品发货的主视图文件
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
	var passing;
	var inner; var clickedId=null;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
     function build_grid_tr(){cjTable_tr( 'product_out_list?in_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'final,warehouse'   );                                    ///表格高度,需要隐藏的td
                            }	
	$(function(){
		   function build_grid(){cjTable( '#grid','product_out_list?type=Y&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,通知单,日期,类型,定单号,客户订单号,物料ID,物料名,物料全名,规格,规格2,数量,金额,仓库,作业人,审核人,审核日期, ,委外id ',////表格标题
							 '370px','final,warehouse'   );                                    ///表格高度,需要隐藏的td
                            }
	        function build_search_grid(search_string){cjTable( '#grid','product_out_list?type=Y&s=0'+string,////url of data source
							'序号,通知单,日期,类型,定单号,客户订单号,物料ID,物料名,物料全名,规格,规格2,数量,金额,仓库,作业人,审核人,审核日期, ,委外id ',////表格标题
							 '370px','final,warehouse'   );                                   ///高度,,需要隐藏的td列
                            }	
	   $("#head").makemenu2(base_url);////顶部菜单
	  
	   build_grid();
	   bound_search_controls('成品');
	   $("#search_supplier").hide();	
	   ///////////更正						   
	  $("#form_button_correct").click(
	                      function (){
						             var erro='';
									
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col+1).find('div').eq(0).text()!='') erro='已经审核,不能更改.';
									 var data=$("#form_correct").serialize();
									 erro+=verify("#form_correct",'out_qty,数量,digital||order_id,订单,digital');
									 if(erro=='')
									 {   
									  	  var url='product_out_correct?'+data;
										  updates(url);
										 
							  		  }else{
									       note(erro);
									       }					 
	                                });
	  		   
      ////////////弹出新通知
       $("#product_out").click(
	                      function (){
						             if(clickedId==null)
									 {
									 $("#error").show();resize("#error");
									 }else{ $(':input','#add_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#add_hidden").show();
									 resize("#add_hidden");$("#error").hide();
									 $("#update_material_name").val($(clickedId).find('td').eq(6).text());
									 $("#update_material_id").val($(clickedId).find('td').eq(5).text()); 
									 $("#qty").val($(clickedId).find('td').eq(8).text()); 
									 $("#notice_id").val($(clickedId).find('td').eq(0).text());  
									 $("#material_type").val($(clickedId).find('td').eq(2).text());
									 $("#add_order_id").val($(clickedId).find('td').eq(3).text());
									 $("#customer_order_id").val($(clickedId).find('td').eq(4).text()); 
									 }
	                                }
	                           );
       $("#product_out_check").click(
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
									 $("#check_material_name").val($(clickedId).find('td').eq(6).text());
									 $("#check_material_id").val($(clickedId).find('td').eq(5).text()); 
									 $("#check_qty").val($(clickedId).find('td').eq(8).text()); 
									 $("#check_notice_id").val($(clickedId).find('td').eq(0).text());  
									 $("#check_order_id").val($(clickedId).find('td').eq(3).text());
									 }
	                                }
	                           );
       $("#product_out_preparation").click(
	                      function (){
						             
						             if(clickedId==null)
									 {
									 $("#error").show();resize("#error");
									 }else{ $(':input','#preparation_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#preparation_hidden").show();
									 resize("#preparation_hidden");$("#error").hide();
									 $("#preparation_material_name").val($(clickedId).find('td').eq(6).text());
									 $("#preparation_order_id").val($(clickedId).find('td').eq(3).text());
									 $.getJSON('welcome/product_preparation?order_id='+$("#preparation_order_id").val(),
									           function(result){
											                  $.each(result, function(k, v) {
														       
														                                      $("#out_preparation").html(v);
														                                     });             
															    });
									 }
	                                }
	                           );
	  ///////////检验						   
	  $("#form_button_check").click(
	                      function (){
						            //alert($("#material_type").val());
						             var data=$("#form_check").serialize();
						             var erro='';
									 erro=verify("#form_check",'qty_after_check,数量,digital||material_name,产品名称,required');
								  	 if(erro=='')
									 {
									                $.getJSON('product_inventory_check?material_type='+$("#material_type").val()+'&qty='+$("#qty_after_check").val()+'&material_id='+$("#check_material_id").val(),
													function(result) {
																	 if (result<$("#out_qty").val() ) 
																										  {erro="库存不足"; note(erro); return false; }
																									  else{ var add_url='welcome/product_delivery_check?'+data;///新增操作指向的页面
																											updates(add_url);
																											
																										  }
																	 });
									 }else{
									       note(erro);
									       }					 
	                                });
						   
	  ///////////反审核						   
	  $("#revocation").click(
	                      function (){
						             var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('委外')").index();///得到审核内容所在的列数
								  	 if ($(clickedId).find('td').eq(col+1).find('div').eq(0).text()!='0') {var erro='请到委外加工界面处理';note(erro);return false;}
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
								  	 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()=='') erro='未审核过';
									 if(erro=='')
									 {
									  	 var url='product_out_revocation?id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										 url+='&notice_id='+$(clickedId).find('td').eq(1).text();
										 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('数量')").index();///得到数量所在的列数 
										 url+='&out_qty='+$(clickedId).find('td').eq(col).text();
										  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('订单号')").index();///得到订单号所在的列数 
										 url+='&order_id='+$(clickedId).find('td').eq(col).text();
										  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('物料ID')").index();///得到订单号所在的列数
										 url+='&material_id='+$(clickedId).find('td').eq(col).text();
										  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('类型')").index();///得到订单号所在的列数
										 url+='&material_type='+$(clickedId).find('td').eq(col).text();
										 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('仓库')").index();///得到订单号所在的列数
										 url+='&warehouse='+$(clickedId).find('td').eq(col).text();
										 updates(url);
							  		  }else{
									       note(erro);
									       }					 
	                                });
	  ///////////审核						   
	  $("#approve").click(
	                      function (){var erro='';
						             var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('委外id')").index();///得到审核内容所在的列数
								  	
									 if ($(clickedId).find('td').eq(col+1).find('div').eq(0).text()!='0') {erro='请到委外加工界面审核';note(erro);return false;}
						             
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
								  	 if ($(clickedId).find('td').eq(col+1).find('div').eq(0).text()!='') erro='不能审核';
									 if(erro=='')
									 {
									  	 var url='product_out_approve?id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										 url+='&notice_id='+$(clickedId).find('td').eq(1).text();
										 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('数量')").index();///得到数量所在的列数 
										 url+='&out_qty='+$(clickedId).find('td').eq(col).text();
										  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('订单号')").index();///得到订单号所在的列数 
										 url+='&order_id='+$(clickedId).find('td').eq(col).text();
										  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('物料ID')").index();///得到订单号所在的列数
										 url+='&material_id='+$(clickedId).find('td').eq(col).text();
										  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('类型')").index();///得到订单号所在的列数
										 url+='&material_type='+$(clickedId).find('td').eq(col).text();
										 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('仓库')").index();///得到订单号所在的列数
										 
										 url+='&warehouse='+$(clickedId).find('td').eq(col).text();
										 updates(url);
										 
							  		  }else{
									       note(erro);
									       }					 
							          							 
														 
	                                });
	                     					                     									 
      $("#product_out_print").click(
	                      function(){
						               $("#table_title tr").each(function(index){
									                                          if(index>0){$(this).find('td').eq(11).find('div').eq(0).find('input').removeClass('in_ids');}
									                                          });
									   var ids='';
									   $(".in_ids").each(function(){
									                                if ($(this).is(':checked')) ids+=$(this).attr('name'); ///得到被选中的id
									                                });
									   if(ids=='') return false;
									    ids=ids.replace(/in_id\[/gm,'').replace(/]/gm,',');
									    window.location='product_out_print?ids='+ids;
									   
	                                }
	                     );
       	                     
	  $("#next_page").click(function(){
	                       if ( $("#table1").children('tbody').children('tr').length > 1 ) {
	                       var p=parseInt($("#page").val())+1;
						   $("#page").val(p);
						   page_string='&page='+p;
						   string=search_string+page_string;
						   build_search_grid();
						   	}			 				 
                                      });
	  $("#previous_page").click(function(){
	                      if(parseInt($("#page").val())>0){
						   var p=parseInt($("#page").val())-1;
						   $("#page").val(p);
						   string=search_string+'&page='+p;
						   build_search_grid();
						   				 		}		 
                                      });								  
	  $("#search").click(function(){
						   $("#page").val(0);
						   var erro='';
						   if($("#search_start_date").val().length>0)  erro=verify("#searchs",'start_date,起始日期,isdate');
						   if($("#search_end_date").val().length>0)    erro=verify("#searchs",'end_date,截止日期,isdate');
						   if(erro==''){
						   search_string='&material_id='+$("#search_material_id").val();
						   search_string=search_string+'&start_date='+$("#search_start_date").val();
						   search_string=search_string+'&end_date='+$("#search_end_date").val();
						   search_string=search_string+'&warehouse='+$("#search_warehouse_id").val();
						   search_string=search_string+'&customer_order_id='+$("#search_customer_order_id").val();
						   string=search_string;
						   build_search_grid();
						               }else{
									   alert(erro);
									   }
									   
                                      });			 
	  				 				 
        
		 $("#correct").focus(function(){
	                       
												if(clickedId==null)
												 {
												 $("#error").show();resize("#error");
												 }else{ $(':input','#correct_hidden')  ///清空所有input
													 .not(':button, :submit, :reset')  
													 .val('')  
													 .removeAttr('checked')  
													 .removeAttr('selected'); 
												 $("#correct_hidden").show();
												 resize("#correct_hidden");$("#error").hide();
												  $("#correct_in_id").val($(clickedId).find('td').eq(0).text());
												 $("#correct_material_name").val($(clickedId).find('td').eq(7).text());
												 $("#correct_material_id").val($(clickedId).find('td').eq(6).text()); 
												 $("#correct_qty").val($(clickedId).find('td').eq(9).text()); 
												 $("#correct_order_id").val($(clickedId).find('td').eq(4).text());
												  var selected=$(clickedId).find('td').eq(3).text();
												 //$("#correct_material_type").find("option[text='待定品']").attr("selected",true);
												 switch (selected)
														{ case '良品':  $("#correct_material_type").val('G');break;
														  case '待定品':$("#correct_material_type").val('P');break;
														  case '次品':  $("#correct_material_type").val('D');break;
														}
												  get_tr_content("correct_hidden","form_correct");
												  $("#form_button_correct").val('确定'); 
												 }
						          });						
	   })  
</script>
<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>产品发货记录</h2><p></p>
    <div id=main_left>
	    <form id=form_print>
	    <div id=grid>
		
		</div>
		</form>
		<a href="product_out_notice"><input type="button"  value="新发货"/></a>
	    <input type="button" id="product_out_print"  value="打印出库单"/>
		<input type="button" id="correct"  value="更正"/>
		<input type="button" id="approve"  value="审核"/>
		<input type="button" id="revocation"  value="取消审核"/>
　　　　<!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
	  <input type=hidden id=search_customer_id name=customer_id class=customer_id />
	  <input type=text id=search_customer name=customer class=customer_name size=8 placeholder="点击选客户" />
	  <input type=text size=8 name=customer_order_id id=search_customer_order_id class=customer_order_id placeholder="客户订单号" />
	  <?php include "search_control.php";?>
	  </form> 
       </div>
　    <!--查询套件结束 -><-->


      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>
 	  <!--用于正式出货的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">发货通知<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td><input type=text id=update_material_name name=material_name readonly /><input type=hidden id=update_material_id name=material_id /><input type=hidden id=notice_id name=notice_id /></td></tr>
						<tr height=35>
						<td>订 单 号</td><td><input  type=text id=add_order_id name=order_id readonly /> </td></tr>
						<td>客户订单号</td><td><input  type=text id=customer_order_id name=customer_order_id readonly />                   
						</td></tr>
						<tr height=35>
						<td>发货数量</td><td><input type=text id=qty name=out_qty  /></td></tr>
						<tr height=35>
						<td>产品质量</td><td><input type=text id=material_type name=material_type readonly />
						</td></tr>
						<tr height=35>
						<td>备注</td><td><input type=text id=remark name=remark /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="出库确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于正式出货的pop up--结束 -->
 	  <!--用于出货检验的pop up-->
		<div id="check_hidden" class="pop_up">
		              <div class="div_title">发货检验<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        
				        <form action="" id=form_check>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td><input type=text id=check_material_name name=material_name readonly /><input type=hidden id=check_material_id name=material_id /><input type=hidden id=check_notice_id name=notice_id /></td></tr>
						<tr height=35>
						<td>订 单 号</td><td><input  type=text id=check_order_id name=order_id readonly /> </td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td>通知数量</td><td><input type=text id=check_qty name=out_qty readonly /></td></tr>
						<tr height=35>
						<td>合格数量</td><td><input type=text id=qty_after_check name=qty_after_check  />
						</td></tr>
						<tr height=35>
						<td>备注</td><td><input type=text id=check_remark name=remark /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_check value="检验确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于出货检验的pop up--结束 -->
 	  <!--用于出货要求的pop up-->
		<div id="preparation_hidden" class="pop_up">
		              <div class="div_title">发货要求<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_check>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称:</td><td><input type=text id=preparation_material_name name=material_name readonly /></td></tr>
						<tr height=35>
						<td>订 单 号:</td><td><input  type=text id=preparation_order_id name=order_id readonly /> </td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td>备货要求:</td><td id=out_preparation></td></tr>
						<tr height=35>
						<td></td><td>
						</td></tr>
						
						</table>
						</form>
				  </div>
		</div>
      <!--用于出货要求的pop up--结束 -->
 	  <!--用于出货更正的pop up-->
		<div id="correct_hidden" class="pop_up">
		              <div class="div_title">更正<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        
				        <form action="" id=form_correct>
						<input type=hidden id=correct_in_id name=in_id /><input type=hidden /><input type=hidden />
						<table class=table_update>
						<tr height=35>
						<td>状态</td><td>
						<select id=correct_material_type name=material_type />
						<option value=G>良品</option>
						<option value=P>待定品</option>
						<option value=D>次品</option>
						</select><input  type=hidden />
						</td></tr>
						<tr height=35>
						<td>订 单 号</td><td><input  type=text id=correct_order_id name=order_id  /> </td></tr>
						<tr height=35>
						<td>客户订单号</td><td><input  type=text id=correct_customer_order_id name=customer_order_id  /> </td></tr>
						<tr  height=35>
						<td>物料名称</td><td><input type=hidden id=correct_material_id name=material_id /><input type=text id=correct_material_name name=material_name readonly /></td></tr>
						<tr height=35>
						<td>材料全名</td><td><input type=text id=correct_material_name2 name=material_name2 class=material_name2 readonly /></td></tr>
						<tr height=35>
						<td>规格</td><td><input type=text id=correct_material_specification name=specification class=material_specification readonly /></td></tr>
						<tr height=35>
						<td>规格2</td><td><input type=text id=correct_material_specification2 name=specification2  class=material_specification2 readonly /></td></tr>
						
						<tr height=35>
						<td>数量</td><td><input type=text id=correct_qty name=out_qty  /></td></tr>
						<tr height=35>
						<td>金额</td><td><input type=text id=correct_acmount name=out_amount  /></td></tr>
						<tr height=35>
						<td>仓库</td><td><input type=hidden id=correct_warehouse_id class=warehouse_id name=warehouse_id /><input type=text id=correct_warehouse class=warehouse_name name=warehouse  /></td></tr>
						<tr height=35 >
						<td></td><td></td></tr>
						<tr height=35>
						<td>备注</td><td></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_correct value="更正" /></td></tr>
						</table>
						</form>
						</form>
				  </div>
		</div>
      <!--用于出货要求的pop up--结束 -->
  	  <!--用于反审核的pop up-->
		<div id="revocation_hidden" class="pop_up">
		              <div class="div_title">交货反审核<div class=title_close>关闭</div></div>
			            <div class="table_margin">
				        <form action="" id=form_revocation>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td><input type=text id=revocation_material_name name=material_name readonly /><input type=hidden id=revocation_in_id  name=in_id /><input type=hidden id=revocation_notice_id name=notice_id />
						</td></tr>
						<tr height=35>
						<td>订 单 号</td><td><input  type=text id=revocation_order_id name=order_id  readonly /> </td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td>数量</td><td><input type=text id=revocation_qty name=out_qty  readonly /></td></tr>
						<tr height=35>
						<td>备注</td><td><input type=text id=revocation_remark name=remark /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_revocation value="确定反审核" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于反审核的pop up--结束 -->    
	  <!--用于复核的pop up-->
		<div id="approve_hidden" class="pop_up">
		              <div class="div_title">交货出库审核<div class=title_close>关闭</div></div>
			            <div class="table_margin">
				        
				        <form action="" id=form_approve>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td><input type=text id=approve_material_name name=material_name readonly /><input type=hidden id=approve_material_id name=material_id readonly /><input type=hidden id=approve_in_id  name=in_id /><input type=hidden id=approve_notice_id name=notice_id />
						
						</td></tr>
						<tr height=35>
						<td>订 单 号</td><td><input  type=text id=approve_order_id name=order_id  readonly /> </td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td>数量</td><td><input type=text id=approve_qty name=out_qty  readonly /></td></tr>
						
						<tr height=35>
						<td>备注</td><td><input type=text id=approve_remark name=remark /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_approve value="确定无误" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于复核的pop up--结束 -->          
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
