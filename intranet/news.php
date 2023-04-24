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
    <title>News - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/news.css">
    
    <?php
    include '../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include("../private/intranet/assets/nav.php")
?>
<body class="main" id="main">

    
    
</body>

<?php
//include scripts for bottom
include("../private/intranet/assets/scripts-bottom.php")
?>

</html>