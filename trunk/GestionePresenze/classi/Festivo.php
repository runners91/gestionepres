<?php
/**
 * Gestione Festivo
 *
 * @author Bryan Daepp
 * @author Ethan Winiger
 */
class Festivo {
    private $id;
    private $nome;
    private $data;
    private $filiale;
    private $ricorsivo;
    private $durata;
    private $errori;

    function setId($id) {
        $this->id = $id;
    }
    function getNome() {
        return $this->nome;
    }
    function setNome($n) {
        $nome = trim($n);
        if(strlen($nome)==0 || $nome=="a")
            $this->aggiungiErrore(" - Nome non pu&ograve avere valore nullo", "nome");
        $this->nome = $nome;
    }
    function getData() {
        return $this->data;
    }
    function setData($data) {
        if(!Calendario::checkData($data,"",false))
            $this->aggiungiErrore(" - La data inserita non &egrave valida","data");
        $this->data = Calendario::getTimestamp($data);
    }
    function getFiliale() {
        return $this->filiale;
    }
    function setFiliale($filiale) {
        $this->filiale = $filiale;
    }
    function isRicorsivo() {
        return $this->ricorsivo;
    }
    function setRicorsivo($ricorsivo) {
        $this->ricorsivo = $ricorsivo;
    }
    function getDurata() {
        return $this->durata;
    }
    function setDurata($durata) {
        $this->durata = $durata;
    }

    function trovaFestivoDaId($id) {
        $rs = Database::getInstance()->eseguiQuery("SELECT * FROM festivi WHERE id_festivo = ?",array($id));
        $this->id = $rs->fields["id_festivo"];
        $this->nome = $rs->fields["nome"];
        $this->durata = $rs->fields["durata"];
        $this->ricorsivo = $rs->fields["ricorsivo"];
        $this->data = $rs->fields["data"];
        $rs2 = Database::getInstance()->eseguiQuery("SELECT fk_filiale FROM festivi_effettuati WHERE fk_festivo = ?",array($id));
        $this->filiale = $rs2->fields["fk_filiale"];
    }

     /**
     * Aggiunge un errore all'array nella posizione d, ovvero dove e' avvenuto l'errore
     * @param String $errore Descrizione dell'errore
     * @param String $d Indica dove e' successo l'errore
     */
    function aggiungiErrore($errore, $d){
        $this->errori[$d]  = $errore;
    }

    function getErrori(){
        return $this->errori;
    }

    function insersciFestivo() {
        if(sizeof($this->errori)>0) return false;
            $rs = Database::getInstance()->eseguiQuery("INSERT INTO festivi (nome,data,durata,ricorsivo) values (?,?,?,?)",array($this->nome, $this->data,$this->durata, $this->ricorsivo));
            $this->id = Database::getInstance()->getConnection()->Insert_ID();
            return $rs && Database::getInstance()->eseguiQuery("INSERT INTO festivi_effettuati (fk_filiale,fk_festivo) values (?,?)",array($this->filiale, $this->id));
    }

    function aggiornaFestivo() {
        if(sizeof($this->errori)>0) return false;
            $rs = Database::getInstance()->eseguiQuery("UPDATE festivi SET nome = ?, data = ?, durata = ?, ricorsivo = ? WHERE id_festivo = ?",array($this->nome, $this->data,$this->durata, $this->ricorsivo,$this->id));
            return $rs && Database::getInstance()->eseguiQuery("UPDATE festivi_effettuati SET fk_filiale = ? WHERE fk_festivo =?",array($this->filiale, $this->id));
    }

    function eliminaFestivo() {
        if(sizeof($this->errori)>0) return false;
        $rs = Database::getInstance()->eseguiQuery("DELETE FROM festivi_effettuati WHERE fk_festivo = ?",array($this->id));
        return $rs && Database::getInstance()->eseguiQuery("DELETE FROM festivi WHERE id_festivo = ?",array($this->id));
    }
}
?>
