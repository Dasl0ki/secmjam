<?php
/**
 * Created by PhpStorm.
 * User: uyve7bo
 * Date: 01.04.2016
 * Time: 09:54
 */
forceSSL();
require_once("config/config.php");
require_once("config/db_cnx.php");
require 'config/setup.php';
require 'config/functions.php';
session_start();
if($_SESSION["user"]["id"] == "1") {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

$smarty = new Smarty_mjam();
$smarty->assign('current_site', substr($_SERVER['SCRIPT_NAME'],1));
$smarty->assign('countUnlockedOrders', count(getUnlockedOrders()));
$smarty->assign('success', FALSE);
$smarty->assign('error', FALSE);

if(isset($_SESSION["user"])) {
    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);
    if($page == 'change') {
        $user = array(
                'id' => $_SESSION['user']['id'],
                'pwd_old' => md5(filter_input(INPUT_POST, 'oldpwd', FILTER_SANITIZE_SPECIAL_CHARS)),
                'pwd_new' => md5(filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_SPECIAL_CHARS)),
                'pwd2' => md5(filter_input(INPUT_POST, 'pwd2', FILTER_SANITIZE_SPECIAL_CHARS))
        );
        if(changePWD($user) == TRUE) {
            $smarty->assign('success', TRUE);
            header("Refresh:2; url=user_settings.php");
        } else {
            $smarty->assign('error', TRUE);
        }
    }

    $smarty->display('changepwd.tpl');
} else {
    $smarty->display('timeout.tpl');
}
$mysqli->close();
?>