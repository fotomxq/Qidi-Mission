<?php

/**
 * 首页
 * @package 
 * @version 1
 * @author liuzilu
 */
require('frame.php');
require(DIR_LIB . DS . 'frame' . DS . 'frame_smarty.php');
require(DIR_LIB . DS . 'mission' . DS . 'mission_login.php');
$missionLogin = new MissionLogin();
if ($missionLogin->check() == true) {
    $smarty->assign('WEB_URL', WEB_URL);
    if (isset($_GET['mobile'])) {
        $smarty->display('mobile_index.html');
    } else {
        $smarty->display('index.html');
    }
} else {
    $mobile = false;
    if ($mobile == true) {
        $smarty->display('mobile_login.html');
    } else {
        $smarty->display('login.html');
    }
}
?>
