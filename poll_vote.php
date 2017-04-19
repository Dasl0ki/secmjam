<?php
/**
 * Created by PhpStorm.
 * User: uyve7bo
 * Date: 04.04.2016
 * Time: 07:15
 */
require_once("config/config.php");
require_once("config/db_cnx.php");
session_start();
if($_SESSION["user"]["id"] == "1") {
    ini_set("display_errors", 1);
    error_reporting(E_ALL | E_STRICT);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}
$vote = $_REQUEST['vote'];
$id = $_SESSION["user"]["id"];

//get content of textfile
$filename = "config/poll_result.txt";
$content = file($filename);

//put content in array
$array = explode("||", $content[0]);
$noodles = $array[0];
$pizza = $array[1];
$kebap = $array[2];
$schnitzel = $array[3];

if ($vote == 1) {
    $noodles = $noodles + 1;
}
if ($vote == 2) {
    $pizza = $pizza + 1;
}
if ($vote == 3) {
    $kebap = $kebap + 1;
}
if ($vote == 4) {
    $schnitzel = $schnitzel + 1;
}

//insert votes to txt file
$insertvote = $noodles."||".$pizza."||".$kebap."||".$schnitzel;
$fp = fopen($filename,"w");
fputs($fp,$insertvote);
fclose($fp);
$update_vote = "UPDATE login SET vote = '1' WHERE id = '$id'";
$query = $mysqli->query($update_vote);
$_SESSION["user"]["vote"] = "1";

?>

<h2>Result:</h2>
<table>
    <tr>
        <td>Noodles:</td>
        <td>
            <img src="img/poll.png"
                 width='<?php echo(100*round($noodles/($noodles+$pizza+$kebap+$schnitzel),2)); ?>'
                 height='20'>
            <?php echo(100*round($noodles/($noodles+$pizza+$kebap+$schnitzel),2)); ?>%
        </td>
    </tr>
    <tr>
        <td>Pizza:</td>
        <td>
            <img src="img/poll.png"
                 width='<?php echo(100*round($pizza/($noodles+$pizza+$kebap+$schnitzel),2)); ?>'
                 height='20'>
            <?php echo(100*round($pizza/($noodles+$pizza+$kebap+$schnitzel),2)); ?>%
        </td>
    </tr>
    </tr>
    <tr>
        <td>Kebap:</td>
        <td>
            <img src="img/poll.png"
                 width='<?php echo(100*round($kebap/($noodles+$pizza+$kebap+$schnitzel),2)); ?>'
                 height='20'>
            <?php echo(100*round($kebap/($noodles+$pizza+$kebap+$schnitzel),2)); ?>%
        </td>
    </tr>
    </tr>
    <tr>
        <td>Schnitzel:</td>
        <td>
            <img src="img/poll.png"
                 width='<?php echo(100*round($schnitzel/($noodles+$pizza+$kebap+$schnitzel),2)); ?>'
                 height='20'>
            <?php echo(100*round($schnitzel/($noodles+$pizza+$kebap+$schnitzel),2)); ?>%
        </td>
    </tr>
</table>