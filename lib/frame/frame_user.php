<?php

/**
 * 用户操作类
 * 
 * @version 1
 * @author liuzilu
 * @package Frame
 * @global int $variable
 * @filesource lib/frame/frame_sql.php
 */
class FrameUser {

    /**
     * 表名
     * @var string 
     */
    private $tableName;

    /**
     * 初始化
     */
    public function __construct() {
        $this->tableName = DB_T . 'user';
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * 查看用户列表
     * @param int $page 页数
     * @param int $max 页长
     * @param int $sort 排序字段数组键值
     * @param int $desc 排序方式
     * @return array
     */
    public function viewList($page = 1, $max = 15, $sort = 0, $desc = 1) {
        $re = array();
        $frameSQL = new FrameSQL();
        $fieldArr = array('ur_id', 'ur_username', 'ur_name', 'ur_lastip', 'ur_lasttime', 'ur_power');
        $sql = 'SELECT ur_id,ur_username,ur_name,ur_session,ur_lastip,ur_lasttime,ur_power FROM ' . $this->tableName . $frameSQL->getOrder($frameSQL->getSortField($fieldArr, $sort)) . $frameSQL->getDesc($desc) . $frameSQL->getLimit($page, $max);
        $db = FrameDB::$PDO->prepare($sql);
        if ($db->execute()) {
            $re = $db->fetchAll(PDO::FETCH_ASSOC);
        }
        return $re;
    }

    /**
     * 检查用户名和密码是否匹配
     * @param string $user 用户名
     * @param string $pass 密码
     * @param boolean $loginOn 检测完后登录该用户
     * @return int 用户ID
     */
    public function check($user, $pass, $loginOn = true) {
        $re = 0;
        $passSha1 = sha1($pass);
        $sql = 'SELECT ur_id FROM ' . $this->tableName . ' WHERE ur_username = ? and ur_password = ?';
        $db = FrameDB::$PDO->prepare($sql);
        $db->bindParam(1, $user, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        $db->bindParam(2, $passSha1, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        if ($db->execute() == true) {
            $re = $db->fetchColumn();
            if ($loginOn == true) {
                $this->loginIn($re);
            }
        }
        return $re;
    }

    /**
     * 登录用户
     * @param int $urID 用户ID
     * @return boolean
     */
    public function loginIn($urID) {
        $re = false;
        if (isset($_SESSION['user']) == false) {
            $sql = 'SELECT ur_name,ur_power FROM ' . $this->tableName . ' WHERE ur_id = ?';
            $db = FrameDB::$PDO->prepare($sql);
            $db->bindParam(1, $urID, PDO::PARAM_INT);
            if ($db->execute() == true) {
                $res = $db->fetch(PDO::FETCH_ASSOC);
                if (isset($res['ur_name'])) {
                    $time = date('YmdHis');
                    $sessionIDSha1 = sha1(session_id());
                    $ip = IP_ID;
                    $sqlUpdate = 'UPDATE ' . $this->tableName . ' SET ur_session = ?,ur_lastip = ?,ur_lasttime = ? WHERE ur_id = ?';
                    $dbUpdate = FrameDB::$PDO->prepare($sqlUpdate);
                    $dbUpdate->bindParam(1, $sessionIDSha1, PDO::PARAM_STR);
                    $dbUpdate->bindParam(2, $ip, PDO::PARAM_INT);
                    $dbUpdate->bindParam(3, $time, PDO::PARAM_STR);
                    $dbUpdate->bindParam(4, $urID, PDO::PARAM_INT);
                    if ($dbUpdate->execute() == true) {
                        $_SESSION['user']['id'] = $urID;
                        $_SESSION['user']['name'] = $res['ur_name'];
                        $_SESSION['user']['power'] = $res['ur_power'];
                        $re = true;
                    }
                }
            }
        }
        return $re;
    }

    /**
     * 检查用户是否登录
     * @return boolean
     */
    public function loginCheck() {
        $re = false;
        if (isset($_SESSION['user']['id'])) {
            if ($_SESSION['user']['id'] > 0) {
                $re = true;
            }
        }
        return $re;
    }

    /**
     * 登出用户
     */
    public function loginOut() {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
    }

    /**
     * 添加新的用户
     * @param string $username 用户名
     * @param string $password 密码
     * @param string $name 名字
     * @param string $power 权限
     * @return int 用户ID
     */
    public function add($username, $password, $name, $power) {
        $re = 0;
        $sql = 'SELECT ur_id FROM ' . $this->tableName . ' WHERE ur_username = ?';
        $db = FrameDB::$PDO->prepare($sql);
        $db->bindParam(1, $username, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        if ($db->execute() == true) {
            $res = $db->fetchColumn();
            if ($res < 1) {
                $passSha1 = sha1($password);
                $sqlInsert = 'INSERT INTO ' . $this->tableName . '(ur_id,ur_username,ur_password,ur_name,ur_session,ur_lastip,ur_lasttime,ur_power) VALUES(NULL,?,?,?,\'0\',\'0\',\'0\',?)';
                $dbInsert = FrameDB::$PDO->prepare($sqlInsert);
                $dbInsert->bindParam(1, $username, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
                $dbInsert->bindParam(2, $passSha1, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
                $dbInsert->bindParam(3, $name, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
                $dbInsert->bindParam(4, $power, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
                if ($dbInsert->execute() == true) {
                    $re = FrameDB::$PDO->lastInsertId();
                }
            }
        }
        return $re;
    }

    /**
     * 编辑用户
     * @param int $urID 用户ID
     * @param string $password 密码
     * @param string $name 名字
     * @param string $power 权限
     * @return boolean
     */
    public function edit($urID, $password, $name, $power) {
        $re = false;
        $passSha1 = sha1($password);
        $sqlUpdate = 'UPDATE ' . $this->tableName . ' SET ur_password = ?,ur_name = ?,ur_power = ? WHERE ur_id = ?';
        $dbUpdate = FrameDB::$PDO->prepare($sqlUpdate);
        $dbUpdate->bindParam(1, $passSha1, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        $dbUpdate->bindParam(2, $name, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        $dbUpdate->bindParam(3, $power, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        $dbUpdate->bindParam(4, $urID, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
        if ($dbUpdate->execute() == true) {
            $re = true;
            if ($urID == $_SESSION['user']['id']) {
                $this->loginOut();
            }
        }
        return $re;
    }

    /**
     * 删除用户
     * @param int $urID 用户ID
     * @return boolean
     */
    public function del($urID) {
        $re = false;
        if (isset($_SESSION['user']['id'])) {
            if ($urID != $_SESSION['user']['id']) {
                $sqlDelete = 'DELETE FROM ' . $this->tableName . ' WHERE ur_id = ?';
                $dbDelete = FrameDB::$PDO->prepare($sqlDelete);
                $dbDelete->bindParam(1, $urID, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
                if ($dbDelete->execute() == true) {
                    $re = true;
                }
            }
        }
        return $re;
    }

}

?>
