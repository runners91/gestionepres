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
    static function stampaFormEvento(){
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
            <?php
                if(Autorizzazione::gruppoAmministrazione($_SESSION["username"]) || $_SESSION["id_utente"] == $evt->getDipendente())
                    stampaEvento::stampaFormAggiungiEvento($evt);
                else
                    stampaEvento::stampaVisualizzaEvento($evt);
            ?>
        </div>
    <?php
    }
    static function stampaFormAggiungiEvento($evt){ ?>
        <form name="taskCalendario" action="#" method="POST">
                <input type="hidden" name="stato" value="<?php echo Autorizzazione::gruppoAmministrazione($_SESSION["username"])?"2":"1";?>"/>
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
                            <input id="sel1" <?php echo isset($evt->errori["data_da"]) && Utilita::eseguiControlliFormEvento()?"class='errore'":""; ?> type="textfield" name="dataDa" value="<?php
                            if(!isset($_POST['action']) && !$evt->getID())
                                echo trim(date("d",$data).'.'.date("m",$data).'.'.date("Y",$data));
                            else if(isset($evt->errori["data_da"]))
                                echo "";
                            else
                                echo date("d.m.Y",$evt->getDataDa());
                            ?>" />
                        </td>
                        <td>
                            <input value="" type="reset" onclick="return showCalendar('sel1', '%d.%m.%Y');" class="imgCal" />
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
                            <input id="sel2" <?php echo isset($evt->errori["data_a"]) && Utilita::eseguiControlliFormEvento()?"class='errore'":""; ?> type="textfield" name="dataA" value="<?php
                            if(!isset($_POST['action']) && !$evt->getID())
                                echo trim(date("d",$data).'.'.date("m",$data).'.'.date("Y",$data));
                            else if(isset($evt->errori["data_a"]))
                                echo "";
                            else
                                echo date("d.m.Y",$evt->getDataA());
                            ?>" />
                        </td>
                        <td>
                            <input value="" type="reset" onclick="return showCalendar('sel2', '%d.%m.%Y');" class="imgCal" />
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
                            <select name="tipo" class="selectField<?php echo isset($evt->errori["fk_causale"])&& Utilita::eseguiControlliFormEvento()?" errore":""; ?>">
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
                        <td valign="top">
                            <select name="durata">
                                <option value="G" selected="selected">Giornata</option>
                                <?php $txt = ""; if($evt->getDurata()=="M") $txt = "selected='selected'"; ?>
                                <option value="M" <?php echo $txt; ?>>Mattina</option>
                                <?php $txt = ""; if($evt->getDurata()=="P") $txt = "selected='selected'"; ?>
                                <option value="P" <?php echo $txt; ?>>Pomeriggio</option>
                            </select>
                        </td>
                    </tr>
                    <?php if(Autorizzazione::gruppoAmministrazione($_SESSION["username"])) {?>
                        <tr>
                            <td class="label">
                                Utente:
                            </td>
                            <td>
                                <select name="utente" class="selectField<?php echo isset($evt->errori["fk_dipendente"])&& Utilita::eseguiControlliFormEvento()?" errore":""; ?>">
                                    <option value="0">-</option>
                                 <?php
                                    if(Autorizzazione::gruppoAmministrazione($_SESSION['username']))
                                        $rs = Database::getInstance()->eseguiQuery("SELECT d.username as d, d.id_dipendente as r FROM dipendenti d");
                                    else
                                        $rs = Database::getInstance()->eseguiQuery("select d.id_dipendente r,d.username d from dipendenti d,dipendenti_gruppi dg where d.id_dipendente = dg.fk_dipendente and dg.fk_gruppo in ( select dg2.fk_gruppo from dipendenti_gruppi dg2, dipendenti d2 where d2.id_dipendente = dg2.fk_dipendente and d2.username = ? )",array($_SESSION['username']));
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
                    <?php }?>
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
                            <span class="prio1"><input type="radio" name="priorita" value="1" checked="checked" />1</span>
                            <?php $txt = ""; if($evt->getPriorita()==2) $txt = "checked='checked'"; ?>
                            <span class="prio2"><input type="radio" name="priorita" value="2" <?php echo $txt; ?> />2</span>
                            <?php $txt = ""; if($evt->getPriorita()==3) $txt = "checked='checked'"; ?>
                            <span class="prio3"><input type="radio" name="priorita" value="3" <?php echo $txt; ?> />3</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="messaggioTaskOk">
                            <?php
                                if(isset($_POST['action'])){
                                    if($_POST['action']=="inserisci"){
                                        if($evt->inserisciEvento()){
                                            echo "Evento inserito con successo";
                                            Utilita::reload();
                                        }
                                    }
                                    else if($_POST['action']=="aggiorna"){
                                        if($evt->aggiornaEvento()){
                                            echo "Evento aggiornato con successo";
                                            //Utilita::reload();
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
    <?php }

    static function stampaVisualizzaEvento($evt){
        ?>
        <div class="aggiungiEventoContainer" id="sel">
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
                        <?php
                        if(!isset($_POST['action']) && !$evt->getID())
                            echo trim(date("d",$data).'.'.date("m",$data).'.'.date("Y",$data));
                        else if(isset($evt->errori["data_da"]))
                            echo "";
                        else
                            echo date("d.m.Y",$evt->getDataDa());
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        A:
                    </td>
                    <td>
                        <?php
                            echo date("d.m.Y",$evt->getDataA());
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        Tipo:
                    </td>
                    <td>
                        <?php
                            $rs = Database::getInstance()->eseguiQuery("SELECT nome FROM causali WHERE id_motivo = ?",array($evt->getCausale()));
                            echo $rs->fields["nome"];
                         ?>
                    </td>
                    <td valign="top">
                        <?php
                            if($evt->getDurata() == "M")
                                echo "Mattina";
                            else if($evt->getDurata() == "P")
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
                            $rs = Database::getInstance()->eseguiQuery("SELECT username FROM dipendenti WHERE id_dipendente = ?",array($evt->getDipendente()));
                            echo $rs->fields["username"];
                         ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        Commento:
                    </td>
                    <td>
                        <?php echo $evt->getCommento(); ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        Priorit&agrave:
                    </td>
                    <td>
                        <?php echo $evt->getPriorita(); ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php
    }


     /**
     * Stampa un report contenente gli eventi del giorno selezionato
     */
    static function stampaReportEventi(){
        $dataGiorno = Utilita::getDataHome();
        $visualizza = 6;
        $prio    = Utilita::getValoreFiltro($_GET['prio']);
        $utente  = Utilita::getValoreFiltro($_GET['utn']);
        $filiale = Utilita::getValoreFiltro($_GET['filiale']);
        $tipo    = Utilita::getValoreFiltro($_GET['tipo']);
        $da = mktime(23, 59, 59, date("n",$dataGiorno), date("j",$dataGiorno), date("Y",$dataGiorno));
        $a  = mktime(0, 0, 0, date("n",$dataGiorno), date("j",$dataGiorno), date("Y",$dataGiorno));
        $sql = "SELECT count(*) as totEventi FROM eventi e,causali c,dipendenti d,filiali f WHERE DATA_DA <= ? and DATA_A >= ? and c.id_motivo = e.fk_causale and d.fk_filiale = f.id_filiale and d.id_dipendente = e.fk_dipendente and (e.fk_causale = ? or ? = 0 ) and (e.priorita = ? or ? = 0 ) and (e.fk_dipendente = ? or ? = 0 ) and (d.fk_filiale = ? or ? = 0 )";
        $rs = Database::getInstance()->eseguiQuery($sql,array($da,$a,$tipo,$tipo,$prio,$prio,$utente,$utente,$filiale,$filiale));
        $minRiga = Utilita::getNewMinRiga($_POST['codPag'],$_POST['minRiga'],$rs->fields["totEventi"],$visualizza);

        $editTxt  = '<a alt="edit" href="'.Utilita::getHomeUrlFiltri().'&data='.$dataGiorno.'&id_evento='; $editTxt2 = '&minrg='.$minRiga.'"><img border="0" src="./img/modifica.png" /></a>';
        $cnfTxt   = '<a alt="conferma" href="?pagina=amministrazione&tab=gestione_segnalazioni&azione=visualizza&id_evento='; $cnfTxt2 = '">Conferma</a>';
        $prioTxt  = '<img src="./img/prio'; $prioTxt2 = '.png" />';
        $sql = "SELECT e.id_evento as id, CONCAT(?,e.priorita,?) as ' ', CONCAT(?,e.id_evento,?) as Edit, c.nome as Causale,d.username as Utente,date_format(FROM_UNIXTIME(e.data_da),'%d.%m.%y') as Dal,date_format(FROM_UNIXTIME(e.data_a),'%d.%m.%y') as Al,f.nome as Filiale,CASE WHEN e.stato = 1 THEN 'Richiesto' WHEN e.stato = 2 THEN 'Accettato' ELSE CONCAT(?,e.id_evento,?) END as Stato,e.commento as Commento FROM eventi e,causali c,dipendenti d,filiali f WHERE DATA_DA <= ? and DATA_A >= ? and c.id_motivo = e.fk_causale and d.fk_filiale = f.id_filiale and d.id_dipendente = e.fk_dipendente and (e.fk_causale = ? or ? = 0 ) and (e.priorita = ? or ? = 0 ) and (e.fk_dipendente = ? or ? = 0 ) and (d.fk_filiale = ? or ? = 0 ) ORDER BY e.priorita DESC,e.data_da,c.nome,d.username";
        $rs = Database::getInstance()->eseguiQuery($sql,array($prioTxt,$prioTxt2,$editTxt,$editTxt2,$cnfTxt,$cnfTxt2,$da,$a,$tipo,$tipo,$prio,$prio,$utente,$utente,$filiale,$filiale));
        if($rs->fields){
            echo '<p class="cellaTitoloTask">'.stampaEvento::getTitoloReport($dataGiorno).'</p>';
            Utilita::stampaTabella($rs, $_GET["id_evento"],$visualizza);
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
