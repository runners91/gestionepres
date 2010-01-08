<?php
/**
 * Gestione Dipendente
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */
class Dipendente {
    private $id;
    private $nome;
    private $cognome;
    private $username;
    private $filiale;
    private $errori;
    
    function  __construct() {

    }

    public function __get($key) {
        return $this->$key;
    }

    public function setId($id){
        $this->id = $id;
    }
    public function setNome($nome){
        if(strlen(trim($nome))==0)
            $this->aggiungiErrore(" - Nome non pu&ograve avere un valore nullo", "nome");
        $this->nome = $nome;
    }
    public function setCognome($cognome){
        if(strlen(trim($cognome))==0)
            $this->aggiungiErrore(" - Cognome non pu&ograve avere un valore nullo", "cognome");
        $this->cognome = $cognome;
            
    }
    public function setUsername($username){
        $query = ";";
        if(isset($this->id))
            $query = " AND id_dipendente != ".$this->id.";";
        $rs = Database::getInstance()->eseguiQuery("SELECT count(*) as username from dipendenti where username = '".$_POST["username"]."'".$query);
        if(strlen(trim($username))==0 ){
            $this->aggiungiErrore(" - Username non pu&ograve; avere un valore nullo", "username");
        }
            
        else if($rs->fields["username"]==1)
            $this->aggiungiErrore(" - Username gi&agrave; esistente", "username");
        $this->username = $username;
    }
    public function setFiliale($filiale){
        $this->filiale = $filiale;
    }

    /**
     * aggiunge il nuovo dipendente nel DB
     */
    public function aggiungiDipendente(){
        if(sizeof($this->errori)==0){
            Database::getInstance()->eseguiQuery("INSERT INTO dipendenti (nome,cognome,username,password,fk_filiale) values ('".$this->nome."','".$this->cognome."','".$this->username."',md5('inizio'),".$this->filiale.");");
            return true;
        }
        return false;
    }

     /**
     * modifica un dipendente
     */
    public function aggiornaDipendente(){
        if(sizeof($this->errori)==0){
            Database::getInstance()->eseguiQuery("UPDATE dipendenti set nome = '".$this->nome."',cognome='".$this->cognome."',username='".$this->username."',fk_filiale=".$this->filiale." where id_dipendente = ".$this->id.";");
            return true;
        }
        return false;
    }

    /**
     * Trova l'utente in base all'id passato e lo salva nell'oggetto
     * @param int $id Id dell'utente da cercare
     * @return Dipendente l'oggetto con i dati del dipendente cercato
     */
    function trovaUtenteDaId($id){
        $rs = Database::getInstance()->eseguiQuery("SELECT nome,cognome,username,fk_filiale from dipendenti where id_dipendente = ".$id.";");
        $this->id = $id;
        $this->nome = $rs->fields["nome"];
        $this->cognome = $rs->fields["cognome"];
        $this->username = $rs->fields["username"];
        $this->filiale = $rs->fields["fk_filiale"];
        return $this;
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
