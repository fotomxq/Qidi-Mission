<?php

/**
 * 配置项类
 * 
 * @version 5
 * @author liuzilu
 * @package Frame
 * @filesource lib/frame/frame_error.php
 * @global string DIR_DATA
 * @global string DS
 */
class FrameConfig {

    /**
     * 已加载的数据
     * @var array 
     */
    public static $configArr;

    /**
     * 已加载过的数据文件名
     * @var array 
     */
    public static $loadedList;

    /**
     * 读取配置文件
     * @param string $name 配置文件名
     * @throws FrameError 失败则抛出异常
     */
    public static function loadFile($name) {
        try {
            if (isset(FrameConfig::$loadedList[$name]) != true) {
                FrameConfig::$configArr[$name] = parse_ini_file(DIR_DATA . DS . 'configs' . DS . $name . '.ini', true);
                FrameConfig::$loadedList[$name] = true;
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
