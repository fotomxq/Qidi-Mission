<?php

/**
 * 图片查看类
 * 
 * @version 1
 * @author liuzilu
 * @package Frame
 * @filesource lib/frame/frame_cache.php
 */
class FrameImg extends FrameCache {

    /**
     * 文件源路径
     * @var string 
     */
    private $fileSrc;

    /**
     * 文件创建时间
     * @var string 
     */
    private $fileCTime;

    /**
     * 文件SHA1
     * @var string 
     */
    private $fileSha1;

    /**
     * 文件类型
     * @var string
     */
    private $fileType;

    /**
     * 初始化
     */
    public function __construct($src, $ctime, $sha1, $type) {
        parent::__construct('pictures');
        $this->fileSrc = $src;
        $this->fileCTime = $ctime;
        $this->fileSha1 = $sha1;
        $this->fileType = $type;
    }

    /**
     * 将图片输出到浏览器
     * @param int $sizeW 宽度
     * @param int $sizeH 高度
     */
    public function viewHeader($sizeW = 0, $sizeH = 0, $quality = 100) {
        $fileSrc = $this->viewFile($sizeW, $sizeH);
        if ($fileSrc) {
            header("Content-Type: image/jpeg");
            $img = @imagecreatefromjpeg($fileSrc);
            imagejpeg($img, NULL, $quality);
            imagedestroy($img);
        }
    }

    /**
     * 获取图像缓冲文件路径
     * <p>如果不存在则创建缓冲文件</p>
     * @param int $sizeW 宽度
     * @param int $sizeH 高度
     * @return string
     */
    public function viewFile($sizeW = 0, $sizeH = 0) {
        $re = '';
        $fileSrcCutDir = substr($this->fileCTime, 0, 6) . DS . substr($this->fileCTime, 6, 2);
        $fileSrcCut = $fileSrcCutDir . DS . substr($this->fileCTime, 8) . '_' . $this->fileSha1;
        $fileCache = $this->dir . DS . $fileSrcCut . '_' . $sizeW . '_' . $sizeH;
        if ($this->fileOperate->isFile($fileCache)) {
            $re = $fileCache;
        } else {
            if ($this->fileOperate->newDir($this->dir . DS . $fileSrcCutDir)) {
                $img = null;
                switch ($this->fileType) {
                    case 'jpg':
                        $img = @imagecreatefromjpeg($this->fileSrc);
                        break;
                    case 'jpeg':
                        $img = @imagecreatefromjpeg($this->fileSrc);
                        break;
                    case 'png':
                        $img = @imagecreatefrompng($this->fileSrc);
                        break;
                    case 'gif':
                        $img = @imagecreatefromgif($this->fileSrc);
                        break;
                    case 'wbmp':
                        $img = @imagecreatefromwbmp($this->fileSrc);
                        break;
                }
                if ($img) {
                    //获取图片尺寸并压缩
                    $srcW = imagesx($img);
                    $srcH = imagesy($img);
                    if ($srcW > 0 && $srcH > 0) {
                        $sizeW = $sizeW ? $sizeW : $srcW;
                        $sizeH = $sizeH ? $sizeH : $srcH;
                        $newSize = $this->getSizeZoom($srcW, $srcH, $sizeW, $sizeH);
                        if ($newSize[0] > 0 && $newSize[1] > 0) {
                            $imgP = imagecreatetruecolor($newSize[0], $newSize[1]);
                            if (imagecopyresampled($imgP, $img, 0, 0, 0, 0, $newSize[0], $newSize[1], $srcW, $srcH)) {
                                if (imagejpeg($imgP, $fileCache, 100)) {
                                    $re = $fileCache;
                                }
                            }
                            imagedestroy($imgP);
                        }
                    }
                    imagedestroy($img);
                }
            }
        }
        return $re;
    }

    /**
     * 获取压缩尺寸
     * @param int $srcW 源宽度
     * @param int $srcH 源高度
     * @param int $maxW 最大宽度
     * @param int $maxH 最大高度
     * @return array
     */
    private function getSizeZoom($srcW, $srcH, $maxW, $maxH) {
        $re = array();
        $cutW = $srcW - $maxW;
        $cutH = $srcH - $maxH;
        if ($cutW > 0 || $cutH > 0) {
            $p = 1;
            if ($cutW > $cutH) {
                $p = $cutW / $srcW;
            } else {
                $p = $cutH / $srcH;
            }
            $p = 1 - $p;
            $newW = floor($srcW * $p);
            $newH = floor($srcH * $p);
            if ($newW == 0) {
                $newW = 1;
            }
            if ($newH == 0) {
                $newH = 1;
            }
            $re = array($newW, $newH);
        } else {
            $re = array($srcW, $srcH);
        }
        return $re;
    }

}

?>
