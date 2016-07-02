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
 * production_save 生产单主视图
 * @category	welcome
 * @源代码
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ERP系统</title>
</head>
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/Rightgrid.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<style>
.f{margin-right:70px;
   padding-top:8px;
   
   text-align:right;border-right:#FF0000 inset 1px;
}
.ff{
   
}
.underline{text-decoration:underline;}
.small_button{FONT-SIZE:6pt;height:17px;width:17px;font-size:6px;padding-top:1px;vertical-align:middle;}
</style>


<script>
    var clickedId=null;
	 var list='';
	 var tds='';
    var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
   
	var passing;
		var search_string;var page_string;var string;
		var base_url='<?php echo base_url();?>';

        function material_outs(material_id) {
										  	$("#material_out_hidden").show();   
											resize("#material_out_hidden");    
											$("#material_selection").append("<div id=selection class=comboboxnote></div>");
		                                    cjTable_light('selection',"material_list_in_production?material_id="+material_id,'名称,id',100);
											$("#selection").show();                   
		                                 }
						

	   passing=function(){
		             			 
				$('#supplier_id').val($(this).find("td").first().text());
				$('#supplier_name').val($(this).children("td").eq(1).text());
				        
		                 }
	  function show_qty_made(production){
	                          
	  
	  
	                           }
	  function progress_detail(production){
	                               $.getJSON('get_name_process?list='+list,function(result)
															                              {     var result=eval(result);  
																						        //alert(tds);        
																						        for(var i=0; i<result.length;i++){ 
																																	tds=tds.replace('['+result[i].material_id+']','<b>'+result[i].process+'&#8594;</b>:['+result[i].material_name+']');
																																	tds=tds.replace('"*",'+result[i].material_id+')' ,    '"'+result[i].material_name+'",'+result[i].material_id+')');
																																	if(result[i].resource==1||result[i].resource==3) tds=tds.replace(','+result[i].material_id+') border=0',','+result[i].material_id+' border=0 width=0 height=0');
																								                                 }
																								 $("#process_info").append(tds);
																								 $("#progress_detail").after("<input onclick=show_qty_made("+production+")  type=button value='进展'>");
																								 $("#progress_detail").remove();
																								 
																                          });
	                              }
	  function show_job_add(production_id,product_name,target_material_id)
	                         {
							resize("#add_job"); 
							$("#add_job").css({"z-index":"3"});
							$("#add_job").show();
	                        $("#add_production_id").val(production_id);
							$("#job_material_id").val(target_material_id);
							$("#job_material_names").val(product_name);
	 						  }	 
	  				   
       function build_grid_tr(in_id){cjTable_tr('production_list?production_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',0);   
	                     ///表格高度,需要隐藏的td
                            }
	   function build_grid_tr_add(in_id){cjTable_tr( 'production_list?production_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',1);                        ///表格高度,需要隐藏的td
                            }
    $(function(){
	   ///////////////////////////validate update///
	  
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','production_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '生产单号,产品,规格,产品ID,数量,已完成数量,单位,下达日期,完成期限,剩余天数,状态,输入人,审核人,审核时间',////表格标题
							 '300px','supplier_id,currency_id,'   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','production_list?s=2'+string,////url and search string 
							  '生产单号,产品,规格,产品ID,数量,已完成数量,单位,下达日期,完成期限,剩余天数,状态,输入人,审核人,审核时间',////标题
							 '300px','supplier_id,currency_id'   );                                    ///高度,,需要隐藏的td列
                            }					

	   build_grid();
      ////////////update handling
	  $("#form_button_update").click(
	                      function (){
						             var data=$("#form_update").serialize();
						             var erro='';
									 var erro=verify("#form_update",'material_id,产品,required||qty,数量,digital||delivery_date,完成日期,isdate');
									 if (erro==''){
									 var update_url='production_update';///更新操作指向的页面
									 updates(update_url,data);
									 }else{ note(erro);}
	                                });
      ////////////add new handling
	  $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									  //var data=$("#form_add").serialize();alert(data);
									  var erro=verify("#form_add",'material_name,产品,required||production_qty,数量,digital||delivery_date,完成日期,isdate');
									 if (erro==''){
									 var add_url='production_add';///新增操作指向的页面
	                                 adds_2(add_url);
									 
	                                }else{  note(erro);}
									}
	                          );
	  ///////////////add new job						    
	  $("#form_button_job_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									  var data=$("#form_job_add").serialize();alert(data);
									  var erro=verify("#form_job_add",'material_name,产品,required||add_material_ids,产品ID,required||qty_plan,数量,digital||worker,作业员,required');
									 if (erro==''){
									 var add_url='job_add';///新增操作指向的页面
	                                 adds_2(add_url,'#form_job_add');
									 //build_grid();
	                                }else{  note(erro);}
									}
	                          );  
	  $("#button_consumption").click(
	                      function(){
						             $("#show").show();	$("#consumption_info").empty();								                       
						            // $("#consumption_info").parent().remove();$("#process_info").parent().empty();
									 //$(clickedId).after("<tr><td style='padding-left:100px' id=consumption_info colspan=11>已领出物料:</td></tr>");
									 var production =($(clickedId).find('td').eq(0).find('div').eq(0).html());
									 var product_id =($(clickedId).find('td').eq(3).find('div').eq(0).html());
									 var made_qty =($(clickedId).find('td').eq(5).find('div').eq(0).html());
									 var qty =($(clickedId).find('td').eq(4).find('div').eq(0).html());
									 cjTable_light3('#consumption_info','x','material_consumption?qty='+qty+'&made_qty='+made_qty+'&production='+production+'&product_id='+product_id+'&dates='+Math.floor(Math.random()*9999+1),
							 '物料ID,物料名称,规格,已领数量,单耗,领料比例 ',
							 '200px','supplier_id,currency_id'   ); 
							          //$(clickedId).next().find("td").eq(0).before('已领出物料:');
									 resize("#show"); 
	                                }
	                     );       
	  $("#button_progress").click(
	                      function(){
						             var production =($(clickedId).find('td').eq(0).find('div').eq(0).html());
									 var product_id =($(clickedId).find('td').eq(3).find('div').eq(0).html());
									 var product_name=($(clickedId).find('td').eq(1).find('div').eq(0).html());
									  $("#show2").show();	$("#process_div").empty();		
									  $("#process_div").append("<div style='padding-left:100px;' id=process_info colspan=11></div>");
									 $.getJSON(
									          'the_process',function(result)
											              {
														   //var result=eval(result);//var td;
														   list=product_id;
														   function bom(id){
																		   var results=result;
																		   var td='<div class=ff>';
																		   for(var i=0;i<results.length;i++){ 
																											if (results[i].p==id) 
																											{//alert(id);
																											td+='<div class=f><div class=underline>['+results[i].c+']x'+results[i].q+"<img src=../../img/job.png onclick=show_job_add("+production+',"*",'+results[i].c+") border=0 title='派工'>_</div>";
																											td+=bom(results[i].c);
																											td+='</div>';
																											list+=','+results[i].c;
																											}
																											} 
																			td+="</div>";
																			return td;
																		  }	
															tds=bom(product_id);
															
															tds=tds.replace(/<div class=f><\/div>/gm,'');
															
															tds='<div class=f><div class=underline>['+product_id+"]<img src=../../img/job.png onclick=show_job_add("+production+',"'+product_name+'",'+product_id+") border=0 title='派工'></div>"+tds;
															//$("#process_info").append('<input type="button" id=progress_detail class="button" onclick="progress_detail('+production+')" value=" 展开 ">');
															progress_detail(10);
									                          });
															
									resize("#show2");						  
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
									  var erro=verify("#search",'order_date,日期,isdate');
									  if (erro==''){
													 var search_string=$('#search').serialize();///搜索操作使用的字
													 build_search_grid('&'+search_string);//alert(search_string);
									               }else{ note(erro);}
	                                }
	                     ); 
		$("#refresh").click(function(){
	                       build_grid();
						   }
	                     ); 				 
		////////////////如果没有select元素,请注释掉下面一行				 
		//loads_select("#measurement_waiting1,#measurement_waiting2",mainurl+'welcome/show_measurement',''); ///对select元素添加option ///有几个select元素加几行 	  			 
		$(".material_name").keyup(function(){
		                   $("#selection").remove();
	                      $(this).parent().append("<div id=selection class=comboboxnote ></div>");
						   cjTable_light5('selection','product_list?final_product=all&material_name='+$(this).val()+'&dates='+Math.floor(Math.random()*999+1),
							 'material_id,material_name1',////表格标题
							 '500px','amaterial_id,material_specification,meausrement','yes','#update_material_name,1||#update_material_id,0||#add_material_name,1||#add_material_id,0||#add_final,5||#update_final,5');
							  
						     $("#selection").show();
						   }
	                     );	
		$("#button_correct").click(function(){//显示更正表单
		                    var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
							var erro='';
							if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') { erro='应该先反审核'; note(erro);return false;}	
							get_tr_content('update_hidden','form_update');$("#form_button_update").val("确认");
						    });		
		$("#button_halt").click(function(){//强制中断生产单//或恢复
		                   var erro='';
									 
									 if(erro=='')
									 {
									  	 var url='production_halt?production_id='+$(clickedId).find('td').eq(0).text();
										 updates(url); 
							  		  }else{
									       note(erro);
									       }					 
						    });		
        ///////////审核						   
	    $("#button_approve").click(
	                      function (){
						            
						             var erro='';
									 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('审核人')").index();///得到审核内容所在的列数
									 if ($(clickedId).find('td').eq(col).find('div').eq(0).text()!='') erro='不能再审核';
									 if(erro=='')
									 {
									  	 var url='production_approve?production_id='+$(clickedId).find('td').eq(0).text();
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
									  	 var url='production_revocation?production_id='+$(clickedId).find('td').eq(0).text();
										 updates(url); 
							  		  }else{
									       note(erro);
									       }					 
	                                }); 
	  $("#delivery_date_add").focus(function(){
		                                       
		                                        $(this).val(CurrentDateAddDay(30));
												});									       
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
<div id=main><h2>生产任务单</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		   <input type=button  id=button_add value="新增" />
		    <input type=button  id=button_correct value="更正" /><input type=button  id=button_halt value="终止" />
			<input type=button  id=button_approve value="审核" />
			<input type=button  id=button_revocation value="反审核" />
		   <input type=button  id=button_consumption value="料耗" />
		   <input type=button  id=button_progress value="工序" />

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
		<div id="update_hidden" class="pop_up">
		              <div class="div_title">修改<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_update>
						<table class=table_update>
						<tr height=35><td>
						序号</td><td> <input type=text id=purchase_id name=production_id readonly /></td></tr><tr height=35><td>
						产品</td><td><input type=text id=update_material_name class=material_name name=material_name readonly  /></td></tr><TR><td>
						规格</td><td> <input type=text id=specification name=specification /></td></tr><tr height=35><td><input type=hidden id=update_material_id name=material_id value='' /></td></tr><tr height=35><td>
						

						数量</td><td> <input type=text id=qty name=qty /><input type=hidden id=qty_arrived name=qty_arrived /></td></tr><tr height=35><td>单位:</td><td><input type=text id=measurement name=measurement readonly /></td></tr><tr height=35><td>
						</td><td><input type=hidden id=order_date name=order_date  /></td></tr><tr height=35><td>
						完成期限</td><td><input type=text id=delivery_date_update name=delivery_date  /></td></tr><tr height=35><td>
						状态</td><td><select name=halted>
						            <option value='' >未结束</option>
									<option value='Y' >终止</option>
						             </select>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_update value=更新 /></td></tr>
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
						
						产品</td><td><input type=text id=add_material_name name=material_name  class=material_name  /><input type="button" class="get_material" value=...></td></tr><tr height=35><td>
						数量</td><td> <input type=text id=qty name=production_qty /></td></tr><tr height=35><td>
						
						交期</td><td><input type=text id=delivery_date_add name=delivery_date /></td></tr><tr height=35><td>
						<input type=hidden id=add_material_id name=material_id value='' />
						</td>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="新增" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于添加的pop up--结束 --> 
 	  <!--用于添加派工单的pop up-->
		<div id="add_job" class="pop_up">
		              <div class="div_title">新增<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_job_add>
						<table class=table_update><tr height=35><td>
						</td><td></td></tr><tr height=35><td>
						目标物料</td><td> <input type=text id=job_material_names name=material_name /></td></tr><tr height=35><td>
						生产数量</td><td> <input type=text id=qty_plan name=qty_plan /></td></tr><tr height=35><td>
						作业人员</td><td><input type=text id=worker name=worker /></td></tr><tr height=35><td>
						<input type=hidden id=add_production_id name=production_id value='' />
						<input type=hidden id=job_material_id name=add_material_ids value='' /></td>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_job_add value="确认" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
	 <!--用于添加的pop up--结束 --> 	
	 <!--用于材料耗用pop up--开始 --> 	
	<div id=show class=pop_up style="padding:0px 4px 4px 4px;" >
        <div class="div_title" >已领料<div class=title_close>关闭</div></div>
        <div id=consumption_info align=center></div>
    </div>	
	<!--用于材料耗用的pop up--结束 --> 	
	 <!--用于材料耗用pop up--开始 --> 	
	<div id=show2 class=pop_up style="padding:0px 4px 24px 4px;" >
        <div class="div_title" >工序<div class=title_close>关闭</div></div>
        <div id=process_div align=center s></div>
    </div>	
	<!--用于材料耗用的pop up--结束 --> 
</div>
	
	<div id=main_right>
	</div>
</div>
<div id=tipok class=tipok>
<img src=../../img/tick.jpg width=80 />
</div>

<div id=tipnote class=tipnote>
    <div align=center><img src=../../img/note.jpg width=80 /></div>
    <div id=tipnote_word align=center></div>
</div>



</div>
</body>
</html>
