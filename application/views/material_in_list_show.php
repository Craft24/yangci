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
 * material_in_list_show 进料明细修改和查询页面的主视图文件
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
	var passing;var clickedId=null;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
    									
    function build_grid_tr(in_id){cjTable_tr('material_in_list?in_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'production_id,house_id,supplier_id',0);   
	                     ///表格高度,需要隐藏的td
                            }
	function build_grid_tr_add(in_id){cjTable_tr( 'material_in_list?in_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'production_id,house_id,supplier_id',1);                        ///表格高度,需要隐藏的td
                            }

	$(function(){
	   $("#head").makemenu2(base_url);////顶部菜单
	    bound_search_controls('原料');
	   function build_grid(){cjTable( '#grid','material_in_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '进料ID,日期,原材料id,原材料名,原材料全名,规格,规格2,入库数量,单位,批号,采购单,入库作业员,审核人,审核日期,生产退料,暂进ID,仓库,供应商',////表格标题
							 '300px','supplier_id,currency_id,production_id,house_id,supplier_id'   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','material_in_list?s=2'+string,////url and search string 
							 '进料ID,日期,原材料id,原材料名,原材料全名,规格,规格2,入库数量,单位,批号,采购单,入库作业员,审核人,审核日期,生产退料,暂进ID,仓库,供应商',////表格标题
							 '300px','supplier_id,currency_id,production_id,house_id,supplier_id'   );                                    ///高度,,需要隐藏的td列
                            }
	    build_grid();
	   
      ////////////update handling
	  $("#button_update").click(
	                      function (){
						             var data=$("#form_correct").serialize();
						             var erro=verify("#form_correct",'in_qty,数量,digital');
									  if (erro==''){
									 var update_url='material_in_add';///入库
	                                 updates(update_url,data);
									 
									 }else{ note(erro);}
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
						    search_string=search_string+'&purchase_id='+$("#search_purchase_id").val();
						    search_string=search_string+'&warehouse='+$("#search_warehouse_id").val();
							search_string=search_string+'&supplier_id='+$("#search_supplier_id").val();
						   string=search_string;
						   build_search_grid();
						               }else{
									   alert(erro);
									   }
                                      });			 
        $("#searchs").submit(function () {
                                       return false;
                                        }); 
		
            
		 $("#correct").click(function(){
	                       
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
									  $("#correct_pending_id").val($(clickedId).find('td').eq(14).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('材料名')").index();$("#update_material_name").val($(clickedId).find('td').eq(col).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('材料id')").index(); $("#update_material_id").val($(clickedId).find('td').eq(col).text()); 
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('采购单')").index(); $("#update_purchase_id").val($(clickedId).find('td').eq(col+1).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('序号')").index();   $("#update_in_id").val($(clickedId).find('td').eq(col).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('规格')").index();   $("#update_material_specification").val($(clickedId).find('td').eq(col).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('规格2')").index();  $("#update_material_specification2").val($(clickedId).find('td').eq(col).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('入库数量')").index();$("#update_qty").val($(clickedId).find('td').eq(col+1).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('批号')").index();$("#update_batch").val($(clickedId).find('td').eq(col+1).text()); 
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('材料全名')").index();$("#update_material_name2").val($(clickedId).find('td').eq(col).text());  
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('单位')").index();$("#update_material_measurement").val($(clickedId).find('td').eq(col+1).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('仓库')").index();$("#update_warehouse").val($(clickedId).find('td').eq(col+1).text());$("#update_warehouse_id").val($(clickedId).find('td').eq(col+1).text());
									 
									 }
						    });			
		
		 $("#approve").click(function(){
	                       
									if(clickedId==null)
									 {
									 $("#error").show();resize("#error");
									 }else{ $(':input','#approve_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#approve_hidden").show();
									 resize("#approve_hidden");$("#error").hide();
									 $("#approve_material_name").val($(clickedId).find('td').eq(3).text());
									 $("#approve_material_id").val($(clickedId).find('td').eq(2).text()); 
									 
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('入库数量')").index();$("#approve_qty").val($(clickedId).find('td').eq(col+1).text());
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('采购单')").index();    $("#approve_purchase_id").val($(clickedId).find('td').eq(col+1).text());
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('仓库')").index();    $("#approve_warehouse_id").val($(clickedId).find('td').eq(col+2).text());

									 $("#approve_in_id").val($(clickedId).find('td').eq(0).text());
									 $("#approve_pending_id").val($(clickedId).find('td').eq(14).text());
									 
									 }
						    });	
	 	
	   $(".purchase_id").focus(function(){
	                               
								   $("#selection").remove();
								   $(this).parent().append("<div id=selection class=comboboxnote></div>");
								   cjTable_light5('selection','purchase_list?material_id='+$("#update_material_id").val()+'&dates='+Math.floor(Math.random()*999+1),
									 'Id,采购单号,物料名称,规格,id,供应商,采购数量,已到达数量,单位,价格,币种,税率,订购日,交货期,审核,审核日期, ',////表格标题
									 '500px','Asupplier_id','yes','.purchase_id,0||.supplier_name,5||.supplier_id,6||.purchase_qty,6||.purchase_qty_arrived,7');
									 $("#selection").show();
							} );								
	 	     ///////////更正						   
	  $("#form_button_correct").click(
	                      function (){
						             
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 
									 if ($(clickedId).find('td').eq(col+1).find('div').eq(0).text()!='') erro='应该先反审核';
									 if(erro=='')
									 {
									  	 var url='material_in_correct?';
									     erro=verify("#form_correct","in_qty,数量,digital");	 
										 var data=$("#form_correct").serialize();
										
										 updates(url+data); 
										  
							  		  }else{
									       note(erro);
									       }					 
	                                });
		 	     ///////////审核						   
	  $("#form_button_approve").click(
	                      function (){
						            
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核')").index()+1;///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') erro='不能再审核';
									 
									 if(erro=='')
									 {
									  	 var url='material_in_approve?id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('采购单')").index()+1;
										 url+='&purchase_id='+$(clickedId).find('td').eq(col).find('div').eq(0).text();
										 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('入库数量')").index()+1;
										 url+='&in_qty='+$(clickedId).find('td').eq(col).find('div').eq(0).text();
										 url+='&material_id='+$('#approve_material_id').val();
										 url+='&warehouse_id='+$('#approve_warehouse_id').val();
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
	   })  
</script>
<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>原材料入库记录</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		
		<input type="button" id="revocation"  value="反审核"/>
　　　　<!--查询套件 -><-->

	   <div class=button_right><form id=searchs action="">
	   <?php include "search_control.php";?>
		</form> 
       </div>
　    <!--查询套件结束 -><-->
      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>
  	
      	  
  	  <!--用于反审核的pop up-->
		<div id="revocation_hidden" class="pop_up">
		              <div class="div_title">入库反审核<div class=title_close>关闭</div></div>
			            <div class="table_margin">
				        <form action="" id=form_revocation>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td><input type=text id=revocation_material_name name=material_name readonly /><input type=hidden id=revocation_material_id name=material_id readonly /><input type=hidden id=revocation_in_id  name=in_id /><input type=hidden id=revocation_pending_id  name=pending_id />
						</td></tr>
						<tr height=35>
						<td>采购单号</td><td><input  type=text id=revocation_purchase_id name=purchase_id  readonly /> </td></tr>
						<td></td><td>                 
						</td></tr>
						<tr height=35>
						<td>数量</td><td><input type=text id=revocation_qty name=in_qty  readonly /></td></tr>
						<tr height=35>
						<td>备注</td><td><input type=text id=revocation_remark name=remark /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_revocation value="确定反审核" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于反审核的pop up--结束 -->    
	  	
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
/*  material_in_list_show文件的结尾 */
/*  在系统中的位置: ./application/views/material_in_list_show */
?>
