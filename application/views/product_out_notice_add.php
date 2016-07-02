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
 * product_out_notice_add 增加新的发货通知单的视图
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
<style>
.item{display:none;}
</style>
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/Rightgrid.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<?php echo "<script> var result=".json_encode($items).";</script>";
     
?>

<script>
    var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
    
	var passing;
	var inner;
	var base_url='<?php echo base_url();?>';
    $(function(){
	
       var clickedId=null;
	   ///////////////////////////validate update///
	   
	   $("#head").makemenu2(base_url);////顶部菜单
	  ///////////新通知加入数据库						   
	  $("#form_button_add").click(
	                      function (){
						            
						             var data=$("#form_add").serialize();
						             var erro='';
									 erro=verify("#form_add",'out_qty,数量,digital||material_name,产品名称,required');
								  	 if(erro=='')
									 {
											        var add_url='welcome/product_delivery_notice_add?'+data;///新增操作指向的页面
													adds(add_url);
													$("#add_hidden").hide();
													$(':input','#add_hidden')  ///清空所有input
										           .not(':button, :submit, :reset')  
										           .val('')  
										           .removeAttr('checked')  
										           .removeAttr('selected'); 
						                            document.execCommand('Refresh');
													$("#go_on").show();
									                resize("#go_on");
							  		 }else{
									       note(erro);
									       }					 
	                                });
   
	  ////输入部分产品名称	 则弹出列表供选择				  
	  $("#update_material_name").keyup(function(){
	                       if ($(this).val().length<1) $("#selection").remove();  
	                       if($(this).val().length>1)
							  {
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light5('selection','product_list?type=f&s=1&material_name='+$("#update_material_name").val()+'&dates='+Math.floor(Math.random()*999+1),
							 'production_id,material_name1',////表格标题
							 '500px','Amaterial_id,material_specification,meausrement','yes','#update_material_name,1||#update_material_id,0');
						    $("#selection").show();
							   }
							}
	                     );

	  ////如果把订单号输入框删除到全空,则删除下拉列表					 
	  $("#add_order_id").keyup(function(){
	                       if ($(this).val().length<1) $("#selection").remove(); 					 
						                });
						 						 
	  $("#refresh").click(function(){
	                      document.execCommand('Refresh');
						   }
	                     ); 
      $("#add_hidden").show();
      $.each(result, function(k , v)////根据客户订单号,在指定位置显示订单下的物料名称
								  { var td='<tr><td>物料名称</td><td>';
								   $.each(v, function(kk,vv)
									 {
									  if (kk=='order_id') td+='<input type=hidden name="'+kk+'[]" value="'+vv+'" >';
									  if (kk=='material_id') td+='<input type=hidden class=material_id name="'+kk+'[]" value="'+vv+'" >';
									  if (kk=='material_name')	td+='<input type=text readonly name="'+kk+'[]" value="'+vv+'" >&nbsp;&nbsp;&nbsp;&nbsp; 发货数量:<input type=text class=out_qty size=4 name="out_qty[]">&nbsp;&nbsp;&nbsp;&nbsp; 备注:<input type=text name="remark[]"><br>';
																									 
									 });
									 td+='</td></tr>';
									  $("#table_notice").find('tr').eq(0).after(td);
									  
								  });						                         
      $(".out_qty").blur(function(){  
	                                var url="welcome/product_inventory_check?material_id="+$(this).parent().find('input[class=material_id]').val();
									url+="&out_qty="+$(this).parent().find('input[class=out_qty]').val();
	                                $.getJSON(url, function(result) {
									                                 $.each(result,function(k,v){
																	                         alert(k);
																							 alert(v);
																	                         });
																	 
									                                 });
									  
									});	
                                 

	   })  
	   
	   
	               
</script><body>
<div id=container>
<div id=head>


</div>
<div id=main>
  <h2>发货通知</h2><p></p>
    <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		
		<a href="product_out_notice"><input type="button" name="test" value="发货通知一览表"/></a>
		<br /><br />  
		<a href="order"><input  type="button"  id=go_on style="display:none; " name="test" value=" 继 续 "/></a>
        
 	  <!--用于添加的pop up-->
		<div id="add_hidden" class="pop_up">
		          <div class="div_title">发货通知<div class=title_close></div></div>    
			      <div class="table_margin">
				        <form action="" id=form_add>
						<table id=table_notice>
						<tr  height=55>
						<td>客户订单号</td><td><input  type=text id=customer_order_id name=customer_order_id readonly value="<?php echo $customer_order_id;?>"  />                   
						</td></tr>
						
						<tr height=35><td colspan=3><input class=button type=button id=form_button_add value="确认" /></td></tr>
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
<?php
/*  product_out_notice_add文件的结尾 */
/*  在系统中的位置: ./application/views */
?>
</html>
