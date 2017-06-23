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

$smarty->assign('category', 'kebap');
$smarty->assign('page', 'menue');
$smarty->assign('menue', getMenue('kebap'));
$smarty->assign('owner', '1');
$smarty->assign('dn', date('U', time()));

$smarty->display('create_order.tpl');