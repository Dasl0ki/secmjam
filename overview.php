<?php
require_once("config/config.php");
require_once("config/db_cnx.php");
require 'config/functions.php';
require 'config/setup.php';
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

if(isset($_SESSION["user"])) {
    $dn = filter_input(INPUT_GET, 'dn', FILTER_SANITIZE_SPECIAL_CHARS);
    $do = filter_input(INPUT_GET, 'do', FILTER_SANITIZE_SPECIAL_CHARS);    
    
    /* Overview - Unlock Order - Start */
    if($dn != NULL AND $do != NULL) {
        if($do == "unlock" and $_SESSION["user"]["id"] == $owner) {
            unlockOrder($dn);
            echo "Bestellung entsperrt";
        } else {
            echo "Insufficient rights";
        }
    }
    /* Overview - Unlock Order - End */
    
    /* Overview Landing - Start*/
    if ($dn == NULL) {
        $select_deliverys = "SELECT * FROM deliverys WHERE status != '1' GROUP BY delivery_number";
        $query = $mysqli->query($select_deliverys);
        while ($row = $query->fetch_object()) {
            $owner = $row->owner;
            $select_owner_name = "SELECT * FROM login WHERE id = '$owner'";
            $query_owner = $mysqli->query($select_owner_name);
            while ($row_owner = $query_owner->fetch_object()) {
                $owner_fn = $row_owner->firstname;
                $owner_ln = $row_owner->lastname;
            }
            $owner_fullname = $owner_fn . " " . $owner_ln;
            $category = ucfirst($row->category);
            $date = new DateTime();
            $date_output = $date->setTimestamp($row->delivery_number)->format('d.m.Y');
            echo '<a href="overview.php?dn=' . $row->delivery_number . '&owner=' . $row->owner . '">' . $date_output . ' - ' . $category . ' - ' .  $owner_fullname . '</a><br>';
        }
        
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
        $jq_cat = $query->fetch_object()->category; //check category for jQuery
        $query->data_seek(0); //reset pointer for while-loop        
        
        $ownerData = getUserData($owner);
        
        $smarty->assign('deliverys', getDeliverys($dn));
        $smarty->assign('ownerFullName', $ownerData['firstname'] . " " . $ownerData['lastname']);
        $smarty->assign('jq_cat', $jq_cat);
        
        $smarty->display('view_order.tpl');
    /* Overview Order - End */
    }
} else {
    echo 'Session abgelaufen. Bitte neu <a href="index.php">einloggen</a>';
}
$mysqli->close();