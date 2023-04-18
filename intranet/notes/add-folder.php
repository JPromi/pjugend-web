<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");
?>

<?php
$userID = $dbSESSION["user_id"];
include '../../private/database/int.php';

?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordner Erstellen - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="css/add-edit-folder.css">
</head>
<body>
    <section class="popup">
        <div class="window">
            <form method="post">
                <p>Ordner Erstellen</p>
                <input type="text" name="folder_title">
                <div class="button">
                    <input type="submit" name="folder_add" value="Erstellen">
                    <input type="submit" name="cancle" value="Abbrechen">
                </div>
            </form>
        </div>
    </section>

    <section class="background" onclick="window.location.href=`../notes`">
        <div class="block"></div>
        <iframe src="../notes" frameborder="0"></iframe>
    </section>
</body>
</html>

<?php
//logic
if(isset($_POST["folder_add"])) {

    $folderName = $_POST["folder_title"];
    mysqli_query($con, "INSERT INTO `notes_group` (name, owner_id) VALUE ('$folderName', '$userID')");

    header("Location: ../notes");
} elseif (isset($_POST["cancle"])) {
    header("Location: ../notes");
}
?>