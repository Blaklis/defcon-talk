<?php
include('../config.php');
global $sql;
function restore($backup) {
    global $sql;
    $backup = str_replace('..', '', $backup);
    $backupContent = file_get_contents('backups/'.$backup);
    $queries = explode(";#%%\n", $backupContent);
    mysqli_query($sql, "SET NAMES 'utf8'");
    mysqli_query($sql, "SET foreign_key_checks = 0;");

    foreach ($queries as $query) {
        $base64tags = preg_match('/##_base64_start_##(.*?)##_base64_end_##/s', $query, $match);
        if($base64tags) {
            $newValue = '"'.base64_decode($match[1]).'"';
            $query = str_replace($match[0], $newValue, $query);
        }
        if(trim($query) != "") {
            mysqli_query($sql, $query);
        }
    }
    mysqli_query($sql, "SET foreign_key_checks = 1;");
}

restore($_GET['name']);