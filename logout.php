<?php

/**
 * 退出登陆
 * @package Mission
 * @version 1
 * @author liuzilu
 */
require('frame.php');
require(DIR_LIB . DS . 'mission' . DS . 'mission_login.php');
$missionLogin = new MissionLogin();
$missionLogin->logout();
header('Location:index.php');
?>
