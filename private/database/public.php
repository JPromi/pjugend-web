<?php
$db_HOST = "localhost";
$db_USER = "pjugend";
$db_PASSWD = "";
$db_DATABASE = "pjugend_public";

$con_public = mysqli_connect($db_HOST,$db_USER,$db_PASSWD,$db_DATABASE);
$con_public_new = new mysqli($db_HOST,$db_USER,$db_PASSWD,$db_DATABASE);
// Check connection
if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>