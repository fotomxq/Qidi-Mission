<?php

/**
 * 登陆验证
 * @package Mission
 * @version 2
 * @author liuzilu
 */
//隐藏对验证码的判断
require('frame.php');
require(DIR_LIB . DS . 'mission' . DS . 'mission_login.php');
$url = 'index.php';
if (isset($_POST['password'])){ 
// && isset($_POST['vcode'])) {
    //if (strtolower($_SESSION["vcode"]) == strtolower($_POST['vcode'])) {
        $missionLogin = new MissionLogin();
        $result = $missionLogin->login($_POST['password']);
        if (isset($_GET['mobile'])) {
            $url .= '?mobile=1';
        }
    //}
}
header('Location:' . $url);
?>
