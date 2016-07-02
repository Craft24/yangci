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
 * product_out_pending 成品出库主视图文件
 * @category welcome
 * @源代码
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>成品检验报告</title>

</head>
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/Rightgrid.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
    var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
	var passing;
	var inner; var clickedId=null;clicked_line_index=null;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
	function build_grid_tr(){cjTable_tr( 'fqc_report_list?in_id='+$(clickedId).find('td').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'house_id,supplier_ids',0);                        ///表格高度,需要隐藏的td
                            }
    function build_grid_tr_add(in_id){cjTable_tr( 'fqc_report_list?in_id='+in_id+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'house_id,supplier_ids',1);                        ///表格高度,需要隐藏的td
                            }
    $(function(){
	   $("#head").makemenu2(base_url);////顶部菜单
	   
	   function build_grid(){cjTable( '#grid','fqc_report_list?dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '产品id,品名,产品全名,规格,规格2,数量,抽检数量,不合格数,不合格率,单位,金额',////表格标题
							 '370px','house_id,customer_ids'   );   ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','fqc_report_list?'+string+'&dates='+Math.floor(Math.random()*9999+1),////url of data source
							  '产品id,品名,产品全名,规格,规格2,数量,抽检数量,不合格数,不合格率,单位,金额',
							 '370px','house_id,customer_ids'   );                                    ///表格高度,需要隐藏的td
                            }
	   build_grid();
	   
	   bound_search_controls("成品");
	   $("#search_supplier").hide();
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
						   
						   search_string=search_string+'&final_product='+$("#search_final_product").val();
						   string=search_string;
						   
						   build_search_grid();
						               }else{
									   note(erro);
									   }
                                      });			 
	  
		$("#search_start_date").focus(function(){///自动填写起始日期
		                                        
		                                       if($(this).val().length<1) $(this).val($("#search_end_date").val());
												});	
		
		 $("#search_material_name").focus(function(){
	                       if ($(this).val()=='输入品名') $(this).val('');
						    });
         $("#search_customer_id").focus(function(){
	                       if ($(this).val()=='客户号') $(this).val('');
						    });
	    
	   
	   })  
</script>
<body>
<div id=container>
<div id=head>
</div>
<div id=main>
  <h2>成品半成品检验报告</h2><p></p>
    <div id=main_left>
	    
	    <div id=grid>		</div>
		
       <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！	  </div>
	 <!--查询套件 -><-->
	    <div class=button_right><form id=searchs action="">
	    <select id=search_final_product><option value=''>成品半成品</option><option value='F'>成品</option><option value='S'>半成品</option></select>
	  
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
</body>
</html>
<?php
/*  material_in_pending文件的结尾 */
/*  在系统中的位置: ./application/views/material_in_pending*/
?>
