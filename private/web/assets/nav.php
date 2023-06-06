<?php
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/get_session.php");
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/firmung/get_session.php");
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
            <ul class="left">
                <li><a href="/">
                    <img src="https://<?php echo($domain["cdn"]);?>/logo/pjugend/p_jugend-white.svg">
                </a></li>
                <li><a href="/events">Veranstaltungen</a></li>
                <li><a href="/gallery">Galerie</a></li>
                <li><a href="/socials">Social Media</a></li>
                <li>
                    <a>Sakramente</a>
                    <ul>
                        <li><a href="/firmung">Firmung</a></li>
                    </ul>
                </li>
                <li><a href="/about">Über uns</a></li>
            </ul>
            <div class="right">
                <?php
                // intranet
                if(isset($dbSESSION)) {

                    //profile picture checker
                    $img_profile_root = $_SERVER["DOCUMENT_ROOT"]."/../cdn/profile/picture/im_p-".substr(md5($dbSESSION["user_id"]), 0, 10).$dbSESSION["user_id"].'-256.jpg';

                    if(file_exists($img_profile_root)) {
                        $img_profile_path = "https://".$domain["cdn"]."/profile/picture/im_p-".substr(md5($dbSESSION["user_id"]), 0, 10).$dbSESSION["user_id"]."-256.jpg";
                    } else {
                        $img_profile_path = "https://".$domain["cdn"]."/profile/placeholder/picture.jpg";
                    }
                    ?>
                    <div class="profile" onclick="menu('intranet')">
                        <img src="<?php echo($img_profile_path);?>">
                    </div>

                    
                    <?php
                }
                ?>

                <?php
                // firmling
                if(isset($dbSESSION_firmling) && !isset($dbSESSION)) {
                    ?>
                    <div class="profile profile-firmling" onclick="menu('firmling')">
                        <img src="https://<?php echo($domain["cdn"]);?>/firmung/firmling/profilepicture">
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
                <div class="accountmenu hidden" id="menu-intranet">
                    <div class="info">
                        <img src="<?php echo($img_profile_path);?>">
                        <h2><?php echo($dbSESSION["username"]) ?></h2>
                    </div>
                    <div class="links">
                        <div class="block">
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
                        
                        <div class="block">
                            <a href="/firmung/firmling">
                                <span class="material-symbols-outlined">
                                workspace_premium
                                </span>
                                Firmung
                            </a>

                            <a href="/firmung/firmling/event">
                                <span class="material-symbols-outlined">
                                schedule
                                </span>
                                Firm Aktionen
                            </a>

                            <a href="/firmung/firmling/settings">
                                <span class="material-symbols-outlined">
                                settings
                                </span>
                                Firm Einstellungen
                            </a>
                        </div>
                        
                    </div>
                    <a href="https://<?php echo($domain["auth"]) ?>/logout?direct=public" class="logout">
                        <span class="material-symbols-outlined">
                        logout
                        </span>
                        Logout
                    </a>

                    
                </div>
                <span onclick="menu('intranet')" class="fullscreenback hidden" id="menuBack-intranet"></span>
                
                <?php
            }
            ?>

            <?php
            // firmling
            if(isset($dbSESSION_firmling) && !isset($dbSESSION)) {
                ?>
                <div class="accountmenu hidden" id="menu-firmling">
                    <div class="info">
                        <img src="https://<?php echo $domain["cdn"];?>/firmung/firmling/profilepicture">
                        <h2><?php echo($dbSESSION["username"]) ?></h2>
                    </div>
                    <div class="links">

                        <div class="block">
                            <a href="/firmung/firmling">
                                <span class="material-symbols-outlined">
                                workspace_premium
                                </span>
                                Firmung
                            </a>

                            <a href="/firmung/firmling">
                                <span class="material-symbols-outlined">
                                schedule
                                </span>
                                Firm Aktionen
                            </a>

                            <a href="/firmung/firmling">
                                <span class="material-symbols-outlined">
                                settings
                                </span>
                                Firm Einstellungen
                            </a>
                        </div>

                    </div>
                    <a href="https://<?php echo($domain["auth"]) ?>/logout?direct=public" class="logout">
                        <span class="material-symbols-outlined">
                        logout
                        </span>
                        Logout
                    </a>

                    
                </div>
                <span onclick="menu('firmling')" class="fullscreenback hidden" id="menuBack-firmling"></span>
                
                <?php
            }
            ?>
</header>
<script src="/js/nav.js"></script>
<script src="/js/account.js"></script>