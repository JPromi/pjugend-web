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

            while ($gallery = $galleries->fetch_assoc()) {
                if($gallery["owner"] == $dbSESSION["user_id"] || in_array("jugendteam_admin", $dbSESSION_perm) || in_array($dbSESSION["user_id"], explode(";", $gallery["user_edit"]))) {
                    //get thumbnail
                    $pathGallery = $_SERVER["DOCUMENT_ROOT"].'/../cdn/gallery/'.$gallery["hash_id"].'/thumbnail/';
                    $galleryFolder = scandir ($pathGallery);

                    if($galleryFolder[2] != "") {
                        $thumbnail = 'https://'.$domain["cdn"].'/gallery/'.$gallery["hash_id"].'/thumbnail/'.$galleryFolder[2];
                    } else {
                        $thumbnail = 'https://'.$domain["cdn"].'/gallery/placeholder/gallery.jpg';
                    }
                    echo '
                    <a href="/gallery/view?id='.$gallery["hash_id"].'">
                        <img src="'.$thumbnail.'">
                        <p>'.$gallery["title"].'</p>
                    </a>
                    ';
                }
                
            }
            ?>
        </div>
    </div>

</div>