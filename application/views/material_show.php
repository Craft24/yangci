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
 * material_show 原材料清单修改和查询页面的主视图文件
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
    var search_string;
	var page_string;
	var string;
	var clickedId=null;
	var base_url='<?php echo base_url();?>';
	 function material_category(){   var option='';///取得物料的分组
						             $.getJSON('material_category?dates='+Math.floor(Math.random()*9999+1),function(result){
									                                                                  $.each(result, function(k,v)
																									  {
																										   $.each(v,function(kk,vv)
																										   {  
																											 if(kk=='material_category' && vv!='')option+='<option value='+vv+'>'+vv+'</option>';
																											 
																											}); 
																											
																									 });option+='<option value=0>添加新分组</option>';
																											$(".material_category").empty().append(option);
																											$(".category_new").remove();
								                                                  });
								   
								   																	 
								 }																	  
     function build_grid_tr(in_id){ 
	                               cjTable_tr('material_list?material_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',0);   
	                     ///表格高度,需要隐藏的td
                            }
	function build_grid_tr_add(in_id){cjTable_tr( 'material_list?material_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',1);                        ///表格高度,需要隐藏的td
                            }

 
    $(function(){
	   
	   $("#head").makemenu2(base_url);////顶部菜单
	   //$("#main_right").makemenu_side();
	   //$("#material_category_new").hide();
	   material_category();
	   function build_grid(){cjTable( '#grid','material_list?final_product=R&dates='+Math.floor(Math.random(999)*999+1),////url of data source
							 '产品序号,简称,全称,规格,规格2,计量单位,条形码,安全库存,最小起订量,物料分组,废弃,备注',////表格标题
							 '300px',''  );                                    ///表格高度
                            }
	   function build_search_grid(search_string){cjTable( '#grid','material_list?final_product=R&s=1'+string,////url and search string 
							  '产品序号,简称,全称,规格,规格2,计量单位,条形码,安全库存,最小起订量,物料分组,废弃,备注',////表格标题
							 '300px',''   );                                    ///高度
                            }					
	   build_grid();bound_search_controls("原料");
	   $("#search_start_date").hide();
	   $("#search_end_date").hide();
	   $("#search_warehouse").hide();
	   $("#search_supplier").hide();	
       ////////////update handling
	   $("#form_update_button").click(
	                      function (){
						             $("#material_barcode_update").val($.trim($("#material_barcode_update").val()));
									if($("#material_barcode_update").val()=='') $("#material_barcode_update").val(0);
									$("#moq_update").val($.trim($("#moq_update").val()));  
									if($("#moq_update").val()=='') $("#moq_update").val(0);
									
									 var data=$("#form_update").serialize();
									
									 var erro=verify("#form_update",'measurement,单位,required||material_name,材料名称,required||material_specification,规格,required||material_name2,物料全称,required||material_barcode,条形码,digital||safe_invent,安全库存,digital||moq,最小订货,digital');

									  if (erro==''){
									 var update_url='material_list_update';///更新操作指向的页面
	                                 updates(update_url,data);
									
									 }else{ note(erro);}
	                                }
	                           );
       ////////////add new handling
	   $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									$("#material_barcode").val($.trim($("#material_barcode").val()));
									if($("#material_barcode").val()=='') $("#material_barcode").val(0);
									$("#moq").val($.trim($("#moq").val()));  
									if($("#moq").val()=='') $("#moq").val(0);
									
									$("#safe_invent").val($.trim($("#safe_invent").val())); if($("#safe_invent").val()=='') $("#safe_invent").val(0);       
									 var erro=verify("#form_add",'measurement,单位,required||material_name,材料名称,required||material_specification,规格,required||material_name2,物料全称,required||material_barcode,条形码,digital||safe_invent,安全库存,digital||moq,最小订货,digital');
									 
									 
									 if (erro==''){
									 var add_url='../welcome/material_list_add';///新增操作指向的页面
	                                 adds_2(add_url);
									// $("#material_category").empty().append('<option value=0>请选择物料分组</option>');
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
												
												$(clickedId).css('background-color','#bbb');
												var update_content=$(clickedId).html();
												var update_content=update_content.replace(/<\/div>/g,'');  
												var update_content=update_content.replace(/<div class="innercell">/g,'');    
												var update_content=update_content.replace(/<\/td>/g,'');
												var update_content=update_content.replace(/<td style="display:none;">/g,'<td>');
												var update_content=update_content.split("<td>");
												$("#form_update input").each(function(index){$(this).val(update_content[index+1]);          });
												$("#update_hiddens").show();
												var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('物料分组')").index();
												var category_selected=$(clickedId).find('td').eq(col).text();
												//$("#material_category_update").find("option[text='"+category_selected+"']").attr("selected",true);
												
												$("#form_update_button").val('更新');
												resize('#update_hiddens');	$("#error").hide();	
												var c=$("#material_category_no_use").val();
									            $("#material_category_update").val(c);
												
												});	
	  $("#button_remove").click(
	                      function(){
						             if(clickedId==null)
									                    {
									                       $("#error").show();resize("#error");
														   return false;
														}  
									 var remove_url='../welcome/material_list_remove?material_id='+$(clickedId).find('td').eq(0).text();///移除操作指向的页面
									 if (remove(remove_url)){ $("#clickedId").remove(); clickedId=null;$("#error").hide();}///删除
									 
										
	                                }
	                     );       
      $(".category_refresh").click( function(){
	                                
	                                 material_category();	
						                } );
	  $(".material_category").change( function(){
	                                     if($(this).val()==0)                                 
	                                	 {$(this).parent().append('<input type=text class=category_new name=category_new size=10 >');}
										 else{$(".category_new").remove();}
										} );								
     
	  $("#material_name2").click(function(){
	                                 $(this).val($("#material_name").val());	
						            } );
	  $("#keyword").keyup(
	                      function(){
									 var keyword=$("#keyword").val();/////////////////过滤操作
	                                 filter_material(keyword);
	                                }
	                     );       
      $("#button_search").click(
	                      function(){
									 var 
									 search_string="&"+$("#search1").attr('name')+"="+$("#search1").val();///搜索操作使用的字符串//请替换=号和Search1,2,3的input的name属性
	                                 build_search_grid(search_string);
	                                }
	                     ); 
	 						  
	  $("#search").click(function(){
						   $("#page").val(0);
						   var erro='';
						  
						   if(erro==''){
						   search_string='&material_id='+$("#search_material_id").val();
						   string=search_string;
						   build_search_grid();
						               }else{
									   note(erro);
									   }
									   
                                      });			 
	  				 				 
      $("#searchs").submit(function () {
                                       return false;
                                        }); 


	   })  
	   
	   
	               
