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

function getPollVotes() {
    $filename = "config/poll_result.txt";
    $content = file($filename);
    
    $array = explode("||", $content[0]);
    $vote = array(
        'noodles' => $array[0],
        'pizza' => $array[1],
        'kebap' => $array[2],
        'schnitzel' => $array[3]
    );
    
    return $vote;
}

function updateVotes($vote) {
    $filename = "config/poll_result.txt";
    $insertvote = $vote['noodles']."||".$vote['pizza']."||".$vote['kebap']."||".$vote['schnitzel'];
    $fp = fopen($filename,"w");
    fputs($fp,$insertvote);
    fclose($fp);
} 

function getVotePercent($vote) {
    $percent = array(
        'noodles' => (100*round($vote['noodles']/($vote['noodles']+$vote['pizza']+$vote['kebap']+$vote['schnitzel']),2)),
        'pizza' => (100*round($vote['pizza']/($vote['noodles']+$vote['pizza']+$vote['kebap']+$vote['schnitzel']),2)),
        'kebap' => (100*round($vote['kebap']/($vote['noodles']+$vote['pizza']+$vote['kebap']+$vote['schnitzel']),2)),
        'schnitzel' => (100*round($vote['schnitzel']/($vote['noodles']+$vote['pizza']+$vote['kebap']+$vote['schnitzel']),2))
    );
    return $percent;
}

function lockOrder($dn) {
    global $mysqli;
    $update_lock = "UPDATE deliverys SET locked='1' WHERE delivery_number = '$dn'";
    $mysqli->query($update_lock);
}

function unlockOrder($dn) {
    global $mysqli;
    $update_lock = "UPDATE deliverys SET locked='0' WHERE delivery_number = '$dn'";
    $mysqli->query($update_lock);
}

function closeOrder($dn, $helperArray, $category, $owner) {
    global $mysqli;
    $update = 'UPDATE deliverys SET status = 1, locked = 1 WHERE delivery_number = ?';
    $stmt = $mysqli->prepare($update);
    $stmt->bind_param('s', $dn);
    $stmt->execute();
    
    $pts = parse_ini_file('config/pts.ini', TRUE);
    $delivery_count = 'SELECT count(DISTINCT userid) FROM deliverys WHERE delivery_number = ?';
    $stmt = $mysqli->prepare($delivery_count);
    $stmt->bind_param('s', $dn);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $points = $result['count(DISTINCT userid)'] * $pts['pts'][$category];
    $pointsold = getPoints($owner);
    $update_owner = 'UPDATE login SET points = '.($pointsold + $points).' WHERE id = '.$owner;
    $mysqli->query($update_owner);
    if($helperArray != NULL) {
        foreach($helperArray as $helper) {
            $pointsold = getPoints($helper);
            $update_helper = 'UPDATE login SET points = '.($pointsold + ceil($points/2)).' WHERE id = '.$helper;
            $mysqli->query($update_helper);
        }
    }
}

function getPoints($id) {
    global $mysqli;
    $select = 'SELECT * FROM login WHERE id = '.$id;
    $query = $mysqli->query($select);
    return $query->fetch_object()->points;
}

function lastOrders($id) {
    global $mysqli;
    $select_user_delivery = 'SELECT * FROM deliverys WHERE userid = '.$id.' AND status = 1 GROUP BY delivery_number ORDER BY id DESC LIMIT 10';
    $query = $mysqli->query($select_user_delivery);
    $orders = array();
    while ($row = $query->fetch_object()) {
        $date_output = new DateTime();
        $orders[] = array(
            'dn' => $row->delivery_number,
            'owner' => $row->owner,
            'date' => $date_output->setTimestamp($row->delivery_number)->format('d.m.Y'),
            'category' => $row->category
        );
    }
    
    return $orders;
}

function getDeliverys($dn) {
    global $mysqli;
    $deliverys = array();
    $select = 'SELECT * FROM deliverys WHERE delivery_number = '.$dn.' ORDER BY CAST(delivery_text AS DECIMAL), sauce';    
    $query = $mysqli->query($select);
    while($row = $query->fetch_assoc()) {
        $userData = getUserData($row['userid']);
        $delivery = getDeliveryText($row['delivery_text']);
        $deliverys[] = array(
            'id' => $row['id'],
            'fullname' => $userData['firstname'].' '.$userData['lastname'],
            'delivery' => $delivery['sub_category'].' '.$delivery['item'],
            'size' => $delivery['size'],
            'extra' => $row['sauce'],
            'price' => $delivery['prize'],
            'userid' => $row['userid'],
            'status' => $row['status'],
            'locked' => $row['locked'],
            'owner' => $row['owner']
        );
    }
    return $deliverys;
}

function getUserData($id) {
    global $mysqli;
    $select = 'SELECT * FROM login WHERE id = '.$id;
    $query = $mysqli->query($select);
    
    return $query->fetch_assoc();    
}

function getDeliveryText($id) {
    global $mysqli;
    $select = 'SELECT * FROM menue WHERE id = '.$id;
    $query = $mysqli->query($select);
    
    return $query->fetch_assoc();
}

