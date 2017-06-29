<?php
/*** Created by PhpStorm. User: Loki Date: 13.10.2015 Time: 09:25 */
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
$smarty->assign('current_site', substr($_SERVER['SCRIPT_NAME'],1));
$smarty->assign('countUnlockedOrders', count(getUnlockedOrders()));

if(isset($_SESSION["user"])) {
    $vote = getPollVotes();
    
    $smarty->assign('voteStatus', $_SESSION['user']['vote']);
    $smarty->assign('percent', getVotePercent($vote));
    $smarty->assign('user', getUserData($_SESSION['user']['id']));
    
    $smarty->display('main.tpl');
} else {
    $smarty->display('timeout.tpl');
}
$mysqli->close();