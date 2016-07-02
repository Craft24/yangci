<?php
/**
 * EachERP
 * EachERP是开源软件,基于PHP 5.1.6 以上版本和CodeIgniter 2.0框架
 * @软件包	EachERP
 * @授权		http://EachERP.net/user_guide/license.html
 * @链接		http://EachERP.net
 * @版本	    0.1beta

 * 版权所有(C) 2015 作者:陈国彤
本程序为自由软件；您可依据自由软件基金会所发表的GNU 通用公共授权条款，对本程序再次发布和/ 或修改；无论您依据的是本授权的第三版，或（您可选的）任一日后发行的版本。
本程序是基于使用目的而加以发布，然而不负任何担保责任；亦无对适售性或特定目的适用性所为的默示性担保。详情请参照GNU 通用公共授权。
您应已收到附随于本程序的GNU 通用公共授权的副本；如果没有，请参照<http://www.gnu.org/licenses/>.
 * report.php 报表视图文件
 * @category	stat
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
<script src="../../cjquery/src/reportgrid.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
<?php 
          
	      echo 'var report_type="'   .$report_type.'";';//良品次品待定品
	      echo 'var final_product="'.$final_product.'";';//成品半陈品
?>		

    var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
    
	var passing;
	var inner; var clickedId=null;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';	
     $(function()
	    {	
		  if(report_type=="m")
		   {
	       var report_url='material_inventory_print?s=0&dates='+Math.floor(Math.random()*9999+1)
		   var head_string='原材料ID,原材料名称,规格,状态,库存,单位,盘点数量,复盘人,盘赢,盘亏';////表格标题
		   var hide_string='in_id';
		   var report_head="库存盘点";
           }
		  if(final_product=="s")
		   {
		   var report_url='product_inventory_print?final_product=s&s=0&dates='+Math.floor(Math.random()*9999+1)
		   var head_string='ID,品名,规格,状态,库存,单位,盘点数量,复盘人,盘赢,盘亏';////表格标题
		   var hide_string='in_id';
		   var report_head="半成品盘点";
	        }
		   var report_url='product_inventory_print?s=0&dates='+Math.floor(Math.random()*9999+1)
		   var head_string='ID,品名,规格,状态,库存,单位,盘点数量,复盘人,盘赢,盘亏';////表格标题
		   var hide_string='in_id';
		   var report_head="产品盘点";
           
		   
		   
		   
		   
		  $("#print").click(function(){
									   $(this).hide();$(".button_right").hide();
									   window.print();
									   $(this).show();$(".button_right").show();
		                              }); 

		   function build_search_grid(search_string){cjTable( '#grid',report_url+string,head_string,
							 
							  '300px',hide_string);                                 ///高度,,需要隐藏的td列
                              $("#report_title").html(report_head);
	                          $("#foot").html('第'+  (parseInt($("#page").val())+1)+'页');	
							
							}						
	   ///////////////////////////validate update///
	   
	 
	       
	       build_search_grid();
		   

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

	   })  
	   
	   
	               
</script>


<body>
<button id=print>打印</button>
<div id=container>

<div > <!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
	  
		
		<input type="hidden" id="page" name="page" value=0  >
        <input type=button id=previous_page value=前页 ><input type=button id=next_page value=后页 >  
		</form> 
       </div>
	   <!--查询套件结束 -->
  <h2><div id=report_title>原材料库存</div></h2><p></p>
    <div id=main_left>
	    
	<div id=grid>
		
	</div>
    <div class=clear></div>
<div id=foot>第页</div> 
         
	  </div>
	
	<div id=main_right>
	</div>

</div>


</div>
</div>

</body>
</html>
