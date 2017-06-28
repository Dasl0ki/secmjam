<?php
/**
 * Created by PhpStorm.
 * User: Loki
 * Date: 13.10.2015
 * Time: 08:01
 */
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
require 'config/functions.php';
require 'config/setup.php';
require_once("config/config.php");
require_once("config/db_cnx.php");
session_start();

$smarty = new Smarty_mjam();
$smarty->assign('current_site', substr($_SERVER['SCRIPT_NAME'],1));
$smarty->assign('countUnlockedOrders', count(getUnlockedOrders()));
$smarty->assign('success', FALSE);
$smarty->assign('error', FALSE);

if(isset($_SESSION["user"])) {
    echo '<meta http-equiv="refresh" content="1; URL=main.php" />' . "\n";
    die;
}
$verhalten = 0;
$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);

if(!isset($_SESSION["user"]) and $page != NULL) {
    $verhalten = 0;
}

if($page == "login") {
    $user = strtolower(filter_input(INPUT_POST, 'user', FILTER_SANITIZE_SPECIAL_CHARS));
    $pwd = md5(filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_SPECIAL_CHARS));

    if($pwd == "d41d8cd98f00b204e9800998ecf8427e") {
            $verhalten = 4; // 4 = Wrong Password given
    } else {
        $control = 0; // PWD Check Status, 0 = not checked
        $login = doLogin($user, $pwd);
        
        if($login != FALSE) {
            $control++;
        } else {
            $verhalten = 2;
        }
        
        if($control != 0) {
            $_SESSION["user"] = $login;
            $verhalten = 3; // 3 = Login
        } else {
            $verhalten = 2; // 2 = Login failed
        }

    }
}

if($verhalten == 0) {
    $smarty->assign('success', FALSE);
    $smarty->assign('error', FALSE);
}

if($verhalten == 1) {
    echo 'Ersteinstieg';
}

if($verhalten == 2) {
    $smarty->assign('error', TRUE);
}

if($verhalten == 3) {
    $smarty->assign('success', TRUE);
    header("Refresh:2; url=main.php");
}

if($verhalten == 4) {
    $smarty->assign('error', TRUE);
}

$smarty->display('index.tpl');
$mysqli->close();