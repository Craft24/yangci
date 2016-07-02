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
 * customer  客户管理的建立,修改和查询页面的主视图文件
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
<link href="../../cjquery/css/css.css" rel="stylesheet" type="text/css">
<script>
    var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
	var passing;
	var search_string;var page_string;var string;
    var error='';
    var clickedId=null;var clicked_line_index=null;
	var base_url='<?php echo base_url();?>'; 
	function build_grid_tr(in_id){
	
	
	cjTable_tr(     'customer_list?customer_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',0);                        ///表格高度,需要隐藏的td
                            }
	function build_grid_tr_add(in_id){cjTable_tr( 'customer_list?customer_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',1);                        ///表格高度,需要隐藏的td
                            }

    $(function(){
	  
	   //////////////////////
	   
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','customer_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							  '客户号,名称,地址,联系人,联系方式,交货须知,状态',////表格标题
							 '300px',''   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','customer_list?s=2'+string,////url and search string 
							  '客户号,名称,地址,联系人,联系方式,交货须知,状态',////表格标题
							 '300px',''   );                                    ///高度,,需要隐藏的td列
                            }					
	   build_grid();
      ////////////update handling
	  $("#form_button_update").click(
	                      function (){var erro='';
						              erro=verify("#form_update",'customer_id,客户ID,required||customer_name,客户名称,required||customer_address,地址,required||contact,联系人,required');
									 var data=$("#form_update").serialize();
									 
									 if (erro==''){
									 var update_url='customer_list_update';///更新操作指向的页面
	                                 updates(update_url,data);
									 }else{ note(erro);}
									 return false;
	                                }
	                           );
	  $("#button_remove").click(
	                      function (){
						             
									 var update_url='customer_remove?customer_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text();///
	                                 updates(update_url,'');
									 
	                                }
	                           );
      ////////////add new handling
	  $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									  //var data=$("#form_add").serialize();alert(data);
									  var erro=verify("#form_add",'customer_name,客户名称,required||customer_address,地址,required||contact,联系人,required');
									 
									 if (erro==''){
									 var add_url='customer_add';///新增操作指向的页面
	                                 adds_2(add_url);
	                                }else{  note(erro);}
									}
	                          ); 
	  $("#button_update").click( function()
												{
												 if(clickedId==null)
									                    {
									                       $("#error").show();resize("#error");
														   return false;
														 }  
														   
												 
												//$("#table1 tr").css('background-color','#fff');
												$(clickedId).css('background-color','#bbb');
												
												
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
																							 });
												
												
												////////////get value from other input, and set the option selected,then remove the input
												comboBox_getvalue();
												  
												 
												$("#form_button_update").val("确定");  
												$("#update_hidden").show();
												
												resize('#update_hidden');	$("#error").hide();	
												}	
																
							                    );  							   
	
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
	                                 if($("#search_customer_name").val().length>2)
									 search_string+="&customer_id="+$("#search_customer_id").val();
									 if($("#search_material_name").val().length>2)
									 search_string+="&material_id="+$("#search_material_id").val();
									 if($("#search_start_date").val().length==10)
									 search_string+="&start_date="+$("#search_start_date").val();
									 if($("#search_end_date").val().length==10)
									 search_string+="&end_date="+$("#search_end_date").val();
									 
									 build_search_grid(search_string);//alert(search_string);
									 
	                                }
	                     ); 
		$("#refresh").click(function(){
	                       build_grid();
						   }
	                     ); 
		$("#button_stat").click(function(){//统计
		                   var sum=0; var line=0; var total=new Object();
	                       $.each($("#table1 tr"), function()
						          {
										  if (line>0)
										  {
												  var qty=Number($(this).find('td').eq(8).text()); 
												  sum=sum+qty;
												  var amount=Number($(this).find('td').eq(10).text());
												  var currency=$(this).find('td').eq(11).text();
												  
												  if (total[currency]>0) {total[currency]+=amount;}else{total[currency]=amount;}
												  
										  }line++;
										  
								  });
								  var stat='数量:'+sum+'\r\n';
								  for(var i in total){ stat+=i+':'+total[i]+'\n';}
						   		
								  alert(stat);
						   }
	                     ); 							 				 
		////////////////如果没有select元素,请注释掉下面一行				 
		//loads_select("#measurement_waiting1,#measurement_waiting2",mainurl+'welcome/show_measurement',''); ///对select元素添加option ///有几个select元素加几行 	  			 
        $(".get_currency").click(function(){
		                   $("#selection").remove();
		                  $(this).parent().append("<div id=selection class=comboboxnote></div>");
	                       $("#selection").show();
						  
						   cjTable_light('selection','currency_list?s=0&dates='+Math.floor(Math.random()*999+1),////url of data source
							 'currency',///////表格标题
							 '100px'   );
						   }
	                     );
		 $(".pick_customer_names").keyup(function(){
		                      if($(this).val().length>1)
							  {
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							   cjTable_light4('selection','customer_list?s=2&customer_name='+$(this).val()+'&dates='+Math.floor(Math.random()*999+1),////url of data source
								 'customer_id,customer_name',///////表格标题
								 '100px','customer_id,customer_address,delivery_note,contact,phone',//要隐藏的字段
								 'yes'  ); ////是否不要标题行
							  }
							  if ($(this).val().length==0) $("#selection").remove();
						   }
	                     );	
		 $(".get_customer").click(function(){	
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							   cjTable_light4('selection','customer_list?s=0&dates='+Math.floor(Math.random()*999+1),////url of data source
								 'customer_id,customer_name',///////表格标题
								 '100px','customer_id,customer_address,delivery_note,contact,phone',//要隐藏的字段
								 'yes'  ); ////是否不要标题行
							  
						   }
	                     );	
		 $(".get_product").click(function(){	
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							   cjTable_light4('selection','product_list?s=0&dates='+Math.floor(Math.random()*999+1),////url of data source
								 'material_id,material_name',///////表格标题
								 '100px','customer_id,customer_address,delivery_note,contact,phone',//要隐藏的字段
								 'yes'  ); ////是否不要标题行
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
						  
						   if(erro==''){
						
						   search_string=search_string+'&customer_id='+$("#search_customer_id").val();
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
		$("#search_customer_name").keyup(function(){
	                       if ($(this).val().length<1) {$("#selection").remove();$("#search_customer_id").val('0');}  
	                       if($(this).val().length>1)
							  {
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light5('selection','customer_list?s=1&customer_name='+$("#search_customer_name").val()+'&dates='+Math.floor(Math.random()*999+1),
							 'production_id,material_name1',////表格标题
							 '500px','amaterial_id,material_specification,meausrement','yes','#search_customer_name,1||#search_customer_id,0');
						    $("#selection").show();
							   }
							}
						   	
	                     );
		 $("#search_customer_name").focus(function(){
	                       if ($(this).val()=='输入名称') $(this).val('');
						    });		
		 $("#search_customer_name").focus(function(){
	                      // if ($(this).val().length<1) {$("#selection").remove();$("#search_customer_id").val('0');}  
	                       if ($(this).val()=='选择客户') $(this).val('');
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						    cjTable_light5('selection','customer_list?',
							 'customer_id,customer_name1',////表格标题
							 '500px','amaterial_id,material_specification,meausrement','yes','#search_customer_name,1||#search_customer_id,0');
						    $("#selection").show();
							   
							}
						   	
	                     );					
		 	
	                      
						   	
			$("#search_customer_name").keyup(function(){
	                       if ($(this).val().length<1) {$("#selection").remove();$("#search_customer_id").val('0');}  	
							                               });
	   })  
	               
</script>


<body>
<div id=container>
<div id=head></div>
<div id=main><h2>客户</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		<button id=button_add>新增</button> <button id=button_update>更新</button> <button id=button_remove>删除</button>
      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>  
　　　　<!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
		<input type=text title="输入客户名称的第一个字符，即可搜索" id=search_customer_name name=customer_name autocomplete=off size=6 value="选择客户"/>
		<input type=hidden id=search_customer_id name=customer_id />
		<input type=button id=search value="搜索"/>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="hidden" id="page" name="page" value=0  >
        <button id=previous_page>前页</button><button id=next_page>后页</button>  
		</form> 
       </div>
　    <!--查询套件结束 -><-->
      <!--用于更新的pop up-->
		<div id="update_hidden" class="pop_up">
		              <div class="div_title">修改<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_update>
						<table class=table_update>
						<tr height=35><td>
						序号</td><td> <input type=text id=customer_id name=customer_id readonly /></td></tr><tr height=35><td>
						客户名称</td><td width=250> <input type=text id=update_customer_name  name=customer_name  /></td></tr><tr height=35><td>
						地址</td><td><input type=text id=update_customer_address class=pick_material_name name=customer_address    /></td></tr><tr height=35><td>
						联系人</td><td> <input type=text id=update_customer_contact name=contact /></td></tr><tr height=35><td>
						联系方式</td><td><input type=text id=update_customer_phone name=phone  /></td></tr><tr height=35><td>
						交货要点</td><td><input type=text id=update_delivery_note name=delivery_note   /></td></tr><tr height=35><td>
						</td>
						</tr>
						<tr height=35><td colspan=3><input type=button value="更新" id=form_button_update></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于更新的pop up--结束 -->
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">新客户<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update><tr height=35><td>
						</td><td></td></tr><tr height=35><td>
						客户名称</td><td width=250> <input type=text id=add_customer_name class=pick_customer_name name=customer_name  /></td></tr><tr height=35><td>
						地址</td><td><input type=text id=add_customer_address class=pick_material_name name=customer_address    /></td></tr><tr height=35><td>
						联系人</td><td> <input type=text id=customer_contact name=contact /></td></tr><tr height=35><td>
						联系方式</td><td><input type=text id=customer_phone name=phone  /></td></tr><tr height=35><td>
						交货要点</td><td><input type=text id=delivery_note name=delivery_note   /></td></tr><tr height=35><td>
						</td>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value=" 确定 " /></td></tr>
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
/*  customer.php文件的结尾 */
/*  在系统中的位置: ./application/views/customer */
?>
</html>
