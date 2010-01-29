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
    private $email;
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
    public function setNome($n){
        $nome = trim($n);
        if(strlen($nome)==0)
            $this->aggiungiErrore(" - Nome non pu&ograve avere un valore nullo", "nome");
        $this->nome = $nome;
    }
    public function setCognome($c){
        $cognome = trim($c);
        if(strlen($cognome)==0)
            $this->aggiungiErrore(" - Cognome non pu&ograve avere un valore nullo", "cognome");
        $this->cognome = $cognome;
            
    }
    public function setEmail($e){
        $email = trim($e);
        if(strlen($email)==0)
            $this->aggiungiErrore(" - Email non pu&ograve avere un valore nullo", "email");
        else if(!Utilita::validaEmail($email))
            $this->aggiungiErrore(" - Email non &egrave valida", "email");


        $this->email = $email;
    }
    public function setUsername($u){
        $username = trim($u);
        $param = array($username);
        $sql = "SELECT count(*) as username FROM dipendenti where username = ?";
        if($this->id) {
            $sql .= "AND id_dipendente != ?;";
            $param[1] = $this->id;
        }

        $rs = Database::getInstance()->eseguiQuery($sql,$param);
        if(strlen($username)==0 ){
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
            $ris = Database::getInstance()->eseguiQuery("INSERT INTO dipendenti (nome,cognome,username,password,fk_filiale,email) values (?,?,?,md5('inizio'),?,?)",array($this->nome,$this->cognome,$this->username,$this->filiale,$this->email));
            $rs = Database::getInstance()->eseguiQuery("SELECT id_dipendente as id FROM dipendenti WHERE username = ?",array($this->username));
            //Database::getInstance()->eseguiQuery("INSERT INTO dipendenti_gruppi (fk_dipendente,fk_gruppo) values (?,2);",array($rs->fields['id']));
            return $ris;
        }
        return false;
    }

     /**
     * modifica un dipendente
     */
    public function aggiornaDipendente(){
        if(sizeof($this->errori)==0){
            return Database::getInstance()->eseguiQuery("UPDATE dipendenti set nome = ?,cognome = ?,username = ?,fk_filiale = ?,email = ? where id_dipendente = ?",array($this->nome,$this->cognome,$this->username,$this->filiale,$this->email,$this->id));
        }
        return false;
    }

    /**
     * Trova l'utente in base all'id passato e lo salva nell'oggetto
     * @param int $id Id dell'utente da cercare
     * @return Dipendente l'oggetto con i dati del dipendente cercato
     */
    function trovaUtenteDaId($id){
        $rs = Database::getInstance()->eseguiQuery("SELECT nome,cognome,username,fk_filiale,email from dipendenti where id_dipendente = ?",array($id));
        $this->id = $id;
        $this->nome = $rs->fields["nome"];
        $this->cognome = $rs->fields["cognome"];
        $this->username = $rs->fields["username"];
        $this->filiale = $rs->fields["fk_filiale"];
        $this->email = $rs->fields["email"];
        return $this;
    }

    /**
     * Trova l'utente in base al nome utente passato e lo salva nell'oggetto
     * @param int $username nome utente dell'utente da cercare
     * @return Dipendente l'oggetto con i dati del dipendente cercato
     */
    function trovaUtenteDaUsername($username){
        $rs = Database::getInstance()->eseguiQuery("SELECT id_dipendente,nome,cognome,username,fk_filiale,email from dipendenti where username = ?",array($username));
        $this->id = $rs->fields["id_dipendente"];
        $this->nome = $rs->fields["nome"];
        $this->cognome = $rs->fields["cognome"];
        $this->username = $username;
        $this->filiale = $rs->fields["fk_filiale"];
        $this->email = $rs->fields["email"];
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
