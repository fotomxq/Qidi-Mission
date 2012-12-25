//全局
var mission_time;
//发送post请求
function action_post(type,action,arr){
    if($("#frame").data("ajax-action") == 1){
        $("#frame").data("ajax-action",0);
        $.post("post.php?type="+type+"&action="+action+"&r="+Math.random(),arr,
            function(res){
                $("#frame").data("ajax-action",1);
                switch(action){
                    case "list":
                        refresh_list(type,res);
                        if(!res[0]){
                            msg("","没有任何内容");
                        }
                        break;
                    case "mission":
                        break;
                    case "add":
                        if(Math.valueOf(res) > 0){
                            msg("ok","添加成功");
                        }else{
                            msg("error","添加失败");
                        }
                        refresh_list_action(type);
                        break;
                    case "edit":
                        if(res == "1"){
                            msg("ok","修改成功");
                        }else{
                            msg("error","修改失败");
                        }
                        refresh_list_action(type);
                        break;
                    case "del":
                        if(res == "1"){
                            msg("ok","删除成功");
                        }else{
                            msg("error","删除失败");
                        }
                        refresh_list_action(type);
                        break;
                }
            },"json");
    }
}
//刷新列表动作
function refresh_list_action(type){
    if(type == "exam"){
        action_post("exam","list",{name:"",desc:0});
    }else{
        action_post("topic","list",{examid:$("#frame").data("exam-id"),content:""});
    }
}
//刷新列表
//type = exam|topic 题库|题目
//res 结果集
function refresh_list(type,res){
    var html_id = "";
    var html_content = "";
    $("#frame").data(type+"-data",res);
    if(type == "exam"){
        $(res).each(function(k,v){
            html_f = "";
            if(v["post_name"].length > 35){
                html_f = "...";
            }
            html_content += "<li class=\"ui-state-default\" value=\""+k+"\" title=\"<p>题库名称 : "+v["post_name"]+"</p><p>创建时间 : "+v["post_data"]+"</p>\"><span class=\"ui-icon ui-icon-note\"></span>"+(k+1)+" : "+v["post_name"].substr(0,35)+html_f+"<div class=\"list_edit\"><span class=\"ui-icon ui-icon-pencil\"></span>编辑</div><div class=\"list_del\"><span class=\"ui-icon ui-icon-trash\"></span>删除</div></li>";
        });
    }else{
        $(res).each(function(k,v){
            html_f = "";
            if(v["post_content"].length > 35){
                html_f = "...";
            }
            html_content += "<li class=\"ui-state-default\" value=\""+k+"\" title=\"<p>题目内容 : "+v["post_content"]+"</p><p>答案 : "+v["post_name"]+"</p><p>分值 : "+v["post_fraction"]+"</p>\"><span class=\"ui-icon ui-icon-note\"></span>"+(k+1)+" : "+v["post_content"].substr(0,35)+html_f+"<div class=\"list_edit\"><span class=\"ui-icon ui-icon-pencil\"></span>编辑</div><div class=\"list_del\"><span class=\"ui-icon ui-icon-trash\"></span>删除</div></li>";
        });
    }
    if(type == "exam"){
        html_id = "#tab-1";
    }else{
        html_id = "#tab-2";
    }
    $(html_id).children(":not(:last())").remove();
    $(html_id).prepend(html_content);
    $(html_id).children(":not(:last())").children("[class=\"list_edit\"]").hide();
    $(html_id).children(":not(:last())").children("[class=\"list_del\"]").hide();
    //鼠标特效
    $(html_id).children(":not(:last())").mouseover(function(){
        $(html_id).children(":not(:last())").attr("class","ui-state-default");
        $(this).attr("class","ui-state-highlight");
        $(html_id).children(":not(:last())").children("[class=\"list_edit\"]").hide();
        $(html_id).children(":not(:last())").children("[class=\"list_del\"]").hide();
        $(this).children("[class=\"list_edit\"]").show();
        $(this).children("[class=\"list_del\"]").show();
    });
    if(type == "exam"){
        $(html_id).children(":not(:last())").dblclick(function(){
            $("#frame").data("exam-id",$("#frame").data("exam-data")[$(this).attr("value")]["id"]);
            refresh_list_action("topic");
            load_frame("topic");
        });
    }else{
        //课堂模式
        $(html_id).children(":not(:last())").dblclick(function(){
            load_mission($(this).attr("value"),0);
        });
    }
    $(html_id).sortable({
        items:"> li:not(:last())",
        update:function(e,ui){
            if(type == "exam"){
                exam_sort();
            }else{
                topic_sort();
            }
        }
    });
    $(html_id).children(":not(:last())").tooltip({
        track: true
    });
    //编辑按钮事件
    $(html_id).children(":not(:last())").children("[class=\"list_edit\"]").click(function(){
        if(type == "exam"){
            exam_edit($(this).parent().attr("value"));
        }else{
            topic_edit($(this).parent().attr("value"));
        }
    });
    //删除按钮事件
    $(html_id).children(":not(:last())").children("[class=\"list_del\"]").click(function(){
        if(type == "exam"){
            exam_del($(this).parent().attr("value"));
        }else{
            topic_del($(this).parent().attr("value"));
        }
    });
    html_content = "";
}
//弹出消息
function msg(type,msg){
    var id = "msg_"+Math.random().toString().slice(2,5);
    var html_content = "<div id=\""+id+"\" class=\"ui-ts ui-corner-all ui-state-";
    switch(type){
        case "ok":
            html_content += "highlight\"><p><span class=\"ui-icon ui-icon-check";
            break;
        case "error":
            html_content += "error\"><p><span class=\"ui-icon ui-icon-alert";
            break;
        default:
            html_content += "default\"><p><span class=\"ui-icon ui-icon-info";
            break;
    }
    html_content += "\" style=\"float: left; margin-right: .3em;\"></span>" + msg + "</p></div>";
    $(".ui-ts-kj").append(html_content);
    $("#"+id).fadeIn();
    window.setTimeout(function(){
        $("#"+id).fadeOut("normal",function(){
            $("#"+id).remove();
        });
    }, 5000);
}
//添加新的题库
function exam_add(){
    var val_name = $("#exam_add_input").val();
    if(val_name){
        action_post("exam","add",{
            name:val_name
        });
    }
}
//切换框架
function load_frame(type){
    var frame_display = $("#frame").data("frame-display");
    if(frame_display != type){
        var frame_id_hide = "#frame_"+frame_display;
        var frame_id_show = "#frame_"+type;
        $(frame_id_hide).stop();
        $(frame_id_show).stop();
        $(frame_id_hide).animate({
            left:"-100%"
        },"normal","",function(){
            $(frame_id_hide).hide();
        });
        $(frame_id_show).css("left","100%");
        $(frame_id_show).fadeIn();
        $(frame_id_show).animate({
            left:"0%"
        });
        $("#frame").data("frame-display",type);
    }
}
//刷新题库排序
function exam_sort(){
    var exam_sort = new Array();
    var id = 0;
    $("#tab-1 > li:not(:last)").each(function(k,v){
        id = $("#frame").data("exam-data")[$(this).attr("value")]["id"];
        exam_sort[k] = id;
    });
    action_post("exam","sort",{
        "sort":exam_sort
    });
}
//编辑题库
function exam_edit(list_id){
    $("#frame").data("action-exam-edit",list_id);
    $("#exam_edit").children("p:first").html("请为"+$("#frame").data("exam-data")[list_id]["post_name"]+"题库输入新的名称：");
    $("#exam_edit_input").val($("#frame").data("exam-data")[list_id]["post_name"]);
    $("#exam_edit").dialog("open");
}
function exam_edit_action(){
    action_post("exam","edit",{
        id:$("#frame").data("exam-data")[$("#frame").data("action-exam-edit")]["id"],
        sort:$("#frame").data("exam-data")[$("#frame").data("action-exam-edit")]["sort"],
        name:$("#exam_edit_input").val()
    });
}
//删除题库
function exam_del(list_id){
    $("#frame").data("action-exam-del",list_id);
    $("#exam_del").html("<p>您确定要删除"+$("#frame").data("exam-data")[list_id]["post_name"]+"题库么？</p>");
    $("#exam_del").dialog("open");
}
//删除题库动作
function exam_del_action(){
    action_post("exam","del",{
        id:$("#frame").data("exam-data")[$("#frame").data("action-exam-del")]["id"]
    });
}
//刷新题目排序
function topic_sort(){
    var topic_sort = new Array();
    var id = 0;
    $("#tab-2 > li:not(:last)").each(function(k,v){
        id = $("#frame").data("topic-data")[$(this).attr("value")]["id"];
        topic_sort[k] = id;
    });
    action_post("topic","sort",{
        "sort":topic_sort
    });
}
//编辑题目
function topic_edit(list_id){
    $("#frame").data("action-topic-edit",list_id);
    $("#topic_edit_input_content").val($("#frame").data("topic-data")[list_id]["post_content"]);
    $("#topic_edit_input_fraction").val($("#frame").data("topic-data")[list_id]["post_fraction"]);
    $("#topic_edit_input_answer").val($("#frame").data("topic-data")[list_id]["post_name"]);
    $("#topic_edit").dialog("open");
}
//编辑题目动作
function topic_edit_action(){
    var content = $("#topic_edit_input_content").val();
    if(content){
        action_post("topic","edit",{
            "id":$("#frame").data("topic-data")[$("#frame").data("action-topic-edit")]["id"],
            "sort":$("#frame").data("topic-data")[$("#frame").data("action-topic-edit")]["post_sort"],
            "content":content,
            "fraction":$("#topic_edit_input_fraction").val(),
            "answer":$("#topic_edit_input_answer").val()
        });
    }
    
}
//删除题目
function topic_del(list_id){
    $("#frame").data("action-topic-del",list_id);
    $("#topic_del").html("<p>您确定要删除"+$("#frame").data("topic-data")[list_id]["post_content"]+"题目么？</p>");
    $("#topic_del").dialog("open");
}
//删除题目动作
function topic_del_action(){
    action_post("topic","del",{
        id:$("#frame").data("topic-data")[$("#frame").data("action-topic-del")]["id"]
    });
}
//课堂模式
function load_mission(list_id,fin){
    var html_content = "";
    var html_data = "";
    if($("#frame").data("topic-data")[list_id]){
        html_data = $("#frame").data("topic-data")[list_id];
        var fraction_all = Math.abs($("#frame").data("mission-fraction-all")) + Math.abs(html_data["post_fraction"]);
        $("#frame").data("mission-fraction-all",fraction_all);
        if(fin == 1){
        }else if(fin == 2){
            var fraction = Math.abs($("#frame").data("mission-fraction")) + Math.abs(html_data["post_fraction"]);
            $("#frame").data("mission-fraction",fraction);
        }
    }
    if(fin){
        list_id += 1;
    }else{
        //初始化课堂模式
        $("#frame").data("mission-start",list_id);
        $("#frame").data("mission-fraction-all",0);
        $("#frame").data("mission-fraction",0);
        html_content = "<li><div id=\"button_mission_answer\">答案</div><div id=\"button_mission_right\">正确</div><div id=\"button_mission_error\">错误</div></li>";
        $("#tab-3").html(html_content);
        $("#button_mission_answer").button({
            icons:{
                primary: "ui-icon-help"
            },
            text:true
        });
        $("#button_mission_right").button({
            icons:{
                primary: "ui-icon-check"
            },
            text:true
        });
        $("#button_mission_error").button({
            icons:{
                primary: "ui-icon-close"
            },
            text:true
        });
        $("#button_mission_answer").click(function(){
            $("#mission_answer").fadeIn();
        });
        load_frame("mission");
    }
    if($("#frame").data("topic-data")[list_id]){
        //加载题目
        html_data = $("#frame").data("topic-data")[list_id];
        html_content = "<li>第"+(list_id+1)+"题 ( "+html_data["post_fraction"]+" 分 )</li>";
        html_content += "<li>"+html_data["post_content"]+"</li>";
        html_content += "<li id=\"mission_answer\" style=\"display:none;\">答案 : "+html_data["post_name"]+"</li>";
        html_content += "<li id=\"mission_right\" style=\"display:none;\"><img src=\"images/topic_right.png\"></li>";
        html_content += "<li id=\"mission_error\" style=\"display:none;\"><img src=\"images/topic_error.png\"></li>";
        $("#tab-3 > li:not(:last)").remove();
        $("#tab-3").prepend(html_content);
        //重新绑定单击事件
        $("#button_mission_right").die();
        $("#button_mission_error").die();
        $("#button_mission_right").click(function(){
            document.getElementById("topic_audio_right").play();
            window.clearTimeout(mission_time);
            $("#mission_right").fadeIn();
            mission_time = window.setTimeout(function(){
                load_mission(list_id,2);
            }, 3000);
        });
        $("#button_mission_error").click(function(){
            document.getElementById("topic_audio_error").play();
            window.clearTimeout(mission_time);
            $("#mission_error").fadeIn();
            mission_time = window.setTimeout(function(){
                load_mission(list_id,1);
            }, 3000);
        });
    }else{
        //结束
        var x = (($("#frame").data("mission-fraction") / $("#frame").data("mission-fraction-all"))*100).toString().slice(0,3);
        html_content = "<li>考试结束！</li><li>得分 : "+$("#frame").data("mission-fraction")+"分 ( 总共"+$("#frame").data("mission-fraction-all")+"分 )</li><li>正确率 : "+x+"%</li><li><div id=\"mission_return\">返回题目列表</div></li>";
        $("#tab-3 > li").remove();
        $("#tab-3").html(html_content);
        $("#mission_return").button({
            icons:{
                primary: "ui-icon-arrowrefresh-1-w"
            },
            text:true
        });
        $("#mission_return").click(function(){
            load_frame("topic");
        });
    }
}
//初始化
$(function(){
    //初始化
    //变量
    $("#frame").data("ajax-action",1);
    $("#frame").data("frame-display","exam");
    //框架
    $("#frame_exam").tabs();
    $("#frame_topic").tabs();
    $("#frame_mission").tabs();
    //题库添加按钮
    $("#button_exam_add").button({
        icons:{
            primary: "ui-icon-plusthick"
        },
        text:true
    });
    $("#button_exam_add").click(function(){
        exam_add();
    });
    $("#exam_add_input").keydown(function(e){
        if(e.keyCode == 13){
            exam_add();
        }
    });
    //编辑题库框架
    $("#exam_edit").dialog({
        resizable: false,
        width:400,
        height:300,
        modal: true,
        buttons: {
            "修改":function(){
                exam_edit_action();
                $(this).dialog("close");
            },
            "取消":function(){
                $(this).dialog("close");
            }
        },
        autoOpen:false
    });
    //删除题库框架
    $("#exam_del").dialog({
        resizable: false,
        width:300,
        height:250,
        modal: true,
        buttons: {
            "确定":function(){
                exam_del_action();
                $(this).dialog("close");
            },
            "取消":function(){
                $(this).dialog("close");
            }
        },
        autoOpen:false
    });
    //题目添加按钮
    $("#button_topic_add").button({
        icons:{
            primary: "ui-icon-plusthick"
        },
        text:true
    });
    $("#button_topic_add").click(function(){
        var content = $("#topic_add_input_content").val();
        if(content){
            action_post("topic","add",{
                "examid":$("#frame").data("exam-id"),
                "content":content,
                "fraction":$("#topic_add_input_fraction").val(),
                "answer":$("#topic_add_input_answer").val()
            });
        }
    });
    //编辑题目框架
    $("#topic_edit").dialog({
        resizable: false,
        width:500,
        height:700,
        modal: true,
        buttons: {
            "修改":function(){
                topic_edit_action();
                $(this).dialog("close");
            },
            "取消":function(){
                $(this).dialog("close");
            }
        },
        autoOpen:false
    });
    //删除题目框架
    $("#topic_del").dialog({
        resizable: false,
        width:300,
        height:250,
        modal: true,
        buttons: {
            "确定":function(){
                topic_del_action();
                $(this).dialog("close");
            },
            "取消":function(){
                $(this).dialog("close");
            }
        },
        autoOpen:false
    });
    //刷新列表
    refresh_list_action("exam");
});