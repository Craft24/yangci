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
 * plugins.php   插件管理主视图文件
 * @category plugins
 * @源代码
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>物料管理系统</title>
</head>
<script src="<?php echo base_url();?>jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>cjquery/src/Rightgrid.js" type="text/javascript"></script>
<link href="<?php echo base_url();?>cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
	var mainurl="";
    var search_string;var page_string;var string;
	var clickedId=null;
	var base_url='<?php echo base_url();?>';
    function down(url)//下载指定插件
    {   resize("#downloading");
	    $("#download_ok").hide();$("#download_NG").hide();		
		$("#downloading").show();
		$("#download_progress").show();
		
		
		$.get("./plugins/download?url="+url,function(result){
										                      if (result=='ok')  {$("#download_ok").show()   ;$("#download_progress").hide();} 
															  if (result=='failure')  {$("#download_NG").show()   ;$("#download_progress").hide();} 															  
									                        }
									  );	
		
		
	}	
    function uninstall(plugin_name)//卸载指定插件
    {   
		
		
		$.get("./plugins/uninstall?plugin_name="+plugin_name,function(result){
										                      if (result=='ok')  {$(this).parent().append("已卸载");   } 
															   															  
									                        }
									  );	
		
		
	}	
   function install(plugin_name)//安装指定插件
    {   
		
		
		$.get("./plugins/install?plugin_name="+plugin_name,function(result){
										                      if (result=='ok')  {$(this).parent().append("已安装");   } 
															   															  
									                        }
									  );	
		
		
	}	
	$(function(){
       $(".pop_close").click(function(){$(".pop_up").hide();});
	   $(".ok_close").click(function(){$(".tipok").hide();});
	   //////////////////////
	   $("#head").makemenu2(base_url);////顶部菜单
       
        $("#install_new").click(
	                      function (){
							          $(".pop_up").hide();
						              $("#add_hidden").show();
                                      $.get("./plugins/plugins_avai",function(result){
										                               $("#plugins_avai").html(result);
									                                  }
									  );
									  
									}
	                          );  
         $("#installed").click(
	                      function (){
						              $(".pop_up").hide();$("#installed_hidden").show();
                                      $.get("./plugins/plugins_installed",function(result){
										                               $("#plugins_installed").html(result);
									                                  }
									  );
									  
									}
	                          );  		
		 $("#install").click(///可立即安装的插件列表
	                      function (){
						              $(".pop_up").hide();$("#install_hidden").show();
                                      $.get("./plugins/installing",function(result){
										                               $("#plugins_install").html(result);
									                                  }
									  );
									  
									}
	                          );  
							  ///可立即安装的插件列表
         $(document).on("click",".installing_button",function (){
	                      
						              var plugin_name=$(this).attr('id');
                                      $.get("./plugins/install?plugin_name="+plugin_name,function(result){
										                               $("#plugins_install").html(result);
									                                  }
									  );
									  
									}
	                          );  
         					  ///卸载指定插件
         $(document).on("click",".uninstall_button",function (){
	                      
						              var plugin_name=$(this).attr('id');
									  $("#plugins_installed").html("正在卸载...");
                                      $.get("./plugins/uninstall?plugin_name="+plugin_name,function(result){
										                       //刷新   
															 $.get("./plugins/plugins_installed",function(result){
										                               $("#plugins_installed").html(result);
									                                  });
									    															  
									                        }
									  );}	
									
	                          );    							  
        })  ;
</script>
<body>
<div id=container>
<div id=head>


</div>
<div id=main>
<h2>插件管理</h2>

    <div id=main_left>
	
	<button id=installed>已经安装的插件</button> <button id=install_new>下载新插件</button> <button id=install>安装已下载的插件</button>
	
	
    	
			
	<div id=grid>


	</div>
       
　　　
	  <!--用于更新的pop up-->
		<div id="update_hidden" class="pop_up">
		              <div class="div_title">修改<div class=pop_close>关闭</div></div>
			      <div class="table_margin">
				        <form id=form_update>
						<table class=table_update>
						<tr height=35><td>
						内容:</td><td><input type=text id=update_message name=message size=20  /><input type=hidden id=update_calendar_id name=calendar_id value=''  />
						</td></tr>
						<tr height=35><td>
						范围:</td><td><select type=text id=update_range  name=range />
						             <option value=>选择</option>
									 <option value=a>全公司</option>
									 <option value=d>本部门</option>
									 <option value=m>我自己</option>
									 </select>
						</td></tr>
						<tr height=35><td colspan=3><input class=button type=button id=form_button_update value="修改" /></td></tr>
						</table>
						</form>
				  </div>
		</div>
      <!--用于更新的pop up--结束 -->
 
 	  
	 <!--用于显示可下载插件的pop up-->
		<div id="add_hidden" class="pop_up">
		              <div class="div_title">可下载插件<div class="pop_close">关闭</div></div>
			      <div class="table_margin">
				        <div id=plugins_avai>
						
						</div></br><br>
				  </div>
		</div>
      <!--用于添加的pop up--结束 -->
	 <!--用于显示已安装插件的pop up-->
		<div id="installed_hidden" class="pop_up">
		              <div class="div_title">已安装的插件<div class="pop_close">关闭</div></div>
			      <div class="table_margin">
				        <div id=plugins_installed>
						
						</div></br><br>
				  </div>
		</div>
      <!--用于添加的pop up--结束 -->
	  <!--用于显示可按装插件的pop up-->
		<div id="install_hidden" class="pop_up">
		              <div class="div_title">可安装的插件<div class="pop_close">关闭</div></div>
			      <div class="table_margin">
				        <div id=plugins_install>
						
						</div></br><br>
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
<div id=downloading width=40% class=tipok>
<div class="div_title">下载<div class=ok_close>关闭</div></div>
<div id=download_progress > 正在下载。。。</div>
<div id=download_ok > 下载完成！</div>
<div id=download_NG > 下载失败！</div>
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
<?php
/*  calendar.php文件的结尾 */
/*  在系统中的位置: ./application/views/calendar */
?>
</html>
