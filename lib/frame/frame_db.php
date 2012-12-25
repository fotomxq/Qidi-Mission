<?php

/**
 * 数据库连接类
 * 
 * 仅进行创建连接，不实现具体行为
 * 
 * @version 3
 * @author liuzilu
 * @package Frame
 * @global string PDO_DSN
 * @global string PDO_USER
 * @global string PDO_PASS
 * @global string PDO_PARAM_SET
 * @global string PDO_PARAM_PERSISTENT
 */
class FrameDB {

    /**
     * 数据库连接句柄
     * @var PDO 
     */
    public static $PDO;

    /**
     * 连接数据库
     * @throws ErrorException 失败则抛出一个失败信息
     */
    public static function linkDB() {
        try {
            FrameDB::$PDO = new PDO(PDO_DSN, PDO_USER, PDO_PASS, array('PDO::MYSQL_ATTR_INIT_COMMAND' => PDO_PARAM_SET, 'PDO::ATTR_PERSISTENT' => PDO_PARAM_PERSISTENT));
            FrameDB::$PDO->exec(PDO_PARAM_SET);
        } catch (PDOException $e) {
            throw new FrameError('PDO');
        }
    }

}

?>
