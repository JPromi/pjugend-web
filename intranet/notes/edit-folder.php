<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");

//include("../assets/html/favicon.html");
?>

<?php
$folderID = $_GET["folder"];
$userID = $dbSESSION["user_id"];
include '../../private/database/int.php';

$dbFOLDERinfo = "SELECT * FROM `notes_group` WHERE id = '$folderID' AND owner_id = '$userID'";
$dbFOLDERinfo = $con_new->query($dbFOLDERinfo);
$dbFOLDERinfo = $dbFOLDERinfo->fetch_assoc();

if (empty($dbFOLDERinfo)) {
    header("Location: ../notes");
}

?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordner Bearbeiten - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="css/add-edit-folder.css">
</head>
<body>
    <section class="popup">
            <form method="post">
                <p>Ordner Bearbeiten</p>
                <?php
                echo '
                    <input type="text" name="folder_title" value="'.$dbFOLDERinfo["name"].'">
                ';
                ?>
                <div class="notes">
                <?php
                    //select note

                    //create array from notes that inside the folder
                    $folderNoteID = explode(";", $dbFOLDERinfo["notes_id"]);
                    
                    //get notes own and shared notes
                    $sharedNoteIDs = [];

                    $dbNOTEprePERM = "SELECT * FROM `notes`";
                    $dbNOTEprePERM = $con_new->query($dbNOTEprePERM);

                    //select all shared notes
                    while ($sharedNote = $dbNOTEprePERM->fetch_assoc()) {
                        $sharedNoteRead = explode(';', $sharedNote["reader_id"]);
                        $sharedNoteWrite = explode(';', $sharedNote["writer_id"]);
                        
                        if (in_array($userID, $sharedNoteRead)) {
                            array_push($sharedNoteIDs, $sharedNote["id"]);
                        } elseif(in_array($userID, $sharedNoteWrite)) {
                            array_push($sharedNoteIDs, $sharedNote["id"]);
                        } else {
                            array_push($sharedNoteIDs, "0");
                        }
                    }
                    array_unique($sharedNoteIDs);
                    $sharedNoteIDsString = implode(", ", $sharedNoteIDs);

                    $dbNOTEpre = "SELECT * FROM `notes` WHERE owner_id = '$userID' OR id IN ($sharedNoteIDsString) ORDER BY last_change DESC";
                    $dbNOTEpre = $con_new->query($dbNOTEpre);

                    while ($note = $dbNOTEpre->fetch_assoc()) {
                        $note_checked = "";
                        if(in_array($note["id"], $folderNoteID)) {
                            $note_checked = "checked";
                        }
                        echo '
                            <input type="checkbox" name="note_'.$note["id"].'" '.$note_checked.'>
                            <label>'.$note["title"].'</label>
                        ';
                    }

                ?>
                </div>
                <label><input type="checkbox" name="delete"> Löschen</label>
                <div class="button">
                    <input type="submit" name="folder_edit" value="Speichern">
                    
                    <input type="submit" name="cancle" value="Abbrechen">
                </div>
                
            </form>
    </section>

    <section class="background" onclick="window.location.href=`../notes`">
        <div class="block"></div>
        <iframe src="../notes" frameborder="0"></iframe>
    </section>
</body>
</html>

<?php
//logic
if(isset($_POST["folder_edit"])) {

    //notes in folder
    $sdbFolderNotes = [];

    $dbNOTEpre->data_seek(0);
    while ($note = $dbNOTEpre->fetch_assoc()) {
        if(isset($_POST["note_".$note["id"]])) {
            array_push($sdbFolderNotes, $note["id"]);
        }
    }
    asort($sdbFolderNotes);
    $sdbFolderNotesString = implode(";", $sdbFolderNotes);


    //delete
    if(isset($_POST["delete"])) {
        mysqli_query($con, "DELETE FROM `notes_group` WHERE id = '$folderID' AND owner_id = '$userID'");
    } else {
        $folderName = $_POST["folder_title"];
        mysqli_query($con, "UPDATE `notes_group` SET `name` = '$folderName', `notes_id` = '$sdbFolderNotesString' WHERE id = '$folderID' AND owner_id = '$userID'");
    }

    header("Location: ../notes");
} elseif (isset($_POST["cancle"])) {
    header("Location: ../notes");
}
?>