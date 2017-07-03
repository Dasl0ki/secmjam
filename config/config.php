<?php
/**
 * Created by PhpStorm.
 * User: Loki
 * Date: 13.10.2015
 * Time: 08:53
 */
$config = parse_ini_file('../../mysqli_config.ini', TRUE);
$strQuery = "SET character_set_results = 'utf8',
				character_set_client = 'utf8',
				character_set_connection = 'utf8',
				character_set_database = 'utf8',
				character_set_server = 'utf8'";
