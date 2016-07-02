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
 * bbs.php 公司看板的主视图文件
 * @category	oa
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
<script src="../../cjquery/ueditor1_4_3-utf8-php/ueditor.config.js" type="text/javascript"></script>
<script src="../../cjquery/ueditor1_4_3-utf8-php/ueditor.all.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<style>
      #input_big{width:900px;}
</style>

<script>
    var search_string;var page_string;var string;var id=0;var ue;
	var clickedId=null;
	var base_url='<?php echo base_url();?>';
    function build_bbs_tr(in_id){unibbs('grid','../oa/bbs_list?bbs_id='+in_id+'&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'updating',in_id);   
	                     ///表格高度,需要隐藏的td
                            }
	function build_bbs_tr_add(){unibbs('grid', '../oa/bbs_list?id=new&s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 'adding');                        ///表格高度,需要隐藏的td
                            }

    function build_bbs(material_id){ if(typeof(material_id) != "undefined")
	                                 unibbs( 'grid','../oa/bbs_list?id='+material_id+'&'+string+'&dates='+Math.floor(Math.random(999)*999+1)////url of data source
							         ,'show' ); 
								   if(typeof(material_id) == "undefined")
	                                 unibbs( 'grid','../oa/bbs_list?'+string+'&dates='+Math.floor(Math.random(999)*999+1)////url of data source
							          ,'show')
								   id=material_id;                                   ///表格高度
                           }
    
    function updates_bbs(bbs_id){
		         $("#add_hidden").show();resize("#add_hidden");
				 $("#add_material_name").hide();
				 $("#bbs_id").val(bbs_id);
				  var url='bbs_list?bbs_id='+bbs_id;
		         $.getJSON(url,
				       function(result){
					                    $.each(result, function(k, v) {
										       $.each(v,function(kk, vv) {
											        if (kk=='message') {
													                    ue.setContent(vv);
																		}
													if (kk=='id')      $("#bbs_id").val(vv);			  
											                             
													if (kk=='amaterial_id')$("#add_material_id").val(vv);
													if (kk=='material_name')$("#add_material_name").val(vv);
																  
											                             });					 
																		 
														   
									       });
					                    });
		          }			

	$(function(){
	   //////////////////////
	   $("#head").makemenu2(base_url);////顶部菜单
	   //$("#main_right").makemenu_side();
	   build_bbs(); 
	   $("#grid").css("padding-top",20); 
	   $(".button_right").css("padding-right",30);                   
	   $("#button_add").click(function(){
	                                   $("#add_hidden").show();
									   resize("#add_hidden");
									   $("#add_material_name").show();
	                                    $("#bbs_id").val('');
										$("#add_material_id").val('');
										$("#add_material_name").val('输入品名');
										ue.setContent('');
									   });
						   
									   
       $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									 var erro='';
									 erro=verify("#form_add",'message,内容,required||material_id,话题,required');
									 
									 if (erro!='') {$("#tipnote_word note").css("z-index",'1999');alert(erro.replace(/<br \/>/gm,','));return false;}
									 var add_url='bbs_list_add';///新增操作指向的页面
									 var data=$("#form_add").serialize();
									 
									 $.post(
											add_url,
											data,
									        function(result){ 
											                              $("#add_hidden").hide();
									                                      if(parseInt(result)>0)
																		   {
									                                        build_bbs_tr_add();
																			resize("#tipok");
																			$("#tipok").show().fadeOut(900);  
									                                        }else{
																			     if(result=="updated")
																		         {
																				   build_bbs_tr($("#bbs_id").val());
																			       resize("#tipok");
																			       $("#tipok").show().fadeOut(900);  
									                                              }else{
																						 resize("#tipnote");
																						 $("#tipnote_word").html(result);
																						 $("#tipnote").show().fadeOut(5000);
																		                }
																		         } 
									                        });
										}); 
    
	   $(".title_close").click(function(){
	                                   $(this).parent().parent().hide();
	                                     });
	   $(".material_name").focus(function(){
	                           if ($(this).val()=='输入品名') $(this).val('');
	                            $("#is_note").removeAttr("checked");
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							   cjTable_light5('selection','../welcome/product_list?bbs=yes&final_product=F&material_name='+$(this).val()+'&dates='+Math.floor(Math.random()*999+1),
							 '简称,全称,规格,规格2',
							 '500px','amaterial_id,measurement,barcode,process,final_product,remark','yes','#search_material_name,1||#search_material_id,0||#add_material_name,1||#add_material_id,0');
							  
							   //$("<tr><td style='display:none'>-1</td><td>通知</td><td></td><td></td><td></td></tr>").appendTo($("#selection_table1").find("tr").eq(0));
		                                     });
	
	   //////////////选择物料
	       $(".material_name").keyup(function(){
		                      $("#is_note").removeAttr("checked");
							  if($(this).val().length>1)
							  {
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							    cjTable_light5('selection','../welcome/material_list?material_name='+$(this).val()+'&dates='+Math.floor(Math.random()*999+1),////url of data source
								 '序号,品名,全名,规格,规格2',///////表格标题
								 '300px','measurement,safe,moq,material_category,removed,material_barcode,remark',//要隐藏的字段
								 'yes','.material_name,1||.material_name2,2||.material_id,0||.material_specification,3||.material_specification2,4||.measurement,5'  ); ////是否不要标题行
							  }
							   //$("<tr><td style='display:none'>-1</td><td>通知</td><td></td><td></td><td></td></tr>").appendTo($("#selection_table1"));
							 
							  if ($(this).val().length==0) $("#selection").remove();
						   });	
	   $("#next_page").click(function(){
		                   
	                       var p=parseInt($("#page").val())+1;
						   $("#page").val(p);
						   page_string='&page='+p;
						   string=search_string+page_string;
						   build_bbs(id);
						 		 				 
                                      });
	   $("#previous_page").click(function(){
	                      if(parseInt($("#page").val())>0){
						   var p=parseInt($("#page").val())-1;
						   $("#page").val(p);
						   string=search_string+'&page='+p;
						   build_bbs(id);
						   				 		}		 
                                      });								  
	    $("#search").click(function(){
						   $("#page").val(0);
						   var erro='';
						   if(erro==''){
						   //search_string=search_string+'&material_id='+$(".material_id").val();
						   //string=search_string;
						   build_bbs($(".material_id").val());
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
		
		 	
		 $("#is_note").click(function(){$("#selection").remove();
		                                $(".material_id").val('-1');
										$(".material_name").val('通知');
		                               });			
		
	    ////调用富媒体编辑器
				    ue = UE.getEditor('message', {
					toolbars: [
							['fullscreen', 'source', 'undo', 'redo'],
							['bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor',  'selectall', 'cleardoc']
						],
					autoHeightEnabled: true,
					autoFloatEnabled: true
				   });
	   })  
	 
</script>


<body >
 
    
  
<div id=container>
<div id=head>


</div>
<div id=main><h2>动态BBS</h2>
    <div id=main_left>
	    <button id=button_add>新增</button>
	    <!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
		<input type=text id=search_material_name name=material_name class=material_name autocomplete=off size=16 placeholder="输入品名的前2位，选取话题"/>
		<input type=hidden id=search_material_id name=material_id class=material_id />
		<input type=button id=search value="搜索"/>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="hidden" id="page" name="page" value=0  >
        <button id=previous_page>前页</button><button id=next_page>后页</button>  
		</form> 
       </div>
　    <!--查询套件结束 -><-->
        <div id=grid>
		
		</div>
        
        <div id=error class=pop_up  >
	    请先用鼠标选中一条，再点击按钮！
	    </div>
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">新帖<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update>
						
						<tr height=35><td>
						内容</td><td><div id=input_big align=left>
                        <textarea cols=50 rows=6 id=message name=message /></textarea>
						  </div></td></tr>
						<tr height=35><td>
						话题(产品)</td><td>
						<input type=text placeholder="输入产品名称的第一个字符，即可搜索" name=material_name class=material_name id=add_material_name size=10 autocomplete=off  />
						<input type=hidden name=material_id  class=material_id id=add_material_id size=10 />
						<input type=hidden name=bbs_id  id=bbs_id size=10 />
						<input type=checkbox id=is_note name=is_note value='yes' />通知
						</td></tr>  
						<tr height=35><td>
						</td><td><input class=button type=button id=form_button_add value="发布" /> <td >
						
						</td></tr>
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
<?php include "foot.html" ?>

</body>
</html>
<?php
/*  BBS.php文件的结尾 */
/*  在系统中的位置: ./application/views/bbs */
?>