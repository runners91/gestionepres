<?php
        $stampaform = false;
        if($_POST["azione"] == "segnala"){
            $commento = trim($_POST["commento"]);
            $e = new Evento();
            $e->getValoriDB($_POST["id_evento"]);
            if(strlen($commento)>0) {
                if($e->aggiornaStato(3,$commento)){
                    $messaggioSucc = "<b>L'evento &egrave stato segnalato</b>";
                }
            }
            else {
                $errori["commento"] = "Il commento non pu&ograve essere nullo";
                $stampaform = true;
            }
        }

        if(isset($_GET["id_evento"])){
            $e = new Evento();
            $e->getValoriDB($_GET["id_evento"]);
            if($e->getStato() != 2 || $e->getDipendente() != $_SESSION["id_utente"]){
                $messaggioErr = "Non puoi segnalare questo evento";
                $stampaform = false;
            }
            else
                $stampaform = $_GET["azione"] == "commento";

        }

        $d = new Dipendente();
        $utente = $d->trovaUtenteDaUsername($_SESSION["username"])->id;
        $minRiga = Utilita::getNewMinRiga($_POST['codPag'],$_POST['minRiga']);
        $segnTxt = '<a href="index.php?pagina=utente&tab=segnalazione&azione=commento&minrg='.$minRiga.'&id_evento='; $segnTxt2 = ' ">segnala</a>';
        $prioTxt  = '<img src="./img/prio'; $prioTxt2 = '.png" />';
        $sql = "SELECT e.id_evento 'id', CONCAT('".$prioTxt."',e.priorita,'".$prioTxt2."') as '', c.nome as Nome,date_format(FROM_UNIXTIME(e.data_da),'%d.%m.%y-%H:%i') as Dal,date_format(FROM_UNIXTIME(e.data_a),'%d.%m.%y-%H:%i') as Al,e.commento as Commento, CONCAT('".$segnTxt."',e.id_evento,'".$segnTxt2."') as Segnala FROM eventi e,causali c,dipendenti d WHERE c.id_motivo = e.fk_causale and d.id_dipendente = e.fk_dipendente and e.fk_dipendente = ".$utente." AND e.stato = 2 ORDER BY DATA_DA desc";

        $rs = Database::getInstance()->eseguiQuery($sql);
        echo "<table><tr>";
        if($rs->rowCount()>0) {
            echo "<td valign='top'>";
                Utilita::stampaTabella($rs,isset($e)?$e->getID():0);
            echo "</td>";
        }
        else
            $messaggioErr = "Non ci sono eventi da segnalare";

        if($stampaform){
            echo "<td>";
            echo "</td>";
            echo "<td style='width:30px;'></td>";
            echo "<td valign='top'>";
                stampaFormCommento($e,$errori);
            echo "</td>";
        }
        else 
            echo '<td valign="top"><div class="messaggioErr">'.$messaggioErr.'</div><div class="messaggioTaskOk">'.$messaggioSucc.'</div></td>';
        echo "</tr></table>";

        function stampaFormCommento($e,$errori){
?>
            <div class="messaggioErrore"><?php echo $errori["commento"];?></div>
            Commento:
            <form action="index.php?pagina=utente&tab=segnalazione" method="POST">
                <input type="hidden" name="id_evento" value="<?php echo $e->getID(); ?>"/>
                <input type="hidden" name="azione" value="segnala"/>
                <textarea cols="50" rows="8" name="commento" <?php echo isset($errori["commento"])?"class='errore'":""; ?>></textarea>
                <input type="submit" value="Segnala evento" class="bottCalendario">
            </form>
    <?php
        }
    ?>