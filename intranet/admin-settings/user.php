<?php
//include auth_session.php file on all user panel pages
include("../../private/session/auth_session.php");
include '../../private/database/int.php';
?>

<?php
if(!(in_array("admin", $dbSESSION_perm))) {
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
    <title>Benutzer - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/user.css">
</head>
<?php
//include navigation bar
include("../../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <div class="content">
        <?php
        include '../../private/intranet/admin-settings/header.php';
        top("Benutzer");
        ?>
        <div class="settings">
            <?php
            include '../../private/intranet/admin-settings/nav-user_all.php';
            ?>
            <!--
                In nav choose:
                - select user
                - add user
                - group
            -->
            <div class="middle">

            
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Benutzername</th>
                            <th>Vorname</th>
                            <th>Nachname</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $accounts = "SELECT id, username, lastname, firstname FROM accounts ORDER BY firstname";
                    $accounts = $con_new->query($accounts);
                    while ($account = $accounts->fetch_assoc()) {
                        echo '
                        <tr onclick="window.location.href=`/admin-settings/user/info?id='.$account["id"].'`">
                            <td>'.$account["id"].'</td>
                            <td>'.$account["username"].'</td>
                            <td>'.$account["firstname"].'</td>
                            <td>'.$account["lastname"].'</td>
                        </tr>
                        ';
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
include("../../private/intranet/assets/scripts-bottom.php")
?>

</html>