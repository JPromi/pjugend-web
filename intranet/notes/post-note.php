<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");
?>

<?php
include '../../private/database/int.php';
include '../../private/function/input.php';

$userID = $dbSESSION['user_id'];

$writePerm = false;
$readPerm = false;

$noteID = $_GET["note"];
$dbNOTEchecker = "SELECT * FROM `notes` WHERE id = '$noteID'";
$dbNOTEchecker = $con_new->query($dbNOTEchecker);
$dbNOTEchecker = $dbNOTEchecker->fetch_assoc();

$sharedNoteWritePermARRAY = explode(";", $dbNOTEchecker["writer_id"]);
$sharedNoteReadPermARRAY = explode(";", $dbNOTEchecker["reader_id"]);

//check if you can write/read the note
if(in_array($userID, $sharedNoteWritePermARRAY) || in_array($userID, $sharedNoteReadPermARRAY)) {
    $dbNOTE = "SELECT * FROM `notes` WHERE id = '$noteID'";
    $dbNOTE = $con_new->query($dbNOTE);
    $dbNOTE = $dbNOTE->fetch_assoc();
} else {
    $dbNOTE = "SELECT * FROM `notes` WHERE owner_id = '$userID' AND id = '$noteID'";
    $dbNOTE = $con_new->query($dbNOTE);
    $dbNOTE = $dbNOTE->fetch_assoc();
    if(!(empty($dbNOTE))) {
        $writePerm = true;
        $readPerm = true;
    }
}

//check premission
if(in_array($userID, $sharedNoteWritePermARRAY)) {
    $writePerm = true;
    $guestPerm = true;
} elseif (in_array($userID, $sharedNoteReadPermARRAY)) {
    $readPerm = true;
} elseif (!(isset($_POST["note"]))) {
    $readPerm = true;
    $writePerm = true;
    $guestPerm = false;
}

if (!(isset($_GET["note"]))) {
    $guestPerm = true;
}
?>

<?php
    if(isset($_POST["save"]) && $writePerm == true) {

        //check if title or text is definated
        if (!(empty($_POST["title"]) && empty($_POST["text"]))) {
            //check if exist in databse
            $title = htmlspecialchars($_POST["title"]);
            $text = $_POST["text"];

            if (empty($_POST["text"])) {
                $dbNOTE = "SELECT * FROM `notes` WHERE id = '$noteID'";
                $dbNOTE = $con_new->query($dbNOTE);
                $dbNOTE = $dbNOTE->fetch_assoc();
                $text = $dbNOTE["text"];
            }
            
            $TimeStamp = date("Y-m-d H:i:s");

            //replace important tags
            /*$text = preg_replace('#<script(.*?)>', '', $text); //script
            $text = preg_replace('#<meta(.*?)>#is', '', $text); //meta
            $text = preg_replace('#<link(.*?)>#is', '', $text); //link
            $text = preg_replace('#<iframe(.*?)>', '', $text); //iframe
            $text = preg_replace('#<style(.*?)>', '', $text); //css*/

            if(empty($dbNOTE)){
                $query_add = "INSERT INTO `notes` (title, text, owner_id, created_at, last_change) VALUE ('$title', '$text', '$userID', '$TimeStamp', '$TimeStamp')";
                mysqli_query($con, $query_add);
            } else {
                $query_edit = "UPDATE `notes` SET `title` = '$title', `text` = '$text', last_change = '$TimeStamp' WHERE id = '$noteID'";
                mysqli_query($con, $query_edit);

            }

            //for safe things
            $dbNOTEchecker = "SELECT * FROM `notes` WHERE id = '$noteID'";
            $dbNOTEchecker = $con_new->query($dbNOTEchecker);
            $dbNOTEchecker = $dbNOTEchecker->fetch_assoc();
            echo('<meta http-equiv="refresh" content="0; URL=../notes?note='.$dbNOTE["id"].'">');
        } 
    } elseif (isset($_POST["delete"]) && $writePerm == true) {
        mysqli_query($con, "DELETE FROM `notes` WHERE id = '$noteID'");

        echo('<meta http-equiv="refresh" content="0; URL=../notes">');
    }
?>