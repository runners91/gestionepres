<?php
/**
 * Description of Evento
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */
class Evento {
    private $id_evento;
    private $data_da;
    private $data_a;
    private $priorita;
    private $commento;
    private $stato;
    private $commento_segn;
    private $fk_dipendente;
    private $fk_causale;



    function __construct($data_da,$data_a,$priorita,$commento,$stato,$commento_segn,$fk_dipendente,$fk_causale,$id_evento = null){
        $this->id_evento     = $id_evento;
        $this->data_da       = $data_da;
        $this->data_a        = $data_a;
        $this->priorita      = $priorita;
        $this->commento      = $commento;
        $this->stato         = $stato;
        $this->commento_segn = $commento_segn;
        $this->fk_dipendente = $fk_dipendente;
        $this->fk_causale    = $fk_causale;
    }

    /**
     * Crea e ritorna l'istanza dell'evento preso dal Database con id = $id_evento
     * @return Evento
     */
    static function getEvento($id_evento){
        $sql = "SELECT * FROM eventi WHERE id_evento = ".$id_evento;
        $rs = Database::getInstance()->eseguiQuery($sql);
        return new Evento($rs->fields['data_da'],$rs->fields['data_a'],$rs->fields['priorita'],$rs->fields['commento'],$rs->fields['stato'],$rs->fields['commento_segnalazione'],$rs->fields['fk_dipendente'],$rs->fields['fk_causale'],$_GET['id_evento']);
    }

    /**
     * Aggiorna lo stato dell'evento
     * @param int $stato stato che si vuole mettere
     * @param String $commento commento della segnalazione nel caso lo stato è 3
     */
    function aggiornaStato($stato,$commento = ""){
        return Database::getInstance()->eseguiQuery("UPDATE eventi set commento_segnalazione = '".$commento."', stato = ".$stato." WHERE id_evento = ".$this->id_evento.";");
    }

     /**
     * Elimina l'evento dal Database
     */
    function eliminaEvento(){
        if($this->id_evento)
            return Database::getInstance()->eseguiQuery("DELETE FROM eventi where id_evento = ".$this->id_evento.";");
        return false;
    }

    /**
    * Esegue l'inserimento nel Database e ne ritorna la riuscita (true/false)
    * @return boolean
    */
    function inserisciEvento(){
        $sql =  "insert into eventi(data_da,data_a,priorita,commento,stato,commento_segnalazione,fk_dipendente,fk_causale) ";
        $sql .= "values (".Calendario::getTimestamp($this->data_da).",".Calendario::getTimestamp($this->data_a).",".$this->priorita.",'".$this->commento."',".$this->stato.",'".$this->commento_segn."',".$this->fk_dipendente.",".$this->fk_causale.");";
        return Database::getInstance()->getConnection()->execute($sql);
    }

    /**
    * Esegue l'aggiornamento nel Database e ne ritorna la riuscita (true/false)
    * @return boolean
    */
    function aggiornaEvento(){
        $sql =  "update eventi set data_da = ".Calendario::getTimestamp($this->data_da).",data_a = ".Calendario::getTimestamp($this->data_a).",fk_dipendente = ".$this->fk_dipendente.",fk_causale = ".$this->fk_causale.",commento = '".$this->commento."',priorita = ".$this->priorita.",stato = ".$this->stato.", commento_segnalazione = '".$this->commento_segn."' ";
        $sql .= "where id_evento = ".$this->id_evento.";";

        return Database::getInstance()->getConnection()->execute($sql);
   }


    /**
     * Ritorna il titolo del form in base ai dati contenuti nell'oggetto Evento
     * @return String
     */
    function getTitoloForm(){
        if($this->id_evento){
            return "Evento Nr. ".$this->id_evento;
        }
        else{
            return "Nuovo evento";
        }
    }


    function getID(){
        return $this->id_evento;
    }
    function setID($id){
        $this->id_evento = $id;
    }
    function getDataDa(){
        return $this->data_da;
    }
    function setDataDa($data){
        $this->data_da = $data;
    }
    function getDataA(){
        return $this->data_a;
    }
    function setDataA($data){
        $this->data_a = $data;
    }
    function getCommento(){
        return $this->commento;
    }
    function setCommento($c){
        $this->commento = $c;
    }
    function getPriorita(){
        return $this->priorita;
    }
    function setPriorita($p){
        $this->priorita = $p;
    }
    function getDipendente(){
        return $this->fk_dipendente;
    }
    function setDipendente($d){
        $this->fk_dipendente = $d;
    }
    function getCausale(){
        return $this->fk_causale;
    }
    function setCausale($c){
        $this->fk_causale = $c;
    }
    function getStato(){
        return $this->stato;
    }
    function setStato($s){
        $this->stato = $s;
    }
    function getCommentoSegn(){
        return $this->commento_segn;
    }
    function setCommentoSegn($c){
        $this->commento_segn = $c;
    }
}
?>
