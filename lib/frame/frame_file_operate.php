<?php

/**
 * 文件操作类
 * 
 * @version 6
 * @author liuzilu
 * @package Frame
 * @global string DS
 */
class FrameFileOperate {

    /**
     * 判断是否为文件
     * @param string $src 路径
     * @return boolean 
     */
    static public function isFile($src) {
        return is_file($src);
    }

    /**
     * 以文件形式读取路径
     * @param string $src 路径
     * @return string/boolean 文件内容 / 失败
     */
    static public function readFile($src) {
        return file_get_contents($src);
    }

    /**
     * 以文件形式写入路径
     * @param string $src 路径
     * @param string $data 写入数据
     * @param boolean $append 是否为插入信息
     * @return boolean 执行是否成功
     */
    static public function editFile($src, $data, $append = false) {
        $res = null;
        if ($append) {
            $res = file_put_contents($src, $data, FILE_APPEND);
        } else {
            $res = file_put_contents($src, $data);
        }
        return $res;
    }

    /**
     * 剪切或修改名称
     * 剪切或者修改文件或文件夹的路径或名称
     * @param string $src 源路径
     * @param string $dest 目标路径
     * @return boolean 
     */
    static public function cutF($src, $dest) {
        return rename($src, $dest);
    }

    /**
     * 复制文件
     * @param string $src 源文件路径
     * @param string $dest 目标路径
     * @return boolean 
     */
    static public function copyFile($src, $dest) {
        return copy($src, $dest);
    }

    /**
     * 移动上传文件
     * @param string $src 上传文件地址
     * @param string $dest 目标路径
     * @return boolean 
     */
    static public function uploadFile($src, $dest) {
        return move_uploaded_file($src, $dest);
    }

    /**
     * 删除文件
     * @param string $src 路径
     * @return boolean 
     */
    static public function deleteFile($src) {
        $re = true;
        if (is_file($src)) {
            $re = unlink($src);
        }
        return $re;
    }

    /**
     * 判断是否为目录
     * @param string $src 路径
     * @return boolean 
     */
    static public function isDir($src) {
        return is_dir($src);
    }

    /**
     * 搜索目录
     * @param string $src 搜索的路径 eg: C:\a
     * @param string $search 搜索的内容 eg: *
     * @param int $flags 参数 eg: GLOB_ONLYDIR
     * @return array/boolean 数据数组 / 失败 
     */
    static public function listDir($src, $search = '', $flags = null) {
        $res = null;
        $src = $src . DS . $search;
        if ($flags) {
            $res = glob($src, $flags);
        } else {
            $res = glob($src);
        }
        return $res;
    }

    /**
     * 创建目录
     * @param string $src 路径
     * @return boolean 
     */
    static public function newDir($src) {
        $re = false;
        if (FrameFileOperate::isDir($src)) {
            $re = true;
        } else {
            $re = mkdir($src, 0777, true);
        }
        return $re;
    }

    /**
     * 复制目录
     * @param string $src 源目录路径
     * @param string $dest 目标路径
     * @return boolean
     */
    static public function copyDir($src, $dest) {
        $re = true;
        if (FrameFileOperate::newDir($dest) == true) {
            $dirList = FrameFileOperate::listDir($src, '*');
            if ($dirList) {
                foreach ($dirList as $v) {
                    $vSrc = $src . DS . $v;
                    $vDest = $dest . DS . $v;
                    if (FrameFileOperate::isDir($vSrc) == true) {
                        if (FrameFileOperate::copyDir($vSrc, $vDest) == false) {
                            $re = false;
                            break;
                        }
                    }
                    if (FrameFileOperate::isFile($vSrc)) {
                        if (FrameFileOperate::copyFile($vSrc, $vDest) == false) {
                            $re = false;
                            break;
                        }
                    }
                }
            }
        } else {
            $re = false;
        }
        return $re;
    }

    /**
     * 删除目录
     * @param string $src 路径
     * @return boolean 
     */
    static public function deleteDir($src) {
        $re = true;
        if (FrameFileOperate::isDir($src) == true) {
            $dirList = FrameFileOperate::listDir($src, '*');
            if ($dirList) {
                foreach ($dirList as $v) {
                    $vSrc = $src . DS . $v;
                    if (FrameFileOperate::isDir($vSrc) == true) {
                        if (FrameFileOperate::deleteDir($vSrc) == false) {
                            $re = false;
                            break;
                        }
                    }
                    if (FrameFileOperate::isFile($vSrc) == true) {
                        if (FrameFileOperate::deleteFile($vSrc) == false) {
                            $re = false;
                            break;
                        }
                    }
                }
            } else {
                if (rmdir($src) == false) {
                    $re = false;
                }
            }
        } else {
            $re = false;
        }
        return $re;
    }

}

?>
