<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");

include("../../private/database/int.php");
include("../../private/database/public.php");
include("../../private/intranet/image/gallery.php");
?>

<?php
if(!(in_array("gallery", $dbSESSION_perm)) || !(in_array("jugendteam_admin", $dbSESSION_perm))) {
    header("Location: /");
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
    <link rel="stylesheet" href="css/add.css">
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

        <div class="gallery">
            <?php
            include '../../private/intranet/gallery/nav.php';
            ?>
            <form class="middle" method="POST" enctype="multipart/form-data">

                <!--Alerts-->
                <div class="alert hidden" id="alert">

                    <div class="window hidden" id="user">
                        <h2>Benutzer</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        Bearbeiten
                                    </th>
                                    <th>
                                        Benutzer
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="user_edit" value="userID">
                                    </td>
                                    <td>
                                        Jonas Prom
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="window hidden" id="event">
                        <h2>Veranstalltungen</h2>
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="event" value="eventID">
                                    </td>
                                    <td>
                                        Test
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <span class="back" onclick="closeAlerts()"></span>
                </div>

                <input type="text" name="title" placeholder="Titel">
                <textarea name="description"></textarea>

                <div class="block settings">
                    <label><input type="checkbox" name="public_view"> Öffentlich sichtbar</label>
                    <!--<input type="password" name="password" placeholder="Passwort">-->
                    <a onclick="openAlert('user')">Benutzer</a>
                    <a onclick="openAlert('event')">Veranstalltung</a>
                </div>
                <div class="block">
                    <input type="file" name="images[]" accept="image/png, image/jpeg" multiple>
                </div>
                <input type="submit" name="submit" value="Speichern">
            </form>
            <script src="js/settings.js"></script>
        </div>

    </div>
    
</body>

<?php
//include scripts for bottom
include("../../private/intranet/assets/scripts-bottom.php")
?>

</html>

<?php
if(isset($_POST["submit"])) {
    $title = checkInput($_POST["title"]);
    $description = checkInput($_POST["description"]);
    $ownerID = "'".$dbSESSION["user_id"]."'";
    $hashID = "g-".bin2hex(random_bytes(5)).$dbSESSION["user_id"].substr(md5(date("Y-m-d h:m:i")) , 0, 5); 

    
    $addGallery  = "INSERT INTO gallery (title, description, owner, hash_id) VALUES ($title, $description, $ownerID, '$hashID')";
    $con_public->query($addGallery);


    mkdir("../../cdn/gallery/".$hashID);
    mkdir("../../cdn/gallery/".$hashID.'/thumbnail');
    mkdir("../../cdn/gallery/".$hashID.'/images');
    mkdir("../../cdn/gallery/".$hashID.'/original');

    //gallery
    if($_FILES["images"]["tmp_name"][0] != "") {
        //gallery
        for($i=0 ; $i < count($_FILES["images"]["name"]); $i++) {
            try {
                createImage($_FILES['images']['tmp_name'][$i], $_FILES['images']['type'][$i], substr(md5(date("Y-m-d h:m:i")) , 0, 5).$i."-".pathinfo($_FILES['images']['name'][$i], PATHINFO_FILENAME), $hashID);
            } catch (\Throwable $th) {
                //throw $th;
                echo("<p class='error'>Fehler: ".$_FILES['images']['name'][$i]."</p>");
                exit();
            }
        }
    }

    echo '<meta http-equiv="refresh" content="0; url=/gallery">';
}
?>

<?php

//function
function checkInput($input) {
    global $con;
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($con, $input);

    if(!(empty($input))) {
        $input = "'".$input."'";
    } else {
        $input = "NULL";
    }

    return $input;
}
?>