<?php
/**
 * Created by PhpStorm.
 * User: Loki
 * Date: 22.10.2015
 * Time: 13:58
 */
require_once("config/config.php");
require_once("config/db_cnx.php");
header('Content-Type: text/html; charset=utf-8');
ob_start();
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
        <script src="https://code.jquery.com/jquery-2.2.3.js" integrity="sha256-laXWtGydpwqJ8JA+X9x2miwmaiKhn8tVmOVEigRNtP4=" crossorigin="anonymous"></script>
    </head>
    <?php
    $dn = filter_input(INPUT_GET, 'dn', FILTER_SANITIZE_SPECIAL_CHARS);
    $owner = filter_input(INPUT_GET, 'owner', FILTER_SANITIZE_SPECIAL_CHARS);
    $do = filter_input(INPUT_GET, 'do', FILTER_SANITIZE_SPECIAL_CHARS);    
    
    /* Overview - Lock Order - Start */
    if($dn != NULL AND $owner != NULL AND $do != NULL) {
        if($do == "unlock" and $_SESSION["user"]["id"] == $owner) {
            $update_lock = "UPDATE deliverys SET locked='0' WHERE delivery_number = '$dn'";
            $query = $mysqli->query($update_lock);
            echo "Bestellung entsperrt";
        } else {
            echo "Insufficient rights";
        }
    }
    /* Overview - Lock Order - End */
    
    /* Overview Landing - Start*/
    if ($dn == NULL) {
        echo "Offene Bestellungen:<br>";
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
		?>
		<br>
		<br>
		<form action="create_order.php" method="post">
                    <input type="hidden" name="userid" value="<?php echo $_SESSION["user"]["id"]; ?>">
                    <input type="submit" value="Neue Bestellung anlegen">
		</form>
		<br>
                Meine letzten 10 Abgeschlossenen Bestellungen:<br><br>
		<?php
		$userid = $_SESSION["user"]["id"];
		$select_user_delivery = "SELECT * FROM deliverys WHERE userid = '$userid' AND status ='1' GROUP BY delivery_number ORDER BY id DESC LIMIT 10 ";
		$query = $mysqli->query($select_user_delivery);
		while ($row = $query->fetch_object()) {
                    $date_output = new DateTime();
			echo '<a href="overview.php?dn=' 
                                . $row->delivery_number . '&owner=' . $row->owner .'">' 
                                . $date_output->setTimestamp($row->delivery_number)->format('d.m.Y') 
                                . ' - '. ucfirst($row->category) .'</a><br>';
		}
		echo '<br><br><a href="main.php">Zurück zur Hauptseite</a>';
    }
    /* Overview Landing - End */
    
    /* Overview Order - Start */
    if ($dn != NULL) {
        $date_display = new DateTime();
        ?>
        <h3>Bestellungen für den <?php echo $date_display->setTimestamp($dn)->format('d.m.Y'); ?></h3>
        <?php
        $select_owner_name = "SELECT * FROM login WHERE id = '$owner'";
        $query_owner = $mysqli->query($select_owner_name);
        while ($row_owner = $query_owner->fetch_object()) {
            $owner_fn = $row_owner->firstname;
            $owner_ln = $row_owner->lastname;
        }
        $owner_fullname = $owner_fn . " " . $owner_ln;
		echo "Owner: " . $owner_fullname;
		?>
		<br><br>
        <table id="item">
            <tr>
                <th>Name</th>
                <th>Bestellung</th>
                <th>Größe</th>
                <th>Extras</th>
                <th>Preis</th>
                <th>Optionen</th>
            </tr>
            <?php
            $cost_array = array();
            $count_array = array();
            $select_dn = "SELECT * FROM deliverys WHERE delivery_number = '$dn' AND owner = '$owner' ORDER BY CAST(delivery_text AS DECIMAL), sauce";
            $query = $mysqli->query($select_dn);
            $jq_cat = $query->fetch_object()->category; //check category for jQuery
            $query->data_seek(0); //reset pointer for while-loop
            if($jq_cat == "kebap" or $jq_cat == "schnitzel") { ?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('td:nth-child(3),th:nth-child(3)').hide();
                    })
                </script>
            <?php }
            $counter = 2;
            while ($row = $query->fetch_object()) {
                $lock = $row->locked;
                $status = $row->status;
                if($row->category == "kebap") {
                    $sauce_arr = explode("|", $row->sauce);

                } else {
                    $sauce_arr = array("0" => $row->sauce);
                }
                if (!isset($cost_array["$row->userid"])) {
                    $cost_array["$row->userid"] = array("userid" => "$row->userid",
                        "total" => "0");
                }
                $select_user = "SELECT * FROM login WHERE id = '$row->userid'";
                $query_user = $mysqli->query($select_user);
                $user_array = $query_user->fetch_object();

                $select_food = "SELECT * FROM menue WHERE id = '$row->delivery_text'";
                $query_food = $mysqli->query($select_food);
                $food_array = $query_food->fetch_object();                
                if($counter % 2 == 0) {
                    echo '<tr class="one">';
                } else {
                    echo '<tr class="two">';
                }
                echo "<td>" . $user_array->firstname . " " . $user_array->lastname . "</td>
                <td>" . $food_array->sub_category . " " . $food_array->item . "</td>
                <td>" . $food_array->size . "</td>
                <td>";
                foreach($sauce_arr as $sauce) {
                    if($row->category == "kebap" and !empty($sauce)) {
                        echo "Ohne ".ucfirst($sauce)."<br>";
                    }
                    elseif($row->category == "schnitzel" and !empty($sauce)) {
                        $extras = explode("|", $sauce);
                        $extra_output = "";
                        foreach($extras as $extra) {
                            $extra_output .= $extra . " & ";
                        }
                        echo substr($extra_output,0,-2);

                    }
                    else {
                        echo ucfirst($sauce)."<br>";
                    }
                }
                
                echo "</td>
                <td>€ " . number_format($food_array->prize, 2,',','.') . "</td>";
                if($_SESSION["user"]["id"] == $row->userid AND $_SESSION["user"]["id"] != $row->owner AND $lock == "0") {
                    echo '<td><a href="storno.php?id='.$row->id.'&owner='.$row->owner.'&dn='.$row->delivery_number.'">Storno</a>';
                } else {
                    echo '<td></td>';
                }
                echo "</tr>";
                $cost_array["$row->userid"]["total"] = $cost_array["$row->userid"]["total"] + $food_array->prize;
                $counter++;
            }
           while($row = $query->fetch_object()) {
                $select_food = "SELECT * FROM deliverys WHERE delivery_number = '$dn' AND delivery_text = '$row->delivery_text'";
                $query_food_count = $mysqli->query($select_food);
            }
            ?>
        </table>
        <br>
        <table style="border: none">
            <tr style="border: none">
                <td style="border: none">
                    <table id="item">
                        <tr>
                            <th>User</th>
                            <th>Total</th>
                        </tr>
                    <?php
                    $sumtotal = 0;
                    $counter = 2;
                    foreach ($cost_array as $total) {
                        $select_user = "SELECT * FROM login WHERE id = '" . $total["userid"] . "'";
                        $query = $mysqli->query($select_user);
                        $user = $query->fetch_object();
                        if($counter % 2 == 0) {
                            echo '<tr class="one">';
                        } else {
                            echo '<tr class="two">';
                        }
                        echo "		<td>" . $user->firstname . " " . $user->lastname . "</td><td>&euro; " . number_format($total["total"], 2,',','.') . "</td>\n";
                        echo "	</tr>\n";
                        $sumtotal = $sumtotal + $total["total"];
                        $counter++;
                    } ?>
                        <tr>
                            <td colspan="2">Summe: &euro; <?php echo number_format($sumtotal, 2,',','.') ?></td>
                        </tr>
                    </table>
                </td>
                <td style="border: none; vertical-align: top; padding-left: 41px;">
                    <table id="item">
                        <tr>
                            <th style="border-left: 3px solid darkred; border-right: 3px solid darkred; border-top: 3px solid darkred; ">Zusammenfassung</th>
                        </tr>
                        <tr>
                            <td style="border-left: 3px solid darkred; border-right: 3px solid darkred; "><?php
                                $summary_array = Array();
                                $select_summary = "SELECT DISTINCT delivery_text FROM deliverys WHERE delivery_number = '$dn' and owner = '$owner'";
                                $query_summary = $mysqli->query($select_summary);
                                $n = 0;
                                $count_sum = 0;
                                while ($row = $query_summary->fetch_object()) {
                                    $piece = $row->delivery_text;
                                    $select_piece_count = "SELECT COUNT(delivery_text) FROM deliverys WHERE delivery_number = '$dn' and delivery_text = '$piece'";
                                    $query_piece = $mysqli->query($select_piece_count);
                                    $row_piece = $query_piece->fetch_assoc();
                                    $summary_array[$n]["id"] = $piece;
                                    $summary_array[$n]["count"] = $row_piece["COUNT(delivery_text)"];
                                    $n++;
                                }
                                foreach ($summary_array as $output) {
                                    $food_id = $output["id"];
                                    $select_food = "SELECT * FROM menue WHERE id = '$food_id'";
                                    $query = $mysqli->query($select_food);
                                    $row = $query->fetch_assoc();
                                    if($row["size"] != "-") {
                                        echo $output["count"] . "x " . $row["sub_category"] . " " . $row["item"] . " " . $row["size"] . "<br>";
                                    } else {
                                        echo $output["count"] . "x " . $row["sub_category"] . " " . $row["item"] . "<br>";
                                    }
                                    $count_sum = $count_sum + $output["count"];
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-left: 3px solid darkred; border-right: 3px solid darkred; border-bottom: 3px solid darkred; ">
                                <?php echo "Total Items:" . $count_sum; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
    </table>
        <?php
        echo '<table class="table">';
        if($_SESSION["user"]["id"] == $owner AND $lock == "0") {
            echo '<br><a href="lock_order.php?dn='.$dn.'&owner='.$owner.'">Bestellung sperren</a><br><br>';
        }
        if($_SESSION["user"]["id"] == $owner AND $lock == "1" AND $status == "0") {
            echo '<br><a href="overview.php?dn='.$dn.'&owner='.$owner.'&do=unlock">Bestellung entsperren</a><br><br>';
        }
        if ($_SESSION["user"]["id"] == $owner AND $status == "0") {
            echo 'Helfer:';
            $select_helper = "SELECT * FROM login WHERE id != '$owner' AND active = TRUE ORDER BY lastname";
            $query = $mysqli->query($select_helper);
            echo '<form action="close_order.php?dn='. $dn .'" method="post">';
            $helper_array = array();
            while ($row_helper = $query->fetch_assoc()) {                
                $helper_array[] = $row_helper;
            }
            $count_helper_array = count($helper_array);
            for($i = 0; $i < 10; $i++) {
                echo '<tr>';
                for($c = 0; $c < ($count_helper_array / 10); $c++) {                    
                    $add = $c.'0';
                    $key = $i + $add;
                    if(array_key_exists($key, $helper_array)) {
                        ?>
                        <td>
                            <input type="checkbox" name="helper[]" value="<?php echo $helper_array[$key]['id']; ?>">                            
                            <?php echo $helper_array[$key]['firstname']." ".$helper_array[$key]['lastname']; ?>
                        </td>
                        <?php
                    } else {
                        echo '<td></td>';
                    }
                }
                echo '</tr>';
            }
            echo '</table><br>';
            echo '<input type="submit" value="Bestellung abschließen">';
            echo '</form>';
        }
        echo '<br><br><a href="overview.php">Zurück zur Übersicht</a>';

    }
    /* Overview Order - End */
} else {
    echo 'Session abgelaufen. Bitte neu <a href="index.php">einloggen</a>';
}
$mysqli->close();
/* $html = ob_get_clean();

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
echo $tidy; */