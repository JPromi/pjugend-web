<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");

include("../../private/database/int.php");
include("../../private/database/public.php");
include("../../private/database/form.php");
?>

<?php
$formID = $_GET["id"];
$form = "SELECT * FROM `form` WHERE id = '$formID'";
$form = $con_public_new->query($form);
$form = $form->fetch_assoc();
$formID = $form["id"];

if(empty($form)) {
    header("Location: ../form");
    exit();
};

/*if(!(in_array($dbSESSION["user_id"], explode(";", $form["organizer"]))) && !(in_array("jugendteam_admin", $dbSESSION_perm))) {
    header("Location: ../form");
    exit();
}*/

?>

<?php
if(!(in_array("form", $dbSESSION_perm))) {
    header("Location: ../form");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formular Bearbeiten -  <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/add.css">
                
    <?php
    include '../../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include("../../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <?php
    include("../../private/intranet/form/header.php");
    top("Formular Bearbeiten");
    ?>

    <form class="content" method="post">
        <!--alert box-->
        <div class="alert disabled" id="alert">

            <!--advanced settings-->
            <div class="alertbox disabled settings" id="settings">
                <h1>Einstellungen</h1>

                <div class="elements">

                    <div class="element">
                        <h3>Sicherheit</h3>
                        <?php
                        if($form["protected"] == "1") {
                            $checkboxProtected = "checked";
                        } else {
                            $formPasswordClasss = "disabled";
                        }
                        ?>
                        <label><input type="checkbox" name="protected" <?php echo($checkboxProtected); ?> onchange="passwordProtection('formpassword')"> Schützen</label>
                        <label id="formpassword" class="<?php echo($formPasswordClasss); ?>">Passwort: <input type="text" name="formpassword" value="<?php echo($form["password"]); ?>"></label>
                    </div>

                    <div class="element">
                        <h3>Mitglieder</h3>
                        <p>Bearbeiter / Betrachter hinzufügen</p>
                        <a onclick="alertadd('member')">
                            <span class="material-symbols-outlined">
                            add
                            </span>
                        </a>
                    </div>

                </div>
            </div>

            <!--advanced settings-->
            <div class="alertbox disabled member" id="member">
                <h1>Mitglieder</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>Bearbeiten</th>
                                <th>Betrachter</th>
                                <th>Benutzer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $users = "SELECT id, firstname, lastname, username FROM `accounts` WHERE NOT id = '".$dbSESSION["user_id"]."'";
                            $users = $con_new->query($users);

                            while ($user = $users->fetch_assoc()) {
                                $editor = "";
                                $viewer = "";
                                if(in_array($user["id"], explode(";", $form["user_edit"]))) {
                                    $editor = "checked";
                                }

                                if(in_array($user["id"], explode(";", $form["viewer"]))) {
                                    $viewer = "checked";
                                }

                                echo '
                                <tr>
                                    <td>
                                        <input type="checkbox" name="editor[]" value="'.$user["id"].'" '.$editor.'>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="viewer[]" value="'.$user["id"].'" '.$viewer.'>
                                    </td>
                                    <td>
                                        <p>'.$user["firstname"].' '.$user["lastname"].'</p>
                                    </td>
                                </tr>
                                ';
                            };
                            ?>
                            
                        </tbody>
                    </table>
            </div>

            <div class="back disabled" id="back" onclick="removealert()">

            </div>
        </div>

        <!--normal content-->
        <div class="top">
            <div class="buttons">
                <a onclick="alertadd('settings')">
                    <span class="material-symbols-outlined">
                    settings
                    </span>
                </a>
            </div>
            <input type="text" name="title" placeholder="Titel" required value="<?php echo($form["title"]); ?>">
            <textarea name="description" placeholder="Beschreibung"><?php echo($form["description"]); ?></textarea>
        </div>

        <div class="builder" id="builder">
            <?php
            $formIndex = "SELECT * FROM `form_index` WHERE form_id = '$formID'";
            $formIndex = $con_public_new->query($formIndex);

            while ($element = $formIndex->fetch_assoc()) {
                echo($element["place_index"]);
                echo '
                <div class="single" id="'.$element["place_index"].'">
                    <div class="settings">
                        <label>Type:</label>
                        <select name="type[]" id="setting" onchange="changeContent(`'.$element["place_index"].'`)">
                            <option value=""></option>
                            <option value="text">Text</option>
                            <option value="date">Date</option>
                            <option value="email">Email</option>
                            <option value="number">Number</option>
                            <option value="stTitle">Titel</option>
                            <option value="stDesc">Beschreibung</option>
                        </select>
                        <input type="hidden" name="id[]" value="">
                        <a onclick="removeField(`'.$element["place_index"].'`)" title="Löschen">
                            <span class="material-symbols-outlined">
                            delete
                            </span>
                        </a>
                    </div>
                    <div class="input" id="content'.$element["place_index"].'">
                        
                    </div>
                </div>
                ';
            }
            ?>
        </div>
        <div class="builder-settings">
            <a onclick="addFormField()">
                <span class="material-symbols-outlined">
                add
                </span>
            </a>
        </div>
        <div class="btn">
            <input type="submit" name="submit" value="Speichern">
            <a href="../form">Abbrechen</a>
        </div>
    </form>
    <script src="js/builder.js"></script>
    <script src="js/alert.js"></script>
    <script src="js/alert-content.js"></script>
</body>

<?php
//include scripts for bottom
include("../../private/intranet/assets/scripts-bottom.php");
?>

</html>

<?php

if(!(empty($_POST["submit"]))) {
    $PostTitle = htmlspecialchars($_POST["title"]);
    $PostDesc = htmlspecialchars($_POST["description"]);
    $ownerID = $dbSESSION["user_id"];

    $tableArray = array();

    //permissions
    $viewerID;
    $editorID;

    if(!empty($_POST["editor"])) {
        $editorID = implode(";", $_POST["editor"]);
    };

    if(!empty($_POST["viewer"])) {
        $viewerID = implode(";", $_POST["viewer"]);
    };

    //insert into form settings
    $addForm = "INSERT INTO `form`    (`title`, `description`, `owner`, `result_viewer`, `user_edit`) VALUES
                                        ('$PostTitle', '$PostDesc', '$ownerID', '$viewerID', '$editorID')";
    $con_public->query($addForm);
    $formID = $con_public->insert_id;

    //insert in form index
    if (!empty($_POST["type"])) {
    for ($i=0; $i < count($_POST["type"]); $i++) {
        $required = "0";
        if(!($_POST["type"] == "")) {

            if(!empty($_POST["required"])) {
                if(in_array($_POST["id"][$i], $_POST["required"])) {
                    $required = "1";
                }
            }
            
            $placeIndex = $i + 1;
            $title = htmlspecialchars($_POST["text"][$i]);
            $type = htmlspecialchars($_POST["type"][$i]);
            $addFormIndex = "INSERT INTO `form_index`   (`form_id`, `place_index`, `title`, `type`, `required`) VALUES
                                                        ('$formID', '$placeIndex', '$title','$type' , '$required')";
            $con_public->query($addFormIndex);

            array_push($tableArray, "form_field" . $i+1 . " VARCHAR(255)");
        }
    }

    $tableString = implode(", ", $tableArray);

    //create table
    $createTable = "CREATE TABLE form_".$formID." (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        ".$tableString.",
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        ip_address VARCHAR(255)
        )";
        
    $con_form_new->query($createTable);
    }
    //go back
    echo('<meta http-equiv="refresh" content="0; url=view?id='.$formID.'">');
}
?>

