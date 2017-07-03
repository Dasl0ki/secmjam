<?php
/**
 * Created by PhpStorm.
 * User: uyve7bo
 * Date: 12.04.2016
 * Time: 10:24
 */
header('Content-Type: text/html; charset=utf-8');
require_once("config/config.php");
require_once("config/db_cnx.php");
require 'config/setup.php';
require 'config/functions.php';
forceSSL();

$smarty = new Smarty_mjam();

$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);
$flag_no_mail = FALSE;

if($page == "send") {
    $email = strtolower(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS));

    $select_mail = "SELECT * FROM login WHERE email = ?";
    $stmt = $mysqli->prepare($select_mail);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 0) {
        $flag_no_mail = TRUE;
        echo "No Mail";
    } else {
        $userData = $result->fetch_assoc();

        //Generate userhash
        $hash = $userData['user'] . date("Ymd", time());
        $hash = md5($hash);
        $hash = substr($hash, 0, 6);

        //Send user mail
        $headers = array();
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        $headers[] = "From: SEC-Mjam <no-reply@loki-net.at>";
        $headers[] = "Reply-To: SEC-Mjam <no-reply@loki-net.at>";
        $headers[] = "Subject: Passwort Rücksetzung";
        $headers[] = "X-Mailer: PHP/" . phpversion();

        $betreff = "Subject: Passwort Rücksetzung";
        $from = "From: SEC-Mjam <no-reply@loki-net.at>";
        $text = 'Es wurde ein neues Passwort für Ihren User angefordert. Sollten sie dies nicht veranlasst haben können sie diese Email ignorieren.<br><br>
            Sollten sie Ihr Passwort zurücksetzen wollen klicken sie bitte den unten stehenden Link. Sollte dies nicht funktionieren kann er auch
            in die Adressezeile Ihres Browser kopiert werden<br><br>
            <a href="http://sec-mjam.loki-net.at/pwd_forget.php?page=reset&check=' . $hash . '">http://sec-mjam.loki-net.at/pwd_forget.php?page=reset&check=' . $hash . '</a>';
        mail($userData['email'], $betreff, $text, implode("\r\n", $headers));

        echo "Ein Verifizierungsmail wurde an die hinterlegte Mailadresse gesendet. Bitte überprüfen sie gegebenenfalls auch den Spam-Ordner";
    }
} elseif($page == "reset") {
    //Get user from hash
    $check_hash = filter_input(INPUT_GET, 'check');
    $select_user = "SELECT * FROM login";
    $query = $mysqli->query($select_user);
    while($row = $query->fetch_assoc()) {
        $userhash = $row["user"].date("Ymd", time());
        $userhash = md5($userhash);
        $userhash = substr($userhash, 0, 6);
        if($userhash == $check_hash) {
            //Pwd interface
            $id = $row["id"];?>
            <form action="pwd_forget.php?page=change" method="post">
                <table>
                    <tr>
                        <td><label for="pwd">Neues Passwort:</label></td>
                        <td>
                            <input type="password" name="pwd">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="pwd2">Passwort wiederholen:</label></td>
                        <td>
                            <input type="password" name="pwd2">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" value="Passwort ändern">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="hash" value="<?php echo $userhash; ?>">
                        </td>
                    </tr>
                </table>
            </form><?php
        }
    }
} elseif($page == "change") {
    $id = $_POST["id"];
    $pwd = md5($_POST["pwd"]);
    $pwd2 = md5($_POST["pwd2"]);
    $hash = $_POST["hash"];

    if($pwd == $pwd2) {
        $update_pwd = "UPDATE login SET pwd = ? WHERE id = ?";
        $stmt = $mysqli->prepare($update_pwd);
        $stmt->bind_param("ss", $pwd, $id);
        if($stmt->execute()) {
            echo "Passwort geändert";
            echo '<meta http-equiv="refresh" content="2; URL=index.php" />' . "\n";
            die;
        } else {
            echo 'Etwas ist schief gelaufen, bitte versuche es erneut.<br><br>';
            echo '<a href="pwd_forget.php?page=reset&check='.$hash.'">Zurück</a>';
            die;
        }
    } else {
        echo 'Passwort stimmt nicht überein<br>';
        echo '<a href="pwd_forget.php?page=reset&check='.$hash.'">Zurück</a><br><br><br>';
        die;
    }
} else {
    $smarty->display('pwd_forget.tpl');
}
?>