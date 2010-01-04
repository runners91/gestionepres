<?php
/**
 * Description of Calendario
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */

class Calendario {

    function __contruct(){

    }

     /**
     * Stampa il calendario
     * @param m indica lo spostamento di mese (+1/-1) di default è 0
     */
    static function stampaCalendario($m = 0){
        date_default_timezone_set("Europe/Zurich");

        if($m=="r"){
            $anno = date("Y",time());
            $mese = date("n",time());
        }
        else if(isset($_POST['anno']) && isset($_POST['mese'])){
            $anno = $_POST['anno'];
            $mese = $_POST['mese'] + $m;
        }
        else if(isset($_GET['data'])){
            $anno = date("Y",$_GET['data']);
            $mese = date("n",$_GET['data']) + $m;
        }
        else{
            $anno = date("Y",time());
            $mese = date("n",time());
        }
        $data = mktime(0,0,0,$mese,1,$anno);

        $mesi = array(1=>'Gennaio', 'Febbraio', 'Marzo', 'Aprile','Maggio', 'Giugno', 'Luglio', 'Agosto','Settembre', 'Ottobre', 'Novembre','Dicembre');
        $giorni = array(1=>'Luned&igrave','Marted&igrave','Mercoled&igrave','Gioved&igrave','Venerd&igrave','Sabato','Domenica');
     ?>
    <div id="calendario">
        <form name="calendario" action="?pagina=home" method="POST">
            <table>
                <tr>
                    <td>
                    </td>
                    <td colspan="7" class="parCalendario">
                        <table>
                            <tr>
                                <td>
                                    <input class="bottCalendario" type="submit" value="<" onmousedown="document.getElementById('m').value=-1" />
                                </td>
                                <td class="cellaMese">
                                    <input type="hidden" id="m" name="m" />
                                    <?php echo $mesi[date("n",$data)]." ".date("Y",$data); ?>
                                </td>
                                <td>
                                    <input class="bottCalendario" type="submit" value=">" onmousedown="document.getElementById('m').value=1" />
                                </td>
                                <td class="cellaSpazio">

                                </td>
                                <td>
                                    <select class="selectCalendario" name="anno"><?php
                                    for($i=1990;$i<2020;$i++){
                                        if(date("Y",$data)==$i)
                                            echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                        else
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                    }
                                    ?></select>
                                </td>
                                <td>
                                    <select class="selectCalendario" name="mese"><?php
                                        foreach ($mesi as $key => $value) {
                                            if(date("n",$data)==$key)
                                                echo '<option value="'.$key.'" selected>'.$value.'</option>';
                                            else
                                                echo '<option value="'.$key.'">'.$value.'</option>';
                                        }
                                    ?></select>
                                </td>
                                <td>
                                    <input class="bottCalendario" type="submit" value="Vai" />
                                </td>
                                <td>
                                    <input class="bottCalendario" type="button" value="Oggi" onclick="location.href = '?pagina=home'" />
                                </td>
                            </tr>
                        </table>
                </tr>
                <tr height="20"></tr>
                <tr>
                    <td>

                    </td><?php
                        foreach($giorni as $value){
                           echo '<td class="cellaGiorno">';
                              echo $value;
                           echo '</td>';
                        }
                        ?>
                </tr>
                <?php
                    $nrGiorno = 1-date("N",$data);
                    for(;;){
                        echo '<tr>';
                            for($j=$nrGiorno;$j<=($nrGiorno+7);$j++){
                                $dataGiorno = mktime(0,0,0,date("n",$data),$j,date("Y",$data));
                                if($j==$nrGiorno){
                                    echo '<td class="cellaSettimana">';
                                        echo "Settimana ".date("W",mktime(0,0,0,date("n",$data),$j+7,date("Y",$date)));
                                    echo '</td>';
                                }
                                else{
                                    if(date("j-n-o",time())==date("j-n-o",$dataGiorno))
                                        echo '<td class="cellaData cellaDataOggi">';
                                    else if(date("j-n-o",$_GET['data'])==date("j-n-o",$dataGiorno))
                                        echo '<td class="cellaData cellaDataSelezionata">';
                                    else if(date("n",$dataGiorno)!=date("n",$data))
                                        echo '<td class="cellaData cellaDataGrigia">';
                                    else
                                        echo '<td class="cellaData">';

                                    echo '<a class="linkGiorno" href="?pagina=home&data='.$dataGiorno.'&event=Y">'.date("d",$dataGiorno).'</a>';
                                    echo '</td>';
                                }
                            }
                            $nrGiorno += 7;
                        echo '</tr>';
                        if(date("n",mktime(0,0,0,date("n",$data),$j+1,date("Y",$data)))!=date("n",$data) && $j>7)
                            break;
                    }
                ?>
            </table>
        </form>
    </div>
        <?php

    }

     /**
     * Stampa il form per aggiungere assenze/vacanze/ecc...
     */
    static function stampaFormAggiungiTask(){
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
                        <td>
                            Da:
                        </td>
                        <td>
                            <input id="sel1" class="calTextfield" type="textfield" name="dataDa" value="<?php
                            if(!$_POST)
                                echo trim(date("d",$_GET['data']).'/'.date("n",$_GET['data']).'/'.date("Y",$_GET['data']).' - 08:00');
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
                                    if(!Utilita::checkData($_POST['dataDa'],"",false) && $_POST){
                                        $ok = false;
                                        echo Utilita::checkData($_POST['dataDa'],"Da:",true);
                                    }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            A:
                        </td>
                        <td>
                            <input id="sel2" class="calTextfield" type="textfield" name="dataA" value="<?php
                            if(!$_POST)
                                echo trim(date("d",$_GET['data']).'/'.date("n",$_GET['data']).'/'.date("Y",$_GET['data']).' - 08:30');
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
                                    if(!Utilita::checkData($_POST['dataA'],"",false) && $_POST){
                                        $ok = false;
                                        echo Utilita::checkData($_POST['dataA'],"A:",true);
                                    }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Tipo:
                        </td>
                        <td>
                            <select name="tipo" class="selectField">
                                <option value="0">-</option>
                             <?php
                                $rs = Database::getInstance()->eseguiQuery("SELECT e.nome as d, e.id_evento as r FROM eventi e");
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
                        <td>
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
                        <td>
                            Importanza:
                        </td>
                        <td>
                            <span class="superfluo"><input type="radio" name="etichetta" value="1" checked="checked" />1</span>
                            <span class="ordinario"><input type="radio" name="etichetta" value="2" />2</span>
                            <span class="importante"><input type="radio" name="etichetta" value="3" />3</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="messaggioTaskOk">
                            <?php if($ok && $_POST) echo "Evento salvato con successo !"; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="bottCalendario spazioTop" type="button" onclick="location.href = '?pagina=home&data=' + <?php echo $_GET['data']; ?> + '&event=N'" value="Annulla" />
                        </td>
                        <td>
                            <input class="bottCalendario spazioTop" type="submit" value="Salva" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    <?php
    }

}
?>
