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
                    echo '<th class="reportCella"> '.$value.' </th>';
                }
                $rs->MoveNext();
            echo '</tr>';
        }
        echo '</table>';
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
