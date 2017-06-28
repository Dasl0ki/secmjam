<?php
/**
 * Created by PhpStorm.
 * User: Loki
 * Date: 19.11.2015
 * Time: 11:03
 */
require_once("config/config.php");
require_once("config/db_cnx.php");
require 'config/setup.php';
session_start();
if($_SESSION["user"]["id"] == "1") {
    ini_set("display_errors", 1);
    error_reporting(E_ALL | E_STRICT);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}

$smarty = new Smarty_mjam();

if(isset($_SESSION["user"])) {
    session_destroy();
    $smarty->display('logout.tpl');
    header("Refresh:2; url=index.php");
} else {
    $smarty->display('timeout.tpl');
}
$mysqli->close();