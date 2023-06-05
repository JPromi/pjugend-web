<?php
//get database login
require($_SERVER["DOCUMENT_ROOT"].'/../private/database/firmung.php');

//get sesstion from db
$session_hash = $_COOKIE["SESSION_FIRMLING_ID"];
$dbSESSION_firmling = $con_firmung->query("SELECT * FROM `session` WHERE `cookie_hash`='$session_hash'");
$dbSESSION_firmling = $dbSESSION_firmling->fetch_assoc();
?>