<?php

/**
 * 日志系统
 * 
 * @version 11
 * @author liuzilu
 * @package Frame
 * @global string IP_ADDR
 * @global string DIR_DATA
 * @global string DS
 * @filesource lib/frame/frame_file_operate.php
 */
class FrameLog {

    /**
     * 添加一个记录
     * @param string $msg 记录内容
     */
    static public function add($msg) {
        if (FRAME_LOG_ON == true) {
            $timeYm = date('Ym');
            $timeD = date('d');
            $timeHis = date('His');
            $dirLocation = DIR_DATA . DS . 'logs' . DS . $timeYm;
            if (FrameFileOperate::newDir($dirLocation) == true) {
                $fileLocation = $dirLocation . DS . $timeYm . $timeD . '.log';
                $ip = IP_ADDR;
                $content = $time . ' ' . $ip . ' ' . $msg;
                if (FrameFileOperate::editFile($fileLocation, $content, true) == false) {
                    die();
                }
            }
        }
    }

}

?>
