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

$autolock_time = new DateTime();
$dn = '1498718551';
$time = '12:00';
$autolock_time->setTimestamp($dn);
$autolock_time->setTime(substr($time,0,2), substr($time,-2));

echo substr($time,0,2).'<br>';
echo substr($time,-2).'<br>';
echo $autolock_time->format('Y-m-d H:i:s');