<?php
/**
 * Gestione Paese
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */
class Paese {
    private $id_paese;
    private $nome;
    private $cap;
    private $fk_nazione;
    private $errori;


    public function __get($key) {
        return $this->$key;
    }
    function setId($id) {
        $this->id_paese = $id;
    }
    function setNome($n) {
        $nome = trim($n);
        if(strlen($nome)==0)
            $this->aggiungiErrore(" - Nome non pu&ograve; avere un valore nullo", "nome");
        $this->nome = $nome;
    }
    function setCap($c) {
        $cap = trim($c);
        if(!is_numeric($cap) || $cap == 0)
            $this->aggiungiErrore(" - Codice d'avviamento postale non &egrave valido", "cap");
        $this->cap = $cap;
    }
    function setNazione($n) {
        $this->fk_nazione = $n;
    }

    function aggiungiPaese(){
        if($this->errori>0) return false;
        return Database::getInstance()->eseguiQuery("INSERT INTO paesi (nome,CAP,fk_nazione) values (?,?,?)",array($this->nome,$this->cap,$this->fk_nazione));
    }
    function aggiornaPaese(){
        if($this->errori>0) return false;
        return Database::getInstance()->eseguiQuery("UPDATE paesi SET nome = ?, CAP = ?, fk_nazione = ? WHERE id_paese = ?",array($this->nome,$this->cap,$this->fk_nazione,$this->id_paese));
    }
    function eliminaPaese(){
        return Database::getInstance()->eseguiQuery("DELETE FROM paesi WHERE id_paese = ?",array($this->id_paese));
    }

    function trovaPaeseDaId($id_paese){
        $rs = Database::getInstance()->eseguiQuery("SELECT * FROM paesi WHERE id_paese = ? ",$id_paese);
        $this->nome = $rs->fields["nome"];
        $this->cap = $rs->fields["CAP"];
        $this->fk_nazione = $rs->fields["fk_nazione"];
    }

    static function getListaPaesi(){
        $paesi = array();
        $rs = Database::getInstance()->eseguiQuery("SELECT id_paese,nome FROM paesi");
        while(!$rs->EOF) {
            $paesi[$rs->fields["id_paese"]] = $rs->fields["nome"];
            $rs->MoveNext();
        }
        return $paesi;
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
