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
 * product_in_list.php  产品半产品入库的建立,修改和查询页面的主视图文件
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
	var inner;var clickedId=null;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
	 function build_grid_tr(){cjTable_tr( 'product_in_list_show?in_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							
							 '',0  );                                    ///表格高度,需要隐藏的td
                            }	
    function build_grid_tr_add(in_id){cjTable_tr( 'product_in_list_show?in_id=new'+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',1);                        ///表格高度,需要隐藏的td
                            }

    $(function(){
	   $("#head").makemenu2(base_url);////顶部菜单
	   bound_search_controls('成品');
	   function build_grid(){cjTable( '#grid','product_in_list_show?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,入库日期,物料id,品名,全名,规格,规格2,状态,数量,单位,批号,生产单号,入库,审核,审核日期,成品, ,供应商名,仓库,退货',////表格标题题
							 '400px','currency_id,warehouse,supplier_ids');                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','product_in_list_show?s=0'+string,////url of data source
							 '序号,入库日期,物料id,品名,全名,规格,规格2,状态,数量,单位,批号,生产单号,入库,审核,审核日期,成品, ,供应商名,仓库,退货',////表格标题题
							 '400px','currency_id,warehouse,supplier_ids');                                    ///表格高度,需要隐藏的td
                            }
	    function updates2(url)
			                     {
	                                var data=$("#form_update").serialize();
	                                $.get(mainurl+url,
									      data,
										  function (result){
											                if (result=="yes"){
																				$('.pop_up').hide();
																				resize("#tipok");
																				$("#tipok").show().fadeOut(900);
																				build_grid_tr();
																				}
										                                      else{resize("#tipnote");
																			    $("#tipnote_word").html(result);
																				$("#tipnote").show().fadeOut(5000);
																				
																			  }            
										                      }
									     );     
	                             }					 				
							
	   build_grid();

	 ///////////更正						   
	  $("#form_button_update").click(
	                      function (){
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') erro='已经审核,不能更改.';
									 var data=$("#form_update").serialize();
									 erro+=verify("#form_update",'in_qty,数量,digital||production_id,生产单,digital||material_id,产品名称,required||production_id,生产单,required||house_id,仓库,required||warehouse,仓库,required');
									 if(erro=='')
									 {     //alert(data);
									  	  var url='product_in_correct?'+data;
										  updates(url);
										  
							  		  }else{
									       note(erro);
									       }					 
	                                });
	 ///////////反审核						   
	  $("#revocation").click(
	                      function (){
									  var erro='';
									

									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核')").index();///得到审核内容所在的列数
								  	 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()=='') erro='未审核过';
									 if(erro=='')
									 {
									  	  var url='product_in_revocation?id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();
										 
										  updates(url);//build_grid_tr();
										 
							  		  }else{
									       note(erro);
									       }					 
	                                });
	  
	 								
	  
	   $("#correct_material_name").keyup(function(){
		                      if($(this).val().length>1)
							  {
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							   cjTable_light5('selection','product_list?s=2&material_name='+$(this).val()+'&dates='+Math.floor(Math.random()*999+1),////url of data source
								 'material_id,material_name',///////表格标题
								 '300px','moq,remark',//要隐藏的字段
								 'yes','#correct_material_name,1||#correct_material_id,0'  ); ////是否不要标题行
							  }
							  if ($(this).val().length==0) $("#selection").remove();
						   }
	                     );									
       	$("#stat").click(function(){///汇总统计
						   $("#page").val(0);
						   var erro='';
						   if($("#search_start_date").val().length>0)  erro=verify("#searchs",'start_date,起始日期,isdate');
						   if($("#search_end_date").val().length>0)    erro=verify("#searchs",'end_date,截止日期,isdate');
						   
						   if(erro==''){
						   search_string='&material_id='+$("#search_material_id").val();
						   search_string=search_string+'&start_date='+$("#search_start_date").val();
						   search_string=search_string+'&end_date='+$("#search_end_date").val();
						   search_string=search_string+'&final_product='+$("#search_final_product").val();
						   search_string=search_string+'&warehouse='+$("#search_warehouse_id").val();
						   string=search_string; //alert(string);
						   $.getJSON('product_in_list_show?s=1&dates='+Math.floor(Math.random()*9999+1)+'&stat=1&'+string,
									 function(result)
									                { var sum="";
													  $.each(result,function(k,v){
													                              $.each(v,function(kk,vv)
																				          {
																						   if(kk=='amount')   sum+="金额:"+vv+'';
																						   if(kk=='total_qty')sum+="数量:"+vv+'<br>';
																						   if(kk=='currency') sum+="元 "+vv;
																						   });
													                             });
													note(sum);
													 });	
						               }else{
									   alert(erro);
									   }
									   
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
						   search_string=search_string+'&final_product='+$("#search_final_product").val();
						   search_string=search_string+'&warehouse='+$("#search_warehouse_id").val();
						   search_string=search_string+'&supplier_id='+$("#search_supplier_id").val();
						   string=search_string;//alert(string);
						   build_search_grid();
						               }else{
									   note(erro);
									   }
									   
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
		
	   })  
</script>
<body>
<div id=container>
<div id=head>
</div>
<div id=main>
  <h2>产品入库记录</h2><p></p>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
	
		
		
		<input type="button" id="revocation"  value="取消审核"/>
	
	　<!--查询套件 -><-->
	    
		<div class=button_right><form id=searchs action="">
		<select id=search_final_product name=final_product><option value=F>成品</option><option value=S>半成品</option></select>
		
		
<?php include "search_control.php";?>　</form> 
       </div>   
 <!--查询套件结束 -><-->
      </div>
      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
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
