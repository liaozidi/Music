<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Music~</title>
    <meta name="Description" content="音乐"/>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <script src="layui/layui.js" charset="utf-8"></script>
    <script src="layui/jquery-3.3.1.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="layui/css/layui.css">
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
<!--[if lt IE 9]>
  <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
  <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->   
    <style type="text/css">
        .bg1{
            background-color: red;
        }
        .bg2{
            background-color: green;
        }
        .box{
            margin-top: 100px;
            display: flex;
            flex-flow: column nowrap;
            justify-content: center;
            align-items: center;
        }
        .list{
            width: 300px;
            /*height: 600px;*/

            display: flex;
            flex-flow: column nowrap;
            justify-content: flex-start;
            align-items: center;

        }
        .page{
            width: 300px;
            height: 60px;
            display: flex;
            flex-flow: row nowrap;
            justify-content: center;
            align-items: center;

        }
        .item{
            border-bottom:1px solid #999;
            width: 300px;
            height: 50px;
            font-size: 14px;
            display: flex;
            flex-flow: row nowrap;
            justify-content: space-between;
            align-items: center;
        }
    </style>
    
</head>

<body>
<div class="box">
    <form class="layui-form layui-form-pane" action="">
      
      <div class="layui-form-item input">
        <label class="layui-form-label">搜索</label>
        <div class="layui-input-inline">
          <input type="text" name="name" id="name"  lay-verify="required" placeholder="请输入歌曲、作者" autocomplete="off" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">确定</button>
        </div>
    </div>

<!--     <div class="layui-form-item">
        <div class="layui-input-block">
            <select id="page" name="page">
        <option value="杭州">杭州</option>
        <option value="北京">北京</option>
      </select>
        </div>
    </div> -->



    <div class="layui-form-item">
        <img style="margin: 0px auto;" width="300" id="pic" src="">
    </div>

    <div class="layui-form-item">   
        <audio id="mp3" controls>
            <source src="" />
        </audio>
    </div>

    <div class="layui-form-item">
        <div style="margin: 0px auto;text-align: center;"><br> 下载:
    <a id="download-mp3" target="_blank" download></a>
    <br> 下载:
    <a id="download-ogg" target="_blank" download></a>
    <br></div>

    </div>
    </form>

    <div class="list" id="list">
        <!-- <div class="item">
            <div class="name">消愁(Live)——毛不易</div>
            <div class="down"><i class="layui-icon" style="font-size: 24px;">&#xe601;</i></div>
        </div> -->

    </div>
    <div class="page">
        <select id="page2">
                    </select>
        <button onclick="page()" class="layui-btn layui-btn-normal layui-btn-xs">跳转</button>
    </div>
</div>
          
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->

<script>
    var key ='';
layui.use(['form', 'layedit', 'laydate'], function(){
  var form = layui.form
  ,layer = layui.layer
  ,layedit = layui.layedit
  ,laydate = layui.laydate;
  
  //创建一个编辑器
  var editIndex = layedit.build('LAY_demo_editor');
 
  
  
  //监听提交
  form.on('submit(demo1)', function(data){
    a(data.field.name);
    // layer.alert(JSON.stringify(data.field), {
    //   title: '最终的提交信息'
    // })
    return false;
  });
 
    window.a=function(a,page=1){
       key = a;

        $.ajax({
            type:"get",
            url:"http://test.tmaize.net:8080/api/proxy",
            async : false,
            data:{url:"luaapp.cn/music.search.json",'urlParm': 'key:' + key + ';page:' + page},
            success:function(data){
                var html="";
                var obj = JSON.parse(data);
                //console.log(obj.list);
                for (var i = 0; i <= obj.now-1; i++) {
                    var one = obj.list[i];
                    console.log(one.singer);
                    html+="<div class='item'>";
                    html+="<div class='name'>"+one.song+"——"+one.singer+"</div>";
                    html+="<div onclick='down(this)' url="+ one.url +" class='down'><i class='layui-icon' style='font-size: 24px;'>&#xe61f;</i></div></div>";
                }
               $("#list").html(html);
               fillSelect(1, Math.ceil(obj.total / 10.0));
            }
        })
    }

     
  
});
function page(){
    var page = $("#page2 option:selected").val();
    var key = $("#name").val();
    // alert(key);return;
    a(key,page);
}

function down(a){
    var a=$(a);//js对象转jquery对象  
    var url=a.attr("url");
    url = url.substr(7);
    
    $.ajax({
            type:"get",
            url:"http://test.tmaize.net:8080/api/proxy",
            timeout:5000,
            data:{'url':url},
            success:function(data){
               var obj = JSON.parse(data);
                $('#pic').attr('src', obj.pic);
                $('#mp3').attr('src', obj.mp3);
                $('#download-mp3').text(obj.song + "-" + obj.singer + ".mp3");
                $('#download-mp3').attr('href', obj.mp3);
                $('#download-ogg').text(obj.song + "-" + obj.singer + ".ogg");
                $('#download-ogg').attr('href', obj.ogg);

            }
        })
  };
function fillSelect(start, end) {
    $('#page2').empty();
    html = '';
    for (var i = start; i <= end; i++) {
        html += "<option value='" + i + "'>" + i + "</option>"
    }
    $('#page2').html(html);
}
</script>
<script type="text/javascript">
   
</script>
</body>

</html>