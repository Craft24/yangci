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
<title>ERP系统</title>
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
$(function(){
	   $("#tipnote").css("z-index",99);
	   $("#head").makemenu2(base_url);////顶部菜单
	   function build_grid(){cjTable( '#grid','overall_list?s=0&dates='+Math.floor(Math.random()*9999+1),////url of data source
							 '设置,值',////表格标题
							 '300px',''   );                                    ///表格高度,需要隐藏的td
                            }
	   function build_search_grid(search_string){cjTable( '#grid','overall_list?s=2'+string,////url and search string 
							  '设置,值',////标题
							 '300px',''   );                                    ///高度,,需要隐藏的td列
                            }					

	   build_grid();
      ////////////update handling
	  $("#form_button_update").click(
	                      function (){
						             var data=$("#form_update").serialize();
						             var erro='';
									
									 if($("#company_url").val() == "" || /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test($("#company_url").val()))
									  { erro="地址格式不对";
									   }
									 if($("#company_name").val() == "" )
									  { erro="公司名称未填";
									   }  
									 if($("#materialhouse").val() == "" )
									  { erro="仓库名称未填";
									   }    
									 if($("#finishedhouse").val() == "" )
									  { erro="仓库名称未填";
									   }   
									 if (erro==''){
									 var update_url='settings_update';///更新操作指向的页面
									 updates(update_url,data);
									 build_grid(2);
									 }else{ note(erro);}
	                                });
      $("#button_correct").click(function(){//显示更正表单
		                   
							$("#company_name").val($("#table1").find('tbody').eq(0).find('tr').eq(1).find('td').eq(1).text());
							$("#company_url") .val($("#table1").find('tbody').eq(0).find('tr').eq(2).find('td').eq(1).text());	
							$("#materialhouse") .val($("#table1").find('tbody').eq(0).find('tr').eq(3).find('td').eq(1).text());
							$("#finishedhouse") .val($("#table1").find('tbody').eq(0).find('tr').eq(4).find('td').eq(1).text());
							$("#update_hidden").show();
							resize("#update_hidden");
						    });	
		 
	   })  
</script>
<body>
<div id=container>
<div id=head></div>
<div id=main><h2>基础设置</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		   
		<input type=button  id=button_correct value="更新" />
		   <!--用于更新的pop up-->
		<div id="update_hidden" class="pop_up">
		              <div class="div_title">修改设置<div class=title_close>关闭</div></div>
		        	      <div class="table_margin">
				        <form id=form_update>
						<table class=table_update>
						<tr height=35><td>
						公司名称</td><td> <input type=text id=company_name name=company_name  /></td></tr>
						<tr height=35><td>
						公司网址</td><td><input type=text  id=company_url  name=company_url  /></td></tr>
						
						
						<tr height=35><td colspan=3><input class=button type=button id=form_button_update value=更新 /></td></tr>
						</table>
						</form>
				  </div>
		</div>
             <!--用于更新的pop up--结束 -->
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