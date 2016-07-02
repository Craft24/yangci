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
 * material_price 原材料平均价格的调整和查询页面的主视图文件
 * @category welcome
 * @源代码
 */
date_default_timezone_set('Asia/Shanghai');
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
     function build_grid_tr(in_id){ 
	                               cjTable_tr('material_price_list?price_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',0);   
	                     ///表格高度,需要隐藏的td
                            }
	function build_grid_tr_add(in_id){cjTable_tr( 'material_price_list?price_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',1);                        ///表格高度,需要隐藏的td
                            }

 
    $(function(){
	
       
	   //////////////////////////validate update///
	  
	   //////////////////////
	   
	   $("#head").makemenu2(base_url);////顶部菜单
	   //$("#main_right").makemenu_side();
	   function build_grid(){cjTable( '#grid','material_price_list?final_product=R&dates='+Math.floor(Math.random(999)*999+1),////url of data source
							 '序号,名称,全名,材料ID,规格,规格2,价格,税率,单位,起价量从,起价量止,供应商,供应商名称,输入,定价日期,审核人,审核日期',////表格标题
							 '350px',''  );                                    ///表格高度
                            }
	   function build_search_grid(search_string){cjTable( '#grid','material_price_list?final_product=R&s=1'+string,////url and search string 
							  '序号,名称,全名,材料ID,规格,规格2,价格,税率,单位,起价量从,起价量止,供应商,供应商名称,输入,定价日期,审核人,审核日期',////表格标题
							 '350px',''   );                                    ///高度
                            }					
	   build_grid();	
	   bound_search_controls('原料');
	   $("#search_start_date").hide();
	   $("#search_end_date").hide();
	   $("#search_warehouse").hide();
      ////////////update handling
	  $("#form_button_update").click(
	                      function (){
						             var data=$("#form_update").serialize();
									 var erro=verify("#form_update",'material_name,材料名称,required||material_specification,规格,required||price,价格,digital||qty_range_from,起价范围,digital||qty_range_to,起价范围,digital||tax,税率,digital');
									  if (erro==''){
									 var update_url='material_price_update';///更新操作指向的页面
	                                 updates(update_url,data);
									
									 }else{ note(erro);}
	                                }
	                           );
      ////////////add new handling
	 
	   $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									
									  var erro='';//verify("#form_add",'measurement,单位,required||material_name,材料名称,required||material_specification,规格,required||moq,最小起订量,digital');
									 
									 if (erro==''){
						          
									 var add_url='material_price_add';///新增操作指向的页面
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
														
												$(clickedId).css('background-color','#bbb');
												var update_content=$(clickedId).html();
												var update_content=update_content.replace(/<\/div>/g,'');  
												var update_content=update_content.replace(/<div class="innercell">/g,'');    
												var update_content=update_content.replace(/<\/td>/g,'');
												var update_content=update_content.replace(/<td style="display:none;">/g,'<td>');
												var update_content=update_content.split("<td>");
												$("#form_update input").each(function(index){ if($(this).attr('id')!="form_button_update")
												                                              $(this).val(update_content[index+1]);          });
											
												$("#update_hiddens").show();
												resize('#update_hiddens');	$("#error").hide();	$("#form_update_button").val('更新');
												}	
																
							                    );  						   						   
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

	  $("#keyword").keyup(
	                      function(){
									 var keyword=$("#keyword").val();/////////////////过滤操作
	                                 filter_material(keyword);
	                                }
	                     );
	
	 	     ///////////审核						   
	    $("#button_approve").click(
	                      function (){
						            
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') erro='不能再审核';
									 if(erro=='')
									 {
									  	 var url='material_price_approve?price_id='+$(clickedId).find('td').eq(0).text();
										 updates(url); 
							  		  }else{
									       note(erro);
									       }					 
	                                });        
        ///////////反审核						   
	    $("#button_revocation").click(
	                      function (){
						            
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()=='') erro='不能反审核';
									 if(erro=='')
									 {
									  	 var url='material_price_revocation?price_id='+$(clickedId).find('td').eq(0).text();
										 updates(url); 
							  		  }else{
									       note(erro);
									       }					 
	                                });        					   	
	                     								        
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
						   search_string+='&supplier_id='+$("#search_supplier_id").val();
						   search_string=search_string+'&final_product=R';
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
<div id=main><h2>采购价格管理</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
        <button id=button_add>新增</button><button  id=button_remove >删除</button><button  id="updates" >更新</button> <button   id=button_approve >审核</button> <button   id=button_revocation >反审核</button> 
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
				        <form id=form_update><input type=hidden id=update_price_id name=price_id  class=price_id />
						<table class=table_update><tr height=35><td>
						名称</td><td> <input type=text id=update_material_name name=material_name autocomplete=off class=material_name /></td></tr><tr height=35><td>
						全名</td><td> <input type=text id=update_material_name2 name=material_name2 autocomplete=off class=material_name2 /><input type=hidden id=update_material_id name=material_id  class=material_id /></td></tr><tr height=35><td>

						规格</td><td><input type=text id=update_material_specification name=material_specification  id=update_material_specification readonly /></td></tr><tr height=35><td>
						规格2</td><td><input type=text id=update_material_specification2 name=material_specification2  class=update_material_specification2 readonly /></td></tr><tr height=35><td>
						<!--工序</td><td> <input type=text id=process name=process size=6  /><a target=_blank href=../../help/help.php#process><img src=../../img/help.gif height=20/></a>-->
						</td></tr>
						<tr height=35><td>
						价格</td><td><input type=text id=update_price name=price size=6   /></td></tr><tr><td>
						<tr height=35><td>
						税率</td><td><input type=text id=update_tax name=tax size=5  />(例如:0.17和0.06)</td></tr><tr><td>
						<tr height=35><td>
						单位</td><td> <input type=text id=update_measurement name=measurement size=6 autocomplete=off  />(例如:个,公斤,米...)
						</td></tr><tr height=35><td>
						数量从</td><td><input type=text name=qty_range_from id=update_qty_range_from size=6  placeholder="可选填" />到<input type=text id=update_qty_range_to name=qty_range_to size=6  placeholder="可选填" />之间</td></tr>
						<tr height=35><td>
						供应商</td><td><input type=hidden id=add_supplier_id name=supplier_id class=supplier_id size=6   /><input type=text id=add_supplier_name class=supplier_name name=supplier_name size=6  autocomplete=off /></td></tr><tr><td>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_update value="更新" /></td></tr>
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
						<table class=table_update><tr height=35><td>
						名称</td><td> <input type=text id=add_material_name name=material_name  placeholder="输入品名前2位字符,选择" autocomplete=off class=material_name /><input type=hidden id=add_material_id name=material_id  class=material_id /></td></tr><tr height=35><td>
						全名</td><td> <input type=text id=add_material_name2 name=material_name2  autocomplete=off class=material_name2 />
						规格</td><td><input type=text name=material_specification class=material_specification id=add_material_specification readonly /></td></tr><tr height=35><td>
						规格2</td><td><input type=text name=material_specification2 class=material_specification2 id=add_material_specification2 readonly /></td></tr><tr height=35><td>
						单位</td><td> <input type=text id=add_measurement name=measurement class=measurement size=6 autocomplete=off  />(例如:个,公斤,米...)
						</td></tr><tr height=35><td>
						<!--工序</td><td> <input type=text id=process name=process size=6  /><a target=_blank href=../../help/help.php#process><img src=../../img/help.gif height=20/></a>-->
						</td></tr>
						<tr height=35><td>
						价格</td><td><input type=text id=add_price name=price size=6   /></td></tr><tr><td>
						<tr height=35><td>
						税率</td><td><input type=text id=add_tax name=tax size=5  />(例如:0.17和0.06)</td></tr><tr><td>
						<tr height=35><td>
						数量从</td><td><input type=text name=qty_range_from id=qty_range_from size=6 placeholder="可选填"  />到<input type=text id=qty_range_to name=qty_range_to size=6  placeholder="可选填" />之间</td></tr><tr><td>
						<tr height=35><td>
						供应商</td><td><input type=text id=add_supplier_name class=supplier_name name=supplier_name size=6  autocomplete=off /><input type=hidden id=add_supplier_id name=supplier_id class=supplier_id size=6   /></td></tr><tr><td>
						
						备注</td><td><input type=text name=remark size=6   /></td>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="新增" /></td></tr>
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
/*  material_price文件的结尾 */
/*  在系统中的位置: ./application/views/ */
?>
</html>
