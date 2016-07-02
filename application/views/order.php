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
 * order.php 订单主页面的视图文件
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
	var search_string;var page_string;var string;
    var error='';
    var clickedId=null;
	var base_url='<?php echo base_url();?>';
    function build_grid_tr(in_id){cjTable_tr('order_list?order_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',0);   
	                     ///表格高度,需要隐藏的td
                            }
	function build_grid_tr_add(in_id){cjTable_tr( 'order_list?order_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',1);                        ///表格高度,需要隐藏的td
                            }
	$(function(){
	   //////////////////////
	   
	   $("#head").makemenu2(base_url);////顶部菜单
	   bound_search_controls("产品");
	   $("#search_supplier").hide();
	   $("#search_warehouse").hide();
	   
	   function build_grid(){cjTable( '#grid','order_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '订单号,客户订单号,订单日期,Id,产品,规格,客户,Id,价格,数量,税率,金额,币种,交期,已交数量,备注,状态,发货通知',////表格标题
							 '300px',''   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','order_list?s=2'+string,////url and search string 
							 '订单号,客户订单号,订单日期,Id,产品,规格,客户,Id,价格,数量,税率,金额,币种,交期,已交数量,备注,状态,发货通知',////表格标题
							 '300px',''   );                                    ///高度,,需要隐藏的td列
                            }					
	   build_grid();
       ////////////update handling
	   $("#form_button_update").click(
	                      function (){
						             var erro=verify("#form_update",'customer_id,客户ID,required||currency,货币,required||qty,数量,digital||tax,税率,digital||delivery_date,交期,isdate||material_id,产品id,digital');
									 var data=$("#form_update").serialize();
									 if (erro==''){
									 var url='order_list_update';///更新操作指向的页面
	                                 updates(url,data);
									 
									 }else{ note(erro);}
									 
	                                });
     	$("#button_add2").click(function()//新加订单
												{
												$("#add_hidden").show();
												////清空form
												$("#add_qty").val('0');
												$("#add_material_name").val('');
												$("#add_material_id").val('');
												$("#add_price").val('');
												resize('#add_hidden');
												}); 							
	  ////////////add new handling
	  $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									  //var data=$("#form_add").serialize();alert(data);
									  var erro="";
									  if($("#qty").val()=='0') var erro="数量不对";
									  erro+=verify("#form_add",'customer_id,客户ID,required||currency,货币,required||qty,数量,digital||tax,税率,digital||delivery_date,交期,isdate||material_id,产品id,digital');
									 
									 if (erro==''){
									 var add_url='order_list_add';///新增操作指向的页面
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
														   
												 
												
												$(clickedId).css('background-color','#bbb');
												//$("#update").val("确定");  
												
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
												
												$("#update_hidden").show();
												$("#form_button_update").val('确定');
												resize('#update_hidden');	$("#error").hide();	
												}	
																
							                    );  							   
	   $("#button_remove").click(
	                      function(){
						  
											          if(clickedId==null)
									                    {
									                       $("#error").show();resize("#error");
														   return false;
														 }  
									 var remove_url='order_list_remove?order_id='+$(clickedId).find('td').eq(0).text();///移除操作指向的页面
	                                 remove(remove_url);
									 build_grid();
									 clickedId=null;
									 $("#error").hide();
	                                }
	                     );       
	   $("#button_notice").click(//发货通知
	                      function(){
						  
											          if(clickedId==null)
									                    {
									                       $("#error").show();resize("#error");
														   return false;
														 }  
									// var re_url='product_out_notice_add?customer_order_id='+$(clickedId).find('td').eq(1).text();///
	                                // $(window.location).attr('href', re_url);
	                                 $(".qty_check").parent().parent().remove();
									$("#notice_check input").each(function(index){            
																			     if($(this).is(':checked'))
																				             {		 
																							
																							 var order_id=$(this).parent().parent().parent().find('td').eq(0).text();
																							 var customer_order_id=$(this).parent().parent().parent().find('td').eq(1).text();
																							 var material_name=$(this).parent().parent().parent().find('td').eq(4).text();
																							 var material_id=$(this).parent().parent().parent().find('td').eq(3).text();
																							 var material_specification=$(this).parent().parent().parent().find('td').eq(5).text();
																							 var app="<tr height=35><td>订单号</td>";
																		app+="<td><input type=text readonly size=5 name=order_id[] value="+order_id+" /></td>";
																		app+="<td>客户订单号</td>";
																		app+="<td><input type=text readonly size=5 name=customer_order_id[] value='"+customer_order_id+"' /></td>";
																		app+="<td>品名</td>";
																		app+="<td><input type=text readonly  size=5 name=material_name[] value='"+material_name+"'/><input type=hidden name=material_id[] value='"+material_id+"' /></td>";
																		app+="<td>规格</td>";
																		app+="<td><input type=text readonly  size=5 name=material_specification[] value='"+material_specification+"' /></td>";
																		app+="<td><b>类型</b></td>";
																		app+='<td><select name="material_type2" class=material_type ><option value="G" selected>良品</option><option value="P">待定品</option><option value="D">不良品</option></select><input type=hidden name=material_type[] value="G" ></td>';
																		app+="<td><b>发货数量</b></td>";
																		app+="<td><input type=text size=5 name=qty[] class=qty_check  value='' /></td></tr>";
																							 $(app).insertBefore($("#notice_submit").parent().parent());
																							  }
																							 
																							 });
									$("#notice_hidden").show();resize("#notice_hidden");
									return false;
									}
	                     );       
	    
		$("body").on("blur",".qty_check", function ()//重新帮顶事件检查库存
		                                     {var qty=jQuery.trim($(this).val());
											  var material_type=$(this).parent().parent().find('td').eq(9).find('select').eq(0).val();
											  if(qty=='') {qty=0;$(this).val(0);return false;} 
											  if(!jQuery.isNumeric(qty) || qty<0) {note("请填写合理的数字");return false;}
		 	 						          $.getJSON('product_inventory_check?qty='+qty+'&material_type='+material_type+'&material_id='+$(this).parent().parent().find('td').eq(5).find('input').eq(1).val(),
													function(result) {
																	 if(parseFloat(result)<parseFloat(qty)){ erro="库存仅有"+result; note(erro);  }
																	 });   
									         }
	                     ); 
      	$("body").on("change",".material_type", function ()//重新帮顶事件 产品类型
		                                     {var m_type=$(this).val();
											  $(this).parent().find('input').eq(0).val(m_type);
											 
									         }
	                     ); 			       
	  	$("#notice_submit").click(function(){
		                                     var data=$("#form_notice").serialize();
		                                     $.post("product_out_notice_add",data,function(result){
																									   if (result=='yes'){        
																									  
																										$('.pop_up').hide();
																										resize("#tipok");
																										$("#tipok").show().fadeOut(900);
																										} 
																									  else{
																										 resize("#tipnote");
																										 $("#tipnote_word").html(result);
																										$("#tipnote").show().fadeOut(5000);
																										  }            
																									 
											                                                  }); 
		                                     return false;
		                                     });
		
		$(".price").focus(function(){
		                              
									  var qty=$(this).parent().parent().parent().find('input[class="qty"]').eq(0).val();
									  var material_id=$(this).parent().parent().parent().find('input[class="material_id"]').eq(0).val();
									  var customer_id=$(this).parent().parent().parent().find('input[class="customer_id"]').eq(0).val();
									  var that=$(this);
									  $.getJSON("../price/product_price_get",'&qty='+qty+'&material_id='+material_id+'&customer_id='+customer_id,
									            function(result){
												                $.each(result, function(k, v) {
														                        
														                        $.each(v,function(kk, vv) { 
																				                            if(kk=="price") $(that).val(vv);
																											if(kk=="tax")   $(that).parent().parent().parent().find('input[class="tax"]').eq(0).val(vv);
																		                                      });
																							   });	   
												                } );
		                             });

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
						   });
	
		 
		
		$(".price").focus(function(){
		                             
		
		                            });				   
		
	  
	   $("#add_delivery_date").focus(function(){
		                                       
		                                        $(this).val(CurrentDateAddDay(30));
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
						   search_string=search_string+'&customer_id='+$("#search_customer_id").val();
						   search_string=search_string+'&customer_order_id='+$("#search_customer_order_id").val();
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
						   search_string=search_string+'&customer_order_id='+$("#search_customer_order_id").val();
						   string=search_string;
						   $.getJSON('order_list?s=1&dates='+Math.floor(Math.random()*9999+1)+'&stat=1&'+string,
						             function(result)
									                { var sum="";
													  $.each(result,function(k,v){
													                              $.each(v,function(kk,vv)
																				          {
																						   if(kk=='amount')sum+="金额:"+vv+'';
																						   if(kk=='total_qty')sum+="数量:"+vv+'<br>';
																						   if(kk=='currency')sum+="元 "+vv;
																						   });
																				 	   
													                             });
													note(sum);
													 });	
						               }else{
									   alert(erro);
									   }
									   
                                      });		  				 				 
        $("#searchs").submit(function () {
                                       return false;
                                        }); 
		
		
	   })  
	               
