<?php

/**
 * sql部分生成器
 * 
 * @version 15
 * @author liuzilu
 * @package Frame
 */
class FrameSQL {

    /**
     * sql-limit生成
     * @param int $page 页数或偏移值 最小为1
     * @param int $max 页长
     * @return string sql
     */
    public function getLimit($page, $max) {
        $sql = '';
        $page+=0;
        $max+=0;
        if ($page > 0) {
            if ($max > 0) {
                $sql = ' LIMIT ' . ($page - 1) * $max . ',' . $max;
            } else {
                $sql = ' LIMIT ' . $page;
            }
        }
        return $sql;
    }

    /**
     * sql-order生成
     * @param string $sort 排序字段
     * @return string sql
     */
    public function getOrder($sort) {
        $sql = ' ORDER BY ' . $sort;
        return $sql;
    }

    /**
     * sql-desc生成
     * @param boolean $desc 倒序或正序
     * @return string sql
     */
    public function getDesc($desc) {
        $sql = '';
        if ($desc) {
            $sql = ' DESC';
        } else {
            $sql = ' ASC';
        }
        return $sql;
    }

    /**
     * 根据字段数据获取字段
     * @param array $fieldArr 字段数组
     * @param string $key 键值
     * @return string 字段
     */
    public function getSortField($fieldArr, $key) {
        $sortField = '';
        if (isset($fieldArr[$key])) {
            $sortField = $fieldArr[$key];
        } else {
            $sortField = $fieldArr[0];
        }
        return $sortField;
    }

}

?>
