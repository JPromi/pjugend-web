<link rel="stylesheet" href="/admin-settings/css/nav.css">
<div class="left">
    <div class="links">
        <?php
        //admin settings
        if(in_array("admin", $dbSESSION_perm)) {
        ?>
            <a href="/admin-settings/user">
                <span class="material-symbols-outlined">
                person
                </span>
                Benutzer
            </a>
        <?php
        }
        ?>

        <?php
        //jt admin settings
        if(in_array("jugendteam_admin", $dbSESSION_perm)) {
        ?>
            <a href="/admin-settings/social-media">
                <span class="material-symbols-outlined">
                public
                </span>
                Social Media
            </a>

            <a href="/admin-settings/team">
                <span class="material-symbols-outlined">
                groups
                </span>
                Team
            </a>
        <?php
        }
        ?>
    </div>
</div>