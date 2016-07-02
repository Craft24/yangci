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
 * product_out_notice.php 产品发货通知的主视图文件
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
 	function build_grid_tr(){cjTable_tr( 'product_out_notice_list?notice_id='+$(clickedId).find('td').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',0);                        ///表格高度,需要隐藏的td
                            }
    function build_grid_tr_add(in_id){cjTable_tr( 'product_out_notice_list?notice_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',1);                        ///表格高度,需要隐藏的td
                            }

    $(function(){
	   ///////////////////////////validate update///

	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','product_out_notice_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,日期,类型,定单号,客户订单号,物料ID,物料名,物料全名,规格,规格2,数量,通知人,检验合格数,检验员,备注,状态',////表格标题
							 '370px',''   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(){
	                         
							 //alert(string);
	                         cjTable( '#grid','product_out_notice_list?s=1&dates='+string,////url and search string 
							 '序号,日期,类型,定单号,客户订单号,物料ID,物料名,物料全名,规格,规格2,数量,通知人,检验合格数,检验员,备注,状态',////表格标题
							 '370px',''   );                                    ///表格高度,需要隐藏的                                   ///高度,,需要隐藏的td列
                            }					
	  
	  build_grid();
	  bound_search_controls('成品');$("#search_warehouse").hide(); 	
	     ////////////弹出新通知
       $("#notice_new").click(
	                      function (){
						           
						             $("#notice_hidden").show();
									 resize("#notice_hidden");
	                                }
	                           );	   
      ////////////出货弹出
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
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('物料名')").index();///得到审核内容所在的列数
									 $("#update_material_name").val($(clickedId).find('td').eq(col).text()); 
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('物料ID')").index();///得到审核内容所在的列数
									 $("#update_material_id").val($(clickedId).find('td').eq(col).text()); 
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('物料全名')").index();///得到审核内容所在的列数
									 $("#update_material_name2").val($(clickedId).find('td').eq(col).text());
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('规格')").index();///得到审核内容所在的列数
									 $("#update_material_specification").val($(clickedId).find('td').eq(col).text());
									 
									 $("#update_material_specification2").val($(clickedId).find('td').eq(col+1).text());
									
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('数量')").index();///得到审核内容所在的列
									 
									 $("#qty").val($(clickedId).find('td').eq(col).text()); 
									 $("#notice_id").val($(clickedId).find('td').eq(0).text());  
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('类型')").index();///得到审核内容所在的列数
									 $("#material_type").val($(clickedId).find('td').eq(col).text());
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('客户订单号')").index();///得到审核内容所在的列数
									  $("#customer_order_id").val($(clickedId).find('td').eq(col).text()); 
									  $("#add_order_id").val($(clickedId).find('td').eq(col-1).text());
									 }}
	                           );
		     ////////////弹出修改
       $("#product_out_correct").click(
	                      function (){
						             
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
									
									 var selected=$(clickedId).find('td').eq(2).text();
									 switch (selected)
									        { case '良品':  $("#correct_material_type").val('G');break;
											  case '待定品':$("#correct_material_type").val('P');break;
											  case '次品':  $("#correct_material_type").val('D');break;
									        }
									 }
									 ////
									  $("#correct_notice_id").val($(clickedId).find('td').eq(0).text());
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('物料名')").index();///得到审核内容所在的列数
									 $("#correct_material_name").val($(clickedId).find('td').eq(col).text());
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('物料ID')").index();///得到审核内容所在的列数
									  $("#correct_material_id").val($(clickedId).find('td').eq(col).text());
									  
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('物料全名')").index();///得到审核内容所在的列数
									 $("#correct_material_name2").val($(clickedId).find('td').eq(col).text());
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('规格')").index();///得到审核内容所在的列数
									 $("#correct_material_specification").val($(clickedId).find('td').eq(col).text());
									 $("#correct_material_specification2").val($(clickedId).find('td').eq(col+1).text());
									 
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('数量')").index();///得到审核内容所在的列
									 $("#correct_qty").val($(clickedId).find('td').eq(col).text()); 
									 $("#correct_notice_id").val($(clickedId).find('td').eq(0).text());  
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('客户订单号')").index();///得到审核内容所在的列数
									  $("#correct_customer_order_id").val($(clickedId).find('td').eq(col).text()); 
									  $("#correct_order_id").val($(clickedId).find('td').eq(col-1).text());
									   
									 //
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
									 $("#correct_notice_id").val($(clickedId).find('td').eq(0).text());
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('物料名')").index();///得到审核内容所在的列数
									 $("#check_material_name").val($(clickedId).find('td').eq(col).text());
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('物料ID')").index();///得到审核内容所在的列数
									  $("#check_material_id").val($(clickedId).find('td').eq(col).text());
									  
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('物料全名')").index();///得到审核内容所在的列数
									 $("#check_material_name2").val($(clickedId).find('td').eq(col).text());
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('规格')").index();///得到审核内容所在的列数
									 $("#check_material_specification").val($(clickedId).find('td').eq(col).text());
									 $("#check_material_specification2").val($(clickedId).find('td').eq(col+1).text());
									 
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('数量')").index();///得到审核内容所在的列
									 $("#check_qty").val($(clickedId).find('td').eq(col).text()); 
									 $("#check_notice_id").val($(clickedId).find('td').eq(0).text());  
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('客户订单号')").index();///得到审核内容所在的列数
									  $("#check_customer_order_id").val($(clickedId).find('td').eq(col).text()); 
									  $("#check_order_id").val($(clickedId).find('td').eq(col-1).text());
									 
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
									 $.getJSON('product_preparation?order_id='+$("#preparation_order_id").val(),
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
									 { var add_url='product_delivery_check?'+data;///新增操作指向的页面
											updates(add_url);
														
									 }else{
									       note(erro);
									       }					 
							          							 
														 
	                                });
	  ///////////更正						   
	  $("#form_button_correct").click(
	                      function (){
						            
						            
						             var erro='';
									 erro=verify("#form_correct",'');
								  	 if(erro=='')
									 {
									                $.getJSON('product_inventory_check?material_type='+$("#correct_material_type").val()+'&qty='+$("#correct_qty").val()+'&material_id='+$("#correct_material_id").val(),
													function(result) {//alert(result);
																	 if (result<$("#out_qty").val() ) 
																										  {erro="库存不足"; note(erro); return false; }
																									  else{ var add_url='product_notice_correct';///新增操作指向的页面
																											 var data=$("#form_correct").serialize();
																											 alert(data);
																											 updates(add_url,data);
																											
																										  }
																	 });
									 }else{
									       note(erro);
									       }					 
							          							 
														 
	                                });
	 	  ///////////出货						   
	  $("#form_button_add").click(
	                      function (){
						             var data=$("#form_add").serialize();
						             var erro='';
									 erro=verify("#form_add",'out_qty,数量,digital||material_name,产品名称,required||warehouse_id,仓库,required||warehouse_name,仓库,required');
									 //alert(data);
								  	 if(erro=='')
									 {
									 			//alert(data);return false;
												var add_url='product_delivery_finish?'+data;///新增操作指向的页面
												
												updates(add_url);
									 }else{
									       note(erro);
									      }					 
	                                });								                           
      $("#button_search").click(
	                      function(){
									 var 
									 search_string="&"+$("#search1").attr('name')+"="+$("#search1").val();///搜索操作使用的字符串//请替换=号和Search1,2,3的input的name属性
	                                 build_search_grid(search_string);//alert(search_string);
									 
	                                }
	                     );
	 
	  
	  ////如果把订单号输入框删除到全空,则删除下拉列表					 
	 
						 					
	  $("#refresh").click(function(){
	                       build_grid();
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
	   	$("#search_material_name").focus(function(){
	                       if ($(this).val()=='输入品名') $(this).val('');
						    });   
	     $("#search_material_name").keyup(function(){
	                       
	                       if ($(this).val().length<1) {$("#selection").remove();$("#search_material_id").val('0');}  
	                       if($(this).val().length>1)
							  {
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light5('selection','product_list?s=1&material_name='+$("#search_material_name").val()+'&dates='+Math.floor(Math.random()*999+1),
							 'production_id,material_name1',////表格标题
							 '500px','Amaterial_id,material_specification,meausrement','yes','#search_material_name,1||#search_material_id,0');
						    $("#selection").show();
							   }
							}
	                     );

	   })  
	   
	   
	               
