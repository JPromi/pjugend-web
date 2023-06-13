<?php
//include "favicon.html";
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");
?>

<header>
    <link rel="stylesheet" href="/css/style/nav.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    
    <nav id="sidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn navOpen menubtn" onclick="toggleNav()" id="singlenav">
            <span class="material-symbols-outlined">
                menu
            </span>
        </a>
        <!--(&times;)-->
        

        <!--Home-->
        <a href="/" id="singlenav" class="navOpen">
            <span class="material-symbols-outlined icon">home</span>
            <p id="navtext">Home</p>
        </a>

        <!--Notes-->
        <a href="/notes" id="singlenav" class="navOpen">
            <span class="material-symbols-outlined icon">note</span>
            <p id="navtext">Notizen</p>
        </a>

        <!--Firmung-->
        <?php
        if (in_array("firmbegleiter", $dbSESSION_perm) || in_array("firmung_admin", $dbSESSION_perm)) {
            echo('
            <a href="/firmung" id="singlenav" class="navOpen">
                <span class="material-symbols-outlined icon">workspace_premium</span>
                <p id="navtext">Firmung</p>
                </span>
            </a>
            ');
        };
        ?>

        <!--Fileshare-->
        <?php
        if (in_array("fileshare", $dbSESSION_perm)) {
            echo('
            <a href="/fileshare" id="singlenav" class="navOpen">
                <span class="material-symbols-outlined icon">upload</span>
                <p id="navtext">Fileshare</p>
                </span>
            </a>
            ');
        };
        ?>

        <!--Chat-->
        <?php
        if (in_array("chat", $dbSESSION_perm)) {
            echo('
            <a href="/chat" id="singlenav" class="navOpen">
                <span class="material-symbols-outlined icon">chat</span>
                <p id="navtext">Fileshare</p>
                </span>
            </a>
            ');
        };
        ?>

        <!--Gallery-->
        <?php
        if (in_array("gallery", $dbSESSION_perm) || in_array("jugendteam_admin", $dbSESSION_perm)) {
            echo('
            <a href="/gallery" id="singlenav" class="navOpen">
                <span class="material-symbols-outlined icon">gallery_thumbnail</span>
                <p id="navtext">Gallery</p>
            </a>
            ');
        };
        ?>

        <!--News-->
        <?php
        if (in_array("news", $dbSESSION_perm) || in_array("jugendteam_admin", $dbSESSION_perm)) {
            echo('
            <a href="/news" id="singlenav" class="navOpen">
                <span class="material-symbols-outlined icon">newspaper</span>
                <p id="navtext">News</p>
            </a>
            ');
        };
        ?>

        <!--Events-->
        <?php
        if (in_array("event", $dbSESSION_perm) || in_array("jugendteam_admin", $dbSESSION_perm)) {
            echo('
            <a href="/event" id="singlenav" class="navOpen">
                <span class="material-symbols-outlined icon">event</span>
                <p id="navtext">Veranstaltungen</p>
            </a>
            ');
        };
        ?>

        <!--Form-->
        <?php
        if (in_array("form", $dbSESSION_perm) || in_array("jugendteam_admin", $dbSESSION_perm)) {
            echo('
            <a href="/form" id="singlenav" class="navOpen">
                <span class="material-symbols-outlined icon">assignment</span>
                <p id="navtext">Formular</p>
            </a>
            ');
        };
        ?>

        <!--Admin Settings-->
        <?php
        if (in_array("admin", $dbSESSION_perm) || in_array("jugendteam_admin", $dbSESSION_perm) || in_array("firmung_admin", $dbSESSION_perm)) {
            echo('
            <a href="/admin-settings" id="singlenav" class="navOpen">
                <span class="material-symbols-outlined icon">admin_panel_settings</span>
                <p id="navtext">Admin Einstellungen</p>
            </a>
            ');
        };
        ?>

        <!--Settings-->
        <a href="/settings" id="singlenav" class="navOpen">
            <span class="material-symbols-outlined icon">settings</span>
            <p id="navtext">Einstellungen</p>
        </a>

        <!--Logout-->
        <div class="bottom">
            <a href="https://<?php echo($domain["auth"]); ?>/logout" class="logout navOpen" id="singlenav">
                <span class="material-symbols-outlined icon">logout</span>
                <p id="navtext">Logout</p>
            </a>
        </div>
        
    </nav>
    <script src="/js/nav/nav.js"></script>
</header>