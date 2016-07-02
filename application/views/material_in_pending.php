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
 * material_in_pending 原料暂修改和查询页面的主视图文件
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
	var inner; var clickedId=null;clicked_line_index=null;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
	function build_grid_tr(){cjTable_tr( 'material_pending_list?in_id='+$(clickedId).find('td').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'house_id,supplier_ids',0);                        ///表格高度,需要隐藏的td
                            }
    function build_grid_tr_add(in_id){cjTable_tr( 'material_pending_list?in_id='+in_id+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'house_id,supplier_ids',1);                        ///表格高度,需要隐藏的td
                            }
    $(function(){
	   $("#head").makemenu2(base_url);////顶部菜单
	   
	   function build_grid(){cjTable( '#grid','material_pending_list?dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,日期,采购单,材料id,材料名,材料全名,规格,规格2,暂收数量,单位,批号,供应商,抽样数,不良数,不良率,结果,仓管,状态,检验员,仓库',////表格标题
							 '370px','house_id,supplier_ids'   );   ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','material_pending_list?'+string+'&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,日期,采购单,材料id,材料名,材料全名,规格,规格2,暂收数量,单位,批号,供应商,抽样数,不良数,不良率,结果,仓管,状态,检验员,仓库',////表格标题
							 '370px','house_id,supplier_ids'   );                                    ///表格高度,需要隐藏的td
                            }
	   build_grid(); bound_search_controls('原料');
	   $(".supplier").hide();
	   
	  
      ////////////新加 handling
       $(".out").click(
	                      function (){
						             $(':input','#add_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); $(".supplier").hide();
						             $("#add_hidden").show();$("#add_hidden").hide();
									 resize("#add_hidden");$("#add_hidden").show();
									 $("#tr_work").hide();
									 $("#tr_production").hide();
									 $("#tr_other").hide();
									 if ($(this).attr("id")=='out_by_production') {$("#tr_production").show();}
									 if ($(this).attr("id")=='out_by_other') {$("#tr_other").show();$("#add_production_id").val('0');$("#other_order").val('其他成品入仓检验'); }//生产单号码设置为0,则表示零星领料
	                                }
	                           );
		  $("#correct").click(function(){////更正prop-up
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('入库')").index();
									 if($(clickedId).find('td').eq(col).text()=='已入') {note('已经入库，不能修改');return false;}
									　if($(clickedId).find('td').eq(col).text()=='已退') {note('已经退库，不能修改');return false;}
									
									if(clickedId==null)
									 {
									    $("#error").show();resize("#error");
									 }else{ $(':input','#correct_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#correct_hidden").show();$(".supplier").hide();
									 resize("#correct_hidden");$("#error").hide();
									  
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('材料名')").index();$("#update_material_name").val($(clickedId).find('td').eq(col).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('材料id')").index(); $("#update_material_id").val($(clickedId).find('td').eq(col).text()); 
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('采购单')").index(); $("#update_purchase_id").val($(clickedId).find('td').eq(col).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('序号')").index();   $("#update_in_id").val($(clickedId).find('td').eq(col).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('规格')").index();   $("#update_material_specification").val($(clickedId).find('td').eq(col).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('规格2')").index();  $("#update_material_specification2").val($(clickedId).find('td').eq(col).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('暂收数量')").index();$("#update_qty").val($(clickedId).find('td').eq(col).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('供应商')").index(); $("#update_supplier_name").val($(clickedId).find('td').eq(col).text());
									                                                                                                $("#update_supplier_id").val($(clickedId).find('td').eq(col+1).text());  
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('批号')").index();$("#update_batch").val($(clickedId).find('td').eq(col).text()); 
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('材料全名')").index();$("#update_material_name2").val($(clickedId).find('td').eq(col).text());  
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('单位')").index();$("#update_material_measurement").val($(clickedId).find('td').eq(col).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('仓库')").index();$("#update_warehouse").val($(clickedId).find('td').eq(col+1).text());$("#update_warehouse_id").val($(clickedId).find('td').eq(col+2).text());
									
									 }
						    });										   
		  	$("#transfer").click(
	                      function(){ $("#qty_ok_transfer").attr("disabled",false).show();
									 if (clickedId!=null)
									 {
									
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('检验员')").index();
									 if($(clickedId).find('td').eq(col).text().length<1) return false;
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('入库')").index();
									 if($(clickedId).find('td').eq(col).text()=='已入') return false;
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('结果')").index();
									 if($(clickedId).find('td').eq(col).text()=='退回')  $("#qty_ok_transfer").val('0').hide();
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('批号')").index();
									 $("#batch").val($(clickedId).find('td').eq(col).text());
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('仓库')").index();
									 $("#warehouse_id").val($(clickedId).find('td').eq(col+1).text());
									
									 $("#purchase_id").val($(clickedId).find('td').eq(2).text());
									 $("#material_name").val($(clickedId).find('td').eq(4).text());
									 $("#in_id_transfer").val($(clickedId).find('td').eq(0).text());
									 $("#transfer_hidden").show();
									 resize('#transfer_hidden');
									 }
	                                }
	                     ); 
		  $("#qc").click(function(){/////////品检
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('入库')").index();
									 if($(clickedId).find('td').eq(col).text()=='已入') {note('已经入库，不能再检');return false;}
									　if($(clickedId).find('td').eq(col).text()=='已退') {note('已经退库，不能再检');return false;}
									if(clickedId==null)
									 {
									 $("#error").show();resize("#error");
									 }else{
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('抽样数')").index();
									   if($(clickedId).find('td').eq(col+1).find('div').eq(0).text().length>0){return false;}
									 
									  $(':input','#qc_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#qc_hidden").show();$(".supplier").hide();
									 resize("#qc_hidden");$("#error").hide();
									 $("#qc_qty").val($(clickedId).find('td').eq(5).text()); 
									 $("#qc_in_id").val($(clickedId).find('td').eq(0).text());
									 }
						    });							
		  $("#qc_update").click(function(){/////////品检更改
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('入库')").index();
									 if($(clickedId).find('td').eq(col).text()=='已入') {note('已经入库，不能再检');return false;}
									　if($(clickedId).find('td').eq(col).text()=='已退') {note('已经退库，不能再检');return false;}
									if(clickedId==null)
									 {
									 $("#error").show();resize("#error");
									 }else{
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('抽样数')").index();
									 if($(clickedId).find('td').eq(col).find('div').eq(0).text().length<1){return false;}
									 
									 $(':input','#qc_update_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#qc_update_hidden").show();$(".supplier").hide();
									 resize("#qc_update_hidden");$("#error").hide();
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('抽样数')").index();
									 $("#qc_update_sampling_qty").val($(clickedId).find('td').eq(col+1).find('div').eq(0).text());
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('不良数')").index();
									 $("#qc_update_sampling_defect_qty").val($(clickedId).find('td').eq(col+1).find('div').eq(0).text());
									 $("#qc_update_in_id").val($(clickedId).find('td').eq(0).text());
									 }
						    });	
	      ///////////审核						   
	  $("#approve").click(
	                      function (){
						            
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核')").index()+1;///得到审核内容所在的列数
									// if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') erro='不能再审核';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('结果')").index()+1;///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='通过' && $(clickedId).find('td').eq(col).find('div').eq(0).text()!='特采') erro='品检未通过'; 
									 if(erro=='')
									 {
									  	 var url='material_in_approve?id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										
										 updates(url); 
										  
							  		  }else{
									       note(erro);
									       }					 
	                                });															 
	///////////反审核						   
	  $("#revocation").click(
	                      function (){
						            
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index()+1;///得到审核内容所在的列数
								  	 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()=='') erro='未审核过';
									
									 if(erro=='')
									 {
									  	  var url='material_in_revocation?id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										
										  updates(url);
							  		  }else{
									      note(erro);
									       }					 
	                                });
	  $("#form_button_add").click(
	                      function (){
						             var data=$("#form_add").serialize();
									 var erro=verify("#form_add",'qty,数量,digital||material_name,物料名称,required||material_id,物料名称,required||house_id,仓库,required||supplier_id,供应商,required');
									  if (erro==''){
									  var add_url='material_in_add?'+data;///新增操作指向的页面
	                                  adds_2(add_url);
									  
									 }else{ note(erro);}
	                                }
	                           );
	  $("#form_button_update").click(
	                      function (){
						             var data=$("#form_update").serialize();
									 var erro=verify("#form_update",'material_name,物料名称,required||material_id,物料名称,required||qty,数量,digital||house_id,仓库,required||supplier_id,供应商,required');
									 if (erro==''){
									    var url='material_pending_correct?'+data;///操作指向的页面
	                                    updates(url);
									    }else{ note(erro);}
	                                });
	                           
	  $("#form_button_qc").click(
	                      function (){
						             var data=$("#form_qc").serialize();
									 var erro=verify("#form_qc",'sampling_defect_qty,不良数量,digital||sampling_qty,抽样数量,digital||result_ok,检验结论,required');
									  if (erro==''){
									  var url='material_pending_qc?'+data;///操作指向的页面
	                                  updates(url);
									 }else{ note(erro);}
	                                }
	                           );
	  $("#form_button_qc_update").click(
	                      function (){
						             
						             var data=$("#form_qc_update").serialize();
									 var erro=verify("#form_qc_update",'sampling_defect_qty,不良数量,digital||sampling_qty,抽样数量,digital||result_ok,检验结论,required');
									  if (erro==''){
									  var url='material_pending_qc?'+data;///操作指向的页面
	                                  updates(url);
									 }else{ note(erro);}
	                                }
	                           );
  
		$("#add_material_barcode").change(
		                       function(){
							              $.getJSON('material_list?barcode='+$(this).val(),function(result){
										                                                                $.each(result, function(k, v) {
																																			$.each(v,function(kk, vv) {
																																				                       if(kk=='material_id') $("#add_material_id").val(vv);
																																									   if(kk=='material_name') $("#add_material_name").val(vv);
																																									   if(kk=='material_2name') $("#add_material_name2").val(vv);
																																									   if(kk=='material_specification') $("#add_material_specification").val(vv);
																																									   if(kk=='material_2specification') $("#add_material_specification2").val(vv);
																																									   if(kk=='measurement') $(".measurement").val(vv);
																																									  });
																																				
																																		});
										  
										                                                                 });
							   
							              });
									
	   $(".purchase_id").focus(function(){
	                               
								   $("#selection").remove();
								   $(this).parent().append("<div id=selection class=comboboxnote></div>");
								   cjTable_light5('selection','purchase_list?material_id='+$("#add_material_id").val()+'&dates='+Math.floor(Math.random()*999+1),
									 'Id,采购单号,物料名称,全称,规格,规格2,id,供应商,采购数量,已到达数量,单位,价格,币种,税率,订购日,交货期,审核,审核日期, ',////表格标题
									 '500px','Asupplier_id','yes','.purchase_id,0||.supplier_name,7||.supplier_id,8||.purchase_qty,9||.purchase_qty_arrived,10');
									 $("#selection").show();
							} );     
	  $("#update_production_id").focus(function(){
								   $("#selection").remove();
								   $(this).parent().append("<div id=selection class=comboboxnote></div>");
								   cjTable_light5('selection','production_list_unfinished?material_id='+$("#update_material_id").val()+'&dates='+Math.floor(Math.random()*999+1),
									 'production_id,material_name1',////表格标题
									 '500px','Amaterial_id,material_specification,meausrement','yes','#update_production_id,0');
									 $("#selection").show();
							} );      
	  
	                     		
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
						   search_string=search_string+'&purchase_id='+$("#search_purchase_id").val();
						    search_string=search_string+'&warehouse='+$("#search_warehouse_id").val();
						   string=search_string;
						  // alert(string);
						   build_search_grid();
						               }else{
									   note(erro);
									   }
                                      });			 
	  				 				 
        
		
		
	   })  
