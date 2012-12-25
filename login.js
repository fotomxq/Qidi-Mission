function hvcode(){
    var r = Math.random();
    $("#vcode").attr("src","vcode.php?"+r);
}
function login_submit(){
    var vcode = $("#input_vcode").val();
    if(vcode && vcode.length == 4){
        var pass = $("#input_pass").val();
        if(pass){
            $("#login").submit();
        }else{
            return;
        }
    }else{
        return;
    }
}
$(function(){
    $("#button_submit").button({
        icons:{
            primary:"ui-icon-locked"
        }
    });
    $("#button_submit").click(function(){
        login_submit();
    });
    $("#input_pass").keydown(function(e){
        if(e.keyCode == 13){
            login_submit();
        }
    });
    $("#input_vcode").keydown(function(e){
        if(e.keyCode == 13){
            login_submit();
        }
    });
});