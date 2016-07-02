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
 * material_in_unused 生产部门退料 修改和查询页面的主视图文件
 * @category welcome
 * @源代码
 */
 date_default_timezone_set('Asia/Shanghai');
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
	var inner;
	var search_string;var page_string;var string;var clickedId=null;
	var base_url='<?php echo base_url();?>';
	passing=function(){
		             			 
				$('#supplier_id').val($(this).find("td").first().text());
				$('#supplier_name').val($(this).children("td").eq(1).text());
				        
		              }
	function material_check(){
	                        var e=0;                           
							$.getJSON(mainurl+'welcome/inventory_check?qty='+$("#qty").val()+'&material_id='+$("#update_material_id").val(),
										function(result) {   
										                 if (result>=$("#qty").val()) { alert("enough");e=1;}
										                 });
							 return e; 			    
							}
	
	function build_grid_tr(in_id){    cjTable_tr( 'material_in_list?in_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'supplier_id,currency_id,house_id,supplier_id,supplier_name' ,0   );                      ////表格高度,需要隐藏的td
                            }	
	function build_grid_tr_add(in_id){cjTable_tr( 'material_in_list?in_id=new&unused=yes&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'supplier_id,currency_id,house_id,supplier_id,supplier_name',1);                        ///表格高度,需要隐藏的td
                            }
	                                                   			  
    $(function(){
	
       
	   ///////////////////////////validate update///
	   
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','material_in_list?unused=yes&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							  '进料ID,日期,原材料id,原材料名,原材料全名,规格,规格2,生产单,入库数量,单位,批号,采购单,入库作业员,审核人,审核日期,生产退料,暂进ID,仓库',////表格标题
							 '300px','supplier_id,currency_id,house_id,supplier_id,supplier_name'   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable(  '#grid','material_in_list?unused=yes&s=0&dates=2'+string,////url and search string 
							  '进料ID,日期,原材料id,原材料名,原材料全名,规格,规格2,生产单,入库数量,单位,批号,采购单,入库作业员,审核人,审核日期,生产退料,暂进ID,仓库',////表格标题
							 '300px','Apurchase_id,currency_id,house_id,supplier_id,supplier_name'   );                                    ///高度,,需要隐藏的td列
                            }					
	   build_grid();
	
	  //////////////选择物料
	   $(".material_name").keyup(function(){
		                      if($(this).val().length>1)
							  {
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							    cjTable_light5('selection','material_list?&final_product=R&material_name='+$("#add_material_name").val()+'&dates='+Math.floor(Math.random()*999+1),////url of data source
								 '序号,品名,全名,规格,规格2',///////表格标题
								 '300px','measurement,safe,moq,material_category,removed,material_barcode,remark',//要隐藏的字段
								 'yes','#add_material_name,1||#add_material_name2,2||#add_material_id,0||#add_material_specification,3||#add_material_specification2,4'  ); ////是否不要标题行
							  }
							  if ($(this).val().length==0) $("#selection").remove();
						   });	
       $(".production_id").focus(function(){
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							     cjTable_light5('selection','material_out_list?&material_id='+$("#add_material_id").val()+'&dates='+Math.floor(Math.random()*999+1),////url of data source
								 '生产单号,领料日期,物料名,物料全称,规格,规格2,单位,批号,仓id,仓库,领料人',///////表格标题
								 '300px','in_id,job_id,Amaterial_id,abs(in_qty),moq,remark,auditor,left(approved_date,10),author',//要隐藏的字段
								 'No','.production_id,1||.batch,11||.warehouse_name,14||.warehouse_id,13'  ); ////是否不要标题行
						                  });	
	   $("#form_button_add").click(
	                      function (){
						               var data=$("#form_add").serialize();
						             var erro=verify("#form_add",'production_id,生产单,required||material_id,料件号,required||qty,退料数量,digital');
									 if (erro==''){
									 var url='material_unused_add?';///
	                                 adds_2(url);
									 //build_grid();
									 }else{ note(erro);}
									 return false;
	                                }
	                           );
	  $("#form_button_update").click(
	                      function (){
						             var data=$("#form_update").serialize();
						             var erro=verify("#form_update",'production_id,生产单,required||material_id,料件号,required||qty,退料数量,digital');
									 if (erro==''){
									 var url='material_unused_update?';///
	                                 updates(url,data);
									 
									 //build_grid_tr($(clickedId).find('td').eq(0).find('div').eq(0).text());
									 }else{ note(erro);}
									 return false;
	                                }
	                           );						   
	 $("#update").click( function()
												{
												 if(clickedId==null)
									                    {
									                       $("#error").show();resize("#error");
														   return false;
														 }  
												$(clickedId).css('background-color','#bbb');
												$("#update").val("确定");  
												var update_content=$(clickedId).html();
												var update_content=update_content.replace(/<\/div>/g,'');  
												var update_content=update_content.replace(/<div class="innercell">/g,'');    
												var update_content=update_content.replace(/<\/td>/g,'');
												var update_content=update_content.replace(/<td style="display:none;">/g,'<td>');
												var update_content=update_content.split("<td>");
												$("#form_update input").each(function(index){$(this).val(update_content[index+1]); 
												                                              if($(this).attr('id')=='qty') $(this).val(0);
																							  if($(this).attr('id')=='applier') $(this).val('');
																							 });
												
												
											
												 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('仓库')").index();
									             $("#update_warehouse_name").val($(clickedId).find('td').eq(col).text());
												  $("#update_warehouse_id").val($(clickedId).find('td').eq(col+1).text());
												$("#update_hidden").show();
												
												resize('#update_hidden');	$("#error").hide();	
												}	
																
							                    );  	
	
	 
	   $("#return").click( function()
												{
												$(':input','#add_hidden')  ///清空所有input
													 .not(':button, :submit, :reset')  
													 .val('')  
													 .removeAttr('checked')  
													 .removeAttr('selected'); 
												$("#error").hide();	
												//$("#table1 tr").css('background-color','#fff');
												//$(clickedId).css('background-color','#bbb');
												$("#add_hidden").show().hide();
												resize('#add_hidden');$("#add_hidden").show();
												clickedId=null;	
												}); 	
		     ///////////审核						   
	  $("#form_button_approve").click(
	                      function (){
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') erro='不能再审核';
									
									 if(erro=='')
									 {
									  	 var url='material_unused_approve?id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										 updates(url); //build_grid_tr();
										  
							  		  }else{
									       note(erro);
									       }
									return false;	   					 
	                                });
															
	     ///////////反审核						   
	  $("#form_button_disapprove").click(
	                      function (){
						             var erro='';
									
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()=='') erro='不能反审核';
									 
									 if(erro=='')
									 {
									  	 var url='material_unused_disapprove?id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										 updates(url); //build_grid_tr();
										  
							  		  }else{
									       note(erro);
									       }
									return false;	   					 
	                                });
	　  //////////////选择仓库
	   $(".warehouse_name").focus(function(){
							   //$("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							   cjTable_light5('selection','../settings/warehouse_list?type=原材料仓&dates='+Math.floor(Math.random()*999+1),
							    '仓库序号,仓库名称,类别,　',////表格标题
							    '500px','amaterial_id,material_specification,meausrement','yes','.warehouse_name,1||.warehouse_id,0');
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
						   string=search_string;
						   build_search_grid();$("#return_unused").show();$("#buttons").addClass("button_right");
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
						   cjTable_light5('selection','material_list?s=1&material_name='+$("#search_material_name").val()+'&dates='+Math.floor(Math.random()*999+1),
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
  <h2>原材料退领</h2><p></p>
    <div id=main_left>
	    <div id=grid>
		</div>
	  <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>  
	   <button id=return class=out2>新退领</button> <button id=update >修改</button> <button id=form_button_approve class=out>审批</button> <button id=form_button_disapprove class=out>反审批</button>  
　　　　<!--查询套件 -><-->
	   <div class=button_right id=buttons><form id=searchs action="">
	   从<input type=text id=search_start_date name=start_date size=6 value="" placeholder="开始日期"/>到
	   <input type=text id=search_end_date name=end_date size=6 value="<?php echo date('Y-m-d',time());?>" placeholder="截至日期"/>
		<input type=text id=search_material_name name=material_name autocomplete=off size=6 placeholder="输入品名"/>
		<input type=hidden id=search_material_id name=material_id />
		
		<input type=button id=search value="搜索"/>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="hidden" id="page" name="page" value=0  >
        <button id=previous_page>前页</button><button id=next_page>后页</button>  
		</form>
       </div>*原材料退领，是生产部门将生产完成后剩余的材料退回仓库。
　    <!--查询套件结束 -><-->		   

 
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">退领料<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update>
						
						
						<tr height=35>
						<td>物料名称</td><td><input  type=text id=add_material_name placeholder="输入名称前两个字符，即可选择"  size=25 name=material_name class=material_name autocomplete=off /><input  type=hidden id=add_material_id   name=material_id  value="" />
											</td></tr>
                       <tr height=35>
						<td>物料全称</td><td><input  type=text id=add_material_name2 readonly  name=material_name2 class=material_name2 autocomplete=off size=25 />
											</td></tr>
					   <tr height=35>
						<td>规格</td><td>
											<input  type=text id=add_material_specification name=material_specification class=material_specification readonly autocomplete=off size=25  /></td></tr>
						<tr height=35>
						<td>规格2</td><td>
											<input  type=text id=add_material_specification2 name=material_specification2 class=material_specification2 readonly autocomplete=off size=25 /></td></tr>
						
						<tr height=35>
						<td>原生产单</td><td>
											<input  type=text id=add_production_id placeholder="单击,根据领料记录确定生产单号" size=25 name=production_id class=production_id readonly autocomplete=off /><br />*为了准确计算每个生产单耗用的原材料，应填写生产单号<br /><br /></td></tr>
						<tr height=35>
						<td>批号</td><td>
											<input  type=text id=add_material_batch name=batch  class=batch  autocomplete=off size=25 /></td></tr>
						<tr height=35>
						<td>仓库</td><td>
											<input  type=text id=add_material_warehouse_name name=warehouse_name class=warehouse_name  autocomplete=off size=25 /><input type=hidden id=add_warehouse_id class=warehouse_id name=warehouse   /></td></tr>
						<tr height=35>
						<td>退领数量</td><td><input  type=text id=add_qty name=qty  autocomplete=off />
											</td></tr>
						<tr height=35>
						<td>退料人</td><td>
										  <input  type=text id=add_applier name=applier  autocomplete=off value='' />
										</td></tr>
						<tr height=35><td colspan=3><button id=form_button_add>确认</button></td></tr>
						</table>
						</form><br />
				  </div>
		</div>
      <!--用于添加的pop up--结束 -->
	  <!--用于更新的pop up-->
		<div id="update_hidden" class="pop_up">
		              <div class="div_title">退领料<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_update>
						<table class=table_update>
						<tr id=tr_production height=35>
						<td></td><td>
						                    <input  type=hidden id=add_id name=id readonly autocomplete=off />
											<input  type=hidden id=date name= readonly autocomplete=off />
											<input  type=hidden id=add_material_id   name=material_id   />
						</td></tr>
						<tr height=35>
						<td>物料名称</td><td><input  type=text id=add_material_name name=material_name class=material_name  autocomplete=off />
											</td></tr>
						<tr height=35>
						<td>物料全称</td><td><input  type=text id=add_material_name2 readonly  name=material_name2  class=material_name2 autocomplete=off  />
											</td></tr>					
                       <tr height=35>
						<td>规格</td><td>
											<input  type=text id=nouse name=material_specification class=material_specification readonly autocomplete=off />
											</td></tr>
						<tr height=35>
						<td>规格2</td><td>
											<input  type=text id=update_material_specification2 name=material_specification2 class=material_specification2 readonly autocomplete=off  /></td></tr>
						<tr height=35>
						<tr height=35>
						<td>原生产单</td><td>
											<input  type=text id=update_production_id placeholder="单击,根据领料记录确定生产单号"  class=production_id name=production_id autocomplete=off /></td></tr>
						<td></td><td></td></tr>
						<tr height=35>
						<td>退领数量</td><td><input  type=text id=update_qty name=qty size=15  /> 单位<input size=4 type=text  name=measurement  readonly />
											</td></tr>
						<tr height=35>
						<td>批号</td><td>
											<input  type=text id=update_material_batch name=batch  class=batch  autocomplete=off  /></td></tr>
					   <tr height=35>
						<td>仓库</td><td>
											<input  type=text id=update_warehouse_name name=warehouse_name class=warehouse_name  autocomplete=off /><input type=hidden id=update_warehouse_id class=warehouse_id name=warehouse   /></td></tr>

						<tr height=35>
						<td>退领人</td><td><input  type=hidden id=nouse name=author readonly autocomplete=off />
										  <input  type=hidden id=nouse name=appliers readonly autocomplete=off value='' />
										  <input  type=text id=applier name=applier  autocomplete=off value='' />
										</td></tr>
						<tr height=35><td colspan=3><button id=form_button_update>确认</button></td></tr>
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
/*  material_in_unused文件的结尾 */
/*  在系统中的位置: ./application/views/material_in_unused.php */
?>
</html>
