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
$smarty->assign('current_site', substr($_SERVER['SCRIPT_NAME'], 1));
$smarty->assign('countUnlockedOrders', count(getUnlockedOrders()));


if(isset($_SESSION["user"])) {
    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);
    $smarty->assign('userid', $_SESSION['user']['id']);
    $smarty->assign('page', $page);
    $smarty->assign('userBalance', checkBalance($_SESSION['user']['id']));
    $smarty->assign('success', FALSE);

    switch ($page) {
        case 'menue':
            $category = filter_input(INPUT_POST, 'food', FILTER_SANITIZE_SPECIAL_CHARS);
            $smarty->assign('category', $category);
            $smarty->assign('menue', getMenue($category));
            $smarty->assign('owner', $_SESSION['user']['id']);
            $smarty->assign('dn', date('U', time()));
            break;
        case 'save':
            $food_id = filter_input(INPUT_POST, 'foodid', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);

            if($food_id == NULL) {
                echo "Keine Speise ausgewählt<br>";
                echo '<a href="javascript:history.back()">Zurück</a>';
                die;
            } else {
                $order = array(
                    'dn' => filter_input(INPUT_POST, 'dn', FILTER_SANITIZE_SPECIAL_CHARS),
                    'new_order' => filter_input(INPUT_POST, 'new', FILTER_SANITIZE_SPECIAL_CHARS),
                    'userid' => filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_SPECIAL_CHARS),
                    'food' => filter_input(INPUT_POST, 'foodid', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY),
                    'amount' => filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY),
                    'sauce' => filter_input(INPUT_POST, 'sauce', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY),
                    'order' => filter_input(INPUT_POST, 'dn', FILTER_SANITIZE_SPECIAL_CHARS),
                    'category' => filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS),
                    'owner' => filter_input(INPUT_POST, 'owner', FILTER_SANITIZE_SPECIAL_CHARS),
                    'mail_check' => filter_input(INPUT_POST, 'mail_check', FILTER_SANITIZE_SPECIAL_CHARS),
                    'autolock_check' => filter_input(INPUT_POST, 'check', FILTER_SANITIZE_SPECIAL_CHARS),
                    'autolock_time' => filter_input(INPUT_POST, 'autolock', FILTER_SANITIZE_SPECIAL_CHARS)
                );

                saveOrder($order);

                if($order['new_order'] == '1' AND $order['mail_check'] == '1') {
                    if($order['autolock_check'] == '1') {
                        sendInfoMail($order['owner'], $order['category'], TRUE, $order['autolock_time']);
                    } else {
                        sendInfoMail($order['owner'], $order['category']);
                    }
                }
                $smarty->assign('success', TRUE);
                header('Refresh:2 url=overview.php?dn='.$order['dn']);
            }
            break;
    }

    $smarty->display('create_order.tpl');
} else {
    $smarty->display('timeout.tpl');
}
$mysqli->close();