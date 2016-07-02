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
 * semi_product_out 半成品出库视图文件
 * @category	welcome
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
  
	var passing;
	var inner; var clickedId=null;var clicked_line_index;
	var search_string;var page_string;var string;var display;
	var base_url='<?php echo base_url();?>';
	function build_grid_tr(){cjTable_tr( 'semi_product_out_list?in_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'A.final_product,supplier_id',0   );                                    ///表格高度,需要隐藏的td
                            }	
	function build_grid_tr_add(in_id){cjTable_tr( 'semi_product_out_list?in_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'A.final_product,supplier_id',1   );                                    ///表格高度,需要隐藏的td
                            }							
    $(function(){
		   function build_grid(){cjTable( '#grid','semi_product_out_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,通知单,日期,类型,定单号,客户订单号,物料ID,物料名,物料全名,规格,规格2,数量,作业人,审核人,审核日期, ,委外供应商,仓库,house_id,生产单',////表格标题
							 '370px','A.final_product,supplier_id'   );                                    ///表格高度,需要隐藏的td
                            }
	       function build_search_grid(search_string){cjTable( '#grid','semi_product_out_list?s=0'+string,////url of data source
							'序号,通知单,日期,类型,定单号,客户订单号,物料ID,物料名,物料全名,规格,规格2,数量,作业人,审核人,审核日期, ,委外供应商,仓库,house_id,生产单',////表格标题
							 '370px','A.final_product,supplier_id'   );                                   ///高度,,需要隐藏的td列
                            }	
	   ///////////////////////////validate update///
	   
	   $("#head").makemenu2(base_url);////顶部菜单
	  
	    build_grid();
		bound_search_controls('成品');
	 ///////////更正						   
	  $("#form_button_correct").click(
	                      function (){
						             var erro='';
									 
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') erro='已经审核,不能更改.';
									 var data=$("#form_correct").serialize();
									 erro=verify("#form_correct",'out_qty,数量,digital||material_name,产品名称,required||material_id,产品名称,required||warehouse,仓库,required||house_id,仓库,required');
                                     // alert(data);
									 if(erro=='')
									 {   
									  	  var url='product_out_correct?'+data;
										  updates(url);
										 
							  		  }else{
									       note(erro);
									       }					 
	                                });
	  		   
      ////////////弹出新通知
       $("#add").click(
	                      function (){ display="add";
						              $(':input','#add_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#add_hidden").show();
									 resize("#add_hidden");$("#error").hide();resize("#add_hidden");
									
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
									 $.getJSON(mainurl+'product_preparation?order_id='+$("#preparation_order_id").val(),
									           function(result){
											                  $.each(result, function(k, v) {
														       
														                                      $("#out_preparation").html(v);
														                                     });             
															    });
									 }
	                                }
	                           );

	  ///////////出库					   
	  $("#form_button_add").click(
	                      function (){
						            //alert($("#material_type").val());
						             var data=$("#form_add").serialize();
						             var erro='';
									 erro=verify("#form_add",'out_qty,数量,digital||material_name,产品名称,required||material_id,产品名称,required||warehouse,仓库,required||house_id,仓库,required');
									 if(erro=='')
									 {  
									    var add_url='semi_product_out_add?'///新增操作指向的页面
										adds_2(add_url);
									 }else{
									       note(erro);
									       }					 
	                                });
	  ///////////检验						   
	  $("#form_button_check").click(
	                      function (){
						            //alert($("#material_type").val());
						             var data=$("#form_check").serialize();
						             var erro='';
									 erro=verify("#form_check",'qty_after_check,数量,digital||material_name,产品名称,required');
								  	 if(erro=='')
									 {
									                $.getJSON(mainurl+'product_inventory_check?material_type='+$("#material_type").val()+'&qty='+$("#qty_after_check").val()+'&material_id='+$("#check_material_id").val(),
													function(result) {
																	 if (result<$("#out_qty").val() ) 
																										  {erro="库存不足"; note(erro); return false; }
																									  else{ var add_url='product_delivery_check?'+data;///新增操作指向的页面
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
						            
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
								  	 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()=='') erro='未审核过';
									 if(erro=='')
									 {
									  	 var url='product_out_revocation?id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										  
										// alert(url);
										 updates(url);
							  		  }else{
									       note(erro);
									       }					 
	                                });
	  ///////////审核						   
	  $("#approve").click(
	                      function (){var erro='';
						           
						             
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
								  	 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') erro='不能审核';
									 if(erro=='')
									 {
									  	 var url='product_out_approve?id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										 updates(url);
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
	  				 				 
       
		$("#form_button_add").submit(function () {
                                       return false;
                                        });								
		
		
		 $("#correct").focus(function(){
	                                 display="update";
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
									 $("#correct_material_name2").val($(clickedId).find('td').eq(8).text());
									 $("#correct_material_specification").val($(clickedId).find('td').eq(9).text());
									 $("#correct_material_specification2").val($(clickedId).find('td').eq(10).text());   
									 $("#correct_material_id").val($(clickedId).find('td').eq(6).text()); 
									 $("#correct_qty").val($(clickedId).find('td').eq(11).text()); 
									 $("#correct_order_id").val($(clickedId).find('td').eq(4).text());
									 $("#correct_warehouse").val($(clickedId).find('td').eq(19).text());
									 $("#correct_warehouse_id").val($(clickedId).find('td').eq(20).text());
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('生产单')").index();
									 
									 $("#update_production_id").val($(clickedId).find('td').eq(col+2).find('div').eq(0).text());
									  var selected=$(clickedId).find('td').eq(3).text();
									 //$("#correct_material_type").find("option[text='待定品']").attr("selected",true);
									 switch (selected)
									        { case '良品':  $("#correct_material_type").val('G');break;
											  case '待定品':$("#correct_material_type").val('P');break;
											  case '次品':  $("#correct_material_type").val('D');break;
									        }
									 resize("#correct_hidden");		
									 }
						    });						
		
		

	   })  
	   
	   
	               
</script>


<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>半成品出库记录</h2><p></p>
    <div id=main_left>
	    <form id=form_print>
	    <div id=grid>
		
		</div>
		</form>
		 <input type="button" id="add"  value="新出库"/>
	    <input type="button" id="product_out_print"  value="打印出库单"/>
		<input type="button" id="correct"  value="更正"/>
		<input type="button" id="approve"  value="审核"/>
		<input type="button" id="revocation"  value="取消审核"/>
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
		              <div class="div_title">半成品领用<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        
				        <form action="" id=form_add>
						<table class=table_update>
						<tr  height=35>
						<td>物料简称</td><td><input type=text id=add_material_name name=material_name class=semi_product_name autocomplete=off placeholder="输入名称的前两个字符" /><input type=hidden id=add_material_id name=material_id class=material_id /></td></tr>
						<tr height=35>
						<td>材料全名</td><td><input type=text id=add_material_name2 name=material_name2 class=material_name2 readonly /></td></tr>
						<tr height=35>
						<td>规格</td><td><input type=text id=add_material_specification name=specification class=material_specification readonly /></td></tr>
						<tr height=35>
						<td>规格2</td><td><input type=text id=add_material_specification2 name=specification2  class=material_specification2 readonly /></td></tr>
						<tr height=35>
						<td>领用数量</td><td><input type=text id=add_out_qty name=out_qty  /></td></tr>
						<tr height=35>
						<td>生产单</td><td><input  type=text id=add_production_id name=production_id class=production_id />
						</td></tr>
						<tr height=35>
						<td>产品质量</td><td><select id=add_material_type name=material_type />
						<option value=G>良品</option>
						<option value=P>待定品</option>
						<option value=D>次品</option>
						</select>
						</td></tr>
						<tr height=35>
						<td>供应商</td><td><input type=text id=add_supplier class=supplier name=supplier placeholder="如果委外加工,请点击"  /><input type=hidden id=add_supplier_id class=supplier_id name=supplier_id  /></td></tr>
						<tr height=35>
						<td>仓库</td><td><input type=text id=add_warehouse class=warehouse_name name=warehouse  /></td></tr>
						<tr height=35 >
						<td></td><td><input type=hidden id=add_warehouse_id class=warehouse_id name=warehouse_id /></td></tr>
						
						<tr height=35>
						<td>备注</td><td><input type=text id=add_remark name=remark /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="确认" /></td></tr>
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
 
 	  <!--用于出货更正的pop up-->
		<div id="correct_hidden" class="pop_up">
		              <div class="div_title">更正<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        
				        <form action="" id=form_correct>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td><input type=text id=correct_material_name name=material_name readonly /><input type=hidden id=correct_material_id name=material_id /><input type=hidden id=correct_in_id name=in_id /></td></tr>
						<tr height=35>
						<td>材料全名</td><td><input type=text id=correct_material_name2 name=material_name2 class=material_name2 readonly /></td></tr>
						<tr height=35>
						<td>规格</td><td><input type=text id=correct_material_specification name=specification class=material_specification readonly /></td></tr>
						<tr height=35>
						<td>规格2</td><td><input type=text id=correct_material_specification2 name=specification2  class=material_specification2 readonly /></td></tr>
						
						<tr height=35>
						<td>数量</td><td><input type=text id=correct_qty name=out_qty  /></td></tr>
						<tr height=35>
						<td>生产单</td><td><input  type=text id=update_production_id name=production_id class=production_id />
						</td></tr>
						<tr height=35>
						<td>状态</td><td>
						<select id=correct_material_type name=material_type />
						<option value=G>良品</option>
						<option value=P>待定品</option>
						<option value=D>次品</option>
						</select>
						</td></tr>
						<tr height=35>
						<td>仓库</td><td><input type=text id=correct_warehouse class=warehouse_name name=warehouse  /></td></tr>
						<tr height=35 >
						<td></td><td><input type=hidden id=correct_warehouse_id class=warehouse_id name=warehouse_id /></td></tr>
						<tr height=35>
						<td>备注</td><td><input type=text id=correct_remark name=remark /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_correct value="更正" /></td></tr>
						</table>
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
