<?php
include '../private/database/public.php';
include '../private/config.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/index.css">
    
    <?php
    include '../private/favicon/main.php';
    ?>
    
</head>

<?php
include '../private/web/assets/nav.php';
?>

<body>
    <div class="content">

        <div class="events">
            <h2>Veranstalltungen</h2>

            <div class="elements">
                <?php
                $events_max = 5;
                $events_counter = 1;

                $weekShortName = array("So", "Mo", "Di", "Mi", "Do", "Fr", "Sa");
                $monthName = array("Jänner", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember");

                $event_elements = array();

                $events = $con_public->query("
                                                SELECT DISTINCT events.* 
                                                FROM `event` AS events JOIN `event_calendar` AS calendar ON calendar.event_id = events.id 
                                                WHERE visibility = 'all' AND events.id IN (SELECT event_id FROM event_calendar WHERE event_calendar.`start` >= NOW()) 
                                                ORDER BY calendar.start <= CURRENT_DATE,
                                                ABS(DATEDIFF(calendar.start, CURRENT_DATE))
                                                LIMIT 4");

                while ($event = $events->fetch_assoc()) {
                    if($events_counter <= $events_max) {
                        $tmp_eventID = $event["id"];

                        $eventCalendar = $con_public->query("SELECT * FROM `event_calendar` WHERE event_id = '$tmp_eventID' AND `start` >= NOW()")->fetch_assoc();

                        $event_img_root_path = $_SERVER["DOCUMENT_ROOT"].'/../cdn/event/image/img-t_'.substr(md5($event["id"]), 5).'-512.jpg';
                        if(file_exists($event_img_root_path)) {
                            $event_img_path = "https://".$domain["cdn"].'/event/image/img-t_'.substr(md5($event["id"]), 5).'-512.jpg';
                        } else {
                            $event_img_path = "https://".$domain["cdn"].'/event/placeholder/image.png';
                        }
                        
                        array_push($event_elements, array(
                            'date' => strtotime($eventCalendar["start"]),
                            'code' => '
                            <div onclick="window.location.href=`/events/view?id='.$event['id'].'`" class="event">
                                <div class="text">
                                    <h3>'.$event["title"].'</h3>
                                    <p>'.
                                        $weekShortName[date("w", strtotime($eventCalendar["start"]))]
                                        .', '.
                                        date("d", strtotime($eventCalendar["start"]))
                                        .' '.
                                        $monthName[date("m", strtotime($eventCalendar["start"])) - 1]
                                    .'</p>
                                </div>
                                <div class="e-background">
                                    <img src="'.$event_img_path.'">
                                </div>
                            </div>
                            ')
                        );
                    }
                    $events_counter++;
                }
                $key_values = array_column($event_elements, 'date'); 
                array_multisort($key_values, SORT_ASC, $event_elements);

                for ($i=0; $i < count($event_elements); $i++) { 
                    echo($event_elements[$i]["code"]);
                }
                if($events_counter <= 4) {
                    for ($i=$events_counter; $i < $events_max; $i++) { 
                        echo '
                        <div class="event placeholder" id="event' . $events_counter . '">
                            <div class="e-background">
                            </div>
                        </div>
                        ';
                    }
                }
                
                ?>
            </div>
            

        </div>

        <div class="news">
            <h2>Neuigkeiten</h2>
            <div class="elements">
                <div class="new">
                    <h3>Text</h3>
                </div>
                <div class="new">
                    <h3>Text</h3>
                </div>
                <div class="new">
                    <h3>Text</h3>
                </div>
            </div>
        </div>
    </div>
</body>

<?php
include '../private/web/assets/footer.php';
?>

</html>