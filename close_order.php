<?php
/**
 * Created by PhpStorm.
 * User: uyve7bo
 * Date: 17.03.2016
 * Time: 10:57
 */
require 'config/functions.php';
require_once("config/config.php");
require_once("config/db_cnx.php");
forceSSL();
header('Content-Type: text/html; charset=utf-8');
session_start();
if($_SESSION["user"]["id"] == "1") {
    ini_set("display_errors", 1);
    error_reporting(E_ALL | E_STRICT);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}
if(isset($_SESSION["user"])) {
    ?>
    <html>
        <head>
            <title>SEC Mjam</title>
        </head>
            <body>
            <?php
                $dn = filter_input(INPUT_GET, 'dn', FILTER_SANITIZE_SPECIAL_CHARS);
                if($dn != NULL) {
                    $helper_arr = filter_input(INPUT_POST, 'helper', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    $owner = $_SESSION["user"]["id"];
                    $close_order = "UPDATE deliverys SET status = '1' WHERE delivery_number = '$dn' AND owner = '$owner'";
                    $query = $mysqli->query($close_order);
                    if ($helper_arr != NULL) {
                        foreach($helper_arr as $id) {
                            $select_help_count = "SELECT helps FROM login WHERE id = '$id'";
                            $query_help_count = $mysqli->query($select_help_count);
                            $help_count = $query_help_count->fetch_object()->helps;
                            $new_help_count = $help_count + 1;
                            echo $new_help_count;
                            $update_help_count = "UPDATE login SET helps = '$new_help_count' WHERE id = '$id'";
                            $query_update = $mysqli->query($update_help_count);
                        }
                    }
                    echo "Closed";
                } else {
                    echo "Error";
                }
            ?>
            <meta http-equiv="refresh" content="3; URL=main.php" />
            </body>
    </html>
    <?php
} else {
    $smarty->display('timeout.tpl');
}
$mysqli->close();
