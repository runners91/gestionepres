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
     *  Restituisce la data che il calendario utilizza per essere visualizzato correttamente
     */
    static function getCalData($m){
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

        return $data;
    }

    /**
     *  Stampa i parametri del calendario
     */
    static function stampaParametriCalendario($m = 0){
        $data = Calendario::getCalData($m);
        $mesi = array(1=>'Gennaio', 'Febbraio', 'Marzo', 'Aprile','Maggio', 'Giugno', 'Luglio', 'Agosto','Settembre', 'Ottobre', 'Novembre','Dicembre');
        $giorni = array(1=>'Luned&igrave','Marted&igrave','Mercoled&igrave','Gioved&igrave','Venerd&igrave','Sabato','Domenica');
        ?>
        <form name="calendarioNav" action="?pagina=home" method="POST">
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
                        <select class="selectCalendario" name="anno" onchange="submit()"><?php
                            for($i=1990;$i<2020;$i++){
                                if(date("Y",$data)==$i)
                                    echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                else
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                        ?></select>
                    </td>
                    <td>
                        <select class="selectCalendario" name="mese" onchange="submit()"><?php
                            foreach ($mesi as $key => $value) {
                                if(date("n",$data)==$key)
                                    echo '<option value="'.$key.'" selected>'.$value.'</option>';
                                else
                                    echo '<option value="'.$key.'">'.$value.'</option>';
                            }
                        ?></select>
                    </td>
                    <td>
                        <?php $selected = Utilita::getValoreFiltro($_GET['prio']); ?>
                        <select name="filtroPrio" onchange="redirect('<?php echo Utilita::getHomeUrlCompleto(); ?>&prio='+this.value)">
                            <option value="0">- Priorit&agrave;</option>
                        <?php if($selected==1) $txt = 'selected="selected"'; ?>
                            <option value="1" <?php echo $txt; ?>>Priotit&agrave 1</option>
                        <?php $txt = ""; if($selected==2) $txt = 'selected="selected"'; ?>
                            <option value="2" <?php echo $txt; ?>>Priotit&agrave 2</option>
                        <?php $txt = ""; if($selected==3) $txt = 'selected="selected"'; ?>
                            <option value="3" <?php echo $txt; ?>>Priotit&agrave 3</option>
                        </select>
                    </td>
                    <td>
                        <select name="filtroUtente" onchange="redirect('<?php echo Utilita::getHomeUrlCompleto(); ?>&utn='+this.value)">
                            <option value="0">- Utente</option>
                                <?php
                                    $selected = Utilita::getValoreFiltro($_GET['utn']);
                                    $rs = Database::getInstance()->eseguiQuery("SELECT d.username as d, d.id_dipendente as r FROM dipendenti d");
                                    while(!$rs->EOF){
                                        if($rs->fields['r']==$selected)
                                            echo '<option selected="selected" value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                        else
                                            echo '<option value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                        $rs->MoveNext();
                                    }
                                ?>
                        </select>
                    </td>
                    <td>
                        <select name="filtroFiliale" onchange="redirect('<?php echo Utilita::getHomeUrlCompleto(); ?>&filiale='+this.value)">
                            <option value="0">- Filiale</option>
                                <?php
                                    $selected = Utilita::getValoreFiltro($_GET['filiale']);
                                    $rs = Database::getInstance()->eseguiQuery("SELECT f.nome as d, f.id_filiale as r FROM filiali f");
                                    while(!$rs->EOF){
                                        if($rs->fields['r']==$selected)
                                            echo '<option selected="selected" value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                        else
                                            echo '<option value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                        $rs->MoveNext();
                                    }
                                ?>
                        </select>
                    </td>
                    <td>
                        <select name="filtroTipo" onchange="redirect('<?php echo Utilita::getHomeUrlCompleto(); ?>&tipo='+this.value)">
                            <option value="0">- Causale</option>
                                <?php
                                    $selected = Utilita::getValoreFiltro($_GET['tipo']);
                                    $rs = Database::getInstance()->eseguiQuery("SELECT c.nome as d, c.id_motivo as r FROM causali c");
                                    while(!$rs->EOF){
                                        if($rs->fields['r']==$selected)
                                            echo '<option selected="selected" value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                        else
                                            echo '<option value="'.$rs->fields['r'].'">'.$rs->fields['d'].'</option>';
                                        $rs->MoveNext();
                                    }
                                ?>
                        </select>
                    </td>
                    <td>
                        <input class="bottCalendario" type="button" value="reset" onclick="location.href = '?pagina=home'" />
                    </td>
                </tr>
            </table>
        </form><?php
    }


     /**
     * Stampa il calendario
     * @param m indica lo spostamento di mese (+1/-1) di default è 0
     */
    static function stampaCalendario($m = 0){
        $data = Calendario::getCalData($m);
        $mesi = array(1=>'Gennaio', 'Febbraio', 'Marzo', 'Aprile','Maggio', 'Giugno', 'Luglio', 'Agosto','Settembre', 'Ottobre', 'Novembre','Dicembre');
        $giorni = array(1=>'Luned&igrave','Marted&igrave','Mercoled&igrave','Gioved&igrave','Venerd&igrave','Sabato','Domenica');
        ?>
        <div id="calendario">
                <table>
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
                                            echo "Sett-".date("W",mktime(0,0,0,date("n",$data),$j+7,date("Y",$date)));
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


                                        echo '<a class="linkGiorno" href="'.Utilita::getHomeUrlFiltri().'&data='.$dataGiorno.'">'.date("d",$dataGiorno).'</a>';
                                        Calendario::stampaEventiGiorno($dataGiorno);
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
        </div>
    <?php
    }

     /**
     * Stampa all'interno dell cella di un giorno i suoi eventi
     */
    static function stampaEventiGiorno($dataGiorno){
        $da = mktime(23, 59, 59, date("n",$dataGiorno), date("j",$dataGiorno), date("Y",$dataGiorno));
        $a  = mktime(0, 0, 0, date("n",$dataGiorno), date("j",$dataGiorno), date("Y",$dataGiorno));

        $prio    = Utilita::getValoreFiltro($_GET['prio']);
        $utente  = Utilita::getValoreFiltro($_GET['utn']);
        $filiale = Utilita::getValoreFiltro($_GET['filiale']);
        $tipo    = Utilita::getValoreFiltro($_GET['tipo']);
        
        $sql = "SELECT c.nome,e.priorita FROM eventi e,causali c,dipendenti d WHERE DATA_DA <= ".$da." and DATA_A >= ".$a." and c.id_motivo = e.fk_causale and e.fk_dipendente = d.id_dipendente and (e.fk_causale = ".$tipo." or ".$tipo." = 0 ) and (e.priorita = ".$prio." or ".$prio." = 0 ) and (e.fk_dipendente = ".$utente." or ".$utente." = 0 ) and (d.fk_filiale = ".$filiale." or ".$filiale." = 0 ) ORDER BY e.priorita DESC,e.data_da,c.nome LIMIT 3";
        $rs = Database::getInstance()->eseguiQuery($sql);
        while(!$rs->EOF) {
            echo "<p class='prio".$rs->fields['priorita']."'>".$rs->fields['nome']."</p>";
            $rs->MoveNext();
        }
        $rs = Database::getInstance()->eseguiQuery("SELECT count(*) c FROM eventi e,dipendenti d WHERE e.DATA_DA <= ".$da." and e.DATA_A >= ".$a." and e.fk_dipendente = d.id_dipendente and (e.fk_causale = ".$tipo." or ".$tipo." = 0 ) and (e.priorita = ".$prio." or ".$prio." = 0 ) and (e.fk_dipendente = ".$utente." or ".$utente." = 0 ) and (d.fk_filiale = ".$filiale." or ".$filiale." = 0 )");
        if($rs->fields["c"]>3){
            echo "altri ".($rs->fields["c"]-3)."...";
        }

    }


    /**
     *
     * Controlla se una data e' valida (dd/mm/yyyy - hh:mm)
     * ritorna i messaggi di errore se $conMessaggi = true altrimenti ritorna true/false (data ok/non ok)
     * @param String $d Contiene la data da controllare
     * @param String $name Contiene il nome della data che viene controllata
     * @param boolean $conMessaggi Indica cosa la funzione deve ritornare
     */
    static function checkData($d,$name,$conMessaggi){
        $return = "";
        $returnB = true;
        if($d == null){
            $returnB = false;
            $return = "- Data ".$name." non inserita<br/>";
            if($conMessaggi) return $return; else return $returnB;
        }
        if(!strpos($d,"-")){
            $return = "- Il formato della data ".$name." non è corretto.<br/>";
            $returnB = false;
            if($conMessaggi) return $return; else return $returnB;
        }

        $data   = explode("/",$d);
        $giorno = $data[0];
        $mese   = $data[1];
        $anno   = substr($data[2],0,4);
        if(!checkdate($mese, $giorno, $anno)){
            $return .= "- La data ".$name." immessa non esiste<br/>";
            $returnB = false;
        }

        $orario = explode(":",$d);
        $ore    = substr($orario[0],-2);
        $min    = $orario[1];
        if($ore>23 || $ore<0){
            $return .= "- L'ora ".$name." indicata non è valida<br/>";
            $returnB = false;
        }
        if($min>60 || $min<0){
            $return .= "- I minuti ".$name." indicati non sono validi<br/>";
            $returnB = false;
        }

        if($conMessaggi) return $return; else return $returnB;
    }

     /**
     *  Prende una data in formato dd/mm/yyyy hh:mm e la ritorna in timestamp
     * @param String $d Contiente la data da convertire
     */
    static function getTimestamp($d){
        $data   = explode("/",$d);
        $giorno = (int)$data[0];
        $mese   = (int)$data[1];
        $anno   = (int)(substr($data[2],0,4));
        $orario = explode(":",$d);
        $ore    = (int)substr($orario[0],-2);
        $min    = (int)$orario[1];

        return mktime($ore, $min, 0, $mese, $giorno, $anno);
    }
}
?>
