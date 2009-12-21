<?php
    if($_POST)
        Utilita::stampaCalendario($_POST['m']);
    else
        Utilita::stampaCalendario($_GET['m']);
?>