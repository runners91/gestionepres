<?php include("navigazione/submenu.php"); ?>
<div>
    <ul id="tabList">
        <?php
            $paginaAttuale = "amministrazione";
            $tabAttuale = $_GET['tab'];

            if($tabAttuale=="")
                $tabAttuale = "inserisci_assenza";

            creaSubmenuItem($paginaAttuale, $tabAttuale, "gestione_utente", "GESTIONE UTENTI");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "gestione_autorizzazioni", "GESTIONE AUTORIZZAZIONI");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "gestione_gruppi", "GESTIONE GRUPPI");
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