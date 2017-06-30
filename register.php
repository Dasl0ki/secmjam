<?php
/**
 * Created by PhpStorm.
 * User: Loki
 * Date: 19.11.2015
 * Time: 10:13
 */
require_once("config/config.php");
require_once("config/db_cnx.php");
require 'config/functions.php';
require 'config/setup.php';
forceSSL();
header('Content-Type: text/html; charset=utf-8');

$smarty = new Smarty_mjam();
session_start();
if($_SESSION["user"]["id"] == "1") {
    ini_set("display_errors", 1);
    error_reporting(E_ALL | E_STRICT);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}

$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);
$smarty->assign('page', $page);
$smarty->assign('error_captcha', FALSE);
$smarty->assign('error_user', FALSE);
$smarty->assign('error_pwd', FALSE);
$smarty->assign('error', FALSE);
$smarty->assign('success', FALSE);

if($page != NULL) {
    if ($page == "register") {
        $flag_captcha = FALSE;
        $flag_user = FALSE;
        $flag_pwd = FALSE;
        $captcha = filter_input(INPUT_POST, 'g-recaptcha-response', FILTER_SANITIZE_SPECIAL_CHARS);
        $user = strtolower($_POST["user"]);
        $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $firstname = ucfirst(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS));
        $lastname = ucfirst(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS));
        $pwd = md5(filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_SPECIAL_CHARS));
        $pwd2 = md5(filter_input(INPUT_POST,'pwd2', FILTER_SANITIZE_SPECIAL_CHARS));

        if (!$captcha) {
            $smarty->assign('error_captcha', TRUE);
            $flag_captcha = TRUE;
        } else {
            $captcha_response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret="
                . $config['captcha']["secret_key"] . "&response="
                . $captcha . "&remoteip="
                . $_SERVER['REMOTE_ADDR']), true);
            if ($captcha_response['success'] == false) {
                $smarty->assign('error_captcha', TRUE);
                $flag_captcha = TRUE;
            }
        }

        $select_users = "SELECT user FROM login";
        $query_user = $mysqli->query($select_users);
        while($row = $query_user->fetch_object()) {
            if($row->user == $user) {
                $smarty->assign('error_user', TRUE);
                $flag_user = TRUE;
            }
        }

        if ($pwd != $pwd2) {
            $smarty->assign('error_pwd', TRUE);
            $flag_pwd = TRUE;
        }

        if($flag_captcha == FALSE AND $flag_user == FALSE AND $flag_pwd == FALSE) {
            $insert = "INSERT INTO login (user, firstname, lastname, email, pwd) VALUES (?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($insert);
            $stmt->bind_param("sssss", $user, $firstname, $lastname, $email, $pwd);
            if($stmt->execute()) {
                //$stmt->execute();
                $stmt->close();
                $smarty->assign('success', TRUE);
                $mysqli->close();
                header('Refresh:2, url=index.php');
            } else {
                $smarty->assign('error', TRUE);
            }
        }
    }
}

$smarty->display('register.tpl');

