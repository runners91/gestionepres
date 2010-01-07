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
        </form>
    </div>
        <?php

    }

     /**
     * Stampa all'interno dell cella di un giorno i suoi eventi
     */
    static function stampaEventiGiorno($dataGiorno){
        $da = mktime(23, 59, 59, date("n",$dataGiorno), date("j",$dataGiorno), date("Y",$dataGiorno));
        $a  = mktime(0, 0, 0, date("n",$dataGiorno), date("j",$dataGiorno), date("Y",$dataGiorno));
        
        $sql = "SELECT c.nome,e.priorita FROM eventi e,causali c WHERE DATA_DA <= ".$da." and DATA_A >= ".$a." and c.id_motivo = e.fk_causale ORDER BY DATA_DA LIMIT 3";
        $rs = Database::getInstance()->eseguiQuery($sql);
        while(!$rs->EOF) {
            echo "<p class='prio".$rs->fields['priorita']."'>".$rs->fields['nome']."</p>";
            $rs->MoveNext();
        }
        $rs = Database::getInstance()->eseguiQuery("SELECT count(*) c FROM eventi WHERE DATA_DA <= ".$da." and DATA_A >= ".$a);
        if($rs->fields["c"]>3){
            echo ($rs->fields["c"]-3)." altri...";
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
            $return = "- Il formato della data ".$name." non è corretto. ('-' tra data e ora non trovato)<br/>";
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
        $giorno = $data[0];
        $mese   = $data[1];
        $anno   = substr($data[2],0,4);
        $orario = explode(":",$d);
        $ore    = substr($orario[0],-2);
        $min    = $orario[1];

        return mktime($ore, $min, 0, $mese, $giorno, $anno);
    }
}
?>
