<?php
include('config.php');
global $token_ws, $iv_length, $method, $key;

if(isset($_COOKIE['CockpitCaptchaText'])) {
    $raw = base64_decode($_COOKIE['CockpitCaptchaText']);
    $iv = substr($raw, 0, $iv_length);
    $cipher = substr($raw, $iv_length);
    $text = openssl_decrypt($cipher, $method, $key, OPENSSL_RAW_DATA, $iv);
} else {
    $text = "";
}

$im = imagecreate(40+strlen($text)*9, 30);
$bg = imagecolorallocate($im, 255, 255, 255);
$textcolor = imagecolorallocate($im, 0, 0, 255);

imagestring($im, 5, 15, 10, $text, $textcolor);

header('Content-type: image/png');

imagepng($im);
imagedestroy($im);
?>