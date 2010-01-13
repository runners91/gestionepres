<?php
/**
 * Description of stampaEvento
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */
class stampaEvento {

     /**
     * Stampa il form per aggiungere assenze/vacanze/ecc...
     */
    static function stampaFormAggiungiEvento(){
        $mesi = array(1=>'Gennaio', 'Febbraio', 'Marzo', 'Aprile','Maggio', 'Giugno', 'Luglio', 'Agosto','Settembre', 'Ottobre', 'Novembre','Dicembre');
        $giorni = array(1=>'Luned&igrave','Marted&igrave','Mercoled&igrave','Gioved&igrave','Venerd&igrave','Sabato','Domenica');
        $data = Utilita::getDataHome();
        $evt = new Evento();

        if(isset($_GET['id_evento']) && !isset($_POST['action'])){
            $evt->getValoriDB($_GET['id_evento']);
        }
        else{
            $evt->getValoriPost();
        }    
        ?>
        <div class="aggiungiEventoContainer" id="sel">
            <form name="taskCalendario" action="#" method="POST">
                <table>
                    <tr>
                        <td class="cellaTitoloTask" colspan="2">
                            <?php echo $evt->getTitoloForm(); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            Da:
                        </td>
                        <td>
                            <input id="sel1" <?php echo isset($evt->errori["data_da"])?"class='errore'":""; ?> type="textfield" name="dataDa" value="<?php
                            if(!$evt->getID())
                                echo trim(date("d",$data).'/'.date("m",$data).'/'.date("Y",$data).' - 08:00');
                            else
                                echo date("d/m/Y - H:i",$evt->getDataDa());
                            ?>" />
                        </td>
                        <td>
                            <input value="" type="reset" onclick="return showCalendar('sel1', '%d/%m/%Y - %H:%M');" class="imgCal" />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2">
                            <span class="messaggioErrore"><?php if(Utilita::eseguiControlliFormEvento()) echo $evt->errori["data_da"]; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            A:
                        </td>
                        <td>
                            <input id="sel2" <?php echo isset($evt->errori["data_a"])?"class='errore'":""; ?> type="textfield" name="dataA" value="<?php
                            if(!$evt->getID())
                                echo trim(date("d",$data).'/'.date("m",$data).'/'.date("Y",$data).' - 08:30');
                            else
                                echo date("d/m/Y - H:i",$evt->getDataA());
                            ?>" />
                        </td>
                        <td>
                            <input value="" type="reset" onclick="return showCalendar('sel2', '%d/%m/%Y - %H:%M');" class="imgCal" />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2">
                            <span class="messaggioErrore"><?php if(Utilita::eseguiControlliFormEvento()) echo $evt->errori["data_a"]; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            Tipo:
                        </td>
                        <td>
                            <select name="tipo" class="selectField<?php echo isset($evt->errori["fk_causale"])?" errore":""; ?>">
                                <option value="0">-</option>
                             <?php
                                $rs = Database::getInstance()->eseguiQuery("SELECT c.nome as d, c.id_motivo as r FROM causali c");
                                while(!$rs->EOF){
                                    if($rs->fields['r']==$evt->getCausale())
                                        echo '<option selected="selected" value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                    else
                                        echo '<option value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                    $rs->MoveNext();
                                }
                             ?>
                            </select><br />
                            <div class="messaggioErrore"><?php if(Utilita::eseguiControlliFormEvento()) echo $evt->errori["fk_causale"]; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            Utente:
                        </td>
                        <td>
                            <select name="utente" class="selectField<?php echo isset($evt->errori["fk_dipendente"])?" errore":""; ?>">
                                <option value="0">-</option>
                             <?php
                                $rs = Database::getInstance()->eseguiQuery("SELECT d.username as d, d.id_dipendente as r FROM dipendenti d");
                                while(!$rs->EOF){
                                    if($rs->fields['r']==$evt->getDipendente())
                                        echo '<option selected="selected" value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                    else
                                        echo '<option value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                    $rs->MoveNext();
                                }
                             ?>
                            </select><br />
                            <div class="messaggioErrore"><?php if(Utilita::eseguiControlliFormEvento()) echo $evt->errori["fk_dipendente"]; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            Commento:
                        </td>
                        <td>
                            <input type="textflied" name="commento" value="<?php echo $evt->getCommento(); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            Priorit&agrave:
                        </td>
                        <td>
                            <span class="prio1"><input type="radio" name="etichetta" value="1" checked="checked" />1</span>
                            <?php $txt = ""; if($evt->getPriorita()==2) $txt = "checked='checked'"; ?>
                            <span class="prio2"><input type="radio" name="etichetta" value="2" <?php echo $txt; ?> />2</span>
                            <?php $txt = ""; if($evt->getPriorita()==3) $txt = "checked='checked'"; ?>
                            <span class="prio3"><input type="radio" name="etichetta" value="3" <?php echo $txt; ?> />3</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="messaggioTaskOk">
                            <?php
                                if(Utilita::eseguiControlliFormEvento(true) && isset($_POST['action'])){
                                    if($_POST['action']=="inserisci"){
                                        if($evt->inserisciEvento()){
                                            echo "Evento inserito con successo";
                                            Utilita::reload();
                                        }
                                    }
                                    else if($_POST['action']=="aggiorna"){
                                        if($evt->aggiornaEvento()){
                                            echo "Evento aggiornato con successo";
                                            Utilita::reload();
                                        }
                                    }
                                    else if($_POST['action']=="elimina"){
                                        if($evt->eliminaEvento()){
                                            echo "Evento eliminato con successo";
                                            Utilita::reload();
                                        }
                                    }
                                }
                            ?>
                            <span class="messaggioErrore"><?php if(Utilita::eseguiControlliFormEvento()) echo $evt->errori["processi"]; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <?php if(!isset($_POST['action']) || sizeof($evt->errori)>0){ ?>
                            <td colspan="3">
                                <input id="action" type="hidden" name="action" />
                                <input class="bottCalendario" type="button" onclick="redirect('<?php echo Utilita::getHomeUrlCompleto(); ?>')" value="Annulla" />
                            <?php if($evt->getID()){ ?>
                                <input class="bottCalendario" type="submit" value="Elimina" onclick="document.getElementById('action').value='elimina'" />
                            <?php } ?>
                            <?php if($evt->getID()){ ?>
                                <input class="bottCalendario" type="submit" value="Salva" onclick="document.getElementById('action').value='aggiorna';" />
                            <?php } ?>
                            <?php if(!$evt->getID()){ ?>
                                <input class="bottCalendario" type="submit" value="Crea" onclick="document.getElementById('action').value='inserisci'" />
                            <?php } ?>
                            </td>
                        <?php } ?>
                    </tr>
                </table>
            </form>
        </div>
    <?php
    }


