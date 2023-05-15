<?php
if (!(in_array("firmbegleiter", $dbSESSION_perm) || in_array("firmung_admin", $dbSESSION_perm))) {

    header("Location: /");
    exit();
};
?>