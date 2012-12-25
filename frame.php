<?php

/**
 * 框架初始化
 * @package Frame
 * @version 1
 * @author liuzilu
 */
//路径常量
define('DS', '/');
define('DIR_ROOT', __DIR__);
define('DIR_LIB', DIR_ROOT . DS . 'lib');
define('DIR_DATA', DIR_ROOT . DS . 'data');
define('DIR_WEB', DIR_ROOT . DS);

//引用全局设定文件
require(DIR_LIB . DS . 'frame' . DS . 'frame_glob.php');
?>
