<?php
require_once("config/config.php");
require_once("config/db_cnx.php");
require 'config/functions.php';
session_start();
if($_SESSION["user"]["id"] == "1") {
    ini_set("display_errors", 1);
    error_reporting(E_ALL | E_STRICT);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}
$uservote = $_REQUEST['vote'];
$id = $_SESSION["user"]["id"];

$vote = getPollVotes();

if ($uservote == 1) {
    $vote['noodles'] = $vote['noodles'] + 1;
}
if ($uservote == 2) {
    $vote['pizza'] = $vote['pizza'] + 1;
}
if ($uservote == 3) {
    $vote['kebap'] = $vote['kebap'] + 1;
}
if ($uservote == 4) {
    $vote['schnitzel'] = $vote['schnitzel'] + 1;
}
if ($uservote == 5) {
    $vote['grill'] = $vote['grill'] + 1;
}

updateVotes($vote);
$percent = getVotePercent($vote);
$update_vote = "UPDATE login SET vote = '1' WHERE id = '$id'";
$query = $mysqli->query($update_vote);
$_SESSION["user"]["vote"] = "1";

?>

<div class="row" style="margin-top: 10px;">
    <div class="col-xs-3">
        Noodles:
    </div>
    <div class="col-xs-9">
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent['noodles']; ?>%; min-width: 2em;">
              <?php echo $percent['noodles']; ?>%
            </div>
        </div>
    </div>
    <div class="col-xs-3">
        Pizza:
    </div>
    <div class="col-xs-9">
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent['pizza']; ?>%; min-width: 2em;">
              <?php echo $percent['pizza']; ?>%
            </div>
        </div>
    </div>
    <div class="col-xs-3">
        Kebap:
    </div>
    <div class="col-xs-9">
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent['kebap']; ?>%; min-width: 2em;">
              <?php echo $percent['kebap']; ?>%
            </div>
        </div>
    </div>
    <div class="col-xs-3">
        Schnitzel:
    </div>
    <div class="col-xs-9">
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent['schnitzel']; ?>%; min-width: 2em;">
              <?php echo $percent['schnitzel']; ?>%
            </div>
        </div>
    </div>
    <div class="col-xs-9">
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent['grill']; ?>%; min-width: 2em;">
              <?php echo $percent['grill']; ?>%
            </div>
        </div>
    </div>
</div>