</script>
<body>
<div id=container>
<div id=head>
</div>
<div id=main>
  <h2>原材料暂收</h2><p></p>
    <div id=main_left>
	    
	    <div id=grid>		</div>
		<button id=out_by_production class=out>新暂入</button><button id=correct >更改</button>        <button id=qc >品检</button><button id=qc_update >更改</button>       <button id=approve>审核</button>
       <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！	  </div>
	 <!--查询套件 -><-->
	    <div class=button_right><form id=searchs action="">
		<input type=text id=search_purchase_id name=purchase_id class=purchase_id autocomplete=off size=6 placeholder="采购单号"/>
		 <?php include "search_control.php";?>
		</form> 
       </div>
　    <!--查询套件结束 -><--> 
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">暂入<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_add>
						<tr height=35>
						<td>材料名:</td><td><input type=text  id=add_material_name  class=material_name placeholder="输入品名前两位字符,可选择品名" name=material_name autocomplete=off /><input type=hidden id=add_material_id class=material_id name=material_id />或条码:<input type=text id=add_material_barcode autocomplete=off name=material_barcode size=10/></td></tr>
                        <tr height=35>
						<td>材料全称:</td><td><input type=text  id=add_material_name2  class=material_name2  name=material_name2 autocomplete=off /></td></tr>
						<tr height=35>
						<td>规格:</td><td><input type=text id=add_material_specification class=material_specification name=material_specification readonly /></td></tr>
						<tr height=35>
						<td>规格2:</td><td><input type=text id=add_material_specification2 class=material_specification2 name=material_specification2 readonly /></td></tr>
		
						<tr id=tr_production height=35>
						<td>采购单:</td><td><input  type=text id=add_purchase_id class=purchase_id name=purchase_id />
						</td></tr>
						<tr id=tr_production height=35>
						<td>供应商:</td><td><input  type=text id=add_supplier_name name=supplier_name  class=supplier_name /><input  type=hidden id=add_supplier_id name=supplier_id class=supplier_id /><input type=hidden id=add_purchase_qty name=purchase_qty class=purchase_qty /><input type=hidden id=add_purchase_qty_arrived class=purchase_qty_arrived name=purchase_qty_arrived />
						</td></tr>
						<tr height=35>
						<td>到货数量:</td><td><input type=text id=add_qty name=qty />计量单位:<input type=text class=measurement name=measurement id=add_measurement size=6 /></td></tr>
						<tr height=35>
						<td>批号:</td><td><input type=text id=add_batch name=batch  maxlength="32" placeholder="多个批号，应分别入库" /></td></tr>
						<tr height=35>
						<td>仓库:</td><td><input type=text id=add_warehouse class=warehouse_name name=warehouse  maxlength="32" placeholder="点击可选仓库" /><input type=hidden id=add_warehouse_id class=warehouse_id name=warehouse_id  maxlength="32" /></td></tr>

						<tr height=35>
						<td></td><td></td></tr>
						<tr height=35><td colspan=3><input  type=button id=form_button_add value="确认" /></td></tr>
						</table><br />
						</form>
				  </div>
		</div>
      <!--用于添加的pop up--结束 -->
 	  <!--用于更正的pop up-->
		<div id="correct_hidden" class="pop_up">
		              <div class="div_title">更正<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_update>
						<table class=table_add>
						<tr height=35>
						<td>材料名:</td><td><input type=hidden name=in_id id=update_in_id /><input type=text id=update_material_name placeholder="输入品名前两位字符,可选择品名" class=material_name name=material_name autocomplete=off /><input type=hidden id=update_material_id class=material_id name=material_id /></td></tr>
						<tr height=35>
						<td>材料全名:</td><td><input type=text id=update_material_name2 class=material_name name=material_name2 readonly /></td></tr>
			            <tr height=35>
						<td>规格:</td><td><input type=text id=update_material_specification class=material_specification name=material_specification readonly /></td></tr>
			             <tr height=35>
						<td>规格2:</td><td><input type=text id=update_material_specification2 class=material_specification name=material_specification2 readonly /></td></tr>
		                 
						<tr id=tr_production height=35>
						<td>采购单:</td><td><input  type=text id=update_purchase_id class=purchase_id name=purchase_id />
						</td></tr>
						<tr id=tr_production height=35>
						<td>供应商:</td><td><input  type=text id=update_supplier_name name=supplier_name  class=supplier_name /><input  type=hidden id=update_supplier_id name=supplier_id class=supplier_id />
						</td></tr>
						<tr height=35>
						<td>到货数量:</td><td><input type="text" id="update_qty" name="qty">计量单位:<input type=text class=measurement name=measurement id=update_material_measurement size=6 /></td></tr>
						<tr height=35>
						<td>批号:</td><td><input type=text id=update_batch name=batch  maxlength="32" placeholder="多个批号，应分别入库" /></td></tr>
						<tr height=35>
						<td>仓库:</td><td><input type=text id=update_warehouse class=warehouse_name name=warehouse_name  maxlength="32" placeholder="点击可选仓库" /><input type=hidden id=update_warehouse_id class=warehouse_id name=warehouse_id  maxlength="32" /></td></tr>
						<tr height=35>
						<td></td><td></td></tr>
						<tr height=35><td colspan=3><input  type=button id=form_button_update value="确认" /></td></tr>
						</table><br /><input type=hidden id=update_purchase_qty class=purchase_qty name=purchase_qty /><input type=hidden id=update_purchase_qty_arrived class=purchase_qty_arrived name=purchase_qty_arrived />
						</form>
				  </div>
		</div>
      <!--用于添加的pop up--结束 -->
 	  <!--用于品检的pop up-->
		<div id="qc_hidden" class="pop_up">
		              <div class="div_title">品检<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_qc>
						<table class=table_update>
						<tr height=35>
						<td>抽样数量:</td><td><input type=hidden id=qc_in_id name=in_id /><input type=text id=qc_sampling_qty name=sampling_qty /></td></tr>
						<tr height=35>
						<td>不良数量:</td><td><input type=text id=qc_sampling_defect_qty name=sampling_defect_qty /></td></tr>
						<tr height=35>
						<td>结论:</td><td><select id=qc_result_ok name=result_ok /><option value=''>选择结论</option><option value='1'>通过</option><option value='2'>退回</option><option value='3'>特采</option><option value='4'>取消</option></select></td></tr>
						<tr height=35>
						<td></td><td></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_qc value="确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于品检的pop up--结束 -->
 	  <!--用于品检更改的pop up-->
		<div id="qc_update_hidden" class="pop_up">
		              <div class="div_title">品检更正<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_qc_update>
						<table class=table_update>
						<tr height=35>
						<td>抽样数量:</td><td><input type=hidden id=qc_update_in_id name=in_id /><input type=text id=qc_update_sampling_qty name=sampling_qty /></td></tr>
						<tr height=35>
						<td>不良数量:</td><td><input type=text id=qc_update_sampling_defect_qty name=sampling_defect_qty /></td></tr>
						<tr height=35>
						<td>结论:</td><td><select id=qc_update_result_ok name=result_ok /><option value=''>选择结论</option><option value='1'>通过</option><option value='2'>退回</option><option value='3'>特采</option><option value='4'>取消</option></select></select></td></tr>
						<tr height=35>
						<td></td><td></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_qc_update value="确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于品检更改的pop up--结束 -->
	  <!--用于拨转的pop up--结束 -->
		<div id="transfer_hidden" class="pop_up">
		              <div class="div_title">进料拨转<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_transfer>
						<table class=table_transfer>
						<tr height=35><td>
						</td><td>材料名称:<br /><input size=6 type=text id=material_name name=material_name readonly /><br /><br /> 
						<input type=hidden id=in_id_transfer name=in_id readonly />
						<input type=hidden id=purchase_id name=purchase_id readonly />
						<input type=hidden id=batch name=batch readonly />
						<input type=hidden id=warehouse_id name=warehouse_id readonly />
						<input type=hidden id=pending_id name=pending_id readonly />
						</td></tr><tr height=35><td>
						</td><td>
						拨往仓库数量:<br /><input type=text id=qty_ok_transfer name=qty_ok size=6 /><br /><br />
						拨往退料仓库数量:<br /><input type=text id=qty_ng_transfer name=qty_ng size=6 />
						</td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=button_transfer value=提交 /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于拨转pop up--结束 -->
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
/*  material_in_pending文件的结尾 */
/*  在系统中的位置: ./application/views/material_in_pending*/
?>
