<?php
/**
 * Created by PhpStorm.
 * User: Loki
 * Date: 13.10.2015
 * Time: 10:17
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
    <html>
        <head>
            <link rel="stylesheet" href="config/style.css">
        </head>
        <script   src="https://code.jquery.com/jquery-2.2.3.js" integrity="sha256-laXWtGydpwqJ8JA+X9x2miwmaiKhn8tVmOVEigRNtP4=" crossorigin="anonymous"></script>
    <body>
    <?php
        $input_food = filter_input(INPUT_POST, 'food', FILTER_SANITIZE_SPECIAL_CHARS);
        $select_open_orders = "SELECT * FROM deliverys WHERE status != '1' AND locked != '1' GROUP BY delivery_number";
        $query = $mysqli->query($select_open_orders);
        if ($query->num_rows == "0") {
            echo "Keine offenen Bestellungen<br><br>";
            ?>
            <form action="create_order.php" method="post">
                <input type="hidden" name="userid" value="<?php echo $_SESSION["user"]["id"]; ?>">
                <input type="submit" value="Neue Bestellung anlegen">
            </form>
            <?php
        } else {
            ?>
            Bitte Bestellung auswählen:<br>
            <form action="menue.php" method="post">
                <select name="food" onchange="this.form.submit()">
                    <option></option>
                    <?php

                    while ($row = $query->fetch_object()) {
                        $owner_id = $row->owner;
                        $select_owner = "SELECT * FROM login WHERE id = '$owner_id'";
                        $query_owner_name = $mysqli->query($select_owner);
                        while ($row_owner = $query_owner_name->fetch_object()) {
                            $owner_fn = $row_owner->firstname;
                            $owner_ln = $row_owner->lastname;
                            $owner_full = $owner_fn . " " . $owner_ln;
                        }
                        $date = new DateTime();
                        $date_output = $date->setTimestamp($row->delivery_number)->format('d.m.Y');
                        $category = ucfirst($row->category);
                        echo "<option value='$row->category|$row->delivery_number|$owner_id'>$date_output - $category - $owner_full</option>";
                    }
                    ?>
                </select>
            </form>
            <?php
        }
    echo '<a href="main.php">Zurück zur Hauptseite</a><br><br>';
    if ($input_food != NULL) {
        $balance = checkBalance($_SESSION['user']['id']);
        echo 'Dein Guthaben: € '.number_format($balance,2,',','.').'<br><br>';
        $array = explode("|", filter_input(INPUT_POST, 'food', FILTER_SANITIZE_SPECIAL_CHARS));
        $food = $array[0];
        $dn = $array[1];
        $owner = $array[2];
    }
    if (isset($food)) {
        if($food == "kebap" or $food == "schnitzel") { ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('td:nth-child(3),th:nth-child(3)').hide();
                })
            </script>
        <?php } 
        if($food == 'test') {
            echo '<form action="order2.php" method="post">';
        } else {
            echo '<form action="order.php" method="post">';
        }
        ?>        
            <table class="sub">
                <tr>
                    <th>Sub Cat</th>
                    <th>Item</th>
                    <th>Size</th>
                    <th>Extras</th>
                    <th>Prize</th>
                    <th>Amount</th>
                    <th>Check</th>
                </tr>
                <?php
                $select = "SELECT * FROM menue WHERE category = '$food' ORDER BY sub_category";
                $result = $mysqli->query($select);
                if (!$result) {
                    printf("Errormessage: %s\n", $mysqli->error);
                }
                $i = 2;
                while ($row = $result->fetch_assoc()) {
                    ?>
                <tr class="<?php if($i % 2 == 0) { echo "one"; } else { echo "two"; } ?>">
                            <td><?php echo $row["sub_category"]; ?></td>
                            <td><?php echo $row["item"]; ?></td>
                            <td><?php echo $row["size"]; ?></td>
                            <td>
                                <?php
                                if($food == "noodles") {
                                    ?>
                                    <select size="1" name="sauce[]">
                                        <option value="false"></option>
                                        <option value="<?php echo $row["id"]; ?>*Ohne">Ohne</option>
                                        <option value="<?php echo $row["id"]; ?>*Soja">Soja</option>
                                        <option value="<?php echo $row["id"]; ?>*Süß-Sauer">Süß-Sauer</option>
                                        <option value="<?php echo $row["id"]; ?>*Teriyaki">Teriyaki</option>
                                        <option value="<?php echo $row["id"]; ?>*Scharf">Scharf</option>
                                    </select>

                                    <?php
                                }
                                elseif($food == "schnitzel") {
                                    ?>
                                    <table class="embbed">
                                        <tr>
                                            <td>Ketchup</td>
                                            <td><input type="checkbox" name="sauce[]"
                                                       value="<?php echo $row["id"]; ?>*Ketchup"></td>
                                        </tr>
                                        <tr>
                                            <td>Mayo</td>
                                            <td><input type="checkbox" name="sauce[]"
                                                       value="<?php echo $row["id"]; ?>*Mayo"></td>
                                        </tr>
                                        <tr>
                                            <td>Senf</td>
                                            <td><input type="checkbox" name="sauce[]"
                                                       value="<?php echo $row["id"]; ?>*Senf"></td>
                                        </tr>
                                        <tr>
                                            <td>Salat</td>
                                            <td><input type="checkbox" name="sauce[]"
                                                       value="<?php echo $row["id"]; ?>*Salat"></td>
                                        </tr>
                                    </table>
                                    <!--
                                    <select size="1" name="sauce[]">
                                        <option value="false"></option>
                                        <option value="<?php echo $row["id"]; ?>*Ohne">Ohne</option>
                                        <option value="<?php echo $row["id"]; ?>*Ketchup">Ketchup</option>
                                        <option value="<?php echo $row["id"]; ?>*Mayo">Mayo</option>
                                        <option value="<?php echo $row["id"]; ?>*Senf">Senf</option>
                                        <option value="<?php echo $row["id"]; ?>*Ketchup & Mayo">Ketchup & Mayo</option>
                                        <option value="<?php echo $row["id"]; ?>*Ketchup & Mayo">Ketchup & Senf</option>
                                    </select> -->
                                    <?php
                                }
                                elseif($food == "kebap") {
                                    ?>
                                    <table class="embbed">
                                        <tr>
                                            <td>Ohne Salat</td>
                                            <td><input type="checkbox" name="sauce[]" value="<?php echo $row["id"]; ?>*salat"></td>
                                        </tr>
                                        <tr>
                                            <td>Ohne Zwiebel</td>
                                            <td><input type="checkbox" name="sauce[]" value="<?php echo $row["id"]; ?>*zwiebel"></td>
                                        </tr>
                                        <tr>
                                            <td>Ohne Tomate</td>
                                            <td><input type="checkbox" name="sauce[]" value="<?php echo $row["id"]; ?>*tomate"></td>
                                        </tr>
                                        <tr>
                                            <td>Ohne Sauce</td>
                                            <td><input type="checkbox" name="sauce[]" value="<?php echo $row["id"]; ?>*sauce"></td>
                                        </tr>
                                        <tr>
                                            <td>Ohne Scharf</td>
                                            <td><input type="checkbox" name="sauce[]" value="<?php echo $row["id"]; ?>*scharf"></td>
                                        </tr>
                                        <tr>
                                            <td>Ohne Rotkraut</td>
                                            <td><input type="checkbox" name="sauce[]" value="<?php echo $row["id"]; ?>*rotkraut"></td>
                                        </tr>
                                    </table>
                                    <?php
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                            <td>€ <?php echo $row["prize"]; ?></td>
                            <td>
                                <input type="number" name="<?php echo $row["id"]; ?>_amount" min="1" max="5" value="1">
                            </td>
                            <td>
                                <input type="checkbox" value="<?php echo $row["id"]; ?>" name="foodid[]">
                            </td>
                            </tr>
                    <?php
                    $i++;
                }
                ?>
            </table>
            <br>
            <input type="hidden" value="<?php echo $_SESSION["user"]["id"]; ?>" name="userid">
            <input type="hidden" value="<?php echo $dn; ?>" name="dn">
            <input type="hidden" value="<?php echo $owner; ?>" name="owner">
            <input type="hidden" value="<?php echo $food; ?>" name="cat">
            <input type="submit" value="Bestellen">
        </form>

        <?php

    }
    ?>
    </body>
    </html>
    <?php
} else {
    echo 'Session abgelaufen. Bitte neu <a href="index.php">einloggen</a>';
}
$mysqli->close();