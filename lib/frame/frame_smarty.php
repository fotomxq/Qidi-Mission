<?php

/**
 * smarty引用页
 * 
 * @version 2
 * @author liuzilu
 * @package Frame
 */
if (!defined('DIR_LIB') || !defined('DIR_DATA') || !defined('DS')) {
    die();
}
//Smarty
require(DIR_LIB . DS . 'smarty' . DS . 'Smarty.class.php');
$smarty = new Smarty;
$smarty->debugging = false;
$smarty->caching = true;
$smarty->cache_lifetime = 0;
$smarty->template_dir = DIR_DATA . DS . 'theme' . DS . WEB_STYLE;
$smarty->compile_dir = DIR_DATA . DS . 'cache' . DS . 'smarty' . DS . 'tempc';
$smarty->cache_dir = DIR_DATA . DS . 'cache' . DS . 'smarty' . DS . 'cache';
$smarty->config_dir = DIR_DATA . DS . 'configs';
$smarty->left_delimiter = '#{';
$smarty->right_delimiter = '}#';
//加载框架语言包
FrameLang::loadFile('frame');
//网站标题
$smarty->assign('WEB_TITLE', FrameLang::$langArr['frame']['Title']);
?>
