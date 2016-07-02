<script>
   function bound_search_controls(material_type)
           //material_type=='成品' or material_type=='原料'
		    
   {      ///选择生产单
           $(".production_id").focus(function(){
	                               //if($("#add_final").val()=='成品')
								   //{
								   if (display=='add') var material_id=$("#add_material_id").val();
								    if (display=='update') var material_id=$("#update_material_id").val();
								   
								   $("#selection").remove();
								   $(this).parent().append("<div id=selection class=comboboxnote></div>");
								   cjTable_light5('selection','../welcome/production_list_unfinished?material_id='+material_id+'&dates='+Math.floor(Math.random()*999+1),
									 '生产单号,品名,规格,规格2,计划数量,完成数量,单位,下达日期,交货日期, ',////表格标题
									 '500px','Amaterial_id','yes','#add_production_id,0||.production_id,0');
									 $("#selection").show();
							      //}
							});        
	                     
   //////////////选择仓库
	      $(".warehouse_name").focus(function(){
							   //$("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   
							   $("#selection").show();
							    var warehouse_type="";
								
							    if(material_type=='成品')warehouse_type='成品仓库';
							    if(material_type=='原料')warehouse_type='原材料仓';
							   cjTable_light5('selection','../settings/warehouse_list?type='+warehouse_type+'&dates='+Math.floor(Math.random()*999+1),
							    '仓库序号,仓库名称,类别,　',////表格标题
							    '500px','','yes','.warehouse_name,1||.warehouse_id,0');
						         });	
		   $(".warehouse_name").blur(function(){
		                     $(".warehouse_id").val('');
	                     });
		   //////////////选择物料
	       $(".material_name").keyup(function(){
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
							  if ($(this).val().length==0) $("#selection").remove();
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
  //////////////选择物料
	         $(".semi_product_name").keyup(function(){
		                      if($(this).val().length>1)
							  {
							   $("#selection").remove();
							   $(this).parent().append("<div id=selection class=comboboxnote></div>");
							   $("#selection").show();
							    cjTable_light5('selection','../welcome/product_list?final_product=S&material_name='+$(this).val()+'&dates='+Math.floor(Math.random()*999+1),////url of data source
								 '序号,品名,规格,规格2,类型, , ',///////表格标题
								 '300px','material_2name,measurement,safe,moq,material_category,removed,material_barcode,remark,process',//要隐藏的字段
								 'yes','.semi_product_name,1||.material_name2,2||.material_id,0||.material_specification,3||.material_specification2,4||.measurement,5||.final,7'  ); ////是否不要标题行
							  }
							  if ($(this).val().length==0) $("#selection").remove();
						   });		
		    $(".material_name").blur(function(){
		                     $(".material_id").val('');$("#material_id").val('');
	                     });
		    $(".product_name").blur(function(){
		                     $(".material_id").val('');$("#material_id").val('');
	                     });
			$(".semi_product_name").blur(function(){
		                     $(".material_id").val('');$("#material_id").val('');
	                     });			 
			$(".supplier").click(function(){
		                    $("#selection").remove();
							$(this).val('');
							$(".supplier_id").val('');
		                    $(this).parent().append("<div id=selection class=comboboxnote></div>");
						    $("#selection").show();
						    cjTable_light5('selection','../welcome/supplier_list?s=0&dates='+Math.floor(Math.random()*999+1),
							 '序号,名称,全名',////表格标题
							 '500px','','yes','#supplier_id,0||#update_supplier_id,0||.supplier,1||.supplier_id,0');
						    $("#selection").show();
							});	
		    $(".customer_name").focus(function(){
	                      
	                       if ($(this).val()=='选择客户') $(this).val('');
		                   $("#selection").remove();
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						    cjTable_light5('selection','../welcome/customer_list?',
							 '序号,客户名称',////表格标题
							 '500px','amaterial_id,material_specification,meausrement','yes','.customer_name,1||.customer_id,0');
						    $("#selection").show();
							   
							}
						   	
	                     );					
		    $(".customer_name").keyup(function(){
	                       if ($(this).val().length<1) {$("#selection").remove();$(".customer_id").val('0');}  	
							                               });
		    $(".supplier").blur(function(){
		                    $(".supplier_id").val(''); 
							});
	        $(".order_id").click(function(){
	                        $("#selection").remove();
	                       if ($(this).val().length<1) $("#selection").remove(); 
		                  
						    var arr=new Array();
							str= $(this).attr('id');
							arr=str.split('_');//注split可以用字符或字符串分割
							
						   if($("#"+arr[0]+"_material_name").val().length>1)
						   {
	                       $(this).parent().append("<div id=selection class=comboboxnote></div>");
						   cjTable_light5('selection','../welcome/order_list?s=1&material_id='+$("#"+arr[0]+"_material_id").val()+'&dates='+Math.floor(Math.random()*999+1),
							 '订单号,客户订单号,订单日,数量,品名, ',////表格标题
							 '500px','meausrement,state,amount,currency,a.remark,tax,delivery_date','','.order_id,0||.customer_order_id,1');
						    $("#selection").show();
							}
							}
	                     ); 
			 $(".supplier_name").click(function(){
		                    $("#selection").remove();
		                    $(this).parent().append("<div id=selection class=comboboxnote></div>");
						    $("#selection").show();
						    cjTable_light5('selection','../welcome/supplier_list?s=0&dates='+Math.floor(Math.random()*999+1),
							 '代号,名称,全名,地址',////表格标题
							 '500px','','yes','#supplier_id,0||#update_supplier_id,0||#supplier,1||#update_supplier,1||.supplier_id,0||.supplier_name,1');
						    $("#selection").show();
							});	
			$(".supplier_name").keyup(function(){
			                if ($(this).val().length<1) $("#selection").remove();$(".supplier_id").val(''); 
			                                });
							
			 $(".order_id").keyup(function(){
	                       if ($(this).val().length<1) $("#selection").remove();$(".customer_order_id").val(''); 
						   					 
						                });	
							 				 
             $("#searchs").submit(function () {
                                       return false;
                                        }); 
			 $("#search_start_date").focus(function(){
		                                        
		                                        $(this).val($("#search_end_date").val().substring(0,8)+'01');
													});	
			 $(".todays").focus(function(){
		                                       
		                                        $(this).val(CurrentDateAddDay(0));
												});															
			  $(".day_30").focus(function(){
		                                       
		                                        $(this).val(CurrentDateAddDay(30));
												}); 														             							 			 				 
	 }					 
						 	
</script>
<?php error_reporting(null); date_default_timezone_set('Asia/Shanghai');
echo ' 
从<input type=text id=search_start_date name=start_date size=6 value="" placeholder="输入开始日期"/>到
	    <input type=text id=search_end_date name=end_date size=6 placeholder="输入截止日期" value="'.date('Y-m-d',time()).'"/>
		<input type=text id=search_material_name class=material_name name=material_name autocomplete=off size=6 placeholder="输入品名前2位"/>
		<input type=hidden id=search_material_id name=material_id class=material_id />
		<input type=hidden id=search_supplier_id name=supplier_id class=supplier_id />
		<input type=text id=search_supplier name=supplier class=supplier size=8 placeholder="点击选供应商" />
		<input type=text id=search_warehouse class=warehouse_name name=warehouse_name  size=8 placeholder="点击可选仓库" /><input type=hidden id=search_warehouse_id class=warehouse_id name=warehouse  />
		<button id=search value=""/>搜索</button>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<button id=stat value="" />汇总</button> 
		<input type="hidden" id="page" name="page" value=0  >
        <button id=previous_page>前页</button><button id=next_page>后页</button>';
?>
