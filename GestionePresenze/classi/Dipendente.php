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
    private $vacanze;
    private $stato;
    private $commento_stato;
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
    public function setVacanze($v){
        $vacanze = trim($v);
        if(strlen($vacanze)==0)
            $this->aggiungiErrore(" - Vacanze non pu&ograve avere un valore nullo", "vacanze");
        else if(!is_numeric($vacanze))
            $this->aggiungiErrore(" - Vacanze non ha un valore valido", "vacanze");
        $this->vacanze = $vacanze;
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

    public function setStato($stato){
        if($stato == 1 || $stato == 2  || $stato == 3)
            $this->stato = $stato;
    }

    public function setCommentoStato($commento){
        $this->commento_stato = trim($commento);
    }

    /**
     * aggiunge il nuovo dipendente nel DB
     */
    public function aggiungiDipendente(){
        if(sizeof($this->errori)==0){
            $ris = Database::getInstance()->eseguiQuery("INSERT INTO dipendenti (nome,cognome,username,password,fk_filiale,email) values (?,?,?,md5('inizio'),?,?);",array($this->nome,$this->cognome,$this->username,$this->filiale,$this->email));
            $rs = Database::getInstance()->eseguiQuery("SELECT id_dipendente as id FROM dipendenti WHERE username = ?;",array($this->username));
            Database::getInstance()->eseguiQuery("INSERT INTO saldi (fk_dipendente,saldo, saldo_strd, vac_spt, vac_rst, vac_matr) values (?,0,0,?,?,0);",array($rs->fields["id"],$this->vacanze,$this->vacanze));
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
     * aggiorna lo stato del dipendente
     */
    public function aggiornaStato(){
        return Database::getInstance()->eseguiQuery("UPDATE dipendenti set stato_att = ?, commento_stato= ? where id_dipendente = ?",array($this->stato,$this->commento_stato,$this->id));
    }

    /**
     * Trova l'utente in base all'id passato e lo salva nell'oggetto
     * @param int $id Id dell'utente da cercare
     * @return Dipendente l'oggetto con i dati del dipendente cercato
     */
    function trovaUtenteDaId($id){
        $rs = Database::getInstance()->eseguiQuery("SELECT nome,cognome,username,fk_filiale,email,stato_att as stato,commento_stato from dipendenti where id_dipendente = ?",array($id));
        $this->id = $id;
        $this->nome = $rs->fields["nome"];
        $this->cognome = $rs->fields["cognome"];
        $this->username = $rs->fields["username"];
        $this->filiale = $rs->fields["fk_filiale"];
        $this->email = $rs->fields["email"];
        $this->stato = $rs->fields["stato"];
        $this->commento_stato = $rs->fields["commento_stato"];
        return $this;
    }

    /**
     * Trova l'utente in base al nome utente passato e lo salva nell'oggetto
     * @param int $username nome utente dell'utente da cercare
     * @return Dipendente l'oggetto con i dati del dipendente cercato
     */
    function trovaUtenteDaUsername($username){
        $rs = Database::getInstance()->eseguiQuery("SELECT id_dipendente,nome,cognome,username,fk_filiale,email,stato_att as stato,commento_stato from dipendenti where username = ?",array($username));
        $this->id = $rs->fields["id_dipendente"];
        $this->nome = $rs->fields["nome"];
        $this->cognome = $rs->fields["cognome"];
        $this->username = $username;
        $this->filiale = $rs->fields["fk_filiale"];
        $this->email = $rs->fields["email"];
        $this->stato = $rs->fields["stato"];
        $this->commento_stato = $rs->fields["commento_stato"];
        return $this;
    }

    function getStatoOggi($admin){
        $stato = array();
        $data = Calendario::getTimestamp(Date("d.m.Y"));
        $rs = Database::getInstance()->eseguiQuery("SELECT date_format(FROM_UNIXTIME(e.data_da),'%d.%m.%Y') as da, date_format(FROM_UNIXTIME(e.data_a),'%d.%m.%Y') as a, c.nome as nome FROM eventi e, causali c WHERE e.fk_causale = c.id_motivo AND e.data_da <= ? AND e.data_a >= ? AND e.fk_dipendente = ?;",array($data,$data,$this->id));
        if($rs->RowCount()>0){
            $da = $rs->fields["da"];
            $a = $rs->fields["a"];
            $stato['stato'] = 3;
            if($da == $a) $date_commento = " il ".$da;
            else $date_commento = " dal :".$rs->fields["da"]." al :".$rs->fields["a"];

            if($admin) $stato['commento'] = $rs->fields["nome"].$date_commento;
            else $stato['commento'] = "Assente ".$date_commento;
        }
        else {
            $stato['stato'] = $this->stato;
            $stato['commento'] = $this->commento_stato;
        }
        return $stato;
    }

     /**
     * Aggiunge un errore all'array nella posizione d, ovvero dove e' avvenuto l'errore
     * @param String $errore Descrizione dell'errore
     * @param String $d Indica dove e' successo l'errore
     */
    function aggiungiErrore($errore, $d){
        $this->errori[$d]  = $errore;
    }


    /**
     * Va a calcolare il saldo orario dell'utente
     * @return String saldo
     */
    function getSaldo(){
        $data = mktime(0,0,0,1,1,date("Y",time()));
        $saldo = 0;
        for($j=1;;$j++){
            $tot = 0;
            $d = date("N",mktime(0,0,0,date("n",$data),$j,date("Y",$data)));

            if($d!=7 && $d!=6){
                $sql = "select e.fk_causale from eventi e where fk_dipendente = ? and e.data_da <= ? and e.data_a >= ? order by e.priorita desc limit 1";
                $dataGiorno = mktime(0,0,0,date("n",$data),$j,date("Y",$data));
                $rs  = Database::getInstance()->eseguiQuery($sql,array($_SESSION['id_utente'],$dataGiorno,$dataGiorno));
                if(strlen($rs->fields['fk_causale'])>0) $tot = 28800; /* 8 ore */

                // calcola totale ore giornaliere
                $sql = "select data,stato from timbrature where fk_dipendente = ? and data > ? and data < ? order by data";
                $rs = Database::getInstance()->eseguiQuery($sql,array($_SESSION['id_utente'],mktime(0,0,0,date("n",$data),$j,date("Y",$data)),mktime(23,59,59,date("n",$data),$j,date("Y",$data))));

                $i = 0;
                while(!$rs->EOF){
                    if($i==0) $inizio = (int)$rs->fields['data'];
                    if($rs->fields['stato']=="E" && $i%2==0)
                        $e = (int)$rs->fields['data'];
                    else if($rs->fields['stato']=="U" && $i%2==1){
                        $tot = $tot + (int)$rs->fields['data'] - $e;
                        $fine = (int)$rs->fields['data'];
                    }
                    $rs->MoveNext();
                    $i++;
                }

                if(($fine-$inizio)-$tot<3600 && $tot>0 && ($fine-$inizio)>0) /* se non è stata fatta 1 ora di pausa viene tolta automaticamente*/
                    $tot = ($fine-$inizio)-3600;

                $saldo += $tot-28800;
                //echo date("d.m.Y",mktime(0,0,0,date("n",$data),$j,date("Y",$data)))." - ".Utilita::oreMinDaSec($saldo)."<br>";
            }

            if(mktime(0,0,0,date("n",$data),$j+1,date("Y",$data))>time())
                break;
        }
        return Utilita::oreMinDaSec($saldo);
    }
}
?>
