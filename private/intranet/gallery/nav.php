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
            $user_id = $dbSESSION["user_id"];
            $galleries = "SELECT * FROM gallery WHERE owner = '$user_id' OR id IN (SELECT gallery_id FROM gallery_permission WHERE user_id = '$user_id')";
            $galleries = $con_public->query($galleries);

            while ($gallery = $galleries->fetch_assoc()) {
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
            ?>
        </div>
    </div>

</div>