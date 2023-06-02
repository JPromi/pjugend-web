<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/session/get_session.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/firmung.php';

//access
if(!in_array("firmung_admin", $dbSESSION_perm) && !in_array("firmbegleiter", $dbSESSION_perm)) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array("access" => "denny"));
    exit;
}


//check get
$firmlingID = checkInput($_GET["firmling_id"]);
$firmungYEAR = checkInput($_GET["year"]);

//selec firmung
$firmung = "SELECT * FROM firmung WHERE `year` = $firmungYEAR";
$firmung = $con_firmung->query($firmung);
$firmung = $firmung->fetch_assoc();

//set firmungID
$firmungID = checkInput($firmung["id"]);

//select Firmung
$firmling = "SELECT * FROM firmling WHERE id = $firmlingID AND firmung_id = $firmungID";
$firmling = $con_firmung->query($firmling);
$firmling = $firmling->fetch_assoc();

//error if firmling couldnt be found
if(!isset($firmling)) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array("error" => "entry could not be found"));
    exit;
}

$imagePath = $_SERVER["DOCUMENT_ROOT"].'/firmung/firmlinge/_picture/'.$firmung["year"].'-'.$firmling["id"].'.jpg';

if(file_exists($imagePath)) {
    //header('Content-Description: File Transfer');
    //header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    //header('Content-Length: ' . filesize($imagePath));

    header('Content-Type: image/jpeg');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Pragma: public');
    ob_clean();
    flush();

    $image = imagecreatefromjpeg($imagePath);
    imagejpeg($image);
    imagedestroy($image);

    //readfile($imagePath);
    exit;
} else {
    header('Content-Type: image/jpeg');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Pragma: public');
    ob_clean();
    flush();

    $image = imagecreatefromjpeg($_SERVER["DOCUMENT_ROOT"].'/firmung/firmlinge/_picture/default.jpg');
    imagejpeg($image);
    imagedestroy($image);
    exit;
}

?>


<?php
function checkInput($input) {
    global $con_firmung;
    $input = htmlspecialchars($input);
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($con_firmung, $input);

    if(!(empty($input))) {
        $input = "'".$input."'";
    } else {
        $input = "NULL";
    }

    return $input;
}
?>