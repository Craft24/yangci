var TableInit = function() {
    var oTableInit = new Object();
    //初始化Table
    oTableInit.Init = function() {
        $('[i="tableList"]').bootstrapTable({
            url :GLOBAL.domain+'/admin/admin/get_admin_list', 
            method : 'get', 
            pagination: true,
                columns : [ 
                {
                    field : 'admin_id',
                    title : '序号'
                }, {
                    field : 'admin_name',
                    title : '昵称'
                }, {
                    field : 'true_name',
                    title : '名称'
                }, {
                    field : 'mobile_phone',
                    title : '手机号'
                }, {
                  field: 'tool',
                  title: '操作'
                }
            ],
            queryParams: function(data) {
                var ajaxData = {};
                if($('[i="admin_name"]').val() !== ""){
                    ajaxData.admin_name = $('[i="admin_name"]').val();
                }
                ajaxData.page_now = parseInt(data.offset / data.limit) + 1;
                ajaxData.page_size = data.limit;
                return ajaxData;
            },
            responseHandler: function(data) {
                if (data.code !=200) {
                    swal(data.msg);
                };
                var resuseData = {};
                resuseData.total = data.page.count;
                //列数据参数的顺序
                var fuckData = data.lists;
                resuseData.data = [];
                $.each(fuckData, function (i,v) {
                    fuckData[i].admin_id = v.admin_id;
                    fuckData[i].admin_name = v.admin_name;
                    fuckData[i].true_name =  v.true_name ;
                    fuckData[i].mobile_phone = v.mobile_phone;
                    fuckData[i].tool = '<a i="see" href="javascript:;"data-id="' + v.admin_id + '">查看</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a  href="javascript:;"i="delete" data-id="' + v.uid + '">删除</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a i="rest" href="javascript:;" data-id="' + v.uid + '">重置密码</a>';
                });
                //将数据处理为dt可以识别的格式
                resuseData.rows = fuckData;
                return resuseData;
             }
        });
    };
    return oTableInit;
};

//初始化页面上面的按钮事件
var ButtonInit = function() { 
    var oInit = new Object(); 
    oInit.Init = function(){  
        $('[i="seach_list"]').on('click',function(event){
            $('[i="tableList"]').bootstrapTable("refresh");
        });
    };  
    return oInit;
};

$(function() {
    //1.初始化Table
    var oTable = new TableInit();
    oTable.Init();

    //2.初始化Button的点击事件
    var oButtonInit = new ButtonInit();
    oButtonInit.Init(); 
});
