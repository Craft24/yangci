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
 * job_order    工单主页面,修改和查询页面的主视图文件

 * @category welcome
 * @源代码
 */
date_default_timezone_set('Asia/Shanghai');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ERP系统</title>
</head>

<script src="<?php echo base_url();?>jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>cjquery/src/Rightgrid.js" type="text/javascript"></script>
<link href="<?php echo base_url();?>cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
    var clickedId=null;
	var list='';
	var tds='';
	var passing;
	var search_string;var page_string;var string;
	var passing;
	var base_url='<?php echo base_url();?>';
	    function report(id){
						   $("#report_hidden").show();resize("#report_hidden");
                          }
	   ///////////////////////////////////////add new  material
		function adds_3(url,data)
			                     {
	                                
									
	                                $.get(url,
									       data,
										   function (result){
											   if (parseInt(result)>0){        
											                                   build_grid_tr_add('new');//new means new insert
																				$('.pop_up').hide();
																				resize("#tipok");
																				$("#tipok").show().fadeOut(900);
																				} 
										                                      else{
																				 resize("#tipnote");
																				 $("#tipnote_word").html(result);
																				$("#tipnote").show().fadeOut(5000);
																				  }            
										                      }
									     );     
	                                }
	    function job_next(id){                   $(".report_div").remove(); $("#l").attr("id","");
												var tdid=$(id).parent().parent().parent();
												var update_content=$("#update_hidden").html();
												var update_content=$(tdid).html();
												var update_content=update_content.replace(/<\/div>/g,'');  
												var update_content=update_content.replace(/<div class="innercell">/g,'');    
												var update_content=update_content.replace(/<\/td>/g,'');
												var update_content=update_content.replace(/<td style="display:none;">/g,'<td>');
												var update_content=update_content.split("<td>");
												$("#form_update input").each(function(index){$(this).val(update_content[index+1]);          });
												
												$("#button_next").val("确定");  
												
												/////////////
												   var name=$("#update_material_names").val(); 
												   
												  $("#update_material_id").val('');
												  
												  $.getJSON( 'get_next_process_n_material?id='+$(tdid).find('td').eq(3).text(),function(result){
																
																$.each(result, function(k, v) {
																	        
																			$.each(v,function(kk, vv) {
																				if(kk=="material_name"){$("#update_material_names").val(vv);}
																				if(kk=="product_id")  {$("#update_material_id").val(vv); }
																				if(kk=="process")      {$("#process").val(vv);} 
																	                
														 						   });
														                   
										   			                         }); 
																			 });
												 						 
																			 
												$("#worker_next").val('');	$("#qty").val('');							 
												//////////////////////////////////
												resize('#updates_hidden');$("#updates_hidden").show();
						   }
	   function job_repeat(id){                 $(".report_div").remove(); $("#l").attr("id","");
												var tdid=$(id).parent().parent().parent();
												var update_content=$("#update_hidden").html();
												var update_content=$(tdid).html();
												var update_content=update_content.replace(/<\/div>/g,'');  
												var update_content=update_content.replace(/<div class="innercell">/g,'');    
												var update_content=update_content.replace(/<\/td>/g,'');
												var update_content=update_content.replace(/<td style="display:none;">/g,'<td>');
												var update_content=update_content.split("<td>");
												$("#form_updates input").each(function(index){$(this).val(update_content[index+1]);  });
												$("#button_updates").val("确定");  
												//////////////////////////////////get value from other input, and set the option selected,then remove the input
												
												/////////////
												$("#operator_repeat").val('');	$("#qty_plan_repeat").val('');$("#process").val($(tdid).find('td').eq(5).find('div').eq(0).text());
												var material_ids=$(tdid).find('td').eq(3).find('div').eq(0).html();
												
												//alert(material_ids);
												$("#update_material_ids").val(material_ids);							 
												//////////////////////////////////
												resize('#update_hidden');$("#update_hidden").show();
						   }
	   passing=function(){
				$('#supplier_id').val($(this).find("td").first().text());
				$('#supplier_name').val($(this).children("td").eq(1).text());
		                 }
	   
	   function build_grid_tr(in_id){cjTable_tr('job_list?job_id='+$(clickedId).find('td').eq(1).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',0);   
	                     ///表格高度,需要隐藏的td
                            }
	   function build_grid_tr_add(in_id){cjTable_tr( 'job_list?job_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',1);                        ///表格高度,需要隐藏的td
                            }
	   function build_grid(){cjTable( '#grid','job_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '生产单号,派工单,目标产品,id,工序,计划数量,已完成良品,报废数,待定数,下达日期,作业员, ,  ,    ,',////表格标题
							 '400px','currency_id,'   );                                    ///表格高度,需要隐藏的td
							 //////////
                            }
  $(function(){
	
       clickedId=1;
	   ///////////////////////////validate update///
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_search_grid(search_string){cjTable( '#grid','job_list?s=2'+string,////url and search string 
							  '生产单号,派工单,目标产品,id,工序,计划数量,已完成良品,报废数,待定数,下达日期,作业员, ,  ,    ,',////表格标题
							 '400px','currency_id,'   );                                    ///高度,,需要隐藏的td列
                            }					
	   build_grid();
	   //////////////////////////////
	   							 $.each($('.report'),function(i,v){
							                                 // alert($(this).parent().parent().parent().find('td').eq(5).find('div').eq(0).text());
															  alert('yes');
															  
															  $(v).attr("disabled","disabled");
							                                  }
							                   );
       $(document).on('click',".report_check",function()
	                             {
							     var data='&job_id='+$(clickedId).find('td').eq(1).text();
							     updates("job_approve?"+data);
		                         });
	  $("#report_submit").click(	                    
			                function(){
							var data=$("#report_form").serialize()+'&job_id='+$(clickedId).find('td').eq(1).text();
						    var erro=verify("#report_form",'made,合格品,digital||defect,报废品,digital||mrb,待定品,digital');
							updates("job_report?"+data);
		                     });
      $(".material_name").keyup(function(){
		                   $("#selection").remove();
	                      $(this).parent().append("<div id=selection class=comboboxnote ></div>");
						   cjTable_light5('selection','product_list?final_product=all&material_name='+$(this).val()+'&dates='+Math.floor(Math.random()*999+1),
							 'material_id,material_name1',////表格标题
							 '500px','amaterial_id,material_specification,meausrement','yes','#update_material_names,1||#update_material_id,0||#add_material_names,1||#add_material_ids,0||#add_final,5||#update_final,5');
							  
						     $("#selection").show();
						   }
	                     );	
	 
	  $("#button_next").click(
	                      function (){
						             var data=$("#form_update").serialize();
						             var erro=verify("#form_update",'add_material_ids,产品,required||qty_plan,数量,digital||worker,作业员代码,required');
									 
									 if (erro==''){
									 var add_url='job_add?';///新增操作指向的页面
	                                 //alert(data);
									 adds_3(add_url,data);
									 
									 }else{ note(erro);}
	                                }
	                           );
      ////////////add new 
	    $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									 var data=$("#form_add").serialize();
									 var erro=verify("#form_add",'material_name,产品,required||qty_plan,数量,digital|add_material_ids,目标ID,digital');
									 if (erro==''){
									 var add_url='job_add';///新增操作指向的页面
	                                 adds_2(add_url);
									
	                                }else{  note(erro);}
									}
	                          );  
		 ////////////job repeat
	    $("#button_updates").click(
	                      function (){
						             ///////验证表单规则用||号区分
									 var data=$("#form_updates").serialize();
									 var erro=verify("#form_updates",'material_name,产品,required||qty_plan,数量,digital|add_material_ids,目标ID,digital');
									 if (erro==''){
									 var add_url='job_add?';///新增操作指向的页面
	                                 adds_3(add_url,data);
									 
	                                  }else{  note(erro);}
									}
	                          );  					  
		$(".get_material").click(function(){
		                   $("#selection").remove();
	                      $(this).parent().append("<div id=selection class=comboboxnote ></div>");
						   cjTable_light('selection','material_list?s=0&dates='+Math.floor(Math.random()*999+1),////url of data source
							 'material_id,material_name',////表格标题
							 '100px'   );
						     $("#selection").show();
						   }
	                     );		 
       	$(".get_production").click(function(){
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote ></div>");
						   cjTable_light('selection','production_list_mini?material_id='+$('#add_material_ids').val()+'&s=0&dates='+Math.floor(Math.random()*999+1),////url of data source
							 'production_id,material_name,数量,完成数,日期',////表格标题
							 '100px'   );
						     $("#selection").show();
						   }
	                     );		 
       	$("#add_material_names").focus(function(){
		                   var name=$("#add_material_name").val(); 
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote ></div>");
						   cjTable_light('selection','get_process_n_target_material?name='+name+'&f=y&dates='+Math.floor(Math.random()*999+1),////url of data source
							 'material_ids,material_names',////表格标题
							 '100px'   );
						     $("#selection").show();
						   }
	                     );	
       //////////////////////////查询套件
			                     
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
						   
						   search_string=search_string+'&unfinished='+$("#unfinished").val();
						   string=search_string;
						   build_search_grid();
						               }else{
									   note(erro);
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
						   search_string=search_string+'&unfinished='+$("#unfinished").val();
						   string=search_string; //alert(string);
						   $.getJSON('production_list?s=1&dates='+Math.floor(Math.random()*9999+1)+'&stat=1&'+string,
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
									   note(erro);
									   }
									   
                                      });		  						 				 
        $("#searchs").submit(function () {
                                       return false;
                                        }); 
		$("#search_start_date").focus(function(){
		                                        
		                                        $(this).val($("#search_end_date").val());
												});								
	   	$("#search_material_name").focus(function(){
	                       if ($(this).val()=='输入品名') $(this).val('');
						    });   
	    $("#search_material_name").keyup(function(){
	                       
	                       if ($(this).val().length<1) {$("#selection").remove();$("#search_material_id").val('0');}  
	                       if($(this).val().length>1)
							  {
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light5('selection','product_list?s=1&material_name='+$("#search_material_name").val()+'&dates='+Math.floor(Math.random()*999+1),
							 'production_id,material_name1',////表格标题
							 '500px','Amaterial_id,material_specification,meausrement,process,final_product,built_date','yes','#search_material_name,1||#search_material_id,0');
						    $("#selection").show();
							   }
							}
	                     );

		 
	   })  
