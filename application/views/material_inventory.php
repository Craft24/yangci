<?php
/**
 * EachERP
 * EachERP是开源软件,基于PHP 5.1.6 以上版本, CodeIgniter 2.0框架, Jquery1.9
 * @软件包	EachERP
 * @授权		http://EachERP.net/user_guide/license.html
 * @链接		http://EachERP.net
 * @版本	    0.1beta

 * 版权所有(C) 2015 作者:陈国彤
本程序为自由软件；您可依据自由软件基金会所发表的GNU 通用公共授权条款，对本程序再次发布和/ 或修改；无论您依据的是本授权的第三版，或（您可选的）任一日后发行的版本。
本程序是基于使用目的而加以发布，然而不负任何担保责任；亦无对适售性或特定目的适用性所为的默示性担保。详情请参照GNU 通用公共授权。
您应已收到附随于本程序的GNU 通用公共授权的副本；如果没有，请参照<http://www.gnu.org/licenses/>.
 *material_inventory 原材料库存主视图文件
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
<script src="../../jquery1.9/jquery-1.9.0.min.js" type="text/javascript"></script>
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/cjqueryWithComboBox.js?s=2" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
    var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
    
	var passing;var inner;
	var page=1;
	var base_url='<?php echo base_url();?>';
	passing=function(){
		             			 
				$('#supplier_id').val($(this).find("td").first().text());
				$('#supplier_name').val($(this).children("td").eq(1).text());
		                }
	
    $(function(){
	
       var clickedId=null;
	   ///////////////////////////validate update///
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','material_inventory_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '原材料ID,原材料名称,规格,状态,库存数量,金额',////表格标题
							 '300px','in_id');                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','material_inventory_list?s=0'+search_string,////url and search string 
							 '原材料ID,原材料名称,规格,状态,库存数量,金额',////标题
							 '300px','in_id');                                    ///高度,,需要隐藏的td列
                            }					
	   build_grid();
	   bound_search_controls("原料");
	  $("#keyword").keyup(
	                      function(){
									 var keyword=$("#keyword").val();/////////////////过滤操作
	                                 filter_material(keyword);
	                                }
	                     ); 
	  $("#material_name").keyup(
	                      function(){
									 var keyword=$("#material_name").val();///////////原材料名称选择
	                                
	                                }
	                     );     				       
      $("#button_search").click(
	                      function(){
									  var erro=verify("#search",'latest_update,日期,isdate');
									  if (erro==''){
													 var search_string=$('#search').serialize();///搜索操作使用的字
													 build_search_grid('&'+search_string);alert(search_string);
									                }else{note(erro);}
	                                }
	                     ); 
		////////////////如果没有select元素,请注释掉下面一行				 
		//loads_select("#measurement_waiting1,#measurement_waiting2",mainurl+'welcome/show_measurement',''); ///对select元素添加option ///有几个select元素加几行 	  			 
         $("#next_page").click(
	                      function(){
									 var 
									 search_string=$('#search').serialize()+'&page='+(page+1);
									 if (page<=1){ $("#previous_page").attr('disabled', 'disabled');}else{$("#previous_page").removeAttr('disabled');}
									 page++; //alert(search_string);
	                                 build_search_grid(search_string);
									 $("#page_info").html("第"+(page-1)+"页");
	                                }
	                     ); 
						 
         $("#previous_page").click(
	                      function(){
									 var 
									 search_string=$('#search').serialize()+'&page='+(page-1);
									 if (page<=1){ $(this).attr('disabled', 'disabled');}else{$(this).removeAttr('disabled');}
									 if (page>0) page--; //alert(page);
	                                 build_search_grid(search_string);
									  $("#page_info").html("第"+(page+1)+"页");
	                                }
	                     ); 
						 
		 $("#previous_page").attr('disabled', 'disabled');			 
						 
						 				 
         
	   })  
</script>
<body>
<div id=container>
<div id=head>
</div>
<div id=main>
  <h2>原材料总库存</h2><p></p>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		<div id=pager>
		
		</div>
		   
		   <form id=search> 
		     <?php include "search_control.php";?>
           </form>
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
/*  material_inventory文件的结尾 */
/*  在系统中的位置: ./application/views/material_inventory */
?>
</body>
</html>
