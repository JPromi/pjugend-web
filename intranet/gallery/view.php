<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");

include("../../private/database/int.php");
include("../../private/database/public.php");
include("../../private/config.php");
include("../../private/intranet/image/gallery.php");
?>

<?php
$hash_id = mysqli_real_escape_string($con_public, stripcslashes($_GET["id"]));
$selectedGallery = "SELECT * FROM gallery WHERE hash_id = '$hash_id'";
$selectedGallery = $con_public->query($selectedGallery);
$selectedGallery = $selectedGallery->fetch_assoc();
$hash_id = $selectedGallery["hash_id"];

if(!isset($selectedGallery)) {
    header("Location: ../gallery");
    exit();
}

?>

<?php
if($selectedGallery["owner"] == $dbSESSION["user_id"] || in_array("jugendteam_admin", $dbSESSION_perm) || in_array($dbSESSION["user_id"], explode(";", $selectedGallery["user_edit"]))) {

} else {
    header("Location: ../gallery");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/view.css">
    <link rel="stylesheet" href="/gallery/css/style.css">
        
    <?php
    include '../../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include("../../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <div class="content">

        <?php
        include '../../private/intranet/gallery/header.php';
        top("Galerie");
        ?>

        <form class="alert hidden" id="alert" method="POST" enctype="multipart/form-data">

            <div class="window hidden" id="settings">
                <h2>Einstellungen</h2>
                
                <div class="block">
                    <h3>Bilder Hochladen</h3>
                    <input type="file" name="upload_image[]" accept="image/png, image/jpeg" multiple>
                </div>

                <div class="block">
                    <h3>Sichtbarkeit</h3>
                    <label><input type="checkbox" name="public_view"> Öffentlich Sichtbar</label>
                    <label>Passwort: <input type="text" name="password"></label>
                </div>

                <div class="block">
                    <h3>Löschen</h3>
                    <label><input type="checkbox" name="delete_gallery"> Löschen</label>
                    <label><input type="checkbox" name="delete_gallery_verify"> Löschen Bestätigen</label>
                </div>
                <input type="submit" name="settings_save" value="Speichern">
            </div>

            <span class="back" onclick="closeAlerts()"></span>
        </form>

        <form class="gallery" method="POST">
            <?php
            include '../../private/intranet/gallery/nav.php';
            ?>
            <div class="middle">
                <h1><?php echo($selectedGallery["title"]); ?></h1>
                <p><?php echo str_replace("\n", '<br>', $selectedGallery["description"]); ?></p>
                <div class="btn">
                    <input type="submit" value="Download" name="download_all" title="alles Herunterladen">
                    <input type="button" onclick="openAlert('settings')" value="Settings">
                </div>

                <div class="btn hidden" id="checkedSettings">

                    <label title="Auswahl Löschen">
                        <input type="submit" name="delete" value="Löschen">
                    </label>

                    <label title="Auswahl Herunterladen">
                        <input type="submit" name="download_selection" value="Download">
                    </label>
                </div>

                <div class="images">
                    <?php
                    //select images
                    $files = scandir('../../cdn/gallery/'.$selectedGallery["hash_id"].'/images/');
                    try {
                        $files = array_slice($files, 2);
                        foreach ($files as $image) {
                            $imagePath = 'https://'.$domain["cdn"].'/gallery/'.$selectedGallery["hash_id"].'/thumbnail/'.$image;
                            echo '
                            <label>
                                <input type="checkbox" name="image[]" value="'.$image.'" class="imageCheckbox">
                                    <span class="material-symbols-outlined checkbox">
                                    check_circle
                                    </span>
                                <img src="'.$imagePath.'" onclick="window.location.href=`image?g='.$selectedGallery["hash_id"].'&i='.$image.'`">
                            </label>
                            ';
                        }
                    } catch (\Throwable $th) {

                    }
                    ?>
                </div>
            </div>
            
        </form>
        <script src="js/select.js"></script>
        <script src="js/settings.js"></script>

    </div>
    
</body>

<?php
//include scripts for bottom
include("../../private/intranet/assets/scripts-bottom.php")
?>

</html>

<?php

//delete images
if(isset($_POST["delete"])) {
    for ($i=0; $i < count($_POST["image"]); $i++) { 
        unlink("../../cdn/gallery/".$hash_id.'/thumbnail/'.$_POST["image"][$i]);
        unlink("../../cdn/gallery/".$hash_id.'/images/'.$_POST["image"][$i]);
        unlink("../../cdn/gallery/".$hash_id.'/original/'.$_POST["image"][$i]);
    }
}

//download all
if(isset($_POST["download_all"])) {

    $filename = $hash_id."-".strtotime(date("Y-m-d H:i:s")).".zip";

    $pathdir = "../../cdn/gallery/".$hash_id."/original/"; 
    $zipcreated = "../../cdn/gallery/tmp/".$filename;

    $zip = new ZipArchive;
    
    if($zip -> open($zipcreated, ZipArchive::CREATE ) === TRUE) {

        $dir = opendir($pathdir);
        
        while($file = readdir($dir)) {
            if(is_file($pathdir.$file)) {
                $zip -> addFile($pathdir.$file, $file);
            }
        }
        $zip ->close();
    }

    //echo '<meta http-equiv="refresh" content="0; url=https://'.$domain["cdn"].'/gallery/tmp/'.$filename.'">';
    echo '<meta http-equiv="refresh" content="0; url=/gallery/module/download?tmp='.$filename.'">';
}

//download specific
if(isset($_POST["download_selection"])) {

    $filename = $hash_id."-".strtotime(date("Y-m-d H:i:s")).".zip";

    $pathdir = "../../cdn/gallery/".$hash_id."/original/"; 
    $zipcreated = "../../cdn/gallery/tmp/".$filename;

    $zip = new ZipArchive;
    
    if($zip -> open($zipcreated, ZipArchive::CREATE ) === TRUE) {

        //$dir = opendir($pathdir);

        foreach ($_POST["image"] as $image) {
            $zip -> addFile($pathdir.$image, $image);
        }

        $zip ->close();
    }

    //echo '<meta http-equiv="refresh" content="0; url=https://'.$domain["cdn"].'/gallery/tmp/'.$filename.'">';
    echo '<meta http-equiv="refresh" content="0; url=/gallery/module/download?tmp='.$filename.'">';
}
?>

<?php
//settings
if(isset($_POST["settings_save"])) {
    
    //upload
    if($_FILES["upload_image"]["tmp_name"][0] != "") {
        //gallery
        for($i=0 ; $i < count($_FILES["upload_image"]["name"]); $i++) {
            try {
                //createImage($_FILES['upload_image']['tmp_name'][$i], $_FILES['upload_image']['type'][$i], substr(md5(date("Y-m-d h:m:i")) , 0, 5).$i."-".pathinfo($_FILES['upload_image']['name'][$i], PATHINFO_FILENAME), $hash_id);
                createImage($_FILES['upload_image']['tmp_name'][$i], $_FILES['upload_image']['type'][$i], pathinfo($_FILES['upload_image']['name'][$i])['filename']."-".$i.substr(md5(date("Y-m-d h:m:i")) , 0, 5), $hash_id);
            } catch (\Throwable $th) {
                //throw $th;
                echo("<p class='error'>Fehler: ".$_FILES['upload_image']['name'][$i]."</p>");
                exit();
            }
        }
    }


    //delete gallery
    if($_POST["delete_gallery"] == "on") {
        if($_POST["delete_gallery_verify"] == "on") {
            
            $folder = '../../cdn/gallery/'.$hash_id;
 
            //delete folder
            system("rm -rf ".escapeshellarg($folder));


            //delete from database
            $deleteGallery = "DELETE FROM gallery WHERE hash_id = '$hash_id'";
            $con_public->query($deleteGallery);
        }
    }

    echo '<meta http-equiv="refresh" content="0; url=">';
}


?>