<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/public.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: * ');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Credentials: true');

$links = $con_public->query("SELECT title, link FROM socialmedia");
$socialmediaJSON = array();
$counter = 0;

while ($link = $links->fetch_assoc()) {
    array_push($socialmediaJSON, $link);
}
echo '{ "links":';
echo json_encode($socialmediaJSON);
echo "}";
?>