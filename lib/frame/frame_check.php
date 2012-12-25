<?php

/**
 * 用户提交检查类
 * 
 * @version 1
 * @author liuzilu
 * @package Frame
 */
class FrameCheck {

    /**
     * 检测POST
     * @param array $post POST
     * @param string $check 检查字符
     * @return boolean
     */
    public function checkPost($post, $check) {
        $re = true;
        $checkArr = explode('|', $check);
        foreach ($checkArr as $k => $v) {
            switch ($v) {
                case 'int':
                    if (is_int($post[$k]) == false) {
                        $re = false;
                    }
                    break;
                case 'str':
                    if (is_string($post[$k]) == false) {
                        $re = false;
                    }
                    break;
            }
        }
        return $re;
    }

}

?>
