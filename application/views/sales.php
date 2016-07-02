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
 * Sales.php 订单列表视图文件
 *
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
    var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
    
	var passing;
	var inner; var clickedId=null;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
    $(function(){
		   function build_grid(){cjTable( '#grid','sales_list?type=Y&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,通知单,日期,定单号,客户订单号,物料ID,物料名,规格,数量,价格,税率,金额,审核日期 ',////表格标题
							 '300px','final'   );                                    ///表格高度,需要隐藏的td
                            }
	        function build_search_grid(search_string){cjTable( '#grid','sales_list?type=Y&s=0'+string,////url of data source
							'序号,通知单,日期,定单号,客户订单号,物料ID,物料名,规格,数量,价格,税率,金额,审核日期 ',////表格标题
							 '300px','final'   );                                   ///高度,,需要隐藏的td列
                            }	
           function build_grid_tr(){cjTable_tr( 'sales_list?in_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'final'   );                                    ///表格高度,需要隐藏的td
                            }	
       
	   ///////////////////////////validate update///
	   
	   $("#head").makemenu2(base_url);////顶部菜单
	  
	    build_grid();
	
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
						   search_string=search_string+'&customer_id='+$("#search_customer_id").val();
						   string=search_string;
						   build_search_grid();
						               }else{
									   alert(erro);
									   }
									   
                                      });			 
	  	  $("#stat").click(function(){///汇总统计
						   $("#page").val(0);
						   var erro='';
						   if($("#search_start_date").val().length>0)  erro=verify("#searchs",'start_date,起始日期,isdate');
						   if($("#search_end_date").val().length>0)    erro=verify("#searchs",'end_date,截止日期,isdate');
						   
						   if(erro==''){
						   search_string='&material_id='+$("#search_material_id").val();
						   search_string=search_string+'&start_date='+$("#search_start_date").val();
						   search_string=search_string+'&end_date='+$("#search_end_date").val();
						   search_string=search_string+'&customer_id='+$("#search_customer_id").val();
						   string=search_string; //alert(string);
						   $.getJSON('sales_list?s=1&dates='+Math.floor(Math.random()*9999+1)+'&stat=1&'+string,
									 function(result)
									                { var sum="";
													  $.each(result,function(k,v){
													                              $.each(v,function(kk,vv)
																				          {
																						   if(kk=='amount')   sum+="金额:"+vv+'';
																						   if(kk=='total_qty')sum+="数量:"+vv+'&nbsp;&nbsp;&nbsp;&nbsp;';
																						   if(kk=='currency') sum+="元 "+vv;
																						   });
													                             });
													
													$("#total").html(sum);$("#total").html().replace('null','0.00');
													
													 });	
									 
									  
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
						   cjTable_light5('selection','product_list?s=1&material_name='+$("#search_material_name").val()+'&dates='+Math.floor(Math.random()*999+1),
							 'production_id,material_name1',////表格标题
							 '500px','amaterial_id,material_specification,meausrement','yes','#search_material_name,1||#search_material_id,0');
						    $("#selection").show();
							   }
							}
						   	
	                     );
		 $("#search_material_name").focus(function(){
	                       if ($(this).val()=='输入品名') $(this).val('');
						    });	
		 $("#search_customer_name").focus(function(){
	                       if ($(this).val()=='输入客户') $(this).val('');
						    });					
		 $("#search_customer_name").keyup(function(){
		                      if($(this).val().length>1)
							  {
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							   var url='../welcome/customer_list?s=1&customer_name='+$(this).val()+'&dates='+Math.floor(Math.random()*999+1);
							   //alert(url);
							   cjTable_light5('selection',url,
							 'customer_id,customer_name1',////表格标题
							 '500px','customer_address,contact,phone,delivery_note','yes','#search_customer_name,1||#search_customer_id,0');
							  }
							  if ($(this).val().length==0) $("#selection").remove();
						   });	
 });
</script>
<body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>销售统计</h2><p></p>
    <div id=main_left>
	    <form id=form_print>
	    <div id=grid>
		
		</div>
		</form>
<div>		
<span id=total></span>
　　　　<!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
	   从<input type=text id=search_start_date name=start_date size=6 value=""/>到
	   <input type=text id=search_end_date name=end_date size=6 value="<?php echo date('Y-m-d',time());?>"/>
		<input type=text id=search_material_name name=material_name autocomplete=off size=6 value="输入品名"/>
		<input type=text id=search_customer_name name=customer_name autocomplete=off size=6 value="输入客户"/>
		<input type=hidden id=search_customer_id name=customer_id >
		<input type=hidden id=search_material_id name=material_id />
		<input type=button id=search value="搜索"/>  <input type=button id=stat value="汇总"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="hidden" id="page" name="page" value=0  >
        <button id=previous_page>前页</button><button id=next_page>后页</button>  
		</form> 
       </div>
　    <!--查询套件结束 -><-->
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
