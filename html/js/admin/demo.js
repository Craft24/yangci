$(function() {  //初始事件绑定

    //1.初始化Table
    var oTable = new TableInit();
    oTable.Init();  

    //2.初始化Button的点击事件
    var oButtonInit = new ButtonInit(); 
    oButtonInit.Init();

}); 

//初始化页面上面的按钮事件
var ButtonInit = function() { 
    var oInit = new Object(); 
    oInit.Init = function(){         
        $('[i="seach_list"]').on('click',function(event){
            $('#table_list').bootstrapTable("refresh");
        });
    };  
    return oInit;
};

var TableInit = function() { 
    var oTableInit = new Object();  //初始化Table
    oTableInit.Init = function() {  
        $('#table_list').bootstrapTable({
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
                sidePagination: "server", //分页方式：client客户端分页，server服务端分页（*）
                pageList: [10, 25, 50, 100],
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
    return oTableInit;
};  

