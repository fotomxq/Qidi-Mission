<?php

/**
 * 获取IP地址
 * 
 * @version 12
 * @author liuzilu
 * @package Frame
 */
class FrameIP {

    /**
     * 获取IP地址
     * @return string
     */
    static public function addr() {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        elseif (isset($_SERVER["HTTP_CLIENT_IP"]))
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        elseif (isset($_SERVER["REMOTE_ADDR"]))
            $ip = $_SERVER["REMOTE_ADDR"];
        elseif (getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        elseif (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        elseif (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "0.0.0.0";
        return $ip;
    }

}

?>
