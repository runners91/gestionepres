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
        <form name="calendario" action="#" method="POST">
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
                                    <input class="bottCalendario" type="submit" value="Oggi" onmousedown="document.getElementById('m').value='r'" />
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
                                    else if(date("n",$dataGiorno)!=date("n",$data))
                                        echo '<td class="cellaData cellaDataGrigia">';
                                    else
                                        echo '<td class="cellaData">';

                                    echo '<a class="linkGiorno" href="?pagina=home&data='.$dataGiorno.'">'.date("d",$dataGiorno).'</a>';
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
        ?>
        <div class="aggiungiTaskContainer">
            <form name="taskCalendario" action="#" method="POST">
                <table>
                    <tr>
                        <td class="cellaDataTask" colspan="2">
                            <?php echo $giorni[date("N",$_GET['data'])].' '.date("d",$_GET['data']).' '.$mesi[date("n",$_GET['data'])].' '.date("Y",$_GET['data']); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Nuovo:
                        </td>
                        <td>
                            <select>
                                <option value="1">Assenza</option>
                                <option value="2">Vacanza</option>
                                <option value="3">Presenza fuori sede</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="button" onclick="location.href = '?pagina=home'" value="Annulla" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    <?php
    }

    /**
     *
     * Controlla se una data e' valida
     * @param String $d Contiene la data da controllare
     */
    static function checkData($d){
        
    }

    /**
     *  Controlla se l'utente ha accesso alla pagina
     * @param String $pagina Contiente la pagina da visualizzare
     */
    static function verificaAccesso($pagina){
       if($pagina=="") $pagina = "home";
       $rs = Database::getInstance()->eseguiQuery("SELECT count(*) as accesso FROM dipendenti d,gruppi g, dipendenti_gruppi dg, gruppi_pagine gp, pagine p WHERE d.id_dipendente = dg.fk_dipendente AND dg.fk_gruppo = g.id_gruppo AND g.id_gruppo = gp.fk_gruppo AND gp.fk_pagina = p.id_pagina AND d.username='".$_SESSION['username']."' AND p.url = '".$pagina."';");
       return $rs->fields['accesso'] == 1;
    }

}

?>
