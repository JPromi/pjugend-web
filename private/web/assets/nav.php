<?php
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/get_session.php");
?>

<link rel="stylesheet" href="/css/style/nav.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
<header>
    
    <nav id="nav">
        <div class="mobile" onclick="nav()">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
        
        <div class="nav">
            <div class="left">
                <a href="/">
                    <img src="https://<?php echo($domain["cdn"]);?>/logo/pjugend/p_jugend-blue.svg">
                </a>
                <a href="/events">Veranstaltungen</a>
                <a href="/gallery">Galerie</a>
                <a href="/socials">Social Media</a>
                <a href="/about">Über uns</a>
            </div>
            <div class="right">
                <?php
                // intranet
                if(isset($dbSESSION)) {

                    //profile picture checker
                    $img_profile_root = $_SERVER["DOCUMENT_ROOT"]."/../cdn/profile/picture/im_p-".md5($dbSESSION["user_id"]).".jpg";

                    if(file_exists($img_profile_root)) {
                        $img_profile_path = "https://".$domain["cdn"]."/profile/picture/im_p-".md5($dbSESSION["user_id"]).".jpg";
                    } else {
                        $img_profile_path = "https://".$domain["cdn"]."/profile/placeholder/picture.jpg";
                    }
                    ?>
                    <div class="profile" onclick="menu()">
                        <img src="<?php echo($img_profile_path);?>">
                    </div>

                    
                    <?php
                }
                ?>
            </div>
        </div>
        
    </nav>

    <?php
            // intranet
            if(isset($dbSESSION)) {
                ?>
                <div class="accountmenu hidden" id="accountMenu">
                    <div class="info">
                        <img src="<?php echo($img_profile_path);?>">
                        <h2><?php echo($dbSESSION["username"]) ?></h2>
                    </div>
                    <div class="links">

                        <a href="https://<?php echo($domain["intranet"]) ?>/">
                            <span class="material-symbols-outlined">
                            apps
                            </span>
                            Intranet
                        </a>

                        <a href="https://<?php echo($domain["intranet"]) ?>/notes">
                            <span class="material-symbols-outlined">
                            note
                            </span>
                            Notizen
                        </a>

                        <a href="https://<?php echo($domain["intranet"]) ?>/settings">
                            <span class="material-symbols-outlined">
                            settings
                            </span>
                            Einstellungen
                        </a>
                    </div>
                    <a href="https://<?php echo($domain["auth"]) ?>/logout?direct=public" class="logout">
                        <span class="material-symbols-outlined">
                        logout
                        </span>
                        Logout
                    </a>
                </div>

                
                <?php
            }
            ?>
</header>
<script src="/js/nav.js"></script>
<script src="/js/account.js"></script>