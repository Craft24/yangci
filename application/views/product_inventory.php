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
 * product_inventory.php 产品半成品的库存
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
	var inner; var clickedId=null;
	var search_string;var page_string;var string;var clicked_line_index;
	var base_url='<?php echo base_url();?>';
    $(function(){
		   function build_grid(){cjTable( '#grid','product_inventory_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'ID,品名,全名,规格,规格2,状态,库存数量,单位,仓库, ',////表格标题
							 '300px','in_id,map,warehouse');                                   ///表格高度,需要隐藏的td
                            }
	        function build_search_grid(search_string){cjTable( '#grid','product_inventory_list?s=0'+string,////url and search string 
							 'ID,品名,全名,规格,规格2,状态,库存数量,单位,仓库, ',////表格标题
							 '300px','in_id,map,warehouse');                           ///高度,,需要隐藏的td列
                            }	
      
	   ///////////////////////////validate update///
	   
	  $("#head").makemenu2(base_url);////顶部菜单
	  build_grid();
	  bound_search_controls("成品");
	  $("#search_supplier").hide();
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
						   search_string='&material_id='+$("#search_material_id").val();
						   search_string=search_string+'&end_date='+$("#search_end_date").val();
						   search_string=search_string+'&material_type='+$("#material_type").val();
						   search_string=search_string+'&final_product='+$("#final_product").val();
						   search_string=search_string+'&warehouse='+$("search_house_id").val();
						   string=search_string;
						   //alert(string);
						   build_search_grid();
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
		$("#search_material_name").keyup(function(){
	                       if ($(this).val().length<1) {$("#selection").remove();$("#search_material_id").val('0');}  
	                       if($(this).val().length>1)
							  {
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light5('selection','material_list?s=1&material_name='+$("#search_material_name").val()+'&dates='+Math.floor(Math.random()*999+1),
							  '材料,全称,规格,规格2,单位',////表格标题
							 '500px','amaterial_id,material_specification,meausrement','yes','#search_material_name,1||#search_material_id,0');
						    $("#selection").show();
							   }
							}
						   	
	                     );
		 $("#search_material_name").focus(function(){
	                       if ($(this).val()=='输入品名') $(this).val('');
						    });	
		 //////////////选择仓库
	     $(".warehouse").focus(function(){
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							   cjTable_light5('selection','../settings/warehouse_list?type=成品仓库&dates='+Math.floor(Math.random()*999+1),
							 '仓库序号,仓库名称,类别,　',////表格标题
							 '500px','amaterial_id,material_specification,meausrement','yes','.warehouse_id,0||.warehouse,1');
							  
							  
						   }
	                     );	
		  //////////////选择仓库
	     $(".warehouse").blur(function(){
							   
							   $("#selection").hide().remove();
							   if($(this).val()=='') {$(".warehouse_id").val(0);$(".warehouse").val('');}
							  
						   }
	                     );						 											 
	   })  
	   
	   
	               
</script>


<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>成品半成品库存</h2><p></p>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		
　　　　<!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
	   <select id=material_type name=material_type>
		<option value='G'>良品</option>
		<option value='P'>待定品</option>
		<option value='D'>废品</option></select>
		<select id=final_product name=final_product>
		<option value='F'>成品</option>
		<option value='S'>半成品</option>
		<option value=''>全部</option></select>
	  <?php include "search_control.php";?>
        
		</form> 
       </div>
　    <!--查询套件结束 -><-->


     
         
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
/*  product_inventory.php 文件的结尾 */
/*  在系统中的位置: ./application/views */
?>
</body>
</html>
