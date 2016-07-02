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
* material_return 原料料退回供应商查询页面的主视图文件
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
    
	var passing; var clickedId=null;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
	passing=function(){
				$('#supplier_id').val($(this).find("td").first().text());
				$('#supplier_name').val($(this).children("td").eq(1).text());
		               }
	  function build_grid_tr(){cjTable_tr( 'material_return_list?dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'house_id,supplier_ids',0);                        ///表格高度,需要隐藏的td
                            }				   
	  $(function(){
	   ///////////////////////////validate update///
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','material_return_list?dates='+Math.floor(Math.random()*9999+1),////url of data source
							  '序号,日期,采购单,材料id,材料名,材料全名,规格,规格2,暂收数量,单位,批号,供应商,抽样数,不良数,不良率,结果,仓管,状态,检验员,仓库,退料人,退料日',////表格标题
							 '370px','house_id,supplier_ids'   );   ///表格高度,需要隐藏的td
							                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','material_return_list?'+string,////url and search string 
							  '序号,日期,采购单,材料id,材料名,材料全名,规格,规格2,暂收数量,单位,批号,供应商,抽样数,不良数,不良率,结果,仓管,状态,检验员,仓库,退料人,退料日',////表格标题
							 '370px','house_id,supplier_ids'   );   ///表格高度,需要隐藏的td                              ///高度,,需要隐藏的td列
                            }					
	   build_grid();bound_search_controls('原料');
      ///////////////记录退料作业
	  $("#button_return").click(
	                      function (){
									 var data=$("#form_return").serialize();
									
									 var update_url='material_return_update?'+data;///记录退料数量,
	                                 updates(update_url);
									 
	                                });
     ///////////////显示退料窗口					      
	  $("#return").click(
	                      function(){
									 if (clickedId!=null)
									 {
									 $("#qty_ok_transfer").val($(clickedId).find('td').eq(7).text());
									 $("#qty_ng_transfer").val($(clickedId).find('td').eq(8).text());
									 $("#in_id").val($(clickedId).find('td').eq(0).text());
									 $("#material_name").val($(clickedId).find('td').eq(4).text());
									 $("#transfer_hidden").show();
									 resize('#transfer_hidden');
									 }
	                                }
	                     );       					      
	  $("#button_print").click(
	                      function(){
									 window.open(mainurl+'material_return_print');
	                                }
	                     );       	
	  $("#keyword").keyup(
	                      function(){
									 var keyword=$("#keyword").val();/////////////////过滤操作
	                                 filter_material(keyword);
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
						   search_string=search_string+'&warehouse='+$("#search_warehouse_id").val();
						  
						   string=search_string;
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

	   })  
</script>
<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>原材料退回供应商库</h2><p></p>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		<button id=return>退料</button>
	   <div class=button_right> <form id=searchs action="">
	   
	  <?php include "search_control.php";?>
		</form> 
       </div>
　    <!--查询套件结束 -><-->
      <!--用于拨转的pop up--结束 -->
		<div id="transfer_hidden" class="pop_up">
		              <div class="div_title">退料<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_return>
						<table class=table_transfer>
						<tr height=35><td>
						</td><td>
						<input type=hidden id=in_id name=in_id readonly />
						</td></tr><tr height=35><td>
						</td><td>
						
						</td></tr>
						<tr height=35>
						<td colspan=3>
						<input class=button type=button id=button_return value=提交 /> 
						<input  class=button type=button id=button_print value=打印 />
						</td></tr>
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
/*  material_return.php文件的结尾 */
/*  在系统中的位置: ./application/views */
?>
</html>
