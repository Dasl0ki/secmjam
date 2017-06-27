<?php
/**
 * Created by PhpStorm.
 * User: uyve7bo
 * Date: 01.04.2016
 * Time: 06:42
 */
require_once("config/config.php");
require_once("config/db_cnx.php");
require 'config/setup.php';
require 'config/functions.php';
header('Content-Type: text/html; charset=utf-8');
session_start();
if($_SESSION["user"]["id"] == "1") {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

$smarty = new Smarty_mjam();
$smarty->assign('current_site', substr($_SERVER['SCRIPT_NAME'],1));
$smarty->assign('countUnlockedOrders', count(getUnlockedOrders()));

if(isset($_SESSION["user"])) {
    $smarty->assign('success', FALSE);
    $smarty->assign('error', FALSE);
    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);
    if($page == "save") {
        $id = $_SESSION["user"]["id"];
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        if(filter_input(INPUT_POST, 'notify', FILTER_SANITIZE_SPECIAL_CHARS) == NULL) {
            $notify = FALSE;
        } else {
            $notify = TRUE;
        }
        if(filter_input(INPUT_POST, 'active', FILTER_SANITIZE_SPECIAL_CHARS) == NULL) {
            $active = FALSE;
        } else {
            $active = TRUE;
        }

        $update_settings = "UPDATE login SET email = ?, notify = ?, active = ? WHERE id = ?";
        $stmt = $mysqli->prepare($update_settings);
        $stmt->bind_param("ssss", $email, $notify, $active, $id);
        if($stmt->execute()) {
            $_SESSION["user"]["notify"] = $notify;
            $_SESSION["user"]["active"] = $active;
            $smarty->assign('success', TRUE);
            header("Refresh:2; url=user_settings.php");
        } else {
            $smarty->assign('error', TRUE);
        }
    }

    $smarty->assign('user', getUserData($_SESSION['user']['id']));

    $smarty->display('user_settings.tpl');
} else {
    echo 'Session abgelaufen. Bitte neu <a href="index.php">einloggen</a>';
}
$mysqli->close();
?>