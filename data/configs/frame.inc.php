<?php

/**
 * 全局定义
 * 
 * @version 3
 * @author liuzilu
 * @package Frame
 */
//定义页面编码
header("Content-type: text/html; charset=utf-8");
//定义时区
date_default_timezone_set('PRC');
//开启session
@session_start();

//数据库DSN设定
define('PDO_DSN', 'mysql:host=localhost;dbname=qidimission');
//数据库用户名
define('PDO_USER', 'root');
//数据库密码
define('PDO_PASS', 'root');
//数据库参数设定
define('PDO_PARAM_SET', 'SET NAMES utf8');
define('PDO_PARAM_PERSISTENT', true);
//表前缀
define('DB_T', 'mi_');
//数据库关键字屏蔽符号
define('DB_I', '`');

//网站地址
define('WEB_URL', 'http://localhost/personal/web');
//服务器IP
define('SEVER_IP', 'localhost');
//黑名单开关
define('IP_BAN_ON', true);

//网站默认样式
define('WEB_STYLE', 'default');

//日志开关
define('FRAME_LOG_ON', true);

//默认语言
define('FRAME_LANG', 'ch_sim');

//错误开关
define('FRAME_ERR_ON', true);
//错误日志开关
define('FRAME_ERR_LOG_ON', true);
//错误导向页面
define('FRAME_ERR_URL', WEB_URL . '/error.php');
//侦错模式开关
define('FRAME_ERR_DEBUG_ON', true);
?>
