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
 * BOM.php   bom的建立,修改和查询页面的主视图文件
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
<script src="../../cjquery/src/cjqueryWithBOM.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">

<style>
td{vertical-align:top}
.bom_repair{font-size:20px}
.backward{vertical-align:middle;height:15px;}
</style>
<script>
     var clickedId='';
	 var base_url='<?php echo base_url();?>';
     function cancel(){
	 return false;
	 }
	 function bom_submits(element,product_id)
	 { 
	  var url= 'bom_new?'+$("#form_bom").serialize()+'&dates='+Math.floor(Math.random()*9999+1);
	 
	  $.get(url,
	              function(result){
	                          if (result=="yes"){
																				$('.pop_up').hide();
																				resize("#tipok");
																				$("#tipok").show().fadeOut(900);
																				var el=$('#selection_bom').prev().attr('id');
	                                                                            $(".comboboxnote_wide").remove();
																				bom_content(element,product_id);
						                                                      								
												}
										          else{                         resize("#tipnote");
																			    $("#tipnote_word").html(result);
																				$("#tipnote").show().fadeOut(5000);
												      }            
						
	                        });                                 
	 }
	    //////////////////////点击+号添一行
	 function add_one_line(add){								  
									     var newline=$(add).parent().prev().html();
										 $(add).parent().before('<tr height=30>'+newline+'</tr>');
																		
										 }	
	 function abort()
	                  {
					  $(".comboboxnote_wide").remove();
					  }									 
	 
    $(function(){
	
     $("#head").makemenu2(base_url);
    
	  $(document).on('focus','.name',function(){    
	                                  if($(this).val()=='输入品名')
									  
	                                  {$(this).val('');this.style.color='#000';}
									  
									    });
    
     $("#bom_check").click(function(){    $("#bom").parent().html('<button id=bom>+</button>');
	                                      
										  var first_id=$("#search_material_id").val(); 
										  var first_content=$("#search_material_name").val()+"子物料组成";
										  $("#bom").after(first_content+"<span alt='修改子物料组成' id=first_"+first_id+"  class=bom_repair>&#9997</span>");
										  bom_content('bom',$("#search_material_id").val());
	 								    });
	 								
	 $("#selection").css("left",(($(document).width())/2-(parseInt(500)/2))+"px");
	 $(document).on('click',".title_close",function(){
	                                                  $(this).parent().parent().hide();
	                                                 });
	 $(document).on('keyup','.name',function(){                       
	                       if ($(this).val().length<1) {$("#selection").remove();$(this).parent().find('input').eq(1).val('');        }  
	                       if($(this).val().length>1)
							  {
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_BOM('selection','material_list?s=1&material_name='+$(this).val()+'&dates='+Math.floor(Math.random()*999+1),
							 'production_id,material_name1',///////表格标题
							 '500px','Amaterial_id,material_specification,meausrement','yes','#search_material_name,1||#search_material_id,0');
						   $("#selection").show();
							   }}
						); 	
	
	 $(document).on('click','.bom_repair',function(){
	                                    $(this).parent().parent().parent().next().remove();
										$("#selection_bom").remove();
										$(this).parent().find('table').remove();
	                                    $(this).parent().append("<div id=selection_bom class=comboboxnote_wide><div class='div_title'>编辑子物料<div class='title_close'></div></div></div>");
	                                    $("#selection_bom").show();
										 var id=$(this).attr("id").split('_');
										 
										$.getJSON('bom_children?product_id='+id[1]+'&dates='+Math.floor(Math.random()*9999+1),
										                    function(result){
															                 var selection_selection='<form id=form_bom method=post ><input type=hidden name=product value='+id[1]+' ><table><tr><td>子物料品名</td><td>全名</td><td>规格</td><td>规格2</td><td>用量</td><td></td></tr>';
										                                     $.each(result, function(k, v) {    
																			                                selection_selection+="<tr  height=30>";var selection_selection_name;var selection_selection_spec;var selection_selection_qty;
																											var selection_selection_hidden='';
										                                                                    $.each(v, function(kk, vv)
																											    {
																												if(kk=='material_name'||kk=='material_name2'||kk=='material_specification'||kk=='material_specification2'||kk=='qty')
																												{
																												                               if (kk=='material_name') {selection_selection_name="<td><input autocomplete=off  readonly  name="+kk+'[] size=10 value="'+vv+'"></td>'; }
																												                               if (kk=='material_name2') {selection_selection_name2="<td><input autocomplete=off  readonly  name="+kk+'[] size=10 value="'+vv+'"></td>'; }
																																		       if (kk=='material_specification'){selection_selection_spe="<td><input readonly  name="+kk+'[] size=5 value="'+vv+'">';}
																												                               if (kk=='material_specification2'){selection_selection_spe2="<td><input readonly  name="+kk+'[] size=5 value="'+vv+'">';}
																																			   if (kk=='qty'){ selection_selection_qty="<td><input name="+kk+'[] size=5 value="'+vv+'"></td>'; }
																												}else{
																												      if(kk=='bom_id'||kk=='product_id'||kk=='material_id')
																												            selection_selection_hidden+="<input type=hidden name="+kk+"[] value="+vv+">"; 
																												      }
																												
																												
																												});selection_selection+=selection_selection_name+selection_selection_name2+selection_selection_spe+selection_selection_spe2+selection_selection_qty;
										                                                                    selection_selection+='<td>'+selection_selection_hidden+'</td>'+"</tr>";
										                                                                    });
																								
																								selection_selection+='<tr height=30><td><input  class=name autocomplete=off placeholder="输入品名"  name=material_name[] size=10 ></td><td><input  class=name2 readonly  name=material_name2[] size=10 ></td><td><input readonly name=material_specification[] size=5></td><td><input readonly name=material_specification2[] size=5> </td><td><input name=qty[] size=5 ><td><input type=hidden name=material_id[] value=none >  </td></tr><tr ><td onclick="add_one_line(this)">+</td><td></td><td></td><td></td><td></td><td></td></tr>';
	                                                                							selection_selection+='<tr><td></td><td><input class=button2 type=button onClick="bom_submits(this,'+id[1]+');bom_content(this,'+id[1]+');" id=submits value=保存 />&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input class=button2 type=button onclick="abort()" value=退出 /></td><td></td></tr></table>';
																								selection_selection+='<div style="float:right;">Note:若删除子物料,请将用量设为0</div></form>';
																								$("#selection_bom").append(selection_selection);
																	         });
	                                   });
	   }); 
</script>
<body>
<div id=container>
<div id=head>

</div>
<div id=main>
  <div ><h2>BOM表</h2></div>
    <div id=main_left>
	    <table><tr>
                   <td><input title="输入产品名称的第一个字符，即可搜索" id=search_material_name class=name name=material_name autocomplete=off placeholder="输入品名"  style="color:#999999"/></td>
		           <td><input type=hidden  name=product /></td>
				   <td><input type=hidden  name=product2 /></td> <td><input type=hidden  name=product2 /></td> <td><input type=hidden  name=product2 /></td>
				   <td><input type=hidden id=search_material_id name=product_id value='' /><button id=bom_check>查BOM</button></td></tr>
		</table>
		<div id=bom_grid>
		<table>
		 <tr ><td><button id=bom>+</button></td></tr>
		</table>
		</div>
    
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
<div id=selection class=comboboxnote>搜索:<input id=material_search size=8><div class=close>X</div><div id=search_result></div></div>
</body>
<?php
/*  BOM.php文件的结尾 */
/*  在系统中的位置: ./application/views/BOM */
?>
</html>
