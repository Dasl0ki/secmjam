<?php
/**
 * Created by PhpStorm.
 * User: uyve7bo
 * Date: 01.04.2016
 * Time: 09:54
 */
require_once("config/config.php");
require_once("config/db_cnx.php");
header('Content-Type: text/html; charset=utf-8');
session_start();
if($_SESSION["user"]["id"] == "1") {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
ob_start();
if(isset($_SESSION["user"])) {
    // Page Content comes here
    if(isset($_GET["page"]) AND $_GET["page"] == "change") {
        $id = $_SESSION["user"]["id"];
        $oldpwd = md5($_POST["oldpwd"]);
        $pwd = md5($_POST["pwd"]);
        $pwd2 = md5($_POST["pwd2"]);

        $getusercred = "SELECT * FROM login WHERE id = '$id'";
        $query = $mysqli->query($getusercred);
        while($row = $query->fetch_object()) {
            if($oldpwd == $row->pwd) {
                if($pwd == $pwd2) {
                    $update_pwd = "UPDATE login SET pwd = ? WHERE id = ?";
                    $stmt = $mysqli->prepare($update_pwd);
                    $stmt->bind_param("ss", $pwd, $id);
                    if($stmt->execute()) {
                        echo "Passwort geändert";
                        echo '<meta http-equiv="refresh" content="2; URL=user_settings.php" />' . "\n";
                        die;
                    } else {
                        echo 'Etwas ist schief gelaufen, bitte versuche es erneut.<br><br>';
                        echo '<a href="changepwd.php">Zurück</a>';
                        die;
                    }
                } else {
                    echo 'Passwort stimmt nicht überein<br>';
                    echo '<a href="changepwd.php">Zurück</a><br><br><br>';
                    die;
                }
            } else {
                echo 'Aktuelles Passwort nicht korrekt<br><br>';
                echo '<a href="changepwd.php">Zurück</a><br>';
                die;
            }
        }
    } else {
        ?>
        <head>
            <link rel="stylesheet" href="config/style.css">
        </head>
        <body>
        <form action="changepwd.php?page=change" method="post">
            <table>
                <tr>
                    <td><label for="oldpwd">Aktuelles Passwort:</label></td>
                    <td><input id="oldpwd" name="oldpwd" type="password"</td>
                </tr>
                <tr>
                    <td><label for="pwd">Neues Passwort:</label></td>
                    <td><input type="password" id="pwd" name="pwd"></td>
                </tr>
                <tr>
                    <td><label for="pwd2">Neues Passwort wiederholen</label></td>
                    <td><input type="password" id="pwd2" name="pwd2"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Absenden"></td>
                </tr>
            </table>
        </form>
        <br>
        <a href="user_settings.php">Zurück</a>
        </body>
        <?php
    }
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