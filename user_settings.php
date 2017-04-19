<?php
/**
 * Created by PhpStorm.
 * User: uyve7bo
 * Date: 01.04.2016
 * Time: 06:42
 */
require_once("config/config.php");
require_once("config/db_cnx.php");
header('Content-Type: text/html; charset=utf-8');
session_start();
if($_SESSION["user"]["id"] == "1") {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
if(isset($_SESSION["user"])) {
    // Page Content comes here
    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);
    if($page == "save") {
        $id = $_SESSION["user"]["id"];
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        if(filter_input(INPUT_POST, 'notify', FILTER_SANITIZE_SPECIAL_CHARS) == NULL) {
            $notify = "0";
        } else {
            $notify = "1";
        }
        if(filter_input(INPUT_POST, 'active', FILTER_SANITIZE_SPECIAL_CHARS) == NULL) {
            $active = FALSE;
        } else {
            $active = TRUE;
        }
        
        $update_settings = "UPDATE login SET email = ?, notify = ?, active = ? WHERE id = ?";
        $stmt = $mysqli->prepare($update_settings);
        $stmt->bind_param("ssss", $email, $notify, $active, $id);
        if($stmt->execute()) {
            $_SESSION["user"]["notify"] = $notify;
            $_SESSION["user"]["active"] = $active;
            echo "Einstellungen gespeichert";
            echo '<meta http-equiv="refresh" content="2; URL=user_settings.php" />' . "\n";
            die;
        } else {
            echo 'Etwas ist schief gelaufen, bitte versuche es erneut.<br><br>';
            echo '<a href="user_settings.php">Zurück</a>';
            die;
        }
    } else {
        ?>
        <head>
            <link rel="stylesheet" href="config/style.css">
        </head>
        <body>
            <form action="user_settings.php?page=save" method="post">
                <table>
                    <tr>

                        <td><label for="email">Email Adresse:</label></td>
                        <td><input id="email" type="text" name="email" value="<?php echo $_SESSION["user"]["email"]; ?>" size="30">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="notify">Email Benachrichtungen:</label></td>
                        <td>
                            <?php
                                if ($_SESSION["user"]["notify"] == TRUE) {
                            ?>
                                    <input id="notify" type="checkbox" name="notify" value="0" checked>
                            <?php
                                } else {
                            ?>
                                    <input id="notify" type="checkbox" name="notify" value="1">
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="active">Account Aktiv?</label></td>
                        <td>
                            <?php
                                if ($_SESSION["user"]["active"] == "1") {
                            ?>
                                    <input id="active" type="checkbox" name="active" value="0" checked>
                            <?php
                                } else {
                            ?>
                                    <input id="active" type="checkbox" name="active" value="1">
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Passwort:</td>
                        <td><a href="changepwd.php">Ändern</a></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Speichern"></td>
                    </tr>
                </table>
            </form>
            <a href="main.php">Zurück</a>
        </body>
        <?php
        // Page Content ends here
    }
} else {
    echo 'Session abgelaufen. Bitte neu <a href="index.php">einloggen</a>';
}
$mysqli->close();
?>