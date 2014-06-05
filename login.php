<?php

/**
 * 登陆验证
 * @package Mission
 * @version 3
 * @author liuzilu
 */
//隐藏对验证码的判断
require('frame.php');
require(DIR_LIB . DS . 'mission' . DS . 'mission_login.php');
$url = 'index.php';
if (isset($_POST['password']) == true && isset($_POST['vcode']) == true) {
    if (strtolower($_SESSION["vcode"]) == strtolower($_POST['vcode'])) {
    	//验证是否重复尝试，超过10次禁止访问
    	$sessionNumName = 'login-num';
    	if(isset($_SESSION[$sessionNumName]) == false){
    		$_SESSION[$sessionNumName] = 0;
    	}
    	if($_SESSION[$sessionNumName] < 10){
	        $missionLogin = new MissionLogin();
	        $result = $missionLogin->login($_POST['password']);
	        if ($result == true && isset($_GET['mobile'])) {
	            $url .= '?mobile=1';
	        }
	        if($result == true){
	        	$_SESSION[$sessionNumName] = 0;
	        }else{
	        	$_SESSION[$sessionNumName] += 1;
	        }
    	}
    }
}
header('Location:' . $url);
?>
