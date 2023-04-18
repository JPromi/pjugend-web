<?php
//include auth_session.php file on all user panel pages
include("../private/session/auth_session.php");

include("../private/database/int.php");
include("../private/database/public.php");
?>

<?php
    if(isset($_POST["submit"])) {
        $generateParameter;
        
        $allowedSearch = array("title");
        foreach ($allowedSearch as $parameter) {
            if(!($_POST[$parameter]) == "") {
                $generateParameter = $generateParameter . "&" . $parameter . "=" . $_POST[$parameter];                
            } 
        }

        if(!($_GET["page"] == "")) {
            $generateParameter =  $generateParameter . "&page=" . $_GET["page"];
        }
        $generateParameter = "?" . substr($generateParameter, 1);
        
        header("Location: /form" . $generateParameter);
    }
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formular -  <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="css/form.css">
</head>
<?php
//include navigation bar
include("../private/intranet/assets/nav.php")
?>
<body class="main" id="main">
    <?php
    include("../private/intranet/form/header.php");
    top("Formulare");
    ?>

<div class="search">
        <form method="post">
            <div class="input">

                <input type="text" name="title" placeholder="Titel suchen..." value="<?php echo($_GET["title"]); ?>">
                <input type="submit" value="Suchen" name="submit">
            </div>
        </form>
    </div>

    <div class="content">
        <?php
        $pageLimit = 40;
        $allForms = "SELECT * FROM `form`";
        $allForms = $con_public_new->query($allForms);
        $formsIds = array();

        if($_GET["page"] == "") {
            $currentPage = 1;
        } else {
            $currentPage =  intval($_GET["page"]);
        }
        
        while ($form = $allForms->fetch_assoc()) {
            if(isset($_GET["age"]) || isset($_GET["title"]) || isset($_GET["date_from"])|| isset($_GET["date_to"])) {
                //age
                if(!($_GET["age"] == "")) {
                    if ($form["age_from"] <= $_GET["age"] && $_GET["age"] <= $form["age_to"]) {
                        array_push($formsIds, $form["id"]);
                    }
                }
                
                
                //title
                if(!($_GET["title"] == "")) {
                    $titleDbUPPER = strtolower($form["title"]);
                    $titleGetUPPER = strtolower($_GET["title"]);
                    if (str_contains($titleDbUPPER, $titleGetUPPER)) {
                        array_push($formsIds, $form["id"]);
                    }
                }
                
                
            } else {
                array_push($formsIds, $form["id"]);
            }
        }
        $formsIds = array_unique($formsIds);

        $STARTform = ($pageLimit * $currentPage - $pageLimit);
        $ENDform = $STARTform + $pageLimit;

        $formsIdsLimited = array();

        for ($i=$STARTform; $i < $ENDform; $i++) { 
            array_push($formsIdsLimited, $formsIds[$i]);
        }


        $formsIdsLimited = implode("','", array_unique($formsIdsLimited));

        $allForms = "SELECT * FROM `form` WHERE id IN ('$formsIdsLimited') ORDER BY id DESC";
        $allForms = $con_public_new->query($allForms);
        
        while ($form = $allForms->fetch_assoc()) {
            
            $allowedUser = array();
            
            $UserCouldInside = array("owner", "result_viewer", "user_edit");
            
                foreach ($UserCouldInside as $key) {
                    $key = explode(";", $form[$key]);
                    foreach ($key as $formSingle) {
                        array_push($allowedUser, $formSingle);
                    }
                }
            
            if(in_array($dbSESSION["user_id"], $allowedUser)) {
            ?>

            <div class="single" onclick="window.location.href=`form/view?id=<?php echo($form["id"]); ?>`">
                <div class="text">
                    <h1>
                        <?php 
                        echo($form["title"]); 
                        ?>
                    </h1>

                    <p class="description">
                        <?php 
                        echo($form["description"]); 
                        ?>
                    </p>
                </div>
            </div>

            <?php
            }
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
        if(count($formsIds) > $ENDform) {
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