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
        $ok = true;
        $evt = new Evento($_GET['id_evento']);
        $data = Utilita::getDataHome();
        ?>
        <div class="aggiungiTaskContainer" id="sel">
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
                            <input id="sel1" class="calTextfield" type="textfield" name="dataDa" value="<?php
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
                            <div class="messaggioTaskErr">
                                <?php
                                    if(!Calendario::checkData($_POST['dataDa'],"",false) && Utilita::eseguiControlliFormEvento()){
                                        $ok = false;
                                        echo Calendario::checkData($_POST['dataDa'],"Da:",true);
                                    }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            A:
                        </td>
                        <td>
                            <input id="sel2" class="calTextfield" type="textfield" name="dataA" value="<?php
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
                            <div class="messaggioTaskErr">
                                <?php
                                    if(!Calendario::checkData($_POST['dataA'],"",false) && Utilita::eseguiControlliFormEvento()){
                                        $ok = false;
                                        echo Calendario::checkData($_POST['dataA'],"A:",true);
                                    }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            Tipo:
                        </td>
                        <td>
                            <select name="tipo" class="selectField">
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
                            </select>
                            <div class="messaggioTaskErr">
                                <?php if($_POST['tipo']==0 && Utilita::eseguiControlliFormEvento()){ $ok = false; echo "- Tipo non inserito<br/>"; }?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            Utente:
                        </td>
                        <td>
                            <select name="utente" class="selectField">
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
                            </select>
                            <div class="messaggioTaskErr">
                                <?php if($_POST['utente']==0 && Utilita::eseguiControlliFormEvento()){ $ok = false; echo "- Utente non inserito<br/>"; }?>
                            </div>
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
                                if($ok && Utilita::eseguiControlliFormEvento()){
                                    $evt->inserisciDatiEvento();
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="bottCalendario" type="button" onclick="location.href = '?pagina=home&data=' + <?php echo $_GET['data']; ?> + '&event=N'" value="Annulla" />
                        </td>
                        <td>
                            <input class="bottCalendario" type="submit" value="<?php echo $evt->getNomeBottone() ?>" />
                        </td>
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
        $tipo    = Utilita::getValoreFiltro($_GET['tipo']);

        $sql = "SELECT CONCAT('".$prioTxt."',e.priorita,'".$prioTxt2."') as ' ', CONCAT('".$editTxt."',e.id_evento,'".$editTxt2."') as Edit, c.nome as Nome,d.username as Utente,date_format(FROM_UNIXTIME(e.data_da),'%d.%m.%y-%H:%i') as Dal,date_format(FROM_UNIXTIME(e.data_a),'%d.%m.%y-%H:%i') as Al,e.commento as Commento FROM eventi e,causali c,dipendenti d WHERE DATA_DA <= ".$da." and DATA_A >= ".$a." and c.id_motivo = e.fk_causale and d.id_dipendente = e.fk_dipendente and (e.fk_causale = ".$tipo." or ".$tipo." = 0 ) and (e.priorita = ".$prio." or ".$prio." = 0 ) and (e.fk_dipendente = ".$utente." or ".$utente." = 0 ) ORDER BY DATA_DA";
        $rs = Database::getInstance()->eseguiQuery($sql);
        if($rs->fields){
            echo '<p class="cellaTitoloTask">Eventi del '.date("d.m.Y",$dataGiorno).'</p>';
            Utilita::stampaTabella($rs);
        }
    }
}
?>