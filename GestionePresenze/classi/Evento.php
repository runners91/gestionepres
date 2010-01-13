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
    public $errori;

    function __construct(){

    }

    /**
     * Setta i valori dell'evento a quelli presi dal Database con id = $id_evento
     * @return Evento
     */
    function getValoriDB($id_evento){
        $sql = "SELECT * FROM eventi WHERE id_evento = ".$id_evento;
        $rs = Database::getInstance()->eseguiQuery($sql);

        $this->id_evento      = $id_evento;
        $this->data_da        = $rs->fields['data_da'];
        $this->data_a         = $rs->fields['data_a'];
        $this->priorita       = $rs->fields['priorita'];
        $this->commento       = $rs->fields['commento'];
        $this->stato          = $rs->fields['stato'];
        $this->commento_segn  = $rs->fields['commento_segnalazione'];
        $this->fk_dipendente  = $rs->fields['fk_dipendente'];
        $this->fk_causale     = $rs->fields['fk_causale'];
    }

    /**
     * Setta i valori dell'evento a quelli presi in $_POST
     * @return Evento
     */
    function getValoriPost(){
        $this->setID($_GET['id_evento']);
        $this->setDataDa($_POST['dataDa']);
        $this->setDataA($_POST['dataA']);
        $this->setPriorita($_POST['etichetta']);
        $this->setCommento($_POST['commento']);
        $this->setStato(2);
        $this->setCommentoSegn("");
        $this->setDipendente($_POST['utente']);
        $this->setCausale($_POST['tipo']);
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
    }

    /**
    * Esegue l'inserimento nel Database e ne ritorna la riuscita (true/false)
    * @return boolean
    */
    function inserisciEvento(){
        if(sizeof($this->errori)>0) return false;
        $sql = "select count(*) as c from eventi where fk_dipendente=".$this->fk_dipendente." and fk_causale=".$this->fk_causale." and data_da=".$this->data_da;
        $rs = Database::getInstance()->eseguiQuery($sql);
        if($rs->fields['c']==0){
            $sql =  "insert into eventi(data_da,data_a,priorita,commento,stato,commento_segnalazione,fk_dipendente,fk_causale) ";
            $sql .= "values (".$this->data_da.",".$this->data_a.",".$this->priorita.",'".$this->commento."',".$this->stato.",'".$this->commento_segn."',".$this->fk_dipendente.",".$this->fk_causale.");";
            return Database::getInstance()->getConnection()->execute($sql);
        }
        else{
            $this->aggiungiErrore("Non si può inserire 2 volte lo stesso evento","processi");
            return false;
        }
    }

    /**
    * Esegue l'aggiornamento nel Database e ne ritorna la riuscita (true/false)
    * @return boolean
    */
    function aggiornaEvento(){
        if(sizeof($this->errori)>0) return false;
        $sql =  "update eventi set data_da = ".$this->data_da.",data_a = ".$this->data_a.",fk_dipendente = ".$this->fk_dipendente.",fk_causale = ".$this->fk_causale.",commento = '".$this->commento."',priorita = ".$this->priorita.",stato = ".$this->stato.", commento_segnalazione = '".$this->commento_segn."' ";
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

     /**
     * Aggiunge un errore all'array nella posizione d, ovvero dove e' avvenuto l'errore
     * @param String $errore Descrizione dell'errore
     * @param String $d Indica dove e' successo l'errore
     */
    function aggiungiErrore($errore, $d){
        if(Utilita::eseguiControlliFormEvento()) $this->errori[$d]  = $errore;
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
        if(!Calendario::checkData($_POST['dataDa'],"",false) && Utilita::eseguiControlliFormEvento())
            $this->aggiungiErrore(Calendario::checkData($_POST['dataDa'],"Da:",true),"data_da");
        else
            $this->data_da = Calendario::getTimestamp($data);
    }
    function getDataA(){
        return $this->data_a;
    }
    function setDataA($data){
        if(!Calendario::checkData($_POST['dataA'],"",false) && Utilita::eseguiControlliFormEvento())
            $this->aggiungiErrore(Calendario::checkData($_POST['dataA'],"A:",true),"data_a");
        else
            $this->data_a = Calendario::getTimestamp($data);
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
        if($d==0)
            $this->aggiungiErrore("Dipendente non inserito","fk_dipendente");
        $this->fk_dipendente = $d;
    }
    function getCausale(){
        return $this->fk_causale;
    }
    function setCausale($c){
        if($c==0)
            $this->aggiungiErrore("Causale non inserita","fk_causale");
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
