<?php
if (!(in_array("firmung_admin", $dbSESSION_perm))) {
    
    header("Location: /");
    exit();
};
?>