     /**
     * Stampa un report contenente gli eventi del giorno selezionato
     */
    static function stampaReportEventi(){
        $dataGiorno = Utilita::getDataHome();
        $da = mktime(23, 59, 59, date("n",$dataGiorno), date("j",$dataGiorno), date("Y",$dataGiorno));
        $a  = mktime(0, 0, 0, date("n",$dataGiorno), date("j",$dataGiorno), date("Y",$dataGiorno));
        $editTxt  = '<a alt="ciao" href="'.Utilita::getHomeUrlFiltri().'&data='.$dataGiorno.'&id_evento='; $editTxt2 = '"><img border="0" src="./img/modifica.png" /></a>';
        $prioTxt  = '<img src="./img/prio'; $prioTxt2 = '.png" />';
        
        $prio    = Utilita::getValoreFiltro($_GET['prio']);
        $utente  = Utilita::getValoreFiltro($_GET['utn']);
        $filiale = Utilita::getValoreFiltro($_GET['filiale']);
        $tipo    = Utilita::getValoreFiltro($_GET['tipo']);

        $sql = "SELECT e.id_evento as id, CONCAT('".$prioTxt."',e.priorita,'".$prioTxt2."') as ' ', CONCAT('".$editTxt."',e.id_evento,'".$editTxt2."') as Edit, c.nome as Causale,d.username as Utente,date_format(FROM_UNIXTIME(e.data_da),'%d.%m.%y-%H:%i') as Dal,date_format(FROM_UNIXTIME(e.data_a),'%d.%m.%y-%H:%i') as Al,f.nome as Filiale,e.commento as Commento FROM eventi e,causali c,dipendenti d,filiali f WHERE DATA_DA <= ".$da." and DATA_A >= ".$a." and c.id_motivo = e.fk_causale and d.fk_filiale = f.id_filiale and d.id_dipendente = e.fk_dipendente and (e.fk_causale = ".$tipo." or ".$tipo." = 0 ) and (e.priorita = ".$prio." or ".$prio." = 0 ) and (e.fk_dipendente = ".$utente." or ".$utente." = 0 ) and (d.fk_filiale = ".$filiale." or ".$filiale." = 0 ) ORDER BY e.priorita DESC,e.data_da,c.nome,d.username";
        $rs = Database::getInstance()->eseguiQuery($sql);
        if($rs->fields){
            echo '<p class="cellaTitoloTask">'.stampaEvento::getTitoloReport($dataGiorno).'</p>';
            Utilita::stampaTabella($rs, $_GET["id_evento"]);
        }
    }

    /**
     *  Ritorna il titolo del report per la stampa degli eventi di un dato giorno
     */
    static function getTitoloReport($data){
        if(date("dmY",time())==date("dmY",$data))
            return "Eventi di Oggi - ".date("d.m.Y",$data);
        else if(date("dmY",(time()+86400))==date("dmY",$data))
            return "Eventi di Domani - ".date("d.m.Y",$data);
        else if(date("dmY",(time()-86400))==date("dmY",$data))
            return "Eventi di Ieri - ".date("d.m.Y",$data);
        else
            return "Eventi del ".date("d.m.Y",$data);
    }
}
?>
