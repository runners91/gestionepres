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
    private $data_succ;
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
        if(!isset($this->errori['data'])>0){
            // cerco se ce l'ultima timbratura effettuata
            $sql = "select id_timbratura,data,stato from timbrature where fk_dipendente = ? and data <= ? order by 2 desc limit 1";
            $rs = Database::getInstance()->eseguiQuery($sql,array($this->fk_dipendente,$this->data));
            if($rs->fields['data']) $this->data_prec = $rs->fields['data']; else $this->data_prec = 1;
            if($rs->fields['stato']=='E') $this->stato = "U"; else $this->stato = "E";

            // cerco se ce la timbratura successiva
            $sql = "select id_timbratura,data,stato from timbrature where fk_dipendente = ? and data >= ? order by 2 limit 1";
            $rs = Database::getInstance()->eseguiQuery($sql,array($this->fk_dipendente,$this->data));
            if($rs->fields['data']) $this->data_succ = $rs->fields['data']; else $this->data_succ = 10000000000000000000000000;
            

            // se la data è conforme inserisco la timbratura
            if(($this->data)-($this->data_prec) >= 350 && $this->data_succ-$this->data >= 350){ // se ci sono 5 min tra una timbratura e l'altra
                $sql = "insert into timbrature(data,stato,fk_dipendente) values(?,?,?)";
                $rs = Database::getInstance()->eseguiQuery($sql,array($this->data,$this->stato,$this->fk_dipendente));
                $this->aggiungiErrore("Timbratura in ".$this->stato." inserita con successo", "timbratura");
            }
            else{
                $this->aggiungiErrore("Devono passare almeno 5 min fra una timbratura e l'altra", "data");
            }
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
