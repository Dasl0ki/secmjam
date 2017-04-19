<?php
$config = parse_ini_file('mysqli_config.ini', TRUE);
$strQuery = "SET character_set_results = 'utf8',
				character_set_client = 'utf8',
				character_set_connection = 'utf8',
				character_set_database = 'utf8',
				character_set_server = 'utf8'";
require_once("db_cnx.php");
$select_orders = "SELECT * FROM deliverys WHERE locked = '0'";
$query_order = $mysqli->query($select_orders);
while($row = $query_order->fetch_assoc()) {
    if($row["autolock"] < date("Y-m-d H:i:s", time())) {
        echo "Ergebnis:";
        echo $row["autolock"];
        if($row["autolock"] != "0000-00-00 00:00:00") {
            $id = $row["id"];
            $lock_order = "UPDATE deliverys SET locked = '1' WHERE id='$id'";
            $query_lock = $mysqli->query($lock_order);
        }
    }
}

?>