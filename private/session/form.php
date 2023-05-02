<?php
if (!(in_array("form", $dbSESSION_perm))) {
    
    header("Location: /");
    exit();
};
?>