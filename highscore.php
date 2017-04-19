<?php
/**
 * Created by PhpStorm.
 * User: uyve7bo
 * Date: 17.03.2016
 * Time: 11:09
 */
require_once("config/config.php");
require_once("config/db_cnx.php");
header('Content-Type: text/html; charset=utf-8');
ini_set("display_errors", 1);
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
        <link rel="stylesheet" href="config/style.css">
    </head>
    <body>
    <table class="stat_table">
        <tr>
            <th>Name</th>
            <th>Geholt</th>
            <th>Bestellungen</th>
            <th>Quote Geholt</th>
            <th>Quote Beteiligung</th>
        </tr>
        <?php
        $select_total = "SELECT COUNT(DISTINCT delivery_number) AS count FROM deliverys";
        $query_total = $mysqli->query($select_total);
        $count_total = $query_total->fetch_object()->count;
        
        $select_owner = "SELECT DISTINCT owner FROM deliverys GROUP BY delivery_number";
        $query = $mysqli->query($select_owner);
        $i = 0;
        $owner_count_arr = array();
        while ($owner_arr = $query->fetch_assoc()) {
            foreach ($owner_arr as $owner) {
                $select_count = "SELECT COUNT(owner) FROM deliverys WHERE owner = '$owner' GROUP BY delivery_number";
                $query_count = $mysqli->query($select_count);
                $select_helps = "SELECT * FROM login WHERE id = '$owner'";
                $query_helps = $mysqli->query($select_helps);
                $helps = $query_helps->fetch_object()->helps;
                $select_orders = "SELECT COUNT(DISTINCT delivery_number) AS anzahl FROM deliverys WHERE userid = '$owner'";
                $query_orders = $mysqli->query($select_orders);
                $row = $query_orders->fetch_assoc();
                $owner_count_arr[$i]["orders"] = $row["anzahl"];
                $owner_count_arr[$i]["id"] = $owner;
                $owner_count_arr[$i]["count"] = $query_count->num_rows + $helps;
                $i++;
            }
        }
        $flag = "0";
        $select_helper = "SELECT * FROM login WHERE helps > '0'";
        $query_helper = $mysqli->query($select_helper);
        while ($row = $query_helper->fetch_assoc()) {
            foreach($owner_count_arr as $check_arr) {
                if($check_arr["id"] == $row["id"]) {
                    $flag = "1";
                }
            }
            if($flag != "1") {
                $user_id = $row["id"];
                $select_orders = "SELECT COUNT(DISTINCT delivery_number) FROM deliverys WHERE userid = '$user_id'";
                $query_orders = $mysqli->query($select_orders);
                $row_orders = $query_orders->fetch_assoc();
                $owner_count_arr[$i]["orders"] = $row_orders["COUNT(DISTINCT delivery_number)"];
                $owner_count_arr[$i]["id"] = $row["id"];
                $owner_count_arr[$i]["count"] = $row["helps"];
            }
            $flag = "0";
            $i++;
        }
        
        $select_orderer = "SELECT DISTINCT userid FROM deliverys GROUP BY delivery_number, userid";
        $query_oderer = $mysqli->query($select_orderer);
        while($row = $query_oderer->fetch_assoc()) {
            foreach($owner_count_arr as $check_arr) {
                if($check_arr["id"] == $row["userid"]) {
                    $flag = "1";
                }
            }
            
            if($flag != "1") {
                $user_id = $row['userid'];
                $select_orders = "SELECT COUNT(DISTINCT delivery_number) FROM deliverys WHERE userid = '$user_id'";
                $query_orders = $mysqli->query($select_orders);
                $row_orders = $query_orders->fetch_assoc();
                $owner_count_arr[$i]["orders"] = $row_orders["COUNT(DISTINCT delivery_number)"];
                $owner_count_arr[$i]["id"] = $row["userid"];
                $owner_count_arr[$i]["count"] = 0;
            }
            $flag = "0";
            $i++;
        }

        foreach ($owner_count_arr as $key => $row) {
            $id[$key] = $row['id'];
            $count[$key] = $row['count'];
        }
        array_multisort($count, SORT_DESC, $id, SORT_ASC, $owner_count_arr);
        for ($i = 0; $i <= count($owner_count_arr) - 1; $i++) {
            $id = $owner_count_arr[$i]["id"];
            $select_names = "SELECT * FROM login WHERE id = '$id'";
            $query_names = $mysqli->query($select_names);
            while ($row = $query_names->fetch_object()) {
                $fn = $row->firstname;
                $ln = $row->lastname;
                $print_id = $row->id;
                $name = $fn . " " . $ln;
            }
            $percent = number_format(Round((($owner_count_arr[$i]["count"] / $owner_count_arr[$i]["orders"]) * 100),2),2);
            $percent_total = number_format(Round((($owner_count_arr[$i]["orders"] / $count_total) * 100),2),2);
            if($i % 2 == 0) {
                echo '<tr class="one">';
            } else {
                echo '<tr class="two">';
            }
            echo '  <td>'.$name . '</td>'
                    . '<td align="center">' . $owner_count_arr[$i]["count"] . '</td>'
                    . '<td align="center">'. $owner_count_arr[$i]["orders"] . '</td>'
                    . '<td align="center">'. $percent .' %</td>'
                    . '<td align="center">' . $percent_total .'%</td>'
                    ;
            echo '</tr>';
        }


        ?>
        </table>
        <br>
        <a href="main.php">Zurück zur Hauptseite</a>
    </body>
    </html>
    <?php
} else {
    echo 'Session abgelaufen. Bitte neu <a href="index.php">einloggen</a>';
}
$mysqli->close();