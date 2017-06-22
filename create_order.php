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

$smarty = new Smarty_mjam();
$smarty->assign('current_site', substr($_SERVER['SCRIPT_NAME'],1));

if(isset($_SESSION["user"])) {
	?>
    <head>
        <link rel="stylesheet" href="config/style.css">
    </head>
	<body>
	<?php
		$dn = date("U", time());
		$owner = $_SESSION["user"]["id"];
                $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);
		if($page == "menue") {
			$food = filter_input(INPUT_POST, 'food', FILTER_SANITIZE_SPECIAL_CHARS);
		}
	?>
	<form action="create_order.php?page=menue" method="post">
		<select name="food" onchange="this.form.submit()">
			<option></option>
			<option value="pizza">Pizza (Pizzakeller)</option>
			<option value="noodles">Noodles</option>
			<option value="kebap">Kepab</option>
			<option value="schnitzel">Schnitzel</option>
                        <?php if($_SESSION["user"]["id"] == "1" OR $_SESSION["user"]["id"] == "2") { echo '<option value="test">Test</option>'; } ?>
		</select>
	</form>
	<?php
	if (isset($food)) {            
            if($food == 'test') {
                echo '<form action="order2.php" method="post">';
            } else {
                echo '<form action="order.php" method="post">';
            }
		?>		
                    <table class="sub">
                        <tr>
                            <td>
                                <input type="checkbox" name="check" value="1">
                            </td>
                            <td>
                                Bestellung möglich bis: <input name="autolock" type="time" step="900" value="00:00">
                            </td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="mail_check" value="1" checked></td>
                            <td>Infomail aussenden?</td>
                        </tr>
                    </table>
                    <br>
                    <?php echo 'Dein Gutehaben: € '.number_format(checkBalance($_SESSION['user']['id']),2,',','.'); ?>
                    <br><br>
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
                            $i = 1;
                            while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr class="<?php if($i % 2 == 0) { echo "one"; } else { echo "two"; } ?>">
                                            <td><?php echo $row["sub_category"]; ?></td>
                                            <td><?php echo $row["item"]; ?></td>
                                            <td><?php echo $row["size"]; ?></td>
                                            <td>
                                                    <?php
                                                    if ($food == "noodles") {
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
                                                    } elseif ($food == "schnitzel") {
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
                                                            <?php
                                                    } elseif ($food == "kebap") {
                                                            ?>
                                                            <table class="embbed">
                                                                    <tr>
                                                                            <td>Ohne Salat</td>
                                                                            <td><input type="checkbox" name="sauce[]"
                                                                                               value="<?php echo $row["id"]; ?>*salat"></td>
                                                                    </tr>
                                                                    <tr>
                                                                            <td>Ohne Zwiebel</td>
                                                                            <td><input type="checkbox" name="sauce[]"
                                                                                               value="<?php echo $row["id"]; ?>*zwiebel"></td>
                                                                    </tr>
                                                                    <tr>
                                                                            <td>Ohne Tomate</td>
                                                                            <td><input type="checkbox" name="sauce[]"
                                                                                               value="<?php echo $row["id"]; ?>*tomate"></td>
                                                                    </tr>
                                                                    <tr>
                                                                            <td>Ohne Sauce</td>
                                                                            <td><input type="checkbox" name="sauce[]"
                                                                                               value="<?php echo $row["id"]; ?>*sauce"></td>
                                                                    </tr>
                                                                    <tr>
                                                                            <td>Ohne Scharf</td>
                                                                            <td><input type="checkbox" name="sauce[]"
                                                                                               value="<?php echo $row["id"]; ?>*scharf"></td>
                                                                    </tr>
                                                                    <tr>
                                                                            <td>Ohne Rotkraut</td>
                                                                            <td><input type="checkbox" name="sauce[]"
                                                                                               value="<?php echo $row["id"]; ?>*rotkraut"></td>
                                                                    </tr>
                                                            </table>
                                                            <?php
                                                    } else {
                                                            echo '- <input type="hidden" value="-" name="sauce[]">';
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
                    <input type="hidden" value="1" name="new">
                    <input type="submit" value="Bestellen">
		</form>

		</body>
		<?php
	}
        $smarty->assign('userBalance', checkBalance($_SESSION['user']['id']));
        $smarty->display('create_order.tpl');
} else {
    echo 'Session abgelaufen. Bitte neu <a href="index.php">einloggen</a>';
}
$mysqli->close();