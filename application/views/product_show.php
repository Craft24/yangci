<?php
/**
 * EachERP
 * EachERP是开源软件,基于PHP 5.1.6 以上版本和CodeIgniter 3.0框架
 * @软件包	EachERP
 * @授权		http://EachERP.net/user_guide/license.html
 * @链接		http://EachERP.net
 * @版本	    2.0

 * 版权所有(C) 2015 作者:陈国彤
本程序为自由软件；您可依据自由软件基金会所发表的GNU 通用公共授权条款，对本程序再次发布和/ 或修改；无论您依据的是本授权的第三版，或（您可选的）任一日后发行的版本。
本程序是基于使用目的而加以发布，然而不负任何担保责任；亦无对适售性或特定目的适用性所为的默示性担保。详情请参照GNU 通用公共授权。
您应已收到附随于本程序的GNU 通用公共授权的副本；如果没有，请参照<http://www.gnu.org/licenses/>.
 * * product_show.php 产品清单的主视图
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
    var search_string;var page_string;var string;
	var clickedId=null;
	var base_url='<?php echo base_url();?>';
	function build_grid_tr(){cjTable_tr( 'product_list?material_id='+$(clickedId).find('td').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',0);                      ///表格高度,需要隐藏的td
                            }
    function build_grid_tr_add(in_id){cjTable_tr( 'product_list?in_id='+in_id+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',1);                        ///表格高度,需要隐藏的td
                            }

    $(function(){
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','product_list?final_product=all&dates='+Math.floor(Math.random(999)*999+1),////url of data source
							 '产品序号,名称,全名,规格,规格2,单位,工序,类型,来源,建立日,备注,条码',////表格标题
							 '300px',''  );                                    ///表格高度
                            }
	   function build_search_grid(search_string){cjTable( '#grid','product_list?s=1'+string,////url and search string 
							 '产品序号,名称,全名,规格,规格2,单位,工序,类型,来源,建立日,备注,条码',////标题
							 '300px',''   );                                    ///高度
                            }					
	   build_grid();	
	   bound_search_controls('成品');			   
      ////////////update handling
	  $("#form_update_button").click(
	                      function (){
						             var erro=verify("#form_update",'measurement,单位,required||material_name,材料名称,required||material_name2,材料全名,required||material_specification,规格,required||final_product,产品类型,required ||process,工序,required||resource,来源,required');
									 if (erro==''){
									 var data=$("#form_update").serialize();
									 var update_url='product_list_update?'+data;///更新操作指向的页面
									 updates(update_url);
									 }else{ note(erro);}
	                                }
	                           );
      ////////////add new handling
	 
	  $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									  var erro=verify("#form_add",'measurement,单位,required||material_name,材料名称,required||material_name2,材料全名,required||material_specification,规格,required||process,工序,required||final_product,产品类型,required||resource,来源,required');
									 if (erro==''){
						             var data=$("#form_add").serialize();
									 var add_url='product_list_add?'+data;///新增操作指向的页面
	                                 adds_2(add_url);
	                                }else{  note(erro);}
									}
	                          ); 
		$("#updates").click( function()
												{
												 if(clickedId==null)
									                    {
									                       $("#error").show();resize("#error");
														   return false;
														 } 
											
												var update_content=$(clickedId).html();
												var update_content=update_content.replace(/<\/div>/g,'');  
												var update_content=update_content.replace(/<div class="innercell">/g,'');    
												var update_content=update_content.replace(/<\/td>/g,'');
												var update_content=update_content.replace(/<td style="display:none;">/g,'<td>');
												var update_content=update_content.split("<td>");
												$("#form_update input").each(function(index){$(this).val(update_content[index+1]); 
												                                             if($("#final_product_hidden").val()=='成品')
																							   { $("#final_product_update option[value='F']").attr("selected",true);
												                                                }else{
																								$("#final_product_update option[value='S']").attr("selected",true);
																								}
																							if($("#resource_hidden").val()=='自制')
																							   { $("#resource_update option[value='2']").attr("selected",true);
												                                                }else{
																								$("#resource_update option[value='3']").attr("selected",true);
																								}	
																								
																								
																								
																							 });
												
												
												////////////get value from other input, and set the option selected,then remove the input
												comboBox_getvalue();
												$("#form_update_button").val('确定');resize('#update_hiddens');
												$("#update_hiddens").show();$("#update_hiddens").hide();$("#update_hiddens").show();
												resize('#update_hiddens');	$("#error").hide();	// alert($(clickedId).find('td').eq(0).text());
												}	
																
							                    );  						   						   
	  $("#button_remove").click(
	                      function(){
						             if(clickedId==null)
									                    {
									                       $("#error").show();resize("#error");
														   return false;
														 }  
									 var remove_url='product_list_remove?material_id='+$(clickedId).find('td').eq(0).text();///移除操作指向的页面
									 
									 updates(remove_url);build_grid();
									 $("#error").hide();	
	                                }
	                     );       

      $("#button_search").click(
	                      function(){
									 var 
									 search_string="&"+$("#search1").attr('name')+"="+$("#search1").val();///搜索操作使用的字符串//请替换=号和Search1,2,3的input的name属性
	                                 build_search_grid(search_string);
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
						   search_string=search_string+'&final_product='+$("#final_product_search").val();
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
		
        $('.warehouse_name').hide();
        $('#search_supplier').hide();

	   })  
	   
	   
	               
</script>


<body>
<div id=container>
<div id=head>


</div>
<div id=main><h2>(半)成品全表</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
        <button id=button_add>新增</button><button  id=button_remove >删除</button><button  id="updates" >更新</button>
      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>

　　　　<!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
	    类型<select id=final_product_search name=final_product ><option value=''>请选择</option><option value='F' selected>成品</option><option value='S'>半成品</option></select>
        <?php include "search_control.php";?>
		</form> 
       </div>
　    <!--查询套件结束 -><-->

	<!--用于更新的pop up-->
		<div id="update_hiddens" class="pop_up">
		              <div class="div_title">修改<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_update>
						<table class=table_update>
						<tr height=35><td>
						</td><td><input type=hidden name=material_id readonly size=5 /></td></tr><tr height=35><td>
						名称</td><td> <input type=text name=material_name  /></td></tr><tr height=35><td>
						全名</td><td> <input type=text name=material_name2  /></td></tr><tr height=35><td>
						规格</td><td><input type=text name=material_specification   /></td></tr><tr height=35><td>
						规格2</td><td><input type=text name=material_specification2   /></td></tr><tr height=35><td>
						单位</td><td> <input type=text id=measurement name=measurement size=6   /></td></tr><tr height=35><td>
						工序</td><td> <input type=text id=process name=process size=6  /><a target=_blank href=../../help/help.php#process><img src=../../img/help.gif height=20/></a></td></tr>
						<tr height=35><td>
						来源</td><td> <select id=resource_update name=resource><option value=''>请选择</option><option value='2'>自制</option><option value='3'>外协</option></select>
						</td></tr>
						<tr height=35><td>
						类型</td><td><select id=final_product_update name=final_product><option value=''>请选择</option><option value='F'>成品</option><option value='S'>半成品</option></select>
						<input type=hidden id="final_product_hidden" name="final_product_hidden" />
						<input type=hidden id="resource_hidden" name="resource_hidden" />
						</td></tr>
						<tr height=35><td>
						备注</td><td><input type=hidden name=built_date /><input type=text name=remark   /></td></tr>
						<tr height=35><td>
						条码</td><td><input type=text name=material_barcode /></td></tr>
						<tr height=35><td colspan=3><input type=button id=form_update_button value="更新" /></td></tr>
						</table>
						</form><br /><br />
				  </div>
		</div>
      <!--用于更新的pop up--结束 -->
 
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up" width=170%>
		              <div class="div_title">新增<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update  ><tr height=35><td>
						名称</td><td> <input type=text name=material_name placeholder="简称" /></td></tr><tr height=35><td>
						全名</td><td> <input type=text name=material_name2 placeholder="正式名称,比较长" /></td></tr><tr height=35><td>
						规格</td><td><input type=text name=material_specification   /></td></tr><tr height=35><td>
						规格2</td><td><input type=text name=material_specification2 placeholder="颜色尺寸包装等"  /></td></tr><tr height=35><td>
						单位</td><td> <input type=text id=measurement name=measurement placeholder="个,张,公斤,升" size=6   />
						
						</td></tr>
						<tr height=35><td>
						来源</td><td> <select id=resource name=resource><option value=''>请选择</option><option value='2'>自制</option><option value='3'>外协</option></select>
						</td></tr>
						<tr height=35><td>
						类型</td><td> <select id=final_product_add name=final_product> 
						              <option value='Y'>成品</option> <option value='S'>半成品</option>
						              </select>   
						              
						</td></tr>
						<tr height=35><td>
						工序</td><td> <input type=text id=process name=process size=12 placeholder="例如:冲压,裁剪,电镀,加热" /><a target=_blank href=../../help/help.php#process><img src=../../img/help.gif height=20/></a>
						</td></tr>
						<tr><td>
						备注</td><td><input type=text name=remark size=6   /></td>
						</tr>
						<tr><td>
						条码</td><td><input type=text name=material_barcode size=6   /></td>
						</tr>
						<tr height=35><td colspan=3><input  type=button id=form_button_add value="新增" /></td></tr>
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
</html>
