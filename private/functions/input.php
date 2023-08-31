<?php
function checkInput($input, $type = "db") {
    global $con;
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($con, $input);

    if(!(empty($input))) {
        if($type == "db") {
            $input = "'".$input."'";
        } else if ($type == "string") {
            $input = $input;
        }
        
    } else {
        $input = "NULL";
    }

    return $input;
}

function checkTextInput($input, $type = "db") {
    global $con;
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($con, $input);

    if(!(empty($input))) {
        if($type == "db") {
            $input = "'".$input."'";
        } else if ($type == "string") {
            $input = $input;
        }
    } else {
        $input = "NULL";
    }

    return $input;
}

function checkBoolean($input) {
    if($input == "on") {
        $input = "1";
    } else {
        $input = "0";
    }

    return $input;
}

function ifElseInput($input1, $input2) {
    if($input1) {
        return $input1;
    } else {
        return $input2;
    }
}

function inputCheckDate($input)
{
    if($input == "") {
        $input = "NULL";
    } else {
        $input = "'". date("Y-m-d H:i", strtotime($input)) ."'";
    }
    return $input;
}

function checkInputPassword($input) {
    global $con;
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($con, $input);

    return $input;
}
?>