</script>
<body>
<div id=container>
<div id=head></div>
<div id=main><h2>派工单</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		   <input type=button  id=button_add value="新增" />
		  		 
　　　　<!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
	   从<input type=text id=search_start_date name=start_date size=6 value=""/>到
	   <input type=text id=search_end_date name=end_date size=6 value="<?php echo date('Y-m-d',time());?>"/>
		<input type=text id=search_material_name name=material_name autocomplete=off size=6 value="输入品名"/>
		<input type=hidden id=search_material_id name=material_id />
		<select id=unfinished name=unfinished>
		<option value=1 />未完成</option>
		<option value=2 />已完成</option>
		<option value=0 />全部</option>
		</select>
		<input type=button id=search value="搜索"/>  <input type=button id=stat value="汇总"/>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="hidden" id="page" name="page" value=0  >
        <button id=previous_page>前页</button><button id=next_page>后页</button>  
		</form> 
       </div>
　    <!--查询套件结束 -><-->
		<!--用于更新的pop up-->
		<div id="updates_hidden" class="pop_up">
		              <div class="div_title">下工序派工<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_update>
						<table class=table_update>
						<tr height=35><td>
						生产单</td><td> <input type=text id=production_id name=production_id readonly /></td></tr><tr height=35><td>
						</td><td><input type=hidden id=update_job_id name=job_id readonly  /></td></tr><TR><td>
						目标物料</td><td><input type=text id=update_material_names class=material_name name=material_name /></td></tr><tr height=35><td></td></tr>
						<tr height=35><td>
						工序</td><td> <input type=text id=process name=process /></td></tr>
						<tr height=35><td>
						数量</td><td> <input type=text id=qty name=qty_plan /></td></tr><tr height=35><td></td><td></td></tr><tr height=35><td>
						作业员</td><td><input type=text id=worker_next name=worker /></td>
						</tr>
						<tr height=35><td colspan=3><input  type=button id="button_next" value="更新" />
						<input type=hidden id=update_material_id name=add_material_ids class=material_name value='' />
						</td></tr>
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
						</td><td></td></tr><tr height=35><td>
						派工物料:</td><td> <input type=text id=add_material_names name=add_material_names class=material_name /><input type=hidden id=add_material_ids class=material_id name=add_material_ids /></td></tr><tr height=35><td>
						最终产品:</td><td><input type=text id=add_material_name name=material_name   readonly /><input type="button" class="get_production" value=生产单></td></tr><tr height=35><td>
						生产单号:</td><td><input type=text id=add_production_id name=production_id value='' /></td></tr><tr height=35><td>
						生产数量:</td><td> <input type=text id=qty_plan name=qty_plan /></td></tr><tr height=35><td>
						作业人员:</td><td><input type=text id=worker name=worker /></td></tr><tr height=35><td>
						</td>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于添加的pop up--结束 --> 
		<!--用于再派工的pop up-->
		<div id="update_hidden" class="pop_up">
		          <div class="div_title">再派工<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_updates>
						<table class=table_update>
						<tr height=35><td>
						生产单</td><td> <input type=text id=production_id name=production_id readonly /></td></tr><tr height=35><td>
						</td><td><input type=hidden id=update_job_id name=job_id readonly  /></td></tr><TR><td>
						目标产品</td><td><input type=text id=update_material_names name=material_name /><input type=hidden id=update_material_ids name=add_material_ids value='' /></td></tr><tr height=35><td></td></tr>
						<tr height=35><td>
						工序</td><td> <input type=text id=process name=process /></td></tr>
						<tr height=35><td>
						数量</td><td> <input type=text id=qty_plan_repeat name=qty_plan /></td></tr><tr height=35><td></td><td></td></tr><tr height=35><td>
						
						作业员</td><td><input type=text id=operator_repeat name=worker /></tr>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=button_updates value=更新 />
						
						</td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于更新的pop up--结束 -->
	</div>
	
	<div id=main_right>
	</div>
</div>
 <div id="report_hidden" class="pop_up"  >
          <div class="div_title">统计<div class=title_close>关闭</div></div>
		  <DIV class=report_div id=report_div ><table><tr><td width=30></td><td width=200><form  id=report_form>成品数<input type=text name=made size=5><br><br />报废数<input type=text name=defect size=5><br><br />待定数<input type=text name=mrb size=5><br><br /><input type=button id=report_submit value=确认></form><br /><br /></td></tr></DIV>
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
/*  job_order.php文件的结尾 */
/*  在系统中的位置: ./application/views/ */
?>
</html>
