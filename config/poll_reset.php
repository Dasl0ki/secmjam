<?php
/**
 * Created by PhpStorm.
 * User: uyve7bo
 * Date: 04.04.2016
 * Time: 08:37
 */
$config = parse_ini_file('mysqli_config.ini', TRUE);
$strQuery = "SET character_set_results = 'utf8',
				character_set_client = 'utf8',
				character_set_connection = 'utf8',
				character_set_database = 'utf8',
				character_set_server = 'utf8'";
require_once("db_cnx.php");
$filename = "/var/www/vhosts/loki-net.at/sec-mjam.loki-net.at/prod/config/poll_result.txt";
$insertvote = "0||0||0||0";
$fp = fopen($filename,"w");
fputs($fp,$insertvote);
fclose($fp);
echo 'poll_result.txt reset successfull<br><br>';
$update_vote = "UPDATE login SET vote = '0'";
$query = $mysqli->query($update_vote);
echo 'Vote count reset successfull';
