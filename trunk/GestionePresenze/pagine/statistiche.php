<?php include("navigazione/submenu.php"); ?>
<script language="javascript" type="text/javascript" src="script/jquery.flot.min.js"></script>
<div>
    <ul id="tabList">
        <?php
            $paginaAttuale = "statistiche";
            $tabAttuale = $_GET['tab'];

            if($tabAttuale=="")
                $tabAttuale = "statistiche_globali";

            creaSubmenuItem($paginaAttuale, $tabAttuale, "statistiche_personali", "STATISTICHE PERSONALI");
            creaSubmenuItem($paginaAttuale, $tabAttuale, "statistiche_globali", "STATISTICHE GLOBALI");
            
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