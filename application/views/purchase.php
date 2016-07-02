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
 * purchase 采购单主页面的视图文件
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
	var passing;var clickedId=null;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
    function build_grid_tr(in_id){cjTable_tr('purchase_list?purchase_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',0);   
	                     ///表格高度,需要隐藏的td
                            }
	function build_grid_tr_add(in_id){cjTable_tr( 'purchase_list?purchase_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',1);                        ///表格高度,需要隐藏的td
                            }

    $(function(){                         				
	
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','purchase_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '行号,采购单号,品名,全名,规格,规格2,原材料ID,供应商,id,数量,已到数量,单位,价格,币种,税率,采购日,交期,审核人,审核日期, ',////表格标题
							 '300px','currency_id'   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','purchase_list?s=2'+string,////url and search string 
							  '行号,采购单号,品名,全名,规格,规格2,原材料ID,供应商,id,数量,已到数量,单位,价格,币种,税率,采购日,交期,审核人,审核日期, ',////表格标题
							 '300px','supplier_id'   );                                    ///高度,,需要隐藏的td列
                            }					
	   build_grid();bound_search_controls("原料");
	   $("#search_warehouse").hide();
      ////////////add new handling
	   $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									  //var data=$("#form_add").serialize();alert(data);
									 var erro=verify("#form_add",'supplier,供应商,required||material,原材料,required||qty,数量,digital||tax,税率,digital||delivery_date,交期,isdate||material_id,material_id,digital');
									 if (erro==''){
									 var add_url='purchase_list_add';///新增操作指向的页面
	                                 adds_2(add_url);
									 
	                                }else{  note(erro);}
									}
	                          );  
	    $("#form_button_correct").click(
	                      function(){
						                              var erro="";
											          if(clickedId==null)
									                    {
									                       $("#error").show();resize("#error");
														   return false;
														 } 
									  if(erro=='')
									 {   var data=$("#form_correct").serialize();
									  	 var url='purchase_correct?purchase_id='+$(clickedId).find('td').eq(0).text();
										 updates(url,data); 
							  		  }else{
									       note(erro);
									       }					 
	                                });        
									
	                         			      
	 
		
		$("#button_correct").click(function(){//显示更正表单
		                             var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 var erro='';
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') { erro='应该先反审核'; note(erro);return false;}	  
									
							         get_tr_content('correct_hidden','form_correct');$("#form_button_correct").val("确认");
						    });				 				 
		
		$("#button_adds").click(function(){//显示更正表单
		                            $("#add_hidden").show();resize($("#add_hidden"));
									$("#add_material_name").val('');
									$("#add_material_id").val('');
									$("#add_material_specification").val('');		        
									$("#qty").val('');	
									$("#price").val('');
										
							
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
						   search_string=search_string+'&supplier_id='+$("#search_supplier_id").val();
						   search_string=search_string+'&purchase_no='+$("#search_purchase_no").val();
						   search_string=search_string+'&page='+$("#page").val();
						   string=search_string;
						   build_search_grid();
						               }else{
									   alert(erro);
									   }
									   
                                      });			 
	  				 				 
         $("#searchs").submit(function () {
                                       return false;
                                        }); 
			
		
		
		 $(".price").focus(function(){
		                              
									  var qty=$(this).parent().parent().parent().find('input[class="qty"]').eq(0).val();
									  var material_id=$(this).parent().parent().parent().find('input[class="material_id"]').eq(0).val();
									  var supplier_id=$(this).parent().parent().parent().find('input[class="supplier_id"]').eq(0).val();
									  var that=$(this);
									  $.getJSON("../price/material_price_get",'&qty='+qty+'&material_id='+material_id+'&supplier_id='+supplier_id,
									            function(result){
												                $.each(result, function(k, v) {
														                        
														                        $.each(v,function(kk, vv) { 
																				                            if(kk=="price") $(that).val(vv);
																											if(kk=="tax")   $(that).parent().parent().parent().find('input[class="tax"]').eq(0).val(vv);
																		                                      });
																							   });	   
												                } );
		                             });
						 
		
		
  	     ///////////审核						   
	    $("#button_approve").click(
	                      function (){
						            
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') erro='不能再审核';
									 if(erro=='')
									 {
									  	 var url='purchase_approve?purchase_id='+$(clickedId).find('td').eq(0).text();
										 updates(url); 
							  		  }else{
									       note(erro);
									       }					 
	                                });        
        ///////////反审核						   
	    $("#button_revocation").click(
	                      function (){
						            
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()=='') erro='不能反审核';
									 if(erro=='')
									 {
									  	 var url='purchase_revocation?purchase_id='+$(clickedId).find('td').eq(0).text();
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
<div id=main><h2>采购单</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
       <button  id=button_adds>新增</button>  <button  id=button_correct >更正</button><button   id=button_approve >审核</button> <button   id=button_revocation >反审核</button> 
	   
      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>  
　　　　<!--查询套件 -><-->
	   <div class=button_right><form id=searchs action=""><input type=text id=search_purchase_no name=purchase_no autocomplete=off size=6 placeholder="输入采购单号" /> 
	   <?php include "search_control.php";?>
		</form> 
       </div>
　    <!--查询套件结束 -><-->

		<!--用于更新的pop up-->
		<div id="correct_hidden" class="pop_up">
		              <div class="div_title">修改采购单<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_correct>
						<table class=table_update>
						<tr height=35><td>
						序号</td><td> <input type=text id=correct_purchase_id name=purchase_id readonly /></td></tr><tr height=35><td>
						采购单号</td><td width=250> <input type=text id=correct_purchase_no name=purchase_no  /></td></tr>
						<tr height=35><td>
						原材料代码</td><td><input type=text id=correct_material_name name=material_name class=material_name   /></td></tr>
						<tr height=35><td>
						原材料全名</td><td><input type=text id=correct_material_name2 name=material_name2 class=material_name2   /></td></tr>
						<TR><td>
						规格</td><td> <input type=text id=correct_material_specification name=material_specification class=material_specification readonly /></td></tr>
						<TR><td>
						规格2</td><td> <input type=text id=correct_material_specification2 name=material_specification2 class=material_specification2 readonly /></td></tr>
						<tr height=35><td><input type=hidden id=correct_material_id name=material_id class=material_id value='' /></td></tr>
						<tr height=35><td>
						供应商</td><td> <input type=text id=correct_supplier_name name=supplier_name class=supplier_name   /><input type=hidden id=correct_supplier_id name=supplier_id class=supplier_id value='' /></td></tr><tr height=35><td>

						数量</td><td> <input type=text id=correct_qty name=qty class=qty /><input type=hidden id=correct_qty_arrived name=qty_arrived /></td></tr><tr height=35><td>单位:</td><td><input type=text id=correct_measurement name=measurement readonly /></td></tr><tr height=35><td>
						价格</td><td><input type=text id=correct_price name=price class=price  /></td></tr><tr height=35><td>
						币种</td><td><input type=text id=correct_currency name=currency readonly  /></td></tr><tr height=35><td>
						税率</td><td><input type=text id=correct_tax name=tax  class=tax /></td></tr><tr height=35><td>
						采购日</td><td><input type=text id=correct_purchase_date name=purchase_date  placeholder="YYYY-MM-dd" /></td></tr><tr height=35><td>
						交期</td><td><input type=text id=correct_delivery_date name=delivery_date  placeholder="YYYY-MM-dd" /></td></tr><tr height=35><td>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_correct value=更新 /></td></tr>
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
						</td><td></td></tr>
						<tr height=35><td>
						采购单号</td><td width=250> <input type=text id=add_purchase_no name=purchase_no  /></td></tr><tr height=35><td>
						供应商</td><td width=250> <input type=text id=add_supplier_name class=supplier_name name=supplier_name placeholder="输入供应商前2个字"/></td></tr><tr height=35><td>
						原材料代码</td><td><input type=text id=add_material_name name=material_name  class=material_name autocomplete=off placeholder="输入材料前2个字" /></td></tr><tr height=35><td>
						原材料全称</td><td><input type=text id=add_material_name2 name=material_name2  class=material_name2 autocomplete=off  /></td></tr><tr height=35><td>
						规格</td><td> <input type=text id=add_material_specification name=material_specification class=material_specification readonly /></td></tr><tr height=35><td>
						规格2</td><td> <input type=text id=add_material_specification2 name=material_specification2 class=material_specification2 readonly /></td></tr><tr height=35><td>
						数量</td><td> <input type=text id=qty name=qty class=qty /></td></tr><tr height=35><td>
						价格</td><td><input type=text id=price name=price class=price /></td></tr><tr height=35><td>
						币种</td><td><input type=text id=add_currency name=currency size=3 value=RMB /></td></tr><tr height=35><td>
						税率</td><td><input type=text id=tax name=tax  class=tax /></td></tr><tr height=35><td>
						采购日</td><td><input type=text id=purchase_date name=purchase_date class=todays placeholder="YYYY-MM-dd" /></td></tr><tr height=35><td>
						交期</td><td><input type=text id=delivery_date name=delivery_date class=day_30 placeholder="YYYY-MM-dd" /></td></tr><tr height=35><td>
						备注</td><td><input type=text name=remark   />
						<input type=hidden id=add_material_id name=material_id class=material_id value='' />
						<input type=hidden id=add_supplier_id name=supplier_id class=supplier_id value='' />
						</td>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="新增" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
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
