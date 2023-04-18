<?php
if (!(in_array("event", $dbSESSION_perm))) {
    
    header("Location: /");
    exit();
};
?>