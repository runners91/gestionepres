<?php include("navigazione/submenu.php"); ?>
<div>
    <ul id="tabList">
        <?php
            $paginaAttuale = "statistiche";
            $tabAttuale = $_GET['tab'];

            if($tabAttuale=="")
                $tabAttuale = "statistiche_globali";

            creaSubmenuItem($paginaAttuale, $tabAttuale, "statistiche_globali", "STATISTICHE GLOBALI");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "statistiche_personali", "STATISTICHE PERSONALI");
            
        ?>
    </ul>

    <div id="contenuto">
        <?php
            if(!isset($_GET['tab'])){
                include("tab/statistiche_globali.php");
            }
            else{
                include("tab/".$_GET['tab'].".php");
            }
        ?>
    </div>
</div>