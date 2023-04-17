<?php
function top($title) {
?>
<link rel="stylesheet" href="/event/css/header.css">
<div class="top">

    <div class="left">
        <h4><a href="/event"><?php echo($title); ?></a></h4>
    </div>

    <div class="right">
        <button title="Neue Veranstalltung" onclick="window.location.href=`/event/add`">
            <span class="material-symbols-outlined">
            add
            </span>
        </button>
    </div>

</div>

<?php
};
?>