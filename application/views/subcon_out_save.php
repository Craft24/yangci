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
 * subcon_out_save.php 外协发出 主视图文件 
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
	var inner; var clickedId=null;
	var search_string;var page_string;var string; var clicked_line_index;
	var base_url='<?php echo base_url();?>';
	 function build_grid_tr(in_id){      cjTable_tr( 'subcon_list?in_id='+$(clickedId).find('td').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source'
										               'currency_id,warehouse',0); 
									      }
	 function build_grid_tr_add(in_id){  cjTable_tr( 'subcon_list?in_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source'
										               'currency_id,warehouse',1); 
									      }
								
    $(function(){
		   function build_grid(){cjTable( '#grid','subcon_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,日期,物料id,品名,全名,规格,规格2,数量,类型,生产单号,状态,抽样数,不良数,不良率,结果,检验员,委外供应商,供应商id,种类,批号,仓库',
							 '370px','warehouse'   );                                    ///表格高度,需要隐藏的td
                            }
	       function build_search_grid(search_string){cjTable( '#grid','subcon_list?s=0'+string,////url of data source
							'序号,日期,物料ID,品名,全名,规格,规格2,生产单,数量,供应商id,供应商,作业人,审核人,审核日期,in_type,仓库,批号',////表格标题
							 '370px','warehouse'   );                                   ///高度,,需要隐藏的td列
                            }	
	   ///////////////////////////validate update///
	   $("#head").makemenu2(base_url);////顶部菜单
	   build_grid();
	 ///////////更正						   
	  $("#form_button_correct").click(
	                      function (){
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') erro='先反审核,才能更改.';
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('in_type')").index();
									  var in_type= $(clickedId).find('td').eq(col).find('div').eq(0).text();
									 var data=$("#form_correct").serialize();
									 erro=verify("#form_correct",'out_qty,数量,digital||material_name,品名,required');
									 if(parseFloat($("#correct_qty").val())>=0&& (in_type=='2' || in_type=='4' ) )
									                     {note('数量应该是负数'); return e;}								 
									 if(erro=='')
									 {   
									  	  var url='subcontract_out_correct?'+data;
										  updates(url);
										 
							  		  }else{
									       note(erro);
									       }					 
	                                });
      ////////////弹出新通知
       $("#subcon_out_add").click(
	                      function (){
						           
						             $("#add_hidden").show();resize("#add_hidden");$("#add_hidden").hide();$("#add_hidden").show();
									 resize("#add_hidden");$("#error").hide();
									 }
	                               
	                           );
       $("#subcon_out_check").click(
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
	  ///////////发出确认					   
	  $("#form_button_add").click(
	                      function (){
						            //alert($("#material_type").val());
						             var data=$("#form_add").serialize();
						             var erro='';
									 erro=verify("#form_add",'qty,数量,digital||material_name,品名,required||warehouse_id,仓库,required');
								  	 if(erro=='')
									 {
									                var add_url='subcontract_out_add?'+data;///新增操作指向的页面
													adds_2(add_url);
									 }else{
									       note(erro);
									       }					 
	                                });
	 
	                     									 
      $("#subcon_out_print").click(
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
						   search_string=search_string+'&in_o_out='+$("#search_in_o_out").val();
						   
						   string=search_string;
						   build_search_grid();
						               }else{
									   alert(erro);
									   }
                                      });			 
	  				 				 
         $("#stat").click(function(){///汇总统计
						   $("#page").val(0);
						   var erro='';
						   if($("#search_start_date").val().length>0)  erro=verify("#searchs",'start_date,起始日期,isdate');
						   if($("#search_end_date").val().length>0)    erro=verify("#searchs",'end_date,截止日期,isdate');
						   if(erro==''){
						   search_string='&material_id='+$("#search_material_id").val();
						   search_string=search_string+'&start_date='+$("#search_start_date").val();
						   search_string=search_string+'&end_date='+$("#search_end_date").val();
						   search_string=search_string+'&in_o_out='+$("#search_in_o_out").val();
						   string=search_string; //alert(string);
						   $.getJSON('subcon_list?s=1&dates='+Math.floor(Math.random()*9999+1)+'&stat=1&'+string,
									 function(result)
									                { var sum="";
													  $.each(result,function(k,v){
													                              $.each(v,function(kk,vv)
																				          {
																						   if(kk=='amount')   sum+="金额:"+vv+'';
																						   if(kk=='total_qty')sum+="数量:"+vv+'<br>';
																						   if(kk=='currency') sum+="元 "+vv;
																						   });
													                             });
													note(sum);
													 });	
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
	  //////////////选择物料
	   $(".material_name").keyup(function(){
		                      if($(this).val().length>1)
							  {
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							    cjTable_light5('selection','material_list?material_name='+$(this).val()+'&dates='+Math.floor(Math.random()*999+1),////url of data source
								 '序号,品名,全名,规格,规格2',///////表格标题
								 '300px','measurement,safe,moq,material_category,removed,material_barcode,remark',//要隐藏的字段
								 'yes','.material_name,1||.material_name2,2||.material_id,0||.material_specification,3||.material_specification2,4||.measurement,5'  ); ////是否不要标题行
							  }
							  if ($(this).val().length==0) $("#selection").remove();
						   });					 
		 
		 $(".production_id").focus(function(){
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light5('selection','production_list?unfinished=1&dates='+Math.floor(Math.random()*999+1),
							 '生产单号,品名,全称,规格,规格2,数量,截止日期',////表格标题
							 '500px','Amaterial_id,material_specification,meausrement','yes','#add_production_id,0||#correct_production_id,0');
						    $("#selection").show();
							} );
		 
		 $(".production_id").keyup(function(){
		                   if($(this).val().length<1)$("#selection").remove();
                           });	
		 $("#correct").click(function(){
	                       		     var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 var erro='';
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') { erro='应该先反审核'; note(erro);return false;}	  
							         get_tr_content('correct_hidden','form_correct');$("#form_button_correct").val("确认");
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('仓库')").index();
									             $("#correct_warehouse_id").val($(clickedId).find('td').eq(col).text());
												 $("#correct_warehouse").val($(clickedId).find('td').eq(col+1).text());
									 
									 
						              });						
		 $("#revocation").click(function(){
									 var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
								  	 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()=='') erro='不能反审核';
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('数量')").index(); 
									  if ($(clickedId).find('td').eq(col).find('div').eq(0).text()>0) {var in_out='in';}else{var in_out='out';}
									 if(erro=='')
									 {
									  	 var url='subcontract_'+in_out+'_revocation?contract_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										 url+='&material_id='+$(clickedId).find('td').eq(2).text();
										 url+='&out_qty='+$(clickedId).find('td').eq(5).text();
										 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('in_type')").index();
										 url+='&in_type='+$(clickedId).find('td').eq(col).text();
										 alert (url);
										 updates(url);
							  		  }else{
									       note(erro);
									       }					 
	                                });
		 $("#approve").click(function(){
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
								  	 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') erro='不能重复审核';
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('数量')").index(); 
									  if ($(clickedId).find('td').eq(col).find('div').eq(0).text()>0) {var in_out='in';}else{var in_out='out';}
									 if(erro=='')
									 {
									  	 var url='subcontract_'+in_out+'_approve?contract_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										 url+='&material_id='+$(clickedId).find('td').eq(2).text();
										 url+='&out_qty='+$(clickedId).find('td').eq(8).text();
										 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('in_type')").index();
										 url+='&in_type='+$(clickedId).find('td').eq(col).text();
										 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('仓库')").index();
										 url+='&warehouse='+$(clickedId).find('td').eq(col).text();
										 updates(url);
							  		  }else{
									       note(erro);
									       }					 
	                                });
		   $(".get_supplier").click(function(){
		                    $("#selection").remove();
		                    $(this).parent().append("<div id=selection class=comboboxnote></div>");
						    $("#selection").show();
						   cjTable_light5('selection','supplier_list?s=0&dates='+Math.floor(Math.random()*999+1),
							 '序号,供应商,全称,地址,联系人,联系方法',////表格标题
							 '500px','','yes','#add_supplier_id,0||#correct_supplier_id,0||#add_supplier,1||#correct_supplier,1');
						    $("#selection").show();
							});	
	       bound_search_controls('');					                							
	   })  
</script>


<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>委外加工</h2><p></p>
    <div id=main_left>
	    <form id=form_print>
	    <div id=grid>
		
		</div>
		</form>
		
		<button id="subcon_out_print"  value=""/>打印</button>
		
	
　　　　<!--查询套件 -><-->
	   <div class=button_right>
	   <form id=searchs action="">
	   <select id=search_in_o_out name=in_o_out />
		     <option value=all>全部</option>
			 <option value=out>发出</option>
			 <option value=in >收入</option></select>
	   <?php include "search_control.php"; ?>
	   
		</form> 
       </div>
　    <!--查询套件结束 -><-->
      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>
 	  <!--用于正式出货的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">委外发出<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td><input placeholder="输入简称前两个字符，即可选" type=text class=material_name id=add_material_name name=material_name autocomplete=off /><input type=hidden id=add_material_id class=material_id name=material_id /></td></tr>
						<tr height=35>
						<td>物料全称</td><td><input type=text  id=update_material_name2 name=material_name2 class=material_name2 autocomplete=off readonly /> </td></tr>
						<tr height=35>
						<td>规格</td><td>  <input type=text  id=update_material_specification name=material_specification class=material_specification readonly /> </td></tr>
						<tr height=35>
						<td>规格２</td><td><input type=text  id=update_material_specification2 name=material_specification2 class=material_specification2 readonly /> </td></tr>
						<tr height=35>
						<td>生产单号</td><td><input  type=text class=production_id id=add_production_id name=production_id  /> </td></tr>
						<tr height=35>
						<td>发出数量</td><td><input type=text id=add_qty name=out_qty  /> 单位：<input type=text name=measurement size=5 class=measurement /></td></tr>
                        <tr height=35>
						<td>供应商</td><td><input type=text class=get_supplier id=add_supplier name=supplier readonly /><input type=hidden id=add_supplier_id name=supplier_id readonly />
						<tr height=35>
						<td>品质</td><td><select  id=material_type name=material_type /><option value="G">良品</option><option value="D">废品</option><option value="P">待定品</option>
						</td></tr>
						<tr height=35>
						<td>仓库:</td><td><input type=text id=update_warehouse class=warehouse_name name=warehouse_name   placeholder="点击可选仓库" /><input type=hidden id=update_warehouse_id class=warehouse_id name=warehouse_id   /></td></tr>
						<tr height=35>
						<td>备注</td><td><input type=text id=add_remark name=remark /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="出库确认" /></td></tr>
						</table><input type=hidden id=add_final_product name=final_product class=final_product />
						</form>
				  </div>
		</div>
      <!--用于正式出货的pop up--结束 -->

 	  <!--用于出货更正的pop up-->
		<div id="correct_hidden" class="pop_up">
		              <div class="div_title">更正<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_correct>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td>
						<input type=hidden id=correct_contract_id name=contract_id readonly /><input type=hidden id=date name=contract_date readonly />
						<input type=hidden id=correct_material_id name=material_id />
						<input type=text id=correct_material_name name=material_name readonly /></td></tr>
						<tr height=35>
						<td>物料全称</td><td><input type=text  id=correct_material_name2 name=material_name2 class=material_name2 autocomplete=off readonly /> </td></tr>
						<tr height=35>
						<td>规格</td><td>  <input type=text  id=correct_material_specification name=material_specification class=material_specification readonly /> </td></tr>
						<tr height=35>
						<td>规格２</td><td><input type=text  id=correct_material_specification2 name=material_specification2 class=material_specification2 readonly /> </td></tr>
						<tr height=35>
						<td>生产单号</td><td><input  type=text class=production_id id=correct_production_id name=production_id  /> </td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td>发出数量</td><td><input type=text id=correct_qty name=out_qty  /></td></tr>
						<tr height=35>
						<td>供应商</td><td><input type=hidden  id=correct_supplier_id name=supplier_id readonly /><input type=text class=get_supplier id=correct_supplier name=supplier readonly />
						</td></tr>
						<tr height=35>
						<td>仓库</td><td><input type=text id=correct_warehouse class=warehouse_name name=warehouse_name   placeholder="点击可选仓库" /><input type=hidden id=correct_warehouse_id class=warehouse_id name=warehouse_id   /></td></tr>
						<tr height=35>
						<td></td><td></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_correct value="出库更正" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于更正pop up--结束 -->
  	  
    
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
