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
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formular Einträge -  <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/result.css">
                
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
    top("Formular");
    ?>

    <div class="content">

        <!--normal content-->
        <div class="top">
            <div class="settings">

                <a href="result?id=<?php echo($form["id"]); ?>" title="Ergebnisse">
                    <span class="material-symbols-outlined">
                    menu_book
                    </span>
                </a>

                <a href="https://<?php echo($domain["web"]); ?>/form?id=<?php echo($form["id"]); ?>" title="Formular">
                    <span class="material-symbols-outlined">
                    description
                    </span>
                </a>

                <a onclick="copy('https://<?php echo($domain['web']); ?>/form?id=<?php echo($form['id']); ?>')" title="In Zwischenablage kopieren">
                    <span class="material-symbols-outlined">
                    content_copy
                    </span>
                </a>

                <script src="js/copy.js"></script>

            </div>
            <h2><?php echo($form["title"]); ?></h2>
            <p><?php echo(str_replace("\n", "<br>", $form["title"])); ?></p>

            <div class="elements">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <?php
                            $formIndex = "SELECT * FROM `form_index` WHERE form_id = '$formID' ORDER BY place_index";
                            $formIndex = $con_public_new->query($formIndex);

                            $noInputs = array("stTitle", "stDesc");
                            $formFileds = array();

                            while ($formElement = $formIndex->fetch_assoc()) {
                                if(!(in_array($formElement["type"], $noInputs))) {
                                    echo '
                                    <th>'.$formElement["title"].'</th>
                                    ';

                                    array_push($formFileds, $formElement["id"]);
                                }
                                
                            }

                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $formEntrys = "SELECT * FROM `form_$formID` ORDER BY id DESC";
                        $formEntrys = $con_form_new->query($formEntrys);

                        while ($entry = $formEntrys->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>'.$entry["id"].'</td>';
                            foreach ($formFileds as $field) {
                                echo '<td>'.$entry["form_field".$field].'</td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>

<?php
//include scripts for bottom
include("../../private/intranet/assets/scripts-bottom.php");
?>

</html>