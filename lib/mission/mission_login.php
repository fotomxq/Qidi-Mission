<?php

/**
 * 登陆类
 * @package Mission
 * @version 1
 * @author liuzilu
 */
class MissionLogin {

    /**
     * 判断是否登陆
     * @return boolean
     */
    public function check() {
        $re = false;
        if ($_SESSION['login'] == 1) {
            $re = true;
        }
        return $re;
    }

    /**
     * 登陆检测
     * @param string $password 密码
     * @return boolean
     */
    public function login($password) {
        $re = false;
        FrameConfig::loadFile('login');
        if (isset(FrameConfig::$configArr['login']['Login']['password'])) {
            if ($password == FrameConfig::$configArr['login']['Login']['password']) {
                $_SESSION['login'] = 1;
                $re = true;
            }
        }
        return $re;
    }

    /**
     * 退出登陆
     */
    public function logout() {
        $_SESSION['login'] = 0;
    }

}

?>
