<?php
setlocale(LC_ALL, 'de_DE.utf8');
require 'config/setup.php';
require 'config/functions.php';
require_once("config/config.php");
require_once("config/db_cnx.php");
$smarty = new Smarty_mjam();
session_start();
if($_SESSION["user"]["id"] == "1") {
    ini_set("display_errors", 1);
    error_reporting(E_ALL | E_STRICT);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}

$smarty->assign('current_site', substr($_SERVER['SCRIPT_NAME'],1));
$smarty->assign('countUnlockedOrders', count(getUnlockedOrders()));

if(isset($_SESSION["user"])) {
    // Page Content comes here
    
    // Top 5 Bestellungen
    $select = "SELECT COUNT(delivery_text) as Anzahl, delivery_text FROM deliverys GROUP BY delivery_text ORDER BY COUNT(delivery_text) DESC LIMIT 0,5";
    $query = $mysqli->query($select);
    $count_arr = array();
    while($row = $query->fetch_assoc()) {
        $select = 'SELECT * FROM menue WHERE id = '.$row['delivery_text'];
        $query_food = $mysqli->query($select);
        $result = $query_food->fetch_assoc();
        if($result["size"] != "-") {
            $food_item = $result["sub_category"] . ' ' . $result["item"] . ' ' . $result["size"];
        } else {
            $food_item = $result["sub_category"] . ' ' . $result["item"];
        }
        $count_arr[] = array(
            'count' => $row['Anzahl'],
            'item' => $food_item
        );
    }
    
    // Top 5 Bestellungen Persönlich
    $select = 'SELECT COUNT(delivery_text) as anzahl, delivery_text '
            . 'FROM deliverys '
            . 'WHERE userid = '.$_SESSION['user']['id'].' '
            . 'GROUP BY delivery_text '
            . 'ORDER BY COUNT(delivery_text) DESC LIMIT 0,5';
    $query = $mysqli->query($select);
    $count_arr_pers = array();
    while($row = $query->fetch_assoc()) {
        $select = 'SELECT * FROM menue WHERE id = '.$row['delivery_text'];
        $query_food = $mysqli->query($select);
        $result = $query_food->fetch_assoc();
        if($result["size"] != "-") {
            $food_item = $result["sub_category"] . ' ' . $result["item"] . ' ' . $result["size"];
        } else {
            $food_item = $result["sub_category"] . ' ' . $result["item"];
        }
        $count_arr_pers[] = array(
            'count' => $row['anzahl'],
            'item' => $food_item
        );
    }
    
    // Top Kategorien
    $select_food = 'SELECT COUNT(DISTINCT delivery_number) AS count, category FROM deliverys GROUP BY category ORDER BY COUNT(DISTINCT delivery_number) DESC';
    $query_food = $mysqli->query($select_food);
    $cat_arr = array();
    while($row = $query_food->fetch_assoc()) {
        $cat_arr[] = $row;
    }
    
    //Top Kategorien persönlich
    $select_food = 'SELECT COUNT(DISTINCT delivery_number) AS count, category FROM deliverys WHERE userid = '.$_SESSION['user']['id'].' GROUP BY category ORDER BY COUNT(DISTINCT delivery_number) DESC';
    $query_food = $mysqli->query($select_food);
    $cat_arr_pers = array();
    while($row = $query_food->fetch_assoc()) {
        $cat_arr_pers[] = $row;
    }
    
    //Top Besteller
    $select_top_orderer = "SELECT COUNT(DISTINCT delivery_number) as count, userid, timestamp "
                        . "FROM deliverys GROUP BY userid, MONTH(timestamp) "
                        . "ORDER BY COUNT(DISTINCT delivery_number) DESC LIMIT 0,3";
    $query_top_orderer = $mysqli->query($select_top_orderer);
    $orderer_arr = array();
    while($row = $query_top_orderer->fetch_assoc()) {
        $timestamp = new DateTime($row['timestamp']);
        $output_date = strftime("%B %Y", $timestamp->getTimestamp());
        $select_user = 'SELECT * FROM login WHERE id = '.$row["userid"];
        $query = $mysqli->query($select_user);
        $result = $query->fetch_assoc();
        $orderer_arr[] = array(
            'count' => $row['count'],
            'firstname' => $result["firstname"],
            'lastname' => $result["lastname"],
            'output_date' => $output_date            
        );
    }
    // Page Content ends here
} else {
    $smarty->display('timeout.tpl');
}
$mysqli->close();

$smarty->assign('count_arr', $count_arr);
$smarty->assign('count_arr_pers', $count_arr_pers);
$smarty->assign('cat_arr', $cat_arr);
$smarty->assign('cat_arr_pers', $cat_arr_pers);
$smarty->assign('orderer_arr', $orderer_arr);
$smarty->display('stats.tpl');