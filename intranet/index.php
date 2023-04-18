<?php
//include auth_session.php file on all user panel pages
include("../private/session/auth_session.php");
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<?php
//include navigation bar
include("../private/intranet/assets/nav.php")
?>
<body class="main" id="main">

    <section class="title">
        <h4>Herzlich Willkommen <?php echo ($dbSESSION['firstname']." ".$dbSESSION['lastname']); ?>!</h4>
    </section>
    
</body>

<?php
//include scripts for bottom
include("../private/intranet/assets/scripts-bottom.php")
?>

</html>