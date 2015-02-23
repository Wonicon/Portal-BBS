<?php
/**
 * Project: NJUOPEN/Portal-BBS
 * Contributor:WHZ
 * Filename: log.php
 */

//参考:blog.csdn.net/sysprogram/article/details/21107041
function login($params) {
    //print_r($params);
    $username = $params['username'];
    $password = sha1($params['password']);
    //连接数据库
    require_once(BBS_ROOT.'/include/module/SQL.php');
    $user = new SQL_User;
    //认证
    $user_info = $user->getInfOfUserByName($username);
    if ( !empty($user_info) && $user_info['Name'] == $username && $user_info['Code'] == $password) { //登录成功
    $_SESSION['Name']  = $user_info['Name'];
    $_SESSION['SysID'] = $user_info['SysID'];
    echo 'Login success<br />';
    // 记录头像路径
    if (file_exists(BBS_WEB_ROOT.'/userfile/'.$user_info['Picture']))
    {
        $_SESSION['avatar'] = BBS_WEB_ROOT.'/userfile/'.$user_info['Picture'];
    }
    else
    {
        $_SESSION['avatar'] = BBS_WEB_ROOT.'/userfile/default-avatar';
    }

    //exit;
    } else {
        echo("Login failed<br />");
    }
    loadUI('general');
	if (isset($_SESSION['SysID'])) loadUI('editor');
}

/**
 * 登出
 */
function logout() {
    unset($_SESSION['SysID']);
    unset($_SESSION['Name']);
    echo "Log out success<br />";
    loadUI('general');
	if (isset($_SESSION['SysID'])) loadUI('editor');
}


function register_show_form() {
	loadUI('register');
}

function register($params) {
	loadUI('general');
    $email = $params['email'];
    $name = $params['username'];
    $pw = $params['password'];
    $repw = $params['repassword'];
    if ($email=='' || $name=='' || $pw=='' || $repw=='') return;
    if ($pw == $repw) {
        require_once(BBS_ROOT."/include/module/SQL.php");
        $pw = sha1($pw);
        $newUser = new SQL_User;
        $newUser->addUser($name, $pw, NULL, 1, 0, $email, 0, 18);
        // default avatar
        $all_info = $newUser->getInfOfUserByName($name);
        $newUser->resetUserPicture($all_info['SysID'], 'default-avatar');
        echo "User Registered.<br />";
    } else {
        echo "password not match<br/>";
    }
}
?>