</script>
<body>
<div id=container>
<div id=head></div>
<div id=main><h2>定单</h2>
    <div id=main_left>
	    <form id=notice_check>
	    <div id=grid>
		
		</div></form>
		<button id=button_add2>新增</button> <button id=button_update>更新</button> <button id=button_remove>删除</button> <button id=button_notice>发货通知</button>
		
      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>  
　　　　<!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
		<input type=text id=search_customer_name name=customer_name class=customer_name autocomplete=off size=6 placeholder="选择客户" />
		<input type=hidden id=search_customer_id name=customer_id class=customer_id />
		<input type=text id=search_customer_order_id name=customer_order_id autocomplete=off size=6 placeholder="输入客户订单号" />
       <?php include "search_control.php"; ?>
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
						序号</td><td> <input type=text id=purchase_id name=order_id readonly /></td></tr><tr height=35><td>
						客户订单号</td><td><input type=text id=customer_order_id name=customer_order_id /></td></tr><tr height=35><td>
						订单日</td><td><input type=text id=order_date name=order_date readonly /></td></tr><tr height=35><td>
						</td></tr><tr height=35><td>
						产品</td><td><input type=hidden id=update_material_id class=material_id name=material_id value='' /><input type=text id=update_material_name class=product_name name=material_name   autocomplete="off" /><button type="button" class=get_product>...</button></td></tr><TR><td>
						规格</td><td> <input type=text id=material_specification name=material_specification readonly /></td></tr><tr height=35><td>
						客户</td><td> <input type=text  id=update_customer_name name=customer_name class=customer_name /> <input type=hidden  id=update_customer_id name=customer_id class=customer_id /><button type="button" class=get_customer>...</button></td></tr><tr height=35><td>
						价格</td><td><input type=text id=price name=price  class=price /></td></tr><tr height=35><td>
						数量</td><td> <input type=text id=qty name=qty class=qty /> </td></tr><tr height=35><td>
						税率</td><td><input type=text id=tax name=tax  /><input type=hidden id=amount name=amount /></td></tr><tr height=35><td>
						币种</td><td><select id=update_currency name=currency><option value=RMB>RMB</option></select>
						<input type=hidden id=update_currency_hidden name=update_currency_hidden size=3 readonly /></td></tr><tr height=35><td>
						交期</td><td><input type=text id=delivery_date name=delivery_date  /><input type=hidden id=delivered name=delivered  /></td></tr><tr height=35><td>
						备注</td><td><input type=text name=remark /><input type=hidden name=state />
						</tr>
						<tr height=35><td colspan=3><input type=button id=form_button_update value='更新' /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于更新的pop up--结束 -->
 
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">新订单<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update><tr height=35><td>
						</td><td></td></tr><tr height=35><td>
						客户名称</td><td width=250> <input type=text placeholder="输入客户名称的第一个字符，即列出相关。。。" id=add_customer_name class=customer_name name=customer_name  autocomplete="off" /></td></tr><tr height=35><td>
						客户订单号</td><td> <input type=text id=add_customer_order_id name=customer_order_id /></td></tr><tr height=35><td>
						产品</td><td><input title="输入产品名称的第一个字符，即列出相关产品" type=text id=add_material_name class=product_name name=material_name autocomplete="off"   /></td></tr><tr height=35><td>
						数量</td><td> <input type=text id=add_qty name=qty class=qty /></td></tr><tr height=35><td>
						价格</td><td><input type=text id=add_price name=price class=price  /></td></tr><tr height=35><td>
						币种</td><td><select id=add_currency name=currency><option value=RMB>RMB</option></select>
						<input type=hidden id=add_currency_hidden name=add_currency_hidden size=3 readonly /></td></tr><tr height=35><td>
						税率</td><td><input type=text id=add_tax name=tax class=tax  /></td></tr><tr height=35><td>
						交期</td><td><input type=text id=add_delivery_date name=delivery_date  /></td></tr><tr height=35><td>
						备注</td><td><input type=text id=add_remark name=remark   />
						<input type=hidden id=add_material_id name=material_id class=material_id value='' />
						<input type=hidden id=add_customer_id name=customer_id class=customer_id value='' />
						</td>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value=" 确定 " /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于添加的pop up--结束 -->
      <!--用于添加的pop up-->
		<div id="notice_hidden" class="pop_up">
		              <div class="div_title">新发货通知<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_notice >
						<table id=table_notice_new class=table_update>
						<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input id="notice_submit" type=submit name="发出"></td></tr>
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
</html><?php
/*  order.php文件的结尾 */
/*  在系统中的位置: ./application/views */
?>
