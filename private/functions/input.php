<?php
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

function checkTextInput($input) {
    global $con;
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($con, $input);

    if(!(empty($input))) {
        $input = "'".$input."'";
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
?>