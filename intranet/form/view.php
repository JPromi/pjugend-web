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
    <title>Formular -  <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/view.css">
</head>
<?php
//include navigation bar
include("../../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <?php
    include("../../private/intranet/form/header.php");
    top("Formular");
    ?>

    <div class="content">

        <!--normal content-->
        <div class="top">
            <div class="settings">
                <a href="result?id=<?php echo($form["id"]); ?>">
                    <span class="material-symbols-outlined">
                    menu_book
                    </span>
                </a>
            </div>
            <h2><?php echo($form["title"]); ?></h2>
            <p><?php echo(str_replace("\n", "<br>", $form["title"])); ?></p>

            <div class="elements">
                <?php
                $formIndex = "SELECT * FROM `form_index` WHERE form_id = '$formID' ORDER BY place_index";
                $formIndex = $con_public_new->query($formIndex);

                $noInputs = array("stTitle", "stDesc");

                while ($formElement = $formIndex->fetch_assoc()) {
                    echo '<div class="single">';
                    if(!(in_array($formElement["type"], $noInputs))) {
                        echo '
                            <label>'.$formElement["title"].'<input type="'.$formElement["type"].'" disabled></label>
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

    </div>
</body>

<?php
//include scripts for bottom
include("../../private/intranet/assets/scripts-bottom.php");
?>

</html>