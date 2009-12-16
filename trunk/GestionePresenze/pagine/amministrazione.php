<?php include("navigazione/submenu.php"); ?>
<div>
    <ul id="tabList">
        <?php
            $paginaAttuale = "amministrazione";
            $tabAttuale = $_GET['tab'];

            if($tabAttuale=="")
                $tabAttuale = "inserisci_assenza";

            creaSubmenuItem($paginaAttuale, $tabAttuale, "inserisci_assenza", "INSERISCI ASSENZA");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "inserisci_festivo", "INSERISCI FESTIVO");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "gestione_autorizzazioni", "AUTORIZZAZIONI");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "gestione_costanti", "MODIFICA DATI");
        ?>
    </ul>

    <div id="contenuto">
        <?php
            if(!isset($_GET['tab'])){
                include("tab/inserisci_assenza.php");
            }
            else{
                include("tab/".$_GET['tab'].".php");
            }
        ?>
    </div>
</div>