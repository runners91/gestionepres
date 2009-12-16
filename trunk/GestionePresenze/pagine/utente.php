<?php include("navigazione/submenu.php"); ?>
<div>
    <ul id="tabList">
        <?php
            $paginaAttuale = "utente";
            $tabAttuale = $_GET['tab'];

            if($tabAttuale=="")
                $tabAttuale = "visualizza_saldi";

            creaSubmenuItem($paginaAttuale, $tabAttuale, "visualizza_saldi", "VISUALIZZA SALDI");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "visualizza_timbrature", "VISUALIZZA TIMBRATURE");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "segnalazione", "SEGNALA");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "cambia_password", "CAMBIA PASSWORD");

        ?>
    </ul>

    <div id="contenuto">
        <?php
            if(!isset($_GET['tab'])){
                include("tab/visualizza_saldi.php");
            }
            else{
                include("tab/".$_GET['tab'].".php");
            }
        ?>
    </div>
</div>