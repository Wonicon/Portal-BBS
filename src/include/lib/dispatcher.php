<?php
/**
 * Project: NJUOPEN/Portal-BBS
 * Contributor: WHZ
 * Filename: dispatcher.php
 */

/*
 申明了以下全局变量：
  $action:前端提交的动作；
  $params:前端提交的参数；
*/
if (!defined('BBS_ROOT')) exit(0);

$action = $_REQUEST['action'];
$params = array();
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    foreach ($_POST as $key=>$val) {
    //FIXED:通过$_POST获取数据，可能导致部分由GET提交的数据被遗漏，如$params['num']
        if ($key != 'action') {
            $params[$key]=$val;
        }
    }
} else if ($method == 'GET') {
    foreach ($_GET as $key=>$val) {
      if ($key != 'action') {
         $params[$key]=$val;
      }
    }
} else {
    echo 'Unexpected request!<br />';
    $action = 'invalid';
}

switch ($action) {
case 'register'  :
    require_once(BBS_ROOT.'/include/module/log.php');
    register_show_form($params);
    break;
case 'register2' :
    require_once(BBS_ROOT.'/include/module/log.php');
    register($params);
    require_once(BBS_ROOT.'/include/module/post.php');
    showPostList($params);
    break;
case 'login' :
    require_once(BBS_ROOT.'/include/module/log.php');
    login($params);
    require_once(BBS_ROOT.'/include/module/post.php');
    showPostList($params);
    break;
case 'logout' :
    require_once(BBS_ROOT.'/include/module/log.php');
    logout();
    require_once(BBS_ROOT.'/include/module/post.php');
    showPostList($params);
    break;
case 'change_info':
    require_once(BBS_ROOT.'/include/module/info.php');
    updateUserInfo($params);
    getUserInfo($params);
    break;
case 'change_avatar':
    require_once(BBS_ROOT.'/include/module/file.php');
    save_avatar();
case 'information':
    require_once(BBS_ROOT.'/include/module/info.php');
    getUserInfo($params);
    break;
case 'doPost' :
    require_once(BBS_ROOT.'/include/module/post.php');
    doPost($params);
    showPostList($params);
    break;
case 'doReply' :
    require_once(BBS_ROOT.'/include/module/post.php');
    doReply($params);
    showPostView($params);
    break;
case 'postView' :
    require_once(BBS_ROOT.'/include/module/post.php');
    showPostView($params);
    break;
    /*Direct uploading to BBS_USERFILE directory is currently unavailable
    case 'upload' :
      require_once(BBS_ROOT.'/include/module/file.php');
      upload_file();
      break;
    */
case 'invalid' :
    echo 'action is set invalid<br />';
    //break;
default :  //默认显示主页的贴子列表
    require_once(BBS_ROOT.'/include/module/post.php');
    showPostList($params);
    // TODO add more
}
?>
