<?php
/**
 * Gestione Filiale
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */
class Filiale {
    private $id_filiale;
    private $nome;
    private $indirizzo;
    private $telefono;
    private $fk_paese;
    private $errori;
    
    public function __get($key) {
        return $this->$key;
    }

    function setId($id) {
        $this->id_filiale = $id;
    }

    function setNome($n) {
        $nome = trim($n);
        if(strlen($nome)==0)
            $this->aggiungiErrore(" - Nome non pu&ograve avere un valore nullo", "nome");
        $this->nome = $nome;
    }

    function setIndirizzo($i) {
        $indirizzo = trim($i);
        if(strlen($indirizzo)==0)
            $this->aggiungiErrore(" - Indirizzo non valido ", "indirizzo");
        $this->indirizzo = $indirizzo;
    }

    function setTelefono($t) {
        $telefono = trim($t);
        if(!is_numeric($telefono))
            $this->aggiungiErrore(" - Numero telefono non valido ", "telefono");
        $this->telefono = $telefono;
    }

    function setPaese($p) {
        $this->fk_paese = $p;
    }

    function trovaFilialeDaId($id_filiale){
        $rs = Database::getInstance()->eseguiQuery("SELECT * FROM filiali WHERE id_filiale = ?",array($id_filiale));
        $this->id_filiale = $rs->fields["id_filiale"];
        $this->nome = $rs->fields["nome"];
        $this->indirizzo = $rs->fields["indirizzo"];
        $this->telefono = $rs->fields["telefono"];
        $this->fk_paese = $rs->fields["fk_paese"];
    }

    function aggiungiFiliale() {
        if(sizeof($this->errori)>0) return false;
        return Database::getInstance()->eseguiQuery("INSERT INTO filiali (nome,indirizzo,telefono,fk_paese) values (?,?,?,?)",array($this->nome,$this->indirizzo,$this->telefono,$this->fk_paese));
    }
    function aggiornaFiliale() {
        if(sizeof($this->errori)>0) return false;
        return Database::getInstance()->eseguiQuery("UPDATE filiali SET nome = ?, indirizzo = ?, telefono = ?, fk_paese = ? WHERE id_filiale = ?",array($this->nome,$this->indirizzo,$this->telefono,$this->fk_paese,$this->id_filiale));
    }
    function eliminaFiliale() {
        return Database::getInstance()->eseguiQuery("DELETE FROM filiali WHERE id_filiale = ?",array($this->id_filiale));
    }

    static function getListaFiliali(){
        $filiali = array();
        $rs = Database::getInstance()->eseguiQuery("SELECT id_filiale,nome FROM filiali");
        while(!$rs->EOF) {
            $filiali[$rs->fields["id_filiale"]] = $rs->fields["nome"];
            $rs->MoveNext();
        }
        return $filiali;
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
