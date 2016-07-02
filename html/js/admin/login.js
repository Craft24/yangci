$(function(){
    $('#submit_name').click(
	    function(){
            $.ajax({
                type:"POST",
                url: window.location.origin+'/index.php'+"/admin/login/post_index",
                data:{admin_name:$("#admin_name").val(),password:$("#password").val()},
                dataType:"json",
                success:function(data){
                	location.href=window.location.origin+'/html'+"/admin/table.html"; 
                },
               	error:function(e){
                    swal({
                        timer:2000,
                        title: "错误提示",
                        text: JSON.parse(e.responseText).msg,
                        type: "warning",
                        confirmButtonClass: 'btn-warning',
                        confirmButtonText: '确定'
                    });
                } 
            });
    	}
    ); 
}); 