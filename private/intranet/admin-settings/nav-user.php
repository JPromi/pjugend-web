<link rel="stylesheet" href="/admin-settings/css/nav.css">
<div class="left">
    <div class="back">
        <a href="/admin-settings/user">
            <span class="material-symbols-outlined">
            arrow_back
            </span>
            Zurück
        </a>
    </div>
    <h1><?php echo($account["username"]);?></h1>
    <div class="links">
        <a href="/admin-settings/user/info?id=<?php echo($_GET["id"]); ?>">
            <span class="material-symbols-outlined">
            info
            </span>
            Informationen
        </a>
        <a href="/admin-settings/user/edit?id=<?php echo($_GET["id"]); ?>">
            <span class="material-symbols-outlined">
            edit
            </span>
            Bearbeiten
        </a>
        <a href="/admin-settings/user/login-log?id=<?php echo($_GET["id"]); ?>">
            <span class="material-symbols-outlined">
            web_stories
            </span>
            Login Log
        </a>


    </div>
</div>