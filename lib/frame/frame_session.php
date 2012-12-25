<?php

/**
 * session
 * 
 * @version 8
 * @author liuzilu
 * @package Frame
 * @global int IP_ID 客户IP ID
 */
class FrameSession {

    /**
     * 开启Session
     */
    public function start() {
        try {
            if (session_status() == PHP_SESSION_DISABLED) {
                session_start();
            }
        } catch (Exception $e) {
            
        }
    }

    /**
     * 检查Session有效性
     * @return boolean
     */
    public function check() {
        $re = false;
        if (defined('IP_ID')) {
            if ($_SESSION['ip_id'] == IP_ID) {
                $re = true;
            }
        }
        return $re;
    }

    /**
     * 设定SessionID值
     * @param string $session_id sessionID MD5值
     */
    public function setID($sessionID) {
        session_id($sessionID);
    }

}

?>
