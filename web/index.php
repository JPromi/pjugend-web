<?php
include '../private/config.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/index.css">
    
    <?php
    include '../private/favicon/main.php';
    ?>
    
</head>

<?php
include '../private/web/assets/nav.php';
?>

<body>
    <div class="content">

        <div class="top">
            <!--slideshow-->
            <div class="slideshow">
                <div class="images" id="slideshow">
                    <a href="#img1" id="img1">
                        <img src="https://picsum.photos/800/500" alt="">
                    </a>
                </div>
                <div class="preview">

                </div>
            </div>
            <script src="js/slideshow.js"></script>

        </div>
    </div>
</body>

<?php
include '../private/web/assets/footer.php';
?>

</html>