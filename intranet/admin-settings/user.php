<?php
//include auth_session.php file on all user panel pages
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/auth_session.php");
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
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
    <link rel="stylesheet" href="/admin-settings/css/style.css">
    <link rel="stylesheet" href="/admin-settings/css/user.css">
                
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
        top("Benutzer");
        ?>
        <div class="settings">
            <?php
            include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/admin-settings/nav-user_all.php';
            ?>
            <!--
                In nav choose:
                - select user
                - add user
                - group
            -->
            <div class="middle">

                    <div class="search">
                        <input type="text" placeholder="Suche" id="filterTable" onkeyup="search('usrData')">

                        <span class="material-symbols-outlined" id="searchRemove" onclick="clearInput('filterTable')">
                        close
                        </span>
                    </div>

            
                <table>
                    <thead>
                        <tr>
                            <th>
                                <div class="ct">
                                    ID
                                    <div class="arrow"></div>
                                </div>
                            </th>
                            <th>
                                <div class="ct">
                                    Benutzername
                                    <div class="arrow"></div>
                                </div>
                            </th>
                            <th>
                                <div class="ct">
                                    Vorname
                                    <div class="arrow"></div>
                                </div>
                            </th>
                            <th>
                                <div class="ct">
                                    Nachname
                                    <div class="arrow"></div>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="usrData">
                        <tr><!--placeholder--></tr>
                    <?php
                    $accounts = "SELECT id, username, lastname, firstname FROM accounts ORDER BY firstname";
                    $accounts = $con_new->query($accounts);
                    while ($account = $accounts->fetch_assoc()) {
                        echo '
                        <tr class="entry" onclick="window.location.href=`/admin-settings/user/info?id='.$account["id"].'`">
                            <td>'.$account["id"].'</td>
                            <td class="name">'.$account["username"].'</td>
                            <td class="name">'.$account["firstname"].'</td>
                            <td class="name">'.$account["lastname"].'</td>
                        </tr>
                        ';
                    }
                    ?>

                    </tbody>
                </table>
            </div>
            
        </div>
        <script src="/admin-settings/user/js/order.js"></script>
        <script src="/admin-settings/user/js/search.js"></script>
    </div>

</body>

<?php
//include scripts for bottom
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/scripts-bottom.php")
?>

</html>