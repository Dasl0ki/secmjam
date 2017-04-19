<?php
/**
 * Created by PhpStorm.
 * User: uyve7bo
 * Date: 12.04.2016
 * Time: 10:24
 */
header('Content-Type: text/html; charset=utf-8');
ini_set("display_errors", 1);
error_reporting(E_ALL | E_STRICT);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once("config/config.php");
require_once("config/db_cnx.php");
if(isset($_GET["page"]) and $_GET["page"] == "send") {
    $user = strtolower($_POST["user"]);

    //Get User email adress
    $select_mail = "SELECT email FROM login WHERE user = ?";
    $stmt = $mysqli->prepare($select_mail);
    $stmt->bind_param("s", $user);
    if($stmt->execute()) {
        $result = $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($data);
        $stmt->fetch();

        //Generate userhash
        $hash = $user.date("Ymd", time());
        $hash = md5($hash);
        $hash = substr($hash, 0, 6);

        //Send user mail
        $headers = array();
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        $headers[] = "From: SEC-Mjam <no-reply@loki-net.at>";
        $headers[] = "Reply-To: SEC-Mjam <no-reply@loki-net.at>";
        $headers[] = "Subject: Passwort Rücksetzung";
        $headers[] = "X-Mailer: PHP/".phpversion();

        $betreff = "Subject: Passwort Rücksetzung";
        $from = "From: SEC-Mjam <no-reply@loki-net.at>";
        $text = 'Es wurde ein neues Passwort für Ihren User angefordert. Sollten sie dies nicht veranlasst haben können sie diese Email ignorieren.<br><br>
                Sollten sie Ihr Passwort zurücksetzen wollen klicken sie bitte den unten stehenden Link. Sollte dies nicht funktionieren kann er auch
                in die Adressezeile Ihres Browser kopiert werden<br><br>
                <a href="http://sec-mjam.loki-net.at/pwd_forget.php?page=reset&check='.$hash.'">http://sec-mjam.loki-net.at/pwd_forget.php?page=reset&check='.$hash.'</a>';
        mail($data, $betreff, $text, implode("\r\n", $headers));

        echo "Ein Verifizierungsmail wurde an die hinterlegte Mailadresse gesendet. Bitte überprüfen sie gegebenenfalls auch den Spam-Ordner";
    }
} elseif(isset($_GET["page"]) and $_GET["page"] == "reset") {
    //Get user from hash
    $check_hash = $_GET["check"];
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
} elseif(isset($_GET["page"]) and $_GET["page"] == "change") {
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
    ?>

    <form method="post" action="pwd_forget.php?page=send">
        <table>
            <tr>
                <td><label for="user">Username:</label></td>
                <td>
                    <input type="text" name="user" id="user">
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Senden"></td>
            </tr>
        </table>
    </form>
    <?php
}
?>