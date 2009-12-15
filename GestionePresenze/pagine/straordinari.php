<?php include("navigazione/submenu.php"); ?>
<div>
    <ul id="tabList">
        <?php
            $paginaAttuale = "straordinari";
            $tabAttuale = $_GET['tab'];

            if($tabAttuale=="")
                $tabAttuale = "panoramica";

            creaSubmenuItem($paginaAttuale, $tabAttuale, "panoramica", "HOME");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "p2", "GESTIONE GIORNI FESTIVI");
        ?>
    </ul>

    <div id="contenuto">
        Questa è la pagina straordinari
    </div>
</div>