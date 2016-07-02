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
 * product_in_pending.php成品半成品暂时出入库的视图文 
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
<script src="../../cjquery/src/light6_grid.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<?php 
if (@$_GET['sub'])echo "<script>  var sub='".@$_GET['sub']."'; </script>";///如果是委外入库
?>
<script>

	var passing;
	var inner; var clickedId=null;
	var search_string;var page_string;var string;
	var display;
	var base_url='<?php echo base_url();?>';
	function build_grid_tr(in_id){
	 
	                         cjTable_tr( 'product_in_pending_list?in_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'warehouse,supplier_id,supplier_ids,subcontract_out_id',0);  
							                      
                            }
	function build_grid_tr_add(in_id){cjTable_tr( 'product_in_pending_list?in_id=new'+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'warehouse,supplier_id,supplier_ids,subcontract_out_id',1);                        ///表格高度,需要隐藏的td
                            }
 
    $(function(){
	
	   $("#head").makemenu2(base_url);////顶部菜单
	   bound_search_controls('成品');	
	   function build_grid(){cjTable( '#grid','product_in_pending_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,暂入日期,物料id,品名,全名,规格,规格2,数量,类型,生产单号,状态,抽样数,不良数,不良率,结果,检验员,委外供应商,种类,入库,批号,仓库,退货,审核',////表格标题
							 '370px','currency_id,warehouse,supplier_ids,subcontract_out_id'   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','product_in_pending_list?string='+string+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,暂入日期,物料id,品名,全名,规格,规格2,数量,类型,生产单号,状态,抽样数,不良数,不良率,结果,检验员,委外供应商,种类,入库,批号,仓库,退货,审核',////表格标题
							 '370px','currency_id,warehouse,supplier_ids,subcontract_out_id'   );                                    ///表格高度,需要隐藏的td
                            }
	   build_grid();
	   
	   $(".bordered").hide();///把委外供应商隐藏
	   $(".is_subcontract").click(function(){ if ($(this).is(':checked')){
	                                                                     $(".supplier").show();$(".bordered").show();}else{
																		 $(".supplier").hide();
																		 $(".bordered").hide();
																		 $(".supplier_id").val('');
																		  $(".supplier").val('');
																		 $(".class").remove;} 
	                                         });
      ////////////新加 handling
          $(".out").click(
	                      function (){
						             display="add";
									 $(':input','#add_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); $(".supplier").hide();
						             $("#add_hidden").show();resize("#add_hidden");
									 resize("#add_hidden");
									 $("#tr_work").hide();
									 $("#tr_production").hide();$(".add").remove();$(".bordered").hide();
									 $("#tr_other").hide();
									 if ($(this).attr("id")=='out_by_production') {$("#tr_production").show();}
									 if ($(this).attr("id")=='out_by_other') {$("#tr_other").show();$("#add_production_id").val('0');$("#other_order").val('其他成品入仓检验'); }//生产单号码设置为0,则表示零星领料
	                                });
		  $("#correct").click(function(){////更正prop-up
		                             display="update";
									 $("#bordered_correct").hide();$("#bordered_correct").insertAfter("#form_update");
									 ////有抽样,即不能修改
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('抽样数')").index();
									 if($(clickedId).find('td').eq(col).find('div').eq(0).text().length>0){return false;}
									 /////清理表单
									 if(clickedId==null )
									 {
									 $("#error").show();resize("#error");
									 }else{ $(':input','#correct_hidden')  ///清空所有input
										   .not(':button, :submit, :reset')  
										   .val('')  
										   .removeAttr('checked')  
										   .removeAttr('selected');
									 $(".add").remove();$("br").remove();	    
						             $("#correct_hidden").show();$("#qc_hidden").hide();
									 $(".supplier").hide();
									 resize("#correct_hidden");$("#error").hide();
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('类型')").index();
									 //填充表单
									 get_tr_content("correct_hidden","form_update");
									 
									 $("#form_button_update").val('确定修改'); 
									  if($("#update_material_type_hidden").val()=='良品')
																			 { $("#update_material_type option[value='G']").attr("selected",true);}
									  if($("#update_material_type_hidden").val()=='待定品')
																			 { $("#update_material_type option[value='P']").attr("selected",true);}
									  if($("#update_material_type_hidden").val()=='废品')
																			 { $("#update_material_type option[value='D']").attr("selected",true);}																			 												                               
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('委外供应商')").index();
								  	 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') {$(".supplier").show();
									                                                                    $("#update_supplier_id").val($(clickedId).find('td').eq(col+1).text());
																										$("#update_supplier").val($(clickedId).find('td').eq(col).text());
									                                                                    }
									 }
									////把委托外出加工情况的div从后部移到前部
									 $("#bordered_correct").insertAfter("#update_is_subcontract_in");
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核')").index();
									 var ids=$(clickedId).find('td').eq(col+3).find('div').eq(0).text();///发出委托加工时的出库id
									////如果当初有委托外出，则显示出来
									 if(ids.length>0){
									                  
									                  get_subcon_ids(ids,"#update_subcon_ids");
									 
									                  }
									 
									 });	
	 
		  $("#qc").click(function(){/////////品检
										
									
									if(clickedId==null)
									 {
									 $("#error").show();resize("#error");
									 }else{ 
									 
												 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核')").index();///得到审核内容所在的列数
												 var erro='';
												 if ($(clickedId).find('td').eq(col+2).find('div').eq(0).text()!='') var erro='先反审核';
												 if( erro!='') note(erro);
																			
												  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('抽样数')").index();
												 if($(clickedId).find('td').eq(col).find('div').eq(0).text().length>0){return false;}
												 
												  $(':input','#qc_hidden')  ///清空所有input
													 .not(':button, :submit, :reset')  
													 .val('')  
													 .removeAttr('checked')  
													 .removeAttr('selected'); 
												 $("#qc_hidden").show();$(".supplier").hide();
												 resize("#qc_hidden");$("#error").hide();
												
												 $("#qc_qty").val($(clickedId).find('td').eq(5).text()); 
												 $("#qc_in_id").val($(clickedId).find('td').eq(0).text());
									 } });
		  $("#qc_update").click(function(){/////////品检更改
									if(clickedId==null)
									 {
									 $("#error").show();resize("#error");
									 }else{
									 
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核')").index();///得到审核内容所在的列数
									 var erro='';
									 if ($(clickedId).find('td').eq(col+2).find('div').eq(0).text()!=''){ var erro='先反审核';return false;}
									 if( erro!='') note(erro);
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
									 $("#qc_update_sampling_qty").val($(clickedId).find('td').eq(col).find('div').eq(0).text());
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('不良数')").index();
									 $("#qc_update_sampling_defect_qty").val($(clickedId).find('td').eq(col).find('div').eq(0).text());
									 $("#qc_update_in_id").val($(clickedId).find('td').eq(0).text());
									 }
						    });											 
	  $("#form_button_add").click(
	                      function (){
						             var data=$("#form_add").serialize();
						             if($("#add_final").val().indexOf('半成品')<0 && $("#add_production_id").val().length<1) {note('请填写生产单号');return false;} 
									 var erro=verify("#form_add",'qty,数量,digital||material_type,品质,required||material_name,品名,required||warehouse_id,仓库,required||warehouse_name,仓库名称,required');
									 
									 if (erro==''){
													  var add_url='product_in_pending_add';///新增操作指向的页面
													  adds_2(add_url);
									                }else{ note(erro);}
	                                });
	  $("#form_button_update").click(
	                      function (){
						             var data=$("#form_update").serialize();
						             if($("#update_final").val().indexOf('半成品')<0 && $("#update_production_id").val().length<1) {note('请填写生产单号');return false;} 
									 var erro=verify("#form_update",'qty,数量,digital||material_type,品质,required||material_name,品名,required||warehouse_id,仓库,required||warehouse_name,仓库名称,required');
									  if (erro==''){
									  var url='product_in_pending_update?'+data;///操作指向的页面
	                                  updates(url);
									 }else{ note(erro); }
	                                }
	                           );
	  $("#form_button_qc").click(
	                      function (){
						             var data=$("#form_qc").serialize();
									 var erro=verify("#form_qc",'qty_ng,不良数量,digital||sampling_qty,抽样数量,digital||defect_how,结论,required');
									  if (erro==''){
									  var url='product_in_pending_qc?'+data;///操作指向的页面
	                                  updates(url);
									 }else{ note(erro);}
	                                });
	  $("#form_button_qc_update").click(
	                      function (){
						             var data=$("#form_qc_update").serialize();
									 var erro=verify("#form_qc_update",'qty_ng,不良数量,digital||sampling_qty,抽样数量,digital||defect_how,结论,required');
									  if (erro==''){
									  var url='product_in_pending_qc_update?'+data;///操作指向的页面
	                                  updates(url);
									 }else{ note(erro);}
	                                });
      $("#button_search").click(
	                      function(){
									 var 
									 search_string="&"+$("#search1").attr('name')+"="+$("#search1").val();///搜索操作使用的字符串//请替换=号和Search1,2,3的input的name属性
	                                 build_search_grid(search_string);//alert(search_string);
	                                }); 
	  $("#update_production_ids").focus(function(){
	                              
								   $("#selection").remove();
								   $(this).parent().append("<div id=selection class=comboboxnote></div>");
								   cjTable_light5('selection','production_list_unfinished?material_id='+$("#update_material_id").val()+'&dates='+Math.floor(Math.random()*999+1),
									 'production_id,material_name1',////表格标题
									 '500px','Amaterial_id,material_specification,meausrement','yes','#update_production_id,0');
									 $("#selection").show();
							     }); 
	   
	   $(".subcontract_out_id").focus(function(){
	                              
								   $("#selection").remove();
								   $(this).parent().append("<div id=selection class=comboboxnote></div>");
								   cjTable_light5('selection','subcontract_out_id?material_id='+$(".material_id").val()+'&supplier_id='+$(".supplier_id").val()+'&dates='+Math.floor(Math.random()*999+1),
									 '序号,品名,全称,规格,规格2,发出数量,发出日期,供应商',////表格标题
									 '500px','Amaterial_id,material_specification','yes','#update_production_id,0');
									 $("#selection").show();
							     }); 
	   $(".subcon_ids").focus(function(){
	                              
								   $("#selection").remove();
								   $(this).parent().append("<div id=selection class=comboboxnote></div>");
								   if(display=='add') {var material_id=$("#add_material_id").val();var supplier_id=$("#supplier_id").val();}
								   if(display=='update'){var material_id=$("#update_material_id").val();var supplier_id=$("#update_supplier_id").val();}  
								   //alert(material_id);alert(supplier_id);
								   cjTable_light6('selection','subcontract_out_id?material_id='+material_id+'&supplier_id='+supplier_id+'&dates='+Math.floor(Math.random()*999+1),
									 '序号,品名,全称,规格,规格2,发出数量,发出日期,供应商',////表格标题
									 '500px','','yes','#update_production_id,0');
									 $("#selection").show();
							     }); 
	    ////////////transfer handling  ok品入库,ng拨往退库
	   $("#button_transfer").click(
	                      function (){
									 ///祛除空格
									 $("#qty_ok_transfer").val(  $.trim( $("#qty_ok_transfer").val() ));//+
									 $("#qty_ng_transfer").val(  $.trim( $("#qty_ng_transfer").val() ));//+
									 $("#in_id_transfer").val($(clickedId).find('td').eq(0).text());
									 var data=$("#form_transfer").serialize();
						             var erro=verify("#form_transfer",'qty_ok,拨往仓库数量,digital');
									 if (erro==''){
									 var url='product_in_add?'+data;
	                                 updates(url);
									 }else{ note(erro);}
	                                });	
	    ///////////审核						   
	  $("#approve").click(
	                      function (){
						            
						             var erro='';
									 //var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('供应商id')").index();///得到审核内容所在的列数
								  	 //if ($(clickedId).find('td').eq(col).find('div').eq(0).text()>0) {var erro='请到委外加工界面处理';note(erro);return false;}

									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核')").index();///得到审核内容所在的列数
									 //alert($(clickedId).find('td').eq(col+2).find('div').eq(0).text());
									 if ($(clickedId).find('td').eq(col+2).find('div').eq(0).text()!='') erro='不能再审核';
									 var type='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('结果')").index();
									 //alert($(clickedId).find('td').eq(col).find('div').eq(0).text());
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='通过' && $(clickedId).find('td').eq(col).find('div').eq(0).text()!='特采') erro='不能审核';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('类型')").index();
									 switch ($(clickedId).find('td').eq(col).text()) 
									               {
														case "良品":   type= 'G';break;
														case "待定品": type= 'P';break;
														case "次品":   type= 'D';break;
													}
									 var shuliang=$("#table_title").find('tr').eq(0).find('th').filter(":contains('数量')").index(); 
									 
									 if(erro=='')
									 {
									  	 var url='product_in_approve?id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										 url+='&production_id='+$(clickedId).find('td').eq(7).text();
										 url+='&in_qty='+$(clickedId).find('td').eq(shuliang).text();
										 url+='&material_type='+type;
									 	 url+='&material_id='+$(clickedId).find('td').eq(2).text(); 
										 updates(url); //build_grid_tr();
										  
							  		  }else{
									       note(erro);
									       }					 
	                                });

	   $("#transfer").click(
	                      function(){ $("#qty_ok_transfer").attr("disabled",false);
									 if (clickedId!=null)
									 {
									
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('检验员')").index();
									 if($(clickedId).find('td').eq(col).text().length<1) return false;
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('入库')").index();
									 if($(clickedId).find('td').eq(col).text()=='已处理') return false;
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('结果')").index();
									 if($(clickedId).find('td').eq(col).text()!='通过'&& $(clickedId).find('td').eq(col).text()!='特采')  return false;
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('数量')").index();
									  $("#qty_ok_transfer").val($(clickedId).find('td').eq(col).text());
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('生产单号')").index();
									  $("#production_id_transfer").val($(clickedId).find('td').eq(col).text());
									 $("#material_name_transfer").val($(clickedId).find('td').eq(3).text());
									
									 $("#in_id_transfer").val($(clickedId).find('td').eq(0).text());
									 $("#transfer_hidden").show();
									 resize('#transfer_hidden');
									 }
	                                }
	                     ); 													 
	  	  ///选择
           $(".material_typeS").focus(function(){
	                               //if($("#add_final").val()=='成品')
								   //{
								   $("#selection").remove();
								   $(this).parent().append("<div id=selection class=comboboxnote></div>");
								   cjTable_light5('selection','op?options=G,良品||D,报废品||P,待定品&'+'s=0&dates='+Math.floor(Math.random()*999+1),
									 ' ,品质类型',////表格标题
									 '500px','','yes','.material_type,0');
									 $("#selection").show();
							      //}
							});  
		  $(".subcon_ids_clear").click(function(){
		                                          $(".add").remove();$(this).parent().find('br').remove();
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
						   search_string=search_string+'&warehouse='+$("#search_warehouse_id").val();
						   search_string=search_string+'&supplier_id='+$("#search_supplier_id").val();
						   string=search_string;
						   
						   build_search_grid();
						               }else{
									   note(erro);
									   }
									   
                                      });			 
	  				 				 
        $("#searchs").submit(function () {
                                       return false;
                                        }); 
			
		
		 if(sub=='yes') {$("#add_hidden").show();resize("#add_hidden");}
		} ); 					 
