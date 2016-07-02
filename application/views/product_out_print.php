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
 * product_out_print.php 产品发货打印的主视图
 * @category	welcome
 * @源代码
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script>
     $(function()
	    {
		 $("#print").click(function(){
									   $(this).hide();
									   window.print();
									   $(this).show();
		                              }); 
		
		
		
		
		});


</script>


<title>送货单</title>

</head>

<body>
<button id=print>打印</button>
<div width=825 align="left" style="margin-left:100px;"><h2><?php echo $this->session->userdata("company_name");?>送货单</h2></div>
<table width="825" height="70" border="1" cellspacing="0">
  <tr>
    <td width="108" height="31"><div align="center">序号</div></td>
    <td width="119"><div align="center">订单号</div></td>
    <td width="110"><div align="center">客户订单号</div></td>
    <td width="341"><div align="center">品名、规格</div></td>
    <td width="113"><div align="center">数量</div></td>
	 <td width="113"><div align="center"></div></td>
  </tr>
<?php 

      $de_json = json_decode($jason,TRUE);
      $count_json = count($de_json);
        for ($i = 0; $i < $count_json; $i++)
           {
                
			 echo '<tr>
			<td height="31">'.$de_json[$i]['in_id']."</td>
			<td>".$de_json[$i]['order_id']."</td>
			<td>".$de_json[$i]['customer_order_id']."</td>
			<td>".$de_json[$i]['material_name'].
			$de_json[$i]['material_specification']."</td>
			<td>".$de_json[$i]['-in_qty']."</td>
			
			<td>&nbsp;</td>
			 </tr>";
	       }
?>
</table>
<p>日期：<?php echo date('Y-m-d',time());?>　　　　　　　　　　　　　　　　　　　　　　　　客户签收：</p>
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
/*  product_out_print.php 文件的结尾 */
/*  在系统中的位置: ./application/views */
?>
