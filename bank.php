<?php
forceSSL();
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
    $action = filter_input(INPUT_GET, 'do', FILTER_SANITIZE_SPECIAL_CHARS);
    $userid = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_SPECIAL_CHARS);
    
    if($action == "add") {
        $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_SPECIAL_CHARS);
        $add_to_user = filter_input(INPUT_POST, 'add_to_user', FILTER_SANITIZE_SPECIAL_CHARS);
        
        $changeBalance = changeBalance($add_to_user, $amount);
        
        if($changeBalance == TRUE) {
            echo 'Erfolgreich eingezahlt';
            echo '<meta http-equiv="refresh" content="3; URL=bank.php">';
        } else {
            echo 'Es ist ein Fehler aufgetreten';
        }        
    } else {
        $select_users = 'SELECT * FROM login WHERE active = TRUE ORDER BY lastname ASC';
        $query_users = $mysqli->query($select_users);
        $userlist = array();
        while($row = $query_users->fetch_assoc()) {
            $userlist[] = $row;
        }

        if($userid != NULL) {
            $balance = checkBalance($userid);
            $smarty->assign('balance', $balance);
            $smarty->assign('add_to_user', $userid);
        }
        $smarty->assign('userlist', $userlist);    
        $smarty->display('bank.tpl');
    }
} else {
    echo 'Session abgelaufen. Bitte neu <a href="index.php">einloggen</a>';
}
$mysqli->close();

