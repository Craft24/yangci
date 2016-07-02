$(function(){
    $('#submit_name').click(
         function(){
        	 swal({ 
     		    title: "您确定要删除吗？", 
     		    text: "您确定要删除这条数据？", 
     		    type: "warning", 
     		    showCancelButton: true, 
     		    closeOnConfirm: false, 
     		    confirmButtonText: "是的，我要删除", 
     		    confirmButtonColor: "#ec6c62"
     		},function(){ 
     			$.ajax({
                    type:"POST",
                    url:GLOBAL.domain+"/admin/Auth/delete_user",
                    data:{user_id:$("#user_id").val()},
                    dataType:"json",
                    success:function(data){
                    	swal("Deleted!","Your imaginary file has been deleted.","success");
                    	setTimeout(function() {
                    		 window.location.href=GLOBAL.htmlpath+"/admin/main.html";  
                    	}, 2000);
                    },
                    error:function(data){
                    	swal("Deleted!",JSON.parse(data.responseText).msg,"error");
                    	setTimeout(function() {
                    		window.location.href=GLOBAL.htmlpath+"/admin/main.html";  
                    	}, 2000);
                    }
                });
     		});	    
         }
    );
});
