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
 * material_inventory 原材料库存修改和查询页面的主视图文件
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
	var search_string;var page_string;var string;var clicked_line_index;
	var base_url='<?php echo base_url();?>';
     function build_grid_tr(in_id){
	                               var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('均价')").index()+1;///得到所在的列数
								   $(clickedId).find('td').eq(col).find('div').eq(0).html($("#map_new").val());	
	                               var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('库存数量')").index()+1;///得到数量所在的列数
								   var col2=$("#table_title").find('tr').eq(0).find('th').filter(":contains('金额')").index()+1;///得到金额所在的列数
								   var amount=parseFloat($("#map_new").val())*parseFloat($(clickedId).find('td').eq(col).find('div').eq(0).html());
								   $(clickedId).find('td').eq(col2).find('div').eq(0).html(amount);       
                            }
    $(function(){
		   function build_grid(){cjTable( '#grid','material_inventory_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '原材料ID,原材料名称,全名,规格1,规格2,状态,库存数量,安全库存,单位,均价,金额,仓库,',////表格标题
							 '300px','in_id,warehouse');                                   ///表格高度,需要隐藏的td
                            }
	       function build_search_grid(search_string){cjTable( '#grid','material_inventory_list?s=0'+string,////url and search string 
							 '原材料ID,原材料名称,全名,规格1,规格2,状态,库存数量,安全库存,单位,均价,金额,仓库,',////标题
							 '300px','in_id,warehouse');                           ///高度,,需要隐藏的td列
                            }	
	   ///////////////////////////validate update///
	   $("#head").makemenu2(base_url);////顶部菜单
	  
	    build_grid();
	  	bound_search_controls("原料");
		$("#search_supplier").hide();	   
      $("#print_verification").click(function(){//打印盘点表
	                                           window.location.href='reports'; 
	                                           });
	       ////////////update handling
	  $("#form_button_update").click(
	                      function (){
						             var erro=verify("#form_update",'map||价格,digital');
									 var data=$("#form_update").serialize();
									 if (erro==''){
									 var url='../price/material_map_update';///更新操作指向的页面
	                                 updates(url,data);
									 }else{ note(erro);}
									 
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
						   if($("#search_end_date").val().length>0)    erro=verify("#searchs",'end_date,截止日期,isdate');
						   if(erro==''){
						   if ($("#search_warehouse").val()=='') $("#search_warehouse_id").val('0');
						   search_string='&material_id='+$("#search_material_id").val();
						   search_string=search_string+'&end_date='+$("#search_end_date").val();
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
						   
						   if($("#search_end_date").val().length>0)    erro=verify("#searchs",'end_date,截止日期,isdate');
						   if(erro==''){
						   search_string='&material_id='+$("#search_material_id").val();
						   
						   search_string=search_string+'&end_date='+$("#search_end_date").val();
						   search_string=search_string+'&warehouse='+$("#search_warehouse_id").val();
						   string=search_string; //alert(string);
						   $.getJSON('material_inventory_list?s=1&dates='+Math.floor(Math.random()*9999+1)+'&stat=1&'+string,
									 function(result)
									                { var sum="";
													  $.each(result,function(k,v){
													                              $.each(v,function(kk,vv)
																				          {
																						   if(kk=='amount')  {var am=parseFloat(vv).toFixed(2); sum+="金额:"+ am;}
																						   if(kk=='total_qty')sum+="数量:"+vv+'<br>';
																						   if(kk=='currency') sum+="元 "+vv;
																						   });
													                             });
													note(sum);
													 });	
						               }else{
									   alert(erro);
									   }});
        	  $("#map").click( function()
												{
												 if(clickedId==null)
									                    {
									                       $("#error").show();resize("#error");
														   return false;
														 }  
														   
												 
												
												$(clickedId).css('background-color','#bbb');
												//$("#update").val("确定");  
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('原材料名称')").index();$("#material_name").val($(clickedId).find('td').eq(col+1).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('原材料ID')").index(); $("#material_id").val($(clickedId).find('td').eq(col+1).text()); 
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('全名')").index(); $("#material_name2").val($(clickedId).find('td').eq(col+1).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('规格1')").index();   $("#material_specification").val($(clickedId).find('td').eq(col+1).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('规格2')").index();  $("#material_specification2").val($(clickedId).find('td').eq(col+1).text());
									   var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('均价')").index();$("#map_old").val($(clickedId).find('td').eq(col+1).text()); 
									  
							
												$("#update_hidden").show();
												$("#form_button_update").val('确定');
												resize('#update_hidden');	$("#error").hide();	
												}	
																
							                    );                                		  		
	    $("#searchs").submit(function () {
                                       return false;
                                        }); 
    	$("#search_start_date").focus(function(){
		                                        $(this).val($("#search_end_date").val());
												});	
		$("#search_material_name").keyup(function(){
	                       if ($(this).val().length<1) {$("#selection").remove();$("#search_material_id").val('0');}  
	                       if($(this).val().length>0)
							  {
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light5('selection','material_list?s=1&final_product=R&material_name='+$("#search_material_name").val()+'&dates='+Math.floor(Math.random()*999+1),
							 'material_id,material_name1',////表格标题
							 '500px','amaterial_id,material_specification,meausrement','yes','#search_material_name,1||#search_material_id,0');
						    $("#selection").show();
							   }}
	                     );
		 $("#search_material_name").focus(function(){
	                                                if ($(this).val()=='输入品名') $(this).val('');
		
						                            });
		 //////////////选择仓库
	   $(".warehouse").focus(function(){
		                     
							   //$("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							   cjTable_light5('selection','../settings/warehouse_list?type=原材料仓&dates='+Math.floor(Math.random()*999+1),
							 '仓库序号,仓库名称,类别,　',////表格标题
							 '500px','amaterial_id,material_specification,meausrement','yes','.warehouse_id,0||.warehouse,1');
							  
							  
						   }
	                     );																 
	   })  
</script>
<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>原材料库存</h2><p></p>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
	<button id=map>均价调整</button>	
　　　　<!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
	   
	   <form id=search> 
		     <?php include "search_control.php";?>
          
		</form> 
       </div>
　    <!--查询套件结束 -><-->
<!--用于更新的pop up-->
		<div id="update_hidden" class="pop_up">
		              <div class="div_title">均价调整<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_update>
						<table class=table_update>
						<tr height=35><td>
						</td><td> <input type=hidden id=material_id name=material_id readonly /></td></tr><tr height=35><td>
						</td><td></td></tr><TR><td>
						物料名称</td><td><input type=text id=material_name class=material_name name=material_name readonly /></td></tr><tr height=35><td></td></tr>
						<TR><td>
						物料全名</td><td><input type=text id=material_name2 class=material_name name=material_name2  readonly /></td></tr><tr height=35><td></td></tr>
						<tr height=35><td>
						规格</td><td> <input type=text id=material_specification name=material_specification readonly /></td></tr>
						<tr height=35><td>
						规格2</td><td> <input type=text id=material_specification2 name=material_specification2 readonly /></td></tr>
						<tr height=35><td>
						原均价</td><td> <input type=hidden /><input type=text id=map_old name=map_old readonly /></td></tr>
						<tr height=35><td>
						新均价</td><td> <input type=text id=map_new name=map /></td></tr><tr height=35><td></td><td></td></tr><tr height=35><td>
						</td><td></td>
						</tr>
						<tr height=35><td colspan=3><input  type=button id="form_button_update" value="更新" />
						
						</td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于更新的pop up--结束 -->
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
/*  material_inventory2.php文件的结尾 */
/*  在系统中的位置: ./application/views/ */
?>
</body>
</html>
