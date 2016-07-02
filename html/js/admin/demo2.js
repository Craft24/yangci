$(function() {  //初始事件绑定  
    app.domInit();
    app.evenInit(); 
    app.tableListInit();

}); 


var app = {};
app.domInit = function () {
    app.$table = $('[i="table_list"]');
};

app.evenInit = function () {
    app.$table.on('click','[i="delete"]',function(event){
        var id = $(this).attr('data-id')||0;
        app.delete(id);
    });
    app.$table.on('click','[i="rest"]',function(event){
        var id = $(this).attr('data-id')||0;
        app.resetPasword(id);
    });
    app.$table.on('click','[i="see"]',function(event){
        var id = $(this).attr('data-id')||0;
        alert(id);
    });
    $('[i="seach_list"]').on('click',function(event){
        app.$table.bootstrapTable("refresh");
    });
};

//删除用户
app.delete = function(id){
     $.ajax({
        type:"POST",
        url:GLOBAL.domain+"/admin/admin/delete_index",
        data:{id:id},
        dataType:"json",
        success:function(data){
            swal({
                timer:1000,
                title: "删除成功",
                type: "success", 
            });
            app.$table.bootstrapTable("refresh");
        },
        error:function(e){
            swal({
                timer:2000,
                title: "删除失败",
                text: JSON.parse(e.responseText).msg,
                type: "warning",
                confirmButtonClass: 'btn-warning',
                confirmButtonText: '确定'
            });
        }, 
    });
};

//重置密码
app.resetPasword = function(id){  
    APP.swalConfirm({text:'你确定要重置密码吗？',callback: function () {
        APP.ajax('admin/users/reset_pwd', {uid:id}, function (returnData) {
            app.$table.bootstrapTable("refresh");
        }, 'put');
    }});
}

//查看
app.see = function(id){
    app.$('[i="myModal"]').modal('show');
    app.$('[i="modal-title"]',app.$modal_edit).html('查看用户');
    app.$('[i="button"]',app.$modal_edit).html('查看用户');
}

app.tableListInit = function() { 
    	$('[i="table_list"]').bootstrapTable({
    		    url: GLOBAL.domain + '/admin/admin/get_admin_list', //请求后台的URL（*）
                method: 'get', //请求方式（*）
                pagination: true, //是否显示分页（*）
                queryParams: function(data) { //请求参数
                    var ajaxData = {};
                    var getData = {};
                    getData.admin_name = $('[i="admin_name"]').val().trim();
                    if(getData.admin_name !== ""){
                        ajaxData.admin_name = getData.admin_name;
                    }
                    getData.true_name = $('[i="true_name"]').val().trim();
                    if(getData.true_name !== ""){
                        ajaxData.true_name = getData.true_name;
                    }
                    getData = undefined;

                    ajaxData.page_now = parseInt(data.offset / data.limit) + 1;
                    ajaxData.page_size = data.limit;
                    return ajaxData;
                },
                columns: [
                {
                    field: 'admin_id',
                    title: '管理员编号'
                },
                {
                    field: 'admin_name',
                    title: '管理员名称'
                },
                {
                    field: 'true_name',
                    title: '真实姓名'
                },
                {
                    field: 'mobile_phone',
                    title: '手机'
                },
                {
                    field: 'add_time',
                    title: '添加时间'
                },
                {
                    field: 'tool',
                    title: '操作'
                }
                ],
                responseHandler: function(data) {  //返回数据预处理
                    if(data.code != 200) {
                        swal(data.msg);
                    };
                    var resuseData = {};
                    resuseData.total = data.page.count;                  
                    var fuckData = data.lists;
                    resuseData.data = [];
                    //列数据参数的顺序
                    $.each(fuckData,function(i, v) {
                        fuckData[i].admin_id = v.admin_id;
                        fuckData[i].mobile_phone = v.mobile_phone;
                        fuckData[i].add_time = v.add_time;
                        fuckData[i].tool = '<a i="see" href="javascript:;"data-id="' + v.admin_id + '">查看</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a  href="javascript:;"i="delete" data-id="' + v.admin_id + '">删除</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a i="rest" href="javascript:;" data-id="' + v.admin_id + '">重置密码</a>';
                    });
                    //将数据处理为dt可以识别的格式
                    resuseData.rows = fuckData;
                    return resuseData;
                }         
        }); 
};  
