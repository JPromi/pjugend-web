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

<?php
$year = mysqli_real_escape_string($con_firmung, $_GET["year"]);

$firmung = "SELECT * FROM firmung WHERE `year` = $year";
$firmung = $con_firmung->query($firmung);
$firmung = $firmung->fetch_assoc();

if(!isset($firmung)) {
    header("Location: /admin-settings/firmung");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmung <?php echo($firmung["year"]) ?> Bearbeiten - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/view.css">
                
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
            include '../../../private/intranet/admin-settings/nav-firmung_single.php';
            ?>
            <div class="middle">
                <?php
                $img_logo_root = $_SERVER["DOCUMENT_ROOT"]."/../cdn/firmung/logo/".$firmung["year"].'.png';

                if(file_exists($img_logo_root)) {
                    $img_logo_path = "https://".$domain["cdn"]."/firmung/logo/".$firmung["year"].'.png';
                } else {
                    $img_logo_path = "https://".$domain["cdn"]."/firmung/logo/default.svg";
                }
                ?>
                <div class="title">
                    <img class="logo" src="<?php echo($img_logo_path); ?>">
                    <div class="text">
                        <h1>Firmung <?php echo($firmung["year"]); ?></h1>
                        <p><?php echo($firmung["description"]); ?></p>
                    </div>
                </div>

                <div class="bot">

                    <?php
                        $firmungID = $firmung["id"];
                        $firmlinge = "SELECT * FROM firmling WHERE firmung_id = '$firmungID'";
                        $firmlinge = $con_firmung->query($firmlinge);

                        $allFirmlinge = 0;

                        while ($firmling = $firmlinge->fetch_assoc()) {
                            $allFirmlinge++;
                        }
                    ?>
                    <!--firmlinge-->
                    <div class="single">
                        <h3>Firmlinge</h3>
                        <span class="line-title"></span>

                        <p><b>Alle:</b> <?php echo($allFirmlinge); ?></p>
                        <?php
                        $group_block = "SELECT * FROM firmung_group_block WHERE firmung_id = '$firmungID'";
                        $group_block = $con_firmung->query($group_block);

                        while ($group = $group_block->fetch_assoc()) {
                            $groupCount = $group["id"];
                            $groupCount = "SELECT COUNT(id) FROM firmling WHERE group_block_id = '$groupCount'";
                            $groupCount = $con_firmung->query($groupCount);
                            $groupCount = $groupCount->fetch_assoc();

                            echo '
                                <p><b>'.$group["name"].':</b> '.$groupCount['COUNT(id)'].'</p>
                            ';
                        }
                        ?>
                    </div>

                    <!--zeitraum-->
                    <div class="single">
                        <h3>Zeitraum</h3>
                        <span class="line-title"></span>

                        <p><b>Start:</b> <?php echo(date("d.m.Y", strtotime($firmung["start_date"]))); ?></p>
                        <p><b>Ende:</b> <?php echo(date("d.m.Y", strtotime($firmung["end_date"]))); ?></p>
                    </div>

                    <!--Firmung date-->
                    <div class="single">
                        <h3>Firmtermine</h3>
                        <span class="line-title"></span>

                        <?php
                        $allFirmDates = "SELECT * FROM firmung_date WHERE firmung_id = '$firmungID'";
                        $allFirmDates = $con_firmung->query($allFirmDates);

                        while ($date = $allFirmDates->fetch_assoc()) {

                            echo '
                                <p><b>'.$date["title"].':</b> '.date("d.m.Y, H:i", strtotime($date["date"])).'</p>
                            ';
                        }
                        ?>
                    </div>

                    <!--events-->
                    <div class="single">
                        <h3>Aktionen</h3>
                        <span class="line-title"></span>

                        <?php
                        $allFirmEvents = "SELECT * FROM firmung_event WHERE firmung_id = '$firmungID'";
                        $allFirmEvents = $con_firmung->query($allFirmEvents);

                        while ($event = $allFirmEvents->fetch_assoc()) {

                            echo '
                                <p>'.$event["title"].'</p>
                            ';
                        }
                        ?>
                    </div>

                    <!--registrations-->
                    <div class="single">
                        <h3>Anmeldung</h3>
                        <span class="line-title"></span>

                        <?php
                        $registrationsCount = "SELECT COUNT(id) FROM firmung_registration WHERE firmung_id = '$firmungID'";
                        $registrationsCount = $con_firmung->query($registrationsCount);
                        $registrationsCount = $registrationsCount->fetch_assoc();

                        $registrationInfo = "SELECT * FROM firmung_registration_settings WHERE firmung_id = '$firmungID'";
                        $registrationInfo = $con_firmung->query($registrationInfo);
                        $registrationInfo = $registrationInfo->fetch_assoc();
                        ?>
                        <p><b>Anmeldungen:</b> <?php echo($registrationsCount["COUNT(id)"]); ?></p>
                        <p><b>Start:</b> <?php echo(date("d.m.Y, H:i", strtotime($registrationInfo["start"]))); ?></p>
                        <p><b>Ende:</b> <?php echo(date("d.m.Y, H:i", strtotime($registrationInfo["end"]))); ?></p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

</body>

<?php
//include scripts for bottom
include("../../../private/intranet/assets/scripts-bottom.php")
?>

</html>