<?php
include '../database/int.php';

$sessions = "SELECT * FROM `session`";
$sessions = $con->query($sessions);

$expirdeSessions = array();
while ($session = $sessions->fetch_assoc()) {
    if(strtotime($session["created_at"]."+7 days") < strtotime(date("Y-m-d"))) {
        array_push($expirdeSessions, $session["id"]);
    }
}
$expirdeSessions = implode("', '", $expirdeSessions);

$sessionsExpired = "DELETE FROM `session` WHERE id IN ('$expirdeSessions')";
$con->query($sessionsExpired);

?>