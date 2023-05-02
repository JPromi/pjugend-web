<?php
include '../private/config.php';
include '../private/database/int.php';
include '../private/database/public.php';
include '../private/database/form.php';
?>

<?php
$formID = $_GET["id"];
$form = "SELECT * FROM `form` WHERE id = '$formID'";
$form = $con_public_new->query($form);
$form = $form->fetch_assoc();


if (empty($form)) {
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
    <title>Formular - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/form.css">
    
    <?php
    include '../private/favicon/main.php';
    ?>
    
</head>

<?php
include '../private/web/assets/nav.php';
?>

<body>
    <form class="content" method="post">

        <!--normal content-->
        <div class="top">
            <h2><?php echo($form["title"]); ?></h2>
            <p><?php echo(str_replace("\n", "<br>", $form["title"])); ?></p>

            <div class="elements">
                <?php
                $formIndex = "SELECT * FROM `form_index` WHERE form_id = '$formID' ORDER BY place_index";
                $formIndex = $con_public_new->query($formIndex);

                $noInputs = array("stTitle", "stDesc");

                while ($formElement = $formIndex->fetch_assoc()) {
                    echo '<div class="single">';

                    if($formElement["required"] == "1") {
                        $thisRequired = "required";
                        $requiredStard = '<p class="required">*</p>';
                    } else {
                        $thisRequired = "";
                        $requiredStard = "";
                    }

                    if(!(in_array($formElement["type"], $noInputs))) {
                        echo '
                            <label>'.$requiredStard.' '.$formElement["title"].'<input type="'.$formElement["type"].'" name="form_field'.$formElement["id"].'" '.$thisRequired.'></label>
                        ';
                    } else if($formElement["type"] == "stTitle") {
                        echo '
                            <h3>'.$formElement["title"].'</h3>
                        ';
                    } else if($formElement["type"] == "stDesc") {
                        echo '
                            <p>'.str_replace("\n", "<br>", $formElement["title"]).'</>
                        ';
                    }
                    echo '</div>';
                }
                ?>
            </div>
            </div>
            <p class="required info">* = Pflichtfeld</p>
            <div class="btn">
                <input type="submit" name="submit" value="Speichern">
            </div>
            

        </form>
</body>

<?php
include '../private/web/assets/footer.php';
?>

</html>

<?php
if(isset($_POST["submit"])) {

    //select columns
    $formfields = "SHOW COLUMNS FROM form_$formID";
    $formfields = $con_form_new->query($formfields);

    //result/filed arrays
    $formFieldsArray = array();
    $formResultsArray = array();

    //set var
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    //push formfiled name in array
    while ($formfield = $formfields->fetch_assoc()) {
        if(str_contains($formfield["Field"], "form_field")) {
            array_push($formFieldsArray, $formfield["Field"]);
        };
    }

    echo '<br>'.json_encode($formFieldsArray).'<br>';

    //push result in array
    foreach ($formFieldsArray as $formField) {
        array_push($formResultsArray, "'".$_POST[$formField]."'");
    }

    $formFieldsString = implode(", ", $formFieldsArray);
    $formResultsString = implode(", ", $formResultsArray);

    //insert into
    $formfieldsPost = "INSERT INTO form_$formID (ip_address, $formFieldsString) VALUES ('$ipAddress', $formResultsString)";
    $formfieldsPost = $con_form->query($formfieldsPost);

    $postID = $con_form->insert_id;

    //redirect
    echo '<meta http-equiv="refresh" content="0; url=/form/post?post_id='.$postID.'&form_id='.$formID.'">';
}
?>