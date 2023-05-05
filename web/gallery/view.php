<?php
include '../../private/config.php';
include '../../private/database/public.php';
?>

<?php
$hashID = mysqli_real_escape_string($con_public, stripcslashes($_GET["id"]));
$gallery = "SELECT * FROM gallery WHERE hash_id = '$hashID'";
$gallery = $con_public->query($gallery);
$gallery = $gallery->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/view.css">
    
    <?php
    include '../../private/favicon/main.php';
    ?>
    
</head>

<?php
include '../../private/web/assets/nav.php';
?>

<body>
        <?php
        //check if gallery has password
        if($gallery["password"]) {

            //check if cookie isset
            if($_COOKIE["PUBLIC_SESSION_ID"]) {
                $sessionPublic = $_COOKIE["PUBLIC_SESSION_ID"];
                $galleryID = $gallery["id"];
                
                $gallerySession = $con_public->query("SELECT * FROM gallery_session WHERE cookie_hash = '$sessionPublic' AND gallery_id = '$galleryID'");
                $gallerySession = $gallerySession->fetch_assoc();

                //check if cookie is expired
                if(empty($gallerySession)) {
                    echo '<meta http-equiv="refresh" content="0; url=verify?id='.$hashID.'">';
                    exit();
                }
            } else {
                echo '<meta http-equiv="refresh" content="0; url=verify?id='.$hashID.'">';
                exit();
            }
        }
        ?>

    <form class="content" method="POST">
        

        <div class="top">
            <h1><?php echo($gallery["title"]); ?></h1>
            <div class="btn">
                <label title="Alle Elemente Herunterladen">
                    <input type="submit" name="download_all" value="dl_all">
                    <span class="material-symbols-outlined">
                    download
                    </span>
                </label>
            </div>

            <div class="btn hidden" id="checkedSettings">
                <h4>Auswahl</h4>
                <label title="Auswahl Herunterladen">
                    <input type="submit" name="download_selection" value="dl">
                    <span class="material-symbols-outlined">
                    download
                    </span>
                </label>
            </div>
        </div>

        <div class="gallery" id="gallery">
        <?php
                    //select images
                    $files = scandir('../../cdn/gallery/'.$gallery["hash_id"].'/images/');
                    try {
                        $files = array_slice($files, 2);
                        foreach ($files as $image) {
                            $imagePath = 'https://'.$domain["cdn"].'/gallery/'.$gallery["hash_id"].'/thumbnail/'.$image;
                            echo '
                            <label class="image">
                                <input type="checkbox" name="image[]" value="'.$image.'" class="imageCheckbox" id="btn-'.$image.'">
                                    <span class="material-symbols-outlined checkbox">
                                    check_circle
                                    </span>
                                <img src="'.$imagePath.'" onclick="window.location.href=`image?g='.$gallery["hash_id"].'&i='.$image.'`">
                            </label>
                            ';
                        }
                    } catch (\Throwable $th) {

                    }
                    ?>
        </div>


    </form>
    <script src="js/select.js"></script>
</body>

<?php
include '../../private/web/assets/footer.php';
?>

</html>

<?php
//download all
if(isset($_POST["download_all"])) {

    $filename = $hashID."-".strtotime(date("Y-m-d H:i:s")).".zip";

    $pathdir = "../../cdn/gallery/".$hashID."/original/"; 
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

    $filename = $hashID."-".strtotime(date("Y-m-d H:i:s")).".zip";

    $pathdir = "../../cdn/gallery/".$hashID."/original/"; 
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