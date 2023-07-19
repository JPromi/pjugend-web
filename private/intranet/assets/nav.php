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
                <!--<span class="material-symbols-outlined icon">workspace_premium</span>-->
                <svg id="Ebene_1" data-name="Ebene 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000">
                <defs>
                    <style>
                    .cls-1 {
                        fill: #fff;
                    }
                    </style>
                </defs>
                <g id="g18">
                    <path id="path118" class="cls-1" d="m839.75,411.1s211.55-236.94-141.91-277.73c0,0,363.93,10.84,149.28,283.1l-7.37-5.37"/>
                    <path id="path120" class="cls-1" d="m895.41,315.87s-37.93-1.28-61.48-50.49c0,0,60.22.02,67.81,19.11,7.49,19.08-6.33,31.38-6.33,31.38"/>
                    <path id="path122" class="cls-1" d="m898.94,244.58s-28.84,4.2-53.39-29.8c0,0,45.57-8.2,53.86,5.21,8.31,13.42-.47,24.58-.47,24.58"/>
                    <path id="path124" class="cls-1" d="m860.98,186.01s-13.69,11.94-37.92,2.46c0,0,21.02-19.68,29.87-15.44,8.83,4.18,8.06,12.98,8.06,12.98"/>
                    <path id="path126" class="cls-1" d="m904.63,273.81s29.59-23.71,79.5-1.74c0,0-45.69,39.23-63.83,29.64-18.12-9.57-15.67-27.91-15.67-27.91"/>
                    <path id="path128" class="cls-1" d="m886.38,198.57s19.19-21.98,59.96-12.23c0,0-29.26,35.91-44.26,31.17-15.04-4.77-15.7-18.95-15.7-18.95"/>
                    <path id="path130" class="cls-1" d="m847.55,180.85s-6.41-16.96,10.86-36.46c0,0,11.17,26.53,4.2,33.41-7.06,6.82-15.05,3.05-15.05,3.05"/>
                    <path id="path132" class="cls-1" d="m622.88,362.64s67.93-111.71,144.59-74.44c76.63,37.23,56.89,41.59,81.01,48.15,24.09,6.59,37.2,4.39,37.2,4.39,0,0-56.92,37.27-72.2,48.18-15.37,10.98-94.21,199.31-129.26,251.91-35.08,52.56-199.33,113.88-302.22,144.56-102.96,30.65-146.79,67.87-190.61,124.82-43.79,56.94-52.52,89.81-52.52,89.81,0,0,28.49-188.41,232.2-254.07,203.64-65.72,271.53-59.14,376.64-328.55,0,0,4.4-24.07,37.26-48.19l46.53-16.54s-63.79-66.62-208.61,9.98"/>
                    <path id="path134" class="cls-1" d="m560.02,358.75s-98.56-62.61-149.61-133.88c-51.03-71.24-174.54-66.14-216.75-93.49C151.43,104.07,18.54,0,18.54,0c0,0,108.49,160.93,205.27,183.58,96.84,22.61,150.11,46.89,174.22,73.37,24.13,26.5,111.54,95.83,161.99,101.81"/>
                    <path id="path136" class="cls-1" d="m209.76,251.77c4.69.25-8.8-2.93-13.05-4.45C13.86,181.92,23.56,47.34,23.56,47.34c0,0-65.59,191.26,186.2,204.43"/>
                    <path id="path138" class="cls-1" d="m364.19,373.79c5.89-1.21-11.86-.79-17.62-1.29C98.6,350.52,67.15,180.51,67.15,180.51c0,0-19.45,258.38,297.04,193.27"/>
                    <path id="path140" class="cls-1" d="m489.63,521.09c6.23-1.74-12.92.05-19.17-.06-269.65-4.9-316.59-186.26-316.59-186.26,0,0-1.36,280.77,335.75,186.31"/>
                </g>
                </svg>
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