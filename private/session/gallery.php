<?php
if (!(in_array("gallery", $dbSESSION_perm))) {
    
    header("Location: /");
    exit();
};
?>