<?php
//functions

//builder
function contentBlock($block, $blockID) {
    $textBlock = array("text", "date", "email", "number");

    // get content
    if(in_array($form, $textBlock)) {
        $content = '
                <label><input type="text" name="text[]" id="" placeholder="Text"></label>
                <input type="text" placeholder="Input" disabled>
            ';
    } else if ($block == "checkbox") {
        $content = '
        <div id="checkboxes` + blockID + `">
            <label><input type="checkbox" disabled> <input type="input" name="text[]" placeholder="Name"></label>
            <label><input type="checkbox" disabled> <input type="input" name="text[]" placeholder="Name"></label>
            <label><input type="checkbox" disabled> <input type="input" name="text[]" placeholder="Name"></label>
            <label><input type="checkbox" disabled> <input type="input" name="text[]" placeholder="Name"></label>
            <label><input type="checkbox" disabled> <input type="input" name="text[]" placeholder="Name"></label>
        </div>
        ';
    }

    $content = $content + `
        <label class="important"><input type="checkbox" name="required[]" value="`+blockID+`"> Pflichtfeld</label>
    `;

    if($block == "") {
        $content = "";
    } else if ($block == "stTitle") {
        $content = `
        <label class="title"><input type="text" name="text[]" placeholder="Titel"></label>
        `;
    } else if ($block == "stDesc") {
        $content = `
        <label class="description"><textarea name="text[]" placeholder="Beschreibung"></textarea></label>
        `;
    }

    return '<div class="single" id="'.$blockID.'">'.$content.'</div>';
}
?>