<?php
if (!(in_array("jugendteam_admin", $dbSESSION_perm))) {
    
    header("Location: /");
    exit();
};
?>