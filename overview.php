<?php
require_once("config/config.php");
require_once("config/db_cnx.php");
require 'config/functions.php';
require 'config/setup.php';
forceSSL();
header('Content-Type: text/html; charset=utf-8');
ob_start();
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
    $dn = filter_input(INPUT_GET, 'dn', FILTER_SANITIZE_SPECIAL_CHARS);
    $do = filter_input(INPUT_GET, 'do', FILTER_SANITIZE_SPECIAL_CHARS);
    
    /* Overview - Unlock Order - Start */
    if($dn != NULL AND $do != NULL) {
        $delivery = getDeliverys($dn);
        if($do == "unlock" AND $_SESSION["user"]["id"] == $delivery[0]['owner']) {
            unlockOrder($dn);
            $smarty->assign('countUnlockedOrders', count(getUnlockedOrders()));
        }
        elseif($do == 'lock' AND $_SESSION["user"]["id"] == $delivery[0]['owner']) {
            lockOrder($dn);
            $smarty->assign('countUnlockedOrders', count(getUnlockedOrders()));
        }
        elseif($do == 'close' AND $_SESSION["user"]["id"] == $delivery[0]['owner']) {
            $helper = filter_input(INPUT_POST, 'helper', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
            $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS);
            closeOrder($dn, $helper);
            header('Location: overview.php?dn='.$dn);
        }
        else {
            echo "Insufficient rights";
        }
    }
    /* Overview - Unlock Order - End */
    
    /* Overview Landing - Start*/
    if ($dn == NULL) {


        $smarty->assign('openOrders', getOpenOrders());
        $smarty->assign('orders', lastOrders($_SESSION["user"]["id"]));
        $smarty->display('overview.tpl');
		
    }
    /* Overview Landing - End */
    
    /* Overview Order - Start */
    if ($dn != NULL) {
        $date_display = new DateTime();
        
	    $smarty->assign('date_display', $date_display->setTimestamp($dn)->format('d.m.Y'));
               

        $cost_array = array();
        $count_array = array();
        $select_dn = "SELECT * FROM deliverys WHERE delivery_number = '$dn' ORDER BY CAST(delivery_text AS DECIMAL), sauce";
        $query = $mysqli->query($select_dn);
        $owner = $query->fetch_object()->owner;
        $query->data_seek(0);
        $category = $query->fetch_object()->category; //check category for jQuery
        $query->data_seek(0); //reset pointer for while-loop        
        
        $ownerData = getUserData($owner);
        $helperArray = getHelper();
        
        $total = total($dn);
        $totalSum = 0;
        foreach ($total as $row) {
            $totalSum = $totalSum + $row['total'];
        }
        
        $smarty->assign('dn', $dn);
        $smarty->assign('sessionUser', $_SESSION['user']['id']);
        $smarty->assign('total', $total);
        $smarty->assign('totalSum', $totalSum);
        $smarty->assign('totalItems', totalItems($dn));
        $smarty->assign('deliverys', getDeliverys($dn));
        $smarty->assign('ownerID', $owner);
        $smarty->assign('ownerFullName', $ownerData['firstname'] . " " . $ownerData['lastname']);
        $smarty->assign('category', $category);
        $smarty->assign('points', getPointsFromDelivery($dn));
        $smarty->assign('helperArray', $helperArray);
        $smarty->assign('to', floor(count($helperArray)/10));
        $smarty->assign('col', 12/ceil(count($helperArray)/10));

        $smarty->display('view_order.tpl');
    /* Overview Order - End */
    }
} else {
    $smarty->display('timeout.tpl');
}
$mysqli->close();