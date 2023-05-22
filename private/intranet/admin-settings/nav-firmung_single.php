<?php
$yearGET = $_GET["year"];
?>

<link rel="stylesheet" href="/admin-settings/css/nav.css">
<div class="left">
    <div class="back">
        <a href="/admin-settings/firmung">
            <span class="material-symbols-outlined">
            arrow_back
            </span>
            Zurück
        </a>
    </div>
    
    <div class="links">

        <a href="/admin-settings/firmung/view?year=<?php echo($yearGET); ?>">
            <span class="material-symbols-outlined">
            info
            </span>
            Allgemein
        </a>

        <a href="/admin-settings/firmung/event?year=<?php echo($yearGET); ?>">
            <span class="material-symbols-outlined">
            schedule
            </span>
            Aktionen
        </a>

        <a href="/admin-settings/firmung/group?year=<?php echo($yearGET); ?>">
            <span class="material-symbols-outlined">
            group
            </span>
            Gruppen
        </a>

        <a href="/admin-settings/firmung/firmbegleiter?year=<?php echo($yearGET); ?>">
            <span class="material-symbols-outlined">
            shield_person
            </span>
            Firmbegleiter
        </a>

        <a href="/admin-settings/firmung/firmlinge?year=<?php echo($yearGET); ?>">
            <span class="material-symbols-outlined">
            person
            </span>
            Firmlinge
        </a>

        <a href="/admin-settings/firmung/registration?year=<?php echo($yearGET); ?>">
            <span class="material-symbols-outlined">
            assignment
            </span>
            Registrationen
        </a>

    </div>
</div>