function getOpenOrders() {
    global $mysqli;
    $select_deliverys = "SELECT * FROM deliverys WHERE status != '1' GROUP BY delivery_number";
    $query = $mysqli->query($select_deliverys);
    $orders = array();    
    while ($row = $query->fetch_assoc()) {
        $date = new DateTime();
        $date_output = $date->setTimestamp($row['delivery_number'])->format('d.m.Y');
        $owner = getUserData($row['owner']);        
        $orders[] = array(
            'dn' => $row['delivery_number'],
            'date_output' => $date_output,
            'category' => $row['category'],
            'ownerFullname' => $owner['firstname'].' '.$owner['lastname'],
            'locked' => $row['locked']
        );    
    }
    
    return $orders;
}

function total($dn) {
    global $mysqli;
    $userids = array();
    $total = array();
    $items = array();
    $selectUserid = 'SELECT DISTINCT userid FROM deliverys WHERE delivery_number = '.$dn;
    $query = $mysqli->query($selectUserid);    
    while ($row = $query->fetch_assoc()) {
        $userids[] = $row['userid'];
    }
    foreach ($userids as $user) {
        $total[$user]['total'] = 0;        
        $userData = getUserData($user);
        $total[$user]['fullname'] = $userData['firstname'].' '.$userData['lastname'];
        $selectItems = 'SELECT delivery_text FROM deliverys WHERE delivery_number = '.$dn.' AND userid = '.$user;
        $query = $mysqli->query($selectItems);        
        while($row = $query->fetch_assoc()) {
            $items[] = $row['delivery_text'];
        }
        
        foreach ($items as $item) {
            $itemData = getItem($item);
            $total[$user]['total'] = $total[$user]['total'] + $itemData['prize'];
        }
        $items = array();
    }
    
    return $total;
}

function getItem($id) {
    global $mysqli;
    $select = 'SELECT * FROM menue WHERE id = '.$id;
    $query = $mysqli->query($select);
    return $query->fetch_assoc();
}

function totalItems($dn) {
    global $mysqli;
    $items = array();
    $total = array();
    $select = 'SELECT DISTINCT delivery_text FROM deliverys WHERE delivery_number = '.$dn;
    $query = $mysqli->query($select);
    while ($row = $query->fetch_assoc()) {
        $items[] = $row['delivery_text'];
    }
    
    foreach ($items as $item) {
        $itemData = getItem($item);
        $total[$item]['item'] = $itemData['sub_category'].' '.$itemData['item'];
        $count = 'SELECT count(delivery_text) FROM deliverys WHERE delivery_text = '.$item.' AND delivery_number = '.$dn;
        $query = $mysqli->query($count);
        $itemCount = $query->fetch_assoc();
        $total[$item]['count'] = $itemCount['count(delivery_text)'];
    }
    
    return $total;
}

function getHelper() {
    global $mysqli;
    $helper = array();
    $select = 'SELECT * FROM login WHERE active = 1 ORDER by lastname ASC';
    $query = $mysqli->query($select);
    while ($row = $query->fetch_assoc()) {
        $helper[] = $row;
    }
    
    return $helper;
}

function getMenue($category) {
    global $mysqli;
    $menue = array();
    $select = 'SELECT * FROM menue WHERE category = ? ORDER BY sub_category ASC';
    $stmt = $mysqli->prepare($select);
    $stmt->bind_param('s', $category);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $menue[] = $row;
    }

    return $menue;
}

function saveOrder($order) {
    global $mysqli;
    $now = new DateTime('now');
    $extras = '';
    $insert_timestamp = $now->format('Y-m-d H:i:s');
    if ($order['new_order'] == "1") {
        if($order['autolock_check'] == "1") {
            $autolock_time = new DateTime();
            $autolock_time->setTimestamp($order['dn']);
            $autolock_time->setTime(substr($order['autolock_time'],0,-2), substr($order['autolock_time'],-2));
            $autolock = $autolock_time->format('Y-m-d H:i:s');
        } else {
            $autolock = null;
        }
    } else {
        $select_autolock = 'SELECT * FROM deliverys WHERE delivery_number = '.$order['dn'];
        $query_autolock = $mysqli->query($select_autolock);
        $autolock = $query_autolock->fetch_object()->autolock;
    }

    foreach ($order['food'] as $item) {
        for ($i = 1; $i <= $order['amount'][$item]; $i++) {
            foreach($order['sauce'][$item] as $extra) {
                $extras .= '|'.$extra;
            }
            $extras = substr($extras,1);
            $insert = 'INSERT INTO deliverys (delivery_number, delivery_text, userid, sauce, owner, category, autolock, timestamp) '
                .'VALUES ('.$order['dn'].', '.$item.', '.$order['userid'].', '.$extras.', '.$order['owner'].', '.$order['category'].', '
                .$autolock.', '.$insert_timestamp.')';
            $mysqli->query($insert);
            $extras = "";
        }
    }
}