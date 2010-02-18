<?php
/**
 * Description of Timbratura
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */

class Timbratura {
    private $id;
    private $data;
    private $data_prec;
    private $stato;
    private $fk_dipendente;
    public  $errori;

    /**
     *
     * @param String $data data in formato dd.mm.yyyy hh:mm
     */
    function  __construct($data){
        $this->fk_dipendente = $_SESSION['id_utente'];
        $this->setData($data);
    }

    function setData($d){
        if(!Calendario::checkDataOra($d, "della timbratura", false))
            $this->aggiungiErrore(Calendario::checkDataOra($d, "della timbratura", true), "data");
        $this->data = Calendario::getTimestampOre($d);
    }

    /**
     * Inserisce la timbratura nel DB, dopo averne controllato la conformità
     */
    function inserisciTimbratura(){
        if(sizeof($this->errori)>0){
            // cerco l'ultima timbratura effettuata
            $sql = "select max(data),stato from timbrature where fk_dipendente = ?";
            $rs = Database::getInstance()->eseguiQuery($sql,array($this->fk_dipendente));
            $this->data_prec = $rs->fields['data'];
            if($rs->fields['stato']=="E") $this->stato = "U"; else $this->stato = "E";

            // se la data è conforme inserisco la timbratura
            //if($this->data-$this->data_prec >= 350){
                $sql = "insert into timbrature(data,stato,fk_dipendente) values(?,?,?)";
                $rs = Database::getInstance()->eseguiQuery($sql,array($this->data,$this->stato,$this->fk_dipendente));
            //}
        }
    }

    /**
     * Aggiunge un errore all'array nella posizione d, ovvero dove e' avvenuto l'errore
     * @param String $errore Descrizione dell'errore
     * @param String $d Indica dove e' successo l'errore
     */
    function aggiungiErrore($errore, $d){
        $this->errori[$d]  = $errore;
    }


}
?>
