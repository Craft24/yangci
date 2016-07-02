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
 * documents.php 文档中心的主视图 
 * @category oa
 * @源代码
 */
?>
<?php error_reporting(null);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>文档中心</title>
<style type="text/css">
<!--
.title_close{ width:50px;
        float:right;
}
#dfm_list {
    position:absolute;
	background-color:#BDC6D9;
    width:300px;
	overflow:scroll;
    z-index:100;     //如果被东西遮挡 就在把这个值设置高
    left: 51px;
    top: 60px;
	display:none;
	padding:20px;
	border:#000099 thin solid;
}

#show_existing{margin:20px; border:thin #003399 groove;display:none;}
-->
</style>
</head>
<script language="javascript">
function current(){ 
var d=new Date(),str=''; 
str +=d.getFullYear()+'-'; //获取当前年份 
str +=d.getMonth()+1+'-'; //获取当前月份（0——11） 
str +=d.getDate()+''; 
return str; } 
function UnixToDate (unixTime, isFull, timeZone) {
                if (typeof (timeZone) == 'number')
                {
                    unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
                }
                var time = new Date(parseInt(unixTime) * 1000);
                var ymdhis = "";
                ymdhis += time.getUTCFullYear() + "-";
                ymdhis += (time.getUTCMonth()+1) + "-";
                ymdhis += time.getUTCDate();
                if (isFull === true)
                {
                    ymdhis += " " + time.getUTCHours() + ":";
                    ymdhis += time.getUTCMinutes() + ":";
                    ymdhis += time.getUTCSeconds();
                }
                return ymdhis;
            }
</script>
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/Rightgrid.js" type="text/javascript"></script>
<script src="../../cjquery/src/docgrid.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<?php $th= explode("|",$jason);
      foreach($th as $v)
	  {
	        $td.="<td>".$v."</td>";
	  
	  }
	  $td='<tr id=first_line class=title_th>'.$td.'</tr>';
