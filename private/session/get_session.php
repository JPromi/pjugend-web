<?php
//get database login
require($_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php');

//get sesstion from db
if(isset($_REQUEST["SESSION_ID"])) {
    $session_hash = $_REQUEST["SESSION_ID"];
} else {
    $session_hash = $_COOKIE["SESSION_ID"];
}

$dbSESSION = $con_new->query("SELECT * FROM `session` WHERE `cookie_hash`='$session_hash'");
$dbSESSION = $dbSESSION->fetch_assoc();


//get all perms from user
$userPermissions = $con->query("SELECT permission_id FROM accounts_permission WHERE `user_id`='".$dbSESSION["user_id"]."'")->fetch_all();

//get all perms
$allPermDB = $con->query("SELECT `id` FROM `permissions`");

//set user permissions
$dbSESSION_perm = array();
$dbSESSION_group = array();
foreach ($userPermissions as $permID) {
    //check if array element is empty
    if (!($permID == "")) {
        //select name from permission table
        $getPermDB = $con->query("SELECT `perm` FROM `permissions` WHERE `id`='".$permID[0]."'")->fetch_assoc();

        //set perm
        array_push($dbSESSION_perm, $getPermDB['perm']);
    }
}


//select permission group from table
$userPermissionsGroup = $con->query("SELECT group_id FROM accounts_permission_group WHERE `user_id`='".$dbSESSION["user_id"]."'")->fetch_all();

foreach ($userPermissionsGroup as $permGroupID) {

    //select permission from permission group
    $permGroup = $con->query("SELECT * FROM permissions_group WHERE `id`='".$permGroupID[0]."'")->fetch_assoc();
    array_push($dbSESSION_group, $permGroup["perm"]);

    $getPermDB = $con->query("SELECT perm FROM `permissions` WHERE id IN (SELECT permission_id FROM permissions_group_index WHERE group_id = '".$permGroupID[0]."')")->fetch_all();
    for ($i=0; $i < count($getPermDB); $i++) { 
        array_push($dbSESSION_perm, $getPermDB[$i][0]);
    }

}

//check if user has admin permission
if (in_array("admin", $dbSESSION_perm)) {
    $getAllPermDB = $con->query("SELECT perm FROM `permissions`")->fetch_all();

    for ($i=0; $i < count($getAllPermDB); $i++) { 
        array_push($dbSESSION_perm, $getAllPermDB[$i][0]);
    }
    
}
//remove double enties
$dbSESSION_perm = array_unique($dbSESSION_perm);
?>