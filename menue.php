<?php
/**
 * Created by PhpStorm.
 * User: Loki
 * Date: 13.10.2015
 * Time: 10:17
 */
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
    $orders = getUnlockedOrders();
    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);
    $smarty->assign('sessionUserID', $_SESSION['user']['id']);
    $smarty->assign('page', $page);
    $smarty->assign('orders', $orders);

    switch ($page) {
        case 'menue':
            $dn = filter_input(INPUT_POST, 'dn', FILTER_SANITIZE_SPECIAL_CHARS);
            $singleOrder = getSingleOrder($dn);
            $menue = getMenue($singleOrder['category']);
            $smarty->assign('category', $singleOrder['category']);
            $smarty->assign('menue', $menue);
            $smarty->assign('singleOrder', $singleOrder);
            break;
        case 'save':
            $food_id = filter_input(INPUT_POST, 'foodid', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);

            if($food_id == NULL) {
                echo "Keine Speise ausgewählt<br>";
                echo '<a href="javascript:history.back()">Zurück</a>';
                die;
            } else {
                $dn = filter_input(INPUT_POST, 'dn', FILTER_SANITIZE_SPECIAL_CHARS);
                $singleOrder = getSingleOrder($dn);
                $order = array(
                    'dn' => $singleOrder['delivery_number'],
                    'new_order' => NULL,
                    'userid' => filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_SPECIAL_CHARS),
                    'food' => filter_input(INPUT_POST, 'foodid', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY),
                    'amount' => filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY),
                    'sauce' => filter_input(INPUT_POST, 'sauce', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY),
                    'order' => filter_input(INPUT_POST, 'dn', FILTER_SANITIZE_SPECIAL_CHARS),
                    'category' => filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS),
                    'owner' => filter_input(INPUT_POST, 'owner', FILTER_SANITIZE_SPECIAL_CHARS),
                    'mail_check' => NULL,
                    'autolock_time' => $singleOrder['autolock']
                );

                //var_dump($order);
                saveOrder($order);
            }
            break;
    }

    $smarty->display('menue.tpl');
} else {
    $smarty->display('timeout.tpl');
}
$mysqli->close();