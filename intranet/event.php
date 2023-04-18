<?php
//include auth_session.php file on all user panel pages
include("../private/session/auth_session.php");

include("../private/database/int.php");
include("../private/database/public.php");
?>

<?php
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
            $generateParameter =  $generateParameter . "&page=" . $_GET["page"];
        }
        $generateParameter = "?" . substr($generateParameter, 1);
        
        header("Location: /event" . $generateParameter);
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
    <link rel="stylesheet" href="css/event.css">
    <link rel="stylesheet" href="event/css/header.css">
</head>
<?php
//include navigation bar
include("../private/intranet/assets/nav.php")
?>
<body class="main" id="main">

    <?php
    include("../private/intranet/event/header.php");
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
        $pageLimit = 40;
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
            ?>

            <div class="single" onclick="window.location.href=`event/view?id=<?php echo($event["id"]); ?>`">
                <div class="text">
                    <h1>
                        <?php 
                        echo($event["title"]); 
                        ?>
                    </h1>

                    <p class="description">
                        <?php 
                        echo($event["description"]); 
                        ?>
                    </p>

                    <p class="date">
                        <?php 
                        echo(date("j.n.Y h:m", strtotime($event["date_from"])) . " - " . date("j.n.Y h:m", strtotime($event["date_to"]))); 
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
include("../private/intranet/assets/scripts-bottom.php")
?>

</html>