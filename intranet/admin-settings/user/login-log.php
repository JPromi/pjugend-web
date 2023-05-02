<?php
//include auth_session.php file on all user panel pages
include("../../../private/session/auth_session.php");
include("../../../private/database/int.php");
?>

<?php
if(!(in_array("admin", $dbSESSION_perm))) {
    header("Location: /");
    exit();
}
?>

<?php
//get account
$accountID = $_GET["id"];
$account = "SELECT * FROM `accounts` WHERE id = '$accountID'";
$account = $con_new->query($account);
$account = $account->fetch_assoc();
$username = $account["username"];
?>

<?php
if(empty($account)) {
    header("Location: ../user");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo($account["username"]); ?> Login Log - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/admin-settings/css/style.css">
    <link rel="stylesheet" href="css/login-log.css">
                
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
        top("Benutzer");
        ?>
        <div class="settings">
            <?php
            include '../../../private/intranet/admin-settings/nav-user.php';
            ?>
            <div class="middle">
                <div class="title">
                    <h2>Informationen</h2>
                </div>
                <div class="info">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Benutzername</th>
                                <th>Status</th>
                                <th>IP Adresse</th>
                                <th>Zeit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $log = "SELECT * FROM login_log WHERE username = '$username' ORDER BY id DESC";
                            $log = $con->query($log);

                            while ($singleLog = $log->fetch_assoc()) {
                                echo '
                                <tr>
                                    <td>'.$singleLog["id"].'</td>
                                    <td>'.$singleLog["username"].'</td>
                                    <td>'.$singleLog["status"].'</td>
                                    <td>'.$singleLog["ip"].'</td>
                                    <td>'.date("h:i d.m.Y", strtotime($singleLog["timestamp"])).'</td>
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
include("../../../private/intranet/assets/scripts-bottom.php")
?>

</html>