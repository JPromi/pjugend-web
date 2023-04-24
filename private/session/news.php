<?php
if (!(in_array("news", $dbSESSION_perm))) {
    
    header("Location: /");
    exit();
};
?>