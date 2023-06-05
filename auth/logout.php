<?php
    include("../private/database/int.php");
    include("../private/database/firmung.php");
    include("../private/config.php");

    //intranet
    $session_hash = $_COOKIE["SESSION_ID"];
    mysqli_query($con, "DELETE FROM `session` WHERE cookie_hash = '$session_hash'");
    setcookie("SESSION_ID", "", 0, "", ".".$domain["default"]);

    $session_hash = $_COOKIE["SESSION_FIRMLING_ID"];
    mysqli_query($con_firmung, "DELETE FROM `session` WHERE cookie_hash = '$session_hash'");
    setcookie("SESSION_FIRMLING_ID", "", 0, "", ".".$domain["default"]);

    if(isset($_GET["direct"])) {
        $parameter = "?direct=".$_GET["direct"];
    }
    header("Location: https://".$domain["auth"]."/".$parameter);
?>
