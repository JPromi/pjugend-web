<?php
//get database login
require($_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php');

//get sesstion from db
$session_hash = $_COOKIE["SESSION_ID"];
$dbSESSION = $con_new->query("SELECT * FROM `session` WHERE `cookie_hash`='$session_hash'");
$dbSESSION = $dbSESSION->fetch_assoc();


//get all perms from user
$userPermissions = $con_new->query("SELECT permission, permission_group FROM accounts WHERE `id`='".$dbSESSION["user_id"]."'");
$userPermissions = $userPermissions->fetch_assoc();
$userPermissionsGroup = explode(";", $userPermissions["permission_group"]);
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


//select permission group from table
foreach ($userPermissionsGroup as $permGroupID) {

    //select permission from permission group
    $permGroup = $con_new->query("SELECT * FROM permissions_group WHERE `id`='$permGroupID'");
    $permGroup = $permGroup->fetch_assoc();
    $permGroup = explode(";", $permGroup["permission_ids"]);

    foreach ($permGroup as $permID) {
        //check if array element is empty
        if (!($permID == "")) {
            //select name from permission table
            $getPermDB = $con_new->query("SELECT * FROM `permissions` WHERE `id`='$permID'");
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
}

//remove double enties
$dbSESSION_perm = array_unique($dbSESSION_perm);
?>