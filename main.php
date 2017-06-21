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

if(isset($_SESSION["user"])) {
    //get content of textfile
    $filename = "config/poll_result.txt";
    $content = file($filename);

    //put content in array
    $array = explode("||", $content[0]);
    $noodles = $array[0];
    $pizza = $array[1];
    $kebap = $array[2];
    $schnitzel = $array[3];
    $sum = $noodles + $pizza + $kebap + $schnitzel;
    if($sum == 0) {
        $noodles_percent = 0;
        $pizza_percent = 0;
        $kebap_percent = 0;
        $schnitzel_percent = 0;                                            
    } else {
        $noodles_percent = 100*round($noodles/$sum,2);
        $pizza_percent = 100*round($pizza/$sum,2);
        $kebap_percent = 100*round($kebap/$sum,2);
        $schnitzel_percent = 100*round($schnitzel/$sum,2);
    }
    
    $smarty->display('main.tpl');
} else {
    echo 'Session abgelaufen. Bitte neu <a href="index.php">einloggen</a>';
}
$mysqli->close();