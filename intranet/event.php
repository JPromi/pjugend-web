<?php
//include auth_session.php file on all user panel pages
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/auth_session.php");

include($_SERVER["DOCUMENT_ROOT"]."/../private/database/int.php");
include($_SERVER["DOCUMENT_ROOT"]."/../private/database/public.php");
?>

<?php
    //handle post search request
    if(isset($_POST["submit"])) {
        $generateParameter;
        echo json_encode($_POST);
        $dateSearch = array("date_from", "date_to");
        $allowedSearch = array("title", "age", "date_from", "date_to");
        foreach ($allowedSearch as $parameter) {
            if(!($_POST[$parameter]) == "") {
                if(in_array($parameter, $dateSearch)) {
                    if(!($_POST[$parameter] == date("Y-m-d"))) {
                        $generateParameter = $generateParameter . "&" . $parameter . "=" . $_POST[$parameter];
                    }
                } else {
                    $generateParameter = $generateParameter . "&" . $parameter . "=" . $_POST[$parameter];
                }
                
            } 
        }

        if(!($_GET["page"] == "")) {
            $generateParameter =  $generateParameter;
        }
        $generateParameter = "?" . substr($generateParameter, 1);
        
        header("Location: /events" . $generateParameter);
    }
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veranstaltungen -  <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/css/event.css">
    <link rel="stylesheet" href="/event/css/header.css">
            
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/nav.php")
?>
<body class="main" id="main">

    <?php
    include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/event/header.php");
    top("Veranstaltungen");
    ?>
    
    <div class="search">
        <form method="post">
            <div class="input">
                <a onclick="advancedSearch()">
                    <span class="material-symbols-outlined">
                    settings
                    </span>
                </a>
                <input type="text" name="title" placeholder="Titel suchen..." value="<?php echo($_GET["title"]); ?>">
                <input type="submit" value="Suchen" name="submit">
            </div>
            
            
            <div class="disabled" id="advanced-search">
                <div class="single">
                    <label>Alter: </label>
                    <input type="number" placeholder="Alter" name="age" value="<?php echo($_GET["age"]); ?>">
                </div>
                
                <div class="single">
                    <label>Von</label>
                    <input type="date" name="date_from" value="<?php echo($_GET["date_from"]); ?>">

                    <label>bis</label>
                    <input type="date" name="date_to" value="<?php echo($_GET["date_to"]); ?>">
                </div>
                

            </div>
        </form>
        <script src="/event/js/search.js"></script>
    </div>

    <div class="content">
        <?php
        $pageLimit = 10;
        $allEvents = "SELECT * FROM `event`";
        $allEvents = $con_public_new->query($allEvents);
        $eventsIds = array();

        if($_GET["page"] == "") {
            $currentPage = 1;
        } else {
            $currentPage =  intval($_GET["page"]);
        }

        $weekShortName = array("so", "mo", "di", "mi", "do", "fr", "sa");
        
        while ($event = $allEvents->fetch_assoc()) {

            $tmp_eventID = $event["id"];
            
            $eventCalendar = $con_public->query("SELECT * FROM `event_calendar` WHERE event_id = '$tmp_eventID' AND `start` >= NOW()")->fetch_assoc();
            if(!isset($eventCalendar)) {
                $eventCalendar = $con_public->query("SELECT * FROM `event_calendar` WHERE event_id = '$tmp_eventID' ORDER BY `start` DESC")->fetch_assoc();
            }

            if(isset($_GET["age"]) || isset($_GET["title"]) || isset($_GET["date_from"])|| isset($_GET["date_to"])) {
                //age
                if(!($_GET["age"] == "")) {
                    if ($event["age_from"] <= $_GET["age"] && $_GET["age"] <= $event["age_to"]) {
                        array_push($eventsIds, $event["id"]);
                    }
                }
                
                
                //title
                if(!($_GET["title"] == "")) {
                    $titleDbUPPER = strtolower($event["title"]);
                    $titleGetUPPER = strtolower($_GET["title"]);
                    if (str_contains($titleDbUPPER, $titleGetUPPER)) {
                        array_push($eventsIds, $event["id"]);
                    }
                }
                

                //date
                if(isset($_GET["date_from"]) && isset($_GET["date_to"])) {
                    if (strtotime($eventCalendar["start"]) >= strtotime($_GET["date_from"])) {
                        array_push($eventsIds, $event["id"]);
                    }
                }
                
            } else {
                array_push($eventsIds, $event["id"]);
            }
        }
        $eventsIds = array_unique($eventsIds);
        rsort($eventsIds);

        $STARTevent = ($pageLimit * $currentPage - $pageLimit);
        $ENDevent = $STARTevent + $pageLimit;

        $eventsIdsLimited = array();

        for ($i=$STARTevent; $i < $ENDevent; $i++) { 
            array_push($eventsIdsLimited, $eventsIds[$i]);
        }


        $eventsIdsLimited = implode("','", array_unique($eventsIdsLimited));

        $allEvents = "SELECT * FROM `event` WHERE id IN ('$eventsIdsLimited') ORDER BY id DESC";
        $allEvents = $con_public_new->query($allEvents);

        while ($event = $allEvents->fetch_assoc()) {

            $event_img_root_path = $_SERVER["DOCUMENT_ROOT"].'/../cdn/event/image/img-t_'.substr(md5($event["id"]), 5).'-256.jpg';

            if(file_exists($event_img_root_path)) {
                $event_img_path = "https://".$domain["cdn"].'/event/image/img-t_'.substr(md5($event["id"]), 5).'-256.jpg';
            } else {
                $event_img_path = "";
            }

            $tmp_eventID = $event["id"];

            $eventCalendar = $con_public->query("SELECT * FROM `event_calendar` WHERE event_id = '$tmp_eventID' AND `start` >= NOW()")->fetch_assoc();
            if(!isset($eventCalendar)) {
                $eventCalendar = $con_public->query("SELECT * FROM `event_calendar` WHERE event_id = '$tmp_eventID' ORDER BY `start` DESC")->fetch_assoc();
            }
            ?>

            <div class="single" onclick="window.location.href=`/event/view?id=<?php echo($event['id']); ?>`"
            style="
                background-image: linear-gradient(90deg, rgba(255, 255, 255) 0%, rgba(255, 255, 255, .75) 100%),
                url(<?php echo($event_img_path); ?>);
                "
            >
                <div class="date">
                    <?php
                    if(!empty($eventCalendar["start"])) {
                        echo '
                        <h1>'.$weekShortName[date("w", strtotime($eventCalendar["start"]))].'</h1>
                        <p title="'.date("j.n.Y", strtotime($eventCalendar["start"])).'">'.date("j.n.y", strtotime($eventCalendar["start"])).'</p>
                        ';
                    }
                    ?>
                    
                </div>
                <div class="text">

                    <h2>
                        <?php 
                        echo($event["title"]); 
                        ?>
                    </h2>

                    <p class="description">
                        <?php 
                        echo($event["description"]); 
                        ?>
                    </p>

                </div>
            </div>

            <?php
        }

        ?>
    </div>
    <div class="pages">
        <?php
        $generateParameter;
        $dateSearch = array("date_from", "date_to");
        $allowedSearch = array("title", "age", "date_from", "date_to");
        foreach ($allowedSearch as $parameter) {
            if(!($_GET[$parameter]) == "") {

                    $generateParameter = $generateParameter . "&" . $parameter . "=" . $_GET[$parameter];

                
            } 
        }
        if(!empty($generateParameter)) {
            $generateParameter = substr($generateParameter, 1) . "&";

        }
        if (!($currentPage == 1)) {
        echo '
        <a href="?'.$generateParameter . 'page=' . $currentPage - 1 . '">
            <span class="material-symbols-outlined">
            arrow_back_ios
            </span>
        </a>
        ';
        } else {
            echo '<a></a>';
        };
        echo '
        <p>
            '.$currentPage.'
        </p>
        ';
        if(count($eventsIds) > $ENDevent) {
        echo '
        <a href="?'.$generateParameter . 'page=' . $currentPage + 1 . '">
            <span class="material-symbols-outlined">
            arrow_forward_ios
            </span>
        </a>
        ';
        } else {
            echo '<a></a>';
        };
        ?>
    </div>
</body>

<?php
//include scripts for bottom
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/scripts-bottom.php")
?>

</html>