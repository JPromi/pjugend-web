<?php
include '../../../private/database/public.php';
include '../../../private/config.php';

$eventID = mysqli_real_escape_string($con_public, $_GET["id"]);

$event = "SELECT * FROM `event` WHERE id = '$eventID'";
$event = $con_public->query($event);
$event = $event->fetch_assoc();

$icsDATA = '
BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
DTSTART:'.date("Ymd\THis\Z",strtotime($event["date_from"])).'
DTEND:'.date("Ymd\THis\Z",strtotime($event["date_to"])).'
DTSTAMP:'.date("Ymd\THis\Z").'
TZID:Europe/Berlin
LOCATION:'.$event["location"].'
SUMMARY:'.$event["title"].'
DESCRIPTION:'.str_replace("\r\n", "\\n", $event["description"]).'
URL:https://'.$domain["web"].'/events/view?id='.$_GET["id"].'
END:VEVENT
END:VCALENDAR
';

header("Content-type:text/calendar");
header("Content-type:text/calendar");
header('Content-Disposition: attachment; filename='.str_replace(" ", "-", strtolower($event["title"])).'.ics"');
Header('Content-Length: '.strlen($icsDATA));
Header('Connection: close');
echo $icsDATA;

?>