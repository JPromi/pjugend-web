<?php
function top($title) {
    global $dbSESSION_perm;
?>
<link rel="stylesheet" href="/event/css/header.css">
<div class="top">

    <div class="left">
        <h4><a href="/event"><?php echo($title); ?></a></h4>
    </div>

    <div class="right">
        <?php
        if(in_array("event", $dbSESSION_perm)) {
        ?>
        <button title="Neue Veranstalltung" onclick="window.location.href=`/event/add`">
            <span class="material-symbols-outlined">
            add
            </span>
        </button>
        <?php
        };
        ?>
    </div>

</div>

<?php
};
?>