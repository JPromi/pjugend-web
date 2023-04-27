<?php
//include auth_session.php file on all user panel pages
include("../../../private/session/auth_session.php");
include '../../../private/database/public.php';
?>

<?php
if(!(in_array("jugendteam_admin", $dbSESSION_perm))) {
    header("Location: /");
    exit();
}
?>

<?php
$socialmedias = "SELECT * FROM socialmedia";
$socialmedias = $con_public->query($socialmedias);
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/edit.css">
                    
    <?php
    include '../../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include("../../../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <div class="content">
        <?php
        include '../../../private/intranet/admin-settings/header.php';
        top("Social Media");
        ?>
        <div class="settings">
            <?php
            include '../../../private/intranet/admin-settings/nav-social_media.php';
            ?>
            <form class="middle" method="POST">
                <h2>Social Media</h2>

                <div class="linklist">
                    <div class="links" id="links">
                        <?php
                        while ($social = $socialmedias->fetch_assoc()) {
                        ?>
                        
                            <a class="single" data-id="<?php echo($social["id"]); ?>">
                                <div class="btn">
                                    <span class="material-symbols-outlined" onclick="deleteLink('<?php echo($social["id"]); ?>')">
                                    delete
                                    </span>
                                </div>

                                <div class="edit" id="edit1">
                                    <input type="hidden" name="id[]" value="<?php echo($social["id"]); ?>">
                                    <input type="hidden" name="index_id[]" value="<?php echo($social["index_id"]); ?>">
                                    <label>Titel: <input type="text" name="title[]" placeholder="Titel" class="name" value="<?php echo($social["title"]); ?>" required></label>
                                    <label>Link: <input type="text" name="link[]" placeholder="Link" value="<?php echo($social["link"]); ?>" required></label>
                                </div>
                            </a>

                        <?php
                            }
                        ?>
                    </div>
                    
                    <a class="single add" onclick="addLink()">
                        <p class="ele">
                            <span class="material-symbols-outlined">
                            add
                            </span>
                            Neues Element
                        </p>
                    </a>
                </div>
                <input type="submit" name="submit" value="Speichern">
            </form>
            <script src="js/edit.js"></script>
        </div>
    </div>

</body>

<?php
//include scripts for bottom
include("../../../private/intranet/assets/scripts-bottom.php")
?>

</html>

<?php

if(isset($_POST["submit"])) {

    //get existing ids
    $selectAll = "SELECT * FROM `socialmedia`";
    $selectAll = $con_public->query($selectAll);

    $available = array();

    while ($sm = $selectAll->fetch_assoc()) {
        array_push($available, $sm["id"]);
    }

    //foreach element
    try {
        //code...
    
        for ($i=0; $i < count($_POST["link"]); $i++) { 

            $title = checkInput($_POST["title"][$i]);
            $link = checkInput($_POST["link"][$i]);
            $id = checkInput($_POST["id"][$i]);
            $index_id = checkInput($_POST["index_id"][$i]);

            if(!($_POST["link"][$i] == "")) {
                if(in_array($_POST["id"][$i], $available)) {
                    $update = "UPDATE `socialmedia` SET title = $title, link = $link, index_id = $index_id WHERE id = $id";
                    $con_public->query($update);
                } else {
                    $insert = "INSERT INTO socialmedia (title, link, index_id) VALUES ($title, $link, $index_id)";
                    $con_public->query($insert);
                }
            }
            
        }
    } catch (\Throwable $th) {

    }

    try {
        //delete
        for ($i=0; $i < count($available); $i++) { 
            if(!(in_array($available[$i], $_POST["id"]))) {
                $delete = "DELETE FROM socialmedia WHERE id = '".$available[$i]."'";
                $con_public->query($delete);
                echo("del".$available[$i]);
            }
        }
    } catch (\Throwable $th) {

    }

    if(!(isset($_POST["link"]))) {
        $delete = "DELETE FROM socialmedia";
        $con_public->query($delete);
    }

    echo '<meta http-equiv="refresh" content="0; url=">';
    
}

?>
<?php

//function
function checkInput($input) {
    global $con_public;
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($con_public, $input);

    if(!(empty($input))) {
        $input = "'".$input."'";
    } else {
        $input = "NULL";
    }

    return $input;
}
?>