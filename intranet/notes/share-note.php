<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");
?>
<?php
//include("../assets/html/favicon.html");
?>

<?php
$noteID = $_GET["note"];
$userID = $dbSESSION["user_id"];

include '../../private/database/int.php';

$dbNOTEinfo = "SELECT * FROM `notes` WHERE id = '$noteID' AND owner_id = '$userID'";
$dbNOTEinfo = $con_new->query($dbNOTEinfo);
$dbNOTEinfo = $dbNOTEinfo->fetch_assoc();

if (empty($dbNOTEinfo)) {
    header("Location: ../notes");
}

?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notiz Freigeben - Arcade</title>

    <link rel="stylesheet" href="css/share-note.css">
</head>
<body>
    <section class="popup">
            <form method="post">
                <?php echo '<h1>'.$dbNOTEinfo["title"].' Freigeben</h1>'; ?>

                <input type="text" id="searchUser" onkeyup="searchShareUser()" placeholder="Benutzer suchen">
                <div class="select">
                    <table>
                        <thead>
                            <tr>
                                <th>Lesen</th>
                                <th>Schreiben</th>
                                <th>Benutzer</th>
                            </tr>
                        </thead>
                        <tbody id="users">
                            
                            <?php
                                //get array
                                $shareWrite = explode(";", $dbNOTEinfo["writer_id"]);
                                $shareRead = explode(";", $dbNOTEinfo["reader_id"]);

                                //get user
                                $dbUsers = "SELECT * FROM `accounts` ORDER BY firstname";
                                $dbUsers = $con_new->query($dbUsers);

                                while ($user = $dbUsers->fetch_assoc()) {
                                    if(!($user["id"] == $userID)){ 
                                        $readPerm = "";
                                        $writePerm = "";
                                        
                                        //write
                                        if (in_array($user["id"], $shareWrite)) {
                                            $writePerm = "checked";
                                        };

                                        if (in_array($user["id"], $shareRead)) {
                                            $readPerm = "checked";
                                        };

                                        echo '
                                            <tr class="row">
                                                <td class="btn"><input type="checkbox" name="read_u'.$user["id"].'" '.$readPerm.'></td>
                                                <td class="btn"><input type="checkbox" name="write_u'.$user["id"].'" '.$writePerm.'></td>
                                                <td><p>'.$user["firstname"].' '.$user["lastname"].'</p></td>
                                            </tr>
                                        ';
                                    } 
                                }
                            ?>
                        
                        </tbody>
                    </table>
                </div>
                <input type="submit" name="share" value="Freigeben">
                <input type="submit" name="cancle" value="Abbrechen">
            </form>
            <script src="js/search.js"></script>
    </section>

    <section class="background" onclick="window.location.href=`../notes`">
        <div class="block"></div>
        <iframe src="../notes" frameborder="0"></iframe>
    </section>
</body>
</html>

<?php
//logic
if(isset($_POST["share"])) {

    //notes in folder
    $sdbUsersRead = [];
    $sdbUsersWrite = [];

    $dbUsers->data_seek(0);
    while ($user = $dbUsers->fetch_assoc()) {
        //read
        if(isset($_POST["read_u".$user["id"]])) {
            array_push($sdbUsersRead, $user["id"]);
        }

        //write
        if(isset($_POST["write_u".$user["id"]])) {
            array_push($sdbUsersWrite, $user["id"]);
        }
    }

    //sort and convert to srting
    asort($sdbUsersRead);
    asort($sdbUsersWrite);
    $sdbUsersReadString = implode(";", $sdbUsersRead);
    $sdbUsersWriteString = implode(";", $sdbUsersWrite);


    $folderName = $_POST["folder_title"];
    mysqli_query($con, "UPDATE `notes` SET `writer_id` = '$sdbUsersWriteString', `reader_id` = '$sdbUsersReadString' WHERE id = '$noteID' AND owner_id = '$userID'");

    echo('<meta http-equiv="refresh" content="0; url=../notes">');
} else if (isset($_POST["cancle"])) {
    echo('<meta http-equiv="refresh" content="0; url=../notes">');
}
?>