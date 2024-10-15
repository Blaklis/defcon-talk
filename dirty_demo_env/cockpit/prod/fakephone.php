<?php
include('config.php');
global $token_ws, $iv_length, $method, $key;

if(isset($_POST['msisdn'])) {
    $msisdn = $_POST['msisdn'];
    $token = json_decode(file_get_contents($token_ws."?action=retrieve_sms&msisdn=".urlencode($msisdn)));
    if($token == NULL) { $token = ""; }
} else {
    $msisdn = "";
    $token = "";
}
?>

<html>
<head>
    <style>

        body {
        }

        .faker-phone {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
            background: #2d3436;
            width: calc(76px * 4);
            height: calc(138px * 4);
            padding: 60px 15px 70px;
            border-radius: 35px;
            box-sizing: border-box;
        }
        .faker-phone::before {
            content: "";
            position: absolute;
            top: 27px;
            left: 50%;
            margin-left: -25px;
            width: 50px;
            height: 6px;
            border-radius: 3px;
            background: #000;
            opacity: 0.7;
        }
        .faker-phone::after {
            content: "";
            position: absolute;
            bottom: 15px;
            left: 50%;
            margin-left: -22px;
            height: 40px;
            width: 40px;
            background: rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(0, 0, 0, 0.8);
            border-radius: 100%;
        }
        .faker-phone--screen {
            width: 100%;
            height: 100%;
            background: #fff;
            overflow: auto;
            position: relative;
        }
    </style>
</head>
<body>
<div class="phone-input">
    <form method="POST">
        <div>Enter your phone number</div>
        <input type="text" name="msisdn" value="<?=$msisdn?>">
        <input type="submit" value="Submit">
    </form>
</div>
<div class="faker-phone">
    <div class="faker-phone--screen">
        <?=$token?>
    </div>
</div>
</body>
</html>