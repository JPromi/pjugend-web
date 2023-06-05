<?php
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/firmung/get_session.php");
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");
?>

<?php
    if (empty($dbSESSION_firmling)) {

        //set redirect
        $URL = $_SERVER["REQUEST_URI"];
        $URL = substr($URL, 1);
        //$URL = str_replace("?", "%3F", $URL);
        //$URL = str_replace("=", "%3D", $URL);
        //$URL = str_replace("/", "%2F", $URL);
        header("Location: https://".$domain["auth"]."/?direct=".$URL);
        exit();
    }
?>