echo "<script> var th_heading='".$td."';</script>";
?>
<script>
	var passing;var comboBox;
	var inner; var mousemove=1;
	var die_data; var heading_array=new Array;var heading;
	var erro;
	var search_string;var page_string;var string;
	var clickedId=null; 
	var base_url='<?php echo base_url();?>';
    
	
	
	   function build_grid(){//$("#table_title").empty();
	                         //$("#table1").empty();
							 cjTable_doc( '#grid','documents_list?s=0&filter=30&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '400px','document_id',''   );///影藏document_id
                            }
       function build_grid_tr(in_id){ 
							 cjTable_doc( '#grid','documents_list?in_id='+$(clickedId).find('td').eq(2).find('div').eq(0).text()+'&s=0&filter=30&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '400px','document_id',$(clickedId).find('td').eq(2).find('div').eq(0).text()
							    );///影藏document_id　//如果是修改一条
                            }
       function build_grid_new(){ 
							 cjTable_doc( '#grid','documents_list?in_id=new&s=0&filter=30&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '400px','document_id','new'   );///影藏document_id,如果是新的
                            }
	   function build_search_grid(){
	                         var head='<tr class=title_th>'+$("#table_title").find('tr').eq(0).html()+'</tr>';
							 
	                         $("#table1").empty();$("#table1").append(head);
							 
							 $("#table_title").empty();$("#table_title").append(head);
							 cjTable_doc( '#grid','documents_list?s=0'+string+'&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '400px','document_id',''   );///影藏document_id
							 
                            }						
	   function remove_file(filefield,sub)//移除某个文件
							 {               var data="filefield="+$.trim(filefield)+'&sub='+sub+'&document_id='+$(clickedId).find('td').eq(2).find('div').eq(0).text();
											 $.post('remove_files?',data,function(){
																				
																					 
																			});  $("#dfm_hidden").hide(); 
																					  resize("#tipok");
																					  $("#tipok").show().fadeOut(900) ;
								 
							 } 					
		$(function(){
		build_grid(0);////制作内容
		$("#head").makemenu2(base_url);
	    $("#table_title").append(th_heading); //插入表头 
	    $("#table1").append(th_heading);
	    
		  
	  					
	   
        $(document).on('click', '.sender', function(e) { /////////dfm历史
	                                 
	                                  var sender_id =$(this).text();
									  $.getJSON("get_sender?sender_id="+sender_id+'&dates='+Math.floor(Math.random()*9999+1),
									          function(result){ 
									                           $.each(result, function(k, v)
															      {
															       $.each(v,function(kk, vv) {
									                                                          if(kk=='author') note('上传者:'+vv);  
																							 });		  
									                               } );
																}); 
														});	
         $("#add").click(function(){ /////////新增一条
									$("#add_hidden").show();
									resize("#add_hidden");
								    $(".not_show").remove(); 
									$(':input','#add_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected');
									$("#error").hide();resize("#add_hidden");
	                                });  
          
		 $("#config").click(function(){ //////设置标题,新增栏目工具显示
									$("#add_hidden").hide();
									$("#dfm_hidden").hide();
									$("#config_hidden").show();
									resize("#config_hidden");
									$(':input','#config_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected');
									$("#error").hide();
									$("#config_table").empty();
								    $.getJSON('docu_heading',function(result){ 
									                           $.each(result, function(k, v)
															      {
															        var sub;
																   $.each(v,function(kk, vv) {
																     if(kk=='field_name' )      sub=vv;
									                                 if(kk=='field_comment' )   $("#config_table").append('<tr height=35><td></td><td><input type=hidden  name=field_name[] value="'+sub+'"><input type=text name=field_comment[] value="'+vv+'"   /></td></tr>');
						                  
																							 });		  
									                               } );
																 $("#config_table").append('<tr height=35><td width="74" ><input type=hidden name=field_name[] /></td><td width="334"><input type=text name=field_comment[] /></td></tr><tr height=35 id="config_bottom"><td><div align="right"></div></td><td><input class=button type=button id=forms_button_config value="确认" /><input type=button id=add_comment value=+ >确认后请刷新页面</td></tr> ');
																 resize("#config_hidden");
																}); 
	                                });
         $(document).on('click', '#forms_button_config', function()//////设置标题,新增栏目
		                            {
									$("#add_hidden").hide();
									$("#dfm_hidden").hide();
									$("#config_hidden").hide();
									var data=$("#form_config").serialize();
								    $.get('docu_heading_update',data,function(result){ 
									                                         
																			  if (result=="yes"){
																			                    $('.pop_up').hide();location.reload();
																				                note("OK");
																								 
																				                 
																								}
																             }); 
	                                });
          /////////点击加号,跳出
	     $(document).on('click', '.plus', function(e) {  
		                            ////制作标题                         
								    $("#dfm_hidden").show();
									$("#show_existing").html('');
								    resize("#dfm_hidden");
									var col=this.parentNode.parentNode.cellIndex;
									var title=$("#table_title").find('tbody').eq(0).find('tr').eq(0).find('td').eq(col-1).text();
									$(".title_word").html(title);
									var document_id=$(this).parent().parent().parent().find('td').eq(2).text();
									$("#document_id").val(document_id);
									$("#field").val(title);resize("#dfm_hidden");
									$.get('get_existing',
												  {"document_id":$.trim(document_id),"field":title,"date":Math.floor(Math.random()*9999+1)},
												  function(result){$("#show_existing").html('');$("#show_existing").html(result);
												                   if ($("#show_existing").html().length>0){$("#show_existing").show();}else{$("#show_existing").hide();}//
												   } 
												 
									       );      
									$("#userfile").val('');
									$(".upload_info").html('');
									$("#error").hide();
									
	                                });
         /////////点击加号,跳出新增一个文件类型
	     $(document).on('click', '#add_comment', function(e) {  
		                            ////制作标题                         
								    //$("#config_table").append('<tr height=35><td></td><td><input type=text  name=field_name[] value=""><input type=text name=field_comment[] value="" /></td></tr>');
	                                 $("#config_bottom").before('<tr height=35><td width="74" ><input type=hidden name=field_name[] /></td><td width="334"><input type=text name=field_comment[] /></td></tr> ');
									});
								
        $(".material_name").keyup(function(){
		                      if($(this).val().length>0)
							  {
							   $("#selection").empty();
							   $(this).parent().append("<div id=selection class=comboboxnote><div class='tip'>双击可选择<div class='title_close'>X</div></div></div>");
							   $("#selection").show();
							   cjTable_light5('selection','../welcome/product_list?type=f&s=1&material_name='+$(this).val()+'&dates='+Math.floor(Math.random()*999+1),
							   '',////表格标题
							   '500px','Amaterial_id,material_specification,meausrement','yes','.material_name,1||.material_id,0');
						         }
							     if ($(this).val().length==0) {$("#selection").remove();
								                               $(".material_id").val('');
															   $(".material_name").val('');}
							  });
		    //////////////选择物料
	       $(".product_name").keyup(function(){
		                      if($(this).val().length>1)
							  {
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							    cjTable_light5('selection','../welcome/product_list?final_product=all&material_name='+$(this).val()+'&dates='+Math.floor(Math.random()*999+1),////url of data source
								 '序号,品名,规格,规格2,类型, , ',///////表格标题
								 '300px','material_2name,measurement,safe,moq,material_category,removed,material_barcode,remark,process',//要隐藏的字段
								 'yes','.product_name,1||.material_name2,2||.material_id,0||.material_specification,3||.material_specification2,4||.measurement,5||.final,7'  ); ////是否不要标题行
							  }
							  if ($(this).val().length==0) $("#selection").remove();
						   });							  
      $("#next_page").click(function(){
	                       if ( $("#table1").children('tbody').children('tr').length > 1 ) {
	                       var p=parseInt($("#page").val())+1;
						   $("#page").val(p);
						   page_string='&page='+p;
						   string='&material_id='+$("#material_id").val()+page_string;
						   build_search_grid();
						   	}			 				 
                                      });
	  $("#previous_page").click(function(){
	                      if(parseInt($("#page").val())>0){
						   var p=parseInt($("#page").val())-1;
						   $("#page").val(p);
						  
						   string='&material_id='+$("#material_id").val()+'&page='+p;
						   build_search_grid();
						   				 		}		 
                                      });			  
	 $("#check_by_die").click(function(){
	                                       //if($("#material_name").val()=='' || $("#material_id").val()=='' ) return false;
	                                       if($("#material_id").val()!='' && $("#material_name").val()!='') 
										   {string='&material_id='+$("#material_id").val();
										   build_search_grid();$("#page").val(0);}
	                                       });		 									   
//////////////////上传部分
            var start;
            $('#forms_button_add').click(function() {
                $('#form_submit').submit();
                $('.upload_info').html('开始上传...');
                start = setTimeout(us_add, 1000);//设置一个计时器，隔一段时间请求上传状态
            });
            $('#forms_button_dfm').click(function() {
                $("#in_id_dfm").val($(clickedId).find('td').eq(0).text());
			    $('#form_dfm').submit();
                $('.upload_info').html('开始上传...');
                start = setTimeout(us, 5000);//设置一个计时器，隔一段时间请求上传状态
            });			
		    
            function us() {
                var url = '../upload/upload_state';
                $.post(url, function(data) {
                    if (data=='1') {
                        $('.upload_info').html('上传成功');
                        clearTimeout(start);//获取状态后清除计时器
						$("#add_hidden").hide();
						$("#dfm_hidden").hide();
						$("#dfm_quotation").hide();
						$(".userfile").val('');
						build_grid_tr(0);
                    } else {
                        $('.upload_info').html('上传失败');
						$(".userfile").val('');
						clearTimeout(start);
                    }
                });}
             function us_add() {
                var url = '../upload/upload_state';
                $.post(url, function(data) {
                    if (data==1) {
                        $('.upload_info').html('上传成功');
                        clearTimeout(start);//获取状态后清除计时器
						$("#add_hidden").hide();
						$("#dfm_hidden").hide();
						$(".userfile").val('');
						//build_grid();
						build_grid_new(0);
                    } else {
                        $('.upload_info').html('上传失败');
						$(".userfile").val('');
						clearTimeout(start);
                    }
                });}           
	   }) ; 
</script>

<body>
<div id=container>
  
<div id=head>
</div>
<div id=main><h2>文档中心</h2>
   
   <div id=main_left>
        
   
	    <div>产品号：<input type=text name=material_name id=material_name class=product_name autocomplete=off /><input type=hidden name=material_id id=material_id class=material_id autocomplete=off /><input type="hidden" id="page" name="page" value=0  >
		 <button id=check_by_die>查</button> <button id=previous_page>前页</button><button id=next_page>后页</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 
		 <button id=add>新增产品</button><button id=config>配置栏目</button><input type="hidden" id="page" name="page" value=0  >
		 <!--<button id=config>设置</button>-->
		 </div>
		 <div id=grid>
		        <div id=first_title class=cj_div ><div  id=title><table id=table_title class='cj_table'></table></div></div>
				<div id=first class=cj_div >
				   <table id=table1 class='cj_table'></table><div class=heightsure></div>
				</div>
		 </div>

		
	

  
 </div>
	<div id=main_right>
	
	</div>	   


</div>
<iframe  name="iframe_upload" style="border:0;width:0px;height:0px;"></iframe>
      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
	  </div>

    <!--用于添加的pop up--结束 -->
<div id="dfm_hidden" class=pop_up style="background-color:#FFF">
		          <div class="div_title"><span class=title_word></span><div class=title_close>关闭</div></div>
                  <div id="show_existing">
				  </div>			     
				  <div class="table_margin"  >
				  <form id=form_dfm action="../upload/do_upload_dfm"  target="iframe_upload" method="post"  accept-charset="utf-8" enctype="multipart/form-data">
				  <table class=table_update border=0>
						<tr height=35>
						<td><div align="right" class="file_types" >文件：</div></td><td><input id=userfile class=userfile type="file" name="userfile" size="20" />
						<span class="upload_info"></span>
						</td></tr>
						<tr id=manual height=35 >
						<td height="38" ><div align="right">描述：</div></td>
						<td><input type=text name=tag id=update_tag maxlength="10" /></td>
						</tr>
						<tr height=35><td><div align="right"></div></td><td><input class=button type=button id=forms_button_dfm value="确认" />
						<input type="hidden" name="MAX_FILE_SIZE" value="1024000"/>
						<input type="hidden" id="document_id" name="document_id" value="" />
						<input type="hidden" id="field" name="field" value='' />
						<input type="hidden" id=types name="types" value="dfm"  />
						</td></tr> 
				   </table>
				 </form>
				  </div>
		</div>
      <!--用于上传的pop up--结束 -->
       <!--用于配置标题的pop up--开始 -->
<div id="config_hidden" class=pop_up style="background-color:#FFF">
		          <div class="div_title">新栏目<div class=title_close>关闭</div></div>
				  <div class="table_margin"  >
				   <form id=form_config >
				   <table class=table_update id=config_table border=0>
						<tr height=35>
						<td width="74" ><input type=hidden name=field_name[] /></td>
						<td width="334"><input type=text name=field_comment[] />
						</td></tr>
						<tr height=35 id="config_bottom"><td><div align="right"></div></td><td><input class=button type=button id=forms_button_config value="确认" /><button id=add_comment>+</button>
						</td></tr>
						
					</table>
                    </form>				
				  </div>
		</div>
      <!--用于配置标题的pop up--结束 -->
<!--add new-->
<div id="add_hidden" class=pop_up style="background-color:#FFF">
		          <div class="div_title">新增加产品文档<div class=title_close>关闭</div></div>
			      <div class="table_margin" >
				 <form id=form_submit action="../upload/do_upload_sample_drawing"  target="iframe_upload" method="post" target="iframe_upload" accept-charset="utf-8" enctype="multipart/form-data">
				 <table class=table_update border=0>
						<tr  height=35>
						<td width="74" height=""><div align="right">产品名称：</div></td>
						<td width="334"><input title="输入产品名称的第一个字符，即可搜索" type=text name=material_name class=material_name id=add_material_name autocomplete=off /><input type=hidden name=material_id class=material_id id=add_material_id />
						</td></tr>
						<tr id=manual height=35 >
						<td height="38"   ><div align="right">版本号：</div></td>
						<td><input type=text name=version id=add_version /></td>
						</tr>
						<tr id=manual height=35 >
						<td height="38"   ><div align="right">说明：</div></td>
						<td><input type=text name=tag id=add_tag maxlength="10" /></td>
						</tr>
						<tr height=35>
						<td><div align="right" class="file_types" >图纸：</div></td><td><input id=userfile1 class=userfile type="file" name="userfile" size="20" />
						<span class="upload_info"></span>
						</td></tr>
						<tr height=35><td><div align="right"></div></td><td><input class=button type=button id=forms_button_add value="确认" />
						<input type="hidden" name="MAX_FILE_SIZE" value="1024000"/>
						</td></tr>
					</table>
				 </form>
				  </div>
		</div>
<!---add new end-->




	  
<div id=tipok class=tipok>
<img src=../../img/tick.jpg width=80 />
</div>
<div id=tipnote class=tipnote>
<div align=center>
<img src=../../img/note.jpg width=80 />
</div>
<div id=tipnote_word align=center>请刷新页面</div>

</div>
</div>
<div id=dfm_list></div>
</body>
</html>
