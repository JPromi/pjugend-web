<?php
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/get_session.php");
include($_SERVER["DOCUMENT_ROOT"]."/../private/database/public.php");
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");

function createSession()
{
    global $con_public;
    global $domain;

    $userIP = $_SERVER['REMOTE_ADDR'];
    $cookie_hash = bin2hex(random_bytes(10));

    if($dbSESSION) {
        $userID = "'".$dbSESSION["user_id"]."'";
    } else {
        $userID = "NULL";
    }


    if($_COOKIE["PUBLIC_SESSION_ID"] == "") {
        $con_public->query("INSERT INTO session_public (ip, user_id, cookie_hash) VALUES ('$userIP', $userID, '$cookie_hash')");
        setcookie("PUBLIC_SESSION_ID", $cookie_hash, time() + (86400 * 7), "/", $domain["web"]);
        return $cockie_hash;
    }
    
}
?>