<?php
/**
 * EachERP
 * EachERP是开源软件,基于PHP 5.1.6 以上版本, CodeIgniter 2.0框架, Jquery1.9
 * @软件包	EachERP
 * @授权		http://EachERP.net/user_guide/license.html
 * @链接		http://EachERP.net
 * @版本	    0.1beta

 * 版权所有(C) 2015 作者:陈国彤
本程序为自由软件；您可依据自由软件基金会所发表的GNU 通用公共授权条款，对本程序再次发布和/ 或修改；无论您依据的是本授权的第三版，或（您可选的）任一日后发行的版本。
本程序是基于使用目的而加以发布，然而不负任何担保责任；亦无对适售性或特定目的适用性所为的默示性担保。详情请参照GNU 通用公共授权。
您应已收到附随于本程序的GNU 通用公共授权的副本；如果没有，请参照<http://www.gnu.org/licenses/>.
 * calendar.php   calendar 的建立,修改和查询页面的主视图文件
 * @category oa
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
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
	var mainurl="";
    var search_string;var page_string;var string;
	var clickedId=null;
	var base_url='<?php echo base_url();?>';
    function build_calendar(){unicalendar( 'grid','calendar_list?s=0&dates='+Math.floor(Math.random(999)*999+1)////url of data source
							     );                                    ///表格高度
                            }
	function build_search_calendar(){
	                                 unicalendar( '#grid','calendar_list?range='+$("#ranges").val() ////url and search string 
							      );                                    ///高度
                            }
	function build_grid_tr(id){  
	                             unicalendar( 'grid','calendar_list?calendar_id='+id+'&dates='+Math.floor(Math.random(999)*999+1)////url of data source
							     );                                    ///表格高度
                            }											
	function build_grid_tr_add(id){  
	                             unicalendar( 'grid','calendar_list?calendar_id=new&dates='+Math.floor(Math.random(999)*999+1)////url of data source
							     );                                    ///表格高度
                            }											

	$(function(){
	   
	   
	   
	   
	   //////////////////////
	   $("#head").makemenu2(base_url);////顶部菜单
	   //$("#main_right").makemenu_side();
	    build_calendar();
	   	
										   
	     $("#form_button_add").click(
	                      function (){
						             ///////验证表单规则用||号区分
									 var erro=verify("#form_add",'message,内容,required||range,范围,required');
									 if (erro==''){
									 var add_url='../oa/calendar_add';///新增操作指向的页面
	                                 adds_2(add_url);
									 
	                                }else{  note(erro);}
									}
	                          );  
         $("#form_button_add").submit(function () {
                                       return false;
                                        });  
	     $("#form_button_update").click(
	                      function (){
						            
											 var erro=verify("#form_update",'range,范围,required');
											 var data=$("#form_update").serialize();
											 if (erro==''){
															 var add_url='../oa/calendar_correct';///
															 updates(add_url,data);
											 }else{  note(erro);}
										 
									 });
	                            
         $("#form_button_update").submit(function () {
                                       return false;
                                        }); 
		 $(".button_add").click(function (){
		                                 $("#add_calendar_date").val($(this).parent().find('div').eq(0).attr('id'));
	                                     $("#add_message").val('');
										 $("#add_range").val('');
										 $("#add_hidden").show();
										 resize("#add_hidden");
						                
								           });
		$(".button_add").mouseover(function (){
		                                $(this).attr("title","添加");
								           });
										    								    
	    $("#add_hidden").mouseleave(function (){
	                                     $(this).hide();
								           }); 
         $("#update_hidden").mouseleave(function (){
	                                     $(this).hide();
								           });										   
		 $(document).on('click','.calendars',function(){
		      $("#update_calendar_id").val($(this).parent().attr('id').replace('c_',''));
              /////获得message内容
			  var message= $(this).parent().contents().filter(function(){
                                                                         return this.nodeType == 3;
                                                                        }).text();

			  $("#update_message").val(message);
              ////获得范围代号
			  $("#update_range").val($(this).attr('id'));
			  
			  $("#update_hidden").show();
		      resize("#update_hidden");
         });	
	    $("#ranges").change(function(){   /////改变可见范围
	                                   if($(this).val()=='a')
									   $("#main").find('h2').eq(0).html("公司日历");
									   if($(this).val()=='d')
									   $("#main").find('h2').eq(0).html("部门日历");
									   if($(this).val()=='m')
									   $("#main").find('h2').eq(0).html("我的日历");
									   
									   $('.calendar').remove();
									   build_search_calendar();
	                                   });
		 
		 							                                           
	            })  
</script>
<body>
<div id=container>
<div id=head>


</div>
<div id=main>
<h2>日历</h2>

    <div id=main_left>
	<select id=ranges name=range>
	<option value='a'>公司</option>
	<option value='d'>部门</option>
	<option value='m'>我</option>
	</select><button><a  href='?time=<?php date_default_timezone_set('Asia/Shanghai'); echo $time-30*60*60*24 ;?>'><<<</a></button><button> <a href='?time=<?php echo $time+30*60*60*24 ;?>'>>>></a></button>
			
	<div id=grid>
<?php 
function weekday($when)
 {
			$i=date('w',$when) ;
			switch ($i)
			{
				case 0: $str = "<span class=holiday>星期日</span>"; break; 
				case 1: $str = "星期一"; break; 
				case 2: $str = "星期二"; break; 
				case 3: $str = "星期三"; break; 
				case 4: $str = "星期四"; break; 
				case 5: $str = "星期五"; break; 
				case 6: $str = "<span class=holiday>星期六</span>"; break;
			}
			return $str;
}
		
		$i=7;
		
           while($i>0)
		   {
			   $add='';
			   if($time-$i*24*60*60>= time()) $add='<span class=button_add>+</span>';///是否允许添加+
			   echo "<div class=out_day> <div id=date_".date('Y-m-d',$time- $i*24*60*60)." class=day></div>".date('Y-m-d',$time- $i*24*60*60).weekday($time- $i*24*60*60)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$add."</div>";
			   $i--;
		   }
		   
		   $i=1;$add='';
		   if($time+$i*24*60*60>= time()) $add='<span class=button_add>+</span>';///是否允许添加+
		   echo "<div class=out_day><div id=date_".date('Y-m-d',$time)." class=today></div>".date('Y-m-d',$time).weekday($time)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$add."</div>";
           while($i<23)
		   {
			   $add='';
			   if($time+$i*24*60*60>= time()) $add='<span class=button_add>+</span>';///是否允许添加+
			   echo "<div class=out_day><div id=date_".date('Y-m-d',$time+ $i*24*60*60)." class=day></div>".date('Y-m-d',$time+ $i*24*60*60).weekday($time+ $i*24*60*60)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$add."</div>";
			   $i++;
		   }  
		?>
		</div>
       
　　　
	  <!--用于更新的pop up-->
		<div id="update_hidden" class="pop_up">
		              <div class="div_title">修改<div class=title_close>关闭</div></div>
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
 
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		           <br />   
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table class=table_update><tr height=35><td>
						内容:</td><td><input type=text id=add_message name=message size=20  /><input type=hidden id=add_calendar_date name=calendar_date value=''  />
						</td></tr>
						<tr height=35><td>
						可见范围:</td><td><select type=text id=add_range  name=range />
						             <option value=>选择</option>
									 <option value=a>全公司</option>
									 <option value=d>本部门</option>
									 <option value=m>我自己</option>
									 </select>
						</td></tr>
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
<?php include "foot.html" ?>

</body>
<?php
/*  calendar.php文件的结尾 */
/*  在系统中的位置: ./application/views/calendar */
?>
</html>
