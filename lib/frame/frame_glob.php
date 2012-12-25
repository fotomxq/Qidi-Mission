<?php

/**
 * 框架全局引用文件
 * 
 * @version 2
 * @author liuzilu
 * @package Frame
 */
if (!defined('DIR_LIB') || !defined('DIR_DATA') || !defined('DS')) {
    die();
}

//配置
require(DIR_DATA . DS . 'configs' . DS . 'frame.inc.php');

//初始化错误触发器
require(DIR_LIB . DS . 'frame' . DS . 'frame_error.php');
//加载配置项
require(DIR_LIB . DS . 'frame' . DS . 'frame_config.php');
//语言配置包
require(DIR_LIB . DS . 'frame' . DS . 'frame_lang.php');
FrameLang::loadFile('frame');

//初始化数据库连接
require(DIR_LIB . DS . 'frame' . DS . 'frame_db.php');
FrameDB::linkDB();

//SQL构造类
require(DIR_LIB . DS . 'frame' . DS . 'frame_sql.php');

//初始化IP
require(DIR_LIB . DS . 'frame' . DS . 'frame_ip.php');
define('IP_ADDR', FrameIP::addr());

//加载配置项
require(DIR_LIB . DS . 'frame' . DS . 'frame_log.php');
?>
