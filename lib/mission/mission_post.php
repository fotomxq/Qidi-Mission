<?php

/**
 * POST类
 * @package Mission
 * @version 1
 * @author liuzilu
 */
class MissionPost {

    /**
     * 表名
     * @var string 
     */
    protected $tableName;

    /**
     * 初始化
     */
    public function __construct() {
        $this->tableName = DB_T . 'post';
    }

    /**
     * 查看Post
     * @param string $where 条件
     * @param boolean $desc 排序方式
     * @param int $page 页数
     * @param int $max 页长
     * @return array 数据数组
     */
    public function view($where = '1', $desc = true, $page = 1, $max = 10) {
        $re = array();
        $frameSQL = new FrameSQL();
        $sql = 'SELECT id,post_data,post_data_modified,post_fraction,post_content,post_static,post_sort,post_name,post_parent,ping_url,post_type,post_meta_type FROM ' . $this->tableName . ' WHERE ' . $where . ' ORDER BY post_sort,id' . $frameSQL->getDesc($desc) . $frameSQL->getLimit($page, $max);
        $db = FrameDB::$PDO->prepare($sql);
        if ($db->execute()) {
            $re = $db->fetchAll(PDO::FETCH_ASSOC);
        }
        return $re;
    }

    /**
     * 编辑post
     * @param int $id ID
     * @param array $arr 值数组
     * @return boolean
     */
    public function edit($id, $arr) {
        $re = false;
        $sql = 'UPDATE ' . $this->tableName . ' SET ';
        $setArr = array();
        foreach ($arr as $k => $v) {
            $sql .= $k . ' = :val_' . $k . ',';
            $setArr[':val_' . $k] = $v;
        }
        $sql = substr($sql, 0, strlen($sql) - 1);
        $sql .= ' WHERE id = :id';
        $db = FrameDB::$PDO->prepare($sql);
        foreach ($setArr as $k => $v) {
            $db->bindParam($k, $v, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        }
        $db->bindParam(':id', $id, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        if ($db->execute()) {
            $re = true;
        }
        return $re;
    }

    /**
     * 编辑排序
     * @param array $arr 修改数组
     * @return boolean
     */
    public function sort($arr) {
        $re = false;
        if (is_array($arr)) {
            $sql = 'UPDATE ' . $this->tableName . ' SET post_sort = :sort WHERE id = :id';
            $db = FrameDB::$PDO->prepare($sql);
            foreach ($arr as $k => $v) {
                $db->bindParam(':sort', $k, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
                $db->bindParam(':id', $v, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
                $db->execute();
            }
            $re = true;
        }
        return $re;
    }

    /**
     * 删除post
     * @param int $id ID
     * @return boolean
     */
    public function del($id) {
        $re = false;
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE id = :examid or post_parent = :examid';
        $db = FrameDB::$PDO->prepare($sql);
        $db->bindParam(':examid', $id, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
        if ($db->execute()) {
            $re = true;
        }
        return $re;
    }

}

?>
