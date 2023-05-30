<?php
include($_SERVER["DOCUMENT_ROOT"]."/../private/config.php");
?>

<link rel="stylesheet" href="/css/style/footer.css">
<footer>

    <div class="main">

        <div class="block">
            <h4>Legacy</h4>
            <a href="/impressum">Impressum</a>
            <a href="/privacy">Datenschutz</a>
        </div>

        <div class="block">
            <h4>Intern</h4>
            <a href="https://<?php echo($domain["auth"]); ?>/">Login</a>
            <a href="https://<?php echo($domain["auth"]); ?>/?l=firmung">Firmling Login</a>
        </div>

    </div>

    <div class="bottom">
        <p>© <?php echo(date("Y")) ?> <a href="https://jpromi.com">JPromi.com</a></p>
    </div>

</footer>