</script>


<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>发货通知</h2><p></p>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		<div class=button_left>
		
		<input type="button" id="product_out_correct"  value="更改"/>
		<input type="button" id="product_out_preparation"  value="备货要求"/>
		<input type="button" id="product_out_check"  value="出货检验"/>
	    <input type="button" id="product_out"  value="出 库"/>   
       </div> 
　　　　<!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
		<?php include "search_control.php";?> 
		</form> 
       </div>
　    <!--查询套件结束 -><-->
      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>
 	  <!--用于正式出货的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">出库操作<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        
				        <form action="" id=form_add>
						<table class=table_update>
						<tr  height=35>
						<td>品名</td><td><input type=text class=material_name id=update_material_name name=material_name readonly /><input type=hidden id=update_material_id name=material_id /><input type=hidden id=notice_id name=notice_id /></td></tr>
                        <tr  height=35>
						<td>产品全名</td><td><input type=text class=material_name2 id=update_material_name2 name=material_name2 readonly /></td></tr>
						<tr  height=35>
						<td>规格</td><td><input type=text class=material_specification id=update_material_specification name=material_specification readonly /></td></tr>
                        <tr  height=35>
						<td>规格2</td><td><input type=text class=material_specifications id=update_material_specification2 name=material_specification2 readonly /></td></tr>


						<tr height=35>
						<td>订 单 号</td><td><input  type=text class=order_id id=add_order_id name=order_id readonly /> </td></tr>
						<td>客户订单号</td><td><input  type=text id=customer_order_id name=customer_order_id readonly />                   
						</td></tr>
						<tr height=35>
						<td>发货数量</td><td><input type=text id=qty name=out_qty  /></td></tr>
						<tr height=35>
						<td>产品质量</td><td><input type=text id=material_type name=material_type readonly />
						</td></tr>
						<tr height=35>
						<td>批号</td><td><input type=text id=batch name=batch class=batch />
						</td></tr>
						<tr height=35>
						<td>仓库</td><td><input type=text id=warehouse name=warehouse_name class=warehouse_name /><input type=hidden id=warehouse_id name=warehouse_id  class=warehouse_id />
						</td></tr>
						<tr height=35>
						<td>备注</td><td><input type=text id=remark name=remark /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="出库确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于正式出货的pop up--结束 -->
	    <!--用于出货改正的pop up-->
		<div id="correct_hidden" class="pop_up">
		              <div class="div_title">更正发货通知<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        
				        <form action="" id=form_correct>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td><input type=text class=material_name id=correct_material_name name=material_name class=material_name autocomplete=off /><input type=hidden id=correct_material_id name=material_id  class=material_id /><input type=hidden id=correct_notice_id name=notice_id /></td></tr>
						<tr  height=35>
						<td>产品全名</td><td><input type=text class=material_name2 id=correct_material_name2 name=material_name2 class=material_name2 readonly /></td></tr>
						<tr  height=35>
						<td>规格</td><td><input type=text class=material_specification id=correct_material_specification name=material_specification readonly /></td></tr>
                        <tr  height=35>
						<td>规格2</td><td><input type=text class=material_specification2 id=correct_material_specification2 name=material_specification2 readonly /></td></tr>

						<tr height=35>
						<td>订 单 号</td><td><input  type=text class=order_id id=correct_order_id name=order_id /> </td></tr>
						<tr height=35>
						<td>客户订单号</td><td><input  type=text class=customer_order_id id=correct_customer_order_id name=customer_order_id /> </td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td>通知数量</td><td><input type=text id=correct_qty name=out_qty  /></td></tr>
						<tr height=35>
						<td>产品质量</td><td><select id=correct_material_type name=material_type  />
						                    <option value='G'>良品</option><option value='P'>待定品</option><option value='D'>次品</option>
											</select>
											
						</td></tr>
						<tr height=35>
						<td>备注</td><td><input type=text id=correct_remark name=remark /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_correct value="更正确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于出货更正的pop up--结束 --
 	  <!--用于出货检验的pop up-->
		<div id="check_hidden" class="pop_up">
		              <div class="div_title">发货检验<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        
				        <form action="" id=form_check>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td><input type=text id=check_material_name name=material_name readonly /><input type=hidden id=check_material_id name=material_id /><input type=hidden id=check_notice_id name=notice_id /></td></tr>
						<tr  height=35>
						<td>产品全名</td><td><input type=text class=material_name2 id=check_material_name2 name=material_name2 class=material_name2 readonly /></td></tr>
						<tr  height=35>
						<td>规格</td><td><input type=text class=material_specification id=check_material_specification name=material_specification readonly /></td></tr>
                        <tr  height=35>
						<td>规格2</td><td><input type=text class=material_specification2 id=check_material_specification2 name=material_specification2 readonly /></td></tr>
						<tr height=35>
						<td>订 单 号</td><td><input  type=text id=check_order_id name=order_id readonly /> </td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td>通知数量</td><td><input type=text id=check_qty name=out_qty  /></td></tr>
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
<!--用于添加的pop up-->
		<div id="notice_hidden" class="pop_up">
		          <div class="div_title">新发货通知<div class=title_close></div></div>    
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td><input type=text id=notice_material_name name=material_name autocomplete=off /><input type=hidden id=notice_material_id name=material_id /></td></tr>
						<tr height=35>
						<td>订 单 号</td><td><input  type=text id=notice_order_id name=order_id  /> </td></tr>
						<td>客户订单号</td><td><input  type=text id=notice_customer_order_id name=customer_order_id  />                   
						</td></tr>
						<tr height=35>
						<td>发货数量</td><td><input type=text id=notice_out_qty name=out_qty /></td></tr>
						<tr height=35>
						<td>产品质量</td><td><select name=material_type id=notice_material_type />
						                    <option value=G selected>良品</option>
											<option value=P>待定品</option>
						                    <option value=D>次品</option></select>
						</td></tr>
						<tr height=35>
						<td>备注</td><td><input type=text id=notice_remark name=remark  maxlength="20"/></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_notice value="确认" /></td></tr>
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
<?php include "foot.html" ?>
<?php
/*  product_out_notice.php文件末尾 */
/*  在系统中的位置: ./application/views */
?>
</body>
</html>
