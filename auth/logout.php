<?php
    include("../private/database/int.php");
    include("../private/config.php");
    $session_hash = $_COOKIE["SESSION_ID"];
    mysqli_query($con, "DELETE FROM `session` WHERE cookie_hash = '$session_hash'");

    //delete cookie
    setcookie("SESSION_ID", "", 0, "", ".".$domain["default"]);
    if(isset($_GET["direct"])) {
        $parameter = "?direct=".$_GET["direct"];
    }
    header("Location: https://".$domain["auth"]."/".$parameter);
?>
