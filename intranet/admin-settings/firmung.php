<?php
//include auth_session.php file on all user panel pages
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/auth_session.php");
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/firmung.php';

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
    <title>Firmung - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/admin-settings/css/style.css">
    <link rel="stylesheet" href="/admin-settings/css/firmung.css">
                
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <div class="content">
        <?php
        include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/admin-settings/header.php';
        top("Firmung");
        ?>
        <div class="settings">
            <?php
            include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/admin-settings/nav-firmung.php';
            ?>
            <div class="middle">
                <?php
                $firmungen = "SELECT * FROM firmung ORDER BY year DESC";
                $firmungen = $con_firmung->query($firmungen);
                ?>
                <h2>Firmungen</h2>

                <div class="dataset">
                    
                    <h3>Aktuell: </h3>
                    <table>
                        <tbody>
                            <?php
                            while ($firmung = $firmungen->fetch_assoc()) {
                                if(strtotime($firmung["start_date"]) <= strtotime(date("Y-m-d")) && strtotime(date("Y-m-d")) <= strtotime($firmung["end_date"])) {
                                    echo '
                                    <tr onclick="window.location.href=`/admin-settings/firmung/view?year='.$firmung["year"].'`">
                                        <td>'.$firmung["year"].'</td>
                                        <td>'.$firmung["title"].'</td>
                                    </tr>
                                    ';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    
                    <h3>Alle</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Jahr</th>
                                <th>Titel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $firmungen->data_seek(0);
                                while ($firmung = $firmungen->fetch_assoc()) {
                                    echo '
                                    <tr onclick="window.location.href=`/admin-settings/firmung/view?year='.$firmung["year"].'`">
                                        <td>'.$firmung["year"].'</td>
                                        <td>'.$firmung["title"].'</td>
                                    </tr>
                                    ';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</body>

<?php
//include scripts for bottom
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/scripts-bottom.php")
?>

</html>