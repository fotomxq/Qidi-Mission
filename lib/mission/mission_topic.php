<?php

/**
 * 题目操作类
 * @package Mission
 * @version 1
 * @author liuzilu
 */
class MissionTopic extends MissionPost {

    /**
     * 初始化
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取题目列表
     * @param int $examID 题库ID
     * @param string $content 搜索的题目内容
     * @return array
     */
    public function viewList($examID, $content = '') {
        $where = 'post_parent = ' . $examID;
        if ($content != '') {
            $where .= ' AND post_content = \'%' . $content . '%\'';
        }
        return parent::view($where, 0, 0, 0);
    }

    /**
     * 添加新的题目
     * @param int $examID 题库ID
     * @param string $content 内容
     * @param int $fraction 分值
     * @param string $answer 答案
     * @return boolean
     */
    public function add($examID, $content, $fraction, $answer) {
        $re = 0;
        $sql = 'INSERT INTO ' . $this->tableName . '(post_data,post_name,post_fraction,post_content,post_parent) VALUES(NOW(),?,?,?,?)';
        $db = FrameDB::$PDO->prepare($sql);
        $db->bindParam(1, $answer, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        $db->bindParam(2, $fraction, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
        $db->bindParam(3, $content, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        $db->bindParam(4, $examID, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
        if ($db->execute()) {
            $re = FrameDB::$PDO->lastInsertId();
        }
        return $re;
    }

    /**
     * 编辑题目
     * @param int $topicID 题目ID
     * @param int $sort 排序
     * @param string $content 内容
     * @param int $fraction 分值
     * @param string $answer 答案
     * @return boolean
     */
    public function edit($topicID, $sort, $content, $fraction, $answer) {
        $re = false;
        $sql = 'UPDATE ' . $this->tableName . ' SET post_sort = ?,post_content = ?,post_fraction = ?,post_name = ? WHERE id = ?';
        $db = FrameDB::$PDO->prepare($sql);
        $db->bindParam(1, $sort, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
        $db->bindParam(2, $content, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        $db->bindParam(3, $fraction, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
        $db->bindParam(4, $answer, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
        $db->bindParam(5, $topicID, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
        if ($db->execute()) {
            $re = true;
        }
        return $re;
    }

}

?>
