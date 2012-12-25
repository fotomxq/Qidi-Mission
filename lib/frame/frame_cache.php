<?php

/**
 * 缓冲基类
 * 
 * @version 1
 * @author liuzilu
 * @package Frame
 */
abstract class FrameCache {

    /**
     * 操作的目录
     * @var string 
     */
    protected $dir;

    /**
     * 文件句柄
     * @var FrameFileOperate 
     */
    protected $fileOperate;

    /**
     * 初始化
     * @param string $dir 操作的目录
     */
    public function __construct($dir) {
        $this->dir = DIR_DATA . DS . 'cache' . DS . $dir;
        $this->fileOperate = new FrameFileOperate();
    }

    abstract public function viewFile();
}

?>
