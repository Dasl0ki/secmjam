<?php
/**
 * Created by PhpStorm.
 * User: Loki
 * Date: 19.11.2015
 * Time: 10:13
 */
require_once("config/config.php");
require_once("config/db_cnx.php");
header('Content-Type: text/html; charset=utf-8');
session_start();
if($_SESSION["user"]["id"] == "1") {
    ini_set("display_errors", 1);
    error_reporting(E_ALL | E_STRICT);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}

if(isset($_GET["page"])) {
    if ($_GET["page"] == "register") {
        $captcha = $_POST["g-recaptcha-response"];
        $user = strtolower($_POST["user"]);
        $email = $_POST["email"];
        $firstname = ucfirst($_POST["firstname"]);
        $lastname = ucfirst($_POST["lastname"]);
        $pwd = md5($_POST["pwd"]);
        $pwd2 = md5($_POST["pwd2"]);

        if (!$captcha) {
            echo 'Bitte Captcha lösen<br><br>';
            echo '<a href="register.php">Zurück</a>';
            die;
        } else {
            $captcha_response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $config['captcha']["secret_key"] . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
            if ($captcha_response['success'] == false) {
                echo 'Captcha Wrong<br><br>';
                echo '<a href="register.php">Zurück</a>';
            } else {
                $select_users = "SELECT user FROM login";
                $query_user = $mysqli->query($select_users);
                while($row = $query_user->fetch_object()) {
                    if($row->user == $user) {
                        echo "Username bereits vergeben<br>";
                        echo '<a href="register.php">Zurück</a>';
                        die;
                    }
                }
                if ($pwd != $pwd2) {
                    echo "Passwort stimmt nicht überein<br><br>";
                    echo '<a href="register.php">Zurück</a>';
                    die;
                } else {
                    $insert = "INSERT INTO login (user, firstname, lastname, email, pwd) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $mysqli->prepare($insert);
                    $stmt->bind_param("sssss", $user, $firstname, $lastname, $email, $pwd);
                    if($stmt->execute()) {
                        //$stmt->execute();
                        $stmt->close();
                        echo 'Registrierung erfolreich. Zurück zum <a href="index.php">Login</a>';
                        $mysqli->close();
                        die;
                    } else {
                        echo 'Etwas ist schief gelaufen.';
                        die;
                    }
                }
            }
        }
    }
}
?>
<html>
    <head>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <form method="post" action="register.php?page=register">
            <table>
                <tr>
                    <td>User:</td>
                    <td><input type="text" size="20" name="user"></td>
                </tr>
                <tr>
                    <td>E-Mail:</td>
                    <td><input type="text" size="20" name="email"></td>
                </tr>
                <tr>
                    <td>Vorname:</td>
                    <td><input type="text" size="20" name="firstname"></td>
                </tr>
                <tr>
                    <td>Nachname:</td>
                    <td><input type="text" size="20" name="lastname"></td>
                </tr>
                <tr>
                    <td>Passwort:</td>
                    <td><input type="password" size="20" name="pwd"></td>
                </tr>
                <tr>
                    <td>Passwort bestätigen:</td>
                    <td><input type="password" size="20" name="pwd2"></td>
                </tr>
                <tr>
                    <td colspan="2"><div class="g-recaptcha" data-sitekey="6Le0SxwTAAAAAKxJsiY4VqGjZmmtTCLcgpswr4xv"></div></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Absenden"></td>
                </tr>
            </table>
        </form>
    </body>
</html>
