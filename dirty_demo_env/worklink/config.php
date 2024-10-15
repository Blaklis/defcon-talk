<?php

$key = "Th1s is n0t guessable, 1 think!!";
$method = "aes-256-cbc";
$iv_length = openssl_cipher_iv_length($method);
$token_ws = "http://127.0.0.1/cockpit/smscentral/";

