<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/session/get_session.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/public.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/functions/input.php';

//set content type to json
header('Content-Type: application/json; charset=utf-8');
# header('Access-Control-Allow-Origin: https://'.$domain["intranet"]);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE');
header('Access-Control-Allow-Credentials: true');

//check permissions
if(!in_array('event', $dbSESSION_perm) && !in_array('jugendteam_admin', $dbSESSION_perm)) {
    http_response_code(403);
    exit();
}

if(isset($_REQUEST["title"])) {
    $tmp_title = checkInput($_REQUEST["title"]);
    $tmp_description = checkInput($_REQUEST["description"]);
    $tmp_location = checkInput($_REQUEST["location"]);
    $tmp_age_from = checkInput($_REQUEST["age_from"]);
    $tmp_age_to = checkInput($_REQUEST["age_to"]);
    $tmp_price = checkInput($_REQUEST["price"]);

    $tmp_user_id = checkInput($dbSESSION["user_id"]);

    $con_public->query("INSERT INTO event (title, description, age_from, age_to, location, price) VALUES ($tmp_title, $tmp_description, $tmp_age_from, $tmp_age_to, $tmp_location, $tmp_price)");
    if(!$con_public->error) {
        $tmp_event_id = $con_public->insert_id;
        //organizer, links, image

        //calendar
        if(isset($_POST["date_id"])) {
            $tmp_date = array();
            for ($i=0; $i < count($_POST["date_id"]); $i++) {
                if($_POST["date_start"][$i] != '' || $_POST["date_end"][$i] != '') {
                    array_push($tmp_date, '('.checkInput($tmp_event_id).', '.inputCheckDate($_POST["date_start"][$i]).', '.inputCheckDate($_POST["date_end"][$i]).')');
                }
            }
            $tmp_date_SQL = implode(', ', $tmp_date);
            $con_public->query("INSERT INTO event_calendar (event_id, start, end) VALUES $tmp_date_SQL");
        }
        

        //links
        if(isset($_POST["link"])) {
            $tmp_link = array();
            for ($i=0; $i < count($_POST["link"]); $i++) {
                if($_POST["link"][$i] != '') {
                    array_push($tmp_link, '('.checkInput($tmp_event_id).', '.checkInput($_POST["link_title"][$i]).', '.checkInput($_POST["link"][$i]).')');
                }
            }
            $tmp_link_SQL = implode(', ', $tmp_link);
            $con_public->query("INSERT INTO event_link (event_id, title, link) VALUES $tmp_link_SQL");
        }

        //organizer
        $tmp_organizer = array();
        array_push($tmp_organizer, '('.checkInput($tmp_event_id).', '.$tmp_user_id.')');

        if(isset($_POST["organizer_id"])) {
            for ($i=0; $i < count($_POST["organizer_id"]); $i++) {
                if($_POST["organizer_id"][$i] != '') {
                    array_push($tmp_organizer, '('.checkInput($tmp_event_id).', '.checkInput($_POST["organizer_id"][$i]).')');
                }
            }
        }
        $tmp_organizer_SQL = implode(', ', $tmp_organizer);
        $tmp_organizer_SQL = array_unique($tmp_organizer_SQL);
        $con_public->query("INSERT INTO event_organizer (event_id, user_id) VALUES $tmp_organizer_SQL");

        echo json_encode(array(
            'status' => 'ok',
            'id' => $tmp_event_id,
        ));
    } else {
        echo json_encode(array(
            'status' => 'error',
            'error' => 'something went wrong',
        ));
    }

} else {
    echo json_encode(array(
        'status' => 'error',
        'error' => 'gallery ID or image not set',
    ));
}
?>