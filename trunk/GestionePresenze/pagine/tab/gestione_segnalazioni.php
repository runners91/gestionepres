<?php
    $stampaform = false;
    if($_POST["azione"] == "Elimina"){
        if(Evento::eliminaEvento($_POST["id_evento"]))
            $messaggioSucc = "l'evento &egrave; stato eliminato";
    }
    else if($_POST["azione"] == "Salva"){
        $e = new Evento($_POST["id_evento"]);
        if($e->aggiornaEvento($_POST["dataDa"], $_POST["dataA"], $_POST["tipo"], $_POST["commento"], $_POST["priorita"],2))
            $messaggioSucc = "Aggiornamento eseguito con successo";
        else
            $messaggioErr = "non &egrave; stato possibile modificare l'evento";
    }

    if(isset($_GET["id_evento"]) && $_GET["azione"] == "visualizza"){
        $e = new Evento($_GET["id_evento"]);
        if($e->getStato() == 3)
            $stampaform = true;
        else
            $messaggioErr = "Questo evento non è stato segnalato";
    }
    $visTxt = '<a href="index.php?pagina=amministrazione&tab=gestione_segnalazioni&&azione=visualizza&&id_evento='; $visTxt2 = ' ">visualizza</a>';
    $prioTxt  = '<img src="./img/prio'; $prioTxt2 = '.png" />';
    $sql = "SELECT e.id_evento 'id', CONCAT('".$prioTxt."',e.priorita,'".$prioTxt2."') as '',d.username as Utente, c.nome as Nome,date_format(FROM_UNIXTIME(e.data_da),'%d.%m.%y-%H:%i') as Dal,date_format(FROM_UNIXTIME(e.data_a),'%d.%m.%y-%H:%i') as Al,e.commento as Commento, CONCAT('".$visTxt."',e.id_evento,'".$visTxt2."') as Visualizza FROM eventi e,causali c,dipendenti d WHERE c.id_motivo = e.fk_causale AND d.id_dipendente = e.fk_dipendente AND stato = 3 ORDER BY DATA_DA";

    $rs = Database::getInstance()->eseguiQuery($sql);
    echo "<table><tr>";
    if($rs->rowCount()>0) {
        echo "<td valign='top'>";
            Utilita::stampaTabella($rs,isset($e)?$e->getID():0);
        echo "</td>";
    }
    else
        $messaggioErr = "Non ci sono segnalazioni";

    if($stampaform) {
        echo "<td>";
            stampaFormModificaEvento($e);
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
                    <input id="sel1" class="calTextfield" type="textfield" name="dataDa" value="<?php echo date("d/m/Y - H:i",$e->getDataDa()); ?>" />
                </td>
                <td>
                    <input value="" type="reset" onclick="return showCalendar('sel1', '%d/%m/%Y - %H:%M');" class="imgCal" />
                </td>
            </tr>
            <tr>
                <td class="label">
                    A:
                </td>
                <td>
                    <input id="sel2" class="calTextfield" type="textfield" name="dataA" value="<?php echo date("d/m/Y - H:i",$e->getDataA()); ?>" />
                </td>
                <td>
                    <input value="" type="reset" onclick="return showCalendar('sel2', '%d/%m/%Y - %H:%M');" class="imgCal" />
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
                <td>
                    <input type="submit" name="azione" value="Elimina" class="bottCalendario" /> &nbsp;<input type="button" value="Annulla" class="bottCalendario" onclick="location.href = '?pagina=amministrazione&tab=gestione_segnalazioni'">
                </td>
                <td>
                    <input type="submit" name="azione" value="Salva" class="bottCalendario" />
                </td>
            </tr>
        </table>
    </form>
<?php
    }
?>