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
 * product_inventory_gain.php 产品及半成品仓库盘盈的视图文
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
	var inner; var clickedId=null;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
          function build_grid_tr(in_id){cjTable_tr('product_inventory_gain_list?gain_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'operator,warehouse',0);   
	                     ///表格高度,需要隐藏的td
                            }
	      function build_grid_tr_add(in_id){cjTable_tr( 'product_inventory_gain_list?gain_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'operator,warehouse',1);                        ///表格高度,需要隐藏的td
                            }					

    
		   function build_grid(){cjTable( '#grid','product_inventory_gain_list?gain_type=product&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,日期,物料id,品名,全名,规格,规格2,盘赢数量,状态,仓库,审核人,审核日期,操作员, ',////表格标题gain_id,gain_date,a.material_id,b.material_name,qty,approved,operator 
							 '300px','operator,warehouse');                                    ///表格高度,需要隐藏的td
                            }
	        function build_search_grid(search_string){cjTable(  '#grid','product_inventory_gain_list?'+string+'&date='+Math.floor(Math.random()*9999+1),////url of data source
							'序号,日期,物料id,品名,全名,规格,规格2,盘赢数量,状态,仓库,审核人,审核日期,操作员, ',////表格标题
							 '300px','operator,warehouse');                                   ///高度,,需要隐藏的td列
                            }	
      $(function(){
	   ///////////////////////////validate update///
	   
	   $("#head").makemenu2(base_url);////顶部菜单
	  
	    build_grid();
	  	bound_search_controls('成品');	   
      ////////////弹出盘盈表单
       $("#new").click(
	                      function (){
									  $(':input','#add_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#add_hidden").hide();
									 $("#error").hide();$("#add_hidden").hide();resize("#add_hidden");$("#add_hidden").show();
									
	                                }
	                           );
		
       $("#update").click(
	                      function (){
						             
						             if(clickedId==null)
									 {
									 $("#error").show();resize("#error");
									 }else{ $(':input','#check_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
									var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col+1).find('div').eq(0).text()!=''){note( '已经审核过,不能再修改');return false;} 
						             $("#check_hidden").show();
									 resize("#check_hidden");$("#error").hide();
									
									
									 
									 get_tr_content("check_hidden","form_check");
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('类型')").index();
									 
									 if($(clickedId).find('td').eq(col).text()=='良品')
									 
									 $("#check_material_type option[value='G']").attr("selected","selected");
									 if($(clickedId).find('td').eq(col).text()=='待定品')
									 $("#check_material_type option[value='P']").attr("selected","selected");
									 if($(clickedId).find('td').eq(col).text()=='报废品')
									 $("#check_material_type option[value='D']").attr("selected","selected");
									 $("#form_button_check").val("确定");
									 }
	                                }
	                           );
	    $("#approve").click(
	                      function (){   
						   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
							if ($(clickedId).find('td').eq(col+1).find('div').eq(0).text()!='') {note( '已经审核过,不能再审核');return false;}
						  var checked=0;
										 $("input[class='checkbox']").each(function(){////check if any is checked
										                                             if ($(this).is(':checked')) checked=1; 
										                                             
										                                             });
										 if(checked>0){
                 										var data=$("#form_approve").serialize();
														var add_url='product_inventory_gain_approve?'+data;
														updates_syn(add_url);//document.execCommand('Refresh'); 
										              }else{
													  note("没有选中任何一条");
													  }
									 }
	                           );						   
	  /////////更新确认					   
	  $("#form_button_check").click(
	                      function (){
						           
						             var data=$("#form_check").serialize();
						             var erro='';
									 erro=verify("#form_check",'qty,数量,digital||material_name,产品名称,required||material_id,产品名称,required');
								  	 if(erro=='')
									 {
									  var add_url='product_inventory_gain_update?'+data;
										updates(add_url);
										
									 }else{
									       note(erro);
									       }					 
							          							 
														 
	                                });
	  ///////////新增						   
	  $("#form_button_add").click(
	                      function (){
						            //alert($("#material_type").val());
						             var data=$("#form_add").serialize();
						             var erro='';
									 erro=verify("#form_add",'qty,数量,digital||material_name,产品名称,required||material_id,产品名称,requiredd||warehouse_id,仓库,required||d||warehouse_name,仓库,required');
								  	 
									 if(erro=='')
									 {
											var add_url='product_inventory_gain_add?'+data;///新增操作指向的页面
											adds_2(add_url);
											
											
									 }else{
									       note(erro);
									       }					 
	                                });	                           

	  
      $("#select_all").click( function(){
	                                     
	                                     $(".checkbox").attr("checked",true);
	                                     
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
						   string=search_string;
						   build_search_grid();
						               }else{
									   note(erro);
									   }
									   
                                      });			 
	  				 				 
        $("#searchs").submit(function () {
                                       return false;
                                        }); 
		$("#search_start_date").focus(function(){
		                                        
		                                        $(this).val($("#search_end_date").val());
												});	
		
	   })  
	   
	   
	               
</script>


<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>盘点记录.(半)成品</h2><p></p>
    <div id=main_left>
	    <form id=form_approve>
	    <div id=grid>
		
		</div>
		</form>
		
	    <input type="button" id="new"  value="盘点输入"/>
		<input type="button" id="update"  value="修改"/>
		<input type="button" id="approve"  value="批准"/>
		<input type="button" id="select_all"  value="全选"/>
		
		
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
		              <div class="div_title">盘盈盘亏输入<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        
				        <form action="" id=form_add>
						<table class=table_update>
						<tr  height=35>
						<td>物料简称</td><td><input type=text id=update_material_name name=material_name class=product_name autocomplete=off placeholder="输入品名前2位,选择" /><input type=hidden id=update_material_id name=material_id class=material_id /></td></tr>
                         <tr  height=35>
						<td>物料全名</td><td><input type=text id=update_material_name2 name=material_name2 class=material_name2 autocomplete=off /></td></tr>
						 <tr  height=35>
						<td>规格</td><td><input type=text id=update_material_specification name=material_specification class=material_specification autocomplete=off /></td></tr>
	                    <tr  height=35>
						<td>规格2</td><td><input type=text id=update_material_specification2 name=material_specification2 class=material_specification2 autocomplete=off /></td></tr>
	                    <tr  height=35>
						<td>仓库</td><td><input type=text id=update_warehouse_name name=warehouse_name class=warehouse_name autocomplete=off /><input type=hidden id=update_warehouse_id name=warehouse_id class=warehouse_id autocomplete=off /></td></tr>
	
						<tr height=35>
						<td>盘盈数量</td><td><input type=text id=qty name=qty  />（盘亏请填负数）</td></tr>
						<tr height=35>
						<td>产品质量</td><td><select id=material_type name=material_type  />
						                    <option value=G>良品</option> <option value=P>待定品</option> <option value=D>次品（报废品）</option>
											</select>
						</td></tr>
						<tr height=35>
						<td>备注</td><td><input type=text id=remark name=remark /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于正式出货的pop up--结束 -->
 	  <!--用于出货检验的pop up-->
		<div id="check_hidden" class="pop_up">
		              <div class="div_title">盘盈更新<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        
				        <form action="" id=form_check>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td><input type=hidden id=check_gain_id name=gain_id /><input name=nouse /><input type=hidden id=check_material_name name=material_name readonly /><input type=hidden id=check_material_id name=material_id /></td></tr>
						  <tr  height=35>
						<td>物料全名</td><td><input type=text id=check_material_name2 name=material_name2 class=material_name2 autocomplete=off /></td></tr>
						 <tr  height=35>
						<td>规格</td><td><input type=text id=check_material_specification name=material_specification class=material_specification autocomplete=off /></td></tr>
	                    <tr  height=35>
						<td>规格2</td><td><input type=text id=check_material_specification2 name=material_specification2 class=material_specification2 autocomplete=off /></td></tr>
	                    <tr height=35>
						<td>数量</td><td><input type=text id=check_qty name=qty  /></td></tr>
						
						
						<tr height=35>
						<td>质量</td><td><select id=check_material_type name=material_type  />
						                    <option value='G'>良品</option> <option value='P'>待定品</option> <option value='D'>次品（报废品）</option>
											</select><input type=hidden id=update_material_type2 name=material_type2 class=material_type />
						</td></tr>
						<tr  height=35>
						<td>仓库</td><td><input type=hidden id=check_warehouse_id name=warehouse_id class=warehouse_id autocomplete=off /><input type=text id=check_warehouse_name name=warehouse_name class=warehouse_name autocomplete=off /></td></tr>
	
						<tr height=35>
						<td></td><td></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_check value="确认" /></td></tr>
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
/*  product_inventory_gain.php文件的结尾 */
/*  在系统中的位置: ./application/views */
?>
</body>
</html>
