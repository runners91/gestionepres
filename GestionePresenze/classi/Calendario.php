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
    static function getCalData($m = 0){
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
     *
     * @param int $m indica lo spostamento di mese (+1/-1)
     * @param boolean $parRicerca indica se stampare anche i parametri di ricerca (tipo,utente,...)
     */
    static function stampaParametriCalendario($m = 0, $parRicerca = true,$utenti = null){
        $data = Calendario::getCalData($m);
        $mesi = array(1=>'Gennaio', 'Febbraio', 'Marzo', 'Aprile','Maggio', 'Giugno', 'Luglio', 'Agosto','Settembre', 'Ottobre', 'Novembre','Dicembre');
        $giorni = array(1=>'Luned&igrave','Marted&igrave','Mercoled&igrave','Gioved&igrave','Venerd&igrave','Sabato','Domenica');
        ?>
        <form name="calendarioNav" action="#" method="POST">
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
                    <?php if($parRicerca){ ?>
                    <td class="cellaSpazio">

                    </td>
                    <td>
                        <?php $selected = Utilita::getValoreFiltro($_GET['prio']); ?>
                        <select name="filtroPrio" onchange="redirect('<?php echo Utilita::getHomeUrlCompleto("prio"); ?>&prio='+this.value)">
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
                        <select name="filtroUtente" onchange="redirect('<?php echo Utilita::getHomeUrlCompleto("utn"); ?>&utn='+this.value)">
                            <option value="0">- Utente</option>
                                <?php
                                    $selected = Utilita::getValoreFiltro($_GET['utn']);
                                    if(Autorizzazione::gruppoAmministrazione($_SESSION['username']))
                                        $rs = Database::getInstance()->eseguiQuery("SELECT d.username as d, d.id_dipendente as r FROM dipendenti d");
                                    else 
                                        $rs = Database::getInstance()->eseguiQuery("SELECT id_dipendente r,username d FROM dipendenti WHERE id_dipendente in (".implode(",", $utenti).");");
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
                        <?php if(Autorizzazione::gruppoAmministrazione($_SESSION['username'])){ ?>
                        <select name="filtroFiliale" onchange="redirect('<?php echo Utilita::getHomeUrlCompleto("filiale"); ?>&filiale='+this.value)">
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
                        <?php } ?>
                    </td>
                    <td>
                        <select name="filtroTipo" onchange="redirect('<?php echo Utilita::getHomeUrlCompleto("tipo"); ?>&tipo='+this.value)">
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
                    <?php } ?>
                </tr>
            </table>
        </form><?php
    }


     /**
     * Stampa il calendario
     * @param m indica lo spostamento di mese (+1/-1) di default è 0
     */
    static function stampaCalendario($m = 0,$utenti = null){
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
                                        Calendario::stampaEventiGiorno($dataGiorno,$utenti);
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
    static function stampaEventiGiorno($dataGiorno,$utenti = null){
        $da = mktime(23, 59, 59, date("n",$dataGiorno), date("j",$dataGiorno), date("Y",$dataGiorno));
        $a  = mktime(0, 0, 0, date("n",$dataGiorno), date("j",$dataGiorno), date("Y",$dataGiorno));
        /*for($i=0;$i<50;$i++){
            Database::getInstance()->eseguiQuery("insert into eventi (data_da,data_a,priorita,commento,fk_dipendente,fk_causale,stato,durata) values (?,?,?,?,?,?,?,?)",array($da,$a,1,'ciao',15,1,2,'G'));
        }*/
        $prio    = Utilita::getValoreFiltro($_GET['prio']);
        $utente  = Utilita::getValoreFiltro($_GET['utn']);
        $filiale = Utilita::getValoreFiltro($_GET['filiale']);
        $tipo    = Utilita::getValoreFiltro($_GET['tipo']);
        if(!Autorizzazione::gruppoAmministrazione($_SESSION["username"])){
            $listaUtenti = implode(",", $utenti);
            foreach ($utenti as $key => $value) {
                if($value == $utente) {
                    $listaUtenti = $utente;
                    break;
                }
            }
            $sql = "SELECT e.id_evento,c.nome,e.priorita,e.data_da
                    FROM eventi e,causali c,dipendenti d
                    WHERE DATA_DA <= ? and DATA_A >= ? AND c.id_motivo = e.fk_causale
                    AND e.fk_dipendente = d.id_dipendente AND (e.fk_causale = ? or ? = 0 )
                    AND (e.priorita = ? or ? = 0 )
                    AND (e.fk_dipendente in (".$listaUtenti.")) ORDER BY e.priorita DESC,e.data_da,c.nome LIMIT 3";
            $rs = Database::getInstance()->eseguiQuery($sql,array($da,$a,$tipo,$tipo,$prio,$prio));
        
            $sql = "SELECT COUNT(*) as c
                    FROM eventi e,causali c,dipendenti d
                    WHERE DATA_DA <= ? and DATA_A >= ? AND c.id_motivo = e.fk_causale
                    AND e.fk_dipendente = d.id_dipendente AND (e.fk_causale = ? or ? = 0 )
                    AND (e.priorita = ? or ? = 0 )
                    AND (e.fk_dipendente in (".$listaUtenti.")) ORDER BY e.priorita DESC,e.data_da,c.nome";

            $c = Database::getInstance()->eseguiQuery($sql,array($da,$a,$tipo,$tipo,$prio,$prio));
            $count = $c->fields["c"];
        }
        else {
            $sql = "SELECT e.id_evento,c.nome,e.priorita,e.data_da FROM eventi e,causali c,dipendenti d WHERE DATA_DA <= ? and DATA_A >= ? and c.id_motivo = e.fk_causale and e.fk_dipendente = d.id_dipendente and (e.fk_causale = ? or ? = 0 ) and (e.priorita = ? or ? = 0 ) and (e.fk_dipendente = ? or ? = 0 ) and (d.fk_filiale = ? or ? = 0 ) ORDER BY e.priorita DESC,e.data_da,c.nome LIMIT 3";
            $rs = Database::getInstance()->eseguiQuery($sql,array($da,$a,$tipo,$tipo,$prio,$prio,$utente,$utente,$filiale,$filiale));
            $c = Database::getInstance()->eseguiQuery("SELECT count(*) c FROM eventi e,dipendenti d WHERE e.DATA_DA <= ? and e.DATA_A >= ? and e.fk_dipendente = d.id_dipendente and (e.fk_causale = ? or ? = 0 ) and (e.priorita = ? or ? = 0 ) and (e.fk_dipendente = ? or ? = 0 ) and (d.fk_filiale = ? or ? = 0 )",array($da,$a,$tipo,$tipo,$prio,$prio,$utente,$utente,$filiale,$filiale));
            $count = $c->fields["c"];

        }
        while(!$rs->EOF) {
            echo '<br /><a alt="'.$rs->fields['nome'].'" href="'.Utilita::getHomeUrlFiltri().'&data='.$dataGiorno.'&id_evento='.$rs->fields['id_evento'].'" class="prio'.$rs->fields['priorita'].'">'.$rs->fields['nome'].'</a>';
            $rs->MoveNext();
        }
        if($count>3){
            echo " altri ".($count-3)."...";
        }

    }


    /**
     *
     * Controlla se una data e' valida (dd.mm.yyyy)
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

        $data   = explode(".",$d);
        $giorno = $data[0];
        $mese   = $data[1];
        $anno   = $data[2];
        if(!checkdate($mese, $giorno, $anno)){
            $return .= "- La data ".$name." immessa non &egrave; valida<br/>";
            $returnB = false;
        }

        if($conMessaggi) return $return; else return $returnB;
    }

     /**
     *  Prende una data in formato dd.mm.yyyy e la ritorna in timestamp
     * @param String $d Contiente la data da convertire
     * @return int
     */
    static function getTimestamp($d){
        $data   = explode(".",$d);
        $giorno = (int)$data[0];
        $mese   = (int)$data[1];
        $anno   = (int)$data[2];
        
        return mktime(0, 0, 0, $mese, $giorno, $anno);
    }
}
?>
