<?php
include '../private/config.php';
include '../private/database/public.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/socials.css">
    
    <?php
    include '../private/favicon/main.php';
    ?>
    
</head>

<?php
include '../private/web/assets/nav.php';
?>

<body>
    <div class="content">
        <h2>Social Media</h2>
        <div class="links">
            <?php
            $socialmedias = "SELECT * FROM socialmedia ORDER BY index_id";
            $socialmedias = $con_public->query($socialmedias);

            while ($social = $socialmedias->fetch_assoc()) {
                echo '
                    <a class="single" href="'.$social["link"].'" target="_blank" rel="noopener noreferrer">
                        <p>'.$social["title"].'</p>
                    </a>
                ';
            }
            ?>
        </div>
    </div>
</body>

<?php
include '../private/web/assets/footer.php';
?>

</html>