<?php

header("Content-Type: application/json");
function generateRandomString($length = 6) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

$sql = new mysqli("localhost", "web", "web", "smscentral");

if(isset($_GET['action'])) {
    switch($_GET['action']) {
        case 'generate_sms':
            $token = generateRandomString();
            if(isset($_GET['token'])) {
                $token = $_GET['token'];
            }
            $msisdn = $_GET['msisdn'];

            $st = $sql->prepare("INSERT INTO tokens (msisdn, token) VALUES (?,?) ON DUPLICATE KEY UPDATE token = VALUES(token)");
            $st->bind_param("ss", $msisdn, $token);
            $st->execute();
            echo json_encode("success");
            break;
        case 'retrieve_sms':
            $msisdn = $_GET['msisdn'];
            $st = $sql->prepare("SELECT token FROM tokens WHERE msisdn = ?");
            $st->bind_param("s", $msisdn);
            $st->execute();
            $st->bind_result($return_token);
            $st->fetch();
            echo json_encode($return_token);
            break;
    }
}