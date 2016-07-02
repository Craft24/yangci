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
 * overall.php  基础设置,修改和查询页面的主视图文件
 * @category settings
 * @源代码
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>仓库设置</title>
</head>
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/Rightgrid.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
    var clickedId=null;
	var list='';
	var tds='';
	var passing;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
	function build_grid_tr(){cjTable_tr( 'warehouse_list?house_id='+$(clickedId).find('td').eq(0).text()+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',0);                        ///表格高度,需要隐藏的td
                            }
    function build_grid_tr_add(in_id){cjTable_tr( 'warehouse_list?in_id='+in_id+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '',1);                        ///表格高度,需要隐藏的td
                            }
	
$(function(){
	   $("#tipnote").css("z-index",99);
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','warehouse_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,仓库名称,仓库类型',////表格标题
							 '200px',''   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','warehouse_list?s=2'+string,////url and search string 
							  '序号,仓库名称,仓库类型',//
							 '200px',''   );                                    ///高度,,需要隐藏的td列
                            }					

	   build_grid();
      ////////////update handling
	  $("#form_button_update").click(
	                      function (){
						             var data=$("#form_update").serialize();
						             var erro='';
									
									 if($("#update_house_name").val() == "" )
									  { erro="仓库名称未填";
									   }  
									
									 if (erro==''){
									 var update_url='warehouse_list_update?'+data;///更新操作指向的页面
									 updates(update_url);
									 
									 }else{ note(erro);}
	                                });
          ////////////add new handling
	  $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									  var data=$("#form_add").serialize();
									  var erro=verify("#form_add",'house_name,仓库名称,required');
									 if (erro==''){
									 var add_url='warehouse_list_add?'+data;///新增操作指向的页面
	                                     adds_2(add_url);
									 
	                                }else{  note(erro);}
									}
	                          );  
	  $("#button_correct").click(function(){//显示更正表单
	           $("#error").hide();
			  if(clickedId==null)
									 {
									    $("#error").show();resize("#error");
									    return false;
									  }
	          $(':input','#update_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
		     var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('仓库序号')").index();
			 $("#update_house_id").val($(clickedId).find('td').eq(col).find('div').eq(0).text());              
			 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('仓库名称')").index();
			 $("#update_house_name").val($(clickedId).find('td').eq(col).find('div').eq(0).text());
			 var col=$("#table_title").find('tr').eq(0).find('th').filter(":contains('仓库类型')").index();
			 $("#update_house_type").val($(clickedId).find('td').eq(col).find('div').eq(0).text());
			 
			 $("#update_house_id").val($(clickedId).find('td').eq(0).text());
							$("#update_hidden").show();
							resize("#update_hidden");
						    });	
	 ////////////新加 handling
       $("button_add").click(
	                      function (){$("#error").hide();
						             $(':input','#add_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); $(".supplier").hide();
						             $("#add_hidden").show();
									 resize("#add_hidden");
	                                }
	                   　　　　 );	 
	   })  
</script>
<body>
<div id=container>
<div id=head></div>
<div id=main><h2>仓库设置</h2>
    <div id=main_left>
	    <div id=grid>
		
		</div>
		   
		<input type=button  id=button_add value="新增" /><input type=button  id=button_correct value="更新" />
		   <!--用于更新的pop up-->
		<div id="update_hidden" class="pop_up">
		              <div class="div_title">修改仓库<div class=title_close>关闭</div></div>
		        	      <div class="table_margin">
				        <form id=form_update>
						<table class=table_update>
						<tr height=35><td>
						</td><td></td></tr>
						<tr height=35><td>
						仓库名称</td><td><input type=hidden  id=update_house_id name=house_id   /><input type=text placeholder="例如：3号原料库" id=update_house_name name=house_name   /></td>
						</tr>
						<tr height=35><td>
						仓库类型</td><td> <select id=update_house_type name=house_type />
						                 <option value=原材料仓>原材料仓</option>
										 <option value=成品仓库>成品仓库</option>
										 <option value=半成品仓>半成品仓</option>
										 <option value=辅料仓库>辅料仓库</option>
										 <option value=杂物仓库>杂物仓库</option>
										 </select>
						
						</td></tr>
						<tr height=35><td>
						</td>
						</tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_update value=更新 /></td></tr>
						</table>
						</form>
				  </div>
		</div>
	  <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！	  </div>	
             <!--用于更新的pop up--结束 -->
	     <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">新增<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update><tr height=35><td>
						</td><td></td></tr>
						<tr height=35><td>
						仓库名称</td><td><input type=text placeholder="例如：3号原料库" id=add_house_name name=house_name   /></td>
						</tr>
						<tr height=35><td>
						仓库类型</td><td> <select id=housetype name=house_type />
						                <option value=原材料仓>原材料仓</option>
										 <option value=成品仓库>成品仓库</option>
										 <option value=半成品仓>半成品仓</option>
										 <option value=辅料仓库>辅料仓库</option>
										 <option value=杂物仓库>杂物仓库</option>
										 </select>
						
						</td></tr>
						<tr height=35><td>
						</td>
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
    <div align=center><img src=../../img/note.jpg width=80 /></div>
    <div id=tipnote_word align=center></div>
</div>
</div>
</body>
</html>
<?php
/*  overall.php文件的结尾 */
/*  在系统中的位置: ./application/views */
?>