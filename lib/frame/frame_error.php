<?php

/**
 * 错误处理包
 * 
 * @version 4
 * @author liuzilu
 * @package Frame
 * @filesource lib/frame/frame_config.php
 * @filesource lib/frame/frame_log.php
 * @global boolean FRAME_ERR_ON 报错开关
 * @global string FRAME_ERR_URL 转到页面地址
 * @global boolean FRAME_ERR_DEBUG_ON DeBug模式开关
 * @global boolean FRAME_ERR_LOG_ON 日志记录开关
 */
class FrameError extends Exception {

    /**
     * 初始化函数
     * @param string $message
     * @param string $code
     * @param string $previous
     */
    public function __construct($message = '', $code = '', $previous = '', $passLog = false) {
        if ($message != '') {
            if ($code != '') {
                if ($previous != '') {
                    parent::__construct($message, $code, $previous);
                } else {
                    parent::__construct($message, $code);
                }
            } else {
                parent::__construct($message);
            }
        } else {
            parent::__construct();
        }
        if (FRAME_ERR_ON) {
            if ($message != 'PDO' || $message != 'Log') {
                if (FRAME_ERR_LOG_ON == true && $passLog == false && class_exists('FrameLog') == true) {
                    FrameLog('Error-' . $message);
                }
            } else {
                if (FRAME_ERR_LOG_ON == true && $passLog == false) {
                    error_log($message);
                }
            }
            $URL = FRAME_ERR_URL . '?message=';
            if (isset(FrameLang::$langArr['errMsg']['Default'])) {
                $URL .= FrameLang::$langArr['errMsg']['Default'];
            }
            if (FRAME_ERR_DEBUG_ON == true) {
                $URL .= '<p>Debug : ON </p><p>Message : ' . $this->getMSG() . '</p><p>MessageE : ' . $this->getMessage() . '</p><p>File : ' . $this->getFile() . '</p><p>Code : ' . $this->getCode() . '</p><p>Line : ' . $this->getLine() . '</p>';
            }
            header('Location:' . $URL);
        }
    }

    /**
     * 获取输出信息
     */
    public function getMSG() {
        return $this->message;
    }

}

?>
