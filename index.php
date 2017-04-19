<?php
/**
 * Created by PhpStorm.
 * User: Loki
 * Date: 13.10.2015
 * Time: 08:01
 */
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
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
    echo '<meta http-equiv="refresh" content="1; URL=main.php" />' . "\n";
    die;
}
?>
    <head>
        <title>SEC-Mjam</title>
        <link rel="stylesheet" href="config/style.css">
    </head>
<?php
$verhalten = 0;
$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);

if(!isset($_SESSION["user"]) and $page != NULL) {
    $verhalten = 0;
}

if($page == "login") {
    $user = strtolower(filter_input(INPUT_POST, 'user', FILTER_SANITIZE_SPECIAL_CHARS));
    $pwd = md5(filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_SPECIAL_CHARS));

    if($pwd == "d41d8cd98f00b204e9800998ecf8427e") {
            $verhalten = 4; // 4 = Wrong Password given
    } else {
        $control = 0; // PWD Check Status, 0 = not checked
        $login = doLogin($user, $pwd);
        
        if($login != FALSE) {
            $control++;
        } else {
            $verhalten = 2;
        }
        
        if($control != 0) {
            $_SESSION["user"] = $login;
            $verhalten = 3; // 3 = Login
        } else {
            $verhalten = 2; // 2 = Login failed
        }

    }
}

if($verhalten == 0) {
    echo '
<html>
    <head>
    </head>
    <body>
        <form action="index.php?page=login" method="post">
            <table>
                <tr>
                    <td>Login:</td>
                    <td><input type="text" name="user"></td>
                </tr>
                <tr>
                    <td>PWD:</td>
                    <td><input type="password" name="pwd"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Login"></td>
                </tr>
            </table>
        </form>
        <a href="register.php">Registrieren</a> <a href="pwd_forget.php">Passwort vergessen?</a>
    </body>
</html>
    ';
}

if($verhalten == 1) {
    echo 'Ersteinstieg';
}

if($verhalten == 2) {
    echo 'Login failed';
}

if($verhalten == 3) {
        echo 'Login successful';
        echo '<meta http-equiv="refresh" content="3; URL=main.php" />' . "\n";
}

if($verhalten == 4) {
    echo 'No Password';
}
$mysqli->close();