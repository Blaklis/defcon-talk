<?php
include('database.class.php');
session_start();
/*
$blockedDb = new Database('blocked');
*/

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // User is blocked only if they had 3 invalid login in 2 hours.
    $blockedDb = new Database('blocked');
    $loginFailedDb = new Database('login_failed');

    // We check if that specific username is blocked
    $elements = $blockedDb->read("//block[@username='".$username."']");
    if($elements->length > 0) {
        $blocked = true;
        $error = "Account blocked";
    } else {
        $elements = $loginFailedDb->read("//fail[@username='" . $username . "']");
        if ($elements->length >= 3) {
            $count = 0;
            for ($i = 0; $i < $elements->length; $i++) {
                $element = $elements->item($i);
                $dateline = $element->getAttribute('date');
                $datenow = new DateTime();
                $diff_mins = abs($datenow->getTimestamp() - $dateline) / 60;
                if ($diff_mins > 120) {
                    $loginFailedDb->delete($element);
                } else {
                    $count++;
                }
            }
            if ($count >= 3) {
                $blockedDb->writeLine('block', ['username' => $username]);
                $blocked = true;
                $error = "Account blocked";
            }
        }
    }

    if(!$blocked) {
        // We search for the user in the user DB
        $userDb = new Database('users');
        $elements = $userDb->read('//user[@name="' . $username . '"]');

        if ($elements->length > 0) {
            $name = $elements[0]->getAttribute('name');
            $realpassword = $elements[0]->getAttribute('password');
            $role = $elements[0]->getAttribute('role');

            if ($realpassword == sha1($password)) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
            } else {
                $error = "Wrong password";

                // Add the bad login in the login failed DB.
                $loginFailedDb->writeLine('fail', ['username' => str_replace('"','&quot;',$_POST['username']), 'date' => (new DateTime())->getTimestamp()]);

            }
        } else {
            $error = "No user found.";
            // Add the bad login in the login failed DB.
            $loginFailedDb->writeLine('fail', ['username' => htmlentities($_POST['username']), 'date' => (new DateTime())->getTimestamp()]);
        }
    }
}

include('template.php');