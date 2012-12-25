<?php

/**
 * 语言包读取函数
 * 
 * @version 9
 * @author liuzilu
 * @package Frame
 * @filesource lib/frame/frame_error.php
 * @global string DIR_DATA
 * @global string DS
 */
class FrameLang {

    /**
     * 已加载的语言数据
     * @var array 
     */
    public static $langArr;

    /**
     * 已加载过的数据文件名
     * @var array 
     */
    public static $loadedList;

    /**
     * 加载一个新的语言包
     * @param string $name 文件名
     * @throws FrameError 加载INI失败抛出异常
     */
    public static function loadFile($name) {
        try {
            if (isset(FrameLang::$loadedList[$name]) != true) {
                FrameLang::$langArr[$name] = parse_ini_file(DIR_DATA . DS . 'lang' . DS . FRAME_LANG . DS . $name . '.ini');
                FrameLang::$loadedList[$name] = true;
            }
        } catch (Exception $e) {
            throw new FrameError('Lang');
        }
    }

    /**
     * 卸载已加载的文件项
     * @param string $name 文件名
     */
    public static function unloadFile($name) {
        FrameLang::$langArr[$name] = null;
        FrameLang::$loadedList[$name] = false;
    }

}

?>
