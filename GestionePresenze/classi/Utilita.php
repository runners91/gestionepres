<?php
/**
 * Description of Utilita
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */

class Utilita {

    function __contruct(){

    }

    /**
     * Stampa un report contenente i dati del ResultSet passato come parametro
     * @param ResultSet $rs e' il ResultSet da cui estrapola i dati per stampare un report
     */
    static function stampaTabella($rs){
        $max = $rs->FieldCount();
        echo '<table class="reportTabella">';

        echo '<tr class="reportRigaTitoli">';
        foreach ($rs->fields as $key => $value){
            echo '<th class="reportCella"> '.$key.' </th>';
        }
        echo '</tr>';

        while(!$rs->EOF){
            echo '<tr class="reportRigaDati">';
                foreach ($rs->fields as $key => $value){
                    echo '<td class="reportCella"> '.$value.' </td>';
                }
                $rs->MoveNext();
            echo '</tr>';
        }
        echo '</table>';
    }

     /**
     * Stampa il calendario
     */
    static function stampaCalendario(){
        if($_POST['anno']=="") $anno = date("o",time()); else $anno = ()$_POST['anno'];
        if($_POST['mese']=="") $mese = date("n",time()); else $mese = $_POST['mese'];
        date_default_timezone_set("Europe/Zurich");
        $date = mktime(0,0,0,$mese,1,$anno);
 
        $mesi = array(1=>'gennaio', 'febbraio', 'marzo', 'aprile','maggio', 'giugno', 'luglio', 'agosto','settembre', 'ottobre', 'novembre','dicembre');
        $giorni = array('domenica','lunedì','marted','mercoledì','giovedì','venerdì','sabato');
     ?>

        <form name="intCalendario" action="#" method="POST">
            <select name="anno"><?php
                for($i=1970;$i<2100;$i++){
                    if(date("o",$date)==$i)
                        echo '<option value="'.$i.'" selected>'.$i.'</option>';
                    else
                        echo '<option value="'.$i.'">'.$i.'</option>';
                }
            ?></select>
            <select name="mese"><?php
                foreach ($mesi as $key => $value) {
                    if(date("n",$date)==$key)
                        echo '<option value="'.$value.'" selected>'.$value.'</option>';
                    else
                        echo '<option value="'.$value.'">'.$value.'</option>';
                }
            ?></select>
            <input type="submit" value="Vai" />
        </form>

        <table>
            <tr>
                <td class="cellaMese">
                    <?php echo ucfirst($mesi[date("n",$date)])." ".date("o",$date); ?>
                </td>
                <td class="cellaGiorno">
                    Luned&igrave;
                </td>
                <td class="cellaGiorno">
                    Marted&igrave;
                </td>
                <td class="cellaGiorno">
                    Mercoled&igrave;
                </td>
                <td class="cellaGiorno">
                    Gioved&igrave;
                </td>
                <td class="cellaGiorno">
                    Venerd&igrave;
                </td>
                <td class="cellaGiorno">
                    Sabato
                </td>
                <td class="cellaGiorno">
                    Domenica
                </td>
            </tr>
            <?php
                $nrGiorno = 1-date("N",$date);
                for(;;){
                    echo '<tr>';
                        for($j=$nrGiorno;$j<=($nrGiorno+7);$j++){
                            if($j==$nrGiorno){
                                echo '<td class="cellaSettimana">';
                                    echo "Settimana ".date("W",mktime(0,0,0,date("n",$date),$j,date("o",$date)));
                                echo '</td>';
                            }
                            else{
                                if(date("j-n-o",time())==date("j-n-o",mktime(0,0,0,date("n",$date),$j,date("o",$date))))
                                    echo '<td class="cellaData cellaDataOggi">';
                                else
                                    echo '<td class="cellaData">';

                                    echo date("d",mktime(0,0,0,date("n",$date),$j,date("o",$date)));
                                echo '</td>';
                            }
                        }
                        $nrGiorno += 7;
                    echo '</tr>';
                    if(date("n",mktime(0,0,0,date("n",$date),$j,date("o",$date)))!=date("n",$date) || j>31){
                        break;
                    }
                }
            ?>
        </table>
        <?php
        
    }

    /**
     *
     * Controlla se una data e' valida
     * @param String $d Contiene la data da controllare
     */
    static function checkData($d){
        
    }

}

?>
