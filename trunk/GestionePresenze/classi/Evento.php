<?php
/**
 * Description of Evento
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */
class Evento {

     /**
     * Stampa il form per aggiungere assenze/vacanze/ecc...
     */
    static function stampaFormAggiungiEvento(){
        $mesi = array(1=>'Gennaio', 'Febbraio', 'Marzo', 'Aprile','Maggio', 'Giugno', 'Luglio', 'Agosto','Settembre', 'Ottobre', 'Novembre','Dicembre');
        $giorni = array(1=>'Luned&igrave','Marted&igrave','Mercoled&igrave','Gioved&igrave','Venerd&igrave','Sabato','Domenica');
        $ok = true;

        ?>
        <div class="aggiungiTaskContainer" id="sel">
            <form name="taskCalendario" action="#" method="POST">
                <table>
                    <tr>
                        <td class="cellaTitoloTask" colspan="2">
                            Nuovo Evento
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            Da:
                        </td>
                        <td>
                            <input id="sel1" class="calTextfield" type="textfield" name="dataDa" value="<?php
                            if(!$_POST)
                                echo trim(date("d",$_GET['data']).'/'.date("m",$_GET['data']).'/'.date("Y",$_GET['data']).' - 08:00');
                            else
                                echo $_POST['dataDa'];
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
                                    if(!Calendario::checkData($_POST['dataDa'],"",false) && $_POST){
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
                            if(!$_POST)
                                echo trim(date("d",$_GET['data']).'/'.date("m",$_GET['data']).'/'.date("Y",$_GET['data']).' - 08:30');
                            else
                                echo $_POST['dataA'];
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
                                    if(!Calendario::checkData($_POST['dataA'],"",false) && $_POST){
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
                                    if($rs->fields['r']==$_POST['tipo'])
                                        echo '<option selected="selected" value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                    else
                                        echo '<option value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                    $rs->MoveNext();
                                }
                             ?>
                            </select>
                            <div class="messaggioTaskErr">
                                <?php if($_POST && $_POST['tipo']==0){ $ok = false; echo "- Tipo non inserito<br/>"; }?>
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
                                    if($rs->fields['r']==$_POST['utente'])
                                        echo '<option selected="selected" value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                    else
                                        echo '<option value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                    $rs->MoveNext();
                                }
                             ?>
                            </select>
                            <div class="messaggioTaskErr">
                                <?php if($_POST && $_POST['utente']==0){ $ok = false; echo "- Utente non inserito<br/>"; }?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            Commento:
                        </td>
                        <td>
                            <input type="textflied" name="commento" value="<?php echo $_POST['commento']; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            Priorit&agrave:
                        </td>
                        <td>
                            <span class="superfluo"><input type="radio" name="etichetta" value="1" checked="checked" />1</span>
                            <?php $txt = ""; if($_POST['etichetta']==2) $txt = "checked='checked'"; ?>
                            <span class="ordinario"><input type="radio" name="etichetta" value="2" <?php echo $txt; ?> />2</span>
                            <?php $txt = ""; if($_POST['etichetta']==3) $txt = "checked='checked'"; ?>
                            <span class="importante"><input type="radio" name="etichetta" value="3" <?php echo $txt; ?> />3</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="messaggioTaskOk">
                            <?php
                                if($ok && $_POST){
                                    Evento::inserisciDatiEvento();
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="bottCalendario" type="button" onclick="location.href = '?pagina=home&data=' + <?php echo $_GET['data']; ?> + '&event=N'" value="Annulla" />
                        </td>
                        <td>
                            <input class="bottCalendario" type="submit" value="Crea" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    <?php
    }

     /**
     * Inserisce i dati dell'evento in POST nel DataBase
     */
    static function inserisciDatiEvento(){
        $sql =  "insert into eventi(data_da,data_a,fk_dipendente,fk_causale,commento,priorita) ";
        $sql .= "values (".Calendario::getTimestamp($_POST['dataDa']).",".Calendario::getTimestamp($_POST['dataA']).",".$_POST['utente'].",".$_POST['tipo'].",'".$_POST['commento']."',".$_POST['etichetta'].");";

        if (Database::getInstance()->getConnection()->execute($sql) === false) {
            echo 'Error inserting: '.$conn->ErrorMsg().'<BR>';
        }
        else{
            echo "Evento salvato con successo !";
        }
        $href = "index.php?pagina=home&data=".$_GET['data'];
        ?>
        <script language="javascript" type="text/javascript">
            window.setTimeout("redirect('<?php echo $href ?>')",1000);
        </script>
        <?php
    }


     /**
     * Stampa un report contenente gli eventi del giorno selezionato
     */
    static function stampaReportEventi(){
        $dataGiorno = $_GET['data'];
        $da = mktime(23, 59, 59, date("n",$dataGiorno), date("j",$dataGiorno), date("Y",$dataGiorno));
        $a  = mktime(0, 0, 0, date("n",$dataGiorno), date("j",$dataGiorno), date("Y",$dataGiorno));
        $preTxt = '<a href="#" onclick="setItem(\'id_evento\',';
        $postTxt = ')"><img border="0" src="./img/modifica.png" /></a>';

        $sql = "SELECT CONCAT(".$preTxt.",e.id_evento,".$linkTxt.") as Edit, c.nome as Nome,d.username as Utente,date_format(FROM_UNIXTIME(e.data_da),'%d.%m.%y-%H:%i') as Dal,date_format(FROM_UNIXTIME(e.data_a),'%d.%m.%y-%H:%i') as Al,e.priorita as Prt,e.commento as Commento FROM eventi e,causali c,dipendenti d WHERE DATA_DA <= ".$da." and DATA_A >= ".$a." and c.id_motivo = e.fk_causale and d.id_dipendente = e.fk_dipendente ORDER BY DATA_DA";
        $rs = Database::getInstance()->eseguiQuery($sql);
        if($rs->fields){
            echo '<p class="cellaTitoloTask">Eventi di oggi</p>';
            Utilita::stampaTabella($rs);
        }
    }
}
?>