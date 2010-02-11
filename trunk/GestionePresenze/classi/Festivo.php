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
        if(sizeof($this->errori)==0) {
            Database::getInstance()->eseguiQuery("INSERT INTO festivi (nome,data,durata,ricorsivo) values (?,?,?,?)",array($this->nome, $this->data,$this->durata, $this->ricorsivo));
            $this->id = Database::getInstance()->getConnection()->Insert_ID();
            return Database::getInstance()->eseguiQuery("INSERT INTO festivi_effettuati (fk_filiale,fk_festivo) values (?,?)",array($this->filiale, $this->id));
        }
        return false;
    }
}
?>
