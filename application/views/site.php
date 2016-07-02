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
 * site.php 现场物料的视图文件
 *
 * @category	welcome
 * @源代码
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ERP系统</title>
</head>
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/Rightgrid.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
    var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
   
	var passing;
   
	var clickedId=null;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
		function button_operation(){
		                            if(expanded!=1){
													$("#table1").children().find('tr').eq(0).append("<th style='display:none;'></th><th style='display:none;'></th><th style='display:none;'></th><th style='display:none;'></th><th >-</th>");
													$("#table_title").children().find('tr').eq(0).append("<th style='display:none;'></th><th style='display:none;'></th><th style='display:none;'></th><th style='display:none;'></th><th >-</th>");
                                                    expanded=1;
												    }
						            $(".order").each( function()
									                  {$(this).parent().parent().css("display","block"); } 
													  );
						  
		                   }
						   
      	function build_grid_tr_add(in_id){window.location.reload();}
                            
 	

    $(function(){
	
       var clickedId=null;
	   ///////////////////////////validate update///
	  
	   //////////////////////
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','site_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '序号,物料代码,物料名称,规格,状态,数量 ',////表格标题
							 '400px',''   ); ///表格高度,需要隐藏的td 
                            }
	   function build_search_grid(search_string){cjTable( '#grid','site_list?s=2'+string,////url and search string 
							 '序号,物料代码,物料名称,规格,状态,数量 ',////表格标题
							 '400px',''   );                                    ///高度,,需要隐藏的td列
                            }					
	   build_grid();
      ////////////弹出盘盈表单
       $("#new").click(
	                      function (){
									  $(':input','#add_hidden')  ///清空所有input
										 .not(':button, :submit, :reset')  
										 .val('')  
										 .removeAttr('checked')  
										 .removeAttr('selected'); 
						             $("#add_hidden").show();
									 resize("#add_hidden");$("#error").hide();
									
	                                }
	                           );
      ////////////add new handling
	  ///////////新增						   
	  $("#form_button_add").click(
	                      function (){
						            //alert($("#material_type").val());
						             var data=$("#form_add").serialize();
						             var erro='';
									 erro=verify("#form_add",'qty,数量,digital||material_name,产品名称,required||material_id,产品名称,required');
								  	 
									 if(erro=='')
									 {
											var add_url='site_inventory_gain_add?'+data;///新增操作指向的页面
											adds_2(add_url);
											 
									 }else{
									       note(erro);
									       }					 
	                                });	
	   $("#update_material_name").keyup(function(){//供选列表
	                       if ($(this).val().length<1) {$("#selection").remove();$("#update_material_id").val('0');}  
	                       if($(this).val().length>1)
							  {
								   $("#selection").remove();
								   $(this).parent().append("<div id=selection class=comboboxnote></div>");
								   cjTable_light5('selection','material_list?s=1&material_name='+$(this).val()+'&dates='+Math.floor(Math.random()*999+1),
									 'production_id,material_name1',////表格标题
									 '500px','amaterial_id,material_specification,meausrement','yes','#update_material_name,1||#update_material_id,0');
									$("#selection").show();
							   }
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
						   search_string=search_string+'&start_date='+$("#search_start_date").val();
						   search_string=search_string+'&end_date='+$("#search_end_date").val();
						   string=search_string;
						   build_search_grid();
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
		$("#search_material_name").keyup(function(){
	                       if ($(this).val().length<1) {$("#selection").remove();$("#search_material_id").val('0');}  
	                       if($(this).val().length>1)
							  {
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light5('selection','product_list?s=1&material_name='+$("#search_material_name").val()+'&dates='+Math.floor(Math.random()*999+1),
							 'production_id,material_name1',////表格标题
							 '500px','amaterial_id,material_specification,meausrement','yes','#search_material_name,1||#search_material_id,0');
						    $("#selection").show();
							   }
							}
						   	
	                     );
		 $("#search_material_name").focus(function(){
	                       if ($(this).val()=='输入品名') $(this).val('');
						    });				 
		 
	   })  
	   
	   
	               
</script>


<body>
<div id=container>
<div id=head></div>
<div id=main><h2>生产现场物料</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		 <input type="button" id="new"  value="盘点输入"/>
		　<!--查询套件 -><-->
	   <div class=button_right><form id=searchs action="">
	   <input type=hidden id=search_start_date name=start_date size=6 value=""/>
	    <input type=hidden id=search_end_date name=end_date size=6 value="<?php date_default_timezone_set('Asia/Shanghai'); echo date('Y-m-d',time());?>"/>
		<input type=text id=search_material_name name=material_name autocomplete=off size=6 value="输入品名"/>
		<input type=hidden id=search_material_id name=material_id />
		<input type=button id=search value="搜索"/>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="hidden" id="page" name="page" value=0  >
        <button id=previous_page>前页</button><button id=next_page>后页</button>  
		</form> 
       </div>
　    <!--查询套件结束 -><-->
		 

			  <!--用于盘盈输入的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">盘盈盘亏输入<div class=title_close>关闭</div></div>
			      <div class="table_margin">
				        
				        <form action="" id=form_add>
						<table class=table_update>
						<tr  height=35>
						<td>物料名称</td><td><input type=text id=update_material_name name=material_name autocomplete=off /><input type=hidden id=update_material_id name=material_id /></td></tr>
						
						<tr height=35>
						<td>盘盈数量</td><td><input type=text id=qty name=qty  />（盘亏请填负数）</td></tr>
						<tr height=35>
						<td>产品质量</td><td><select id=material_type name=material_type  />
						                    <option value=G>良品</option> <option value=G>待定品</option> <option value=D>次品（报废品）</option>
											</select>
						</td></tr>
						<tr height=35>
						<td>备注</td><td><input type=text id=remark name=remark /></td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="确认" /></td></tr>
						</table>
						</form>
				  </div>
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
</body>
</html>
