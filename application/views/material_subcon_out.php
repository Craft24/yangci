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
 * material_out  原材料领用的视图文件
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
   
	var passing;
	var inner;var clickedId=null;var clicked_line_index;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
	
	function build_grid_tr(){        cjTable_tr('material_out_list?subcon=yes&in_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text(),////url of data source
							 'warehouse,supplier_id',0);                                    ///表格高度,需要隐藏的td
                            }	
	function build_grid_tr_add(in_id){cjTable_tr( 'material_out_list?subcon=yes&in_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'warehouse,supplier_id',1);                        ///表格高度,需要隐藏的td
                            }
																
    $(function(){
	   ///////////////////////////validate update///
	   
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','material_out_list?subcon=yes&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,生产单号,派工单号,日期,物料id,物料名,物料全名,规格,规格2,领用数量,单位,批号,仓管员,仓库,领料人,审核人,审核日,委外id,委外供应商',////表格标题
							 '370px','currency_id,warehouse,supplier_id'   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable(  '#grid','material_out_list?subcon=yes&s=0&dates=2'+string,////url and search string 
							 '序号,生产单号,派工单号,日期,物料id,物料名,物料全名,规格,规格2,领用数量,单位,批号,仓管员,仓库,领料人,审核人,审核日,委外id,委外供应商',////表格标题
							 '370px','currency_id,warehouse,supplier_id'   );                                    ///高度,,需要隐藏的td列
                            }					
	   build_grid();
	   bound_search_controls('原料');	
	  //////出库类型
	   $("#out_type").change(function(){switch($(this).val())
	                                           {
											    case "0":$("#tr_production").show();$("#tr_subcontract").hide();break;
											    case "1":$("#tr_production").hide();$("#tr_subcontract").show();break;
												case "2":$("#tr_production").hide();$("#tr_subcontract").hide();break;
											   }
	                                    });
	  							
	  
	  		   
      ////////////update handling
       $(".out").click(
	                      function (){
						             $(':input','#add_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#add_hidden").show();
									 resize("#add_hidden");  resize("#add_hidden");
									 $("#tr_work").hide();
									 $("#tr_production").hide();
									 $("#tr_other").hide();
						             
									 if ($(this).attr("id")=='out_by_production') {$("#tr_production").show();}
									 if ($(this).attr("id")=='out_by_other') {$("#tr_other").show();$("#add_production_id").val('0');$("#other_order").val('其他领料'); }//生产单号码设置为0,则表示零星领料
									 
	                                }
	                           );
	  $("#form_button_add").click(
	                      function (){
						             var data=$("#form_add").serialize();
						             var erro='';
									 erro=verify("#form_add",'qty,数量,digital||applier,领料人,required||material_name,物料名称,required||production_order_id,产品名称,requireded||warehouse_id,仓库,required||warehouse_name,仓库,required');
									 
									 if(erro!=''){note(erro);return false;}
									  var add_url='material_out_add?'+data;///新增操作指向的页面
									  adds_2(add_url);
									                          
	                                });
	                           
      $("#button_search").click(
	                      function(){
									 var 
									 search_string="&"+$("#search1").attr('name')+"="+$("#search1").val();///搜索操作使用的字符串//请替换=号和Search1,2,3的input的name属性
	                                 build_search_grid(search_string);
									 
	                                });
	 
						 						 
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
						   search_string=search_string+'&supplier_id='+$("#search_supplier_id").val();
						   search_string=search_string+'&warehouse='+$("#search_warehouse_id").val();
						   string=search_string;
						   build_search_grid();
						               }else{
									   note(erro);
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
						   search_string=search_string+'&final_product='+$("#search_final_product").val();
						   search_string=search_string+'&warehouse='+$("#search_warehouse_id").val();
						   search_string=search_string+'&supplier_id='+$("#search_supplier_id").val();
						   string=search_string; //alert(string);
						   $.getJSON('material_out_list?subcon=yes&s=1&dates='+Math.floor(Math.random()*9999+1)+'&stat=1&'+string,
									 function(result)
									                { var sum="";
													  $.each(result,function(k,v){
													                              $.each(v,function(kk,vv)
																				          {
																						   if(kk=='amount')   sum+="金额:"+vv+'';
																						   if(kk=='total_qty')sum+="数量:"+(-vv)+'<br>';
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
	   	$("#search_material_name").focus(function(){
	                       if ($(this).val()=='输入品名') $(this).val('');
						    });   
	    $("#search_material_name").keyup(function(){
	                       if ($(this).val().length<1) {$("#selection").remove();$("#search_material_id").val('0');}  
	                       if($(this).val().length>1)
							  {
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light5('selection','material_list?s=1&final_product=R&material_name='+$("#search_material_name").val()+'&dates='+Math.floor(Math.random()*999+1),
							 '材料,全称,规格,规格2,单位',////表格标题
							 '500px','material_id,material_specification,meausrement','yes','#search_material_name,1||#search_material_id,0');
						    $("#selection").show();
							   }
							}
	                     );
						 
		 $("#correct").click(function(){//更正
	                       
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
									 $("#correct_material_name").val($(clickedId).find('td').eq(5).text());
									 $("#correct_material_id").val($(clickedId).find('td').eq(4).text()); 
									 
									 $("#correct_qty_old").val($(clickedId).find('td').eq(7).text()); 
									 $("#correct_production_id").val($(clickedId).find('td').eq(1).text());
									 $("#correct_in_id").val($(clickedId).find('td').eq(0).text());
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('领用数量')").index();
									 $("#correct_qty").val($(clickedId).find('td').eq(col).text());
									 $("#correct_qty_old").val($(clickedId).find('td').eq(col).text());
									  var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('批号')").index();
									 $("#correct_batch").val($(clickedId).find('td').eq(col).text()); 
									 }
						    });			
		
		
         	     ///////////更正						   
	      $("#form_button_correct").click(
	                      function (){
						            
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('委外')").index();///得到审核内容所在的列数
								  	 if ($(clickedId).find('td').eq(col+1).find('div').eq(0).text().length>0) { erro='请到委外加工界面处理';note(erro);return false;}
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col+1).find('div').eq(0).text()!='') erro='应该先反审核';
									 if(erro=='')
									 {
									  	 var url='material_out_correct?';
									     erro=verify("#form_correct","in_qty,数量,digital");	 
										 var data=$("#form_correct").serialize();
										
										 updates(url+data); 
										  
							  		  }else{
									       note(erro);
									       }					 
	                                });
		 	     ///////////审核						   
	  $("#approve").click(
	                      function (){
						            var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col+1).find('div').eq(0).text()!='') erro='不能再审核';
									 if(erro=='')
									 {
									  	 var url='material_out_approve?id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										 
										 updates(url); 
										  
							  		  }else{
									       note(erro);
									       }					 
	                                });
		///////////反审核						   
	  $("#revocation").click(
	                      function (){
						             var erro='';
									
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
								  	 if ($(clickedId).find('td').eq(col+1).find('div').eq(0).text()=='') erro='未审核过';
									 if(erro=='')
									 {
									  	  var url='material_out_revocation?id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										 
										  updates(url);//build_grid_tr();
										 
							  		  }else{
									       note(erro);
									       }					 
	                                });	  				 				 
        $("#sub_relation").click(function(){
	                                       location.href = "subcon_out_check?raw=raw";
						                  });  
	   }) ; 
</script>


<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>原材料委外出库记录</h2><p></p>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		
　　　　<!--查询套件 -><-->
	   <input id=sub_relation type=button value="委外加工对账" />
	   <div class=button_right><form id=searchs action="">
	   
	   <?php include "search_control.php";?>
		</form> 
       </div>
　    <!--查询套件结束 -><-->		   


 
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">原料出库<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update>
						<tr  height=35>
						<td></td><td><input type=hidden name=non_production value=0 />
						             
									 
									
						</td></tr>
						<tr height=35>
						<td>物料名称</td><td><input type=text placeholder="输入物料的前两个字符,可选择" id=update_material_name name=material_name class=material_name autocomplete=off /> <input type=hidden id=update_material_id name=material_id class=material_id /> </td></tr>
						<tr height=35>
						<td>物料全称</td><td><input type=text  id=update_material_name2 class=material_name2 name=material_name2 autocomplete=off readonly /> </td></tr>
						<tr height=35>
						<td>规格</td><td>  <input type=text  id=update_material_specification class=material_specification name=material_specification readonly /> </td></tr>
						<tr height=35>
						<td>规格２</td><td><input type=text  id=update_material_specification2 class=material_specification2 name=material_specification2 readonly /> </td></tr>
						<tr id=tr_production height=35>
						<td>生产单号</td><td><input  type=text class=production_id id=add_production_id placeholder="点击选取相关生产单" name=production_order_id readonly  />
						</td></tr>
						<tr height=35>
						<td>领用数量</td><td><input type=text id=qty name=qty />单位:<input type=text id=measurement name=measurement class=measurement readonly /></td></tr>
						<tr height=35>
						<td>批号</td><td><input type=text id=batch name=batch /></td></tr>
						<tr height=35>
						<td>仓库:</td><td><input type=text id=update_warehouse class=warehouse_name name=warehouse_name   placeholder="点击可选仓库" /><input type=hidden id=update_warehouse_id class=warehouse_id name=warehouse_id   /></td></tr>
						<tr height=35>
						<td>委外供应商</td><td><input type=text id=update_supplier_name name=supplier_name class=supplier placeholder="如果发给委外供应商,请点击" /><input type=hidden id=update_supplier_id name=supplier_id  /></td></tr>
						<tr height=35>
						<td>领用人</td><td><input type=text id=applier name=applier /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于添加的pop up--结束 -->
  	  <!--用于更正的pop up-->
		<div id="correct_hidden" class="pop_up">
		              <div class="div_title">领料更正<div class=title_close>关闭</div></div>
			            <div class="table_margin">
				        <form action="" id=form_correct>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td><input type=text id=correct_material_name name=material_name readonly autocomplete=off /><input type=hidden id=correct_material_id name=material_id readonly /><input type=hidden id=correct_in_id  name=in_id readonly />
						</td></tr>
						<tr height=35>
						<td>生产单号</td><td><input  type=text class=production_id id=correct_production_id name=production_id   /><input  type=hidden id=correct_production_id_old name=production_id_old   /> </td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td>数量</td><td><input type=text id=correct_qty name=in_qty  /><input type=hidden id=correct_qty_old name=in_qty_old  /></td></tr>
						<tr height=35>
						<td>批号</td><td><input type=text id=correct_batch name=batch /></td></tr>
						<tr height=35>
						<td>备注</td><td><input type=text id=correct_remark name=remark /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_correct value="确定更正" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于更正的pop up--结束 -->    	  
  	  
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
/*  material_out.php文件的结尾 */
/*  在系统中的位置: ./application/views/ */
?>
</html>
