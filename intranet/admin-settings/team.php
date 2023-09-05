<?php
//include auth_session.php file on all user panel pages
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/auth_session.php");
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/public.php';
?>

<?php
if(!(in_array("jugendteam_admin", $dbSESSION_perm))) {
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
    <title>Team - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/admin-settings/css/style.css">
    <link rel="stylesheet" href="/admin-settings/css/team.css">
                
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
            include $_SERVER["DOCUMENT_ROOT"].'/../private/intranet/admin-settings/nav-team.php';
            ?>
            
            <div class="middle">
                <div class="team">
                    <?php
                    $teamUsers = array();

                    //get all users
                    $allUsers = "SELECT id, permission, permission_group, firstname, lastname FROM accounts ORDER BY id";
                    $allUsers = $con->query($allUsers);

                    //get goup permission

                    $teamUsers = $con->query("SELECT user_id FROM accounts_permission_group WHERE group_id IN (SELECT id FROM permissions_group WHERE perm = 'jugendteam')")->fetch_all();
                    $teamUsers = array_column($teamUsers, 0);

                    //go throught all users
                    foreach ($teamUsers as $userID) {
                        //selec team profile
                        $user_id = $userID;
                        $user = $con->query("SELECT * FROM accounts WHERE id = '$user_id'")->fetch_assoc();
                        $teamEntry = "SELECT * FROM team WHERE user_id = '$user_id'";
                        $teamEntry = $con_public->query($teamEntry);
                        $teamEntry = $teamEntry->fetch_assoc();

                        if (isset($teamEntry)) {
                            $profile_image_root_path = $_SERVER["DOCUMENT_ROOT"]."/../cdn/profile/team/picture/im_p-".substr(md5($teamEntry["user_id"]), 0, 10).$teamEntry["user_id"].'-512.jpg';
                            if(file_exists($profile_image_root_path)) {
                                $profile_image_path = "https://".$domain["cdn"].'/profile/team/picture/im_p-'.substr(md5($teamEntry["user_id"]), 0, 10).$teamEntry["user_id"].'-512.jpg';
                            } else {
                                $profile_image_path = "https://".$domain["cdn"].'/profile/placeholder/picture.jpg';
                            }
                            
                            echo '
                            <div class="single" onclick="window.location.href=`/admin-settings/team/edit?id='.$user_id.'`">
                                <img src="'.$profile_image_path.'">
                                <h4>'.$teamEntry["name"].'</h4>

                                <p class="focus sub">'.$teamEntry["focus"].'</p>
                                <p class="description">'.$teamEntry["description"].'</p>

                            </div>
                            ';
                        } else {
                            $teamEntry = "SELECT * FROM team WHERE user_id = '$user_id'";
                            echo '
                            <div class="single new" onclick="window.location.href=`/admin-settings/team/edit?id='.$user_id.'`">
                                <img src="https://'.$domain["cdn"].'/profile/placeholder/picture.jpg">
                                <h4>'.$user["firstname"].' '.$user["lastname"].'</h4>    
                            </div>
                            ';
                        }


                    }

                    //all where account doesnt exist
                    $teamEntrys = "SELECT * FROM team WHERE user_id NOT IN ('".implode("','", $teamUsers )."')";
                    $teamEntrys = $con_public->query($teamEntrys);



                    while ($teamEntry = $teamEntrys->fetch_assoc()) {

                            if (isset($teamEntry)) {
                                $profile_image_root_path = $_SERVER["DOCUMENT_ROOT"]."/../cdn/profile/team/picture/im_p-".substr(md5($teamEntry["user_id"]), 0, 10).$teamEntry["user_id"].'-512.jpg';
                                if(file_exists($profile_image_root_path)) {
                                    $profile_image_path = "https://".$domain["cdn"].'/profile/team/picture/im_p-'.substr(md5($teamEntry["user_id"]), 0, 10).$teamEntry["user_id"].'-512.jpg';
                                } else {
                                    $profile_image_path = "https://".$domain["cdn"].'/profile/placeholder/picture.jpg';
                                }
                                
                                echo '
                                <div class="single" onclick="window.location.href=`/admin-settings/team/edit?id='.$user_id.'`">
                                    <img src="'.$profile_image_path.'">
                                    <h4>'.$teamEntry["name"].'</h4>
    
                                    <p class="focus sub">'.$teamEntry["focus"].'</p>
                                    <p class="description">'.$teamEntry["description"].'</p>
    
                                </div>
                                ';

                        }
                    }
                    ?>
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