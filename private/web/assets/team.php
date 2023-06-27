<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/public.php';

function teamEntry($userID, $returnType = "name") {
    
    global $con;
    global $con_public;
    $userID = checkInput($userID);

    $teamEntry = $con_public->query("SELECT * FROM team WHERE user_id = $userID AND `disabled` = '0'")->fetch_assoc();

    if(isset($teamEntry)) {
        if($returnType == "name") {
            return $teamEntry["name"];
        } elseif ($returnType == "email") {
            return $teamEntry["email"];
        }
    } else {
        $account = $con->query("SELECT firstname, lastname FROM accounts WHERE id = $userID")->fetch_assoc();

        if($returnType = "name") {
            return $account["firstname"] . " " . substr($account["lastname"], 0, 2) . ".";
        } elseif ($returnType = "email") {
            return NULL;
        }
    }

    return NULL;
}

function checkInput($input) {
    global $con;
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($con, $input);

    if(!(empty($input))) {
        $input = "'".$input."'";
    } else {
        $input = "NULL";
    }

    return $input;
}
?>