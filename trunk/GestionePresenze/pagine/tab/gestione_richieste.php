<?php
    $stampaform = false;
    $e = new Evento();
    if(isset($_GET['id_evento']) && !isset($_POST['azione'])){
        $e->getValoriDB($_GET['id_evento']);
    }
    else{
        $e->getValoriPost();
    }

    if($_POST["azione"] == "Rifiuta"){
        if($e->eliminaEvento())
            $messaggioSucc = "l'evento &egrave; stato eliminato";
            //mail con avviso all'utente?
    }
    else if($_POST["azione"] == "Accetta"){
        if($e->aggiornaStato(2))
            $messaggioSucc = "L'evento &egrave; stato accettato con successo";
        else {
            $messaggioErr = "non &egrave; stato possibile accettare l'evento";
            $stampaform = true;
        }
    }

    if(isset($_GET["id_evento"]) && $_GET["azione"] == "visualizza"){
        if($e->getStato() == 1)
            $stampaform = true;
        else
            $messaggioErr = "Questo evento non è stato richiesto";
    }

    $sql = "SELECT count(*) as totEventi FROM eventi e,causali c,dipendenti d WHERE c.id_motivo = e.fk_causale AND d.id_dipendente = e.fk_dipendente AND stato = 1 ORDER BY DATA_DA";
    $rs = Database::getInstance()->eseguiQuery($sql);

    $minRiga = Utilita::getNewMinRiga($_POST['codPag'],$_POST['minRiga']);
    $visTxt = '<a href="index.php?pagina=amministrazione&tab=gestione_richieste&azione=visualizza&minrg='.$minRiga.'&id_evento='; $visTxt2 = ' "><img border="0" src="img/modifica.png" /></a>';
    $prioTxt  = '<img src="./img/prio'; $prioTxt2 = '.png" />';

    $sql = "SELECT e.id_evento 'id', CONCAT(?,e.priorita,?) as '',d.username as Utente, c.nome as Nome,date_format(FROM_UNIXTIME(e.data_da),'%d.%m.%y-%H:%i') as Dal,date_format(FROM_UNIXTIME(e.data_a),'%d.%m.%y-%H:%i') as Al,e.commento as Commento, CONCAT(?,e.id_evento,?) as Edit FROM eventi e,causali c,dipendenti d WHERE c.id_motivo = e.fk_causale AND d.id_dipendente = e.fk_dipendente AND stato = 1 ORDER BY DATA_DA";
    $rs = Database::getInstance()->eseguiQuery($sql,array($prioTxt,$prioTxt2,$visTxt,$visTxt2));
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
    }
    else
        echo '<td style="width:30px;"></td><td valign="top" ><div class="messaggioErrore">'.$messaggioErr.'</div> <div class="messaggioTaskOk">'.$messaggioSucc.'</div></td>';

    echo "</tr></table>";

    function stampaFormModificaEvento($e) {

?>
    <form action="index.php?pagina=amministrazione&tab=gestione_richieste" method="POST">
        <input type="hidden" name="id_evento" value="<?php echo $e->getID(); ?>">
        <input type="hidden" name="utente" value="<?php echo $e->getDipendente(); ?>">
        <table>
            <tr>
                <td class="cellaTitoloTask" colspan="2">
                    Accetta Evento:
                </td>
            </tr>
            <tr>
                <td class="label">
                Da:
                </td>
                <td>
                    <?php echo date("d.m.Y",$e->getDataDa()); ?>
                </td>
            </tr>
            <tr>
                <td class="label">
                    A:
                </td>
                <td>
                    <?php echo date("d.m.Y",$e->getDataA()); ?>
                </td>
            </tr>
            <tr>
                <td class="label">
                    Tipo:
                </td>
                <td>
                    <?php
                        $rs = Database::getInstance()->eseguiQuery("SELECT nome FROM causali WHERE id_motivo = ?",array($e->getCausale()));
                        echo $rs->fields["nome"];
                    ?>
                </td>
                <td class="label">
                    Durata:
                </td>
                <td valign="top">
                    <?php
                        if($e->getDurata() == "M")
                            echo "Mattina";
                        else if($e->getDurata() == "P")
                            echo "Pomeriggio";
                        else
                            echo "Giorno";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="label">
                    Utente:
                </td>
                <td>
                     <?php
                        $rs = Database::getInstance()->eseguiQuery("SELECT username FROM dipendenti WHERE id_dipendente = ?",array($e->getDipendente()));
                        echo $rs->fields["username"];
                     ?>
                </td>
            </tr>
            <tr>
                <td class="label">
                    Commento:
                </td>
                <td>
                    <?php echo $e->getCommento(); ?>
                </td>
            </tr>
            <tr>
                <td class="label">
                    Priorit&agrave:
                </td>
                <td>
                    <?php echo $e->getPriorita(); ?>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <input type="button" value="Annulla" class="bottCalendario" onclick="location.href = '?pagina=amministrazione&tab=gestione_richieste'"> &nbsp;<input type="submit" name="azione" value="Rifiuta" class="bottCalendario" /> &nbsp;<input type="submit" name="azione" value="Accetta" class="bottCalendario" />
                </td>
            </tr>
        </table>
    </form>
<?php
    }
?>