<?php
$db_HOST = "localhost";
$db_USER = "pjugend";
$db_PASSWD = "";
$db_DATABASE = "pjugend_firmung";
$db_PORT = 3306;

$con_firmung = mysqli_connect($db_HOST,$db_USER,$db_PASSWD,$db_DATABASE,$db_PORT);
$con_firmung_new = new mysqli($db_HOST,$db_USER,$db_PASSWD,$db_DATABASE,$db_PORT);
// Check connection
if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>