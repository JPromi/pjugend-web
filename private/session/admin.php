<?php
if (!(in_array("admin", $dbSESSION_perm))) {
    
    header("Location: /");
    exit();
};
?>