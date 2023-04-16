<?php
//include "favicon.html";
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");
?>

<header>
    <link rel="stylesheet" href="/css/style/nav.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    
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

        <!--Admin Settings-->
        <?php
        if (in_array("admin", $dbSESSION_perm)) {
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