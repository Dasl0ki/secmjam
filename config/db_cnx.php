<?php
$mysqli = new mysqli(   $config['database_sec_mjam']['database_host'],
                        $config['database_sec_mjam']['database_user'],
                        $config['database_sec_mjam']['database_pwd'],
                        $config['database_sec_mjam']['database_name']);
if($mysqli->errno) {
    echo "Failed to connect to database" . $mysqli->error;
    die;
}

$mysqli->query("SET NAMES 'utf8'");
$mysqli->query($strQuery);
$mysqli->set_charset('utf8');