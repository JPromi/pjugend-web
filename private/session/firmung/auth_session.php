<?php
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/firmung/get_session.php");
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");
?>

<?php
    if (empty($dbSESSION_firmling)) {

        //set redirect
        header("Location: https://".$domain["auth"]."/?l=firmung");
        exit();
    }
?>