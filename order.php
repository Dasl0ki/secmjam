<?php
/**
 * Created by PhpStorm.
 * User: Loki
 * Date: 22.10.2015
 * Time: 13:22
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
if(isset($_SESSION["user"])) {
    ?>
    <head>
        <title>SEC Mjam</title>
        <link rel="stylesheet" href="config/style.css">
    </head>
    <?php
    if(filter_input(INPUT_POST, 'foodid', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) == NULL) {
        echo "Keine Speise ausgewählt<br>";
        echo '<a href="javascript:history.back()">Zurück</a>';
        die;
    }
    
    $new_order = filter_input(INPUT_POST, 'new', FILTER_SANITIZE_SPECIAL_CHARS);    
    $user = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_SPECIAL_CHARS);
    $food = filter_input(INPUT_POST, 'foodid', FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
    $sauce = filter_input(INPUT_POST, 'sauce', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    $order = filter_input(INPUT_POST, 'dn', FILTER_SANITIZE_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS);
    $owner = filter_input(INPUT_POST, 'owner', FILTER_SANITIZE_SPECIAL_CHARS);
    $mail_check = filter_input(INPUT_POST, 'mail_check', FILTER_SANITIZE_SPECIAL_CHARS);
    $autolock_check = filter_input(INPUT_POST, 'check', FILTER_SANITIZE_SPECIAL_CHARS);
    
    if ($new_order == "1") {
        if($autolock_check == "1") {
            $time = filter_input(INPUT_POST, 'autolock', FILTER_SANITIZE_SPECIAL_CHARS);
            $autolock = $order . " " . $time;
        } else {
            $autolock = null;
        }
    } else {
        $select_autolock = "SELECT * FROM deliverys WHERE delivery_number = '$order' AND owner = '$owner'";
        $query_autolock = $mysqli->query($select_autolock);
        $autolock = $query_autolock->fetch_object()->autolock;
    }
    
    $select_name = "SELECT * FROM login WHERE id = '$owner'";
    $query_name = $mysqli->query($select_name);
    while ($row = $query_name->fetch_object()) {
        $name = $row->firstname . " " . $row->lastname;
    }

    $sauce_arr = array();
    $sauce_arr_2 = array();
    $extras_arr = array();
    foreach ($sauce as $item) {
        if ($item == "false") {
            continue;
        } else {
            $sauce_arr[] = $item;
        }
    }

    foreach ($sauce_arr as $item) {
        $item_arr = explode("*", $item);
        if ($category == "kebap" OR $category == "schnitzel") {
            $extras_arr[] = array("$item_arr[0]" => $item_arr[1]);
            $extras = "";
            foreach ($extras_arr as $extra) {
                if (isset($extra[$item_arr[0]])) {
                    $extras = $extras . "|" . $extra[$item_arr[0]];
                }
            }
            $sauce_arr_2[$item_arr[0]] = substr($extras, 1);
        } else {
            $sauce_arr_2[$item_arr[0]] = $item_arr[1];
        }
    }
    $now = new DateTime('now');
    $insert_timestamp = $now->format('Y-m-d H:i:s');
    foreach ($food as $foodid) {
        $foodid_amount = $foodid."_amount";
        $amount = filter_input(INPUT_POST, $foodid_amount, FILTER_SANITIZE_SPECIAL_CHARS);
        for($i=1; $i <= $amount; $i++) {
            $sauce_insert = $sauce_arr_2[$foodid];
            $insert = " INSERT INTO deliverys (delivery_number, delivery_text, userid, sauce, owner, category, autolock, timestamp)
                        VALUES ('$order', '$foodid', '$user', '$sauce_insert', '$owner', '$category', '$autolock', '$insert_timestamp')";
            $query = $mysqli->query($insert);
        }
    }

    echo "Bestellung erfolgreich gespeichert";

    if ($new_order == "1" AND $mail_check == "1") {
        $headers = array();
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        $headers[] = "From: SEC-Mjam <no-reply@loki-net.at>";
        $headers[] = "Reply-To: SEC-Mjam <no-reply@loki-net.at>";
        $headers[] = "Subject: Neue SEC-Mjam Bestellung";
        $headers[] = "X-Mailer: PHP/".phpversion();
        $select_receiver = "SELECT email FROM login WHERE notify = '1' AND active = TRUE";
        $query = $mysqli->query($select_receiver);
        $receiver_arr = array();
        while ($row = $query->fetch_object()) {
            $receiver_arr[] = $row->email;
        }

        $betreff = "Neue SEC-Mjam Bestellung";
        $mail_food = ucfirst($category);
        $from = "From: SEC-Mjam <no-reply@loki-net.at>";
        if($autolock_check == "1") {
            $text = "Es wurde von $name eine neue Bestellung für $mail_food angelegt<br>Die Bestellung ist bis $time Uhr möglich.<br><br>" . '
            <a href="http://sec-mjam.loki-net.at">SEC-Mjam</a>';
        } else {
            $text = "Es wurde von $name eine neue Bestellung für $mail_food angelegt<br><br>" . '
            <a href="http://sec-mjam.loki-net.at">SEC-Mjam</a>';
        }
        foreach ($receiver_arr as $receiver) {
            //mail($receiver, $betreff, $text, implode("\r\n", $headers));
        }
    }
    echo '<meta http-equiv="refresh" content="3; URL=overview.php?dn='. $order .'&owner='. $owner .'" />' . "\n";
} else {
    $smarty->display('timeout.tpl');
}
$mysqli->close();