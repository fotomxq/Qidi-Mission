<?php

/**
 * 题库类
 * @package Mission
 * @version 1
 * @author liuzilu
 */
class MissionExam extends MissionPost {

    /**
     * 初始化
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 查看列表
     * @param string $name 查询名称
     * @param boolean $desc 排序方式
     * @return array 数据数组
     */
    public function viewList($name = '', $desc = false) {
        $where = 'post_parent = 0';
        if($name){
            $where = ' AND post_name = \'%' . $name . '%\'';
        }
        return parent::view($where, $desc, 0, 0);
    }

    /**
     * 添加新的题库
     * @param string $name 题库名称
     * @return boolean
     */
    public function add($name) {
        $re = 0;
        $sql = 'INSERT INTO ' . $this->tableName . '(post_data,post_name) VALUES(NOW(),?)';
        $db = FrameDB::$PDO->prepare($sql);
        $db->bindParam(1, $name, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        if ($db->execute()) {
            $re = FrameDB::$PDO->lastInsertId();
        }
        return $re;
    }

    /**
     * 编辑题库
     * @param int $id ID
     * @param int $sort 排序
     * @param string $name 名称
     * @return boolean
     */
    public function edit($id, $sort, $name) {
        return parent::edit($id, array('post_sort' => $sort, 'post_name' => $name));
    }

}

?>
