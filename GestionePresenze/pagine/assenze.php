<?php include("navigazione/submenu.php"); ?>
<div>
    <ul id="tabList">
        <?php
            $paginaAttuale = "assenze";
            $tabAttuale = $_GET['tab'];

            if($tabAttuale=="")
                $tabAttuale = "panoramica";

            creaSubmenuItem($paginaAttuale, $tabAttuale, "panoramica", "HOME");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "test", "TEST");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "p1", "SEGNALA ASSENZA");
        ?>
    </ul>

    <div id="contenuto">
        Questa è la pagina assenze
    </div>
</div>