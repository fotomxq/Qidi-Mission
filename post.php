<?php

/**
 * post值
 * @package Mission
 * @version 1
 * @author liuzilu
 */
require('frame.php');
require(DIR_LIB . DS . 'mission' . DS . 'mission_login.php');
require(DIR_LIB . DS . 'mission' . DS . 'mission_post.php');
require(DIR_LIB . DS . 'mission' . DS . 'mission_exam.php');
require(DIR_LIB . DS . 'mission' . DS . 'mission_topic.php');
$missionLogin = new MissionLogin();
if ($missionLogin->check() == true) {
    if ($_GET['type'] == 'exam') {
        $missionExam = new MissionExam();
        if ($_GET['action'] == 'list') {
            $_POST['name'] = $_POST['name'] ? $_POST['name'] : '';
            $_POST['desc'] = $_POST['desc'] ? $_POST['desc'] : '0';
            $res = $missionExam->viewList($_POST['name'], $_POST['desc']);
            echo json_encode($res);
        }
        if ($_GET['action'] == 'add') {
            if ($_POST['name']) {
                echo $missionExam->add($_POST['name']);
            }
        }
        if ($_GET['action'] == 'edit') {
            if ($_POST['id'] > 0 && $_POST['name']) {
                echo $missionExam->edit($_POST['id'], $_POST['sort'], $_POST['name']);
            }
        }
        if ($_GET['action'] == 'del') {
            if ($_POST['id'] > 0) {
                echo $missionExam->del($_POST['id']);
            }
        }
        if ($_GET['action'] == 'sort') {
            echo $missionExam->sort($_POST['sort']);
        }
    } elseif ($_GET['type'] == "topic") {
        $missionTopic = new MissionTopic();
        if ($_GET['action'] == 'list') {
            if ($_POST['examid'] > 0) {
                $_POST['content'] = $_POST['content'] ? $_POST['content'] : '';
                $res = $missionTopic->viewList($_POST['examid'], $_POST['content']);
                echo json_encode($res);
            }
        }
        if ($_GET['action'] == 'add') {
            if ($_POST['examid'] > 0 && $_POST['content']) {
                echo $missionTopic->add($_POST['examid'], $_POST['content'], $_POST['fraction'], $_POST['answer']);
            }
        }
        if ($_GET['action'] == 'edit') {
            if ($_POST['id'] > 0 && $_POST['content']) {
                echo $missionTopic->edit($_POST['id'], $_POST['sort'], $_POST['content'], $_POST['fraction'], $_POST['answer']);
            }
        }
        if ($_GET['action'] == 'del') {
            if ($_POST['id'] > 0) {
                echo $missionTopic->del($_POST['id']);
            }
        }
        if ($_GET['action'] == 'sort') {
            echo $missionTopic->sort($_POST['sort']);
        }
    }
}
?>
