<?php
require 'config/setup.php';
require 'config/functions.php';
require_once("config/config.php");
require_once("config/db_cnx.php");
forceSSL();
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
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
    $owner = filter_input(INPUT_GET, 'owner', FILTER_SANITIZE_SPECIAL_CHARS);
    $dn = filter_input(INPUT_GET, 'dn', FILTER_SANITIZE_SPECIAL_CHARS);
    $select_food = 'SELECT * FROM deliverys WHERE id = '.$id;
    $query_food = $mysqli->query($select_food);
    $result = $query_food->fetch_assoc();
    //changeBalance($result['userid'], getPrice($result['delivery_text']));
    $storno = "DELETE FROM deliverys WHERE id = '$id'";
    $query = $mysqli->query($storno);
    $smarty->assign('success', TRUE);
    header('Refresh:2; url=overview.php?dn='.$result['delivery_number']);
    $smarty->display('storno.tpl');
} else {
    $smarty->display('timeout.tpl');
}
$mysqli->close();
?>