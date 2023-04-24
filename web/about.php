<?php
include '../private/config.php';
include '../private/database/public.php';
include '../private/database/int.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Über uns - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/about.css">
    
    <?php
    include '../private/favicon/main.php';
    ?>
    
</head>

<?php
include '../private/web/assets/nav.php';
?>

<body>
    <div class="team">

        <h2>Jugendteam</h2>

        <div class="persons">
            <?php
            $team = "SELECT * FROM `team` WHERE NOT `disabled` = '1'";
            $team = $con_public_new->query($team);

            while ($person = $team->fetch_assoc()) {
                $profile_image_root_path = "../cdn/profile/team/picture/im_p-".substr(md5($person["user_id"]), 0, 10).$person["user_id"].'-512.jpg';
                if(file_exists($profile_image_root_path)) {
                    $profile_image_path = "https://".$domain["cdn"].'/profile/team/picture/im_p-'.substr(md5($person["user_id"]), 0, 10).$person["user_id"].'-512.jpg';
                } else {
                    $profile_image_path = "https://".$domain["cdn"].'/profile/placeholder/picture.jpg';
                }
                ?>
                <div class="single">
                <img src="<?php echo($profile_image_path); ?>">
                <h4><?php echo($person["name"]); ?></h4>
                <p class="focus sub"><?php echo($person["focus"]); ?></p>
                <?php
                if($person["show_age"] == "1") {
                    $personInfo = "SELECT birthdate FROM `accounts` WHERE id = '".$person["user_id"]."'";
                    $personInfo = $con_new->query($personInfo);
                    $personInfo = $personInfo->fetch_assoc();

                    $age = date_diff(date_create(date("Y-m-d")), date_create($personInfo["birthdate"]))->y;
                    echo '
                    <p class="age sub">Alter: '.$age.'</p>
                    ';
                }
                ?>
                
                <p class="description"><?php echo(str_replace("\n", "<br>", $person["description"])); ?></p>

                <div class="btn">
                    <?php
                    if(!(empty($person["email"]))) {
                        echo '
                        <a href="mailto:'.$person["email"].'">
                            <span class="material-symbols-outlined">
                            mail
                            </span>
                        </a>
                        ';
                    }
                    ?>
                </div>

            </div>
                <?php
            }
            ?>
        </div>

    </div>
</body>

<?php
include '../private/web/assets/footer.php';
?>

</html>