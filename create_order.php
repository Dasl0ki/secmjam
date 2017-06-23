<?php
require 'config/setup.php';
require 'config/functions.php';
require_once("config/config.php");
require_once("config/db_cnx.php");
header('Content-Type: text/html; charset=utf-8');
session_start();
if($_SESSION["user"]["id"] == "1") {
	ini_set("display_errors", 1);
	error_reporting(E_ALL | E_STRICT);
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}

$smarty = new Smarty_mjam();

if(isset($_SESSION["user"])) {
    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);
    $smarty->assign('userid', $_SESSION['user']['id']);

    if($page == 'menue') {
        $category = filter_input(INPUT_POST, 'food', FILTER_SANITIZE_SPECIAL_CHARS);
        $smarty->assign('category', $category);
        $smarty->assign('menue', getMenue($category));
        $smarty->assign('owner', $_SESSION['user']['id']);
        $smarty->assign('dn', date('U', time()));
    }

    $smarty->assign('userBalance', checkBalance($_SESSION['user']['id']));
    $smarty->assign('current_site', substr($_SERVER['SCRIPT_NAME'],1));
    $smarty->assign('page', $page);
    $smarty->display('create_order.tpl');
} else {
    echo 'Session abgelaufen. Bitte neu <a href="index.php">einloggen</a>';
}
$mysqli->close();