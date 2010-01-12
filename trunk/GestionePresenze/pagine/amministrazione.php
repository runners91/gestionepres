<?php include("navigazione/submenu.php"); ?>
<div>
    <ul id="tabList">
        <?php
            $paginaAttuale = "amministrazione";
            $tabAttuale = $_GET['tab'];

            if($tabAttuale=="")
                $tabAttuale = "gestione_utente";

            creaSubmenuItem($paginaAttuale, $tabAttuale, "gestione_utente", "GESTIONE UTENTI");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "gestione_autorizzazioni", "GESTIONE AUTORIZZAZIONI");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "gestione_gruppi", "GESTIONE GRUPPI");
            $rs = Database::getInstance()->eseguiQuery("SELECT count(*) as totale FROM eventi WHERE stato = 3");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "gestione_segnalazioni", "GESTIONE SEGNALAZIONI(".$rs->fields["totale"].")");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "gestione_costanti", "MODIFICA DATI");
        ?>
    </ul>

    <div id="contenuto">
        <?php
            if(!isset($_GET['tab'])){
                include("tab/gestione_utente.php");
            }
            else{
                include("tab/".$_GET['tab'].".php");
            }
        ?>
    </div>
</div>
