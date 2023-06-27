<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/session/get_session.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';

//set content type to json
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: * ');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Credentials: true');

if(isset($_REQUEST["username"]) && isset($_REQUEST["password"])) {
    $username = stripslashes($_REQUEST['username']);    // removes backslashes
    $username = mysqli_real_escape_string($con, $username);
    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($con, $password);

    //log login
    $LOG_IP = $_SERVER['REMOTE_ADDR'];

    //failed attemps
    $failed = "SELECT * FROM `login_log` WHERE username = '$username'";
    $failed = mysqli_query($con, $failed);

    $failedCounterIP = 0;
    $failedCounter = 0;

    while ($fa = $failed->fetch_assoc()) {
        
        if(strtotime(date("Y-m-d h:i", strtotime("-3 minutes"))) <= strtotime($fa["timestamp"])) {
            if($fa["ip"] == $LOG_IP) {
            $failedCounterIP++;
            }
            $failedCounter++;
        }

        if($fa["status"] == "login") {
            $failedCounterIP = 0;
            $failedCounter = 0;
        }

    }

    if($failedCounter <= 10) {
        // Check user is exist in the database
        // ARGON2ID
        $verify = "SELECT * FROM `accounts` WHERE username = '$username'";
        $verify = mysqli_query($con, $verify);
        $verify = $verify->fetch_assoc();
    
        $passwordVerify = password_verify($password, $verify["password"]);

        if ($passwordVerify == 1) {
            $loginStatus = "login";
            $coockie_hash = bin2hex(random_bytes(40));
            //setcookie("SESSION_ID", $coockie_hash, time() + (86400 * 7), "", ".".$domain["default"]);
            $generateSession =    "INSERT INTO `session` 
                        (user_id, username, firstname, lastname, cookie_hash, used_for)
                        VALUES
                        ('".$verify['id']."', '".$verify['username']."', '".$verify['firstname']."', '".$verify['lastname']."', '$coockie_hash', 'api')";
            mysqli_query($con_new, $generateSession);

            // Set log var
            $userID = "'".$verify['id']."'";

            echo '
                {
                    "user_id": "'.$verify["id"].'",
                    "username": "'.$verify["username"].'",
                    "firstname": "'.$verify["firstname"].'",
                    "lastname": "'.$verify["lastname"].'",
                    "login_date": "'.date("Y-m-d H:i:s").'",
                    "coockie_hash": "'.$coockie_hash.'"
                }';
            // Redirect to user home page
        } else {
            $loginStatus = "error";
            echo '{"error":"password or username incorrect"}';
            $userID = "NULL";
        }

        //log login
        mysqli_query($con_new, "INSERT INTO `login_log` (username, ip, `status`, user_id) VALUES ('$username', '$LOG_IP', '$loginStatus', $userID)");
    } else {
        echo '{"error":"to much attempts"}';
    }
} else {
    echo '{"error":"no username or password set"}';
}
?>