<?php
/**
 * Created by PhpStorm.
 * User: uyve7bo
 * Date: 17.03.2016
 * Time: 11:09
 */
require_once("config/config.php");
require_once("config/db_cnx.php");
require 'config/setup.php';
require 'config/functions.php';
forceSSL();
session_start();
if($_SESSION["user"]["id"] == "1") {
    ini_set("display_errors", 1);
    error_reporting(E_ALL | E_STRICT);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}
$smarty = new Smarty_mjam();
$smarty->assign('current_site', substr($_SERVER['SCRIPT_NAME'],1));
$smarty->assign('countUnlockedOrders', count(getUnlockedOrders()));

if(isset($_SESSION["user"])) {
   $highscore = getHighscore();

   $smarty->assign('highscore', $highscore);
   $smarty->display('highscore.tpl');
} else {
    echo 'Session abgelaufen. Bitte neu <a href="index.php">einloggen</a>';
}
$mysqli->close();