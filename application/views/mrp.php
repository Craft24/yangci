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
 * mrp2.php   物料采购计修改和查询页面的主视图文件
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
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>
    var comboBox="measurement";//combobox元素的名称列表, 多个元素以逗号分隔如 'a,b,c,d,e'
    
	var passing;
	var inner; var clickedId=null;
	var search_string;var page_string;var string;
	var base_url='<?php echo base_url();?>';
					

    $(function(){
	        function build_search_grid(search_string){cjTable( '#grid','mrp_result?dates='+Math.floor(Math.random()*9999+1)+string,
							 '料号,名称,毛需求,已分配已领用,已分配已加工,库存,半成品库存抵消,在途,是否原料,安全库存,应采购数量 ',////表格标题
							 '300px',''   );                                   ///高度,,需要隐藏的td列
                            }	
      
	   ///////////////////////////validate update///
	   
	  $("#head").makemenu2(base_url);////顶部菜单
	  $("#search").click(function(){
						  
						   var erro='';
						  
						   if($("#delivery_date").val().length>0)    erro=verify("#searchs",'delivery_date,截止日期,isdate');
						   
						   if(erro==''){
						   search_string='&material_id='+$("#mrp_material_id").val();
						   search_string=search_string+'&deadline='+$("#delivery_date").val();
						   search_string=search_string+'&qty='+$("#mrp_qty").val();
						   string=search_string;
						   //alert(string);
						   build_search_grid();
						               }else{
									   alert(erro);
									   }
									   
                                      });			 
	  				 				 
        $("#searchs").submit(function () {
                                       //return false;
                                        }); 
		
		$("#mrp_material_name").keyup(function(){
	                       if ($(this).val().length<1) {$("#selection").remove();$("#mrp_material_id").val('0');}  
	                       if($(this).val().length>1)
							  {
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light5('selection','product_list?s=1&material_name='+$("#mrp_material_name").val()+'&dates='+Math.floor(Math.random()*999+1),
							 'production_id,material_name1',////表格标题
							 '500px','amaterial_id,material_specification,meausrement','yes','#mrp_material_name,1||#mrp_material_id,0');
						    $("#selection").show();
							   }
							}
						   	
	                     );
		 $("#mrp_material_name").focus(function(){
	                       if ($(this).val()=='输入品名') $(this).val('');
						    });				 
	   })  
	   
	   
	               
</script>


<body>
<div id=container>
<div id=head>


</div>
<div id=main>
<h2>MRP计算</h2>
计算为一个新的生产单要准备多少原材料
<p><form id=searchs>
  <input  id=mrp_material_name name=material_name autocomplete=off value="输入品名" style="color:#999999" type=text />
  <input type=hidden id=mrp_material_id name=material_id />
  <input  size=4 title="数量" id=mrp_qty name=mrp_qty value=0 />
  <input size=10  id=delivery_date name=delivery_date value='<?php date_default_timezone_set('Asia/Shanghai'); echo date("Y-m-d",time());?>' />
  <input type=button id=search value="开始计算"/>
 </form>
 
        <div id=main_left>
	    
	    <div id=grid>
		
		</div>
		
		1.毛需求=未结束的生产单的物料需求+本次新生产单的物料需求;<br />
		2.未结束生产单中已经领用物料数量;<br />
		3.未结束生产单的已经加工的物料数量;<br />
		4.库存=原材料库存+半成品库存折算的原材料数量;<br />
		进行mrp计算的步骤
		<ol class=" list-paddingleft-2" style="list-style-type: decimal;">
		    <li class=>关闭所有不再执行的生产单
			<li>进入本页面，不输入任何内容，点击“开始计算”
			<li>得到的结果是应该采购的原材料数量（所有现有的未结束的生产单）
			<li>输入品名，数量后，再点击“开始计算”。
			<li>得到的结果是应该采购的原材料总数量（所有现有的未结束的生产单＋你输入的新的生产数量）
		</ol>

      <div id=error class=pop_up  >
	  请先用鼠标选中一条，再点击按钮！
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


<?php include "foot.html" ?>

</body>
</html>
