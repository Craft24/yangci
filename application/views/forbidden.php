<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ERP系统</title>
</head>
<script src="../../jquery1.9/jquery-1.9.0.min.js" type="text/javascript"></script>
<script src="../../jquery1.9/jquery-1.9.0.js" type="text/javascript"></script>
<script src="../../cjquery/src/Rightgrid.js" type="text/javascript"></script>
<link href="../../cjquery/css/grid.css" rel="stylesheet" type="text/css">
<link href="../../cjquery/css/menu.css" rel="stylesheet" type="text/css">
<script>

    var mainurl="http://www.mesoco.net/erp/index.php/"; 
	var passing;

    $(function(){
	
	   $("#head").makemenu2();////顶部菜单
       $("#tipnote").append('<div style="padding-left:50px;">没有权限</div>');
	   $("#tipnote").show();
	   resize("#tipnote");
	   })  
</script>
<body>
<div id=container>
<div id=head></div>
<div id=main><h2>没有权限</h2>
    <div id=main_left>
	    
	    <div id=grid>
		
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
