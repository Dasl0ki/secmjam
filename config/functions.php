<?php
function doLogin($user, $pwd) {
    global $mysqli;
    $abfrage = "SELECT * FROM login WHERE user = ?";
    $ergebnis = $mysqli->prepare($abfrage);
    $ergebnis->bind_param('s', $user);
    $ergebnis->execute();
    $result = $ergebnis->get_result();
    if(!$result) {
        printf("Errormessage: %s\n", $mysqli->error);
    }
    if($result->num_rows == 0) {
        return FALSE;
    }
    $row = $result->fetch_assoc();
    if($row["pwd"] == $pwd) {
        $name = array(
            "vorname" => $row["firstname"],
            "nachname" => $row["lastname"],
            "id" => $row["id"],
            "user" => $row["user"],
            "email" => $row["email"],
            "notify" => $row["notify"],
            "vote" => $row["vote"],
            "active" => $row["active"]
        );
        return $name;
    } else {
        return FALSE;
    }
}

function checkBalance($userid) {
    global $mysqli;
    $select_balance = 'SELECT balance FROM login WHERE id = '.$userid;
    $query = $mysqli->query($select_balance);
    return $query->fetch_object()->balance;
}

function changeBalance($userid, $amount, $do = 'add') {
    global $mysqli;
    $get_balance = 'SELECT balance FROM login WHERE id = '.$userid;
    $query_balance = $mysqli->query($get_balance);
    $balance = $query_balance->fetch_object()->balance;
    if($do == 'sub') {
        $new_balance = $balance - $amount;
    } else {
        $new_balance = $balance + $amount;
    }

    $update_balance = 'UPDATE login SET balance = '.$new_balance.' WHERE id = '.$userid;
    $query_new_balance = $mysqli->query($update_balance);
    if($query_new_balance == TRUE) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function getPrice($foodid, $amount = 1) {
    global $mysqli;
    $select = 'SELECT * FROM menue WHERE id = '.$foodid;
    $query = $mysqli->query($select);
    $price = $query->fetch_object()->prize;
    return $price * $amount;
}

function checkAfford($fullprice, $userid) {
    $balance = checkBalance($userid);
    if($balance >= $fullprice) {
        return TRUE;
    } else {
        return FALSE;
    }
}
