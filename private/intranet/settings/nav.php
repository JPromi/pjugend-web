<link rel="stylesheet" href="/settings/css/nav.css">
<div class="left">
    <h1><?php echo($dbSESSION["username"]);?></h1>
    <div class="links">
        <a href="/settings/account">
                <span class="material-symbols-outlined">
                person
                </span>
                Konto
        </a>

        <?php
        if (in_array("jugendteam", $dbSESSION_group)) {
            echo '
            <a href="/settings/team">
                <span class="material-symbols-outlined">
                groups
                </span>
                Team Eintrag
            </a>
            ';
        }
        ?>
    </div>
</div>