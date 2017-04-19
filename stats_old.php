<?php
setlocale(LC_ALL, 'de_DE.utf8');
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
    ?>
    <head>
        <link rel="stylesheet" href="config/style.css">
    </head>
    <?php
    $select = "SELECT DISTINCT delivery_text FROM deliverys";
    $query = $mysqli->query($select);
    echo '<div style="max-width: 500px;">';
    echo '<table id="stat_table" style="width: 100%;">';
    echo '<th colspan="2" align="center"><b>Top 5 Bestellungen</b></td>';
    $i = 0;
    $count_arr = array();
    while($row = $query->fetch_object()) {
        $deltext = $row->delivery_text;
        $select = "SELECT COUNT(delivery_text) FROM deliverys WHERE delivery_text = '$deltext'";
        $query_count = $mysqli->query($select);
        $count = $query_count->fetch_assoc();
        $select_name = "SELECT * FROM menue WHERE id = '$deltext'";
        $query_names = $mysqli->query($select_name);
        $name = $query_names->fetch_assoc();
        $count_arr[$i]["count"] = $count["COUNT(delivery_text)"];
        if($name["size"] != "-") {
            $count_arr[$i]["item"] = $name["sub_category"] . ' ' . $name["item"] . ' ' . $name["size"];
        } else {
            $count_arr[$i]["item"] = $name["sub_category"] . ' ' . $name["item"];
        }
        $i++;
    }

    usort($count_arr, function($a, $b) {
    return $b['count'] - $a['count'];
    });
    $counter = 1;
    foreach($count_arr as $item) {
        if($counter % 2 == 0) {
            echo '<tr class="two">';
        } else {
            echo '<tr class="one">';
        }
        echo '<td align="center" style="width: 10%;">'. $item["count"] .'x</td><td>' . $item["item"] . '</td>';
        echo '</tr>';
        if($counter == "5") {
            break;
        }
        $counter++;
    }
    echo '</table>';
    echo '<br>';
    echo '<table id="stat_table" style="width: 100%; max-width: inherit;">';
    echo '<th colspan=2>Top Kategorien</th>';
    $select_food = "SELECT COUNT(DISTINCT delivery_number) AS count, category FROM deliverys GROUP BY category ORDER BY COUNT(DISTINCT delivery_number) DESC";
    $query_food = $mysqli->query($select_food);
    $counter = 1;
    while($row = $query_food->fetch_assoc()) {
        if($counter % 2 == 0) {
            echo '<tr class="two">';
        } else {
            echo '<tr class="one">';
        }
        echo '<td align="center" style="width: 10%;">'. $row['count'] .'x</td>'
        . '<td>'. ucfirst($row['category']) .'</td>'
        . '</tr>';
        $counter++;
    }
    echo '</table>';
    
    $select_top_orderer = "SELECT COUNT(DISTINCT delivery_number) as count, userid, timestamp "
                        . "FROM deliverys GROUP BY userid, MONTH(timestamp) "
                        . "ORDER BY COUNT(DISTINCT delivery_number) DESC LIMIT 0,3";
    $query_top_orderer = $mysqli->query($select_top_orderer);
    
    echo '<br>';
    echo '<table id="stat_table" style="width: 100%; max-width: inherit;">';
    echo '<th colspan="3">Top 3 Bestellungen in einem einzigen Monat</th>';
    $counter = 1;
    while($row = $query_top_orderer->fetch_assoc()) {
        $timestamp = new DateTime($row['timestamp']);
        $output_date = strftime("%B %Y", $timestamp->getTimestamp());
        $userid = $row["userid"];
        $select_user = "SELECT * FROM login WHERE id = $userid";
        $query = $mysqli->query($select_user);
        $result = $query->fetch_assoc();
        if($counter % 2 == 0) {
            echo '<tr class="two">';
        } else {
            echo '<tr class="one">';
        }
        echo  '<td align="center" style="width: 10%;">'. $row["count"] .'x</td>'  
            . '<td>'. $result["firstname"] .' '. $result["lastname"] .'</td>'
            . '<td>'. $output_date .'</td>'
            . '</tr>';
        $counter++;
    }
    echo '</table>';
    echo '</div>';
    echo '<br><a href="main.php">Zurück zur Hauptseite</a>';
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