<?php
if(empty($_GET["l"]) || $_GET["l"] == "int") {
    include '../private/auth/intranet.php';
} else if ($_GET["l"] == "fir" || $_GET["l"] == "f") {
    include '../private/auth/firmung.php';
}

?>