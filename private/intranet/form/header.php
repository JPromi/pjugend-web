<?php
function top($title) {
    global $dbSESSION_perm;
?>
<link rel="stylesheet" href="/form/css/header.css">
<div class="top">

    <div class="left">
        <h4><a href="/form"><?php echo($title); ?></a></h4>
    </div>

    <div class="right">
        <?php
        if(in_array("form", $dbSESSION_perm)) {
        ?>
        <button title="Neue Veranstalltung" onclick="window.location.href=`/form/add`">
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