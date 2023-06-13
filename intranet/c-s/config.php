<?php
include '../../private/config.php';
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

if($_GET["type"] == "domain") {
    if($_GET["domain"] == "intranet") {
        echo '{ "domain": "'.$domain["intranet"].'" }';
    } else if($_GET["domain"] == "web") {
        echo '{ "domain": "'.$domain["web"].'" }';
    } else if($_GET["domain"] == "auth") {
        echo '{ "domain": "'.$domain["auth"].'" }';
    } else if($_GET["domain"] == "cdn") {
        echo '{ "domain": "'.$domain["cdn"].'" }';
    } else if($_GET["domain"] == "api") {
        echo '{ "domain": "'.$domain["api"].'" }';
    } else if($_GET["domain"] == "default") {
        echo '{ "domain": "'.$domain["default"].'" }';
    } else {
        echo json_encode($domain);
    }
}
?>