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
    static function stampaTabella($rs,$idSelezionato=null,$nascondiID=true){
        $max = $rs->FieldCount();
        echo '<table class="reportTabella">';

        echo '<tr class="reportRigaTitoli">';
        foreach ($rs->fields as $key => $value){
            $style="";
            if($key == "id" && $nascondiID)
                $style="style='display:none;'";
            echo '<th class="reportCella" '.$style.'> '.$key.' </th>';
        }
        echo '</tr>';

        while(!$rs->EOF){
            if($rs->fields["id"]) $id = $rs->fields["id"]; else $id = -1;
            $style = '';
            if($id == $idSelezionato)
                $style = 'style="background-color:#CEF6F5;"';
            echo '<tr class="reportRigaDati"'.$style.'>';
                foreach ($rs->fields as $key => $value){
                    $style="";
                    if($key == "id")
                        $style="style='display:none;'";
                    echo '<td class="reportCella" '.$style.'> '.$value.' </td>';
                }
                $rs->MoveNext();
            echo '</tr>';
        }
        echo '</table>';
    }

     /**
     *  Ritorna la stringa dell'url completo attuale (pagina home.php)
     */
    static function getHomeUrlCompleto(){
        $url = "index.php?pagina=home";
        if(isset($_GET['data'])) $url .= "&data=".$_GET['data'];
        if(isset($_GET['prio'])) $url .= "&prio=".$_GET['prio'];
        if(isset($_GET['utn'])) $url .= "&utn=".$_GET['utn'];
        if(isset($_GET['tipo'])) $url .= "&tipo=".$_GET['tipo'];
        return $url;
    }

     /**
     *  Ritorna la stringa dell'url attuale, solo filtri (pagina home.php)
     */
    static function getHomeUrlFiltri(){
        $url = "index.php?pagina=home";
        if(isset($_GET['prio'])) $url .= "&prio=".$_GET['prio'];
        if(isset($_GET['utn'])) $url .= "&utn=".$_GET['utn'];
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
     *  Ritorna il true se i controlli sono da effettuare, false se invece no
     */
    static function eseguiControlliFormEvento(){
        if($_POST && isset($_GET['data']))
            return true;
        else
            return false;
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
     *  Controlla se l'utente ha accesso alla pagina, ritorna true se è si, altrimenti ritorna false
     * @param String $pagina Contiente la pagina da contrlollare
     */
    static function verificaAccesso($pagina){
       if($pagina=="") $pagina = "home";
       $rs = Database::getInstance()->eseguiQuery("SELECT count(*) as accesso FROM dipendenti d,gruppi g, dipendenti_gruppi dg, gruppi_pagine gp, pagine p WHERE d.id_dipendente = dg.fk_dipendente AND dg.fk_gruppo = g.id_gruppo AND g.id_gruppo = gp.fk_gruppo AND gp.fk_pagina = p.id_pagina AND d.username='".$_SESSION['username']."' AND p.url = '".$pagina."';");
       return $rs->fields['accesso'] >= 1;
    }

}

?>
