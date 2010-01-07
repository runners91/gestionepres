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
