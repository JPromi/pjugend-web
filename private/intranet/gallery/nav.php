<link rel="stylesheet" href="/gallery/css/nav.css">
<div class="left">

    <div class="links">

        <a href="/gallery/add">
            <span class="material-symbols-outlined">
            add
            </span>
            Erstellen
        </a>

        <div class="overview">
            <?php
            $galleries = "SELECT * FROM gallery";
            $galleries = $con_public->query($galleries);

            while ($gallery) {
                # code...
            }
            ?>
        </div>
    </div>

</div>