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
 * supplier.php 供应商管理 主视图
 * @category	welcome 
 * @源代码
 */ 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>供应商管理系统</title>
</head>

<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/Rightgrid.js" type="text/javascript"> </script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
   
	var passing;
	var search_string;var page_string;var string;
    var error='';
    var clickedId=null;  
	var base_url='<?php echo base_url();?>';
	function build_grid_tr(in_id)    {
	                                cjTable_tr(     'supplier_list?supplier_id='+$(clickedId).find('td').eq(0).find('div').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							           '',0);                        ///表格高度,需要隐藏的td
                                      }
	function build_grid_tr_add(in_id){cjTable_tr( 'supplier_list?supplier_id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							          '',1);                        ///表格高度,需要隐藏的td
                                      } 
    $(function(){
	
	   ///////////////////////////validate update///
	  
	   //////////////////////
	   
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','supplier_list?s=0&dates='+Math.floor(Math.random()*999+1),////url of data source
							 '序号,供应商简称,全称,地址,联系人,电话,备注',////表格标题
							 '300px',''   );                                    ///表格高度
                            }
	   function build_search_grid(search_string){cjTable( '#grid','supplier_list?s=1&'+string,////url and search string 
							'序号,供应商简称,全称,地址,联系人,电话,备注',////表格标题
							 '300px',''   );                                    ///高度
                            }					
	   build_grid();	
	   			   
      ////////////update handling
	  $("#button_update").click( function()
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
												$("#form_update input").each(function(index){$(this).val(update_content[index+1]); 
												                                             if($("#final_product_hidden").val()=='成品')
																							   { $("#final_product_update option[value='F']").attr("selected",true);
												                                                }else{
																								$("#final_product_update option[value='S']").attr("selected",true);
																								}
																							 });
												//comboBox_getvalue();
												  
												 
												$("#form_button_update").val("确定");  
												$("#update_hidden").show();
												
												resize('#update_hidden');	$("#error").hide();	
												}	
																
							                    );  			
	  $("#form_button_update").click(
	                      function (){
						             var erro=verify("#form_update",'supplier_name,商号,required||address,地址,required||phone,电话,required||contact,联系人,required');
									  if (erro==''){
									 var update_url='supplier_list_update';///更新操作指向的页面
	                                 var data=$("#form_update").serialize();
									 updates(update_url,data);
									
									 }else{ note(erro);}
	                                }
	                           );
      ////////////add new handling
	  $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									
									  var erro=verify("#form_add",'supplier_name,商号,required||address,地址,required||phone,电话,required||contact,联系人,required');
									 
									 if (erro==''){
						          
									 var add_url='supplier_list_add';///新增操作指向的页面
	                                 adds_2(add_url);
									 
	                                }else{  note(erro);}
									}
	                          );  
	  $("#button_remove").click(
	                      function(){
									 var remove_url='supplier_list_remove';///移除操作指向的页面
	                                 remove(remove_url);
									 build_grid();
	                                }
	                     );       
	  $("#keyword").keyup(
	                      function(){
									 var keyword=$("#keyword").val();/////////////////过滤操作
	                                 filter_material(keyword);
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
	  $("#button_search").click(function(){
						   $("#page").val(0);
						   var erro='';
						   if(erro==''){
						 
						   search_string='supplier_name='+$("#search_supplier_name").val();
						   string=search_string;
						   build_search_grid();
						               }else{
									   note(erro);
									   }
									   
                                      });			 	 
	 $("#search_supplier_name").focus(function(){
	                       if ($(this).val()=='输入名称') $(this).val('');
						    });	
	   })  
	   
	   
	               
</script>


<body>
<div id=container>
<div id=head>


</div>

<div id=main><h2>供应商</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		    <button  id=button_add >新增</button> <button  id="button_update"  />更新 </button>
          <input type=text  id=search_supplier_name name=supplier_name autocomplete=off size=6 value="输入名称"/>
		   <button  id=button_search  />搜索</button>
           <input type="hidden" id="page" name="page" value=0  >
           <button id=previous_page>前页</button><button id=next_page>后页</button>  
      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>  
		<!--用于更新的pop up-->
		<div id="update_hidden" class="pop_up">
		              <div class="div_title">修改<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_update>
						<table class=table_update>
						<tr height=35><td>
						序号</td><td><input type=text name=supplier_id readonly size=5 /></td></tr><tr height=35><td>
						简称</td><td> <input type=text name=supplier_name /></td></tr><tr height=35><td>
						全称</td><td> <input type=text name=supplier_full_name /></td></tr><tr height=35><td>
						地址</td><td><input type=text name=address  /></td></tr><tr height=35><td>
						联系人</td><td> <input type=text id=contact name=contact /></td></tr><tr height=35><td>
						电话</td><td><input type=text id=contact name=phone  /></td></tr><tr height=35><td>
						备注</td><td><input type=text name=remark   /></td>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_update value=更新 /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于更新的pop up--结束 -->
 
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		          <div class="div_title">新增供应商<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update><tr height=35><td>
						简称</td><td> <input type=text name=supplier_name  maxlength=50 class="required" /></td></tr><tr height=35><td>
						全称</td><td> <input type=text name=supplier_full_name /></td></tr><tr height=35><td>
						地址</td><td><input type=text name=address   maxlength=50 class="required"/></td></tr><tr height=35><td>
						联系人</td><td> <input type=text id=contact name=contact  maxlength=50 class="required"  /></td></tr><tr height=35><td>
						电话</td><td><input type=text name=phone  maxlength=50  /></td></tr><tr><td>
						备注</td><td><input type=text name=remark maxlength=50   /></td>
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
</html>
