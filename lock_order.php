<?php
require_once("config/config.php");
require_once("config/db_cnx.php");
header('Content-Type: text/html; charset=utf-8');
session_start();
if($_SESSION["user"]["id"] == "1") {
    ini_set("display_errors", 1);
    error_reporting(E_ALL | E_STRICT);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}
ob_start();
if(isset($_SESSION["user"])) {
    // Page Content comes here
    $dn = $_GET["dn"];
    $owner = $_GET["owner"];
    $update_lock = "UPDATE deliverys SET locked='1' WHERE delivery_number = '$dn'";
    $query = $mysqli->query($update_lock);
    echo 'Bestellung gesperrt';
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
$html = ob_get_clean();

// Specify configuration
$config = array(
    'indent'         => '2',
    'output-xhtml'   => true,
    'wrap'           => 200);

// Tidy
$tidy = new tidy;
$tidy->parseString($html, $config, 'utf8');
$tidy->cleanRepair();

// Output
echo $tidy;
?>