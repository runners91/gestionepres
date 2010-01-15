<?php
    $stampaform = false;
    $e = new Evento();
    if(isset($_GET['id_evento']) && !isset($_POST['azione'])){
        $e->getValoriDB($_GET['id_evento']);
    }
    else{
        $e->getValoriPost();
    }

    if($_POST["azione"] == "Elimina"){
        if($e->eliminaEvento())
            $messaggioSucc = "l'evento &egrave; stato eliminato";
    }
    else if($_POST["azione"] == "Salva"){
        if(sizeof($e->errori)==0){
            if($e->aggiornaEvento())
                $messaggioSucc = "Aggiornamento eseguito con successo";
            else {
                if(isset($e->errori["processi"]))
                    $messaggioErr = $e->errori["processi"];
                else
                    $messaggioErr = "non &egrave; stato possibile modificare l'evento";
                $stampaform = true;
            }
        }
        else
            $stampaform = true;
    }

    if(isset($_GET["id_evento"]) && $_GET["azione"] == "visualizza"){
        if($e->getStato() == 3)
            $stampaform = true;
        else
            $messaggioErr = "Questo evento non è stato segnalato";
    }
    
    $sql = "SELECT count(*) as totEventi FROM eventi e,causali c,dipendenti d WHERE c.id_motivo = e.fk_causale AND d.id_dipendente = e.fk_dipendente AND stato = 3 ORDER BY DATA_DA";
    $rs = Database::getInstance()->eseguiQuery($sql);

    $minRiga = Utilita::getNewMinRiga($_POST['codPag'],$_POST['minRiga']);
    $visTxt = '<a href="index.php?pagina=amministrazione&tab=gestione_segnalazioni&azione=visualizza&minrg='.$minRiga.'&id_evento='; $visTxt2 = ' "><img border="0" src="img/modifica.png" /></a>';
    $prioTxt  = '<img src="./img/prio'; $prioTxt2 = '.png" />';

    $sql = "SELECT e.id_evento 'id', CONCAT('".$prioTxt."',e.priorita,'".$prioTxt2."') as '',d.username as Utente, c.nome as Nome,date_format(FROM_UNIXTIME(e.data_da),'%d.%m.%y-%H:%i') as Dal,date_format(FROM_UNIXTIME(e.data_a),'%d.%m.%y-%H:%i') as Al,e.commento as Commento, CONCAT('".$visTxt."',e.id_evento,'".$visTxt2."') as Edit FROM eventi e,causali c,dipendenti d WHERE c.id_motivo = e.fk_causale AND d.id_dipendente = e.fk_dipendente AND stato = 3 ORDER BY DATA_DA";
    $rs = Database::getInstance()->eseguiQuery($sql);
    echo "<table><tr>";
    if($rs->rowCount()>0) {
        echo "<td style='float:left' valign='top'>";
            Utilita::stampaTabella($rs,isset($e)?$e->getID():0);
        echo "</td>";
    }
    else
        echo "Non ci sono segnalazioni";

    if($stampaform) {
        echo "<td style='width:30px;'></td>";
        echo "<td>";
            stampaFormModificaEvento($e);
        echo '<div class="messaggioErrore">'.$messaggioErr.'</div>';
        echo "</td>";
        echo "<td style='width:30px;'></td>";
        echo "<td valign='top'>";
            echo "<b>Commento Segnalazione:</b><br />";
            echo $e->getCommentoSegn();
        echo "</td>";
    }
    else
        echo '<td style="width:30px;"></td><td valign="top" ><div class="messaggioErrore">'.$messaggioErr.'</div> <div class="messaggioTaskOk">'.$messaggioSucc.'</div></td>';
        
    echo "</tr></table>";
    
    function stampaFormModificaEvento($e) {
    
?>
    <form action="index.php?pagina=amministrazione&tab=gestione_segnalazioni" method="POST">
        <input type="hidden" name="id_evento" value="<?php echo $e->getID(); ?>">
        <input type="hidden" name="utente" value="<?php echo $e->getDipendente(); ?>">
        <input type="hidden" name="commento_segn" value="<?php echo $e->getCommentoSegn(); ?>">
        <table>
            <tr>
                <td class="cellaTitoloTask" colspan="2">
                    Modifica Evento:
                </td>
            </tr>
            <tr>
                <td class="label">
                Da:
                </td>
                <td>
                    <input id="sel1" class="calTextfield<?php echo isset($e->errori["data_da"])?" errore":"";?>" type="textfield" name="dataDa" value="<?php echo date("d.m.Y",$e->getDataDa()); ?>" />
                </td>
                <td>
                    <input value="" type="reset" onclick="return showCalendar('sel1', '%d.%m.%Y');" class="imgCal" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">
                    <span class="messaggioErrore"><?php echo $e->errori["data_da"]; ?></span>
                </td>
            </tr>
            <tr>
                <td class="label">
                    A:
                </td>
                <td>
                    <input id="sel2" class="calTextfield <?php echo isset($e->errori["data_a"])?" errore":"";?>" type="textfield" name="dataA" value="<?php echo date("d.m.Y",$e->getDataA()); ?>" />
                </td>
                <td>
                    <input value="" type="reset" onclick="return showCalendar('sel2', '%d.%m.%Y');" class="imgCal" />
                </td>
            </tr>
                        <tr>
                <td></td>
                <td colspan="2">
                    <span class="messaggioErrore"><?php echo $e->errori["data_a"]; ?></span>
                </td>
            </tr>
            <tr>
                <td class="label">
                    Tipo:
                </td>
                <td>
                    <select name="tipo" class="selectField">
                    <?php
                        $rs = Database::getInstance()->eseguiQuery("SELECT c.nome as d, c.id_motivo as r FROM causali c");
                        while(!$rs->EOF){
                            if($rs->fields['r']==$e->getCausale())
                                echo '<option selected="selected" value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                            else
                                echo '<option value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                            $rs->MoveNext();
                        }
                    ?>
                    </select>
                </td>
                <td valign="top">
                    <select name="durata">
                        <option value="G" selected="selected">Giornata</option>
                        <?php $txt = ""; if($e->getDurata()=="M") $txt = "selected='selected'"; ?>
                        <option value="M" <?php echo $txt; ?>>Mattina</option>
                        <?php $txt = ""; if($e->getDurata()=="P") $txt = "selected='selected'"; ?>
                        <option value="P" <?php echo $txt; ?>>Pomeriggio</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label">
                    Commento:
                </td>
                <td>
                    <input type="textflied" name="commento" value="<?php echo $e->getCommento(); ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">
                    Priorit&agrave:
                </td>
                <td>
                    <span class="prio1"><input type="radio" name="priorita" value="1" checked="checked" />1</span>
                    <?php $txt = ""; if($e->getPriorita()==2) $txt = "checked='checked'"; ?>
                    <span class="prio2"><input type="radio" name="priorita" value="2" <?php echo $txt; ?> />2</span>
                    <?php $txt = ""; if($e->getPriorita()==3) $txt = "checked='checked'"; ?>
                    <span class="prio3"><input type="radio" name="priorita" value="3" <?php echo $txt; ?> />3</span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <input type="button" value="Annulla" class="bottCalendario" onclick="location.href = '?pagina=amministrazione&tab=gestione_segnalazioni'"> &nbsp;<input type="submit" name="azione" value="Elimina" class="bottCalendario" /> &nbsp;<input type="submit" name="azione" value="Salva" class="bottCalendario" />
                </td>
            </tr>
        </table>
    </form>
<?php
    }
?>