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
            $formIndex = "SELECT * FROM `form_index` WHERE `form_id` = '$formID' AND `disabled` = '0'";
            $formIndex = $con_public_new->query($formIndex);

            $counterFormElements = 0;

            while ($element = $formIndex->fetch_assoc()) {
                //echo($element["place_index"]);

                $counterFormElements++;

                $form_option_text = NULL;
                $form_option_date = NULL;
                $form_option_email = NULL;
                $form_option_number = NULL;
                $form_option_stTitle = NULL;
                $form_option_stDesc = NULL;

                if($element["type"] == "text") {
                    $form_option_text = "selected";
                } else if($element["type"] == "date") {
                    $form_option_date = "selected";
                } else if($element["type"] == "email") {
                    $form_option_email = "selected";
                } else if($element["type"] == "number") {
                    $form_option_number = "selected";
                } else if($element["type"] == "stTitle") {
                    $form_option_stTitle = "selected";
                } else if($element["type"] == "stDesc") {
                    $form_option_stDesc = "selected";
                }
                echo '
                <div class="single" id="'.$element["place_index"].'">
                    <div class="settings">
                        <label>Type:</label>
                        <select name="type[]" id="setting'.$element["place_index"].'" onchange="changeContent(`'.$element["place_index"].'`)">
                            <option value=""></option>
                            <option value="text" '.$form_option_text.'>Text</option>
                            <option value="date" '.$form_option_date.'>Date</option>
                            <option value="email" '.$form_option_email.'>Email</option>
                            <option value="number" '.$form_option_number.'>Number</option>
                            <option value="stTitle" '.$form_option_stTitle.'>Titel</option>
                            <option value="stDesc" '.$form_option_stDesc.'>Beschreibung</option>
                        </select>
                        <input type="hidden" name="id_element[]" value="'.$element["id"].'">
                        <input type="hidden" name="id[]" value="'.$element["place_index"].'">
                        <a onclick="removeField(`'.$element["place_index"].'`)" title="Löschen">
                            <span class="material-symbols-outlined">
                            delete
                            </span>
                        </a>
                    </div>
                    <div class="input" id="content'.$element["place_index"].'">
                ';

                if($element["type"] == 'stTitle') {
                    echo '
                        <label class="title"><input type="text" name="text[]" placeholder="Titel" value="'.$element["title"].'"></label>
                    ';
                } else if ($element["type"] == 'stDesc') {
                    echo '
                        <label class="description"><textarea name="text[]" placeholder="Beschreibung">'.$element["title"].'</textarea></label>
                    ';
                } else {
                    if($element["required"] == "1") {
                        $elementRequired = 'checked';
                    } else {
                        $elementRequired = '';
                    }
                    echo '
                        <label><input type="text" name="text[]" id="" placeholder="Text" value="'.$element["title"].'"></label>
                        <input type="text" placeholder="Input" disabled>
                        <label class="important"><input type="checkbox" name="required[]" value="'.$element["id"].'" '.$elementRequired.'> Pflichtfeld</label>
                    ';
                }

                echo '
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
    
    //select all form_elements
    $formIndex = "SELECT * FROM `form_index` WHERE `form_id` = '$formID' AND `disabled` = '0'";
    $formIndex = $con_public->query($formIndex);

    $oldElements = array();

    while ($element = $formIndex->fetch_assoc()) {
        array_push($oldElements, $element["id"]);
    }

    //disable all deleted elements
    foreach ($oldElements as $oldElement) {
        
        if(!in_array($oldElement, $_POST["id_element"])) {
            $con_public->query("UPDATE form_index SET `disabled` = '1' WHERE id = $oldElement AND form_id = $formID");
        }
    }

    //foreach element
    $p_counter1 = 0;
    foreach ($_POST["id_element"] as $element) {

        //update element
        if(in_array($element, $oldElements)) {
            $element_ci_title = checkInput($_POST["text"][$p_counter1]);
            $element_ci_type = checkInput($_POST["type"][$p_counter1]);
            $element_ci_place_index = checkInput($_POST["id"][$p_counter1]);

            if(isset($_POST["required"])) {
                if(in_array($element, $_POST["required"])) {
                    $element_ci_required = '1';
                } else {
                    $element_ci_required = '0';
                }
            } else {
                $element_ci_required = '0';
            }

            
            $con_public->query("UPDATE form_index SET title = $element_ci_title, `place_index` = $element_ci_place_index, `type` = $element_ci_type, `required` = $element_ci_required WHERE id = '$element' AND form_id = '$formID'");
        } 
        //new element
        else {
            //check inputs
            $element_ci_title = checkInput($_POST["text"][$p_counter1]);
            $element_ci_type = checkInput($_POST["type"][$p_counter1]);
            $element_ci_place_index = checkInput($_POST["id"][$p_counter1]);

            if(isset($_POST["required"])) {
                if(in_array($element, $_POST["required"])) {
                    $element_ci_required = '1';
                } else {
                    $element_ci_required = '0';
                }
            } else {
                $element_ci_required = '0';
            }

            $con_public->query("INSERT INTO form_index (`form_id`, `place_index`, `title`, `type`, `required`) VALUES ('$formID', $element_ci_place_index, $element_ci_title, $element_ci_type, $element_ci_required)");

            echo $con_public->error;
            $newElementID = $con_public->insert_id;

            $con_form->query("ALTER TABLE `form_$formID` ADD `form_field$newElementID` TEXT");
            echo $con_form->error;
        }
        $p_counter1++;
    }

    //go back
    echo('<meta http-equiv="refresh" content="0; url=view?id='.$formID.'">');
}
?>

<?php
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