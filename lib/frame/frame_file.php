<?php

/**
 * 文件操作类
 * <p>查询文件和上传新的文件操作</p>
 * @version 16
 * @author liuzilu
 * @package Frame
 * @filesource lib/frame/frame_file_operate.php
 * @filesource data/configs/file.ini
 */
class FrameFile {

    /**
     * 本地数据目录
     * @var string
     */
    private $dataDir;

    /**
     * 文件操作类
     * @var FrameFileOperate 
     */
    private $fileOperate;

    /**
     * 文件表名
     * @var string
     */
    private $filesTableName;

    /**
     * 引用表名
     * @var type 
     */
    private $filesQuoteTableName;

    /**
     * 应用名称
     * @var string 
     */
    private $app;

    /**
     * 初始化
     */
    public function __construct($app) {
        $this->dataDir = DIR_DATA . DS . 'files';
        $this->fileOperate = new FrameFileOperate();
        $this->filesTableName = DB_T . 'files';
        $this->filesQuoteTableName = DB_T . 'files_quote';
        $this->app = $app;
        FrameConfig::loadFile('file');
    }

    /**
     * 查询文件
     * @param int $fqID ID
     * @return array
     */
    public function view($fqID) {
        $re = array();
        $sqlQuote = 'SELECT fq_id,fs_id,fq_name,fq_views,fq_app,fq_tag FROM ' . $this->filesQuoteTableName . ' WHERE fq_id = ? and fq_app = ?';
        $dbQuote = FrameDB::$PDO->prepare($sqlQuote);
        $dbQuote->bindParam(1, $fqID, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
        $dbQuote->bindParam(2, $this->app, PDO::PARAM_STR);
        if ($dbQuote->execute()) {
            $resQuote = $dbQuote->fetch(PDO::FETCH_ASSOC);
            if (isset($resQuote['fq_id'])) {
                $sqlFiles = 'SELECT fs_id,fs_sha1,fs_ctime,fs_size,fs_type,fs_save,fs_save_value FROM ' . $this->filesTableName . ' WHERE fs_id = ?';
                $dbFiles = FrameDB::$PDO->prepare($sqlFiles);
                $dbFiles->bindParam(1, $resQuote['fs_id'], PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
                if ($dbFiles->execute()) {
                    $resFiles = $dbFiles->fetch(PDO::FETCH_ASSOC);
                    $re['id'] = $resQuote['fq_id'];
                    $re['sha1'] = $resFiles['fs_sha1'];
                    $re['ctime'] = $resFiles['fs_ctime'];
                    $re['size'] = $resFiles['fs_size'];
                    $re['type'] = $resFiles['fs_type'];
                    $re['name'] = $resQuote['fq_name'];
                    $re['views'] = $resQuote['fq_views'];
                    $re['tag'] = $resQuote['fq_tag'];
                    $re['app'] = $resQuote['fq_app'];
                    $re['src'] = $this->getSrc($re['ctime'], $re['sha1'], $resFiles['fs_save'], $resFiles['fs_save_value']);
                }
            }
        }
        return $re;
    }

    /**
     * 上传新的文件
     * @param uploadfile $uploadFile 上传的文件
     * @param string $fileName 自定义文件名
     * @param string $fileTags 自定义标签
     * @param string $save 保存点
     * @param string $saveValue 保存点参数值
     * @return int 文件ID
     */
    public function upload($uploadFile, $fileName = '', $fileTags = '', $save = 'default', $saveValue = '') {
        $re = 0;
        if (FrameConfig::$configArr['file']['upload']['UploadOn'] == true && $uploadFile['error'] == UPLOAD_ERR_OK && is_uploaded_file($uploadFile['tmp_name'])) {
            //检查文件大小
            $fileSize = $uploadFile['size'];
            if (FrameConfig::$configArr['file']['upload']['UploadMin']) {
                if ($fileSize < FrameConfig::$configArr['file']['upload']['UploadMin']) {
                    return $re;
                }
            }
            if (FrameConfig::$configArr['file']['upload']['UploadMax']) {
                if ($fileSize > FrameConfig::$configArr['file']['upload']['UploadMax']) {
                    return $re;
                }
            }
            //检查文件格式黑名单
            $fileType = $this->getType($uploadFile['name']);
            if (stripos(FrameConfig::$configArr['file']['upload']['TypeBan'], $fileType)) {
                return $re;
            }
            //检查文件是否存在
            $fileSha1 = sha1_file($uploadFile['tmp_name']);
            $sqlFiles = 'SELECT fs_id FROM ' . $this->filesTableName . ' WHERE fs_sha1 = ?';
            $dbFiles = FrameDB::$PDO->prepare($sqlFiles);
            $dbFiles->bindParam(1, $fileSha1, PDO::PARAM_STR);
            if ($dbFiles->execute()) {
                $resFiles = $dbFiles->fetchColumn();
                if ($resFiles < 1) {
                    //生成时间
                    $timeYm = date('Ym');
                    $timeD = date('d');
                    $timeHis = date('His');
                    //生成目录
                    $dir = $this->dataDir . DS . $timeYm . DS . $timeD;
                    if ($this->fileOperate->newDir($dir)) {
                        $fileLocation = $dir . DS . $fileSha1;
                        //保存文件
                        if ($this->fileOperate->uploadFile($uploadFile['tmp_name'], $fileLocation)) {
                            $fileCTime = $timeYm . $timeD . $timeHis;
                            $sqlFiles = 'INSERT INTO ' . $this->filesTableName . '(fs_id,fs_sha1,fs_ctime,fs_size,fs_type,fs_save,fs_save_value) VALUES(NULL,?,?,?,?,?,?)';
                            $dbFiles = FrameDB::$PDO->prepare($sqlFiles);
                            $dbFiles->bindParam(1, $fileSha1, PDO::PARAM_STR);
                            $dbFiles->bindParam(2, $fileCTime, PDO::PARAM_STR);
                            $dbFiles->bindParam(3, $fileSize, PDO::PARAM_STR);
                            $dbFiles->bindParam(4, $fileType, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
                            $dbFiles->bindParam(5, $save, PDO::PARAM_STR);
                            $dbFiles->bindParam(6, $saveValue, PDO::PARAM_STR);
                            if ($dbFiles->execute()) {
                                $resFiles = FrameDB::$PDO->lastInsertId();
                            } else {
                                $this->deleteFile($fileLocation);
                            }
                        }
                    }
                }
                if ($resFiles > 0) {
                    //添加新的引用
                    $fileName = $fileName ? $fileName : substr($uploadFile['name'], 0, strpos($uploadFile['name'], '.'));
                    $sqlQuote = 'INSERT INTO ' . $this->filesQuoteTableName . '(fq_id,fs_id,fq_name,fq_views,fq_app,fq_tag) VALUES(NULL,?,?,1,?,?)';
                    $dbQuote = FrameDB::$PDO->prepare($sqlQuote);
                    $dbQuote->bindParam(1, $resFiles, PDO::PARAM_INT);
                    $dbQuote->bindParam(2, $fileName, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
                    $dbQuote->bindParam(3, $this->app, PDO::PARAM_STR);
                    $dbQuote->bindParam(4, $fileTags, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
                    if ($dbQuote->execute()) {
                        $re = FrameDB::$PDO->lastInsertId();
                    }
                }
            }
        }
        return $re;
    }

    /**
     * 编辑文件信息
     * @param int $fqID 文件ID
     * @param string $name 名称
     * @param string $tag 标签
     * @return boolean
     */
    public function edit($fqID, $name, $tag) {
        $re = false;
        $sql = 'UPDATE ' . $this->filesQuoteTableName . ' SET fq_name = ?,fq_tag = ? WHERE fq_id = ? and fq_app = ?';
        $db = FrameDB::$PDO->prepare($sql);
        $db->bindParam(1, $name, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        $db->bindParam(2, $tag, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        $db->bindParam(3, $fqID, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
        $db->bindParam(4, $this->app, PDO::PARAM_STR);
        $re = $db->execute();
        return $re;
    }

    /**
     * 自增文件访问次数
     * @param int $fqID 文件ID
     * @return boolean
     */
    public function editViewsAdd($fqID) {
        $re = false;
        $sqlQuote = 'SELECT fq_views FROM ' . $this->filesQuoteTableName . ' WHERE fq_id = ? and fq_app = ?';
        $dbQuote = FrameDB::$PDO->prepare($sqlQuote);
        $dbQuote->bindParam(1, $fqID, PDO::PARAM_INT);
        $dbQuote->bindParam(2, $this->app, PDO::PARAM_STR);
        if ($dbQuote->execute() == true) {
            $resViews = $dbQuote->fetchColumn();
            if ($resViews > 0) {
                $views = $resViews + 1;
                $sqlUpdate = 'UPDATE ' . $this->filesQuoteTableName . ' SET fq_views = ? WHERE fq_id = ? and fq_app = ?';
                $dbUpdate = FrameDB::$PDO->prepare($sqlUpdate);
                $dbUpdate->bindParam(1, $views, PDO::PARAM_INT);
                $dbUpdate->bindParam(2, $fqID, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
                $dbUpdate->bindParam(3, $this->app, PDO::PARAM_STR);
                $re = $dbUpdate->execute();
            }
        }
        return $re;
    }

    /**
     * 删除文件
     * @param int $fqID 文件引用ID
     * @return boolean
     */
    public function del($fqID) {
        $re = false;
        $sqlQuote = 'SELECT fs_id FROM ' . $this->filesQuoteTableName . ' WHERE fq_id = ? and fq_app = ?';
        $dbQuote = FrameDB::$PDO->prepare($sqlQuote);
        $dbQuote->bindParam(1, $fqID, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
        $dbQuote->bindParam(2, $this->app, PDO::PARAM_STR);
        if ($dbQuote->execute()) {
            $resFilesID = $dbQuote->fetchColumn();
            if ($resFilesID > 0) {
                $sqlQuoteAll = 'SELECT COUNT(fq_id) FROM ' . $this->filesQuoteTableName . ' WHERE fs_id = ?';
                $dbQuoteAll = FrameDB::$PDO->prepare($sqlQuoteAll);
                $dbQuoteAll->bindParam(1, $resFilesID, PDO::PARAM_INT);
                if ($dbQuoteAll->execute()) {
                    $resQuote = $dbQuoteAll->fetchColumn();
                    try {
                        FrameDB::$PDO->beginTransaction();
                        if ($resQuote < 2) {
                            $fileArr = $this->view($fqID);
                            $sqlDeleteFiles = 'DELETE FROM ' . $this->filesTableName . ' WHERE fs_id = ?';
                            $dbDeleteFiles = FrameDB::$PDO->prepare($sqlDeleteFiles);
                            $dbDeleteFiles->bindParam(1, $resFilesID, PDO::PARAM_INT);
                            if ($dbDeleteFiles->execute() == true) {
                                if ($this->deleteFile($fileArr['src']) == false) {
                                    return $re;
                                }
                            } else {
                                return $re;
                            }
                        }
                        $sqlDeleteQuote = 'DELETE FROM ' . $this->filesQuoteTableName . ' WHERE fq_id = ? and fq_app = ?';
                        $dbDeleteQuote = FrameDB::$PDO->prepare($sqlDeleteQuote);
                        $dbDeleteQuote->bindParam(1, $fqID, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
                        $dbDeleteQuote->bindParam(2, $this->app, PDO::PARAM_STR);
                        if ($dbDeleteQuote->execute()) {
                            $re = true;
                        }
                        FrameDB::$PDO->commit();
                    } catch (PDOException $e) {
                        FrameDB::$PDO->rollBack();
                        $re = false;
                    }
                }
            } else {
                $re = true;
            }
        }
        return $re;
    }

    /**
     * 获取文件格式
     * @param string $src 文件路径
     * @return string
     */
    public function getType($src) {
        $re = strtolower(substr(strstr($src, '.'), 1));
        return $re;
    }

    /**
     * 删除文件资源
     * @param string $src 文件路径
     * @return boolean
     */
    private function deleteFile($src) {
        return $this->fileOperate->deleteFile($src);
    }

    /**
     * 获取文件路径
     * @param string $ctime 创建时间
     * @param string $sha1 SHA1
     * @param string $save 保存标记
     * @param string $saveValue 保存标记值
     * @return string 文件所在路径
     */
    private function getSrc($ctime, $sha1, $save, $saveValue = '') {
        $re = '';
        switch ($save) {
            case 'default':
                $re = $this->dataDir . DS . substr($ctime, 0, 6) . DS . substr($ctime, 6, 2) . DS . $sha1;
                break;
        }
        return $re;
    }

}

?>
