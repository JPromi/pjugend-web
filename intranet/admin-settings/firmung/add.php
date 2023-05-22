<?php
//include auth_session.php file on all user panel pages
include("../../../private/session/auth_session.php");
include '../../../private/database/firmung.php';
include '../../../private/intranet/image/firmung.php';

?>

<?php
if(!(in_array("firmung_admin", $dbSESSION_perm))) {
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
    <title>Firmung Erstellen - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/add.css">
                
    <?php
    include '../../../private/favicon/main.php';
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
        top("Firmung");
        ?>
        <div class="settings">
            <?php
            include '../../../private/intranet/admin-settings/nav-firmung.php';
            ?>
            <form class="middle" method="POST" enctype="multipart/form-data">
                <h2>Neue Firmung erstellen</h2>

                <div class="block">
                    <h3>Allgemein</h3>
                    <label>Titel: <input type="text" name="title"></label>

                    <div class="text">
                        <p>Beschreibung:</p>
                        <textarea name="description"></textarea>
                    </div>
                    
                    <label>Jahr: <input type="number" min="2000" max="3000" name="year" maxlength="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required></label>

                    <label>Start: <input type="date" name="start" required></label>
                    <label>Ende: <input type="date" name="end" required></label>
                </div>
                
                <div class="block">
                    <h3>Logo</h3>
                    <input type="file" name="logo" accept="image/png, image/jpeg, image/gif">
                </div>

                <input type="submit" name="submit" value="Erstellen">
            </form>
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

    //check inputs
    $title = checkInput($_POST["title"]);
    $description = checkInput($_POST["description"]);
    $year = checkInput($_POST["year"]);
    $start = checkInput($_POST["start"]);
    $end = checkInput($_POST["end"]);

    //insert in database
    $con_firmung->query("INSERT INTO firmung (title, description, year, start_date, end_date) VALUES ($title, $description, $year, $start, $end)");
    if($con_firmung->error) {
        try {
            createLogo($_FILES['logo']['tmp_name'], $_FILES['logo']['type'], $_POST["year"]);
        } catch (\Throwable $th) {
            //throw $th;
            exit();
        }
    }
}

function checkInput($input) {
    global $con_firmung;
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($con_firmung, $input);

    if(!(empty($input))) {
        $input = "'".$input."'";
    } else {
        $input = "NULL";
    }

    return $input;
}
?>