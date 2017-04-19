<?php
/**
 * Created by PhpStorm.
 * User: Loki
 * Date: 13.10.2015
 * Time: 09:25
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
        <title>SEC Mjam</title>
        <link rel="stylesheet" href="config/style.css">
        <script>
            function getVote(int) {
                xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("poll").innerHTML = xmlhttp.responseText;
                    }
                };
                xmlhttp.open("GET", "poll_vote.php?vote=" + int, true);
                xmlhttp.send();
            }
        </script>
    </head>
    <body>
        <div style="width: 100%; height: auto;">
            <table class="main">
                <tr>
                    <td align="center" style="width: 15%;">
                        <?php include("links.php"); ?>
                    </td>
                </tr>
                <tr>
                    <td class="content_cell">
                        <table class="container" style="width: 100%">
                            <tr>
                                <td style="width: 50%; vertical-align: top;">
                                    <table style="margin-left: auto; margin-right: auto; width: 85%" class="container_table">
                                        <tr>
                                            <th colspan="2" style="text-align: center;">
                                                Übersicht
                                            </th>
                                        </tr>
                                        <tr>
                                            <td style="width: 33%">
                                                Guthaben
                                            </td>
                                            <td>
                                                <?php echo '€ '.number_format(checkBalance($_SESSION['user']['id']),2,',','.'); ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>    
                                <td style="width: 50%;">
                                    <?php
                                    if($_SESSION["user"]["vote"] != "1") {
                                        ?>
                                        <div id="poll">
                                            <h3><label for="vote">Todays Lunch?</label></h3>
                                            <form>
                                                <table style="width: 85%; margin-left: auto; margin-right: auto;" class="container_table">
                                                    <tr>
                                                        <td style="width: 30%;">Noodles:</td>
                                                        <td><input id="vote" type="radio" name="vote" value="1" onclick="getVote(this.value)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 30%;">Pizza:</td>
                                                        <td><input id="vote" type="radio" name="vote" value="2" onclick="getVote(this.value)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 30%;">Kebap:</td>
                                                        <td><input id="vote" type="radio" name="vote" value="3" onclick="getVote(this.value)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 30%;">Schnitzel:</td>
                                                        <td><input id="vote" type="radio" name="vote" value="4" onclick="getVote(this.value)"></td>
                                                    </tr>
                                                </table>
                                            </form>
                                        </div>
                                    <?php } else {
                                        //get content of textfile
                                        $filename = "config/poll_result.txt";
                                        $content = file($filename);

                                        //put content in array
                                        $array = explode("||", $content[0]);
                                        $noodles = $array[0];
                                        $pizza = $array[1];
                                        $kebap = $array[2];
                                        $schnitzel = $array[3];
                                        $sum = $noodles + $pizza + $kebap + $schnitzel;
                                        if($sum == 0) {
                                            $noodles_percent = 0;
                                            $pizza_percent = 0;
                                            $kebap_percent = 0;
                                            $schnitzel_percent = 0;                                            
                                        } else {
                                            $noodles_percent = 100*round($noodles/$sum,2);
                                            $pizza_percent = 100*round($pizza/$sum,2);
                                            $kebap_percent = 100*round($kebap/$sum,2);
                                            $schnitzel_percent = 100*round($schnitzel/$sum,2);
                                        }

                                        ?>
                                        <div id="poll">
                                            <table style="width: 85%; vertical-align: middle; margin-left: auto; margin-right: auto;" class="container_table">
                                                <tr>
                                                    <td>
                                                        Noodles:
                                                    </td>
                                                    <td id="progress_cell">
                                                        <div style="width: 100%">
                                                            <div style="text-align: center; color: darkblue; background: #3aacff; width: <?php echo $noodles_percent; ?>%;">
                                                                <?php echo $noodles_percent; ?>%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Pizza:
                                                    </td>
                                                    <td id="progress_cell">
                                                        <div style="width: 100%">
                                                            <div style="text-align: center; color: darkblue; background: #3aacff; width: <?php echo $pizza_percent; ?>%;">
                                                                <?php echo $pizza_percent; ?>%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Kebap:
                                                    </td>
                                                    <td id="progress_cell">
                                                        <div style="width: 100%">
                                                            <div style="text-align: center; color: darkblue; background: #3aacff; width: <?php echo $kebap_percent; ?>%;">
                                                                <?php echo $kebap_percent; ?>%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Schnitzel:
                                                    </td>
                                                    <td id="progress_cell">
                                                        <div style="width: 100%">
                                                            <div style="text-align: center; color: darkblue; background: #3aacff; width: <?php echo $schnitzel_percent; ?>%;">
                                                                <?php echo $schnitzel_percent; ?>%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                        <br>
                        Changelog & News:
                        <iframe src="changelog.php" style="width: 100%; height: 200px;"></iframe>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>
    <?php
} else {
    echo 'Session abgelaufen. Bitte neu <a href="index.php">einloggen</a>';
}
$mysqli->close();