<?php
//get database login
require($_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php');

//get sesstion from db
$session_hash = $_COOKIE["SESSION_ID"];
$dbSESSION = $con_new->query("SELECT * FROM `session` WHERE `cookie_hash`='$session_hash'");
$dbSESSION = $dbSESSION->fetch_assoc();


//get all perms from user
$userPermissions = $con_new->query("SELECT permission FROM accounts WHERE `id`='".$dbSESSION["user_id"]."'");
$userPermissions = $userPermissions->fetch_assoc();
$userPermissions = explode(";", $userPermissions["permission"]);

//get all perms
$allPermDB = $con_new->query("SELECT `id` FROM `permissions`");

//set user permissions
$dbSESSION_perm = array();
foreach ($userPermissions as $perms) {
    $explodePermID = explode(";", $perms);



    //select permission from table
    foreach ($explodePermID as $permID) {

        //check if array element is empty
        if (!($permID == "")) {
            //select name from permission table
            $getPermDB = $con_new->query("SELECT `perm` FROM `permissions` WHERE `id`='$permID'");
            $getPermDB = $getPermDB->fetch_assoc();

            //set perm
            array_push($dbSESSION_perm, $getPermDB['perm']);
        }
    }
}

//check if user has admin permission
if (in_array("admin", $dbSESSION_perm)) {
    //forech every permission
    foreach ($allPermDB as $perm) {
        $permID = $perm["id"];

        //get database row
        $getPermDB = $con_new->query("SELECT `perm` FROM `permissions` WHERE `id`='$permID'");
        $getPermDB = $getPermDB->fetch_assoc();

        //set perm
        array_push($dbSESSION_perm, $getPermDB['perm']);
    }

    //remove double entries
    $dbSESSION_perm = array_unique($dbSESSION_perm);
}

?>