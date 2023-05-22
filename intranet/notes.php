<?php
//include auth_session.php file on all user panel pages
include($_SERVER["DOCUMENT_ROOT"]."/../private/session/auth_session.php");
?>

<?php
//include database
$userID = $dbSESSION['user_id'];
include $_SERVER["DOCUMENT_ROOT"].'/../private/database/int.php';
?>

<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notizen - <?php echo($conf_title["intranet"]); ?></title>

    <link rel="stylesheet" href="/css/style/style.css">
    <link rel="stylesheet" href="/css/notes.css">

    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/../private/favicon/main.php';
    ?>

</head>
<?php
//include navigation bar
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/nav.php")
?>
<body class="main" id="main">

    <section class="content">

        <div class="header">
            <div class="left">
                <h4>Notizen</h4>
            </div>
            
            <div class="middle">

            </div>

            <div class="right">
                <a href="/notes" class="new">
                    <span class="material-symbols-outlined">add</span>
                </a>
            </div>
            
        </div>

        <!--Note Folder-->
        <div class="notebody">
            <div class="folder shadow-border" id="folder">
                <div class="scroll">
                    <div class="option">
                        <button onclick="window.location.href=`/notes/add-folder`">
                            <span class="material-symbols-outlined">
                            create_new_folder
                            </span>
                        </button>

                        <!--toggle search-->
                        <button onclick="searchFolderInput()">
                            <span class="material-symbols-outlined">
                            search
                            </span>
                        </button>

                        <input type="text" id="searchFolder" style="display: none;" onkeyup="searchFolder()" placeholder="Ordner suchen">
                    </div>
                    <div class="single text" onclick="window.location.href=`./notes`">
                        <h1>Alle Notizen</h1>
                    </div>
                    
                    <?php
                        //select preview from database
                        $dbFOLDERlist = "SELECT * FROM `notes_group` WHERE owner_id = '$userID'";
                        $dbFOLDERlist = $con_new->query($dbFOLDERlist);

                        while ($folder = $dbFOLDERlist->fetch_assoc()) {

                            $folderSelected = "";
                            if ($_GET["folder"] == $folder["id"]) {
                                $folderSelected = "selected";
                            }

                            echo '
                            <div class="single '.$folderSelected.'" id="single">
                                <div class="text" onclick="window.location.href=`?folder='.$folder["id"].'`">
                                    <h1>'.substr($folder["name"], 0, 50).'</h1>
                                    <p>'.count(explode(";", $folder["notes_id"])).' Notizen</p>
                                </div>
                                <div class="btn">
                                    <button onclick="window.location.href=`/notes/edit-folder?folder='.$folder["id"].'`">
                                        <span class="material-symbols-outlined">
                                        edit
                                        </span>
                                    </button>
                                </div>
                            </div>
                            ';
                        }
                    ?>
                </div>
            </div>

            <!--notes-->
            <div class="notes shadow-border" id="notes">
                <div class="scroll">
                    <div class="option">

                        <!--toggle search-->
                        <button onclick="searchNoteInput()">
                            <span class="material-symbols-outlined">
                            search
                            </span>
                        </button>

                        <input type="text" id="searchNote" style="display: none;" onkeyup="searchNote()" placeholder="Notiz suchen">
                    </div>
                    <?php
                        //folder logic
                        $folderID = $_GET["folder"];

                        $sharedNoteIDs = [];

                        $dbFOLDER = "SELECT * FROM `notes_group` WHERE owner_id = '$userID' AND id = '$folderID'";
                        $dbFOLDER = $con_new->query($dbFOLDER);
                        $dbFOLDER = $dbFOLDER->fetch_assoc();

                        $dbNOTEprePERM = "SELECT * FROM `notes`";
                        $dbNOTEprePERM = $con_new->query($dbNOTEprePERM);

                        //select all shared notes
                        while ($sharedNote = $dbNOTEprePERM->fetch_assoc()) {
                            $sharedNoteRead = explode(';', $sharedNote["reader_id"]);
                            $sharedNoteWrite = explode(';', $sharedNote["writer_id"]);
                            
                            if (in_array($userID, $sharedNoteRead)) {
                                array_push($sharedNoteIDs, $sharedNote["id"]);
                            } elseif(in_array($userID, $sharedNoteWrite)) {
                                array_push($sharedNoteIDs, $sharedNote["id"]);
                            } else {
                                array_push($sharedNoteIDs, "0");
                            }
                        }
                        array_unique($sharedNoteIDs);
                        $sharedNoteIDsString = implode(", ", $sharedNoteIDs);


                        if (isset($dbFOLDER)) {
                            $noteArray = str_replace(";", ", ", $dbFOLDER["notes_id"]);
                            
                            //select preview from database
                            $dbNOTEpre = "SELECT * FROM `notes` WHERE id IN ($noteArray) ORDER BY last_change DESC";
                            $dbNOTEpre = $con_new->query($dbNOTEpre);
                            
                        } else {
                            $dbNOTEpre = "SELECT * FROM `notes` WHERE owner_id = '$userID' OR id IN ($sharedNoteIDsString) ORDER BY last_change DESC";
                            $dbNOTEpre = $con_new->query($dbNOTEpre);
                        }

                        

                        if (isset($_GET["folder"])) {
                            $addURL = "folder=".$_GET["folder"]."&";
                        }
                        if(!(empty($dbNOTEpre))) {
                            while ($note = $dbNOTEpre->fetch_assoc()) {
                                $lastChangeSingel = date('d.m.Y H:i', strtotime($note["last_change"]));

                                //check if folder is selected
                                $noteSelected = "";
                                if ($_GET["note"] == $note["id"]) {
                                    $noteSelected = "selected";
                                }
                                $textPRE = strip_tags($note["text"]);
                                $textPRE = substr($textPRE, 0, 100);
                                echo '
                                <div class="single '.$noteSelected.'" onclick="window.location.href=`?'.$addURL.'note='.$note["id"].'`">
                                    <h1>'.substr($note["title"], 0, 50).'</h1>
                                    <p class="text">'.$textPRE.'</p>
                                    <p class="date">'.$lastChangeSingel.'</p>
                                </div>
                                ';
                            };
                        }
                    ?>
                </div>
            </div>

            <!--note editor-->
            <div class="editor shadow-border">
                <?php
                        $writePerm = false;
                        $readPerm = false;

                        $noteID = $_GET["note"];
                        $dbNOTEchecker = "SELECT * FROM `notes` WHERE id = '$noteID'";
                        $dbNOTEchecker = $con_new->query($dbNOTEchecker);
                        $dbNOTEchecker = $dbNOTEchecker->fetch_assoc();

                        $sharedNoteWritePermARRAY = explode(";", $dbNOTEchecker["writer_id"]);
                        $sharedNoteReadPermARRAY = explode(";", $dbNOTEchecker["reader_id"]);

                        //check if you can write/read the note
                        if(in_array($userID, $sharedNoteWritePermARRAY) || in_array($userID, $sharedNoteReadPermARRAY)) {
                            $dbNOTE = "SELECT * FROM `notes` WHERE id = '$noteID'";
                            $dbNOTE = $con_new->query($dbNOTE);
                            $dbNOTE = $dbNOTE->fetch_assoc();
                        } else {
                            $dbNOTE = "SELECT * FROM `notes` WHERE owner_id = '$userID' AND id = '$noteID'";
                            $dbNOTE = $con_new->query($dbNOTE);
                            $dbNOTE = $dbNOTE->fetch_assoc();
                            if(!(empty($dbNOTE))) {
                                $writePerm = true;
                                $readPerm = true;
                            }
                        }

                        //check premission
                        if(in_array($userID, $sharedNoteWritePermARRAY)) {
                            $writePerm = true;
                            $guestPerm = true;
                        } elseif (in_array($userID, $sharedNoteReadPermARRAY)) {
                            $readPerm = true;
                        } elseif (!(isset($_POST["note"]))) {
                            $readPerm = true;
                            $writePerm = true;
                            $guestPerm = false;
                        }

                        if (!(isset($_GET["note"]))) {
                            $guestPerm = true;
                        }

                        //out from note when no perm
                        if (isset($_GET["note"])) {
                            if ($writePerm == false && $readPerm == false) {
                                echo('<meta http-equiv="refresh" content="0; URL=/notes">');
                            }
                        }
                        
                        $lastChange = date('d.m.Y H:i', strtotime($dbNOTE["last_change"]));
                        $createdAt = date('d.m.Y H:i', strtotime($dbNOTE["created_at"]));

                        //get owner info
                        $ownerID = $dbNOTE["owner_id"];
                        $dbOWNER = "SELECT firstname, lastname, username FROM `accounts` WHERE id = '$ownerID'";
                        $dbOWNER = $con_new->query($dbOWNER);
                        $dbOWNER = $dbOWNER->fetch_assoc();

                        //set writeable to true
                        $writeable = "false";
                        if($writePerm == true) {
                            $writeable = "true";
                        };

                        if (isset($noteID)) {
                            $notePostURL = $noteID;
                        } else {
                            $notePostURL = "new";
                        }

                            echo '
                            <form action="/notes/post-note?note='.$notePostURL.'" method="post" autocomplete="off" onload="setSendNoteForm()">
                                <div class="tools">
                                    <div class="top">
                                        <div class="left">
                            ';
                            if($writePerm == true) {
                                echo '
                                        <label>
                                            <input type="submit" name="save" value="Speichern">
                                            <span class="material-symbols-outlined">
                                            save
                                            </span>
                                        </label>
                                ';
                                if (!($guestPerm == true)) {
                                    echo '
                                        <a onclick="deleteNote()" id="deleteSetNote">
                                            <span class="material-symbols-outlined">
                                            delete
                                            </span>
                                        </a>
                                        
                                        <div id="deleteNote" style="display: none;">
                                            
                                            <label>
                                                <input type="submit" name="delete" value="Löschen">
                                                <span class="material-symbols-outlined">
                                                delete
                                                </span>
                                            </label>

                                            <a onclick="deleteNoteUndo()" id="deleteSetNoteUndo">
                                                <span class="material-symbols-outlined">
                                                close
                                                </span>
                                            </a>

                                            <p>Willst du diese Notiz wirklich löschen?</p>
                                            
                                        </div>
                                    ';
                                }
                            }

                            echo '
                                    </div>
                                    <div class="right">
                            ';

                            
                            if($writePerm == true) {
                                if (!($guestPerm == true)) {
                                    echo '
                                    <a href="./notes/share-note?note='.$dbNOTE["id"].'">
                                        <span class="material-symbols-outlined">
                                        share
                                        </span>
                                    </a>
                                    ';
                                }
                            }
                
                            echo '  
                                    </div>
                                    </div>

                                    <div class="specText" onclick="setSendNoteForm()">

                                            <a>
                                                <span data-command="undo" class="material-symbols-outlined">
                                                undo
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="redo" class="material-symbols-outlined">
                                                redo
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="insertHorizontalRule" class="material-symbols-outlined">
                                                maximize
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="bold" class="material-symbols-outlined">
                                                format_bold
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="italic" class="material-symbols-outlined">
                                                format_italic
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="underline" class="material-symbols-outlined">
                                                format_underlined
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="strikeThrough" class="material-symbols-outlined">
                                                strikethrough_s
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="justifyLeft" class="material-symbols-outlined">
                                                format_align_left
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="justifyCenter" class="material-symbols-outlined">
                                                format_align_center
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="justifyRight" class="material-symbols-outlined">
                                                format_align_right
                                                </span>
                                            </a>

                                            <!--<a>
                                                <span data-command="justifyFull" class="material-symbols-outlined">
                                                format_align_justify
                                                </span>
                                            </a>-->

                                            <!--<a data-command="indent">indent</a>
                                            <a data-command="outdent">outdent</a>-->

                                            <a>
                                                <span data-command="insertUnorderedList" class="material-symbols-outlined">
                                                format_list_bulleted
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="insertOrderedList" class="material-symbols-outlined">
                                                format_list_numbered
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="html" data-command-argument="h1" class="material-symbols-outlined">
                                                title
                                                </span>
                                            </a>

                                            <!--<a data-command="html" data-command-argument="h2">h2</a>
                                            <a data-command="html" data-command-argument="h3">h3</a>-->

                                            <a>
                                                <span data-command="html" data-command-argument="p" class="material-symbols-outlined">
                                                text_format
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="html" data-command-argument="pre" class="material-symbols-outlined">
                                                code
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="subscript" class="material-symbols-outlined">
                                                subscript
                                                </span>
                                            </a>

                                            <a>
                                                <span data-command="superscript" class="material-symbols-outlined">
                                                superscript
                                                </span>
                                            </a>
                                        </div>

                                    
                                </div>
                                
                                
                                <div class="inputs scroll">
                                    <input type="text" name="title" class="title" value="'.$dbNOTE["title"].'" maxlength="254"';
                            if($writePerm == false) {
                                echo('readonly');
                            };
                            echo '
                                    >
                                    <div class="texboxOut">
                                        <div id="editorText" class="textbox" contenteditable="'.$writeable.'" onclick="setSendNoteForm()" onkeyup="setSendNoteForm()">
                                        '.$dbNOTE["text"].'
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <div class="fleft">
                            ';
                            if(isset($_GET["note"])) {
                                echo '
                                        <p>Geändert am '.$lastChange.'</p>
                                        <p>Erstellt am '.$createdAt.'</p>
                                        <p>Erstellt von '.$dbOWNER["firstname"].' '.$dbOWNER["lastname"].'</p>
                                ';
                            };
                            echo '       
                                    </div>
                                    
                                    <div class="fright">
                                        <js-replace id="charcount"> </js-replace><p> / 65 000</p>
                                    </div>
                                </div>
                                <textarea id="TextToForm" name="text""></textarea>
                            </form>
                            ';
                    
                ?>
            </div>
        </div>
        
    </section>
    <script src="/notes/js/search.js"></script>
    <script src="/notes/js/editor.js"></script>
    
</body>

<?php
//include scripts for bottom
include($_SERVER["DOCUMENT_ROOT"]."/../private/intranet/assets/scripts-bottom.php")
?>

</html>