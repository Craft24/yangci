$(function () {
   
    //定义系统配置项
    window.GLOBAL = {};
    GLOBAL.domain = window.location.origin+'/index.php';
    GLOBAL.hostname = window.location.hostname;
    GLOBAL.htmlpath = window.location.origin+'/html';
    GLOBAL.is_login=false;

    //检测是否登录
    if(!GLOBAL.is_login){
        $.ajax({
            type:"GET",
            url:GLOBAL.domain+"/admin/login/get_check_login",
            dataType:"json",
            success:function(data){
                if(!data.data.is_login){
                    location.href = GLOBAL.htmlpath+"/admin/login.html";
                }
                GLOBAL.is_login=data.data.is_login;
            },
            error:function(data){
                swal("Deleted!",JSON.parse(data.responseText).msg,"error");
                setTimeout(function() {
                    window.location.href=GLOBAL.htmlpath+"/admin/main.html";  
                }, 2000);
            }
           
        });
    }
    
	//退出按钮事件
    $('[i="exit_login"]').on('click',function(){
        //alert(6);
        $.ajax({
            type:"GET",
            url:GLOBAL.domain+"/admin/login/delete_index",
            dataType:"json",
            success:function(data){
                location.href = GLOBAL.htmlpath+"/admin/login.html"; 
            },
            error:function(data){
                swal("Deleted!",JSON.parse(data.responseText).msg,"error");
                setTimeout(function() {
                    window.location.href=GLOBAL.htmlpath+"/admin/main.html";  
                }, 2000);
            }
        });
    });

    //头部公共部分
    if(!$('[i="top"]').html()){
        console.log($('[i="top"]').html());
        $('[i="top"]').load('exit.html');
    }
    $('[i="top"]').load('exit.html');
    //console.log($('[i="top"]').html());
    
});