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
if(isset($_SESSION["user"])) {
    // Page Content comes here
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
    $owner = filter_input(INPUT_GET, 'owner', FILTER_SANITIZE_SPECIAL_CHARS);
    $dn = filter_input(INPUT_GET, 'dn', FILTER_SANITIZE_SPECIAL_CHARS);
    $select_food = 'SELECT * FROM deliverys WHERE id = '.$id;
    $query_food = $mysqli->query($select_food);
    $result = $query_food->fetch_assoc();
    changeBalance($result['userid'], getPrice($result['delivery_text']));
    $storno = "DELETE FROM deliverys WHERE id = '$id'";
    $query = $mysqli->query($storno);
    echo "Bestellung storniert";
    echo '<meta http-equiv="refresh" content="3; URL=overview.php?dn='. $dn .'&owner='.$owner.'" />' . "\n";
    ?>
    <head>
        <link rel="stylesheet" href="config/style.css">
    </head>
    <body>

    </body>
    <?php
    // Page Content ends here
} else {
    echo 'Session abgelaufen. Bitte neu <a href="index.php">einloggen</a>';
}
$mysqli->close();
?>