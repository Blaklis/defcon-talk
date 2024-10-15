<?php
include('config.php');
global $token_ws, $iv_length, $method, $key;
session_name('prod');
session_start();
if(isset($_GET['reset'])) {
    session_destroy();
    session_start();
    $urlinfo=parse_url($_SERVER["REQUEST_URI"]);
    header('Location: '.$urlinfo['path']);
    exit;
}

function generateRandomString($length = 6) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

if(!isset($_SESSION["step"])) {
    $_SESSION["step"] = 0;
}

switch($_SESSION["step"]) {
    case 0:
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            $template = file_get_contents("templates/step0.html");
            include 'template.php';
        } else if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Do not check phone number - we don't care for that example :)

            // Generate SMS token
            file_get_contents($token_ws."?action=generate_sms&msisdn=".urlencode($_POST['phone']));
            // Get the SMS token from WS
            $token = json_decode(file_get_contents($token_ws."?action=retrieve_sms&msisdn=".urlencode($_POST['phone'])));

            $_SESSION['step'] = 1;
            $iv = openssl_random_pseudo_bytes($iv_length);
            setcookie("CockpitCaptchaText",base64_encode($iv.openssl_encrypt(generateRandomString(), $method, $key, OPENSSL_RAW_DATA, $iv)));
            setcookie("CockpitMsisdnKey",base64_encode($iv.openssl_encrypt($_POST['phone'], $method, $key, OPENSSL_RAW_DATA, $iv)));
            setcookie("CockpitSmsTokenKey",base64_encode($iv.openssl_encrypt($token, $method, $key, OPENSSL_RAW_DATA, $iv)));
            header("Refresh:0");
            exit;
        }
        break;
    case 1:
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(isset($_COOKIE['CockpitCaptchaText']) && isset($_COOKIE['CockpitMsisdnKey']) && isset($_COOKIE['CockpitSmsTokenKey'])) {
                $raw = base64_decode($_COOKIE['CockpitMsisdnKey']);
                $iv = substr($raw, 0, $iv_length);
                $cipher = substr($raw, $iv_length);
                $msisdn = openssl_decrypt($cipher, $method, $key, OPENSSL_RAW_DATA, $iv);
                $template = file_get_contents("templates/step1.html");
                include 'template.php';
            } else {
                session_destroy();
            }
        } else if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $captcha = $_POST['captcha'];
            if(isset($_COOKIE['CockpitCaptchaText']) && isset($_COOKIE['CockpitMsisdnKey']) && isset($_COOKIE['CockpitSmsTokenKey'])) {
                $raw = base64_decode($_COOKIE['CockpitCaptchaText']);
                $iv = substr($raw, 0, $iv_length);
                $cipher = substr($raw, $iv_length);
                $captchaText = openssl_decrypt($cipher, $method, $key, OPENSSL_RAW_DATA, $iv);

                $raw = base64_decode($_COOKIE['CockpitMsisdnKey']);
                $iv = substr($raw, 0, $iv_length);
                $cipher = substr($raw, $iv_length);
                $msisdn = openssl_decrypt($cipher, $method, $key, OPENSSL_RAW_DATA, $iv);

                $smstoken = json_decode(file_get_contents($token_ws."?action=retrieve_sms&msisdn=".urlencode($msisdn)));


                if($smstoken == $token && $captchaText == $captcha || $_POST['cancel']) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['msisdn'] = $msisdn;
                    $_SESSION['step'] = 2;
                } else if($_POST['cancel']) {
                    session_destroy();
                }
                else {
                    $_SESSION['loggedin'] = false;
                    $_SESSION['error'] = "Token or captcha are invalid";
                    $_SESSION['step'] = 'error';
                }
                header("Refresh:0");
                exit;
            }
            exit;
        }
        break;
    case 2:
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(isset($_COOKIE['CockpitCaptchaText']) && isset($_COOKIE['CockpitMsisdnKey']) && isset($_COOKIE['CockpitSmsTokenKey'])) {
                $raw = base64_decode($_COOKIE['CockpitMsisdnKey']);
                $iv = substr($raw, 0, $iv_length);
                $cipher = substr($raw, $iv_length);
                $msisdn = openssl_decrypt($cipher, $method, $key, OPENSSL_RAW_DATA, $iv);
                $template = str_replace('{{phone}}',htmlspecialchars($msisdn),file_get_contents("templates/step2.html"));
                include 'template.php';
            } else {
                session_destroy();
            }
        } else if($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_destroy();
            header("Refresh:0");
            exit;
        }
        break;
    case 'error':
        $template = str_replace('{{error}}',htmlspecialchars($_SESSION['error']),file_get_contents("templates/error.html"));
        include 'template.php';
        break;


}