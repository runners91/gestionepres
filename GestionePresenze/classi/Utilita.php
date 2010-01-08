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
     *  Ritorna la stringa dell'url completo attuale
     */
    static function getUrlCompleto(){
        $url = "index.php?pagina=home";
        if(isset($_GET['data'])) $url .= "&data=".$_GET['data'];
        if(isset($_GET['event'])) $url .= "&event=".$_GET['event'];
        if(isset($_GET['prio'])) $url .= "&prio=".$_GET['prio'];
        if(isset($_GET['utn'])) $url .= "&utn=".$_GET['utn'];
        if(isset($_GET['tipo'])) $url .= "&tipo=".$_GET['tipo'];
        return $url;
    }

    function getValoreFiltro($val){
        if(!$val)
            return 0;
        else
            return $val;
    }

    /**
     *  Controlla se l'utente ha accesso alla pagina, ritorna true se è si, altrimenti ritorna false
     * @param String $pagina Contiente la pagina da visualizzare
     */
    static function verificaAccesso($pagina){
       if($pagina=="") $pagina = "home";
       $rs = Database::getInstance()->eseguiQuery("SELECT count(*) as accesso FROM dipendenti d,gruppi g, dipendenti_gruppi dg, gruppi_pagine gp, pagine p WHERE d.id_dipendente = dg.fk_dipendente AND dg.fk_gruppo = g.id_gruppo AND g.id_gruppo = gp.fk_gruppo AND gp.fk_pagina = p.id_pagina AND d.username='".$_SESSION['username']."' AND p.url = '".$pagina."';");
       return $rs->fields['accesso'] >= 1;
    }

}

?>
