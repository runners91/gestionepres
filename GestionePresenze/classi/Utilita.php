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
     * @param int $idSelezionato id della riga da selezionare
     * @param int $visualizza numero di record da visualizzare
     * @param boolean $nascondiID true se si vuole nascondere la colonna ID, false se la si vuole vedere
     */
    static function stampaTabella($rs,$idSelezionato=1,$visualizza=8,$nascondiID=true){

        // Preparo le variabili che mi servono per la paginazione del report
        $riga     = 0;
        $numField = $rs->FieldCount();
        $cont     = $rs->RecordCount();
        $minRiga = Utilita::getNewMinRiga($_POST['codPag'],$_POST['minRiga'],$cont,$visualizza);

        if(($minRiga+$visualizza)<$cont) $maxRiga = $minRiga+$visualizza; else $maxRiga = $cont;

        // Stampo la tabella contenente i dati
        echo '<table class="reportTabella">';

        echo '<tr class="reportRigaTitoli">';
        foreach($rs->fields as $key => $value){
            $style = "";
            if($key == "id" && $nascondiID)
                $style="style='display:none;'";
            echo '<th class="reportCella" '.$style.'> '.$key.' </th>';
        }
        echo '</tr>';

        while(!$rs->EOF){
            if($riga>=$minRiga && $riga<$maxRiga){
                $id = $rs->fields["id"];
                $style = "";
                if($id == $idSelezionato)
                    $style = 'style="background-color:#CEF6F5;"';
                echo '<tr class="reportRigaDati"'.$style.'>';
                    foreach($rs->fields as $key => $value){
                        $style="";
                        if($key == "id")
                            $style="style='display:none;'";
                        echo '<td class="reportCella" '.$style.'> '.$value.' </td>';
                    }
                echo '</tr>';
            }
            $riga++;
            $rs->MoveNext();
        } ?>
            <form name="paginazione" action="#" method="POST">
                <tr>
                    <td colspan="<?php echo $numField; ?>" align="center" class="paginazione">
                        <table>
                            <tr>
                                <td>
                                    <input type="hidden" name="minRiga" value="<?php echo $minRiga; ?>" />
                                    <input type="hidden" name="codPag" id="codPag" />
                                    <?php if($minRiga-$visualizza*2>=0){ ?><input class="bottPaginazione" type="submit" value="<<" onclick="document.getElementById('codPag').value='PP'" /><?php } ?>
                                    <?php if($minRiga-$visualizza>=0){ ?><input class="bottPaginazione" type="submit" value="<" onclick="document.getElementById('codPag').value='P'" /><?php } ?>
                                </td>
                                <td>
                                    <span class="testoPaginazione"><?php echo ($minRiga+1).' - '.$maxRiga.' di '.$cont; ?></span>
                                </td>
                                <td>
                                    <?php if($minRiga+$visualizza<$cont){ ?><input class="bottPaginazione" type="submit" value=">" onclick="document.getElementById('codPag').value='S'" /><?php } ?>
                                    <?php if($minRiga+$visualizza*2<$cont){ ?><input class="bottPaginazione" type="submit" value=">>" onclick="document.getElementById('codPag').value='SS'" /><?php } ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </form>
        <?php
           echo '</table>';
    }

    /**
     * Ritorna il valore della nuova prima riga del report in base ai parametri
     * @param String $action indica l azione compiuta ("PP" prima pagina, "P" precedente, "S" seguente, "SS" ultima pagina)
     * @param int $vecchioVal indica il vecchio valore della prima riga
     * @param int $cont indica il totale di record che il report mostra
     * @param int $visualizza indica quanti record per pagina si visualizzano
     * @return int
     */
    static function getNewMinRiga($action,$vecchioVal,$cont=8,$visualizza=8){
        if(!$action && isset($_GET['minrg'])){
            $minRiga = $_GET['minrg'];
        }
        else if($action=="P"){ /////////// pagina precedente /////////
            $minRiga = (int)$vecchioVal-$visualizza;
        }
        else if($action=="S"){ /////////// pagina successiva /////////
            $minRiga = (int)$vecchioVal+$visualizza;
        }
        else if($action=="SS"){ /////////// ultima pagina /////////
            if($cont%$visualizza!=0) $minRiga = $cont-$cont%$visualizza; else $minRiga = $cont-$visualizza;
        }
        else{ /////////// prima pagina /////////
            $minRiga = 0;
        }
        return $minRiga;
    }

     /**
     *  Ritorna la stringa dell'url completo attuale (pagina home.php)
     *  @param $selezionato indica quale filtro non aggiungere nell'url in caso che è l'attuale selezionato
     *  @return String
     */
    static function getHomeUrlCompleto($selezionato = null){
        $url = "index.php?pagina=home";
        if(isset($_GET['data']) && $selezionato!="data") $url .= "&data=".$_GET['data'];
        if(isset($_GET['prio']) && $selezionato!="prio") $url .= "&prio=".$_GET['prio'];
        if(isset($_GET['utn']) && $selezionato!="utn") $url .= "&utn=".$_GET['utn'];
        if(isset($_GET['filiale']) && $selezionato!="filiale") $url .= "&filiale=".$_GET['filiale'];
        if(isset($_GET['tipo']) && $selezionato!="tipo") $url .= "&tipo=".$_GET['tipo'];
        return $url;
    }

     /**
     *  Ritorna la stringa dell'url attuale, solo filtri (pagina home.php)
     *  @return String
     */
    static function getHomeUrlFiltri(){
        $url = "index.php?pagina=home";
        if(isset($_GET['prio'])) $url .= "&prio=".$_GET['prio'];
        if(isset($_GET['utn'])) $url .= "&utn=".$_GET['utn'];
        if(isset($_GET['filiale'])) $url .= "&filiale=".$_GET['filiale'];
        if(isset($_GET['tipo'])) $url .= "&tipo=".$_GET['tipo'];
        return $url;
    }

     /**
     *  Ritorna il valore corretto per i filtri nelle query sql
     */
    static function getValoreFiltro($val){
        if(!$val)
            return 0;
        else
            return $val;
    }


    /**
     * Ritorna il true se i controlli sono da effettuare, false se invece no
     * @return boolean
     */
    static function eseguiControlliFormEvento(){
        if(isset($_POST['action']) && $_POST['action']!="elimina")
            return true;
        return false;
    }



    static function reload($url = null,$millisec = 1000){
        if(!$url) $url = Utilita::getHomeUrlCompleto(); ?>
            <script type="text/javascript">
                window.setTimeout("redirect('<?php echo $url ?>')",<?php echo $millisec; ?>);
            </script>
        <?php
    }

    static function getListaUtentiPerGruppo(){
        $rs = Database::getInstance()->eseguiQuery("SELECT d.id_dipendente id FROM dipendenti d,dipendenti_gruppi dg WHERE d.id_dipendente = dg.fk_dipendente AND dg.fk_gruppo in (   SELECT dg2.fk_gruppo FROM dipendenti_gruppi dg2, dipendenti d2 WHERE d2.id_dipendente = dg2.fk_dipendente AND d2.username = ? );",array($_SESSION["username"]));
        while(!$rs->EOF){
            $utenti[] = $rs->fields["id"];;
            $rs->MoveNext();
        }
        return $utenti;
    }

     /**
     *  Ritorna la data in GET (pagina home.php) oppure quella di oggi se non è settato il GET
     */
     static function getDataHome(){
         if(isset($_GET['data'])){
             return $_GET['data'];
         }
         else{
            $anno   = date("Y",time());
            $mese   = date("n",time());
            $giorno = date("d",time());
            return mktime(0,0,0,$mese,$giorno,$anno);
         }
     }

     /**
      * Controlla se è un email valida
      * @param String $email email da controllare
      * @return boolean true se è valida, false se non è valida
      */
     static function validaEmail($email) {
        return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
    }

}

?>