</script>
<body>
<div id=container>
<div id=head>
</div>
<div id=main>
  <h2>成品（半成品）暂收入库</h2><p></p>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		<button id=out_by_production class=out>新暂入</button><button id=correct >更改</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <button id=qc >品检</button><button id=qc_update >更改</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="approve"  value="入库审核"/>
       <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>
	 <!--查询套件 -><-->
	    <div class=button_right><form id=searchs action="">
		
	  
		<?php include "search_control.php"; ?>
		</form> 
       </div>
　    <!--查询套件结束 -><--> 	  
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">（半）成品暂入<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_add>
						<tr height=35>
						<td>入库品名</td><td><input type=text id=add_material_name class=product_name name=material_name placeholder="输入料号的前2位字符" autocomplete=off /><input type=hidden id=add_material_id name=material_id class=material_id /><input type=hidden id=add_final class=final name=final /></td></tr><tr id=tr_production height=35>
                        <tr height=35>
						<td>物料全称</td><td><input type=text  id=add_material_name2 name=material_name2 class=material_name2 autocomplete=off readonly /> </td></tr>
						<tr height=35>
						<td>规格</td><td>  <input type=text  id=add_material_specification name=material_specification class=material_specification readonly /> </td></tr>
						<tr height=35>
						<td>规格２</td><td><input type=text  id=add_material_specification2 name=material_specification2 class=material_specification2 readonly /> </td></tr>
						<td>生产单</td><td><input  type=text id=add_production_id name=production_id class=production_id />
						</td></tr>
						<tr height=35>
						<td>数量</td><td><input type=text id=qty name=qty /> 单位：<input type=text name=measurement size=5 class=measurement /></td></tr>
						<tr height=35>
						<td>委外加工供应商</td><td><input type=text  class=supplier placeholder="点击选择供应商" id=supplier name=supplier /><input type=hidden id=supplier_id name=supplier_id class=supplier_id /><input type=checkbox id=is_subcontract_in class=is_subcontract />
						<div class=bordered>委外发出时的ID<p><input type=button class=subcon_ids id=subcon_ids name=subcon_ids value='点击选取' /><input type=button class=subcon_ids_clear id=subcon_ids_clear name=subcon_ids value='清除' /</div>
						 </td></tr>
						</td></tr>
						<tr height=35>
						<td>品质</td><td><select name=material_type />
						                <option value=''></option>
						                <option value=G>良品</option>
										<option value=D>报废品</option>
										<option value=P>待定品</option>
										</select>
						</td></tr>
						<tr height=35>
						<td>批号:</td><td><input type=text id=add_batch class=batch name=batch   placeholder="不同批号应分开入库" /></td></tr>

						<tr height=35>
						<td>仓库:</td><td><input type=text id=add_warehouse class=warehouse_name name=warehouse_name   placeholder="点击可选仓库" /><input type=hidden id=add_warehouse_id class=warehouse_id name=warehouse_id   /></td></tr>

						<tr height=35>
						<td>送检人</td><td><input type=text name=supplier id=add_supplier /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于添加的pop up--结束 -->
 	  <!--用于添加的pop up-->
		<div id="correct_hidden" class="pop_up">
		              <div class="div_title">更正<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_update>
						<table class=table_update>
						<tr height=35>
						<td>入库品名</td><td><input type=hidden id=update_in_id name=in_id /><input type=hidden id= name=nouse /><input type=hidden id=update_material_id class=material_id name=material_id /><input type=text id=update_material_name class=product_name name=material_name placeholder="输入料号的前2位字符" autocomplete=off /></td></tr><tr id=tr_production height=35>
						<tr height=35>
						<td>物料全称</td><td><input type=text  id=update_material_name2 name=material_name2 class=material_name2 autocomplete=off readonly /> </td></tr>
						<tr height=35>
						<td>规格</td><td>  <input type=text  id=update_material_specification name=material_specification class=material_specification readonly /> </td></tr>
						<tr height=35>
						<td>规格２</td><td><input type=text  id=update_material_specification2 name=material_specification2 class=material_specification2 readonly /> </td></tr>
						<tr height=35>
						<td>数量</td><td><input type=text id=update_qty name=qty /></td></tr>
						<tr height=35>
						<td>品质</td><td><select name=material_type id=update_material_type />
						                <option value=''></option>
						                <option value=G>良品</option>
										<option value=D>报废品</option>
										<option value=P>待定品</option>
										</select><input type=hidden id=update_material_type_hidden name=material_type2 />
						</td></tr>
						<td>生产单</td><td><input type=text id=update_production_id name=production_id class=production_id  />
						                  <input type=hidden id=update_material_type3 name=nouse   />
										  <input type=hidden id=update_material_type4 name=nouse   />
										  <input type=hidden id=update_material_type5 name=nouse   />
										  <input type=hidden id=update_material_type5 name=nouse   /><input type=hidden id=update_material_type4 name=nouse   />
						</td></tr>
						<tr height=35>
						<td>委外加工供应商</td><td><input type=text  class=supplier placeholder="点击选择供应商" id=update_supplier name=supplier /><input type=hidden id=update_supplier_id name=supplier_id class=supplier_id /><input type=checkbox id=update_is_subcontract_in class=is_subcontract />
						
						 </td></tr>
						</td></tr>
						<tr height=35>
						<td>品质</td><td><select name=material_type />
						                <option value=''></option>
						                <option value=G>良品</option>
										<option value=D>报废品</option>
										<option value=P>待定品</option>
										</select>
						</td></tr>
						<tr height=35>
						<td>类型</td><td><input type=text id=update_final class=final name=final /> <input type=hidden id=update_material_type3 name=nouse   /></td></tr>
						<tr height=35>
						<td>批号:</td><td><input type=text id=update_batch class=batch name=batch   placeholder="不同批号应分开入库" /></td></tr>

						<tr height=35>
						<td>仓库:</td><td><input type=text id=update_warehouse class=warehouse_name name=warehouse_name   placeholder="点击可选仓库" /><input type=hidden id=update_warehouse_id class=warehouse_id name=warehouse_id   /></td></tr>

						<tr height=35>
						<td></td><td></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_update value="确认" /></td></tr>
						</table><div class=bordered id=bordered_correct>委外发出时的ID<p><input type=button class=subcon_ids id=update_subcon_ids name=subcon_ids value='点击选取' /><input type=button class=subcon_ids_clear id=update_subcon_ids_clear name=subcon_ids value='清除' /</div>
						</form>
				  </div>
		</div></div>
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
						<td>不良数量:</td><td><input type=text id=qty_ng name=qty_ng /></td></tr>
						<tr height=35>
						<td>结论:</td><td><select id=defect_how name=defect_how /><option value=''>选择结论</option><option value='1'>通过</option><option value='2'>退回</option><option value='3'>特采</option></select></td></tr>
						
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
						<td>不良数量:</td><td><input type=text id=qc_update_sampling_defect_qty name=qty_ng /></td></tr>
						<tr height=35>
						<td>结论:</td><td><select id=defect_how name=defect_how /><option value=''>选择结论</option><option value='1'>通过</option><option value='2'>退回</option><option value='3'>特采</option></select></td></tr>
						
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
		         <div class="div_title">成品半成品拨转<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_transfer>
						<table class=table_transfer>
						<tr height=35><td>
						</td><td>名称:<br /><input size=10 type=text id=material_name_transfer name=material_name readonly /><br /><br /> 
						<input type=hidden id=in_id_transfer name=in_id readonly />
						<input type=hidden id=production_id_transfer name=production_id readonly />
						</td></tr><tr height=35><td>
						</td><td width=300>
						拨往仓库数量:<br /><input type=text id=qty_ok_transfer name=qty_ok size=6/ value=0 readonly><br /><br />
						</td></tr>
						
						
						<tr height=35><td colspan=3><input class=button type=button id=button_transfer value=确定 /></td></tr>
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
<?php
/*  product_in_pending 文件的结尾 */
/*  在系统中的位置: ./application/views*/
?>
</html>
