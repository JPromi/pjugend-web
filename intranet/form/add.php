<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");

include("../../private/database/int.php");
include("../../private/database/public.php");
include("../../private/database/form.php");
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
    <title>Formular Erstellen -  <?php echo($conf_title["intranet"]); ?></title>

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
    top("Formular Erstellen");
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
                        <label><input type="checkbox" name="protected" onchange="passwordProtection('formpassword')"> Schützen</label>
                        <label id="formpassword" class="disabled">Passwort: <input type="text" name="formpassword"></label>
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
                                echo '
                                <tr>
                                    <td>
                                        <input type="checkbox" name="editor[]" value="'.$user["id"].'">
                                    </td>
                                    <td>
                                        <input type="checkbox" name="viewer[]" value="'.$user["id"].'">
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
            <input type="text" name="title" placeholder="Titel" required>
            <textarea name="description" placeholder="Beschreibung"></textarea>
        </div>

        <div class="builder" id="builder">
        </div>
        <div class="builder-settings">
            <a onclick="addFormField()">
                <span class="material-symbols-outlined">
                add
                </span>
            </a>
        </div>
        <div class="btn">
            <input type="submit" name="submit" value="Erstellen">
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
    $viewerID = array();
    $editorID = array();

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

            $formFieldID = $con_public->insert_id;

            array_push($tableArray, "form_field" . $formFieldID . " TEXT");
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