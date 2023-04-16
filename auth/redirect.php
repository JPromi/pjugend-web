<?php
include("../private/session/auth_session.php");
include("../private/config.php");

?>

<?php
    switch ($_GET["direct"]) {
        case 'public':
            $link = $domain["web"];
            break;
        
        case 'int':
            $link = $domain["intranet"];
            break;
            

        default:
            $link = $domain["intranet"];
            break;
    }

    header("Location: https://".$link);
?>