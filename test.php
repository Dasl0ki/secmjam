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

if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') === TRUE ) {
    echo 'Du verwendest einen idiotischen Browser deswegen musst du selbst auf den link klicken, anstatt komfortabel weitergeleitet zu werden!!!<br><br>';
    die;
} else {
    echo 'Baum';
}