</script>


<body>
<div id=container>
<div id=head>


</div>
<div id=main><h2>原材料全表</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
        <button id=button_add>新增</button><button  id=button_remove >删除</button><button  id="updates" >更新</button>
      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>

　　　　<!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
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
						  <tr>
						    <td></td>
						    <td><input type="hidden" name="material_id" id="material_id" readonly /></td>
						    <td>&nbsp;</td>
						    <td>&nbsp;</td>
					      </tr>
						  <td width="64">
						名称</td><td width="187"> <input type=text name=material_name id=material_name class="required" />
						  *</td>
						<td width="141"><div align="right">全称:</div></td>
						<td width="213"><input type="text" id=material_name2 name="material_name2" class="required" />						  
						  *</td>
						</tr><tr height=35><td>
						规格</td><td><input type=text name=material_specification   class="required"/>
						  *</td>
						<td><div align="right">其他规格(比如颜色):</div></td>
						<td><input type="text" name="material_specification2"   class="required"/></td>
						</tr><tr height=35><td>
						单位</td><td> <input type=text id=measurement name=measurement size=6 class="required"  />
						  (例如:个,公斤,米...)
						*<td><div align="right">条形码:                        </div>
						<td><input name="material_barcode" type="text"   class="required" id="material_barcode_update"/>                        
						<tr height=35><td>
						安全库存</td>
						  <td><input name="safe_invent" type="text" id="safe_invent_update" size="6" value="0" ></td>
						  <td><div align="right">最小订货</div></td>
						<td><input type=text id=moq_update name=moq size=6 value="0"  /></td>
						</tr><tr><td>
						物料分组</td><td><select id=material_category_update name=material_category class=material_category>
						
						</select><a href="javascript:void(0)" onclick="material_category()">刷新</a><input type=hidden name=no_use id=material_category_no_use value=R />
						  </td>
						<td><div align="right">废弃</div></td>
						<td><select class="removed" name="removed" id=removed_update >
                            <option value=n>否</option>
						    <option value=Y>是</option>
                            </select>
						    <input type=hidden name=no_use value=R />
						   </td>
						</tr>
						<tr>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td><div align="right">备注:</div></td>
						  <td><input type=text name=remark size=6   /></td>
						  </tr>
						<tr height=35><td colspan=5>						  带*号的为必填项目</td>
						</tr>

						<tr height=50><td colspan=3><input  type=button id=form_update_button value='更新' /><input type=hidden name=final_product value=R /><br /><br /></td></tr>
						</table>
						</form> 
				  </div>
		</div>
      <!--用于更新的pop up--结束 -->
 
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">新增<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update><tr height=35><td width="64">
						名称</td><td width="187"> <input type=text name=material_name id=material_name class="required" />
						  *</td>
						<td width="141"><div align="right">全称:</div></td>
						<td width="213"><input type="text" id=material_name2 name="material_name2" class="required" />
						  *</td>
						</tr><tr height=35><td>
						规格</td><td><input type=text name=material_specification   class="required"/>
						  *</td>
						<td><div align="right">其他规格(比如颜色):</div></td>
						<td><input type="text" name="material_specification2"   class="required"/></td>
						</tr><tr height=35><td>
						单位</td><td> <input type=text id=measurement name=measurement size=6 class="required"  />
						  (例如:个,公斤,米...)
						*<td><div align="right">条形码:                        </div>
						<td><input name="material_barcode" type="text"   class="required" id="material_barcode"/>                        
						<tr height=35><td>
						最小订货</td>
						  <td><input type=text id=moq name=moq size=6 value="0"  /></td>
						  <td><div align="right">安全库存:</div></td>
						<td><input name="safe_invent" type="text" id="safe_invent" size="6" value="0" ></td>
						</tr><tr><td>
						备注</td><td><input type=text name=remark size=6 /><input type=hidden name=final_product value=R />
						  </td>
						<td><div align="right">物料分组:</div></td>
						<td>
						<select id=material_category name=material_category class=material_category>
						</select>&nbsp;<a href="javascript:void(0)" onclick="material_category()">刷新</a></td>
						</tr>
						<tr>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td><div align="right"></div></td>
						  <td>					  </td>
						  </tr>
						<tr height=35><td colspan=5><input class=button type=button id=form_button_add value="新增" />
						  带*号的为必填项目</td>
						</tr>
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
<?php
/*  material_show文件的结尾 */
/*  在系统中的位置: ./application/views */
?>
</html>
