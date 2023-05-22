<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/public.php';
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
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
    <title>Veranstaltungen - <?php echo($conf_title["web"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/css/events.css">
    
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/../private/favicon/main.php';
    ?>
    
</head>

<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/web/assets/nav.php';
?>

<body>
    <div class="title">
        <h4>Veranstaltungen</h4>
    </div>

    <form class="search" method="post">
        <div class="input">

            <a onclick="advancedSettings()">
                <span class="material-symbols-outlined">
                settings
                </span>
            </a>
            
            <input type="text" name="title" value="<?php echo($_GET["title"]) ?>">

            <label>
                <input type="submit" name="submit" value="">
                <span class="material-symbols-outlined">
                search
                </span>
            </label>
        </div>
        <div class="advanced hidden" id="advancedSearch">
            <label>
                <p>Alter: </p>
                <input type="number" name="age" value="<?php echo($_GET["age"]) ?>">
            </label>
            <label>
                <p>Datum </p>
                <input type="date" name="date_from" value="<?php echo($_GET["date_from"]) ?>">
                <p> bis </p>
                <input type="date" name="date_to" value="<?php echo($_GET["date_to"]) ?>">
            </label>
            
        </div>
    </form>

    <div class="content">
        <?php
        $pageLimit = 10;
        $allEvents = "SELECT * FROM `event` WHERE only_specific_group = '0' AND visibility = 'all'";
        $allEvents = $con_public_new->query($allEvents);
        $eventsIds = array();

        if($_GET["page"] == "") {
            $currentPage = 1;
        } else {
            $currentPage =  intval($_GET["page"]);
        }

        $weekShortName = array("so", "mo", "di", "mi", "do", "fr", "sa");
        
        while ($event = $allEvents->fetch_assoc()) {
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
                    if (strtotime($event["date_from"]) >= strtotime($_GET["date_from"])) {
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
            ?>

            <div class="single" onclick="window.location.href=`/events/view?id=<?php echo($event['id']); ?>`"
            style="
                background-image: linear-gradient(90deg, rgba(255, 255, 255) 0%, rgba(255, 255, 255, .75) 100%),
                url(<?php echo($event_img_path); ?>);
                "
            >
                <div class="date">
                    <?php
                    if(!empty($event["date_from"])) {
                        echo '
                        <h1>'.$weekShortName[date("w", strtotime($event["date_from"]))].'</h1>
                        <p>'.date("j.n", strtotime($event["date_from"])).'</p>
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
    <script src="/events/js/search.js"></script>
</body>

<?php
include $_SERVER["DOCUMENT_ROOT"].'/../private/web/assets/footer.php';
?>

</html>