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
    private $durata;
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
        $this->durata         = $rs->fields['durata'];
    }

    /**
     * Setta i valori dell'evento a quelli presi in $_POST
     * @return Evento
     */
    function getValoriPost(){
        if(isset($_GET['id_evento']))
            $this->setID($_GET['id_evento']);
        else if(isset($_POST['id_evento']))
            $this->setID($_POST['id_evento']);
        $this->setDataDa($_POST['dataDa']);
        $this->setDataA($_POST['dataA']);
        $this->setPriorita($_POST['priorita']);
        $this->setCommento($_POST['commento']);
        $this->setStato(2);
        $this->setCommentoSegn($_POST['commento_segn']);
        $this->setDipendente($_POST['utente']);
        $this->setCausale($_POST['tipo']);
        $this->setDurata($_POST['durata']);
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
        if($this->durata != "G")
            $stringa = "(durata = 'G' OR durata = '".$this->durata."') AND";
        $sql =  "SELECT count(*) as totEventi FROM eventi WHERE ".$stringa." fk_dipendente=".$this->fk_dipendente.
                " AND (data_da<=".$this->data_da." AND data_a>=".$this->data_da.
                " OR data_da<=".$this->data_a." AND data_a>=".$this->data_a.
                " OR data_da>=".$this->data_da." AND data_a<=".$this->data_a.");";
        $rs = Database::getInstance()->eseguiQuery($sql);
        if($rs->fields['totEventi']==0){
            $sql =  "insert into eventi(data_da,data_a,priorita,commento,stato,commento_segnalazione,fk_dipendente,fk_causale,durata) ";
            $sql .= "values (".$this->data_da.",".$this->data_a.",".$this->priorita.",'".$this->commento."',".$this->stato.",'".$this->commento_segn."',".$this->fk_dipendente.",".$this->fk_causale.",'".$this->durata."');";
            return Database::getInstance()->getConnection()->execute($sql);
        }
        else{
            $d = new Dipendente();
            $d->trovaUtenteDaId($this->fk_dipendente);
            $this->aggiungiErrore("l'utente \"".$d->username."\" ha gi&agrave; un evento in questa data","processi");
            return false;
        }
    }

    /**
    * Esegue l'aggiornamento nel Database e ne ritorna la riuscita (true/false)
    * @return boolean
    */
    function aggiornaEvento(){
        if(sizeof($this->errori)>0) return false;
        if($this->durata != "G")
            $stringa = "(durata = 'G' OR durata = '".$this->durata."') AND";
        $sql =  "SELECT count(*) as totEventi FROM eventi WHERE ".$stringa." id_evento != ".$this->id_evento." AND fk_dipendente=".$this->fk_dipendente.
                " AND (data_da<=".$this->data_da." AND data_a>=".$this->data_da.
                " OR data_da<=".$this->data_a." AND data_a>=".$this->data_a.
                " OR data_da>=".$this->data_da." AND data_a<=".$this->data_a.");";
        $rs = Database::getInstance()->eseguiQuery($sql);
        if($rs->fields['totEventi']==0){
            $sql =  "update eventi set data_da = ".$this->data_da.",data_a = ".$this->data_a.",fk_dipendente = ".$this->fk_dipendente.",fk_causale = ".$this->fk_causale.",commento = '".$this->commento."',priorita = ".$this->priorita.",stato = ".$this->stato.", commento_segnalazione = '".$this->commento_segn."',durata = '".$this->durata."' ";
            $sql .= "where id_evento = ".$this->id_evento.";";
            return Database::getInstance()->getConnection()->execute($sql);
        }
        else{
            $d = new Dipendente();
            $d->trovaUtenteDaId($this->fk_dipendente);
            $this->aggiungiErrore("l'utente \"".$d->username."\" ha gi&agrave; un evento in questa data","processi");
            return false;
        }
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
        $this->errori[$d]  = $errore;
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
        if(!Calendario::checkData($data,"",false))
            $this->aggiungiErrore(Calendario::checkData($data,"Da:",true),"data_da");
        $this->data_da = Calendario::getTimestamp($data);
    }
    function getDataA(){
        return $this->data_a;
    }
    function setDataA($data){
        if(!Calendario::checkData($data,"",false))
            $this->aggiungiErrore(Calendario::checkData($data,"A:",true),"data_a");
        else if($this->data_da>Calendario::getTimestamp($data))
            $this->aggiungiErrore("- Data Da: maggiore di A:","data_a");
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
            $this->aggiungiErrore("- Utente non inserito","fk_dipendente");
        else
            $this->fk_dipendente = $d;
    }
    function getCausale(){
        return $this->fk_causale;
    }
    function setCausale($c){
        if($c==0)
            $this->aggiungiErrore("- Causale non inserita","fk_causale");
        else
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
    function getDurata(){
        return $this->durata;
    }
    function setDurata($d){
        $this->durata = $d;
    }
}
?>
