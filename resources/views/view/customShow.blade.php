<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <!-- 支持icheck(或者加载jquery1.7+) -->
    <script type="text/javascript" src="js/jquery-1.8.3.mini.js"></script>
    <script type="text/javascript" src="ui/bootstrap-mini/bootstrap.min.js"></script>
    <script type="text/javascript" src="ui/layer/layer.js"></script>
    <script type="text/javascript" src="js/jquery.form.js"></script>

    <link type="text/css" rel="stylesheet" href="ui/bootstrap-mini/bootstrap.min.css">

    <style type="text/css">
        .addheight{height:40px}
        .submitButton{border-color: #F90;background-color:#F90;width:150px;height:30px;}
        .submitButton strong{color:white}
    </style>
    <script type="text/javascript">

        $(document).ready(function() {
            //get dropdown data from json   //plus time stamp to maake sure be loaded everytime
            var url = 'json/ccbuy.json?time='+getTimeStamp();
            jQuery.getJSON(url, function (data){
                //relationship dropdrow list
                var relationshipData = data.custom.relationship;
                var relationshipCount = relationshipData.length;
                for(var i = 0; i < relationshipCount; i++) {
                    jQuery('#dropdownMenu').append('<li><a href="#" onclick="setNameRelationship(\''+relationshipData[i]+'\')">'+relationshipData[i]+'</a></li>');
                }
                //dgFrom dropdrow list
                var dgFromData = data.custom.from;
                var dgFromCount = dgFromData.length;
                for (var i = 0; i < dgFromCount; i++) {
                    jQuery('#dropdownMenu2').append('<li><a href="#" onclick="setNameFrom(\''+dgFromData[i]+'\')">'+dgFromData[i]+'</a></li>');
                }
            });

            //aviod a bug when new open this window and press ENTER the form will be submit
            jQuery('#customName').focus();
            //异步提交 添加客户信息
            var options = {
                target: '#showResult',   //返回值呈现在target处
                url: "{{url('addcustom')}}",
                type: 'post',
               // dataType: 'json',
                beforeSubmit:function(){
                    checkCustomData();
                },
                success: function() {
                    layer.alert('添加成功',  {
                        skin: 'layui-layer-molv', //样式类名
                        closeBtn: 0
                    }, function () {
                        goback();
                    });
                },
                error: function(){
                    layer.alert('发生未知错误, 请联系涛哥', {icon:2});
                }
            };

            $('#addCustomForm').submit(function () {
                $(this).ajaxSubmit(options);
                return false;
            });
        });

        //检查数据
        function checkCustomData() {

        }

        //关闭窗口
        function closeWindos()
        {
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        }

        //关闭当前页面返回上一页
        function goback()
        {
            var a = jQuery('#newopencart',window.parent.document);
            parent.layer.closeAll();  //疯狂模式，关闭所有层
            a.click();
        }

        //从dropdown选择一个客户关系
        function setNameRelationship(setName)
        {
            jQuery('#relationship').val(setName);
        }
        //set dgFrom name for textbox when select dropdown
        function setNameFrom(setName) {
            jQuery('#dgFrom').val(setName);
        }
        //添加新的客户关系
        function addNewRelationship()
        {
            var relationName = jQuery('#relationship').val();
            if(relationName.trim() == ''){
                layer.tips('小样! 还想用空名字?', '#relationship', {
                    tips: [1, '#78BA32']
                });
                return;
            }
            var list = jQuery('#dropdownMenu li');
            var count = list.length;
            for(var i = 0; i<count; i++)
            {
                //判断是否已经存在这个名称
                if(relationName == list[i].getAttribute('value')){
                    layer.tips('小样! 这个名字已经存在了', '#relationship', {
                        tips: [1, '#78BA32']
                    });
                    return;
                }
            }
            layer.tips('<strong>此功能暂时被取消, 录入信息后直接点击添加客户即可</strong>', '#customsubmit', {
                tips: [1, '#78BA32']
            });
        }
        //添加新的客户来源
        function addNewFrom()
        {
            var dgFrom = jQuery('#dgFrom').val();
            if(dgFrom.trim() == ''){
                layer.tips('小样! 还想用空名字?', '#dgFrom', {
                    tips: [1, '#78BA32']
                });
                return;
            }
            var list = jQuery('#dropdownMenu2 li');
            var count = list.length;
            for(var i = 0; i<count; i++)
            {
                //判断是否已经存在这个名称
                if(dgFrom == list[i].getAttribute('value')){
                    layer.tips('小样! 这个名字已经存在了', '#dgFrom', {
                        tips: [1, '#78BA32']
                    });
                    return;
                }
            }
            layer.tips('<strong>此功能暂时被取消, 录入信息后直接点击添加客户即可</strong>', '#customsubmit', {
                tips: [1, '#78BA32']
            });
        }

        //time + random num
        function getTimeStamp() {
            var myData = new Date();
            return myData.getTime()+Math.random();
        }

    </script>
</head>

<body>
<form class="form-horizontal" id="addCustomForm">

    <div class="addheight"></div>

    <div class="form-group">
        <label class="col-xs-2 control-label" for="customName">客户姓名</label>
        <div class="col-xs-9">
            <input class="form-control input-sm" required type="text" name="customName" id="customName" placeholder="比如 隔壁老王的小老婆">
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-2 control-label" for="relationship">客户关系</label>
        <div class="col-xs-6">
            <input class="form-control input-sm" type="text" name="relationship" id="relationship" placeholder="比如 家人 朋友, 或输入新名字点击右侧按钮添加" required>
        </div>
        <div class="col-xs-4">
            <!-- Split button -->
            <div class="btn-group">
                <button type="button" class="btn btn-warning" onclick="addNewRelationship()">确认添加新客户关系</button>
                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" id="dropdownMenu">
                </ul>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-2 control-label" for="dgFrom">客户来源</label>
        <div class="col-xs-6">
            <input class="form-control input-sm" type="text" name="dgFrom" id="dgFrom" placeholder="比如 涛哥 灿灿" required>
        </div>
        <div class="col-xs-4">
            <!-- Split button -->
            <div class="btn-group">
                <button type="button" class="btn btn-warning" onclick="addNewFrom()">确认添加新客户来源</button>
                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" id="dropdownMenu2">
                </ul>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-2 control-label" for="info">备注</label>
        <div class="col-xs-9">
            <input class="form-control input-sm" type="text" name="info" id="info" placeholder="额外信息">
        </div>
    </div>

    <div class="addheight"></div>
    <div id="showResult"></div>
    <div class="form-group">
        <div class="col-xs-5 text-right"><p><button class="submitButton" name="customsubmit" id="customsubmit" type="submit"><strong>添加客户</strong></button></p></div>
        <div class="col-xs-2"></div>
        <div class="col-xs-5 text-left"><p><button class="submitButton" onclick="closeWindos()"><strong>关闭窗口</strong></button></p></div>
    </div>{!! csrf_field() !!}
</form>

</body>
</html>
